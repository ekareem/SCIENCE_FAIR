<?php
	require_once "includes/const/config.const.php";
    require_once $PATH_ABSOLUTE."includes/schedule.inc.php";
    require_once $PATH_ABSOLUTE."includes/util/secure/session.util.php";
	require_once $PATH_ABSOLUTE."includes/util/secure/scope.util.php";

?>
<html>
	<head>
		<title>Intensify by TEMPLATED</title>
        <?php require_once $PATH_ABSOLUTE."templates/meta.html" ?>
        <?php require_once $PATH_ABSOLUTE."templates/schedule/head.html" ?>
        <?php require_once $PATH_ABSOLUTE."includes/const/db.const.php"?>
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
            
			<?php
				if($_SESSION['type'] == "ADMIN" && !isset($_SESSION['chair']) && !hasSchedule())
				{
			?>
			<div class="container">
				<div id="modal" class="">
					<div class="modal-dialog">
						<div class="modal-content">
							<form method = "get" action = "#">
								<div class="modal-header">						
								</div>
								<div class="modal-body">				
									<button  type="submit" name = "submit" value="schedule">CREATE SCHEDULE</button>
								</div>
								<div class="modal-footer">
								</div>
							</form>
							
						</div>
					</div>
				</div>
			</div>
			<?php
				}
			?>

            <?php
				if(isset($_GET[$DB_SCHEDULE_COL_ID]) && isset($_GET[$DB_SESSION_COL_ID]) && isset($_GET[$DB_JUDGE_COL_ID]) && isset($_GET[$DB_BOOTH_COL_ID]) && ((isset($_GET["submit"]) && $_GET["submit"] != "close") || !isset($_GET["submit"]) ) )
				{
			?>
			<div class="container">
				<div id="modal" class="">
					<div class="modal-dialog">
						<div class="modal-content">
						
							<form method = "get" action = "#">
								<div class="modal-header">						
									<h4 class="modal-title">SESSION</h4>
								</div>
								<div class="modal-body">				

									<?php
										form();
									?>
								</div>
								<div class="modal-footer">
									<input type="submit" name = "submit" value="edit">
									<input type="submit" name = "submit" value="close">
								</div>
							</form>
							
						</div>
					</div>
				</div>
			</div>
			<?php
				}
			?>
		<!-- two -->
			<section id="two" class="wrapper">
			<h1 style="text-align:center">SCHEDULE</H1>
				<div class = "inner">
					<div class="table-wrapper">
						<table id="example" class="display" style="width:100%">
							<thead>
								<?php createHeader();?>
							</thead>
							<tbody>
								<?php createBody();?>
							</tbody>
							<tfoot>
                                <?php createFooter();?>
							</tfoot>
						</table> 
					</div>
				<div>
			</section>

		<!-- Footer -->
		<?php require_once $PATH_ABSOLUTE."templates/footer.html"?>

		<!-- Scripts -->
			
        <?php require_once $PATH_ABSOLUTE."templates/crud/script.html"?>

	</body>
</html>


