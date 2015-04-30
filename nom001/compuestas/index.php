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

		if(isset($_POST['regreso']) AND $_POST['regreso'] === '2'){
			formularioParametros($_POST['id'], intval($_POST['cantidad']), json_decode($_POST['valores'],TRUE), json_decode($_POST['parametros'],TRUE), json_decode($_POST['adicionales'],TRUE), $_POST['idparametro'],$_POST['boton']);
		}else{
			insertMediciones($_POST["mcompuestas"], $muestreo['id']);

			formularioParametros($_POST['id'], $_POST['cantidad'], "", "", "", "", 'guardar nuevos parametros', 1);
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
			formularioParametros($_POST['id'], intval($_POST['cantidad']), json_decode($_POST['valores'],TRUE), json_decode($_POST['parametros'],TRUE), json_decode($_POST['adicionales'],TRUE), $_POST['idparametro'],$_POST['boton'], 1);
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
		$cantidad = $_POST['cantidad'];
		try
		{
			$sql='SELECT * FROM parametrostbl
				WHERE muestreoaguaidfk = (SELECT id 
				      FROM muestreosaguatbl
				      WHERE generalaguaidfk = :id)';
			$s=$pdo->prepare($sql); 
			$s->bindValue(':id',$_POST['id']);
			$s->execute();
		}catch (PDOException $e){
			$mensaje='Hubo un error extrayendo la información de parametros.';
			include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
			exit();
		}
		if($param1 = $s->fetch()){
			formularioParametros($_POST['id'], $cantidad, $valores, $parametros, $adicionales, $param1['id'], 'salvar parametros', 1, $param1);
		}else{
			formularioParametros($_POST['id'], $cantidad, "", "", "", "", 'guardar nuevos parametros', 1);
		}
	}

/**************************************************************************************************/
/* Función para insertar GyA y coliformes */
/**************************************************************************************************/
function insertMediciones($mcompuestas, $muestreoid){
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
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