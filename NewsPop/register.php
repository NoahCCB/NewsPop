<?php
    require 'database.php';

    if(isset($_POST['user']) && isset($_POST['password'])){
        //trim and cast username to ensure that it is a string with no white space
        $username = strval(trim($_POST['user']));
        $password = strval(trim($_POST['password']));
    
        $password_s = password_hash($password, PASSWORD_DEFAULT);
        $user = strtolower($username);

        $userFound = false;
        $check = $mysqli->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
        $check->bind_param('s', $user);
        //do check
        $check->execute();
        $check->bind_result($result);
        //see result
        $check->fetch();
        //close
        $check->close();
        //check if the user exists
        if ($result > 0) {
            $userFound = true;
            printf("<h1 class='error'>Username is Taken</h1>");
            exit;
        }

        $add = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

        if (!$add){
            printf("<h1 class='error'>User Registration Failed: %s\n </h1>", $mysqli->error);
            exit;
        }

        $add->bind_param('ss', $user, $password_s);
        $add->execute();
        $add->close();

        header("Location: login.php");
        exit();
    }
?>