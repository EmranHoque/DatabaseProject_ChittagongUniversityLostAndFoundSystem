 <?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['comment_id']) || !isset($_GET['post_id'])) {
    header('Location: posts.php');
    exit;
}

$comment_id = $_GET['comment_id'];
$post_id = $_GET['post_id'];
$user_id = $_SESSION['user_id'];


$sql = "DELETE FROM comments 
        WHERE comment_id = :comment_id AND user_id = :user_id";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute(['comment_id' => $comment_id, 'user_id' => $user_id]);
    header("Location: post_details.php?post_id=$post_id");
    exit;
} catch (PDOException $e) {
    die("Error deleting comment: " . $e->getMessage());
}
?>
