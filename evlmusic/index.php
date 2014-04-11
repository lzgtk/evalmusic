<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Music Eval</title>
<link href="css/login-box.css" rel="stylesheet" type="text/css" />
<script>
function check(){
	var letter1=document.login.areacode.value.length+1;
	if(letter1<=3){
		document.login.areacode.focus();
	}else{
		document.login.provider.focus();
	}
}
function check2(){
	var letter2 = document.login.provider.value.length+1;
	if(letter2<=3){
		document.login.provider.focus();
	}else{
		document.login.phone.focus();
	}
}

</script>
</head>
<body style="background: url(img/login.jpg) no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;">

<div style="padding: 180px 0 0 0px;" align="center">
	<div id="login-box" style="padding-top:20px">
		<form name="login" method="post" action="http://ix.cs.uoregon.edu/~hanqing/evlmusic/getcode.php">
			<input type="text" class="form-login" size="3" maxlength="3" placeholder="XXX"  name="areacode" pattern="[0-9]{3}" required="required" onKeyUp="check()"/>
			<font size="5">-</font>
			<input type="text" class="form-login" size="3" maxlength="3" placeholder="XXX"  name="provider" pattern="[0-9]{3}" required="required" onKeyUp="check2()"/>
			<font size="5">-</font>

			<input type="text" class="form-login" size="4" maxlength="4" placeholder="XXXX"  name="phone" pattern="[0-9]{4}" required="required" onKeyUp="check3()"/>
			<br>
			<p>You will received the access code by the specified phone number</p>

			<br>
			<br>
			<input type="image" name="submit" value="" src="img/login-btn.png" border="0" alt="Submit">
		</form>
		</div>
	</div>
</body>
</html>
