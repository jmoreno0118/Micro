<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Orden nueva</title>
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
   <h2>Alta de ordenes</h2>
   <p>Para que dar de alta una orden en el sistema de captura es necesario que antes se de alta administrativamente</p> 
   <?php if (isset($mensaje)): ?>
	 <P><strong><?php htmlout($mensaje) ?></strong></p>
   <?php endif; ?>	
    <form action="?contordenueva" method="post">
	  <div>
	    <label for="ot">Num. de OT: </label>
	    <input type="text" name="ot" id="ot" value="">
	  </div>
	  <div>	
	    <input type="submit"  value="Continuar">
	  </div> 
	</form>
  <p><a href="../ordenes">Regresa a busqueda de ordenes</a>	
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>