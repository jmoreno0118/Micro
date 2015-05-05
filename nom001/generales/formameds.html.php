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
   <h2>Mediciones de la OT. <?php htmlout($nombreot['ot']); ?></h2>
   <p><a href="..">Regresa a búsqueda de ordenes</a></p>
   <p><a href="?accion=capturar">capturar nueva medicion</a></p>
   
   <div style="float:right;">
    <form action="../pdf/index.php" method="post" target="_blank">
     <input type="hidden" name="ot" value="<?php htmlout($nombreot['ot']); ?>">
     <input type="submit" name="accion" value="informe">
    </form>
    <a href="<?php htmlout('http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/pdf/index.php?ot='.$nombreot['ot'].'&id='.$ot); ?>" target="_blank">Informe</a>
   </div>

   <div style="float:right;">
    <form action="../preliminar/index.php" method="post" target="_blank">
     <input type="hidden" name="ot" value="<?php htmlout($nombreot['ot']); ?>">
     <input type="submit" name="accion" value="preliminar">
    </form>
   </div>
   
   <?php if (isset($medsagua)) : ?>
    <table>
      <tr><th>Núm. Medición</th><th>Lugar</th><th>Proceso</th><th></th></tr>
      <?php foreach ($medsagua as $medagua): ?>
      <tr>
       <td><?php htmlout($medagua['numedicion']); ?></td>
       <td><?php htmlout($medagua['lugarmuestreo'])?></td>
       <td><?php htmlout($medagua['descriproceso'])?></td>
       <td>
         <div>
          <form action="" method="post" style="float:left;">
            <input type="hidden" name="id" value="<?php echo $medagua['id']; ?>">
            <input type="submit" name="accion" value="editar">
            <input type="submit" name="accion" value="borrar">
            <input type="hidden" name="tipomedicion" value="<?php echo $medagua['tipomediciones']; ?>">
            <?php if($medagua['parametros'] !== ""): ?><input type="hidden" name="idparametro" value="<?php echo $medagua['parametros']; ?>"><?php endif; ?>
            <input type="submit" name="accion" value="parametros">
            <input type="hidden" name="ot" value="<?php htmlout($ot); ?>">
            <input type="hidden" name="numedicion" value="<?php htmlout($medagua['numedicion']); ?>">
            <input type="submit" name="accion" value="croquis">
          </form>
          <form action="conagua/index.php" method="post" style="float:left;" target="_blank">
           <input type="hidden" name="otm" value="<?php htmlout($nombreot['ot'].'-'.$medagua['numedicion']); ?>">
           <input type="submit" name="accion" value="conagua">
          </form>
         </div>
       </td>
      </tr>
      <?php endforeach; ?>
    </table>
    <form action="" method="post">
      <input type="hidden" name="ot" value="<?php htmlout($ot); ?>">
      <input type="checkbox" name="terminada" value="1" <?php if(!is_null($nombreot['fechafin'])) echo "checked"; ?>>Terminada
      <input type="submit" name="accion" value="Enviar">
    </form>
   <?php else : ?>
     <p>Lo sentimos no se encontró ningún reconocimiento inicial en la orden de trabajo seleccionada</p>  
   <?php endif; ?>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>