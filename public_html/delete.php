<?php
include('conn/connData.txt');
?>
<html>
<body align="center">
<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
Once the music is deleted, it cannot be restored!<br>
<strong>Are You Sure?<strong><br>
<input type="hidden" name="mid" value="<?php echo $_GET['mid']?>">
<input type="hidden" name="file" value="<?php echo $_GET['file']?>">
<input type="submit" value="Sure" name="submit">
</form>
<br>
<a href="manage.php?pswd=ilovechina">GO BACK</a>

</body>
</html>

<?php
//---------------------------------------------set up the connection with mysql
$mysqli = new mysqli($server, $user, $pass, $dbname, $port);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if(isset($_POST['submit'])) 
{ 
    $musicid=$_POST['mid'];
    $file=$_POST['file'];
    while(strchr($file,'\\')) { 
		$file = stripslashes($file); 
    } 
    echo $file.'<br>';
    $file='/home/a5831203/public_html/music/'.$file;
    echo $file;
    $sql = "delete from message where musicid=?";
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

    $sql = "delete from comments where musicid=?";
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

    $sql = "delete from music where musicid=?";
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

    $stmt->close();
    if(file_exists($file)){
    	if (!unlink($file)){
			echo ("Error deleting $file");
    	}else{
    		header("location:manage.php?pswd=ilovechina");
	}
      }else{
		header("location:manage.php?pswd=ilovechina");
      }
}
$mysqli->close();
?>

