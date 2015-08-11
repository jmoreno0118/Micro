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
          if(is_array($valores['acreditacion']))
          {
            $valoracreditacion = $valores['acreditacion']['id'];
          }
          elseif( strcmp($valores['acreditacion'], '') === 0 )
          {
            $valoracreditacion = key($acreditaciones);
          }

          $formulario = array(
                      'acreditacion[id]' => array(
                                            'label' => 'Acreditacion',
                                            'tipo' => 'select',
                                            'option' => $acreditaciones,
                                            'valor' => $valoracreditacion
                                            ),
                      'numedicion' => array(
                                            'label' => 'Número de medición',
                                            'tipo' => 'text'
                                            ),
                      'fechamuestreo' => array(
                                            'label' => 'Fecha de muestreo(aaaa-mm-dd)',
                                            'tipo' => 'text'
                                            ),
                      'fechamuestreofin' => array(
                                            'label' => 'Fecha fin de muestreo(aaaa-mm-dd)',
                                            'tipo' => 'text'
                                            ),
                      'empresagiro' => array(
                                            'label' => 'Giro de la empresa (2) (Max. 45)',
                                            'tipo' => 'text'
                                            ),
                      'lugarmuestreo' => array(
                                            'label' => 'Area del muestreo (4)',
                                            'tipo' => 'text'
                                            ),
                      'descriproceso' => array(
                                            'label' => 'Descripción del proceso y/o áreas que genera la descarga (7) (Max. 500)',
                                            'tipo' => 'textarea',
                                            'atts' => array('maxlength' => 500)
                                            ),
                      'materiasusadas' => array(
                                            'label' => 'Materias primas usadas en el proceso que genera la descarga (8)',
                                            'tipo' => 'text'
                                            ),
                      'tratamiento' => array(
                                            'label' => 'Tratamiento del agua antes de la descarga (9) (Max. 500)',
                                            'tipo' => 'textarea',
                                            'atts' => array('maxlength' => 500)
                                            ),
                      'Caracdescarga' => array(
                                            'label' => 'Características de la descarga (10)',
                                            'tipo' => 'text'
                                            ),
                      'identificacion' => array(
                                            'label' => 'Identificación de la descarga (11)',
                                            'tipo' => 'text'
                                            ),
                      'descargaen' => array(
                                            'label' => 'Tipo de receptor de la descarga (12)',
                                            'tipo' => 'select2',
                                            'option' => $descargaen
                                            ),
                      'uso' => array(
                                            'label' => 'Tipo de cuerpo receptor (uso de agua)',
                                            'tipo' => 'select'
                                            ),
                      'estrategia' => array(
                                            'label' => 'Estrategia de muestreo (14)  (Max. 6500)',
                                            'tipo' => 'textarea',
                                            'atts' => array('maxlength' => 6500)
                                            ),
                      'observaciones' => array(
                                            'label' => 'Observaciones (19)  (Max. 6500)',
                                            'tipo' => 'textarea',
                                            'atts' => array('maxlength' => 6500)
                                            ),
                      'tipomediciones' => array(
                                            'label' => 'Horas que opera el proceso generador de la descarga',
                                            'tipo' => 'select',
                                            'option' => array('1' => 'Puntual',
                                                              '4' => '<4 Horas',
                                                              '8' => '>4 y <12 Horas',
                                                              '12' => '>12 Horas')
                                            ),
                      'numuestras' => array(
                                            'label' => 'No. muestras tomadas',
                                            'tipo' => 'text',
                                            'atts' => array('disabled', 'class' => 'numuestras')
                                            ),
                      'temperatura' => array(
                                            'label' => 'Temperatura(Ej. 12.12)',
                                            'tipo' => 'text'
                                            ),
                      /*'caltermometro' => array(
                                            'label' => 'E.M. en calibración del termómetro(Ej. 12.1234)',
                                            'tipo' => 'text'
                                            ),*/
                      'termometro' => array(
                                            'label' => 'Termometro',
                                            'tipo' => 'select',
                                            'option' => $termometros
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
                      "responsable[0][responsable]" => array(
                                            'label' => 'Responsable 1',
                                            'tipo' => 'select',
                                            'atts' => array('name' => 'resonsable[0]'),
                                            'valor' => isset($valores['responsable'][0]['responsable']) ? $valores['responsable'][0]['responsable'] : '',
                                            'option' => $muestreadores
                                            ),
                      "responsable[1][responsable]" => array(
                                            'label' => 'Responsable 2',
                                            'tipo' => 'select',
                                            'atts' => array('name' => 'resonsable[1]'),
                                            'valor' => isset($valores['responsable'][1]['responsable']) ? $valores['responsable'][1]['responsable'] : '',
                                            'option' => $muestreadores
                                            ),
                      "responsable[2][responsable]" => array(
                                            'label' => 'Responsable 3',
                                            'tipo' => 'select',
                                            'atts' => array('name' => 'resonsable[2]'),
                                            'valor' => isset($valores['responsable'][2]['responsable']) ? $valores['responsable'][2]['responsable'] : '',
                                            'option' => $muestreadores
                                            ),
                      "responsable[3][responsable]" => array(
                                            'label' => 'Responsable 4',
                                            'tipo' => 'select',
                                            'atts' => array('name' => 'resonsable[3]'),
                                            'valor' => isset($valores['responsable'][3]['responsable']) ? $valores['responsable'][3]['responsable'] : '',
                                            'option' => $muestreadores
                                            ),
                      'mflotante' => array(
                                            'label' => 'Materia flotante visual',
                                            'tipo' => 'select',
                                            'option' => array('Ausente', 'Presente')
                                            )
          );

          $arquitectura = array(
                                "valores" => array("variables" => 'acreditacion,empresagiro,descargaen,uso,numedicion,lugarmuestreo,descriproceso,tipomediciones,proposito,materiasusadas,tratamiento,Caracdescarga,receptor,estrategia,numuestras,observaciones,fechamuestreo,fechamuestreofin,identificacion,temperatura,caltermometro,termometro,pH,conductividad,signatario,nombresignatario,responsable,mflotante,olor,color,turbiedad,GyAvisual,burbujas',
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
              <?php if(isset($_SESSION['supervisada']))
              {
                $value['atts'] = array('disabled');
              }

              if($key === "numedicion" AND isset($valores['numedicion']) AND $valores['numedicion'] !== "" AND !isset($new))
              {
                $value['atts'] = array('disabled') ?>
                <input type="hidden" name="numedicion" value="<?php htmlout($valores['numedicion']); ?>">
              <?php
              }

              if($key === "tipomediciones" AND isset($valores['tipomediciones']) AND $valores['tipomediciones'] !== "")
              {
                $value['atts'] = array('disabled') ?>
                <input type="hidden" name="tipomediciones" value="<?php htmlout($valores['tipomediciones']); ?>">
              <?php
              }

              if($key === "numuestras")
              { ?>
                <input type="hidden" class ="numuestras" name="numuestras" value="<?php isset($valores['numuestras']) ? htmlout($valores['numuestras']) : '';?>">
              <?php
              }

              if($key === "signatario" AND isset($valores['nombresignatario']) AND trim($valores['nombresignatario']) !== "")
              { ?>
                <label for="nombresignatario">Signatario actual: </label>
                <input type="text" ame="nombresignatario" style="width:250px" value="<?php echo $valores['nombresignatario']; ?>" disabled>
                <input type="hidden" name="nombresignatario" value="<?php echo $valores['nombresignatario']; ?>">
                <br><br>
              <?php
              }

              if($key === "acreditacion[id]" AND isset($valores['acreditacion']) AND isset($valores['acreditacion']['nombre']))
              { ?>
                <label for="acreditacion[nombre]">Acreditacion actual: </label>
                <input type="text" name="acreditacion[nombre]" style="width:200px" value="<?php echo $valores['acreditacion']['nombre']; ?>" disabled>
                <input type="hidden" name="acreditacion[nombre]" value="<?php echo $valores['acreditacion']['nombre']; ?>">
                <br><br>
              <?php
              }

              if(strpos($value['label'], 'Responsable') !== FALSE)
              {
                $numero = explode(' ', $value['label']);
                $numero = explode('*', $numero[1]);

                if(isset($valores['responsable'][($numero[0]-1)]['nombre']))
                { ?>
                  <input type="hidden" name="responsable[<?php echo $numero[0]-1; ?>][id]" value="<?php echo $valores['responsable'][($numero[0]-1)]['id'] ?>">

                  <label for="signatarios">Responsable <?php echo $numero[0]; ?> actual: </label>
                  <input type="text" style="width:250px" value="<?php echo $valores['responsable'][($numero[0]-1)]['nombre'] ?>" disabled>
                  <input type="hidden" name="responsable[<?php echo $numero[0]-1; ?>][nombre]" value="<?php echo $valores['responsable'][($numero[0]-1)]['nombre'] ?>">
                  <br><br>
                <?php
                }
              } ?>

              <?php
                $valor = "";
                if(isset($value['valor']))
                {
                  $valor = $value['valor'];
                }
                elseif(isset($valores[$key]))
                {
                  $valor = $valores[$key];
                }

                crearForma(
                    $value['label'], //Texto del label
                    $key, //Texto a colocar en los atributos id y name
                    $valor, //Valor extraido de la bd
                    (isset($value['atts'])) ? $value['atts'] : '', //Atributos extra de la etiqueta
                    $value['tipo'], //Tipo de etiqueta
                    (isset($value['option'])) ? $value['option'] : '' //Options para los select
                );
              ?>
          	</div>
            <br>
        	<?php endforeach?>
          <div>
            <input type="hidden" name="id" value="<?php htmlout($id); ?>">
            <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
            <input type="submit" name="accion" value="siguiente">
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
</html>
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

  jQuery.validator.addMethod('caltermometro', function (value, element, param) {
  return /^-{0,1}\d{1,2}(\.\d{1,4})$/.test(value);
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
       maxlength: 500
      },
      tipomediciones: "required",
      proposito: {
       required: true,
       maxlength: 6500
      },
      materiasusadas: "required",
      tratamiento: {
       required: true,
       maxlength: 500
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
       caltermometro: true
      },
      pH: {
       required: true,
       doscimales: true
      },
      conductividad: {
       required: true,
       trescimales: true
      },
      <?php if(!isset($valores['nombresignatario'])): ?>
        signatario: "required",
      <?php endif; ?>
      <?php if(!isset($valores['responsable'][0]['nombre'])): ?>
        'responsable[0]': "required",
      <?php endif; ?>
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
