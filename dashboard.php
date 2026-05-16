<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$uid = $_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT id,mhid,first_name,last_name,email,dob,phone,gender,state,district,college FROM users WHERE id = ?");
$stmt->bind_param('i', $uid);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

$reg_stmt = $mysqli->prepare("SELECT id AS reg_id, event_name, event_category, participant_name, registered_at FROM registrations WHERE user_id=? ORDER BY registered_at DESC");
$reg_stmt->bind_param('i', $uid);
$reg_stmt->execute();
$regs = $reg_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$reg_stmt->close();

$event_groups = [
    'Cultural' => [
        ['name' => 'Dance', 'image' => 'https://i.pinimg.com/originals/0b/12/43/0b124366a1e2cbf6eb64fee6366a43fb.jpg'],
        ['name' => 'Singing', 'image' => 'https://i.ytimg.com/vi/bBnTTtZ7qBM/maxresdefault.jpg']
    ],
    'Sports' => [
        ['name' => 'Running 100M', 'image' => 'https://tse1.explicit.bing.net/th/id/OIP.Y5IkMvWgOPaTiXvx6QEUuAHaEo'],
        ['name' => 'Running 400M', 'image' => 'https://th.bing.com/th/id/OIP.EAO-Lws898kHWyGUEOWu-QHaEK']
    ]
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard - Mahotsav</title>

<style>
:root{
  --bg1: #2b0443;
  --bg2: #6b2c7b;
  --accent: #ffdb6b;
  --muted: #e9dff6;
  --card: rgba(255,255,255,0.06);
  --glass: rgba(255,255,255,0.06);
  --radius: 14px;
  --glass-border: rgba(255,255,255,0.08);
  --text: #fff;
}
*{box-sizing:border-box}
html,body{height:100%;margin:0;font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial;}
body{
  background: radial-gradient(ellipse at center, rgba(255,255,255,0.02) 0%, transparent 30%), linear-gradient(135deg,var(--bg1) 0%,var(--bg2) 100%);
  color:var(--text);
  -webkit-font-smoothing:antialiased;
  -moz-osx-font-smoothing:grayscale;
}
.container{max-width:1100px;margin:36px auto;padding:28px;}
.events-grid { display: flex; gap: 16px; flex-wrap: wrap; }
.event-card { width: 220px; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; overflow: hidden; background: #2c1a66; color: #fff; box-sizing: border-box; }
.event-card img { width: 100%; height: 130px; object-fit: cover; display: block; }
.event-card .content { padding: 12px; }
.button { cursor: pointer; background: linear-gradient(90deg, #ffbd5f, #ff7e5f); border: none; padding: 8px 12px; border-radius: 4px; font-weight: bold; color: #2b0443; transition: background 0.3s ease; }
.button:hover { background: linear-gradient(90deg, #ffa64c, #e65b42); }
.table { width: 100%; border-collapse: collapse; margin-top: 16px; color: #fff; }
.table th, .table td { padding: 12px 8px; border: 1px solid #ddd; text-align: left; }
.alert { background: rgba(255, 189, 95, 0.3); color: #ffbd5f; padding: 10px; border-radius: 5px; margin-top: 10px; }
.dashboard { display:grid; grid-template-columns:260px 1fr; gap:18px; margin-top:20px; }
.sidebar { background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border-radius:14px; padding:18px; border:1px solid var(--glass-border); }
.sidebar h3{margin:0 0 10px 0}
.menu-item{padding:12px;border-radius:10px;color:var(--muted);cursor:pointer;margin-bottom:8px}
.menu-item:hover{background:rgba(255,255,255,0.02); color:#fff}
.main{ background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border-radius:14px; padding:20px; border:1px solid var(--glass-border); }
.profile-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.profile-field{background: rgba(255,255,255,0.02); padding:12px;border-radius:10px}
.field-title{font-size:12px;color:#e9dff6;opacity:0.9;margin-bottom:6px}
.field-value{font-weight:600;color:#fff;font-size:15px}
.events-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;margin-top:12px}
.event-card{padding:12px;border-radius:12px;background:linear-gradient(180deg, rgba(255,255,255,0.015), rgba(255,255,255,0.01));border:1px solid rgba(255,255,255,0.03)}
.table{width:100%;border-collapse:collapse;margin-top:12px}
.table th, .table td{padding:10px;text-align:left;border-bottom:1px solid #ddd;}

.table th{font-size:13px;color:#e9dff6 ;background: rgba(255,255,255,0.05);}
@media (max-width:900px){
  .hero{flex-direction:column;align-items:flex-start}
  .form-grid{grid-template-columns:1fr}
  .dashboard{grid-template-columns:1fr}
  .lotus{width:140px;height:140px;flex:0 0 140px}
}
</style>
</head>
<body>
	
  <div class="container">
    <div class="dashboard">
      <div class="sidebar">
        <h3>Welcome, <?php echo e($user['first_name']); ?></h3>
        <div class="menu-item" onclick="show('profile')">Profile</div>
        <div class="menu-item" onclick="show('events')">Events</div>
        <div class="menu-item" onclick="show('registered')">Registered Events</div>
        <div class="menu-item" onclick="show('changepw')">Change Password</div>
        <div style="margin-top:10px"><a href="logout.php" class="button" style="display:inline-block">Logout</a></div>
      </div>

      <div class="main">

        <div id="profile" class="panel">
          <h2>Profile</h2>
		  <a href="edit_profile.php" class="button" style="margin-bottom:12px;display:inline-block;">
				Edit Profile
					</a>

          <div class="profile-grid">
            <div class="profile-field"><div class="field-title">MHID</div><div class="field-value"><?php echo e($user['mhid']); ?></div></div>
            <div class="profile-field"><div class="field-title">Name</div><div class="field-value"><?php echo e($user['first_name'].' '.$user['last_name']); ?></div></div>
            <div class="profile-field"><div class="field-title">Email</div><div class="field-value"><?php echo e($user['email']); ?></div></div>
            <div class="profile-field"><div class="field-title">Phone</div><div class="field-value"><?php echo e($user['phone']); ?></div></div>
            <div class="profile-field"><div class="field-title">DOB</div><div class="field-value"><?php echo e($user['dob']); ?></div></div>
            <div class="profile-field"><div class="field-title">College</div><div class="field-value"><?php echo e($user['college'] . ', ' . $user['district'] . ', ' . $user['state']); ?></div></div>
          </div>
        </div>

        <div id="events" class="panel" style="display:none;">
          <h2>Events</h2>
          <?php foreach ($event_groups as $category => $subevents): ?>
            <h3><?php echo e($category); ?></h3>
            <div class="events-grid">
              <?php foreach ($subevents as $ev): ?>
                <div class="event-card">
                  <img src="<?php echo e($ev['image']); ?>" alt="<?php echo e($ev['name']); ?>">
                  <div class="content">
                    <div class="event-name" style="font-weight:bold;"><?php echo e($ev['name']); ?></div>
                    <div style="margin-top:6px;">
                      <button class="button" 
                        onclick='openRegister(<?php echo json_encode(['name' => $ev['name'], 'category' => $category], JSON_HEX_TAG | JSON_HEX_APOS); ?>)'>Register</button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>

          <div id="eventReg" style="display:none;margin-top:12px;">
            <h3>Register for Event</h3>
            <div class="card" style="background:#452d9a; padding:16px; border-radius:6px;">
              <form id="eventRegForm" onsubmit="return false;">
                <input type="hidden" name="event_name" id="event_name">
                <input type="hidden" name="event_category" id="event_category">
                <div class="form-row">
                  <label>MHID</label>
                  <input id="ev_mhid" readonly style="background:#ddd; color:#444;">
                </div>
                <div class="form-row">
                  <label>Phone</label>
                  <input id="ev_phone" readonly style="background:#ddd; color:#444;">
                </div>
                <div class="form-row">
                  <label>Your Full Name</label>
                  <input id="ev_name" required>
                </div>
                <div style="display:flex;justify-content:flex-end;margin-top:8px;">
                  <button type="button" class="button" onclick="submitEventReg()">Register</button>
                  <button type="button" class="button" style="margin-left:10px; background:#a04a4a; color:#fff;" onclick="closeRegister()">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div id="registered" class="panel" style="display:none;">
          <h2>Registered Events</h2>
          <?php if(empty($regs)): ?>
            <div class="alert">No event registrations yet.</div>
          <?php else: ?>
            <table class="table">
              <thead><tr><th>Event</th><th>Category</th><th>Participant Name</th><th>Registered At</th><th>Action</th></tr></thead>
              <tbody>
                <?php foreach($regs as $r): ?>
                  <tr id="regrow<?php echo e($r['reg_id']); ?>">
                    <td><?php echo e($r['event_name']); ?></td>
                    <td><?php echo e($r['event_category']); ?></td>
                    <td><?php echo e($r['participant_name']); ?></td>
                    <td><?php echo e($r['registered_at']); ?></td>
                    <td><button class="button" style="background:#d9534f; color:#fff;" onclick="deleteReg(<?php echo e($r['reg_id']); ?>)">Delete</button></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>

        <div id="changepw" class="panel" style="display:none;">
          <h2>Change Password</h2>

          <?php
          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['old_password'])) {

              $old = $_POST['old_password'];
              $new = $_POST['new_password'];
              $confirm = $_POST['confirm_password'];

              $psql = $mysqli->prepare("SELECT COALESCE(password_hash,password) AS pw FROM users WHERE id=?");
              $psql->bind_param("i", $uid);
              $psql->execute();
              $pres = $psql->get_result()->fetch_assoc();
              $currentHash = $pres['pw'] ?? '';
              $psql->close();

              if (!password_verify($old, $currentHash)) {
                  echo "<div class='alert'>Old password is incorrect.</div>";
              } elseif ($new !== $confirm) {
                  echo "<div class='alert'>New passwords do not match.</div>";
              } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).{8,}$/', $new)) {
                  echo "<div class='alert'>Password must be 8+ chars and include upper, lower, digit and special char.</div>";
              } else {
                  $newHash = password_hash($new, PASSWORD_DEFAULT);
                  $usql = $mysqli->prepare("UPDATE users SET password_hash=? WHERE id=?");
                  $usql->bind_param("si", $newHash, $uid);
                  $usql->execute();
                  $usql->close();

                  echo "<div class='alert' style='background:#4CAF50;color:white;'>Password changed successfully.</div>";
              }
          }
          ?>

          <form method="POST" style="max-width:400px;">
			<div class="form-row">
			  <label>Old Password</label>
			  <input type="password" name="old_password" required>
			</div>

			<div class="form-row">
			  <label>New Password</label>
			  <input type="password" name="new_password" required>
			</div>

			<div class="form-row">
			  <label>Confirm New Password</label>
			  <input type="password" name="confirm_password" required>
			</div>

			<button type="submit" class="button" style="margin-top:10px;">Update Password</button>
		</form>

        </div>

      </div>
    </div>
  </div>

<script>
const user = <?php echo json_encode($user, JSON_HEX_TAG|JSON_HEX_APOS); ?>;
const eventRegForm = document.getElementById('eventRegForm');

function show(id){
  document.querySelectorAll('.panel').forEach(p=>p.style.display='none');
  document.getElementById(id).style.display='block';
}
show('profile');

function openRegister(ev){
  document.getElementById('eventReg').style.display='block';
  document.getElementById('event_name').value = ev.name;
  document.getElementById('event_category').value = ev.category;
  document.getElementById('ev_mhid').value = user.mhid;
  document.getElementById('ev_phone').value = user.phone;
  document.getElementById('ev_name').value = user.first_name + ' ' + user.last_name;
  window.scrollTo({top: document.getElementById('eventReg').offsetTop - 20, behavior:'smooth'});
}
function closeRegister(){
  document.getElementById('eventReg').style.display='none';
}

function submitEventReg(){
  const eventName = document.getElementById('event_name').value;
  const eventCategory = document.getElementById('event_category').value;
  const participantName = document.getElementById('ev_name').value.trim();

  if(!participantName){
    alert('Enter your name');
    return;
  }

  const fd = new FormData();
  fd.append('name', eventName);
  fd.append('category', eventCategory);
  fd.append('participant_name', participantName);

  fetch('register_event.php', {method:'POST', body:fd})
    .then(r=>r.json())
    .then(j=>{
      if(j.success){
        alert(j.message);
        closeRegister();

        const tableBody = document.querySelector('#registered table tbody');
        if(tableBody){
          const tr = document.createElement('tr');
          const tempId = 'temp-'+Date.now();
          tr.id = 'regrow'+tempId;

          function escapeHtml(text)
		  { return text.replace(/[&<>"']/g, function(m) 
		  { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]; }); }

          tr.innerHTML = `
            <td>${escapeHtml(eventName)}</td>
            <td>${escapeHtml(eventCategory)}</td>
            <td>${escapeHtml(participantName)}</td>
            <td>Just now</td>
            <td><button class="button" style="background:#d9534f; color:#fff;" onclick="deleteReg('${tempId}')">Delete</button></td>
          `;
          tableBody.prepend(tr);
          const noRegsAlert = document.querySelector('#registered .alert');
          if(noRegsAlert) noRegsAlert.style.display = 'none';
        }
        show('registered');
      } else {
        alert(j.error || 'Failed');
      }
    });
}

function deleteReg(regId){
  if(!confirm('Delete this registration?')) return;
  const fd = new FormData();
  fd.append('reg_id', regId);
  fetch('delete_registration.php', {method:'POST', body:fd})
    .then(r=>r.json())
    .then(j=>{
      if(j.success){
        document.getElementById('regrow'+regId)?.remove();
        const tbody = document.querySelector('#registered table tbody');
        if(tbody && tbody.children.length === 0){
          const alertDiv = document.querySelector('#registered .alert');
          if(alertDiv) alertDiv.style.display = 'block';
          else {
            const newAlert = document.createElement('div');
            newAlert.className = 'alert';
            newAlert.textContent = 'No event registrations yet.';
            document.getElementById('registered').appendChild(newAlert);
          }
        }
      } else {
        alert(j.error || 'Failed');
      }
    });
}
</script>
</body>
</html>
