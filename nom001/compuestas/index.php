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
	if(isset($_POST['accion']) and $_POST['accion']=='guardar')
	{
		/*$mensaje='Error Forzado 2.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();*/

		$_SESSION['accion'] = 'guardar';
	    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
	    $host     = $_SERVER['HTTP_HOST'];
	    $script   = $_SERVER['SCRIPT_NAME'];
	    $params   = $_SERVER['QUERY_STRING'];
	    $currentUrl = $protocol . '://' . $host . $script . '?' . $params;
	    $_SESSION['url'] = $currentUrl;

		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
		if(isset($_POST['regreso']) AND $_POST['regreso'] === '2'){
			formularioParametros($_POST['id'], intval($_POST['cantidad']), $_POST['idparametro'], json_decode($_POST['valores'],TRUE), json_decode($_POST['parametros'],TRUE), json_decode($_POST['adicionales'],TRUE), 1, $_POST['accionparam']);
		}else{
			insertMediciones($_POST["mcompuestas"], $_POST['id']);

			formularioParametros($_POST['id'], $_POST['cantidad'], "", "", "", "", 1);
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

		$_SESSION['accion'] = 'salvar';
	    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
	    $host     = $_SERVER['HTTP_HOST'];
	    $script   = $_SERVER['SCRIPT_NAME'];
	    $params   = $_SERVER['QUERY_STRING'];
	    $currentUrl = $protocol . '://' . $host . $script . '?' . $params;
	    $_SESSION['url'] = $currentUrl;

		$id = $_POST['id'];
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';

		if(isset($_POST['regreso']) AND $_POST['regreso'] === '2'){
			formularioParametros($_POST['id'], intval($_POST['cantidad']), $_POST['idparametro'], json_decode($_POST['valores'],TRUE), json_decode($_POST['parametros'],TRUE), json_decode($_POST['adicionales'],TRUE), 1, $_POST['accionparam']);
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
	if(isset($_POST['accion']) and $_POST['accion']=='volver a mediciones')
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
		//var_dump($mcompuestas);
		//exit();
		foreach ($mcompuestas as $key => $value) {
	        $sql='INSERT INTO mcompuestastbl SET
							muestreoaguaidfk=:id,
							hora=:hora,
							flujo=:flujo,
							volumen=:volumen,
							observaciones=:observaciones,
							caracteristicas=:caracteristicas,
							identificacion=:identificacion,
							fecharecepcion=:fecharecepcion,
							horarecepcion=:horarecepcion';
	        $s=$pdo->prepare($sql);
	        $s->bindValue(':id', $muestreoid);
	        $s->bindValue(':hora', (isset($value["hora"])) ? $value["hora"] : '');
	        $s->bindValue(':flujo', (isset($value["flujo"])) ? $value["flujo"] : '');
	        $s->bindValue(':volumen', (isset($value["volumen"])) ? $value["volumen"] : 0);
	        $s->bindValue(':observaciones', $value["observaciones"]);
	        $s->bindValue(':caracteristicas', $value["caracteristicas"]);
	        $s->bindValue(':identificacion', $value["identificacion"]);
	       	$s->bindValue(':fecharecepcion', (isset($value["fechalab"])) ? $value["fechalab"] : '0000-00-00');
	        $s->bindValue(':horarecepcion', (isset($value["horalab"])) ? $value["horalab"] : '00:00');
	        $s->execute();
      	}
	}catch (PDOException $e){
		$pdo->rollback();
		$mensaje='Hubo un error al tratar de insertar GyA y coliformes. Favor de intentar nuevamente.'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
}