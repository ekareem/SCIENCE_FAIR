<!--
	name : retirive.dbh.php
	purpose : retives data from the databse
	last changed : 26/9/2020
	change log : created file
-->
<?php
	require_once $PATH_ABSOLUTE."includes/dbh/connect.dbh.php";
	/**
	name : retriveAccountTypesDB
	purpose : retiver every possible account types in the databse
	return : array - all account types
	**/
	function retriveAccountTypesDB()
	{
		return array("Admin", "Judge","Session Chairs");
	}
	
	
	/**
	name : validAccessCodeDB
	purpose : chiecks the in the accessCode matches the account type
	param[1] : int -  acessCode
	param[2] : string - accoint type for example admin or judge
	return : boolean - checks if access code is valide
	**/
	function validAccessCodeDB($accessCode,$accountType)
	{
		return !empty($accessCode);
	}
	
	function quaryDB($quary,$bool = false)
	{
		global $con;
		$stmt = $con->prepare($quary);
		$run = $stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if($bool == 'error')
			return $stmt->errorInfo();

		if($bool)
			return $run;
		
		return $rows;
	}
	
	function selectAllDB($table,$orderBy = '')
	{
		global $con;
		$quary = "";
		if ($orderBy == '')
			$quary = "SELECT * FROM `".$table;
		else 
			$quary = "SELECT * FROM `".$table."`ORDER BY ".$orderBy;
		
		
		$stmt = $con->prepare($quary);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $rows;
	}