<?php
require_once("user_logic.php");

checkUser();

$lvl = $_SESSION["level"];

if ($lvl == "three" || $lvl =="four"){
	header('Location:searchdisp.php');
}

require_once("head$lvl.php");
require_once("db_lookup.php");
require_once("get_date.php");
require_once("db_kpi.php");
?>

<table class="table table-striped table-bordered">

		<tr>
			<td align="center"><b>Work #</b></td>
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
<?php
			$kpiid = $_GET['flag'] ? $_SESSION['kpiid'] : $_POST['kpiid'];
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
		<form action="searchanalyst.php" method="post">
			<td align="center"><input name="kpiid" type="text" class="form-control input-sm" value="<?=$kpiid?>"></td>
			<td align="center"><input name="creation_date" type="text" class="form-control input-sm" value="<?=$creation_date?>"></td>
			<td align="center"><input name="type" type="text" class="form-control input-sm" value="<?=$type?>"></td>
			<td align="center"><input name="customer" type="text" class="form-control input-sm" value="<?=$customer?>"></td>
			<td align="center"><input name="tool" type="text" class="form-control input-sm" value="<?=$tool?>"></td>
			<td align="center"><input name="ticket" type="text" class="form-control input-sm" value="<?=$ticket?>"></td>
			<td align="center"><input name="severity" type="text" class="form-control input-sm" value="<?=$severity?>"></td>
			<td align="center"><input name="description" type="text" class="form-control input-sm" value="<?=$description?>"></td>
			<td align="center"><input name="status" type="text" class="form-control input-sm" value="<?=$status?>"></td>
			<td><button class="btn btn-sm btn-success" style="position: absolute; left: -9999px">Search</button>
		</form>
			<form action="clean.php" method="post">
            			<button class="btn btn-sm btn-success btn-block" name="clean">Clean</button>
          	        </form>
          	</td>
		</tr>
		<?php
			$_SESSION['kpiid'] = $kpiid;
            		$_SESSION['creation_date'] = $creation_date;
            		$_SESSION['type']  = $type;
            		$_SESSION['customer'] = $customer;
            		$_SESSION['tool']  = $tool;
            		$_SESSION['ticket']  = $ticket;
            		$_SESSION['severity'] = $severity;
            		$_SESSION['description'] = $description;
            		$_SESSION['status'] = $status;
			$users_email = Whois();
			
			if (startsWith("closed", strtolower($status)) == NULL){
					$real_status = 1;
				}elseif(startsWith("opened", strtolower($status)) == NULL){
							$real_status = 0;
					}else{
								$real_status = "";
						}


			$kpis = filterUserKPIs($db, $kpiid, $users_email, $cdate, $type, $customer, $tool, $ticket, $severity, $description, $real_status);
			foreach($kpis as $kpi) :
				$status_v = $kpi['status'] == 0 ? "Closed" : "Opened";
		?>
		<tr>
			<td align="center"><?= $kpi['id'] ?></td>
			<td align="center"><?= date('Y/m/d H:i:s', strtotime($kpi['creation_date'])) ?></td>
			<td align="center"><?= $kpi['type'] ?></td>
			<td align="center"><?= $kpi['c_name'] ?></td>
			<td align="center"><?= $kpi['t_name']?></td>
			<td align="center"><?= htmlspecialchars($kpi['external_ticket']) ?></td>
			<td align="center"><?= $kpi['severity'] ?></td>
			<td align="center" style="max-width:100px;word-wrap: break-word;"><?= htmlspecialchars(substr($kpi['description'], 0, 50)) ?></td>
			<td align="center"><?= $status_v ?></td>
			<td align="center">
			<form action="dupkpi.php" method="post">
			<input type="hidden" name="id" value="<?=$kpi['id']?>">
			<input type="hidden" name="description" value="<?=$kpi['description']?>">
			<button <?=$disabled?> class="btn btn-sm btn-primary btn-block">Dupl</button>
			</form>
<?php
     		       if( $kpi['creation_date'] >= date('Y-m-d H:i:s', strtotime("last Saturday"))){
    ?>
			<form action="change_page.php" method="post">
			<input type="hidden" name="id" value="<?=$kpi['id']?>">
			<button <?=$disabled?> class="btn btn-sm btn-warning btn-block">Alter</button>
			</form>
<?php } ?>
			</td>
		</tr>
		<?php
			endforeach
		?>
</table>

<script type="text/javascript">
    $(".datetimepicker").datetimepicker({
        });
</script>

<?php include("footer.php"); ?>
