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
    <form id="limitesform" action="" method="post">
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
<link rel="stylesheet" href="../../includes/jquery-validation-1.13.1/demo/site-demos.css">
<script type="text/javascript" src="../../includes/jquery-validation-1.13.1/lib/jquery.js"></script>
<script type="text/javascript" src="../../includes/jquery-validation-1.13.1/lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="../../includes/jquery-validation-1.13.1/dist/jquery.validate.js"></script>
<script type="text/javascript" src="../../includes/jquery-validation-1.13.1/dist/additional-methods.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
   jQuery.validator.addMethod('permitido', function (value, element, param) {
    return /^(\d{1,2}\.\d{1,4})$/.test(value); 
   }, 'Ingresar de 1 a 4 decimales.');

    $("#limitesform").validate({
      rules: {
        GyA: {
          required: true,
          permitido: true
        },
        coliformes: {
          required: true,
          permitido: true
        },
        ssedimentables: {
          required: true,
          permitido: true
        },
        ssedimentables: {
          required: true,
          permitido: true
        },
        ssuspendidos: {
         required: true,
         permitido: true
        },
        dbo: {
         required: true,
         permitido: true
        },
        /*nkjedahl: {
         required: true,
         permitido: true
        },
        nitritos: {
         required: true,
         permitido: true
        },
        nitratos: {
         required: true,
         permitido: true
        },*/
        nitrogeno: {
         required: true,
         permitido: true
        },
        fosforo: {
         required: true,
         permitido: true
        },
        arsenico: {
         required: true,
         permitido: true
        },
        cadmio: {
         required: true,
         permitido: true
        },
        cianuros: {
         required: true,
         permitido: true
        },
        cobre: {
         required: true,
         permitido: true
        },
        cromo: {
         required: true,
         permitido: true
        },
        mercurio: {
         required: true,
         permitido: true
        },
        niquel: {
         required: true,
         permitido: true
        },
        plomo: {
         required: true,
         permitido: true
        },
        zinc: {
         required: true,
         permitido: true
        },
        hdehelminto: {
         required: true,
         permitido: true
        }
      },
      success: "valid"
    });
  });</script>