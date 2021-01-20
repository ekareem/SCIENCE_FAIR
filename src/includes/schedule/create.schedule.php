<?php
    //require_once "../const/config.const.php";
    require_once $PATH_ABSOLUTE."includes/dbh/retrive.dbh.php";

	function getSessions()
     {
         $query = "SELECT SESSION.SessionID from SESSION";
        $results = quaryDB($query);
	 	$array = array();
	 	foreach($results as $result)
	 	{
	 		array_push($array,$result['SessionID']);
	 	}
         return $array;
    }
	
    function projectsNotInSession($sessionID)
    {
        $query = "SELECT PROJECT.ProjectID
                    FROM PROJECT where PROJECT.ProjectID NOT IN (
                    SELECT PROJECT.ProjectID 
                        FROM SCHEDULE
                        INNER JOIN PROJECT ON PROJECT.ProjectID = SCHEDULE.ProjectID
                        WHERE SCHEDULE.SessionID = $sessionID
                    ) ";

        $results  = quaryDB($query);
        $array = array();
		foreach($results as $result)
		{
			array_push($array,$result['ProjectID']);
		}
        return $array;

    }

    function judgeNotInProject($projectID,$sessionID)
    {
        $query = "SELECT JUDGE.JudgeID 
                    FROM JUDGE where JUDGE.JudgeID NOT IN (
                    SELECT  JUDGE.JudgeID
                        FROM SCHEDULE
                        INNER JOIN JUDGE ON JUDGE.JudgeID = SCHEDULE.JudgeID
                        WHERE SCHEDULE.ProjectID = $projectID or SCHEDULE.SessionID = $sessionID
                    ) ";

        $results  = quaryDB($query);
        $array = array();
		foreach($results as $result)
		{
			array_push($array,$result['JudgeID']);
		}
        return $array;
    }

    function sessionChairID ()
    {
        $query = "SELECT JUDGE.JudgeID FROM JUDGE WHERE JUDGE.FirstName = 'session' AND JUDGE.LastName = 'Chair'";
        $result = quaryDB($query);
        return $result[0]["JudgeID"];
    }

    function runSchedule()
    {
        $sessions =  getSessions();
        foreach($sessions as $session)
        {
            $projects =  projectsNotInSession($session);
            //print_r($projects);
            
            foreach($projects as $project)
            {   
                $judges = judgeNotInProject($project,$session);
                
                //queue
                //$queue = new SplQueue();

                //$queue->enqueue($judge);
                if(!empty($judges))
                    $j = $judges[0];
                else 
                    $j = sessionChairID();

                    $p = $project;

                    $s = $session;
                    quaryDB("INSERT INTO `ekareem_db`.`SCHEDULE` ( `SessionID`, `ProjectID`, `JudgeID`) VALUES ('$s', '$p', '$j')");
                
            }
        }
    }

    function clearSchedule()
    {
        $query = "DELETE SCHEDULE.ScheduleID FROM SCHEDULE";
        quaryDB($query,true);
        
    }

    



