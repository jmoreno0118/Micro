<?php
  // este archivo los límites que comunmente se colocan en informe para la nom 001 semarnat
 
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
	$sql='INSERT INTO nom01maximostbl SET
		identificacion="Común",
		GyA=25,
		coliformes=2000,
		ssedimentables=2,
		ssuspendidos=125,
		dbo=150,
		nitrogeno=60,
		fosforo=30,
		arsenico=0.2,
		cadmio=0.2,
		cianuros=2,
		cobre=6,
		cromo=1,
		mercurio=0.01,
		niquel=4,
		plomo=0.4,
		zinc=20,
		hdehelminto=5';
	$pdo->exec($sql);	

	}
	catch (PDOExeption $e)
	{
	  $pdo->rollbak();
	  $mensaje='Hubo un error tratando de colocar los valores iniciales de la norma 001'.$e;
	  include 'error.html.php';
	  exit();
	}
	echo 'los valores iniciales se han colocado correctamente';
?>