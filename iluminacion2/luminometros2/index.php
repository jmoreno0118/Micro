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
  	$pestanapag = "Nuevo Luminometro";
  	$titulopagina = "Nuevo Luminometro";
    $boton = "crear";
    include('formacapturalum.html.php');
   	exit();
  }

/**************************************************************************************************/
/* Ir a crear un nuevo luminometro */
/**************************************************************************************************/
  if(isset($_POST['accion']) AND $_POST['accion']=='editar')
  {
  	verLuminometro($_POST['id']);
  }

/**************************************************************************************************/
/* Guardar un nuevo luminometro */
/**************************************************************************************************/
  if(isset($_POST['accion']) AND $_POST['accion']=='crear')
  {
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try
    {
      $sql='INSERT INTO luminometrostbl SET
           marca=:marca,
           modelo=:modelo,
           serie=:serie,
           intervalos=:intervalos';
      $s=$pdo->prepare($sql);
      $s->bindValue(':marca', $_POST['marca']);
      $s->bindValue(':modelo', $_POST['modelo']);
      $s->bindValue(':serie', $_POST['serie']);
      $s->bindValue(':intervalos', intervalos($_POST));
      $s->execute();
    }catch(PDOException $e){
      $mensaje='Hubo un error extrayendo la información del cliente'.$e;
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
    }
    verLuminometros();
  }


/**************************************************************************************************/
/* Editar un luminometro */
/**************************************************************************************************/
  if(isset($_POST['accion']) AND $_POST['accion']=='salvar')
  {
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try
    {
      $sql='UPDATE luminometrostbl SET
           marca=:marca,
           modelo=:modelo,
           serie=:serie,
           intervalos=:intervalos
           WHERE id = :id';
      $s=$pdo->prepare($sql);
      $s->bindValue(':marca', $_POST['marca']);
      $s->bindValue(':modelo', $_POST['modelo']);
      $s->bindValue(':serie', $_POST['serie']);
      $s->bindValue(':intervalos', intervalos($_POST));
      $s->bindValue(':id',  $_POST['id']);
      $s->execute();
    }catch(PDOException $e){
      $mensaje='Hubo un error extrayendo la información del cliente'.$e;
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
    }
    verLuminometro($_POST['id']);
  }

/**************************************************************************************************/
/* Borrar un luminometro */
/**************************************************************************************************/
  if(isset($_POST['accion']) AND $_POST['accion']=='borrar')
  {
   $id=$_POST['id'];
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php'; 
   try
   {
    $sql='SELECT marca, modelo, serie FROM luminometrostbl WHERE id=:id';
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
   $marca=$resultado['marca'];
   $modelo=$resultado['modelo'];
   $serie=$resultado['serie'];
   include 'formaconfimaluminometro.html.php';
   exit();
  }

/**************************************************************************************************/
/* Confirma borrado de luminometro */
/**************************************************************************************************/
  if(isset($_POST['accion']) AND $_POST['accion']=='Continuar borrando luminometro')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
     $pdo->beginTransaction();

     $sql='DELETE FROM luminometrostbl WHERE id=:id';
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
   verLuminometros();
  }

/**************************************************************************************************/
/* Ver tabla de luminometros */
/**************************************************************************************************/
 verLuminometros();

/**************************************************************************************************/
/* Función para ver tabla de luminometros */
/**************************************************************************************************/
 function verLuminometros(){
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
     $sql='SELECT * FROM luminometrostbl';
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
 function verLuminometro($id){
  $pestanapag = "Editar Luminometro";
  $titulopagina = "Editar Luminometro";
  $boton = "salvar";
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
    $sql='SELECT * FROM luminometrostbl WHERE id = :id';
    $s=$pdo->prepare($sql);
    $s->bindValue(':id', $id);
    $s->execute();
    $luminometro = $s->fetch();
  }catch(PDOException $e){
    $mensaje='Hubo un error extrayendo la información del cliente'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  include('formacapturalum.html.php');
  exit();
 }
?>