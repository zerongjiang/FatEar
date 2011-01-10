<?php 
include ("conn.php");

$myuid;

if(isset($_SESSION['uid'])){
	$myuid = $_SESSION['uid'];
}

function clear(){
	echo "<div class='clear'></div>";}

function getHeader()
{
	
	echo "<a href=\"index.php\" class='a_btn'>Home</a>";
	echo "<a href=\"song.php\" class='a_btn'>Songs</a>";

	echo "<a href=\"album.php\" class='a_btn'>Albums</a>";

	echo "<a href=\"artist.php\" class='a_btn'>Artists</a>";
	echo "<form style=\" display: inline-block;\"action='search.php' method=\"get\">";
	echo "<input id='searchbox' type=\"text\" name='keyword' value=\"Search\">";
	echo "</form>";
	echo "<div id=\"auto_complete\" class='hidden'></div>";
	
	echo "<a class='a_btn'>Earfat©Zerong Jiang</a>";

            if(isset($_SESSION['uid'])){
				$myuid = $_SESSION['uid'];
				echo "<div id='myself'>";
                echo "<a class='a_btn' href=\"profile.php\">".$_SESSION['user']."</a>";
				
				$sql = "SELECT count(uid1) as msgnum FROM follow,user WHERE uid1=uid and uid2='$myuid' and friend='request'";
				$result = mysql_query($sql);
				$row = mysql_fetch_array($result);
				
                echo "<a class='a_btn' href=\"msg.php\">Msg(".$row["msgnum"].")</a>";
				echo "<a class='a_btn' href='index.php?action=logout' >Logout</a>";
				echo "</div>";
            }	
	
	
}


function getSidebar()
{
			$uid;
			
			$myuid;
			
			if(isset($_GET["id"])) $uid = $_GET["id"];
			if(isset($_SESSION['uid'])) $myuid = $_SESSION['uid'];
			
			if(!isset($_SESSION['uid'])) getWidgetLogin();
			
			if(isset($_GET["id"])) getUserinfo($uid);
			else if(isset($_SESSION['uid'])) getUserinfo($myuid);				

}

function getFooter(){
	echo "<div id='footer'>© 2011 Zerong Jiang <span></span></div>";
}

function getWidgetLogin(){
	
				echo "<div id='login'>";
                if(isset($_SESSION['login'])&&$_SESSION['login']=='failed'){
                    echo "Login Failed !!!!";
                }
                echo "<form action='index.php' method='post'>
                <div><label>Username:</label><input type='text'  name='username' id='user'/></div>
                <div><label>Password:</label><input type='password' name='password' id='pwd'/></div>
                <div><input class='inputbtn' type='submit' value='Log In'/>
				<input type='button' class='inputbtn' onSubmit=\"return false;\" onclick=\"location.href='register.php';\" value=\"Register\"></div>
				</form>";
				
				echo "</div>";
				
}


function getUserinfo($uid)
{
				$sql = "SELECT * FROM user WHERE uid='$uid'";
				$result = mysql_query($sql);
				$row = mysql_fetch_array($result);
				echo "<div id='userinfo'>";
				echo "<div class='userlarge'>";
				echo "<a title=\"".$row["username"]."\" href=\"".$row["avatar"]."\"><img src=\"".$row["avatar"]."\"  class=\"avatarlarge\"/></a>";
				echo "</div>";
				echo "<a href=\"user.php?id=".$row["uid"]."\">".$row["username"]."</a>";	
				echo "</div>";
				getUsertools();
				echo "<div id='userdetail'>";
				echo "<table><tbody>";
				echo "<tr>";
				echo  "<td>Age:</td>";
				echo  "<td>".$row["age"]."</td>";
				echo "</tr>";
				echo "<tr>";
				echo  "<td>City:</td>";
				echo  "<td>".$row["city"]."</td>";
				echo "</tr>";
				echo "</tbody></table>";
				if($row["profile"] != NULL) 
						echo  "<p>".htmlspecialchars($row["profile"])."</p>";
				else 
						echo  "<p>This guy is lazy</p>";

				
				echo "</div>";
				
				getUserralation($uid);


}


function getUsertools(){
				if(isset($_GET["id"])){
					 $uid = $_GET["id"];
				if(isset($_SESSION['uid'])){
					 $myuid = $_SESSION['uid'];
				if($myuid != $uid) {
					echo "<div id='usertool'>";
					$sql = "SELECT * FROM follow WHERE uid1='$myuid' AND uid2='$uid'";
					$result = mysql_query($sql);
					if(mysql_num_rows($result) == 1){
						echo "<a  class='a_btn_ff' onclick=\"ajaxFollow('$uid')\" >unFollow</a>";
						echo "<br/>";
						$row = mysql_fetch_array($result);
						switch ($row["friend"])
							{
							case "request":
							  echo "<a  class='a_btn_ff' onclick=\"ajaxFriend('unrequest','$uid')\" >UnRequest</a>";
							  break;  
							case "confirm":
							  echo "<a  class='a_btn_ff' onclick=\"ajaxFriend('del','$uid')\" >Unfriend</a>";
							  break;
							default:
							  echo "<a  class='a_btn_ff' onclick=\"ajaxFriend('add','$uid')\" >Add Friend</a>";
							}
					}
					else{ echo "<a  class='a_btn_ff' onclick=\"ajaxFollow('$uid')\" >Follow</a>";
					echo "<br/>";
					echo "<a  class='a_btn_ff' onclick=\"ajaxFriend('add','$uid')\" >Add Friend</a>";
					}
					
					echo "</div>";
				}
				}
				}
}


function getUserralation($uid){
	
	$sql = "select uid1 from follow where uid2='$uid'"	;	
	$result = mysql_query($sql);
	echo "<div id='ffinfo'>";
	echo "<h4>Followers: ".mysql_num_rows($result)."</h4>";
	echo "<div>";
	while($row = mysql_fetch_array($result)){
		getUsermini($row["uid1"]);
	}
	echo "</div>";
	
	$sql = "select uid2 from follow where uid1='$uid'"	;	
	$result = mysql_query($sql);
	echo "<h4>Following: ".mysql_num_rows($result)."</h4>";
	echo "<div>";
	while($row = mysql_fetch_array($result)){
		getUsermini($row["uid2"]);
	}
	echo "</div>";
	
	
	$sql = "select uid2 from follow where uid1='$uid' and friend='confirm'"	;	
	$result = mysql_query($sql);
	echo "<h4>Friends: ".mysql_num_rows($result)."</h4>";
	echo "<div>";
	while($row = mysql_fetch_array($result)){
		getUsermini($row["uid2"]);
	}
	echo "</div>";
	echo "</div>";
}




function getUsermini($uid)
{
				$sql = "SELECT * FROM user WHERE uid='$uid'";
				$result = mysql_query($sql);
				$row = mysql_fetch_array($result);
				echo "<div class='usermini'>";
				echo "<a title=\"".$row["username"]."\" href=\"user.php?id=".$row["uid"]."\"> 
				<img src=\"".$row["avatar"]."\" class=\"avatarmini\"/></a>";
				echo "</div>";
}

function getUserlink($uid){
				$sql = "SELECT * FROM user WHERE uid='$uid'";
				$result = mysql_query($sql);
				$row = mysql_fetch_array($result);
				
				echo "<a href=\"user.php?id=".$row["uid"]."\">".$row["username"]."</a>";

}

function getUsername($uid){
				$sql = "SELECT * FROM user WHERE uid='$uid'";
				$result = mysql_query($sql);
				$row = mysql_fetch_array($result);
				
				echo $row["username"];

}


function getGrade($uid,$sid){
	 	$sql = "select * from activity where uid=".$uid." and sid=".$sid;
			 //echo $sql;
			 $result = mysql_query($sql);
			 $row = mysql_fetch_array($result);
	
			 if(mysql_num_rows($result) == 0 || $row["rank"] == NULL){
				 echo "<p class='grade'>";
				 	echo "<a onclick='ajaxGrade(".$sid.",1)'></a>";
				 	echo "<a onclick='ajaxGrade(".$sid.",2)'></a>";
					echo "<a onclick='ajaxGrade(".$sid.",3)'></a>";
					echo "<a onclick='ajaxGrade(".$sid.",4)'></a>";
					echo "<a onclick='ajaxGrade(".$sid.",5)'></a>";
				 echo "</p>";
			 }
			 else{
				 $rank = $row["rank"] ;
				 echo "<p class='grade'>";
				 	for($i = 1; $i <= 5 ; $i++){
						if($i <= $rank)	 	echo "<a class='selected' onclick='ajaxGrade(".$sid.",".$i.")'></a>";
						else echo "<a onclick='ajaxGrade(".$sid.",".$i.")'></a>";
					}
				 echo "</p>";
			 }	 
}

function getAlbumGrade($uid,$aid){
	 		 $sql = "select * from activity where uid=".$uid." and album_id=".$aid;
			 //echo $sql;
			 $result = mysql_query($sql);
			 $row = mysql_fetch_array($result);
	
			 if(mysql_num_rows($result) == 0 || $row["rank"] == NULL){
				 echo "<p class='grade'>";
				 	echo "<a onclick='ajaxGradeAlbum(".$aid.",1)'></a>";
				 	echo "<a onclick='ajaxGradeAlbum(".$aid.",2)'></a>";
					echo "<a onclick='ajaxGradeAlbum(".$aid.",3)'></a>";
					echo "<a onclick='ajaxGradeAlbum(".$aid.",4)'></a>";
					echo "<a onclick='ajaxGradeAlbum(".$aid.",5)'></a>";
				 echo "</p>";
			 }
			 else{
				 $rank = $row["rank"];
				 echo "<p class='grade'>";
				 	for($i = 1; $i <= 5 ; $i++){
						if($i <= $rank)	 	echo "<a class='selected' onclick='ajaxGradeAlbum(".$aid.",".$i.")'></a>";
						else echo "<a onclick='ajaxGradeAlbum(".$aid.",".$i.")'></a>";
					}
				 echo "</p>";
			 }	 
}


function getArtistGrade($uid,$aid){
	 		 $sql = "select * from activity where uid=".$uid." and artist_id=".$aid;
			 //echo $sql;
			 $result = mysql_query($sql);
			 $row = mysql_fetch_array($result);
	
			 if(mysql_num_rows($result) == 0 || $row["rank"] == NULL){
				 echo "<p class='grade'>";
				 	echo "<a onclick='ajaxGradeArtist(".$aid.",1)'></a>";
				 	echo "<a onclick='ajaxGradeArtist(".$aid.",2)'></a>";
					echo "<a onclick='ajaxGradeArtist(".$aid.",3)'></a>";
					echo "<a onclick='ajaxGradeArtist(".$aid.",4)'></a>";
					echo "<a onclick='ajaxGradeArtist(".$aid.",5)'></a>";
				 echo "</p>";
			 }
			 else{
				 $rank = $row["rank"];
				 echo "<p class='grade'>";
				 	for($i = 1; $i <= 5 ; $i++){
						if($i <= $rank)	 	echo "<a class='selected' onclick='ajaxGradeArtist(".$aid.",".$i.")'></a>";
						else echo "<a onclick='ajaxGradeArtist(".$aid.",".$i.")'></a>";
					}
				 echo "</p>";
			 }	 
}





function getGrades($sid){
	$ranks = array(0,0,0,0,0,0);
	 $sql = "select  rank, count(uid) as num from activity where rank is not NULL and sid=".$sid." group by rank";
	 $result = mysql_query($sql);
	 while($row = mysql_fetch_array($result)){
		  $ranks[$row["rank"]] = $row["num"];
	 }
	 echo "<ul>";
	 echo "<li ><span class='rankico appraise5'></span>".$ranks[5]."</li>";
	 echo "<li ><span class='rankico appraise4'></span>".$ranks[4]."</li>";
	 echo "<li ><span class='rankico appraise3'></span>".$ranks[3]."</li>";
	 echo "<li ><span class='rankico appraise2'></span>".$ranks[2]."</li>";
	 echo "<li ><span class='rankico appraise1'></span>".$ranks[1]."</li>";
	 echo "</ul>";
}


function getAlbumGrades($aid){
	 $ranks = array(0,0,0,0,0,0);
	 $sql = "select  rank, count(uid) as num from activity where rank is not NULL and album_id=".$aid." group by rank";
	 $result = mysql_query($sql);
	 while($row = mysql_fetch_array($result)){
		  $ranks[$row["rank"]] = $row["num"];
	 }
	 echo "<ul>";
	 echo "<li ><span class='rankico appraise5'></span>".$ranks[5]."</li>";
	 echo "<li ><span class='rankico appraise4'></span>".$ranks[4]."</li>";
	 echo "<li ><span class='rankico appraise3'></span>".$ranks[3]."</li>";
	 echo "<li ><span class='rankico appraise2'></span>".$ranks[2]."</li>";
	 echo "<li ><span class='rankico appraise1'></span>".$ranks[1]."</li>";
	 echo "</ul>";
}



function getArtistGrades($aid){
	 $ranks = array(0,0,0,0,0,0);
	 $sql = "select  rank, count(uid) as num from activity where rank is not NULL and artist_id=".$aid." group by rank";
	 $result = mysql_query($sql);
	 while($row = mysql_fetch_array($result)){
		  $ranks[$row["rank"]] = $row["num"];
	 }
	 echo "<ul>";
	 echo "<li ><span class='rankico appraise5'></span>".$ranks[5]."</li>";
	 echo "<li ><span class='rankico appraise4'></span>".$ranks[4]."</li>";
	 echo "<li ><span class='rankico appraise3'></span>".$ranks[3]."</li>";
	 echo "<li ><span class='rankico appraise2'></span>".$ranks[2]."</li>";
	 echo "<li ><span class='rankico appraise1'></span>".$ranks[1]."</li>";
	 echo "</ul>";
}



function getLikeusers($sid){
		$sql = "select uid from activity where challenge=1 and sid=".$sid;
		$result = mysql_query($sql);
		echo "<h3><span>".mysql_num_rows($result)."</span> like this song</h3>";
		echo "<div id='likeuserlist'>";
		while($row = mysql_fetch_array($result)){
			getUsermini($row["uid"]);
		}
		echo "</div>";
}

function getLikeAlbumusers($aid){
		$sql = "select uid from activity where challenge=1 and album_id=".$aid;
		$result = mysql_query($sql);
		echo "<h3><span>".mysql_num_rows($result)."</span> like this album</h3>";
		echo "<div id='likeuserlist'>";
		while($row = mysql_fetch_array($result)){
			getUsermini($row["uid"]);
		}
		echo "</div>";
}

function getLikeArtistusers($aid){
		$sql = "select uid from activity where challenge=1 and artist_id=".$aid;
		$result = mysql_query($sql);
		echo "<h3><span>".mysql_num_rows($result)."</span> are Fans</h3>";
		echo "<div id='likeuserlist'>";
		while($row = mysql_fetch_array($result)){
			getUsermini($row["uid"]);
		}
		echo "</div>";
}



function getLikebtn($sid){
	if(isset($_SESSION['uid']))
	{$myuid = $_SESSION['uid'];
	$sql = "select * from activity where challenge=1 and uid=".$myuid." and sid=".$sid;
	$result = mysql_query($sql);
	if($row = mysql_fetch_array($result)){
		echo "<a id='like_btn'><span>You Like This</span></a>";
		
	}else{
		echo "<a id='like_btn'><span>Like It</span></a>";
	}
	
	}
}

function getAlbumLikebtn($aid){
	if(isset($_SESSION['uid']))
	{$myuid = $_SESSION['uid'];
	$sql = "select * from activity where challenge=1 and uid=".$myuid." and album_id=".$aid;
	$result = mysql_query($sql);
	if($row = mysql_fetch_array($result)){
		echo "<a id='like_btn'><span>You Like This</span></a>";
		
	}else{
		echo "<a id='like_btn'><span>Like It</span></a>";
	}
	
	}
}


function getArtistLikebtn($aid){
	if(isset($_SESSION['uid']))
	{$myuid = $_SESSION['uid'];
	$sql = "select * from activity where challenge=1 and uid=".$myuid." and artist_id=".$aid;
	$result = mysql_query($sql);
	if($row = mysql_fetch_array($result)){
		echo "<a id='like_btn'><span>Your are Fans</span></a>";
		
	}else{
		echo "<a id='like_btn'><span>Be his/her Fans</span></a>";
	}
	
	}
}




function getLikeminibtn($sid){
	if(isset($_SESSION['uid']))
	{$myuid = $_SESSION['uid'];
	$sql = "select * from activity where challenge=1 and uid=".$myuid." and sid=".$sid;
	$result = mysql_query($sql);
	if($row = mysql_fetch_array($result)){
		echo "<a id='like_btn_mini'><span>You Like This</span></a>";
		
	}else{
		echo "<a id='like_btn_mini'><span>Like It</span></a>";
	}
	
	}
}




function getSongplayer($sid,$auto){
	
		$sql = "select * from song natural join songurl where sid='$sid'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		echo "<object  width=\"290\" height=\"24\" data=\"res/player.swf\" type='application/x-shockwave-flash'>
	<param value=\"#FFFFFF\" name=\"bgcolor\">
	<param value=\"transparent\" name=\"wmode\">
	<param value=\"true\" name=\"menu\">
	<param value=\"animation=no&amp;autostart=".$auto."&amp;titles=".rawurlencode($row["title"])."&amp;soundFile=".rawurlencode($row["surl"])."\" name=\"flashvars\">
	</object>";
}

?>