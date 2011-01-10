<?php
session_start();

include ("conn.php");
include ("functions.php");


$uid ;
$myuid;

if(isset($_SESSION['uid'])){
	$myuid = $_SESSION['uid'];
}

if(isset($_GET["id"])){
	$uid = $_GET["id"];
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
  

</script>
<link rel="stylesheet" type="text/css" href="css/style.css" />

<title><?php  echo "Welcme to "; getUsername($uid); echo "'s homepage";?></title>
</head>

<body>

<div id="header">

<?php
getHeader();
?>



</div>


<div id="main"> 
	<div id="content">
   
    <?php
	
		if(isset($_GET['id'])){ 
		$uid=$_GET['id'];
		$mysql = "SELECT *,activity.sid AS asid, activity.album_id AS aalbum_id, activity.artist_id AS aartist_id
					  	FROM activity
						LEFT JOIN song ON activity.sid = song.sid
						LEFT JOIN album ON activity.album_id = album.album_id
						LEFT JOIN artist ON activity.artist_id = artist.artist_id
						where activity.uid=".$uid."
						order by updatetime desc
					  	limit 0,10";
			$result = mysql_query($mysql);
		echo "<div class='activitylist'>";
		echo "<h4>";
		getUsername($uid);
		echo "'s Activities:</h4>";
		echo "<ul>";
		while($row = mysql_fetch_array($result)){
			if($row["asid"] != NULL){
			
			echo "<li>";
			getUsermini($row["uid"]);
			echo "<div class='activity'>";
			echo "<div>";
			getUserlink($row["uid"]);
			echo "<span>".$row["updatetime"]."</span>";
			if($row["challenge"] != NULL){
				echo "<span>Likes Song:</span>";
				echo "<a href='song.php?sid=".$row["asid"]."'><strong>".$row["title"]."</strong></a>";
				if($row["rank"] != NULL){
					echo "<span>Rated with</span>";
					echo "<span class='rankico appraise".$row["rank"]."'></span>";
					}
				if($row["comment"] != NULL){
						echo "<span>And Say:</span>";}
			}else if($row["rank"] != NULL){
				echo "<span>Rated Song:</span>";
				echo "<a href='song.php?sid=".$row["asid"]."'><strong>".$row["title"]."</strong></a>";
				echo "<span class='rankico appraise".$row["rank"]."'></span>";
				if($row["comment"] != NULL){
				echo "<span>And Say:</span>";}
			}else if($row["comment"] != NULL){
				echo "<span>Commented Song:</span>";
				echo "<a href='song.php?sid=".$row["asid"]."'><strong>".$row["title"]."</strong></a>";
			}
			echo  "<a style='display:none' class='checkit' href='song.php?sid=".$row["asid"]."'>Check It</a>";
			echo "</div>";
			if($row["comment"] != NULL) echo "<p class='quotecomment'><span>".$row["comment"]."</span></p>";
			echo "</div>";
			clear();
			echo "</li>";
			}
			else if($row["aalbum_id"] != NULL){
			echo "<li>";
			getUsermini($row["uid"]);
			
			echo "<div class='activity albumactivity'>";
			
			echo "<div class='activitybref'>";
			getUserlink($row["uid"]);
			echo "<span>".$row["updatetime"]."</span>";
			if($row["challenge"] != NULL){
				echo "<span>Likes Album:</span>";
				echo "<a href='album.php?aid=".$row["aalbum_id"]."'><strong>".$row["album_name"]."</strong></a>";
				if($row["rank"] != NULL){
					echo "<span>Rated with</span>";
					echo "<span class='rankico appraise".$row["rank"]."'></span>";
					}
				if($row["comment"] != NULL){
						echo "<span>And Say:</span>";}
			}else if($row["rank"] != NULL){
				echo "<span>Rated Album:</span>";
				echo "<a href='album.php?aid=".$row["aalbum_id"]."'><strong>".$row["album_name"]."</strong></a>";
				echo "<span class='rankico appraise".$row["rank"]."'></span>";
				if($row["comment"] != NULL){
				echo "<span>And Say:</span>";}
			}else if($row["comment"] != NULL){
				echo "<span>Commented Album:</span>";
				echo "<a href='album.php?aid=".$row["aalbum_id"]."'><strong>".$row["album_name"]."</strong></a>";
			}
			
			echo  "<a style='display:none' class='checkit' href='album.php?aid=".$row["aalbum_id"]."'>Check It</a>";
			echo "</div>";

			
			echo "<div id='albumcover100'>";
			echo "<a class='cdcover100' title='".$row["album_name"]."' href='album.php?aid=".$row["aalbum_id"]."'>".
					"<img alt='".$row["album_name"]."' class='cdimg100' src=\"".$row["album_cover"]."\"></img>"."</a>";
		  	echo "</div>";
			
			
			if($row["comment"] != NULL) echo "<div class='albumcomment'><p class='quotecomment'><span>".$row["comment"]."</span></p></div>";
			echo "</div>";
			clear();
			echo "</li>";

				
			}
			else if($row["aartist_id"] != NULL){
			echo "<li>";
			getUsermini($row["uid"]);
			echo "<div class='activity artistactivity'>";
			echo "<div class='activitybref'>";
			getUserlink($row["uid"]);
			echo "<span>".$row["updatetime"]."</span>";

			if($row["challenge"] != NULL){
				echo "<span>Likes Artist:</span>";
				echo "<a href='artist.php?aid=".$row["aartist_id"]."'><strong>".$row["artist_name"]."</strong></a>";
				if($row["rank"] != NULL){
					echo "<span>Rated with</span>";
					echo "<span class='rankico appraise".$row["rank"]."'></span>";
					}
				if($row["comment"] != NULL){
						echo "<span>And Say:</span>";}
			}else if($row["rank"] != NULL){
				echo "<span>Rated Artist:</span>";
				echo "<a href='artist.php?aid=".$row["aartist_id"]."'><strong>".$row["artist_name"]."</strong></a>";
				echo "<span class='rankico appraise".$row["rank"]."'></span>";
				if($row["comment"] != NULL){
				echo "<span>And Say:</span>";}
			}else if($row["comment"] != NULL){
				echo "<span>Commented Artist:</span>";
				echo "<a href='artist.php?aid=".$row["aartist_id"]."'><strong>".$row["artist_name"]."</strong></a>";
			}
			
			echo  "<a style='display:none' class='checkit' href='artist.php?aid=".$row["aartist_id"]."'>Check It</a>";
			echo "</div>";
			
			echo "<div class='artistavatar100'>";
			echo "<a class='' title='".$row["artist_name"]."' href='artist.php?aid=".$row["aartist_id"]."'>"
			  ."<img alt='".$row["artist_name"]."' class='' src=\"".$row["artist_avatar"]."\"></img>"."</a>";
			echo "</div>";
			
			if($row["comment"] != NULL) echo "<div class='artistcomment'><p class='quotecomment'><span>".$row["comment"]."</span></p></div>";
			echo "</div>";
			clear();
			echo "</li>";
				
			}

			
		}
		echo "</ul>";
		echo "</div>";
		}
	
	
	?>
    
    
	</div>
	<div id="sidebar">
        
			<?php
			
			getSidebar();
				
			
			?>
            
        	
       
        
	</div>
</div>




<?php clear();getFooter();?></body>
</html>