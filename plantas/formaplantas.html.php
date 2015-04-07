<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php'; ?>
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
   <h2>Resultado de la búsqueda de las ordenes de trabajo</h2>
   <?php if (isset($plantas)) : ?>
    <p><a href="..">Regresa a administrador</a></p>
    <form action="" method="post">
     <input type="submit" name="accion" value="Nueva">
    </form>
    <table>
	   <tr>
      <th>Razón Social</th><th>Planta</th><th>Ciudad</th><th>Estado</th><th></th>
     </tr>
      <?php foreach ($plantas as $planta): ?>
  	  <tr>
  	   <td><?php htmlout($planta['razonsocial']); ?></td>
  	   <td><?php htmlout($planta['planta'])?></td>
  	   <td><?php htmlout($planta['ciudad'])?></td>
  	   <td><?php htmlout($planta['estado'])?></td>
  	   <td>
  	    <form action="" method="post">
  	     <div>
  	      <input type="hidden" name="id" value="<?php echo $planta['id']; ?>">
    		  <input type="submit" name="accion" value="Editar">
    		  <input type="submit" name="accion" value="Borrar">
  	     </div>
  	    </form>
       </td>
  	  </tr>
      <?php endforeach; ?>
    </table>
   <?php else : ?>
     <p>Lo sentimos no se encontro nunguna planta</p>	
   <?php endif; ?>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
</div> <!-- contenedor -->
</body>
</html>