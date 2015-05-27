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
      <fieldset>
        <legend> Error !! </legend>
        <div>
          <p> <?php htmlout($mensaje); ?> </p>
        </div>
        <?php if (isset($_POST['arquitectura'])): ?>
          <form action="<?php htmlout($_POST['url']); ?>" method="post">
            <?php 
            foreach (json_decode($_POST['arquitectura'], TRUE) as $nombrevariable => $estructura) {
              if($estructura['tipo'] === 0)
              {
                $valor = isset($estructura['valor']) ? $estructura['valor'] : $_POST[$estructura['variables']];
                echo '<input type="hidden" name="'.$nombrevariable.'" value="'.$valor.'">';
              }
              elseif($estructura['tipo'] === 1)
              {
                $inputs = explode(',', $estructura['variables']);
                $valores = array();
                foreach ($inputs as $variable) {
                  $valores[$variable] =  isset($_POST[$variable])? $_POST[$variable] : "0";
                }
                echo '<input type="hidden" name="'.$nombrevariable.'" value=\''.json_encode($valores).'\'>';
              }
              elseif($estructura['tipo'] === 2)
              {
                $valores = array();
                foreach ($_POST[$nombrevariable] as $key => $valor) {
                    $valores[$key] = $valor;
                }
                echo '<input type="hidden" name="'.$nombrevariable.'" value=\''.json_encode($valores).'\'>';
              }
            }
            $post = json_decode($_POST['post'], TRUE);
            if(isset($_POST['prevact'])){
              $accion = $_POST['prevact'];
            }elseif(isset($post['accion'])){
              $accion = $post['accion'];
            }else{
              $accion = $_SESSION['accion'];
            }
            echo '<input type="hidden" name="accion" value=\''.$accion.'\'>';
            $accionparam = isset($_POST['boton']) ? $_POST['boton'] : $_POST['accion'];
            echo '<input type="hidden" name="accionparam" value=\''.$accionparam.'\'>';
            ?>
            <input type="submit" value="Regresar">
          </form>
        <?php else: ?>
          <a href="javascript:closeWindow();">Close Window</a>
        <?php endif; ?>
      </fieldset>
    </div>  <!-- cuerpoprincipal -->
    <div id="footer">
      <?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
    </div>  <!-- footer -->
  </div> <!-- contenedor -->
</body>
<script language="javascript" type="text/javascript">
function closeWindow() {
  window.open('','_parent','');
  window.close();
}
</script> 
</html>