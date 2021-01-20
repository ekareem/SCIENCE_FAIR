<?php
	if (session_status() == PHP_SESSION_NONE) session_start();

	require_once $PATH_ABSOLUTE."includes/dbh/retrive.dbh.php";
	require_once $PATH_ABSOLUTE."includes/const/db.const.php";
	require_once $PATH_ABSOLUTE."includes/const/name.const.php";
	
	if(!isset($_SESSION[$NAME_SESSION_ID]) || !isset($_SESSION[$NAME_SESSION_EMAIL]) || !isset($_SESSION[$NAME_SESSION_PASSWORD]) || !isset($_SESSION[$NAME_SESSION_TYPE]))
	{
		 
		$_SESSION['url'] = $_SERVER['REQUEST_URI'];
		
		header("Location:login.php");
		exit();
	}
	else
	{
		$quary = "SELECT * FROM `".$DB."`.`".$_SESSION['type']."` WHERE Email = '".$_SESSION[$NAME_SESSION_EMAIL]."' and Password  = '".$_SESSION['password']."'";
		
		$row = quaryDB($quary);
		if(empty($row))
		{
			$_SESSION['url'] = $_SERVER['REQUEST_URI']; 
			header("Location:login.php");
			exit();
		}
	}