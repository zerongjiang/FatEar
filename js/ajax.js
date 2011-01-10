// JavaScript Document
$(document).ready(function(){
	
	$("#searchbox").blur(function(){
		if($(this).attr("value")=='')
			$(this).attr("value",'Search');

	});
	
	$("#searchbox").focus(function(){
		if($(this).attr("value")=='Search')
			$(this).attr("value",'');
	});
	
	$("#searchbox").keyup(function(){
		$("#auto_complete").html("<p>Loading.....</p>");
		keyword = $(this).attr("value");
		if(keyword != '')
		$.ajax({url:"ajax.php",async:false,type:"GET",
		success: function(data){
			$("#auto_complete").removeClass("hidden");
			$("#auto_complete").html(data);
			
			$("#auto_complete ul li").mouseover(function(){
			  	$(this).addClass('search_select');
			});
		  
			$("#auto_complete ul li").mouseout(function(){
			  	$(this).removeClass('search_select');
			});
			
			
			
		},
		data:{action:"search",keyword: keyword}});
	});
	
	
	$("#auto_complete").mouseenter(function(){
			$("#auto_complete").removeClass("hidden");
	});
	
	$("#auto_complete").mouseleave(function(){
			$("#auto_complete").addClass("hidden");
	});
	
	


});

function GetXmlHttpObject()
{
  var xmlHttp=null;
  try
    {
    // Firefox, Opera 8.0+, Safari
    xmlHttp=new XMLHttpRequest();
    }
  catch (e)
    {
    // Internet Explorer
    try
      {
      xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
      }
    catch (e)
      {
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    }
  return xmlHttp;
}


function ajaxFollowstateChanged() 
{ 
  if (xmlHttp.readyState==4)
  { 
 document.getElementById("usertool").innerHTML = xmlHttp.responseText;
  }
}

function ajaxFollow(uid)
 {
  	xmlHttp=GetXmlHttpObject()
	
    var url="ajax.php";
	var action = "follow";
	url=url+"?action="+action+"&uid="+uid;
	xmlHttp.onreadystatechange =xmlHttp.onreadystatechange =function()
  	{
  	if(xmlHttp.readyState==4)
		{
		document.getElementById("usertool").innerHTML = xmlHttp.responseText;
		}
  	}
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
	
 }
 
 
function ajaxFriend(action,uid)
{
  	xmlHttp=GetXmlHttpObject()
	
    var url="ajax.php";
	var action = action;
	url=url+"?action=friend&uid="+uid+"&type="+action;
	xmlHttp.onreadystatechange =function()
  	{
  	if(xmlHttp.readyState==4)
		{
		document.getElementById("usertool").innerHTML = xmlHttp.responseText;
		}
  	}
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
	
}

function ajaxGetFF(){
	
	xmlHttp=GetXmlHttpObject()
    var url="ajax.php";
	
	var action = action;
	url=url+"?action=getff";
	xmlHttp.onreadystatechange =function()
  	{
  	if(xmlHttp.readyState==4)
		{
		document.getElementById("accoutinfo").innerHTML = xmlHttp.responseText;
		}
  	}
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
	
	
}

function ajaxActFollow(uid){
	
	xmlHttp=GetXmlHttpObject()
    var url="ajax.php";
	
	var action = "follow";
	url=url+"?action="+action+"&uid="+uid;
	xmlHttp.onreadystatechange =function()
  	{
  	if(xmlHttp.readyState==4)
		{
			uname = $("#"+uid+" a:eq(1)").html();
			$(".tip").html("You unFollowed <a href='user.php?id="+uid+"'>"+uname+"</a>, and unFriend <a href='user.php?id="+uid+"'>"+uname+"</a> automaticlly");
			$(".tip").slideDown(300);
			$("#"+uid).remove();
		}
  	}
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);	
}

function ajaxActFriend(uid){
	
	xmlHttp=GetXmlHttpObject()
    var url="ajax.php";
	
	var action = "actfriend";
	url=url+"?action="+action+"&uid="+uid;
	xmlHttp.onreadystatechange =function()
  	{
  	if(xmlHttp.readyState==4)
		{
			uname = $("#"+uid+" a:eq(1)").html();
			actname = $("#"+uid+" a:eq(2)").html();
			if(actname == "Add Friend") 			$(".tip").html("You sent a friend request to <a href='user.php?id="+uid+"'>"+uname+"</a>");
			if(actname == "Unrequest") 			$(".tip").html("You canceled your friend request to <a href='user.php?id="+uid+"'>"+uname+"</a>");
			if(actname == "Unfriend") 			$(".tip").html("You unFriended with <a href='user.php?id="+uid+"'>"+uname+"</a>");
			$(".tip").slideDown(300);
			$("#"+uid+" a:eq(2)").html(xmlHttp.responseText.substr(4));
		}
  	}
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
	
}



function ajaxGetMsg(){
	xmlHttp=GetXmlHttpObject()
    var url="ajax.php";
	var action = "getmsg";
	url=url+"?action="+action;
	xmlHttp.onreadystatechange =function()
  	{
  	if(xmlHttp.readyState==4)
		{
			document.getElementById("accoutinfo").innerHTML = xmlHttp.responseText;
		}
  	}
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
	
}


function ajaxActFrdAccept(uid){
	
	xmlHttp=GetXmlHttpObject()
    var url="ajax.php";
	var action = "frdaccept";
	url=url+"?action="+action+"&uid="+uid;
	xmlHttp.onreadystatechange =function()
  	{
  	if(xmlHttp.readyState==4)
		{
			uname = $("#"+uid+" a:eq(1)").html();
			$(".tip").html("You and <a href='user.php?id="+uid+"'>"+uname+"</a> are Friends now :)");
			$(".tip").slideDown(300);
			x= document.getElementById(uid);
			x.parentNode.removeChild(x);
		}
  	}
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
}


function ajaxActFrdDecline(uid){
	
	xmlHttp=GetXmlHttpObject()
    var url="ajax.php";
	var action = "frddecline";
	url=url+"?action="+action+"&uid="+uid;
	xmlHttp.onreadystatechange =function()
  	{
  	if(xmlHttp.readyState==4)
		{
			uname = $("#"+uid+" a:eq(1)").html();
			$(".tip").html("You decline <a href='user.php?id="+uid+"'>"+uname+"</a>'s request :(");
			$(".tip").slideDown(300);
			x= document.getElementById(uid);
			x.parentNode.removeChild(x);
		}
  	}
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
}

function ajaxSongplay(sid){
	
	$("#player").remove();
	xmlHttp=GetXmlHttpObject()
    var url="ajax.php";
	var action = "getsurl";
	url=url+"?action="+action+"&sid="+sid;
	xmlHttp.onreadystatechange =function()
  	{
  	if(xmlHttp.readyState==4)
		{
			$("#songlist #song"+sid).after(xmlHttp.responseText);
			 $("#like_btn_mini").click(function(){
				sid = $("#player").prev().attr("id").substr(4);
			   	ajaxLikesongmini(sid);
  			});
			
		}
  	}
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
	
}


function ajaxSongplay2(sid){
	
	$("#player").remove();
	xmlHttp=GetXmlHttpObject()
    var url="ajax.php";
	var action = "getsurl";
	url=url+"?action="+action+"&sid="+sid;
	xmlHttp.onreadystatechange =function()
  	{
  	if(xmlHttp.readyState==4)
		{
			$("#popularsongs #song"+sid).after(xmlHttp.responseText);
			 $("#like_btn_mini").click(function(){
				sid = $("#player").prev().attr("id").substr(4);
			   	ajaxLikesongmini(sid);
  			});
			
		}
  	}
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
	
}



function ajaxGrade(sid,rank){
	xmlHttp=GetXmlHttpObject()
    var url="ajax.php";
	var action = "rank";
	url=url+"?action="+action+"&sid="+sid+"&rank="+rank;
	xmlHttp.onreadystatechange =function()
  	{
  	if(xmlHttp.readyState==4)
		{
			$("#grader").html(xmlHttp.responseText);
		}
  	}
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
}

function ajaxGradeAlbum(aid,rank){
	xmlHttp=GetXmlHttpObject()
    var url="ajax.php";
	var action = "rankalbum";
	url=url+"?action="+action+"&aid="+aid+"&rank="+rank;
	xmlHttp.onreadystatechange =function()
  	{
  	if(xmlHttp.readyState==4)
		{
			$("#grader").html(xmlHttp.responseText);
		}
  	}
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
}


function ajaxGradeArtist(aid,rank){
	xmlHttp=GetXmlHttpObject()
    var url="ajax.php";
	var action = "rankartist";
	url=url+"?action="+action+"&aid="+aid+"&rank="+rank;
	xmlHttp.onreadystatechange =function()
  	{
  	if(xmlHttp.readyState==4)
		{
			$("#grader").html(xmlHttp.responseText);
		}
  	}
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
}


function ajaxPostcomment(sid){
	mycomment = $("#mycomment textarea").val();

	$.ajax({url:"ajax.php",async:false,type:"POST",
	success: function(){
		$("#mycomment p").html(mycomment);
		$("#mycomment a").html("Modify");
	},
	data:{action:"postcomment",sid: sid,comment:mycomment}});
}

function ajaxLikesong(sid){
	$.ajax({url:"ajax.php",async:false,type:"GET",
	success: function(data){
		if($("#like_btn span").html() == "You Like This"){ $("#like_btn span").html("Like It");}
		else if($("#like_btn span").html() == "Like It"){ $("#like_btn span").html("You Like This");}
		$("#likeusers").html(data);
	},
	data:{action:"likeSong",sid: sid}});
}

function ajaxLikesongmini(sid){
	$.ajax({url:"ajax.php",async:false,type:"GET",
	success: function(data){
		if($("#like_btn_mini span").html() == "You Like This"){ $("#like_btn_mini span").html("Like It");}
		else if($("#like_btn_mini span").html() == "Like It"){ $("#like_btn_mini span").html("You Like This");}
	},
	data:{action:"likeSongmini",sid: sid}});
}
