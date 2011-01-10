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

$(document).ready(function(){

	<?php if(isset($_GET["aid"])) echo "aid=".$_GET["aid"];?>


	$("#header a:eq(2)").addClass("a_btn_focus");
	
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
	
	
	$("#like_btn").click(function(){
		$.ajax({url:"ajax.php",async:false,type:"GET",
		success: function(data){
			if($("#like_btn span").html() == "You Like This"){ $("#like_btn span").html("Like It");}
			else if($("#like_btn span").html() == "Like It"){ $("#like_btn span").html("You Like This");}
			$("#likeusers").html(data);
		},
		data:{action:"likeAlbum",aid: aid}});
	});
	
	$("#mycomment a").click(function(){
	  if($(this).html()=="Post"){
		  	mycomment = $("#mycomment textarea").val();
			$.ajax({url:"ajax.php",async:false,type:"POST",
			success: function(){
				$("#mycomment p").html(mycomment);
				$("#mycomment a").html("Modify");
			},
			data:{action:"postAlbumcomment",aid: aid,comment:mycomment}});
	  }
	  else if($(this).html()=="Modify"){
		  $(this).html("Post");
		  mycomment = $("#mycomment p").html();
		  $("#mycomment p").html("<textarea>"+mycomment+"</textarea>");
	  }
  	});
	

});

</script>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/album.css" />
<title>
<?php
 if(isset($_GET["aid"])){
	$sql = "select album_name from album where album_id=".$_GET["aid"];
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
    echo $row["album_name"]."";
 }else echo "Album Homepage";
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
	if(!isset($_GET["aid"])){
    echo "<div id='navi'> 
    <a href='album.php' class='a_btn'>Recent</a>";
	if(isset($_SESSION['uid']))echo "<a href='album.php?action=social' class='a_btn'>Fo&amp;Fr</a>";

    echo "<a href='album.php?action=popluar' class='a_btn'>Most Popular</a>
	<a href='album.php?action=lastest' class='a_btn'>Lastest Albums</a>";
    echo "</div>";
	
	
	if(!isset($_GET["action"])){
		
		 echo "<script language='javascript'>$(\"#navi a:eq(0)\").addClass(\"a_btn_focus\");</script>";

		$sql = "select * 
				from activity join album
				where album.album_id=activity.album_id and activity.album_id is not null 
				ORDER BY updatetime DESC
				limit 30";
		$result = mysql_query($sql);
		echo "<div class='activitylist albumactivitylist'>";
		
		echo "<ul>";
		while($row = mysql_fetch_array($result)){
			echo "<li>";
			getUsermini($row["uid"]);
			
			echo "<div class='activity albumactivity'>";
			
			echo "<div class='activitybref'>";
			getUserlink($row["uid"]);
			//echo "<span>".$row["updatetime"]."</span>";
			if($row["challenge"] != NULL){
				echo "<span>Likes</span>";
				echo "<a href='album.php?aid=".$row["album_id"]."'><strong>".$row["album_name"]."</strong></a>";
				if($row["rank"] != NULL){
					echo "<span>Rated with</span>";
					echo "<span class='rankico appraise".$row["rank"]."'></span>";
					}
				if($row["comment"] != NULL){
						echo "<span>And Say:</span>";}
			}else if($row["rank"] != NULL){
				echo "<span>Rated</span>";
				echo "<a href='album.php?aid=".$row["album_id"]."'><strong>".$row["album_name"]."</strong></a>";
				echo "<span class='rankico appraise".$row["rank"]."'></span>";
				if($row["comment"] != NULL){
				echo "<span>And Say:</span>";}
			}else if($row["comment"] != NULL){
				echo "<span>Commented</span>";
				echo "<a href='album.php?aid=".$row["album_id"]."'><strong>".$row["album_name"]."</strong></a>";
			}
			
			echo  "<a style='display:none' class='checkit' href='album.php?aid=".$row["album_id"]."'>Check It</a>";
			echo "</div>";

			
			echo "<div id='albumcover100'>";
			echo "<a class='cdcover100' title='".$row["album_name"]."' href='album.php?aid=".$row["album_id"]."'>".
					"<img alt='".$row["album_name"]."' class='cdimg100' src=\"".$row["album_cover"]."\"></img>"."</a>";
		  	echo "</div>";
			
			
			if($row["comment"] != NULL) echo "<div class='albumcomment'><p class='quotecomment'><span>".$row["comment"]."</span></p></div>";
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
				echo "<script language='javascript'>$(\"#navi a:eq(1)\").addClass(\"a_btn_focus\");</script>";
		
				$sql = "select * 
						from activity join album
						where album.album_id=activity.album_id and activity.album_id is not null
							  and uid in( select uid2
							  from follow
							  where uid1=".$myuid.")
						ORDER BY updatetime DESC
						limit 30";
				$result = mysql_query($sql);
				echo "<div class='activitylist albumactivitylist'>";
				
				echo "<ul>";
				while($row = mysql_fetch_array($result)){
					echo "<li>";
					getUsermini($row["uid"]);
					
					echo "<div class='activity albumactivity'>";
					
					echo "<div class='activitybref'>";
					getUserlink($row["uid"]);
					//echo "<span>".$row["updatetime"]."</span>";
					if($row["challenge"] != NULL){
						echo "<span>Likes</span>";
						echo "<a href='album.php?aid=".$row["album_id"]."'><strong>".$row["album_name"]."</strong></a>";
						if($row["rank"] != NULL){
							echo "<span>Rated with</span>";
							echo "<span class='rankico appraise".$row["rank"]."'></span>";
							}
						if($row["comment"] != NULL){
								echo "<span>And Say:</span>";}
					}else if($row["rank"] != NULL){
						echo "<span>Rated</span>";
						echo "<a href='album.php?aid=".$row["album_id"]."'><strong>".$row["album_name"]."</strong></a>";
						echo "<span class='rankico appraise".$row["rank"]."'></span>";
						if($row["comment"] != NULL){
						echo "<span>And Say:</span>";}
					}else if($row["comment"] != NULL){
						echo "<span>Commented</span>";
						echo "<a href='album.php?aid=".$row["album_id"]."'><strong>".$row["album_name"]."</strong></a>";
					}
					
					echo  "<a style='display:none' class='checkit' href='album.php?aid=".$row["album_id"]."'>Check It</a>";
					echo "</div>";
		
					
					echo "<div id='albumcover100'>";
					echo "<a class='cdcover100' title='".$row["album_name"]."' href='album.php?aid=".$row["album_id"]."'>".
							"<img alt='".$row["album_name"]."' class='cdimg100' src=\"".$row["album_cover"]."\"></img>"."</a>";
					echo "</div>";
					
					
					if($row["comment"] != NULL) echo "<div class='albumcomment'><p class='quotecomment'><span>".$row["comment"]."</span></p></div>";
					echo "</div>";
					clear();echo "</li>";

				}
				echo "</ul>";
				echo "</div>";				
		}}
		
		else if($_GET["action"] == "lastest"){
		if(isset($_SESSION['uid']))	echo "<script   language= 'javascript'> $(\"#navi a:eq(3)\").addClass(\"a_btn_focus\");</script>";
		else 						echo "<script   language= 'javascript'> $(\"#navi a:eq(2)\").addClass(\"a_btn_focus\");</script>";

			$sql = "select *
					from album natural join artist
					order by album_id desc";
			$result = mysql_query($sql);
			echo "<div class='albumlist'>";
			echo "<h3>Lastest Album</h3>";
			echo "<ul>";
			while($row = mysql_fetch_array($result)){
				echo "<li id='album".$row["album_id"]."'>";
				echo "<div id='albumcover100'>";
				echo "<a class='cdcover100' title='".$row["album_name"]."' href='album.php?aid=".$row["album_id"]."'>".
					"<img alt='".$row["album_name"]."' class='cdimg100' src=\"".$row["album_cover"]."\"></img>"."</a>";
		  		echo "</div>";
				echo "<span><a href='album.php?aid=".$row["album_id"]."'>".$row["album_name"]."</a></span>";
				echo "<span>By</span>";
				echo "<span><a href='artist.php?aid=".$row["artist_id"]."'>".$row["artist_name"]."</a></span>";
				echo "</li>";
			}
			echo "</ul>";
			echo "</div>";
			
		}
		
		
		else if($_GET["action"] == "popluar"){
		if(isset($_SESSION['uid']))	echo "<script   language= 'javascript'> $(\"#navi a:eq(2)\").addClass(\"a_btn_focus\");</script>";
		else 						echo "<script   language= 'javascript'> $(\"#navi a:eq(1)\").addClass(\"a_btn_focus\");</script>";
			
			$sql = "select activity.album_id, album_cover,album_name, count(uid) as num,album.artist_id, artist_name
					from activity join album join artist
					where activity.album_id=album.album_id and album.artist_id=artist.artist_id
					group by activity.album_id
					order by num desc";
			$result = mysql_query($sql);
			echo "<div class='albumlist'>";
			echo "<h3>Lastest Album</h3>";
			echo "<ul>";
			if($row = mysql_fetch_array($result)){
				$maxpop = $row["num"];
			do{
				echo "<li id='album".$row["album_id"]."'>";
				echo "<div id='albumcover100'>";
				echo "<a class='cdcover100' title='".$row["album_name"]."' href='album.php?aid=".$row["album_id"]."'>".
					"<img alt='".$row["album_name"]."' class='cdimg100' src=\"".$row["album_cover"]."\"></img>"."</a>";
		  		echo "</div>";
				$barlen = $row["num"]/$maxpop*120;
				echo "<span><a class='votebar' style=\"width:".$barlen."px\">Hot:".$row["num"]."</a></span>";
				echo "<span><a href='album.php?aid=".$row["album_id"]."'>".$row["album_name"]."</a></span>";
				echo "<span>By</span>";
				echo "<span><a href='artist.php?aid=".$row["artist_id"]."'>".$row["artist_name"]."</a></span>";
				echo "</li>";
			}while($row = mysql_fetch_array($result));
			}
			echo "</ul>";
			echo "</div>";
			
		}
		
		
	}

	
	
	
	}
	?>
    
    
     <?php
		if(isset($_GET["aid"])){
		echo "<div id='albumintro'>";
		$aid =$_GET["aid"];
		$sql = "select * from album natural left join artist where album_id=".$aid;
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		echo "<div class='albumcover'>";
		echo "<a class='cdcover' title='".$row["album_name"]."' href='album.php?aid=".$row["album_id"]."'>"
		  ."<img alt='".$row["album_name"]."' class='cdimg' src=\"".$row["album_cover"]."\"></img>"."</a>";
		echo "</div>";
		echo "<div id='albumdetail'>";
		echo "<h2>".$row["album_name"]."</h2>";
		  echo "<table>";
		  echo "<tbody>";
		  
		  echo "<tr>";
		  echo "<td class='rc1'>Artist</td>";
		  if($row["artist_name"]!=NULL)
		  	echo "<td>"."<a href='artist.php?aid=".$row["artist_id"]."'><strong>".$row["artist_name"]."</strong></a>"."</td>";
		  else 	echo "<td>Unknown</td>";
		  echo "</tr>";
		  
		  echo "</tbody>";
		  echo "</table>";
		  
		   echo "<h6>Description:</h6>";
		  if($row["album_description"]!=NULL) echo "<p>".$row["album_description"]."</p>";
		  else echo "<p>No Description Yet :(</p>";
		  
		echo "</div>";
		echo "</div>";
		
		
		
		$sql = "select * from song natural join songurl where album_id=".$aid;
		$result = mysql_query($sql);
		echo "<div id='albumsonglist'>";
		echo "<h4>Ablum Tracks</h4>";
		echo "<div id='songlist'>";
		echo "<ul>";
		while($row = mysql_fetch_array($result)){
			echo "<li id='song".$row["sid"]."'>";
			echo "<a href='song.php?sid=".$row["sid"]."'>".$row["title"]."</a>";
			//echo " -- ";
			//echo "<a href='artist.php?aid=".$row["artist_id"]."'>".$row["artist_name"]."</a>";
			echo "<a onclick='ajaxSongplay(".$row["sid"].")' class='songplay'>play</a>";
			echo "</li>";
		}
		echo "</ul>";
		echo "</div>";
		echo "</div>";
		
		
		
		if(isset($_SESSION['uid'])){
			echo "<div class='like_btn_block'>";
			getAlbumLikebtn($aid);
			echo "</div>";
		}
		echo "<div id='likeusers'>";
		getLikeAlbumusers($aid);
		echo "</div>";
		
		echo "<div id='songtool'>";

		echo "<div id='grader'>";
		echo "<h4>Rank It:</h4>";
		if(isset($_SESSION['uid'])){
			$myuid = $_SESSION['uid'];
			getAlbumGrade($myuid,$aid);
		}
		getAlbumGrades($aid);
	 	echo "</div>";
		
		echo "<div id='mycomment'>";		
		if(isset($_SESSION['uid'])){
			$myuid = $_SESSION['uid'];
			$sql = "select  * from activity where album_id=".$aid." and uid=".$myuid;
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
	 	
		
		
		if(isset($_SESSION['uid']))	$myuid = $_SESSION['uid'];
		else $myuid = 0;
		$sql = "select * 
				from activity 
				where (rank is not NULL or comment is not NULL) and uid !=".$myuid." and activity.album_id=".$aid."
				ORDER BY updatetime DESC
				limit 30";
		$result = mysql_query($sql);
		echo "<div class='activitylist'>";
		
		echo "<ul>";
		while($row = mysql_fetch_array($result)){
			echo "<li>";
			getUsermini($row["uid"]);
			
			echo "<div class='activity albumactivity'>";
			
			echo "<div class='activitybref'>";
			getUserlink($row["uid"]);
			echo "<span>".$row["updatetime"]."</span>";
			if($row["rank"] != NULL){
				echo "<span>Rate</span>";
				echo "<span class='rankico appraise".$row["rank"]."'></span>";
			}else {
				echo "<span>Rate</span>";
				echo "<span class='rankico appraise0'></span>";
			}
			echo "</div>";

			if($row["comment"] != NULL) echo "<div class='albumcomment'><p class='quotecomment'><span>".$row["comment"]."</span></p></div>";
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
</html>