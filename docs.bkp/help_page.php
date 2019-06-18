<?php
require_once("user_logic.php");

checkUser();

$lvl = $_SESSION["level"];


require_once("head$lvl.php");

?>
<div align="center">
<h4>How do we use the application?</h4>
</br>
<p>Checkout the tutorial and faq page clicking <a href="http://ibmurl.hursley.ibm.com/NXN2" target="_blank">here</a>. There you may find some videos and other good information.</p>
<p>If you are facing any issue, feel free to contact the team. Just hit us a message!</p>
</br>
</div>
<form action="mail/mailer.php" method="post">
<table class="table">

<h3 align="center">Email</h3>
</br>
<tr>
<td><label>Subject</label></td>
<td><input required type="text" name="subject" class="form-control" placeholder="Subject"></td>
</tr>
<tr>
<td><label>Message</label></td>
<td><textarea required maxlength="2048" name="body" class="form-control" placeholder="Message"></textarea></td>
</tr>
<tr>
<td><button class="btn btn-group btn-primary">Submit</button></td>
</tr>
</form>
</table>

<?php


require_once("footer.php");
?>

