<?php
  // este archivo los límites que comunmente se colocan en informe para la nom 001 semarnat
 
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
	$sql="INSERT INTO `nom01maximostbl` (`id`, `descargaen`, `uso`, `temperatura`, `mflotante`, `GyA`, `coliformes`, `ssedimentables`, `ssuspendidos`, `dbo`, `nitrogeno`, `fosforo`, `arsenico`, `cadmio`, `cianuros`, `cobre`, `cromo`, `mercurio`, `niquel`, `plomo`, `zinc`, `hdehelminto`) VALUES
										(1, 'Ríos', 'Riego agricola (A)', 'NA', 'Ausente', '25.0', '2000.0', '2.0', '200.0', '200.0', '60.0', '30.0', '0.4', '0.4', '3.0', '6.0', '1.5', '0.02', '4.0', '1.0', '20.0', '1.0'),
										(2, 'Ríos', 'Publlico urbano (B)', '40', 'Ausente', '25.0', '2000.0', '2.0', '125.0', '150.0', '60.0', '30.0', '0.2', '0.2', '2.0', '6.0', '1.0', '0.01', '4.0', '0.4', '20.0', '1.0'),
										(3, 'Ríos', 'Protección de vida acuática (C)', '40', 'Ausente', '25.0', '2000.0', '2.0', '60.0', '60.0', '25.0', '10.0', '0.2', '0.2', '2.0', '6.0', '1.0', '0.01', '4.0', '0.4', '20.0', '1.0'),
										(4, 'Embalses nat. y artif.', 'Riego agricola (B)', '40', 'Ausente', '25.0', '2000.0', '2.0', '125.0', '150.0', '60.0', '30.0', '0.4', '0.4', '3.0', '6.0', '1.5', '0.02', '4.0', '1.0', '20.0', '1.0'),
										(5, 'Embalses nat. y artif.', 'Publlico urbano (C)', '40', 'Ausente', '25.0', '2000.0', '2.0', '60.0', '60.0', '25.0', '10.0', '0.2', '0.2', '2.0', '6.0', '1.0', '0.01', '4.0', '0.4', '20.0', '1.0'),
										(6, 'Aguas costeras', 'Explotación pesquera, nav. y otros (A)', '40', 'Ausente', '25.0', '2000.0', '2.0', '200.0', '200.0', 'N.A.', 'N.A.', '0.2', '0.2', '2.0', '6.0', '1.0', '0.02', '4.0', '0.4', '20.0', '1.0'),
										(7, 'Aguas costeras', 'Recreación (B)', '40', 'Ausente', '25.0', '2000.0', '2.0', '125.0', '150.0', 'N.A.', 'N.A.', '0.4', '0.4', '3.0', '6.0', '1.5', '0.02', '4.0', '1.0', '20.0', '1.0'),
										(8, 'Aguas costeras', 'Estuarios (B)', '40', 'Ausente', '25.0', '2000.0', '2.0', '125.0', '150.0', '25.0', '10.0', '0.4', '0.4', '3.0', '6.0', '1.5', '0.02', '4.0', '1.0', '20.0', '1.0'),
										(9, 'Suelo', 'Riego agricola (A)', 'NA', 'Ausente', '25', '2000', 'N.A.', 'N.A.', 'N.A.', 'N.A.', 'N.A.', '0.4', '0.1', '3', '6', '1', '0.01', '4', '10', '4', '1'),
										(10, 'Suelo', 'Humedales naturales', '40', 'Ausente', '25', '2000', '2', '125', '150', 'N.A.', 'N.A.', '0.2', '0.2', '2', '6', '1', '0.01', '4', '0.4', '20', '1')";
	$pdo->exec($sql);	

	}
	catch (PDOExeption $e)
	{
	  $pdo->rollbak();
	  $mensaje='Hubo un error tratando de colocar los valores iniciales de los maximos de la nom 001'.$e;
	  include 'error.html.php';
	  exit();
	}
	echo 'los valores maximos se han colocado correctamente';
?>