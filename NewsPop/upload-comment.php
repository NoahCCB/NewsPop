<?php
session_start();
require 'database.php';

if (isset($_SESSION['logged_in'])) {
    $post_id = $_POST['post_id'];
    $title = $_POST['title'];
    $commenter_id = $_POST['commenter_id'];
    $comment = $_POST['comment'];
    $commenter_id = $_SESSION['id'];

    $stmt = $mysqli->prepare("INSERT INTO comments (post_id, id, comment) VALUES (?, ?, ?)");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param("iis", $post_id, $commenter_id, $comment);
    $stmt->execute();

    $stmt->close();

    // Redirect back to the post detail page
    header("Location: detail.php?title=" . urlencode($title));
    exit();
}
?>
