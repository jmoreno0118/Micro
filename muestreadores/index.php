<?php

 if (!usuarioRegistrado())
 {
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/direccionaregistro.inc.php';
  exit();
 }
 if (!usuarioConPermiso('Supervisor'))
 {
  $mensaje='Solo el Capturista tiene acceso a esta parte del programa';
  include '../accesonegado.html.php';
  exit();
 }

$estudios= array('Iluminacion',
				'Nivel sonoro equivalente',
			 	'Dosis de ruido',
			 	'temperaturas extremas/abatidas',
			 	'Radiaciones NO ionizantes',
			 	'Vibraciones mano-brazo',
			 	'Vibraciones cuerpo completo',
			 	'Radiaciones ionizantes',
			 	'NOM 001',
			 	'NOM 002',
			 	'Fuentes fijas',
			 	'Ruido periferico',
			 	'Suelos',
			 	'CRETIB'
 );

if(isset($_POST['accion']) and $_POST['accion']=='Capturar'){
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	$pestanapag='Nuevo muestreador';
	$titulopagina='Nuevo muestreador';
	$boton='Guardar';
	$representantes=listarepresentantes();
	include 'formacapturar.html.php';
	exit();	
}

if(isset($_POST['accion']) and $_POST['accion']=='Guardar'){
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try{
		$pdo->beginTransaction();

		$sql='INSERT INTO muestreadorestbl SET
              nombre=:nombre,
              ap=:ap,
              am=:am,
              signatario=:signatario';
        $s=$pdo->prepare($sql);
        $s->bindValue(':nombre', $_POST['nombre']);
        $s->bindValue(':ap', $_POST['ap']);
        $s->bindValue(':am', $_POST['am']);
        $s->bindValue(':signatario', isset($_POST['signatario'])? 1 : 0);
        $s->execute();
        $id=$pdo->lastInsertid();

        insertarEstudiosRepresentantes($id, $_POST['estudiosmuestreador'], $_POST['representantes']);
        
        if(isset($_POST['signatario'])){
	        insertarEstudiosSignatario($id, $_POST['estudiossignatarios']);
    	}

		$pdo->commit();
	}catch (PDOException $e){
		$pdo->rollback();
		$mensaje='Hubo un error al tratar de obtener los muestreadores. Favor de intentar nuevamente. '.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	verMuestreadores();
}

if(isset($_POST['accion']) and $_POST['accion']=='Salvar'){
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	$id = $_POST['id'];
	try{
		$pdo->beginTransaction();

		$sql='UPDATE muestreadorestbl SET
              nombre=:nombre,
              ap=:ap,
              am=:am,
              signatario=:signatario
              WHERE id=:id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':nombre', $_POST['nombre']);
        $s->bindValue(':ap', $_POST['ap']);
        $s->bindValue(':am', $_POST['am']);
        $s->bindValue(':signatario', isset($_POST['signatario'])? 1 : 0);
        $s->bindValue(':id', $id);
        $s->execute();

        borrarEstudiosRepresentantes($id);

        insertarEstudiosRepresentantes($id, $_POST['estudiosmuestreador'], $_POST['representantes']);

        borrarEstudiosSignatario($id);

        if(isset($_POST['signatario'])){
	        insertarEstudiosSignatario($id, $_POST['estudiossignatarios']);
    	}

		$pdo->commit();
	}catch (PDOException $e){
		$pdo->rollback();
		$mensaje='Hubo un error al tratar de obtener los muestreadores. Favor de intentar nuevamente. '.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	verMuestreadores();
}

if(isset($_POST['accion']) and $_POST['accion']=='Editar'){
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	$pestanapag='Editar muestreador';
	$titulopagina='Editar muestreador';
	$boton='Salvar';
	$representantes=listarepresentantes();
	$id = $_POST['id'];
	try{
		$sql='SELECT * FROM muestreadorestbl
				WHERE id=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id', $id);
		$s->execute();
		$valores = $s->fetch();

		$sql='SELECT estudio FROM estudiosmuestreadortbl
				WHERE muestreadoridfk=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id', $id);
		$s->execute();
		foreach ($s->fetchAll(PDO::FETCH_ASSOC) as $value) {
			$valores['estudiosmuestreador'][] = $value['estudio'];
		}
		//$valores['estudiosmuestreador'] = $s->fetchAll(PDO::FETCH_ASSOC);

		$sql='SELECT representanteidfk FROM muestreadorrepresentantetbl
				WHERE muestreadoridfk=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id', $id);
		$s->execute();
		foreach ($s->fetchAll(PDO::FETCH_ASSOC) as $value) {
			$valores['representantes'][] = $value['representanteidfk'];
		}
		//$valores['representantes'] = $s->fetchAll(PDO::FETCH_ASSOC);
		
		$sql='SELECT estudio FROM estudiossignatariotbl
				WHERE muestreadoridfk=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id', $id);
		$s->execute();
		foreach ($s->fetchAll(PDO::FETCH_ASSOC) as $value) {
			$valores['estudiossignatarios'][] = $value['estudio'];
		}
		//$valores['estudiossignatarios'] = $s->fetchAll(PDO::FETCH_ASSOC);

	}catch (PDOException $e){
		$mensaje='Hubo un error al tratar de obtener los datos del muestreador. Favor de intentar nuevamente. '.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}

	include 'formacapturar.html.php';
	exit();
}

if(isset($_POST['accion']) and $_POST['accion']=='Borrar'){
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	$id = $_POST['id'];
	try{
		$sql='SELECT * FROM muestreadorestbl
				WHERE id=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id', $id);
		$s->execute();
		$valores = $s->fetch();

		$nombre = $valores['nombre'];
		$ap = $valores['ap'];
		$am = $valores['am'];

	}catch (PDOException $e){
		$mensaje='Hubo un error al tratar de obtener los datos del muestreador. Favor de intentar nuevamente. '.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	include 'formaconfirma.html.php';
	exit();
}

if(isset($_POST['accion']) and $_POST['accion']=='Continuar borrando'){
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	$id = $_POST['id'];
	try{
		$sql='DELETE FROM muestreadorestbl WHERE id = :id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id', $id);
		$s->execute();

		borrarEstudiosRepresentantes($id);

        borrarEstudiosSignatario($id);

	}catch (PDOException $e){
		$mensaje='Hubo un error al tratar de obtener los datos del muestreador. Favor de intentar nuevamente. '.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	verMuestreadores();
}

verMuestreadores();

function verMuestreadores(){
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try{
		$sql='SELECT * FROM muestreadorestbl';
		$s=$pdo->prepare($sql);
		$s->execute();
		$muestreadores = $s->fetchAll();
	}catch (PDOException $e){
		$mensaje='Hubo un error al tratar de obtener los muestreadores. Favor de intentar nuevamente. '.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	include 'formamuestreadores.html.php';
	exit();	
}

/*************************************************************************************/
function listarepresentantes(){
	global $pdo; 
	// Construye lista de representantes y estudios
	try{
		$resultados=$pdo->query('SELECT id, nombre FROM representantestbl');
	}catch (PDOException $e){
		$mensaje='Hubo un error tratando de obtener la informacion de los representantes';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	foreach ($resultados as $resultado){
		$representantes[$resultado['id']] = $resultado['nombre'];
	}
	return $representantes;
}

function borrarEstudiosSignatario($id){
	global $pdo; 
	$sql='DELETE FROM estudiossignatariotbl WHERE muestreadoridfk = :id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id', $id);
	$s->execute();
}

function borrarEstudiosRepresentantes($id){
	global $pdo; 
	$sql='DELETE FROM muestreadorrepresentantetbl WHERE muestreadoridfk = :id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id', $id);
	$s->execute();

	$sql='DELETE FROM estudiosmuestreadortbl WHERE muestreadoridfk = :id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id', $id);
	$s->execute();
}

function insertarEstudiosSignatario($id, $estudios){
	global $pdo; 
	foreach ($estudios as $key => $value) {
    	$sql='INSERT INTO estudiossignatariotbl SET
          muestreadoridfk=:id,
          estudio=:estudio';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $id);
        $s->bindValue(':estudio', $value);
        $s->execute();
    }
}

function insertarEstudiosRepresentantes($id, $estudios, $representantes){
	global $pdo; 
	foreach ($estudios as $key => $value) {
        	$sql='INSERT INTO estudiosmuestreadortbl SET
              muestreadoridfk=:id,
              estudio=:estudio';
	        $s=$pdo->prepare($sql);
	        $s->bindValue(':id', $id);
	        $s->bindValue(':estudio', $value);
	        $s->execute();
        }

        foreach ($representantes as $key => $value) {
        	$sql='INSERT INTO muestreadorrepresentantetbl SET
              muestreadoridfk=:id,
              representanteidfk=:representanteid';
	        $s=$pdo->prepare($sql);
	        $s->bindValue(':id', $id);
	        $s->bindValue(':representanteid', $value);
	        $s->execute();
        }
}

?>