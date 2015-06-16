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
   <?php //var_dump($valores);
   //var_dump($representantes);
        $formulario = array(
                    'nombre' => array(
                                        'label' => 'Nombre',
                                        'tipo' => 'text'
                                        ),
                    'ap' => array(
                                        'label' => 'Apellido Paterno',
                                        'tipo' => 'text'
                                        ),
                    'am' => array(
                                        'label' => 'Apellido Materno',
                                        'tipo' => 'text'
                                        ),
                    'estudiosmuestreador' => array(
                                        'label' => 'Estudios de Muestreador',
                                        'tipo' => 'check',
                                        'options' => $estudios,
                                        'extra' => array('multi' => 1, 'comp' => 'texto', 'value' => 'texto')
                                        ),
                    'representantes' => array(
                                        'label' => 'Representantes',
                                        'tipo' => 'check',
                                        'options' => $representantes,
                                        'extra' => array('multi' => 1, 'comp' => 'value', 'value' => 'value')
                                        ),
                    'signatario' => array(
                                        'label' => 'Signatario',
                                        'tipo' => 'check',
                                        'options' => array('1' => 'Sí'),
                                        'extra' => array('multi' => 0, 'comp' => 'value', 'value' => 'value')
                                        ),
                    'estudiossignatarios' => array(
                                        'label' => 'Estudios para Signatario',
                                        'tipo' => 'check',
                                        'options' => $estudios,
                                        'atts' => array('disabled', 'class' => 'estudiossignatarios'),
                                        'extra' => array('multi' => 1, 'comp' => 'texto', 'value' => 'texto')
                                        ),
        );

      $arquitectura = array("valores" => array("variables" => 'nombre,ap,am,estudios,representantes,signatario',
                                              "tipo" => 2),
                            "id" => array("variables" => "id",
                                          "tipo" => 0),
                            "regreso" => array("variables" => "id",
                                                "tipo" => 0,
                                                "valor" => 2)
                            );
    ?>
    <form action="" method="post">
		<?php foreach($formulario as $key => $value): ?>
			<?php if($key === 'estudiossignatarios'){
				if(isset($valores['signatario']) AND $valores['signatario'] === 1){
					$value['atts'] = array('class' => 'estudiossignatarios');
				}
			}?>
			<div>
				<?php crearForma(
				            $value['label'], //Texto del label
				            $key, //Texto a colocar en los atributos id y name
				            (isset($valores[$key])) ? $valores[$key] : '', //Valor extraido de la bd
				            (isset($value['atts'])) ? $value['atts'] : '', //Atributos extra de la etiqueta
				            $value['tipo'], //Tipo de etiqueta
				            (isset($value['options'])) ? $value['options'] : '', //Options para los select
				            (isset($value['extra'])) ? $value['extra'] : '' //Extra para seleccionar multi check
				  ); ?>
			</div>
			<br>
		<?php endforeach?>
	  <div>	
	    <input type="hidden" name="id" value="<?php htmlout($id); ?>">
	    <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
	  </div> 
	</form>
	<p><a href="">Volver a muestreadores</a></p>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>

<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/dist/jquery.validate.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/dist/additional-methods.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#signatario').change(function() {
		  if ($(this).is(':checked')) {
		    $('.estudiossignatarios').prop("disabled", false);
		  } else {
		    $('.estudiossignatarios').prop("disabled", true);
		  }
		});
	});
</script>