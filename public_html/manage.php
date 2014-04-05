<?php
$pass=$_GET['pswd'];
if(strcmp($pass,'ilovechina') != 0){
    header('location:index.php');
}
include('conn/connData.txt');
//---------------------------------------------set up the connection with mysql
$mysqli = new mysqli($server, $user, $pass, $dbname, $port);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

function counting($musicid){
    global $mysqli;
    $total=null;
    $sql = 'select count(musicid) from comments where musicid=?';
    $stmt=$mysqli->prepare($sql);
    if(!$stmt){
	echo "Prepared failed:(".$mysqli->error.")". $mysqli->error;
    }
    if(!$stmt->bind_param('i', $musicid)){
        echo "Bind Failed";
    }
    if(!$stmt->execute()){
	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    if(!$stmt->bind_result($total)){
	echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    $stmt->fetch();
    return $total;
    $stmt->close();
}

//search from database to grab all the information about one music

$sql = 'select musicid, name, singer, filename, IFNULL(total, 0) from music left join 
(select musicid, count(userid) as total from comments group by musicid) as vote using(musicid)';
$musicid=NULL;
$name=NULL;
$singer=NULL;
$filename=NULL;
$total;
$stmt= $mysqli->prepare($sql);

if(!$stmt){
	echo "Prepared failed:(".$mysqli->error.")". $mysqli->error;
}

if(!$stmt->execute()){
	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
if(!$stmt->bind_result($musicid, $name, $singer, $filename, $total)){
	echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
}
?>
<html>
<head>
<title>Music Evaluation</title>
<link href="css/music.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">

</script>
</head>
<body>

<div id="content">
<table cellspacing="0">
<tr><th>Author</th><th>Track</th><th>Delete</th><th>Total Votes</th></tr>
<?php

echo '<strong>Welcome, admin, <a href="index.php">Logout</a></strong><br>';
?>

<?php
while($stmt->fetch()){
	echo '<tr><td><br>'.$singer.'</td>'.
	'<td><br><a href="music/'.$name.'">'.$filename.'</a></td>'.
	'<td><br><a href="delete.php?mid='.$musicid.'&&file='.$name.'"><image src="img/delete.jpg" width="30" height="30"></a></td>'.
        '<td><br>'.$total.'</td>'.
	'</tr>';
}

?>
</table>
</div>
<?php
$stmt->close();
$mysqli->close();
?>
</body>
</html>												