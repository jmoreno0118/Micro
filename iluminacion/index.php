<?php
 //********** iluminacion **********
 //include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
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
	$estudio='Iluminacion';
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
if((isset($_POST['accion']) and $_POST['accion']=='Ver OT'))
 {
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	$datos=ordendatos($_POST['id']);
	$informes=ordenestudios($_POST['id']);
	if (!isset($datos) or !isset($informes))
		{ exit(); }
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/formas/'.'muestraot.html.php';
	exit();
 }	
	
/* Ver reconocimientos iniciales de una orden de trabajo */
/**************************************************************************************************/

  if((isset($_POST['accion']) and $_POST['accion']=='verci'))
  {
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    $_SESSION['idot']=$_POST['id'];
    $_SESSION['quien']='Iluminacion';
	$idot=$_POST['id'];
	header('Location: http://'.$_SERVER['HTTP_HOST'].str_replace('?','',$_SERVER['REQUEST_URI']).'rci');
    exit();
  }

/**************************************************************************************************/
/* Ver reconocimientos iniciales de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_GET['volverpts']))
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   verPuntos($_GET['idrci']);
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
/* Acción por defualt, llevar a búsqueda de ordenes */
/**************************************************************************************************/
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  $estudio='Iluminacion';
  $otsproceso=TRUE;
  $tablatitulo='Ordenes de iluminacion en proceso';
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

    try   
	{
	 $sql='SELECT * FROM equipostbl WHERE tipo = "Luminometro"';
	 $s=$pdo->prepare($sql);
   	 $s->execute();
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error extrayendo la influencia.';
	 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	 exit();
    }
    $luminometros = $s->fetchAll();

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
?>