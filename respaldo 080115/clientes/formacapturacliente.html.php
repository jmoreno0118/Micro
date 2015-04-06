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
	    <label for="razonsocial">Razón social: </label>
	    <input type="text" name="razonsocial" id="razonsocial" value="<?php htmlout($razonsocial); ?>">
	  </div>
	  <div>
	    <label for="planta">Planta: </label>
	    <input type="text" name="planta" id="planta" value="<?php htmlout($planta); ?>">
	  </div>
	  <div>
	    <label for="calle">Calle: </label>
	    <input type="text" name="calle" id="calle" value="<?php htmlout($calle); ?>">
	  </div>
	  <div>
	    <label for="colonia">Colonia: </label>
	    <input type="text" name="colonia" id="colonia" value="<?php htmlout($colonia); ?>">
	  </div>
	  <div>
	    <label for="municipio">Municipio: </label>
	    <input type="text" name="municipio" id="municipio" value="<?php htmlout($municipio); ?>">
	  </div>
      <div>
	   <label for="estado">Por estado:</label>
	   <select name="estado" id="estado" >
	    <option value="">cualquier estado</option>
	    <?php $num=count($estados);
		for($x = 0; $x < $num; $x++): ?>
	     <option value="<?php echo $estados[$x]; ?>"
						<?php if ($estados[$x]==$estado)
					   {echo ' selected';}?>><?php echo $estados[$x]; ?></option>
	    <?php endfor; ?>
	   </select>
	  </div>
	  <div>
	    <label for="cp">C.P.: </label>
	    <input type="text" name="cp" id="cp" value="<?php htmlout($cp); ?>">
	  </div>
	  <div>
	    <label for="atencion">Atención: </label>
	    <input type="text" name="atencion" id="atencion" value="<?php htmlout($atencion); ?>">
	  </div>
	  <div>
	    <label for="rfc">R.F.C.: </label>
	    <input type="text" name="rfc" id="rfc" value="<?php htmlout($rfc); ?>">
	  </div>
	  <div>
	    <label for="tel">Tels.: </label>
	    <input type="text" name="tel" id="tel" value="<?php htmlout($tel); ?>">
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