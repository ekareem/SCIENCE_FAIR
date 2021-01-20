<?php
	require_once "includes/const/config.const.php";
?>
<html>
	<head>
		<title>Intensify by TEMPLATED</title>
        <?php require_once $PATH_ABSOLUTE."templates/meta.html" ?>
        <?php require_once $PATH_ABSOLUTE."templates/head.html" ?>
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

			<section id="banner" class = "wrapper">
                <div class="content">
					<h1>AN ERROR OCCURED</h1>
					<ul class="actions">
						<li><a href="index.php" class="button scrolly">HOME</a></li>
					</ul>
				</div>
			</section>


		<!-- Footer -->
		<?php require_once $PATH_ABSOLUTE."templates/footer.html"?>

		<!-- Scripts -->
			
        <?php require_once $PATH_ABSOLUTE."templates/crud/script.html"?>

	</body>
</html>


