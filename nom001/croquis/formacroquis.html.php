<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>subir planos</title>
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
   <h2>OT <?php htmlout($nombreot['ot']) ?> croquis de la medición <?php htmlout($numedicion); ?></h2>
   <fieldset>
   <legend>Subir archivo</legend>
    <form action="?" method="post" enctype="multipart/form-data">
	 <div>
	  <label for="archivo">Selecciona el archivo a subir.... </label>
	  <input type="file" id="archivo" name="archivo">
	 </div>
	 <div>
	  <input type="hidden" name="ot" value="<?php htmlout($ot);?>">
	  <input type="hidden" name="id" value="<?php htmlout($id);?>">
	  <input type="hidden" name="update" value="<?php echo ($croquis !== false)? '1' : '0' ?>">
	  <input type="hidden" name="numedicion" value="<?php htmlout($numedicion);?>">
	  <input type="hidden" name="accion" value="subir">
	  <input type="submit" value="Subir">  
	 </div>
	 <p>Nota: Los archivos que se permite subir al sitema son <strong>JPEG, PNG, GIF</strong> y deben tener un tamaño MAXIMO <strong>2Mb</strong></p> 
	</form>
	<?php if($croquis !== false): ?>
	<div>
	 	Croquis actual:
	 	<br>
	 	<img height="265" width="624" src="<?php htmlout('http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/croquis/'.$croquis['nombrearchivado']); ?>">
	 </div>
	<?php endif; ?>
   </fieldset>
   <p><a href="../generales">Volver a mediciones</a></p>
  
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>