<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php'; ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Reusar puntos</title>
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
        <h2>Puntos de a reusar de la  OT. <?php htmlout($ot); ?></h2>
        <?php if (isset($puntos)): ?>
          <form  action="?" method="post">
            <table>
              <tr><th>Reusar</th><th>Número de Medición</th><th>Departamento</th><th>Area</th><th>Ubicación</th><th></th></tr>
              <?php foreach ($puntos as $punto): ?>
              <tr>
                <td><input type="checkbox" name="puntos[]" value="<?php htmlout($punto['id']); ?>"></td>
                <td><?php htmlout($punto['medicion']); ?></td>
                <input type="hidden" name="medicion[]" value="<?php htmlout($punto['medicion']); ?>">
                <td><?php htmlout($punto['departamento']); ?></td>
                <td><?php htmlout($punto['area'])?></td>
                <td><?php htmlout($punto['ubicacion'])?></td>
                <td>
                  <p><a href="?accion=ver&id=<?php echo $punto['id']; ?>">Ver punto</a></p>
                </td>
              </tr>
              <?php endforeach; ?>
            </table>
            <input type="hidden" name="recid" value="<?php htmlout($_SESSION['idrci']); ?>">
            <input type="submit" name="accion" value="Reusar Puntos">
          </form>
        <?php else : ?>
          <p>Lo sentimos no se encontró ningún punto de reuso en la orden de trabajo seleccionada</p>  
        <?php endif; ?>
        <p><a href="<?php htmlout($url) ?>">Regresar a la orden</a></p>
      </div>  <!-- cuerpoprincipal -->
      <div id="footer">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
      </div>  <!-- footer -->
    </div> <!-- contenedor -->
  </body>
</html>