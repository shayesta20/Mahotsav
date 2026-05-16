<?php include 'config.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Mahotsav 2026 – Eternal Harmony</title>

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
    html,body{
      height:100%;
      margin:0;
      font-family:Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      color:var(--text);
    }

    body{
      background:
        linear-gradient(rgba(43,4,67,0.82), rgba(43,4,67,0.82)),
        url('https://www.itsalldowntown.com/wp-content/uploads/2025/01/January-cEDH-Win-a-Black-Lotus-Invitational.jpg')
        no-repeat center center fixed;
      background-size: cover;
    }

    .navbar {
      display:flex;
      justify-content:space-between;
      align-items:center;
      padding:12px 40px;
      background:rgba(255,255,255,0.05);
      position:sticky;
      top:0;
      backdrop-filter:blur(12px);
      z-index:1000;
    }
    .navbar img {height:50px; filter:brightness(0) invert(1);}

    .nav-links {display:flex; gap:20px; align-items:center;}
    .nav-links a {
      color:#fff; text-decoration:none; font-weight:500; transition:0.3s;
    }
    .nav-links a:hover {color:#ffd36b;}
    .nav-btn {
      background:#ffd36b; color:#2b0443;
      padding:6px 14px; border-radius:20px;
      font-size:14px; text-decoration:none; margin-left:10px;
      transition:0.3s;
    }
    .nav-btn:hover {background:#ff9f6b;}

    .hero-section {
      text-align:center; padding:100px 20px;
    }
    .hero-section h1 {font-size:42px; color:#fff;}
    .hero-section p {max-width:700px; margin:auto; font-size:18px; color:#ddd;}

    .about {
      padding:60px 40px;
      background:rgba(255,255,255,0.07);
      border-radius:16px;
      margin:40px auto;
      max-width:800px;
    }
    .about h2 {color:#ffd36b; margin-bottom:16px;}
    .about p {line-height:1.7; color:#eee;}

    .footer {
      margin-top:50px; padding:20px; text-align:center;
      font-size:14px; background:rgba(255,255,255,0.05);
    }
    .footer a {color:#ffd36b;}
  </style>
</head>

<body>

  <div class="navbar">
    <div><img src="https://manabuki.in/wp-content/uploads/2024/12/Vignan-Logo-1.png" /></div>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a href="#about">About Us</a>
      <a href="#events">Events</a>
      <a href="register.php" class="nav-btn">Register</a>
      <a href="login.php" class="nav-btn">Login</a>
    </div>
  </div>

  <div class="hero-section">
    <h1>Vignan Mahotsav 2026</h1>
    <p>The Eternal Harmony – February 5th to 7th, 2026</p>
  </div>

  <div class="about" id="about">
    <h2>About Us</h2>
    <p>
      Vignan Mahotsav 2026 – A Celebration of Eternal Harmony (Feb 5–7, 2026)<br><br>
      Step into a world where unity, creativity, and timeless spirit converge—welcome to the 19th edition of Vignan Mahotsav, 
      a national-level youth festival that has inspired generations since 2002. This year, we invite you to experience Eternal Harmony, 
      a theme that celebrates the enduring rhythm of togetherness across cultures, talents, and time.<br><br>
      With over 80+ events spanning Performing Arts, Visual Arts, Literary Showcases, Gaming, Robo Games, Sports & Para Sports, 
      the festival is a vibrant mosaic of expression and excellence.<br><br>
      💰 Cash Prizes worth ₹15,00,000 👥 Participants: 750+ <br>
      <br>📍 Venue: Vignan's Foundation for Science, Technology & Research
    </p>
  </div>

  <div class="footer">
    <p>
      📞 Contact: +91 1234567890 | +91 0987654321 <br>
      📧 Email: mahotsav@vignan.ac.in <br>
      🌐 <a target="_blank">vignan.ac.in/mahotsav</a><br>
      📷 <a target="_blank">https://www.instagram.com/vignan_mahotsav/</a>
    </p>
  </div>

</body>
</html>
