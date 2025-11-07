<?php
header("Content-Type: application/json");
require __DIR__ . '/_auth.php';

$ct = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($ct,'application/json')!==false) {
  $raw=file_get_contents('php://input');
  $_POST = json_decode($raw,true) ?: [];
}

$user_id = auth_user_id();
$class_id = (int)($_POST['class_id'] ?? 0);
$rows = $_POST['rows'] ?? [];
if(!$class_id) json_error("Missing class_id",400);

// ownership
$chk=$conn->prepare("SELECT id FROM cms_classes WHERE id=? AND user_id=?");
$chk->bind_param("is",$class_id,$user_id); $chk->execute();
if(!$chk->get_result()->num_rows) json_error("Class not found",404);
$chk->close();

$conn->begin_transaction();
$conn->query("DELETE FROM cms_students WHERE class_id=".$class_id);

$now=date('Y-m-d H:i:s');
$ins=$conn->prepare("INSERT INTO cms_students
 (class_id,student_name,section,grade,score,date,page_no,set_no,exam_title,created_at,updated_at)
 VALUES (?,?,?,?,?,?,?,?,?,?,?)");
foreach($rows as $r){
  $student = trim((string)($r['student_name'] ?? ''));
  if($student==='') continue;
  $section = trim((string)($r['section'] ?? ''));
  $grade   = trim((string)($r['grade'] ?? ''));
  $score   = trim((string)($r['score'] ?? ''));
  $date    = trim((string)($r['date'] ?? ''));
  $page    = trim((string)($r['page_no'] ?? ''));
  $set     = trim((string)($r['set_no'] ?? ''));
  $title   = trim((string)($r['exam_title'] ?? ''));
  $ins->bind_param("issssssssss",$class_id,$student,$section,$grade,$score,$date,$page,$set,$title,$now,$now);
  if(!$ins->execute()){ $conn->rollback(); json_error("Insert failed: ".$ins->error,500); }
}
$ins->close();
$conn->commit();

json_ok(['status'=>'success','saved'=>count($rows)]);
