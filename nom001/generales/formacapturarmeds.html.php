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
      $formulario = array(
                  'empresagiro' => array(
                                        'label' => 'Giro de la empresa'
                                        ),
                  'descargaen' => array(
                                        'label' => 'Descarga en',
                                        'tipo' => 'select2',
                                        'option' => $descargaen
                                        ),
                  'uso' => array(
                                        'label' => 'Uso', 
                                        'tipo' => 'select'
                                        ),
                  'numedicion' => array(
                                        'label' => 'Número de medición'
                                        ),
                  'lugarmuestreo' => array(
                                        'label' => 'Lugar del muestreo (5.0)'
                                        ),
                  'descriproceso' => array(
                                        'label' => 'Descripción del proceso (Max. 100)',
                                        'tipo' => 'textarea',
                                        'atts' => array('maxlength' => 100)
                                        ),
                  'tipomediciones' => array(
                                        'label' => 'Tipo de medición',
                                        'tipo' => 'select',
                                        'option' => array('1' => 'Puntual', '8' => '8 Horas', '24' => '4 Horas')
                                        ),
                  'materiasusadas' => array(
                                        'label' => 'Materias primas usadas (8.0)'
                                        ),
                  'tratamiento' => array(
                                        'label' => 'Tratamiento del agua antes de la descarga (9.0) (Max. 100)',
                                        'tipo' => 'textarea',
                                        'atts' => array('maxlength' => 100)
                                        ),
                  'Caracdescarga' => array(
                                        'label' => 'Características de la descarga (10.0)'
                                        ),
                  'receptor' => array(
                                        'label' => 'Tipo de receptor de la decarga (11.0)'
                                        ),
                  'estrategia' => array(
                                        'label' => 'Estrategia de muestreo (12.0)',
                                        'tipo' => 'textarea',
                                        'atts' => array('maxlength' => 6500)
                                        ),
                  'numuestras' => array(
                                        'label' => 'No. muestras tomadas (13.0)'
                                        ),
                  'observaciones' => array(
                                        'label' => 'Observaciones (19.0)',
                                        'tipo' => 'textarea',
                                        'atts' => array('maxlength' => 6500)
                                        ),
                  'fechamuestreo' => array(
                                        'label' => 'Fecha de muestreo(aaaa-mm-dd)'
                                        ),
                  'fechamuestreofin' => array(
                                        'label' => 'Fecha fin de muestreo(aaaa-mm-dd)'
                                        ),
                  'identificacion' => array(
                                        'label' => 'Identificación'
                                        ),
                  'temperatura' => array(
                                        'label' => 'Temperatura(Ej. 12.12)'
                                        ),
                  'caltermometro' => array(
                                        'label' => 'E.M. en calibración del termómetro(Ej. 12.1234)'
                                        ),
                  'pH' => array(
                                        'label' => 'pH Compuesta(Ej. 12.12)'
                                        ),
                  'conductividad' => array(
                                        'label' => 'Conductividad compuesta(Ej. 12.123)'
                                        ),
                  'responsable' => array(
                                        'label' => 'Responsable'
                                        ),
                  'mflotante' => array(
                                        'label' => 'Materia flotante visual',
                                        'tipo' => 'select',
                                        'option' => array('0' => 'Ausente', '1' => 'Presente')
                                        ),
                  'olor' => array(
                                        'label' => 'Olor',
                                        'tipo' => 'select',
                                        'option' => array('0' => 'No', '1' => 'Sí')
                                        ),
                  'color' => array(
                                        'label' => 'Color visual',
                                        'tipo' => 'select',
                                        'option' => array('0' => 'No', '1' => 'Sí')
                                        ),
                  'turbiedad' => array(
                                        'label' => 'Turbiedad visual',
                                        'tipo' => 'select',
                                        'option' => array('0' => 'No', '1' => 'Sí')
                                        ),
                  'GyAvisual' => array(
                                        'label' => 'Grasas y Aceite visual',
                                        'tipo' => 'select',
                                        'option' => array('0' => 'No', '1' => 'Sí')
                                        ),
                  'burbujas' => array(
                                        'label' => 'Burbujas y espuma',
                                        'tipo' => 'select',
                                        'option' => array('0' => 'No', '1' => 'Sí')
                                        )
      );

      $arquitectura = array("valores" => array("variables" => 'empresagiro,descargaen,uso,numedicion,lugarmuestreo,descriproceso,tipomediciones,proposito,materiasusadas,tratamiento,Caracdescarga,receptor,estrategia,numuestras,observaciones,fechamuestreo,fechamuestreofin,identificacion,temperatura,caltermometro,pH,conductividad,responsable,mflotante,olor,color,turbiedad,GyAvisual,burbujas',
                                              "tipo" => 1),
                            "id" => array("variables" => "id",
                                          "tipo" => 0));
   ?>
    <form id="medsform" name="medsform" action="?" method="post">
      <input type="hidden" name="post" value='<?php htmlout(json_encode($_POST)); ?>'>
      <input type="hidden" name="url" value="<?php htmlout($_SESSION['url']); ?>">
      <input type="hidden" name="arquitectura" value='<?php htmlout(json_encode($arquitectura)); ?>'>

    	<?php foreach($formulario as $key => $value): ?>
    	<div>
        <?php if($key === "numedicion" AND isset($valores['numedicion']) AND $valores['numedicion'] !== "" AND !isset($new)): ?>
          <?php $formulario['numedicion']['atts'] = array('disabled') ?>
          <input type="hidden" name="numedicion" value="<?php htmlout($valores['numedicion']); ?>">
        <?php endif; ?>

        <?php if($key === "tipomediciones"  AND isset($valores['tipomediciones']) AND $valores['tipomediciones'] !== ""): ?>
          <input type="hidden" name="tipomediciones" value="<?php htmlout($valores['tipomediciones']); ?>">
        <?php endif; ?>
        <?php crearForma(
                        $value['label'], //Texto del abel
                        $key, //Texto a colocar en los atributos id y name
                        (isset($valores[$key])) ? $valores[$key] : '', //Valor extraido de la bd
                        (isset($value['atts'])) ? $value['atts'] : '', //Atributos extra de la etiqueta
                        (isset($value['tipo'])) ? $value['tipo'] : 'text', //Tipo de etiqueta
                        (isset($value['option'])) ? $value['option'] : '' //Options para los select
              ); ?>
    	</div>
      <br>
    	<?php endforeach?>
	  <div>
	    <input type="hidden" name="id" value="<?php htmlout($id); ?>">
	    <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
      <p><a href="../generales">Volver a mediciones</a></p>
	    <p><a href="..">Regresa al búsqueda de ordenes</a></p>
	  </div> 
	</form>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>

<link rel="stylesheet" href="../../includes/jquery-validation-1.13.1/demo/site-demos.css">
<script type="text/javascript" src="../../includes/jquery-validation-1.13.1/lib/jquery.js"></script>
<script type="text/javascript" src="../../includes/jquery-validation-1.13.1/lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="../../includes/jquery-validation-1.13.1/dist/jquery.validate.js"></script>
<script type="text/javascript" src="../../includes/jquery-validation-1.13.1/dist/additional-methods.js"></script>
<script type="text/javascript">
  $(document).ready(function() {

    function listaUso(descarga, uso){
      $.ajax({
        type: "POST",
        url: "uso.php",
        data: {descargaen: $("#descargaen").val(), descarga: descarga, uso: uso},
        cache: false,
        success: function(html){
          $("#uso").html(html);
        }
      });
    }

    listaUso(<?php echo '"'.$valores["descargaen"].'"' ?>, <?php echo '"'.$valores["uso"].'"' ?>);

    $("#descargaen").change(function(){
      listaUso(<?php echo '"'.$valores["descargaen"].'"' ?>, <?php echo '"'.$valores["uso"].'"' ?>);
    });

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
        descargaen: "required",
        uso: "required",
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
        descriproceso: {
         required: true,
         maxlength: 100
        },
        tipomediciones: "required",
        proposito: {
         required: true,
         maxlength: 6500
        },
        materiasusadas: "required",
        tratamiento: {
         required: true,
         maxlength: 100
        },
        Caracdescarga: "required",
        receptor: "required",
        estrategia: {
         required: true,
         maxlength: 6500
        },
        numuestras: {
         required: true,
         digits: true,
         dosint: true
        },
        observaciones: {
         required: true,
         maxlength: 6500
        },
        fechamuestreo: {
         required: true,
         dateISO: true
        },
        fechamuestreofin: {
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

</html>
