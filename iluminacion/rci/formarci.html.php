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
        <?php //var_dump($_SESSION); ?>
        <p>formarci</p>
        <p><a href="?accion=capturarci&amp;idot=<?php htmlout($idot); ?>">capturar un reconocimiento inicial</a></p>
        <?php if (isset($recsini)) : ?>

          <div style="display:block;overflow: hidden;">
            <?php if(isset($_SESSION['supervisada'])){ ?>
            <div style="float:right;">
              <form action="../pdf/index.php" method="post" target="_blank">
                <input type="hidden" name="ot" value="<?php htmlout($nombreot['ot']); ?>">
                <input type="submit" name="accion" value="informe">
              </form>
              <a href="<?php htmlout('http://'.$_SERVER['HTTP_HOST'].'/reportes/iluminacion/pdf/index.php?ot='.$nombreot['ot'].'&id='.$idot); ?>" target="_blank">Informe</a>
            </div>
            <?php } ?>

            <div style="float:right;">
              <form action="../preliminar/index.php" method="post" target="_blank">
                <input type="hidden" name="ot" value="<?php htmlout($nombreot['ot']); ?>">
                <input type="submit" name="accion" value="preliminar">
              </form>
            </div>
          </div>
          <br>

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

          <form action="" method="post">
            <input type="hidden" name="ot" value="<?php htmlout($idot); ?>">
            <?php if(isset($_SESSION['terminada']) OR isset($_SESSION['supervisada']))
            { ?>
              <br>
              <textarea style="resize: none;" rows=5 cols=50 name="comentario" id="comentario"></textarea>
              <input type="submit" name="accion" value="Comentar y Regresar Orden">
              <?php 
              if(isset($_SESSION['terminada']))
              { ?>
                <input type="submit" name="accion" value="Vo. Bo.">
              <?php
              }
            }
            else
            { ?>
              <input type="checkbox" name="terminada" value="1" <?php if(!is_null($nombreot['fechafin'])) echo "checked"; ?>>Terminada
              <input type="submit" name="accion" value="Enviar">
            <?php
            }
            ?>
          </form>
          <br>

          <?php
          if(count($comentarios) > 0 AND !isset($_SESSION['supervisada']))
          { ?>
            <b>Comentarios</b>
            <div style="height:150px;width:50%;overflow-y:auto;">
              <ul>
                <?php foreach ($comentarios as $value): ?>
                  <li><p><?php echo $value['fecha'].': "'.$value['observacion'].'" - '.$value['supervisor'] ?></p></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php } ?>
        <?php else : ?>
          <p>Lo sentimos no se encontró ningún reconocimiento inicial en la orden de trabajo seleccionada</p>  
        <?php endif; ?>
        <p><a href="..">Regresa a búsqueda de ordenes</a></p>
        </div>  <!-- cuerpoprincipal -->
      <div id="footer">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
      </div>  <!-- footer -->
    </div> <!-- contenedor -->
  </body>
</html>