<html>
<head>
<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript">

swfobject.embedSWF(
"open-flash-chart.swf", "my_chart",
"300", "300", "9.0.0", "expressInstall.swf",
{"data-file":"gallery/pie-chart.php"} );

</script>
</head>

<?php

include '/php-ofc-library/open-flash-chart.php';

$title = new title( 'Pork Pie, Mmmmm' );

$pie = new pie();
$pie->set_alpha(0.6);
$pie->set_start_angle( 35 );
$pie->add_animation( new pie_fade() );
$pie->set_tooltip( '#val# of #total#<br>#percent# of 100%' );
$pie->set_colours( array('#1C9E05','#FF368D') );
$pie->set_values( array(2,3,4,new pie_value(6.5, "hello (6.5)")) );

$chart = new open_flash_chart();
$chart->set_title( $title );
$chart->add_element( $pie );


$chart->x_axis = null;

echo $chart->toPrettyString();

?>

