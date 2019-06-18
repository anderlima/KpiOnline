<h2>Opened</h2>
<table class="table table-bordered">
	<tr>
		<td align="center"><b>Work #</b></td>		
		<td align="center"><b>Target Date</b></td>
		<td align="center"><b>Type</b></td>
		<td align="center"><b>Customer</b></td>
		<td align="center"><b>Tool</b></td>
		<td align="center"><b>Ticket</b></td>
		<td align="center"><b>Sev</b></td>
		<td align="center"><b>Description</b></td>
		<td align="center"><b>Minutes</b></td>
		<td align="center"><b>Server #</b></td>
		<td align="center"><b>Action</b></td>
	</tr>
	<?php
	require_once("db_kpi.php");
	$kpis = listUserKpi($db);

	foreach($kpis as $kpi) :
#		$customer = getCustomerName($connection, $kpi['customers_code']);
#		$tool = getToolName($connection, $kpi['tools_id']);
#		$severity = getSev($connection, $kpi['slas_id']);
#		$type = getSLA($connection, $kpi['slas_id']);

	$created = $kpi['creation_date'];

	if ($date < new DateTime($created)){

		$to_time = strtotime("$date");
		$from_time = strtotime("$created");
		$elapsed_sla = round(abs($to_time - $from_time) / 60,2);

		$sla_minutes = $kpi['sla'];
		
		$time_left = $sla_minutes - $elapsed_sla;

		if($time_left < 1){
			$color = "#f45042";
		}else{
			if($time_left > 0 && $time_left < 1441) {
				$color = "#f49b42";
			}else{
				if($time_left > 86400 && $time_left < 2881){
					$color = "#f4f142";
				}else{
					if($time_left > 4321){
						$color = "#ffffff";
					}
				}
			}
		}
	}
		$target_date = getTargetDate($db, $kpi['creation_date'], $kpi['sla']);
?>
		<tr bgcolor="<?= $color ?>">
			<td align="center"><?= $kpi['id'] ?></td>
			<td align="center"><?= date('Y/m/d H:i:s', strtotime($target_date)) ?></td> 
			<td align="center"><?= $kpi['type'] ?></td>
			<td align="center"><?= $kpi['c_name'] ?></td>
			<td align="center"><?= $kpi['t_name'] ?></td>
			<td align="center"><?= $kpi['external_ticket'] ?></td>
			<td align="center"><?= $kpi['severity'] ?></td>
			<form action="update_kpi.php" method="post">
				<td><textarea required class="form-control" name="description" maxlength="255"><?= $kpi['description'] ?></textarea></td>
				<td><input required class="form-control input-sm form-control-small" type="number" name="time_spent" step="1" min="1" max="15000"></td>
				<td><input required class="form-control input-sm form-control-small" type="number" name="servers" step="1" min="0" value="<?= $kpi['num_server'] ?>"></td>
					<input type="hidden" name="id" value="<?=$kpi['id']?>">
					<td align="center"><button class="btn btn-sm btn-success">Close</button></td>
				</form>
		</tr>
	<?php
	endforeach
	?>
</table>
