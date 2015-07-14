<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Confirma</title>
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
        <div id="confirma">
        <h2>Confirmación de borrado de la medición</h2>
          <fieldset>
            <legend>Borrar</legend>
            <form action="" method="post">
              Estás seguro de que deseas borrar la medición número: <?php htmlout($nummedicion); ?>, 
              con fecha: <?php htmlout($fechamuestreo); ?>, ubicado en el lugar: <?php htmlout($lugarmuestreo);?>,
              en el proceso: <?php htmlout($descriproceso);?> y con identificación: <?php htmlout($identificacion);?>.
              A demás de sus párametros de laboratorio y muestras compuestas.
              <p>
                <input type="hidden" name="id" value="<?php htmlout($id); ?>">
                <input type="hidden" name="ot" value="<?php htmlout($_SESSION['OT']); ?>">
                <input type="submit" name="accion" value="Cancelar borrar medicion">
                <input type="submit" name="accion" value="Continuar borrando medicion">
              </p> 
            </form> 
          </fieldset> 
        </div> <!-- confirma -->
      </div>  <!-- cuerpoprincipal -->
      <div id="footer">
      <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
      </div>  <!-- footer -->
    </div> <!-- contenedor -->
  </body>
</html>
