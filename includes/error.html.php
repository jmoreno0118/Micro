<?php  include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>Error</title>
  <meta charset="utf-8" /
>  <!--[if lt IE 9]>
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
    <div id="mensajerror">
	  <?php //var_dump($_POST); ?>
    <?php //echo $_POST['arquitectura']; ?>
	   <fieldset>
	   <legend> Error !! </legend>
	   <div>
		<p> <?php htmlout($mensaje); ?> </p>
		</div>
    <form action="<?php htmlout($_POST['url']); ?>" method="post">
      <?php 
      foreach (json_decode($_POST['arquitectura'], TRUE) as $nombrevariable => $estructura) {
        if($estructura['tipo'] === 0){
          $valor = isset($estructura['valor']) ? $estructura['valor'] : $_POST[$estructura['variables']];
          echo '<input type="hidden" name="'.$nombrevariable.'" value="'.$valor.'">';
        }elseif($estructura['tipo'] === 1){
          $inputs = explode(',', $estructura['variables']);
          $valores = array();
          foreach ($inputs as $variable) {
            $valores[$variable] =  isset($_POST[$variable])? $_POST[$variable] : "0";
          }
          echo '<input type="hidden" name="'.$nombrevariable.'" value=\''.json_encode($valores).'\'>';
        }elseif($estructura['tipo'] === 2){
          $inputs = explode(',', $estructura['variables']);
          $valores = array();
          foreach ($_POST[$nombrevariable] as $key => $valor) {
            $valores[$key] = $valor;
          }
          echo '<input type="hidden" name="'.$nombrevariable.'" value=\''.json_encode($valores).'\'>';
        }
      }
      $post = json_decode($_POST['post'], TRUE);
      echo '<input type="hidden" name="accion" value=\''.$post['accion'].'\'>';
      ?>
      <input type="submit" value="Regresar">
    </form>
	   </fieldset>
	  
  </div>  <!-- cuerpoprincipal -->
  <div id="footer">
    <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
  </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
</html>