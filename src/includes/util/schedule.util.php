    <?php
    if (session_status() == PHP_SESSION_NONE) session_start();
    require_once $PATH_ABSOLUTE."includes/const/db.const.php";
    require_once $PATH_ABSOLUTE."includes/dbh/retrive.dbh.php";

    if(isset($_GET['Score']) && isset($_GET['scheduleID']))
    {
        $score = $_GET['Score'];
        $scheduleID = $_GET['scheduleID'];
        $sql = "UPDATE `ekareem_db`.`SCHEDULE` SET `Score` = '$score' WHERE `SCHEDULE`.`ScheduleID` = $scheduleID";
        echo $sql;
        if(quaryDB($sql,true))
            Header('Location:schedule.php');
    }

    $sql =' SELECT SCHEDULE.ScheduleID,
    SCHEDULE.Score,
    JUDGE.JudgeID,
    JUDGE.FirstName, 
    JUDGE.LastName, 
    PROJECT.ProjectID, 
    PROJECT.Title ,PROJECT.BoothID, 
    BOOTH.Number,
    SESSION.SessionID,
    SESSION.Date, 
    SESSION.StartTime, 
    SESSION.EndTime
    FROM SCHEDULE
        INNER JOIN SESSION ON SCHEDULE.SessionID = SESSION.SessionID
        INNER JOIN PROJECT ON SCHEDULE.ProjectID = PROJECT.ProjectID
        INNER JOIN JUDGE ON SCHEDULE.JudgeID = JUDGE.JudgeID
        INNER JOIN BOOTH ON PROJECT.BoothID = BOOTH.BoothID';

    $sql.=$condition;

    $rows = quaryDB($sql);
    //print_r($rows);

    function createHeader()
    {
        echo
            "<tr>
                <th>JUDGE</th>
                <th>PROJECT</th>
                <th>BOOTH</th> 
                <th>DATE</th>
                <th>START TIME</th>
                <th>START END</th> 
                <th>SCORE</th>";
        if(!isset($_SESSION['chair']))
                echo "<th></th>";
        #}
        #else
        #echo "<th></th>";
        //if(!isset($_SESSION['chair']))
            //echo "<tr></tr>";
    }

    function createBody()
    {
        GLOBAL $rows;
        GLOBAL $DB_JUDGE_COL_FIRST_NAME;
        GLOBAL $DB_JUDGE_COL_LAST_NAME;
        GLOBAL $DB_PROJECT_COL_TITLE;
        GLOBAL $DB_BOOTH_COL_NUMBER;
        GLOBAL $DB_SESSION_COL_DATE;
        GLOBAL $DB_SESSION_COL_START;
        GLOBAL $DB_SESSION_COL_END;
        GLOBAL $DB_SCHEDULE_COL_ID;
        GLOBAL $DB_PROJECT_COL_ID; 
        GLOBAL $DB_SESSION_COL_ID;
        GLOBAL $DB_JUDGE_COL_ID;
        GLOBAL $DB_BOOTH_COL_ID;

        foreach($rows as $row)
        {
            $firstName = $row[$DB_JUDGE_COL_FIRST_NAME];
            $lastName = $row[$DB_JUDGE_COL_LAST_NAME];
            $title = $row[$DB_PROJECT_COL_TITLE];
            $number = $row[$DB_BOOTH_COL_NUMBER];
            $date = $row[$DB_SESSION_COL_DATE];
            $start = $row[$DB_SESSION_COL_START];
            $end = $row[$DB_SESSION_COL_END];
            $projectID = $row[$DB_PROJECT_COL_ID];
            $scheduleID = $row[$DB_SCHEDULE_COL_ID];
            $sessionID = $row[$DB_SESSION_COL_ID];
            $judgeID = $row[$DB_JUDGE_COL_ID];
            $boothID = $row[$DB_BOOTH_COL_ID];
            $score = $row['Score'];
            echo "<tr>";
            if($_SESSION['type'] == "ADMIN")
                echo "<td><a href=\"judge.php?JudgeID=$judgeID\">$firstName $lastName</a></td>";
            else if($_SESSION['type'] == "JUDGE")
                echo "<td><a href=\"profile.php\">$firstName $lastName</a></td>";

            echo    "<td><a href=\"project.php?ProjectID=$projectID\">$title</a></td>
                    <td><a href=\"booth.php?BoothID=$boothID\">$number</a></td>
                    <td><a href=\"session.php?SessionID=$sessionID\">$date</a></td>
                    <td><a href=\"session.php?SessionID=$sessionID\">$start</a></td>
                    <td><a href=\"session.php?SessionID=$sessionID\">$end</a></td>";    

                    if($_SESSION['type'] == 'JUDGE' && $score == '' && $judgeID == $_SESSION['id'])
                    {
                        echo'<form method = "get" action = "#">
                        <td> <input type = "number" min="0" max = "100" name ="Score" value = "'.$score.'" required/></td>
                        <td> <button type="submit" class="btn btn-success" name="scheduleID" value="'.$scheduleID.'"><span class="glyphicon glyphicon-plus"></span></button></td>
                        </form>';
                    }
                    else if($_SESSION['type'] == 'JUDGE')
                    {
                        echo "<td>$score</td><td></td>";
                    }
                    else
                    {
                        echo"<td>$score </td>";
                    }
                    
                    if($_SESSION['type'] == 'ADMIN' && (!isset($_SESSION['chair'])))
                      echo "<td><a href=\"?$DB_SCHEDULE_COL_ID=$scheduleID&$DB_SESSION_COL_ID=$sessionID&$DB_JUDGE_COL_ID=$judgeID&$DB_PROJECT_COL_ID=$projectID&$DB_BOOTH_COL_ID=$boothID\" class=\"glyphicon glyphicon-edit\"></a> </td>";
            echo    "</tr>";
        }
    }

    function createFooter()
    {
        
    }

    function form()
    {
        echo '<input type = "hidden" name = "ScheduleID"value="'.$_GET['ScheduleID'].'"/>';
        judgeOption();
        projectOption();
        boothOption();
        sessionOption();
        
    }


    function judgeOption()
    {
        GLOBAL $DB_JUDGE_COL_FIRST_NAME;
        GLOBAL $DB_JUDGE_COL_LAST_NAME;
        GLOBAL $DB_PROJECT_COL_TITLE;
        GLOBAL $DB_BOOTH_COL_NUMBER;
        GLOBAL $DB_SESSION_COL_DATE;
        GLOBAL $DB_SESSION_COL_START;
        GLOBAL $DB_SESSION_COL_END;
        GLOBAL $DB_SCHEDULE_COL_ID; 
        GLOBAL $DB_PROJECT_COL_ID; 
        GLOBAL $DB_SESSION_COL_ID;
        GLOBAL $DB_JUDGE_COL_ID;
        GLOBAL $DB_BOOTH_COL_ID;
        echo '
            <label for="cars">JUDGE</label>
                <select name="'. $DB_JUDGE_COL_ID.'" id="'. $DB_JUDGE_COL_ID.'">';

        $sql = "SELECT `$DB_JUDGE_COL_ID`,`$DB_JUDGE_COL_FIRST_NAME`,`$DB_JUDGE_COL_LAST_NAME` FROM `JUDGE` WHERE ".$_GET[$DB_JUDGE_COL_ID];
        $rows = quaryDB($sql);   

        foreach($rows as $row)
        {
            echo '<option value="'.$row[$DB_JUDGE_COL_ID].'"';
            if($_GET[$DB_JUDGE_COL_ID] == $row[$DB_JUDGE_COL_ID])
                echo 'selected';
            echo '>'.$row[$DB_JUDGE_COL_FIRST_NAME].' '. $row[$DB_JUDGE_COL_LAST_NAME];
            echo'</option>';
        }
        echo' </select>';
    }

    function projectOption()
    {
        GLOBAL $DB_JUDGE_COL_FIRST_NAME;
        GLOBAL $DB_JUDGE_COL_LAST_NAME;
        GLOBAL $DB_PROJECT_COL_TITLE;
        GLOBAL $DB_BOOTH_COL_NUMBER;
        GLOBAL $DB_SESSION_COL_DATE;
        GLOBAL $DB_SESSION_COL_START;
        GLOBAL $DB_SESSION_COL_END;
        GLOBAL $DB_PROJECT_COL_ID; 
        GLOBAL $DB_SCHEDULE_COL_ID; 
        GLOBAL $DB_SESSION_COL_ID;
        GLOBAL $DB_JUDGE_COL_ID;
        GLOBAL $DB_BOOTH_COL_ID;
        echo '
            <label for="cars">PROJECT</label>
                <select name="'. $DB_PROJECT_COL_ID.'" id="'. $DB_PROJECT_COL_ID.'">';

        $sql = "SELECT `$DB_PROJECT_COL_ID`,`$DB_PROJECT_COL_TITLE` FROM `PROJECT` WHERE ".$_GET[$DB_PROJECT_COL_ID];
        $rows = quaryDB($sql);
        
        foreach($rows as $row)
        {
            
            echo '<option value="'.$row[$DB_PROJECT_COL_ID].'"';
            if($_GET[$DB_PROJECT_COL_ID] == $row[$DB_PROJECT_COL_ID])
                echo 'selected';
            echo '>'.$row[$DB_PROJECT_COL_TITLE];
            echo'</option>';
        }

        echo ' </select>';
    }

    function boothOption()
    {
        GLOBAL $DB_JUDGE_COL_FIRST_NAME;
        GLOBAL $DB_JUDGE_COL_LAST_NAME;
        GLOBAL $DB_PROJECT_COL_TITLE;
        GLOBAL $DB_BOOTH_COL_NUMBER;
        GLOBAL $DB_SESSION_COL_DATE;
        GLOBAL $DB_SESSION_COL_START;
        GLOBAL $DB_SESSION_COL_END;
        GLOBAL $DB_PROJECT_COL_ID; 
        GLOBAL $DB_SCHEDULE_COL_ID; 
        GLOBAL $DB_SESSION_COL_ID;
        GLOBAL $DB_JUDGE_COL_ID;
        GLOBAL $DB_BOOTH_COL_ID;
        echo '
            <label for="cars">BOOTH</label>
                <select name="'. $DB_BOOTH_COL_ID.'" id="'. $DB_BOOTH_COL_ID.'">';

        $sql = "SELECT `$DB_BOOTH_COL_ID`,`$DB_BOOTH_COL_NUMBER` FROM `BOOTH` WHERE ".$_GET[$DB_BOOTH_COL_ID];
        $rows = quaryDB($sql);
        
        foreach($rows as $row)
        {
            
            echo '<option value="'.$row[$DB_BOOTH_COL_ID].'"';
            if($_GET[$DB_BOOTH_COL_ID] == $row[$DB_BOOTH_COL_ID])
                echo 'selected';
            echo '>'.$row[$DB_BOOTH_COL_NUMBER];
            echo'</option>';
        }

        echo ' </select>';
    }

    function sessionOption()
    {
        GLOBAL $DB_JUDGE_COL_FIRST_NAME;
        GLOBAL $DB_JUDGE_COL_LAST_NAME;
        GLOBAL $DB_PROJECT_COL_TITLE;
        GLOBAL $DB_BOOTH_COL_NUMBER;
        GLOBAL $DB_SESSION_COL_DATE;
        GLOBAL $DB_SESSION_COL_START;
        GLOBAL $DB_SESSION_COL_END;
        GLOBAL $DB_PROJECT_COL_ID; 
        GLOBAL $DB_SCHEDULE_COL_ID; 
        GLOBAL $DB_SESSION_COL_ID;
        GLOBAL $DB_JUDGE_COL_ID;
        GLOBAL $DB_BOOTH_COL_ID;
        echo '
            <label for="cars">SESSION</label>
                <select name="'. $DB_SESSION_COL_ID.'" id="'. $DB_SESSION_COL_ID.'">';

        $sql = "SELECT `$DB_SESSION_COL_ID`,`$DB_SESSION_COL_DATE`,`$DB_SESSION_COL_START`,`$DB_SESSION_COL_END` FROM `SESSION` WHERE ".$_GET[$DB_SESSION_COL_ID];
        $rows = quaryDB($sql);
        
        foreach($rows as $row)
        {
            
            echo '<option value="'.$row[$DB_SESSION_COL_ID].'"';
            if($_GET[$DB_SESSION_COL_ID] == $row[$DB_SESSION_COL_ID])
                echo 'selected';
            echo '> date: '.$row[$DB_SESSION_COL_DATE].'  -  start: '.$row[$DB_SESSION_COL_START].'  -  end: '.$row[$DB_SESSION_COL_END];
            echo'</option>';
        }

        echo '</select>';
    }
