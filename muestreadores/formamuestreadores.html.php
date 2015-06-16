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
   <h2>Muestreadores/Signatarios</h2>
   <form method="post">
    <input type="submit" name="accion" value="Capturar">
   </form>
   <?php if($muestreadores): ?>
    <p><a href="..">Regresa a administrador</a></p>
    <table>
      <tr><th>Nombre</th><th>Apellido Paterno</th><th>Apellido Materno</th><th>Signatario</th><th></th></tr>
      <?php foreach ($muestreadores as $muestreador): ?>
        <tr>
          <td><?php htmlout($muestreador['nombre']); ?></td>
          <td><?php htmlout($muestreador['ap'])?></td>
          <td><?php htmlout($muestreador['am'])?></td>
          <td><?php htmlout( ($muestreador['signatario'] === 1)? 'Sí' : 'No' )?></td>
          <td>
            <form action="" method="post">
            <div>
            <input type="hidden" name="id" value="<?php echo $muestreador['id']; ?>">
            <input type="submit" name="accion" value="Editar">
            <input type="submit" name="accion" value="Borrar">
            </div>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
   <?php else : ?>
     <p>Lo sentimos no se encontro ningún muestreador</p>	
   <?php endif; ?>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>