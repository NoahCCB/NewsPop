<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Post a Story</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div class="main">
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <div>
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div>
                    <label for="body">Body:</label>
                    <textarea id="body" name="body"></textarea>
                </div>
                <div>
                    <label for="link">Link:</label>
                    <input type="url" id="link" name="link">
                </div>
                <div>
                    <label for="image">Image:</label>
                    <input type="file" id="image" name="image">
                </div>
                <div>
                    <input type="submit" value="Submit">
                </div>
            </form>
        </div>
    </body>
</html>