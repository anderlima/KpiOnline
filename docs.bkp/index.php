<?php
require_once("user_logic.php");

checkUser();

$lvl = $_SESSION["level"];

if ($lvl == "three"){
	header('Location:kpi_form.php');
}elseif($lvl == "four"){
	header('Location:searchdisp.php');
}

require_once("head$lvl.php");
require_once("db_lookup.php");
require_once("db_kpi.php");
require_once("get_date.php");

$tools = listTools($db);
$categories = listCategories($db);
$customers = listCustomers($db);
$customer_code = isset($_POST['cust_code']) ? $_POST['cust_code'] : "AC2";

$rtype = isset($_POST['type']) ? $_POST['type'] : "REQUEST";
$tools_id = isset($_POST['tools_id']) ? $_POST['tools_id'] : 7;
$rseverity = isset($_POST['severity']) ? $_POST['severity'] : "";
$ext_ticket = isset($_POST['ext_ticket']) ? $_POST['ext_ticket'] : "NA";
$description = isset($_POST['description']) ? $_POST['description'] : "";
$time_spent = isset($_POST['time_spent']) ? $_POST['time_spent'] : 1;
$num_server = isset($_POST['num_server']) ? $_POST['num_server'] : 0;
$categories_id = isset($_POST['categories_id']) ? $_POST['categories_id'] : "";

$types = listTypesbyCust($db, $customer_code);
$severities = listSevbyCust($db, $customer_code, $rtype);

if($severities == NULL){
		foreach ($types[0] as $key => $rtype) {
			$severities = listSevbyCust($db, $customer_code, $rtype);
		}
}

$amount_min = getTimeSpent($db, Whois());
$amount_hours = $amount_min/60;

?>


<h2>Launch</h2>

<table class="table table-striped">

<form id="junction" action="add_kpi_closed.php" method="post">

	<tr>
		<td>Customer</td>
		<td>Type</td>
		<td>Tool</td>
		<td>External Ticket</td>
		<td>Severity</td>
	</tr>

<tr>
	<td><select name="cust_code" class="form-control input-sm form-control-large" onchange="changeForm()">
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
		<td><select name="type" class="form-control input-sm form-control-medium" onchange="changeForm()">
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
		<td><select name="tools_id" class="form-control input-sm form-control-medium">
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
		<td>
			<input required maxlength="14" type="text" name="ext_ticket" class="form-control input-sm form-control-medium" value="<?=$ext_ticket?>">
		</td>
		<td><select name="severity" class="form-control input-sm form-control-small">
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
		<td>Minutes</td>
		<td># Servers</td>
		<td>Category</td>
		<td>Action</td>
		</tr>
	<tr>
		<td ><input required maxlength="255" type="text" name="description" class="form-control input-sm form-control-large" value="<?=$description?>"></td>
		<td><input required type="number" name="time_spent" step="1" min="1" max="15000" class="form-control input-sm col-xs-1 form-control-small" value="<?=$time_spent?>"></td>
		<td><input required type="number" name="num_server" step="1" min="0" max="999" class="form-control input-sm col-xs-1 form-control-small" value="<?=$num_server?>"></td>
		<td><select name="categories_id" class="form-control input-sm form-control-medium">
			<?php
			foreach($categories as $category) :
				$thisIstheCategory = $categories_id == $category['id'];
				$selectcat = $thisIstheCategory ? "selected='selected'" : "";
			?>
			<option value="<?=$category['id']?>" <?=$selectcat?>>
					<?=$category['name']?>
			</option>
			<?php
			endforeach
			?>
		</select></td>
		<td><button class="btn btn-primary btn-sm" type="submit">Launch</button></td>
</form>
</tr>
</table>


<script>
function changeForm(){
	document.getElementById("junction").action = "index.php";
	document.getElementById("junction").submit();
}
</script>

<?php
include("list_analyst.php");
require_once("footer.php");
?>

