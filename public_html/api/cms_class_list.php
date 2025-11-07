<?php
header("Content-Type: application/json");
require __DIR__ . '/_auth.php'; // $conn, json_ok/json_error, auth_user_id()

$user_id = auth_user_id();
$rows = [];
$sql = "SELECT c.*, 
        (SELECT COUNT(*) FROM cms_students s WHERE s.class_id=c.id) AS students_count
        FROM cms_classes c WHERE c.user_id=? ORDER BY c.updated_at DESC";
$stmt=$conn->prepare($sql); if(!$stmt) json_error("SQL: ".$conn->error,500);
$stmt->bind_param("s",$user_id); $stmt->execute();
$res=$stmt->get_result();
while($r=$res->fetch_assoc()){
  $r['logo_url'] = ($r['logo_path'] && is_file($r['logo_path'])) ? preg_replace('~^.*?/public_html~','', $r['logo_path']) : null;
  $rows[]=$r;
}
json_ok(['items'=>$rows]);
