<!--
	name : util.inc.php
	purpose : adds general util function
	last changed : 26/9/2020
	change log : added validation
-->

<?php
	if (session_status() == PHP_SESSION_NONE) session_start();
	//require_once '../const/config.const.php';
	require_once $PATH_ABSOLUTE."includes/const/config.const.php";
	require_once $PATH_ABSOLUTE."includes/const/name.const.php";
	require_once $PATH_ABSOLUTE."includes/const/error.const.php";
	
	/**
	name : createLabel
	purpose : creates an input label , if input is valid sets $label has the label other wise sers $error has the label 
	param[]1 : boolean - checks if input is validated
	param[2] : string - error message
	param[3] : string - label name
	retirn : string - input label**/
	function createLabel($isValid,$errorMsg,$labelName)
	{
		global $ERROR_COLOR;
		if(!$isValid)
			return '<label style="color:red">'.$errorMsg.'</label>';
		return '<label >'.$labelName.'</label>';
	}
	
	/**
	name : boarderErrorColor
	purpose : changes input boarder color to $ERROR_COLOR if input was unsucssfull validated otherwise leave it has it is
	param[1] : boolean - checks if input was validated
	return : string 
	**/
	function borderErrorColor($isValid)
	{
		global $ERROR_COLOR;
		if (!$isValid)
			return 'style="border-color:'.$ERROR_COLOR.'"';
		return '';
	}
	
	/**
	 * Generate a random string, using a cryptographically secure 
	 * pseudorandom number generator (random_int)
	 *
	 * This function uses type hints now (PHP 7+ only), but it was originally
	 * written for PHP 5 as well.
	 * 
	 * For PHP 7, random_int is a PHP core function
	 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
	 * 
	 * @param int $length      How many characters do we want?
	 * @param string $keyspace A string of all possible characters
	 *                         to select from
	 * @return string
	 */
	function random_str($length)
	{
		$keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if ($length < 1) {
			throw new \RangeException("Length must be a positive integer");
		}
		$pieces = [];
		$max = strlen($keyspace) - 1;
		for ($i = 0; $i < $length; ++$i) {
			$pieces []= $keyspace[rand(0, $max)];
		}
		return implode('', $pieces);
	}

	function logOut()
	{
		global $NAME_SESSION_EMAIL;
		global $NAME_SESSION_PASSWORD;
		global $NAME_SESSION_ID;
		global $NAME_SESSION_TYPE;

		if(isset($_SESSION[$NAME_SESSION_ID]) && isset($_SESSION[$NAME_SESSION_EMAIL]) && isset($_SESSION[$NAME_SESSION_PASSWORD]) && isset($_SESSION[$NAME_SESSION_TYPE]))
		{
			
			unset($_SESSION[$NAME_SESSION_ID]);
			unset($_SESSION[$NAME_SESSION_EMAIL]);
			unset($_SESSION[$NAME_SESSION_PASSWORD]);
			unset($_SESSION[$NAME_SESSION_TYPE]);
			unset($_SESSION['chair']);
			header("Location:login.php");
			exit();
		}
	}
