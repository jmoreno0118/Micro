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
        <h2>Máximos</h2>
        <form action="" method="post">
          <div>
            <input type="submit" name="accion" value="capturar">
          </div>
        </form>
        <?php if (isset($maximos)) : ?>
          <table>
            <tr><th>Descarga en</th><th>Uso</th></tr>
            <?php foreach ($maximos as $maximo): ?>
              <tr>
                <td><?php htmlout($maximo['descargaen']); ?></td>
                <td><?php htmlout($maximo['uso']); ?></td>
                <td>
                  <form action="" method="post">
                    <div>
                      <input type="hidden" name="id" value="<?php echo $maximo['id']; ?>">
                      <input type="submit" name="accion" value="editar">
                      <input type="submit" name="accion" value="borrar">
                    </div>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>
        <?php else : ?>
          <p>Lo sentimos no se encontró ningún reconocimiento inicial en la orden de trabajo seleccionada</p>  
        <?php endif; ?>
        <p><a href="../../">Regresa a administrador</a></p>
      </div>  <!-- cuerpoprincipal -->
      <div id="footer">
      <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
      </div>  <!-- footer -->
    </div> <!-- contenedor -->
  </body>
</html>