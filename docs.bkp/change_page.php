<?php
require_once("user_logic.php");

checkUser();

$lvl = $_SESSION["level"];

require_once("head$lvl.php");
require_once("db_lookup.php");
require_once("get_date.php");
require_once("db_kpi.php");

$readonly = $lvl == "one" || $lvl == "two" ? "readonly" : "";
$readonlydrop = $lvl == "one" || $lvl == "two" ? "readonly='readonly'" : "";
?>
	<p class="alert-warning text-center">Warning! Be sure of the alteration purpose. alterations are audited and you might be asked for alterations reason.</p>
<?php
$id = $_POST['id'];
$kpi = getInfoReg($db, $id, "kpis");
$sla = getInfoReg($db, $kpi['slas_id'], "slas");
$categoryreg = getInfoReg($db, $kpi['categories_id'], "categories");

$tools = listTools($db);
$categories = listCategories($db);
$customers = listCustomers($db);
$customer_code = isset($_POST['cust_code']) ? $_POST['cust_code'] : $kpi['customers_code'];
$types = listTypesbyCust($db, $customer_code);
$agile_group = isset($_POST['agile_group']) ? $_POST['agile_group'] : $kpi['agile_group'];
$users_email = isset($_POST['users_email']) ? $_POST['users_email'] : $kpi['users_email'];
$bucket = isset($_POST['bucket']) ? $_POST['bucket'] : $kpi['bucket'];
$creation_date = isset($_POST['creation_date']) ? $_POST['creation_date'] : $kpi['creation_date'];
$rtype = isset($_POST['type']) ? $_POST['type'] : $sla['type'];
$tools_id = isset($_POST['tools_id']) ? $_POST['tools_id'] : $kpi['tools_id'];
$rseverity = isset($_POST['severity']) ? $_POST['severity'] : $sla['severity'];
$ext_ticket = isset($_POST['ext_ticket']) ? $_POST['ext_ticket'] : $kpi['external_ticket'];
$description = isset($_POST['description']) ? $_POST['description'] : $kpi['description'];
$time_spent = isset($_POST['time_spent']) ? $_POST['time_spent'] : $kpi['time_spent'];
$num_server = isset($_POST['num_server']) ? $_POST['num_server'] : $kpi['num_server'];
$category = isset($_POST['category']) ? $_POST['category'] : $categoryreg['name'];
$selectopen = $kpi['status'] == 1 || $lvl == "one" || $lvl == "two" ? "selected='selected'" : "";

$severities = listSevbyCust($db, $customer_code, $rtype);
if($severities == NULL){
		foreach ($types[0] as $key => $rtype) {
			$severities = listSevbyCust($db, $customer_code, $rtype);
		}
}

$privilege = $lvl == "two" ? "readonly" : "";
?>

<h1>Alteration</h1> </br>

<table class="table">

<form id="junction" action="change_kpi.php" method="post">
<input type="hidden" name="id" value="<?=$kpi['id']?>">
<input id="groups" type="hidden" name="agile_group" readonly value="<?=$agile_group?>">
<tr>
	<td>Customer</td>
	<td><select name="cust_code" class="form-control" onchange="changeForm()">
		<?php
		foreach($customers as $customer) :
			$thisIstheCustomer = $customer_code == $customer['code'];
			$selectcust =  $thisIstheCustomer ? "selected='selected'" : "";
		?>

		<option value="<?=$customer['code']?>" <?=$selectcust?>>
				<?=$customer['name']?>
		</option>
		<?php
		endforeach
		?>
		</select></td>
		<td>User</td>
		<td>
		<input <?=$readonly?> required class="form-control" id="search" type="email" name="users_email" value="<?=$users_email?>">
	</td>
	<td>Bucket</td>
	<td><input required class="form-control" id="buckets" type="text" name="bucket" readonly value="<?=$bucket?>"></td>
</tr>
<tr>
	<td>Status</td>
			<td><select <?=$readonlydrop?> name="status" class="form-control">
  			<option value="0">Closed</option>
  			<option value="1" <?= $selectopen ?>>Open</option>
			</select>
			</td>
	<td>Creation Date</td>
	<td>
	<input <?=$readonly?> required autocomplete="off" class="form-control datetimepicker" type="text" name="creation_date" value="<?=$creation_date?>">
	</td>
	<td>Tool</td>
	<td><select name="tools_id" class="form-control">
			<?php
			foreach($tools as $tool) :
				$thisIstheTool = $tools_id == $tool['id'];
				$selecttool = $thisIstheTool ? "selected='selected'" : "";
			?>
			<option value="<?=$tool['id']?>" <?=$selecttool?>>
					<?=$tool['name']?>
			</option>
			<?php
			endforeach
			?>
		</select></td>
</tr>
<tr>
	<td>Type</td>
	<td><select name="type" class="form-control" onchange="changeForm()">
			<?php
			foreach($types as $type) :
			$thisIstheType = $rtype == $type['type'];
			$selecttype =  $thisIstheType ? "selected='selected'" : "";
			?>
			<option value="<?=$type['type']?>" <?=$selecttype?>>
					<?=$type['type']?>
			</option>
			<?php
			endforeach
			?>
		</select>
	</td>
	<td>External Ticket</td>
	<td><input required maxlength="14" type="text" name="ext_ticket" class="form-control" value="<?=$ext_ticket?>"></td>
	<td>Severity</td>
	<td><select name="severity" class="form-control form-control-small">
			<?php
			foreach($severities as $severity) :
			$thisIstheSev = $rseverity == $severity['severity'];
			$selectsev =  $thisIstheSev ? "selected='selected'" : "";
			?>
			<option value="<?=$severity['severity']?>" <?=$selectsev?>>
					<?=$severity['severity']?>
			</option>
			<?php
			endforeach
			?>
		</select>
	</td>
</tr>
<tr>
	<td>Category</td>
	<td>
	<input required id="category" class="form-control" type="text" name="category" value="<?=$category?>">
</td>
	<td>Description</td>
			<td>
			<textarea required maxlength="255" name="description" class="form-control"><?=$description?></textarea>
			</td>
	<td>Servers Quantity</td>
	<td><input required type="number" name="num_server" step="1" min="0" max="999" class="form-control form-control-small"" value="<?=$num_server?>"></td>
</tr>
<tr>
		<td>
			</br>
				<button class="btn btn-primary" type="submit">Change</button>
		</td>
		</table>
</tr>
</form>

<script>
function changeForm(){
	document.getElementById("junction").action = "change_page.php";
	document.getElementById("junction").submit();
}
    $(".datetimepicker").datetimepicker({
        });
</script>

<?php include("footer.php"); ?>
