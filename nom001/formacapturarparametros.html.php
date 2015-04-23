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
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery-1.11.1.js"></script>
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
   <?php $formulario = array("Fecha de Reporte(aaaa-mm-dd)" => "fechareporte",
                          "Solidos sedimentables" => "ssedimentables",
             							"Solidos suspendidos" => "ssuspendidos",
                          "DBO" => "dbo",
                          "Nitrógeno Kjeldahl" => "nkjedahl",
                          "Nitritos" => "nitritos",
                          "Nitratos" => "nitratos",
                          "Nitrógeno" => "nitrogeno",
                          "Fosforo" => "fosforo",
                          "Arsenico" => "arsenico",
                          "Cadmio" => "cadmio",
                          "Cianuros" => "cianuros",
                          "Cobre" => "cobre",
                          "Cromo" => "cromo",
                          "Mercurio" => "mercurio",
                          "Niquel" => "niquel",
                          "Plomo" => "plomo",
             							"Zinc" => "zinc",
             							"Huevos de Helminto" => "hdehelminto"); ?>
    <form id="medsform" name="medsform"  action="?<?php htmlout($accion); ?>" method="post">
      <fieldset>
        <legend>Resultados de laboratorio:</legend>
      	<?php foreach($formulario as $label => $name): ?>
      	<div>
      		<label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
  	    	<input type="text" <?php if($name==="nitritos" || $name==="nitratos" || $name==="nkjedahl"){?>class="nits"<?php } ?> name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>" value="<?php if(isset($valores)){htmlout($valores[$name]);} ?>" <?php if($name==="nitrogeno") echo "disabled"; ?>>
          <?php if($name==="nitrogeno"){ ?> 
            <input type="hidden" name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>">
          <?php } ?>
      	</div>
      	<?php endforeach?>
  	  
  	    <?php $cantidad = ($cantidad === 1)? $cantidad : $cantidad-1;
        for ($i=0; $i<$cantidad; $i++) :?>
  	    <div>
  	    	<label for="parametros[<?php echo $i; ?>][GyA]">Grasas y Aceites:</label>
    			<input type="text" name="parametros[<?php echo $i; ?>][GyA]" id="mediciones<?php echo $i; ?>" value="<?php isset($parametros[$i]) ? htmlout($parametros[$i]["GyA"]) : ""; ?>">

    			<label for="parametros[<?php echo $i; ?>][coliformes]">Coliformes Fecales:</label>
    			<input type="text" name="parametros[<?php echo $i; ?>][coliformes]" id="mediciones<?php echo $i; ?>" value="<?php isset($parametros[$i]) ? htmlout($parametros[$i]["coliformes"]) : ""; ?>">
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
       <input type="hidden" name="cantidad" value="<?php htmlout($cantidad); ?>">
       <input type="hidden" name="ot" value="<?php htmlout($_SESSION['OT']); ?>">
       <input type="submit" name="accion" value="volvercoms">
      <?php endif;?>
	    <input type="hidden" name="id" value="<?php htmlout($id); ?>">
      <input type="hidden" name="idparametro" value="<?php htmlout($idparametro); ?>">
	    <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
	    <p><a href="../nom001">Regresa al búsqueda de ordenes</a></p>
	  </div> 
	</form>
  <form action="" method="post">
      <input type="hidden" name="ot" value="<?php htmlout($_SESSION['OT']); ?>">
      <input type="submit" name="accion" value="no guardar parametros">
  </form>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>
<link rel="stylesheet" href="../includes/jquery-validation-1.13.1/demo/site-demos.css">
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/dist/jquery.validate.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/dist/additional-methods.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
      //this calculates values automatically 
      calculateSum();

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
      //iterate through each textboxes and add the values
      $(".nits").each(function() {
          //add only if the value is number
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