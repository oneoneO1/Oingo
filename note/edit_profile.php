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
    $gender = "";
    $birthday = "";
    $description = "";
    if (!($row === null)) {
        $gender = $row['gender'];
        $birthday = $row['birthday'];
        $description = $row['description'];
    }

    if (isset($_POST['description'])) {
        if ($row === null) {
            $s = $mysqli->prepare("insert into profile(userid,gender,birthday,description) value(?,?,?,?)");
            $s->bind_param("isss",$userid,$_POST['gender'],$_POST['birthday'],$_POST['description']);
            $s->execute();
            header("location: profile_logged.php");
        } else {
            $s = $mysqli->prepare("update profile set gender = ?, birthday = ?, description = ? where userid = ?");
            $s->bind_param("sssi",$_POST['gender'],$_POST['birthday'],$_POST['description'],$userid);
            $s->execute(); 
            header("location:profile_logged.php");
        }        
    }
?>

<!DOCTYPE html>
<html lag="en">
<head>
    <meta charset="UTF-8"/>
    <title>Edit Profile</title>
    <meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="edit profile">
    <meta name="author" content="Wangyue Wang">

    <!-- Compressed CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/foundation/6.0.6/foundation.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.0/css/foundation-datepicker.min.css">

    <!-- Compressed JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/foundation/6.0.6/foundation.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.0/js/foundation-datepicker.min.js"></script>
    <script type="text/javascript" src="moment.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAADtFA_1sG5GvDFYP6oTTCdZAPHfFLteo&libraries=places"></script>
</head>
<body>
    <div class="grid-x">
        <div class="cell large-4 small-4 large-offset-4" style="margin-top: 20px">
            <h3>Edit Profile</h3>
            <form method="POST">
                <?php 
                if($gender === "male") {
                    echo 'Gender: <input type="radio" name="gender" value="male" checked="checked"> Male ';
                    echo '<input type="radio" name="gender" value="female"> Female ';
                    echo '<input type="radio" name="gender" value="other"> Other <br/>';
                } else if($gender === "female") {
                    echo 'Gender: <input type="radio" name="gender" value="male" > Male ';
                    echo '<input type="radio" name="gender" value="female" checked="checked"> Female ';
                    echo '<input type="radio" name="gender" value="other"> Other <br/>';
                } else {
                    echo 'Gender: <input type="radio" name="gender" value="male" > Male ';
                    echo '<input type="radio" name="gender" value="female"> Female ';
                    echo '<input type="radio" name="gender" value="other" checked="checked"> Other <br/>';
                }
                echo 'Birthday: <input id="dp" type="text" name="birthday" readonly="readonly" value="'.$birthday.'"><br/>';
                echo 'Description: <textarea rows="6" cols="100" name="description" style="resize: none;" value="'.$description.'"></textarea>';
                ?>
                <div style="text-align: center;">
                    <input type="submit" value="Save" class="button" style="display: inline-block;width: 20%">
                </div>
            </form> 
        </div>
    </div>
</body>

<script>
$(function(){
    $('#dp').fdatepicker({
        initialDate: '02-12-1989',
        format: 'mm-dd-yyyy',
        disableDblClickSelection: true,
        leftArrow:'<<',
        rightArrow:'>>',
        closeIcon:'X',
        closeButton: true
    });
});
</script>