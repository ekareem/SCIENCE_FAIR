<?php
	require_once "includes/const/config.const.php";
	require_once $PATH_ABSOLUTE."includes/util/secure/session.util.php";
    require_once $PATH_ABSOLUTE."includes/util/secure/scope.util.php";
    require_once $PATH_ABSOLUTE."includes/upload.inc.php";
    
?>

<html>
	<head>
		<title>Upload</title>
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

            <h1>Upload</h1>

            <p>Upload a csv file with appropriate formatting</p>

            <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" id="fileInput"><br>
            <input type="submit" name="submit" value="Upload">
            </form>

            <a href="csv/template/template.csv" download>
                <button type="button">Download CSV Template</button>
            </a>

            

        <!-- Scripts -->
			
        <?php require_once $PATH_ABSOLUTE."templates/crud/script.html"?>

    </body>
    </html>