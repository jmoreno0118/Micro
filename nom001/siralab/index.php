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

/*var_dump($_POST['mcompuestas']);
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

	        if(isset($value["identificacion"]) AND strcmp(trim($value["identificacion"]), '') !== 0 ){
	        	$s->bindValue(':identificacion', $value["identificacion"]);
	        }elseif(!isset($value["identificacion"]) OR strcmp(trim($value["identificacion"]), '') === 0 ){
	        	$s->bindValue(':identificacion', NULL, PDO::PARAM_INT);
	        }

	        if(isset($value["fechalab"]) AND strcmp(trim($value["fechalab"]), '') !== 0 ){
	        	$s->bindValue(':fecharecepcion', $value["fechalab"]);
	        }elseif(!isset($value["fechalab"])  OR strcmp(trim($value["fechalab"]), '') === 0 ){
	        	$s->bindValue(':fecharecepcion', NULL, PDO::PARAM_INT);
	        }

	        if(isset($value["horalab"]) AND strcmp(trim($value["horalab"]), '') !== 0 ){
	        	$s->bindValue(':horarecepcion', $value["horalab"]);
	        }elseif(!isset($value["horalab"]) OR strcmp(trim($value["horalab"]), '') === 0 ){
	        	$s->bindValue(':horarecepcion', NULL, PDO::PARAM_INT);
	        }

	        if(isset($value["temperatura"]) AND strcmp(trim($value["temperatura"]), '') !== 0 ){
	        	$s->bindValue(':temperatura', $value["temperatura"]);
	        }elseif(!isset($value["temperatura"]) OR strcmp(trim($value["temperatura"]), '') === 0 ){
	        	$s->bindValue(':temperatura', NULL, PDO::PARAM_INT);
	        }

	        if(isset($value["pH"]) AND strcmp(trim($value["pH"]), '') !== 0 ){
	        	$s->bindValue(':pH', $value["pH"]);
	        }elseif(!isset($value["pH"]) OR strcmp(trim($value["pH"]), '') === 0 ){
	        	$s->bindValue(':pH', NULL, PDO::PARAM_INT);
	        }
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

	        if(isset($value["identificacion"]) AND strcmp(trim($value["identificacion"]), '') !== 0 ){
	        	$s->bindValue(':identificacion', $value["identificacion"]);
	        }elseif(!isset($value["identificacion"]) OR strcmp(trim($value["identificacion"]), '') === 0 ){
	        	$s->bindValue(':identificacion', NULL, PDO::PARAM_INT);
	        }

	        if(isset($value["fechalab"]) AND strcmp(trim($value["fechalab"]), '') !== 0 ){
	        	$s->bindValue(':fecharecepcion', $value["fechalab"]);
	        }elseif(!isset($value["fechalab"])  OR strcmp(trim($value["fechalab"]), '') === 0 ){
	        	$s->bindValue(':fecharecepcion', NULL, PDO::PARAM_INT);
	        }

	        if(isset($value["horalab"]) AND strcmp(trim($value["horalab"]), '') !== 0 ){
	        	$s->bindValue(':horarecepcion', $value["horalab"]);
	        }elseif(!isset($value["horalab"]) OR strcmp(trim($value["horalab"]), '') === 0 ){
	        	$s->bindValue(':horarecepcion', NULL, PDO::PARAM_INT);
	        }

	        if(isset($value["temperatura"]) AND strcmp(trim($value["temperatura"]), '') !== 0 ){
	        	$s->bindValue(':temperatura', $value["temperatura"]);
	        }elseif(!isset($value["temperatura"]) OR strcmp(trim($value["temperatura"]), '') === 0 ){
	        	$s->bindValue(':temperatura', NULL, PDO::PARAM_INT);
	        }

	        if(isset($value["pH"]) AND strcmp(trim($value["pH"]), '') !== 0 ){
	        	$s->bindValue(':pH', $value["pH"]);
	        }elseif(!isset($value["pH"]) OR strcmp(trim($value["pH"]), '') === 0 ){
	        	$s->bindValue(':pH', NULL, PDO::PARAM_INT);
	        }
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