<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Reusar punto</title>
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
				<h2></h2>
				<p>formacapturapunto</p>
					<div>
						<label for="nomedicion">Número de medición:</label>
						<input type="text" name="nomedicion" value="<?php isset($dato['medicion']) ? htmlout($dato['medicion']) : ''; ?>" disabled>
					</div>

					<div>
						<label for="fecha">Fecha (aaaa-mm-dd):</label>
						<input type="text" name="fecha" value="<?php isset($dato['fecha']) ? htmlout($dato['fecha']) : ''; ?>"  disabled>
					</div>

					<div>
						<label for="departamento">Departamento:</label>
						<input type="text" name="departamento" value="<?php isset($dato['departamento']) ? htmlout($dato['departamento']) : ''; ?>" disabled>
					</div>

					<div>
						<label for="area">Área:</label>
						<input type="text" name="area" value="<?php isset($dato['area']) ? htmlout($dato['area']) : ''; ?>" disabled>
					</div>

					<div>
						<label for="ubicacion">Ubicación:</label>
						<input type="text" name="ubicacion" value="<?php isset($dato['ubicacion']) ? htmlout($dato['ubicacion']) : ''; ?>" disabled>
					</div>
				<form action="?" method="post">
					<div>
						<input type="hidden" name="id" value="<?php htmlout($dato['id']); ?>">
						<input type="hidden" name="recid" value="<?php htmlout($_SESSION['idrci']); ?>">
						<input type="hidden" name="medicion" value="<?php htmlout($dato['medicion']); ?>">
						<input type="submit" name="accion" value="Reusar">	
					</div> 
				</form>
				<p><a href="<?php htmlout($url) ?>">Regresa los puntos del reconociminento</a></p>
			</div>  <!-- cuerpoprincipal -->
			<div id="footer">
				<?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
			</div>  <!-- footer -->
		</div> <!-- contenedor -->
	</body>
</html>