<?php
require 'config.php';
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
	echo json_encode(['error'=>'Not logged in']);
	exit; }

$reg_id = intval($_POST['reg_id'] ?? 0);
$stmt = $mysqli->prepare("DELETE FROM registrations WHERE id=? AND user_id=?");
$stmt->bind_param('ii',$reg_id,$_SESSION['user_id']);
if($stmt->execute()) 
	echo json_encode(['success'=>true]);
else
	echo json_encode(['error'=>'Delete failed']);
?>
