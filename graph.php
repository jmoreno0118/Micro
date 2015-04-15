<?php
	require_once ('includes/jpgraph-3.5.0b1/src/jpgraph.php');
	require_once ('includes/jpgraph-3.5.0b1/src/jpgraph_bar.php');

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