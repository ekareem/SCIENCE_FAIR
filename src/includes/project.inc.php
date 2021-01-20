<?php 
	require_once $PATH_ABSOLUTE."includes/const/db.const.php";
    require_once $PATH_ABSOLUTE."includes/dbh/retrive.dbh.php";

    if (session_status() == PHP_SESSION_NONE) session_start();
    
    $id = 1;
    if(isset($_GET['ProjectID']))
        $id = $_GET['ProjectID'];

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

    $sql = "SELECT PROJECT.ProjectNumber, PROJECT.Title, PROJECT.Abstract, PROJECT_GRADE_LEVEL.LevelName,CATEGORY.CategoryName,PROJECT.AverageRanking 
    FROM PROJECT 
        INNER JOIN PROJECT_GRADE_LEVEL ON PROJECT.ProjectGradeLevelID = PROJECT_GRADE_LEVEL.ProjectGradeLevelID
        INNER JOIN CATEGORY ON PROJECT.CategoryID = CATEGORY.CategoryID
    WHERE PROJECT.ProjectID = $id";
    $rows = quaryDB($sql);
    $row = $rows[0];

    function body()
    {
        global $id;
        global $row;
        
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
            Header('location:project.php');
        }
    }

    $condition = ' WHERE SCHEDULE.ProjectID = '. $id;
    if($_SESSION['type'] == 'JUDGE')
        $condition.=' AND JUDGE.JudgeID = '.$_SESSION['id']; //WHERE SCHEDULE.ProjectID = '. $id;
    function option()
    {
        global $id;
        $sql = "SELECT PROJECT.ProjectID,PROJECT.Title FROM PROJECT";

        if($_SESSION['type'] == 'JUDGE')
        {
            $sql = 'SELECT SCHEDULE.ProjectID,PROJECT.Title FROM SCHEDULE
            INNER JOIN PROJECT ON PROJECT.ProjectID = SCHEDULE.ProjectID AND SCHEDULE.JudgeID = '.$_SESSION['id'];
        }
        $results = quaryDB($sql);
        foreach ($results as $tr)
        {
            $selected = '';
            if ($id == $tr['ProjectID'])
                $selected = 'selected';

            echo '<option value = "'.$tr['ProjectID'].'" '.$selected.' > '.$tr['Title'].'</option>';
        }
    }

    function studentHead()
    {
        echo "<tr><th>NAME</th><th>GRADE</th></tr>";
    }

    function studentBody()
    {
        global $id;
        $quary = "SELECT STUDENT.StudentID, STUDENT.FirstName, STUDENT.LastName,GRADE.GradeName
                FROM STUDENT
                INNER JOIN GRADE ON STUDENT.GradeID = GRADE.GradeID 
                WHERE STUDENT.ProjectID = $id";
        
        $results = quaryDB($quary);

        foreach($results as $tr)
        {   
            $studentID = $tr['StudentID'];
            $firstname = $tr['FirstName'];
            $lastname = $tr['LastName'];
            $gradename = $tr['GradeName'];

            echo "  <tr>
                        <td><a href=\"student.php?StudentID=$studentID\">$firstname $lastname</a></td>
                        <td>$gradename</td>
                    </tr>";
        }
    }
    require_once  $PATH_ABSOLUTE."includes/util/schedule.util.php";

