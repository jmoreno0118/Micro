<?php
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
//  **** crea usuarios ***
  echo 'estoy en generacion de base de datos de vibraciones';
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS vib_medstbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		puntoidfk INT NOT NULL,
		puesto VARCHAR(50) NOT NULL,
		evento VARCHAR(50) NOT NULL,
		ciclos INT(5) NOT NULL,
		duracion INT(4) NOT NULL,
		herramienta VARCHAR(50) NOT NULL,
		tipoevento VARCHAR(100) NOT NULL,
		med1 DECIMAL(5,3) NOT NULL,
		med2 DECIMAL(5,3) NOT NULL,
		med3 DECIMAL(5,3) NOT NULL
		)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando la tabla vibmedstbl '.$e;
	include 'error.html.php';
	exit();
  } 
// crea la tabla reconocimientos inicialies de vibraciones
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS vib_recstbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		ordenidfk INT,
		procedimiento VARCHAR(200) NOT NULL,
		manto VARCHAR(200) NOT NULL,
		eqvibracionidfk INT NOT NULL,
		acelerometroidfk INT NOT NULL,
		calibradoridfk INT NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando la tabla del reconicimiento de vibraciones '.$e;
	include 'error.html.php';
	exit();
  }
// crea la tabla identificacion de la generacion de vibraciones
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS vib_idstbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		vibrecidfk INT NOT NULL,
		area VARCHAR(50) NOT NULL,
		fuente VARCHAR(50) NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando la tabla de las identificaciones de vibraciones '.$e;
	include 'error.html.php';
	exit();
  }
// crea la tabla identificacion de los puestos de vibraciones
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS vib_puestostbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		vibrecidfk INT NOT NULL,
		nombre VARCHAR(50) NOT NULL,
		descripcion VARCHAR(100) NOT NULL,
		ciclos VARCHAR(50) NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando la tabla de los puestos de trabajo en vibracioens '.$e;
	include 'error.html.php';
	exit();
  }
// crea la tabla de info de produccion
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS vib_producciontbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		vibrecidfk INT NOT NULL,
		depto VARCHAR(50) NOT NULL,
		cnormales VARCHAR(50) NOT NULL,
		preal VARCHAR(50) NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando la tabla produccion en vibraciones'.$e;
	include 'error.html.php';
	exit();
  } 
// crea la tabla de info de produccion
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS vib_poetbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		vibrecidfk INT NOT NULL,
		area VARCHAR(50) NOT NULL,
		numero VARCHAR(50) NOT NULL,
		expo VARCHAR(50) NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando la tabla de poe '.$e;
	include 'error.html.php';
	exit();
  }
// crea tabla de enlace entre puntos y reconocimiento inicialies
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS vib_puntorectbl (
	    puntoidfk INT NOT NULL,
		vibrcidfk INT NOT NULL,
		PRIMARY KEY(puntoidfk, vibrcidfk))
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando la tabla de poe '.$e;
	include 'error.html.php';
	exit();
  }
  echo 'Las bases de datos de vibraciones se han generado correctamente'.'<br>';
  ?>
