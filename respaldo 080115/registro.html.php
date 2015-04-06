<?php
  include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Registro</title>
  <meta charset="utf-8" />
  <!--[if lt IE 9]>
   <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]--> 
   <link rel="stylesheet" type="text/css" href="estilo.css" />
</head>
<body>
<div id="contenedor">
  <header>
    <?php
  	  $ruta='img/logoblco2.gif';
	  include '/includes/encabezado.inc.php'; ?>
  </header>
  <div id="cuerpoprincipal">
    <div id="registro">
     <h2>Hoja de registro.</h2>
	 <p>Debes registrate para poder entrar al sistema<p>
	  <?php if (isset($loginError)): ?>
	     <p><?php htmlout($loginError); ?></p>
	     <?php exit(); ?>
	  <?php else : ?>
	  <form action="" method="post">
       <div>
	     <label for="usuario">Usuario</label>
	     <input type="text" name="usuario" id="usuario"/> 
	   </div>
       <div>	  
	    <label for="clave"> clave de acceso</label>
        <input type="password" name="clave" id="clave">
	   </div>
       <div>	  
	    <input type="hidden" name="accion" value="registro">
	   </div>
       <div>	  
	    <input type="submit" value="Enviar" >
       </div>	
      </form>
     <?php endif; ?>
    </div> <!-- registro -->
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include '/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>