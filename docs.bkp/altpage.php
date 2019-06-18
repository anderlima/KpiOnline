<?php
require_once("head.php");
echo date('D');
?>

<form action="alter.php" method="post">
<table class="table chpwd">

<tr>
<td><label>User</label></td>
<td><input type="email" name="email" class="form-control" placeholder="User to have profile changed">
</tr>
<tr>
<td><label>Group</label></td>
<td> 
<select name="group" class="form-control">
  <option value="DISPATCHER">DISPATCHER</option>
  <option value="HC">HC</option>
  <option value="MGT">MGT</option>
  <option value="I">I</option>
  <option value="II">II</option>
  <option value="III">III</option>
<option value="IV">IV</option>
<option value="Netcool">Netcool</option>
  <option value="TBSM">TBSM</option>
</select>
</td>

</tr>
<tr>
<td><label>Bucket</label></td>
<td>
<select name="bucket" class="form-control">
  <option value="L1">L1</option>
  <option value="L2">L2</option>
  <option value="L3">L3</option>
  <option value="L4">L4</option>
  <option value="JA">JA</option>
  <option value="ETG">ETG</option>
  <option value="24x7">24x7</option>
  <option value="DISPATCHER">DISPATCHER</option>
  <option value="MGT">MGT</option>
  </select>
 </td>
</tr>
<tr>
<td><button class="btn btn-group btn-primary">Submit</button></td>
</tr>
</form>
</table>

<?php
require_once("footer.php");
?>
