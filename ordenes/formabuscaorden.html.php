<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Captura</title>
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
   <h2>Administrador de ordenes</h2>
   <p><a href="?ordenueva">Agrega una orden nueva</a></p>
	<form action="?" method="get">
	 <p>Buscar a una orden de acuerdo con los siguientes criterios</p>
	 <div>
	  <label for="ot">Num de OT:</label>
	  <input type="text" name="ot" id="ot">
	 </div>
	 <div>
	  <label for="fechaini">Por fecha de inicio:</label>
	  <input type="date" name="fechaini" id="fechaini">
	  <label for="fechafin"> fecha fin:
	  <input type="date" name="fechafin" id="fechafin"> (aaaa-mm-dd)</label>
	 </div>
     <div>
	  <label for="tipo">tipo:</label>
	  <select name="tipo" id="tipo" >
	    <option value="">cualquier especialidad</option>
	    <?php $num=count($especialidades);
		for($x = 0; $x < $num; $x++): ?>
	     <option value="<?php echo $especialidades[$x]; ?>"><?php echo $especialidades[$x]; ?></option>
	    <?php endfor; ?>
	  </select>
	 </div>
     <div>
	  <label for="representante">Representante:</label>
	  <select name="representante" id="representante" >
	    <option value="">cualquier representante</option>
	    <?php foreach($representantes as $representante): ?>
	     <option value="<?php htmlout($representante['id']); ?>"><?php htmlout($representante['nombre']); ?></option>
	    <?php endforeach; ?>
	  </select>
	 </div>
	 <div>
	   <input type="hidden" name="accion" value="buscar">
	   <input type="submit" value="buscar">
	 </div>
	</form>
   <p><a href="..">Regresa a administrador</a></p>
   </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>