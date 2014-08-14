<?php

if(!isset($_GET['school'], $_GET['type'])) {
    echo "An unknown error has occurred.";
    die();
}

require_once "util/lib/phpgraph/phpgraphlib.php";
require_once "util/lib/phpgraph/phpgraphlib_pie.php";

$graph = new PHPGraphLibPie(400, 200);
$data = array("Forty" => 0.4, "Sixty" => 0.6);
$graph->addData($data);
$graph->setTitle("test title");
$graph->setLabelTextColor('50, 50, 50');
$graph->setLegendTextColor('50, 50, 50');
//$graph->setDataLabels(false);
$graph->createGraph();

?>