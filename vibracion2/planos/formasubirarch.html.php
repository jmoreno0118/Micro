<?php
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>subir planos</title>
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
   <h2>Administración de planos del estudio de iluminación</h2>
   <p>formasubirarch</p>
   <p><a href="../../iluminacion">Hacer otra búsqueda de ordenes</a> 
   <fieldset>
   <legend>Para subir planos al sistema</legend>
    <form action="?" method="post" enctype="multipart/form-data">
	 <div>
	  <label for="descripcion">Descrpcion del archivo</label>
	  <select name="descripcion" id="descripcion">
	   <option>Plano de Rec. Inicial</option>
	   <option>Plano de Medicones</option>
	  </select> 
	 </div>
	 <div>
	  <label for="archivo">Selecciona el archivo a subir.... </label>
	  <input type="file" id="archivo" name="archivo">
	 </div>
	 <div>
	  <input type="hidden" name="idot" value="<?php htmlout($idot);?>">
	  <input type="hidden" name="hora" value="<?php htmlout(time());?>">
	  <input type="hidden" name="accion" value="subir"> 
	  <input type="submit" value="Subir">  
	 </div>
	 <p>Nota: Los archivos que se permite subir al sitema son <strong>PDF</strong> y deben tener un tamaño MAXIMO <strong>2Mb</strong></p> 
	</form> 
   </fieldset>
    <table>
	<?php if (isset($piniciales) or isset($pmediciones)): ?>
	 <caption>Planos en el sistema</caption>
	 <tr><th>nombre</th><th>Descripción</th><th>Enlace</th><th></th></tr>
    <?php if (isset($piniciales)) : ?>
     <?php foreach ($piniciales as $pinicial): ?>
	  <tr>
	   <td><?php htmlout($pinicial['nombre']); ?></td>
	   <td><?php htmlout($pinicial['descripcion'])?></td>
	   <td><a href="<?php htmlout($pinicial['liga'])?>" target="_blank"><?php htmlout($pinicial['nombre'])?></a></td>
	   <td>
	    <form action="?" method="post">
	     <div>
	      <input type="hidden" name="id" value="<?php echo $pinicial['id']; ?>">
		  <input type="hidden" name="idot" value="<?php htmlout($idot);?>">
		  <input type="hidden" name="accion" value="borraplano">
		  <input type="submit" value="Borrar">
	     </div>
	    </form>	
	   </td>
	  </tr>
      <?php endforeach; ?>
	 <?php endif; ?>
	 <?php if (isset($pmediciones)) : ?>
	  <?php foreach ($pmediciones as $pmedicion): ?>
	  <tr>
	   <td><?php htmlout($pmedicion['nombre']); ?></td>
	   <td><?php htmlout($pmedicion['descripcion'])?></td>
	   <td><a href="<?php htmlout($pmedicion['liga'])?>" target="_blank"><?php htmlout($pmedicion['nombre'])?></a></td>
	   <td>
	    <form action="?" method="post">
	     <div>
	      <input type="hidden" name="id" value="<?php echo $pmedicion['id']; ?>">
		  <input type="hidden" name="idot" value="<?php htmlout($idot);?>">
		  <input type="hidden" name="accion" value="borraplano">
		  <input type="submit" value="Borrar">
	     </div>
	    </form>	
	   </td>
	  </tr>
      <?php endforeach; ?>
	 <?php endif; ?>
	<?php endif; ?> 
    </table>
  
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>