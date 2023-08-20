<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>NewsPop</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div class="main">
            <h1>NewsPop</h1>
            <?php
            session_start();
            require 'database.php';

            if (isset($_SESSION['logged_in'])){
                $username = $_SESSION['username'];
                echo "<br>
                <!-- Add this button to navigate to the post page -->
                <a href='post.php' class='button'>Post a Story</a>
                <a class='button' href='profile.php?user=". urlencode($username) . "'>Profile</a>
                <a class='button' href='feed.php?user=". urlencode($username) . "'>Feed</a>
                <a class='log-button' href='logout.php'>Logout</a>
                <br> <br>";
            }
            else {
                echo "<br><a class='log-button' href='login.php'>Login</a>";
            }

            $stmt = $mysqli->prepare("select * from posts ORDER BY time_stamp DESC");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            
            $stmt->execute();
            
            $stmt->bind_result($poster_id, $title, $body, $link, $time_stamp, $image_path, $post_id);
            
            echo "<div class='grid'>";
           
            while ($stmt->fetch()) {
                echo "<div class='grid-item'>";
                echo "<a class='title' href='detail.php?title=" . urlencode($title) . "'>";
                //Display the image with the title and link
                if (!is_null($image_path)){
                    echo "<img src='$image_path' alt='$title'>";
                }
                echo "<h3>$title</h3>";
                echo "</a>";
                if (strlen($link) > 0){
                    echo "<a href='$link'>Read more</a>";
                }
                
                echo "</div>"; 
            }

            echo "</div>"; 

            $stmt->close();
            ?>
        </div>
    </body>
</html>