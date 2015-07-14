<?php
 /********** Norma 001 **********/
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/funcionesecol.inc.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';

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
/* Guardar nuevo siralab de una medicion de una orden de trabajo */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='guardar')
{
	/*$mensaje='Error Forzado 3.';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();*/
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try
	{
		$pdo->beginTransaction();

		$sql='INSERT INTO siralabtbl SET
		muestreoaguaidfk=:id,
		titulo=:titulo,
		anexo=:anexo,
		rfc=:rfc,
		cuenca=:cuenca,
		tipoestudio=:tipoestudio,
		numerodescarga=:numerodescarga,
		region=:region,
		procedencia=:procedencia,
		lattgrados=:lattgrados,
		lattmin=:lattmin,
		lattseg=:lattseg,
		lontgrados=:lontgrados,
		lontmin=:lontmin,
		lontseg=:lontseg,
		latpgrados=:latpgrados,
		latpmin=:latpmin,
		latpseg=:latpseg,
		lonpgrados=:lonpgrados,
		lonpmin=:lonpmin,
		lonpseg=:lonpseg,
		datumgps=:datumgps,
		comentarios=:comentarios';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id', $_POST['id']);
		$s->bindValue(':titulo', $_POST['titulo']);
		$s->bindValue(':anexo', $_POST['anexo']);
		$s->bindValue(':rfc', $_POST['rfc']);
		$s->bindValue(':cuenca', $_POST['cuenca']);
		$s->bindValue(':tipoestudio', $_POST['tipoestudio']);
		$s->bindValue(':numerodescarga', $_POST['numerodescarga']);
		$s->bindValue(':region', $_POST['region']);
		$s->bindValue(':procedencia', $_POST['procedencia']);
		$s->bindValue(':lattgrados', $_POST['lattgrados']);
		$s->bindValue(':lattmin', $_POST['lattmin']);
		$s->bindValue(':lattseg', $_POST['lattseg']);
		$s->bindValue(':lontgrados', $_POST['lontgrados']);
		$s->bindValue(':lontmin', $_POST['lontmin']);
		$s->bindValue(':lontseg', $_POST['lontseg']);
		$s->bindValue(':latpgrados', $_POST['latpgrados']);
		$s->bindValue(':latpmin', $_POST['latpmin']);
		$s->bindValue(':latpseg', $_POST['latpseg']);
		$s->bindValue(':lonpgrados', $_POST['lonpgrados']);
		$s->bindValue(':lonpmin', $_POST['lonpmin']);
		$s->bindValue(':lonpseg', $_POST['lonpseg']);
		$s->bindValue(':datumgps', $_POST['datumgps']);
		$s->bindValue(':comentarios', $_POST['comentarios']);
		$s->execute();

		foreach ($_POST['mcompuestas'] as $value) {
	        $sql='UPDATE mcompuestastbl SET
						identificacion=:identificacion,
						fecharecepcion=:fecharecepcion,
						horarecepcion=:horarecepcion,
						temperatura=:temperatura,
						pH=:pH
						WHERE id=:id';
	        $s=$pdo->prepare($sql);
	        $s->bindValue(':id', $value["id"]);
	        $s->bindValue(':identificacion', $value["identificacion"]);
	       	$s->bindValue(':fecharecepcion', (isset($value["fechalab"])) ? $value["fechalab"] : '0000-00-00');
	        $s->bindValue(':horarecepcion', (isset($value["horalab"])) ? $value["horalab"] : '00:00');
	        $s->bindValue(':temperatura', $value["temperatura"]);
	        $s->bindValue(':pH', $value["pH"]);
	        $s->execute();
  		}

		$pdo->commit();
	}
	catch (PDOException $e)
	{
		$pdo->rollback();
		$mensaje='Hubo un error al tratar de insertar el reconocimiento. Favor de intentar nuevamente.'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/generales');
	exit();
}

/**************************************************************************************************/
/* Editar siralab de una medicion de una orden de trabajo */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='salvar')
{
	/*$mensaje='Error Forzado 3.';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();*/
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try
	{
		$pdo->beginTransaction();

		$sql='UPDATE siralabtbl SET
		titulo=:titulo,
		anexo=:anexo,
		rfc=:rfc,
		cuenca=:cuenca,
		tipoestudio=:tipoestudio,
		numerodescarga=:numerodescarga,
		region=:region,
		procedencia=:procedencia,
		cuerporeceptor=:cuerporeceptor,
		lattgrados=:lattgrados,
		lattmin=:lattmin,
		lattseg=:lattseg,
		lontgrados=:lontgrados,
		lontmin=:lontmin,
		lontseg=:lontseg,
		latpgrados=:latpgrados,
		latpmin=:latpmin,
		latpseg=:latpseg,
		lonpgrados=:lonpgrados,
		lonpmin=:lonpmin,
		lonpseg=:lonpseg,
		datumgps=:datumgps,
		comentarios=:comentarios
		WHERE muestreoaguaidfk=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id', $_POST['id']);
		$s->bindValue(':titulo', $_POST['titulo']);
		$s->bindValue(':anexo', $_POST['anexo']);
		$s->bindValue(':rfc', $_POST['rfc']);
		$s->bindValue(':cuenca', $_POST['cuenca']);
		$s->bindValue(':tipoestudio', $_POST['tipoestudio']);
		$s->bindValue(':numerodescarga', $_POST['numerodescarga']);
		$s->bindValue(':region', $_POST['region']);
		$s->bindValue(':procedencia', $_POST['procedencia']);
		$s->bindValue(':cuerporeceptor', $_POST['cuerporeceptor']);
		$s->bindValue(':lattgrados', $_POST['lattgrados']);
		$s->bindValue(':lattmin', $_POST['lattmin']);
		$s->bindValue(':lattseg', $_POST['lattseg']);
		$s->bindValue(':lontgrados', $_POST['lontgrados']);
		$s->bindValue(':lontmin', $_POST['lontmin']);
		$s->bindValue(':lontseg', $_POST['lontseg']);
		$s->bindValue(':latpgrados', $_POST['latpgrados']);
		$s->bindValue(':latpmin', $_POST['latpmin']);
		$s->bindValue(':latpseg', $_POST['latpseg']);
		$s->bindValue(':lonpgrados', $_POST['lonpgrados']);
		$s->bindValue(':lonpmin', $_POST['lonpmin']);
		$s->bindValue(':lonpseg', $_POST['lonpseg']);
		$s->bindValue(':datumgps', $_POST['datumgps']);
		$s->bindValue(':comentarios', $_POST['comentarios']);
		$s->execute();

		foreach ($_POST['mcompuestas'] as $value)
		{
	        $sql='UPDATE mcompuestastbl SET
						identificacion=:identificacion,
						fecharecepcion=:fecharecepcion,
						horarecepcion=:horarecepcion,
						temperatura=:temperatura,
						pH=:pH
						WHERE id=:id';
	        $s=$pdo->prepare($sql);
	        $s->bindValue(':id', $value["id"]);
	        $s->bindValue(':identificacion', $value["identificacion"]);
	       	$s->bindValue(':fecharecepcion', (isset($value["fechalab"])) ? $value["fechalab"] : '0000-00-00');
	        $s->bindValue(':horarecepcion', (isset($value["horalab"])) ? $value["horalab"] : '00:00');
	        $s->bindValue(':temperatura', $value["temperatura"]);
	        $s->bindValue(':pH', $value["pH"]);
	        $s->execute();
  		}

		$pdo->commit();
	}
	catch (PDOException $e)
	{
		$pdo->rollback();
		$mensaje='Hubo un error al tratar de insertar el reconocimiento. Favor de intentar nuevamente.'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/generales');
	exit();
}

/**************************************************************************************************/
/* Acción default */
/**************************************************************************************************/
$id = $_SESSION['siralab']['id'];
$valores = $_SESSION['siralab']['valores'];
$mcompuestas = $_SESSION['siralab']['mcompuestas'];
$cantidad = $_SESSION['siralab']['cantidad'];
$boton = $_SESSION['siralab']['boton'];
$regreso = $_SESSION['siralab']['regreso'];
$pestanapag = $_SESSION['siralab']['pestanapag'];
$titulopagina = $_SESSION['siralab']['titulopagina'];
include 'formacapturar.html.php';
exit();