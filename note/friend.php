<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Friends</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.5.1/dist/css/foundation.min.css" integrity="sha256-1mcRjtAxlSjp6XJBgrBeeCORfBp/ppyX4tsvpQVCcpA= sha384-b5S5X654rX3Wo6z5/hnQ4GBmKuIJKMPwrJXn52ypjztlnDK2w9+9hSMBz/asy9Gw sha512-M1VveR2JGzpgWHb0elGqPTltHK3xbvu3Brgjfg4cg5ZNtyyApxw/45yHYsZ/rCVbfoO5MSZxB241wWq642jLtA==" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.5.1/dist/js/foundation.min.js" integrity="sha256-WUKHnLrIrx8dew//IpSEmPN/NT3DGAEmIePQYIEJLLs= sha384-53StQWuVbn6figscdDC3xV00aYCPEz3srBdV/QGSXw3f19og3Tq2wTRe0vJqRTEO sha512-X9O+2f1ty1rzBJOC8AXBnuNUdyJg0m8xMKmbt9I3Vu/UOWmSg5zG+dtnje4wAZrKtkopz/PEDClHZ1LXx5IeOw==" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        session_start();
        if(!isset($_SESSION['userid'])) {
            header('Location:index.php');
        }
        include 'handle/db.php';
        // fetch the top 3 pending requests to be responded of the current user 
        // $username = $_SESSION['username'];
        // $useridRes = $mysqli->query("select userid from user where username='$username'");
        // $userid = $useridRes->fetch_assoc()['userid'];
        $userid = $_SESSION['userid'];
        $query = "select fromid, username from friendRequest join user on friendRequest.fromid = user.userid where toid='$userid' and answer = 0";
        $friendReqs = $mysqli->query($query);
        $allFriends = $mysqli->query("select user.userid, user.username from friendship left join user on friendship.friendid = user.userid where friendship.userid=$userid");
        $numOfFriends = mysqli_num_rows($allFriends);
    ?>
    <div class="container" id="friend_page">
        <div class="grid-x">
            <div class="cell medium-7 small-offset-2">
                <input type="text" placeholder="" name="searchbox" id="search-field"/>
            </div>
            <div class="cell medium-1">
                <button onClick=searchFriend() type="button" class="button expanded">Search</button>
            </div>
        </div>
        <div class="grid-x">
            <div class="card cell medium-8 small-offset-2">
                <div class="card-divider grid-x">
                    <h6 class="cell small-4 text-left">Pending Requests</h6>
                    <div class="cell small-6"></div>
                    <a href="mng-friend-inv.php" class="cell small-2 text-right text-bottom"><h6>Manage All</h6></a>
                </div>
                <div class="card-section grid-x">
                    <?php
                        while($row = $friendReqs->fetch_assoc()) {
                            echo "<div class='cell small-12' id='req".$row['fromid']."'>";
                            echo "  <div class='grid-x'>";
                            echo "      <div class='cell small-8 text-left' id='".$row['fromid']."'>".$row['username']."</div>";
                            echo "      <div class='cell auto'>";
                            echo "          <div class='grid-x'>";
                            echo "              <div class='cell medium-3'></div>";
                            echo "              <button onClick='decline(".$row['fromid'].",".$userid.") 'class='cell medium-4 button secondary' type='button' id='decline"
                                                .$row['fromid']."'>Decline</button>";
                            echo "              <div class='cell auto'></div>";
                            echo "              <button onClick='accept(".$row['fromid'].",".$userid.") 'class='cell medium-4 hollow button' type='button' id='accept"
                                                .$row['fromid']."'>Accept</button>";
                            echo "</div></div></div></div>";
                        }
                    ?>
                    <!-- <div class="cell small-12">
                        <div class="grid-x">
                            <div class="cell small-8 text-left" id="fromid">Crystal</div>
                            <div class="cell auto">
                                <div class="grid-x">
                                    <div class="cell medium-3"></div>
                                    <button class="cell medium-4 button secondary" type="button" id="decline01">Decline</button>
                                    <div class="cell auto"></div>
                                    <button class="cell medium-4 hollow button" type="button" id="accept01">Accept</button>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="cell auto"></div>
        </div>
        <div class="grid-x">
            <div class="card cell medium-8 small-offset-2">
                <div class="card-divider grid-x">
                    <?php echo "<h6 class='cell small-4 text-left'>".$numOfFriends." Friends</h6>"; ?>
                </div>
                <div class="card-section grid-x">
                    <?php
                        while ($row=$allFriends->fetch_assoc()) {
                            echo "<div class='cell small-12'>";
                            echo "  <div class='cell small-8 text-left'>".$row['username']."</div>";
                            echo "</div>";
                        }
                    ?>
                </div>
            </div>
        </div>
        <script>
            function searchFriend() {
                var search_key = document.getElementById("search-field").value;
                window.location = "friend-search.php?key="+search_key;
            }

            function decline(fromid, toid) {
                // alert(fromid);
                var paras  = {
                    functionname: "declineFriendRequest",
                    fromid: fromid,
                    toid: toid
                };
                // delete request from db
                $.ajax({
                    type: "POST",
                    url: "handle/friendHandler.php",
                    data: JSON.stringify(paras),
                    contentType: "application/json",
                    dataType: "json", 
                    success: function(result) {
                        console.log(result);
                        var row = document.getElementById("req"+fromid);
                        while(row.firstChild){
                            row.removeChild(row.firstChild);
                        }
                    },
                    error: function(requestObject, error, errorThrown){
                        console.log("Error with Ajax Post Request:" + error);
                        console.log(errorThrown);
                    }
                });
            }

            function accept(fromid, toid) {
                var paras = {
                    functionname: "acceptFriendRequest",
                    fromid: fromid,
                    toid: toid
                };
                $.ajax({
                    type: "POST",
                    url: "handle/friendHandler.php",
                    contentType: "application/json",
                    dataType: "json",
                    data: JSON.stringify(paras), 
                    success: function(result) {
                        var row = document.getElementById("req"+fromid);
                        while(row.firstChild){
                            row.removeChild(row.firstChild);
                        }
                    },
                    error: function(requestObject, error, errorThrown){
                        console.log("Error with Ajax Post Request:" + error);
                        console.log(errorThrown);
                    }
                });
            }
        </script>
    </div>
</body>
</html>