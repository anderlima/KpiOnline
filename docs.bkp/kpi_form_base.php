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
		<input required class="form-control" id="search" type="email" name="users_email">
	</td>
	<td>Bucket</td>
	<td><input required class="form-control" id="buckets" type="text" name="bucket" readonly></td>
</tr>
<tr>
	<td>Agile Group</td>
	<td><input required class="form-control" id="groups" type="text" name="agile_group" readonly></td>
	<td>Creation Date</td>
	<td>
	<input required class="form-control datetimepicker" type="date" name="creation_date" value="<?= $date ?>">
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
	<td><input required type="text" name="ext_ticket" class="form-control" value="<?=$ext_ticket?>"></td>
	<td>Severity</td>
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
	<td>Category</td>
	<td>
	<input required id="category" class="form-control" type="text" name="categories" >
</td>
	<td>Description</td>
	<td>
		<textarea required maxlength="255" name="description" class="form-control" value="<?=$description?>"></textarea>
	</td>
	<td>Servers Quantity</td>
	<td><input required type="number" name="num_server" step="1" min="0" max="999" class="form-control" value="<?=$num_server?>"></td>
</tr>
