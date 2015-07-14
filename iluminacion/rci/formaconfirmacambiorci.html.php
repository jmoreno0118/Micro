<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Confirma cambio</title>
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
          <h2>Confirmación de cambio de infuencia en el reconocimiento inicial</h2>
          <p>formaconfirmacambiorci</p>
          <fieldset>
            <legend>Cambios</legend>
            <form action="?" method="post">
              Estas seguro de que deseas cambiar la influencia del reconocimiento inicial?, Ya que de continuar se perderá la informacion de la segunda y tercer lectura de cada punto que este asociado a este reconocimiento.
              <p>
                <?php for ($x=0; $x<count($campos); $x++) : ?>
                  <input type="hidden" name="<?php htmlout($campos[$x]); ?>" value="<?php htmlout($contenidos[$x]); ?>">
                <?php endfor; ?>
                <?php for ($i=0; $i<count($puestos); $i++) :?>
                  <input type="hidden" name="puestos[<?php echo $i; ?>][puesto]" value="<?php htmlout($puestos[$i]); ?>">
                  <input type="hidden" name="puestos[<?php echo $i; ?>][numtrabajadores]" value="<?php htmlout($numtrabajadores[$i]); ?>">
                  <input type="hidden" name="puestos[<?php echo $i; ?>][actividades]" value="<?php htmlout($actividades[$i]); ?>">
                <?php endfor; ?>	  
                <input type="submit" name="accion" value="Continua cambio">
                <input type="submit" name="accion" value="Cancela cambio">
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
