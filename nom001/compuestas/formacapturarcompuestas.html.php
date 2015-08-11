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
        <?php //var_dump($mcompuestas);
            $formulario = array(
                        'id'   => array(
                                            'tipo' => 'hidden'
                                            ),
                        'hora' => array(
                                            'label' => 'Hora(hh:mm)',
                                            'tipo' => 'text'
                                            ),
                        'flujo' => array(
                                            'label' => 'Flujo(m3/s) Ej. 1.1234',
                                            'tipo' => 'text'
                                            ),
                        'volumen' => array(
                                            'label' => 'Volumen(ml)',
                                            'tipo' => 'text'
                                            )
            );

            $formulario2 = array(
                        'caracteristicas' => array(
                                            'label' => 'Caracteristicas (Max. 350)',
                                            'tipo' => 'textarea'
                                            ),
                        'observaciones' => array(
                                            'label' => 'Observaciones',
                                            'tipo' => 'textarea'
                                            )
              );

          $arquitectura = array("mcompuestas" => array("variables" => 'id,hora,flujo,volumen,observaciones,caracteristicas,fechalab,horalab',
                                                  "tipo" => 2),
                                "id" => array("variables" => "id",
                                              "tipo" => 0),
                                "muestreoid" => array("variables" => "muestreoid",
                                              "tipo" => 0),
                                "regreso" => array("variables" => "id",
                                                    "tipo" => 0,
                                                    "valor" => 2),
                                "cantidad" => array("variables" => "cantidad",
                                                    "tipo" => 0)
                                );
        ?>
          <form id="compuestasform" action="" method="post">
            <input type="hidden" name="post" value='<?php echo json_encode($_POST); ?>'>
            <input type="hidden" name="url" value="<?php htmlout($_SESSION['url']); ?>">
            <input type="hidden" name="arquitectura" value='<?php echo json_encode($arquitectura); ?>'>
            <input type="hidden" name="cantidad" value="<?php htmlout($cantidad); ?>">

            <?php /*Foreach 1*/ foreach ($formulario as $key => $value): ?>
              <?php if($key !== 'id'){ ?>
                <fieldset>
                <legend><?php echo $value['label']; ?>:</legend>
              <?php } ?>
              <?php /* For1 */ for ($i = 0; $i<$cantidad+1; $i++): ?>
                <?php if(($i+1 === $cantidad+1) AND ($key === "flujo" OR $key === "volumen"))
                      {
                        continue;
                      }

                      if(isset($_SESSION['supervisada']))
                      {
                        $value['atts'] = array('disabled');
                      } ?>
                <?php
                  crearForma(
                      "Toma ".(($i < $cantidad) ? $i+1 : "Compuesta"), //Texto del label
                      "mcompuestas[".$i."][".$key."]", //Texto a colocar en los atributos id y name
                      ($mcompuestas !== "") ? (isset($mcompuestas[$i][$key])) ? $mcompuestas[$i][$key] : '' : '', //Valor extraido de la bd
                      (isset($value['atts'])) ? $value['atts'] : '', //Atributos extra de la etiqueta
                      $value['tipo'], //Tipo de etiqueta
                      (isset($value['option'])) ? $value['option'] : '' //Options para los select
                  );
                ?>
                <?php if($key !== 'id'){
                        echo "<br>";
                      } ?>
              <?php /* For1 */ endfor; ?>
              <?php if($key !== 'id'){ ?>
                </fieldset>
                <br>
              <?php } ?>
            <?php /*Foreach 1*/ endforeach; ?>

            <?php for ($j=0; $j < ($cantidad+1); $j++) { ?>
              <fieldset>
                <legend><?php echo "Toma ".(($j < $cantidad) ? $j+1 : "Compuesta"); ?>:</legend>
                <?php /* Foreach2 */ foreach ($formulario2 as $key => $value): ?>
                  <?php if(isset($_SESSION['supervisada']))
                        {
                          $value['atts'] = array('disabled');
                        } ?>
                  <?php
                    crearForma(
                        $value['label'], //Texto del label
                        "mcompuestas[".$j."][".$key."]", //Texto a colocar en los atributos id y name
                        ($mcompuestas !== "") ? (isset($mcompuestas[$j][$key])) ? $mcompuestas[$j][$key] : '' : '', //Valor extraido de la bd
                        (isset($value['atts'])) ? $value['atts'] : '', //Atributos extra de la etiqueta
                        $value['tipo'], //Tipo de etiqueta
                        (isset($value['option'])) ? $value['option'] : '' //Options para los select
                    );
                  ?>
                  <br>
                <?php /* Foreach2 */ endforeach; ?>
              </fieldset>
              <br>
            <?php } ?>

            <!-- Se usa para almacenar el numero de la ot y saber que se usó el regreso -->
            <?php if(isset($regreso) AND $regreso === 1)
                  { ?>
              <input type="hidden" name="regreso" value="1">
              <input type="hidden" name="ot" value="<?php htmlout($_SESSION['OT']); ?>">
            <?php } ?>
            <input type="hidden" name="id" value="<?php htmlout($id); ?>">
            <input type="hidden" name="muestreoid" value="<?php htmlout($muestreoid); ?>">
            <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
            <input type="submit" name="accion" value="siguiente">
          </form>
        <br>
        <form action="http://<?php echo $_SERVER['HTTP_HOST']; ?>/reportes/nom001/generales/" method="post">
          <input type="hidden" name="regreso" value="1">
          <input type="hidden" name="id" value="<?php htmlout($id); ?>">
          <input type="hidden" name="meds" value="">
          <input type="submit" name="accion" value="volver">
        </form>
        <p><a href="../generales">Volver a mediciones</a></p>
        <p><a href="../../nom001">Regresa a la búsqueda de ordenes</a></p>
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
  jQuery.validator.addMethod('hora', function (value, element, param) {
  return /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(value); 
  }, 'Favor de introducir una hora valida.');

  jQuery.validator.addMethod('decimal', function (value, element, param) {
  return /^\d{1,2}(\.\d{1,3})$/.test(value); 
  }, 'Ingresar de 1 a 3 decimales.');

  jQuery.validator.addMethod('volumen', function (value, element, param) {
  return /^(\S\/\F|[s]\/[f]|\d{1,10})$/.test(value); 
  }, 'Ingresar enteros.');

  jQuery.validator.addMethod('flujo', function (value, element, param) {
  return /^(\S\/\F|[s]\/[f]|\d{1,2}\.\d{1,10})$/.test(value); 
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
      volumen: true
    },
    'mcompuestas[$i][observaciones]':{
     required: true,
     maxlength: 350
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
    },";
     if($i+1 === $cantidad):
     echo "'mcompuestas[".($i+1)."][observaciones]':{
       required: true,
       maxlength: 350
      },
      'mcompuestas[".($i+1)."][caracteristicas]':{
       required: true,
       maxlength: 350
      }";
     endif;
     endfor; ?>
    },
    success: "valid"
  });
});</script>