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
   		$formulario = array("Título de concesión" => array("titulo", "input"),
                          "RFC" => array("rfc", "input"),
                          "Cuenca" => array("cuenca", "input"),
                          "Región Hidrólogica" => array("region", "input"),
             							"Procedencia de la descarga" => array("procedencia", "input"),
             							//"Cuerpo receptor de la descarga" => array("cuerporeceptor", "input"),
                          "Ubicación geográfica del punto de descarga según el título de concesión" => 
                              array("Latitud" => array(
                                                      "Grados" => "lattgrados",
                                                      "Minutos" => "lattmin",
                                                      "Segundos" => "lattseg"
                                    ),
                                    "Longitud" => array(
                                                        "Grados" => "lontgrados",
                                                        "Minutos" => "lontmin",
                                                        "Segundos" => "lontseg"
                                    )
                                  ),
                          "Coordenadas del punto de muestreo" =>
                              array("Latitud" => array(
                                                      "Grados" => "latpgrados",
                                                      "Minutos" => "latpmin",
                                                      "Segundos" => "latpseg"
                                    ),
                                    "Longitud" => array(
                                                        "Grados" => "lonpgrados",
                                                        "Minutos" => "lonpmin",
                                                        "Segundos" => "lonpseg"
                                    )
                                  ),
             							"Datum GPS" => array("datumgps", "input", "WGS84"),
             							"Comentarios" => array("comentarios" , "textarea")
                          );

      $arquitectura = array("valores" => array("variables" => 'titulo,rfc,cuenca,region,procedencia,cuerporeceptor,lattGrados,lattmin,lattseg,lontGrados,lontmin,lontseg,latpGrados,latpmin,latpseg,lonpGrados,lonpmin,lonpseg,datumgps,comentarios',
                                              "tipo" => 1),
                            "id" => array("variables" => "id",
                                          "tipo" => 0));
   ?>
    <form id="siralabform" name="siralabform" action="?" method="post">
      <input type="hidden" name="post" value='<?php htmlout(json_encode($_POST)); ?>'>
      <input type="hidden" name="url" value="<?php htmlout($_SESSION['url']); ?>">
      <input type="hidden" name="arquitectura" value='<?php htmlout(json_encode($arquitectura)); ?>'>

    	<?php foreach($formulario as $label => $name): ?>
    	<div>
        <label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
        <?php if(isset($name[0])): ?>
          <?php 
            if(isset($valores[$name[0]])){
               $valor = $valores[$name[0]];
            }elseif(isset($name[2])){
              $valor = $name[2];
            }else{
              $valor = "";
            } ?>
          <?php if($name[1] === "input"): ?>
              <input type="text" name="<?php htmlout($name[0]); ?>" id="<?php htmlout($name[0]); ?>" value="<?php htmlout($valor) ?>">
          <?php elseif($name[1] === "textarea"): ?>
              <br><textarea style="resize: none;" maxlength=350 rows=5 cols=50 name="<?php htmlout($name[0]); ?>" id="<?php htmlout($name[0]); ?>"><?php htmlout($valor); ?></textarea>
          <?php endif; ?>
        <?php else: ?>
          <?php foreach ($name as $key => $dms): ?>
            <br><br>
            <label><?php htmlout($key); ?>:</label>
            <br>
            <?php foreach ($dms as $key => $value): ?>
              <?php $valor = (isset($valores[$value]))? $valores[$value] : ""; ?>
              <label for="<?php htmlout($value); ?>"><?php htmlout($key); ?>:</label>
              <input type="text" name="<?php htmlout($value); ?>" id="<?php htmlout($value); ?>" value="<?php htmlout($valor) ?>">
            <?php endforeach; ?>
          <?php endforeach; ?>
        <?php endif; ?>
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


   jQuery.validator.addMethod('cuatrocimales', function (value, element, param) {
    return /^\d{1,2}(\.\d{1,4})$/.test(value);
   }, 'Ingresar de 1 a 4 decimales.');

   jQuery.validator.addMethod('dosint', function (value, element, param) {
    return /^\d{1,2}$/.test(value);
   }, 'Ingresar de 1 a 4 decimales.');

    $("#siralabform").validate({
      rules: {
        empresagiro: "required",
        titulo: "required",
        rfc: "required",
        cuenca: "required",
        region: "required",
        procedencia: "required",
        //cuerporeceptor: "required",
        lattGrados: {
         required: true,
         dosint: true
        },
        lattmin: {
         required: true,
         dosint: true
        },
        lattseg: {
         required: true,
         cuatrocimales: true
        },
        lontGrados: {
         required: true,
         dosint: true
        },
        lontmin: {
         required: true,
         dosint: true
        },
        lontseg: {
         required: true,
         cuatrocimales: true
        },
        latpGrados: {
         required: true,
         dosint: true
        },
        latpmin: {
         required: true,
         dosint: true
        },
        latpseg: {
         required: true,
         cuatrocimales: true
        },
        lonpGrados: {
         required: true,
         dosint: true
        },
        lonpmin: {
         required: true,
         dosint: true
        },
        lonpseg: {
         required: true,
         cuatrocimales: true
        },
        datumgps: "required",
        comentarios: "required"
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
