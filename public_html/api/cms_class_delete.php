<?php
header("Content-Type: application/json");
require __DIR__ . '/_auth.php'; // $conn, json_ok/json_error, auth_user_id()

// accept JSON
$ct = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($ct,'application/json')!==false) {
  $raw = file_get_contents('php://input');
  $_POST = json_decode($raw, true) ?: [];
}

$user_id = auth_user_id();
$class_id = (int)($_POST['class_id'] ?? 0);
if(!$class_id) json_error("Missing class_id", 400);

// own it?
$stmt = $conn->prepare("SELECT logo_path FROM cms_classes WHERE id=? AND user_id=? LIMIT 1");
if(!$stmt) json_error("SQL: ".$conn->error,500);
$stmt->bind_param("is", $class_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();
if(!$row) json_error("Class not found",404);

// delete DB (students cascade via FK)
$del = $conn->prepare("DELETE FROM cms_classes WHERE id=? AND user_id=?");
$del->bind_param("is", $class_id, $user_id);
if(!$del->execute()) json_error("Delete failed: ".$del->error,500);
$del->close();

// delete storage folder if exists
$root = realpath(__DIR__.'/../storage/cms') ?: (__DIR__.'/../storage/cms');
$dir  = $root . "/$user_id/$class_id";
if (is_dir($dir)) {
  $it = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
  $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
  foreach($files as $f){ $f->isDir()?@rmdir($f->getRealPath()):@unlink($f->getRealPath()); }
  @rmdir($dir);
}

json_ok(['status'=>'success','deleted_id'=>$class_id]);
