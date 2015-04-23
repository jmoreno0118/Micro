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
    <?php //var_dump($_POST); ?>
   <h2><?php htmlout($titulopagina); ?></h2>
   <?php $formulario = array("hora" => "Hora(hh:mm)",
                          "flujo" => "Flujo(m3/s) Ej. 1.1234:",
                          "volumen" => "Volumen(ml):",
                          "observaciones" => "Observaciones:",
                          "caracteristicas" => "Caracteristicas: (Max. 350)",
                          "fechalab" => "Fecha recepción laboratorio(aaaa-mm-dd):",
                          "horalab" => "Hora recepción laboratorio(hh:mm):");
    ?>
    <form id="compuestasform" action="?<?php htmlout($accion); ?>" method="post">
      <?php foreach ($formulario as $key => $value): ?>
        <fieldset>
        <legend><?php echo $value; ?>:</legend>
  	    <?php for ($i=0; $i<$cantidad; $i++) :?>
          <label for="mcompuestas[<?php echo $i; ?>][<?php echo $key; ?>]">Muestra <?php if($i+1<$cantidad){htmlout($i+1);}else{echo "Compuesta";} ?>:</label>
          <?php if($key === "caracteristicas"):?>
            <br>
            <textarea style="resize: none;" maxlength=350 rews=5 cols=50 name="mcompuestas[<?php echo $i; ?>][<?php echo $key; ?>]" id="mcompuestas[<?php echo $i; ?>][<?php echo $key; ?>]"><?php if(isset($mcompuestas[$i])){htmlout($mcompuestas[$i][$key]);} ?></textarea>
            <br>
          <?php else: ?>
    			   <input type="text" name="mcompuestas[<?php echo $i; ?>][<?php echo $key; ?>]" id="mcompuestas[<?php echo $i; ?>][<?php echo $key; ?>]" value="<?php if(isset($mcompuestas[$i])){htmlout($mcompuestas[$i][$key]);} ?>">
          <?php endif; ?>
          <br>
  	  <?php endfor; ?>
      </fieldset>
      <br>
    <?php endforeach; ?>
	  <div>
      <?php if(isset($regreso) AND $regreso === 1): ?>
       <input type="hidden" name="regreso" value="1">
       <input type="hidden" name="ot" value="<?php htmlout($_SESSION['OT']); ?>">
       <input type="submit" name="accion" value="volvercmeds">
      <?php endif;?>
      <input type="hidden" name="cantidad" value="<?php htmlout($cantidad); ?>"> 
	    <input type="hidden" name="id" value="<?php htmlout($id); ?>">
	    <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
	    <p><a href="../nom001">Regresa al búsqueda de ordenes</a></p>
	  </div> 
	</form>
  <form action="" method="post">
      <input type="hidden" name="ot" value="<?php htmlout($_SESSION['OT']); ?>">
      <input type="submit" name="accion" value="volvermed">
  </form>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>
<link rel="stylesheet" href="../includes/jquery-validation-1.13.1/demo/site-demos.css">
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/dist/jquery.validate.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/dist/additional-methods.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
   jQuery.validator.addMethod('hora', function (value, element, param) {
    return /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(value); 
   }, 'Favor de introducir una hora valida.');

   jQuery.validator.addMethod('decimal', function (value, element, param) {
    return /^\d{1,2}(\.\d{1,3})$/.test(value); 
   }, 'Ingresar de 1 a 3 decimales.');

   jQuery.validator.addMethod('flujo', function (value, element, param) {
    return /^(\S\/\F|\d{1,2}\.\d{1,10})$/.test(value); 
   }, 'Ingresar de 1 a 10 decimales.');

    $("#compuestasform").validate({
      rules: {
       <?php for ($i=0; $i<$cantidad; $i++) :
       echo "
      'mcompuestas[$i][hora]':{
        required: true,
        hora: true
      },
      'mcompuestas[$i][flujo]':{
        required: true,
        flujo: true
      },
      'mcompuestas[$i][volumen]':{
        required: true,
        digits: true
      },
      'mcompuestas[$i][observaciones]':{
       required: true
      },
      'mcompuestas[$i][caracteristicas]':{
       required: true,
       maxlength: 350
      },
      'mcompuestas[$i][fechalab]':{
        required: true,
        date: true
      },
      'mcompuestas[$i][horalab]':{
        required: true,
        hora: true
      }";
       echo ($i<$cantidad-1)? "," : "";
       endfor; ?>
      },
      success: "valid"
    });
  });</script>