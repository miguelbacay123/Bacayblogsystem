<?php
session_start();
$error = '';

// ✅ Connect to MySQL
$conn = new mysqli("localhost", "root", "", "blogsystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Username and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username']; // safer to pull from DB
        $_SESSION['email'] = $user['email'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign In</title>
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
    <h2>Sign In</h2>
    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>
      <button type="submit">Sign In</button>
    </form>
    <div class="link">
      Not a member? <a href="register.php">Click here</a>
    </div>
  </div>
</body>
</html>
