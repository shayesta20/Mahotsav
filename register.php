<?php
require 'config.php';

function strong_password($pw){
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{8,}$/', $pw);
}

$errors = [];
$success_mhid = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $dob = $_POST['dob'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $gender = $_POST['gender'] ?? 'Male';
    $state = $_POST['state'] ?? '';
    $district = $_POST['district'] ?? '';
    $college = $_POST['college'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!$first || !$last) $errors[] = "First and last name required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email required.";
    if (!$dob) $errors[] = "Date of birth required.";
    if (!preg_match('/^\+?\d{7,15}$/', $phone)) $errors[] = "Valid phone required (digits only).";
    if (!strong_password($password)) $errors[] = "Password must be >=8 chars and include uppercase, lowercase, digit and special character.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";

    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "Email already registered.";
    }
    $stmt->close();

    if (empty($errors)) {
        $pw_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("INSERT INTO users (first_name,last_name,email,dob,phone,gender,state,district,college,password_hash) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssssssss', $first, $last, $email, $dob, $phone, $gender, $state, $district, $college, $pw_hash);
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            $mhid = generate_mhid($mysqli, $user_id);

            $u = $mysqli->prepare("UPDATE users SET mhid = ? WHERE id = ?");
            $u->bind_param('si', $mhid, $user_id);
            $u->execute();
            $u->close();

            $success_mhid = $mhid;
        } else {
            $errors[] = "Registration failed: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Register - Mahotsav</title>
  <link rel="stylesheet" href="style.css">
  <style>
    select {
      color: white ;
      border-radius: 6px;
      padding: 6px 10px;
      border: 1px solid #555;
    }
    select option {
      color: black;
      background-color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="index.php" style="color:#fff; text-decoration:none;">← Back</a>
    <div class="card">
      <h2 style="margin-top:0">Participant Registration</h2>

      <?php if(!empty($errors)): ?>
        <div class="alert" style="border-left:4px solid #ff9f6b">
          <strong>Errors:</strong>
          <ul>
            <?php foreach($errors as $e) echo "<li>$e</li>"; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if($success_mhid): ?>
        <div class="alert" style="display:flex;justify-content:space-between;align-items:center">
          <div>
            <strong>Registered!</strong>
            <div class="helper">Your Mahotsav ID: <span style="font-weight:800; margin-left:6px;"><?php echo htmlspecialchars($success_mhid); ?></span></div>
          </div>
          <div>
            <button class="button" onclick="copyAndProceed('<?php echo $success_mhid; ?>')">Copy MHID & Proceed to Login</button>
          </div>
        </div>
      <?php else: ?>

      <form method="post" id="regForm" class="card">
        <div class="form-grid">
          <div class="form-row">
            <label>First Name</label>
            <input type="text" name="first_name" required value="<?php echo htmlspecialchars($_POST['first_name'] ?? '') ?>">
          </div>
          <div class="form-row">
            <label>Last Name</label>
            <input type="text" name="last_name" required value="<?php echo htmlspecialchars($_POST['last_name'] ?? '') ?>">
          </div>

          <div class="form-row">
            <label>Email</label>
            <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>">
          </div>
          <div class="form-row">
            <label>Date of Birth</label>
            <input type="date" name="dob" required value="<?php echo htmlspecialchars($_POST['dob'] ?? '') ?>">
          </div>

          <div class="form-row">
            <label>Phone</label>
            <input type="tel" name="phone" required value="<?php echo htmlspecialchars($_POST['phone'] ?? '') ?>">
          </div>
          <div class="form-row"; >
            <label>Gender</label>
            <select name="gender">
              <option <?php if(($_POST['gender'] ?? '')=='Male') echo 'selected'; ?>>Male</option>
              <option <?php if(($_POST['gender'] ?? '')=='Female') echo 'selected'; ?>>Female</option>
              <option <?php if(($_POST['gender'] ?? '')=='Other') echo 'selected'; ?>>Other</option>
            </select>
          </div>

          <div class="form-row">
            <label>State</label>
            <select id="state" name="state"></select>
          </div>
          <div class="form-row">
            <label>District</label>
            <select id="district" name="district"></select>
          </div>

          <div class="form-row" style="grid-column:1/3">
            <label>College</label>
            <select id="college" name="college" required>
              <option value="">Select college</option>
            </select>
          </div>

          <div class="form-row">
            <label>Password</label>
            <input type="password" name="password" id="password" required>
            <div class="helper">Min 8 chars, 1 uppercase, 1 lowercase, 1 digit, 1 special char</div>
          </div>
          <div class="form-row">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
          </div>
        </div>

        <div style="display:flex;justify-content:flex-end;margin-top:10px">
          <button type="submit" class="button">Register</button>
        </div>
      </form>
      <?php endif; ?>

    </div>
  </div>

  <script>
  const data = {
    "Andhra Pradesh": {
      "Guntur": ["Vignan's University", "Vignan Pharmacy College", "Guntur JC"],
      "Vijayawada": ["KVSR Engg College","ABC College"]
    },
    "Telangana": {
      "Hyderabad": ["JNTU", "Osmania University"],
      "Warangal": ["Kakatiya University"]
    }
  };

  const stateSel = document.getElementById('state');
  const districtSel = document.getElementById('district');
  const collegeSel = document.getElementById('college');

  function populateStates(){
    stateSel.innerHTML = '<option value="">Select State</option>';
    Object.keys(data).forEach(s=>{
      stateSel.innerHTML += `<option>${s}</option>`;
    });
    const preState = "<?php echo addslashes($_POST['state'] ?? '') ?>";
    if(preState) stateSel.value = preState;
    onStateChange();
  }

  function onStateChange(){
    const s = stateSel.value;
    districtSel.innerHTML = '<option value="">Select District</option>';
    collegeSel.innerHTML = '<option value="">Select College</option>';
    if(!s || !data[s]) return;
    Object.keys(data[s]).forEach(d=>{
      districtSel.innerHTML += `<option>${d}</option>`;
    });
    const preDist = "<?php echo addslashes($_POST['district'] ?? '') ?>";
    if(preDist) districtSel.value = preDist;
    onDistrictChange();
  }

  function onDistrictChange(){
    const s = stateSel.value;
    const d = districtSel.value;
    collegeSel.innerHTML = '<option value="">Select College</option>';
    if(!s || !d) return;
    (data[s][d]||[]).forEach(c=>{
      collegeSel.innerHTML += `<option>${c}</option>`;
    });
    const preColl = "<?php echo addslashes($_POST['college'] ?? '') ?>";
    if(preColl) collegeSel.value = preColl;
  }

  stateSel?.addEventListener('change', onStateChange);
  districtSel?.addEventListener('change', onDistrictChange);
  populateStates();

  document.getElementById('regForm')?.addEventListener('submit', function(e){
    const pw = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    const re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{8,}$/;
    if(!re.test(pw)){
      e.preventDefault();
      alert('Password must meet strength requirements.');
      return false;
    }
    if(pw !== confirm){
      e.preventDefault();
      alert('Passwords do not match.');
      return false;
    }
  });

  function copyAndProceed(mhid){
    navigator.clipboard?.writeText(mhid).then(()=>{

      window.location.href = 'login.php?mhid=' + encodeURIComponent(mhid);
    }).catch(()=>{
      window.location.href = 'login.php?mhid=' + encodeURIComponent(mhid);
    });
  }
  </script>
</body>
</html>

