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
   <h2>Adminidtrador de clientes</h2>
   <p><a href="?clientenuevo">Agrega un nuevo cliente</a></p>
	<form action="" method="get">
	 <p>Buscar a un cliente de acuerdo con los siguientes criterios</p>
	 <div>
	  <label for="razonsocial">Por razón social:</label>
	  <input type="text" name="razonsocial" id="razonsocial">
	  <label for="municipio">Por municipio:</label>
	  <input type="text" name="municipio" id="municipio">
      <div>
	   <label for="estado">Por estado:</label>
	   <select name="estado" id="estado" >
	    <option value="">cualquier estado</option>
	    <?php $num=count($estados);
		for($x = 0; $x < $num; $x++): ?>
	     <option value="<?php echo $estados[$x]; ?>"><?php echo $estados[$x]; ?></option>
	    <?php endfor; ?>
	   </select>
	  </div>
	  <label for="rfc">Por RFC:</label>
	  <input type="text" name="rfc" id="rfc">
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