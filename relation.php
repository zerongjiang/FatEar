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
<title>My Relationships</title>

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
    <a href="relation.php" class="a_btn a_btn_focus">Fo&amp;Fr</a>
    <a href="msg.php" class="a_btn">Messages</a>
    <a href="avatar.php" class="a_btn">Avatar</a>
    </div>
	 
    <p class='tip hidden'></p>
    <div id="accoutinfo">
    <?php
    		$sql = "SELECT uid,username,friend,avatar FROM follow,user WHERE follow.uid1='$myuid' and follow.uid2=user.uid";
			$result = mysql_query($sql);
			echo "<h4>Following:</h4>";
			echo "<ul id='fflist'>";
			while($row = mysql_fetch_array($result)){
				echo "<li id=\"".$row["uid"]."\">";
				getUsermini($row["uid"]); 
				echo "<a>".$row["username"]."</a>";
				
				if($row["friend"] == NULL){
					echo "<a href=\"#\" class='a_btn a_btn_ff' onclick=\"ajaxActFriend(".$row["uid"].")\" >Add Friend</a>";
				}
				else if($row["friend"] == "request"){
					echo "<a href=\"#\" class='a_btn a_btn_ff' onclick=\"ajaxActFriend(".$row["uid"].")\" >Unrequest</a>";
				}
				else if($row["friend"] == "confirm"){
					echo "<a href=\"#\" class='a_btn a_btn_ff' onclick=\"ajaxActFriend(".$row["uid"].")\" >Unfriend</a>";
				}
				
				echo "<a href=\"#\" class='a_btn a_btn_ff' onclick=\"ajaxActFollow(".$row["uid"].")\" >Unfollow</a>";
				
				
				echo "</li>";
			}
			
			echo "</ul>";
			
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