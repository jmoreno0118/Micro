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
   <h2>Resultado de la búsqueda de las ordenes de iluminacion</h2>
   <p>formaordenesilum</p>
   <p><a href="../boton">Hacer otra búsqueda</a> 
   <p><a href="../../">Regresa a administrador</a></p>
   <?php if (isset($ordenes)) : ?>
    <table>
	 <tr><th>OT.</th><th>Cliente</th><th>planta</th><th>municipio</th><th>estado</th><th></th></tr>
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
	   </td>	   <td>
	    <form action="?" method="post">
	     <div>
	      <input type="hidden" name="id" value="<?php echo $orden['id']; ?>">
		  <input type="hidden" name="accion" value="verci">
		  <input type="submit" name="boton" value="Reconocimientos">
	     </div>
	    </form>
       </td>
	  </tr>
      <?php endforeach; ?>
    </table>
   <?php else : ?>
     <p>Lo sentimos no se encontro nunguna orden con las caracteristicas solicitadas</p>	
   <?php endif; ?>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>