<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <?php
    require 'database.php';
    session_start();
    if(isset($_POST['user']) && isset($_POST['password'])){
        //trim and cast username to ensure that it is a string with no white space
        $username = strval(trim($_POST['user']));
        $password = strval(trim($_POST['password']));

        $stmt = $mysqli->prepare("SELECT COUNT(*), id, password FROM users WHERE username=?");

        $stmt->bind_param('s', $username);
        $stmt->execute();

        $stmt->bind_result($cnt, $id, $pwd_hash);
        $stmt->fetch();

        $pwd_guess = $_POST['password'];
        // Compare the submitted password to the actual password hash
    
        if($cnt == 1 && password_verify($pwd_guess, $pwd_hash)){
	        // Login succeeded!
	        $_SESSION['id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            header("Location: home.php");
            exit();
        } else {
            header("Location: login.php");
            exit();
        }
    }
    ?>
    <body>
        <div class="container">
            <form action="login.php" method="post">
                <h1>Login</h1>
                <div class="form-group">
                    <label for="user">User Name:</label>
                    <input type="text" id="user" name="user" placeholder="Enter your username">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password">
                </div>
                <input type="submit" value="Submit" class="btn-submit">
                <br>
                <a href="register.html" class="btn-register">Register</a>
                <br><br>
                <a href="home.php" class="btn-register" onclick="<?php session_start(); $_SESSION['guest'] = true; ?>">Continue as guest</a>
            </form>
        </div>
    </body>
</html>