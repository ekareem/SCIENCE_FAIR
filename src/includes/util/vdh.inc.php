<?php
	//require_once '../const/config.const.php'
	require_once $PATH_ABSOLUTE."includes/const/config.const.php";
	require_once $PATH_ABSOLUTE."includes/util/email.util.php";

	/**
	name: textValidate
	purpose: simple validation for text field
	param[1] : String  -  user name
	return: boolean 
	*/
	
	function validateText($name)
	{
		return !empty($name);
	}

	/**
	name: emailValidate
	purpose: validates email field
	param[1] : String - email
	return: boolean
	*/
	function validateEmail($email)
	{
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		//checks for email validation errorss
		return !(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL));
	}

	/**
	name: pwdValidate
	purpose: validates password field
	param[1] : String - password
	return: boolean
	*/
	function validatePassword($password)
	{
		global $PASSWORD_MIN_LENGTH;
		if(empty($password))
			return false;

		//password standard
		$uppercase = preg_match('@[A-Z]@', $password);
		$lowercase = preg_match('@[a-z]@', $password);
		$number    = preg_match('@[0-9]@', $password);
		$specialChars = preg_match('@[^\w]@', $password);

		//checks if password requirements are met
		return !(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < $PASSWORD_MIN_LENGTH);
	}
	
	/**
	name : validation
	purpose : validates basic option form
	param[1] : string - user choice
	return : array - options
	**/
	function validateOption($choice,$options)
	{
		return in_array($choice,$options);
	}
	
	function isRealEmail($email)
	{
		// Initialize library class
		$mail = new VerifyEmail();

		// Set the timeout value on stream
		$mail->setStreamTimeoutWait(1);

		// Set debug output mode
		$mail->Debug= FALSE; 
		$mail->Debugoutput= 'html'; 

		// Set email address for SMTP request
		$mail->setEmailFrom('ekareem@iu.edu');

		// Check if email is valid and exist
		return $mail->check($email);
	}