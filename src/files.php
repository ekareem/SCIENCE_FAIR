<?php
    require_once "includes/const/config.const.php";
	require_once $PATH_ABSOLUTE."includes/util/secure/session.util.php";
    require_once $PATH_ABSOLUTE."includes/util/secure/scope.util.php";
    //require_once $PATH_ABSOLUTE."includes/dbh/connect.dbh.php";

    function displayFiles(){
        $path = 'csv/';
        $files = array_diff(scandir($path), array('.','..'));
        if (!empty($files)){
            print "<table id='fileTable' class='display' cellspacing='0'>";
            print "<thead><tr><th>File Name</th><th>Insert</th></tr></thead>";

            foreach ($files as $file){
                if ($file != "template"){
                    print "<form method='post' action='files.php' enctype='multipart/form-data'><tr>";
                    print "<td>$file</td><td><input type='hidden' name='file' value='".$file."'><input type='submit' name='insert' value='Insert $file into DataBase'></td>";
                    print "</tr></form>";
                }
            }

            print "</table>";
        }
        else{
            print "No csv files";
        }
    }

    //returns a 2d array where each row is a row in the csv, includes the header
    function csvToArray($filepath){
        $file = fopen($filepath,"r");
        $csvarray = array();
        
        while (! feof($file)){
            $row = fgetcsv($file);
            array_push($csvarray,$row);        
        }
        return $csvarray;
    }

    function insertCsv($filepath){
        $array = csvToArray($filepath);
        if(!empty($array)){
            //remove header from array 
            array_shift($array);
            foreach($array as $row){
                
                $firstName = strtolower($row[0]);
                if ($firstName == ""){
                    //echo "skipped first";
                    continue;
                }

                $middleName = strtolower($row[1]);
                if($middleName == ""){
                    $middleName = NULL;
                }

                $lastName = strtolower($row[2]);
                if ($lastName == ""){
                    //echo "skipped last";
                    continue;
                }


                $studentGrade = strtolower($row[3]);
                if ($studentGrade == ""){
                    //echo "skipped student grade";
                    continue;
                }else if($studentGrade == "k"){
                    $studentGrade = 0;
                }

                $gender = strtolower($row[4]);
                if ($gender == ""){
                    //echo "skipped gender";
                    continue;
                }
                $studentYear = strtolower($row[5]);
                if ($studentYear == ""){
                    //echo "skipped student year";
                    continue;
                }
                $schoolName = strtolower($row[6]);
                if ($schoolName == ""){
                    //echo "skipped school name";
                    continue;
                }
                $stateName = strtolower($row[7]);
                if ($stateName == ""){
                    //echo "skipped state name";
                    continue;
                }
                $stateCode = strtolower($row[8]);
                if($stateCode == ""){
                    //echo "skipped state code";
                    continue;
                }
                $county = strtolower($row[9]);
                if($county == ""){
                    //echo "skipped county";
                    continue;
                }
                $city = strtolower($row[10]);
                if ($city == ""){
                    //echo "skipped city ";
                    continue;
                }
                $projectNumber = strtolower($row[11]);
                if ($projectNumber == ""){
                    //echo "skipped project number";
                    continue;
                }
                $projectTitle = strtolower($row[12]);
                if ($projectTitle == ""){
                    //echo "skipped project title";
                    continue;
                }
                $projectAbstract = strtolower($row[13]);
                if ($projectAbstract == ""){
                    //echo "skipped project abstract";
                    continue;
                }
                $projectGL = strtolower($row[14]);
                if ($projectGL == ""){
                    //echo "skipped project gl";
                    continue;
                }
                $projectGLMin = strtolower($row[15]);
                if ($projectGLMin == ""){
                    //echo "skipped min";
                    continue;
                }
                $projectGLMax = strtolower($row[16]);
                if ($projectGLMax == ""){
                    //echo "skipped max";
                    continue;
                }
                $category = strtolower($row[17]);
                if($category == ""){
                    //echo "skipped category";
                    continue;
                }
                $boothNumber = strtolower($row[18]);
                if ($boothNumber == ""){
                    //echo "skipped booth";
                    continue;
                }
                $courseNetwork = strtolower($row[19]);
                if ($courseNetwork == ""){
                    $courseNetwork = NULL;
                }
                $projectYear = strtolower($row[20]);
                if ($projectYear == ""){
                    //echo "skipped project year";
                    continue;
                }
                /*** mysql hostname ***/
                $hostname = 'localhost';

                /*** mysql username ***/
                $username = 'ekareem';

                /*** mysql password ***/
                $password = 'ekareem';

                //grade levels are already in the DB no need to insert
                //gender already in the DB no need to insert
                try{
                    $con = new PDO("mysql:host=$hostname;dbname=ekareem_db", $username, $password);
                    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    //reused queries 
                    //these do not consider duplicates, like two schools with the same name
                    $stateIdSql = ("SELECT DISTINCT StateID from STATE where StateName = ?");
                    $stateIdStmt = $con->prepare($stateIdSql);

                    $cityIdSql = ("select distinct CityID from CITY where CityName = ? AND StateID = ?");
                    $cityIdStmt = $con->prepare($cityIdSql);

                    $countyIdSql = ("select distinct CountyID from COUNTY where CountyName = ? AND StateID = ?");
                    $countyIdStmt = $con->prepare($countyIdSql);

                    $schoolIdSql = ("select distinct SchoolID from SCHOOL where SchoolName = ? AND StateID = ? AND CountyID = ? AND CityID = ?");
                    $schoolIdStmt = $con->prepare($schoolIdSql);

                    $projectGLIdSql = ("select distinct ProjectGradeLevelID from PROJECT_GRADE_LEVEL where LevelName = ? AND MinGradeID = ? AND MaxGradeID = ?");
                    $projectGLIdStmt = $con->prepare($projectGLIdSql);

                    $categoryIdSql = ("select distinct CategoryID from CATEGORY where CategoryName = ? AND Active = ?");
                    $categoryIdStmt = $con->prepare($categoryIdSql);

                    $boothIdSql = ("select distinct BoothID from BOOTH where Number = ? AND Active = ?");
                    $boothIdStmt = $con->prepare($boothIdSql);

                    $projectIdSql = ("select distinct ProjectID from PROJECT where ProjectNumber = ? AND Title = ? AND Abstract = ? AND ProjectGradeLevelID = ? AND CategoryID = ? AND BoothID = ? AND Year = ?");
                    $projectIdStmt = $con->prepare($projectIdSql);



                    //insert state
                    $stateCheckSql = ("select count(*) from STATE where StateName = '".$stateName."'");
                    $stateCheckResult = $con->query($stateCheckSql);
                    $count = $stateCheckResult->fetchColumn();
                    if ($count == 0){
                        $stateSql = "INSERT INTO STATE (StateCode, StateName) VALUES (?,?)";
                        $stateStmt = $con->prepare($stateSql);
                        $stateStmt->execute([$stateCode, $stateName]);
                        //echo "New state:".$stateName." added successfully<br>";
                    }else{
                        //echo "State:".$stateName." already in DB<br>";
                    }
                    
                    //save state id for the state 
                    $stateIdStmt->execute([$stateName]);
                    $stateId = $stateIdStmt->fetch()["StateID"];
                  
                    
                    //insert city 
                    $cityCheckSql = ("select count(*) from CITY where CityName = '".$city."'");
                    $cityCheckResult = $con->query($cityCheckSql);
                    $count = $cityCheckResult->fetchColumn();
                    if ($count == 0){
                        // $stateIdSql = ("select StateId from STATE where StateName = '".$stateName."'");
                        // $stateId = $con->query($stateIdSql);
                        
                        $citySql = "INSERT INTO CITY (StateID, CityName) VALUES (?,?)";
                        $cityStmt = $con->prepare($citySql);
                        $cityStmt->execute([$stateId, $city]);
                        //echo "New city:".$city." added successfully<br>";
                    }else{
                        //echo "City:".$city." is already in DB<br>";
                    }
                    
                    //save the city id for the city
                    $cityIdStmt->execute([$city,$stateId]);
                    $cityId = $cityIdStmt->fetch()["CityID"];

                    //insert county
                    $countyCheckSql = ("select count(*) from COUNTY where CountyName = '".$county."'");
                    $countyCheckResult = $con->query($countyCheckSql);
                    $count = $countyCheckResult->fetchColumn();
                    if($count == 0){
                        
                        $countySql = "INSERT INTO COUNTY (StateID, CountyName) VALUES (?,?)";
                        $countyStmt = $con->prepare($countySql);
                        $countyStmt->execute([$stateId, $county]);
                        //echo "New county :".$county." added successfully<br>";
                    }else{
                        //echo "County :".$county." is already in DB<br>";
                    }
                    
                    //save the county id 
                    
                    $countyIdStmt->execute([$county,$stateId]);
                    $countyId = $countyIdStmt->fetch()["CountyID"];
                    

                    //insert school
                    $schoolCheckSql = ("select count(*) from SCHOOL where SchoolName = '".$schoolName."' AND StateID = '".$stateId."' AND CountyID = '".$countyId."' AND CityID = '".$cityId."'");
                    $schoolCheckResult = $con->query($schoolCheckSql);
                    $count = $schoolCheckResult->fetchColumn();
                    if($count == 0){
                       $schoolSql = "INSERT INTO SCHOOL (SchoolName, CityID, CountyID, StateID) VALUES (?,?,?,?)";
                       $schoolStmt = $con->prepare($schoolSql);
                       $schoolStmt->execute([$schoolName,$cityId,$countyId,$stateId]);
                       //echo "New school :".$schoolName." added successfully<br>";
                    }else{
                        //echo "School :".$schoolName." is already in DB<br>";
                    }

                    //save school ID for new school
                    $schoolIdStmt->execute([$schoolName,$stateId,$countyId,$cityId]);
                    $schoolId = $schoolIdStmt->fetch()["SchoolID"];

                    //insert project grade level
                    $projectGLMin = (int)$projectGLMin + 1;
                    $projectGLMax = (int)$projectGLMax + 1;
                    $projectGLCheckSql = ("select count(*) from PROJECT_GRADE_LEVEL where MinGradeID = '".$projectGLMin."' AND MaxGradeID = '".$projectGLMax."'");
                    $projectGLCheckResult = $con->query($projectGLCheckSql);
                    $count = $projectGLCheckResult->fetchColumn();
                    if($count == 0){
                        $projectGLSql = "INSERT INTO PROJECT_GRADE_LEVEL (LevelName, MinGradeID, MaxGradeID) VALUES (?,?,?)";
                        $projectGLStmt = $con->prepare($projectGLSql);
                        $projectGLStmt->execute([$projectGL,$projectGLMin,$projectGLMax]);
                        //echo "New project grade level: ".$projectGL." added successfully<br>";
                    }else{
                        //echo "Project Grade Level: ".$projectGL." is already in DB<br>";
                    }
                    $projectGLIdStmt->execute([$projectGL,$projectGLMin,$projectGLMax]);
                    $projectGLId = $projectGLIdStmt->fetch()["ProjectGradeLevelID"];

                    //insert category
                    $categoryCheckSql = ("select count(*) from CATEGORY where CategoryName = '".$category."'");
                    $categoryCheckResult = $con->query($categoryCheckSql);
                    $count = $categoryCheckResult->fetchColumn();
                    if($count == 0){
                        $categorySql = "INSERT INTO CATEGORY (CategoryName, Active) VALUES (?,?)";
                        $categoryStmt = $con->prepare($categorySql);
                        $categoryStmt->execute([$category,1]);
                        //echo "New category: ".$category." added successfully<br>";
                    }else{
                        //echo "Category: ".$category." is already in DB<br>";
                    }
                    $categoryIdStmt->execute([$category,1]);
                    $categoryId = $categoryIdStmt->fetch()["CategoryID"];

                    //insert booth
                    $boothCheckSql = ("select count(*) from BOOTH where Number = '".$boothNumber."'");
                    $boothCheckResult = $con->query($boothCheckSql);
                    $count = $boothCheckResult->fetchColumn();
                    if($count == 0){
                        $boothSql = "INSERT INTO BOOTH (Number,Active) VALUES (?,?)";
                        $boothStmt = $con->prepare($boothSql);
                        $boothStmt->execute([$boothNumber,1]);
                        //echo "New booth: ".$boothNumber." added successfully<br>";
                    }else{
                        //echo "Booth: ".$boothNumber." is already in DB<br>";
                    }
                    $boothIdStmt->execute([$boothNumber, 1]);
                    $boothId = $boothIdStmt->fetch()["BoothID"];

                    //insert project
                    $projectYear = (int)$projectYear;
                    //$projectCheckSql = ("select count(*) from PROJECT where ProjectNumber = '".$projectNumber."' AND Title = '".$projectTitle."' AND Abstract = '".$projectAbstract."' AND ProjectGradeLevelID = '".$projectGLId."' AND CategoryID = '".$categoryId."' AND BoothID = '".$boothId."' AND CourseNetworkID = '".$courseNetwork."' AND Year = '".$projectYear."'");
                    $projectCheckSql = ("select count(*) from PROJECT where ProjectNumber = '".$projectNumber."' AND Title = '".$projectTitle."' AND Abstract = '".$projectAbstract."' AND ProjectGradeLevelID = '".$projectGLId."' AND CategoryID = '".$categoryId."' AND BoothID = '".$boothId."' AND Year = '".$projectYear."'");
                    $projectCheckResult = $con->query($projectCheckSql);
                    $count = $projectCheckResult->fetchColumn();
                    if($count == 0){
                        $projectSql = "INSERT INTO PROJECT (ProjectNumber, Title, Abstract, ProjectGradeLevelID, CategoryID, BoothID, CourseNetworkID, AverageRanking, Year) VALUES (?,?,?,?,?,?,?,?,?)";
                        $projectStmt = $con->prepare($projectSql);
                        $projectStmt->execute([$projectNumber, $projectTitle, $projectAbstract,$projectGLId,$categoryId,$boothId,$courseNetwork,0,$projectYear]);
                        echo "New project: ".$projectTitle." added successfully<br>";
                    }else{
                        echo "Project: ".$projectTitle." already in DB<br>";
                    }
                    $projectIdStmt->execute([$projectNumber, $projectTitle, $projectAbstract,$projectGLId,$categoryId,$boothId,$projectYear]);
                    $projectId = $projectIdStmt->fetch()["ProjectID"];

                    //insert student
                    $studentYear = (int)$studentYear;
                    $studentGrade = (int)$studentGrade + 1;
                    $studentCheckSql = ("select count(*) from STUDENT where FirstName = '".$firstName."' AND LastName = '".$lastName."' AND GradeID = '".$studentGrade."' AND SchoolID = '".$schoolId."' AND ProjectID = '".$projectId."' AND Year = '".$studentYear."'");
                    $studentCheckResult = $con->query($studentCheckSql);
                    $count = $studentCheckResult->fetchColumn();
                    if($count == 0){
                        $studentSql = "INSERT INTO STUDENT (FirstName, MiddleName,LastName,GradeID,GenderID,SchoolID,ProjectID,Year,Active) VALUES (?,?,?,?,?,?,?,?,?)";
                        $studentStmt = $con->prepare($studentSql);
                        if ($gender == 'm'){
                            $genderId = 1;
                        }else{
                            $genderId = 2;
                        }
                        $studentStmt->execute([$firstName,$middleName,$lastName,$studentGrade,$genderId,$schoolId,$projectId,$studentYear,1]);
                        echo "New student: ".$firstName." ".$lastName." added successfully<br>";
                    }else{
                        echo "Student:".$firstName." ".$lastName." already in DB<br>";
                    }
                    


                }catch (PDOexception $e){
                    echo "<br>" . $e->getMessage();
                }
                

                //$schoolSql = "INSERT INTO SCHOOL";

                
            }
        }else{
            return;
        }

    }


?>

<html>
	<head>
		<title>Files</title>
        <?php require_once $PATH_ABSOLUTE."templates/meta.html" ?>
        <?php require_once $PATH_ABSOLUTE."templates/crud/head.html" ?>
	</head>
	<body>

    <!-- Header -->
	<header id="header">
		<?php require_once $PATH_ABSOLUTE."templates/header.html"?>
	</header>

    <!-- Menu -->
    <nav id="menu">
			<?php include_once $PATH_ABSOLUTE."templates/menu.php" ?>
    </nav>
            
            
    <h1 class="page-title">CSV Files</h1>

    <?php displayFiles(); ?>
    <?php 
        if(isset($_POST['insert'])){
            $filepath = 'csv/' . $_POST['file'];
            insertCsv($filepath);
            
        }
    //insertCsv("csv/student.csv"); 
    
    ?>
    <!-- Scripts -->
	<?php require_once $PATH_ABSOLUTE."templates/crud/script.html"?>

    </body>
</html> 