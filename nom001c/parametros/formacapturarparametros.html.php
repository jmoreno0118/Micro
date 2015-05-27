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
function agregarIntervalo(nombre, unidades, resultado){
  $('#adicionales').append('<div>'
    +(i+1)+': '
    +'<label for="adicionales['+i+'][nombre]">Nombre del párametro: </label>'
    +'<input type="text" name="adicionales['+i+'][nombre]" id="adicionales['+i+'][nombre]" value="'+nombre+'"> '

    +'<label for="adicionales['+i+'][unidades]">Unidades: </label>'
    +'<input type="text" name="adicionales['+i+'][unidades]" id="adicionales['+i+'][unidades]" value="'+unidades+'"> '

    +'<label for="adicionales['+i+'][resultado]">Resultado: </label>'
    +'<input type="text" name="adicionales['+i+'][resultado]" id="adicionales['+i+'][resultado]" value="'+resultado+'"> '
    +'</div>');
  i += 1;
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
          $formulario = array(
                      'fechareporte' => array(
                                        'label' => 'Fecha de Reporte(aaaa-mm-dd)',
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
                                        'tipo' => 'text',
                                        'atts' => array('class' => 'nits')
                                        ),
                      'nitritos' => array(
                                        'label' => 'Nitrógeno de Nitritos',
                                        'tipo' => 'text',
                                        'atts' => array('class' => 'nits')
                                        ),
                      'nitratos' => array(
                                        'label' => 'Nitrógeno de Nitratos',
                                        'tipo' => 'text',
                                        'atts' => array('class' => 'nits')
                                        ),
                      'nitrogeno' => array(
                                        'label' => 'Nitrógeno',
                                        'atts' => array('disabled')
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
                                        ),
          );



          $arquitectura = array("valores" => array("variables" => 'fechareporte,ssedimentables,ssuspendidos,dbo,nkjedahl,nitritos,nitratos,nitrogeno,fosforo,arsenico,cadmio,cianuros,cobre,cromo,mercurio,niquel,plomo,zinc,hdehelminto',
                                                  "tipo" => 1),
                                "parametros" => array("variables" => 'GyA,coliformes',
                                              "tipo" => 2),
                                "adicionales" => array("variables" => 'nombre,unidades,resultado',
                                              "tipo" => 2),
                                "id" => array("variables" => "id",
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
      <input type="hidden" name="url" value="<?php htmlout($_SESSION['url']); ?>">
      <input type="hidden" name="arquitectura" value='<?php echo json_encode($arquitectura); ?>'>
      <input type="hidden" name="cantidad" value="<?php htmlout($cantidad); ?>">

      <fieldset>
        <legend>Resultados de laboratorio:</legend>
      	<?php foreach($formulario as $key => $value): ?>
      	<div>
          <?php crearForma(
                        $value['label'], //Texto del abel
                        $key, //Texto a colocar en los atributos id y name
                        (isset($valores[$key])) ? $valores[$key] : '', //Valor extraido de la bd
                        (isset($value['atts'])) ? $value['atts'] : '', //Atributos extra de la etiqueta
                        $value['tipo'], //Tipo de etiqueta
                        (isset($value['option'])) ? $value['option'] : '' //Options para los select
              ); ?>
          <?php if($key==="nitrogeno"){ ?> 
            <input type="hidden" name="<?php htmlout($key); ?>" id="<?php htmlout($key); ?>">
          <?php } ?>
      	</div>
      	<?php endforeach?>
  	  
  	    <?php for ($i=0; $i<$cantidad; $i++) :?>
    	    <div>
    	    	<label for="parametros[<?php echo $i; ?>][GyA]">Grasas y Aceites:</label>
      			<input type="text" class="GyA" name="parametros[<?php echo $i; ?>][GyA]" id="mediciones<?php echo $i; ?>" value="<?php isset($parametros[$i]) ? htmlout($parametros[$i]["GyA"]) : ""; ?>">

      			<label for="parametros[<?php echo $i; ?>][coliformes]">Coliformes Fecales:</label>
      			<input type="text" class="coliformes" name="parametros[<?php echo $i; ?>][coliformes]" id="mediciones<?php echo $i; ?>" value="<?php isset($parametros[$i]) ? htmlout($parametros[$i]["coliformes"]) : ""; ?>">
    	    </div>
  	   <?php endfor; ?>
        <fieldset id="adicionales">
          <legend>Adicionales:</legend>
          <input type="button" id="agregar" value="Agregar nuevo adicional">
          <?php for ($i=0; $i<10; $i++): ?>
            <div>
            <?php echo ($i+1).":"; ?>
            <label for="adicionales[<?php echo $i; ?>][nombre]">Nombre del párametro:</label>
            <input type="text" name="adicionales[<?php echo $i; ?>][nombre]" id="adicionales[<?php echo $i; ?>][nombre]" value="<?php isset($adicionales[$i]) ? htmlout($adicionales[$i]["nombre"]) : ""; ?>">

            <label for="adicionales[<?php echo $i; ?>][unidades]">Unidades:</label>
            <input type="text" name="adicionales[<?php echo $i; ?>][unidades]" id="adicionales[<?php echo $i; ?>][unidades]" value="<?php isset($adicionales[$i]) ? htmlout($adicionales[$i]["unidades"]) : ""; ?>">

            <label for="adicionales[<?php echo $i; ?>][resultado]">Resultado:</label>
            <input type="text" name="adicionales[<?php echo $i; ?>][resultado]" id="adicionales[<?php echo $i; ?>][resultado]" value="<?php isset($adicionales[$i]) ? htmlout($adicionales[$i]["resultado"]) : ""; ?>">
            </div>
          <?php endfor; ?>
          <?php if(isset($adicionales) AND count($adicionales)>10): ?>
            <?php for ($i=0; $i<(count($adicionales)-10); $i++): ?>
              <script type="text/javascript">
              agregarIntervalo(<?php echo $adicionales[$i+10]["nombre"]; ?>, <?php echo $adicionales[$i+10]["unidades"]; ?>, <?php echo $adicionales[$i+10]["resultado"]; ?>);
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
  	    <input type="hidden" name="id" value="<?php htmlout($id); ?>">
        <input type="hidden" name="idparametro" value="<?php htmlout($idparametro); ?>">
  	    <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
  	  </div>
      <br>
      <div>
        <input type="hidden" name="boton" value="<?php htmlout($boton); ?>">
        <input type="hidden" name="id" value="<?php htmlout($id); ?>">
        <input type="submit" name="accion" value="Siralab">
      </div>
      <?php if(isset($regreso) AND $regreso === 1): ?>
        <br>
        <div>
          <input type="hidden" name="regreso" value="1">
          <input type="hidden" name="id" value="<?php htmlout($id); ?>">
          <input type="hidden" name="cantidad" value="<?php htmlout($cantidad); ?>">
          <input type="hidden" name="coms" value="">
          <input type="submit" name="accion" value="volver">
        </div>
      <?php endif;?>

    	</form>

    <p><a href="../generales">No guardar parametros</a></p>
    <p><a href="..">Regresa al búsqueda de ordenes</a></p>
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
                $(".coliformes").val("<"+parseInt(html['coliformes']));
                $("#ssedimentables").val("<"+html['ssedimentables']);
                $("#ssuspendidos").val("<"+html['ssuspendidos']);
                $("#dbo").val("<"+html['dbo']);
                $("#fosforo").val("<"+html['fosforo']);
                $("#arsenico").val("<"+html['arsenico']);
                $("#cadmio").val("<"+html['cadmio']);
                $("#cianuros").val("<"+html['cianuros']);
                $("#cobre").val("<"+html['cobre']);
                $("#cromo").val("<"+html['cromo']);
                $("#mercurio").val("<"+html['mercurio']);
                $("#niquel").val("<"+html['niquel']);
                $("#plomo").val("<"+html['plomo']);
                $("#zinc").val("<"+html['zinc']);
                $("#hdehelminto").val("<"+html['hdehelminto']);
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

   jQuery.validator.addMethod('permitido', function (value, element, param) {
    return /^(\< *\d{1,3}\.\d{1,4}|\d{1,3}\.\d{1,4} *\± *\d{1,3}\.\d{1,4})$/.test(value);
   }, 'Sólo valores decimales iniciando con < o conteniendo ±.');

   jQuery.validator.addMethod('gya', function (value, element, param) {
    return /^(\< *12|\d*\.\d{1,3}|\d*)$/.test(value);
   }, 'Sólo "<12" o valores enteros o decimales.');

   jQuery.validator.addMethod('coliformes', function (value, element, param) {
    return /^(\< *3|\> *2400|\d*)$/.test(value);
   }, 'Sólo valores enteros o ">2400" o "<3"');

    $("#medsform").validate({
      rules: {
        fechareporte: {
         required: true,
         dateISO: true
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
        nitrogeno: {
         required: true,
         permitido: true
        },
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
        },
       <?php for ($i=0; $i<$cantidad; $i++) :
        echo "
        'parametros[$i][GyA]':{
          required: true,
          gya: true
        },
        'parametros[$i][coliformes]':{
          required: true,
          coliformes: true
        }";
        echo ($i<$cantidad-1)? "," : "";
       endfor; ?>
      },
      success: "valid",
    });
  });</script>