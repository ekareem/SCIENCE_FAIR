<!--
name : error.const.php
purpse : holds constant for different error attribute
lastchanged : 9/26/2020
change log: created file
-->
<?php
	//require_once "config.const.php"
	require_once $PATH_ABSOLUTE."includes/const/config.const.php";
	
	$ERROR_COLOR = '#ff0000'; //red
	
	$ERROR_MSG_REQUIRED_FIELD = 'field empty';
	
	$ERROR_MSG_BAD_EMAIL = 'email badly formatted';
	$ERROR_MSG_NON_MATCHING_EMAIL = 'emails do not match';
	
	$ERROR_MSG_BAD_PASSWORD = $PASSWORD_MIN_LENGTH.',1 upper case letter, 1 number, and 1 special character';
	$ERROR_MSG_NON_MATCHING_PASSWORD = 'passwords do not match';
	
	$ERROR_MSG_NON_MATCHING_ACCOUNT_TYPE = 'select an Account type';