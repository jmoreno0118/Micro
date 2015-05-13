<?php
 //********** Vibracion-mano **********
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/funcioneshig.inc.php';
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
 // ********* esta es la busqueda de las ordenes abiertas *******
 // genera la lista de ordenes del representante
 // rutina de busqueda  
 if (isset($_GET['accion']) and $_GET['accion']=='buscar')
 {	
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  $estudio='Vibraciones mano-brazo';
  $tablatitulo='Ordenes de vibraciones solicitadas';
  $mensaje='Lo sentimos no se encontro nunguna orden con las caracteristicas solicitadas';
  if (isset($_GET['otsproceso']))
	 { $otsproceso=TRUE; }
  else
     { $otsproceso=FALSE; }
  if (isset($_GET['ot']))
	 { $ot=$_GET['ot']; }
  else
     { $ot=''; }  
  $ordenes=buscaordenes($estudio,$otsproceso,$ot);
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/formas/'.'formabuscaordeneshig.html.php';
  exit();
  }
/***********************************************************************************/
/* Ver datos de una orden de trabajo */
/***********************************************************************************/
/***********************************************************************************/
if((isset($_POST['accion']) and $_POST['accion']=='Ver OT'))
 {
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    $datos=ordendatos($_POST['id']);
	$informes=ordenestudios($_POST['id']);
	if (!isset($datos) or !isset($informes))
	  { exit(); }
	include include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/formas/'.'muestraot.html.php';
	exit();
 }	
	
/* Ver reconocimientos iniciales de una orden de trabajo */
/**************************************************************************************************/

  if((isset($_POST['accion']) and $_POST['accion']=='verci') or isset($_GET['volverci']))
  {
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    if (isset($_GET['volverci']))
	{
     verRecs($_GET['idot']);
	}
   else 
	 { verRecs($_POST['id']); }
  }

/**************************************************************************************************/
/* Ver reconocimientos iniciales de una orden de trabajo */
/**************************************************************************************************/

  if(isset($_GET['volverpts']))
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   verPuntos($_GET['idrci']);
  }
/**************************************************************************************************/
/* Capturar reconocimientos iniciales de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_GET['accion']) and $_GET['accion']=='capturarci')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  	$pestanapag='Agrega Reconocimiento Inicial';
    $titulopagina='Agregar un nuevo reconocimiento inicial';
    $accion='';
    $boton = 'guardarrci';
  	$valores = array("fecha" => "",
					"departamento" => "",
					"area" => "",
					"descriproceso" => "",
					"largo" => "",
					"ancho" => "",
					"alto" => "",
					"tipolampara" => "",
					"potencialamp" => "",
					"numlamp" => "",
					"alturalamp" => "",
					"techocolor" => "",
					"paredcolor" => "",
					"pisocolor" => "",
					"influencia" => "",
					"percepcion" => "");
	$idot=$_GET['idot'];
  	include 'formacapturarci.html.php';
  	exit();
  }

/**************************************************************************************************/
/* Editar reconocimiento inicial de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='editarci')
  {
  	$id = $_POST['id'];
  	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try   
	{
	 $sql='SELECT * FROM recsilumtbl
		   INNER JOIN deptorecilumtbl ON recsilumtbl.id=deptorecilumtbl.deptoidfk
		   INNER JOIN deptostbl ON deptorecilumtbl.deptoidfk=deptostbl.id
		   WHERE recsilumtbl.id = :id';
	 $s=$pdo->prepare($sql); 
	 $s->bindValue(':id',$_POST['id']);
   	 $s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='Hubo un error extrayendo la información de reconocimiento inicial.';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
   }
   foreach ($s as $linea)
   {
    $valores = array("fecha" => $linea["fecha"],
					"departamento" => $linea["departamento"],
					"area" => $linea["area"],
					"descriproceso" => $linea["descriproceso"],
					"largo" => $linea["largo"],
					"ancho" => $linea["ancho"],
					"alto" => $linea["alto"],
					"tipolampara" => $linea["tipolampara"],
					"potencialamp" => $linea["potencialamp"],
					"numlamp" => $linea["numlamp"],
					"alturalamp" => $linea["alturalamp"],
					"techocolor" => $linea["techocolor"],
					"paredcolor" => $linea["paredcolor"],
					"pisocolor" => $linea["pisocolor"],
					"influencia" => $linea["influencia"],
					"percepcion" => $linea["percepcion"]);
	$idot=$linea["ordenidfk"];
  }
  try   
  {
   $sql='SELECT descripuestostbl.puesto, descripuestostbl.numtrabajadores, descripuestostbl.actividades
   			 FROM descripuestostbl
	 		 INNER JOIN deptostbl ON descripuestostbl.deptoidfk = deptostbl.id
	 		 INNER JOIN deptorecilumtbl ON deptostbl.id = deptorecilumtbl.deptoidfk
	 		  WHERE deptorecilumtbl.recilumidfk = :id';
 	$s=$pdo->prepare($sql); 
	$s->bindValue(':id',$_POST['id']);
   	$s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='Hubo un error extrayendo la información de reconocimiento inicial.';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
   }
   foreach($s as $linea){
   	$puestos[] = array("puesto" => $linea["puesto"],
   					   "numtrabajadores" => $linea["numtrabajadores"],
   					    "actividades" => $linea["actividades"]);
   }
   $pestanapag='Editar Reconocimiento Inicial';
    $titulopagina='Editar reconocimiento inicial';
    $accion='';
    $boton = 'salvarci';
   include 'formacapturarci.html.php';
   exit();
  }
/* **************************************************************************
**  Se continua con el cambio de influencia.                               **
*****************************************************************************/
 if(isset($_POST['accion']) and $_POST['accion']=='Continua cambio')
 {
   $id = $_POST['id'];
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';

   try
   {
    $sql='UPDATE recsilumtbl SET
          fecha=:fecha,
		  largo=:largo,
		  ancho=:ancho,
		  alto=:alto,
		  tipolampara=:tipolampara,
		  potencialamp=:potencialamp,
		  numlamp=:numlamp,
		  alturalamp=:alturalamp,
		  techocolor=:techocolor,
		  paredcolor=:paredcolor,
		  pisocolor=:pisocolor,
		  influencia=:influencia,
		  percepcion=:percepcion
		 WHERE id=:id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id',$_POST['id']);
	$s->bindValue(':fecha',$_POST['fecha']);
	$s->bindValue(':largo',$_POST['largo']);
	$s->bindValue(':ancho',$_POST['ancho']);
	$s->bindValue(':alto',$_POST['alto']);
	$s->bindValue(':tipolampara',$_POST['tipolampara']);
 	$s->bindValue(':potencialamp',$_POST['potencialamp']);
	$s->bindValue(':numlamp',$_POST['numlamp']);
	$s->bindValue(':alturalamp',$_POST['alturalamp']);
	$s->bindValue(':techocolor',$_POST['techocolor']);
	$s->bindValue(':paredcolor',$_POST['paredcolor']);
	$s->bindValue(':pisocolor',$_POST['pisocolor']);
	$s->bindValue(':influencia',$_POST['influencia']);
	$s->bindValue(':percepcion',$_POST['percepcion']);
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
    $sql='UPDATE deptostbl 
    	 INNER JOIN deptorecilumtbl ON deptostbl.id=deptorecilumtbl.deptoidfk
    	 SET
          departamento=:departamento,
		  area=:area,
		  descriproceso=:descriproceso
		 WHERE deptorecilumtbl.recilumidfk=:id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id',$_POST['id']);
	$s->bindValue(':departamento',$_POST['departamento']);
	$s->bindValue(':area',$_POST['area']);
	$s->bindValue(':descriproceso',$_POST['descriproceso']);
	$s->execute();
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error al tratar de editar el departamento. Favor de intentar nuevamente.';
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
    }
    try
    {
     $sql="DELETE descripuestostbl FROM descripuestostbl
	 	  INNER JOIN deptostbl ON descripuestostbl.deptoidfk = deptostbl.id
	 	  INNER JOIN deptorecilumtbl ON deptostbl.id = deptorecilumtbl.deptoidfk
	 	  WHERE deptorecilumtbl.recilumidfk = :id";
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error al tratar de eliminar los puestos. Favor de intentar nuevamente.';
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
    }
    try
    {
     $sql="SELECT deptostbl.id FROM deptostbl 
	 INNER JOIN deptorecilumtbl ON deptostbl.id = deptorecilumtbl.deptoidfk WHERE deptorecilumtbl.recilumidfk = :id";
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();
	 $resultado=$s->fetch();
     foreach ($_POST["descpuestos"] as $key => $value) {
      if($value["puesto"] != "" && $value["numtrabajadores"] != "" && $value["actividades"]!="")
      {
	   $sql='INSERT INTO descripuestostbl SET
	         deptoidfk=:deptoidfk,
			 puesto=:puesto,
			 numtrabajadores=:numtrabajadores,
			 actividades=:actividades';
	   $s=$pdo->prepare($sql);
	   $s->bindValue(':deptoidfk', $resultado["id"]);
	   $s->bindValue(':puesto', $value["puesto"]);
	   $s->bindValue(':numtrabajadores', $value["numtrabajadores"]);
	   $s->bindValue(':actividades', $value["actividades"]);
	   $s->execute();
      }
     }
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error al tratar de agregar los puestos. Favor de intentar nuevamente.';
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
    }
	try
    {
	$pdo->beginTransaction(); 
     $sql='SELECT puntoidfk FROM puntorecilumtbl
	 WHERE recilumidfk = :id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();
	 $resultado=array();
	 foreach ($s as $linea)
	  {
		$sql='DELETE FROM medsilumtbl WHERE puntoidfk=:puntoid
			ORDER BY id DESC LIMIT 2';
		$s=$pdo->prepare($sql);
		$s->bindValue(':puntoid',$linea['puntoidfk']);
		$s->execute();
	 }		
	$pdo->commit();
	}
	catch (PDOException $e)
	{
     $pdo->rollback();
	 $mensaje='Hubo un error al seleccionar las mediciones que se borrarán. '.$e;
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();	
	}
   $idot=$_POST['idot'];
   verRecs($idot);
   exit();
 }
/* *****************************************************************************
**  Se cancela cambio de influencia.                                          **
*******************************************************************************/
 if(isset($_POST['accion']) and $_POST['accion']=='Cancela cambio')
 {
   $pestanapag='Editar Reconocimiento Inicial';
   $titulopagina='Editar reconocimiento inicial';
   $accion='';
   $boton = 'salvarci';
   $puestos=array();
   $id = $_POST['id'];
   $idot=$_POST['idot'];
   foreach($_POST['descpuestos'] as $linea){
   	$puestos[] = array("puesto" => $linea["puesto"],
   					   "numtrabajadores" => $linea["numtrabajadores"],
   					    "actividades" => $linea["actividades"]);
   }
   $valores = array("fecha" => $_POST["fecha"],
					"departamento" => $_POST["departamento"],
					"area" => $_POST["area"],
					"descriproceso" => $_POST["descriproceso"],
					"largo" => $_POST["largo"],
					"ancho" => $_POST["ancho"],
					"alto" => $_POST["alto"],
					"tipolampara" => $_POST["tipolampara"],
					"potencialamp" => $_POST["potencialamp"],
					"numlamp" => $_POST["numlamp"],
					"alturalamp" => $_POST["alturalamp"],
					"techocolor" => $_POST["techocolor"],
					"paredcolor" => $_POST["paredcolor"],
					"pisocolor" => $_POST["pisocolor"],
					"influencia" => 1,
					"percepcion" => $_POST["percepcion"]);
   include 'formacapturarci.html.php';
   exit();
 }
/*************** sigue modificacion 1 ************************/
function desglosapost($post="")
{ global $campos, $contenidos, $puestos, $numtrabajadores, $actividades;
  $campos=array();
  $contenidos=array();
  $puestos=array();
  $numtrabajadores=array();
  $actividades=array();
  foreach ($post as $campo=>$contenido)
  {
    if ($campo<>'descpuestos' and $campo<>'accion' and $campo<>'boton')
	{
	  $campos[]=$campo;
	  $contenidos[]=$contenido;
	}
  }
  foreach ($post['descpuestos'] as $descrip)
  {
	 if ($descrip['puesto']!="")
	 {
       $puestos[]=$descrip['puesto'];
	   $numtrabajadores[]=$descrip['numtrabajadores'];
	   $actividades[]=$descrip['actividades'];
	 }
	 else{ break; } 
  }
}

/**************************************************************************************************/
/* Guardar la edición de un reconocimiento inicial de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='salvarci')
  {
   $id = $_POST['id'];
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   $pestanapag='Editar Reconocimiento Inicial';
   $titulopagina='Editar reconocimiento inicial';
   $accion='';
   $boton = 'salvarci';
/* *******************
  inicia modificacion
******************** */
  try
  {
    $sql='SELECT influencia FROM recsilumtbl where id=:id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id',$id);
	$s->execute();
  }
  catch(PDOException $e)
  {
    $mensaje='Hubo un error al tratar de editar el reconocimiento inicial. Favor de intentar nuevamente.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();	
  }
  $influencianterior=$s->fetch();
  if ($influencianterior==True and $_POST['influencia']==false)
  {
    desglosapost($_POST);
    include 'formaconfirmacambiorci.html.php';
    exit();
  }
/* *******************
  termina modificacion
******************** */

   try
   {
    $sql='UPDATE recsilumtbl SET
          fecha=:fecha,
		  largo=:largo,
		  ancho=:ancho,
		  alto=:alto,
		  tipolampara=:tipolampara,
		  potencialamp=:potencialamp,
		  numlamp=:numlamp,
		  alturalamp=:alturalamp,
		  techocolor=:techocolor,
		  paredcolor=:paredcolor,
		  pisocolor=:pisocolor,
		  influencia=:influencia,
		  percepcion=:percepcion
		 WHERE id=:id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id',$_POST['id']);
	$s->bindValue(':fecha',$_POST['fecha']);
	$s->bindValue(':largo',$_POST['largo']);
	$s->bindValue(':ancho',$_POST['ancho']);
	$s->bindValue(':alto',$_POST['alto']);
	$s->bindValue(':tipolampara',$_POST['tipolampara']);
 	$s->bindValue(':potencialamp',$_POST['potencialamp']);
	$s->bindValue(':numlamp',$_POST['numlamp']);
	$s->bindValue(':alturalamp',$_POST['alturalamp']);
	$s->bindValue(':techocolor',$_POST['techocolor']);
	$s->bindValue(':paredcolor',$_POST['paredcolor']);
	$s->bindValue(':pisocolor',$_POST['pisocolor']);
	$s->bindValue(':influencia',$_POST['influencia']);
	$s->bindValue(':percepcion',$_POST['percepcion']);
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
    $sql='UPDATE deptostbl 
    	 INNER JOIN deptorecilumtbl ON deptostbl.id=deptorecilumtbl.deptoidfk
    	 SET
          departamento=:departamento,
		  area=:area,
		  descriproceso=:descriproceso
		 WHERE deptorecilumtbl.recilumidfk=:id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id',$_POST['id']);
	$s->bindValue(':departamento',$_POST['departamento']);
	$s->bindValue(':area',$_POST['area']);
	$s->bindValue(':descriproceso',$_POST['descriproceso']);
	$s->execute();
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error al tratar de editar el departamento. Favor de intentar nuevamente.';
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
    }
    try
    {
     $sql="DELETE descripuestostbl FROM descripuestostbl
	 	  INNER JOIN deptostbl ON descripuestostbl.deptoidfk = deptostbl.id
	 	  INNER JOIN deptorecilumtbl ON deptostbl.id = deptorecilumtbl.deptoidfk
	 	  WHERE deptorecilumtbl.recilumidfk = :id";
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error al tratar de eliminar los puestos. Favor de intentar nuevamente.';
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
    }
    try
    {
     $sql="SELECT deptostbl.id FROM deptostbl 
	 INNER JOIN deptorecilumtbl ON deptostbl.id = deptorecilumtbl.deptoidfk WHERE deptorecilumtbl.recilumidfk = :id";
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();
	 $resultado=$s->fetch();
     foreach ($_POST["descpuestos"] as $key => $value) {
      if($value["puesto"] != "" && $value["numtrabajadores"] != "" && $value["actividades"]!="")
      {
	   $sql='INSERT INTO descripuestostbl SET
	         deptoidfk=:deptoidfk,
			 puesto=:puesto,
			 numtrabajadores=:numtrabajadores,
			 actividades=:actividades';
	   $s=$pdo->prepare($sql);
	   $s->bindValue(':deptoidfk', $resultado["id"]);
	   $s->bindValue(':puesto', $value["puesto"]);
	   $s->bindValue(':numtrabajadores', $value["numtrabajadores"]);
	   $s->bindValue(':actividades', $value["actividades"]);
	   $s->execute();
      }
     }
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error al tratar de agregar los puestos. Favor de intentar nuevamente.';
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
    }
/********************************************************************/
//  voy a borrar esto porque muestra nuevamente la informacion que  //
//  se modific´y en su lugar pondré los reconocimientos de la orden //
/********************************************************************/  
 /*try   
   {$sql='';
	$select='SELECT descripuestostbl.puesto, descripuestostbl.numtrabajadores, descripuestostbl.actividades';
	$from=' FROM descripuestostbl
	 		 INNER JOIN deptostbl ON descripuestostbl.deptoidfk = deptostbl.id
	 		 INNER JOIN deptorecilumtbl ON deptostbl.id = deptorecilumtbl.deptoidfk';
	$where = " WHERE deptorecilumtbl.recilumidfk = :id";
	$sql=$select.$from.$where;
 	$s=$pdo->prepare($sql); 
	$s->bindValue(':id',$_POST['id']);
   	$s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='Hubo un error extrayendo la información de reconocimiento inicial.';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
   }
   foreach($s as $linea){
   	$puestos[] = array("puesto" => $linea["puesto"],
   					   "numtrabajadores" => $linea["numtrabajadores"],
   					    "actividades" => $linea["actividades"]);
   }
   $valores = array("fecha" => $_POST["fecha"],
					"departamento" => $_POST["departamento"],
					"area" => $_POST["area"],
					"descriproceso" => $_POST["descriproceso"],
					"largo" => $_POST["largo"],
					"ancho" => $_POST["ancho"],
					"alto" => $_POST["alto"],
					"tipolampara" => $_POST["tipolampara"],
					"potencialamp" => $_POST["potencialamp"],
					"numlamp" => $_POST["numlamp"],
					"alturalamp" => $_POST["alturalamp"],
					"techocolor" => $_POST["techocolor"],
					"paredcolor" => $_POST["paredcolor"],
					"pisocolor" => $_POST["pisocolor"],
					"influencia" => $_POST["influencia"],
					"percepcion" => $_POST["percepcion"]);
   include 'formacapturarci.html.php';*/
   $idot=$_POST['idot'];
   verRecs($idot);
   exit();
  }

/**************************************************************************************************/
/* Guardar un nuevo reconocimientos iniciales de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='guardarrci')
  {
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
//    $ot = $_POST['ot'];
//    $sql='SELECT id FROM ordenestbl WHERE ot=:ot';
//    $s=$pdo->prepare($sql);
//    $s->bindValue(':ot',$ot);
//    $s->execute();
//    $resultado=$s->fetch();
    $idot=$_POST['idot'];
   try
   {
   	$pdo->beginTransaction();
    $sql='INSERT INTO recsilumtbl SET
		 ordenidfk=:ordenid,
         fecha=:fecha,
		 largo=:largo,
		 ancho=:ancho,
		 alto=:alto,
		 tipolampara=:tipolampara,
		 potencialamp=:potencialamp,
		 numlamp=:numlamp,
		 alturalamp=:alturalamp,
		 techocolor=:techocolor,
		 paredcolor=:paredcolor,
		 pisocolor=:pisocolor,
		 influencia=:influencia,
		 percepcion=:percepcion';
	$s=$pdo->prepare($sql);
	$s->bindValue(':ordenid',$idot);
	$s->bindValue(':fecha',$_POST['fecha']);
	$s->bindValue(':largo',$_POST['largo']);
	$s->bindValue(':ancho',$_POST['ancho']);
	$s->bindValue(':alto',$_POST['alto']);
	$s->bindValue(':tipolampara',$_POST['tipolampara']);
	$s->bindValue(':potencialamp',$_POST['potencialamp']);
	$s->bindValue(':numlamp',$_POST['numlamp']);
	$s->bindValue(':alturalamp',$_POST['alturalamp']);
	$s->bindValue(':techocolor',$_POST['techocolor']);
	$s->bindValue(':paredcolor',$_POST['paredcolor']);
	$s->bindValue(':pisocolor',$_POST['pisocolor']);
	$s->bindValue(':influencia',$_POST['influencia']);
	$s->bindValue(':percepcion',$_POST['percepcion']);
	$s->execute();
	$rcid=$pdo->lastInsertId();
 
    $sql='INSERT INTO deptostbl SET
         departamento=:departamento,
		 area=:area,
		 descriproceso=:descriproceso';
	$s=$pdo->prepare($sql);
	$s->bindValue(':departamento',$_POST['departamento']);
	$s->bindValue(':area',$_POST['area']);
	$s->bindValue(':descriproceso',$_POST['descriproceso']);
	$s->execute();
	$deptoid=$pdo->lastInsertId();

    $sql='INSERT INTO deptorecilumtbl SET
         deptoidfk=:deptoidfk,
		 recilumidfk=:recilumidfk';
	$s=$pdo->prepare($sql);
	$s->bindValue(':deptoidfk', $deptoid);
	$s->bindValue(':recilumidfk',$rcid);
	$s->execute();

    foreach ($_POST["descpuestos"] as $key => $value) {
     if($value["puesto"] != "" && $value["numtrabajadores"] != "" && $value["actividades"]!="")
     {
	  $sql='INSERT INTO descripuestostbl SET
	         deptoidfk=:deptoidfk,
			 puesto=:puesto,
			 numtrabajadores=:numtrabajadores,
			 actividades=:actividades';
	  $s=$pdo->prepare($sql);
	  $s->bindValue(':deptoidfk', $deptoid);
	  $s->bindValue(':puesto', $value["puesto"]);
	  $s->bindValue(':numtrabajadores', $value["numtrabajadores"]);
	  $s->bindValue(':actividades', $value["actividades"]);
	  $s->execute();
     }
    }
    $pdo->commit();
   }
   catch (PDOException $e)
   {
   	$pdo->rollback();
    $mensaje='Hubo un error al tratar de insertar el reconocimiento. Favor de intentar nuevamente.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   $pestanapag='Agrega Reconocimiento Inicial';
   $titulopagina='Agregar un nuevo reconocimiento inicial';
   $accion='';
   $boton = 'guardarrci';
   $valores = array("fecha" => "",
					"departamento" => $_POST["departamento"],
					"area" => $_POST["area"],
					"descriproceso" => "",
					"largo" => "",
					"ancho" => "",
					"alto" => "",
					"tipolampara" => "",
					"potencialamp" => "",
					"numlamp" => "",
					"alturalamp" => "",
					"techocolor" => "",
					"paredcolor" => "",
					"pisocolor" => "",
					"influencia" => "",
					"percepcion" => "");
   include 'formacapturarci.html.php';
   exit();
  }

/* ******************************************************************
** Es cuando se desea subir un plano a al sistema                  **
****************************************************************** */
if (isset($_POST['accion']) and $_POST['accion']=='Planos')
{ 
  $_SESSION['idot']=$_POST['id'];
  $_SESSION['quien']='iluminacion';
  //$parent = dirname($_SERVER['SERVER_ADDR']);
  header('Location: http://'.$_SERVER['HTTP_HOST'].str_replace('?','',$_SERVER['REQUEST_URI']).'planos');
  exit();  
}
/**************************************************************************************************/
/* Ver los puntos de un reconocimient inicial de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='puntos' || $_POST['accion']=='Cancelar borrar punto')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   verPuntos($_POST['id']);
  }
/**************************************************************************************************/
/* Crear un nuevo punto es un reconocimient inicial de una orden de trabajo */
/**************************************************************************************************/
//  if(isset($_POST['accion']) and $_POST['accion']=='nuevopunto')
  if (isset($_GET['nuevopunto']))
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   	$valores = array("nomedicion" => "",
					 "fecha" => "",
					 "departamento" => "",
					 "area" => "",
					 "ubicacion" => "",
					 "identificacion" => "",
					 "observaciones" => "",
					 "nirm" => "");
	$idrci=$_GET['idrci'];
	$id="";				 
  	formularioPuntos('Agrega Punto', 'Agregar un nuevo punto', 'guardarpunto', $idrci, $id, $valores);
  }

/**************************************************************************************************/
/* Guardar un nuevo punto y mediciones de un reconocimiento inicial de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='guardarpunto')
  {
   $idrci = $_POST['idrci'];
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
		 identificacion=:identificacion,
		 observaciones=:observaciones,
		 nirm=:nirm';
	$s=$pdo->prepare($sql);
	$s->bindValue(':nomedicion',$_POST['nomedicion']);
	$s->bindValue(':fecha',$_POST['fecha']);
	$s->bindValue(':departamento',$_POST['departamento']);
	$s->bindValue(':area',$_POST['area']);
	$s->bindValue(':ubicacion',$_POST['ubicacion']);
	$s->bindValue(':identificacion',$_POST['identificacion']);
	$s->bindValue(':observaciones',$_POST['observaciones']);
	$s->bindValue(':nirm',$_POST['nirm']);
	$s->execute();
	$puntosid=$pdo->lastInsertId();

	$sql='INSERT INTO puntorecilumtbl SET
         puntoidfk=:puntoidfk,
		 recilumidfk=:recilumidfk';
	$s=$pdo->prepare($sql);
	$s->bindValue(':puntoidfk', $puntosid);
	$s->bindValue(':recilumidfk',$idrci);
	$s->execute();

    foreach ($_POST["mediciones"] as $key => $value) {
     if($value["hora"] != "" && $value["e1plano"]!="" && $value["e2plano"]!="")
     {
	  $sql='INSERT INTO medsilumtbl SET
	         puntoidfk=:puntoidfk,
			 hora=:hora,
			 e1pared=:e1pared,
			 e2pared=:e2pared,
			 e1plano=:e1plano,
			 e2plano=:e2plano';
	   $s=$pdo->prepare($sql);
	   $s->bindValue(':puntoidfk', $puntosid);
	   $s->bindValue(':hora', $value["hora"]);
	   $s->bindValue(':e1pared', $value["e1pared"]);
	   $s->bindValue(':e2pared', $value["e2pared"]);
	   $s->bindValue(':e1plano', $value["e1plano"]);
	   $s->bindValue(':e2plano', $value["e2plano"]);
	   $s->execute();
      }
     }
     $pdo->commit();
    }
    catch (PDOException $e)
    {
     $pdo->rollback();
     $mensaje='Hubo un error al tratar de agregar las mediciones. Favor de intentar nuevamente.'.$e;
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
    }
  	$valores = array("nomedicion" => "",
					 "fecha" => "",
					 "departamento" => $_POST['departamento'],
					 "area" => $_POST['area'],
					 "ubicacion" => "",
					 "identificacion" => "",
					 "observaciones" => "",
					 "nirm" => "");
	$idrci=$_POST['idrci'];
	$id="";
  	formularioPuntos('Agrega Punto', 'Agregar un nuevo punto', 'guardarpunto', $idrci, $id, $valores);
  }


/**************************************************************************************************/
/* Editar un punto de un reconocimiento inicial de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='editarpunto')
  {
  	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try   
	{
	 $sql='SELECT *
	 	   FROM puntostbl
		   WHERE id = :id';
	 $s=$pdo->prepare($sql); 
	 $s->bindValue(':id',$_POST['id']);
   	 $s->execute();

     $punto = $s->fetch();

     $sql='SELECT medsilumtbl.hora, medsilumtbl.e1pared, medsilumtbl.e2pared, medsilumtbl.e1plano, medsilumtbl.e2plano
	 	   FROM medsilumtbl
	 	   INNER JOIN puntostbl ON medsilumtbl.puntoidfk = puntostbl.id
	 	   WHERE puntostbl.id = :id';
 	 $s=$pdo->prepare($sql); 
	 $s->bindValue(':id',$_POST['id']);
   	 $s->execute();
    }
    catch (PDOException $e) 
    {
     $mensaje='Hubo un error extrayendo la información del punto.';
	 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	 exit();
    }
    foreach($s as $linea){
   	 $mediciones[] = array("hora" => $linea["hora"],
   					       "e1pared" => $linea["e1pared"],
   					       "e2pared" => $linea["e2pared"],
   					       "e1plano" => $linea["e1plano"],
   					       "e2plano" => $linea["e2plano"]);
    }
  	$valores = array("nomedicion" => $punto['medicion'],
					 "fecha" => $punto['fecha'],
					 "departamento" => $punto['departamento'],
					 "area" => $punto['area'],
					 "ubicacion" => $punto['ubicacion'],
					 "identificacion" => $punto['identificacion'],
					 "observaciones" => $punto['observaciones'],
					 "nirm" => $punto['nirm']);	
	$idrci=idrecdepuntos($_POST['id']);
	$idot=idotdeidrci($idrci);
	$ot=otdeordenes($idot);
	
  	formularioPuntos('Editar Punto', 'Editar un punto de la OT. '.$ot, 'salvarpunto',  $idrci, $_POST['id'], $valores, $mediciones);
  }

/**************************************************************************************************/
/* Guardar la edición de un punto de un reconocimiento inicial de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='salvarpunto')
  {
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
		 identificacion=:identificacion,
		 observaciones=:observaciones,
		 nirm=:nirm
		 WHERE id = :id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':nomedicion',$_POST['nomedicion']);
	$s->bindValue(':fecha',$_POST['fecha']);
	$s->bindValue(':departamento',$_POST['departamento']);
	$s->bindValue(':area',$_POST['area']);
	$s->bindValue(':ubicacion',$_POST['ubicacion']);
	$s->bindValue(':identificacion',$_POST['identificacion']);
	$s->bindValue(':observaciones',$_POST['observaciones']);
	$s->bindValue(':nirm',$_POST['nirm']);
	$s->bindValue(':id',$_POST['id']);
	$s->execute();

    $sql="DELETE FROM medsilumtbl
	 	   WHERE puntoidfk = :id";
	$s=$pdo->prepare($sql);
	$s->bindValue(':id',$_POST['id']);
	$s->execute();

    foreach ($_POST["mediciones"] as $key => $value) {
     if($value["hora"] != "" && $value["e1plano"]!="" && $value["e2plano"]!="")
     {
	  $sql='INSERT INTO medsilumtbl SET
	         puntoidfk=:puntoidfk,
			 hora=:hora,
			 e1pared=:e1pared,
			 e2pared=:e2pared,
			 e1plano=:e1plano,
			 e2plano=:e2plano';
	  $s=$pdo->prepare($sql);
	  $s->bindValue(':puntoidfk', $_POST['id']);
	  $s->bindValue(':hora', $value["hora"]);
	  $s->bindValue(':e1pared', $value["e1pared"]);
	  $s->bindValue(':e2pared', $value["e2pared"]);
	  $s->bindValue(':e1plano', $value["e1plano"]);
	  $s->bindValue(':e2plano', $value["e2plano"]);
	  $s->execute();
     }
    }
    $pdo->commit();
   }
   catch (PDOException $e)
   {
   	$pdo->rollback();
    $mensaje='Hubo un error al tratar de actualizar punto. Favor de intentar nuevamente.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   try   
   {
   	$sql='SELECT *
	 	  FROM medsilumtbl 
	 	  WHERE puntoidfk = :id';
 	$s=$pdo->prepare($sql); 
	$s->bindValue(':id',$_POST['id']);
   	$s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='Hubo un error extrayendo la información de mediciones.';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
   }
   foreach($s as $linea){
   /*programa original
   $mediciones[] = array("hora" => $linea["hora"],
   					   "e1pared" => $linea["e1pared"],
   					   "e2pared" => $linea["e2pared"],
   					   "e1plano" => $linea["e1plano"],
   					   "e2plano" => $linea["e2plano"]);*/
	// modificacion
   $mediciones[] = array("hora" => '',
   					   "e1pared" => '',
   					   "e2pared" => '',
   					   "e1plano" => '',
   					   "e2plano" => '');	
   }
/*  programa original
  	$valores = array("nomedicion" => $_POST['nomedicion'],
					 "fecha" => $_POST['fecha'],
					 "departamento" => $_POST['departamento'],
					 "area" => $_POST['area'],
					 "ubicacion" => $_POST['ubicacion'],
					 "identificacion" => $_POST['identificacion'],
					 "observaciones" => $_POST['observaciones'],
					 "nirm" => $_POST['nirm']); */
	// modificacion
  	$valores = array("nomedicion" => '',
					 "fecha" => $_POST['fecha'],
					 "departamento" => $_POST['departamento'],
					 "area" => $_POST['area'],
					 "ubicacion" => '',
					 "identificacion" => '',
					 "observaciones" => '',
					 "nirm" => ''); 
	$idrci=idrecdepuntos($_POST['id']);
	$id='';
  	formularioPuntos('Agrega Punto', 'Agregar un nuevo punto', 'guardarpunto', $idrci, $id, $valores, $mediciones);
  }

/**************************************************************************************************/
/* Borrar un punto de un reconocimiento inicial de una orden de trabajo */
/**************************************************************************************************/
  if (isset($_POST['accion']) and $_POST['accion']=='borrarpunto')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php'; 
   try
   {
    $sql='SELECT medicion, fecha, departamento, area, identificacion FROM puntostbl WHERE id=:id';
    $s= $pdo->prepare($sql);
    $s->bindValue(':id',$_POST['id']); 
    $s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='No se pudo hacer al confirmacion de eliminación'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   $id=$_POST['id'];
   $idrci=idrecdepuntos($_POST['id']);
   $resultado=$s->fetch();
   $medicion=$resultado['medicion'];
   $fecha=$resultado['fecha'];
   $departamento=$resultado['departamento'];
   $area=$resultado['area'];
   $identificacion=$resultado['identificacion'];
   include 'formaconfirmapuntos.html.php';
   exit();
  }

/**************************************************************************************************/
/* Confirmación de borrado de un punto de un reconocimiento inicial de una orden de trabajo */
/**************************************************************************************************/
  if (isset($_POST['accion']) and $_POST['accion']=='Continuar borrando punto')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
   	$pdo->beginTransaction();
     $sql='DELETE FROM puntorecilumtbl WHERE puntoidfk=:id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();

     $sql='DELETE FROM medsilumtbl WHERE puntoidfk=:id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();

     $sql='DELETE FROM puntostbl WHERE id=:id';
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
   verPuntos($_POST['idrci']);
  }
/**************************************************************************************************/
/* cancelación de borrado de un punto de un reconocimiento inicial de una orden de trabajo */
/**************************************************************************************************/
  if (isset($_POST['accion']) and $_POST['accion']=='Cancela borra punto')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
/*   try
   {
   	$pdo->beginTransaction();
     $sql='DELETE FROM puntorecilumtbl WHERE puntoidfk=:id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['idpunto']);
	 $s->execute();

     $sql='DELETE FROM medsilumtbl WHERE puntoidfk=:id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['idpunto']);
	 $s->execute();

     $sql='DELETE FROM puntostbl WHERE id=:id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['idpunto']);
	 $s->execute();
	$pdo->commit();
   }
   catch (PDOException $e)
   {
   	$pdo->rollback();
    $mensaje='Hubo un error borrando el punto. Intente de nuevo. '.$e;
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
   } */
   $idrci=idrecdepuntos($_POST['id']);
//   echo $idrci.' idrci '.$_POST['id'].' id';exit();
   verPuntos($_POST['idrci']);
  }

/**************************************************************************************************/
/* Borrar un reconocimiento inicial de una orden de trabajo */
/**************************************************************************************************/
  if (isset($_POST['accion']) and $_POST['accion']=='borrarci')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    $id=$_POST['id'];
	try
   {
    $sql='SELECT COUNT(*) as Puntos FROM puntorecilumtbl WHERE recilumidfk=:id';
    $s= $pdo->prepare($sql);
    $s->bindValue(':id',$id); 
    $s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='No se pudo hacer el conteo de puntos'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
	$cuenta = $s->fetch();
   if($cuenta["Puntos"] > 0){
   	$mensaje='Este reconocimiento inicial no puede ser borrado ya que tiene puntos. ';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
   }else{
   	$sql='SELECT recsilumtbl.fecha, deptostbl.departamento, deptostbl.area
   	      FROM recsilumtbl
		  INNER JOIN deptorecilumtbl ON recsilumtbl.id = deptorecilumtbl.recilumidfk
		  INNER JOIN deptostbl ON deptorecilumtbl.deptoidfk = deptostbl.id
		  WHERE recsilumtbl.id = :id';
	$s=$pdo->prepare($sql); 
    $s->bindValue(':id',$id);
   	$s->execute();
    $resultado=$s->fetch();

    $fecha=$resultado['fecha'];
    $departamento=$resultado['departamento'];
    $area=$resultado['area'];
    include 'formaconfirmarci.html.php';
    exit();
   }
  }
/**************************************************************************************************/
/* Ver los reconocimientos iniciales de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='Cancelar borrar rec')
  {
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  { $sql='SELECT id, ordenidfk FROM recsilumtbl
		  WHERE  id=:id';
	$s=$pdo->prepare($sql); 
    $s->bindValue(':id',$_POST['id']);
   	$s->execute();
  }
  catch(PDOException $e)
  {
	$mensaje='Hubo un error mostando los reconocimientos iniciales que siguen en la orden. Intente de nuevo. ';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
  }	
   $resultado=$s->fetch();
   $ot=$resultado['ordenidfk'];
   verRecs($ot);
  }
/**************************************************************************************************/
/* Confirmación de borrado de un reconocimiento inicial de una orden de trabajo */
/**************************************************************************************************/
  if (isset($_POST['accion']) and $_POST['accion']=='Continuar borrando rec')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
   // recupero el num. de ot para regresar a los reconocimientos
   	$pdo->beginTransaction();
   	$sql='SELECT id, ordenidfk FROM recsilumtbl
		  WHERE  id=:id';
	$s=$pdo->prepare($sql); 
    $s->bindValue(':id',$_POST['id']);
   	$s->execute();
    $resultado=$s->fetch();
    $ot=$resultado['ordenidfk'];
    // fin de la modificacion
     $sql='DELETE FROM descripuestostbl WHERE deptoidfk IN (SELECT deptoidfk FROM deptorecilumtbl WHERE recilumidfk = :id)';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();

     $sql='DELETE FROM deptostbl WHERE id IN (SELECT deptoidfk FROM deptorecilumtbl WHERE recilumidfk = :id)';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute(); 

     $sql='DELETE FROM deptorecilumtbl WHERE recilumidfk=:id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();

     $sql='DELETE FROM recsilumtbl WHERE id=:id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();
	$pdo->commit();
   }
   catch (PDOException $e)
   {
   	$pdo->rollback();
    $mensaje='Hubo un error borrando el reconocimiento. Intente de nuevo. '.$e;
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
   }
   verRecs($ot);
  }

/**************************************************************************************************/
/* Acción por defualt, llevar a búsqueda de ordenes */
/**************************************************************************************************/
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  $estudio='"Vibraciones mano-brazo"';
  $otsproceso=TRUE;
  $tablatitulo='Ordenes de vibraciones en proceso';
  $mensaje='no hay ordenes abiertas de vibraciones';
  $ordenes=buscaordenes($estudio,$otsproceso,'');

  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/formas/'.'formabuscaordeneshig.html.php';
  exit();

/**************************************************************************************************/
/* Función para ver reconocimientos iniciales de una orden de trabajo */
/**************************************************************************************************/
  function verRecs($id = ""){
   global $pdo;
   try   
   {
	$sql='SELECT recsilumtbl.id, deptostbl.departamento, deptostbl.area, deptostbl.descriproceso
		  FROM recsilumtbl
		  INNER JOIN ordenestbl ON ordenidfk=ordenestbl.id
		  INNER JOIN deptorecilumtbl ON recsilumtbl.id=deptorecilumtbl.deptoidfk
		  INNER JOIN deptostbl ON deptorecilumtbl.deptoidfk=deptostbl.id
		  WHERE recsilumtbl.ordenidfk = :id';
	$s=$pdo->prepare($sql); 
	$s->bindValue(':id',$id);
   	$s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='Hubo un error extrayendo la lista de reconocimientos iniciales.';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
   }

   foreach ($s as $linea)
   {
    $recsini[]=array('id'=>$linea['id'],'departamento'=>$linea[				'departamento'],
				 'area'=>$linea['area'],'descriproceso'=>$linea['descriproceso']);
   }
   $idot=$id;
   $ot=otdeordenes($id);
   include 'formarci.html.php';
   exit();
  }


/**************************************************************************************************/
/* Función para ver puntos de un reconocimiento inicial */
/**************************************************************************************************/
  function verPuntos($id = ""){  
   global $pdo;
   try
   {
	$sql='SELECT puntostbl.id, puntostbl.departamento, puntostbl.area, puntostbl.identificacion
	 	  FROM puntostbl
		  INNER JOIN puntorecilumtbl ON puntostbl.id=puntorecilumtbl.puntoidfk
		  WHERE puntorecilumtbl.recilumidfk = :id';
	$s=$pdo->prepare($sql); 
	$s->bindValue(':id',$id);
   	$s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='Hubo un error extrayendo la lista de puntos.';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php'.$e;
	exit();
   }

   foreach ($s as $linea)
   {
    $puntos[]=array('id'=>$linea['id'],'departamento'=>$linea['departamento'],
				'area'=>$linea['area'],'identificacion'=>$linea['identificacion']);
   } 
   $idrci=$id;
   $idot=idotdeidrci($idrci);
   $ot=otderecsilum($idrci);
   include 'formarpuntos.html.php';
   exit();
  }

/**************************************************************************************************/
/* Función para ir a formulario de puntos de un reconocimiento inicial */
/**************************************************************************************************/
  function formularioPuntos($pestanapag="", $titulopagina="", $boton="", $idrci="", $id="", $valores="", $meds="", $accion=""){
    global $pdo;
	try   
	{
	 $sql='SELECT influencia FROM recsilumtbl
		   WHERE recsilumtbl.id = :id';
	 $s=$pdo->prepare($sql); 
	 $s->bindValue(':id', $idrci);
   	 $s->execute();
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error extrayendo la influencia.';
	 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	 exit();
    }
    $influencia = $s->fetch();
    $nmediciones = $influencia['influencia'] == 0 ? 1 : 3; 
/*	echo 'para este estudio tantas mediciones '.$nmediciones.'<br>'.',fluencia '.$influencia['influencia']; exit(); */
    if($meds !== ""){
     $mediciones = $meds;
    }
    $idot=idotdeidrci($idrci);
  	include 'formacapturarpuntos.html.php';
  	exit();
  }
/**************************************************************************************************/
/* Función obtener el numero de OT a partir del id de recsilumtbl */
/**************************************************************************************************/
  function otderecsilum($id="")
  {
  	global $pdo;
	try   
	{
	 $sql='SELECT ot FROM ordenestbl
			INNER JOIN recsilumtbl on ordenidfk=ordenestbl.id
		   WHERE recsilumtbl.id = :id';
	 $s=$pdo->prepare($sql); 
	 $s->bindValue(':id', $id);
   	 $s->execute();
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error extrayendo el numero de OT.';
	 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	 exit();
    }
    $resultado = $s->fetch();
    return $resultado['ot']; 
  }
/**************************************************************************************************/
/* Función obtener el numero de OT a partir del id de puntos */
/**************************************************************************************************/
  function otdepuntos($id="")
  {
  	global $pdo;
	try   
	{
	 $sql='SELECT ot FROM ordenestbl
			INNER JOIN recsilumtbl ON recsilumtbl.ordenidfk=ordenestbl.id
			INNER JOIN puntorecilumtbl ON recsilumtbl.id=puntorecilumtbl.recilumidfk
		   WHERE puntorecilumtbl.puntoidfk = :id';
	 $s=$pdo->prepare($sql); 
	 $s->bindValue(':id', $id);
   	 $s->execute();
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error extrayendo el numero de OT.';
	 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	 exit();
    }
    $resultado = $s->fetch();
    return $resultado['ot']; 
  }
/**************************************************************************************************/
/* Función obtener el numero de id de recsilumtbl a partir del id de puntos */
/**************************************************************************************************/
  function idrecdepuntos($id="")
  {
  	global $pdo;
	try   
	{
	 $sql='SELECT recilumidfk FROM puntorecilumtbl
		
		INNER JOIN puntostbl ON puntostbl.id=puntorecilumtbl.puntoidfk
		   WHERE puntostbl.id = :id';
	 $s=$pdo->prepare($sql); 
	 $s->bindValue(':id', $id);
   	 $s->execute();
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error extrayendo informacion del reconocimiento.'.$e;
	 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	 exit();
    }
    $resultado = $s->fetch();
    return $resultado['recilumidfk']; 
  }
/**************************************************************************************************/
/* Función obtener el numero de OT  partir del id de ordenestbl */
/**************************************************************************************************/
  function otdeordenes($id="")
  {
  	global $pdo;
	try   
	{
	 $sql='SELECT ot FROM ordenestbl WHERE id = :id';
	 $s=$pdo->prepare($sql); 
	 $s->bindValue(':id', $id);
   	 $s->execute();
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error extrayendo informacion de la orden.';
	 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	 exit();
    }
    $resultado = $s->fetch();
    return $resultado['ot']; 
  }
/**************************************************************************************************/
/* Función obtener el numero de id de ordenes partir del ot */
/**************************************************************************************************/
  function iddeordenes($ot="")
  {
  	global $pdo;
	try   
	{
	 $sql='SELECT id FROM ordenestbl WHERE ot = :ot';
	 $s=$pdo->prepare($sql); 
	 $s->bindValue(':ot', $ot);
   	 $s->execute();
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error extrayendo informacion de la orden.';
	 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	 exit();
    }
    $resultado = $s->fetch();
    return $resultado['id']; 
  }
/**************************************************************************************************/
/* Función obtener el numero de idot a partir de idrci */
/**************************************************************************************************/
  function idotdeidrci($idrci="")
  {
  	global $pdo;
	try   
	{
	 $sql='SELECT ordenidfk FROM recsilumtbl WHERE  id= :id';
	 $s=$pdo->prepare($sql); 
	 $s->bindValue(':id', $idrci);
   	 $s->execute();
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error extrayendo informacion de la orden.';
	 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	 exit();
    }
    $resultado = $s->fetch();
    return $resultado['ordenidfk']; 
  }
  function limpiasession(){
   if (isset($_SESSION['idot'])){
     unset($_SESSION['idot']);
   }
   if (isset($_SESSION['quien'])){
	 unset($_SESSION['quien']);
	} 
  }
?>