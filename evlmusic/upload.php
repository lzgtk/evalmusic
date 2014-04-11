<?php
include('conn/connData.txt');
//---------------------------------------------set up the connection with mysql
$mysqli = new mysqli($server, $user, $pass, $dbname, $port);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
$name=$_POST['mname'];
while(strchr($name,'\\')) { 
	$name = stripslashes($name); 
} 

$singer=$_POST['singer'];
echo $name.$singer;

$sql = "insert into music(name, singer, filename) values(?,?,?)";
//insert into database
    $stmt=$mysqli->prepare($sql);
    if(!$stmt){
	echo "Prepared failed:(".$mysqli->error.")". $mysqli->error;
    }
    if(!$stmt->bind_param('sss', $name, $singer, $name)){
        echo "Bind Failed";
    }
    if(!$stmt->execute()){
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
header("location:manage.php?pswd=ilovechina");

?>
