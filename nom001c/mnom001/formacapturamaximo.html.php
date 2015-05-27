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
   <h2><?php htmlout($titulopagina); ?></h2>
   <?php
   		$formulario = array("Descarga en" => "descargaen",
                          "Uso" => "uso",
                          "Grasas y Aceites" => "GyA",
                          "Coliformes Fecales" => "coliformes",
             							"Solidos sedimentables" => "ssedimentables",
                          "Solidos suspendidos" => "ssuspendidos",
                          "DBO" => "dbo",
                          "Nitrogeno" => "nitrogeno",
                          "Fosforo" => "fosforo",
                          "Arsenico" => "arsenico",
                          "Cadmio" => "cadmio",
                          "Cianuros" => "cianuros",
                          "Cobre" => "cobre",
                          "Cromo" => "cromo",
                          "Mercurio" => "mercurio",
                          "Niquel" => "niquel",
                          "Plomo" => "plomo",
                          "Zinc" => "zinc",
                          "Huevos de Helminto" => "hdehelminto",
                          "Temperatura" => "temperatura",
                          "Materia Flotante" => "mflotante");
   ?>
    <form action="" method="post">
    	<?php foreach($formulario as $label => $name): ?>
      <div>
        <label for="<?php htmlout($name); ?>"><?php htmlout($label); ?>:</label>
      <?php if($name === "temperatura"): ?>
        <select name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>">
          <option value=""<?php if(strval($valores["temperatura"]) === "") echo 'selected'?>>Seleccionar</option>
          <option value="NA" <?php if(strval($valores["temperatura"]) === "NA") echo 'selected'?>>N.A.</option>
          <option value="40" <?php if(strval($valores["temperatura"]) === "40") echo 'selected'?>>40</option>
        </select> 
      <?php elseif($name === "mflotante"): ?>
        <select name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>">
          <option value=""<?php if(strval($valores["mflotante"]) === "") echo 'selected'?>>Seleccionar</option>
          <option value="Ausente"<?php if(strval($valores["mflotante"]) === "Ausente") echo 'selected'?>>Ausente</option>
          <option value="Presente" <?php if(strval($valores["mflotante"]) === "Presente") echo 'selected'?>>Presente</option>
        </select> 
      <?php else: ?>
	    	<input type="text" name="<?php htmlout($name); ?>" id="<?php htmlout($name); ?>" value="<?php if(isset($valores)){htmlout($valores[$name]);} ?>">
    	<?php endif; ?>
      </div>
    	<?php endforeach?>
	  <div>
      <?php if(isset($id)): ?><input type="hidden" name="id" value="<?php echo $id; ?>"><?php endif; ?>
	    <input type="submit" name="accion" value="<?php htmlout($boton); ?>">
      <a href="../mnom001">Volver</a>
	  </div> 
	</form>
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>