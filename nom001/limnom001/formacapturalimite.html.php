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
                      'GyA' => array(
                                            'label' => 'Grasas y Aceites',
                                            'tipo' => 'text'
                                            ),
                      'coliformes' => array(
                                            'label' => 'Coliformes Fecales',
                                            'tipo' => 'text'
                                            ),
                      'ssedimentables' => array(
                                            'label' => 'Solidos sedimentables',
                                            'tipo' => 'text'
                                            ),
                      'ssuspendidos' => array(
                                            'label' => 'Solidos suspendidos',
                                            'tipo' => 'text'
                                            ),
                      'dbo' => array(
                                            'label' => 'DBO',
                                            'tipo' => 'text'
                                            ),
                      'nkjedahl' => array(
                                            'label' => 'Nitrógeno Kjeldahl',
                                            'tipo' => 'text'
                                            ),
                      'nitritos' => array(
                                            'label' => 'Nitrógeno de Nitritos',
                                            'tipo' => 'text'
                                            ),
                      'nitratos' => array(
                                            'label' => 'Nitrógeno de Nitratos',
                                            'tipo' => 'text'
                                            ),
                      'fosforo' => array(
                                            'label' => 'Fosforo',
                                            'tipo' => 'text'
                                            ),
                      'arsenico' => array(
                                            'label' => 'Arsenico',
                                            'tipo' => 'text'
                                            ),
                      'cadmio' => array(
                                            'label' => 'Cadmio',
                                            'tipo' => 'text'
                                            ),
                      'cianuros' => array(
                                            'label' => 'Cianuros',
                                            'tipo' => 'text'
                                            ),
                      'cobre' => array(
                                            'label' => 'Cobre',
                                            'tipo' => 'text'
                                            ),
                      'cromo' => array(
                                            'label' => 'Cromo',
                                            'tipo' => 'text'
                                            ),
                      'mercurio' => array(
                                            'label' => 'Mercurio',
                                            'tipo' => 'text'
                                            ),
                      'niquel' => array(
                                            'label' => 'Niquel',
                                            'tipo' => 'text'
                                            ),
                      'plomo' => array(
                                            'label' => 'Plomo',
                                            'tipo' => 'text'
                                            ),
                      'zinc' => array(
                                            'label' => 'Zinc',
                                            'tipo' => 'text'
                                            ),
                      'hdehelminto' => array(
                                            'label' => 'Huevos de Helminto',
                                            'tipo' => 'text'
                                            )
          );
        ?>
        <form id="limitesform" action="" method="post">
        	<?php foreach($formulario as $key => $value): ?>
          	<div>
          		<?php
              crearForma(
                  $value['label'], //Texto del label
                  $key, //Texto a colocar en los atributos id y name
                  (isset($valores[$key])) ? $valores[$key] : '', //Valor extraido de la bd
                  (isset($value['atts'])) ? $value['atts'] : '', //Atributos extra de la etiqueta
                  $value['tipo'], //Tipo de etiqueta
                  (isset($value['option'])) ? $value['option'] : '' //Options para los select
              );
              ?>
          	</div>
        	<?php endforeach?>
          <div>
            <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
            <p><a href="../../">Regresa a administrador</a></p>
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
    jQuery.validator.addMethod('permitido', function (value, element, param) {
    return /^(\d{1,2}\.\d{1,4})$/.test(value); 
    }, 'Ingresar de 1 a 4 decimales.');

    $("#limitesform").validate({
      rules: {
        GyA: {
          required: true,
          permitido: true
        },
        coliformes: {
          required: true,
          permitido: true
        },
        ssedimentables: {
          required: true,
          permitido: true
        },
        ssedimentables: {
          required: true,
          permitido: true
        },
        ssuspendidos: {
         required: true,
         permitido: true
        },
        dbo: {
         required: true,
         permitido: true
        },
        nkjedahl: {
         required: true,
         permitido: true
        },
        nitritos: {
         required: true,
         permitido: true
        },
        nitratos: {
         required: true,
         permitido: true
        },
        /*nitrogeno: {
         required: true,
         permitido: true
        },*/
        fosforo: {
         required: true,
         permitido: true
        },
        arsenico: {
         required: true,
         permitido: true
        },
        cadmio: {
         required: true,
         permitido: true
        },
        cianuros: {
         required: true,
         permitido: true
        },
        cobre: {
         required: true,
         permitido: true
        },
        cromo: {
         required: true,
         permitido: true
        },
        mercurio: {
         required: true,
         permitido: true
        },
        niquel: {
         required: true,
         permitido: true
        },
        plomo: {
         required: true,
         permitido: true
        },
        zinc: {
         required: true,
         permitido: true
        },
        hdehelminto: {
         required: true,
         permitido: true
        }
      },
      success: "valid"
    });
});</script>