<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Search Friends</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.5.1/dist/css/foundation.min.css" integrity="sha256-1mcRjtAxlSjp6XJBgrBeeCORfBp/ppyX4tsvpQVCcpA= sha384-b5S5X654rX3Wo6z5/hnQ4GBmKuIJKMPwrJXn52ypjztlnDK2w9+9hSMBz/asy9Gw sha512-M1VveR2JGzpgWHb0elGqPTltHK3xbvu3Brgjfg4cg5ZNtyyApxw/45yHYsZ/rCVbfoO5MSZxB241wWq642jLtA==" crossorigin="anonymous">
    <script class="jsbin" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.5.1/dist/js/foundation.min.js" integrity="sha256-WUKHnLrIrx8dew//IpSEmPN/NT3DGAEmIePQYIEJLLs= sha384-53StQWuVbn6figscdDC3xV00aYCPEz3srBdV/QGSXw3f19og3Tq2wTRe0vJqRTEO sha512-X9O+2f1ty1rzBJOC8AXBnuNUdyJg0m8xMKmbt9I3Vu/UOWmSg5zG+dtnje4wAZrKtkopz/PEDClHZ1LXx5IeOw==" crossorigin="anonymous"></script>
</head>
<body>
<?php 
    session_start();
    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        header('location:index.php');
    }
    include 'handle/db.php';
    $username = $_SESSION['username'];
    // $useridRes = $mysqli->query("select userid from user where username='$username'");
    // $userid = $useridRes->fetch_assoc()['userid'];
    $userid = $_SESSION['userid'];
    $search_key = $_GET['key'];
    $query = "select userid, username, "
            ."if (userid in (select friendid from friendship where friendship.userid=$userid), 'true', 'false') as isfriend "
            ."from user where username like '%$search_key%'";
    $searched_friends = $mysqli->query($query);
    $numOfFriends = mysqli_num_rows($searched_friends);
?>
<div class="container grid-y-margin" id="friend_search_page">
        <!-- <div class="grid-x">
            <div class="cell medium-7 small-offset-2">
                <input type="text" placeholder="" name="searchbox"/>
            </div>
            <div class="cell medium-1">
                <button type="button" class="button expanded">Search</button>
            </div>
        </div> -->
        <div class="grid-x">
            <div class="card cell medium-8 small-offset-2">
                <div class="card-divider grid-x">
                    <?php 
                        echo "<h6 class='cell small-4 text-left'>Showing ".$numOfFriends." results</h6>";
                    ?>
                    <!-- <h6 class="cell small-4 text-left">Showing XXX results</h6> -->
                </div>
                <div class="card-section grid-x">
                    <?php
                        while ($row = $searched_friends->fetch_assoc()) {
                            echo "<div class='cell small-12' id='friend".$row['userid']."'>";
                            echo "  <div class='grid-x'>";
                            echo "      <div class='cell small-8 text-left id='name".$row['userid']."'>".$row['username']."</div>";
                            if ($row['isfriend'] == 'false') {
                                echo "<div class='cell auto'></div>";
                                echo "<div class='cell small-2'>";
                                echo "  <button onClick=addFriend(".$userid.",".$row['userid'].") class='hollow button expanded' type='button' id=".$row['userid'].">Add</button>";
                                echo "</div>";
                            }
                            echo "</div></div>";
                        } 
                    ?>
                    <!-- <div class="cell small-12" id="friend01">
                        <div class="grid-x">
                            <div class="cell small-8 text-left" id="name01">Adam</div>
                            <div class="cell auto"></div>
                            <div class="cell small-2">
                                <button class="hollow button expanded" type="button">Add</button>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="cell auto"></div>
        </div>
    </div>
    <script>
        function addFriend(fromid, toid) {
            var data = {
                functionname: "addFriendReq",
                fromid: fromid, 
                toid: toid
            };
            $.ajax({
                type: "POST",
                url: "handle/friendHandler.php",
                contentType: "application/json",
                dataType: "json",
                data: JSON.stringify(data),
                success: function(result) {
                    //request sent successfully, disable button
                    document.getElementById(toid).disabled = true;
                }, 
                error: function(requestObject, error, errorThrown){
                        console.log("Error with Ajax Post Request:" + error);
                        console.log(errorThrown);
                }
            });
        }
    </script>
</body>
</html>