<?php
 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectaclientedb.inc.php';
	
  //  **** crea reconocimineto inicial ***
  try
  {
    $sql='ALTER TABLE clientes ADD COLUMN Giro_Empresa VARCHAR(45) NOT NULL';
	$cpdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando reconocimiento inicial '.$e;
	include 'error.html.php';
	exit();
  }

  echo 'Se ha agregado el campo Giro_Empresa a clientes';
  ?>
