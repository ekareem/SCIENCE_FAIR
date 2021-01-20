<?php 
if (session_status() == PHP_SESSION_NONE) session_start();

    $allowedExt = "csv";
    

     if(isset($_POST["submit"])){
        if($_FILES["file"]["size"] > 20000000){
            echo "File too large.";
        }
        else if(($_FILES["file"]["type"] == "application/vnd.ms-excel") || ($_FILES["file"]["type"] == "text/csv")){
            $temp = explode(".",$_FILES["file"]["name"]);
            $extension = end($temp);
            if($extension == $allowedExt){
                if ($_FILES["file"]["error"] > 0)
                {
                    echo "<h1>Return Code: ". $_FILES["file"]["error"] . "</h1><br>";
                }else{
                    if(file_exists("csv/" . $_FILES["file"]["name"]))
                    {
                        move_uploaded_file($_FILES["file"]["tmp_name"], "csv/" . $_FILES["file"]["name"]);
                        echo "<br><h1>File ".$_FILES["file"]["name"]. " was overwritten</h1>";
                    }else if (is_uploaded_file($_FILES['file']['tmp_name'])){
                        move_uploaded_file($_FILES["file"]["tmp_name"], "csv/" . $_FILES["file"]["name"]);
                        print '<br><h1>File uploaded: '. $_FILES["file"]["name"]. "</h1>";
                    }else{
                        echo "<h1>unable to upload file</h1>";
                    }
                }
            }else{
                echo "<h1>Invalid file</h1>";
            }
        }else{
            echo "<h1>Invalid file</h1>";
        }
        
    }
    ?>