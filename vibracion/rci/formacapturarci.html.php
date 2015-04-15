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
   <p>formacapturavibracion</p>
   
    <form action="?<?php htmlout($accion); ?>" method="post">
	  <fieldset>
	  <legend>Identificación de lugares donde existe exposición a vibraciones:</legend>
	    <?php for ($i=0; $i<10; $i++) :?>
	    <div>
	    	<label for="area[<?php echo $i; ?>]">Área:</label>
			<input type="text" name="ids[<?php echo $i; ?>][area]" id="area[<?php echo $i; ?>]" value="<?php isset($ids[$i]) ? htmlout($ids[$i]["area"]) : ""; ?>">

			<label for="fuente[<?php echo $i; ?>]">Identificación de la fuente:</label>
			<input type="text" name="ids[<?php echo $i; ?>][fuente]" id="fuente[<?php echo $i; ?>]" value="<?php isset($ids[$i]) ? htmlout($ids[$i]["fuente"]) : ""; ?>">
	    </div>
	  <?php endfor; ?>
	  </fieldset>
	  <div>
    	<label for="procedimiento">Descripción de procedimientos de operación de maquinaria, herramientas, materiales usados y equipos del proceso, así como las condiciones que pudieran alterar las características de las vibraciones:</label>
	    <input type="text" name="procedimiento" id="procedimiento" value="<?php htmlout($procedimiento); ?>">
      </div>
	  <fieldset>
	  <legend>Descripción de los puestos de trabajo para determinar la exposición.</legend>
	    <?php for ($i=0; $i<10; $i++) :?>
	    <div>
			<label for="nombre[<?php echo $i; ?>]">Puesto:</label>
			<input type="text" name="puestos[<?php echo $i; ?>][nombre]" id="nombre[<?php echo $i; ?>]" value="<?php isset($puestos[$i]) ? htmlout($puestos[$i]["nombre"]) : ""; ?>">

	    	<label for="descripcion[<?php echo $i; ?>]">Descripción:</label>
			<input type="text" name="puestos[<?php echo $i; ?>][descripcion]" id="descripcion[<?php echo $i; ?>]" value="<?php isset($puestos[$i]) ? htmlout($puestos[$i]["descripcion"]) : ""; ?>">
			
			<label for="ciclos[<?php echo $i; ?>]">Ciclos:</label>
			<input type="text" name="puestos[<?php echo $i; ?>][ciclos]" id="ciclos[<?php echo $i; ?>]" value="<?php isset($puestos[$i]) ? htmlout($puestos[$i]["ciclos"]) : ""; ?>">
	    </div>
	  <?php endfor; ?>
	  </fieldset>
	  <div>
    	<label for="mantenimiento">Programas de mantenimiento de maquinaria y equipos generadores de vibración:</label>
	    <input type="text" name="mantenimiento" id="mantenimiento" value="<?php htmlout($mantenimiento); ?>">
      </div>
	  <fieldset>
	  <legend>Registros de producción:</legend>
	    <?php for ($i=0; $i<10; $i++) :?>
	    <div>
			<label for="depto[<?php echo $i; ?>]">Depto/area:</label>
			<input type="text" name="produccion[<?php echo $i; ?>][depto]" id="depto[<?php echo $i; ?>]" value="<?php isset($produccion[$i]) ? htmlout($produccion[$i]["depto"]) : ""; ?>">

	    	<label for="cnormales[<?php echo $i; ?>]">En condiciones normales:</label>
			<input type="text" name="produccion[<?php echo $i; ?>][cnormales]" id="cnormales[<?php echo $i; ?>]" value="<?php isset($produccion[$i]) ? htmlout($produccion[$i]["cnormales"]) : ""; ?>">
			
			<label for="preal[<?php echo $i; ?>]">Producción real:</label>
			<input type="text" name="produccion[<?php echo $i; ?>][preal]" id="preal[<?php echo $i; ?>]" value="<?php isset($produccion[$i]) ? htmlout($produccion[$i]["preal"]) : ""; ?>">
	    </div>
	  <?php endfor; ?>
	  </fieldset>
	  <fieldset>
	  <legend>Número de POE por área y por proceso de trabajo y tiempos de exposición:</legend>
	    <?php for ($i=0; $i<10; $i++) :?>
	    <div>
			<label for="poearea[<?php echo $i; ?>]">Área y/o proceso:</label>
			<input type="text" name="poes[<?php echo $i; ?>][area]" id="poearea[<?php echo $i; ?>]" value="<?php isset($poes[$i]) ? htmlout($poes[$i]["area"]) : ""; ?>">

	    	<label for="poenumero[<?php echo $i; ?>]">Número de trabajadores:</label>
			<input type="text" name="poes[<?php echo $i; ?>][numero]" id="poenumero[<?php echo $i; ?>]" value="<?php isset($poes[$i]) ? htmlout($poes[$i]["numero"]) : ""; ?>">
			
			<label for="expo[<?php echo $i; ?>]">Tiempo de exposición:</label>
			<input type="text" name="poes[<?php echo $i; ?>][expo]" id="expo[<?php echo $i; ?>]" value="<?php isset($poes[$i]) ? htmlout($poes[$i]["expo"]) : ""; ?>">
	    </div>
	  <?php endfor; ?>
	  </fieldset>
	  <p>
	    Datos de los instumentos:
	  </p>
	  <div>
	    <label for="eqvibracion">Serie de equipo de vibraciones:</label>
		<input type="text" name="eqvibracion" id="eqvibracion" value="<?php htmlout($eqvibracion); ?>">
	  </div>
	  <div>
	    <label for="acelerometro">Serie acelerómetro:</label>
		<input type="text" name="acelerometro" id="acelerometro" value="<?php htmlout($acelerometro); ?>">
	  </div>
	  <div>
	    <label for="calibrador">Serie de Calibrador:</label>
		<input type="text" name="calibrador" id="calibrador" value="<?php htmlout($calibrador); ?>">
	  </div>
	  <div>
	    <input type="hidden" name="id" value="<?php htmlout($id); ?>">
		<input type="hidden" name="accion" value="<?php htmlout($boton); ?>">
	    <input type="submit" name="boton" value="Guardar">
	  </div> 
	</form>
	<p><a href="../rci">Regresa a los reconocimientos iniciales de la orden</a></p>
	<p><a href="../../vibracion">Regresa a la búsqueda de ordenes</a></p>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>