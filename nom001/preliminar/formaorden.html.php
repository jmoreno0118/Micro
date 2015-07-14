<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Orden de Trabajo <?php htmlout($ot); ?></title>
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
<?php 
  /*var_dump($orden);
  echo "<br>";
  var_dump($parametros);
  echo "<br>";
  var_dump($maximos);*/
?>

    <h2>Orden de Trabajo <?php htmlout($ot); ?></h2>
    <?php  for ($i=0; $i < count($orden); $i++) { ?>
      <table style="width:50%">
        <tr>
          <th>Número de medición</th>
          <th><?php htmlout($orden[$i]['numedicion']); ?></th>    
        </tr>
        <tr>
          <td>Cuerpo receptor y Uso de agua</td>
          <td><?php htmlout($maximos[$orden[$i]['muestreoaguaid']]['descargaen']." - ".$maximos[$orden[$i]['muestreoaguaid']]['uso']); ?></td>    
        </tr>
      </table>
      <br>
      <?php //var_dump($parametros[$orden[$i]['muestreoaguaid']]); ?>
      <table style="width:50%">
        <?php if(isset($parametros[$orden[$i]['muestreoaguaid']])): ?>
          <tr>
            <th style="width:40%;">Párametro</th>
            <th>Resultado</th>
            <th>Norma</th>
          </tr>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['hdehelminto'] !== ''): ?>
            <tr>
              <td>Huevos de Helminto</td>
              <td style="<?php if(strpos($parametros[$orden[$i]['muestreoaguaid']]['hdehelminto'], '<') == false){if($parametros[$orden[$i]['muestreoaguaid']]['hdehelminto'] > doubleval($maximos[$orden[$i]['muestreoaguaid']]['hdehelminto'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['hdehelminto']); ?></td>
              <td><?php htmlout($maximos[$orden[$i]['muestreoaguaid']]['hdehelminto']); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['ssedimentables'] !== ''): ?>
            <tr>
              <td>Solidos sedimentables</td>
              <td style="<?php if(strpos($parametros[$orden[$i]['muestreoaguaid']]['ssedimentables'], '<') == false){if(doubleval($parametros[$orden[$i]['muestreoaguaid']]['ssedimentables']) > doubleval($maximos[$orden[$i]['muestreoaguaid']]['ssedimentables'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['ssedimentables']); ?></td>
              <td><?php htmlout($maximos[$orden[$i]['muestreoaguaid']]['ssedimentables']); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['ssuspendidos'] !== ''): ?>
            <tr>
              <td>Solidos suspendidos</td>
              <td style="<?php if(strpos($parametros[$orden[$i]['muestreoaguaid']]['ssuspendidos'], '<') == false){if(doubleval($parametros[$orden[$i]['muestreoaguaid']]['ssuspendidos']) > doubleval($maximos[$orden[$i]['muestreoaguaid']]['ssuspendidos'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['ssuspendidos']); ?></td>
              <td><?php htmlout($maximos[$orden[$i]['muestreoaguaid']]['ssuspendidos']); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['dbo'] !== ''): ?>
            <tr>
              <td>DBO</td>
              <td style="<?php if(strpos($parametros[$orden[$i]['muestreoaguaid']]['dbo'], '<') == false){if(doubleval($parametros[$orden[$i]['muestreoaguaid']]['dbo']) > doubleval($maximos[$orden[$i]['muestreoaguaid']]['dbo'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['dbo']); ?></td>
              <td><?php htmlout($maximos[$orden[$i]['muestreoaguaid']]['dbo']); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['nkjedahl'] !== ''): ?>
            <tr>
              <td>Nitrógeno Kjeldahl</td>
              <td><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['nkjedahl']); ?></td>
              <td><?php htmlout('No Aplica'); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['nitritos'] !== ''): ?>
            <tr>
              <td>Nitrógeno Nitritos</td>
              <td><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['nitritos']); ?></td>
              <td><?php htmlout('No Aplica'); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['nitratos'] !== ''): ?>
            <tr>
              <td>Nitrógeno Nitratos</td>
              <td><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['nitratos']); ?></td>
              <td><?php htmlout('No Aplica'); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['nitrogeno'] !== '' AND $parametros[$orden[$i]['muestreoaguaid']]['nitrogeno'] !== '0.00'): ?>
            <tr>
              <td>Nitrógeno</td>
              <td><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['nitrogeno']); ?></td>
              <td><?php htmlout('Calculado'); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['fosforo'] !== ''): ?>
            <tr>
              <td>Fosforo</td>
              <td style="<?php if(strpos($parametros[$orden[$i]['muestreoaguaid']]['fosforo'], '<') == false){if(doubleval($parametros[$orden[$i]['muestreoaguaid']]['fosforo']) > doubleval($maximos[$orden[$i]['muestreoaguaid']]['fosforo'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['fosforo']); ?></td>
              <td><?php htmlout($maximos[$orden[$i]['muestreoaguaid']]['fosforo']); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['arsenico'] !== ''): ?>
            <tr>
              <td>Arsenico</td>
              <td style="<?php if(strpos($parametros[$orden[$i]['muestreoaguaid']]['arsenico'], '<') == false){if(doubleval($parametros[$orden[$i]['muestreoaguaid']]['arsenico']) > doubleval($maximos[$orden[$i]['muestreoaguaid']]['arsenico'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['arsenico']); ?></td>
              <td><?php htmlout($maximos[$orden[$i]['muestreoaguaid']]['arsenico']); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['cadmio'] !== ''): ?>
            <tr>
              <td>Cadmio</td>
              <td style="<?php if(strpos($parametros[$orden[$i]['muestreoaguaid']]['cadmio'], '<') == false){if(doubleval($parametros[$orden[$i]['muestreoaguaid']]['cadmio']) > doubleval($maximos[$orden[$i]['muestreoaguaid']]['cadmio'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['cadmio']); ?></td>
              <td><?php htmlout($maximos[$orden[$i]['muestreoaguaid']]['cadmio']); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['cianuros'] !== ''): ?>
            <tr>
              <td>Cianuros</td>
              <td style="<?php if(strpos($parametros[$orden[$i]['muestreoaguaid']]['cianuros'], '<') == false){if(doubleval($parametros[$orden[$i]['muestreoaguaid']]['cianuros']) > doubleval($maximos[$orden[$i]['muestreoaguaid']]['nitrogeno'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['cianuros']); ?></td>
              <td><?php htmlout($maximos[$orden[$i]['muestreoaguaid']]['cianuros']); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['cobre'] !== ''): ?>
            <tr>
              <td>Cobre</td>
              <td style="<?php if(strpos($parametros[$orden[$i]['muestreoaguaid']]['cobre'], '<') == false){if(doubleval($parametros[$orden[$i]['muestreoaguaid']]['cobre']) > doubleval($maximos[$orden[$i]['muestreoaguaid']]['cobre'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['cobre']); ?></td>
              <td><?php htmlout($maximos[$orden[$i]['muestreoaguaid']]['cobre']); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['cromo'] !== ''): ?>
            <tr>
              <td>Cromo</td>
              <td style="<?php if(strpos($parametros[$orden[$i]['muestreoaguaid']]['cromo'], '<') == false){if(doubleval($parametros[$orden[$i]['muestreoaguaid']]['cromo']) > doubleval($maximos[$orden[$i]['muestreoaguaid']]['cromo'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['cromo']); ?></td>
              <td><?php htmlout($maximos[$orden[$i]['muestreoaguaid']]['cromo']); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['mercurio'] !== ''): ?>
            <tr>
              <td>Mercurio</td>
              <td style="<?php if(strpos($parametros[$orden[$i]['muestreoaguaid']]['mercurio'], '<') == false){if(doubleval($parametros[$orden[$i]['muestreoaguaid']]['mercurio']) > doubleval($maximos[$orden[$i]['muestreoaguaid']]['mercurio'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['mercurio']); ?></td>
              <td><?php htmlout($maximos[$orden[$i]['muestreoaguaid']]['mercurio']); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['niquel'] !== ''): ?>
            <tr>
              <td>Niquel</td>
              <td style="<?php if(strpos($parametros[$orden[$i]['muestreoaguaid']]['niquel'], '<') == false){if(doubleval($parametros[$orden[$i]['muestreoaguaid']]['niquel']) > doubleval($maximos[$orden[$i]['muestreoaguaid']]['niquel'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['niquel']); ?></td>
              <td><?php htmlout($maximos[$orden[$i]['muestreoaguaid']]['niquel']); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['plomo'] !== ''): ?>
            <tr>
              <td>Plomo</td>
              <td style="<?php if(strpos($parametros[$orden[$i]['muestreoaguaid']]['plomo'], '<') == false){if(doubleval($parametros[$orden[$i]['muestreoaguaid']]['plomo']) > doubleval($maximos[$orden[$i]['muestreoaguaid']]['plomo'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['plomo']); ?></td>
              <td><?php htmlout($maximos[$orden[$i]['muestreoaguaid']]['plomo']); ?></td>
            </tr>
          <?php endif; ?>
          <?php if($parametros[$orden[$i]['muestreoaguaid']]['zinc'] !== ''): ?>
            <tr>
              <td>Zinc</td>
              <td style="<?php if(strpos($parametros[$orden[$i]['muestreoaguaid']]['zinc'], '<') == false){if(doubleval($parametros[$orden[$i]['muestreoaguaid']]['zinc']) > doubleval($maximos[$orden[$i]['muestreoaguaid']]['zinc'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$orden[$i]['muestreoaguaid']]['zinc']); ?></td>
              <td><?php htmlout($maximos[$orden[$i]['muestreoaguaid']]['zinc']); ?></td>
            </tr>
          <?php endif; ?>
        <?php else: ?>
          <tr>
            <th>Sin párametros capturados</th>
          </tr>
        <?php endif;?>
      </table>
      <br>

      <?php if(isset($adicionales[$orden[$i]['muestreoaguaid']]) AND $adicionales[$orden[$i]['muestreoaguaid']] !== ""){ ?>
        <table style="width:50%">
          <tr>
            <th colspan=3>Adicionales</th>
          </tr>
          <tr>
            <th style="width:40%;">Nombre de Párametro</th>
            <th>Unidades</th>
            <th>Resultado</th>
          </tr>
          <?php foreach ($adicionales[$orden[$i]['muestreoaguaid']] as $value): ?>
            <tr>
              <td><?php htmlout($value['nombre']); ?></td>
              <td><?php htmlout($value['unidades']); ?></td>
              <td><?php htmlout($value['resultado']); ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      <?php } ?>

      <br><br>
    <?php } ?>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>