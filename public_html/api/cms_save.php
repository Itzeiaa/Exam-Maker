<?php
// api/cms_save.php
header("Content-Type: application/json");

require __DIR__ . '/_auth.php'; // provides: $conn (mysqli), json_error(), json_ok(), auth_user_id()
// Optionally your db.php is included by _auth.php; if not, require it here:
// require __DIR__ . '/db.php';

/* ========== accept JSON bodies too ========== */
$ct = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';
if (stripos($ct, 'application/json') !== false) {
  $raw = file_get_contents('php://input');
  if ($raw) {
    $json = json_decode($raw, true);
    if (is_array($json)) {
      foreach ($json as $k => $v) {
        if (!array_key_exists($k, $_POST)) $_POST[$k] = $v;
      }
    }
  }
}

$user_id = auth_user_id(); // 401 on failure
if ($user_id === '' || $user_id === null) json_error("Token missing user_id", 401);

/* ========== inputs ========== */
$cms_id       = isset($_POST['cms_id']) ? (int)$_POST['cms_id'] : 0; // if provided => update
$header_text  = trim((string)($_POST['header_text']  ?? ''));
$teacher_name = trim((string)($_POST['teacher_name'] ?? ''));
$exam_title   = trim((string)($_POST['exam_title']   ?? ''));
$default_date = trim((string)($_POST['default_date'] ?? ''));
$default_page = trim((string)($_POST['default_page'] ?? ''));
$set_start    = (int)($_POST['set_start'] ?? 1);

/* rows: array of objects with fields below */
$rows_json    = $_POST['rows'] ?? '[]';
if (is_array($rows_json)) { $rows = $rows_json; } else { $rows = json_decode((string)$rows_json, true) ?: []; }

/* logo: can be multipart file or data URL string */
$logo_data_url = trim((string)($_POST['logo_data_url'] ?? '')); // e.g., "data:image/png;base64,...."
$now = date('Y-m-d H:i:s');

/* ========== basic validation ========== */
if ($header_text === '' || $teacher_name === '' || $exam_title === '') {
  json_error("Missing required fields (header_text, teacher_name, exam_title).");
}

/* ========== ensure user exists ========== */
$stmt = $conn->prepare("SELECT id FROM users WHERE id = ? LIMIT 1");
if (!$stmt) json_error("SQL Error (users prepare): ".$conn->error, 500);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$exists = $stmt->get_result()->num_rows > 0;
$stmt->close();
if (!$exists) json_error("User not found", 404);

/* ========== storage root for CMS ========== */
$CMS_STORAGE_ROOT = realpath(__DIR__ . '/../storage/cms') ?: (__DIR__ . '/../storage/cms');

/* ========== if UPDATE, verify ownership ========== */
if ($cms_id > 0) {
  $chk = $conn->prepare("SELECT id, user_id FROM cms_records WHERE id = ? LIMIT 1");
  if (!$chk) json_error("SQL Error (cms_records check): ".$conn->error, 500);
  $chk->bind_param("i", $cms_id);
  $chk->execute();
  $res = $chk->get_result();
  if ($res->num_rows === 0) { $chk->close(); json_error("CMS record not found", 404); }
  $row = $res->fetch_assoc();
  $chk->close();
  if ((string)$row['user_id'] !== (string)$user_id) {
    json_error("Forbidden: you do not own this CMS record", 403);
  }
}

/* ========== begin transaction ========== */
$conn->begin_transaction();

try {
  $logo_path = null;

  // If logo data URL provided, save to file
  if ($logo_data_url && preg_match('/^data:(image\/(png|jpeg|jpg|webp));base64,/', $logo_data_url, $m)) {
    $ext = ($m[2] === 'jpeg' ? 'jpg' : $m[2]);
    $data = substr($logo_data_url, strpos($logo_data_url, ',') + 1);
    $bin  = base64_decode($data);
    if ($bin === false) throw new Exception("Invalid logo base64.");

    // for new cms_id we need temp id; handle create first, then write, then update path
  }

  if ($cms_id > 0) {
    // UPDATE cms_records
    $sql = "UPDATE cms_records SET header_text=?, teacher_name=?, exam_title=?, default_date=?, default_page=?, set_start=?, updated_at=? WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("SQL Error (update prepare): ".$conn->error);
    $stmt->bind_param("sssssisds", $header_text, $teacher_name, $exam_title, $default_date, $default_page, $set_start, $now, $cms_id, $user_id);
    if (!$stmt->execute()) throw new Exception("SQL Error (update exec): ".$stmt->error);
    $stmt->close();

    // replace rows
    $del = $conn->prepare("DELETE FROM cms_record_rows WHERE cms_id=?");
    if (!$del) throw new Exception("SQL Error (rows delete): ".$conn->error);
    $del->bind_param("i", $cms_id);
    $del->execute();
    $del->close();

  } else {
    // INSERT cms_records
    $sql = "INSERT INTO cms_records (user_id, header_text, teacher_name, exam_title, default_date, default_page, set_start, logo_path, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NULL, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("SQL Error (insert prepare): ".$conn->error);
    $stmt->bind_param("ssssssiss", $user_id, $header_text, $teacher_name, $exam_title, $default_date, $default_page, $set_start, $now, $now);
    if (!$stmt->execute()) throw new Exception("SQL Error (insert exec): ".$stmt->error);
    $cms_id = $stmt->insert_id;
    $stmt->close();
  }

  // Now that we have cms_id, write logo (if present) and update path
  if ($logo_data_url && preg_match('/^data:(image\/(png|jpeg|jpg|webp));base64,/', $logo_data_url, $m2)) {
    $ext  = ($m2[2] === 'jpeg' ? 'jpg' : $m2[2]);
    $data = substr($logo_data_url, strpos($logo_data_url, ',') + 1);
    $bin  = base64_decode($data);
    if ($bin === false) throw new Exception("Invalid logo base64.");

    $dir = rtrim($CMS_STORAGE_ROOT, '/\\') . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR . $cms_id;
    if (!is_dir($dir) && !@mkdir($dir, 0775, true)) throw new Exception("Failed to create storage dir");

    $path = $dir . DIRECTORY_SEPARATOR . "logo." . $ext;
    if (@file_put_contents($path, $bin) === false) throw new Exception("Failed to write logo file");

    // Store relative path for portability
    $rel  = "storage/cms/" . $user_id . "/" . $cms_id . "/logo." . $ext;
    $logo_path = $rel;

    $up = $conn->prepare("UPDATE cms_records SET logo_path=?, updated_at=? WHERE id=?");
    if (!$up) throw new Exception("SQL Error (logo update prepare): ".$conn->error);
    $up->bind_param("ssi", $logo_path, $now, $cms_id);
    if (!$up->execute()) throw new Exception("SQL Error (logo update exec): ".$up->error);
    $up->close();
  }

  // Insert rows
  if (!empty($rows) && is_array($rows)) {
    $ins = $conn->prepare("INSERT INTO cms_record_rows
      (cms_id, position_no, student_name, section, grade_level, score_text, row_date, page_no, set_no, exam_title, created_at, updated_at)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$ins) throw new Exception("SQL Error (rows insert prepare): ".$conn->error);

    $pos = 0;
    foreach ($rows as $r) {
      $pos++;
      $student_name = trim((string)($r['name'] ?? ''));
      if ($student_name === '') continue; // skip empty rows
      $section      = trim((string)($r['section'] ?? ''));
      $grade_level  = trim((string)($r['grade'] ?? ''));
      $score_text   = trim((string)($r['score'] ?? ''));
      $row_date     = trim((string)($r['date'] ?? ''));
      $page_no      = trim((string)($r['page'] ?? ''));
      $set_no       = trim((string)($r['set']  ?? ''));
      $row_title    = trim((string)($r['title'] ?? $exam_title));

      if (!$ins->bind_param(
        "iissssssssss",
        $cms_id, $pos, $student_name, $section, $grade_level, $score_text,
        $row_date, $page_no, $set_no, $row_title, $now, $now
      )) throw new Exception("SQL Error (rows bind): ".$ins->error);

      if (!$ins->execute()) throw new Exception("SQL Error (rows exec): ".$ins->error);
    }
    $ins->close();
  }

  $conn->commit();
  json_ok([
    "status"     => "success",
    "cms_id"     => $cms_id,
    "logo_path"  => $logo_path,
    "message"    => ($cms_id ? "Saved CMS record." : "Created CMS record.")
  ]);

} catch (Exception $e) {
  $conn->rollback();
  json_error($e->getMessage(), 500);
}
