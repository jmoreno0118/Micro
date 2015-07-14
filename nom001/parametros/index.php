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
if (isset($_POST['accion']) and $_POST['accion']=='no guardar parametros' )
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

	fijarAccionUrl('Siralab');

    $valores = (isset($_POST['valores'])) ? json_decode($_POST['valores'], TRUE) : "";
    $mcompuesta = (isset($_POST['mcompuesta'])) ? json_decode($_POST['mcompuesta'], TRUE) : "";
	formularioSiralab($_POST['muestreoid'], $valores, $mcompuesta, $_POST['cantidad'], 0);
}

/**************************************************************************************************/
/* Guardar nuevos parametros de una medicion de una orden de trabajo */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='volver')
{
	fijarAccionUrl('volver');

	$cantidad = intval($_POST['cantidad']);

    if($cantidad === 1){
			if($_POST['accion'] == 'volver' AND isset($_POST['coms'])){
					include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
					try
					{
						$sql='SELECT * FROM generalesaguatbl
							 INNER JOIN muestreosaguatbl ON generalesaguatbl.id=muestreosaguatbl.generalaguaidfk
							 WHERE generalesaguatbl.id = :id';
						$s=$pdo->prepare($sql); 
						$s->bindValue(':id',$_POST['id']);
						$s->execute();
						$linea = $s->fetch();

						$sql='SELECT descargaen, uso FROM nom01maximostbl WHERE id=:id';
						$s=$pdo->prepare($sql); 
						$s->bindValue(':id', $linea["nom01maximosidfk"]);
						$s->execute();
						$nom01maximos = $s->fetch();
					}
					catch (PDOException $e)
					{
						$mensaje='Hubo un error extrayendo la información de reconocimiento inicial.';
						include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
						exit();
					}

					$signatarios = getSignatarios();
					$muestreadores = getMuestradores();
					$descargaen = getMaximos();
					$egiro = getEGiro($linea["ordenaguaidfk"]);
					$valores = array("empresagiro" => getEGiro($linea["ordenaguaidfk"]),
						           "descargaen" => $nom01maximos["descargaen"],
						           "uso" => $nom01maximos["uso"],
						           "numedicion" => $linea["numedicion"],
						           "lugarmuestreo" => $linea["lugarmuestreo"],
						           "descriproceso" => $linea["descriproceso"],
						           "tipomediciones" => $linea["tipomediciones"],
						           //"proposito" => $linea["proposito"],
						           "materiasusadas" => $linea["materiasusadas"],
						           "tratamiento" => $linea["tratamiento"],
						           "Caracdescarga" => $linea["Caracdescarga"],
						           "receptor" => $linea["receptor"],
						           "estrategia" => $linea["estrategia"],
						           "numuestras" => $linea["numuestras"],
						           "observaciones" => $linea["observaciones"],
						           "fechamuestreo" => $linea["fechamuestreo"],
						           "fechamuestreofin" => $linea["fechamuestreofin"],
						           "identificacion" => $linea["identificacion"],
						           "temperatura" => $linea["temperatura"],
						           "caltermometro" => $linea["caltermometro"],
						           "pH" => $linea["pH"],
						           "conductividad" => $linea["conductividad"],
						           "nombresignatario" => getNombreSignatario($linea["ordenaguaidfk"]),
						           "signatario" => getSignatario($linea["ordenaguaidfk"]),
						           "responsable" => getResponsables($linea["ordenaguaidfk"], $id),
						           "mflotante" => $linea["mflotante"],
						           "olor" => $linea["olor"],
						           "color" => $linea["color"],
						           "turbiedad" => $linea["turbiedad"],
						           "GyAvisual" => $linea["GyAvisual"],
						           "burbujas" => $linea["burbujas"]);
					$pestanapag='Editar medicion';
					$titulopagina='Editar medicion';
					$accion='';
					$boton = 'salvar';
					$regreso = 1;
					include 'formacapturarmeds.html.php';
					exit();
			} //cierre de if($_POST['accion'] == 'volvercoms')

			if(isset($_POST['regreso']) AND $_POST['regreso'] === '2'){
				formularioParametros($_POST['id'], intval($_POST['cantidad']), $_POST['idparametro'], json_decode($_POST['valores'], TRUE), json_decode($_POST['parametros'], TRUE), json_decode($_POST['adicionales'], TRUE), 1);
			}
			formularioParametros($_POST['id'], $cantidad, "", "", "", "", 1);
    }
    else
    { //cierre de if($cantidad === 1)
			if(isset($_POST['regreso']) AND $_POST['regreso'] === '2'){
				$mcompuestas = json_decode($_POST['mcompuestas'], TRUE);
				formularioMediciones($id, $cantidad, $mcompuestas, 1);
			}
			else
			{
				formularioMediciones($id, $cantidad, "", 1);
			}
    }
    exit();
	//formularioMediciones($_POST['id'], $_POST['cantidad'], '');
}


/**************************************************************************************************/
/* Acción default */
/**************************************************************************************************/
$id = $_SESSION['parametros']['id'];
$muestreoid = $_SESSION['parametros']['muestreoid'];
$cantidad = $_SESSION['parametros']['cantidad'];
$valores = $_SESSION['parametros']['valores'];
$metodos = $_SESSION['parametros']['metodos'];
$parametros = $_SESSION['parametros']['parametros'];
$adicionales = $_SESSION['parametros']['adicionales'];
$idparametro = $_SESSION['parametros']['idparametro'];
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
	global $pdo;
	try{
		foreach ($adicionales as $key => $value) {
			if($value["nombre"] != "" && $value["unidades"] != "" && $value["resultado"] != ""){
				$sql='INSERT INTO adicionalestbl SET
					parametroidfk=:id,
					nombre=:nombre,
					unidades=:unidades,
					resultado=:resultado,
					metodo=:metodo';
				$s=$pdo->prepare($sql);
				$s->bindValue(':id', $idparametro);
				$s->bindValue(':nombre', $value["nombre"]);
				$s->bindValue(':unidades', $value["unidades"]);
				$s->bindValue(':resultado', $value["resultado"]);
				$s->bindValue(':metodo', $value["metodo"]);
				$s->execute();
			}
		}
	}
	catch (PDOException $e)
	{
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
	catch (PDOException $e)
	{
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
		$s->bindValue(':id', $post['muestreoid']);
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

		$sql='INSERT INTO metodosparametrostbl SET
			parametrosidfk=:id,
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
			GyA=:GyA,
			coliformes=:coliformes';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id', $id);
		$s->bindValue(':ssedimentables', isset($post['metodossedimentables'])? $post['metodossedimentables'] : '');
		$s->bindValue(':ssuspendidos', isset($post['metodossuspendidos'])? $post['metodossuspendidos'] : '');
		$s->bindValue(':dbo', isset($post['metododbo'])? $post['metododbo'] : '');
		$s->bindValue(':nkjedahl', isset($post['metodonkjedahl'])? $post['metodonkjedahl'] : '');
		$s->bindValue(':nitritos', isset($post['metodonitritos'])? $post['metodonitritos'] : '');
		$s->bindValue(':nitratos', isset($post['metodonitratos'])? $post['metodonitratos'] : '');
		$s->bindValue(':nitrogeno', isset($post['metodonitrogeno'])? $post['metodonitrogeno'] : '');
		$s->bindValue(':fosforo', isset($post['metodofosforo'])? $post['metodofosforo'] : '');
		$s->bindValue(':arsenico', isset($post['metodoarsenico'])? $post['metodoarsenico'] : '');
		$s->bindValue(':cadmio', isset($post['metodocadmio'])? $post['metodocadmio'] : '');
		$s->bindValue(':cianuros', isset($post['metodocianuros'])? $post['metodocianuros'] : '');
		$s->bindValue(':cobre', isset($post['metodocobre'])? $post['metodocobre'] : '');
		$s->bindValue(':cromo', isset($post['metodocromo'])? $post['metodocromo'] : '');
		$s->bindValue(':mercurio', isset($post['metodomercurio'])? $post['metodomercurio'] : '');
		$s->bindValue(':niquel', isset($post['metodoniquel'])? $post['metodoniquel'] : '');
		$s->bindValue(':plomo', isset($post['metodoplomo'])? $post['metodoplomo'] : '');
		$s->bindValue(':zinc', isset($post['metodozinc'])? $post['metodozinc'] : '');
		$s->bindValue(':hdehelminto', isset($post['metodohdehelminto'])? $post['metodohdehelminto'] : '');
		$s->bindValue(':GyA', isset($post['metodoGyA'])? $post['metodoGyA'] : '');
		$s->bindValue(':coliformes', isset($post['metodocoliformes'])? $post['metodocoliformes'] : '');

		$s->execute();

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

		$sql='UPDATE metodosparametrostbl SET
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
			GyA=:GyA,
			coliformes=:coliformes
			WHERE parametrosidfk=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id', $post['idparametro']);
		$s->bindValue(':ssedimentables', isset($post['metodossedimentables'])? $post['metodossedimentables'] : '');
		$s->bindValue(':ssuspendidos', isset($post['metodossuspendidos'])? $post['metodossuspendidos'] : '');
		$s->bindValue(':dbo', isset($post['metododbo'])? $post['metododbo'] : '');
		$s->bindValue(':nkjedahl', isset($post['metodonkjedahl'])? $post['metodonkjedahl'] : '');
		$s->bindValue(':nitritos', isset($post['metodonitritos'])? $post['metodonitritos'] : '');
		$s->bindValue(':nitratos', isset($post['metodonitratos'])? $post['metodonitratos'] : '');
		$s->bindValue(':nitrogeno', isset($post['metodonitrogeno'])? $post['metodonitrogeno'] : '');
		$s->bindValue(':fosforo', isset($post['metodofosforo'])? $post['metodofosforo'] : '');
		$s->bindValue(':arsenico', isset($post['metodoarsenico'])? $post['metodoarsenico'] : '');
		$s->bindValue(':cadmio', isset($post['metodocadmio'])? $post['metodocadmio'] : '');
		$s->bindValue(':cianuros', isset($post['metodocianuros'])? $post['metodocianuros'] : '');
		$s->bindValue(':cobre', isset($post['metodocobre'])? $post['metodocobre'] : '');
		$s->bindValue(':cromo', isset($post['metodocromo'])? $post['metodocromo'] : '');
		$s->bindValue(':mercurio', isset($post['metodomercurio'])? $post['metodomercurio'] : '');
		$s->bindValue(':niquel', isset($post['metodoniquel'])? $post['metodoniquel'] : '');
		$s->bindValue(':plomo', isset($post['metodoplomo'])? $post['metodoplomo'] : '');
		$s->bindValue(':zinc', isset($post['metodozinc'])? $post['metodozinc'] : '');
		$s->bindValue(':hdehelminto', isset($post['metodohdehelminto'])? $post['metodohdehelminto'] : '');
		$s->bindValue(':GyA', isset($post['metodoGyA'])? $post['metodoGyA'] : '');
		$s->bindValue(':coliformes', isset($post['metodocoliformes'])? $post['metodocoliformes'] : '');
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
	}
	catch (PDOException $e)
	{
		$pdo->rollback();
		$mensaje='Hubo un error al tratar de insertar los parametros. Favor de intentar nuevamente.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
}