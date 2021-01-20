<?php 
	require_once $PATH_ABSOLUTE."includes/const/db.const.php";
    require_once $PATH_ABSOLUTE."includes/dbh/retrive.dbh.php";

    if (session_status() == PHP_SESSION_NONE) session_start();
    
    $id = 1;
    if(isset($_GET['StudentID']))
        $id = $_GET['StudentID'];

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

    $sql = "SELECT STUDENT.FirstName,STUDENT.LastName,GRADE.GradeName,GENDER.GenderName ,SCHOOL.SchoolName,STUDENT.ProjectID,PROJECT.Title 
	FROM STUDENT
    	INNER JOIN PROJECT ON PROJECT.ProjectID = STUDENT.ProjectID
        INNER JOIN GRADE ON GRADE.GradeID = STUDENT.GradeID
        INNER JOIN GENDER ON GENDER.GenderID = STUDENT.GenderID
        INNER JOIN SCHOOL ON SCHOOL.SchoolID = STUDENT.SchoolID
        WHERE STUDENT.StudentID = $id";
        
    $rows = quaryDB($sql);
    $row = $rows[0];

    function body()
    {
        global $id;
        global $row;
        
        foreach($row as $col => $data)
        {
            if($col != "ProjectID")
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
            Header('location:student.php');
        }
    }

    $condition = ' WHERE SCHEDULE.ProjectID = '.$row['ProjectID'] ;
    if($_SESSION['type'] == 'JUDGE')
        $condition .= ' AND SCHEDULE.JudgeID = '.$_SESSION['id'];

    function option()
    {
        global $id;
        $sql = "SELECT STUDENT.StudentID,STUDENT.FirstName,STUDENT.LastName FROM STUDENT";
        if($_SESSION['type'] == 'JUDGE')
        {  
            $sql = 'SELECT STUDENT.StudentID,STUDENT.FirstName,STUDENT.LastName
            FROM ((STUDENT
            INNER JOIN PROJECT ON PROJECT.ProjectID = STUDENT.ProjectID)
            INNER JOIN SCHEDULE ON SCHEDULE.ProjectID = PROJECT.ProjectID AND SCHEDULE.JudgeID = '.$_SESSION['id'].')';
        }
        $results = quaryDB($sql);
        foreach ($results as $tr)
        {
            $selected = '';
            if ($id == $tr['StudentID'])
                $selected = 'selected';

            echo '<option value = "'.$tr['StudentID'].'" '.$selected.'>'.$tr['FirstName'].' '.$tr['LastName'].'</option>';
        }
        
    }
    require_once  $PATH_ABSOLUTE."includes/util/schedule.util.php";

