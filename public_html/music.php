<?php
include('conn/connData.txt');
$id = $_GET["id"];
if(strcmp($id,'ilovechina@uoregon.edu') == 0){
	header("location:admin.php");
}

//---------------------------------------------set up the connection with mysql
$mysqli = new mysqli($server, $user, $pass, $dbname, $port);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

function findComment($musicid)
{
	global $mysqli;
	$userid=NULL;
	$message=NULL;
	$time=NULL;
	$sql = 'select userid, message,time from message where musicid='.$musicid;

	//create statement
	$stmt=$mysqli->prepare($sql);
	//execute query
	$stmt->execute();
	//bind result
	$stmt->bind_result($userid, $message, $time);
	While($stmt->fetch())
	{
		echo '<hr>';
		echo '<div class="text" > <b>' .$userid.'</b> : '. $time .'</a> <br>';
		echo '<div class="text" > ' .$message.'</a> <br>';
		
	}
	$stmt->close();
}

$voted=array();
$votedid=null;
$sql = 'select musicid from comments where userid=?';
$stmt= $mysqli->prepare($sql);

if(!$stmt){
	echo "Prepared failed:(".$mysqli->error.")". $mysqli->error;
}
if(!$stmt->bind_param('s', $id)){
	echo "Binding parameter failed: (" . $stmt->errno . ") " . $stmt->error;
}
if(!$stmt->execute()){
	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
if(!$stmt->bind_result($votedid)){
	echo "Bind result failed";
}
while($stmt->fetch()){
	array_push($voted, $votedid);
}

function isVoted($var){
	global $voted;
	return in_array($var, $voted);
}

//search from database to grab all the information about one music

$sql = 'select musicid, name, singer, filename from music';
$musicid=NULL;
$name=NULL;
$singer=NULL;
$filename=NULL;

$stmt= $mysqli->prepare($sql);

if(!$stmt){
	echo "Prepared failed:(".$mysqli->error.")". $mysqli->error;
}

if(!$stmt->execute()){
	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
if(!$stmt->bind_result($musicid, $name, $singer, $filename)){
	echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
}
?>
<html>
<head>
<title>Music Evaluation</title>
<link href="css/music.css" rel="stylesheet" type="text/css" />
<link href="css/modal.css" rel="stylesheet" type="text/css" />

<style type="text/css">
    tr:hover {background-color: Red;}
</style>

<script type="text/javascript">
function setValue(musicid, id){
	var a = document.getElementById('vote'); //or grab it by tagname etc
	a.href = "vote.php?id=" + id + "&&musicid=" + musicid;
}
function popup(musicid){
	document.getElementById("key").value=musicid;
	document.getElementById("comments").submit();
	//myWindow = window.open("messages.php?mid="+musicid, "", "width=800,height=800");
	document.getElementById('modal').style.display = "inline";

}
function close(){
	document.getElementById('modal').style.display = "none";
}

</script>
</head>

<body>


<div id="content">
<table cellspacing="0" id="tab">
<tr><th>Author</th><th>Track</th><th>Vote</th></tr>
<?php

echo '<strong>Welcome, '.$id.', <a href="index.php">Logout</a></strong><br>';
while($stmt->fetch()){
	echo '<tr><td><br>'.$singer.'</td>'.
	'<td>'.$filename.'</a><br><audio src="music/'.$name.'" controls preload="none">Your  browser does not support the audio element.</audio>
	<br><a href="javascript:popup('.$musicid.')">Comments</a></td>'.
	'<td><br>';
	if(!isVoted($musicid)){
		echo '<a href=#openModal onClick="setValue('.$musicid.','.$id.')"><image  src="img/like.jpg" width="30" height="30"></a>';
	}else{
		echo 'Voted';
	}
	echo '</td></tr>';
}

?>
</table>
<div id="openModal" class="modalDialog">
	<div align="center">
		<a href="#close" title="Close" class="close">X</a><br>
		<image src="img/logo.png"><br>
		Voting for it?
		<br>
		<a href="vote.php" id="vote">Sure</a>
	</div>
</div>

<div id="modal">
	<p align="center"><a href="javascript:close()">X close</a><p>
	<form name="comments" id="comments" action="<?php echo $_SERVER['PHP_SELF']?>" method="get">
		<input type="hidden" id="key" name="key">
		<input type="hidden" name="id" value="<?php echo $id?>">
	</form>
	<?php

function commentInsertion()
{
	global $mysqli;
	$query = 'INSERT INTO message(musicid, userid, message,time) VALUES (?,?,?,?)';
	$date = date('Y-m-d H:i:s');
	$stmt = $mysqli->prepare($query);
	if(!$stmt){
		echo "Prepare failed: (" . $mysqli->errno .")" . $mysqli->error;
	
	}
	if(!$stmt->bind_param("isss", $_GET['musicid'],$_GET['id'], $_GET['content'], $date)){
			echo "Binding parameters failed: (" . $stmt->errno . ") ".  $stmt->error;
	}
		
	if(!$stmt->execute()){
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	$stmt -> close();
}
	if(!empty($_GET["key"])){
		echo '<script>document.getElementById("modal").style.display = "inline";</script>';
		$key=$_GET["key"];
		findComment($key);
		echo '<form name="newcomments" method="get" action='.$_SERVER['PHP_SELF'].'>';
		echo '<div class="text" style=" text-align:left;"> You say: </a> <br>';
		echo '<textarea name="content" cols="36" rows="8" id="content" required = "required" style="border: 1 solid #888888;LINE-HEIGHT:18px;padding: 3px;"></textarea>';
		echo '<input type="hidden" name="id" value="'.$id.'">';
		echo '<input type="hidden" name="musicid" value="'.$key.'">';
		echo '<input type="submit" value="Submit"/>';
		echo '</form>';
	}
	if(!empty($_GET['content'])){
		commentInsertion();
		header("location:music.php?id=".$id);
	}
	?>
</div>
<?php
$stmt->close();
$mysqli->close();
?>
</body>
</html>