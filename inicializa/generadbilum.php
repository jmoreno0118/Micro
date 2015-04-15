<?php
 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	
  //  **** crea reconocimineto inicial ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS recsilumtbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		fecha DATE NOT NULL,
		largo DECIMAL(3,1) NOT NULL,
		ancho DECIMAL(3,1) NOT NULL,
		alto DECIMAL(2,1),
		tipolampara VARCHAR(50) NOT NULL,
		potencialamp VARCHAR(25) NOT NULL,
		numlamp INT(3),
		alturalamp DECIMAL(2,1) NOT NULL,
		techocolor VARCHAR(15) NOT NULL,
		paredcolor VARCHAR(15) NOT NULL,
		pisocolor VARCHAR(15) NOT NULL,
		influencia BOOLEAN NOT NULL,
		percepcion VARCHAR(255),
		ordenidfk INT)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando reconocimiento inicial '.$e;
	include 'error.html.php';
	exit();
  }

  //  **** crea union departamento-reconocimientoInicial ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS deptorecilumtbl (
	    deptoidfk INT NOT NULL,
		recilumidfk INT NOT NULL,
		PRIMARY KEY(deptoidfk, recilumidfk))
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando departamento-reconocimientoInicial '.$e;
	include 'error.html.php';
	exit();
  }
  
  //  **** crea departamentos del reconocimiento ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS deptostbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		departamento VARCHAR(50),
		area VARCHAR(50),
		descriproceso VARCHAR(255) NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando departametos del reconocimiento '.$e;
	include 'error.html.php';
	exit();
  }
  // **** descripcion de puestos de trabajo ****
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS descripuestostbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		puesto VARCHAR(50) NOT NULL,
		numtrabajadores INT(3) NOT NULL,
		actividades VARCHAR(255) NOT NULL,
		deptoidfk INT)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando descripcion de puesto de trabajo '.$e;
	include 'error.html.php';
	exit();
  }

  //  **** crea union punto-reconocimiento ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS puntorecilumtbl (
	    puntoidfk INT NOT NULL,
		recilumidfk INT NOT NULL,
		PRIMARY KEY(puntoidfk, recilumidfk))
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando departamento-reconocimientoInicial '.$e;
	include 'error.html.php';
	exit();
  }
    
  // **** descripcion del punto ****
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS puntostbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		medicion INT(3) NOT NULL,
		fecha DATE NOT NULL,
		departamento VARCHAR(25),
		area VARCHAR(25),
		ubicacion VARCHAR(50),
		identificacion VARCHAR(50),
		observaciones VARCHAR(255),
		nirm INT(3))
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando descripcion del punto '.$e;
	include 'error.html.php';
	exit();
  }
  
  // **** descripcion de la medicion del punto ****
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS medsilumtbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		hora TIME NOT NULL,
		e1pared DECIMAL(3,1) NOT NULL,
		e2pared DECIMAL(3,1) NOT NULL,
		e1plano DECIMAL(3,1) NOT NULL,
		e2plano DECIMAL(3,1) NOT NULL,
		puntoidfk INT)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando la medicion del punto '.$e;
	include 'error.html.php';
	exit();
  }
  echo 'Las bases de datos de iluminación se han generado correctamente';
  ?>
