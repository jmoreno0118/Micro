<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title><?php htmlout($pestanapag); ?></title>
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
    <h2>Orden de Trabajo </h2>
    <label>Compañia</label> <?php htmlout($orden['razonsocial']); ?>
    <label>RFC</label> clientestbl.rfc <?php htmlout($orden['rfc']); ?>
    <label>Cuerpo receptor y Uso de agua</label> nom01maximostbl.identificacion <?php htmlout($maximo['identificacion']); ?>
    <label>OT</label> ordenestbl.ot <?php htmlout($orden['ot']); ?>
    <label>Horas del proceso de descarga</label> generalesaguatbl.tipomediciones <?php htmlout($orden['tipomediciones']); ?>
    <label>Fecha de emisión</label> ordenestbl.fechaalta <?php htmlout($orden['fechaalta']); ?>
    <label>Dirección</label> <?php htmlout(); ?>
    <label>Signatario</label> <?php htmlout($orden['signatario']); ?>

    <!--  Van a ser varios  -->
    Simple
    <label>Fecha y Hora de muestreo</label> muestreoaguatbl.fechamuestreo mcompuestastbl.hora <?php htmlout(); ?>
    <label>Fecha y Hora de recepcion en laboratorio</label> laboratoriotbl.fecharecepcion laboratoriotbl.horarecepcion <?php htmlout(); ?>
    <label>Identificacion</label> muestreoaguatbl.identificacion <?php htmlout(); ?>
    <label>Descripcion</label> generalesaguatbl.Caracdescarga <?php htmlout(); ?>
    <label>Observaciones por toma</label> mcompuestastbl.observaciones <?php htmlout(); ?>
    <!--  Van a ser varios  -->
    Compuesta
    <label>Fecha y Hora de muestreo</label> muestreoaguatbl.fechamuestreo mcompuestastbl.hora
    <label>Fecha y Hora de recepcion en laboratorio</label> laboratoriotbl.fecharecepcion laboratoriotbl.horarecepcion
    <label>Identificacion</label> muestreoaguatbl.identificacion
    <label>Descripcion</label> generalesaguatbl.Caracdescarga
    <label>Observaciones por toma</label> mcompuestastbl.observaciones

    <label>Huevos de Helminto</label> hdehelminto <?php htmlout($orden['hdehelminto']); ?>
    <label>Solidos sedimentables</label>  ssedimentables <?php htmlout($orden['ssedimentables']); ?>
    <label>Solidos suspendidos</label>  ssuspendidos <?php htmlout($orden['ssuspendidos']); ?>
    <label>DBO</label>  dbo <?php htmlout($orden['dbo']); ?>
    <label>Nitrogeno Amoniacal</label>  namoniacal <?php htmlout($orden['namoniacal']); ?>
    <label>Nitrogeno Organico</label>  norganico <?php htmlout($orden['norganico']); ?>
    <label>Nitritos</label>  nitritos <?php htmlout($orden['nitritos']); ?>
    <label>Nitratos</label>  nitratos <?php htmlout($orden['nitratos']); ?>
    <label>Nitrogeno</label>  nitrogeno <?php htmlout($orden['nitrogeno']); ?>
    <label>Fosforo</label>  fosforo <?php htmlout($orden['fosforo']); ?>
    <label>Arsenico</label> arsenico <?php htmlout($orden['arsenico']); ?>
    <label>Cadmio</label> cadmio <?php htmlout($orden['cadmio']); ?>
    <label>Cianuros</label> cianuros <?php htmlout($orden['cianuros']); ?>
    <label>Cobre</label> cobre <?php htmlout($orden['cobre']); ?>
    <label>Cromo</label> cromo <?php htmlout($orden['cromo']); ?>
    <label>Mercurio</label>  mercurio <?php htmlout($orden['mercurio']); ?>
    <label>Niquel</label>  niquel <?php htmlout($orden['niquel']); ?>
    <label>Plomo</label> plomo <?php htmlout($orden['plomo']); ?>
    <label>Zinc</label> zinc <?php htmlout($orden['zinc']); ?>

    Adicionales

    

  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>