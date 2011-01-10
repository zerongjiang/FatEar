<?php
session_start();
include ("conn.php");
include ("functions.php")
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/register.css" />


<title>Register</title>
<script type="text/javascript">
function check() {

	var username = document.getElementsByName("username").item(0).value;
	var pwd1 	 = document.getElementsByName("password").item(0).value;
	var pwd2 	 = document.getElementsByName("password2").item(0).value;
	
	if(/^([a-z]|[A-Z])[\w_]{3,11}$/.test(username)){
		if( /^[\S]{6,16}$/.test(pwd1)){
			if(pwd1 == pwd2){
				
				document.regfrm.submit();
			}
			else{
		
			alert("make sure twin input of password are same!");
			}
			
		}
		else{
		
		alert("password at least 6 characters!");
		}
			
		
	}
	else{
		
		alert("Username must initialed with letter, '_' letters numbers are allowed, 4 - 12 characters!");
	}
	
	
} 


</script>
</head>
<body>
<div id="header">

<?php
getHeader();
?>


</div>


<?php

if(isset($_POST["username"])&&isset($_POST["password"])&&isset($_POST["password2"])){
		
		$sql = "select * from user where username='$_POST[username]'";
		$result = mysql_query($sql);
		if( mysql_num_rows($result) == 0){
			
			date_default_timezone_set("America/New_York");
			$regtime = date("Y-m-d H:i:s"); 
			$sql = "Insert INTO user (username, pwd, regtime)
				VALUES
				('$_POST[username]','$_POST[password]','$regtime')";
			mysql_query($sql);
			$sql = "SELECT @@IDENTITY as uid";
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result);
			$_SESSION['uid']=$row['uid'];
			$_SESSION['user']=$_POST["username"];
			$_SESSION['login']='login';

			
			echo "<div id='register' ><h3>Register Success</h3><h4>Redirecing to <a href='index.php'>Homepage ....</a></h4></div>";
			
			$url = "index.php";
			
			echo "<script language='javascript' type='text/javascript'>";
			echo  "setTimeout(\"window.location.href='$url'\",5000);";
			echo "</script>"; 
			
		}
		else{
			
			echo "<div id='register' ><h3 class='fail'>Register Failed</h3><h4 class='fail'>Username Existed</h4>
			<h4>Back to <a href='register.php'>Register ....</a></h4></div>";
			
			$url = "register.php";
			
			echo "<script language='javascript' type='text/javascript'>";
			echo  "setTimeout(\"window.location.href='$url'\",3000);";
			echo "</script>"; 
		}
}
else{

 echo " <div id='register'>
   <h3>Create a Account</h3>
   <form action='register.php' method='post' name='regfrm'>
     <div><label>Username:</label><input class='textinput' type='text' name='username'/> </div>
	 <div><label>Choose a password:</label><input class='textinput' type='password' name='password' /></div>
	<div><label>Re-enter password:</label><input class='textinput' type='password' name='password2' /></div>
    <div style='text-align: center'><input class='inputbtn' type='button' value='Create Account' onclick='check()'/></div>
     </form></div>";
	 
}




?>
   
<?php clear();getFooter();?></body>
</html>