<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Orden de Trabajo <?php htmlout($orden['ot']); ?></title>
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
    <h2>Orden de Trabajo <?php htmlout($orden['ot']); ?></h2>

    <table style="width:50%">
      <tr>
        <td style="width:50%;">Compañía</td>
        <td> <?php htmlout($orden['Razon_Social']); ?></td>
      </tr>
      <tr>
        <td>RFC</td>
        <td><?php htmlout($orden['RFC']); ?></td>    
      </tr>
      <tr>
        <td>Cuerpo receptor y Uso de agua</td>
        <td><?php htmlout($maximos['identificacion']); ?></td>    
      </tr>
      <tr>
        <td>OT</td>
        <td><?php htmlout($orden['ot']); ?></td>    
      </tr>
      <tr>
        <td>Número de medición</td>
        <td><?php htmlout($orden['numedicion']); ?></td>    
      </tr>
      <tr>
        <td>Horas del proceso de descarga</td>
        <td><?php htmlout($orden['tipomediciones']); ?></td>    
      </tr>
      <tr>
        <td>Fecha de emisión</td>
        <td><?php htmlout($orden['fechalta']); ?></td>
      </tr>
      <!--tr>
        <td>Dirección</td>
        <td><?php htmlout("direccion"); ?></td>    
      </tr-->
      <tr>
        <td>Signatario</td>
        <td><?php htmlout($orden['signatario']); ?></td>    
      </tr>
    </table>
    <br>
    <?php if(count($mcompuestas) > 1): ?>
      <fieldset style="width:50%">
        <legend>MUESTRAS</legend>
        <?php for ($i=0; $i<count($mcompuestas); $i++) :?>
          <table style="width:100%">
            <tr>
              <th colspan="2">Muestra <?php if($i+1<count($mcompuestas)){htmlout($i+1);}else{echo "Compuesta";} ?>:</th>  
            </tr>
            <tr>
              <td style="width:50%;">Fecha de conformación</td>
              <td><?php htmlout($orden['fechamuestreo']); ?></td>    
            </tr>
            <tr>
              <td>Hora de conformación</td>
              <td><?php htmlout($mcompuestas[$i]['hora']); ?></td>    
            </tr>
            <tr>
              <td>Fecha de recepción laboratorio</td>
              <td><?php htmlout($mcompuestas[$i]['fechalab']); ?></td>  
            </tr>
            <tr>
              <td>Hora de recepción laboratorio</td>
              <td><?php htmlout($mcompuestas[$i]['horalab']); ?></td>    
            </tr>
            <tr>
              <td>Identificación</td>
              <td><?php htmlout($orden['identificacion']); ?></td>    
            </tr>
            <tr>
              <td>Flujo</td>
              <td><?php htmlout($orden['fechalta']); ?></td>    
            </tr>
            <tr>
              <td>Descripción</td>
              <td><?php htmlout($mcompuestas[$i]['caracteristicas']); ?></td>    
            </tr>
            <tr>
              <td>Observaciones</td>
              <td><?php htmlout($mcompuestas[$i]['observaciones']); ?></td>    
            </tr>
          </table>
          <?php if(count($mcompuestas) > 1): ?> <br><?php endif; ?>
        <?php endfor; ?>
      </fieldset><br>
    <?php endif; ?>

    <table style="width:50%">
      <tr>
        <th style="width:40%;">Párametro</th>
        <th>Resultado</th>
        <th>LC/LD</th>
      </tr>
      <tr>
        <td>Huevos de Helminto</td>
        <td><?php htmlout($parametros['hdehelminto']); ?></td>
        <td><?php htmlout($limite['hdehelminto']); ?></td>
      </tr>
      <tr>
        <td>Solidos sedimentables</td>
        <td><?php htmlout($parametros['ssedimentables']); ?></td>
        <td><?php htmlout($limite['ssedimentables']); ?></td>
      </tr>
      <tr>
        <td>Solidos suspendidos</td>
        <td><?php htmlout($parametros['ssuspendidos']); ?></td>
        <td><?php htmlout($limite['ssuspendidos']); ?></td>
      </tr>
      <tr>
        <td>DBO</td>
        <td><?php htmlout($parametros['dbo']); ?></td>
        <td><?php htmlout($limite['dbo']); ?></td>
      </tr>
      <tr>
        <td>Nitrógeno Kjeldahl</td>
        <td><?php htmlout($parametros['nkjedahl']); ?></td>
        <td><?php //htmlout($limite['norganico']); ?></td>
      </tr>
      <tr>
        <td>Nitrógeno Nitritos</td>
        <td><?php htmlout($parametros['nitritos']);?></td>
        <td><?php //htmlout($limite['nitritos']);?></td>
      </tr>
      <tr>
        <td>Nitrógeno Nitratos</td>
        <td><?php htmlout($parametros['nitratos']); ?></td>
        <td><?php //htmlout($limite['nitratos']); ?></td>
      </tr>
      <tr>
        <td>Nitrógeno</td>
        <td><?php htmlout($parametros['nitrogeno']); ?></td>
        <td><?php htmlout($limite['nitrogeno']); ?></td>
      </tr>
      <tr>
        <td>Fosforo</td>
        <td><?php htmlout($parametros['fosforo']); ?></td>
        <td><?php htmlout($limite['fosforo']); ?></td>
      </tr>
      <tr>
        <td>Arsenico</td>
        <td><?php htmlout($parametros['arsenico']); ?></td>
        <td><?php htmlout($limite['arsenico']); ?></td>
      </tr>
      <tr>
        <td>Cadmio</td>
        <td><?php htmlout($parametros['cadmio']); ?></td>
        <td><?php htmlout($limite['cadmio']); ?></td>
      </tr>
      <tr>
        <td>Cianuros</td>
        <td><?php htmlout($parametros['cianuros']); ?></td>
        <td><?php htmlout($limite['cianuros']); ?></td>
      </tr>
      <tr>
        <td>Cobre</td>
        <td><?php htmlout($parametros['cobre']); ?></td>
        <td><?php htmlout($limite['cobre']); ?></td>
      </tr>
      <tr>
        <td>Cromo</td>
        <td><?php htmlout($parametros['cromo']); ?></td>
        <td><?php htmlout($limite['cromo']); ?></td>
      </tr>
      <tr>
        <td>Mercurio</td>
        <td><?php htmlout($parametros['mercurio']); ?></td>
        <td><?php htmlout($limite['mercurio']); ?></td>
      </tr>
      <tr>
        <td>Niquel</td>
        <td><?php htmlout($parametros['niquel']); ?></td>
        <td><?php htmlout($limite['niquel']); ?></td>
      </tr>
      <tr>
        <td>Plomo</td>
        <td><?php htmlout($parametros['plomo']); ?></td>
        <td><?php htmlout($limite['plomo']); ?></td>
      </tr>
      <tr>
        <td>Zinc</td>
        <td><?php htmlout($parametros['zinc']); ?></td>
        <td><?php htmlout($limite['zinc']); ?></td>
      </tr>
    </table>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>