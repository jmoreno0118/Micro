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
   <h2>Resultado de la búsqueda de las ordenes de norma 001</h2>
   <p><a href="">Hacer otra búsqueda</a> 
   <p><a href="..">Regresa a administrador</a></p>
   <?php if (isset($ordenes)) : ?>
    <table>
	 <tr><th>OT.</th><th>Cliente</th><th>Ciudad</th><th>Estado</th><th></th></tr>
      <?php foreach ($ordenes as $orden): ?>
	  <tr>
	   <td><?php htmlout($orden['ot']); ?></td>
	   <td><?php htmlout(htmldecode($orden['razonsocial']))?></td>
	   <td><?php htmlout(htmldecode($orden['ciudad']))?></td>
	   <td><?php htmlout(htmldecode($orden['estado']))?></td>
	   <td>
	    <form action="?" method="post">
	     <div>
	      <input type="hidden" name="ot" value="<?php echo $orden['id']; ?>">
		    <input type="submit" name="accion" value="ver mediciones">
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