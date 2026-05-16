<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Profile</title>

<style>
:root{
  --bg1: #2b0443;
  --bg2: #6b2c7b;
  --glass: rgba(255,255,255,0.06);
  --border: rgba(255,255,255,0.08);
  --text: #fff;
  --accent: #ffbd5f;
}

body{
  margin:0;
  padding:0;
  background: radial-gradient(circle, rgba(255,255,255,0.03), transparent 20%), 
              linear-gradient(135deg,var(--bg1),var(--bg2));
  color: var(--text);
  font-family: Inter, sans-serif;
}

.container{
  max-width: 1100px;
  margin: 40px auto;
  display: flex;
  gap: 20px;
  padding: 20px;
}

.sidebar{
  width:260px;
  background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.02));
  padding:18px;
  border-radius:14px;
  border:1px solid var(--border);
}

.sidebar a{
  display:block;
  padding:10px 12px;
  border-radius:10px;
  color:#e9dff6;
  text-decoration:none;
  margin-bottom:8px;
  font-size:15px;
}

.sidebar a:hover{
  background: rgba(255,255,255,0.05);
  color:white;
}

.logout-btn{
  background:#ff5f5f !important;
  color:white !important;
}

.main-box{
  flex:1;
  padding:30px;
  background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.02));
  border-radius:14px;
  border:1px solid var(--border);
}

h2{
  margin-top:0;
  margin-bottom:20px;
  font-size:26px;
}

form label{
  font-size:14px;
  margin-top:15px;
  display:block;
  color:#e9dff6;
}

form input, form select{
  width:100%;
  padding:10px;
  border-radius:10px;
  border:1px solid rgba(255,255,255,0.1);
  background: rgba(255,255,255,0.1);
  color:white;
  margin-top:5px;
  font-size:15px;
}

form input:focus, form select:focus{
  outline:none;
  border-color: var(--accent);
}

.button{
  margin-top:20px;
  padding:10px 16px;
  border:none;
  background: linear-gradient(90deg,#ffbd5f,#ff7e5f);
  border-radius:10px;
  color:#2b0443;
  font-weight:bold;
  cursor:pointer;
  font-size:16px;
}

.button:hover{
  background: linear-gradient(90deg,#ffb34a,#ff6f50);
}

</style>
</head>

<body>

<div class="container">

    <div class="sidebar">
        <h2 style="color:#fff;margin-top:0;">Welcome, <?php echo strtoupper($user['first_name']); ?></h2>

        <a href="dashboard.php">Profile</a>
        <a href="events.php">Events</a>
        <a href="registered_events.php">Registered Events</a>
        <a href="change_password.php">Change Password</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="main-box">

        <h2>Edit Profile</h2>

        <form action="update_profile.php" method="POST">

            <label>First Name</label>
            <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" required>

            <label>Last Name</label>
            <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" required>

            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>

            <label>Gender</label>
            <select name="gender">
                <option <?php if($user['gender']=="Male") echo "selected"; ?>>Male</option>
                <option <?php if($user['gender']=="Female") echo "selected"; ?>>Female</option>
            </select>

            <label>State</label>
            <input type="text" name="state" value="<?php echo $user['state']; ?>">

            <label>District</label>
            <input type="text" name="district" value="<?php echo $user['district']; ?>">

            <label>College</label>
            <input type="text" name="college" value="<?php echo $user['college']; ?>">

            <button class="button" type="submit">Update Profile</button>

        </form>

    </div>

</div>

</body>
</html>
