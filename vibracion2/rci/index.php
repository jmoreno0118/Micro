<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/funcioneshig.inc.php';

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
/* ************************************************************************* */
/* ****** Capturar reconocimientos iniciales de una orden de trabajo ******* */
/* ************************************************************************* */
  if(isset($_GET['accion']) and $_GET['accion']=='capturarci')
  {
    ininuevorci();
  	include 'formacapturarci.html.php';
  	exit();
  }
/* ************************************************************************* */
/* ******** Guarda reconocimiento inicial de una orden de trabajo ********** */
/* ************************************************************************* */
  if(isset($_POST['accion']) and $_POST['accion']=='guardarrci')
  {
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    $idot=$_SESSION['idot'];
   try
   {
   	$pdo->beginTransaction();
    $sql='INSERT INTO vib_recstbl SET
		 ordenidfk=:ordenid,
         procedimiento=:procedimiento,
		 manto=:manto,
		 eqvibracionidfk=:eqvibracionid,
		 acelerometroidfk=:acelerometroid,
		 calibradoridfk=:calibradorid';
	$s=$pdo->prepare($sql);
	$s->bindValue(':ordenid',$idot);
	$s->bindValue(':procedimiento',$_POST['procedimiento']);
	$s->bindValue(':manto',$_POST['mantenimiento']);
	$s->bindValue(':eqvibracionid',$_POST['eqvibracion']);
	$s->bindValue(':acelerometroid',$_POST['acelerometro']);
	$s->bindValue(':calibradorid',$_POST['calibrador']);
	$s->execute();
	$rcid=$pdo->lastInsertId();
	insertatablasrec($_POST,$rcid);
    $pdo->commit();
   }
   catch (PDOException $e)
   {
   	$pdo->rollback();
    $mensaje='Hubo un error al tratar de insertar el reconocimiento. Favor de intentar nuevamente.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   ininuevorci();
   include 'formacapturarci.html.php';
   exit();
  }
/* *********************************************************** */
/* ** Editar reconocimiento inicial de una orden de trabajo ** */
  if(isset($_POST['accion']) and $_POST['accion']=='editarci')
  {
  	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   $pestanapag='Editar Reconocimiento Inicial';
    $titulopagina='Editar reconocimiento inicial';
    $accion='';
  	$id = $_POST['id'];
    $boton = 'salvarci';
    try   
	{
	 $pdo->beginTransaction();
	 $sql='SELECT * FROM vib_recstbl WHERE id = :id';
	 $s=$pdo->prepare($sql); 
	 $s->bindValue(':id',$_POST['id']);
   	 $s->execute();
	 
	$sql='SELECT * FROM vib_idstbl WHERE vibrecidfk = :id';
	$t=$pdo->prepare($sql); 
	$t->bindValue(':id',$_POST['id']);
	$t->execute();
	
	$sql='SELECT * FROM vib_poetbl WHERE vibrecidfk = :id';
	$u=$pdo->prepare($sql); 
	$u->bindValue(':id',$_POST['id']);
	$u->execute();

	$sql='SELECT * FROM vib_producciontbl WHERE vibrecidfk = :id';
	$v=$pdo->prepare($sql); 
	$v->bindValue(':id',$_POST['id']);
	$v->execute();	
	
	$sql='SELECT * FROM vib_puestostbl WHERE vibrecidfk = :id';
	$w=$pdo->prepare($sql); 
	$w->bindValue(':id',$_POST['id']);
	$w->execute();

	 $pdo->commit();
   }   
   catch (PDOException $e)
   {
    $pdo->rollback();
    $mensaje='Hubo un error extrayendo la información del reconocimiento inicial.';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
   }
	$resultado=$s->fetch();
    $procedimiento=$resultado['procedimiento'];
	$mantenimiento=$resultado['manto'];
	$eqvibracion=$resultado['eqvibracionidfk'];
	$acelerometro=$resultado['acelerometroidfk'];
	$calibrador=$resultado['calibradoridfk'];

    $ids=array();
	foreach ($t as $linea){
        $ids[]=array('area'=>$linea['area'],
					 'fuente'=>$linea['fuente']);
	 }
	$poes=array();
	foreach($u as $linea){
	  $poes[]=array('area'=>$linea['area'],
					'numero'=>$linea['numero'],
					'expo'=>$linea['expo']);
	}
	$produccion=array();
	foreach($v as $linea){
	  $produccion[]=array('depto'=>$linea['depto'],
					'cnormales'=>$linea['cnormales'],
					'preal'=>$linea['preal']);
	}
	$puestos=array();
	foreach($w as $linea){
	  $puestos[]=array('nombre'=>$linea['nombre'],
					'descripcion'=>$linea['descripcion'],
					'ciclos'=>$linea['ciclos']);
	}
   include 'formacapturarci.html.php';
   exit();
  }
/**************************************************************/
/* **** Guardar la edición de un reconocimiento inicial ***** */
/* ********************************************************** */
  if(isset($_POST['accion']) and $_POST['accion']=='salvarci')
  {
   $id = $_POST['id'];
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   $pestanapag='Editar Reconocimiento Inicial';
   $titulopagina='Editar reconocimiento inicial';
   $accion='';
   $boton = 'salvarci';
   try
   {
    $sql='UPDATE vib_recstbl SET
		  procedimiento=:procedimiento,
		  manto=:mantenimiento,
	      eqvibracionidfk=:eqvibracion,
		  acelerometroidfk=:acelerometro,
		  calibradoridfk=:calibrador
		 WHERE id=:id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id',$id);
	$s->bindValue(':procedimiento',$_POST['procedimiento']);
	$s->bindValue(':mantenimiento',$_POST['mantenimiento']);
	$s->bindValue(':eqvibracion',$_POST['eqvibracion']);
	$s->bindValue(':acelerometro',$_POST['acelerometro']);
	$s->bindValue(':calibrador',$_POST['calibrador']);
	$s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='Hubo un error al tratar de editar el reconocimiento inicial. Favor de intentar nuevamente.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
    try
    {
	$pdo->beginTransaction();
     $sql='DELETE FROM vib_idstbl WHERE vibrecidfk = :id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$id);
	 $s->execute();
	 $sql='DELETE FROM vib_poetbl WHERE vibrecidfk = :id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$id);
	 $s->execute();
	 $sql='DELETE FROM vib_producciontbl WHERE vibrecidfk = :id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$id);
	 $s->execute();
	 $sql='DELETE FROM vib_puestostbl WHERE vibrecidfk = :id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$id);
	 $s->execute();
	$pdo->commit();
    }
    catch (PDOException $e)
    {
	 $pdo->rollback();
     $mensaje='Hubo un error al tratar de eliminar los puestos. Favor de intentar nuevamente.';
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
    }
    try
    {
	$pdo->beginTransaction();
	  insertatablasrec($_POST,$id);
	$pdo->commit();
    }
    catch (PDOException $e)
    {
	$pdo->rollback();
     $mensaje='Hubo un error al tratar de agregar los nuevos puestos. Favor de intentar nuevamente.';
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
    }
	header('Location: .');
   exit();
  }
/******************************************************************/
/* ** Borrar un reconocimiento inicial de una orden de trabajo ** */
/******************************************************************/
  if (isset($_POST['accion']) and $_POST['accion']=='borrarci')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    $id=$_POST['id'];
	if (!vaciorci('vib_puntorectbl','vibrcidfk',$id)){
   	   $mensaje='Este reconocimiento inicial no puede ser borrado ya que tiene puntos asociados. ';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
   }else{
	try
	{
	 $sql='SELECT area, fuente FROM vib_idstbl
	 WHERE vibrecidfk = :idrec LIMIT 1';
	 $s=$pdo->prepare($sql); 
	 $s->bindValue(':idrec',$id);
   	 $s->execute();
	}
    catch (PDOException $e)
    {
     $mensaje='Hubo un error accesando al punto.  Favor de intentar nuevamente.';
	 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	 exit();
    }
     $resultado=$s->fetch();
	 $mensaje='Estas seguro de querer borrar el reconocimiento inicial, el cual  incluye el área '.$resultado['area'].' y la fuente '.$resultado['fuente'].'?';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/formas/formaconfirma.html.php';
    exit();
   }
  }
/*******************************************************************/
/**** Ver los reconocimientos iniciales de una orden de trabajo ****/
/*******************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='Cancelar borrado')
  {
   //Nota: este if se puede eliminar, solo lo deje para mostrar la estructura.
	header('Location: .');
	exit();
  } 
/***********************************************************************/
/******** Confirmación de borrado de un reconocimiento inicial *********/
/***********************************************************************/
  if (isset($_POST['accion']) and $_POST['accion']=='Continuar borrando')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
	$pdo->beginTransaction();
     $sql='DELETE FROM vib_recstbl WHERE id=:id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();
     $sql='DELETE FROM vib_idstbl WHERE vibrecidfk = :id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$id);
	 $s->execute();
	 $sql='DELETE FROM vib_poetbl WHERE vibrecidfk = :id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$id);
	 $s->execute();
	 $sql='DELETE FROM vib_producciontbl WHERE vibrecidfk = :id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$id);
	 $s->execute();
	 $sql='DELETE FROM vib_puestostbl WHERE vibrecidfk = :id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$id);
	 $s->execute();
	$pdo->commit();
   }
   catch (PDOException $e)
   {
    $pdo->rollback();
    $mensaje='Hubo un error borrando el reconocimiento. Intente de nuevo. ';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
   }
   header('Location: .');
   exit();
  }

/* ************** Ir a la opcion de puntos ********************* */
/* ************************************************************* */
  if((isset($_POST['accion']) and $_POST['accion']=='puntos'))
  {
	$_SESSION['idrci']=$_POST['id'];
	header('Location: http://'.$_SERVER['HTTP_HOST'].str_replace('?','',$_SERVER['REQUEST_URI']).'puntos');
    exit();
  }
/* *********** accion de inicio **************** */
/* ********************************************* */
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  if(isset($_SESSION['idot']) and isset($_SESSION['quien']) and $_SESSION['quien']=='Vibraciones mano-brazo'){
     $idot=$_SESSION['idot'];
	 if (isset($_SESSION['idrci'])){
	   unset($_SESSION['idrci']);
	 }
  }
  else {
	  header('Location: http://'.$_SERVER['HTTP_HOST'].str_replace('rci/','',$_SERVER['REQUEST_URI']));
  }
  $ot=otdeordenes($idot);
  $recsini=verRecs($idot);
  include 'formarci.html.php';
   exit();
/* ******************************************************* */
/* ***Funcion para inicializar un reconocimiento nuevo.*** */
function ininuevorci(){
  global $pestanapag,$titulopagina,$id,$accion,$boton,$procedimiento,$mantenimiento,$eqvibracion,$acelerometro,$calibrador;
  	$pestanapag='Agrega Reconocimiento Inicial';
    $titulopagina='Agregar un nuevo reconocimiento inicial';
	$id='';
    $accion='';
    $boton = 'guardarrci';
  	$procedimiento='';
	$mantenimiento='';
	$eqvibracion='';
	$acelerometro='';
	$calibrador='';
}
/* * Función para ver reconocimientos iniciales de una orden de trabajo * */
/* ********************************************************************** */
  function verRecs($idot = ""){
   global $pdo;
	$recsini=array();
   try   
   {
	$sql='SELECT id FROM vib_recstbl WHERE ordenidfk = :idot';
	$s=$pdo->prepare($sql); 
	$s->bindValue(':idot',$idot);
   	$s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='Hubo un error extrayendo la lista de reconocimientos iniciales.';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
   }
   $recs=$s->fetchall();
   foreach ($recs as $rec)
   {
	try
	{
	 $sql='SELECT area, fuente FROM vib_idstbl
	 WHERE vibrecidfk = :idrec LIMIT 1';
	 $s=$pdo->prepare($sql); 
	 $s->bindValue(':idrec',$rec['id']);
   	 $s->execute();
	}
    catch (PDOException $e)
    {
     $mensaje='Hubo un error extrayendo la lista de area y fuente.';
	 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	 exit();
    }
     $resultado1=$s->fetch();
	try
	{
	 $sql='SELECT nombre FROM vib_puestostbl
	 WHERE vibrecidfk = :idrec LIMIT 1';
	 $s=$pdo->prepare($sql); 
	 $s->bindValue(':idrec',$rec['id']);
   	 $s->execute();
	}
    catch (PDOException $e)
    {
     $mensaje='Hubo un error extrayendo la lista puestos.';
	 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	 exit();
    }
     $resultado2=$s->fetch();
	 $recsini[]=array('id'=>$rec['id'],'area'=>$resultado1['area'],
				 'nombre'=>$resultado2['nombre'],'fuente'=>$resultado1['fuente']);
	}
	if (empty($recsini)){
	  unset($recsini);
	  return;
	}
	else{
	  return $recsini;
	}
  }
/* **** Funcion para guardar informacion de rci de tablas satelite **** */
/* ******************************************************************** */ 
  
function insertatablasrec($post,$rcid){
  global $pdo;
	foreach ($post['ids'] as $id){
	 if($id['area']!='' and $id['fuente']!=''){
	  $sql='INSERT INTO vib_idstbl SET
		vibrecidfk=:rcid,
		area=:area,
		fuente=:fuente';
	  $s=$pdo->prepare($sql);
	  $s->bindValue('rcid',$rcid);
	  $s->bindValue('area',$id['area']);
	  $s->bindValue('fuente',$id['fuente']);
	  $s->execute();
	 }	
	}
	foreach ($post['poes'] as $poe){
	 if($poe['area']!='' and $poe['numero']!='' and $poe['expo']!=''){
	  $sql='INSERT INTO vib_poetbl SET
		vibrecidfk=:rcid,
		area=:area,
		numero=:numero,
		expo=:expo';
	  $s=$pdo->prepare($sql);
	  $s->bindValue('rcid',$rcid);
	  $s->bindValue('area',$poe['area']);
	  $s->bindValue('numero',$poe['numero']);
	  $s->bindValue('expo',$poe['expo']);
	  $s->execute();
	 }	
	}
	foreach ($post['produccion'] as $prod){
	 if($prod['depto']!='' and $prod['cnormales']!='' and $prod['preal']!=''){
	  $sql='INSERT INTO vib_producciontbl SET
		vibrecidfk=:rcid,
		depto=:depto,
		cnormales=:cnormales,
		preal=:preal';
	  $s=$pdo->prepare($sql);
	  $s->bindValue('rcid',$rcid);
	  $s->bindValue('depto',$prod['depto']);
	  $s->bindValue('cnormales',$prod['cnormales']);
	  $s->bindValue('preal',$prod['preal']);
	  $s->execute();
	 }	
	}
	foreach ($post['puestos'] as $puesto){
	 if($puesto['nombre']!='' and $puesto['descripcion']!='' and $puesto['ciclos']!=''){
	  $sql='INSERT INTO vib_puestostbl SET
		vibrecidfk=:rcid,
		nombre=:nombre,
		descripcion=:descripcion,
		ciclos=:ciclos';
	  $s=$pdo->prepare($sql);
	  $s->bindValue('rcid',$rcid);
	  $s->bindValue('nombre',$puesto['nombre']);
	  $s->bindValue('descripcion',$puesto['descripcion']);
	  $s->bindValue('ciclos',$puesto['ciclos']);
	  $s->execute();
	 }	
	}
}
?>