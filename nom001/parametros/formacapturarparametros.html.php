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
    <script type="text/javascript" src="../../includes/jquery-validation-1.13.1/lib/jquery.js"></script>
    <script type="text/javascript" src="../../includes/jquery-validation-1.13.1/lib/jquery-1.11.1.js"></script>
    <script type="text/javascript">
      i = 10;
      function agregarIntervalo(nombre, unidades, resultado, metodo, disabled){
        $('#adicionales').append('<div>'
          +(i+1)+': '
          +'<label for="adicionales['+i+'][nombre]">Nombre del párametro: </label>'
          +'<input type="text" name="adicionales['+i+'][nombre]" id="adicionales['+i+'][nombre]" value="'+nombre+'" '+disabled+'> '

          +'<label for="adicionales['+i+'][unidades]">Unidades: </label>'
          +'<input type="text" name="adicionales['+i+'][unidades]" id="adicionales['+i+'][unidades]" value="'+unidades+'" '+disabled+'> '

          +'<label for="adicionales['+i+'][resultado]">Resultado: </label>'
          +'<input type="text" name="adicionales['+i+'][resultado]" id="adicionales['+i+'][resultado]" value="'+resultado+'" '+disabled+'> '

          +'<label for="adicionales['+i+'][metodo]">Resultado: </label>'
          +'<input type="text" name="adicionales['+i+'][metodo]" id="adicionales['+i+'][metodo]" value="'+metodo+'" '+disabled+'> '
          +'</div>');
        i += 1;
      }

      function metodos(parametro, seleccionado, select){
        $.ajax({
          type: "POST",
          url: "metodos.php",
          data: {parametro: parametro, seleccionado: seleccionado},
          cache: false,
          success: function(html){
            $(select).html(html);
          }
        });
      }
    </script>
  </head>
  <body>
    <div id="contenedor">
      <header>
       <?php $ruta='/reportes/img/logoblco2.gif';
    	  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/encabezado.inc.php'; ?>
      </header>
      <div id="cuerpoprincipal">
          <h2><?php htmlout($titulopagina); ?></h2>
          <?php
                $formulario = array(
                            'fechareporte' => array(
                                              'label' => 'Fecha de Reporte(aaaa-mm-dd)',
                                              'tipo' => 'text'
                                              ),
                            'ssedimentables' => array(
                                              'label' => 'Sólidos Sedimentables',
                                              'tipo' => 'text',
                                              'metodo' => 'Sólidos Sedimentables'
                                              ),
                            'ssuspendidos' => array(
                                              'label' => 'Solidos suspendidos',
                                              'tipo' => 'text',
                                              'metodo' => 'Solidos suspendidos'
                                              ),
                            'dbo' => array(
                                              'label' => 'DBO',
                                              'tipo' => 'text',
                                              'metodo' => 'DBO'
                                              ),
                            'nkjedahl' => array(
                                              'label' => 'Nitrógeno Kjeldahl',
                                              'tipo' => 'text',
                                              'atts' => array('class' => 'nits'),
                                              'metodo' => 'Nitrógeno Kjeldahl'
                                              ),
                            'nitritos' => array(
                                              'label' => 'Nitrógeno de Nitritos',
                                              'tipo' => 'text',
                                              'atts' => array('class' => 'nits'),
                                              'metodo' => 'Nitrógeno de Nitritos'
                                              ),
                            'nitratos' => array(
                                              'label' => 'Nitrógeno de Nitratos',
                                              'tipo' => 'text',
                                              'atts' => array('class' => 'nits'),
                                              'metodo' => 'Nitrógeno de Nitratos'
                                              ),
                            'nitrogeno' => array(
                                              'label' => 'Nitrógeno',
                                              'tipo' => 'text',
                                              'atts' => array('disabled'),
                                              'metodo' => 'Nitrógeno'
                                              ),
                            'fosforo' => array(
                                              'label' => 'Fósforo',
                                              'tipo' => 'text',
                                              'metodo' => 'Fósforo'
                                              ),
                            'arsenico' => array(
                                              'label' => 'Arsenico',
                                              'tipo' => 'text',
                                              'metodo' => 'Arsenico'
                                              ),
                            'cadmio' => array(
                                              'label' => 'Cadmio',
                                              'tipo' => 'text',
                                              'metodo' => 'Cadmio'
                                              ),
                            'cianuros' => array(
                                              'label' => 'Cianuros',
                                              'tipo' => 'text',
                                              'metodo' => 'Cianuros'
                                              ),
                            'cobre' => array(
                                              'label' => 'Cobre',
                                              'tipo' => 'text',
                                              'metodo' => 'Cobre'
                                              ),
                            'cromo' => array(
                                              'label' => 'Cromo',
                                              'tipo' => 'text',
                                              'metodo' => 'Cromo'
                                              ),
                            'mercurio' => array(
                                              'label' => 'Mercurio',
                                              'tipo' => 'text',
                                              'metodo' => 'Mercurio'
                                              ),
                            'niquel' => array(
                                              'label' => 'Niquel',
                                              'tipo' => 'text',
                                              'metodo' => 'Niquel'
                                              ),
                            'plomo' => array(
                                              'label' => 'Plomo',
                                              'tipo' => 'text',
                                              'metodo' => 'Plomo'
                                              ),
                            'zinc' => array(
                                              'label' => 'Zinc',
                                              'tipo' => 'text',
                                              'metodo' => 'Zinc'
                                              ),
                            'hdehelminto' => array(
                                              'label' => 'Huevos de Helminto',
                                              'tipo' => 'text',
                                              'metodo' => 'Huevos de Helminto'
                                              )
                );

                $arquitectura = array(
                                      "valores" => array("variables" => 'fechareporte,ssedimentables,ssuspendidos,dbo,nkjedahl,nitritos,nitratos,nitrogeno,fosforo,arsenico,cadmio,cianuros,cobre,cromo,mercurio,niquel,plomo,zinc,hdehelminto',
                                                        "tipo" => 1),
                                      "metodos" => array("variables" => 'metodossedimentablesmetodo,ssuspendidosmetodo,dbometodo,nkjedahlmetodo,nitritosmetodo,nitratosmetodo,nitrogenometodo,fosforometodo,arsenicometodo,cadmiometodo,cianurosmetodo,cobremetodo,cromometodo,mercuriometodo,niquelmetodo,plomometodo,zincmetodo,hdehelmintometodo,GyAmetodo,coliformes'metodo,
                                                        "tipo" => 1),
                                      "parametros" => array("variables" => 'GyA,coliformes',
                                                    "tipo" => 2),
                                      "adicionales" => array("variables" => 'nombre,unidades,resultado,metodo',
                                                    "tipo" => 2),
                                      "id" => array("variables" => "id",
                                                    "tipo" => 0),
                                      "muestreoid" => array("variables" => "muestreoid",
                                                    "tipo" => 0),
                                      "idparametro" => array("variables" => "idparametro",
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
          <form id="medsform" name="medsform"  action="" method="post">
              <input type="hidden" name="post" value='<?php echo json_encode($_POST); ?>'>
              <input type="hidden" name="prevact" value='<?php htmlout($_SESSION['accion']); ?>'>
              <input type="hidden" name="url" value="<?php htmlout($_SESSION['url']); ?>">
              <input type="hidden" name="arquitectura" value='<?php echo json_encode($arquitectura); ?>'>
              <input type="hidden" name="cantidad" value="<?php htmlout($cantidad); ?>">

              <fieldset>
                  <legend>Resultados de laboratorio:</legend>
                	<?php foreach($formulario as $key => $value): ?>
                    	<div id="">
                          <?php if(isset($_SESSION['supervisada']))
                                {
                                  $value['atts'] = array('disabled');
                                }
                                
                                crearForma(
                                          $value['label'], //Texto del label
                                          $key, //Texto a colocar en los atributos id y name
                                          (isset($valores[$key])) ? $valores[$key] : '', //Valor extraido de la bd
                                          (isset($value['atts'])) ? $value['atts'] : '', //Atributos extra de la etiqueta
                                          $value['tipo'], //Tipo de etiqueta
                                          (isset($value['option'])) ? $value['option'] : '' //Options para los select
                                );

                                if($key==="nitrogeno")
                                { ?> 
                                    <input type="hidden" name="<?php htmlout($key); ?>" id="<?php htmlout($key); ?>">
                                <?php 
                                }

                                if($key!=="nitrogeno" AND $key!=="fechareporte"){ ?>
                                    <select id="metodo<?php echo $key; ?>" name="metodo<?php echo $key; ?>"></select>
                                    <script type="text/javascript">metodos("<?php echo $value['metodo']; ?>", "<?php echo $metodos[$key]; ?>", "<?php echo '#metodo'.$key; ?>");</script>
                                <?php 
                                }
                          ?>
                    	</div>
                	<?php endforeach?>

                  <?php
                  $disabled = '';
                  if(isset($_SESSION['supervisada'])){ 
                      $disabled = 'disabled';
                  } ?>

                  <div>
                      <label for="metodoGyA">Metodo Grasas y Aceites:</label>
                      <select id="metodoGyA" name="metodoGyA"></select>
                      <script type="text/javascript">metodos("Grasas y Aceites", "<?php echo $metodos['GyA']; ?>", "#metodoGyA");</script>
                  </div>

                  <div>
                      <label for="metodoGyA">Metodo Coliformes:</label>
                      <select id="metodocoliformes" name="metodocoliformes"></select>
                      <script type="text/javascript">metodos("Coliformes", "<?php echo $metodos['coliformes']; ?>", "#metodocoliformes");</script>
                  </div>

                  <?php for ($i=0; $i<$cantidad; $i++) :?>
                    <div>
                      	<label for="parametros[<?php echo $i; ?>][GyA]">Grasas y Aceites:</label>
                        <?php 
                        $disabled2 = '';
                        if(!$parametros[$i]["enabled"])
                        {
                          $disabled2 = 'disabled';
                          ?>
                            <input type="hidden" class="GyA" name="parametros[<?php echo $i; ?>][GyA]" id="mediciones<?php echo $i; ?>" value="">
                            <input type="hidden" class="coliformes" name="parametros[<?php echo $i; ?>][coliformes]" id="mediciones<?php echo $i; ?>" value="">
                        <?php } ?>
                  			<input type="text" class="GyA" name="parametros[<?php echo $i; ?>][GyA]" id="mediciones<?php echo $i; ?>" value="<?php isset($parametros[$i]["GyA"]) ? htmlout($parametros[$i]["GyA"]) : ""; ?>" <?php echo $disabled; ?> <?php echo $disabled2; ?>>

                  			<label for="parametros[<?php echo $i; ?>][coliformes]">Coliformes Fecales:</label>
                  			<input type="text" class="coliformes" name="parametros[<?php echo $i; ?>][coliformes]" id="mediciones<?php echo $i; ?>" value="<?php isset($parametros[$i]["coliformes"]) ? htmlout($parametros[$i]["coliformes"]) : ""; ?>" <?php echo $disabled; ?> <?php echo $disabled2; ?>>
                    </div>
                 <?php endfor; ?>

                  <fieldset id="adicionales">
                      <legend>Adicionales:</legend>
                      <?php if(!isset($_SESSION['supervisada']))
                      { ?>
                          <input type="button" id="agregar" value="Agregar nuevo adicional">
                      <?php } ?>

                      <?php for ($i=0; $i<10; $i++): ?>
                          <div>
                              <?php echo ($i+1).":"; ?>
                              <label for="adicionales[<?php echo $i; ?>][nombre]">Nombre del párametro:</label>
                              <input type="text" name="adicionales[<?php echo $i; ?>][nombre]" id="adicionales[<?php echo $i; ?>][nombre]" value="<?php isset($adicionales[$i]) ? htmlout($adicionales[$i]["nombre"]) : ""; ?>" <?php echo $disabled; ?>>

                              <label for="adicionales[<?php echo $i; ?>][unidades]">Unidades:</label>
                              <input type="text" name="adicionales[<?php echo $i; ?>][unidades]" id="adicionales[<?php echo $i; ?>][unidades]" value="<?php isset($adicionales[$i]) ? htmlout($adicionales[$i]["unidades"]) : ""; ?>" <?php echo $disabled; ?>>

                              <label for="adicionales[<?php echo $i; ?>][resultado]">Resultado:</label>
                              <input type="text" name="adicionales[<?php echo $i; ?>][resultado]" id="adicionales[<?php echo $i; ?>][resultado]" value="<?php isset($adicionales[$i]) ? htmlout($adicionales[$i]["resultado"]) : ""; ?>" <?php echo $disabled; ?>>

                              <label for="adicionales[<?php echo $i; ?>][metodo]">Metodo:</label>
                              <input type="text" name="adicionales[<?php echo $i; ?>][metodo]" id="adicionales[<?php echo $i; ?>][metodo]" value="<?php isset($adicionales[$i]) ? htmlout($adicionales[$i]["metodo"]) : ""; ?>" <?php echo $disabled; ?>>
                          </div>
                      <?php endfor; ?>

                      <?php if(isset($adicionales) AND count($adicionales)>10): ?>
                          <?php for ($i=0; $i<(count($adicionales)-10); $i++): ?>
                              <script type="text/javascript">
                                  agregarIntervalo(<?php echo $adicionales[$i+10]["nombre"]; ?>, <?php echo $adicionales[$i+10]["unidades"]; ?>, <?php echo $adicionales[$i+10]["resultado"]; ?>, <?php echo $adicionales[$i+10]["metodo"]; ?>, "<?php echo $disabled; ?>");
                              </script>
                          <?php endfor; ?>
                      <?php endif;?>
                  </fieldset>
              </fieldset>

              <div>
                  <?php if(isset($regreso) AND $regreso === 1): ?>
                      <input type="hidden" name="regreso" value="1">
                      <input type="hidden" name="ot" value="<?php htmlout($_SESSION['OT']); ?>">
                  <?php endif;?>

                  <input type="hidden" name="muestreoid" value="<?php htmlout($muestreoid); ?>">
                  <input type="hidden" name="id" value="<?php htmlout($id); ?>">
                  <input type="hidden" name="idparametro" value="<?php htmlout($idparametro); ?>">

                  <?php if(!isset($_SESSION['supervisada'])){ ?>
                      <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
                  <?php } ?>
              </div>

              <?php if($cantidad !== 1): ?>
                  <br>
                  <div>
                      <?php if(!isset($_SESSION['supervisada'])){ ?>
                        <input type="hidden" name="boton" value="<?php htmlout($boton); ?>">
                      <?php } ?>

                      <input type="hidden" name="id" value="<?php htmlout($id); ?>">
                      <input type="hidden" name="muestreoid" value="<?php htmlout($muestreoid); ?>">
                      <input type="submit" name="accion" value="Siralab">
                  </div>
              <?php endif; ?>
          </form>

            <?php if(isset($regreso) AND $regreso === 1): ?>
              <form action="http://<?php echo $_SERVER['HTTP_HOST']; ?>/reportes/nom001/generales/" method="post">
                <br>
                <div>
                  <input type="hidden" name="cantidad" value="<?php htmlout($cantidad); ?>">
                  <input type="hidden" name="regreso" value="1">
                  <input type="hidden" name="id" value="<?php htmlout($id); ?>">
                  <input type="hidden" name="coms" value="">
                  <input type="submit" name="accion" value="volver">
                </div>
            	</form>
            <?php endif;?>

            <?php if(isset($_SESSION['supervisada'])){ ?>
              <p><a href="../generales">Terminar</a></p>
            <?php }else{ ?>
              <p><a href="../generales">No guardar parametros</a></p>
            <?php } ?>
            <p><a href="..">Regresa a la búsqueda de ordenes</a></p>
        </div>  <!-- cuerpoprincipal -->
        <div id="footer">
            <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
        </div>  <!-- footer -->
      </div> <!-- contenedor -->
  </body>
</html>
<link rel="stylesheet" href="../../includes/jquery-validation-1.13.1/demo/site-demos.css">
<script type="text/javascript" src="../../includes/jquery-validation-1.13.1/dist/jquery.validate.js"></script>
<script type="text/javascript" src="../../includes/jquery-validation-1.13.1/dist/additional-methods.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    //this calculates values automatically 
    calculateSum();

    <?php if($valores === "" OR $boton === "guardar parametros"): ?>
      $("#fechareporte").on("keydown keyup", function() {
        if($("#fechareporte").val().length === 10){
          $.ajax({
            type: "POST",
            url: "limites.php",
            data: {fecha: $("#fechareporte").val()},
            cache: false,
            dataType: 'json',
            success: function(html){
              if(html !== false){
                $(".GyA").val(parseInt(html['GyA']));
                $(".coliformes").val("< "+parseInt(html['coliformes']));
                $("#ssedimentables").val("< "+html['ssedimentables']);
                $("#ssuspendidos").val("< "+html['ssuspendidos']);
                $("#dbo").val("< "+html['dbo']);
                $("#fosforo").val("< "+html['fosforo']);
                $("#arsenico").val("< "+html['arsenico']);
                $("#cadmio").val("< "+html['cadmio']);
                $("#cianuros").val("< "+html['cianuros']);
                $("#cobre").val("< "+html['cobre']);
                $("#cromo").val("< "+html['cromo']);
                $("#mercurio").val("< "+html['mercurio']);
                $("#niquel").val("< "+html['niquel']);
                $("#plomo").val("< "+html['plomo']);
                $("#zinc").val("< "+html['zinc']);
                $("#hdehelminto").val("< "+html['hdehelminto']);
              }
            }
          });
        }
      });
    <?php endif; ?>

    $(".nits").on("keydown keyup", function() {
        calculateSum();
    });

    $('#agregar').click(function(e){
        e.preventDefault();
        agregarIntervalo("", "", "");
    });

  });

  function calculateSum() {
      var sum = 0;
      $(".nits").each(function() {
          if (this.value.length != 0) {
            if(this.value.search('<') !== -1){
              value = this.value.split('<'); 
              sum += parseFloat(value[1]);
            }else if(this.value.search('±') !== -1){
              value = this.value.split('<'); 
              sum += parseFloat(value[0]);
            }
              $(this).css("background-color", "");
          }
          else if (this.value.length != 0){
              $(this).css("background-color", "red");
          }
      });

      $("input#nitrogeno").val(sum.toFixed(2));
  }

  $(document).ready(function() {

   jQuery.validator.addMethod('permitido2', function (value, element, param) {
    return /^( *|\< *\d{1,3}\.\d{1,4}|\d{1,3}\.\d{1,4} *\± *\d{1,3}\.\d{1,4})$/.test(value);
   }, 'Sólo valores decimales iniciando con < o conteniendo ±.');

   jQuery.validator.addMethod('permitido', function (value, element, param) {
    return /^(\d*\.\d{1,4}| *|\< *\d{1,3}\.\d{1,4}|\d{1,3}\.\d{1,4} *\± *\d{1,3}\.\d{1,4})$/.test(value);
   }, 'Sólo valores decimales o decimales iniciando con < o conteniendo ±.');

   jQuery.validator.addMethod('gya', function (value, element, param) {
    return /^( *|\< *12|\d*\.\d{1,3}|\d*)$/.test(value);
   }, 'Sólo "<12" o valores enteros o con 3 decimales.');

   jQuery.validator.addMethod('coliformes', function (value, element, param) {
    return /^( *|\< *3|\> *2400|\d*)$/.test(value);
   }, 'Sólo valores enteros o ">2400" o "<3"');

    $("#medsform").validate({
      rules: {
        fechareporte: {
         dateISO: true
        },
        ssedimentables: {
         permitido: true
        },
        ssuspendidos: {
         permitido: true
        },
        dbo: {
         permitido: true
        },
        nkjedahl: {
         permitido: true
        },
        nitritos: {
         permitido: true
        },
        nitratos: {
         permitido: true
        },
        nitrogeno: {
         permitido: true
        },
        fosforo: {
         permitido: true
        },
        arsenico: {
         permitido: true
        },
        cadmio: {
         permitido: true
        },
        cianuros: {
         permitido: true
        },
        cobre: {
         permitido: true
        },
        cromo: {
         permitido: true
        },
        mercurio: {
         permitido: true
        },
        niquel: {
         permitido: true
        },
        plomo: {
         permitido: true
        },
        zinc: {
         permitido: true
        },
        hdehelminto: {
         permitido: true
        },
       <?php for ($i=0; $i<$cantidad; $i++) :
        echo "
        'parametros[$i][GyA]':{
          gya: true
        },
        'parametros[$i][coliformes]':{
          coliformes: true
        }";
        echo ($i<$cantidad-1)? "," : "";
       endfor; ?>
      },
      success: "valid",
    });
  });</script>