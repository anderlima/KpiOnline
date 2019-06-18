<?php
require_once("user_logic.php");

checkUser();

$lvl = $_SESSION["level"];

require_once("head$lvl.php");
require_once("db_lookup.php");
require_once("get_date.php");
require_once("db_kpi.php");

$disabled = $lvl == "four" ? "disabled" : "";
?>



<table class="table table-striped table-bordered">

		<tr>
			<td align="center"><b>KPI #</b></td>
			<td align="center"><b>UserID</b></td>
			<td align="center"><b>YYYY-mm-dd</b></td>
			<td align="center"><b>Type</b></td>
			<td align="center"><b>Customer</b></td>
			<td align="center"><b>Tool</b></td>
			<td align="center"><b>Ticket</b></td>
			<td align="center"><b>Sev</b></td>
			<td align="center"><b>Description</b></td>
			<td align="center"><b>Status</b></td>
			<td align="center"><b>Action</b></td>
		</tr>
		<tr>
		<form action="searchdisp.php" method="post">
			<td align="center"><input name="kpiid" type="text" class="form-control input-sm"></td>
			<td align="center"><input name="users_email" type="text" class="form-control input-sm"></td>
			<td align="center"><input name="creation_date" type="text" class="form-control input-sm"></td>
			<td align="center"><input name="type" type="text" class="form-control input-sm"></td>
			<td align="center"><input name="customer" type="text" class="form-control input-sm"></td>
			<td align="center"><input name="tool" type="text" class="form-control input-sm"></td>
			<td align="center"><input name="ticket" type="text" class="form-control input-sm"></td>
			<td align="center"><input name="severity" type="text" class="form-control input-sm"></td>
			<td align="center"><input name="description" type="text" class="form-control input-sm"></td>
			<td align="center"><input name="status" type="text" class="form-control input-sm"></td>
			<button class="btn btn-sm btn-success" style="position: absolute; left: -9999px">Search</button>
		</form>
		</tr>
		<?php
			$kpiid = $_POST['kpiid'] ? $_POST['kpiid'] : "";
			$users_email = $_POST['users_email'] ? $_POST['users_email'] : "";
			$creation_date = $_POST['creation_date'] ? $_POST['creation_date'] : "";
			$type = $_POST['type'] ? $_POST['type'] : "";
			$customer = $_POST['customer'] ? $_POST['customer'] : "";
			$tool = $_POST['tool'] ? $_POST['tool'] : "";
			$ticket = $_POST['ticket'] ? $_POST['ticket'] : "";
			$severity = $_POST['severity'] ? $_POST['severity'] : "";
			$description = $_POST['description'] ? $_POST['description'] : "";
			$status = $_POST['status'] ? $_POST['status'] : "";

			if (startsWith("closed", strtolower($status)) == NULL){
					$real_status = 1;
				}elseif(startsWith("opened", strtolower($status)) == NULL){
							$real_status = 0;
					}else{
								$real_status = "";
						}

			$kpis = filterUserKPIs($connection, $kpiid, $users_email, $creation_date, $type, $customer, $tool, $ticket, $severity, $description, $real_status);
			foreach($kpis as $kpi) :

			$status_v = $kpi['STATUS'] == 0 ? "Closed" : "Opened";
		?>
		<tr>
			<td align="center"><?= $kpi['ID'] ?></td>
			<td align="center"><?= $kpi['USERS_EMAIL'] ?></td>
			<td align="center"><?= $kpi['CREATION_DATE'] ?></td>
			<td align="center"><?= $kpi['TYPE'] ?></td>
			<td align="center"><?= $kpi['C_NAME'] ?></td>
			<td align="center"><?= $kpi['T_NAME']?></td>
			<td align="center"><?= $kpi['EXTERNAL_TICKET'] ?></td>
			<td align="center"><?= $kpi['SEVERITY'] ?></td>
			<td align="center"><?= $kpi['DESCRIPTION'] ?></td>
			<td align="center"><?= $status_v ?></td>
			<form action="deletekpi.php" method="post">
			<input type="hidden" name="id" value="<?=$kpi['ID']?>">
			<td align="center"><button <?=$disabled?> class="btn btn-sm btn-danger">Delete</button></td>
			</form>

		</tr>
	<?php
	endforeach
	?>	
</table>

<?php include("footer.php"); ?>
