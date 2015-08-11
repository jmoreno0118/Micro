<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/funcioneshig.inc.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';

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

global $rectbl;
global $recidfk;
$rectbl = 'vib_puntorectbl';
$recidfk = 'vibrcidfk';

include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/reusapunto/reusarpuntos.php';

/**************************************************************************************************/
/* Crear un nuevo punto en una orden de trabajo */
/**************************************************************************************************/
if (isset($_GET['nuevopunto']))
{
  fijarAccionUrl('nuevopunto');

  if(isset($_POST['dato'])){
    $dato = json_decode($_POST['dato'],TRUE);
  }

  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  $pestanapag='Agrega Punto';
  $titulopagina='Agregar un nuevo punto';
  $accion='guardarpunto'; 
  include 'formacapturapunto.html.php';
  exit();		 
}

/**************************************************************************************************/
/* Guardar inf. y mediciones de un punto nuevo */
/**************************************************************************************************/
if(isset($_GET['guardarpunto']))
{
  /*$mensaje='Error Forzado 1.';
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
  exit();*/

  $idrci = $_SESSION['idrci'];
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
    $pdo->beginTransaction();
    $sql='INSERT INTO puntostbl SET
          medicion=:nomedicion,
          fecha=:fecha,
          departamento=:departamento,
          area=:area,
          ubicacion=:ubicacion,
          puesto=:puesto,
          numestudios=0,
          ordenidfk=:ordenidfk';
    $s=$pdo->prepare($sql);
    $s->bindValue(':nomedicion',$_POST['nomedicion']);
    $s->bindValue(':fecha',$_POST['fecha']);
    $s->bindValue(':departamento',$_POST['departamento']);
    $s->bindValue(':area',$_POST['area']);
    $s->bindValue(':puesto',$_POST['puesto']);
    $s->bindValue(':ubicacion',$_POST['ubicacion']);
    $s->bindValue(':ordenidfk',$_SESSION['idot']);
    $s->execute();
    $puntosid=$pdo->lastInsertId();

    $sql='INSERT INTO vib_puntorectbl SET
          puntoidfk=:puntoidfk,
          vibrcidfk=:vibrcidfk';
    $s=$pdo->prepare($sql);
    $s->bindValue(':puntoidfk', $puntosid);
    $s->bindValue(':vibrcidfk',$idrci);
    $s->execute();

    $sql='INSERT INTO vib_medstbl SET
          puntoidfk=:puntoidfk,
          evento=:evento,
          tipoevento=:tipoevento,
          ciclos=:ciclos,
          duracion=:duracion,
          herramienta=:herramienta,
          med1=:med1,
          med2=:med2,
          med3=:med3,
          identificacion=:identificacion';
    $s=$pdo->prepare($sql);
    $s->bindValue(':puntoidfk', $puntosid);
    $s->bindValue(':evento', $_POST['evento']);
    $s->bindValue(':tipoevento', $_POST['tipoevento']);
    $s->bindValue(':ciclos', $_POST['ciclos']);
    $s->bindValue(':duracion', $_POST['duracion']);
    $s->bindValue(':herramienta', $_POST['herramienta']);
    $s->bindValue(':med1', $_POST['med1']);
    $s->bindValue(':med2', $_POST['med2']);
    $s->bindValue(':med3', $_POST['med3']);
    $s->bindValue(':identificacion',$_POST['identificacion']);
    $s->execute();
    $pdo->commit();
  }
  catch (PDOException $e)
  {
    $pdo->rollback();
    $mensaje='Hubo un error al tratar de agregar las mediciones. Favor de intentar nuevamente.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  $pestanapag='Agrega Punto';
  $titulopagina='Agregar un nuevo punto';
  $accion='guardarpunto';
  $dato=array('nomedicion'=>$_POST['nomedicion']+1,
              'fecha'=>$_POST['fecha'],
              'departamento'=>$_POST['departamento'],
              'area'=>$_POST['area'],
              'ubicacion'=>$_POST['ubicacion'],
              'puesto'=>$_POST['puesto']); 
  include 'formacapturapunto.html.php';
  exit();	
}

/**************************************************************************************************/
/* Editar un punto de vibraciones */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='editarpunto')
{
  fijarAccionUrl('editarpunto');

  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  if(isset($_POST['dato'])){
    $dato = json_decode($_POST['dato'],TRUE);
  }else{
    try   
    {
      $sql='SELECT * FROM puntostbl WHERE id = :id';
      $s=$pdo->prepare($sql); 
      $s->bindValue(':id',$_POST['id']);
      $s->execute();

      $sql='SELECT * FROM vib_medstbl WHERE puntoidfk=:id';
      $t=$pdo->prepare($sql); 
      $t->bindValue(':id',$_POST['id']);
      $t->execute();

      $resultado1=$s->fetch();
      $resultado2=$t->fetch();
    }
    catch (PDOException $e) 
    {
      $mensaje='Hubo un error extrayendo la información del punto.';
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
    }

    $dato=array('nomedicion' => $resultado1['medicion'],
              'fecha' => $resultado1['fecha'],
              'departamento' => $resultado1['departamento'],
              'area' => $resultado1['area'],
              'ubicacion' => $resultado1['ubicacion'],
              'puesto' => $resultado1['puesto'],
              'identificacion' => $resultado2['identificacion'],
              'evento' => $resultado2['evento'],
              'tipoevento' => $resultado2['tipoevento'],
              'ciclos' => $resultado2['ciclos'],
              'duracion' => $resultado2['duracion'],
              'herramienta' => $resultado2['herramienta'],
              'med1' => $resultado2['med1'],
              'med2' => $resultado2['med2'],
              'med3' => $resultado2['med3']);
  }

  $pestanapag='Editar Punto';
  $titulopagina='Editar un punto de la OT. '.otdeordenes($_SESSION['idot']);
  $accion='salvarpunto';
  $id=$_POST['id'];
  include 'formacapturapunto.html.php';
  exit();	
}

/**************************************************************************************************/
/* Guardar la edición de un punto de vibrsaciones */
/**************************************************************************************************/
if (isset($_GET['salvarpunto']))
{
  /*$mensaje='Error Forzado 2.';
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
  exit();*/

  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
    $pdo->beginTransaction();
    $sql='UPDATE puntostbl SET
          medicion=:nomedicion,
          fecha=:fecha,
          departamento=:departamento,
          area=:area,
          ubicacion=:ubicacion,
          puesto=:puesto,
          ordenidfk=:ordenidfk
          WHERE id = :id';
    $s=$pdo->prepare($sql);
    $s->bindValue(':nomedicion',$_POST['nomedicion']);
    $s->bindValue(':fecha',$_POST['fecha']);
    $s->bindValue(':departamento',$_POST['departamento']);
    $s->bindValue(':area',$_POST['area']);
    $s->bindValue(':ubicacion',$_POST['ubicacion']);
    $s->bindValue(':puesto',$_POST['puesto']);
    $s->bindValue(':id',$_POST['id']);
    $s->bindValue(':ordenidfk',$_SESSION['idot']);
    $s->execute();

    $sql='UPDATE vib_medstbl SET
          evento=:evento,
          tipoevento=:tipoevento,
          ciclos=:ciclos,
          duracion=:duracion,
          herramienta=:herramienta,
          med1=:med1,
          med2=:med2,
          med3=:med3,
          identificacion=:identificacion
          WHERE puntoidfk=:id';
    $s=$pdo->prepare($sql);
    $s->bindValue(':id',$_POST['id']);
    $s->bindValue(':evento', $_POST['evento']);
    $s->bindValue(':tipoevento', $_POST['tipoevento']);
    $s->bindValue(':ciclos', $_POST['ciclos']);
    $s->bindValue(':duracion', $_POST['duracion']);
    $s->bindValue(':herramienta', $_POST['herramienta']);
    $s->bindValue(':med1', $_POST['med1']);
    $s->bindValue(':med2', $_POST['med2']);
    $s->bindValue(':med3', $_POST['med3']);
    $s->bindValue(':identificacion',$_POST['identificacion']);
    $s->execute();
    $pdo->commit();
  }
  catch (PDOException $e)
  {
    $pdo->rollback();
    $mensaje='Hubo un error al tratar de actualizar la información del punto. Favor de intentar nuevamente.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  header('Location: .');
}

/**************************************************************************************************/
/* Borrar un punto de un reconocimiento inicial de una orden de trabajo */
/**************************************************************************************************/
if (isset($_POST['accion']) and $_POST['accion']=='borrarpunto')
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php'; 
  try
  {
    $sql='SELECT * FROM puntostbl
          INNER JOIN vib_medstbl ON puntostbl.id = vib_medstbl.puntoidfk
          WHERE puntostbl.id=:id';
    $s= $pdo->prepare($sql);
    $s->bindValue(':id',$_POST['id']); 
    $s->execute();
  }
  catch (PDOException $e)
  {
    $mensaje='No se pudo accesar el punto.  Favor de intentar nuevamente.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  $resultado=$s->fetch();
  $id=$_POST['id'];
  $medicion=$resultado['medicion'];
  $fecha=$resultado['fecha'];
  $departamento=$resultado['departamento'];
  $area=$resultado['area'];
  $identificacion=$resultado['identificacion'];
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/formas/formaconfirmapunto.html.php';
  exit();
}

/**************************************************************************************************/
/* continuacion de borrado de un punto */
/**************************************************************************************************/
if (isset($_POST['accion']) and $_POST['accion']=='Continuar borrando')
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
    $pdo->beginTransaction();
    $sql='DELETE FROM vib_puntorectbl WHERE puntoidfk=:id';
    $s=$pdo->prepare($sql);
    $s->bindValue(':id',$_POST['id']);
    $s->execute();

    $sql='DELETE FROM vib_medstbl WHERE puntoidfk=:id';
    $s=$pdo->prepare($sql);
    $s->bindValue(':id',$_POST['id']);
    $s->execute();

    $sql='SELECT numestudios
          FROM puntostbl
          WHERE id=:id';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':medicion', $_POST['medicion']);
    $s->bindValue(':vibrcidfk', $_POST['recid']);
    $s->execute();

    $numestudios = $s->fetch();
    
    if($numestudios['numestudios'] < 1)
    {
      $sql='DELETE FROM puntostbl WHERE id=:id';
      $s=$pdo->prepare($sql);
      $s->bindValue(':id',$_POST['id']);
      $s->execute();
      $pdo->commit();
    }
    else
    {
      $sql='UPDATE puntostbl SET
          numestudios = numestudios - 1
          WHERE id = :id';
      $s=$pdo->prepare($sql); 
      $s->bindValue(':id', $_POST['id']);
      $s->execute();
    }
    
  }
  catch (PDOException $e)
  {
    $pdo->rollback();
    $mensaje='Hubo un error borrando el punto. Intente de nuevo. ';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  header('Location: .');
}

/**************************************************************************************************/
/* Acción por defualt, llevar a búsqueda de ordenes */
/**************************************************************************************************/
include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
if(isset($_SESSION['idot']) and isset($_SESSION['idrci']) and isset($_SESSION['quien']) and $_SESSION['quien']=='Vibraciones mano-brazo'){
  $idot=$_SESSION['idot'];
  $idrci=$_SESSION['idrci'];
}
else {
  header('Location: http://'.$_SERVER['HTTP_HOST'].str_replace('puntos/','',$_SERVER['REQUEST_URI']));
}
$ot=otdeordenes($idot);
$puntos=verpuntos($idrci);
include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/formas/formapuntos.html.php';
exit();

/**************************************************************************************************/
/* Función para ver puntos de un reconocimiento inicial */
/**************************************************************************************************/
function verPuntos($id = ""){  
  global $pdo;
  try
  {
    $sql='SELECT puntostbl.id, puntostbl.departamento, puntostbl.area
    FROM puntostbl
    INNER JOIN vib_puntorectbl ON puntostbl.id = vib_puntorectbl.puntoidfk
    WHERE vib_puntorectbl.vibrcidfk = :id';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':id',$id);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $mensaje='Hubo un error extrayendo la lista de puntos.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }

  foreach ($s as $linea)
  {
    try
    {
      $sql='SELECT identificacion
      FROM  vib_medstbl 
      WHERE puntoidfk = :id';
      $s=$pdo->prepare($sql); 
      $s->bindValue(':id', $linea['id']);
      $s->execute();
      $identificacion = $s->fetch();
    }
    catch (PDOException $e)
    {
      $mensaje='Hubo un error extrayendo la lista de puntos.'.$e;
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
    }

    $puntos[]=array('id' => $linea['id'],
                    'departamento' => $linea['departamento'],
                    'area' => $linea['area'],
                    'identificacion' => $identificacion['identificacion']);
  }
  if (isset($puntos))
  {
    return $puntos;
  }
  else
  {
    return;
  }
}
?>