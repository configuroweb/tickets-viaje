<style>
	.collapse a {
		text-indent: 10px;
	}

	nav#sidebar {
		/*background: url(assets/uploads/<?php echo $_SESSION['system']['cover_img'] ?>) !important*/
	}
</style>

<nav id="sidebar" class='mx-lt-5 bg-dark'>

	<div class="sidebar-list">
		<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-tachometer-alt "></i></span> Dashboard</a>
		<a href="ticketing/index.php" class="nav-item nav-ticketing"><span class='icon-field'><i class="fa fa-clipboard-list "></i></span> Ticketing</a>
		<?php if ($_SESSION['login_type'] == 1) : ?>

			<div class="mx-2 text-white">Gestión de Tickets</div>
			<a href="index.php?page=stations" class="nav-item nav-stations"><span class='icon-field'><i class="fa fa-list-alt "></i></span> Rutas</a>
			<a href="index.php?page=prices" class="nav-item nav-prices"><span class='icon-field'><i class="fa fa-ticket-alt "></i></span> Precios</a>
		<?php endif; ?>
		<div class="mx-2 text-white">Reporte Diario</div>
		<a href="index.php?page=daily_report" class="nav-item nav-daily_report"><span class='icon-field'><i class="fa fa-th-list"></i></span> Reporte Diario</a>
		<?php if ($_SESSION['login_type'] == 1) : ?>
			<div class="mx-2 text-white">Sistema</div>
			<a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users "></i></span> Usuarios</a>
			<!-- <a href="index.php?page=site_settings" class="nav-item nav-site_settings"><span class='icon-field'><i class="fa fa-cogs"></i></span> System Settings</a> -->
		<?php endif; ?>
	</div>

</nav>
<script>
	$('.nav_collapse').click(function() {
		console.log($(this).attr('href'))
		$($(this).attr('href')).collapse()
	})
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>