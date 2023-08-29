<?php
include 'db_connect.php';
?>
<div class="container-fluid">
	<div class="col-lg-12">
		<form action="" id="manage-price">
			<input type="hidden" name="origin_station" value="<?php echo $_GET['id'] ?>">
			<table class="table table-condensed">
				<thead>
					<tr>
						<th rowspan="2">Destino</th>
						<th colspan="3">Precios</th>
					</tr>
					<tr>
						<th>Adultos</th>
						<th>Estudiantes</th>
						<th>Adultos Mayores</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$qry = $conn->query("SELECT * FROM stations where id !=" . $_GET['id']);
					while ($row = $qry->fetch_assoc()) :
						$get = $conn->query("SELECT * FROM prices where station_from='{$_GET['id']}' and station_to = '{$row['id']}' ");
						$arr = $get->num_rows > 0 ? $get->fetch_array() : '';
					?>
						<tr>
							<td>
								<p><b><?php echo ucwords($row['station']) ?></b></p>
							</td>
							<td><input type="text" class='form-controm-xs number text-right' name="adult[<?php echo $row['id'] ?>]" value="<?php echo isset($arr['adult_price']) ? $arr['adult_price'] : '' ?>"></td>
							<td><input type="text" class='form-controm-xs number text-right' name="student[<?php echo $row['id'] ?>]" value="<?php echo isset($arr['student_price']) ? $arr['student_price'] : '' ?>"></td>
							<td><input type="text" class='form-controm-xs number text-right' name="senior[<?php echo $row['id'] ?>]" value="<?php echo isset($arr['senior_price']) ? $arr['senior_price'] : '' ?>"></td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</form>
	</div>
</div>
<style>
	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}
</style>
<script>
	$('.number').on('input', function() {
		var val = $(this).val()
		val = val.replace(/[^0-9 \,]/, '');
		$(this).val(val)
	})
	$('.number').keyup(function(e) {
		if (e.which == 13) {
			return false;
		}
		var num = $(this).val()
		num = num.replace(/,/g, '')
		num = num > 0 ? parseFloat(num).toLocaleString('en-US') : '';
		$(this).val(num)
	})
	$('#manage-price').submit(function(e) {
		e.preventDefault()
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_price',
			method: 'POST',
			data: $(this).serialize(),
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Datos guardados correctamente", "success");
					end_load()
				}
			}
		})
	})
</script>