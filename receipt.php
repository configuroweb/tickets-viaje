<?php
include 'db_connect.php';
$ids = json_decode(urldecode($_GET['id']));
$ids = implode(',', $ids);
$station = $conn->query("SELECT * FROM stations");
$sname_arr = array();
while ($row = $station->fetch_array()) {
	$sname_arr[$row['id']] = ucwords($row['station']);
}
$ptype = array('', 'Adult', 'Student', 'Senior');
$tickets = $conn->query("SELECT * FROM tickets where id in ($ids) ");
?>

<style>
	.flex {
		display: inline-flex;
		width: 100%;
	}

	.w-50 {
		width: 50%;
	}

	.text-center {
		text-align: center;
	}

	.text-right {
		text-align: right;
	}

	table {
		width: 100%;
		border-collapse: collapse;
	}

	.border-bottom {
		border-bottom: 1px solid;
	}

	p {
		margin: unset;
	}

	.text-primary {
		color: #0062cc
	}
</style>
<div class="container-fluid">
	<?php
	while ($row = $tickets->fetch_assoc()) :
	?>
		<p class="text-center"><b>Tiquete de Viaje</b></p>
		<table width="100%">
			<tr>
				<td width="50%">Ruta de salida:</td>
				<td width="50%">Ruta de llegada:</td>
			</tr>
			<tr>
				<td class="border-bottom"><b><?php echo ucwords($sname_arr[$row['station_from']]) ?></b></td>
				<td class="border-bottom"><b><?php echo ucwords($sname_arr[$row['station_to']]) ?></b></td>
			</tr>
			<tr>
				<td>Pasajero:</td>
				<td>Estado:</td>
			</tr>
			<tr>
				<td class="border-bottom"><b><?php echo ucwords($ptype[$row['passenger_type']]) ?></b></td>
				<td class="border-bottom"><b>Pagado</b></td>
			</tr>
			<tr>
				<td colspan="2"><b>No de tiquete: <span class="text-primary"><?php echo $row['ticket_no'] ?></span></b></td>
			</tr>
		</table>
		<hr>
	<?php endwhile; ?>
</div>