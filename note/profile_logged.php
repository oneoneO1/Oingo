<?php
session_start();
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: home.php");
}
	$userid = $_SESSION['userid'];
	$servername = "localhost:3306";
    $server_username = "root";
    $server_password = "Wwy1234567890";
    $db = "note";
    $mysqli = new mysqli($servername, $server_username, $server_password, $db);
    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_error);
        exit();
    }
    $s = $mysqli->prepare("select gender,birthday,description from profile where userid=?");
    $s->bind_param("i",$userid);
    $s->execute();
    $r = $s->get_result();
    $row = $r->fetch_assoc();
    $gender = "not set";
    $birthday = "not set";
    $description = "not set";
    if (!($row === null)) {
    	$gender = $row['gender'];
    	$birthday = $row['birthday'];
    	$description = $row['description'];
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
            <h3>Profile</h3>
            <p>Gender: <?php echo $gender ?></p>
            <p>Birthday: <?php echo $birthday?></p>
            <p>Description: <?php echo $description?></p>
            <div style="text-align: center;">
            	<a href="edit_profile.php" class="button" style="display: inline-block;margin-top: 30px;width: 30%">Edit</a>
            </div>   
        </div>
    </div>
</body>