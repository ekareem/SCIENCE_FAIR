<?php 
	require_once $PATH_ABSOLUTE."includes/const/db.const.php";
    require_once $PATH_ABSOLUTE."includes/dbh/retrive.dbh.php";

    if (session_status() == PHP_SESSION_NONE) session_start();
    
    $id = $_SESSION['id'];

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

    $sql = "";
    if($_SESSION['type'] == "JUDGE" )
        $sql = "SELECT JUDGE.FirstName,JUDGE.LastName,JUDGE.Title,JUDGE.HighestDegreeEarned,JUDGE_GRADE_PREFERENCE.minGradeID,JUDGE_GRADE_PREFERENCE.maxGradeID, CATEGORY.CategoryName
        FROM JUDGE
            INNER JOIN JUDGE_GRADE_PREFERENCE ON JUDGE.JudgeID = JUDGE_GRADE_PREFERENCE.JudgeID
            INNER JOIN JUDGE_CATEGORY_PREFERENCE ON JUDGE.JudgeID = JUDGE_CATEGORY_PREFERENCE.JudgeID
            INNER JOIN CATEGORY ON JUDGE_CATEGORY_PREFERENCE.CategoryID = CATEGORY.CategoryID
        WHERE JUDGE.JudgeID = $id";

    if($_SESSION['type'] == "ADMIN" )
        $sql = "SELECT ADMIN.FirstName, ADMIN.LastName, ADMIN.Email FROM ADMIN WHERE ADMIN.AdminID = $id";

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

    if($_SESSION['type'] == "JUDGE" )
    {
        $condition = ' WHERE SCHEDULE.JudgeID = '. $id;
        function option()
        {
            $sql = "SELECT JUDGE.JudgeID,JUDGE.FirstName,JUDGE.LastName FROM JUDGE";
            $results = quaryDB($sql);
            foreach ($results as $tr)
            {
                echo '<option value = "'.$tr['JudgeID'].'">'.$tr['FirstName'].' '.$tr['LastName'].'</option>';
            }
            
        }
        //require_once  $PATH_ABSOLUTE."includes/util/schedule.util.php";
    }

