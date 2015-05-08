<?php include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php'; ?>
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
        $formulario = array("Fecha (aaaa-mm-dd)" => "fecha",
               							"Departamento" => "departamento",
               							"Área" => "area",
               							"Largo" => "largo",
               							"Ancho" => "ancho",
               							"Alto" => "alto",
                            "Número de lámpara" => "numlamp",
               							"Tipo de lámpara" => "tipolampara",
               							"Potencia de lámpara" => "potencialamp",
               							"Altura de lámpara" => "alturalamp",
               							"Color de Techo" => "techocolor",
               							"Color de Pared" => "paredcolor",
               							"Color de Piso" => "pisocolor",
               							"Percepción de la iluminación" => "percepcion");
      ?>
      <form id="rciform" action="?<?php htmlout($accion); ?>" method="post">
      	<?php foreach($formulario as $label => $name): ?>
        	<div>
        		<label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
    	    	<input type="text" name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>" value="<?php htmlout($valores[$name]); ?>">
        	</div>
      	<?php endforeach?>
        <div>
        <div>
          <label for="mantenimiento">Programa mantenimiento: (max. 250)</label>
          <br>
          <textarea style="resize: none;" maxlength=250 rows=5 cols=45 name="mantenimiento" id="mantenimiento"><?php htmlout($valores['mantenimiento']); ?></textarea>
        </div>
         <label for="influencia">Influencia:</label>
         <select name="influencia" id="influencia">
          <option value=""<?php if($valores["influencia"] === "") echo 'selected'?>>Seleccionar</option>
          <option value="0"<?php if(strval($valores["influencia"]) === "0") echo 'selected'?>>No</option>
          <option value="1" <?php if(strval($valores["influencia"]) === "1") echo 'selected'?>>Sí</option>
         </select>
        </div>
        <div>
          <label for="descriproceso">Descripción del proceso: (max. 350)</label>
          <br>
          <textarea style="resize: none;" maxlength=350 rows=5 cols=45 name="descriproceso" id="descriproceso"><?php htmlout($valores['descriproceso']); ?></textarea>
        </div>
        <fieldset>
        <legend>Descripción de puestos:</legend>
    	    <?php for ($i=0; $i<20; $i++):?>
            <div style="float: left;margin-right:15px;margin-bottom:15px;border: 1px solid silver;padding:10px;">
              <label for="descpuestos[<?php echo $i; ?>][puesto]">Puesto:</label>
              <input style="width:250px;"type="text" name="descpuestos[<?php echo $i; ?>][puesto]" id="descpuestos[<?php echo $i; ?>][puesto]" value="<?php isset($puestos[$i]) ? htmlout($puestos[$i]["puesto"]) : ""; ?>">
              <br>
              <label for="descpuestos[<?php echo $i; ?>][numtrabajadores]">Número de trabajadores:</label>
              <input style="width:250px;" type="text" name="descpuestos[<?php echo $i; ?>][numtrabajadores]" id="descpuestos[<?php echo $i; ?>][numtrabajadores]" value="<?php isset($puestos[$i]) ? htmlout($puestos[$i]["numtrabajadores"]) : ""; ?>">
              <br>
              <label for="descpuestos[<?php echo $i; ?>][actividades]">Tareas visuales:</label><br>
              <textarea style="resize: none;" maxlength=350 rows=5 cols=50 name="descpuestos[<?php echo $i; ?>][actividades]" id="descpuestos[<?php echo $i; ?>][actividades]"><?php isset($puestos[$i]) ? htmlout($puestos[$i]["actividades"]) : ""; ?></textarea>
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
      <p><a href="..">Regresa a la búsqueda de ordenes</a></p>
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
    jQuery.validator.addMethod('doscimales', function (value, element, param) {
      return /^\d{1,3}(\.\d{1,2}|\d*)$/.test(value);
    }, 'Ingresar enteros o con 2 decimales.');

    jQuery.validator.addMethod('int', function (value, element, param) {
      return /^\d*$/.test(value);
    }, 'Solo enteros');

    $("#rciform").validate({
      rules: {
        fecha: {
          required: true,
          dateISO: true
        },
        departamento: {
          required: true
        },
        area: {
          required: true
        },
        largo: {
           required: true,
           doscimales: true
        },
        ancho: {
           required: true,
           doscimales: true
        },
        alto: {
           required: true,
           doscimales: true
        },
        numlamp: {
           required: true,
           digits: true
        },
        tipolampara: {
          required: true
        },
        potencialamp: {
          required: true
        },
        alturalamp: {
           required: true,
           doscimales: true
        },
        techocolor: {
          required: true
        },
        paredcolor: {
          required: true
        },
        pisocolor: {
          required: true
        },
        percepcion: {
          required: true
        },
        influencia: {
          required: true
        },
        descriproceso: {
         required: true,
         maxlength: 350
        },
        mantenimiento: {
         required: true,
         maxlength: 350
        },
        <?php for ($i=0; $i<20; $i++) :
        echo "'descpuestos[$i][numtrabajadores]':{
          int: true
        }";
        echo ($i<19)? "," : "";
       endfor; ?>
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