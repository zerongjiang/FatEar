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
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/song.css" />


<script type="text/javascript" src="js/jquery.js"></script>
<script src="js/ajax.js" type="text/javascript"></script>
<script type="text/javascript">

<?php if(isset($_GET["sid"])) echo "sid=".$_GET["sid"]; ?>


$(document).ready(function(){
	
  $("#header a:eq(1)").addClass("a_btn_focus");

  $(".grade a").mouseover(function(){
	$(this).addClass("hover");
  	$(this).prevAll().addClass("hover");
	$(this).nextAll().addClass("unselect");
  });
  
  $(".grade a").mouseout(function(){
	   $(this).removeClass("hover");
  	   $(this).prevAll().removeClass("hover");
	   $(this).nextAll().removeClass("unselect");
  });
  
  $("#mycomment a").click(function(){
	  if($(this).html()=="Post"){
		  ajaxPostcomment(sid);
	  }
	  else if($(this).html()=="Modify"){
		  $(this).html("Post");
		  mycomment = $("#mycomment p").html();
		  $("#mycomment p").html("<textarea>"+mycomment+"</textarea>");
	  }
  });
  
  $("#like_btn").click(function(){
	  ajaxLikesong(sid);
  });
  
	 $("#songlist ul li").mouseenter(function(){
	  $(this).css("background-color","#348DD3");
	  $(this).addClass("sl_selected");
  	});
  
  	$("#songlist ul li").mouseleave(function(){
	  $(this).css("background-color","#FFF");
	  $(this).removeClass("sl_selected");
  	});
  
  $(".activitylist li").mouseenter(function(){
	  $(this).find(".checkit").show();	
  });
  $(".activitylist li").mouseleave(function(){
	  $(this).find(".checkit").hide();	
  })
  
  
  
});

</script>
<title>
<?php
 if(isset($_GET["sid"])){
	$sql = "select title from song where sid=".$_GET["sid"];
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
    echo $row["title"];
 }else echo "Songs Homepage";
?>
</title>

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
	if(!isset($_GET["sid"])){
    echo "<div id='navi'> 
    <a href='song.php' class='a_btn'>Recent</a>";
	if(isset($_SESSION['uid']))echo "<a href='song.php?action=social' class='a_btn'>Fo&amp;Fr</a>";
    echo "<a href='song.php?action=popluar' class='a_btn'>Most Popular</a>
	<a href='song.php?action=lastest' class='a_btn'>Lastest Songs</a>";
    echo "</div>";
	
	if(!isset($_GET["action"])){
		
		 echo "<script   language= 'javascript'> $(\"#navi a:eq(0)\").addClass(\"a_btn_focus\");</script>";
		
		
		$sql = "select * 
				from activity join song
				where song.sid= activity.sid and activity.sid is not null 
				ORDER BY updatetime DESC 
				limit 30";
		$result = mysql_query($sql);
		echo "<div class='activitylist'>";
		
		echo "<ul>";
		
		while($row = mysql_fetch_array($result)){
			echo "<li>";
			getUsermini($row["uid"]);
			echo "<div class='activity'>";

			echo "<div>";
			getUserlink($row["uid"]);
			//echo "<span>".$row["updatetime"]."</span>";
			
			if($row["challenge"] != NULL){
				echo "<span>Likes</span>";
				echo "<a href='song.php?sid=".$row["sid"]."'><strong>".$row["title"]."</strong></a>";
				if($row["rank"] != NULL){
					echo "<span>Rated with</span>";
					echo "<span class='rankico appraise".$row["rank"]."'></span>";
					}
				if($row["comment"] != NULL){
						echo "<span>And Say:</span>";}
			}else if($row["rank"] != NULL){
				echo "<span>Rated</span>";
				echo "<a href='song.php?sid=".$row["sid"]."'><strong>".$row["title"]."</strong></a>";
				echo "<span class='rankico appraise".$row["rank"]."'></span>";
				if($row["comment"] != NULL){
				echo "<span>And Say:</span>";}
			}else if($row["comment"] != NULL){
				echo "<span>Commented</span>";
				echo "<a href='song.php?sid=".$row["sid"]."'><strong>".$row["title"]."</strong></a>";
			}
			
			echo  "<a style='display:none' class='checkit' href='song.php?sid=".$row["sid"]."'>Check It</a>";
			echo "</div>";

			
			if($row["comment"] != NULL) echo "<p class='quotecomment'><span>".$row["comment"]."</span></p>";
			echo "</div>";
			clear();echo "</li>";

		}

		echo "</ul>";
		echo "</div>";
		
	}
		
	if(isset($_GET["action"])){


		if($_GET["action"] == "social"){
		if(isset($_SESSION['uid'])){
		$myuid = $_SESSION['uid'];
		echo "<script   language= 'javascript'> $(\"#navi a:eq(1)\").addClass(\"a_btn_focus\");</script>";

			
		$sql = "select * 
				from activity join song
				where song.sid= activity.sid and activity.sid is not null 
				and uid in( select uid2
							from follow
							where uid1=".$myuid."
				)
				ORDER BY updatetime DESC 
				limit 30";
		$result = mysql_query($sql);
		echo "<div class='activitylist'>";
		
		echo "<ul>";
		
		while($row = mysql_fetch_array($result)){
			echo "<li>";
			getUsermini($row["uid"]);
			echo "<div class='activity'>";
			
			echo "<div>";
			getUserlink($row["uid"]);
			//echo "<span>".$row["updatetime"]."</span>";
			
			if($row["challenge"] != NULL){
			echo "<span>Likes</span>";
				echo "<a href='song.php?sid=".$row["sid"]."'><strong>".$row["title"]."</strong></a>";
				if($row["rank"] != NULL){
				echo "<span>Rated with</span>";
				echo "<span class='rankico appraise".$row["rank"]."'></span>";}
				if($row["comment"] != NULL){
				echo "<span>And Say:</span>";}
			}else if($row["rank"] != NULL){
				echo "<span>Rated</span>";
				echo "<a href='song.php?sid=".$row["sid"]."'><strong>".$row["title"]."</strong></a>";
				echo "<span class='rankico appraise".$row["rank"]."'></span>";
				if($row["comment"] != NULL){
				echo "<span>And Say:</span>";}
			}else if($row["comment"] != NULL){
				echo "<span>Commented</span>";
				echo "<a href='song.php?sid=".$row["sid"]."'><strong>".$row["title"]."</strong></a>";
			}
			echo  "<a style='display:none' class='checkit' href='song.php?sid=".$row["sid"]."'>Check It</a>";
			echo "</div>";
			
			if($row["comment"] != NULL) echo "<p class='quotecomment'><span>".$row["comment"]."</span></p>";

			echo "</div>";
			clear();echo "</li>";

		}

		echo "</ul>";
		echo "</div>";
		
		}
		
		}
		
		
		if($_GET["action"] == "lastest"){
		if(isset($_SESSION['uid']))	echo "<script   language= 'javascript'> $(\"#navi a:eq(3)\").addClass(\"a_btn_focus\");</script>";
		else 						echo "<script   language= 'javascript'> $(\"#navi a:eq(2)\").addClass(\"a_btn_focus\");</script>";
		
		$sql = "select * from song natural join artist ORDER BY  sid DESC limit 30";
		$result = mysql_query($sql);
		echo "<div id='lastestsongs'>";
		echo "<h4>Lastest Songs</h4>";
	
		echo "<div id='songlist'>";
		echo "<ul>";
		while($row = mysql_fetch_array($result)){
			echo "<li id='song".$row["sid"]."'>";
			echo "<a href='song.php?sid=".$row["sid"]."'>".$row["title"]."</a>";
			echo " -- ";
			echo "<a href='artist.php?aid=".$row["artist_id"]."'>".$row["artist_name"]."</a>";
			echo "<a onclick='ajaxSongplay(".$row["sid"].")' class='songplay'>play</a>";
			echo "</li>";
		}
		echo "</ul>";
		echo "</div>";
		echo "</div>";
		}

		if($_GET["action"] == "popluar"){
		if(isset($_SESSION['uid']))	echo "<script   language= 'javascript'> $(\"#navi a:eq(2)\").addClass(\"a_btn_focus\");</script>";
		else 						echo "<script   language= 'javascript'> $(\"#navi a:eq(1)\").addClass(\"a_btn_focus\");</script>";

		$sql = "SELECT activity.sid, COUNT( activity.uid ) AS popular, song.title, artist.artist_id, artist.artist_name
			FROM song
			JOIN activity
			JOIN artist
			WHERE activity.sid = song.sid
			AND song.artist_id = artist.artist_id
			AND challenge IS NOT NULL 
			AND activity.sid IS NOT NULL 
			GROUP BY activity.sid
			ORDER BY popular DESC 
			LIMIT 0 , 30
			";
			
		$result = mysql_query($sql);
		echo "<div id='popularsongs'>";
		echo "<h4>Most Popular Songs</h4>";
		echo "<div id='songlist'>";
		echo "<ul>";
		if($row = mysql_fetch_array($result)){
		$maxpop = $row["popular"];
		do{
			echo "<li id='song".$row["sid"]."'>";
			echo "<div class='listsonginfo'>";
			echo "<a href='song.php?sid=".$row["sid"]."'>".$row["title"]."</a>";
			echo " -- ";
			echo "<a href='artist.php?aid=".$row["artist_id"]."'>".$row["artist_name"]."</a>";
			echo "</div>";
			echo "<a  onclick='ajaxSongplay2(".$row["sid"].")' class='songplay'>play</a>";
			$barlen = $row["popular"]/$maxpop*240;
			echo "<span class='votebar' style=\"width:".$barlen."px\">".$row["popular"]."</span>";
			echo "</li>";
		}
		while($row = mysql_fetch_array($result));
		}
		echo "</ul>";
		
		echo "</div>";
		echo "</div>";
	
		}
	}

	} 
	?>
    
    
    
    <?php
	
	//sing song
	
	 if(isset($_GET["sid"])){
		 
		 echo "<div id='song_box'>";
		 
		 $sid = $_GET["sid"];
		 $sql = "select * from song natural join songurl natural left join album natural left join artist where sid=".$sid;
		 //echo $sql;
		 $result = mysql_query($sql);
		 $row = mysql_fetch_array($result);
		 
		 
		 echo "<div id='song_info'>";
		 echo "<h2>".$row["title"]."</h2>";
		 
		  echo "<div id='song_player'>";
		  getSongplayer($row["sid"],"no");
		  echo "</div>";
		  
		  echo "<table>";
		  echo "<tbody>";
		  
		  echo "<tr>";
		  echo "<td class='rc1'>Artist   </td>";
		  if($row["artist_name"]!=NULL)
		  	echo "<td>"."<a href='artist.php?aid=".$row["artist_id"]."'><strong>".$row["artist_name"]."</strong></a>"."</td>";
		  else 									  echo "<td>Unknown</td>";
		  echo "</tr>";
		  
		  echo "<tr>";
		  echo "<td class='rc1'>Album   </td>";
		  if($row["album_name"]!=NULL)
		  echo "<td>"."<a href='album.php?aid=".$row["album_id"]."'><strong>".$row["album_name"]."</strong></a>"."</td>";
		  else 									  echo "<td>Unknown</td>";
		  echo "</tr>";
		  
		  echo "<tr>";
		  echo "<td class='rc1'>Genre   </td>";
		  if($row["genre"]!=NULL) 		  echo "<td><a><strong>".$row["genre"]."</strong></a></td>";
		  else 									  echo "<td>Unknown</td>";
		  echo "</tr>";
		  
		  echo "</tbody>";
		  echo "</table>";
		  
		  echo "</div>";
		  
		  echo "<div class='albumcover'>";
		  
		  if($row["album_name"]!=NULL) { 
		  		echo "<a class='cdcover' title='".$row["album_name"]."' href='album.php?aid=".$row["album_id"]."'>"."<img alt='".$row["album_name"]."' class='cdimg' src=\"".$row["album_cover"]."\"></img>"."</a>";
		  }
		  else{
			  	echo "<a class='cdcover' title='unknown album' >"."<img class='cdimg'  alt='unknown album' src='image/cover/default.jpg'></img>"."</a>";
		  }
		  
		  echo "</div>";
		  
		  
		 
	 	echo "</div>";
	 
	 }
	 
	 
	 ?>
     
     <?php
	 if(isset($_SESSION['uid'])){
	  if(isset($_GET["sid"])){
		$sid = $_GET["sid"];
		echo "<div class='like_btn_block'>";
     	getLikebtn($sid);
		echo "</div>";

	  }
	 }
	  ?>
     <?php
	  if(isset($_GET["sid"])){
		$sid = $_GET["sid"];
		echo "<div id='likeusers'>";
		getLikeusers($sid);
		echo "</div>";
	  }
	
	 ?>
     
     
     <?php
	 
	 if(isset($_GET["sid"])){	
	  echo "<div id='songtool'>";
		$sid = $_GET["sid"];
		
		
	 	echo "<div id='grader'>";
		echo "<h4>Rank It:</h4>";
		if(isset($_SESSION['uid'])){
			$myuid = $_SESSION['uid'];
			getGrade($myuid,$sid);
		}
		 getGrades($sid);
	 	echo "</div>";
	 
	 }
	 
	 ?>
     
     
	
	<?php
	if(isset($_GET["sid"])){
		echo "<div id='mycomment'>";
		$sid = $_GET["sid"];
		//register to give comments
		
		if(isset($_SESSION['uid'])){
			$myuid = $_SESSION['uid'];
			$sql = "select  * from activity where sid=".$sid." and uid=".$myuid;
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result);
			if($row == NULL || $row["comment"] == NULL){
				echo "<h4>Add Comment:</h4>";
				echo "<p><textarea maxlength='300' rows='' cols='' height></textarea></p>";
				echo "<a class='inputbtn'>Post</a>";
			}else{
				echo "<h4>Your Comment:</h4>";
				echo "<p>".htmlspecialchars($row["comment"])."</p>";
				echo "<a class='inputbtn'>Modify</a>";
			}
		}
		else{
			echo "<h4>Add Comment:</h4>";
			echo "<p class=\"tip\"><a href=\"register.php\">register</a> to give comments</p>";
		}
		echo "</div>";
		echo "</div>";
	}
	?>


    <?php
	if(isset($_GET["sid"])){
		$sid = $_GET["sid"];
        
		echo "<div class='activitylist'>";
		if(isset($_SESSION['uid']))	$myuid = $_SESSION['uid'];
		else $myuid = 0;
		
		$sql = "select  * from activity where (rank is not NULL or comment is not NULL) and uid !=".$myuid." and sid=".$sid;
		$result = mysql_query($sql);
		
		if(mysql_num_rows($result)==0){
			echo "<h3>No Others' comments yet</h3>";
		}
		else{
			echo "<h3>Others' comments</h3>";
		}
		echo "<ul>";
		
		while($row = mysql_fetch_array($result)){
			echo "<li>";
			getUsermini($row["uid"]);
			echo "<div class='activity'>";
			echo "<div>";

			getUserlink($row["uid"]);
			echo "<span>".$row["updatetime"]."</span>";
			
			if($row["rank"] != NULL){
				echo "<span>Rate</span>";
				echo "<span class='rankico appraise".$row["rank"]."'></span>";
			}else{
				echo "<span>Not Rate</span>";
				echo "<span class='rankico appraise0'></span>";
			}
			echo "</div>";

			if($row["comment"] != NULL){
				echo "<p class='quotecomment'><span>".$row["comment"]."</span></p>";
			}else{
				//echo "<p class='quotecomment'><span>this guy is lazy, no comment yet</span></p>";
			}
			echo "</div>";

			echo "</li>";
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
<script type="text/javascript">


</script>


</html>