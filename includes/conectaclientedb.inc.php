<?php
// conecta la base de datos al servidor
 //echo 'estoy conectando la base de datos';   
  try
  {
	//$pdo = new PDO('mysql:host=201.166.162.138;dbname=reportes', 'reportes', 'reportes');
	//$pdo = new PDO('mysql:host=209.17.116.156;dbname=reportesdb', 'reporteusuario', 'MicroRep1');
	$cpdo = new PDO('mysql:host=209.17.116.156;dbname=microanalisis_management', 'micro_user', 'Micro2015');
	$cpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$cpdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$cpdo->exec('SET NAMES "utf8"');

  }
  catch (PDOException $e)
  {
    $mensaje='No fue posible conectar al servidor de clientes.' ;
	include 'error.html.php';
	exit();
  }
?>