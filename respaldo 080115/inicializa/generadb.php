<?php
 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
 
//  **** crea usuarios ***
  echo 'estoy en generacion de base de datos';
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS usuariostbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		usuario VARCHAR(50) NOT NULL,
		clave CHAR(32),
		nombre VARCHAR(50) NOT NULL,
		apellido VARCHAR(50) NOT NULL,
		correo VARCHAR(60))
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando usuariostbl '.$e;
	include 'error.html.php';
	exit();
  } 

//  **** usuarioactividadtbl ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS usuarioactivtbl (
		usuarioidfk INT NOT NULL,
		actividfk VARCHAR(50) NOT NULL,
		PRIMARY KEY(usuarioidfk, actividfk))
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando usuarioactivtbl '.$e;
	include 'error.html.php';
	exit();
  }
  
//  **** actividadestbl ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS actividadestbl (
		id VARCHAR(50) NOT NULL PRIMARY KEY,
		descripcion VARCHAR(255) NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando usuarioactivtbl '.$e;
	include 'error.html.php';
	exit();
  }
  
  //  **** representantestbl ***
  try
  {
  $sql='CREATE TABLE IF NOT EXISTS representantestbl (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  estado VARCHAR(25),
  tel VARCHAR(50))
  DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
  $pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
  $mensaje='hubo un error creando la base de datos representante';
  include 'error.html.php';
  exit();
  }
  
  // **** usuariorep ****
    try
  {
    $sql='CREATE TABLE IF NOT EXISTS usuarioreptbl (
		usuarioidfk int NOT NULL,
		representanteidfk INT NOT NULL,
		PRIMARY KEY(usuarioidfk, representanteidfk))
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando usuarioactivtbl '.$e;
	include 'error.html.php';
	exit();
  }
  
  //  **** ordenes ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS ordenestbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		ot VARCHAR(10) NOT NULL,
		fechalta DATE NOT NULL,
		fechafin DATE,
		signatario VARCHAR(50),
		tipo VARCHAR(15) NOT NULL,
		supervisada BOOLEAN NOT NULL,
		representanteidfk INT NOT NULL,
		clienteidfk INT NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando ordenes '.$e;
	include 'error.html.php';
	exit();
  }

  //  **** estudios ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS estudiostbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		nombre VARCHAR(30) NOT NULL,
		ordenidfk int NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando estudios '.$e;
	include 'error.html.php';
	exit();
  }
  
  //  **** crea clientes ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS clientestbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		razonsocial VARCHAR(255) NOT NULL,
		planta VARCHAR(100),
		calle VARCHAR(255) NOT NULL,
		colonia VARCHAR (100),
		municipio VARCHAR(50),
		estado VARCHAR(25)NOT NULL,
		cp INT(5),
		rfc VARCHAR(150),
		atencion VARCHAR(100),
		tel VARCHAR(40))
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando cientes '.$e;
	include 'error.html.php';
	exit();
  }
  
  //  **** crea reconocimineto inicial ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS recinilumtbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		fecha DATE NOT NULL,
		departamento VARCHAR(50),
		area VARCHAR(50),
		descriproceso VARCHAR(255) NOT NULL,
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
		persepcion VARCHAR(255),
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
  
  // **** descripcion de puesto de trabajo ****
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS descripuestotbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		puesto VARCHAR(50) NOT NULL,
		numtrabajadores INT(3) NOT NULL,
		actividades VARCHAR(255) NOT NULL,
		recinilumidfk INT)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando descripcion de pluesto de trabajo '.$e;
	include 'error.html.php';
	exit();
  }
  
  
  // **** descripcion del punto ****
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS puntoilumtbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		medicion INT(3) NOT NULL,
		fecha DATE NOT NULL,
		departamento VARCHAR(25),
		area VARCHAR(25),
		ubicacion VARCHAR(50),
		identificación VARCHAR(50),
		nirm INT(3),
		observaciones VARCHAR(255),
		recinilumidfk INT)
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
    $sql='CREATE TABLE IF NOT EXISTS mediciontbl (
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
  echo 'Las bases de datos se han generado correctamente';
  ?>
