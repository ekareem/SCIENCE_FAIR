<?php	
	

    require_once  $PATH_ABSOLUTE."includes/dbh/retrive.dbh.php";
    require_once  $PATH_ABSOLUTE."includes/util/rank.util.php";

    if($_SESSION['type'] == "ADMIN" && isset($_GET['submit']) && $_GET['submit'] = "ranking")
    {
         getAllRanking();
    }
    
    function printHeader(){
     echo '<tr > 
     <th> JUDGE </th>
     <th> PROJECT </th>
     <th> SCORE </th>
     
     
     </tr>';

    }

    function allScoresGraded()
    {
       $query = "SELECT * FROM `SCHEDULE` WHERE SCHEDULE.Score is NULL";

       $result = quaryDB($query);
       return count($result) == 0;
    }

    function printBody(){
        $query = "SELECT SCHEDULE.Score, PROJECT.ProjectID, JUDGE.JudgeID, JUDGE.FirstName, JUDGE.LastName, PROJECT.Title FROM SCHEDULE INNER JOIN PROJECT ON SCHEDULE.ProjectID = PROJECT.ProjectID INNER JOIN JUDGE ON SCHEDULE.JudgeID = JUDGE.JudgeID ";
        $rows =  quaryDB($query);
       
       foreach($rows as $row) {

          $firstName = $row['FirstName'];
          $lastName = $row['LastName'];
          $projectTitle = $row['Title'];
          $Score = $row['Score'];

          echo '<tr >';


          echo "<td>$firstName $lastName  </td>";
          echo "<td> $projectTitle </td>";
          echo "<td> $Score </td>";
          echo '</tr>';
       }
          
    }
