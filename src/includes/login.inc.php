<!--
	name : login.inc.php
	purpose : adds login php functionality
	last changed : 26/9/2020
	change log : added validation
-->
<?php
	session_start();
	#print_r($_SESSION);
	//require_once "const/config.const.php";
	require_once $PATH_ABSOLUTE."includes/util/vdh.inc.php";
	require_once $PATH_ABSOLUTE."includes/const/error.const.php";
	require_once $PATH_ABSOLUTE."includes/const/name.const.php";
	require_once $PATH_ABSOLUTE."includes/dbh/retrive.dbh.php";
	
	//fields
	$email = (isset($_POST[$NAME_EMAIL])) ? trim($_POST[$NAME_EMAIL]) : "";
	$password = (isset($_POST[$NAME_PASSWORD])) ? trim($_POST[$NAME_PASSWORD]) : "";
	$accountType = (isset($_POST[$NAME_ACCOUNT_TYPE])) ? trim($_POST[$NAME_ACCOUNT_TYPE]) : "";
	
	//database retriveval
	$AccountTypesDB = retriveAccountTypesDB();
	
	//validation error message
	$emailErrorMessage = '';
	$passwordErrorMessage = '';
	$accountTypeErrorMessage = '';
	
	$validated = true;
	
	//email validation
	if(isset($_POST[$NAME_EMAIL]) && !validateEmail($email))
	{
		$emailErrorMessage = $ERROR_MSG_BAD_EMAIL;
		$validated = false;
	}
	
	//password validation
	if(isset($_POST[$NAME_PASSWORD]) && !validateText($password))
	{
		$passwordErrorMessage = $ERROR_MSG_REQUIRED_FIELD;
		$validated = false;
	}

	//accountt type Validation
	if(isset($_POST[$NAME_ACCOUNT_TYPE]) && !validateOption($accountType,$AccountTypesDB))
	{
		$accountTypeErrorMessage = $ERROR_MSG_NON_MATCHING_ACCOUNT_TYPE;
		$validated = false;
	}
	
	//if succesfully validated
	if($validated)
	{
		$dbName = '';
		if ($accountType == 'Judge')
			$dbName = 'JUDGE';
		else if ($accountType == 'Admin')
			$dbName = 'ADMIN';
		else if ($accountType == 'Session Chairs')
			$dbName = 'ADMIN';
			
		$quary = 'SELECT * FROM `'.$dbName.'` WHERE `'.$dbName.'`.`Email` = \''.$email.'\' and `'.$dbName.'`.`Password` = \''.$password.'\'';

		if ($accountType == 'Session Chairs')
			$quary = 'SELECT * FROM `'.$dbName.'` WHERE `'.$dbName.'`.`Email` = \''.$email.'\' and `'.$dbName.'`.`Password` = \''.$password.'\' AND `'.$dbName.'`.`SessionBool`= \'1\'';

		$row = quaryDB($quary);

		if(count($row) == 1)
		{
			if ($accountType == 'Judge')
				$_SESSION[$NAME_SESSION_ID] = $row[0]['JudgeID'];
			else if (($accountType == 'Admin') || ($accountType == 'Session Chairs'))
				$_SESSION[$NAME_SESSION_ID] = $row[0]['AdminID'];
			// else if ($accountType == 'Session Chairs') 
			// 	$_SESSION[$NAME_SESSION_ID] = $row[0]['AdminID'];

			if ($accountType == 'Session Chairs')
				$_SESSION['chair'] = true;

			$_SESSION[$NAME_SESSION_EMAIL] = $email;
			$_SESSION[$NAME_SESSION_PASSWORD] = $password;
			$_SESSION[$NAME_SESSION_TYPE ] = $dbName;
			
			#echo 'http://'.$_SERVER['HTTP_HOST'].$_SESSION['url'];
			if(isset($_SESSION['url']))
				header('Location:'.'http://'.$_SERVER['HTTP_HOST'].$_SESSION['url']);
			else
			{
				header('Location:index.php');
			}
		}
	}
