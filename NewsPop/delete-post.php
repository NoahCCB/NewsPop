<?php
session_start();
require 'database.php';

if (isset($_SESSION['logged_in'])) {
    $post_id = $_POST['post_id'];
    $username = $_POST['username'];
    $id = $_SESSION['id'];

    $stmt = $mysqli->prepare("SELECT id FROM posts WHERE post_id = ?");
    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();
    if ($id === $user_id){
        //delete all comments
        $stmt = $mysqli->prepare("DELETE FROM comments WHERE post_id = ?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param("i", $post_id);
        $stmt->execute();

        $stmt->close();

        //now delete the post
        $stmt = $mysqli->prepare("DELETE FROM posts WHERE post_id = ?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param("i", $post_id);
        $stmt->execute();

        $stmt->close();
    }
    //redirect back to the profile page
    header("Location: profile.php?user=" . urlencode($username));
    exit();
}
?>
