<?php

    if (session_status() == PHP_SESSION_NONE) session_start();
	require_once $PATH_ABSOLUTE."includes/const/db.const.php";
    require_once $PATH_ABSOLUTE."includes/dbh/retrive.dbh.php";
    require_once $PATH_ABSOLUTE."includes/schedule/create.schedule.php";
    

    if(isset($_GET['submit']) && $_GET['submit']== 'schedule'  &&($_SESSION['type'] == 'ADMIN' ))
    {
        runSchedule();
        Header('location:schedule.php');
    }

    if(isset($_GET['submit']) && $_GET['submit']== 'edit'  &&($_SESSION['type'] == 'ADMIN' ))
    {
        $ScheduleID=$_GET['ScheduleID'];
        $SessionID=$_GET['SessionID'];
        $JudgeID=$_GET['JudgeID'];
        $ProjectID=$_GET['ProjectID'];
        $BoothID=$_GET['BoothID'];
        
        $sucess = false;
        $sql = "UPDATE `ekareem_db`.`SCHEDULE` SET `SessionID` = '$SessionID', `ProjectID` = '$ProjectID', `JudgeID` = '$JudgeID' WHERE `SCHEDULE`.`ScheduleID` = $ScheduleID";
        
        $sucess1 = quaryDB($sql,true);
        
        if($sucess1)
        {
            $sql = "UPDATE `ekareem_db`.`PROJECT` SET `BoothID` = '$BoothID' WHERE `PROJECT`.`ProjectID` = $ProjectID";
            $sucess2 = quaryDB($sql,true);
        }
        if($sucess2)
        {
            Header('location:schedule.php');
        }
    }

    $condition = '';
    if($_SESSION['type'] == 'JUDGE')
    {
        $condition = ' WHERE SCHEDULE.JudgeID = '.$_SESSION['id'];
    }

    function hasSchedule()
    {
        $quary = "SELECT * FROM SCHEDULE";
        $result = quaryDB($quary);

        return count($result) != 0;
    }   

    require_once  $PATH_ABSOLUTE."includes/util/schedule.util.php";