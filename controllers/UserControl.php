<?php
class UserControl
{

	//Last Child: Set status codes here
	public function Login($username, $password)
	{
		//Create connection to database
		$connection = CreateLink();

		//escape user input
		$username = mysqli_real_escape_string ( $connection, $username );
		$password = mysqli_real_escape_string ( $connection, $password );

		//create sql login
		$sql = "SELECT * FROM `UserGroup` WHERE `user_email` = '$username' AND user_password = '$password'";
		$result = mysqli_query($connection, $sql);
		$responce = mysqli_fetch_array($result);

		

		if(!$responce)
		{
			$connection->close();
			Set_Status(401, 'Incorrect UserCredentials'); //Access Denied
		}
		

		$Token = new TokenControl();
		$Accesskey = $Token->genToken($username);
		
		$connection->close();
		
		header('Token: '.$Accesskey);
		Set_Status(202);
	    

	}

	public function LogOut($AccessCode)
	{
		$connection = CreateLink();

		$sql = "UPDATE `UserGroup` SET `token_base`=NULL,`token_session`=NULL,`token_expiration`=NULL,`token_integrity`=NULL,`token_lastused`=NULL WHERE `token_session`= '$AccessCode'";
		$result = mysqli_query($connection, $sql);
		$connection->close();
		

		if(!$result)
			Set_Status(500);

		Set_Status(200);		

		
	}


}

?>
