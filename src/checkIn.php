<?php
	require_once "includes/const/config.const.php";
	require_once $PATH_ABSOLUTE."includes/util/secure/session.util.php";
	require_once $PATH_ABSOLUTE."includes/util/secure/scope.util.php";
	require_once $PATH_ABSOLUTE."includes/checkIn.inc.php";
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

			<section id="one" class="wrapper">
				<h1 style="text-align:center">JUDGES CHECK IN STATUS</H1>
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


