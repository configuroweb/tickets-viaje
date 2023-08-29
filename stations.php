<?php include('db_connect.php'); ?>

<div class="container-fluid">

	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
				<form action="" id="manage-station">
					<div class="card">
						<div class="card-header">
							Formulario de la Ruta
						</div>
						<div class="card-body">
							<input type="hidden" name="id">
							<div id="msg" class="form-group"></div>
							<div class="form-group">
								<label class="control-label">Ruta</label>
								<input type="text" class="form-control" name="station">
							</div>
							<div class="form-group">
								<label class="control-label">Dirección</label>
								<textarea name="address" id="address" cols="30" rows="4" class="form-control"></textarea>
							</div>
						</div>

						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Guardar</button>
									<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="$('#manage-station').get(0).reset()"> Cancelar</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<b>Rutas de Viaje</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Información de Ruta</th>
									<th class="text-center">Acción</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$station = $conn->query("SELECT * FROM stations order by station asc");
								while ($row = $station->fetch_assoc()) :
								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class="">
											<p>Nombre: <b><?php echo $row['station'] ?></b></p>
											<p><small>Dirección: <b><?php echo $row['address'] ?></b></small></p>
										</td>
										<td class="text-center">
											<button class="btn btn-sm btn-primary edit_station" type="button" data-id="<?php echo $row['id'] ?>" data-address="<?php echo $row['address'] ?>" data-station="<?php echo $row['station'] ?>">Editar</button>
											<button class="btn btn-sm btn-danger delete_station" type="button" data-id="<?php echo $row['id'] ?>">Eliminar</button>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
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
</style>
<script>
	$('#manage-station').on('reset', function() {
		$('input:hidden').val('')
	})

	$('#manage-station').submit(function(e) {
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url: 'ajax.php?action=save_station',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Datos guardados correctamente", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				} else if (resp == 2) {
					$('#msg').html('<div class="alert alert-danger">Ruta existe actualmente.</div>')
					end_load()
				}
			}
		})
	})
	$('.edit_station').click(function() {
		start_load()
		var cat = $('#manage-station')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='station']").val($(this).attr('data-station'))
		cat.find("[name='address']").val($(this).attr('data-address'))
		end_load()
	})
	$('.delete_station').click(function() {
		_conf("Deseas eliminar esta ruta?", "delete_station", [$(this).attr('data-id')])
	})

	function delete_station($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_station',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Datos eliminados, correctamente.", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
	$('table').dataTable()
</script>