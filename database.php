<?php
function CreateLink()
{

	$sql_host = "localhost";
	$sql_username = "";
	$sql_password = "";
	$sql_database = "ApiControl";

	$link = mysqli_connect($sql_host,$sql_username,$sql_password,$sql_database) or die("Error " . mysqli_error($link)); 

	return $link;
}

?>
