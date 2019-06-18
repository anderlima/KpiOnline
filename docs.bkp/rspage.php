<?php

require_once("user_logic.php");

checkUser();

$lvl = $_SESSION["level"];
require_once("head$lvl.php");

?>

<form action="rspwd.php" method="post">
<table class="table chpwd">

<tr>
<td><label>User</label></td>
<td><input type="text" name="email" class="form-control" placeholder="User to have password reset">
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