<?php
require_once("user_logic.php");

checkUser();

$lvl = $_SESSION["level"];

if ($lvl == "one"){
	$_SESSION["danger"] = "You don't have access to this functionality";
	header('Location:index.php');
}

require_once("head$lvl.php");
require_once("db_lookup.php");
require_once("get_date.php");
require_once("db_kpi.php");
#$severity = listSLAs($connection);
$tools = listTools($db);
$categories = listCategories($db);
$customers = listCustomers($db);
$customer_code = isset($_POST['cust_code']) ? $_POST['cust_code'] : "AC2";
$agile_group = isset($_POST['agile_group']) ? $_POST['agile_group'] : "";
$users_email = isset($_POST['users_email']) ? $_POST['users_email'] : "";
$bucket = isset($_POST['bucket']) ? $_POST['bucket'] : "";
$creation_date = isset($_POST['creation_date']) ? $_POST['creation_date'] : "";
$rtype = isset($_POST['type']) ? $_POST['type'] : "";
$tools_id = isset($_POST['tools_id']) ? $_POST['tools_id'] : "";
$rseverity = isset($_POST['severity']) ? $_POST['severity'] : "";
$ext_ticket = isset($_POST['ext_ticket']) ? $_POST['ext_ticket'] : "";
$description = isset($_POST['description']) ? $_POST['description'] : "";
$time_spent = isset($_POST['time_spent']) ? $_POST['time_spent'] : "";
$num_server = isset($_POST['num_server']) ? $_POST['num_server'] : "";
$category = isset($_POST['category']) ? $_POST['category'] : "";

$types = listTypesbyCust($db, $customer_code);
$severities = listSevbyCust($db, $customer_code, $rtype);
if($severities == NULL){
		foreach ($types[0] as $key => $rtype) {
			$severities = listSevbyCust($db, $customer_code, $rtype);
		}
}

$privilege = $lvl == "two" ? "readonly" : "";
?>

<h1>Create</h1> </br>

<table class="table">
<form id="junction" action="add_kpi.php" method="post">
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
		<input required class="form-control" id="search" type="email" name="users_email" value="<?=$users_email?>">
	</td>
	<td>Bucket</td>
	<td><input required class="form-control" id="buckets" type="text" name="bucket" readonly value="<?=$bucket?>"></td>
</tr>
<tr>
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
	<td>Creation Date</td>
	<td>
	<input required autocomplete="off" class="form-control datetimepicker" type="text" name="creation_date" value="<?=$creation_date?>">
	</td>
	<td>Group</td>
	<td><input required class="form-control" id="groups" type="text" name="agile_group" readonly value="<?=$agile_group?>"></td>	
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
	<td>Description</td>
	<td>
		<textarea required maxlength="255" name="description" class="form-control"><?=$description?></textarea>
	</td>
	<td>Category</td>
	<td>
	<input required id="category" class="form-control" type="text" name="category" value="<?=$category?>">
	</td>
	<td>Servers Quantity</td>
	<td><input required type="number" name="num_server" step="1" min="0" max="999" class="form-control form-control-small"" value="<?=$num_server?>"></td>
</tr>
		<tr>
			<td></td><td></td><td></td><td></td><td></td><td align="right">
			<button class="btn btn-primary" type="submit">Create</button>
			</td>
		</tr>
</table>
</form>

<script>
function changeForm(){
	document.getElementById("junction").action = "kpi_form.php";
	document.getElementById("junction").submit();
}
    $(".datetimepicker").datetimepicker({
        });
</script>

<?php
include("backlog.php");
include("footer.php");
 ?>
