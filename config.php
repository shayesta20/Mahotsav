<?php
session_start();

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'vignan_mahotsav';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die("DB Connect failed: " . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
function generate_mhid($mysqli, $user_id) {
    $base = 260;
    return 'MH' . ($base + $user_id);
}
function e($s){ return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
 }
