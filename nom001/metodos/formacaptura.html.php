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
    <link rel="stylesheet" href="../../includes/jquery-validation-1.13.1/demo/site-demos.css">
    <script type="text/javascript" src="../../includes/jquery-validation-1.13.1/lib/jquery.js"></script>
    <script type="text/javascript" src="../../includes/jquery-validation-1.13.1/lib/jquery-1.11.1.js"></script>
    <script type="text/javascript" src="../../includes/jquery-validation-1.13.1/dist/jquery.validate.js"></script>
    <script type="text/javascript" src="../../includes/jquery-validation-1.13.1/dist/additional-methods.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $("#acredform").validate({
          rules: {
            nombre: {
             required: true
            },
            fecha: {
             required: true,
             date: true
            }
          },
          success: "valid"
        });
      });</script>
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
         <?php $formulario = array(
                        'parametro' => array(    
                                              'label' => 'Parametro',
                                              'tipo' => 'select2',
                                              'option' => $parametros
                                              ),
                        'metodo' => array(
                                              'label' => 'Metodo',
                                              'tipo' => 'text'
                                              )
            );
          ?>
          <form id="acredform" name="acredform"  action="" method="post">
            <?php foreach($formulario as $key => $value): ?>
            <div>
              <?php
              $value['atts'] = array('style' => 'width:250px');
              crearForma(
                  (isset($value['label'])) ? $value['label'] : '', //Texto del label
                  $key, //Texto a colocar en los atributos id y name
                  (isset($valores[$key])) ? $valores[$key] : '', //Valor extraido de la bd
                  (isset($value['atts'])) ? $value['atts'] : '', //Atributos extra de la etiqueta
                  $value['tipo'], //Tipo de etiqueta
                  (isset($value['option'])) ? $value['option'] : '' //Options para los select
              );
              ?>
            </div>
            <br>
            <?php endforeach; ?>
      	  <div>
            <?php if(isset($id)){ ?>
              <input type="hidden" name="id" value="<?php htmlout($id); ?>">
            <?php } ?>
      	    <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
      	  </div> 
      	</form>
        <form action="" method="post">
            <input type="submit" name="accion" value="Volver">
        </form>
      </div>  <!-- cuerpoprincipal -->
      <div id="footer">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
      </div>  <!-- footer -->
    </div> <!-- contenedor -->
  </body>
</html>
