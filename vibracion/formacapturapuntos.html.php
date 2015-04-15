<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title><?php htmlout($pestanapag); ?></title>
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
   <h2><?php htmlout($titulopagina); ?></h2>
   <p>formacapturapuntos</p>
   <?php
   		$formulario = array("Número de medición" => "nomedicion",
             							"Fecha" => "fecha",
             							"Departamento" => "departamento",
             							"Área" => "area",
             							"Ubicación" => "ubicacion",
             							"Identificación" => "identificacion",
             							"Puesto" => "puesto",
             							"Descripción del evento" => "eventos",
										"Num. de eventos en el día" => "ciclos",
										"Duración del evento (min)" => "duracion",
										"Herramienta" => "herramienta",
										"Tipo de evento" => "evento");
   ?>
    <form action="?<?php htmlout($accion); ?>" method="post">
    	<?php foreach($formulario as $etiqueta => $nombre): ?>
    	<div>
    		<label for="<?php htmlout($nombre); ?>"><?php htmlout($etiqueta); ?>:</label>
	    	<input type="text" name="<?php htmlout($nombre); ?>" id="<?php htmlout($nombre); ?>" value="<?php htmlout($valores[$nombre]); ?>">
    	</div>
    	<?php endforeach?>
	  <fieldset>
	  <legend>Mediciones:</legend>
	    <?php for ($i=0; $i<3; $i++) :?>
	    	<label for="medicion[<?php echo $i; ?>]">Med. <?php echo $i+1 ?>:</label>
			<input type="text" name="mediciones[<?php echo $i; ?>]" id="medicion[<?php echo $i; ?>]" value="<?php isset($mediciones[$i]) ? htmlout($mediciones[$i]) : ""; ?>">
	  <?php endfor; ?>
	  </fieldset>
	<div>	
	    <input type="hidden" name="id" value="<?php htmlout($id); ?>">
	    <input type="submit" name="boton" value="Guardar">	
	</div> 
   </form>
	<p><a href="../puntos">Regresa los puntos del reconociminento</a></p>
	<p><a href="../">Regresa los reconocimientos de la orden</a></p>
	    <p><a href="../../">Regresa al búsqueda de ordenes</a></p>
<!--  <form action="" method="post">
      <input type="hidden" name="id" value="<?php //htmlout($id); ?>">
      <input type="submit" name="accion" value="volverci">
  </form> -->
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>