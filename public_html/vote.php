<?php
include ('conn/connData.txt');

$musicid = $_GET['musicid'];
$id = $_GET['id'];
$mysqli = new mysqli($server, $user, $pass, $dbname, $port);

$stmt = $mysqli->prepare("INSERT INTO comments(userid,musicid) VALUES (?,?)");


if (!($stmt)) {
	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

if(!$stmt->bind_param("si", $id, $musicid)){
	echo "Bind error";
}

if(!$stmt->execute()){
	echo "Execute failed: (". $stmt->errno .")".$stmt->error;
}
$stmt->close();
$mysqli->close();
header("location:music.php?id=".$id);

?>