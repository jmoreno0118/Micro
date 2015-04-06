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
	$mensaje='hubo un error creando usuarioreptbl '.$e;
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
		fecharevision DATE NOT NULL,
		representanteidfk INT NOT NULL,
		clienteidfk INT NOT NULL,
		plantaidfk INT NOT NULL)
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
    $sql='CREATE TABLE IF NOT EXISTS plantastbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		razonsocial VARCHAR(255) NOT NULL,
		planta VARCHAR(100),
		calle VARCHAR(255) NOT NULL,
		colonia VARCHAR (100),
		ciudad VARCHAR(50),
		estado VARCHAR(25)NOT NULL,
		cp INT(5),
		rfc VARCHAR(150),
		Numero_Clienteidfk INT NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando plantas '.$e;
	include 'error.html.php';
	exit();
  }

  //  **** crea clientes ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS clientestbl (
	      Numero_Cliente INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  Razon_Social VARCHAR(128) NOT NULL,
		  Calle_Numero VARCHAR(96) NOT NULL,
		  Colonia VARCHAR(64) NOT NULL,
		  Delegacion VARCHAR(64) NOT NULL,
		  Ciudad VARCHAR(64) NOT NULL,
		  Estado VARCHAR(64) NOT NULL,
		  Codigo_Postal VARCHAR(16) NOT NULL,
		  RFC VARCHAR(32) NOT NULL,
		  CURP VARCHAR(32) NOT NULL,
		  Nombre_Usuario VARCHAR(64) NOT NULL,
		  Telefono_Usuario VARCHAR(32) NOT NULL,
		  Email_Usuario VARCHAR(64) NOT NULL,
		  Nombre_Pagos VARCHAR(64) NOT NULL,
		  Telefono_Pagos VARCHAR(32) NOT NULL,
		  Email_Pagos VARCHAR(64) NOT NULL,
		  Descuento_Establecido DECIMAL(10,0) NOT NULL DEFAULT "0",
		  Giro_Empresa VARCHAR(45) NOT NULL)
		DEFAULT CHARACTER SET utf8
		ENGINE=FEDERATED
		CONNECTION="mysql://micro_user:Micro2015@209.17.116.156:3306/microanalisis_management/clientes"';

	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando cientes '.$e;
	include 'error.html.php';
	exit();
  }

  //  **** crea equipos ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS equipostbl (
		  id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
	      prueba VARCHAR(50) NOT NULL,
	      tipo VARCHAR(50) NOT NULL,
	      descripcion VARCHAR(100) NOT NULL,
	      inventario VARCHAR(15) NOT NULL,
	      marca VARCHAR(200) NOT NULL,
	      modelo VARCHAR(45) NOT NULL,
	      serie VARCHAR(45) NOT NULL,
	      fechaalta DATE NOT NULL,
	      causabaja VARCHAR(100) NULL,
	      fechabaja DATE NULL,
	      representanteidfk INT NOT NULL,
	      estado VARCHAR(100) NULL,
	      correccion VARCHAR(1000) NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando cientes '.$e;
	include 'error.html.php';
	exit();
  }

  //  **** crea plantas ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS plantastbl (
	      id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		  razonsocial VARCHAR(255) NOT NULL,
		  planta VARCHAR(100),
		  calle VARCHAR(255) NOT NULL,
		  colonia VARCHAR (100),
		  municipio VARCHAR(50),
		  estado VARCHAR(25)NOT NULL,
		  cp INT(5),
		  rfc VARCHAR(150),
		  empresagiro VARCHAR(45) NULL,
		  Numero_Clienteidfk INT NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando cientes '.$e;
	include 'error.html.php';
	exit();
  }

  echo 'Las bases de datos se han generado correctamente';
  ?>