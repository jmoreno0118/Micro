<?php
 //********** Vibracion-mano **********
// include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
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

/***********************************************************************************/
/* Buscar ordenes */
/***********************************************************************************/
if (isset($_GET['accion']) and $_GET['accion']=='buscar')
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';

  if (isset($_SESSION['terminada']))
  {
    unset($_SESSION['terminada']);
  }
  if (isset($_SESSION['supervisada']))
  {
    unset($_SESSION['supervisada']);
  }
  $tablatitulo='Ordenes de Vibraciones Mano - Brazo';
  $otsproceso = (isset($_GET['otsproceso']))? TRUE : FALSE;
  $supervisada = (isset($_GET['supervisada']))? TRUE : FALSE;
  $ot = (isset($_GET['ot']))? $_GET['ot'] : '';
  $ordenes=buscaordenes('Vibraciones mano-brazo', $otsproceso, $ot, $supervisada);
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/formas/'.'formabuscaordeneshig.html.php';
  exit();
}
 
/**************************************************************************************************/
/* Ver datos de una orden de trabajo */
/**************************************************************************************************/
if((isset($_POST['accion']) and $_POST['accion']=='Ver OT'))
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  $datos=ordendatos($_POST['id']);
	$informes=ordenestudios($_POST['id']);
	if (!isset($datos) or !isset($informes))
  {
    exit();
  }
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/formas/'.'muestraot.html.php';
	exit();
}

/**************************************************************************************************/
/* Ver reconocimientos iniciales de una orden de trabajo */
/**************************************************************************************************/
  if((isset($_POST['accion']) and $_POST['accion']=='verci'))
  {
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    $_SESSION['idot']=$_POST['id'];
    $_SESSION['quien']='Vibraciones mano-brazo';
    $idot=$_POST['id'];
    header('Location: http://'.$_SERVER['HTTP_HOST'].str_replace('?','',$_SERVER['REQUEST_URI']).'rci');
    exit();
  }  
/**************************************************************************************************/
/* Es para cuando se desea subir un plano a al sistema */
/**************************************************************************************************/
if (isset($_POST['accion']) and $_POST['accion']=='Planos')
{ 
  $_SESSION['idot']=$_POST['id'];
  $_SESSION['quien']='Vibraciones mano-brazo';
  header('Location: http://'.$_SERVER['HTTP_HOST'].str_replace('?','',$_SERVER['REQUEST_URI']).'planos');
  exit();  
}

/**************************************************************************************************/
/* Acción por defualt, llevar a búsqueda de ordenes */
/**************************************************************************************************/
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  if(isset($_SESSION['terminada']))
  {
    unset($_SESSION['terminada']);
  }
  if(isset($_SESSION['supervisada']))
  {
    unset($_SESSION['supervisada']);
  } 
  $tablatitulo='Ordenes de Vibraciones Mano - Brazo';
  $ordenes=buscaordenes('Vibraciones mano-brazo', TRUE);
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/formas/'.'formabuscaordeneshig.html.php';
  exit();
?>