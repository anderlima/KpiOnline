<h2 align="center"><u>Backlog</u></h2>
</br>
<div id="cont">
<div class="block">
<table class="table table-bordered table-striped">
	<tr>
		<td align="center"><b>USER</b></td>
		<td align="center"><b># LAUNCHED</b></td>
		<td align="center"><b>DUE 48h</b></td>
		<td align="center"><b>CLAN</b></td>
	</tr>
<?php
$backlogs = backlog($db);
foreach ($backlogs[0] as $backlog) : 
?>
<tr>
		<td align="center"><?= $backlog['users_email'] ?></td>
		<td align="center"><?= $backlog['opened'] ?></td>		
		<td align="center"><?= $backlog['duesoon'] ?></td>
		<td align="center"><?= $backlog['agile_group'] ?></td>
</tr>
<?php
endforeach
?>
</table>
</div>
<div class="block">
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
</div>
<div class="block">
<table class="table table-bordered table-striped">
	<tr>
		<td align="center"><b>USER</b></td>
		<td align="center"><b># LAUNCHED</b></td>
		<td align="center"><b>DUE 48h</b></td>
		<td align="center"><b>CLAN</b></td>
	</tr>
<?php 
foreach ($backlogs[1] as $backlog) : 
?>
<tr>
		<td align="center"><?= $backlog['users_email'] ?></td>
		<td align="center"><?= $backlog['opened'] ?></td>		
		<td align="center"><?= $backlog['duesoon'] ?></td>
		<td align="center"><?= $backlog['agile_group'] ?></td>
</tr>
<?php
endforeach ;
if ($backlogs[2] == 0){
?>
	<tr>
		<td align="center">&nbsp</td>		
		<td align="center">&nbsp</td>
		<td align="center">&nbsp</td>
		<td align="center">&nbsp</td>
</tr>
<?php
}
?>
</table>
</div>
</div>

