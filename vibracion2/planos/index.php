<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';
/* ******************************************************************
** Se invoca la forma para subir archivos y en caso de que haya    **
** algunos en la base de datos, estos se muestran.                 **
****************************************************************** */ 
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
  include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/adminplanos.php';
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  if(isset($_SESSION['idot']) and isset($_SESSION['quien']) and $_SESSION['quien']=='Vibraciones mano-brazo'){
	$idot=$_SESSION['idot'];
  }
  else {echo 'va de regreso';print_r($_SESSION);exit();
	  header('Location: http://'.$_SERVER['HTTP_HOST'].str_replace('planos/','',$_SERVER['REQUEST_URI']));
  }  
  datostabla();
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/formas/formasubirarch.html.php';
  exit();
?>