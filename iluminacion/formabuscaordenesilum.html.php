<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Captura</title>
  <meta charset="utf-8" />
  <!--[if lt IE 9]>
   <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]--> 
   <link rel="stylesheet" type="text/css" href="/reportes/estilo.css" />
</head>
<body>
<div id="contenedor">
  <header>
   <?php 
      $ruta='/reportes/img/logoblco2.gif';
	  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/encabezado.inc.php'; ?>
  </header>
  <div id="cuerpoprincipal">
   <h2>Administrador de ordenes</h2>
   <p>formabuscaordenesilum</p>
	<form action="?" method="get">
	 <p>Buscar a una orden de acuerdo con los siguientes criterios</p>
	 <div>
	  <label for="ot">Num de OT:</label>
	  <input type="text" name="ot" id="ot">
	 </div>
	 <div>
	  <input type="checkbox" name="otsproceso" id="otsproceso" checked>
	  <label for="otsproceso">Ordenes en proceso</label>
	 </div>
	 <div>
	   <input type="hidden" name="accion" value="buscar">
	   <input type="submit" value="buscar">
	 </div>
	</form>
   <p><a href="..">Regresa a administrador</a></p>
   </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>