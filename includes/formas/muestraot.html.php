<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>OT</title>
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
   <h2>Información general del la OT <?php htmlout($ot); ?></h2>
	<form action="?" method="post">
	 <fieldset>
	   <legend>Informacion general</legend>
		<?php foreach ($datos as $indice=>$dato) : ?>
		  <p><?php htmlout($indice); ?>: <?php htmlout($dato); ?></p>
		<?php endforeach; ?>
	 </fieldset>
	 <fieldset>
	  <legend>Estudios en la orden</legend>
		<?php foreach ($informes as $estudio) : ?>
		  <p><?php htmlout($estudio); ?></p>
		<?php endforeach; ?> 
	  </fieldset>
	  <p>
	   <input type="hidden" name="id" value="<?php htmlout($id); ?>">
	   <input type="hidden" name="accion" value="verci">
	   <input type="submit"  value="Seguir">
	 </p>
	</form>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>