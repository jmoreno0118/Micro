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
   <?php
   		$formulario = array("Grasas y Aceites" => "GyA",
                          "Coliformes Fecales" => "coliformes",
             							"Solidos sedimentables" => "ssedimentables",
                          "Solidos suspendidos" => "ssuspendidos",
                          "DBO" => "dbo",
                          "Nitrogeno" => "nitrogeno",
                          "Fosforo" => "fosforo",
                          "Arsenico" => "arsenico",
                          "Cadmio" => "cadmio",
                          "Cianuros" => "cianuros",
                          "Cobre" => "cobre",
                          "Cromo" => "cromo",
                          "Mercurio" => "mercurio",
                          "Niquel" => "niquel",
                          "Plomo" => "plomo",
                          "Zinc" => "zinc",
                          "Huevos de Helminto" => "hdehelminto");
   ?>
    <form action="" method="post">
    	<?php foreach($formulario as $label => $name): ?>
    	<div>
    		<label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
	    	<input type="text" name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>" value="<?php if(isset($valores)){htmlout($valores[$name]);} ?>">
    	</div>
    	<?php endforeach?>
	  <div>
	    <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
      <p><a href="../../">Regresa a administrador</a></p>
	  </div>
	</form>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>