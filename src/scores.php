<?php
    if (session_status() == PHP_SESSION_NONE) session_start();
	require_once "includes/const/config.const.php";
	require_once $PATH_ABSOLUTE."includes/util/secure/session.util.php";
	require_once $PATH_ABSOLUTE."includes/util/secure/scope.util.php";
	require_once $PATH_ABSOLUTE."includes/scores.inc.php";
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
			
		<!-- two -->
			<section id="two" class="wrapper">
			<h1 style="text-align:center">SCORES</H1>
				<div class = "inner">
					<div class="table-wrapper">
						<table id="example" class="display" style="width:100%">
							<thead>
								<?php printHeader();?>
							</thead>
							<tbody>
								<?php printBody();?>
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

	</body>
</html>
