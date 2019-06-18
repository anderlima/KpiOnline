<?php
require_once("user_logic.php");

checkUser();

$lvl = $_SESSION["level"];

if($lvl == "one" || $lvl == "two"){
      header("Location: searchanalyst.php");
}
require_once("head$lvl.php");
require_once("db_lookup.php");
require_once("get_date.php");
require_once("db_kpi.php");

$disabled = $lvl == "four" ? "disabled" : "";
?>

<table class="table table-striped table-bordered">

		<tr>
			<td align="center"><b>Work #</b></td>
			<td align="center"><b>UserID</b></td>
			<td align="center"><b>YYYY-mm-dd</b></td>
			<td align="center"><b>Type</b></td>
			<td align="center"><b>Customer</b></td>
			<td align="center"><b>Tool</b></td>
			<td align="center"><b>Ticket</b></td>
			<td align="center"><b>Sev</b></td>
			<td align="center"><b>Description</b></td>
			<td align="center"><b>Status</b></td>
			<td align="center"><b>SLA</b></td>
			<td align="center"><b>Action</b></td>
		</tr>
		<?php
			$kpiid = $_GET['flag'] ? $_SESSION['kpiid'] : $_POST['kpiid'];
            		$users_email = $_GET['flag'] ? $_SESSION['users_email'] : $_POST['users_email'];
            		$creation_date = $_GET['flag'] ? $_SESSION['creation_date'] : $_POST['creation_date'];
            		$type = $_GET['flag'] ? $_SESSION['type'] : $_POST['type'];
            		$customer = $_GET['flag'] ? $_SESSION['customer'] : $_POST['customer'];
            		$tool = $_GET['flag'] ? $_SESSION['tool'] : $_POST['tool'];
            		$ticket = $_GET['flag'] ? $_SESSION['ticket'] : $_POST['ticket'];
            		$severity = $_GET['flag'] ? $_SESSION['severity'] : $_POST['severity'];
            		$description = $_GET['flag'] ? $_SESSION['description'] : $_POST['description'];
            		$status = $_GET['flag'] ? $_SESSION['status'] : $_POST['status'];
		?>
		<tr>
		<form action="searchdisp.php" method="post">
			<td align="center"><input name="kpiid" type="text" class="form-control input-sm" value="<?=$kpiid?>"></td>
			<td align="center"><input name="users_email" type="text" class="form-control input-sm" value="<?=$users_email?>"></td>
			<td align="center"><input name="creation_date" type="text" class="form-control input-sm" value="<?=$creation_date?>"></td>
			<td align="center"><input name="type" type="text" class="form-control input-sm" value="<?=$type?>"></td>
			<td align="center"><input name="customer" type="text" class="form-control input-sm" value="<?=$customer?>"></td>
			<td align="center"><input name="tool" type="text" class="form-control input-sm" value="<?=$tool?>"></td>
			<td align="center"><input name="ticket" type="text" class="form-control input-sm" value="<?=$ticket?>"></td>
			<td align="center"><input name="severity" type="text" class="form-control input-sm" value="<?=$severity?>"></td>
			<td align="center"><input name="description" type="text" class="form-control input-sm" value="<?=$description?>"></td>
			<td align="center"><input name="status" type="text" class="form-control input-sm" value="<?=$status?>"></td>
			<td align="center"></td>
			<td><button class="btn btn-sm btn-success" style="position: absolute; left: -9999px">Search</button>
			</form>
            			<form action="clean.php" method="post">
            			<button class="btn btn-sm btn-success btn-block" name="clean">Clean</button>
          	        </form>
			</td>
		</form>
		</tr>
		<?php
            		$_SESSION['kpiid'] = $kpiid;
            		$_SESSION['users_email'] = $users_email;
            		$_SESSION['creation_date'] = $creation_date;
            		$_SESSION['type']  = $type;
            		$_SESSION['customer'] = $customer;
            		$_SESSION['tool']  = $tool;
            		$_SESSION['ticket']  = $ticket;
            		$_SESSION['severity'] = $severity;
            		$_SESSION['description'] = $description;
            		$_SESSION['status'] = $status;

			if (startsWith("closed", strtolower($status)) == NULL){
					$real_status = 1;
				}elseif(startsWith("open", strtolower($status)) == NULL){
							$real_status = 0;
					}else{
								$real_status = "";
						}

			$kpis = filterUserKPIs($db, $kpiid, $users_email, $creation_date, $type, $customer, $tool, $ticket, $severity, $description, $real_status);
			foreach($kpis as $kpi) :
			
			$created = $kpi['creation_date'];
			$to_time = strtotime("$date");
			$from_time = strtotime("$created");
			$elapsed_sla = round(abs($to_time - $from_time) / 60,2);
	
			$sla_minutes = $kpi['sla'];
			
			$time_left = round(($sla_minutes - $elapsed_sla) / 60);

			$status_v = $kpi['status'] == 0 ? "Closed" : "Opened";
			$creation_date = substr($kpi['creation_date'], 0, -3);
		?>
		<tr>
			<td align="center"><?= $kpi['id'] ?></td>
			<td align="center"><?= $kpi['users_email'] ?></td>
			<td align="center"><?= $creation_date ?></td>
			<td align="center"><?= $kpi['type'] ?></td>
			<td align="center"><?= $kpi['c_name'] ?></td>
			<td align="center"><?= $kpi['t_name']?></td>
			<td align="center"><?= htmlspecialchars($kpi['external_ticket']) ?></td>
			<td align="center"><?= $kpi['severity'] ?></td>
			<td align="center" style="max-width:100px;word-wrap: break-word;"><?= htmlspecialchars(substr($kpi['description'], 0, 50)) ?></td>
			<td align="center"><?= $status_v ?></td>
			<td align="center"><?= $time_left ?></td>
			<td align="center">
			<form action="dupkpi.php" method="post">
			<input type="hidden" name="id" value="<?=$kpi['id']?>">
			<input type="hidden" name="description" value="<?=$kpi['description']?>">
			<button <?=$disabled?> class="btn btn-sm btn-primary btn-block">Dupl</button>
			</form>
			<form action="deletekpi.php" method="post">
			<input type="hidden" name="id" value="<?=$kpi['id']?>">
			<button <?=$disabled?> onclick="return ConfirmDel()" class="btn btn-sm btn-danger btn-block">Del</button>
			</form>
			<form action="change_page.php" method="post">
			<input type="hidden" name="id" value="<?=$kpi['id']?>">
			<button <?=$disabled?> class="btn btn-sm btn-warning btn-block">Alter</button>
			</form>
			</td>
		</tr>
	<?php
	endforeach
	?>
</table>

<script type="text/javascript">
    function ConfirmDel()
    {
      var dialog = confirm("Are you sure to Delete KPI?");
      if (dialog)
          return true;
      else
          return false;
    }
</script>

<?php include("footer.php"); ?>
