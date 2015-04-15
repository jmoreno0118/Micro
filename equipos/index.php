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
/* Ir a crear un nuevo luminometro */
/**************************************************************************************************/
  if(isset($_POST['accion']) AND $_POST['accion']=='nuevo')
  {
  	$pestanapag = "Nuevo Equipo";
  	$titulopagina = "Nuevo Equipo";
    $boton = "crear";
    $representantes = obtenerRepresentantes();
    $representanteid = "";
    include('formacapturalum2.html.php');
   	exit();
  }

/**************************************************************************************************/
/* Ir a crear un nuevo luminometro */
/**************************************************************************************************/
  if(isset($_POST['accion']) AND $_POST['accion']=='editar')
  {
  	verEquipo($_POST['id']);
  }

/**************************************************************************************************/
/* Guardar un nuevo luminometro */
/**************************************************************************************************/
  if(isset($_POST['accion']) AND $_POST['accion']=='crear')
  {
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try
    {
      $sql='INSERT INTO equipostbl SET
           estudio=:estudio,
           tipo=:tipo,
           descripcion=:descripcion,
           inventario=:inventario,
           marca=:marca,
           modelo=:modelo,
           serie=:serie,
           fechaalta=:fechaalta,
           representanteidfk=:representante,
           estado=:estado,
           correccion=:correccion';
      $s=$pdo->prepare($sql);
      $s->bindValue(':estudio', $_POST['estudio']);
      $s->bindValue(':tipo', $_POST['tipo']);
      $s->bindValue(':descripcion', $_POST['descripcion']);
      $s->bindValue(':inventario', $_POST['inventario']);
      $s->bindValue(':marca', $_POST['marca']);
      $s->bindValue(':modelo', $_POST['modelo']);
      $s->bindValue(':serie', $_POST['serie']);
      $s->bindValue(':fechaalta', $_POST['fechaalta']);
      $s->bindValue(':representante', $_POST['representante']);
      $s->bindValue(':estado', $_POST['estado']);
      $s->bindValue(':correccion', intervalos($_POST));
      $s->execute();
    }catch(PDOException $e){
      $mensaje='Hubo un error extrayendo la información del cliente'.$e;
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
    }
    verEquipos();
  }


/**************************************************************************************************/
/* Editar un luminometro */
/**************************************************************************************************/
  if(isset($_POST['accion']) AND $_POST['accion']=='salvar')
  {
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try
    {
      $sql='UPDATE equipostbl SET
           estudio=:estudio,
           tipo=:tipo,
           descripcion=:descripcion,
           inventario=:inventario,
           marca=:marca,
           modelo=:modelo,
           serie=:serie,
           fechaalta=:fechaalta,
           representanteidfk=:representante,
           estado=:estado,
           correccion=:correccion
           WHERE id = :id';
      $s=$pdo->prepare($sql);
      $s->bindValue(':estudio', $_POST['estudio']);
      $s->bindValue(':tipo', $_POST['tipo']);
      $s->bindValue(':descripcion', $_POST['descripcion']);
      $s->bindValue(':inventario', $_POST['inventario']);
      $s->bindValue(':marca', $_POST['marca']);
      $s->bindValue(':modelo', $_POST['modelo']);
      $s->bindValue(':serie', $_POST['serie']);
      $s->bindValue(':fechaalta', $_POST['fechaalta']);
      $s->bindValue(':representante', $_POST['representante']);
      $s->bindValue(':estado', $_POST['estado']);
      $s->bindValue(':correccion', intervalos($_POST));
      $s->bindValue(':id',  $_POST['id']);
      $s->execute();
    }catch(PDOException $e){
      $mensaje='Hubo un error extrayendo la información del cliente'.$e;
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
    }
    verEquipo($_POST['id']);
  }

/**************************************************************************************************/
/* Borrar un luminometro */
/**************************************************************************************************
  if(isset($_POST['accion']) AND $_POST['accion']=='borrar')
  {
   $id=$_POST['id'];
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php'; 
   try
   {
    $sql='SELECT inventario, marca, modelo, serie FROM equipostbl WHERE id=:id';
    $s= $pdo->prepare($sql);
    $s->bindValue(':id', $id); 
    $s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='No se pudo hacer al confirmacion de eliminación'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   $resultado=$s->fetch();
   $inventario=$resultado['inventario'];
   $marca=$resultado['marca'];
   $modelo=$resultado['modelo'];
   $serie=$resultado['serie'];
   include 'formaconfimaluminometro.html.php';
   exit();
  }

/**************************************************************************************************/
/* Confirma borrado de luminometro */
/**************************************************************************************************
  if(isset($_POST['accion']) AND $_POST['accion']=='Continuar borrando luminometro')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
     $pdo->beginTransaction();

     $sql='DELETE FROM equipostbl WHERE id=:id';
     $s=$pdo->prepare($sql);
     $s->bindValue(':id',$_POST['id']);
     $s->execute();
 
     $pdo->commit();
   }
   catch (PDOException $e)
   {
    $pdo->rollback();
    $mensaje='Hubo un error borrando el punto. Intente de nuevo. '.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   verEquipos();
  }

/**************************************************************************************************/
/* Ver tabla de luminometros */
/**************************************************************************************************/
 verEquipos();

/**************************************************************************************************/
/* Función para ver tabla de luminometros */
/**************************************************************************************************/
 function verEquipos(){
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
     $sql='SELECT * FROM equipostbl';
     $s=$pdo->prepare($sql);
     $s->execute();
     $luminometros = $s->fetchAll();
   }catch(PDOException $e){
     $mensaje='Hubo un error extrayendo la información del cliente'.$e;
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
   }
   include 'formaverlum.html.php';
   exit();
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

/**************************************************************************************************/
/* Función para ver un luminometro */
/**************************************************************************************************/
 function verEquipo($id){
  $pestanapag = "Editar Equipo";
  $titulopagina = "Editar Equipo";
  $boton = "salvar";
  $representantes = obtenerRepresentantes();
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
    $sql='SELECT * FROM equipostbl WHERE id = :id';
    $s=$pdo->prepare($sql);
    $s->bindValue(':id', $id);
    $s->execute();
    $equipo = $s->fetch();
  }catch(PDOException $e){
    $mensaje='Hubo un error extrayendo la información del cliente'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  include('formacapturalum2.html.php');
  exit();
 }

/**************************************************************************************************/
/* Función para ver un luminometro */
/**************************************************************************************************/
 function obtenerRepresentantes(){
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
    $sql='SELECT id, nombre FROM representantestbl';
     $s=$pdo->prepare($sql);
     $s->execute();
  }catch(PDOException $e){
    $mensaje='Hubo un error extrayendo la información del cliente'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  return $representantes = $s->fetchAll();
 }
?>