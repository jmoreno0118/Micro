 <?php
  include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Confirma</title>
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
    <div id="confirma">
     <h2>Confirmación de borrado del punto</h2>
	 <p>formaconfirmapuntos</p>
     <fieldset>
      <legend>Borrar</legend>
	   <form action="?" method="post">.
	   <div>
	     <p>Estas seguro de que deseas borrar al punto con número de medición: <?php htmlout($medicion); ?>, 
		   con fecha: <?php htmlout($fecha); ?>, ubicado en el departamento: <?php htmlout($departamento);?>,
       el área: <?php htmlout($area);?> y con identificación: <?php htmlout($identificacion);?>.</p>
		  <input type="hidden" name="id" value="<?php htmlout($id); ?>">
          <input type="hidden" name="idrci" value="<?php htmlout($idrci); ?>">
		  <input type="submit" name="accion" value="Cancela borra">
		  <input type="submit" name="accion" value="Continuar borrando">
		 </div>
		</form> 
	  </fieldset> 
     </div> <!-- confirma -->
   </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>
 