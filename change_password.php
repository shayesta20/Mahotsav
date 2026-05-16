<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];
$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $stmt = $mysqli->prepare("SELECT password_hash FROM users WHERE id=?");
    $stmt->bind_param("i",$uid);
    $stmt->execute();
    $stmt->bind_result($pw_hash);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current, $pw_hash)) {
        $error = "Old password is incorrect.";
    } elseif ($new !== $confirm) {
        $error = "Passwords do not match.";
    } else {

        $new_hash = password_hash($new, PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("UPDATE users SET password_hash=? WHERE id=?");
        $stmt->bind_param("si", $new_hash, $uid);
        $stmt->execute();
        $stmt->close();

        header("Location: change_password.php?success=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <div class="sidebar">
        <h2>Welcome</h2>

        <a href="dashboard.php">Profile</a>
        <a href="events.php">Events</a>
        <a href="registered_events.php">Registered Events</a>
        <a href="change_password.php">Change Password</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="main-box">

        <h2>Change Password</h2>

        <?php if(isset($_GET['success'])) { ?>
            <p style="color:#00ff99; font-weight:bold;">Password updated successfully!</p>
        <?php } ?>

        <?php if($error) { ?>
            <p style="color:#ff4444; font-weight:bold;"><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST">

            <label>Old Password</label>
            <input type="password" name="current_password" required>

            <label>New Password</label>
            <input type="password" name="new_password" required>

            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" required>

            <button class="edit-btn" type="submit">Update Password</button>

        </form>

    </div>

</div>

</body>
</html>
