<?php

  require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';
  if (!usuarioRegistrado())
  {
    include 'registro.html.php';
	exit();
  }
   if (isset($_SESSION['idot'])){
     unset($_SESSION['idot']);
   }
   if (isset($_SESSION['quien'])){
	 unset($_SESSION['quien']);
	}
  include 'menuPrincipal.html.php';
  exit();
?>