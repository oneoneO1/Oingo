<?php 
    header('Content-Type: application/json');
    include 'db.php';
    $jsonData = json_decode(file_get_contents('php://input'));
    $tagsarr = explode(",",$jsonData->{'tags'});
    $tags = "";
    for ($i=0;$i<count($tagsarr);$i++){
        $tags = $tags."'".$tagsarr[$i]."'";
        if ($i != count($tagsarr)-1) {
            $tags = $tags.",";
        }
    }
    $timeFrom = $jsonData->{'timeFrom'};
    $timeTo = $jsonData->{'timeTo'};
    $scope = $jsonData->{'scope'};
    $latitude = $jsonData->{'latitude'};
    $longitude = $jsonData->{'longitude'};
    $radius = $jsonData->{'radius'};
    if ($scope == 1){
        filterAnyone($tags, $timeFrom, $timeTo, $latitude, $longitude, $radius);
    } else {
        filterFriend($tags, $timeFrom, $timeTo, $latitude, $longitude, $radius);
    }

    function filterAnyone($tags, $timeFrom, $timeTo, $latitude, $longitude, $radius) {
        #search for anyone's notes-> scope='everyone'
        #result = notes visible to everyone + notes visible to friends
        global $mysqli;
        session_start();
        $username = $_SESSION['username'];
        $qNotes="select note.*, GROUP_CONCAT(t1.tagname SEPARATOR ',') as taglist from note left join schedule s on note.scheduleid = s.scheduleid, note2tag as nt left join tag as t1 on nt.tagid=t1.tagid "
                ."where note.noteid in (select distinct noteid from note2tag left join tag on note2tag.tagid = tag.tagid where tagname in ($tags)) "
                ."and scope=1 "
                ."and (note.latitude-$latitude)*(note.latitude-$latitude)+(note.longitude-$longitude)*(note.longitude-$longitude)<=$radius*$radius "
                ."and '$timeFrom'<=s.timeTo && '$timeTo'>=s.timeFrom "
                ."and note.noteid=nt.noteid group by note.noteid "
                ."union all "
                ."select note.*, GROUP_CONCAT(t2.tagname SEPARATOR ',') as taglist from note left join schedule s on note.scheduleid = s.scheduleid, (select friendid from friendship left join user on friendship.userid=user.userid where username='$username') as myfriends, "
                ."note2tag as nt2 left join tag as t2 on nt2.tagid=t2.tagid "
                ."where note.noteid in (select distinct noteid from note2tag left join tag on note2tag.tagid = tag.tagid where tagname in ($tags))"
                ."and note.userid=myfriends.friendid and scope=0 "
                ."and (latitude-$latitude)*(latitude-$latitude)+(longitude-$longitude)*(longitude-$longitude)<=$radius*$radius "
                ."and '$timeFrom'<=timeTo and '$timeTo'>=timeFrom "
                ."and note.noteid=nt2.noteid group by note.noteid;";
        // echo $qNotes;
        $result = $mysqli->query($qNotes);
        $jsonResult = array();
        while($row = $result->fetch_assoc()){
            $jsonResult[] = $row;
        }
        echo json_encode($jsonResult);
    }

    function filterFriend($tags, $timeFrom, $timeTo, $latitude, $longitude, $radius) {
        global $mysqli;
        session_start();
        $username = $_SESSION['username'];
        $qNotes="select note.* from note left join schedule s on note.scheduleid = s.scheduleid, (select friendid from friendship left join user on friendship.userid=user.userid where username='$username') as myfriends "
                ."where noteid in (select distinct noteid from note2tag left join tag on note2tag.tagid = tag.tagid where tagname in ($tags))"
                ."and note.userid=myfriends.friendid "
                ."and (latitude-$latitude)*(latitude-$latitude)+(longitude-$longitude)*(longitude-$longitude)<=$radius*$radius "
                ."and '$timeFrom'<=timeTo and '$timeTo'>=timeFrom";
        $result = $mysqli->query($qNotes);
        $jsonResult = array();
        while($row=$result->fetch_assoc()) {
            $jsonResult[] = $row;
        }
        echo json_encode($jsonResult);
    }
?>