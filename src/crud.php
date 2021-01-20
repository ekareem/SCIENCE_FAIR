<?php
	require_once "includes/const/config.const.php";
	require_once $PATH_ABSOLUTE."includes/util/secure/session.util.php";
	require_once $PATH_ABSOLUTE."includes/util/secure/scope.util.php";
	require_once $PATH_ABSOLUTE."includes/crud.inc.php";
?>
<html>
	<head>
		<title>Intensify by TEMPLATED</title>
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

		
			<h1 align="center"><?php echo $table?></h1>

			<div class = "inner">
			<form method="get" action="#">
				<div class="row uniform">
					
					<!--div class="9u 12u$(small)">
					<input type ="text" name = "table" id="table" value ="" placeholder="table name"/>
					</div-->
					

					<div class="9u 12u$(small)">
						<select id ="table" name = "table">
							<?php option(); ?>
						</select>
					</div>
					<div class="3u$ 12u$(small)">
						<input  type ="submit" name = "getTable" id="getTable" value ="find" class = "fit">
					</div>

				</div>
			</form>
			</div>


		<!-- one -->
			<?php
				if(isset($_GET['quary']) && ($_GET['quary']=='add' || $_GET['quary']=='edit'))
				{
			?>
			<div class="container">
				<div id="modal" class="">
					<div class="modal-dialog">
						<div class="modal-content">
						
							<form method = "get" action = "#">
								<div class="modal-header">						
									<h4 class="modal-title"><?php echo $table?></h4>
								</div>
								<div class="modal-body">				

									<?php
										form($table,$rows,$columns,$relations,$id,$foreignMap,$typeinputMap,$schemas);
									?>
								</div>
								<div class="modal-footer">
									<input type="submit" name = "submit" value="<?php echo(isset($_GET["quary"]))?$_GET["quary"]:"";?>">
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
				<div class = "inner">
					<div class="table-wrapper">
						<table id="example" class="display" style="width:100%">
							<thead>
								<?php printHeader($columns,$relations,$foreignMap);?>
							</thead>
							<tbody>
								<?php printBody($rows,$columns,$id,$relations,$foreignMap);?>
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
		$( "#table" ) .change(function () {

			window.location.assign("?table="+$(this).val()+"&getTable=find#");
		});
		//$('#AdminLevelID').css('pointer-events','none');
		</script>		
	</body>
</html>


