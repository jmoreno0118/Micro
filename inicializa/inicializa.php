<?php
  // este archivo crea los archivos que se utilizaran en el sistema 
  // y colocará los datos minimos para funcionar que son las actividades,
  // un primer usuario y enlazará los 2 archivos además de dar de alta las
  // sucursales de microanalisis
  
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  include 'generadb.php';
  try
  {
	$pdo->beginTransaction();
	$sql='INSERT INTO actividadestbl SET
		id="Administra usuarios",
		descripcion="Agraga, elimina, y edita usuarios"';
	$pdo->exec($sql);
	$sql='INSERT INTO actividadestbl SET
		id="Administra clientes",
		descripcion="Agraga, elimina, y edita clientes"';
	$pdo->exec($sql);	
	$sql='INSERT INTO actividadestbl SET
		id="Administra OT",
		descripcion="Agraga, elimina, y edita ordenes de trabajo"';
	$pdo->exec($sql);
	$sql='INSERT INTO actividadestbl SET
		id="Administra representantes",
		descripcion="Agraga, elimina, y edita representantes"';
	$pdo->exec($sql);
	$sql='INSERT INTO actividadestbl SET
		id="Captura",
		descripcion="Agraga, elimina, y edita datos en los informes"';
	$pdo->exec($sql);
	$sql='INSERT INTO usuariostbl SET 
		usuario="rafa",
		clave=:clave,
		nombre="Rafael",
		apellido="Vazquez Pineda",
		correo="rafa.vazquez@microanalisis.com"';
	$s=$pdo->prepare($sql);
	$s->bindValue(':clave',md5('rafiqui'.'ravol'));
	$s->execute();
	$sql='INSERT INTO usuariostbl SET 
		usuario="jesus",
		clave=:clave,
		nombre="Jesús",
		apellido="Moreno Pérez",
		correo="jj.vazquez@microanalisis.com"';
	$s=$pdo->prepare($sql);
	$s->bindValue(':clave',md5('jesus'.'ravol'));
	$s->execute();
	$sql='INSERT INTO usuarioactivtbl SET 
		usuarioidfk=1,
		actividfk="Administra clientes"';
	$pdo->exec($sql);
	$sql='INSERT INTO usuarioactivtbl SET 
		usuarioidfk=1,
		actividfk="Administra OT"';
	$pdo->exec($sql);
	$sql='INSERT INTO usuarioactivtbl SET 
		usuarioidfk=1,
		actividfk="Administra representantes"';
	$pdo->exec($sql);
	$sql='INSERT INTO usuarioactivtbl SET 
		usuarioidfk=1,
		actividfk="Administra usuarios"';
	$pdo->exec($sql);
	$sql='INSERT INTO usuarioactivtbl SET 
		usuarioidfk=1,
		actividfk="Captura"';
	$pdo->exec($sql);
		$sql='INSERT INTO usuarioactivtbl SET 
		usuarioidfk=2,
		actividfk="Administra clientes"';
	$pdo->exec($sql);
	$sql='INSERT INTO usuarioactivtbl SET 
		usuarioidfk=2,
		actividfk="Administra OT"';
	$pdo->exec($sql);
	$sql='INSERT INTO usuarioactivtbl SET 
		usuarioidfk=2,
		actividfk="Administra representantes"';
	$pdo->exec($sql);
	$sql='INSERT INTO usuarioactivtbl SET 
		usuarioidfk=2,
		actividfk="Administra usuarios"';
	$pdo->exec($sql);
	$sql='INSERT INTO usuarioactivtbl SET 
		usuarioidfk=2,
		actividfk="Captura"';
	$pdo->exec($sql);
	$sql='INSERT INTO representantestbl SET 
		nombre="Micro matriz",
		estado="DF",
		tel="(55)5768-7744"';
	$pdo->exec($sql);
	$sql='INSERT INTO representantestbl SET 
		nombre="Micro Atizapan de Zaragoza",
		estado="México",
		tel="(555)825-8439"';
	$pdo->exec($sql);
	$sql='INSERT INTO representantestbl SET 
		nombre="Micro Cuernavaca",
		estado="Morelos",
		tel="(777)320-6669"';
	$pdo->exec($sql);
	$sql='INSERT INTO representantestbl SET 
		nombre="Micro Puebla",
		estado="Puebla",
		tel="(222)244-4196"';
	$pdo->exec($sql);
	$sql='INSERT INTO representantestbl SET 
		nombre="Micro Reynosa",
		estado="Tamaulipas",
		tel="(899)129-4983"';
	$pdo->exec($sql);
	$sql='INSERT INTO representantestbl SET 
		nombre="Micro Villahermosa",
		estado="Tabasco",
		tel="(993)354-5323"';
	$pdo->exec($sql);

	$pdo->commit();
	}
	catch (PDOExeption $e)
	{
	  $pdo->rollbak();
	  $mensaje='Hubo un error tratando de colocar los valores iniciales '.$e;
	  include 'error.html.php';
	  exit();
	}
	echo 'los valores iniciales se han colocado correctamente';
?>