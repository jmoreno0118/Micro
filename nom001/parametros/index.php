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
/* Guardar nuevos parametros de una medicion de una orden de trabajo */
/**************************************************************************************************/
	if(isset($_POST['accion']) and $_POST['accion']=='guardar parametros')
	{
		/*$mensaje='Error Forzado 3.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();*/

		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
		guardarParams($_POST);

		header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/generales');
		exit();
	}

/**************************************************************************************************/
/* Formulario de parametros de una medicion una orden de trabajo */
/**************************************************************************************************/
	if (isset($_POST['accion']) and $_POST['accion']=='no guardar parametros')
	{
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/generales');
		exit();
	}

/**************************************************************************************************/
/* Formulario de parametros de una medicion una orden de trabajo */
/**************************************************************************************************/
	if (isset($_POST['accion']) and $_POST['accion']=='salvar parametros')
	{
		/*$mensaje='Error Forzado 3.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();*/

		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
		editarParams($_POST);
		
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/generales');
		exit();
	}

/**************************************************************************************************/
/* Formulario de siralab de una medicion una orden de trabajo */
/**************************************************************************************************/
	if (isset($_POST['accion']) and $_POST['accion']=='Siralab')
	{
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
		if($_POST['boton']=='guardar parametros'){
			/*$mensaje='Error Forzado 3.';
			include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
			exit();*/
			guardarParams($_POST);
		}elseif($_POST['boton']=='salvar parametros'){
			/*$mensaje='Error Forzado 3.';
			include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
			exit();*/
			editarParams($_POST);
		}

		$_SESSION['accion'] = 'Siralab';
	    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
	    $host     = $_SERVER['HTTP_HOST'];
	    $script   = $_SERVER['SCRIPT_NAME'];
	    $params   = $_SERVER['QUERY_STRING'];
	    $currentUrl = $protocol . '://' . $host . $script . '?' . $params;
	    $_SESSION['url'] = $currentUrl;

	    $valores = (isset($_POST['valores'])) ? json_decode($_POST['valores'], TRUE) : "";
		formularioSiralab($_POST['id'], $valores, 0);
	}

/**************************************************************************************************/
/* Guardar nuevos parametros de una medicion de una orden de trabajo */
/**************************************************************************************************/
	if(isset($_POST['accion']) and $_POST['accion']=='volver')
	{
		formularioMediciones($_POST['id'], $_POST['cantidad'], '');
	}


/**************************************************************************************************/
/* Acción default */
/**************************************************************************************************/
	//var_dump($_SESSION['parametros']);
	//if(!isset($_SESSION['parametros'])){
		$id = $_SESSION['parametros']['id'];
		$cantidad = $_SESSION['parametros']['cantidad'];
		$valores = $_SESSION['parametros']['valores'];
		$parametros = $_SESSION['parametros']['parametros'];
		$adicionales = $_SESSION['parametros']['adicionales'];
		$idparametro = $_SESSION['parametros']['idparametro'];
		$boton = $_SESSION['parametros']['boton'];
		$regreso = $_SESSION['parametros']['regreso'];
		$pestanapag = $_SESSION['parametros']['pestanapag'];
		$titulopagina = $_SESSION['parametros']['titulopagina'];
		unset($_SESSION['parametros']);
	//}
	include 'formacapturarparametros.html.php';
	exit();

/**************************************************************************************************/
/* Función para insertar adicionales */
/**************************************************************************************************/
function insertAdicionales($adicionales, $idparametro){
	global $pdo;
	try{
		foreach ($adicionales as $key => $value) {
			if($value["nombre"] != "" && $value["unidades"] != "" && $value["resultado"] != ""){
				$sql='INSERT INTO adicionalestbl SET
					parametroidfk=:id,
					nombre=:nombre,
					unidades=:unidades,
					resultado=:resultado';
				$s=$pdo->prepare($sql);
				$s->bindValue(':id', $idparametro);
				$s->bindValue(':nombre', $value["nombre"]);
				$s->bindValue(':unidades', $value["unidades"]);
				$s->bindValue(':resultado', $value["resultado"]);
				$s->execute();
			}
		}
	}catch (PDOException $e){
		$mensaje='Hubo un error al tratar de insertar los adicionales. Favor de intentar nuevamente.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
}

/**************************************************************************************************/
/* Función para insertar GyA y coliformes */
/**************************************************************************************************/
function insertParametros2($parametros, $idparametro){
	global $pdo;
	try{
		foreach ($parametros as $key => $value) {
			if($value["GyA"] != "" && $value["coliformes"] != ""){
				$sql='INSERT INTO parametros2tbl SET
					parametroidfk=:id,
					GyA=:GyA,
					coliformes=:coliformes';
				$s=$pdo->prepare($sql);
				$s->bindValue(':id', $idparametro);
				$s->bindValue(':GyA', $value["GyA"]);
				$s->bindValue(':coliformes', $value["coliformes"]);
				$s->execute();
			}
		}
	}catch (PDOException $e){
		$mensaje='Hubo un error al tratar de insertar GyA y coliformes. Favor de intentar nuevamente.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
}

/**************************************************************************************************/
/* Función para guardar los parametros */
/**************************************************************************************************/
function guardarParams($post){
	global $pdo;
	try   
	{
		$sql='SELECT id FROM muestreosaguatbl WHERE generalaguaidfk = :id';
		$s=$pdo->prepare($sql); 
		$s->bindValue(':id',$post['id']);
		$s->execute();
		$idmuestreo = $s->fetch();
	}
	catch (PDOException $e)
	{
		$mensaje='Hubo un error extrayendo la información de parametros.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	try
	{
		$pdo->beginTransaction();

		$sql='INSERT INTO parametrostbl SET
		muestreoaguaidfk=:id,
		ssedimentables=:ssedimentables,
		ssuspendidos=:ssuspendidos,
		dbo=:dbo,
		nkjedahl=:nkjedahl,
		nitritos=:nitritos,
		nitratos=:nitratos,
		nitrogeno=:nitrogeno,
		fosforo=:fosforo,
		arsenico=:arsenico,
		cadmio=:cadmio,
		cianuros=:cianuros,
		cobre=:cobre,
		cromo=:cromo,
		mercurio=:mercurio,
		niquel=:niquel,
		plomo=:plomo,
		zinc=:zinc,
		hdehelminto=:hdehelminto,
		fechareporte=:fechareporte';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id', $idmuestreo['id']);
		$s->bindValue(':ssedimentables', $post['ssedimentables']);
		$s->bindValue(':ssuspendidos', $post['ssuspendidos']);
		$s->bindValue(':dbo', $post['dbo']);
		$s->bindValue(':nkjedahl', $post['nkjedahl']);
		$s->bindValue(':nitritos', $post['nitritos']);
		$s->bindValue(':nitratos', $post['nitratos']);
		$s->bindValue(':nitrogeno', $post['nitrogeno']);
		$s->bindValue(':fosforo', $post['fosforo']);
		$s->bindValue(':arsenico', $post['arsenico']);
		$s->bindValue(':cadmio', $post['cadmio']);
		$s->bindValue(':cianuros', $post['cianuros']);
		$s->bindValue(':cobre', $post['cobre']);
		$s->bindValue(':cromo', $post['cromo']);
		$s->bindValue(':mercurio', $post['mercurio']);
		$s->bindValue(':niquel', $post['niquel']);
		$s->bindValue(':plomo', $post['plomo']);
		$s->bindValue(':zinc', $post['zinc']);
		$s->bindValue(':hdehelminto', $post['hdehelminto']);
		$s->bindValue(':fechareporte', $post['fechareporte']);
		$s->execute();
		$id=$pdo->lastInsertid();

		insertParametros2($post["parametros"], $id);

		insertAdicionales($post["adicionales"], $id);

		$pdo->commit();
	}
	catch (PDOException $e)
	{
		$pdo->rollback();
		$mensaje='Hubo un error al tratar de insertar el reconocimiento. Favor de intentar nuevamente.'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
}

/**************************************************************************************************/
/* Función para salvar los parametros */
/**************************************************************************************************/
function editarParams($post){
	global $pdo;
	try
	{
		$pdo->beginTransaction();

		$sql='UPDATE parametrostbl SET
			ssedimentables=:ssedimentables,
			ssuspendidos=:ssuspendidos,
			dbo=:dbo,
			nkjedahl=:nkjedahl,
			nitritos=:nitritos,
			nitratos=:nitratos,
			nitrogeno=:nitrogeno,
			fosforo=:fosforo,
			arsenico=:arsenico,
			cadmio=:cadmio,
			cianuros=:cianuros,
			cobre=:cobre,
			cromo=:cromo,
			mercurio=:mercurio,
			niquel=:niquel,
			plomo=:plomo,
			zinc=:zinc,
			hdehelminto=:hdehelminto,
			fechareporte=:fechareporte
			WHERE id = :id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id', $post['idparametro']);
		$s->bindValue(':ssedimentables', $post['ssedimentables']);
		$s->bindValue(':ssuspendidos', $post['ssuspendidos']);
		$s->bindValue(':dbo', $post['dbo']);
		$s->bindValue(':nkjedahl', $post['nkjedahl']);
		$s->bindValue(':nitritos', $post['nitritos']);
		$s->bindValue(':nitratos', $post['nitratos']);
		$s->bindValue(':nitrogeno', $post['nitrogeno']);
		$s->bindValue(':fosforo', $post['fosforo']);
		$s->bindValue(':arsenico', $post['arsenico']);
		$s->bindValue(':cadmio', $post['cadmio']);
		$s->bindValue(':cianuros', $post['cianuros']);
		$s->bindValue(':cobre', $post['cobre']);
		$s->bindValue(':cromo', $post['cromo']);
		$s->bindValue(':mercurio', $post['mercurio']);
		$s->bindValue(':niquel', $post['niquel']);
		$s->bindValue(':plomo', $post['plomo']);
		$s->bindValue(':zinc', $post['zinc']);
		$s->bindValue(':hdehelminto', $post['hdehelminto']);
		$s->bindValue(':fechareporte', $post['fechareporte']);
		$s->execute();

		$sql="DELETE FROM parametros2tbl
			WHERE parametroidfk = :id";
		$s=$pdo->prepare($sql);
		$s->bindValue(':id',$post['idparametro']);
		$s->execute();

		insertParametros2($post["parametros"], $post['idparametro']);

		$sql="DELETE FROM adicionalestbl
			WHERE parametroidfk = :id";
		$s=$pdo->prepare($sql);
		$s->bindValue(':id',$post['idparametro']);
		$s->execute();

		insertAdicionales($post["adicionales"], $post['idparametro']);
		
		$pdo->commit();
	}catch (PDOException $e){
		$pdo->rollback();
		$mensaje='Hubo un error al tratar de insertar los parametros. Favor de intentar nuevamente.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
}