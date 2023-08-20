<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Detail</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="main">
        <?php
        session_start();
        require 'database.php';

        $title = urldecode($_GET['title']);

        $stmt = $mysqli->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.id = users.id WHERE posts.title = ?");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param("s", $title);
        $stmt->execute();

        $stmt->bind_result($poster_id, $title, $body, $link, $time_stamp, $image_path, $post_id, $username);
        $stmt->fetch();

        echo "<h2>$title</h2>";
        echo "<p>Posted by: <a class='button' href='profile.php?user=". urlencode($username) . "'>htmlentities($username)</a></p>";

        if (!is_null($image_path)) {
            echo "<img src='$image_path' alt='$title' class='detail-image'><br>";
        }
    
        if (strlen($link) > 0) {
            echo "<a href='$link'>Read more</a>";
        }

        echo "<p>$body</p>";

        $stmt->close();

        //display comments
        $stmt_comments = $mysqli->prepare("SELECT comments.comment_id, users.username, comments.comment FROM comments JOIN users ON users.id = comments.id WHERE comments.post_id = ? ORDER BY timestamp DESC");

        if (!$stmt_comments) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt_comments->bind_param("i", $post_id);
        $stmt_comments->execute();

        $stmt_comments->bind_result($comment_id, $commenter_username, $comment);
        echo "<h3>Comments</h3>";
        while ($stmt_comments->fetch()) {
            echo "<p><strong>$commenter_username:</strong> $comment</p>";
            if (isset($_SESSION['logged_in'])){
                if ($_SESSION['username'] === $commenter_username) {
                    echo "<form action='delete-comment.php' method='post'>";
                    echo "<input type='hidden' name='comment_id' value='$comment_id'>";
                    echo "<input type='hidden' name='title' value='$title'>";
                    echo "<input type='submit' value='Delete'>";
                    echo "</form>";
                }
            }
        }

        $stmt_comments->close();

        //comment form
        if (isset($_SESSION['logged_in'])) {
            echo "<h3>Add a Comment</h3>";
            echo "<form action='upload-comment.php' method='post'>";
            echo "<input type='hidden' name='post_id' value='$post_id'>";
            echo "<input type='hidden' name='title' value='$title'>";
            echo "<textarea name='comment' placeholder='Enter your comment...' required></textarea><br>";
            echo "<input type='submit' value='Submit'>";
            echo "</form>";
        } else {
            echo "<p>Please log in to leave a comment.</p>";
        }

        ?>
    </div>
</body>
</html>
