<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Captura</title>
  <meta charset="utf-8" />
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
   <p><a href="?capturar">capturar nueva calibracion</a></p>
	 <form action="" method="get">
     <p>Buscar a una calibracion por:</p>
     <div>
      <label for="numero">Numero de inventario</label>
      <input type="text" name="numero" id="numero">
     </div>
     <div>
      <label for="tipo">Tipo</label>
      <input type="text" name="tipo" id="tipo">
     </div>
     <div>
      <label for="lab">Laboratorio de acreditación</label>
      <input type="text" name="lab" id="lab">
     </div>
     <div>
       <input type="hidden" name="accion" value="buscar">
       <input type="submit" value="buscar">
     </div>
  </form>
  <?php if (isset($calibraciones)) : ?>
    <table>
      <caption><?php htmlout($tablatitulo); ?></caption>
      <tr><th>Folio</th><th>No. Inventario</th><th>Laboratorio de acreditación</th><th>Fecha de Calibración</th><th></th></tr>
      <?php foreach ($calibraciones as $calibracion): ?>
        <tr>
        <td><?php htmlout($calibracion['id']); ?></td>
        <td><?php htmlout(htmldecode($calibracion['Numero_Inventario']))?></td>
        <td><?php htmlout(htmldecode($calibracion['laboratorioacreditacion']))?></td>
        <td><?php htmlout(htmldecode($calibracion['fechacalibracion']))?></td>
        <td>
        <form action="?" method="post">
         <div>
          <input type="hidden" name="id" value="<?php echo $calibracion['id']; ?>">
          <input type="hidden" name="numero" value="<?php echo $calibracion['Numero_Inventario']; ?>">
          <input type="submit" name="accion" value="ver">
          <input type="submit" name="accion" value="editar">
         </div>
        </form>
         </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else : ?>
    <p>Lo sentimos no se encontro nunguna orden con las caracteristicas solicitadas</p>  
  <?php endif; ?>
  <p><a href="..">Regresa a administrador</a></p>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>