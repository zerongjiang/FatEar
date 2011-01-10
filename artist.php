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
<link rel="stylesheet" type="text/css" href="css/artist.css" />

<script type="text/javascript" src="js/jquery.js"></script>
<script src="js/ajax.js" type="text/javascript"></script>

<script type="text/javascript">

<?php if(isset($_GET["aid"])) echo "aid=".$_GET["aid"];?>


$(document).ready(function(){
	$("#header a:eq(3)").addClass("a_btn_focus");
	
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
			if($("#like_btn span").html() == "Your are Fans"){ $("#like_btn span").html("Be his/her Fans");}
			else if($("#like_btn span").html() == "Be his/her Fans"){ $("#like_btn span").html("Your are Fans");}
			$("#likeusers").html(data);
		},
		data:{action:"likeArtist",aid: aid}});
	});
	
	$("#mycomment a").click(function(){
	  if($(this).html()=="Post"){
		  	mycomment = $("#mycomment textarea").val();
			$.ajax({url:"ajax.php",async:false,type:"POST",
			success: function(){
				$("#mycomment p").html(mycomment);
				$("#mycomment a").html("Modify");
			},
			data:{action:"postArtistcomment",aid: aid,comment:mycomment}});
	  }
	  else if($(this).html()=="Modify"){
		  $(this).html("Post");
		  mycomment = $("#mycomment p").html();
		  $("#mycomment p").html("<textarea>"+mycomment+"</textarea>");
	  }
  	});


	
	
	
});

</script>
<title>
<?php
 if(isset($_GET["aid"])){
	$sql = "select artist_name from artist where artist_id=".$_GET["aid"];
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
    echo $row["artist_name"]."";
 }else echo "Artists Homepage";
?> </title>

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
    <a href='artist.php' class='a_btn'>Recent</a>";
	if(isset($_SESSION['uid']))echo "<a href='artist.php?action=social' class='a_btn'>Fo&amp;Fr</a>";
	echo	"<a href='artist.php?action=list' class='a_btn'>All Artists</a>";
    echo "</div>";
	
	if(!isset($_GET["action"])){
		echo "<script language='javascript'>$(\"#navi a:eq(0)\").addClass(\"a_btn_focus\");</script>";
		
		$sql = "select * 
				from activity join artist
				where artist.artist_id=activity.artist_id and activity.artist_id is not null 
				ORDER BY updatetime DESC
				limit 0,10";
		$result = mysql_query($sql);
		echo "<div class='activitylist artistactivitylist'>";
		echo "<ul>";
		while($row = mysql_fetch_array($result)){
			echo "<li>";
			getUsermini($row["uid"]);
			echo "<div class='activity artistactivity'>";
			echo "<div class='activitybref'>";
			getUserlink($row["uid"]);
			if($row["challenge"] != NULL){
				echo "<span>Likes</span>";
				echo "<a href='artist.php?aid=".$row["artist_id"]."'><strong>".$row["artist_name"]."</strong></a>";
				if($row["rank"] != NULL){
					echo "<span>Rated with</span>";
					echo "<span class='rankico appraise".$row["rank"]."'></span>";
					}
				if($row["comment"] != NULL){
						echo "<span>And Say:</span>";}
			}else if($row["rank"] != NULL){
				echo "<span>Rated</span>";
				echo "<a href='artist.php?aid=".$row["artist_id"]."'><strong>".$row["artist_name"]."</strong></a>";
				echo "<span class='rankico appraise".$row["rank"]."'></span>";
				if($row["comment"] != NULL){
				echo "<span>And Say:</span>";}
			}else if($row["comment"] != NULL){
				echo "<span>Commented</span>";
				echo "<a href='artist.php?aid=".$row["artist_id"]."'><strong>".$row["artist_name"]."</strong></a>";
			}
			
			echo  "<a style='display:none' class='checkit' href='artist.php?aid=".$row["artist_id"]."'>Check It</a>";
			echo "</div>";
			
			echo "<div class='artistavatar100'>";
			echo "<a class='' title='".$row["artist_name"]."' href='artist.php?aid=".$row["artist_id"]."'>"
			  ."<img alt='".$row["artist_name"]."' class='' src=\"".$row["artist_avatar"]."\"></img>"."</a>";
			echo "</div>";
			
			if($row["comment"] != NULL) echo "<div class='artistcomment'><p class='quotecomment'><span>".$row["comment"]."</span></p></div>";
			echo "</div>";
			clear();echo "</li>";

		}

		echo "</ul>";
		echo "</div>";
	} // no action default artist page
	
	if(isset($_GET["action"])){
		
		if($_GET["action"] == "social"){

		if(isset($_SESSION['uid'])){
			$myuid = $_SESSION['uid'];
			echo "<script language='javascript'>$(\"#navi a:eq(1)\").addClass(\"a_btn_focus\");</script>";
			
			$sql = "select * 
				from activity join artist
				where artist.artist_id=activity.artist_id and activity.artist_id is not null
							  and uid in( select uid2
							  from follow
							  where uid1=".$myuid.")
				ORDER BY updatetime DESC
				limit 0,10";
				
		$result = mysql_query($sql);
		echo "<div class='activitylist artistactivitylist'>";
		echo "<ul>";
		while($row = mysql_fetch_array($result)){
			echo "<li>";
			getUsermini($row["uid"]);
			echo "<div class='activity artistactivity'>";
			echo "<div class='activitybref'>";
			getUserlink($row["uid"]);
			if($row["challenge"] != NULL){
				echo "<span>Likes</span>";
				echo "<a href='artist.php?aid=".$row["artist_id"]."'><strong>".$row["artist_name"]."</strong></a>";
				if($row["rank"] != NULL){
					echo "<span>Rated with</span>";
					echo "<span class='rankico appraise".$row["rank"]."'></span>";
					}
				if($row["comment"] != NULL){
						echo "<span>And Say:</span>";}
			}else if($row["rank"] != NULL){
				echo "<span>Rated</span>";
				echo "<a href='artist.php?aid=".$row["artist_id"]."'><strong>".$row["artist_name"]."</strong></a>";
				echo "<span class='rankico appraise".$row["rank"]."'></span>";
				if($row["comment"] != NULL){
				echo "<span>And Say:</span>";}
			}else if($row["comment"] != NULL){
				echo "<span>Commented</span>";
				echo "<a href='artist.php?aid=".$row["artist_id"]."'><strong>".$row["artist_name"]."</strong></a>";
			}
			
			echo  "<a style='display:none' class='checkit' href='artist.php?aid=".$row["artist_id"]."'>Check It</a>";
			echo "</div>";
			
			echo "<div class='artistavatar100'>";
			echo "<a class='' title='".$row["artist_name"]."' href='artist.php?aid=".$row["artist_id"]."'>"
			  ."<img alt='".$row["artist_name"]."' class='' src=\"".$row["artist_avatar"]."\"></img>"."</a>";
			echo "</div>";
			
			if($row["comment"] != NULL) echo "<div class='artistcomment'><p class='quotecomment'><span>".$row["comment"]."</span></p></div>";
			echo "</div>";
			clear();echo "</li>";

		}

		echo "</ul>";
		echo "</div>";
		}} //action is social
	
		if($_GET["action"] == "list"){
			if(isset($_SESSION['uid']))	echo "<script   language= 'javascript'> $(\"#navi a:eq(2)\").addClass(\"a_btn_focus\");</script>";
			else 						echo "<script   language= 'javascript'> $(\"#navi a:eq(1)\").addClass(\"a_btn_focus\");</script>";
			echo "<div id='alphabet'";
			for($i=65; $i<=90;$i++){
				echo "<a href='artist.php?action=list&alphabet=".chr($i+32)."'>".chr($i)."</a>";
			}
			echo "</div>";
			
			echo "<div id='artistlist'>";
			echo "<ul>";
			
			if(!isset($_GET["alphabet"])){
				$sql = "select * 
						from  artist
						ORDER BY artist_id DESC
						limit 0,30";
				$result = mysql_query($sql);
				while($row = mysql_fetch_array($result)){
						echo "<li>";
						echo "<div class='artistavatar100'>";
						echo "<a class='' title='".$row["artist_name"]."' href='artist.php?aid=".$row["artist_id"]."'>"
					  ."<img alt='".$row["artist_name"]."' class='' src=\"".$row["artist_avatar"]."\"></img>"."</a>";
						echo "</div>";
						echo "<span><a href='artist.php?aid=".$row["artist_id"]."'>".$row["artist_name"]."</a></span>";
						echo "</li>";
				
				}
				echo "</ul>";echo "</div>";}
			else{
				$alphabet = $_GET["alphabet"];
				$sql = "select * 
						from  artist
						where artist_name like '".$alphabet."%'
						ORDER BY artist_id DESC
						limit 0,30";
				$result = mysql_query($sql);
				while($row = mysql_fetch_array($result)){
						echo "<li>";
						echo "<div class='artistavatar100'>";
						echo "<a class='' title='".$row["artist_name"]."' href='artist.php?aid=".$row["artist_id"]."'>"
					  		."<img alt='".$row["artist_name"]."' class='' src=\"".$row["artist_avatar"]."\"></img>"."</a>";
						echo "</div>";
						echo "<span><a href='artist.php?aid=".$row["artist_id"]."'>".$row["artist_name"]."</a></span>";
						echo "</li>";
				}
				echo "</ul>";echo "</div>";}
		}
	} //action
	
	
	} //no album id
	?>
    
    
    
    
    
    <?php	
	
		if(isset($_GET["aid"])){
		echo "<div id='artistprofile'>";
		$aid =$_GET["aid"];
		$sql = "select * from artist where artist_id=".$aid;
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		echo "<div class='artistavatar'>";
		echo "<a class='' title='".$row["artist_name"]."' href='artist.php?aid=".$row["artist_id"]."'>"
		  ."<img alt='".$row["artist_name"]."' class='' src=\"".$row["artist_avatar"]."\"></img>"."</a>";
		echo "</div>";
		echo "<div id='artistdetail'>";
		echo "<h2>".$row["artist_name"]."</h2>";
		  
		  /*echo "<table>";
		  echo "<tbody>";
		  
		  echo "<tr>";
		  echo "<td class='rc1'>Artist</td>";
		  if($row["artist_name"]!=NULL)
		  	echo "<td>"."<a href='artist.php?aid=".$row["artist_id"]."'><strong>".$row["artist_name"]."</strong></a>"."</td>";
		  else 	echo "<td>Unknown</td>";
		  echo "</tr>";
		  
		  echo "</tbody>";
		  echo "</table>";*/
		  
		   echo "<h6>Profile:</h6>";
		  if($row["artist_profile"]!=NULL) echo "<p>".$row["artist_profile"]."</p>";
		  else echo "<p>No Profile Yet :(</p>";
		  
		echo "</div>";
		echo "</div>";
		
		
		$sql = "select * from song natural join songurl  where artist_id=".$aid;
		$result = mysql_query($sql);
		echo "<div id='artistsonglist'>";
		echo "<h4>Songs</h4>";
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
		
		
		$sql = "select *
				from album natural join artist
				where artist_id =".$aid."	
				order by album_id desc";
		$result = mysql_query($sql);
		echo "<div id='artistalbumlist'>";

		echo "<div class='albumlist'>";
			echo "<h4>Albums</h4>";
			echo "<ul>";
			while($row = mysql_fetch_array($result)){
				echo "<li id='album".$row["album_id"]."'>";
				echo "<div id='albumcover100'>";
				echo "<a class='cdcover100' title='".$row["album_name"]."' href='album.php?aid=".$row["album_id"]."'>".
					"<img alt='".$row["album_name"]."' class='cdimg100' src=\"".$row["album_cover"]."\"></img>"."</a>";
		  		echo "</div>";
				echo "<span><a href='album.php?aid=".$row["album_id"]."'>".$row["album_name"]."</a></span>";
				echo "</li>";
		}
		echo "</ul>";
		echo "</div>";
		echo "</div>";
		
		
		
		if(isset($_SESSION['uid'])){
			echo "<div class='like_btn_block'>";
			getArtistLikebtn($aid);
			echo "</div>";
		}
		echo "<div id='likeusers'>";
		getLikeArtistusers($aid);
		echo "</div>";
		
		echo "<div id='songtool'>";

		echo "<div id='grader'>";
		echo "<h4>Rank It:</h4>";
		if(isset($_SESSION['uid'])){
			$myuid = $_SESSION['uid'];
			getArtistGrade($myuid,$aid);
		}
		getArtistGrades($aid);
	 	echo "</div>";
		
		echo "<div id='mycomment'>";		
		if(isset($_SESSION['uid'])){
			$myuid = $_SESSION['uid'];
			$sql = "select  * from activity where artist_id=".$aid." and uid=".$myuid;
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
				where (rank is not NULL or comment is not NULL) and uid !=".$myuid." and activity.artist_id=".$aid."
				ORDER BY updatetime DESC
				limit 30";
		$result = mysql_query($sql);
		echo "<div class='activitylist'>";
		
		echo "<ul>";
		while($row = mysql_fetch_array($result)){
			echo "<li>";
			getUsermini($row["uid"]);
			
			echo "<div class='activity artistactivity'>";
			
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

			if($row["comment"] != NULL) echo "<div class='artistcomment'><p class='quotecomment'><span>".$row["comment"]."</span></p></div>";
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
    getSidebar();?>
	</div>
</div>




<?php clear();getFooter();?></body>
</html>