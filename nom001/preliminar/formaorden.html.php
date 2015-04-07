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
        <td><?php htmlout($maximos[$i]['identificacion']); ?></td>    
      </tr>
    </table>
    <br>
    <table style="width:50%">
      <tr>
        <th style="width:40%;">Párametro</th>
        <th>Resultado</th>
        <th>Norma</th>
      </tr>
      <tr>
        <td>Huevos de Helminto</td>
        <td style="<?php if(strpos($parametros[$i]['hdehelminto'], '<') == false){if($parametros[$i]['hdehelminto'] > doubleval($maximos[$i]['hdehelminto'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$i]['hdehelminto']); ?></td>
        <td><?php htmlout($maximos[$i]['hdehelminto']); ?></td>
      </tr>
      <tr>
        <td>Solidos sedimentables</td>
        <td style="<?php if(strpos($parametros[$i]['ssedimentables'], '<') == false){if(doubleval($parametros[$i]['ssedimentables']) > doubleval($maximos[$i]['ssedimentables'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$i]['ssedimentables']); ?></td>
        <td><?php htmlout($maximos[$i]['ssedimentables']); ?></td>
      </tr>
      <tr>
        <td>Solidos suspendidos</td>
        <td style="<?php if(strpos($parametros[$i]['ssuspendidos'], '<') == false){if(doubleval($parametros[$i]['ssuspendidos']) > doubleval($maximos[$i]['ssuspendidos'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$i]['ssuspendidos']); ?></td>
        <td><?php htmlout($maximos[$i]['ssuspendidos']); ?></td>
      </tr>
      <tr>
        <td>DBO</td>
        <td style="<?php if(strpos($parametros[$i]['dbo'], '<') == false){if(doubleval($parametros[$i]['dbo']) > doubleval($maximos[$i]['dbo'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$i]['dbo']); ?></td>
        <td><?php htmlout($maximos[$i]['dbo']); ?></td>
      </tr>
      <tr>
        <td>Nitrógeno Kjeldahl</td>
        <td><?php htmlout($parametros[$i]['nkjedahl']); ?></td>
        <td><?php htmlout('No Aplica'); ?></td>
      </tr>
      <tr>
        <td>Nitrógeno Nitritos</td>
        <td><?php htmlout($parametros[$i]['nitritos']);?></td>
        <td><?php htmlout('No Aplica'); ?></td>
      </tr>
      <tr>
        <td>Nitrógeno Nitratos</td>
        <td><?php htmlout($parametros[$i]['nitratos']); ?></td>
        <td><?php htmlout('No Aplica'); ?></td>
      </tr>
      <tr>
        <td>Nitrógeno</td>
        <td style="<?php if(strpos($parametros[$i]['nitrogeno'], '<') == false){if(doubleval($parametros[$i]['nitrogeno']) > doubleval($maximos[$i]['nitrogeno'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$i]['nitrogeno']); ?></td>
        <td><?php htmlout($maximos[$i]['nitrogeno']); ?></td>
      </tr>
      <tr>
        <td>Fosforo</td>
        <td style="<?php if(strpos($parametros[$i]['fosforo'], '<') == false){if(doubleval($parametros[$i]['fosforo']) > doubleval($maximos[$i]['fosforo'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$i]['fosforo']); ?></td>
        <td><?php htmlout($maximos[$i]['fosforo']); ?></td>
      </tr>
      <tr>
        <td>Arsenico</td>
        <td style="<?php if(strpos($parametros[$i]['arsenico'], '<') == false){if(doubleval($parametros[$i]['arsenico']) > doubleval($maximos[$i]['arsenico'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$i]['arsenico']); ?></td>
        <td><?php htmlout($maximos[$i]['arsenico']); ?></td>
      </tr>
      <tr>
        <td>Cadmio</td>
        <td style="<?php if(strpos($parametros[$i]['cadmio'], '<') == false){if(doubleval($parametros[$i]['cadmio']) > doubleval($maximos[$i]['cadmio'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$i]['cadmio']); ?></td>
        <td><?php htmlout($maximos[$i]['cadmio']); ?></td>
      </tr>
      <tr>
        <td>Cianuros</td>
        <td style="<?php if(strpos($parametros[$i]['cianuros'], '<') == false){if(doubleval($parametros[$i]['cianuros']) > doubleval($maximos[$i]['nitrogeno'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$i]['cianuros']); ?></td>
        <td><?php htmlout($maximos[$i]['cianuros']); ?></td>
      </tr>
      <tr>
        <td>Cobre</td>
        <td style="<?php if(strpos($parametros[$i]['cobre'], '<') == false){if(doubleval($parametros[$i]['cobre']) > doubleval($maximos[$i]['cobre'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$i]['cobre']); ?></td>
        <td><?php htmlout($maximos[$i]['cobre']); ?></td>
      </tr>
      <tr>
        <td>Cromo</td>
        <td style="<?php if(strpos($parametros[$i]['cromo'], '<') == false){if(doubleval($parametros[$i]['cromo']) > doubleval($maximos[$i]['cromo'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$i]['cromo']); ?></td>
        <td><?php htmlout($maximos[$i]['cromo']); ?></td>
      </tr>
      <tr>
        <td>Mercurio</td>
        <td style="<?php if(strpos($parametros[$i]['mercurio'], '<') == false){if(doubleval($parametros[$i]['mercurio']) > doubleval($maximos[$i]['mercurio'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$i]['mercurio']); ?></td>
        <td><?php htmlout($maximos[$i]['mercurio']); ?></td>
      </tr>
      <tr>
        <td>Niquel</td>
        <td style="<?php if(strpos($parametros[$i]['niquel'], '<') == false){if(doubleval($parametros[$i]['niquel']) > doubleval($maximos[$i]['niquel'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$i]['niquel']); ?></td>
        <td><?php htmlout($maximos[$i]['niquel']); ?></td>
      </tr>
      <tr>
        <td>Plomo</td>
        <td style="<?php if(strpos($parametros[$i]['plomo'], '<') == false){if(doubleval($parametros[$i]['plomo']) > doubleval($maximos[$i]['plomo'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$i]['plomo']); ?></td>
        <td><?php htmlout($maximos[$i]['plomo']); ?></td>
      </tr>
      <tr>
        <td>Zinc</td>
        <td style="<?php if(strpos($parametros[$i]['zinc'], '<') == false){if(doubleval($parametros[$i]['zinc']) > doubleval($maximos[$i]['zinc'])) echo 'font-weight: bold;';} ?>"><?php htmlout($parametros[$i]['zinc']); ?></td>
        <td><?php htmlout($maximos[$i]['zinc']); ?></td>
      </tr>
    </table>
    <br><br>
    <?php } ?>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>