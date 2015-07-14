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
/* Borrar documento */
/**************************************************************************************************/
if (isset($_POST['accion']) and $_POST['accion']=='borraplano')
{
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	$sql='SELECT nombrearchivado FROM documentos001tbl WHERE id=:id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id',$_POST['iddoc']);
	$s->execute();
	$nombre=$s->fetch();
	$arch=$_SERVER['DOCUMENT_ROOT'].'/reportes/nom001/documentos/'.$nombre['nombrearchivado'];

	if (file_exists($arch))
	{
		try
		{	
			$sql='DELETE FROM documentos001tbl WHERE id=:id';
			$s=$pdo->prepare($sql);
			$s->bindValue(':id', $_POST['iddoc']);
			$s->execute();
		}
		catch (PDOException $e)
		{
		   $mensaje='Lo sentimos, no se pudo borrar el plano.  Favor de intentar nuevamente';
		   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		   exit();
		}   
	   unlink($arch);
	}
	else
	{
	   $mensaje='Lo sentimos, no se encontro el archivo que se desea borrar.  Favor de avisar a sistemas de este error';
	   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	   exit();
	}

	$_SESSION['post'] = $_POST;
	header('Location: .');
	exit();
}

/**************************************************************************************************/
/* Subir documento */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='subir')
{
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	// verifica que el archivo se haya subido
	if (!is_uploaded_file($_FILES['archivo']['tmp_name']))
	{
		$mensaje='Hubo un error tratando de subir el archivo.  Favor de revisar la conexión a internet y que el archivo sea menor a 2Mb e intenta de nevo.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}

	if($_POST['tipo'] === 'Croquis')
	{
		$sql='SELECT * FROM documentos001tbl WHERE generalaguaidfk=:id AND tipo = "Croquis"';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id', $_POST['id']);
		$s->execute();
		if($s->fetch())
		{
			$mensaje='Ya existe croquis de esta medición';
			$errorlink = 'http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/generales';
			$errornav = 'Volver a mediciones';
			include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
			exit();
		}
	}

	// se verifica que el nombre del archivo solo contenga caracteres validos
	$nombrearch=preg_replace('/[^A-Z0-9._-]/i','_',$_FILES['archivo']['name']);
	$partes=pathinfo($nombrearch);
	$extension=$partes['extension'];
	$nombrearchivar=$_POST['ot'].'_'.$_POST['id'].'_'.$_POST['numedicion'].'_'.$_POST['hora'].'.'.$extension;
	$nombre=$partes['filename'];

	//se guarda el archivo en la carpeta deseada
	$semovio=move_uploaded_file($_FILES['archivo']['tmp_name'],
	$_SERVER['DOCUMENT_ROOT'].'/reportes/nom001/documentos/'.$nombrearchivar);
	if (!$semovio)
	{
		$mensaje='Vuelva a intentar de nuevo.  Hubo un error tratando de guardar el archivo';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	// Colocar los permisos de lectura al archivo
	chmod ($_SERVER['DOCUMENT_ROOT'].'/reportes/nom001/documentos/'.$nombrearchivar, 0777);
	guardadocumento($nombrearchivar, $nombre, $_POST['tipo'], $_POST['id']);

	$_SESSION['post'] = $_POST;
	header('Location: .');
	exit();
}

/**************************************************************************************************/
/* Ver mediciones de una orden de trabajo */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='volvermed')
{
	$_SESSION['ot'] = $_POST['ot'];
	$request = str_replace('?', '', $_SERVER['REQUEST_URI']);
	$request = str_replace('documentos/', '', $request);
  	header('Location: http://'.$_SERVER['HTTP_HOST'].$request.'generales');
    exit();
}

/**************************************************************************************************/
/* Acción default */
/**************************************************************************************************/
if(isset($_POST['id']) AND isset($_POST['numedicion']) AND isset($_POST['ot'])){
	$post = $_POST;
}
else
{
	$post = $_SESSION['post'];
	if(isset($post))
	{
		//$_SESSION['post'] = '';
		//unset($_SESSION['post']);
	}
}
include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
$ot = $post['ot'];
$numedicion = $post['numedicion'];
$id = $post['id'];
$nombreot = getOT();
$documentos = datostabla();
include 'formacroquis.html.php';
exit();

/**************************************************************************************************/
/* Obtener el nombre de la orden */
/**************************************************************************************************/
function getOT()
{
	global $pdo, $ot; 
	try
	{
		$sql='SELECT ot
		FROM ordenestbl
		WHERE id = :id';
		$s=$pdo->prepare($sql); 
		$s->bindValue(':id',$ot);
		$s->execute();
		$nombreot = $s->fetch();
	}
	catch (PDOException $e)
	{
		$mensaje='Hubo un error extrayendo la información de adicionales.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	return $nombreot;
}

/**************************************************************************************************/
/* Obtener la lista de documentos */
/**************************************************************************************************/
function datostabla()
{
	global $id, $pdo; 
	try
	{
		$sql='SELECT id, nombre, nombrearchivado, tipo FROM documentos001tbl
		WHERE generalaguaidfk=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id',$id);
		$s->execute();
	}
	catch (PDOException $e)
	{
		$mensaje='Hubo un error al tratar de mostrar los planos existentes.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}

	if($docs = $s->fetchAll())
	{
		foreach ($docs as $linea)
		{
			$documentos[$linea['tipo']][] =  array('id' => $linea['id'],
													'nombre' => $linea['nombre'],
													'nombrearchivado' => $linea['nombrearchivado'],
													'tipo' => $linea['tipo'],
													'liga' => $linea['nombrearchivado']);
		}
		$sort = array('Croquis','ASC-F-1','ASC-F-2','ASC-F-4','LGM-AAM-001','APC-F-1A','OMW-F-17','OMW-F-1',
	     				'OMW-F-2','AAS-F-24','OCC-F-58','AIR-F-11','AEI-F-15','OMW-F-15','OMW-F-4','OMW-F-5',
	     				'OMW-F-6','OMW-F-16','OMW-F-9','OMW-F-20','OCC-F-25','Calibración termometro','A1',
	     				'A2','A3','A3.1','A4','A4.1','A5','A5.1','A2-B','A3-B','A3.1-B','A4-B','A4.1-B','A5-B',
	     				'A5.1-B');
		return sortArrayP($documentos, $sort);
	}
	return '';
}

/**************************************************************************************************/
/* Ordenar el arrgeglo de documentos */
/**************************************************************************************************/
function sortArrayP($array, $sort)
{
	for ($i=0; $i < count($sort); $i++)
	{ 
		if(isset($array[$sort[$i]]))
		{
			foreach ($array[$sort[$i]] as $value)
			{
				$newarray[] = $value;
			}
		}
	}
	return (isset($newarray)) ? $newarray : '';
}

/**************************************************************************************************/
/* Guanrdar documento en la base de datos */
/**************************************************************************************************/
function guardadocumento($nombrearchivar='',$nombre='',$tipo='',$id='')
{
  global $pdo;
  try
  {
	  $sql='INSERT INTO documentos001tbl SET
			nombre=:nombre,
			nombrearchivado=:nombrearchivar,
			tipo=:tipo,
			generalaguaidfk=:id';
	  $s=$pdo->prepare($sql);
	  $s->bindValue(':nombre',$nombre);
	  $s->bindValue(':nombrearchivar',$nombrearchivar);
	  $s->bindValue(':tipo',$tipo);
	  $s->bindValue(':id',$id);
	  $s->execute();
  }
  catch(PDOException $e)
  {
    $mensaje='Hubo un error al tratar de guardar la informacion del plano.  intentar nuevamente y avisar de este error a sistemas.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();	
  }
}