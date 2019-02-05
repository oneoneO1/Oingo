<?php 
    header('Content-Type: application/json');
    include 'db.php';

    function declineFriendRequest($fromid, $toid) {
        global $mysqli;
        $mysqli->query("delete from friendRequest where fromid=$fromid and toid=$toid and answer=0");
    }

    function acceptFriendRequest($fromid, $toid) {
        global $mysqli;
        // update friendRequest
        $mysqli->query("update friendRequest set answer=1 where fromid=$fromid and toid=$toid");
        //add friendship
        $mysqli->query("insert into friendship values($fromid, $toid)");
        $mysqli->query("insert into friendship values($toid, $fromid)");
    }

    function addFriendReq($fromid, $toid) {
        global $mysqli;
        $mysqli->query("insert into friendRequest values ($fromid, $toid, 0)");
    }
    $jsonData = json_decode(file_get_contents('php://input'));
    switch($jsonData->{'functionname'}) {
        case 'declineFriendRequest':
            declineFriendRequest($jsonData->{'fromid'}, $jsonData->{'toid'});
            echo json_encode(array('error' => FALSE, 'message' => 'decline successfully'));
            break;
        case 'acceptFriendRequest': 
            acceptFriendRequest($jsonData->{'fromid'}, $jsonData->{'toid'});
            echo json_encode(array('error' => FALSE, 'message' => 'accept successfully'));
            break;
        case 'addFriendReq': 
            addFriendReq($jsonData->{'fromid'}, $jsonData->{'toid'});
            echo json_encode(array('error' => FALSE, 'message' => 'request sent successfully'));
            break;
        default:
            echo json_encode(array('error' => TRUE, 'message' => 'not found function name'));
            break;
    }
    exit();
?>