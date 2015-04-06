<?php
 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  echo 'estoy en generacion de base de datos'; 
//  **** crea muestreos de aguas residuales***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS generalesaguatbl (
	    id INT NOT NULL  AUTO_INCREMENT PRIMARY KEY,
		ordenaguaidfk INT(11) NOT NULL,
		nom01maximosidfk INT(11) NOT NULL,
		numedicion INT(2) NOT NULL,
		lugarmuestreo VARCHAR(45) NOT NULL,
		descriproceso VARCHAR(100) NOT NULL,
		materiasusadas VARCHAR(100) NOT NULL,
		tratamiento VARCHAR(100) NOT NULL,
		Caracdescarga VARCHAR(100) NOT NULL,
		receptor VARCHAR(100) NOT NULL,
		estrategia VARCHAR(500) NOT NULL,
		numuestras INT(2) NOT NULL,
		observaciones VARCHAR(100) NOT NULL,
		tipomediciones VARCHAR(45) NOT NULL,
		proposito VARCHAR(45) NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando muestreosaguatbl '.$e;
	include 'error.html.php';
	exit();
  }
//  **** crea datos de campo ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS muestreosaguatbl (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		generalaguaidfk INT(11) NOT NULL,
	    fechamuestreo DATE NOT NULL,
	    identificacion VARCHAR(45) NOT NULL,
	    temperatura DECIMAL(3,1) NOT NULL,
	    caltermometro DECIMAL(6,4) NOT NULL,
	    pH DECIMAL(4,2) NOT NULL,
	    conductividad DECIMAL(5,3) NOT NULL,
	    cloro VARCHAR(45) NULL,
	    responsable VARCHAR(45) NOT NULL,
	    mflotante TINYINT(1) NOT NULL,
	    olor TINYINT(1) NOT NULL,
	    color TINYINT(1) NOT NULL,
	    turbiedad TINYINT(1) NOT NULL,
	    GyAvisual TINYINT(1) NOT NULL,
	    burbujas TINYINT(1) NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando campotbl '.$e;
	include 'error.html.php';
	exit();
  }
//  **** crea datos de la muestra compuesta ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS mcompuestastbl (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		muestreoaguaidfk INT(11) NOT NULL,
		hora TIME NOT NULL,
		flujo VARCHAR(20) NOT NULL,
		volumen INT(4) NOT NULL,
		observaciones VARCHAR(350) NOT NULL,
		caracteristicas VARCHAR(350) NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando mcompuestastbl '.$e;
	include 'error.html.php';
	exit();
  }
//  **** crea datos de los parametros ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS parametrostbl (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		muestreoaguaidfk VARCHAR(45) NOT NULL,
	    ssedimentables VARCHAR(20) NOT NULL,
	    ssuspendidos VARCHAR(20) NOT NULL,
	    dbo VARCHAR(20) NOT NULL,
	    nkjedahl VARCHAR(20) NULL,
	    nitritos VARCHAR(20) NULL,
	    nitratos VARCHAR(20) NULL,
	    nitrogeno VARCHAR(20) NOT NULL,
	    fosforo VARCHAR(20) NOT NULL,
	    arsenico VARCHAR(20) NOT NULL,
	    cadmio VARCHAR(20) NOT NULL,
	    cianuros VARCHAR(20) NOT NULL,
	    cobre VARCHAR(20) NOT NULL,
	    cromo VARCHAR(20) NOT NULL,
	    mercurio VARCHAR(20) NOT NULL,
	    niquel VARCHAR(20) NOT NULL,
	    plomo VARCHAR(20) NOT NULL,
	    zinc VARCHAR(20) NOT NULL,
	    hdehelminto VARCHAR(20) NOT NULL,
	    fechareporte DATE NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando parametrostbl '.$e;
	include 'error.html.php';
	exit();
  }
//  **** crea datos de los parametros se repeten ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS parametros2tbl (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		parametroidfk INT(11) NOT NULL,
		GyA VARCHAR(20) NOT NULL,
		coliformes VARCHAR(20) NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando parametros2tbl '.$e;
	include 'error.html.php';
	exit();
  }
//  **** crea maximos permitidos por la norma ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS nom01maximostbl (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		identificacion VARCHAR(45) NOT NULL,
	    GyA DECIMAL(3,1) NOT NULL,
	    coliformes DECIMAL(5,1) NOT NULL,
	    ssedimentables DECIMAL(2,1) NOT NULL,
	    ssuspendidos DECIMAL(4,1) NOT NULL,
	    dbo DECIMAL(4,1) NOT NULL,
	    nitrogeno DECIMAL(3,1) NOT NULL,
	    fosforo DECIMAL(3,1) NOT NULL,
	    arsenico DECIMAL(2,1) NOT NULL,
	    cadmio DECIMAL(2,1) NOT NULL,
	    cianuros DECIMAL(2,1) NOT NULL,
	    cobre DECIMAL(2,1) NOT NULL,
	    cromo DECIMAL(2,1) NOT NULL,
	    mercurio DECIMAL(3,2) NOT NULL,
	    niquel DECIMAL(2,1) NOT NULL,
	    plomo DECIMAL(2,1) NOT NULL,
	    zinc DECIMAL(3,1) NOT NULL,
	    hdehelminto DECIMAL(2,1) NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando nom01maximostbl '.$e;
	include 'error.html.php';
	exit();
  }

  //  **** crea limites de laboratorio ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS limitestbl (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		GyA DECIMAL(3,1) NOT NULL,
		coliformes DECIMAL(5,1) NOT NULL,
		ssedimentables DECIMAL(2,1) NOT NULL,
		ssuspendidos DECIMAL(4,1) NOT NULL,
		dbo DECIMAL(4,1) NOT NULL,
		nitrogeno DECIMAL(3,1) NOT NULL,
		fosforo DECIMAL(3,1) NOT NULL,
		arsenico DECIMAL(2,1) NOT NULL,
		cadmio DECIMAL(2,1) NOT NULL,
		cianuros DECIMAL(2,1) NOT NULL,
		cobre DECIMAL(2,1) NOT NULL,
		cromo DECIMAL(2,1) NOT NULL,
		mercurio DECIMAL(3,2) NOT NULL,
		niquel DECIMAL(2,1) NOT NULL,
		plomo DECIMAL(2,1) NOT NULL,
		zinc DECIMAL(3,1) NOT NULL,
		hdehelminto DECIMAL(2,1) NOT NULL,
		fecha DATE NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando limitestbl '.$e;
	include 'error.html.php';
	exit();
  }

    //  **** crea datos de laboratorio ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS laboratoriotbl (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		mcompuestaidfk INT(11) NOT NULL,
		fecharecepcion DATE NULL,
		horarecepcion TIME NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando laboratoriotbl '.$e;
	include 'error.html.php';
	exit();
  }

    //  **** crea adicionales de laboratorio ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS adicionalestbl (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		parametroidfk VARCHAR(45) NOT NULL,
		nombre VARCHAR(45) NOT NULL,
		unidades VARCHAR(45) NOT NULL,
		resultado VARCHAR(45) NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando adicionalestbl '.$e;
	include 'error.html.php';
	exit();
  }

    //  **** crea croquis de aguas ***
  try
  {
    $sql='CREATE TABLE IF NOT EXISTS croquistbl (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		nombre VARCHAR(100) NOT NULL,
		nombrearchivado VARCHAR(100) NOT NULL,
		generalaguaidfk INT(11) NOT NULL)
		DEFAULT CHARACTER SET utf8 ENGINE=InnoDB';
	$pdo->exec($sql);
  }
  catch (PDOExeption $e)
  {
	$mensaje='hubo un error creando adicionalestbl '.$e;
	include 'error.html.php';
	exit();
  }
  echo 'Las bases de datos de nom001 se han generado correctamente';
?>