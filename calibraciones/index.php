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

/**************************************************************************************************/
/* Función para buscar el numero de inventario */
/**************************************************************************************************/
if (isset($_GET['capturar']))
{   
	include 'formanumeroinventario.html.php';
	exit();
}

/**************************************************************************************************/
/* Función para ir a la forma de una nueva calibración */
/**************************************************************************************************/
if (isset($_GET['capturarnueva']))
{   
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try
	{
		$sql='SELECT ID_Equipo, Numero_Inventario, Tipo FROM equipos WHERE Numero_Inventario=:id';
		$s= $pdo->prepare($sql);
		$s->bindValue(':id', $_POST['numero']); 
		$s->execute();
		$valores = $s->fetch();
	}
	catch (PDOException $e)
	{
		$mensaje='Hubo un error obtenindo la informacion de las ordenes de trabajo';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	$pestanapag = 'Nueva calibración';
	$titulopagina = 'Nueva calibración';
	$boton = 'guardar';
	include 'formacaptura.html.php';
	exit();
}

/**************************************************************************************************/
/* Función para ir a la forma de una nueva calibración */
/**************************************************************************************************/
if (isset($_POST['accion']) and $_POST['accion']=='editar')
{   
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try
	{
		$sql='SELECT ID_Equipo, Numero_Inventario, Tipo FROM equipos WHERE Numero_Inventario = :id';
		$s= $pdo->prepare($sql);
		$s->bindValue(':id', $_POST['numero'], PDO::PARAM_STR);
		$s->execute();
		$equipo = $s->fetch();
		foreach ($equipo as $key => $value) {
			$valores[$key] = $value;
		}

		$sql='SELECT * FROM calibracionestbl WHERE id=:id';
		$s= $pdo->prepare($sql);
		$s->bindValue(':id', $_POST['id']); 
		$s->execute();
		$calibraciones = $s->fetch();
		foreach ($calibraciones as $key => $value) {
			$valores[$key] = $value;
		}
	}
	catch (PDOException $e)
	{
		$mensaje='Hubo un error obtenindo la informacion de las calibracion'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	$pestanapag = 'Editar calibración';
	$titulopagina = 'Editar calibración';
	$boton = 'salvar';
	include 'formacaptura.html.php';
	exit();
}

/**************************************************************************************************/
/* Función para insertar calibraciones */
/**************************************************************************************************/
if (isset($_POST['accion']) and $_POST['accion']=='guardar')
{   
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try
	{
		$sql= 'SELECT nombre, apellido FROM usuariostbl
                WHERE usuario = :usuario';
        $s = $pdo->prepare($sql);
        $s->bindValue(':usuario', $_SESSION['usuario']);
        $s->execute();
        $e = $s->fetch();

		$sql='INSERT INTO calibracionestbl SET
                equipoidfk=:equipoidfk,
                fecha=:fecha,
                laboratoriocalibro=:laboratoriocalibro,
                laboratorioacreditacion=:laboratorioacreditacion,
                fechaLabAcreditacion=:fechaLabAcreditacion,
                fechacalibracion=:fechacalibracion,
                parametroevaluado=:parametroevaluado,
                criterioaceptacion=:criterioaceptacion,
                especificaciones=:especificaciones,
                cumple=:cumple,
                correccion=:correccion,
                usuarioidfk=:usuarioidfk';
          $s=$pdo->prepare($sql);
          $s->bindValue(':equipoidfk', $_POST['equipoidfk']);
          $s->bindValue(':fecha', $_POST['fecha']);
          $s->bindValue(':laboratoriocalibro', $_POST['laboratoriocalibro']);
          $s->bindValue(':laboratorioacreditacion', $_POST['laboratorioacreditacion']);
          $s->bindValue(':fechaLabAcreditacion', $_POST['fechaLabAcreditacion']);
          $s->bindValue(':fechacalibracion', $_POST['fechacalibracion']);
          $s->bindValue(':parametroevaluado', $_POST['parametroevaluado']);
          $s->bindValue(':criterioaceptacion', $_POST['criterioaceptacion']);
          $s->bindValue(':especificaciones', $_POST['especificaciones']);
          $s->bindValue(':cumple', $_POST['cumple']);
          $s->bindValue(':correccion', intervalos($_POST));
          $s->bindValue(':usuarioidfk', $e['nombre'].' '.$e['apellido']);
          $s->execute();
	}
	catch (PDOException $e)
	{
		$mensaje='Hubo un error insertando la calibracion'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	$calibraciones = buscacalibraciones();
	$tablatitulo = 'Calibraciones';
	include 'formabuscarcalibraciones.html.php';
	exit();
}

/**************************************************************************************************/
/* Función para insertar calibraciones */
/**************************************************************************************************/
if (isset($_POST['accion']) and $_POST['accion']=='salvar')
{   
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try
	{
		$sql= 'SELECT nombre, apellido FROM usuariostbl
                WHERE usuario = :usuario';
        $s = $pdo->prepare($sql);
        $s->bindValue(':usuario', $_SESSION['usuario']);
        $s->execute();
        $e = $s->fetch();

		$sql='UPDATE calibracionestbl SET
                fecha=:fecha,
                laboratoriocalibro=:laboratoriocalibro,
                laboratorioacreditacion=:laboratorioacreditacion,
                fechaLabAcreditacion=:fechaLabAcreditacion,
                fechacalibracion=:fechacalibracion,
                parametroevaluado=:parametroevaluado,
                criterioaceptacion=:criterioaceptacion,
                especificaciones=:especificaciones,
                cumple=:cumple,
                correccion=:correccion,
                usuarioidfk=:usuarioidfk
                WHERE id = :id';
          $s=$pdo->prepare($sql);
          $s->bindValue(':id', $_POST['id']);
          $s->bindValue(':fecha', $_POST['fecha']);
          $s->bindValue(':laboratoriocalibro', $_POST['laboratoriocalibro']);
          $s->bindValue(':laboratorioacreditacion', $_POST['laboratorioacreditacion']);
          $s->bindValue(':fechaLabAcreditacion', $_POST['fechaLabAcreditacion']);
          $s->bindValue(':fechacalibracion', $_POST['fechacalibracion']);
          $s->bindValue(':parametroevaluado', $_POST['parametroevaluado']);
          $s->bindValue(':criterioaceptacion', $_POST['criterioaceptacion']);
          $s->bindValue(':especificaciones', $_POST['especificaciones']);
          $s->bindValue(':cumple', $_POST['cumple']);
          $s->bindValue(':correccion', intervalos($_POST));
          $s->bindValue(':usuarioidfk', $e['nombre'].' '.$e['apellido']);
          $s->execute();
	}
	catch (PDOException $e)
	{
		$mensaje='Hubo un error insertando la calibracion'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	$calibraciones = buscacalibraciones();
	$tablatitulo = 'Calibraciones';
	include 'formabuscarcalibraciones.html.php';
	exit();
}

/**************************************************************************************************/
/* Función para buscar calibraciones */
/**************************************************************************************************/
if(isset($_GET['accion']) and $_GET['accion']=='buscar'){
	$numero = (isset($_GET['numero'])) ? $_GET['numero'] : '';
	$tipo = (isset($_GET['tipo'])) ? $_GET['tipo'] : '';
	$laboratorio = (isset($_GET['lab'])) ? $_GET['lab'] : '';
	$calibraciones = buscacalibraciones($numero, $tipo, $laboratorio);
	$tablatitulo = 'Calibraciones';
	include 'formabuscarcalibraciones.html.php';
	exit();
}

$calibraciones = buscacalibraciones();
$tablatitulo = 'Calibraciones';
include 'formabuscarcalibraciones.html.php';
exit();

/**************************************************************************************************/
/* Función para buscar calibraciones */
/**************************************************************************************************/
function buscacalibraciones($numero='', $tipo='', $laboratorio=''){
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	$usuarioactivo = $_SESSION['usuario'];
	try   
	{
		$sql='';
		$select='SELECT equipos.Numero_Inventario, calibracionestbl.id, calibracionestbl.laboratorioacreditacion,
				calibracionestbl.fechacalibracion
				FROM equipos
				INNER JOIN calibracionestbl ON equipoidfk = equipos.ID_Equipo';
		$where = '';
		$placeholders = array();
		if ($numero !== ''){
			$where .=" AND Numero_Inventario LIKE :numero";
			$placeholders[':numero']=$numero;
		}
		if ($tipo !== ''){
			$where .="  AND tipo LIKE :tipo";
			$placeholders[':tipo']=$tipo;
		}
		if ($laboratorio !==''){
			$where .=" AND laboratorioacreditacion LIKE :laboratorio";
			$placeholders[':laboratorio']=$laboratorio;
		}
		$sql=$select.$where;
		$s=$pdo->prepare($sql);
		$s->execute($placeholders);
	}catch (PDOException $e){
		$mensaje='Hubo un error extrayendo la lista de ordenes.'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php'.$e;
		exit();
	}
	foreach ($s as $linea){
		$calibraciones[]=array('id'=>$linea['id'],
								'Numero_Inventario'=>$linea['Numero_Inventario'],
								'laboratorioacreditacion'=>$linea['laboratorioacreditacion'],
								'fechacalibracion'=>$linea['fechacalibracion']);
	}
	if (isset($calibraciones)){
		return $calibraciones;
	}else{
		return;
	}
}

/**************************************************************************************************/
/* Función crear json de intevalos */
/**************************************************************************************************/
 function intervalos($post){
  $array = array();
  for ($i=0; $i < count($post['rango']); $i++) { 
    $a = array('Rango' => $post['rango'][$i],
        'Correccion1' => $post['fcorreccion1'][$i],
        'Correccion2' => $post['fcorreccion2'][$i]);

    array_push($array, $a);
  }
  return json_encode($array);
 }