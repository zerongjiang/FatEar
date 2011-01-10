<?php
session_start();
include ("conn.php");
include ("functions.php");
$myuid;
$action;
$response ;

if(isset($_SESSION['uid'])){
$myuid = $_SESSION['uid'];
if(isset($_GET["action"])){
	
	$action = $_GET["action"]; 
		
	if($action == "follow"){
		$uid = $_GET["uid"];
		
		$sql = "SELECT * FROM follow WHERE uid1='$myuid' AND uid2='$uid'";
		$result = mysql_query($sql);
		
		if(mysql_num_rows($result) == 1){
			$sql = "DELETE FROM follow WHERE uid1='$myuid' AND uid2='$uid'";
			mysql_query($sql);
			$sql = "UPDATE follow SET friend = NULL WHERE uid2='$myuid' AND uid1='$uid'";
			mysql_query($sql);
			$response = "<a  class='a_btn_ff' onclick=\"ajaxFollow('$uid')\" >Follow</a>";
			$response = $response."<br/>";
			$response = $response."<a  class='a_btn_ff' onclick=\"ajaxFriend('add','$uid')\" >Add Friend</a>";
			echo $response;
		}
		else if(mysql_num_rows($result) == 0){
			$sql = "INSERT INTO follow (uid1,uid2) VALUES ('$myuid','$uid')";
			mysql_query($sql);
			$response = "<a  class='a_btn_ff' onclick=\"ajaxFollow('$uid')\" >unFollow</a>";
			$response = $response."<br/>";
			$response = $response."<a class='a_btn_ff' onclick=\"ajaxFriend('add','$uid')\" >Add Friend</a>";
			echo $response;
		}
	}
	
	else if($action == "friend"){
		$uid = $_GET["uid"];
		$sql = "SELECT * FROM follow WHERE uid1='$myuid' AND uid2='$uid'";
		$result = mysql_query($sql);
		
		if(mysql_num_rows($result) == 0){
			$sql = "INSERT INTO follow (uid1,uid2,friend) VALUES ('$myuid','$uid','request')";
			mysql_query($sql);
			$response = "<a class='a_btn_ff' onclick=\"ajaxFollow('$uid')\" >unFollow</a>";
			$response = $response."<br/>";
			$response = $response."<a class='a_btn_ff'  onclick=\"ajaxFriend('unrequest','$uid')\" >UnRequest</a>";
			echo $response;
		}
		else if(mysql_num_rows($result) == 1){
			
			switch ($_GET['type']){
				case 'unrequest':
					$sql = "UPDATE follow SET friend = null WHERE uid1='$myuid' AND uid2='$uid'";
					$response = "<a  class='a_btn_ff' onclick=\"ajaxFollow('$uid')\" >unFollow</a>";
					$response = $response."<br/>";
					$response = $response."<a  class='a_btn_ff' onclick=\"ajaxFriend('add','$uid')\" >Add Friend</a>";
					break;
				case 'del':
					$sql = "UPDATE follow SET friend = NULL WHERE (uid1='$myuid' AND uid2='$uid') OR (uid2='$myuid' AND uid1='$uid');";
					$response = "<a  class='a_btn_ff' onclick=\"ajaxFollow('$uid')\" >unFollow</a>";
					$response = $response."<br/>";
					$response = $response."<a  class='a_btn_ff' onclick=\"ajaxFriend('add','$uid')\" >Add Friend</a>";
					break;
				case 'add':
					$sql = "UPDATE follow SET friend = 'request' WHERE uid1='$myuid' AND uid2='$uid'";
					$response = "<a  class='a_btn_ff' onclick=\"ajaxFollow('$uid')\" >unFollow</a>";
					$response = $response."<br/>";
					$response = $response."<a  class='a_btn_ff' onclick=\"ajaxFriend('unrequest','$uid')\" >UnRequest</a>";
					break;
			  
			}
			mysql_query($sql);
			
			echo $response;
		}
		
	}
	
	else if($action == "actfriend"){
		$uid = $_GET["uid"];
		$sql = "SELECT * FROM follow WHERE uid1='$myuid' AND uid2='$uid'";
		$result = mysql_query($sql);
		
		if(mysql_num_rows($result) == 1){
			$row = mysql_fetch_array($result);
			switch ($row["friend"]){
				case NULL:
					$sql = "UPDATE follow SET friend = 'request' WHERE uid1='$myuid' AND uid2='$uid'";
					echo "Unrequest";
					break;
				case "request":
					$sql = "UPDATE follow SET friend = NULL WHERE uid1='$myuid' AND uid2='$uid'";
					echo "Add Friend";
					break;
				case "confirm":
					$sql = "UPDATE follow SET friend = NULL WHERE (uid1='$myuid' AND uid2='$uid') OR (uid2='$myuid' AND uid1='$uid');";
					echo "Add Friend";
					break;
			}
		}
		 mysql_query($sql);
	}
	
	else if($action == "frdaccept"){
		$uid = $_GET["uid"];
		$sql = "UPDATE follow SET friend = 'confirm' WHERE uid2='$myuid' AND uid1='$uid'";
		mysql_query($sql);
		$sql = "INSERT INTO follow (uid1,uid2,friend) VALUES ('$myuid','$uid','confirm') ON DUPLICATE KEY UPDATE friend='confirm'";
		mysql_query($sql);
	}
	
	else if($action == "frddecline"){
		$uid = $_GET["uid"];
		$sql = "UPDATE follow SET friend = NULL WHERE uid2='$myuid' AND uid1='$uid'";
		mysql_query($sql);
		$sql = "UPDATE follow SET friend = NULL WHERE uid1='$myuid' AND uid2='$uid'";
		mysql_query($sql);
	}
	
	
	
	else if($action == "rank"){
		$sid = $_GET["sid"];
		$rank = $_GET["rank"];
		$sql = "select * from activity where uid='$myuid' and sid='$sid'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		if($row == NULL ){
			$sql = "insert into activity (uid,sid,rank) values ('$myuid','$sid','$rank')";
			mysql_query($sql);

		}else{
			$sql = "update activity set rank ='$rank' where uid='$myuid' and sid='$sid'";
			mysql_query($sql);
		}
		echo "<h4>Rank It:</h4>";
		getGrade($myuid,$sid);
		getGrades($sid);
	}
	
	else if($action == "rankalbum"){
		$aid = $_GET["aid"];
		$rank = $_GET["rank"];
		$sql = "select * from activity where uid='$myuid' and album_id='$aid'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		if($row == NULL ){
			$sql = "insert into activity (uid,album_id,rank) values ('$myuid','$aid','$rank')";
			mysql_query($sql);

		}else{
			$sql = "update activity set rank ='$rank' where uid='$myuid' and album_id='$aid'";
			mysql_query($sql);
		}
		echo "<h4>Rank It:</h4>";
		getAlbumGrade($myuid,$aid);
		getAlbumGrades($aid);
	}
	
	else if($action == "rankartist"){
		$aid = $_GET["aid"];
		$rank = $_GET["rank"];
		$sql = "select * from activity where uid='$myuid' and artist_id='$aid'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		if($row == NULL ){
			$sql = "insert into activity (uid,artist_id,rank) values ('$myuid','$aid','$rank')";
			mysql_query($sql);

		}else{
			$sql = "update activity set rank ='$rank' where uid='$myuid' and artist_id='$aid'";
			mysql_query($sql);
		}
		echo "<h4>Rank It:</h4>";
		getArtistGrade($myuid,$aid);
		getArtistGrades($aid);
	}

	
	
	else if($action == "likeSong"){
		$sid = $_GET["sid"];
		$sql = "select * from activity where uid='$myuid' and sid='$sid'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		if($row == NULL ){
			$sql = "insert into activity (uid,sid,challenge) values ('$myuid','$sid',1)";
			mysql_query($sql);

		}else{
			if($row["challenge"] == 1){
			$sql = "update activity set challenge =0 where uid='$myuid' and sid='$sid'";
			mysql_query($sql);}
			else if(($row["challenge"] == 0)){
			$sql = "update activity set challenge =1 where uid='$myuid' and sid='$sid'";
			mysql_query($sql);
			}
		}
		getLikeusers($sid);
	}
	
	else if($action == "likeAlbum"){
		$aid = $_GET["aid"];
		$sql = "select * from activity where uid='$myuid' and album_id='$aid'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		if($row == NULL ){
			$sql = "insert into activity (uid,album_id,challenge) values ('$myuid','$aid',1)";
			mysql_query($sql);

		}else{
			if($row["challenge"] == 1){
			$sql = "update activity set challenge =0 where uid='$myuid' and album_id='$aid'";
			mysql_query($sql);}
			else if(($row["challenge"] == 0)){
			$sql = "update activity set challenge =1 where uid='$myuid' and album_id='$aid'";
			mysql_query($sql);
			}
		}
		getLikeAlbumusers($aid);
	}
	
		else if($action == "likeArtist"){
		$aid = $_GET["aid"];
		$sql = "select * from activity where uid='$myuid' and artist_id='$aid'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		if($row == NULL ){
			$sql = "insert into activity (uid,artist_id,challenge) values ('$myuid','$aid',1)";
			mysql_query($sql);

		}else{
			if($row["challenge"] == 1){
			$sql = "update activity set challenge =0 where uid='$myuid' and artist_id='$aid'";
			mysql_query($sql);}
			else if(($row["challenge"] == 0)){
			$sql = "update activity set challenge =1 where uid='$myuid' and artist_id='$aid'";
			mysql_query($sql);
			}
		}
		getLikeArtistusers($aid);
	}
	
	
	else if($action == "likeSongmini"){
		$sid = $_GET["sid"];
		$sql = "select * from activity where uid='$myuid' and sid='$sid'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		if($row == NULL ){
			$sql = "insert into activity (uid,sid,challenge) values ('$myuid','$sid',1)";
			mysql_query($sql);

		}else{
			if($row["challenge"] == 1){
			$sql = "update activity set challenge =0 where uid='$myuid' and sid='$sid'";
			mysql_query($sql);}
			else if(($row["challenge"] == 0)){
			$sql = "update activity set challenge =1 where uid='$myuid' and sid='$sid'";
			mysql_query($sql);
			}
		}
	}


	
}

if(isset($_POST["action"])){
	$action =$_POST["action"];
	if($action == "postcomment"){
		$sid = $_POST["sid"];
		$comment = addslashes($_POST["comment"]);
		$sql = "select * from activity where uid='$myuid' and sid='$sid'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		if($row == NULL ){
			$sql = "insert into activity (uid,sid,comment) values ('$myuid','$sid','$comment')";
			mysql_query($sql);
			
		}else{
			$sql = "update activity set comment ='$comment' where uid='$myuid' and sid='$sid'";
			mysql_query($sql);
		}
	}
	
	else  if($action == "postAlbumcomment"){
		$aid = $_POST["aid"];
		$comment = addslashes($_POST["comment"]);
		$sql = "select * from activity where uid='$myuid' and album_id='$aid'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		if($row == NULL ){
			$sql = "insert into activity (uid,album_id,comment) values ('$myuid','$aid','$comment')";
			mysql_query($sql);
			
		}else{
			$sql = "update activity set comment ='$comment' where uid='$myuid' and album_id='$aid'";
			mysql_query($sql);
		}
	}
	
	else  if($action == "postArtistcomment"){
		$aid = $_POST["aid"];
		$comment = addslashes($_POST["comment"]);
		$sql = "select * from activity where uid='$myuid' and artist_id='$aid'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		if($row == NULL ){
			$sql = "insert into activity (uid,artist_id,comment) values ('$myuid','$aid','$comment')";
			mysql_query($sql);
			
		}else{
			$sql = "update activity set comment ='$comment' where uid='$myuid' and artist_id='$aid'";
			mysql_query($sql);
		}
	}

	


}

}

if(isset($_GET["action"])){
	$action = $_GET["action"]; 
	if($action == "getsurl"){
		$sid = $_GET["sid"];
		
	echo "<div id='player'>";
	getSongplayer($sid,"yes");
	getLikeminibtn($sid);
	echo "</div>";
	}
	
	if($action == "search"){
		$keyword = $_GET["keyword"];
		echo "<p class=\"auto_complete_tip\">Suggestion</p>";
		
		
		echo "<table><tbody>";
		//song search
		$sql = "select * from song natural left join artist where title like '".$keyword."%' limit 6";
		$result = mysql_query($sql);
		if($row = mysql_fetch_array($result)){
		echo "<tr>
		<th><span>Songs</span></th><td><ul>";
		do{
		echo "<li>";
		echo "<a href='song.php?sid=".$row["sid"]."'>".$row["title"]."<span class='gray'> - ".$row["artist_name"]."</span></a>";
		echo "</li>";
		}while ($row = mysql_fetch_array($result));echo "</ul></td></tr>";}
		
		//albums
		$sql = "select * from album natural left join artist where album_name like '".$keyword."%' limit 6";
		$result = mysql_query($sql);
		if($row = mysql_fetch_array($result)){
		echo "<tr>
		<th><span>Albums</span></th><td><ul>";
		
		do{
		echo "<li>";
		echo "<a href='album.php?aid=".$row["album_id"]."'>
		<div style='display: inline-block;float:left;' class=\"img30\">
		<span><img style='display: inline-block; border: none; float:left;' width='30' height='30' src='".$row["album_cover"]."'></span></div>
		<span style='display: inline-block; float:left;'>".$row["album_name"]."</span><br><span style='display: inline-block ;float:left;'>".$row["artist_name"]."</span>
		</a>";
		echo "</li>";
			
		}while ($row = mysql_fetch_array($result));	
		echo "</ul></td></tr>";}
		
		
		//artists
		$sql = "select * from artist where artist_name like '".$keyword."%' limit 6";
		$result = mysql_query($sql);
		if($row = mysql_fetch_array($result)){
		echo "<tr>
		<th><span>Artists</span></th><td><ul>";
		do{
		echo "<li>";
		echo "<a href='artist.php?aid=".$row["artist_id"]."'>
		<div style='display: inline-block;float:left;' class=\"img30\">
		<span><img style='display: inline-block; border: none; float:left;' width='30' height='30' src='".$row["artist_avatar"]."'></span></div>
		<span style='display: inline-block ;float:left;'>".$row["artist_name"]."</span>
		</a>";		
		echo "</li>";
			
		} while($row = mysql_fetch_array($result));	echo "</ul></td></tr>";}
		echo "</tbody></table>";
		echo "<p><a href='search.php?keyword=".$keyword."'>More Results</a></p>";
	}
}



?>
