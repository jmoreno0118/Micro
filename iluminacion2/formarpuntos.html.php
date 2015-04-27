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
   <h2>Puntos de Iluminación OT. <?php htmlout($ot); ?></h2>
   <p>formarpuntos</p>
   <p><a href="?nuevopunto&amp;idrci=<?php htmlout($idrci) ?>">Agregar un nuevo punto</a></p>
<!--   <form action="?" method="post">
    <div>
     <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
     <input type="submit" name="accion" value="nuevopunto">
     <input type="submit" name="accion" value="volverci">
    </div>
   </form> -->
   <?php if (isset($puntos)) : ?>
    <table>
   <tr><th>Departamento</th><th>Area</th><th>Identificación</th><th></th></tr>
      <?php foreach ($puntos as $punto): ?>
    <tr>
     <td><?php htmlout($punto['departamento']); ?></td>
     <td><?php htmlout($punto['area'])?></td>
     <td><?php htmlout($punto['identificacion'])?></td>
     <td>
      <form action="?" method="post" class="enlinea">
       <div>
        <input type="hidden" name="id" value="<?php echo $punto['id']; ?>">
		<input type="hidden" name="accion" value="editarpunto">
        <input type="submit" name="boton" value="Editar">
       </div>
      </form>
      <form action="?" method="post" class="enlinea">
       <div>
        <input type="hidden" name="id" value="<?php echo $punto['id']; ?>">
        <input type="hidden" name="accion" value="borrarpunto">
        <input type="submit" name="boton" value="Borrar">
       </div>
      </form>
       </td>
    </tr>
      <?php endforeach; ?>
    </table>
   <?php else : ?>
     <p>Lo sentimos no se encontró ningún punto de iluminación en la orden de trabajo seleccionada</p>  
   <?php endif; ?>
  <p><a href="?volverci&amp;idot=<?php htmlout($idot); ?>">regresar a la orden</a></p> 
  <p><a href="../iluminacion">Regresa a búsqueda de ordenes</a></p> 
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>