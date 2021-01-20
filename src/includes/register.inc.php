<!--
	name : register.inc.php
	purpose : adds register php functionality
	last changed : 26/9/2020
	change log : added validation
-->
<?php
	//require_once "const/config.const.php";
	require_once $PATH_ABSOLUTE."includes/util/vdh.inc.php";
	require_once $PATH_ABSOLUTE."includes/util/util.inc.php";
	require_once $PATH_ABSOLUTE."includes/const/config.const.php";
	require_once $PATH_ABSOLUTE."includes/const/error.const.php";
	require_once $PATH_ABSOLUTE."includes/const/name.const.php";
	require_once $PATH_ABSOLUTE."includes/dbh/retrive.dbh.php";
	
	//for fields
	$firstName = (isset($_GET[$NAME_FIRST_NAME])) ? trim($_GET[$NAME_FIRST_NAME]) : "";
	$middleName = (isset($_GET[$NAME_MIDDLE_NAME])) ? trim($_GET[$NAME_MIDDLE_NAME]) : "";
	$lastName = (isset($_GET[$NAME_LAST_NAME])) ? trim($_GET[$NAME_LAST_NAME]) : "";
	$email = (isset($_GET[$NAME_EMAIL])) ? trim($_GET[$NAME_EMAIL]) : "";
	$confirmEmail = (isset($_GET[$NAME_CONFIRM_EMAIL])) ? trim($_GET[$NAME_CONFIRM_EMAIL]) : "";
	$accountType = (isset($_GET[$NAME_ACCOUNT_TYPE])) ? trim($_GET[$NAME_ACCOUNT_TYPE]) : "";

	
	//database retriveval
	$AccountTypesDB = array('JUDGE','ADMIN','SESSION CHAIR');




	function selectCategory()
	{
		$categoryDB = quaryDB("SELECT CATEGORY.CategoryID,CATEGORY.CategoryName FROM CATEGORY");
		echo '<select  name="category" id="category" >';
		foreach ($categoryDB as $row)
		{
			$id = $row['CategoryID'];
			$val = $row['CategoryName'];
			
			echo "<option value=\"$id\">$val</option>";
		}
		echo '</select>';
	}

	function selectGrade($name)
	{
		$gradeDB = quaryDB("SELECT GRADE.GradeID,GRADE.GradeName FROM GRADE");
		echo '<select  name="'.$name.'" id="'.$name.'" >';
		foreach ($gradeDB as $row)
		{
			$id = $row['GradeID'];
			$val = $row['GradeName'];
			
			echo "<option value=\"$id\">$val</option>";
		}
		echo '</select>';
	}

	//validation error message
	$nameErrorMessage ='';
	$emailErrorMessage = '';
	$confirmEmailErrorMessage = '';

	$accountTypeErrorMessage = '';
	
	$validated = true;
	
	if (!isset($_GET['submitRegister']))
		$validated = false;

	//frst name validation
	if(!validateText($firstName) || !validateText($lastName))
	{
		$nameErrorMessage = $ERROR_MSG_REQUIRED_FIELD;
		$validated = false;
	}
	
	
	//email and confirm email validation
	
	if(isset($_GET[$NAME_EMAIL]) && !validateEmail($email))
	{
		$emailErrorMessage = $ERROR_MSG_BAD_EMAIL;
		$validated = false;
	}
	else if(isset($_GET[$NAME_EMAIL]) && $email != $confirmEmail)
	{
		$confirmEmailErrorMessage = $ERROR_MSG_NON_MATCHING_EMAIL;
		$validated = false;
	}
	else if($validated && isset($_GET[$NAME_EMAIL]) && isset($_GET['submitRegister']) &&  !isRealEmail($email))
	{
		$confirmEmailErrorMessage = 'enter a real email';
		$validated = false;
	}
	
	if(isset($_GET[$NAME_ACCOUNT_TYPE]) && !validateOption($accountType,$AccountTypesDB))
	{
		$accountTypeErrorMessage = $ERROR_MSG_NON_MATCHING_ACCOUNT_TYPE;
		$validated = false;
	}
	
	
	if($validated)
	{
		$password = random_str(10);
		
		$subject = "Email Activation";
						
		// email body
		$body = '<p><img style="display: block; margin-left: auto; margin-right: auto;" src="http://sefi.org/celebratescienceindiana/wp-content/uploads/2018/02/CSI_logo_380x261-1.png" alt="seif logo" width="424" height="291" /></p>
				<p style="text-align: center;"><strong>Welcome you have successfully registered as a '.strtolower($accountType) .'</strong></p>
				<p style="text-align: center;"><strong>email : '.$email.' ;</strong></p>
				<p style="text-align: center;"><strong>password: '.$password.';</strong></p>
				<p style="text-align: center;"><strong><a title="login" href="http://corsair.cs.iupui.edu:24441/N342-Course-Project/src/login.php">LOGIN</a></strong></p>';
		
		//email formatting 
		$header = "From: The Sender Name <ekareem@iu.edu>\r\n";
		$header .= "Reply-To: ekareem@iu.edu\r\n";
		
		//makse text to be sent to be rendered has html text
		$header .= "Content-type: text/html\r\n";

		//use PHP built-in functions, see details on https://www.w3schools.com/php/func_mail_mail.asp
		$body = wordwrap($body,70);// use wordwrap() if lines are longer than 70 characters
		//sends m
		if(!mail($email,$subject,$body,$header)) 
		{
			$msg = 'Email not sent. ';
		}
		else 
		{
			//if email is sent go to process.php
			//echo 'message sent';
	
			if($accountType == 'JUDGE')
				$quary =  'INSERT INTO `ekareem_db`.`JUDGE` (`FirstName`, `MiddleName`, `LastName`, `Email`, `Password`, `Year`) VALUES (\''.$firstName.'\', \''.$middleName.'\', \''.$lastName.'\', \''.$email.'\', \''.$password.'\', \''.date('Y').'\')';
			else if($accountType == 'ADMIN' )
				$quary = 'INSERT INTO `ekareem_db`.`ADMIN` (`FirstName`, `MiddleName`, `LastName`, `Email`, `Password`) VALUES (\''.$firstName.'\', \''.$middleName.'\', \''.$lastName.'\', \''.$email.'\', \''.$password.'\')';
			
			
			else if($accountType == 'SESSION CHAIR')
			{
				$quary = 'INSERT INTO `ekareem_db`.`ADMIN` (`FirstName`, `MiddleName`, `LastName`, `Email`, `Password`,`SessionBool`) VALUES (\''.$firstName.'\', \''.$middleName.'\', \''.$lastName.'\', \''.$email.'\', \''.$password.'\', \'1\')';
	
			}

			$stmt = $con->prepare($quary);
			$run = $stmt->execute();
			//print_r ($quary);
			//print_r ($stmt->errorInfo());
			if($run)
			{

				if (isset($_GET[$NAME_ACCOUNT_TYPE]) && $_GET[$NAME_ACCOUNT_TYPE] == 'JUDGE')
				{
					$sql = "SELECT JUDGE.JudgeID FROM JUDGE WHERE JUDGE.Email = \"$email\"";
					$rows = quaryDB($sql);
					$judgeID = $rows[0]['JudgeID'];
					$categoryID = $_GET['category'];
					$minID = $_GET['max'];
					$maxID = $_GET['min'];

					$sql =  "INSERT INTO `ekareem_db`.`JUDGE_CATEGORY_PREFERENCE` ( `JudgeID`, `CategoryID`) VALUES ('$judgeID', '$categoryID')";
					quaryDB($sql);
					$sql =  "INSERT INTO `ekareem_db`.`JUDGE_GRADE_PREFERENCE` (`JudgeID`, `minGradeID`, `maxGradeID`) VALUES ('$judgeID', '$minID', '$maxID')";
					quaryDB($sql);
				}
				Header ("Location:login.php?registred=success");
			}
		}
		
	}