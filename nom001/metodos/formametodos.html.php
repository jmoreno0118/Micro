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
        <h2>Resultado de la búsqueda de los metodos</h2>
        <p><a href="../..">Regresa a administrador</a></p>
        <form action="" method="post">
          <input type="submit" name="accion" value="Nueva">
        </form>

        <?php if (isset($metodos) AND count($metodos)>0): ?>
          <table>
            <tr><th>Parametro</th><th>Metodo</th></tr>
            <?php foreach ($metodos as $metodo): ?>
              <tr>
                <td><?php htmlout($metodo['parametro']); ?></td>
                <td><?php htmlout($metodo['metodo'])?></td>
                <td>
                  <form action="" method="post">
                    <div>
                      <input type="hidden" name="id" value="<?php echo $metodo['id']; ?>">
                      <input type="submit" name="accion" value="Editar">
                      <input type="submit" name="accion" value="Borrar">
                    </div>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>
        <?php else : ?>
          <p>Lo sentimos no se encontro nunguna acreditación</p>	
        <?php endif; ?>
      </div>  <!-- cuerpoprincipal -->
      <div id="footer">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
      </div>  <!-- footer -->
    </div> <!-- contenedor -->
  </body>
</html>