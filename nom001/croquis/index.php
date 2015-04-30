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
/* Subir croquis */
/**************************************************************************************************/
	if(isset($_POST['accion']) and $_POST['accion']=='subir')
	{
		// verifica que el archivo se haya subido
		if (!is_uploaded_file($_FILES['archivo']['tmp_name'])) {
			$mensaje='Hubo un error tratando de subir el archivo.  Favor de revisar la conexión a internet y que el archivo sea menor a 2Mb e intenta de nevo.';
			include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
			exit();
		}

		// se verifica que el nombre del archivo solo contenga caracteres validos
		$nombrearch=preg_replace('/[^A-Z0-9._-]/i','_',$_FILES['archivo']['name']);
		$partes=pathinfo($nombrearch);
		$extension=$partes['extension'];
		$nombrearchivar=$_POST['ot'].'_'.$_POST['id'].'_'.$_POST['numedicion'].'.'.$extension;
		$nombre=$partes['filename'];

		$archivotipo=exif_imagetype($_FILES['archivo']['tmp_name']);
		$tiposaceptados=array(IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG,IMAGETYPE_BMP);
		if (!in_array($archivotipo,$tiposaceptados)){
			$mensaje='el archivo no se acepto por no ser tipo GIF, JPEG, PNG O BMP'; 
			include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
			exit(); 
  		}
		//se guarda el archivo en la carpeta deseada
		$semovio=move_uploaded_file($_FILES['archivo']['tmp_name'],
		$_SERVER['DOCUMENT_ROOT'].'/reportes/nom001/croquis/'.$nombrearchivar);
		if (!$semovio){
			$mensaje='Vuelva a intentar de nuevo.  Hubo un error tratando de guardar el archivo';
			include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
			exit();
		}
		// Colocar los permisos de lectura al archivo
		chmod($_SERVER['DOCUMENT_ROOT'].'/reportes/nom001/croquis/'.$nombrearchivar, 0777);
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
		if($_POST['update'] == '0'){
			try{
				$sql='INSERT INTO croquistbl SET
				nombre=:nombre,
				nombrearchivado=:nombrearchivar,
				generalaguaidfk=:id';
				$s=$pdo->prepare($sql);
				$s->bindValue(':nombre',$nombre);
				$s->bindValue(':nombrearchivar',$nombrearchivar);
				$s->bindValue(':id',$_POST['id']);
				$s->execute();
			}catch(PDOException $e){
				$mensaje='Hubo un error al tratar de guardar la informacion del plano.  intentar nuevamente y avisar de este error a sistemas.';
				include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
				exit(); 
			}
		}else{
			try{
				$sql='UPDATE croquistbl SET
				nombre=:nombre,
				nombrearchivado=:nombrearchivar
				WHERE generalaguaidfk=:id';
				$s=$pdo->prepare($sql);
				$s->bindValue(':nombre',$nombre);
				$s->bindValue(':nombrearchivar',$nombrearchivar);
				$s->bindValue(':id',$_POST['id']);
				$s->execute();
			}catch(PDOException $e){
				$mensaje='Hubo un error al tratar de guardar la informacion del plano.  intentar nuevamente y avisar de este error a sistemas.';
				include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
				exit(); 
			}
		}
  
		$ot = $_POST['ot'];
		$numedicion = $_POST['numedicion'];
		$id = $_POST['id'];
		try{
			$sql='SELECT ot
			FROM ordenestbl
			WHERE id = :id';
			$s=$pdo->prepare($sql); 
			$s->bindValue(':id',$ot);
			$s->execute();
			$nombreot = $s->fetch();

			$sql='SELECT * FROM croquistbl WHERE generalaguaidfk = :id';
			$s=$pdo->prepare($sql); 
			$s->bindValue(':id', $id);
			$s->execute();
			$croquis = $s->fetch();
		}catch (PDOException $e){
			$mensaje='Hubo un error extrayendo la información de adicionales.'.$e;
			include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
			exit();
		}
		include 'formacroquis.html.php';
		exit();
  }

/**************************************************************************************************/
/* Ver mediciones de una orden de trabajo */
/**************************************************************************************************/
	if(isset($_POST['accion']) and $_POST['accion']=='volvermed' || $_POST['accion']=='no guardar parametros' || $_POST['accion']=='Cancelar borrar medicion')
	{
		$_SESSION['ot'] = $_POST['ot'];
		$request = str_replace('?', '', $_SERVER['REQUEST_URI']);
		$request = str_replace('croquis/', '', $request);
	  	header('Location: http://'.$_SERVER['HTTP_HOST'].$request.'generales');
	    exit();
	}

/**************************************************************************************************/
/* Acción default */
/**************************************************************************************************/
	$post = $_SESSION['post'];
	unset($_SESSION['post']);

	$ot = $post['ot'];
	$numedicion = $post['numedicion'];
	$id = $post['id'];
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try{
		$sql='SELECT ot
		FROM ordenestbl
		WHERE id = :id';
		$s=$pdo->prepare($sql); 
		$s->bindValue(':id',$ot);
		$s->execute();
		$nombreot = $s->fetch();

		$sql='SELECT * FROM croquistbl WHERE generalaguaidfk = :id';
		$s=$pdo->prepare($sql); 
		$s->bindValue(':id', $id);
		$s->execute();
		$croquis = $s->fetch();
	}catch (PDOException $e){
		$mensaje='Hubo un error extrayendo la información de adicionales.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	include 'formacroquis.html.php';
	exit();