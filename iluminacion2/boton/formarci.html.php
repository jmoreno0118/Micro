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
   <h2>Reconocimientos iniciales de la OT. <?php htmlout($ot); ?></h2>
   <p>formarci</p>
   <form action="?" method="post">
     <div>
	   <input type="hidden" name="accion" value="capturarci">
	   <input type="hidden" name="idot" value=<?php htmlout($idot); ?>">
	   <input type="submit" value="Captura un rec. ini.">
	 </div>
	</form>
<!--   <p><a href="?accion=capturarci&amp;idot=<?php htmlout($idot); ?>">capturar un reconocimiento inicial</a></p> -->
<!--   <form action="?" method="post">
    <div>
     <input type="hidden" name="id" value="<?php htmlout($id); ?>">
     <input type="submit" name="accion" value="capturarci">
    </div>
   </form> -->
   <?php if (isset($recsini)) : ?>
    <table>
   <tr><th>Departamento</th><th>Area</th><th>Proceso</th><th></th></tr>
      <?php foreach ($recsini as $recini): ?>
    <tr>
     <td><?php htmlout($recini['departamento']); ?></td>
     <td><?php htmlout($recini['area'])?></td>
     <td><?php htmlout($recini['descriproceso'])?></td>
     <td>  
       <form action="?" method="post" class="enlinea">
        <div>
		  <input type="hidden" name="id" value="<?php echo $recini['id']; ?>">
		  <input type="hidden" name="idot" value="<?php echo $idot; ?>">
		  <input type="hidden" name="accion" value="editarci">
          <input type="submit" name="boton" value="Editar">
	   </div>
	  </form>
       <form action="?" method="post"class="enlinea">
	    <div>
          <input type="hidden" name="id" value="<?php echo $recini['id']; ?>">
	 	  <input type="hidden" name="idot" value="<?php echo $idot; ?>">
		  <input type="hidden" name="accion" value="borrarci">
          <input type="submit" name="boton" value="Borrar">
	   </div>
	  </form>
      <form action="?" method="post" class="enlinea">
	   <div>
	    <input type="hidden" name="id" value="<?php echo $recini['id']; ?>">
		<input type="hidden" name="idot" value="<?php echo $idot; ?>">
        <input type="submit" name="accion" value="puntos">
	   </div>
	  </form>
       </td>
    </tr>
      <?php endforeach; ?>
    </table>
   <?php else : ?>
     <p>Lo sentimos no se encontró ningún reconocimiento inicial en la orden de trabajo seleccionada</p>  
   <?php endif; ?>
   <p><a href="../boton">Regresa a búsqueda de ordenes</a></p>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>