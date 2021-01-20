<?php
    #require_once "../const/config.const.php";
    require_once $PATH_ABSOLUTE."includes/dbh/retrive.dbh.php";

    function getAverage($id)
    {
        $query = "SELECT SCHEDULE.JudgeID FROM SCHEDULE WHERE SCHEDULE.ProjectID = $id AND SCHEDULE.Score != 'NULL'";
        $judges = quaryDB($query);

        $count = 1;
        $rank = 0;
        $avrg = 0;
        
        foreach ($judges as $judge)
        {
            $judgeID = $judge['JudgeID'];
            $query = "SELECT SCHEDULE.Score,SCHEDULE.ProjectID FROM SCHEDULE WHERE SCHEDULE.JudgeID = $judgeID AND SCHEDULE.Score != \"NULL\" ORDER BY SCHEDULE.Score   ASC";
            $scores = quaryDB($query);
            $arr = array();
            foreach($scores as $score)
            {
                array_push($arr, $score['ProjectID']);
            }
            $rank += getRank($arr,$id);
             
            $avrg = $rank / $count; 
            $count++;
        }

        $query = "UPDATE `ekareem_db`.`PROJECT` SET `AverageRanking` = '$avrg' WHERE `PROJECT`.`ProjectID` = $id";
        $judges = quaryDB($query,'error');
    }
    
    function getRank($scores  ,$id)
    {
        $count = 0;
        foreach($scores as $score)
        {
            if ($score == $id)
                return $count + 1;
            $count++;
        }
    }


    function getAllRanking()
    {
        $query = "SELECT PROJECT.ProjectID FROM PROJECT";
        $projects =  quaryDB($query);
        foreach($projects as $project)
        {
            getAverage($project['ProjectID']);
        }
    }

    