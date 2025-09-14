<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  die('Invalid post ID.');
}

$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
if (!$post) {
  die('Post not found.');
}

// Ensure uploads directory exists
$uploadDirAbs = __DIR__ . '/uploads';
if (!is_dir($uploadDirAbs)) {
  mkdir($uploadDirAbs, 0777, true);
}

$errors = [];
$request = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($request === 'POST') {
  $title    = trim($_POST['title'] ?? '');
  $content  = trim($_POST['content'] ?? '');
  $category = trim($_POST['category'] ?? '');
  $imagePath = $post['image_path']; // keep current by default

  if ($title === '' || $content === '') {
    $errors[] = 'Title and content are required.';
  }

  if (!empty($_FILES['image']['name'])) {
    $file = $_FILES['image'];
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif'];

    if (!in_array($ext, $allowed, true)) {
      $errors[] = 'Invalid image type.';
    } else {
      $newName   = uniqid('img_', true) . '.' . $ext;
      $targetRel = 'uploads/' . $newName;          // path saved to DB
      $targetAbs = $uploadDirAbs . '/' . $newName; // filesystem path

      if (!move_uploaded_file($file['tmp_name'], $targetAbs)) {
        $errors[] = 'Failed to upload image.';
      } else {
        // delete old image if existed
        if (!empty($imagePath)) {
          $oldAbs = __DIR__ . '/' . $imagePath;
          if (is_file($oldAbs)) { @unlink($oldAbs); }
        }
        $imagePath = $targetRel;
      }
    }
  }

  if (!$errors) {
    $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, category = ?, image_path = ? WHERE id = ?");
    
    // ✅ Fixed this line: corrected types and matched variable count
    $stmt->bind_param("ssssi", $title, $content, $category, $imagePath, $id);
    
    $stmt->execute();
    header('Location: index.php');
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Post</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 40px; color: #222; }
    .wrapper { max-width: 700px; margin: auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    h1 { color: #e91e63; margin-bottom: 20px; }
    label { font-weight: bold; display: block; margin-top: 10px; }
    input[type="text"], textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 8px; }
    textarea { resize: vertical; min-height: 120px; }
    img { max-width: 100%; margin-top: 10px; border-radius: 8px; }
    .error { background: #ffe6e6; color: #b00020; padding: 10px; border-radius: 8px; margin-bottom: 20px; }
    button { background: #e91e63; color: #fff; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; margin-top: 20px; }
    a { display: inline-block; margin-top: 20px; text-decoration: none; color: #555; }
  </style>
</head>
<body>
  <div class="wrapper">
    <h1>Edit Post</h1>

    <?php if ($errors): ?>
      <div class="error">
        <?php foreach ($errors as $e): ?>
          <div>- <?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <label>Title:</label>
      <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>

      <label>Category:</label>
      <input type="text" name="category" value="<?= htmlspecialchars($post['category']) ?>">

      <label>Content:</label>
      <textarea name="content" required><?= htmlspecialchars($post['content']) ?></textarea>

      <label>Image:</label>
      <?php if (!empty($post['image_path'])): ?>
        <img src="<?= htmlspecialchars($post['image_path']) ?>" alt="Current image">
      <?php endif; ?>
      <input type="file" name="image" accept="image/*">

      <button type="submit">Update Post</button>
    </form>

    <a href="index.php">← Back to the blog</a>
  </div>
</body>
</html>
