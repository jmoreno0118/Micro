<?php
	require_once ('includes/jpgraph-3.5.0b1/src/jpgraph.php');
	require_once ('includes/jpgraph-3.5.0b1/src/jpgraph_bar.php');
	 
	/*// We need some data
	$datay=array(4,8,6);
	$labelx=array('10:00','15:00','20:00');
	 
	// Setup the graph. 
	$graph = new Graph(310,200, 'auto');    
	$graph->SetScale("textlin");
	$graph->img->SetMargin(25,15,15,-50);
	$graph->xaxis->SetTickLabels();
	 
	// Setup font for axis
	$graph->xaxis->SetFont(FF_FONT1);
	$graph->yaxis->SetFont(FF_FONT1);
	//$graph->yaxis->Hide();
	 
	// Create the bar pot
	$bplot = new BarPlot($datay);
	$bplot->SetWidth(0.6);
	 
	// Setup color for gradient fill style 
	$bplot->SetFillGradient("navy","lightsteelblue",GRAD_HOR);
	 
	// Set color for the frame of each bar
	$bplot->SetColor("navy");
	$graph->Add($bplot);
	 
	// Finally send the graph to the browser
	$graph->Stroke();*/

$datay1=array(35,160,0);
 
$graph = new Graph(450,200,'auto');    
$graph->SetScale("textlin");
$graph->SetShadow();
$graph->img->SetMargin(30,15,15,30);
$graph->xaxis->SetTickLabels(array('1','2','3'));
 
$bplot1 = new BarPlot($datay1);
$bplot1->SetShadow();

// Setup color for gradient fill style 
$bplot1->SetFillGradient("navy","lightsteelblue",GRAD_HOR);
	 
// Set color for the frame of each bar
$bplot1->SetColor("navy");
$graph->Add($bplot1);
 
$graph->Stroke();
?>