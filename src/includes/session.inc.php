<?php 
	require_once $PATH_ABSOLUTE."includes/const/db.const.php";
    require_once $PATH_ABSOLUTE."includes/dbh/retrive.dbh.php";

    if (session_status() == PHP_SESSION_NONE) session_start();
    
    $id = 1;
    if(isset($_GET['SessionID']))
        $id = $_GET['SessionID'];

    function head()
    {
        echo 
        '<tr>
                <th>
                </th>
                <th>
                </th>
            </tr>';
    }

    $sql = "SELECT SESSION.SessionID,SESSION.Date,SESSION.StartTime,SESSION.EndTime
        FROM SESSION
    WHERE SESSION.SessionID = $id";
    $rows = quaryDB($sql);
    $row = $rows[0];

    function body()
    {
        global $id;
        global $row;
        
        if(!empty($row))
            foreach($row as $col => $data)
            {
                echo "<tr>
                            <td>$col</td>
                            <td>$data</td>
                    </tr>";
            }
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
            Header('location:session.php');
        }
    }

    $condition = ' WHERE SCHEDULE.SessionID = '. $id;
    if($_SESSION['type'] == 'JUDGE')
        $condition .= ' AND SCHEDULE.JudgeID = '.$_SESSION['id'];
    function option()
    {
        global $id;
        $sql = "SELECT SESSION.SessionID,SESSION.Date,SESSION.StartTime,SESSION.EndTime FROM SESSION";
        if($_SESSION['type'] == 'JUDGE')
        {  
            $sql = 'SELECT SESSION.SessionID,SESSION.Date,SESSION.StartTime,SESSION.EndTime
            FROM SESSION
            INNER JOIN SCHEDULE ON SCHEDULE.SessionID = SESSION.SessionID AND SCHEDULE.JudgeID = '.$_SESSION['id'];
        }
        $results = quaryDB($sql);
        foreach ($results as $tr)
        {
            $selected = '';
            if ($id == $tr['SessionID'])
                $selected = 'selected';

            echo '<option value = "'.$tr['SessionID'].'" '.$selected.'>'.$tr['Date'].' '.$tr['StartTime'].' '.$tr['EndTime'].'</option>';
        }
        
    }
    require_once  $PATH_ABSOLUTE."includes/util/schedule.util.php";

