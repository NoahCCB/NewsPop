<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="main">
    <a class='log-button' href='home.php'>Home</a>
        <?php
        session_start();
        require 'database.php';
        $username = "";
        //if user is logged in
        if (isset($_GET['user']) && isset($_SESSION['logged_in'])) {
            $username = $_GET['user'];
            $id = $_SESSION['id'];
        }
        else if (isset($_GET['user'])){
            $username = $_GET['user'];
        }
        else {
            echo "<h3 class='error'>Problem retrieving username</h3>";
        }
        $get_id = $mysqli->prepare("
            SELECT id FROM users 
            WHERE username = ?
        ");
        $get_id->bind_param("s", $username);
        $get_id->execute();

        $get_id->bind_result($profile_id);
        $get_id->fetch();

        $get_id->close();


        $check = $mysqli->prepare("
            SELECT COUNT(*)
            FROM followers
            WHERE id = ? AND follower_id = ?
        ");
        if (!$check) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $check->bind_param("ii", $profile_id, $id);
        $check->execute();

        $check->bind_result($count);
        $check->fetch();

        $check->close();

        $stmt = $mysqli->prepare("
            SELECT p.*
            FROM posts AS p
            INNER JOIN users AS u ON p.id = u.id
            WHERE u.username = ?
        ");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();

        $stmt->bind_result($poster_id, $title, $body, $link, $time_stamp, $image_path, $post_id);


        echo "<h1>$username</h1>";

        $followed = false;

        if ($count > 0) {
            //the follower exists for the specified user
            $followed = true;
        } 
        //only if logged in can you delete
        if (isset($_SESSION['logged_in']) && $username !== $_SESSION['username'] && !$followed){
            echo "<form action='add-follower.php' method='POST'>";
            echo "<input type='hidden' name='id' value='$profile_id'>";
            echo "<input type='hidden' name='username' value='$username'>";
            echo "<input type='submit' value='Follow'>";
            echo "</form>";
        }
        if ($followed){
            echo "<h3 class='following'>Following</h3>";
            echo "<form action='remove-follower.php' method='POST'>";
            echo "<input type='hidden' name='id' value='$profile_id'>";
            echo "<input type='hidden' name='username' value='$username'>";
            echo "<input type='submit' value='Unfollow'>";
            echo "</form>";
        }
        echo "<h3>Posts</h3>";
        
        echo "<div class='grid'>";

        while ($stmt->fetch()) {
            echo "<div class='grid-item'>";
            echo "<a class='title' href='detail.php?title=" . urlencode($title) . "'>";

            if (!is_null($image_path)) {
                echo "<img src='$image_path' alt='$title'>";
            }
            echo "<h3>$title</h3>";
            echo "</a>";
            if (strlen($link) > 0) {
                echo "<a href='$link'>Read more</a>";
            }
            if (isset($_SESSION['logged_in'])){
                if ($username == $_SESSION['username']){
                    echo "<form action='delete-post.php' method='POST'>";
                    echo "<input type='hidden' name='post_id' value='$post_id'>";
                    echo "<input type='hidden' name='username' value='$username'>";
                    echo "<input type='submit' value='Delete'>";
                    echo "</form>";
                }
            }
            echo "</div>";
        }

        echo "</div>";

        $stmt->close();
        ?>
    </div>
</body>
</html>
