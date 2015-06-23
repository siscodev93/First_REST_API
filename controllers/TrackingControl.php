<?php
class TrackingControl
{
	public function Update($Latitude, $Longitude, $AccessCode)
	{
		$connection = CreateLink();

		$latitude = mysqli_real_escape_string ( $connection, $Latitude );
		$longitude = mysqli_real_escape_string ( $connection, $Longitude );


		$sql = "UPDATE `UserGroup` SET `latitude`='$latitude',`longitude`='$longitude' WHERE `token_session`= '$AccessCode'";
		$result = mysqli_query($connection, $sql);
		$connection->close();
		
		if(!$result)
			Set_Status(500);
		
		
	}
}
?>

