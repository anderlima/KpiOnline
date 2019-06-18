<?php
require_once("head.php");
require_once("user_logic.php");

if(isUserLogged()) {

?>
		<p class="text-success">You are logged as <?= Whois() ?>. <a href="logout.php">Deslogar</a></p>
<?php
} else {
?>
	
	<form action="login.php" method="post">
		<table class="table">
		<tr>
		<td colspan="2"><h3>Login Intranet</h3></td>
		</tr>
			<tr>
				<td>Email</td>
				<td><input class="form-control form-control-large" type="text" name="email"></td>
			</tr>
			<tr>
				<td>Senha</td>
				<td><input class="form-control form-control-large" type="password" name="password"></td>
			</tr>
			<tr>
				<td><button class="btn btn-primary">Login</button></td>
			</tr>
		</table>
	</form>
<?php
}
?>

<?php include("footer.php"); ?>
