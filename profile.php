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
<script type="text/javascript" src="js/jquery.js"></script>
<script src="js/ajax.js" type="text/javascript"></script>
<script type="text/javascript">


</script>

<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/account.css" />

<title>My Account</title>

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
    <a href="profile.php" class="a_btn a_btn_focus">Profile</a>
    <a href="relation.php" class="a_btn">Fo&amp;Fr</a>
    <a href="msg.php" class="a_btn">Messages</a>
    <a href="avatar.php" class="a_btn">Avatar</a>
    </div>
		
    <div id="accoutinfo">
    <?php 
	if(isset($_POST["action"])){
		if($_POST["action"] == "profile"){
				$age = $_POST["age"];
				$city = $_POST["city"];
				$profile =addslashes($_POST["profile"]);
				$sql = "UPDATE user SET age='$age' , city='$city' , profile='$profile' WHERE uid='$myuid'";	
				//echo $sql;
				if( mysql_query($sql)){
					
				echo "<p class='tip'>Update Success</p>";
				echo "<script type='text/javascript'>setTimeout(function(){
							$('.tip').slideUp(300);
							 },2000);</script>";
				}
		}
		
	}
	
	?>
    
    
    
    <?php
   				$sql = "SELECT * FROM user WHERE uid='$myuid'";
				$result = mysql_query($sql);
				$row = mysql_fetch_array($result);
				
				echo "<form id=\"userprofile\" method=\"post\" action=\"profile.php\">";
				echo "<div class='userlarge'>";
				echo "<a title=\"".$row["username"]."\" href=\"".$row["avatar"]."\"><img src=\"".$row["avatar"]."\"  class=\"avatarlarge\"/></a>";
				echo "</div>";
				echo "<table><tbody>";
				echo "<tr>";
				echo  "<td>Username:</td>";
				echo  "<td>".$row["username"]."</td>";
				echo "</tr>";
				
				echo "<tr>";
				echo  "<td>Age:</td>";
				echo  "<td><input type=\"text\" name=\"age\" value=\"".$row["age"]."\"/></td>";
				echo "</tr>";
				
				echo "<tr>";
				echo  "<td>City:</td>";
				echo  "<td><input type=\"text\" name=\"city\" value=\"".$row["city"]."\"/></td>";
				echo "</tr>";
				
				echo "<tr>";
				echo  "<td>Profile:</td>";
				echo  "<td><textarea  name=\"profile\">".htmlspecialchars($row["profile"])."</textarea></td>";
				echo "</tr>";
				echo "</tbody></table>";				
				echo "<input type=\"hidden\" name='action' value='profile' />";
				echo "<input type=\"submit\" value='Update'/>";
				
				echo "</form>";
    
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