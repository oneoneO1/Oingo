<?php
session_start();
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('location:home.php');
}

if (isset($_POST['content'])) {
    $userid = $_SESSION['userid'];

    // $servername = "localhost:3306";
    // $server_username = "root";
    // $server_password = "Wwy1234567890";
    // $db = "note";
    // $mysqli = new mysqli($servername, $server_username, $server_password, $db);
    // if ($mysqli->connect_errno) {
    //     printf("Connect failed: %s\n", $mysqli->connect_error);
    //     exit();
    // }
    include 'handle/db.php';

    $time = date('Y-m-d H:i:s');
    $date = date('Y-m-d');
    $s = $mysqli->prepare("insert into schedule(sdate,timeFrom,timeTo,numberFrom,numberTo,isRepeat) values(?,?,?,?,?,?)");
    $s->bind_param('sssiii',$date,$_POST['timeFrom'],$_POST['timeTo'],$_POST['numberFrom'],$_POST['numberTo'],$_POST['repeat']);
    $s->execute();
    $scheduleId = $s->insert_id;
    $s = $mysqli->prepare("insert into note(time,latitude,longitude,radius,content,scheduleid,scope,allow_comment,userid) value(?,?,?,?,?,?,?,?,?)");
    $s->bind_param('sddisiiii', $time,$_POST['lat'],$_POST['lng'],$_POST['radius'],$_POST['content'],$scheduleId,$_POST['scope'],$_POST['comment'],$userid);
    $s->execute();

    $nid = $s->insert_id;
    $tag_arr = explode(",", $_POST['tags']);
    $s = $mysqli->prepare("insert into tag(tagname) value(?)");
    $s2 = $mysqli->prepare("select tagid from tag where tagname = ?");
    $s3 = $mysqli->prepare("insert into note2tag(noteid,tagid) value(?,?)");
    for($i = 0; $i < count($tag_arr); $i++) {
    	$t = $tag_arr[$i];
    	$s2->bind_param('s',$t);
    	$s2->execute();
    	$r2 = $s2->get_result();
    	$row = $r2->fetch_assoc();
    	if( $row === null) {
    		$s->bind_param('s',$t);
    		$s->execute();
    		$tid = $s->insert_id;
    		$s3->bind_param("ii",$nid,$tid);
    		$s3->execute();
    	} else {
    		$tid = $row['tagid'];
    		$s3->bind_param('ii',$nid,$tid);
    		$s3->execute();
    	}
    }
    
    $s->close();
    $s2->close();
    $s3->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lag="en">
<head>
    <meta charset="UTF-8"/>
    <title>Success</title>
    <meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="add successfully">
    <meta name="author" content="Wangyue Wang">

    <!-- Compressed CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.5.1/dist/css/foundation.min.css" integrity="sha256-1mcRjtAxlSjp6XJBgrBeeCORfBp/ppyX4tsvpQVCcpA= sha384-b5S5X654rX3Wo6z5/hnQ4GBmKuIJKMPwrJXn52ypjztlnDK2w9+9hSMBz/asy9Gw sha512-M1VveR2JGzpgWHb0elGqPTltHK3xbvu3Brgjfg4cg5ZNtyyApxw/45yHYsZ/rCVbfoO5MSZxB241wWq642jLtA==" crossorigin="anonymous">

    <!-- Compressed JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.5.1/dist/js/foundation.min.js" integrity="sha256-WUKHnLrIrx8dew//IpSEmPN/NT3DGAEmIePQYIEJLLs= sha384-53StQWuVbn6figscdDC3xV00aYCPEz3srBdV/QGSXw3f19og3Tq2wTRe0vJqRTEO sha512-X9O+2f1ty1rzBJOC8AXBnuNUdyJg0m8xMKmbt9I3Vu/UOWmSg5zG+dtnje4wAZrKtkopz/PEDClHZ1LXx5IeOw==" crossorigin="anonymous"></script>
</head>
<body>
    <div class="grid-x">
        <div class="cell large-4 small-4 large-offset-4" style="margin-top: 20px" style="text-align: center">
            <h4>Add Successfully!</h4>
            <a href="home.php">return</a>
        </div>
    </div>
</body>