<?php

	require_once('database.php');
	//Include all Control Files
	foreach (glob($_SERVER["DOCUMENT_ROOT"].'/controllers/*Control.php') as $control) {
	    require_once $control;
	}

    //Create controls
	$UserControl    = new UserControl();
	$TokenControl   = new TokenControl();
	$TrackingControl = new TrackingControl();
	
	$requestedUrl = strstr(strtolower(substr( $_SERVER['REQUEST_URI'], 1)), '?', true);

	//Switch to chose controller
	switch($requestedUrl)
	{
		case 'session/test/':
		{
			if(isset($_REQUEST['apikey']))
			{
			    $TokenControl->validateToken($_REQUEST['apikey']);
			    Set_Status(202, 'Token Ok');
			}
			break;
		}

		//'http://api.siscodev93.com/session/login/?username=user1&password=1'
		case 'session/login/':
		{
			if(isset($_REQUEST['username']) && isset($_REQUEST['password']))
			{
			    $UserControl->Login($_REQUEST['username'], $_REQUEST['password']);
			}
			break;
		}


		// http://api.siscodev93.com/session/logout/?apikey=dXNlcjFhMzhiYzdlOGZmNzExZmNiMThkMjE2ZTYyN2EzMjdmYWJiNDFhZjhjMzVjZjkwMDViNThiMDkzOGM1MWY1NDRj
		case 'session/logout/':
		{
			if(isset($_REQUEST['apikey']))
			{
			    $TokenControl->validateToken($_REQUEST['apikey']);
			    $UserControl->LogOut($_REQUEST['apikey']);
				break;
			}
			
			break;
		}


		//'http://api.siscodev93.com/location/update/?latitude=latitude&longitude=longitude&apikey=dXNlcjE3MDg3Njc0OTBjMDM5YWJlZDFkOWU0OTUzNGNkMTMwZDJiOTgwNDA5MDIzYWRjMzAxMjRiOGJjZGQxMTY5NmIy'
		case 'location/update/':
		{
			if(isset($_REQUEST['apikey']) && isset($_REQUEST['latitude']) && isset($_REQUEST['longitude']))
			{
				$TokenControl->validateToken($_REQUEST['apikey']);
				$TrackingControl->Update($_REQUEST['latitude'],$_REQUEST['longitude'],$_REQUEST['apikey']);	
			}
			break;
		}


		//No command selected, Later on add more advanced checking here to see
		//what actually went wrong
		default:
		{
			break;
		}
	}


	function Set_Status($code, $message)
	{

		switch ($code) {
			case 200: $msg = '';break;
			case 202: $msg = 'Token Granted';break;
			case 401: $msg = 'Authorization Denied';break;
			case 403: $msg = 'Token Expired';break;
			case 406: $msg = '';break;
			case 500: $msg = '';break;
			case 503: $msg = '';break;
			default:break;
		}
		if($message != '')
		{

			header('HTTP/1.1 '.$code.' '.$message);
			die('Done Request');
		}

		header('HTTP/1.1 '.$code.' '.$msg);
		die('Done Request');
	}

?>
