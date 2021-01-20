<!DOCTYPE HTML>
<!--
	name : register.php
	purpose : user registration page
	last changed : 26/9/2020
	change log : created file
-->
<?php 
	require_once "includes/const/config.const.php";
	require_once $PATH_ABSOLUTE."includes/register.inc.php";
	require_once $PATH_ABSOLUTE."includes/const/name.const.php";
	require_once $PATH_ABSOLUTE."includes/util/util.inc.php";
	require_once $PATH_ABSOLUTE."includes/util/secure/scope.util.php";
?>

<html>
	<head>
		<title>Register</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body>

		<!-- Header -->
		<?php include $PATH_ABSOLUTE."templates/register/header.html";?>

		<!-- Menu -->
		<?php include $PATH_ABSOLUTE."templates/register/nav.html";?>

		<!-- Banner -->
			<section id="banner">
				<div class="content">
					<h1>REGISTRATION</h1>
				</div>
			</section>
			
	

		<!-- One -->
			<section id="one" class="wrapper">
				<div class="inner">
					<form method="get" action="register.php">
						<div class="row uniform">
							
							<!-- first name -->
							<div class="4u 12u$(small)">
								<?php 
									$firstNameIsValid = !(isset($_GET[$NAME_FIRST_NAME]) &&  $firstName == '');
									echo createLabel($firstNameIsValid,$nameErrorMessage,"first name");
								?>
								<input type = "text" <?php echo borderErrorColor($firstNameIsValid);?> name = "<?php echo $NAME_FIRST_NAME ?>" id = "<?php echo $NAME_FIRST_NAME ?>" value = "<?php echo $firstName?>" placeholder = "first name" />
							</div>
							
							<!-- middle name-->
							<div class="4u 12u$(small)">
								<?php 
									$middleNameIsValid = !(isset($_GET[$NAME_MIDDLE_NAME]) && $middleName == '');
									echo createLabel($middleNameIsValid,$nameErrorMessage,"middle name");
								?>
								<input type = "text" <?php echo borderErrorColor($middleNameIsValid);?> name = "<?php echo $NAME_MIDDLE_NAME ?>" id = "<?php echo $NAME_MIDDLE_NAME ?>" value = "<?php echo $middleName?>" placeholder = "middle name" />
							</div>
							
							<!--lastname -->
							<div class="4u 12u$(small)">
								<?php 
									$lastNameIsValid = !(isset($_GET[$NAME_LAST_NAME]) && $lastName == '');
									echo createLabel($lastNameIsValid,$nameErrorMessage,"last name");
								?>
								<input type = "text" <?php echo borderErrorColor($lastNameIsValid);?> name = "<?php echo $NAME_LAST_NAME ?>" id = "<?php echo $NAME_LAST_NAME ?>" value = "<?php echo $lastName?>" placeholder = "last name" />
							</div>
							
							<!-- email-->
							<div class="6u 12u$(small)">
								<?php $emailIsValid = !(isset($_GET[$NAME_EMAIL]) && $emailErrorMessage != '');
									echo createLabel($emailIsValid,$emailErrorMessage,"email");
								?>
								<input type = "text" <?php echo borderErrorColor($emailIsValid);?> name = "<?php echo $NAME_EMAIL ?>" id = "<?php echo $NAME_EMAIL ?>" value = "<?php echo $email?>" placeholder = "email" />
							</div>
							
							<!-- confirm email -->
							<div class="6u 12u$(small)">
								<?php $confirmEmailIsValid = !(isset($_GET[$NAME_CONFIRM_EMAIL]) && $confirmEmailErrorMessage != '');
									echo createLabel($confirmEmailIsValid,$confirmEmailErrorMessage,"confirm email");
								?>
								<input type = "text" <?php echo borderErrorColor($confirmEmailIsValid);?> name = "<?php echo $NAME_CONFIRM_EMAIL ?>" id = "<?php echo $NAME_CONFIRM_EMAIL ?>" value = "<?php echo $confirmEmail?>" placeholder = "confirm email" />
							</div>
							<!-- category type -->
							<?php if (isset($_GET[$NAME_ACCOUNT_TYPE]) && $_GET[$NAME_ACCOUNT_TYPE] == "JUDGE"  && $_GET[$NAME_ACCOUNT_TYPE] == 'JUDGE')
									{
							?>
								<div class="6u 12u$(small)">
									<label >Category prefrence</label>
									<div class="select-wrapper">
										<?php selectCategory() ;?>
									</div>
								</div>

								<div class="3u 12u$(small)">
									<label >min</label>
									<div class="select-wrapper">
										<?php selectGrade("max") ;?>
									</div>
								</div>
								
								<div class="3u 12u$(small)">
									<label >max</label>
									<div class="select-wrapper">
										<?php selectGrade("min") ;?>
									</div>
								</div>
							<?php
									}
							?>
							<!-- account type -->
							<div class="6u 12u$(small)">
								<?php 
										$accountTypeIsValid = !(isset($_GET[$NAME_ACCOUNT_TYPE]) && $accountTypeErrorMessage != '');
										echo createLabel($accountTypeIsValid,$accountTypeErrorMessage,"account type");
									?>
								<div class="select-wrapper">
									<select <?php  echo borderErrorColor($accountTypeIsValid); ?> name="<?php echo $NAME_ACCOUNT_TYPE?>" id="<?php echo $NAME_ACCOUNT_TYPE?>" >
										<option value="">- Register as -</option>
										<?php
											foreach($AccountTypesDB as $type)
											{
												if($accountType == $type)
													echo '<option value="'.$type.'" selected>'.$type.'</option>';
												else
													echo '<option value="'.$type.'">'.$type.'</option>';
											}
										?>
									</select>
								</div>
							</div>
							
							<div class="6u 12u$(small)">
								<label>-</label>
								<input type="submit" name = "<?php echo $NAME_SUBMIT_REGISTER?>" id = "<?php echo $NAME_SUBMIT_REGISTER ?>" value="SIGN UP" class="fit" />
							</div>
						</div>
					</form>
				</div>
			</section>

		<!-- Footer -->
		<?php include $PATH_ABSOLUTE."templates/footer.html";?>
		
		

		<!-- Scripts -->
		<?php include $PATH_ABSOLUTE."templates/script.html";?>
		<script>
		$( "#accountType" ) .change(function () {
			var firstname = $("#firstName").val();
			var middleName = $("#middleName").val();
			var lastName = $("#lastName").val();
			var email = $("#email").val();
			var confirmEmail = $("#confirmEmail").val();

			//firstName=firstname&middleName=middlename&lastName=lastname&email=admea%40email.com&confirmEmail=password&accountType=ADMIN&submitRegister=SIGN+UP
			var get = "?firstName="+firstname+"&middleName="+middleName+"&lastName="+lastName+"&email="+email+"&confirmEmail="+confirmEmail+"&accountType="+$(this).val()+"&";
			
			window.location.assign("register.php"+get);
		});
		</script>
	</body>
</html>