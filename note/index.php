<?php
include 'handle/db.php';
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    header('location:home.php');
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // $servername = "localhost:3306";
    // $server_username = "root";
    // $server_password = "Wwy1234567890";
    // $db = "note";
    // $mysqli = new mysqli($servername, $server_username, $server_password, $db);
    // if ($mysqli->connect_errno) {
    //     printf("Connect failed: %s\n", $mysqli->connect_error);
    //     exit();
    // }
    $s = $mysqli->prepare("select username,pwd,userid from user where email = ?");
    $s->bind_param('s', $email);
    $s->execute();
    $r = $s->get_result();
    $row = $r->fetch_assoc();
    var_dump($row);
    echo $password;
    if (!($row === null) && $password == $row['pwd']) {
        $username = $row['username'];
        $userid = $row['userid'];
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['userid'] = $userid;

        //redirect to home page
        header('location:home.php');
    }else{
        echo "<script>alert('Check username and password again!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lag="en">
<head>
    <meta charset="UTF-8"/>
    <title>login</title>
    <meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="login">
    <meta name="author" content="Wangyue Wang">

    <!-- Compressed CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.5.1/dist/css/foundation.min.css" integrity="sha256-1mcRjtAxlSjp6XJBgrBeeCORfBp/ppyX4tsvpQVCcpA= sha384-b5S5X654rX3Wo6z5/hnQ4GBmKuIJKMPwrJXn52ypjztlnDK2w9+9hSMBz/asy9Gw sha512-M1VveR2JGzpgWHb0elGqPTltHK3xbvu3Brgjfg4cg5ZNtyyApxw/45yHYsZ/rCVbfoO5MSZxB241wWq642jLtA==" crossorigin="anonymous">

    <!-- Compressed JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.5.1/dist/js/foundation.min.js" integrity="sha256-WUKHnLrIrx8dew//IpSEmPN/NT3DGAEmIePQYIEJLLs= sha384-53StQWuVbn6figscdDC3xV00aYCPEz3srBdV/QGSXw3f19og3Tq2wTRe0vJqRTEO sha512-X9O+2f1ty1rzBJOC8AXBnuNUdyJg0m8xMKmbt9I3Vu/UOWmSg5zG+dtnje4wAZrKtkopz/PEDClHZ1LXx5IeOw==" crossorigin="anonymous"></script>
</head>
<body>
    <div class="grid-x">
        <div class="cell large-4 small-4 large-offset-4" style="margin-top: 20px">
            
            <div>
            <h3 style="font-weight: bold;margin-bottom: 20px ;display: inline-block;">Login</h1>  
            <a href="signup.php" style="float: right;">Sign up</a>      
            </div>
            
            <form method="POST">
                email: <input type="text" name="email"><br/>
                password: <input type="password" name="password"><br/>
                <div style="text-align: center;">
                    <input type="submit" value="Login" class="button" style="display: inline-block;">
                </div>
            </form>
        </div>
    </div>
</body>