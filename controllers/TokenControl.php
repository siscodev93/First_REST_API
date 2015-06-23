<?php
class TokenControl
{
	private $username;

	private function createTokenHash($username)
	{
		foreach (hash_algos() as $hash_type)
		{
        	$hash = hash('sha256', (hash($hash_type, $this->username + rand(1111111111111111, 9999999999999999))));
		}
		$hash = $this->username.$hash;

		echo '</br>'.base64_encode($hash).'</br>';


		$hash = array('Session' => base64_encode($hash), 'Integrity' => md5(base64_encode($hash)));
		return $hash;
	}

	function genToken($username)
	{
		$this->username = $username;
		//Create connection to database
		$connection = CreateLink();

			print('Creating token');

			//create token
			$timenow = date('Y-m-d H:i:s');
		    $offset = strtotime("+3 day");

			//Build Token here

			$token_body = $this->createTokenHash($username);


			$token_expiration  = date("Y-m-d H:i:s", $offset);
			$token_session     = $token_body['Session'];
			$token_integrity   = $token_body['Integrity'];

			$Token = array("Expiration" => $token_expiration, "Session" => $token_session);

			$sql = "UPDATE `UserGroup` SET `token_session`='$token_session',`token_expiration` = '$token_expiration',`token_integrity`= '$token_integrity' WHERE `user_email` = '$username'";
			$result = mysqli_query($connection, $sql);
			$connection->close();

			if (!$result) 
	    		Set_Status(500);

	    	return json_encode($Token);
	}

	static function validateToken($token)
	{
		$timenow = date('Y-m-d H:i:s');
		$connection = CreateLink();

		$sql = "SELECT `token_session`, `token_expiration`, `token_integrity` FROM `UserGroup` WHERE `token_session` = '$token'";
		$result = mysqli_query($connection, $sql);
		$responce = mysqli_fetch_array($result);
		$connection->close();

		//Check Key Exsists
		if(!$responce)
			Set_Status(401, 'Token Invalid');

		//Check the integrity of token
		if(md5($token) != $responce['token_integrity'])
			Set_Status(401, 'Token Modified'); //Access Denied

		//Check if token is expired
		if($responce['token_expiration'] < $timenow )
			Set_Status(401, 'Token Expired'); //Access Denied

		
		
		
	}


}

?>
