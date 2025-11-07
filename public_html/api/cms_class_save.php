<?php
header("Content-Type: application/json");
require __DIR__ . '/_auth.php';

$ct = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($ct,'application/json')!==false) {
  $raw=file_get_contents('php://input');
  $_POST = json_decode($raw,true) ?: [];
}

$user_id = auth_user_id();
$id = isset($_POST['class_id']) && $_POST['class_id']!=='' ? (int)$_POST['class_id'] : null;

$name = trim($_POST['name'] ?? '');
$section = trim($_POST['section'] ?? '');
$teacher = trim($_POST['teacher_name'] ?? '');
$exam_title = trim($_POST['exam_title'] ?? '');
$header_text = trim($_POST['header_text'] ?? '');
$logo_data = $_POST['logo_data_url'] ?? '';
$now = date('Y-m-d H:i:s');

if ($id) {
  // check ownership
  $chk=$conn->prepare("SELECT * FROM cms_classes WHERE id=? AND user_id=?");
  $chk->bind_param("is",$id,$user_id); $chk->execute();
  if(!$chk->get_result()->num_rows) json_error("Class not found",404);
  $chk->close();

  $stmt=$conn->prepare("UPDATE cms_classes SET name=?, section=?, teacher_name=?, exam_title=?, header_text=?, updated_at=? WHERE id=?");
  if(!$stmt) json_error("SQL: ".$conn->error,500);
  $stmt->bind_param("ssssssi",$name,$section,$teacher,$exam_title,$header_text,$now,$id);
  if(!$stmt->execute()) json_error("Save failed: ".$stmt->error,500);
  $stmt->close();
} else {
  $color = sprintf("#%06X", mt_rand(0,0xFFFFFF));
  $stmt=$conn->prepare("INSERT INTO cms_classes (user_id,name,section,teacher_name,exam_title,header_text,color,created_at,updated_at)
                        VALUES(?,?,?,?,?,?,?,?,?)");
  if(!$stmt) json_error("SQL: ".$conn->error,500);
  $stmt->bind_param("sssssssss",$user_id,$name,$section,$teacher,$exam_title,$header_text,$color,$now,$now);
  if(!$stmt->execute()) json_error("Insert failed: ".$stmt->error,500);
  $id = $stmt->insert_id; $stmt->close();
}

if ($logo_data && preg_match('~^data:image/(png|jpe?g);base64,~i',$logo_data,$m)) {
  $ext = strtolower($m[1])==='jpeg'?'jpg':strtolower($m[1]);
  $root = realpath(__DIR__.'/../storage/cms') ?: (__DIR__.'/../storage/cms');
  $dir  = $root . "/$user_id/$id";
  if(!is_dir($dir) && !@mkdir($dir,0775,true)) json_error("Cannot create dir",500);
  $file = "$dir/logo.$ext";
  $data = base64_decode(preg_replace('~^data:image/[^;]+;base64,~','',$logo_data));
  if(@file_put_contents($file,$data)===false) json_error("Cannot save logo",500);
  $up=$conn->prepare("UPDATE cms_classes SET logo_path=?, updated_at=? WHERE id=?");
  $up->bind_param("ssi",$file,$now,$id); $up->execute(); $up->close();
}

json_ok(['status'=>'success','class_id'=>$id]);
