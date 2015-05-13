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
   <p>formabuscaordenesvib</p>
	<form action="?" method="get">
	 <p>Buscar a una orden por:</p>
	 <div>
	  <label for="ot">Num de OT:</label>
	  <input type="text" name="ot" id="ot">
	 </div>
	 <div>
	  <input type="checkbox" name="otsproceso" id="otsproceso" checked>
	  <label for="otsproceso">Ordenes en proceso</label>
	 </div>
	 <div>
	   <input type="hidden" name="accion" value="buscar">
	   <input type="submit" value="buscar">
	 </div>
	</form>
   <?php if (isset($ordenes)) : ?>
    <table>
	 <caption><?php htmlout($tablatitulo); ?></caption>
	 <tr><th>OT.</th><th>Cliente</th><th>planta</th><th>municipio</th><th>estado</th><th></th><th></th><th></th></tr>
      <?php foreach ($ordenes as $orden): ?>
	  <tr>
	   <td><?php htmlout($orden['ot']); ?></td>
	   <td><?php htmlout($orden['razonsocial'])?></td>
	   <td><?php htmlout($orden['planta'])?></td>
	   <td><?php htmlout($orden['municipio'])?></td>
	   <td><?php htmlout($orden['estado'])?></td>
	   <td>
	    <form action="?" method="post">
	     <div>
	      <input type="hidden" name="id" value="<?php echo $orden['id']; ?>">
		  <input type="submit" name="accion" value="Ver OT">
	     </div>
	    </form>	
	   </td>
	   <td>
	    <form action="?" method="post">
	     <div>
	      <input type="hidden" name="id" value="<?php echo $orden['id']; ?>">
		  <input type="submit" name="accion" value="Rec.Ini.">
	     </div>
	    </form>
       </td>
	   <td>
	    <form action="?" method="post">
	     <div>
	      <input type="hidden" name="id" value="<?php echo $orden['id']; ?>">
		  <input type="submit" name="accion" value="Planos"> 
	     </div>
	    </form>
       </td>
	  </tr>
      <?php endforeach; ?>
    </table>
   <?php else : ?>
     <p><?php htmlout($mensaje); ?></p>	
   <?php endif; ?>
   <p><a href="..">Regresa a administrador</a></p>
   </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>