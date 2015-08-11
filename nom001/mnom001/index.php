<?php
 /********** Norma 001 **********/
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';

if (!usuarioRegistrado())
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/direccionaregistro.inc.php';
  exit();
}
if (!usuarioConPermiso('Supervisor'))
{
  $mensaje='Solo el Supervisor tiene acceso a esta parte del programa';
  include '../accesonegado.html.php';
  exit();
}

/**************************************************************************************************/
/* Agregar un nuevo máximo a la norma */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='capturar')
{
  $pestanapag='Agregar máximo';
  $titulopagina='Agregar una nuevo máximo';
  $boton = 'guardar';
  include 'formacapturamaximo.html.php';
  exit();
}

/**************************************************************************************************/
/* Guardar un nuevo máximo de la norma */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='guardar')
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try   
  {
      $sql='INSERT INTO nom01maximostbl SET
            descargaen=:descargaen,
            uso=:uso,
            GyA=:GyA,
            coliformes=:coliformes,
            ssedimentables=:ssedimentables,
            ssuspendidos=:ssuspendidos,
            dbo=:dbo,
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
            temperatura=:temperatura,
            mflotante=:mflotante';
      $s=$pdo->prepare($sql);
      $s->bindValue(':descargaen', $_POST['descargaen']);
      $s->bindValue(':uso', $_POST['uso']);
      $s->bindValue(':GyA', $_POST['GyA']);
      $s->bindValue(':coliformes', $_POST['coliformes']);
      $s->bindValue(':ssedimentables', $_POST['ssedimentables']);
      $s->bindValue(':ssuspendidos', $_POST['ssuspendidos']);
      $s->bindValue(':dbo', $_POST['dbo']);
      $s->bindValue(':nitrogeno', $_POST['nitrogeno']);
      $s->bindValue(':fosforo', $_POST['fosforo']);
      $s->bindValue(':arsenico', $_POST['arsenico']);
      $s->bindValue(':cadmio', $_POST['cadmio']);
      $s->bindValue(':cianuros', $_POST['cianuros']);
      $s->bindValue(':cobre', $_POST['cobre']);
      $s->bindValue(':cromo', $_POST['cromo']);
      $s->bindValue(':mercurio', $_POST['mercurio']);
      $s->bindValue(':niquel', $_POST['niquel']);
      $s->bindValue(':plomo', $_POST['plomo']);
      $s->bindValue(':zinc', $_POST['zinc']);
      $s->bindValue(':hdehelminto', $_POST['hdehelminto']);
      $s->bindValue(':temperatura', $_POST['temperatura']);
      $s->bindValue(':mflotante', $_POST['mflotante']);
      $s->execute();
      $id = $pdo->lastInsertid();
  }
  catch (PDOException $e)
  {
    $mensaje='Hubo un error insertando los máximos.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  editarMaximos($_POST, $id);
}

/**************************************************************************************************/
/* Editar un máximo de la norma */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='editar')
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try   
  {
    $sql='SELECT * FROM nom01maximostbl WHERE id = :id';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':id',$_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $mensaje='Hubo un error actualizando los máximos.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  editarMaximos($s->fetch(), $_POST['id']);
}

/**************************************************************************************************/
/* Salvar edición de un máximo de la norma */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='salvar')
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try   
  {
    $sql='UPDATE nom01maximostbl SET
          descargaen=:descargaen,
          uso=:uso,
          GyA=:GyA,
          coliformes=:coliformes,
          ssedimentables=:ssedimentables,
          ssuspendidos=:ssuspendidos,
          dbo=:dbo,
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
          temperatura=:temperatura,
          mflotante=:mflotante
          WHERE id = :id';
    $s=$pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->bindValue(':descargaen', $_POST['descargaen']);
    $s->bindValue(':uso', $_POST['uso']);
    $s->bindValue(':GyA', $_POST['GyA']);
    $s->bindValue(':coliformes', $_POST['coliformes']);
    $s->bindValue(':ssedimentables', $_POST['ssedimentables']);
    $s->bindValue(':ssuspendidos', $_POST['ssuspendidos']);
    $s->bindValue(':dbo', $_POST['dbo']);
    $s->bindValue(':nitrogeno', $_POST['nitrogeno']);
    $s->bindValue(':fosforo', $_POST['fosforo']);
    $s->bindValue(':arsenico', $_POST['arsenico']);
    $s->bindValue(':cadmio', $_POST['cadmio']);
    $s->bindValue(':cianuros', $_POST['cianuros']);
    $s->bindValue(':cobre', $_POST['cobre']);
    $s->bindValue(':cromo', $_POST['cromo']);
    $s->bindValue(':mercurio', $_POST['mercurio']);
    $s->bindValue(':niquel', $_POST['niquel']);
    $s->bindValue(':plomo', $_POST['plomo']);
    $s->bindValue(':zinc', $_POST['zinc']);
    $s->bindValue(':hdehelminto', $_POST['hdehelminto']);
    $s->bindValue(':temperatura', $_POST['temperatura']);
    $s->bindValue(':mflotante', $_POST['mflotante']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $mensaje='Hubo un error extrayendo los máximos.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  editarMaximos($_POST, $_POST['id']);
}

/**************************************************************************************************/
/* Agregar un nuevo máximo a la norma */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='borrar')
{
  $id = $_POST['id'];
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
    $sql='SELECT identificacion FROM nom01maximostbl WHERE id=:id';
    $s= $pdo->prepare($sql);
    $s->bindValue(':id',$id); 
    $s->execute();
    $resultado = $s->fetch();
    $identificacion = $resultado['identificacion'];
  }
  catch (PDOException $e)
  {
    $mensaje='No se pudo hacer la confirmacion de eliminación'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  include 'formaconfirmaximo.html.php';
  exit();
}

/**************************************************************************************************/
/* Confirmación de borrado de una medición de una orden de trabajo */
/**************************************************************************************************/
if (isset($_POST['accion']) and $_POST['accion']=='Continuar borrando')
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
    $sql='DELETE FROM nom01maximostbl WHERE id = :id';
    $s=$pdo->prepare($sql);
    $s->bindValue(':id',$_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $mensaje='Hubo un error borrando el máximo. Intente de nuevo. '.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  verMaximos();
}

/**************************************************************************************************/
/* Ver maximos de la norma 001 */
/**************************************************************************************************/
verMaximos();
	
/**************************************************************************************************/
/* Función para obtener valores máximos */
/**************************************************************************************************/
function verMaximos(){
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try   
  {
    $s=$pdo->prepare('SELECT id, descargaen, uso FROM nom01maximostbl');
    $s->execute();
    $e = $s->fetchAll();
    foreach ($e as $value) {
      $maximos[] = array("id" => $value['id'],
                         "descargaen" => $value['descargaen'],
                         "uso" => $value['uso']);
    }
  }
  catch (PDOException $e)
  {
    $mensaje='Hubo un error extrayendo los maximos.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  include 'formamax.html.php';
  exit();
}

/**************************************************************************************************/
/* Función para obtener valores máximos */
/**************************************************************************************************/
function editarMaximos($valor, $id){
  $valores = array("descargaen" => $valor["descargaen"],
           "uso" => $valor["uso"],
  	    	 "GyA" => $valor["GyA"],
  	    	 "coliformes" => $valor["coliformes"],
  				 "ssedimentables" => $valor["ssedimentables"],
           "ssuspendidos" => $valor["ssuspendidos"],
           "dbo" => $valor["dbo"],
           "nitrogeno" => $valor["nitrogeno"],
           "fosforo" => $valor["fosforo"],
           "arsenico" => $valor["arsenico"],
           "cadmio" => $valor["cadmio"],
           "cianuros" => $valor["cianuros"],
           "cobre" => $valor["cobre"],
           "cromo" => $valor["cromo"],
           "mercurio" => $valor["mercurio"],
           "niquel" => $valor["niquel"],
           "plomo" =>$valor["plomo"],
           "zinc" => $valor["zinc"],
           "hdehelminto" => $valor["hdehelminto"],
           "temperatura" => $valor["temperatura"],
           "mflotante" => $valor["mflotante"]);
  $pestanapag='Editar máximo';
  $titulopagina='Editar un máximo';
  $boton = 'salvar';
  include 'formacapturamaximo.html.php';
  exit();
}