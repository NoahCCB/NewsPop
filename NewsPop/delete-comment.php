<?php
session_start();
require 'database.php';

if (isset($_SESSION['logged_in'])) {
    $comment_id = $_POST['comment_id'];
    $title = $_POST['title'];
    $id = $_SESSION['id'];

    $stmt = $mysqli->prepare("SELECT id FROM comments WHERE comment_id = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if ($id === $comment_user_id) {
        $stmt = $mysqli->prepare("DELETE FROM comments WHERE comment_id = ?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param("i", $comment_id);
        $stmt->execute();

        $stmt->close();
    }
    // Redirect back to the post detail page
    header("Location: detail.php?title=" . urlencode($title));
    exit();
}
?>
