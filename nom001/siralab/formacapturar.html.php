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
                        'titulo' => array(
                                          'label' => 'Título de concesión',
                                          'tipo' => 'text'
                                          ),
                        'anexo' => array(
                                          'label' => 'Anexo',
                                          'tipo' => 'text'
                                          ),
                        'rfc' => array(
                                          'label' => 'RFC',
                                          'tipo' => 'text'
                                          ),
                        'cuenca' => array(
                                          'label' => 'Cuenca',
                                          'tipo' => 'text'
                                          ),
                        'tipoestudio' => array(
                                          'label' => 'Tipo de estudio',
                                          'tipo' => 'text'
                                          ),
                        'numerodescarga' => array(
                                          'label' => 'Número de descarga',
                                          'tipo' => 'text'
                                          ),
                        'region' => array(
                                          'label' => 'Región Hidrólogica',
                                          'tipo' => 'text'
                                          ),
                        'procedencia' => array(
                                          'label' => 'Procedencia de la descarga',
                                          'tipo' => 'text'
                                          ),
                        'ubicaciongeo' => array(
                                          'label' => 'Ubicación geográfica del punto de descarga según el título de concesión'
                                          ),
                        'latitud' => array(
                                          'label' => 'Latitud'
                                          ),
                        'lattgrados' => array(
                                          'label' => 'Grados',
                                          'tipo' => 'text'
                                          ),
                        'lattmin' => array(
                                          'label' => 'Minutos',
                                          'tipo' => 'text'
                                          ),
                        'lattseg' => array(
                                          'label' => 'Segundos',
                                          'tipo' => 'text'
                                          ),
                        'longitud' => array(
                                          'label' => 'Longitud'
                                          ),
                        'lontgrados' => array(
                                          'label' => 'Grados',
                                          'tipo' => 'text'
                                          ),
                        'lontmin' => array(
                                          'label' => 'Minutos',
                                          'tipo' => 'text'
                                          ),
                        'lontseg' => array(
                                          'label' => 'Segundos',
                                          'tipo' => 'text'
                                          ),
                        'muestreo' => array(
                                          'label' => 'Coordenadas del punto de muestreo'
                                          ),
                        'latitud1' => array(
                                          'label' => 'Latitud'
                                          ),
                        'latpgrados' => array(
                                          'label' => 'Grados',
                                          'tipo' => 'text'
                                          ),
                        'latpmin' => array(
                                          'label' => 'Minutos',
                                          'tipo' => 'text'
                                          ),
                        'latpseg' => array(
                                          'label' => 'Segundos',
                                          'tipo' => 'text'
                                          ),
                        'longitud1' => array(
                                          'label' => 'Longitud'
                                          ),
                        'lonpgrados' => array(
                                          'label' => 'Grados',
                                          'tipo' => 'text'
                                          ),
                        'lonpmin' => array(
                                          'label' => 'Minutos',
                                          'tipo' => 'text'
                                          ),
                        'lonpseg' => array(
                                          'label' => 'Segundos',
                                          'tipo' => 'text'
                                          ),
                        'datumgps' => array(
                                          'label' => 'Datum GPS',
                                          'tipo' => 'text'
                                          ),
                        'comentarios' => array(
                                          'label' => 'Comentarios',
                                          'tipo' => 'textarea'
                                          )
            );

            $formulario2 = array(
                        'id' => array(
                                            'tipo' => 'hidden'
                                            ),
                        'fechalab' => array(
                                            'label' => 'Fecha recepción laboratorio(aaaa-mm-dd)',
                                            'tipo' => 'text'
                                            ),
                        'horalab' => array(
                                            'label' => 'Hora recepción laboratorio(hh:mm)',
                                            'tipo' => 'text'
                                            ),
                        'identificacion' => array(
                                            'label' => 'Identificación',
                                            'tipo' => 'text'
                                            ),
                        'temperatura' => array(
                                            'label' => 'Temperatura',
                                            'tipo' => 'text'
                                            ),
                        'pH' => array(
                                            'label' => 'pH',
                                            'tipo' => 'text'
                                            ),

              );

            $arquitectura = array(
                                  "valores" => array("variables" => 'titulo,anexo,rfc,cuenca,region,procedencia,cuerporeceptor,lattgrados,lattmin,lattseg,lontgrados,lontmin,lontseg,latpgrados,latpmin,latpseg,lonpgrados,lonpmin,lonpseg,datumgps,comentarios',
                                                    "tipo" => 1),
                                  "mcompuestas" => array("variables" => 'fechalab,horalab,identificacion',
                                                    "tipo" => 2),
                                  "id" => array("variables" => "id",
                                                "tipo" => 0),
                                  "regreso" => array("variables" => "id",
                                                      "tipo" => 0,
                                                      "valor" => 2),
                                  "cantidad" => array("variables" => "cantidad",
                                                  "tipo" => 0),
                                  "boton" => array("variables" => "accion",
                                                    "tipo" => 0)
                                  );
          ?>
          <form id="siralabform" name="siralabform" action="?" method="post">
              <input type="hidden" name="post" value='<?php htmlout(json_encode($_POST)); ?>'>
              <input type="hidden" name="url" value="<?php htmlout($_SESSION['url']); ?>">
              <input type="hidden" name="arquitectura" value='<?php htmlout(json_encode($arquitectura)); ?>'>
              <input type="hidden" name="cantidad" value="<?php htmlout($cantidad); ?>">

              <?php foreach($formulario as $key => $value): ?>
                  <div>
                      <?php if(isset($_SESSION['supervisada'])) $value['atts'] = array('disabled'); ?>
                      <?php
                      crearForma(
                          $value['label'], //Texto del label
                          $key, //Texto a colocar en los atributos id y name
                          (isset($valores[$key])) ? $valores[$key] : '', //Valor extraido de la bd
                          (isset($value['atts'])) ? $value['atts'] : '', //Atributos extra de la etiqueta
                          (isset($value['tipo'])) ? $value['tipo'] : '', //Tipo de etiqueta
                          (isset($value['option'])) ? $value['option'] : '' //Options para los select
                      ); ?>
                  </div>
                  <br>
              <?php endforeach?>


              <?php foreach ($formulario2 as $key => $value):
                        if($value['tipo'] !== 'hidden'){ ?>
                            <fieldset>
                                <legend><?php echo $value['label']; ?>:</legend>
                                <?php 
                                for ($i=0; $i<$cantidad+1; $i++):
                                    if(($i+1 === $cantidad+1) AND ($key === "flujo" OR $key === "volumen")):
                                        continue;
                                    endif;

                                    if(isset($_SESSION['supervisada'])) $value['atts'] = array('disabled');

                                    $compuesta = '';
                                    if($mcompuestas !== ""){
                                        if( isset($mcompuestas[$i][$key]) AND ($key === 'fechalab' AND $mcompuestas[$i][$key] !== '0000-00-00') OR ($key === 'horalab' AND $mcompuestas[$i][$key] !== '00:00') OR ($key === 'identificacion') OR ($key === 'temperatura') OR ($key === 'pH')){
                                            $compuesta = $mcompuestas[$i][$key];
                                        }
                                    }
                                    crearForma(
                                        "Muestra ".(($i < $cantidad) ? $i+1 : "Compuesta"), //Texto del label
                                        "mcompuestas[".$i."][".$key."]", //Texto a colocar en los atributos id y name
                                        $compuesta, //Valor extraido de la bd
                                        (isset($value['atts'])) ? $value['atts'] : '', //Atributos extra de la etiqueta
                                        $value['tipo'], //Tipo de etiqueta
                                        (isset($value['option'])) ? $value['option'] : '' //Options para los select
                                    ); ?>
                                    <br>
                                <?php
                                endfor;
                                ?>
                            </fieldset>
                            <br>
                  <?php
                        }else{
                            for ($i=0; $i<$cantidad+1; $i++) :
                                if(isset($_SESSION['supervisada'])) $value['atts'] = array('disabled');
                              
                                crearForma(
                                    '', //Texto del label
                                    "mcompuestas[".$i."][".$key."]", //Texto a colocar en los atributos id y name
                                    ($mcompuestas !== "") ? (isset($mcompuestas[$i][$key])) ? $mcompuestas[$i][$key] : '' : '', //Valor extraido de la bd
                                    '', //Atributos extra de la etiqueta
                                    $value['tipo'], //Tipo de etiqueta
                                    '' //Options para los select
                                );
                            endfor; 
                        }
                    endforeach; ?>
              <div>
                  <input type="hidden" name="id" value="<?php htmlout($id); ?>">
                  <?php if(isset($_SESSION['supervisada'])){ ?>
                      <p><a href="../generales">Terminar</a></p>
                  <?php }else{ ?>
                      <input type="submit" name="accion" value="<?php htmlout($boton); ?>"> 
                  <?php } ?>
                  <p><a href="../generales">Volver a mediciones</a></p>
                  <p><a href="../../nom001">Regresa a la búsqueda de ordenes</a></p>
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
   jQuery.validator.addMethod('hora', function (value, element, param) {
    return /^ *|([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(value); 
   }, 'Favor de introducir una hora valida.');

   jQuery.validator.addMethod('cuatrocimales', function (value, element, param) {
    return /^ *|\d{1,2}(\.\d{1,4})$/.test(value);
   }, 'Ingresar de 1 a 4 decimales.');

   jQuery.validator.addMethod('tresint', function (value, element, param) {
    return /^ *|\d{1,3}$/.test(value);
   }, 'Ingresar de 1 a 3 enteros.');


   jQuery.validator.addMethod('doscimales', function (value, element, param) {
    return /^ *|\d{1,2}(\.\d{1,2})$/.test(value);
   }, 'Ingresar de 1 a 2 decimales.');

    $("#siralabform").validate({
      rules: {
        empresagiro: "required",
        titulo: "required",
        anexo: "required",
        rfc: "required",
        cuenca: "required",
        region: "required",
        procedencia: "required",
        lattgrados: {
         required: true,
         tresint: true
        },
        lattmin: {
         required: true,
         tresint: true
        },
        lattseg: {
         required: true,
         cuatrocimales: true
        },
        lontgrados: {
         required: true,
         tresint: true
        },
        lontmin: {
         required: true,
         tresint: true
        },
        lontseg: {
         required: true,
         cuatrocimales: true
        },
        latpgrados: {
         required: true,
         tresint: true
        },
        latpmin: {
         required: true,
         tresint: true
        },
        latpseg: {
         required: true,
         cuatrocimales: true
        },
        lonpgrados: {
         required: true,
         tresint: true
        },
        lonpmin: {
         required: true,
         tresint: true
        },
        lonpseg: {
         required: true,
         cuatrocimales: true
        },
        datumgps: "required",
        comentarios: "required",
        <?php for ($i=0; $i<$cantidad+1; $i++) :
        echo "
          'mcompuestas[$i][fechalab]':{
            date: true
          },
          'mcompuestas[$i][horalab]':{
            hora: true
          },
          'mcompuestas[$i][identificacion]':{
          },
          'mcompuestas[$i][temperatura]':{
            doscimales: true
          },
          'mcompuestas[$i][pH]':{
            doscimales: true
          }
        ";
        echo ($i<$cantidad+2)? "," : "";
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