<?php
session_start();
include ("conn.php");
include ("functions.php");
$myuid;
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
<link rel="stylesheet" type="text/css" href="css/artist.css" />
<link rel="stylesheet" type="text/css" href="css/search.css" />

<title>Search
<?php
if(isset($_GET["keyword"])) echo "Result for: ".$_GET["keyword"];
?></title>
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
	
		if(isset($_GET["keyword"])){
			
			$keyword = $_GET["keyword"];
			
			
			
			//search songs
			$sql = "select * from song natural left join artist where title like '".$keyword."%'";
			$result = mysql_query($sql);
			echo "<div id='lastestsongs'>";
			echo "<h4>Related Songs</h4>";
			if($row = mysql_fetch_array($result)){
				
			
				echo "<div id='songlist'>";
				echo "<ul>";
				do{
					echo "<li id='song".$row["sid"]."'>";
					echo "<a href='song.php?sid=".$row["sid"]."'>".$row["title"]."</a>";
					echo " -- ";
					echo "<a href='artist.php?aid=".$row["artist_id"]."'>".$row["artist_name"]."</a>";
					echo "<a onclick='ajaxSongplay(".$row["sid"].")' class='songplay'>play</a>";
					echo "</li>";
				}while($row = mysql_fetch_array($result));
				echo "</ul>";
				echo "</div>";
				
			}else{
				echo "<p class='tip'>No Result</p>";
			}
			echo "</div>";
			
			
			
			//search albums
			$sql = "select * from album natural left join artist where album_name like '".$keyword."%'";
			$result = mysql_query($sql);
			echo "<div class='albumlist'>";
			echo "<h4>Related Album</h4>";
			if($row = mysql_fetch_array($result)){
			echo "<ul>";
			do{
				echo "<li id='album".$row["album_id"]."'>";
				echo "<div id='albumcover100'>";
				echo "<a class='cdcover100' title='".$row["album_name"]."' href='album.php?aid=".$row["album_id"]."'>".
					"<img alt='".$row["album_name"]."' class='cdimg100' src=\"".$row["album_cover"]."\"></img>"."</a>";
		  		echo "</div>";
				echo "<span><a href='album.php?aid=".$row["album_id"]."'>".$row["album_name"]."</a></span>";
				echo "<span>By</span>";
				echo "<span><a href='artist.php?aid=".$row["artist_id"]."'>".$row["artist_name"]."</a></span>";
				echo "</li>";
			}while($row = mysql_fetch_array($result));
			echo "</ul>";
			}else{
				echo "<p class='tip'>No Result</p>";
			}
			echo "</div>";
			
			
			//search artist
			echo "<div id='artistlist'>";
			$sql = "select * from artist where artist_name like '".$keyword."%'";
			$result = mysql_query($sql);
			echo "<h4>Related Artist</h4>";
			if($row = mysql_fetch_array($result)){
			echo "<ul>";
			do{
					echo "<li>";
					echo "<div class='artistavatar100'>";
					echo "<a class='' title='".$row["artist_name"]."' href='artist.php?aid=".$row["artist_id"]."'>"
				  ."<img alt='".$row["artist_name"]."' class='' src=\"".$row["artist_avatar"]."\"></img>"."</a>";
					echo "</div>";
					echo "<span><a href='artist.php?aid=".$row["artist_id"]."'>".$row["artist_name"]."</a></span>";
					echo "</li>";
			
			}while($row = mysql_fetch_array($result));
			echo "</ul>";}else{
				echo "<p class='tip'>No Result</p>";
			}
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