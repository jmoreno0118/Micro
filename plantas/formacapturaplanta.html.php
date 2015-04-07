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
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery-1.11.1.js"></script>
   <link rel="stylesheet" type="text/css" href="/reportes/estilo.css" />
<script type="text/javascript">
$(document).ready(function(){

  $("#razonsocial").val($("#cliente option:selected").html());

  $("#cliente").change(function(){
    $("#razonsocial").val($("#cliente option:selected").html());
  });
});
</script>
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
   <?php $formulario = array("Planta" => "planta",
							"Calle" => "calle",
							"Colonia" => "colonia",
							"Ciudad" => "ciudad",
							"Estado" => "estado",
							"Código Postal" => "cp",
							"RFC" => "rfc"); ?>
    <form id="plantaform" name="plantaform"  action="" method="post">
      <label for="cliente">Razón Social: </label>
      <select name="cliente" id="cliente" >
        <option value="">Seleciona cliente</option>
        <?php foreach($clientes as $cliente): ?>
         <option value="<?php echo $cliente['id']; ?>"
            <?php if ($cliente['id']==$valores['Numero_Clienteidfk'])
            {echo ' selected';}?>><?php echo $cliente['nombre']; ?></option>
        <?php endforeach; ?>
       </select>
       <input type="hidden" name="razonsocial" id="razonsocial" value="">
      	<?php foreach($formulario as $label => $name): ?>
      	<div>
      		<label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
  	    	<input type="text" name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>" value="<?php if(isset($valores)){htmlout($valores[$name]);} ?>">
      	</div>
      	<?php endforeach?>
	  <div>
      <?php if(isset($id)){ ?>
      <input type="hidden" name="id" value="<?php htmlout($id); ?>">
      <?php } ?>
	    <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
	  </div> 
	</form>
  <form action="" method="post">
      <input type="submit" name="accion" value="Volver">
  </form>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>
<link rel="stylesheet" href="../../includes/jquery-validation-1.13.1/demo/site-demos.css">
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
      success: "valid"
    });
  });</script>