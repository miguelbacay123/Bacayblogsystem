<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Me</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            background: #fafafa;
            color: #222;
        }
        .header {
            display: flex;
            justify-content: flex-end;
            padding: 32px 60px 0;
        }
        nav {
            display: flex;
            gap: 32px;
        }
        nav a {
            color: #222;
            text-decoration: none;
            font-size: 1.08em;
            font-weight: 500;
            padding-bottom: 2px;
        }
        nav a.active {
            color: #e91e63;
            border-bottom: 2px solid #e91e63;
        }
        .main-content {
            display: flex;
            align-items: flex-start;
            gap: 40px;
            padding: 40px 60px;
            max-width: 1000px;
            margin: 0 auto;
        }
        .profile-img {
            width: 280px;
            height: 280px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.10);
            background: #eee;
        }
        .about-details {
            flex: 1;
        }
        .about-details h1 {
            margin: 0;
            font-size: 2.4em;
            font-weight: bold;
            color: #222;
        }
        .about-details .role {
            font-size: 1.1em;
            font-weight: bold;
            color: #e91e63;
            margin: 8px 0 24px;
            display: block;
        }
        .about-details p {
            margin-bottom: 18px;
            font-size: 1.05em;
            line-height: 1.6;
            color: #444;
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['email'])): ?>
  <div style="position: absolute; top: 20px; left: 20px; background: #fff0f5; padding: 10px 16px; border-radius: 8px; font-weight: bold; color: #e91e63; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
    Welcome <?= htmlspecialchars($_SESSION['email']) ?>
  </div>
<?php endif; ?>
    <div class="header">
        <nav>
            <a href="index.php" class="active">View Blog</a>
            <a href="about.php" class="active">About</a>
            <a href="#">Portfolio</a>
            <a href="#">Contact</a>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="logout.php" style="color:#e91e63; font-weight:bold;">Logout</a>
            <?php endif; ?>
        </nav>
    </div>
    <div class="main-content">
        <img src="images/profile.png" alt="Profile" class="profile-img">
        <div class="about-details">
            <h1>ABOUT ME</h1>
            <span class="role">INFORMATION TECHNOLOGIST</span>

            <p>I'm passionate about the dynamic world of technology and its evolving challenges. I thrive on understanding complex systems and crafting innovative solutions that make a difference.</p>

            <p>Adaptability and collaboration are my strongest assets. I see every challenge as a chance to grow, especially when working in teams that share knowledge and push boundaries together.</p>

            <p>Communication and teamwork are the backbone of every successful tech project. I'm constantly learning, exploring new tools, and staying curious about emerging trends.</p>

            <p>Growth comes from resilience — learning from mistakes, improving through experience, and staying driven by a genuine desire to make a meaningful impact in the tech landscape.</p>
        </div>
