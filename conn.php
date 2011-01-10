<?php


$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
  
 mysql_select_db("fatear",$con) or die('could find database'.mysql_error());
 
?>

