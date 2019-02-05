<?php
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
	header("location:home_logged.php");
}else{
    echo "Please login first. <a href='index.php'>Click here</a>";
}
?>
