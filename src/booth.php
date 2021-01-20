<?php
	require_once "includes/const/config.const.php";
	require_once $PATH_ABSOLUTE."includes/util/secure/session.util.php";
	require_once $PATH_ABSOLUTE."includes/util/secure/scope.util.php";
	require_once $PATH_ABSOLUTE."includes/booth.inc.php";
?>
<html>
	<head>
		<title>Intensify by TEMPLATED</title>
        <?php require_once $PATH_ABSOLUTE."templates/meta.html" ?>
        <?php require_once $PATH_ABSOLUTE."templates/table/head.html" ?>
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

			<!--section id="banner" class = "wrapper">
			</section-->


		<!-- one -->
		<section id="one" class="wrapper">
			<h1 style="text-align:center">BOOTH <?php echo strtoupper($row['Number']);?></h1>
			<div class="container">
			<div id="modal" class="">
					<div class="modal-dialog">
						<div class="modal-content">
				<div id="modal" class="">
					<div class="modal-dialog">

						<div class="modal-content">
						<form method="get" action="#">
							<div class="row uniform">
								<div class="9u 12u$(small)">
									<select name = "BoothID" id = "option">
										<?php option(); ?>
									</select>
								</div>
								<div class="3u$ 12u$(small)">
									<input type="submit" value="Search" class="fit" />
								</div>
							</div>
						</form>
						<table id="" class="display" style="width:100%">
							<thead>
								<?php head();?>
							</thead>
							<tbody>
								<?php body();?>
							</tbody>
							<tfoot>
							</tfoot>
						</table> 
						</div>
					</div>
				</div>
			</div>
			</div>
			</div>
			</div>

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

			<h1 style="text-align:center">STUDENTS IN BOOTH <?php echo strtoupper($row['Number']);?></H1>
				<div class = "inner">
					<div class="table-wrapper">
						<table id="example" class="display" style="width:100%">
							<thead>
								<?php studentHead();?>
							</thead>
							<tbody>
								<?php studentBody();?>
							</tbody>
							<tfoot>
							</tfoot>
						</table> 
					</div>
				<div>
			</section>


		<!-- Footer -->
		<?php require_once $PATH_ABSOLUTE."templates/footer.html"?>

		<!-- Scripts -->
			
        <?php require_once $PATH_ABSOLUTE."templates/crud/script.html"?>
		<script>
		$( "#option" ) .change(function () {

			window.location.assign("?BoothID="+$(this).val());
		});
		</script>
	</body>
</html>


