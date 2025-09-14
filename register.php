<?php
session_start();
$error = '';

// ✅ Connect to MySQL
$conn = new mysqli("localhost", "root", "", "blogsystem"); // ← Change "blogsystem" to match your database name
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $error = "All fields are required.";
    } else {
        // ✅ Check for duplicate username
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username already exists. Please choose another.";
        } else {
            // ✅ Insert new user
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed);
            $stmt->execute();

            $_SESSION['user'] = $username;
            header("Location: index.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #fafafa;
      margin: 0;
      color: #222;
    }
    .login-container {
      max-width: 400px;
      margin: 80px auto;
      background: #fff;
      padding: 32px;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }
    h2 {
      color: #e91e63;
      margin-bottom: 24px;
      text-align: center;
      font-size: 2em;
    }
    .form-group {
      margin-bottom: 18px;
    }
    label {
      display: block;
      font-weight: bold;
      margin-bottom: 6px;
    }
    input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1em;
    }
    button {
      width: 100%;
      background: #e91e63;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 1em;
      cursor: pointer;
    }
    .error {
      background: #ffe6e6;
      color: #b00020;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 16px;
      text-align: center;
    }
    .link {
      text-align: center;
      margin-top: 16px;
      font-size: 0.95em;
    }
    .link a {
      color: #e91e63;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Sign Up</h2>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>
      <button type="submit">Sign Up</button>
    </form>
    <div class="link">
      Already a member? <a href="login.php">Click here</a>
    </div>
  </div>
</body>
</html>
