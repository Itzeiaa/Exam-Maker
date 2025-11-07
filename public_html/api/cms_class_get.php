<?php
header("Content-Type: application/json");
require __DIR__ . '/_auth.php';

$user_id = auth_user_id();
$id = (int)($_GET['id'] ?? 0);
if(!$id) json_error("Missing id",400);

$stmt=$conn->prepare("SELECT * FROM cms_classes WHERE id=? AND user_id=? LIMIT 1");
$stmt->bind_param("is",$id,$user_id); $stmt->execute();
$cls=$stmt->get_result()->fetch_assoc();
if(!$cls) json_error("Class not found",404);

$cls['logo_url'] = ($cls['logo_path'] && is_file($cls['logo_path'])) ? preg_replace('~^.*?/public_html~','', $cls['logo_path']) : null;

$st=$conn->prepare("SELECT * FROM cms_students WHERE class_id=? ORDER BY student_name ASC, id ASC");
$st->bind_param("i",$id); $st->execute();
$students = $st->get_result()->fetch_all(MYSQLI_ASSOC);

json_ok(['class'=>$cls,'students'=>$students]);
