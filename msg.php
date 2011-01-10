<?php
session_start();
include ("conn.php");
include ("functions.php");

if(isset($_SESSION['uid'])){
	$myuid = $_SESSION['uid'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/ajax.js" type="text/javascript"></script>
<script type="text/javascript">
</script>

<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/account.css" />

<title>Message Center</title>

</head>

<body>

<div id="header">

<?php
getHeader();
?>


</div>
<div id="main"> 
	<div id="content">
    
    <div id='navi'> 
    <a href="profile.php" class="a_btn">Profile</a>
    <a href="relation.php" class="a_btn">Fo&amp;Fr</a>
    <a href="msg.php" class="a_btn a_btn_focus">Messages</a>
    <a href="avatar.php" class="a_btn">Avatar</a>
    </div>
    <p class='tip hidden'></p>
    <div id="accoutinfo">
    <?php 
		$sql = "SELECT * FROM follow,user WHERE uid1=uid and uid2='$myuid' and friend='request'";
		$result = mysql_query($sql);
		echo "<div id='msglist'>";
		echo "<h4>Friend Requests:</h4>";
		if($row = mysql_fetch_array($result)){
		echo "<ul>";
		do{
			
				echo "<li id=\"".$row["uid"]."\">";
				getUsermini($row["uid"]);
				echo "<a>".$row["username"]."</a>"; echo " wanna be your friend";
				echo "<a class='a_btn_ff' onclick=\"ajaxActFrdAccept(".$row["uid"].")\" >Accept</a>";
				echo "<a class='a_btn_ff' onclick=\"ajaxActFrdDecline(".$row["uid"].")\" >Decline</a>";
				echo "</li>";
		}while($row = mysql_fetch_array($result));
		echo "</ul>";}
		else echo "<p class='tip'>No Friend Request</p>";
		echo "</div>";
    ?>
    
	</div>
    
    </div>
	<div id="sidebar">
        
			<?php
			
            getSidebar();
			 
            ?>
       
        
	</div>
</div>




<?php clear();getFooter();?></body>
</html>