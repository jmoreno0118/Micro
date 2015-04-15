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
   <p>formacapturapunto</p>
   <?php
       $forma=array('Número de medición' => 'nomedicion',
					'Fecha' => 'fecha',
					'Departamento' => 'departamento',
					'Área' => 'area',
					'Ubicación' => 'ubicacion',
					'Puesto' => 'puesto',
					'Identificación' => 'identificacion',
					'Evento' => 'evento',
					'tipo de evento' => 'tipoevento',
					'Ciclos' => 'ciclos',
					'Duración (tiempo en min.)' => 'duracion',
					'Herramienta' => 'herramienta'); ?>
    <form action="?<?php htmlout($accion); ?>" method="post">
    	<?php foreach($forma as $etiqueta => $valor): ?>
    	<div>
    		<label for="<?php htmlout($valor); ?>"><?php htmlout($etiqueta); ?>:</label>
	    	<input type="text" name="<?php htmlout($valor); ?>" id="<?php htmlout($valor); ?>" value="<?php isset($dato[$valor]) ? htmlout($dato[$valor]) : ''; ?>">
    	</div>
    	<?php endforeach;?>
	  <fieldset>
	  <legend>Mediciones:</legend>
	    	<label for="med1">Medicion 1</label>
			<input type="text" name="med1" id="med1" value="<?php isset($med1) ? htmlout($med1) : htmlout(''); ?>">
			
			<label for="med2">Medicion 2</label>
			<input type="text" name="med2" id="med2" value="<?php isset($med2) ? htmlout($med2) : htmlout(''); ?>">
			
			<label for="med3">Medicion 3</label>
			<input type="text" name="med3" id="med3" value="<?php isset($med3) ? htmlout($med3) : htmlout(''); ?>">
	  </fieldset>
	<div>	
	    <input type="hidden" name="id" value="<?php htmlout($id); ?>">
	    <input type="submit" name="boton" value="Guardar">	
	</div> 
   </form>
	<p><a href="../puntos">Regresa los puntos del reconociminento</a></p>
	<p><a href="../">Regresa los reconocimientos de la orden</a></p>
	    <p><a href="../../">Regresa al búsqueda de ordenes</a></p>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>