<?php	
	

    require_once  $PATH_ABSOLUTE."includes/dbh/retrive.dbh.php";
    require_once  $PATH_ABSOLUTE."includes/util/rank.util.php";

    if($_SESSION['type'] == "ADMIN" && isset($_GET['submit']) && $_GET['submit'] = "ranking")
    {
         getAllRanking();
    }

    function allScoresGraded()
    {
       $query = "SELECT * FROM `SCHEDULE` WHERE SCHEDULE.Score is NULL";

       $result = quaryDB($query);
       return count($result) == 0;
    }

    
    function rankHeader(){
      echo '<tr > 
      <th> RANK </th>
      <th> PROJECT </th>
      <th> AVERAGE RANKING </th>
      </tr>';
     }
 
     function rankBody()
     {

      $query = "SELECT PROJECT.Title,PROJECT.AverageRanking FROM PROJECT ORDER BY PROJECT.AverageRanking ASC";
      $rows =  quaryDB($query);
     
      $count = 1;
      foreach($rows as $row) {

         $title = $row['Title'];
         $averageRanking = $row['AverageRanking'];

         echo '<tr >';


         echo "<td>$count  </td>";
         echo "<td> $title </td>";
         echo "<td> $averageRanking </td>";
         echo '</tr>';
         $count ++;
      }

      }
