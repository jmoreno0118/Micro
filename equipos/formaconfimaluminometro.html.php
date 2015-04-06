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
     <h2>Confirmación de borrado del luminometro</h2>
     <fieldset>
      <legend>Borrar</legend>
	   <form action="" method="post">.
	   <div>
	     <p>Estas seguro de que deseas borrar el luminometro de marca: <?php htmlout($marca); ?>, 
		   modelo: <?php htmlout($modelo); ?>, y número de serie: <?php htmlout($serie);?>.</p>
		  <input type="hidden" name="id" value="<?php htmlout($id); ?>">
		  <input type="submit" name="accion" value="Cancela borrar luminometro">
		  <input type="submit" name="accion" value="Continuar borrando luminometro">
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
 