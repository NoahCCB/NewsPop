<?php
session_start();
require 'database.php';

if (isset($_SESSION['logged_in'])) {
    // Get the form data
    $title = strval($_POST['title']);
    $body = strval($_POST['body']);
    $link = strval($_POST['link']);
    $id = (int)$_SESSION['id'];
    $timestamp = date('Y-m-d H:i:s');

    // Handle the uploaded image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $filename = basename($_FILES['image']['name']);
        if (!preg_match('/^[\w_\.\-]+$/', $filename)) {
            echo "<h1 class ='error'>Invalid Filename</h1>";
            // exit;
        }

        $full_path = sprintf("./assets/%s", $filename);

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $full_path)) {
            echo "<h1 class ='error'>Image Upload Failed</h1>";
            // exit;
        }
    } else {
        $full_path = null;
    }

    $add = $mysqli->prepare("INSERT INTO posts (id, title, body, link, time_stamp, image_path) VALUES (?, ?, ?, ?, ?, ?)");

    if (!$add) {
        printf("<h1 class='error'>Post Upload Failed: %s\n </h1>", $mysqli->error);
        // exit;
    }

    $add->bind_param('isssss', $id, $title, $body, $link, $timestamp, $full_path);
    $add->execute();
    $add->close();

    //redirect the user to a success page or perform any other desired actions
    header('Location: home.php');
    exit();
}
?>
