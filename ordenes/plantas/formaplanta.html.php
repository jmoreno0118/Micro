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
   <h2><?php htmlout($titulopagina.$razonsocial); ?></h2>
   <?php $formulario = array("Planta" => "planta",
							"Calle" => "calle",
							"Colonia" => "colonia",
							"Ciudad" => "ciudad",
							"Estado" => "estado",
							"Código Postal" => "cp",
							"RFC" => "rfc"); ?>
    <form id="plantaform" name="plantaform"  action="" method="post" onsubmit="return false;">
      	<?php foreach($formulario as $label => $name): ?>
      	<div>
      		<label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
  	    	<input type="text" name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>" value="<?php if(isset($valores)){htmlout($valores[$name]);} ?>">
      	</div>
      	<?php endforeach?>
	  <div>
	  	<input type="hidden" name="razonsocial" value="<?php htmlout($razonsocial); ?>">
	  	<?php if(isset($_POST['idcliente'])){ ?>
	    <input type="hidden" name="idcliente" value="<?php htmlout($_POST['idcliente']); ?>">
	    <?php } ?>
	    <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
	  </div> 
	</form>
<script language="javascript" type="text/javascript">
function closeWindow() {
	window.open('','_parent','');
	window.close();
}
</script> 
 <button type="button" onclick="closeWindow();">No guardar planta</button>
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
    $("#plantaform").validate({
      rules: {
        razonsocial: {
         required: true
        },
        planta: {
         required: true
        },
        calle: {
         required: true
        },
        colonia: {
         required: true
        },
        ciudad: {
         required: true
        },
        estado: {
         required: true
        },
        cp: {
         required: true
        },
        rfc: {
         required: true
        },
     	idcliente: {
         required: true
        }
      },
      success: "valid",
      submitHandler: function(form) {
      	$.ajax({
	        data: $("#plantaform").serialize(),
	        type: 'post', 
	        url: 'guardaplanta.php',
	        success: function(response) {
	            if (response === "true") {
	                alert("La planta ha sido guardada. Favor de refrescar el listado en la pantalla anterior. Esta pantalla se cerrará");
	                closeWindow();
	            } else {
	            	console.log(response);
					alert("La planta no pude ser guardada.");
	            }
	        },
	        error: function(response) {
	            alert("ERROR: La planta no pude ser guardada.");
	        }
	    });
      }
    });
  });</script>