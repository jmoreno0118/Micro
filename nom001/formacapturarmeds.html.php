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
   <style type="text/css">
    label.error {
    //color:red;
    //font-weight: bold;
    }
</style>
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
   		$formulario = array("Giro de la empresa" => "empresagiro",
                          "Valores máximos" => "nom01maximosidfk",
                          "Número de medición" => "numedicion",
             							"Lugar del muestreo (5.0)" => "lugarmuestreo",
             							"Descripción del proceso" => "descriproceso",
                          "Tipo de medición" => "tipomediciones",
                          "Proósito (6.0)" => "proposito",
             							"Materias primas usadas (8.0)" => "materiasusadas",
             							"Tratamiento del agua antes de la descarga (9.0)" => "tratamiento",
             							"Características de la descarga (10.0)" => "Caracdescarga",
             							"Tipo de receptor de la decarga (11.0)" => "receptor",
             							"Estrategia de muestreo (12.0)" => "estrategia",
                          "No. muestras tomadas (13.0)" => "numuestras",
                          "Observaciones (19.0)" => "observaciones",
                          "Fecha de muestreo(aaaa-mm-dd)" => "fechamuestreo",
                          "Identificación" => "identificacion",
                          "Temperatura(Ej. 12.12)" => "temperatura",
                          "E.M. en calibración del termómetro(Ej. 12.1234)" => "caltermometro",
                          "pH Compuesta(Ej. 12.12)" => "pH",
                          "Conductividad compuesta(Ej. 12.123)" => "conductividad",
                          "Responsable" => "responsable",
                          "Materia flotante visual" => "mflotante",
                          "Olor" => "olor",
                          "Color visual" => "color",
                          "Turbiedad visual" => "turbiedad",
                          "Grasas y Aceite visual" => "GyAvisual",
                          "Burbujas y espuma" => "burbujas");
   ?>
    <form id="medsform" name="medsform" action="?" method="post">
    	<?php foreach($formulario as $label => $name): ?>
    	<div>
        <?php if($name === "tipomediciones"):?>
        <label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
         <select name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>" <?php if($valores['tipomediciones'] !== ""){ ?> disabled <?php } ?>>
          <option value="">Seleccionar</option>
          <option value="1" <?php if($valores["tipomediciones"] === '1') echo 'selected'?>>Puntual</option>
          <option value="8" <?php if($valores["tipomediciones"] === '8') echo 'selected'?>>8 horas</option>
          <option value="24" <?php if($valores["tipomediciones"] === '24') echo 'selected'?>>24 horas</option>
        </select>
        <?php if($valores['tipomediciones'] !== ""){ ?>
          <input type="hidden" name="<?php htmlout($name); ?>" value="<?php htmlout($valores["tipomediciones"]); ?>">
        <?php } ?>
        <?php elseif($name === "nom01maximosidfk"):?>
        <label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
         <select name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>">
          <option value="">Seleccionar</option>
          <?php foreach ($maximos as $value): ?>
            <option value="<?php htmlout($value['id']); ?>"<?php if($valores["nom01maximosidfk"] === $value['id']) echo 'selected'?>>
              <?php htmlout($value['identificacion']); ?>
            </option>
          <?php endforeach; ?>
        </select> 
        <?php elseif($name === "mflotante"):?>
         <label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
         <select name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>">
          <option value="">Seleccionar</option>
          <option value="0"<?php if(intval($valores["mflotante"]) === 0) echo 'selected'?>>Ausente</option>
          <option value="1" <?php if(intval($valores["mflotante"]) === 1) echo 'selected'?>>Presente</option>
         </select> 
        <?php elseif($name === "olor" OR $name === "color" OR $name === "turbiedad" OR $name === "GyAvisual" OR $name === "burbujas"):?>
         <label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
         <select name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>">
          <option value="">Seleccionar</option>
          <option value="0"<?php if(intval($valores[$name]) === 0) echo 'selected'?>>No</option>
          <option value="1" <?php if(intval($valores[$name]) === 1) echo 'selected'?>>Sí</option>
         </select>
        <?php else: ?>
    		 <label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
	    	 <input type="text" name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>" value="<?php htmlout($valores[$name]); ?>" <?php if(($name === "numedicion" AND $valores['numedicion'] !== "") OR ($name === "responsable" AND $valores['responsable'] !== "")){ ?> disabled <?php } ?>>
         <?php if(($name === "numedicion" AND $valores['numedicion'] !== "") OR ($name === "responsable" AND $valores['responsable'] !== "")){ ?>
          <input type="hidden" name="<?php htmlout($name); ?>" value="<?php htmlout($valores[$name]); ?>">
         <?php } ?>
        <?php endif; ?>
    	</div>
      <br>
    	<?php endforeach?>
	  <div>
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

   jQuery.validator.addMethod('uncimal', function (value, element, param) {
    return /^\d{1,2}(\.\d{1})$/.test(value);
   }, 'Ingresar 1 decimal.');

   jQuery.validator.addMethod('doscimales', function (value, element, param) {
    return /^\d{1,2}(\.\d{1,2})$/.test(value);
   }, 'Ingresar de 1 a 2 decimales.');

   jQuery.validator.addMethod('trescimales', function (value, element, param) {
    return /^\d{1,2}(\.\d{1,3})$/.test(value);
   }, 'Ingresar de 1 a 3 decimales.');

   jQuery.validator.addMethod('cuatrocimales', function (value, element, param) {
    return /^\d{1,2}(\.\d{1,4})$/.test(value);
   }, 'Ingresar de 1 a 4 decimales.');

   jQuery.validator.addMethod('dosint', function (value, element, param) {
    return /^\d{1,2}$/.test(value);
   }, 'Sólo se aceptan 2 digitos.');

    $("#medsform").validate({
      rules: {
        empresagiro: "required",
        nom01maximosidfk: "required",
        numedicion: {
         required: true,
         digits: true,
         dosint: true,
         remote:
          {
           url: 'validateMedicion.php',
           type: "post",
           data:
           {
             numedicion: function()
             {
              return $('#medsform :input[name="numedicion"]').val();
             },
             orden: function()
             {
              return $('#medsform :input[name="id"]').val();
             },
            }
          }
        },
        lugarmuestreo: "required",
        descriproceso: "required",
        tipomediciones: "required",
        proposito: "required",
        materiasusadas: "required",
        tratamiento: "required",
        Caracdescarga: "required",
        receptor: "required",
        estrategia: "required",
        numuestras: {
         required: true,
         digits: true,
         dosint: true
        },
        observaciones: "required",
        fechamuestreo: {
         required: true,
         dateISO: true
        },
        identificacion: "required",
        temperatura: {
         required: true,
         doscimales: true
        },
        caltermometro: {
         required: true,
         cuatrocimales: true
        },
        pH: {
         required: true,
         doscimales: true
        },
        conductividad: {
         required: true,
         trescimales: true
        },
        responsable: "required",
        mflotante: "required",
        olor: "required",
        color: "required",
        turbiedad: "required",
        GyAvisual: "required",
        burbujas: "required"
      },
      messages: {
        numedicion:{
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