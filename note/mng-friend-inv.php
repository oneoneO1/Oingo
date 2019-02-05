<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mange Friend Invitations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.5.1/dist/css/foundation.min.css" integrity="sha256-1mcRjtAxlSjp6XJBgrBeeCORfBp/ppyX4tsvpQVCcpA= sha384-b5S5X654rX3Wo6z5/hnQ4GBmKuIJKMPwrJXn52ypjztlnDK2w9+9hSMBz/asy9Gw sha512-M1VveR2JGzpgWHb0elGqPTltHK3xbvu3Brgjfg4cg5ZNtyyApxw/45yHYsZ/rCVbfoO5MSZxB241wWq642jLtA==" crossorigin="anonymous">
    <script class="jsbin" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.5.1/dist/js/foundation.min.js" integrity="sha256-WUKHnLrIrx8dew//IpSEmPN/NT3DGAEmIePQYIEJLLs= sha384-53StQWuVbn6figscdDC3xV00aYCPEz3srBdV/QGSXw3f19og3Tq2wTRe0vJqRTEO sha512-X9O+2f1ty1rzBJOC8AXBnuNUdyJg0m8xMKmbt9I3Vu/UOWmSg5zG+dtnje4wAZrKtkopz/PEDClHZ1LXx5IeOw==" crossorigin="anonymous"></script>
</head>
<body>
    <script>
        $(document).ready(function() {
            $(document).foundation();
        })
    </script>
    <?php
        include 'handle/db.php';
        session_start();
        $username = $_SESSION['username'];
        $userid = $_SESSION['userid'];
        #invitations the user has sent
        $sentQuery = "select user.username from friendRequest left join user on friendRequest.toid=user.userid where fromid=$userid";
        $sent = $mysqli->query($sentQuery);
        #invitations received
        $rcvdQuery = "select friendRequest.fromid, user.username, answer from friendRequest left join user on friendRequest.fromid=user.userid where toid=$userid";
        $rcvd = $mysqli->query($rcvdQuery);
    ?>
    <div class="container" id="mng_friend_page">
        <div class="grid-x">
            <div class="cell medium-7 small-offset-2">
                <ul class="tabs" data-tabs id="mng-friend-tab">
                    <li class="tabs-title is-active"><a href="#panel1" aria-selected="true">Received</a></li>
                    <li class="tabs-title"><a href="#panel2">Sent</a></li>
                </ul>

                <div class="tabs-content" data-tabs-content="mng-friend-tab">
                    <div class="tabs-panel is-active" id="panel1">
                        <?php
                            while($row=$rcvd->fetch_assoc()) {
                                echo "<div class='cell small-12' id='rcvd".$row['fromid']."'>";
                                echo "  <div class='grid-x'>";
                                echo "      <div class='cell small-8 text-left' id='".$row['fromid']."'>".$row['username']."</div>";
                                echo "      <div class='cell auto'>";
                                if ($row['answer'] == 0) {
                                    echo "          <div class='grid-x'>";
                                    echo "              <div class='cell medium-3'></div>";
                                    echo "              <button onClick='decline(".$row['fromid'].",".$userid.") 'class='cell medium-4 button secondary' type='button' id='decline"
                                                        .$row['fromid']."'>Decline</button>";
                                    echo "              <div class='cell auto'></div>";
                                    echo "              <button onClick='accept(".$row['fromid'].",".$userid.") 'class='cell medium-4 hollow button' type='button' id='accept"
                                                        .$row['fromid']."'>Accept</button></div>";
                                }
                                echo "</div></div></div>";
                            }
                        ?>
                        <!-- <div class="grid-x">
                            <div class="cell small-12" id="invatation01">
                                <div class="grid-x">
                                    <div class="cell small-8 text-left">Adam</div>
                                    <div class="cell auto">
                                        <div class="grid-x">
                                            <div class="cell medium-3"></div>
                                            <button class="cell medium-4 button secondary" type="button">Decline</button>
                                            <div class="cell auto"></div>
                                            <button class="cell medium-4 hollow button" type="button">Accept</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <!-- sent invitations -->
                    <div class="tabs-panel" id="panel2">
                        <?php
                            while($row=$sent->fetch_assoc()) {
                                echo "<div class='grid-x>";
                                echo "  <div class='cell small-12'>";
                                echo "      <div class='grid-x'>";
                                echo "          <div class='cell small-8 text-left>".$row['username']."</div>";
                                echo "</div></div></div>";
                            }
                        ?>
                        <!-- <div class="grid-x">
                            <div class="cell small-12" id="sent01">
                                <div class="grid-x">
                                    <div class="cell small-8 text-left">Crystal</div>
                                    <div class="cell auto"></div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
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
                        var row = document.getElementById("rcvd"+fromid);
                        while(row.firstChild) {
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
                        var row = document.getElementById("rcvd"+fromid);
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

</body>
</html>