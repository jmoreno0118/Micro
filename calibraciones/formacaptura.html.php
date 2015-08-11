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
<link rel="stylesheet" href="../includes/jquery-validation-1.13.1/demo/site-demos.css">
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/dist/jquery.validate.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/dist/additional-methods.js"></script>
<script type="text/javascript">
  i = 1;
  $(document).ready(function() {
  
   jQuery.validator.addMethod('entcimales', function (value, element, param) {
      return /^(\d*\.\d{1,3}|\d*)$/.test(value);
   }, 'Sólo valores enteros o de 1 a 3 decimales.');
   
   jQuery.validator.addMethod('negativeentcimales', function (value, element, param) {
      return /^(-?\d*\.\d{1,3}|\d*)$/.test(value);
   }, 'Sólo valores enteros o de 1 a 3 decimales.');
  $("#medsform").validate({
      rules: {
        'rango[0]': {
         required: true,
         digits: true
        },
        'fcorreccion1[0]': {
         required: true,
         entcimales: true
        },
        'fcorreccion2[0]': {
         required: true,
         negativeentcimales: true
        }
      },
      ignore: [],
      success: "valid"
    });

    $('#agregar').click(function(e){
        e.preventDefault();
        agregarIntervalo("", 0, 0);
    });
   
    $('#intervalos').on("click",".borrar", function(e){
        e.preventDefault();
        $(this).parent('div').remove();
    });

  });

function agregarIntervalo(rango, fcorreccion1, fcorreccion2){
      $('#intervalos').append('<div>'
        +'<label for="rango['+i+']">Rango:</label><input type="text" name="rango['+i+']" value="'+rango+'" required="required">'
        +'<label for="fcorreccion1['+i+']"> Factor de Corrección 1:</label><input type="text" name="fcorreccion1['+i+']" value="'+fcorreccion1+'" required="required">'
        +'<label for="fcorreccion2['+i+']"> Factor de Corrección 2:</label><input type="text" name="fcorreccion2['+i+']" value="'+fcorreccion2+'" required="required">'
        +'<a href="#" class="borrar">Remove</a>'
        +'</div>');

      $("#medsform").validate({
        rules: {
          'rango[0]': {
           required: true,
           digits: true
          },
          'fcorreccion1[0]': {
           required: true,
           entcimales: true
          },
          'fcorreccion2[0]': {
           required: true,
           entcimales: true
          }
        },
        ignore: [],
        success: "valid"
      });

      $('input[name="rango['+i+']"]').rules("add", {
        digits: true
      });

      $('input[name="fcorreccion1['+i+']"]').rules("add", {
        entcimales: true
      });

      $('input[name="fcorreccion2['+i+']"]').rules("add", {
        entcimales: true
      });

      i = i + 1;
    }
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
   <?php
    //var_dump($valores);
      $formulario = array(
                  'Numero_Inventario' => array(    
                                        'label' => 'No. de inventario',
                                        'tipo' => 'text',
                                        'atts' => array('disabled' => 'disabled')
                                        ),
                  'fecha' => array(
                                        'label' => 'Fecha de elaboración',
                                        'tipo' => 'text'
                                        ),
                  'Tipo' => array(
                                        'label' => 'Nombre de instrumento',
                                        'tipo' => 'text',
                                        'atts' => array('disabled' => 'disabled')
                                        ),
                  'laboratoriocalibro' => array(
                                        'label' => 'Laboratorio de calibración', 
                                        'tipo' => 'text'
                                        ),
                  'laboratorioacreditacion' => array(
                                        'label' => 'Número de acreditación',
                                        'tipo' => 'text'
                                        ),
                  'fechaLabAcreditacion' => array(
                                        'label' => 'Fecha de acreditación',
                                        'tipo' => 'text'
                                        ),
                  'fechacalibracion' => array(
                                        'label' => 'Fecha de calibración',
                                        'tipo' => 'text'
                                        ),
                  
                  'parametroevaluado' => array(
                                        'label' => 'Parametro evaluado',
                                        'tipo' => 'text'
                                        ),
                  'criterioaceptacion' => array(
                                        'label' => 'Criterios de aceptación',
                                        'tipo' => 'text'
                                        ),
                  'especificaciones' => array(
                                        'label' => 'Especificaciones de calibración',
                                        'tipo' => 'text'
                                        ),
                  'cumple' => array(
                                        'label' => 'El instrumento cumple',
                                        'tipo' => 'select',
                                        'option' => array('No', 'Sí')
                                        ),
                  'equipoidfk' => array(
                                        'tipo' => 'hidden',
                                        'valor' => $valores['ID_Equipo']
                                        )
      );

      $arquitectura = array("valores" => array("variables" => 'numero,fecha,nombre,laboratoriocalibro,laboratorioacreditacion,fechaLabAcreditacion,fechacalibracion,parametroevaluado,criterioaceptacion,especificaciones,cumple,correccion',
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
        <?php 
              $valor = "";
              if(isset($value['valor'])){
                $valor = $value['valor'];
              }elseif(isset($valores[$key])){
                $valor = $valores[$key];
              }
              crearForma(
                        (isset($value['label'])) ? $value['label'] : '', //Texto del label
                        $key, //Texto a colocar en los atributos id y name
                        $valor, //Valor extraido de la bd
                        (isset($value['atts'])) ? $value['atts'] : '', //Atributos extra de la etiqueta
                        $value['tipo'], //Tipo de etiqueta
                        (isset($value['option'])) ? $value['option'] : '' //Options para los select
              ); ?>
    	</div>
      <br>
    	<?php endforeach?>
            <input type="button" id="agregar" value="Agregar nuevo intervalo">
      
      <?php if(isset($valores['correccion'])): ?>
        <?php $intervalos = json_decode($valores['correccion'], true); ?>
      <?php endif; ?>
      <div id="intervalos">
        <div>
          <label for="rango[]">Rango:</label><input name="rango[0]" id="rango[0]" value="<?php if(isset($valores['correccion'])) htmlout($intervalos[0]['Rango']); ?>" required>
          <label for="fcorreccion1[]">Factor de Corrección 1:</label><input name="fcorreccion1[0]" id="fcorreccion1[0]" value="<?php htmlout((isset($valores['correccion'])) ? $intervalos[0]['Correccion1'] : "0"); ?>" required>
          <label for="fcorreccion2[]">Factor de Corrección 2:</label><input name="fcorreccion2[0]" id="fcorreccion2[0]" value="<?php htmlout((isset($valores['correccion'])) ? $intervalos[0]['Correccion2'] : "0"); ?>" required>
        </div>
        <?php if(isset($valores['correccion'])): ?>
          <?php for ($i=1; $i <= count($intervalos)-1; $i++): ?>
            <script>
              agregarIntervalo(<?php echo $intervalos[$i]['Rango']; ?>, <?php echo $intervalos[$i]['Correccion1']; ?>, <?php echo $intervalos[$i]['Correccion2']; ?>);
            </script>
          <?php endfor; ?>
        <?php endif; ?>
      </div>

	  <div>
	    <input type="hidden" name="id" value="<?php htmlout($valores['id']); ?>">
	    <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
      <p><a href="?">Volver a calibraciones</a></p>
	    <p><a href="?">Regresa a la búsqueda de ordenes</a></p>
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
</html>
