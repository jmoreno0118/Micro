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
        <?php
        $formulario = array(
                      'fecha' => array(
                                  'label' => 'Fecha (aaaa-mm-dd)',
                                  'tipo' => 'text'
                                ),
                      'departamento' => array(
                                  'label' => 'Departamento',
                                  'tipo' => 'text'
                                ),
                      'area' => array(
                                  'label' => 'Área',
                                  'tipo' => 'text'
                                ),
                      'largo' => array(
                                  'label' => 'Largo',
                                  'tipo' => 'text'
                                ),  
                      'ancho' => array(
                                  'label' => 'Ancho',
                                  'tipo' => 'text'
                                ),
                      'alto' => array(
                                  'label' => 'Alto',
                                  'tipo' => 'text'
                                ),  
                      'numlamp' => array(
                                  'label' => 'Número de lámpara',
                                  'tipo' => 'text'
                                ),
                      'tipolampara' => array(
                                  'label' => 'Tipo de lámpara',
                                  'tipo' => 'text'
                                ),  
                      'potencialamp' => array(
                                  'label' => 'Potencia de lámpara',
                                  'tipo' => 'text'
                                ),
                      'alturalamp' => array(
                                  'label' => 'Altura de lámpara',
                                  'tipo' => 'text'
                                ),  
                      'techocolor' => array(
                                  'label' => 'Color de Techo',
                                  'tipo' => 'text'
                                ),
                      'paredcolor' => array(
                                  'label' => 'Color de Pared',
                                  'tipo' => 'text'
                                ), 
                      'pisocolor' => array(
                                  'label' => 'Color de Piso',
                                  'tipo' => 'text'
                                ),
                      'percepcion' => array(
                                  'label' => 'Percepción de la iluminación',
                                  'tipo' => 'text'
                                ),
                      'mantenimiento' => array(
                                  'label' => 'Programa mantenimiento (max. 250)',
                                  'tipo' => 'textarea',
                                  'atts' => array('maxlength' => '250', 'cols' => '45')
                                ),
                      'influencia' => array(
                                  'label' => 'Influencia',
                                  'tipo' => 'select',
                                  'option' => array('0' => 'No', '1' => 'Sí')
                                ),
                      'descriproceso' => array(
                                  'label' => 'Descripción del proceso (max. 350)',
                                  'tipo' => 'textarea',
                                  'atts' => array('maxlength' => '350', 'cols' => '45')
                                )
        );

        $arquitectura = array("valores" => array("variables" => 'fecha,departamento,area,largo,ancho,alto,numlamp,tipolampara,potencialamp,alturalamp,techocolor,paredcolor,pisocolor,percepcion,mantenimiento,influencia,descriproceso',
                                                  "tipo" => 1),
                              "puestos" => array("variables" => 'puesto,numtrabajadores,actividades',
                                                  "tipo" => 2),
                              "idot" => array("variables" => "idot",
                                                  "tipo" => 0),
        );
        ?>
        <form id="rciform" action="?" method="post">
          <input type="hidden" name="post" value='<?php htmlout(json_encode($_POST)); ?>'>
          <input type="hidden" name="url" value="<?php htmlout($_SESSION['url']); ?>">
          <input type="hidden" name="arquitectura" value='<?php htmlout(json_encode($arquitectura)); ?>'>

          <?php foreach($formulario as $key => $value): ?>
            <?php 
              crearForma(
              $value['label'], //Texto del label
              $key, //Texto a colocar en los atributos id y name
              (isset($valores[$key]))? $valores[$key] : '', //Valor extraido de la bd
              (isset($value['atts'])) ? $value['atts'] : '', //Atributos extra de la etiqueta
              $value['tipo'], //Tipo de etiqueta
              (isset($value['option'])) ? $value['option'] : '' //Options para los select
              );
            ?>
            <br><br>
          <?php endforeach?>
          <fieldset>
            <legend>Descripción de puestos:</legend>
            <?php for ($i=0; $i<20; $i++):?>
            <div style="float: left;margin-right:15px;margin-bottom:15px;border: 1px solid silver;padding:10px;">
              <label for="puestos[<?php echo $i; ?>][puesto]">Puesto:</label>
              <input style="width:250px;"type="text" name="puestos[<?php echo $i; ?>][puesto]" id="puestos[<?php echo $i; ?>][puesto]" value="<?php isset($puestos[$i]) ? htmlout($puestos[$i]["puesto"]) : ""; ?>">
              <br>
              <label for="puestos[<?php echo $i; ?>][numtrabajadores]">Número de trabajadores:</label>
              <input style="width:250px;" type="text" name="puestos[<?php echo $i; ?>][numtrabajadores]" id="puestos[<?php echo $i; ?>][numtrabajadores]" value="<?php isset($puestos[$i]) ? htmlout($puestos[$i]["numtrabajadores"]) : ""; ?>">
              <br>
              <label for="puestos[<?php echo $i; ?>][actividades]">Tareas visuales:</label><br>
              <textarea style="resize: none;" maxlength=350 rows=5 cols=50 name="puestos[<?php echo $i; ?>][actividades]" id="puestos[<?php echo $i; ?>][actividades]"><?php isset($puestos[$i]) ? htmlout($puestos[$i]["actividades"]) : ""; ?></textarea>
            </div>
            <?php endfor; ?>
          </fieldset>
          <div>
            <?php if(isset($id)): ?>
              <input type="hidden" name="id" value="<?php htmlout($id); ?>">
            <?php endif; ?>
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