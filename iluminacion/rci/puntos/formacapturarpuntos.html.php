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
             							"Fecha (aaaa-mm-dd)" => "fecha",
             							"Departamento" => "departamento",
             							"Área" => "area",
             							"Ubicación" => "ubicacion",
             							"Identificación" => "identificacion",
             							"Observaciones" => "observaciones");
      $nirms = array("50", "100", "200", "300", "500", "750", "1000", "2000");
   ?>
    <form id="puntoform" name="puntoform" action="?<?php htmlout($accion); ?>" method="post">
    	<?php foreach($formulario as $label => $name): ?>
    	<div>
    		<label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
	    	<input style="width:400px;" type="text" name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>" value="<?php htmlout($valores[$name]); ?>"
        <?php if($name === "nomedicion" AND $valores['nomedicion'] !== ""): ?> disabled> 
          <input type="hidden" name="<?php htmlout($name); ?>" value="<?php htmlout($valores[$name]); ?>">
        <?php else: ?>
          >
        <?php endif; ?>
    	</div>
    	<?php endforeach?>
      <div>
        <label for="nirm">NIRM: </label>
        <select name="nirm" id="nirm">
          <option value="" <?php if($valores['nirm'] === "") echo "selected"; ?> disabled>Selecciona NIRM</option>
          <?php foreach($nirms as $nirm): ?>
            <option value="<?php echo $nirm; ?>" <?php if(strval($nirm) === strval($valores['nirm'])) echo "selected"; ?>>
              <?php echo $nirm; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label for="luminometro">Luminometro: </label>
        <select name="luminometro" id="luminometro">
          <option value="" <?php if($valores['luminometro'] === "") echo "selected"; ?> disabled>Selecciona luminometro</option>
          <?php foreach($luminometros as $lum): ?>
            <option value="<?php echo $lum['id']; ?>" <?php if($valores['luminometro'] === $lum['id']) echo "selected"; ?>>
              Marca: <?php echo $lum['marca']; ?>, Modelo: <?php echo $lum['modelo']; ?>, Serie: <?php echo $lum['serie']; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <br>
  	  <fieldset>
  	  <legend>Mediciones:</legend>
  	    <?php for ($i=0; $i<$nmediciones; $i++): ?>
  	    <div>
  	    	<label for="mediciones[<?php echo $i; ?>][hora]">Hora:</label>
    			<input type="text" name="mediciones[<?php echo $i; ?>][hora]" id="mediciones[<?php echo $i; ?>][hora]" value="<?php isset($mediciones[$i]) ? htmlout($mediciones[$i]["hora"]) : ""; ?>">

          <label for="mediciones[<?php echo $i; ?>][e1plano]">E1 Plano:</label>
          <input type="text" name="mediciones[<?php echo $i; ?>][e1plano]" id="mediciones[<?php echo $i; ?>][e1plano]" value="<?php isset($mediciones[$i]) ? htmlout($mediciones[$i]["e1plano"]) : ""; ?>">

          <label for="mediciones[<?php echo $i; ?>][e2plano]">E2 Plano:</label>
          <input type="text" name="mediciones[<?php echo $i; ?>][e2plano]" id="mediciones[<?php echo $i; ?>][e2plano]" value="<?php isset($mediciones[$i]) ? htmlout($mediciones[$i]["e2plano"]) : ""; ?>">

    			<label for="mediciones[<?php echo $i; ?>][e1pared]">E1 Pared:</label>
    			<input type="text" name="mediciones[<?php echo $i; ?>][e1pared]" id="mediciones[<?php echo $i; ?>][e1pared]" value="<?php (isset($mediciones[$i]) AND strval($mediciones[$i]["e1pared"]) !== "0") ? htmlout($mediciones[$i]["e1pared"]) : ""; ?>">

          <label for="mediciones[<?php echo $i; ?>][e2pared]">E2 Pared:</label>
          <input type="text" name="mediciones[<?php echo $i; ?>][e2pared]" id="mediciones[<?php echo $i; ?>][e2pared]" value="<?php (isset($mediciones[$i]) AND strval($mediciones[$i]["e2pared"]) !== "0") ? htmlout($mediciones[$i]["e2pared"]) : ""; ?>">
  	    </div>
  	  <?php endfor; ?>
  	  </fieldset>
    	<div>	
  	    <input type="hidden" name="id" value="<?php htmlout($id); ?>">
    		<input type="hidden" name="idrci" value="<?php htmlout($idrci); ?>">
  	    <input type="submit" name="boton" value="Guardar">	
  	 </div> 
   </form>
	<p><a href="?volverpts&amp;idrci=<?php htmlout($idrci); ?>">Regresa los puntos del reconociminento</a></p>
	<p><a href="?volverci&amp;idot=<?php htmlout($idot); ?>">Regresa los reconocimientos de la orden</a></p>
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
<link rel="stylesheet" href="../../../includes/jquery-validation-1.13.1/demo/site-demos.css">
<script type="text/javascript" src="../../../includes/jquery-validation-1.13.1/lib/jquery.js"></script>
<script type="text/javascript" src="../../../includes/jquery-validation-1.13.1/lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="../../../includes/jquery-validation-1.13.1/dist/jquery.validate.js"></script>
<script type="text/javascript" src="../../../includes/jquery-validation-1.13.1/dist/additional-methods.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    jQuery.validator.addMethod('trint', function (value, element, param) {
      return /^\s*|\d{1,3}$/.test(value);
    }, 'Ingresar enteros.');

    jQuery.validator.addMethod('int', function (value, element, param) {
      return /^\d*$/.test(value);
    }, 'Solo enteros');

    jQuery.validator.addMethod('hora', function (value, element, param) {
      return /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(value); 
    }, 'Favor de introducir una hora valida.');

    $("#puntoform").validate({
      rules: {
        fecha: {
          required: true,
          dateISO: true
        },
        nomedicion: {
          required: true,
          remote:
          {
           url: 'validateMedicion.php',
           type: "post",
           data:
           {
             numedicion: function()
             {
              return $('#puntoform :input[name="nomedicion"]').val();
             },
             rci: function()
             {
              return $('#puntoform :input[name="idrci"]').val();
             },
            }
          }
        },
        departamento: {
          required: true
        },
        area: {
           required: true
        },
        ubicacion: {
           required: true
        },
        identificacion: {
           required: true
        },
        observaciones: {
           required: true
        },
        nirm: {
          required: true
        },
        luminometro: {
          required: true
        },
        <?php for ($i=0; $i<$nmediciones; $i++) :
        echo "'mediciones[$i][hora]':{
          required: true,
          hora: true
        },
        'mediciones[$i][e1plano]':{
          required: true,
          trint: true
        },
        'mediciones[$i][e2plano]':{
          required: true,
          trint: true
        },
        'mediciones[$i][e1pared]':{
          trint: true
        },
        'mediciones[$i][e2pared]':{
          trint: true
        }";
        echo ($i<$nmediciones-1)? "," : "";
       endfor; ?>
      },
      messages: {
        nomedicion:{
          remote: jQuery.validator.format("Número de medición {0} ya existe.")
        }
      },
      success: "valid",
      submitHandler: function(form) {  
                      if ($(form).valid()) 
                       form.submit(); 
                      return false; // prevent normal form posting
                    }
    });
  });
</script>