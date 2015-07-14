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
<link rel="stylesheet" href="../includes/jquery-validation-1.13.1/demo/site-demos.css">
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/dist/jquery.validate.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/dist/additional-methods.js"></script>
<script type="text/javascript">
  i = 1;
  $(document).ready(function() {
  
   jQuery.validator.addMethod('entcimales', function (value, element, param) {
      return /^(\d*\.\d{1,3}|\d*)$/.test(value);
   }, 'Sólo valores enteros o de 1 a 3 decimales.');

  $("#medsform").validate({
      rules: {
        estudio: {
         required: true
        },
        tipo: {
         required: true
        },
        descripcion: {
         required: true
        },
        inventario: {
         required: true
        },
        marca: {
         required: true
        },
        modelo: {
         required: true
        },
        serie: {
         required: true
        },
        fechaalta: {
         required: true,
         dateISO: true
        },
        estado: {
         required: true
        },
        representante: {
         required: true
        },
        'rango[0]': {
         required: true,
         digits: true
        },
        'fcorreccion1[0]': {
         required: true,
         entcimales: true
        },
        'fcorreccion2[0]': {
         required: true,
         entcimales: true
        }
      },
      ignore: [],
      success: "valid"
    });

   jQuery.validator.addMethod('entcimales', function (value, element, param) {
    return /^(\d*\.\d{1,3}|\d*)$/.test(value);
   }, 'Sólo valores enteros o decimales.');

    $('#agregar').click(function(e){
        e.preventDefault();
        agregarIntervalo("", 0, 0);
    });
   
    $('#intervalos').on("click",".borrar", function(e){
        e.preventDefault();
        $(this).parent('div').remove();
    });

  });

function agregarIntervalo(rango, fcorreccion1, fcorreccion2){
  console.log(i);
      $('#intervalos').append('<div>'
        +'<label for="rango['+i+']">Rango:</label><input type="text" name="rango['+i+']" value="'+rango+'" required="required">'
        +'<label for="fcorreccion1['+i+']"> Factor de Corrección 1:</label><input type="text" name="fcorreccion1['+i+']" value="'+fcorreccion1+'" required="required">'
        +'<label for="fcorreccion2['+i+']"> Factor de Corrección 2:</label><input type="text" name="fcorreccion2['+i+']" value="'+fcorreccion2+'" required="required">'
        +'<a href="#" class="borrar">Remove</a>'
        +'</div>');

      $('input[name="rango['+i+']"]').rules("add", {
        digits: true
      });

      $('input[name="fcorreccion1['+i+']"]').rules("add", {
        entcimales: true
      });

      $('input[name="fcorreccion2['+i+']"]').rules("add", {
        entcimales: true
      });

      i += 1;
      console.log(i);
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
      $formulario = array("Estudio" => "estudio",
                          "Tipo" => "tipo",
                          "Descripción" => "descripcion",
                          "Inventario" => "inventario",
                          "Marca" => "marca",
                          "Modelo" => "modelo",
                          "Serie" => "serie",
                          "Fecha de alta" => "fechaalta",
                          "Estado" => "estado");
   ?>
    <form id="medsform" name="medsform" action="" method="post">
      <?php foreach($formulario as $label => $name): ?>
      <div>
         <label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
         <input type="text" name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>" value="<?php if(isset($equipo))htmlout($equipo[$name]); ?>">
      </div>
      <br>
      <?php endforeach?>
      
      <div>
       <label for="representante">Representante: </label>
       <select name="representante" id="representante" >
        <option value="">Seleciona representante</option>
        <?php foreach($representantes as $rep): ?>
         <option value="<?php echo $rep['id']; ?>"
            <?php if ($rep['id']==$equipo['representanteidfk'])
            {echo ' selected';}?>><?php echo $rep['nombre']; ?></option>
        <?php endforeach; ?>
       </select>
      </div>
      <br>

      <input type="button" id="agregar" value="Agregar nuevo intervalo">
      <?php if(isset($equipo)): ?>
        <?php $intervalos = json_decode($equipo['correccion'], true); 
        //var_dump($intervalos); ?>
      <?php endif; ?>
      <div id="intervalos">
        <div>
          <label for="rango[]">Rango:</label><input name="rango[0]" id="rango[0]" value="<?php if(isset($equipo)) htmlout($intervalos[0]['Rango']); ?>" required>
          <label for="fcorreccion1[]">Factor de Corrección 1:</label><input name="fcorreccion1[0]" id="fcorreccion1[0]" value="<?php htmlout((isset($equipo)) ? $intervalos[0]['Correccion1'] : "0"); ?>" required>
          <label for="fcorreccion2[]">Factor de Corrección 2:</label><input name="fcorreccion2[0]" id="fcorreccion2[0]" value="<?php htmlout((isset($equipo)) ? $intervalos[0]['Correccion2'] : "0"); ?>" required>
        </div>
        <?php if(isset($equipo)): ?>
          <?php for ($i=1; $i <= count($intervalos)-1; $i++): ?>
          <?php echo $i; ?>
            <script>
              agregarIntervalo(<?php echo $intervalos[$i]['Rango']; ?>, <?php echo $intervalos[$i]['Correccion1']; ?>, <?php echo $intervalos[$i]['Correccion2']; ?>);
            </script>
          <?php endfor; ?>
        <?php endif; ?>
      </div>
      <br>
      <div>
        <input type="hidden" name="id" value="<?php htmlout($id); ?>">
        <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
        <p><a href="../nom001">Regresa al búsqueda de ordenes</a></p>
      </div> 
    </form>
    <form action="" method="post">
        <input type="submit" name="accion" value="volver">
    </form>
    </div>  <!-- cuerpoprincipal -->
    <div id="footer">
      <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
    </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>