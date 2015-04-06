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
    <form action="?<?php htmlout($accion); ?>" method="post">
	  <div>
	    <label for="ot">Num. de OT: </label>
	    <input type="text" name="ot" id="ot" value="<?php htmlout($ot); ?>">
	  </div>
	  <div>
	    <label for="cliente">Num. de cliente: </label>
	    <input type="text" name="cliente" id="cliente" value="<?php htmlout($cliente); ?>">
	  </div>
	  <div>
	    <label for="representante">Representante: </label>
	   <select name="representante" id="representante" >
	    <option value="">Seleciona representante</option>
	    <?php foreach($representantes as $rep): ?>
	     <option value="<?php echo $rep['id']; ?>"
					<?php if ($rep['id']==$representante)
					{echo ' selected';}?>><?php echo $rep['nombre']; ?></option>
	    <?php endforeach; ?>
	   </select>
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