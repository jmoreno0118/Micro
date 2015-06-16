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
    //var_dump($valores);
      $formulario = array(
                  'empresagiro' => array(
                                        'label' => 'Giro de la empresa',
                                        'tipo' => 'text'
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
                                        'label' => 'Número de medición',
                                        'tipo' => 'text'
                                        ),
                  'lugarmuestreo' => array(
                                        'label' => 'Lugar del muestreo (5.0)',
                                        'tipo' => 'text'
                                        ),
                  'descriproceso' => array(
                                        'label' => 'Descripción del proceso (Max. 100)',
                                        'tipo' => 'textarea',
                                        'atts' => array('maxlength' => 100)
                                        ),
                  'tipomediciones' => array(
                                        'label' => 'Horas que opera el proceso generador de la descarga',
                                        'tipo' => 'select',
                                        'option' => array('1' => 'Puntual',
                                                          '4' => '<4 Horas',
                                                          '8' => '>4 y <12 Horas',
                                                          '12' => '>12 Horas')
                                        ),
                  'materiasusadas' => array(
                                        'label' => 'Materias primas usadas (8.0)',
                                        'tipo' => 'text'
                                        ),
                  'tratamiento' => array(
                                        'label' => 'Tratamiento del agua antes de la descarga (9.0) (Max. 100)',
                                        'tipo' => 'textarea',
                                        'atts' => array('maxlength' => 100)
                                        ),
                  'Caracdescarga' => array(
                                        'label' => 'Características de la descarga (10.0)',
                                        'tipo' => 'text'
                                        ),
                  'receptor' => array(
                                        'label' => 'Tipo de receptor de la decarga (11.0)',
                                        'tipo' => 'text'
                                        ),
                  'estrategia' => array(
                                        'label' => 'Estrategia de muestreo (12.0)',
                                        'tipo' => 'textarea',
                                        'atts' => array('maxlength' => 6500)
                                        ),
                  'numuestras' => array(
                                        'label' => 'No. muestras tomadas (13.0)',
                                        'tipo' => 'text',
                                        'atts' => array('disabled', 'class' => 'numuestras')
                                        ),
                  'observaciones' => array(
                                        'label' => 'Observaciones (19.0)',
                                        'tipo' => 'textarea',
                                        'atts' => array('maxlength' => 6500)
                                        ),
                  'fechamuestreo' => array(
                                        'label' => 'Fecha de muestreo(aaaa-mm-dd)',
                                        'tipo' => 'text'
                                        ),
                  'fechamuestreofin' => array(
                                        'label' => 'Fecha fin de muestreo(aaaa-mm-dd)',
                                        'tipo' => 'text'
                                        ),
                  'identificacion' => array(
                                        'label' => 'Identificación',
                                        'tipo' => 'text'
                                        ),
                  'temperatura' => array(
                                        'label' => 'Temperatura(Ej. 12.12)',
                                        'tipo' => 'text'
                                        ),
                  'caltermometro' => array(
                                        'label' => 'E.M. en calibración del termómetro(Ej. 12.1234)',
                                        'tipo' => 'text'
                                        ),
                  'pH' => array(
                                        'label' => 'pH Compuesta(Ej. 12.12)',
                                        'tipo' => 'text'
                                        ),
                  'conductividad' => array(
                                        'label' => 'Conductividad compuesta(Ej. 1234)',
                                        'tipo' => 'text'
                                        ),
                  'signatario' => array(
                                        'label' => 'Signatario',
                                        'tipo' => 'select',
                                        'option' => $signatarios
                                        ),
                  'responsable[0]' => array(
                                        'label' => 'Responsable 1*',
                                        'tipo' => 'select',
                                        'atts' => array('name' => 'resonsable[0]'),
                                        'valor' => isset($valores['responsable'][0]) ? $valores['responsable'][0] : '',
                                        'option' => $muestreadores,
                                        'extra' => array('disabled' => 'false')
                                        ),
                  'responsable[1]' => array(
                                        'label' => 'Responsable 2',
                                        'tipo' => 'select',
                                        'atts' => array('name' => 'resonsable[1]'),
                                        'valor' => isset($valores['responsable'][1]) ? $valores['responsable'][1] : '',
                                        'option' => $muestreadores,
                                        'extra' => array('disabled' => 'false')
                                        ),
                  'responsable[2]' => array(
                                        'label' => 'Responsable 3',
                                        'tipo' => 'select',
                                        'atts' => array('name' => 'resonsable[2]'),
                                        'valor' => isset($valores['responsable'][2]) ? $valores['responsable'][2] : '',
                                        'option' => $muestreadores,
                                        'extra' => array('disabled' => 'false')
                                        ),
                  'responsable[3]' => array(
                                        'label' => 'Responsable 4',
                                        'tipo' => 'select',
                                        'atts' => array('name' => 'resonsable[3]'),
                                        'valor' => isset($valores['responsable'][3]) ? $valores['responsable'][3] : '',
                                        'option' => $muestreadores,
                                        'extra' => array('disabled' => 'false')
                                        ),
                  'mflotante' => array(
                                        'label' => 'Materia flotante visual',
                                        'tipo' => 'select',
                                        'option' => array('Ausente', 'Presente')
                                        ),
                  'olor' => array(
                                        'label' => 'Olor',
                                        'tipo' => 'select',
                                        'option' => array('No', 'Sí')
                                        ),
                  'color' => array(
                                        'label' => 'Color visual',
                                        'tipo' => 'select',
                                        'option' => array('No', 'Sí')
                                        ),
                  'turbiedad' => array(
                                        'label' => 'Turbiedad visual',
                                        'tipo' => 'select',
                                        'option' => array('No', 'Sí')
                                        ),
                  'GyAvisual' => array(
                                        'label' => 'Grasas y Aceite visual',
                                        'tipo' => 'select',
                                        'option' => array('No', 'Sí')
                                        ),
                  'burbujas' => array(
                                        'label' => 'Burbujas y espuma',
                                        'tipo' => 'select',
                                        'option' => array('No', 'Sí')
                                        )
      );

      $arquitectura = array("valores" => array("variables" => 'empresagiro,descargaen,uso,numedicion,lugarmuestreo,descriproceso,tipomediciones,proposito,materiasusadas,tratamiento,Caracdescarga,receptor,estrategia,numuestras,observaciones,fechamuestreo,fechamuestreofin,identificacion,temperatura,caltermometro,pH,conductividad,signatario,nombresignatario,responsable,mflotante,olor,color,turbiedad,GyAvisual,burbujas',
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
        <?php if(isset($_SESSION['supervisada'])){ ?>
          <?php $value['atts'] = array('disabled'); ?>
        <?php } ?>
        <?php if($key === "numedicion" AND isset($valores['numedicion']) AND $valores['numedicion'] !== "" AND !isset($new)): ?>
          <?php $value['atts'] = array('disabled') ?>
          <input type="hidden" name="numedicion" value="<?php htmlout($valores['numedicion']); ?>">
        <?php endif; ?>

        <?php if($key === "tipomediciones" AND isset($valores['tipomediciones']) AND $valores['tipomediciones'] !== ""): ?>
          <?php $value['atts'] = array('disabled') ?>
          <input type="hidden" name="tipomediciones" value="<?php htmlout($valores['tipomediciones']); ?>">
        <?php endif; ?>

        <?php if($key === "numuestras"): ?>
          <input type="hidden" class ="numuestras" name="numuestras" value="<?php isset($valores['numuestras']) ? htmlout($valores['numuestras']) : '';?>">
        <?php endif; ?>

        <?php if($key === "signatario" AND isset($valores['nombresignatario']) AND trim($valores['nombresignatario']) !== ""): ?>
          <label for="signatarios">Signatario actual: </label>
          <input type="text" style="width:250px" value="<?php echo $valores['nombresignatario']; ?>" disabled>
          <input type="hidden" name="nombresignatario" value="<?php echo $valores['nombresignatario']; ?>">
          <br><br>
        <?php endif; ?>
        <?php 
              $valor = "";
              if(isset($value['valor'])){
                $valor = $value['valor'];
              }elseif(isset($valores[$key])){
                $valor = $valores[$key];
              }
              crearForma(
                        $value['label'], //Texto del label
                        $key, //Texto a colocar en los atributos id y name
                        $valor, //Valor extraido de la bd
                        (isset($value['atts'])) ? $value['atts'] : '', //Atributos extra de la etiqueta
                        $value['tipo'], //Tipo de etiqueta
                        (isset($value['option'])) ? $value['option'] : '' //Options para los select
              ); ?>
    	</div>
      <br>
    	<?php endforeach?>
	  <div>
	    <input type="hidden" name="id" value="<?php htmlout($id); ?>">
	    <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
      <p><a href="../generales">Volver a mediciones</a></p>
	    <p><a href="..">Regresa a la búsqueda de ordenes</a></p>
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

    function numeroMuestras(){
      if( $("#tipomediciones").val() === '1') {
        $('.numuestras').val('1');
      } else if($("#tipomediciones").val() === '4') {
        $('.numuestras').val('2');
      } else if($("#tipomediciones").val() === '8') {
        $('.numuestras').val('4');
      } else if($("#tipomediciones").val() === '12'){
        $('.numuestras').val('6');
      }
    }

    numeroMuestras();

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

    $("#tipomediciones").change(function(){
      numeroMuestras();
    });

   jQuery.validator.addMethod('uncimal', function (value, element, param) {
    return /^\d{1,2}(\.\d{1})$/.test(value);
   }, 'Ingresar 1 decimal.');

   jQuery.validator.addMethod('doscimales', function (value, element, param) {
    return /^\d{1,2}(\.\d{1,2})$/.test(value);
   }, 'Ingresar de 1 a 2 decimales.');

   jQuery.validator.addMethod('trescimales', function (value, element, param) {
    return /^\d{1,4}$/.test(value);
   }, 'Ingresar de 1 a 4 enteros.');

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
        signatario: "required",
        'responsable[0]': "required",
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
