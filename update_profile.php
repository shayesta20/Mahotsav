<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];

$first = trim($_POST['first_name']);
$last  = trim($_POST['last_name']);
$phone = trim($_POST['phone']);
$gender = $_POST['gender'];
$state = $_POST['state'];
$district = $_POST['district'];
$college = $_POST['college'];

$stmt = $mysqli->prepare("
    UPDATE users SET 
        first_name=?, last_name=?, phone=?, gender=?, state=?, district=?, college=?
    WHERE id=?
");
$stmt->bind_param("sssssssi",
    $first, $last, $phone, $gender, $state, $district, $college, $uid
);

$stmt->execute();
$stmt->close();

header("Location: dashboard.php?updated=1");
exit;
?>
