<?php
    if (session_status() == PHP_SESSION_NONE) session_start();
	require_once "includes/const/config.const.php";
	require_once $PATH_ABSOLUTE."includes/util/secure/session.util.php";
	require_once $PATH_ABSOLUTE."includes/util/secure/scope.util.php";
	require_once $PATH_ABSOLUTE."includes/ranking.inc.php";
?>
<html>
	<head>
		<title>Intensify by TEMPLATED</title>
        <?php require_once $PATH_ABSOLUTE."templates/meta.html" ?>
        <?php require_once $PATH_ABSOLUTE."templates/schedule/head.html" ?>
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
		<!-- one -->
		
		<?php
			if($_SESSION['type'] == "ADMIN")
			{
		?>
        <!--section id="banner" class = "wrapper">
		<div class="container">

			<div id="modal" class="">
				<div class="modal-dialog">
					<div class="modal-content">

						<form method = "get" action = "ranking.php?">
							<div class="modal-header">						
							</div>
							<div class="modal-body">				
								<button  type="submit" name = "submit" value="ranking">CALCURATE RANKING</button>
							</div>
							<div class="modal-footer">
							</div>
						</form>
						
					</div>
				</div>
			</div>
		</div>
        </section-->
		<?php
            }
        ?>

		<?php 
			if($_SESSION['type'] == "ADMIN"  && allScoresGraded()) 
			{
                
		?>
			<!-- two -->
			<section id="two" class="wrapper">
            <h1 style="text-align:center">RANKING</H1>
			<div class = "inner">
				<div class="table-wrapper">
					<table id="" class="display" style="width:100%">
						<thead>
							<?php rankHeader();?>
						</thead>
						<tbody>
							<?php rankBody();?>
						</tbody>
						<tfoot>
						</tfoot>
					</table> 
				</div>
			<div>
		</section>
		<?php
            }
            else
            {
            ?>
                <h1 style="text-align:center"><a href = "scores.php">SOME PROJECTS HAVENT BEEN SCORED</a></H1>
        <?php

            }
		?>  

		<!-- Footer -->
		<?php require_once $PATH_ABSOLUTE."templates/footer.html"?>

		<!-- Scripts -->
			
        <?php require_once $PATH_ABSOLUTE."templates/crud/script.html"?>

	</body>
</html>
