<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';?>
<!DOCTYPE html>
<html lang="es">
<head>
<title><?php htmlout($pestanapag); ?></title>
  <meta charset="utf-8" />
  <!--[if lt IE 9]>
   <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
<link rel="stylesheet" href="../includes/jquery-validation-1.13.1/demo/site-demos.css">
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/dist/jquery.validate.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/dist/additional-methods.js"></script>
   <link rel="stylesheet" type="text/css" href="/reportes/estilo.css" />
<script type="text/javascript">
$(document).ready(function(){

	function listaPlantas(planta, cliente){
		$("#idcliente").val($("#cliente").val());
		$.ajax({
			type: "POST",
			url: "plantas/plantas.php",
			data: {id: $("#cliente").val(), planta: planta, cliente: cliente},
			cache: false,
			success: function(html){
				$("#planta").html(html);
			}
		});
	}

	listaPlantas(<?php echo $planta.",".$clienteid; ?>);

	$("#cliente").change(function(){
		listaPlantas(<?php echo $planta.",".$clienteid; ?>);
	});

	$("#refreshPlantas").click(function(e){
		e.preventDefault();
		listaPlantas(<?php echo $planta.",".$clienteid; ?>);
	});

});
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
   	  <div>
	  	<form id="fplanta" action="plantas/index.php" method="post" target="_blank">
	  		<input id="idcliente" name="idcliente" type="hidden" value="<?php htmlout($clienteid); ?>">
	  		<input name="nplanta" type="submit" value="Nueva Planta">
	  	</form>
	  </div>
    <form id="ordenform" action="?<?php htmlout($accion); ?>" method="post">
	  <?php if (isset($mensaje)): ?>
	    <div><strong><?php htmlout($mensaje); ?></strong></div>
	  <?php endif; ?> 
	  <div>
	    <label for="ot">Num. de OT: </label>
	    <input type="text" name="ot" id="ot" value="<?php htmlout($ot); ?>">
	  </div>
	  <div>
	    <label for="representante">Representante: </label>
	   <select name="representante" id="representante" >
	    <option value="">Seleciona representante</option>
	    <?php foreach($representantes as $rep): ?>
	     <option value="<?php echo $rep['id']; ?>"
					<?php if ($rep['id']==$representanteid)
					{echo ' selected';}?>><?php echo $rep['nombre']; ?></option>
	    <?php endforeach; ?>
	   </select>
	  </div>
	  <div>
	    <label for="cliente">Num. de cliente: </label>
		<select name="cliente" id="cliente" >
	    <option value="">Seleciona cliente</option>
	    <?php foreach($clientes as $cliente): ?>
	     <option value="<?php echo $cliente['id']; ?>"
					<?php if ($cliente['id']==$clienteid)
					{echo ' selected';}?>><?php echo $cliente['nombre']; ?></option>
	    <?php endforeach; ?>
	   </select>
	  </div>
	  <div>
	    <label for="planta">Planta: </label>
		<select name="planta" id="planta" >
		   <option selected="selected" disabled value="0">--Selecciona planta--</option>
	   </select>
	   <button type="button" id="refreshPlantas">Refrescar plantas</button>
	  </div>
	  <div>
	    <label for="atencion">La orden irá dirigida a: </label>
		<input name="atencion" id="atencion" value="<?php htmlout($atencion); ?>">
	  </div>
	  <div>
	    <label for="atenciontel">Teléfono: </label>
		<input name="atenciontel" id="atenciontel" value="<?php htmlout($atenciontel); ?>">
	  </div>
	  <div>
	    <label for="atencioncorreo">Correo electrónico: </label>
		<input name="atencioncorreo" id="atencioncorreo" value="<?php htmlout($atencioncorreo); ?>">
	  </div>
      <div>
	   <label for="tipo">Tipo:</label>
	   <select name="tipo" id="tipo" >
	    <option value="">Selecciona especialidad</option>
	    <?php $num=count($especialidades);
		for($x = 0; $x < $num; $x++): ?>
	     <option value="<?php echo $especialidades[$x]; ?>"
						<?php if ($especialidades[$x]==$especialidad)
					   {echo ' selected';}?>><?php echo $especialidades[$x]; ?></option>		   
	    <?php endfor; ?>
	   </select>
	  </div>
	  <div>
	   <fieldset>
	    <legend>Higiene</legend>
		<?php for ($i=0; $i<count($higienestudios); $i++) :?>
	    <div>
	      <label for="higienestudio<?php echo $i; ?>">
		  <input type="checkbox" name="higienestudios[]" id="higienestudio<?php echo $i; ?>" 
                value="<?php htmlout($higienestudios[$i]['nombre']); ?>"
				<?php if ($higienestudios[$i]['seleccionada'])
				 {echo ' checked';}?>>		
		        <?php htmlout($higienestudios[$i]['nombre']); ?>
		   </label>
	    </div>
	  <?php endfor; ?>
	   </fieldset>
	  </div>
	  <div>
	   <fieldset>
	    <legend>Ecologia</legend>
		<?php for ($i=0; $i<count($ecologiaestudios); $i++) :?>
	    <div>
	      <label for="ecologiaestudio<?php echo $i; ?>">
		  <input type="checkbox" name="ecologiaestudios[]" id="ecologiaestudio<?php echo $i; ?>" 
                value="<?php htmlout($ecologiaestudios[$i]['nombre']); ?>"
				<?php if ($ecologiaestudios[$i]['seleccionada'])
				 {echo ' checked';}?>>		
		        <?php htmlout($ecologiaestudios[$i]['nombre']); ?>
		   </label>
	    </div>
	  <?php endfor; ?>
	   </fieldset>
	  </div>	  
	  <div>
		<input type="hidden" name="fechalta" value="<?php htmlout($fechalta); ?>">
	    <input type="hidden" name="otant" value="<?php htmlout($otant); ?>">
	    <input type="hidden" name="id" value="<?php htmlout($id); ?>">
	    <input type="submit"  value="<?php htmlout($boton); ?>">
	  </div> 
	</form>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>
<script type="text/javascript">

    $("#ordenform").validate({
      rules: {
        ot: {
         required: true
        },
        representante: {
         required: true
        },
        cliente: {
         required: true
        },
        atencion: {
         required: true
        },
        atenciontel: {
         required: true
        },
        atencioncorreo: {
         required: true
        },
        tipo: {
         required: true
        }
      },
      success: "valid",
    });

</script>