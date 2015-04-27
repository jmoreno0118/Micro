<?php
 //********** iluminacion **********
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
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
 
/* ******************************************************************
**  Borra el plano seleccionado                                    **
****************************************************************** */
if (isset($_POST['accion']) and $_POST['accion']=='borraplano')
{
 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
 $sql='SELECT nombrearchivado FROM planostbl WHERE id=:id';
 $s=$pdo->prepare($sql);
 $s->bindValue(':id',$_POST['id']);
 $s->execute();
 $nombre=$s->fetch();
 $arch=$_SERVER['DOCUMENT_ROOT'].'/reportes/iluminacion/planos/'.$nombre['nombrearchivado'];

    if (file_exists($arch)) {
	try{	
		$sql='DELETE FROM planostbl WHERE id=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id',$_POST['id']);
		$s->execute();
		}
	catch (PDOException $e)
	{
       $mensaje='Lo sentimos, no se pudo borrar el plano.  Favor de intentar nuevamente';
	   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	   exit();
	}   
	   unlink($arch);
    } else {
       $mensaje='Lo sentimos, no se encontro el archivo que se desea borrar.  Favor de avisar a sistemas de este error';
	   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	   exit();
    } 
	header('Location: .');
	exit();
}

/* *****************************************************************
** Esta funcion guarda la info de una imagen en la base de datos  **
***************************************************************** */
function guardainfoplano($nombrearchivar='',$nombre='',$descripcion='',$idot='')
{
  global $pdo;
  try
  {
	  $sql='INSERT INTO planostbl SET
			nombre=:nombre,
			nombrearchivado=:nombrearchivar,
			descripcion=:descripcion,
			ordenidfk=:idot';
	  $s=$pdo->prepare($sql);
	  $s->bindValue(':nombre',$nombre);
	  $s->bindValue(':nombrearchivar',$nombrearchivar);
	  $s->bindValue(':descripcion',$descripcion);
	  $s->bindValue(':idot',$idot);
	  $s->execute();
  }
  catch(PDOException $e)
  {
    $mensaje='Hubo un error al tratar de guardar la informacion del plano.  intentar nuevamente y avisar de este error a sistemas.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();	
  }
}
/* ******************************************************************
** Se gurda el archivo cargado al sistema y se regresa a la forma  **
** de subir archivos por si aun se desea subir mas                 **
****************************************************************** */
if (isset($_POST['accion']) and $_POST['accion']=='subir')
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  // verifica que el archivo se haya subido
    if (!is_uploaded_file($_FILES['archivo']['tmp_name'])) {
	  $mensaje='Hubo un error tratando de subir el archivo.  Favor de revisar la conexión a internet y que el archivo sea menor a 2Mb e intenta de nevo.';
	  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	  exit();
	}
	// verifica que el archivo sea gif, jpeg, png o bmp
/*	$archivotipo=exif_imagetype($_FILES['archivo']['tmp_name']);
	$tiposaceptados=array(IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG,IMAGETYPE_BMP);
	if (!in_array($archivotipo,$tiposaceptados)){
	  $mensaje='el archivo no se acepto por no ser tipo GIF, JPEG, PNG O BMP'; 
	  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	  exit();	
	} */  
	// se verifica que el nombre del archivo solo contenga caracteres validos
	$nombrearch=preg_replace('/[^A-Z0-9._-]/i','_',$_FILES['archivo']['name']);
	$partes=pathinfo($nombrearch);
	$extension=$partes['extension'];
	$nombrearchivar=$_POST['idot'].'_'.$_POST['hora'].'.'.$extension;
	$nombre=$partes['filename'];
	// verifica que el archivo sea pdf.
	if ($extension!='pdf'){
	  $mensaje='el archivo no se acepto por no ser tipo pdf'; 
	  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	  exit();
	}
	//se guarda el archivo en la carpeta deseada
	$semovio=move_uploaded_file($_FILES['archivo']['tmp_name'],
	$_SERVER['DOCUMENT_ROOT'].'/reportes/iluminacion/planos/'.$nombrearchivar);
	if (!$semovio){
	  $mensaje='Vuelva a intentar de nuevo.  Hubo un error tratando de guardar el archivo';
	  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	  exit();
	}
	// Colocar los permisos de lectura al archivo
	chmod ($_SERVER['DOCUMENT_ROOT'].'/reportes/iluminacion/planos/'.$nombrearchivar,0644);
	guardainfoplano($nombrearchivar,$nombre,$_POST['descripcion'],$_POST['idot']);
	header('Location: .');
   exit();
}

/* ******************************************************************
** Se invoca la forma para subir archivos y en caso de que haya    **
** algunos en la base de datos, estos se muestran.                 **
****************************************************************** */  
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  if(isset($_SESSION['idot']) and isset($_SESSION['quien']) and $_SESSION['quien']=='iluminacion'){
	$idot=$_SESSION['idot'];
  }
  else {
     /* echo $_SERVER['HTTP_HOST'].str_replace('planos/','',$_SERVER['REQUEST_URI']);
	  exit(); */
	  header('Location: http://'.$_SERVER['HTTP_HOST'].str_replace('planos/','',$_SERVER['REQUEST_URI']));
  }  
  try
  {
    $sql='SELECT id, nombre, nombrearchivado, descripcion FROM planostbl
		WHERE ordenidfk=:idot';
	$s=$pdo->prepare($sql);
	$s->bindValue(':idot',$idot);
	$s->execute();
  }
  catch (PDOException $e)
  {
    $mensaje='Hubo un error al tratar de mostrar los planos existentes.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  foreach ($s as $linea)
  {if ($linea['descripcion']=='Plano de Rec. Inicial')
   {
    $piniciales[]=array('id'=>$linea['id'],'nombre'=>$linea['nombre'],
				'nombrearchivado'=>$linea['nombrearchivado'],
				'descripcion'=>$linea['descripcion'],
				'liga'=>$linea['nombrearchivado']);
   }
   else
   {
    $pmediciones[]=array('id'=>$linea['id'],'nombre'=>$linea['nombre'],
				'nombrearchivado'=>$linea['nombrearchivado'],
				'descripcion'=>$linea['descripcion'],
				'liga'=>$linea['nombrearchivado']);
   }
  }
  include 'formasubirarch.html.php';
?>