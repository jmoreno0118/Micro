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
/* Guardar nuevas muestras compuestas de una orden de trabajo */
/**************************************************************************************************/
	if(isset($_POST['accion']) and $_POST['accion']=='guardarmcomp')
	{
		/*$mensaje='Error Forzado 2.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();*/

		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
		if(isset($_POST['regreso']) AND $_POST['regreso'] === '2'){
			formularioParametros($_POST['id'], intval($_POST['cantidad']), $_POST['idparametro'], json_decode($_POST['valores'],TRUE), json_decode($_POST['parametros'],TRUE), json_decode($_POST['adicionales'],TRUE));
		}else{
			insertMediciones($_POST["mcompuestas"], $_POST['id']);

			formularioParametros($_POST['id'], $_POST['cantidad'], "", "", "", "", 1);
			exit();
		}
	}

/**************************************************************************************************/
/* Guardar la edicion de muestras compuestas de una medicion de una orden de trabajo */
/**************************************************************************************************/
	if(isset($_POST['accion']) and $_POST['accion']=='salvarmcomp')
	{
		/*$mensaje='Error Forzado 2.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();*/

		$id = $_POST['id'];
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';

		if(isset($_POST['regreso']) AND $_POST['regreso'] === '2'){
			formularioParametros($_POST['id'], intval($_POST['cantidad']), $_POST['idparametro'], json_decode($_POST['valores'],TRUE), json_decode($_POST['parametros'],TRUE), json_decode($_POST['adicionales'],TRUE), 1);
		}
		try
		{
			$pdo->beginTransaction();

			$sql='SELECT id
			FROM muestreosaguatbl
			WHERE generalaguaidfk = :id';
			$s=$pdo->prepare($sql);
			$s->bindValue(':id',$_POST['id']);
			$s->execute();
			$muestreo = $s->fetch();

			$sql='DELETE FROM laboratoriotbl WHERE mcompuestaidfk IN (SELECT mcompuestastbl.id
									                                FROM mcompuestastbl
									                                INNER JOIN muestreosaguatbl ON mcompuestastbl.muestreoaguaidfk = muestreosaguatbl.id
									                                WHERE muestreosaguatbl.generalaguaidfk = :id)';
			$s=$pdo->prepare($sql);
			$s->bindValue(':id', $muestreo['id']);
			$s->execute();

			$sql='DELETE FROM mcompuestastbl WHERE muestreoaguaidfk = :id';
			$s=$pdo->prepare($sql);
			$s->bindValue(':id', $muestreo['id']);
			$s->execute();

			insertMediciones($_POST["mcompuestas"], $muestreo['id']);

			$pdo->commit();
		}catch (PDOException $e){
			$pdo->rollback();
			$mensaje='Hubo un error al tratar de actulizar la medicion. Favor de intentar nuevamente.';
			include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
			exit();
		}
		formularioParametros($_POST['id'], $_POST['cantidad'], "", "", "", "", 1);
	}

/**************************************************************************************************/
/* Ver mediciones de una orden de trabajo */
/**************************************************************************************************/
	if(isset($_POST['accion']) and $_POST['accion']=='volvermed')
	{
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/generales');
		exit();
	}

/**************************************************************************************************/
/* Acción default */
/**************************************************************************************************/
	$id = $_SESSION['mediciones']['id'];
	$mcompuestas = $_SESSION['mediciones']['mcompuestas'];
	$cantidad = $_SESSION['mediciones']['cantidad'];
	$boton = $_SESSION['mediciones']['boton'];
	$regreso = $_SESSION['mediciones']['regreso'];
	$pestanapag = $_SESSION['mediciones']['pestanapag'];
	$titulopagina = $_SESSION['mediciones']['titulopagina'];
	//unset($_SESSION['mediciones']);
	include 'formacapturarcompuestas.html.php';
	exit();

/**************************************************************************************************/
/* Función para insertar GyA y coliformes */
/**************************************************************************************************/
function insertMediciones($mcompuestas, $muestreoid){
	global $pdo;
	try{
		foreach ($mcompuestas as $key => $value) {
	        $sql='INSERT INTO mcompuestastbl SET
							muestreoaguaidfk=:id,
							hora=:hora,
							flujo=:flujo,
							volumen=:volumen,
							observaciones=:observaciones,
							caracteristicas=:caracteristicas';
	        $s=$pdo->prepare($sql);
	        $s->bindValue(':id', $muestreoid);
	        $s->bindValue(':hora', (isset($value["hora"])) ? $value["hora"] : '');
	        $s->bindValue(':flujo', (isset($value["hora"])) ? $value["flujo"] : '');
	        $s->bindValue(':volumen', (isset($value["hora"])) ? $value["volumen"] : '');
	        $s->bindValue(':observaciones', $value["observaciones"]);
	        $s->bindValue(':caracteristicas', $value["caracteristicas"]);
	        $s->execute();
	        $mcompuesta = $pdo->lastInsertid();

	        $sql='INSERT INTO laboratoriotbl SET
							mcompuestaidfk=:id,
							fecharecepcion=:fecharecepcion,
							horarecepcion=:horarecepcion';
	        $s=$pdo->prepare($sql);
	        $s->bindValue(':id', $mcompuesta);
	        $s->bindValue(':fecharecepcion', (isset($value["hora"])) ? $value["fechalab"] : '');
	        $s->bindValue(':horarecepcion', (isset($value["hora"])) ? $value["horalab"] : '');
	        $s->execute();
      	}
	}catch (PDOException $e){
		$pdo->rollback();
		$mensaje='Hubo un error al tratar de insertar GyA y coliformes. Favor de intentar nuevamente.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
}