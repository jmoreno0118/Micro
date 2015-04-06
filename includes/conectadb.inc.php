<?php
// conecta la base de datos al servidor
 //echo 'estoy conectando la base de datos';   
  try
  {
	 $pdo = new PDO('mysql:host=localhost;dbname=reportes', 'root', '');
	 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	 $pdo->exec('SET NAMES "utf8"');
  }
  catch (PDOException $e)
  {
    $mensaje='No fue posible conectar al servidor.';
	include 'error.html.php';
	exit();
  }
?>