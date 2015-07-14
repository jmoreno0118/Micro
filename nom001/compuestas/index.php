<?php
 /********** Norma 001 **********/
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/funcionesecol.inc.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';

 if (!usuarioRegistrado())
 {
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/direccionaregistro.inc.php';
  exit();
 }
 if (!usuarioConPermiso('Captura'))
 {
  $mensaje='Solo el Capturista tiene acceso a esta parte del programa';
  include '../accesonegado.html.php';
  exit();
 }
 limpiasession();

/**************************************************************************************************/
/* Guardar nuevas muestras compuestas de una orden de trabajo */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='guardar' OR isset($_POST['accion']) and $_POST['accion']=='siguiente')
{
	/*$mensaje='Error Forzado 2.';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();*/

	fijarAccionUrl('guardar');

	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	if(isset($_POST['regreso']) AND $_POST['regreso'] === '2')
	{
		formularioParametros($_POST['id'], $_POST['muestreoid'], intval($_POST['cantidad']), $_POST['idparametro'], json_decode($_POST['valores'],TRUE), json_decode($_POST['parametros'],TRUE), json_decode($_POST['adicionales'],TRUE), 1, $_POST['accionparam']);
	}else{
		if($_POST['accion'] !== 'siguiente')
		{
			try
			{
				foreach ($_POST["mcompuestas"] as $key => $value) {
			        $sql='INSERT INTO mcompuestastbl SET
									muestreoaguaidfk=:id,
									hora=:hora,
									flujo=:flujo,
									volumen=:volumen,
									observaciones=:observaciones,
									caracteristicas=:caracteristicas';
			        $s=$pdo->prepare($sql);
			        $s->bindValue(':id',  $_POST['id']);
			        $s->bindValue(':hora', (isset($value["hora"])) ? $value["hora"] : '');
			        $s->bindValue(':flujo', (isset($value["flujo"])) ? $value["flujo"] : '');
			        $s->bindValue(':volumen', (isset($value["volumen"])) ? $value["volumen"] : 0);
			        $s->bindValue(':observaciones', $value["observaciones"]);
			        $s->bindValue(':caracteristicas', $value["caracteristicas"]);
			        $s->execute();
		      	}
			}
			catch (PDOException $e)
			{
				$pdo->rollback();
				$mensaje='Hubo un error al tratar de insertar GyA y coliformes. Favor de intentar nuevamente.'.$e;
				include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
				exit();
			}
		}
		formularioParametros($_POST['id'], $_POST['muestreoid'], $_POST['cantidad'], "", "", "", "", 1);
		exit();
	}
}

/**************************************************************************************************/
/* Guardar la edicion de muestras compuestas de una medicion de una orden de trabajo */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='salvar')
{
	/*$mensaje='Error Forzado 2.';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();*/

	fijarAccionUrl('salvar');

	$id = $_POST['id'];
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';

	if(isset($_POST['regreso']) AND $_POST['regreso'] === '2')
	{
		formularioParametros($_POST['id'], $_POST['muestreoid'], intval($_POST['cantidad']), $_POST['idparametro'], json_decode($_POST['valores'],TRUE), json_decode($_POST['parametros'],TRUE), json_decode($_POST['adicionales'],TRUE), 1, $_POST['accionparam']);
	}
	try
	{
		foreach ($_POST["mcompuestas"] as $key => $value)
		{
	        $sql='UPDATE mcompuestastbl SET
					hora=:hora,
					flujo=:flujo,
					volumen=:volumen,
					observaciones=:observaciones,
					caracteristicas=:caracteristicas
					WHERE id=:id';
	        $s=$pdo->prepare($sql);
	        $s->bindValue(':hora', (isset($value["hora"])) ? $value["hora"] : '');
	        $s->bindValue(':flujo', (isset($value["flujo"])) ? $value["flujo"] : '');
	        $s->bindValue(':volumen', (isset($value["volumen"])) ? $value["volumen"] : 0);
	        $s->bindValue(':observaciones', $value["observaciones"]);
	        $s->bindValue(':caracteristicas', $value["caracteristicas"]);
	        $s->bindValue(':id', $value["id"]);
	        $s->execute();
	  	}
	}
	catch (PDOException $e)
	{
		$pdo->rollback();
		$mensaje='Hubo un error al tratar de actulizar la medicion. Favor de intentar nuevamente.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	formularioParametros($_POST['id'], $_POST['muestreoid'], $_POST['cantidad'], "", "", "", "", 1);
}

/**************************************************************************************************/
/* Ver mediciones de una orden de trabajo */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='volver a mediciones')
{
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/generales');
	exit();
}

/**************************************************************************************************/
/* Acción default */
/**************************************************************************************************/
$id = $_SESSION['mediciones']['id'];
$muestreoid = $_SESSION['mediciones']['muestreoid'];
$mcompuestas = $_SESSION['mediciones']['mcompuestas'];
$cantidad = $_SESSION['mediciones']['cantidad'];
$boton = $_SESSION['mediciones']['boton'];
$regreso = $_SESSION['mediciones']['regreso'];
$pestanapag = $_SESSION['mediciones']['pestanapag'];
$titulopagina = $_SESSION['mediciones']['titulopagina'];
//unset($_SESSION['mediciones']);
include 'formacapturarcompuestas.html.php';
exit();