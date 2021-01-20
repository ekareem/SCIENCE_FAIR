<?php
	$PASSWORD_MIN_LENGTH = 10;
	$ERROR_COLOR = 'red';
	$PATH_ROOT = 'N342-Course-Project/src/';
	$PATH_ABSOLUTE = $_SERVER['DOCUMENT_ROOT']."/$PATH_ROOT";
	
	$PUBLIC = 'PUBLIC';
	$JUDGE = 'JUDGE';
	$ADMIN = 'ADMIN';
	$SUPER = 'SUPER';
	
	$SCOPES = array(
				$PUBLIC=>1,
				$JUDGE=>2,
				$ADMIN=>3,
				$SUPER=>4
				);
				
	$FILE_SCOPES = array(
						'crud.php'=>$SCOPES[$ADMIN],
						'register.php'=>$SCOPES[$PUBLIC],
						'login.php'=>$SCOPES[$PUBLIC],
						'judgeProfile.php' =>$SCOPES[$JUDGE],
						'profile.php' =>$SCOPES[$JUDGE],
						'project.php' =>$SCOPES[$JUDGE],
						'judgeCheckIn.php' =>$SCOPES[$JUDGE],
						'adminLanding.php' =>$SCOPES[$ADMIN],
						'judgeLanding.php' =>$SCOPES[$JUDGE],
						'logout.php' =>$SCOPES[$JUDGE],
						'upload.php' =>$SCOPES[$ADMIN],
						'files.php' =>$SCOPES[$ADMIN],
						'schedule.php' =>$SCOPES[$JUDGE],
						'judge.php' =>$SCOPES[$JUDGE],
						'session.php' =>$SCOPES[$JUDGE],
						'booth.php' =>$SCOPES[$JUDGE],
						'checkIn.php' =>$SCOPES[$ADMIN],
						'student.php' =>$SCOPES[$JUDGE],
						'scores.php' =>$SCOPES[$JUDGE],
						'index.php' =>$SCOPES[$JUDGE],
						'error.php' =>$SCOPES[$PUBLIC],
						'ranking.php' =>$SCOPES[$ADMIN]
						);