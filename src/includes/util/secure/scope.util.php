<?php
	require_once  $PATH_ABSOLUTE."includes/const/config.const.php";
	require_once  $PATH_ABSOLUTE."includes/const/name.const.php";
	
	if (session_status() == PHP_SESSION_NONE) session_start();

	$file =  basename($_SERVER["SCRIPT_FILENAME"]);
	
	if(!isset($_SESSION[$NAME_SESSION_TYPE]))
	{	
		$_SESSION[$NAME_SESSION_TYPE] = $PUBLIC;
	}

	if(isset($FILE_SCOPES[$file]))
	{
		$type = $_SESSION[$NAME_SESSION_TYPE];
		if($FILE_SCOPES[$file] > $SCOPES[$type])
		{
			header("Location:error.php");
			exit();
		}
	}
	else
	{
		header("Location:error.php");
		exit();
	}