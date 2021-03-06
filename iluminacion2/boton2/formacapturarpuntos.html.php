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
             							"Observaciones" => "observaciones",
             							"NIRM" => "nirm");
   ?>
    <form action="?<?php htmlout($accion); ?>" method="post">
    	<?php foreach($formulario as $label => $name): ?>
    	<div>
    		<label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
	    	<input type="text" name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>" value="<?php htmlout($valores[$name]); ?>">
    	</div>
    	<?php endforeach?>
	  <fieldset>
	  <legend>Mediciones:</legend>
	    <?php for ($i=0; $i<$nmediciones; $i++) :?>
	    <div>
	    	<label for="medicioneshora[<?php echo $i; ?>]">Hora:</label>
			<input type="text" name="mediciones[<?php echo $i; ?>][hora]" id="medicioneshora[<?php echo $i; ?>]" value="<?php isset($mediciones[$i]) ? htmlout($mediciones[$i]["hora"]) : ""; ?>">

			<label for="medicionese1pared[<?php echo $i; ?>]">E1 Pared:</label>
			<input type="text" name="mediciones[<?php echo $i; ?>][e1pared]" id="medicionese1pared[<?php echo $i; ?>]" value="<?php isset($mediciones[$i]) ? htmlout($mediciones[$i]["e1pared"]) : ""; ?>">

      <label for="medicionese2pared[<?php echo $i; ?>]">E2 Pared:</label>
      <input type="text" name="mediciones[<?php echo $i; ?>][e2pared]" id="medicionese2pared[<?php echo $i; ?>]" value="<?php isset($mediciones[$i]) ? htmlout($mediciones[$i]["e2pared"]) : ""; ?>">

			<label for="medicionese1plano[<?php echo $i; ?>]">E1 Plano:</label>
      <input type="text" name="mediciones[<?php echo $i; ?>][e1plano]" id="medicionese1plano[<?php echo $i; ?>]" value="<?php isset($mediciones[$i]) ? htmlout($mediciones[$i]["e1plano"]) : ""; ?>">

      <label for="medicionese2plano[<?php echo $i; ?>]">E2 Plano:</label>
      <input type="text" name="mediciones[<?php echo $i; ?>][e2plano]" id="medicionese2plano[<?php echo $i; ?>]" value="<?php isset($mediciones[$i]) ? htmlout($mediciones[$i]["e2plano"]) : ""; ?>">
	    </div>
	  <?php endfor; ?>
	  </fieldset>
	<div>	
	    <input type="hidden" name="id" value="<?php htmlout($id); ?>">
		<input type="hidden" name="idrci" value="<?php htmlout($idrci); ?>">
		<input type="hidden" name="accion" value="<?php htmlout($boton); ?>">
	    <input type="submit" name="boton" value="Guardar">	
	</div> 
   </form>
   <form action="?" method="post">
    <div>
	  <input type="hidden" name="idrci" value="<?php htmlout($idrci); ?>">
	  <input type="hidden" name="accion" value="volverpts">
	  <input type="submit" name="boton" value="Regresa a los puntos">
	</div>
   </form>
   <form action="?" method="post">
    <div>
	  <input type="hidden" name="idot" value="<?php htmlout($idot); ?>">
	  <input type="hidden" name="accion" value="volverci">
	  <input type="submit" name="boton" value="Regresa a los rec. ini.">
	</div>
   </form>
<!--	<p><a href="?volverpts&amp;idrci=<?php htmlout($idrci); ?>">Regresa los puntos del reconociminento</a></p>
	<p><a href="?volverci&amp;idot=<?php htmlout($idot); ?>">Regresa los reconocimientos de la orden</a></p>
	    <p><a href="../iluminacion">Regresa al búsqueda de ordenes</a></p>
  <form action="" method="post">
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