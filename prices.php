<?php include('db_connect.php'); ?>

<div class="container-fluid">

	<div class="col-lg-12">

		<div class="card">
			<div class="card-header">
				<b>Gestionar Precios de las Rutas</b>
			</div>
			<div class="card-body station-field">
				<div class="row justify-content-center align-center">
					<label for="" class="control-label mr-2 mt-2"><b>Buscar ruta</b></label>
					<input type="text" id="filter" class="form-control-sm col-sm-4">
				</div>
				<div class="row">
					<?php
					$station = $conn->query("SELECT * FROM stations order by station asc");
					while ($row = $station->fetch_assoc()) :
					?>
						<div class="col-md-4 py-2">
							<div class="card bg-light border item" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['station'] ?>">
								<div class="card-body">
									<div class="row justify-content-center align-center">
										<h5><b><?php echo ucwords($row['station']) ?></b></h5>
									</div>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
				<div class="row">
					<div id="msg" class="col-md-12"></div>
				</div>
			</div>
		</div>
	</div>
</div>

</div>
<style>
	td {
		vertical-align: middle !important;
	}

	td p {
		margin: unset;
	}

	.station-field .item {
		cursor: pointer;
	}

	.station-field .item:hover {
		opacity: .7;
	}
</style>
<script>
	$('#filter').keyup(function() {
		var filter = $(this).val()
		$('.station-field .item').each(function() {
			var txt = $(this).text()
			if ((txt.toLowerCase()).includes(filter.toLowerCase()) == true) {
				$(this).parent().toggle(true)
			} else {
				$(this).parent().toggle(false)
			}
		})
		if ($('.station-field .item:visible').length > 0) {
			$('.station-field #msg').html('')
		} else {
			$('.station-field #msg').html('<div class="row justify-content-center align-center"><h4 class="text-center">Sin resultados</h4></div>')
		}
	})
	$('.station-field .item').click(function() {
		uni_modal("Ruta de origen: " + $(this).attr('data-name'), "manage_prices.php?id=" + $(this).attr('data-id'), "mid-large");
	})
</script>