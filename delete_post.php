<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['post_id'])) {
    header('Location: my_profile.php');
    exit;
}

$post_id = $_GET['post_id'];
$user_id = $_SESSION['user_id'];


$sql = "DELETE FROM post WHERE post_id = :post_id AND user_id = :user_id";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute(['post_id' => $post_id, 'user_id' => $user_id]);
    header('Location: my_profile.php');
    exit;
} catch (PDOException $e) {
    die("Error deleting post: " . $e->getMessage());
}
?>