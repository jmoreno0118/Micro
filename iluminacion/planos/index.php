<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/adminplanos.php';
/* ******************************************************************
** Se invoca la forma para subir archivos y en caso de que haya    **
** algunos en la base de datos, estos se muestran.                 **
****************************************************************** */  
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  if(isset($_SESSION['idot']) and isset($_SESSION['quien']) and $_SESSION['quien']=='Iluminacion'){
	$idot=$_SESSION['idot'];
  }
  else {
	  header('Location: http://'.$_SERVER['HTTP_HOST'].str_replace('planos/','',$_SERVER['REQUEST_URI']));
  }  
  datostabla();
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/formas/formasubirarch.html.php';
  exit();
?>