<?php

require_once("user_logic.php");

checkUser();

$lvl = $_SESSION["level"];
require_once("head$lvl.php");

?>

<form action="chpwd.php" method="post">
<table class="table chpwd">

<tr>
<td><label>Current</label></td>
<td><input type="password" name="curpass" class="form-control" placeholder="Current password">
</tr>
<tr>
<td><label>New Password</label></td>
<td><input type="password" name="newpass" class="form-control" placeholder="New password"></td>
</tr>
<tr>
<td><label>Repeat</label></td>
<td><input type="password" name="reppass" class="form-control" placeholder="Repeat password"></td>
</tr>
<tr>
<td><button class="btn btn-group btn-primary">Submit</button></td>
</tr>
</form>
</table>

<?php
require_once("footer.php");
?>