
<?php
 /********** Norma 001 **********/
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';

if (!usuarioRegistrado())
{
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/direccionaregistro.inc.php';
	exit();
}
if (!usuarioConPermiso('Supervisor'))
{
	$mensaje='Solo el Supervisor tiene acceso a esta parte del programa';
	include '../accesonegado.html.php';
	exit();
}

$parametros = array('Sólidos Sedimentables','Grasas y Aceites','pH','Oxigeno','Nitrógeno Kjeldahl','DBO',
					'Sólidos y Sales Suspendidos','Acidez y Alcalinidad','Turbiedad','Color platino cobalto','Dureza',
					'Cloruros totales','Temperatura','Conductividad eletrolítica','Materia flotante',
					'Aguas residuales','Muestreo en cuerpos receptores','Fósforo','DQO','SAAM',
					'Cromo hexavalente','Fenoles totales','Cianuro','Ion de sulfato','Fluoruros',
					'Nitrógeno de Nitritos','Nitrógeno de Nitratos','Cloro libre y cloro total','Yodo libre residual',
					'Coliformes', 'Huevos de helminto','Absorción atómica','Metales','Compuestos orgánicos semivolatiles',
					'Compuestos orgánicos volatiles');

/**************************************************************************************************/
/* Ir a formulario de nueva metodo */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='Nueva')
{
	$pestanapag ='Agrega metodo';
	$titulopagina ='Agregar un nuevo metodo';
	$boton = 'Guardar Metodo';
	include 'formacaptura.html.php';
	exit();
}

/**************************************************************************************************/
/* Guardar nueva metodo */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='Guardar Metodo')
{
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try
	{
		$pdo->beginTransaction();

		$sql='INSERT INTO metodostbl SET
				parametro=:parametro,
				metodo=:metodo';
		$s=$pdo->prepare($sql);
		$s->bindValue(':parametro',$_POST['parametro']);
		$s->bindValue(':metodo',$_POST['metodo']);
		$s->execute();

		$pdo->commit();
	}
	catch (PDOException $e)
	{
		$pdo->rollback();
		$mensaje='Hubo un error al tratar de insertar la metodo. Favor de intentar nuevamente.'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	header('Location: .');
	exit();
}

/**************************************************************************************************/
/* Entrar a edición de una metodo */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='Editar')
{
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	$id = $_POST['id'];
	try
	{
		$sql='SELECT * FROM metodostbl WHERE id=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id',$id);
		$s->execute();
		$valores = $s->fetch();
	}
	catch (PDOException $e)
	{
		$mensaje='Hubo un error al tratar de insertar la metodo. Favor de intentar nuevamente.'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	$pestanapag ='Editar metodo';
	$titulopagina ='Editar un metodo';
	$boton = 'Salvar Metodo';
	include 'formacaptura.html.php';
	exit();
}

/**************************************************************************************************/
/* Salvar edición de un metodo */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='Salvar Metodo')
{
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try
	{
		$pdo->beginTransaction();

		$sql='UPDATE metodostbl SET
			parametro=:parametro,
			metodo=:metodo
			WHERE id=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':parametro',$_POST['parametro']);
		$s->bindValue(':metodo',$_POST['metodo']);
		$s->bindValue(':id',$_POST['id']);
		$s->execute();

		$pdo->commit();
	}
	catch (PDOException $e)
	{
		$pdo->rollback();
		$mensaje='Hubo un error al tratar de insertar la metodo. Favor de intentar nuevamente.'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	header('Location: .');
	exit();
}

/**************************************************************************************************/
/* Borrar un metodo */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='Borrar')
{
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php'; 
	$id=$_POST['id'];
	try
	{
		$sql='SELECT * FROM metodostbl WHERE id=:id';
		$s= $pdo->prepare($sql);
		$s->bindValue(':id',$id); 
		$s->execute();
		$valores=$s->fetch();
	}
	catch (PDOException $e)
	{
		$mensaje='No se pudo extraer información de la metodo'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	include 'formaconfirma.html.php';
	exit();
}

/**************************************************************************************************/
/* Confirmar borrado de un metodo */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='Continuar')
{
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php'; 
	$id=$_POST['id'];
	try
	{
		$sql='DELETE FROM metodostbl WHERE id=:id';
		$s= $pdo->prepare($sql);
		$s->bindValue(':id',$id); 
		$s->execute();
	}
	catch (PDOException $e)
	{
		$mensaje='No se pudo hacer la confirmacion de eliminación de la metodo'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	header('Location: .');
	exit();
}

/**************************************************************************************************/
/* Ver tabla de acreditaciones */
/**************************************************************************************************/
include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
try   
{
	$sql='SELECT * FROM metodostbl';
	$s=$pdo->prepare($sql); 
	$s->execute();
	$metodos = $s->fetchAll();
}
catch (PDOException $e)
{
	$mensaje='Hubo un error extrayendo la lista de ordenes de agua.'.$e;
  	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
  	exit();
}
include 'formametodos.html.php';
exit();