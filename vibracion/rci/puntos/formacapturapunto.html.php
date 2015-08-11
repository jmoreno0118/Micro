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
				<p>formacapturapunto</p>
				<?php
				//var_dump($_SESSION);
					$arquitectura = array(
											"dato" => array("variables" => 'nomedicion,fecha,departamento,area,ubicacion,puesto,identificacion,evento,tipoevento,ciclos,duracion,herramienta,med1,med2,med3',
															"tipo" => 1),
											"id" => array("variables" => "id",
															"tipo" => 0)
					);
				?>
				<form action="?<?php htmlout($accion); ?>" method="post">
					<input type="hidden" name="post" value='<?php htmlout(json_encode($_POST)); ?>'>
					<input type="hidden" name="url" value="<?php htmlout($_SESSION['url']); ?>">
					<input type="hidden" name="arquitectura" value='<?php htmlout(json_encode($arquitectura)); ?>'>
          
					<div>
						<label for="nomedicion">Número de medición:</label>
						<input type="number" name="nomedicion" id="nomedicion" value="<?php isset($dato['nomedicion']) ? htmlout($dato['nomedicion']) : ''; ?>" required>
					</div>

					<div>
						<label for="fecha">Fecha (aaaa-mm-dd):</label>
						<input type="text" name="fecha" id="fecha" value="<?php isset($dato['fecha']) ? htmlout($dato['fecha']) : ''; ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="aaa-mm-dd" oninvalid="setCustomValidity('Ingrese una fecha valida')" required>
					</div>

					<div>
						<label for="departamento">Departamento:</label>
						<input type="text" name="departamento" id="departamento" value="<?php isset($dato['departamento']) ? htmlout($dato['departamento']) : ''; ?>" required>
					</div>

					<div>
						<label for="area">Área:</label>
						<input type="text" name="area" id="area" value="<?php isset($dato['area']) ? htmlout($dato['area']) : ''; ?>" required>
					</div>

					<div>
						<label for="ubicacion">Ubicación:</label>
						<input type="text" name="ubicacion" id="ubicacion" value="<?php isset($dato['ubicacion']) ? htmlout($dato['ubicacion']) : ''; ?>" required>
					</div>

					<div>
						<label for="puesto">Puesto:</label>
						<input type="text" name="puesto" id="puesto" value="<?php isset($dato['puesto']) ? htmlout($dato['puesto']) : ''; ?>" required>
					</div>

					<div>
						<label for="identificacion">Identificación:</label>
						<input type="text" name="identificacion" id="identificacion" value="<?php isset($dato['identificacion']) ? htmlout($dato['identificacion']) : ''; ?>" required>
					</div>

					<div>
						<label for="evento">Evento:</label>
						<input type="text" name="evento" id="evento" value="<?php isset($dato['evento']) ? htmlout($dato['evento']) : ''; ?>" required>
					</div>

					<div>
						<label for="tipoevento">Tipo de evento:</label>
						<input type="text" name="tipoevento" id="tipoevento" value="<?php isset($dato['tipoevento']) ? htmlout($dato['tipoevento']) : ''; ?>" required>
					</div>

					<div>
						<label for="ciclos">Ciclos:</label>
						<input type="number" name="ciclos" id="ciclos" value="<?php isset($dato['ciclos']) ? htmlout($dato['ciclos']) : ''; ?>" required>
					</div>

					<div>
						<label for="duracion">Duración (tiempo en min.):</label>
						<input type="number" name="duracion" id="duracion" value="<?php isset($dato['duracion']) ? htmlout($dato['duracion']) : ''; ?>" required>
					</div>

					<div>
						<label for="herramienta">Herramienta:</label>
						<input type="text" name="herramienta" id="herramienta" value="<?php isset($dato['herramienta']) ? htmlout($dato['herramienta']) : ''; ?>" required>
					</div>

					<fieldset>
						<legend>Mediciones:</legend>
						<label for="med1">Medicion 1:</label>
						<input type="text" name="med1" id="med1" value="<?php isset($dato['med1']) ? htmlout($dato['med1']) : ''; ?>">
						<br>

						<label for="med2">Medicion 2:</label>
						<input type="text" name="med2" id="med2" value="<?php isset($dato['med2']) ? htmlout($dato['med2']) : ''; ?>">
						<br>

						<label for="med3">Medicion 3:</label>
						<input type="text" name="med3" id="med3" value="<?php isset($dato['med3']) ? htmlout($dato['med3']) : ''; ?>">
					</fieldset>

					<div>	
						<input type="hidden" name="id" value="<?php htmlout($id); ?>">
						<input type="submit" name="boton" value="Guardar">	
					</div> 
				</form>
				<p><a href="../puntos">Regresa los puntos del reconociminento</a></p>
				<p><a href="../">Regresa los reconocimientos de la orden</a></p>
				<p><a href="../../">Regresa al búsqueda de ordenes</a></p>
			</div>  <!-- cuerpoprincipal -->
			<div id="footer">
				<?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
			</div>  <!-- footer -->
		</div> <!-- contenedor -->
	</body>
</html>