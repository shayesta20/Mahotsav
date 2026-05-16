<?php
require 'config.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mhid = trim($_POST['mhid'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $mysqli->prepare("SELECT id, password_hash, mhid FROM users WHERE mhid = ?");
    $stmt->bind_param('s',$mhid);
    $stmt->execute();
    $stmt->store_result();
	
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id,$pw_hash,$mhiddb);
        $stmt->fetch();
        if (password_verify($password, $pw_hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['mhid'] = $mhiddb;
            header('Location: dashboard.php');
            exit;
        } else $err = 'Invalid credentials.';
    } else $err = 'Invalid credentials.';
    $stmt->close();
}
$prefill = $_GET['mhid'] ?? '';
?>

<!doctype html>
<html>
<head>
  <title>Login</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
	body{font-family:Inter,Segoe UI,Arial;background:linear-gradient(135deg,#2b0443,#6b2c7b);color:#fff;margin:0}
	.container{max-width:420px;margin:80px auto;background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));padding:22px;border-radius:12px;border:1px solid rgba(255,255,255,0.06)}
	input{width:100%;padding:10px;margin:8px 0;border-radius:8px;border:1px solid rgba(255,255,255,0.06);background:transparent;color:#fff}
	.button{background:linear-gradient(90deg,#ffd36b,#ff9f6b);color:#1b0b00;padding:10px;border-radius:8px;border:none;cursor:pointer}
	.alert{background:rgba(255,255,255,0.03);padding:10px;border-radius:8px}
	a{color:#ffd36b}
  </style>
</head>
<body>
<div class="container">
  <a href="index.php" style="color:#fff; text-decoration:none;">← Back to Home</a>
  <h2>Login</h2>
  <?php if($err): ?><div class="alert"><?= e($err) ?></div><?php endif; ?>
  <form method="post">
    <label>MHID</label>
    <input type="text" name="mhid" value="<?= e($_GET['mhid'] ?? '') ?>">
    <label>Password</label>
    <input type="password" name="password">
    <button type="submit" class="button">Login</button>
  </form>
</div>
</body>
</html>
