<!DOCTYPE html>
<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php'; ?>
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
   <h2>Administra representantes</h2>
   <p><a href="?representantenuevo">Agrega un nuevo representante</a></p>
   <ul>
    <?php foreach ($representantes as $representante): ?>
	 <li>
	  <form action="" method="post">
	   <div>
	    <?php htmlout($representante['nombre']); echo ', '; htmlout($representante['estado']); ?>
		<input type="hidden" name="id" value="<?php echo $representante['id']; ?>">
		<input type="submit" name="accion" value="Edita">
		<input type="submit" name="accion" value="Borra">
	   </div>
	  </form>
     </li>
    <?php endforeach; ?>
   </ul>
   <p><a href="..">Regresa al menú principal</a></p>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>