<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Ruido<?php //htmlout($pestanapag); ?></title>
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
   <h2>NPA Inestable<?php //htmlout($titulopagina); ?></h2>
   <?php
   		$formulario = array("No. Medición" => "numedicion",
                          "NER" => "ner",
                          "Fecha del muestreo" => "fechamuestreo",
                          "Observador" => "observador",
                          "Calibración inicial" => "calini",
                          "Calibración final" => "calfin",
                          "Departamento" => "depto",
                          "Area" => "area",
                          "Ubicación" => "ubicacion",
             							"identificación" => "identificacion");
      $tabindex = 1;
   ?>
    <form id="medsform" name="medsform" action="?" method="post">
    	<?php foreach($formulario as $label => $name): ?>
    	<div>
    		 <label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
         <input type="text" name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>" value="<?php //htmlout($valores[$name]); ?>" tabindex="<?php htmlout($tabindex); ?>">
         <?php /* ?>
	    	 <input type="text" name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>" value="<?php htmlout($valores[$name]); ?>" <?php if($name === "numedicion" AND $valores['numedicion'] !== ""){ ?> disabled <?php } ?>>
         <?php if($name === "numedicion" AND $valores['numedicion'] !== ""){ ?>
          <input type="hidden" name="<?php htmlout($name); ?>" value="<?php htmlout($valores[$name]); ?>">
         <?php } */?>
    	</div>
    	<?php $tabindex++;
      endforeach?>
      <br>

<?php

      $tipo = 1; //0 Estable - 1 inestable


      $titulos1 = array("Primer Periodo" => "p1",
                        "Segundo Periodo" => "p2");

      $titulos2 = array("Primer Periodo" => "p1",
                        "Segundo Periodo" => "p2",
                        "Tercer Periodo" => "p3",
                        "Cuarto Periodo" => "p4",
                        "Quinto Periodo" => "p5");

      $datos = array("db (A)" => "dba",
                    "Lineal" => "lineal",
                    "31.5" => "31.5",
                    "63" => "63",
                    "125" => "125",
                    "250" => "250",
                    "500" => "500",
                    "1000" => "1000",
                    "2000" => "2000",
                    "4000" => "4000",
                    "8000" => "8000");
   ?>

      <?php $j = 0; ?>

      <?php if($tipo === 0): ?>
        <table style="width:8%;float:left;">
          <tr height="52"></tr>
            <?php  foreach ($titulos1 as $key => $value): ?>
            <tr height="125">
              <td>
                <h3><?php htmlout($key); ?></h3>
              </td>
            </tr>
            <?php endforeach; ?>
        </table>
      <?php elseif($tipo === 1): ?>
        <table style="width:8%;float:left;">
          <tr height="52"></tr>
            <?php  foreach ($titulos2 as $key => $value): ?>
            <tr height="125">
              <td>
                <h3><?php htmlout($key); ?></h3>
              </td>
            </tr>
            <?php endforeach; ?>
        </table>
      <?php endif;  ?>


      <?php if($tipo === 0):
        $mediciones = 10;
       elseif($tipo === 1):
        $mediciones = 25;
       endif;
      $k = 0; ?>
      <?php foreach ($datos as $key => $value): ?>
        <table style="width:8%;float:left;">
          <tr>
            <td>
              <h3><?php htmlout($key); ?></h3>
            </td>
          </tr>
          <?php for ($i=0; $i < $mediciones; $i++): ?>
            <?php $j++; $k++; ?>
            <tr>
              <td>
                <label for="<?php htmlout($value."-".$j); ?>"><?php htmlout($k); ?>:</label>
                <input style="width:50%;" type="text" name="<?php htmlout($value."-".$j); ?>" id="<?php htmlout($value."-".$j); ?>" value="<?php //htmlout($valores[$name]); ?>" tabindex="<?php htmlout($tabindex); ?>">
              </td>
            </tr>
          <?php $tabindex++;
          endfor; ?>
        </table>
      <?php endforeach; ?>
      <br><br>
	  <div>
	    <input type="hidden" name="id" value="<?php htmlout($id); ?>">
	    <input type="submit" name="accion" value="Guardar<?php //htmlout($boton); ?>">
	    <p><a href="../nom001">Regresa al búsqueda de ordenes</a></p>
	  </div> 
	</form>
  <form action="" method="post">
      <input type="hidden" name="ot" value="<?php htmlout($_SESSION['OT']); ?>">
      <input type="submit" name="accion" value="volvermed">
  </form>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>
<link rel="stylesheet" href="../includes/jquery-validation-1.13.1/demo/site-demos.css">
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/lib/jquery-1.11.1.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/dist/jquery.validate.js"></script>
<script type="text/javascript" src="../includes/jquery-validation-1.13.1/dist/additional-methods.js"></script>