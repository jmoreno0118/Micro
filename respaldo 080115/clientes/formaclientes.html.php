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
   <h2>Resultado de la búsqueda de clientes</h2>
   <?php if (isset($clientes)) : ?>
    <p><a href="?">Hacer otra búsqueda</a> 
    <p><a href="..">Regresa a administrador</a></p>
    <table>
	 <tr><th>No.</th><th>Cliente</th><th>municipio</th><th>Estado</th><th>R.F.C.</th></tr>
      <?php foreach ($clientes as $cliente): ?>
	  <tr>
	   <td><?php echo $cliente['id']; ?></td><td><?php htmlout($cliente['razonsocial'])?></td>
	   <td><?php htmlout($cliente['municipio'])?></td><td><?php htmlout($cliente['estado'])?></td>
	   <td><?php htmlout($cliente['rfc'])?></td>
	   <td>
	    <form action="?" method="post">
	     <div>
	      <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
		  <input type="submit" name="accion" value="Edita">
		  <input type="submit" name="accion" value="Borra">
	     </div>
	    </form>
       </td>
	  </tr>
      <?php endforeach; ?>
    </table>
   <?php else : ?>
     <p>Lo sentimos no se encontro nungun cliente con las caracteristicas solicitadas</p>	
   <?php endif; ?>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>