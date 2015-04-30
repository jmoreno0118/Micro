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
	if (isset($_POST['accion']) and $_POST['accion']=='guardar nuevos parametros')
	{
		/*$mensaje='Error Forzado 3.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();*/

		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
		try   
		{
			$sql='SELECT id FROM muestreosaguatbl WHERE generalaguaidfk = :id';
			$s=$pdo->prepare($sql); 
			$s->bindValue(':id',$_POST['id']);
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
			$s->bindValue(':ssedimentables', $_POST['ssedimentables']);
			$s->bindValue(':ssuspendidos', $_POST['ssuspendidos']);
			$s->bindValue(':dbo', $_POST['dbo']);
			$s->bindValue(':nkjedahl', $_POST['nkjedahl']);
			$s->bindValue(':nitritos', $_POST['nitritos']);
			$s->bindValue(':nitratos', $_POST['nitratos']);
			$s->bindValue(':nitrogeno', $_POST['nitrogeno']);
			$s->bindValue(':fosforo', $_POST['fosforo']);
			$s->bindValue(':arsenico', $_POST['arsenico']);
			$s->bindValue(':cadmio', $_POST['cadmio']);
			$s->bindValue(':cianuros', $_POST['cianuros']);
			$s->bindValue(':cobre', $_POST['cobre']);
			$s->bindValue(':cromo', $_POST['cromo']);
			$s->bindValue(':mercurio', $_POST['mercurio']);
			$s->bindValue(':niquel', $_POST['niquel']);
			$s->bindValue(':plomo', $_POST['plomo']);
			$s->bindValue(':zinc', $_POST['zinc']);
			$s->bindValue(':hdehelminto', $_POST['hdehelminto']);
			$s->bindValue(':fechareporte', $_POST['fechareporte']);
			$s->execute();
			$id=$pdo->lastInsertid();

			insertParametros2($_POST["parametros"], $id);

			insertAdicionales($_POST["adicionales"], $id);

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
			$s->bindValue(':id', $_POST['idparametro']);
			$s->bindValue(':ssedimentables', $_POST['ssedimentables']);
			$s->bindValue(':ssuspendidos', $_POST['ssuspendidos']);
			$s->bindValue(':dbo', $_POST['dbo']);
			$s->bindValue(':nkjedahl', $_POST['nkjedahl']);
			$s->bindValue(':nitritos', $_POST['nitritos']);
			$s->bindValue(':nitratos', $_POST['nitratos']);
			$s->bindValue(':nitrogeno', $_POST['nitrogeno']);
			$s->bindValue(':fosforo', $_POST['fosforo']);
			$s->bindValue(':arsenico', $_POST['arsenico']);
			$s->bindValue(':cadmio', $_POST['cadmio']);
			$s->bindValue(':cianuros', $_POST['cianuros']);
			$s->bindValue(':cobre', $_POST['cobre']);
			$s->bindValue(':cromo', $_POST['cromo']);
			$s->bindValue(':mercurio', $_POST['mercurio']);
			$s->bindValue(':niquel', $_POST['niquel']);
			$s->bindValue(':plomo', $_POST['plomo']);
			$s->bindValue(':zinc', $_POST['zinc']);
			$s->bindValue(':hdehelminto', $_POST['hdehelminto']);
			$s->bindValue(':fechareporte', $_POST['fechareporte']);
			$s->execute();

			$sql="DELETE FROM parametros2tbl
			WHERE parametroidfk = :id";
			$s=$pdo->prepare($sql);
			$s->bindValue(':id',$_POST['idparametro']);
			$s->execute();

			insertParametros2($_POST["parametros"], $_POST['idparametro']);

			$sql="DELETE FROM adicionalestbl
			WHERE parametroidfk = :id";
			$s=$pdo->prepare($sql);
			$s->bindValue(':id',$_POST['idparametro']);
			$s->execute();

			insertAdicionales($_POST["adicionales"], $_POST['idparametro']);
			
			$pdo->commit();
		}catch (PDOException $e){
			$pdo->rollback();
			$mensaje='Hubo un error al tratar de insertar los parametros. Favor de intentar nuevamente.';
			include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
			exit();
		}
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/generales');
		exit();
	}

/**************************************************************************************************/
/* Acción default */
/**************************************************************************************************/
	$id = $_SESSION['parametros']['id'];
	$cantidad = $_SESSION['parametros']['cantidad'];
	$valores = $_SESSION['parametros']['valores'];
	$parametros = $_SESSION['parametros']['parametros'];
	$adicionales = $_SESSION['parametros']['adicionales'];
	$idparametros = $_SESSION['parametros']['idparametros'];
	$titulopagina = $_SESSION['parametros']['titulopagina'];
	$pestanapag = $_SESSION['parametros']['pestanapag'];
	$boton = $_SESSION['parametros']['boton'];
	$regreso = $_SESSION['parametros']['regreso'];
	$pestanapag = $_SESSION['parametros']['pestanapag'];
	$titulopagina = $_SESSION['parametros']['titulopagina'];
	unset($_SESSION['parametros']);
	include 'formacapturarparametros.html.php';
	exit();

/**************************************************************************************************/
/* Función para insertar adicionales */
/**************************************************************************************************/
function insertAdicionales($adicionales, $idparametro){
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
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
		$pdo->rollback();
		$mensaje='Hubo un error al tratar de insertar los adicionales. Favor de intentar nuevamente.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
}

/**************************************************************************************************/
/* Función para insertar GyA y coliformes */
/**************************************************************************************************/
function insertParametros2($parametros, $idparametro){
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
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
		$pdo->rollback();
		$mensaje='Hubo un error al tratar de insertar GyA y coliformes. Favor de intentar nuevamente.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
}