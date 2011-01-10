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
<title>My Avatar</title>

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
    <a href="msg.php" class="a_btn">Messages</a>
    <a href="avatar.php" class="a_btn a_btn_focus">Avatar</a>
    </div>
		
    <div id="accoutavatar">
    <?php 
	if(isset($_POST["action"])){
		if($_POST["action"] == "avatar"){
				if ((($_FILES["avatar"]["type"] == "image/gif")
					|| ($_FILES["avatar"]["type"] == "image/jpeg")
					|| ($_FILES["avatar"]["type"] == "image/png")
					|| ($_FILES["avatar"]["type"] == "image/x-png")
					|| ($_FILES["avatar"]["type"] == "image/pjpeg"))
					&& ($_FILES["avatar"]["size"] < 300000))
					  {
					  if ($_FILES["avatar"]["error"] > 0)
						{
						echo "<p class='tip err_tip'>". $_FILES["avatar"]["error"] ."</p>";
						}
					  else
						{
							$avatar_url= "image/avatar/".$_SESSION['user']."_".date("Ymdhis").".jpg";
							move_uploaded_file($_FILES["avatar"]["tmp_name"],$avatar_url);
							$sql = "UPDATE user SET avatar='$avatar_url' WHERE uid='$myuid'";
							mysql_query($sql);
							echo "<p class='tip'>Change Avatar Success</p>";
							echo "<script type='text/javascript'>setTimeout(function(){
							$('.tip').slideUp(300);
							 },2000);</script>";

						}
					  }
					else
					  {
					  echo "<p class='tip err_tip'>Invalid file</p>";
				}

	
		}
		
	}
	
	?>
    
    
    
    <?php
   				$sql = "SELECT * FROM user WHERE uid='$myuid'";
				$result = mysql_query($sql);
				$row = mysql_fetch_array($result);
				
				echo "<form id=\"avatar\" method=\"post\" action=\"avatar.php\" enctype=\"multipart/form-data\">";
				echo "<div class='userlarge'>";
				echo "<a title=\"".$row["username"]."\" href=\"".$row["avatar"]."\"><img src=\"".$row["avatar"]."\"  class=\"avatarlarge\"/></a>";
				echo "</div>";
				echo "<br/>";

				echo "<input type=\"file\" name='avatar'>";
				echo "<br/>";

				
				echo "<input type=\"hidden\" name='action' value='avatar' />";
				echo "<input type=\"submit\" value='Change Avatar'/>";
				
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