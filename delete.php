<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: index.php'); exit; }

// fetch image path to delete file
$stmt = $conn->prepare("SELECT image_path FROM posts WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$img = $stmt->get_result()->fetch_assoc();
if ($img && !empty($img['image_path'])) {
    $full = __DIR__ . '/' . $img['image_path'];

    if (file_exists($full)) { @unlink($full); }
}

// delete row
$stmt = $conn->prepare("DELETE FROM posts WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header('Location: index.php');
exit;
