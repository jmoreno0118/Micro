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
   <h2>Equipos</h2>
   <div style="float:left;">
    <form action="?" method="post">
     <input type="hidden" name="id" value="<?php htmlout($ot); ?>">
     <input type="submit" name="accion" value="nuevo">
    </form>
   </div>
   <?php if (isset($luminometros)) : ?>
    <table>
      <tr><th>Tipo</th><th>Inventario</th><th>Marca</th><th>Modelo</th><th>Serie</th><th></th></tr>
      <?php foreach ($luminometros as $luminometro): ?>
      <tr>
        <td><?php htmlout($luminometro['tipo']); ?></td>
       <td><?php htmlout($luminometro['inventario']); ?></td>
       <td><?php htmlout($luminometro['marca']); ?></td>
       <td><?php htmlout($luminometro['modelo'])?></td>
       <td><?php htmlout($luminometro['serie'])?></td>
       <td>
         <div>
          <form action="" method="post" style="float:left;">
            <input type="hidden" name="id" value="<?php echo $luminometro['id']; ?>">
            <input type="submit" name="accion" value="editar">
            <input type="submit" name="accion" value="borrar">
          </form>
         </div>
       </td>
      </tr>
      <?php endforeach; ?>
    </table>
   <?php else : ?>
     <p>Lo sentimos no se encontró ningún reconocimiento inicial en la orden de trabajo seleccionada</p>  
   <?php endif; ?>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>