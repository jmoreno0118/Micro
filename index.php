<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';
  if (!usuarioRegistrado())
  {
    include 'registro.html.php';
	exit();
  }
  include 'menuPrincipal.html.php';
  exit();
?>