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
    <form action="?<?php htmlout($accion); ?>" method="post">
	  <div>
	    <label for="usuario">Nombre de usuario: </label>
	    <input type="text" name="usuario" id="usuario" value="<?php htmlout($usuario); ?>">
	  </div>
	  <div>
	    <label for="nombre">Nombre: </label>
	    <input type="text" name="nombre" id="nombre" value="<?php htmlout($nombre); ?>">
	  </div>
	  <div>
	    <label for="nombre">Apellidos: </label>
	    <input type="text" name="apellido" id="apellido" value="<?php htmlout($apellido); ?>">
	  </div>
	  <div>
	    <label for="correo">Correo electónico: </label>
	    <input type="text" name="correo" id="correo" value="<?php htmlout($correo); ?>">
	  </div>
	  <div>
	    <label for="clave">Clave de acceso</label>
	    <input type="password" name="clave" id="clave">
	  </div>
	  <fieldset>
	  <legend>Permisos:</legend>
	    <?php for ($i=0; $i<count($actividades); $i++) :?>
	    <div>
	      <label for="actividad<?php echo $i; ?>">
		  <input type="checkbox" name="actividades[]" id="actividad<?php echo $i; ?>" 
                value="<?php htmlout($actividades[$i]['id']); ?>"
				<?php if ($actividades[$i]['seleccionada'])
				 {echo ' checked';}?>>		
		        <?php htmlout($actividades[$i]['id']); ?>
		   </label>:<?php htmlout($actividades[$i]['descripcion']); ?>
	    </div>
	  <?php endfor; ?>
	  </fieldset>
	  <fieldset>
	  <legend>Representantes:</legend>
	    <?php for ($i=0; $i<count($representantes); $i++) :?>
	    <div>
	      <label for="representante<?php echo $i; ?>">
		  <input type="checkbox" name="representantes[]" id="representante<?php echo $i; ?>" 
                value="<?php htmlout($representantes[$i]['id']); ?>"
				<?php if ($representantes[$i]['seleccionada'])
				 {echo ' checked';}?>>		
		        <?php htmlout($representantes[$i]['nombre']); ?>
		   </label>
	    </div>
	  <?php endfor; ?>
	  </fieldset>	  
	  <div>	
	    <input type="hidden" name="id" value="<?php htmlout($id); ?>">
	    <input type="submit"  value="<?php htmlout($boton); ?>">
	  </div> 
	</form>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>