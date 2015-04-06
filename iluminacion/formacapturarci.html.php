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
   <p>formacapturarci</p>
   <?php
   		$formulario = array("Fecha" => "fecha",
   							"Departamento" => "departamento",
   							"Área" => "area",
   							"Descripción del proceso" => "descriproceso",
   							"Largo" => "largo",
   							"Ancho" => "ancho",
   							"Alto" => "alto",
   							"Tipo de lámpara" => "tipolampara",
   							"Potencia de lámpara" => "potencialamp",
   							"Número de lámpara" => "numlamp",
   							"Altura de lámpara" => "alturalamp",
   							"Color de Techo" => "techocolor",
   							"Color de Pared" => "paredcolor",
   							"Color de Piso" => "pisocolor",
   							"Influencia" => "influencia",
   							"Percepción" => "percepcion");
   ?>
    <form action="?<?php htmlout($accion); ?>" method="post">
    	<?php foreach($formulario as $label => $name): ?>
    	<div>
    		<label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
	    	<input type="text" name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>" value="<?php htmlout($valores[$name]); ?>">
    	</div>
    	<?php endforeach?>
	  <fieldset>
	  <legend>Descripción de puestos:</legend>
	    <?php for ($i=0; $i<20; $i++) :?>
	    <div>
	    	<label for="descpuestos[<?php echo $i; ?>]">Descripción:</label>
			<input type="text" name="descpuestos[<?php echo $i; ?>][puesto]" id="descpuestos[<?php echo $i; ?>]" value="<?php isset($puestos[$i]) ? htmlout($puestos[$i]["puesto"]) : ""; ?>">

			<label for="numtrabajadores[<?php echo $i; ?>]">Número de trabajadores:</label>
			<input type="text" name="descpuestos[<?php echo $i; ?>][numtrabajadores]" id="numtrabajadores[<?php echo $i; ?>]" value="<?php isset($puestos[$i]) ? htmlout($puestos[$i]["numtrabajadores"]) : ""; ?>">

			<label for="actividades[<?php echo $i; ?>]">Actividades:</label>
			<input type="text" name="descpuestos[<?php echo $i; ?>][actividades]" id="actividades[<?php echo $i; ?>]" value="<?php isset($puestos[$i]) ? htmlout($puestos[$i]["actividades"]) : ""; ?>">
	    </div>
	  <?php endfor; ?>
	  </fieldset>	  
	  <div>
	    <input type="hidden" name="id" value="<?php htmlout($id); ?>">
		<input type="hidden" name="idot" value="<?php htmlout($idot); ?>">
		<input type="hidden" name="accion" value="<?php htmlout($boton); ?>">
	    <input type="submit" name="boton" value="Guardar">
	  </div> 
	</form>
	<p><a href="?volverci&amp;idot=<?php htmlout($idot); ?>">Regresa a los reconocimientos iniciales de la orden</a></p>
	<p><a href="../iluminacion">Regresa a la búsqueda de ordenes</a></p>
<!--	
  <form action="" method="post">
      <input type="hidden" name="id" value="<?php //htmlout($_SESSION['OT']); ?>">
      <input type="submit" name="accion" value="volverci">
  </form>
-->  
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>