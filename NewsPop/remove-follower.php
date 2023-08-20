<?php
session_start();
require 'database.php';

if (isset($_SESSION['logged_in'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $follower_id = $_SESSION['id'];

    $add = $mysqli->prepare("DELETE FROM followers WHERE id=? and follower_id=?");

    if (!$add){
        printf("<h1 class='error'>Remove Follower Failed: %s\n </h1>", $mysqli->error);
        exit;
    }

    $add->bind_param('ii', $id, $follower_id);
    $add->execute();
    $add->close();

    $stmt = $mysqli->prepare("UPDATE users
    SET followers = followers - 1
    WHERE id = ?");

    if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt->close();

    header("Location: profile.php?user=" . urlencode($username));
    exit();
}
?>