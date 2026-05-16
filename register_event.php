<?php
require 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$uid = $_SESSION['user_id'];
$mhid = $_SESSION['mhid'];

$name = trim($_POST['name'] ?? '');
$category = trim($_POST['category'] ?? '');
$participant_name = trim($_POST['participant_name'] ?? '');

if (!$name || !$category || !$participant_name) {
    echo json_encode(['error' => 'Missing required event data']);
    exit;
}

$allowed_events = [
    'Cultural' => ['Dance', 'Singing'],
    'Sports' => ['Running 100M', 'Running 400M']
];

if (!array_key_exists($category, $allowed_events) || !in_array($name, $allowed_events[$category])) {
    echo json_encode(['error' => 'Invalid event']);
    exit;
}

$stmt_check = $mysqli->prepare("SELECT id FROM registrations WHERE user_id=? AND event_name=?");
$stmt_check->bind_param('is', $uid, $name);
$stmt_check->execute();
$stmt_check->store_result();
if ($stmt_check->num_rows > 0) {
    echo json_encode(['error' => 'Already registered for this event']);
    exit;
}
$stmt_check->close();

$stmt = $mysqli->prepare("INSERT INTO registrations (user_id, event_name, event_category, participant_name, registered_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param('isss', $uid, $name, $category, $participant_name);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => "$mhid - $name registered successfully"]);
} else {
    echo json_encode(['error' => 'Database error: ' . $stmt->error]);
}
$stmt->close();
?>
