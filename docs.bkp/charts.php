<!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>;
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<div class="block" align="center" id="pie_div"></div>
<div class="block" align="center" id="column_div"></div>

<h3 align="center"><u>Week Hours</u></h3>
</br>
<div id="cont">
<?php
$weekhours = weekHours($db);
?>
<div class="block">
<table class="table table-bordered table-striped">
<?php
tableContent($weekhours[0]);
?>
</table>
</div>

<div class="block">
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
</div>

<div class="block">
<table class="table table-bordered table-striped">
<?php
tableContent($weekhours[1]);
if ($weekhours[3] == 0){
?>
<tr>
		<td align="center">&nbsp</td>		
		<td align="center">&nbsp</td>
</tr>
<?php
}
?>
</table>
</div>
<div class="block">
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
</div>

<div class="block">
<table class="table table-bordered table-striped">
<?php
tableContent($weekhours[2]);
if ($weekhours[4] == 0 || $weekhours[3] == 0){
?>
<tr>
		<td align="center">&nbsp</td>		
		<td align="center">&nbsp</td>
</tr>
<?php
}
?>
</table>
</div>
</div>

<?php
function tableContent($resultsets){
?>
	<tr>
		<td align="center"><b>USER</b></td>
		<td align="center"><b>SUM</b></td>
	</tr>
<?php
foreach ($resultsets as $resultset) : 
?>
	<tr>
		<td align="center"><?= $resultset['email'] ?></td>
		<td align="center"><?= substr(($resultset['sum'] / 60), 0, 4) ?></td>		
	</tr>
<?php
endforeach ;
} 
?>

<script type="text/javascript">
    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      var pieData = $.ajax({
          url: "charts/typespie.php",
          dataType: "json",
          async: false
          }).responseText;

      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(pieData);

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart(document.getElementById('pie_div'));
      chart.draw(data, {title: 'Taken more than 5 days to be resolved', width: 500, height: 300});
    }
</script>

<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      var columnData = $.ajax({
          url: "charts/toolscolumn.php",
          dataType: "json",
          async: false
          }).responseText;

      var data = new google.visualization.DataTable(columnData);
      var chart = new google.visualization.ColumnChart(document.getElementById('column_div'));
      chart.draw(data, {title: 'Tools being used', width: 600, height: 300});
    }
</script>

</br>
