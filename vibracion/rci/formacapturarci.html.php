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
				<p>formacapturavibracion</p>
				<?php
				//var_dump($_SESSION);
					$arquitectura = array(
											"ids" => array("variables" => 'area,fuente',
															"tipo" => 2),
											"rec" => array("variables" => "procedimiento,mantenimiento,eqvibracion,acelerometro,calibrador",
															"tipo" => 1),
											"puestos" => array("variables" => 'nombre,descripcion,ciclos',
															"tipo" => 2),
											"produccion" => array("variables" => 'depto,cnormales,preal',
															"tipo" => 2),
											"poes" => array("variables" => 'area,numero,expo',
															"tipo" => 2),
											"id" => array("variables" => "id",
															"tipo" => 0)
					);
				?>
				<form action="?" method="post">
					<input type="hidden" name="post" value='<?php htmlout(json_encode($_POST)); ?>'>
					<input type="hidden" name="url" value="<?php htmlout($_SESSION['url']); ?>">
					<input type="hidden" name="arquitectura" value='<?php htmlout(json_encode($arquitectura)); ?>'>
					
					<fieldset>
						<legend>Identificación de lugares donde existe exposición a vibraciones:</legend>
						<?php for ($i=0; $i<10; $i++) :?>
						<div>
							<label for="ids[<?php echo $i; ?>][area]">Área:</label>
							<input type="text" name="ids[<?php echo $i; ?>][area]" id="ids[<?php echo $i; ?>][area]" value="<?php isset($ids[$i]) ? htmlout($ids[$i]["area"]) : ""; ?>">

							<label for="ids[<?php echo $i; ?>][fuente]">Identificación de la fuente:</label>
							<input type="text" name="ids[<?php echo $i; ?>][fuente]" id="ids[<?php echo $i; ?>][fuente]" value="<?php isset($ids[$i]) ? htmlout($ids[$i]["fuente"]) : ""; ?>">
						</div>
						<?php endfor; ?>
					</fieldset>
					<br>

					<div>
						<label for="procedimiento">Descripción de procedimientos de operación de maquinaria, herramientas, materiales usados y equipos del proceso, así como las condiciones que pudieran alterar las características de las vibraciones:</label>
						<input type="text" name="procedimiento" id="procedimiento" value="<?php isset($rec['procedimiento']) ? htmlout($rec['procedimiento']) : "";?>">
					</div>
					<br>

					<fieldset>
						<legend>Descripción de los puestos de trabajo para determinar la exposición.</legend>
						<?php for ($i=0; $i<10; $i++) :?>
						<div>
							<label for="puestos[<?php echo $i; ?>][nombre]">Puesto:</label>
							<input type="text" name="puestos[<?php echo $i; ?>][nombre]" id="puestos[<?php echo $i; ?>][nombre]" value="<?php isset($puestos[$i]) ? htmlout($puestos[$i]["nombre"]) : ""; ?>">

							<label for="puestos[<?php echo $i; ?>][descripcion]">Descripción:</label>
							<input type="text" name="puestos[<?php echo $i; ?>][descripcion]" id="puestos[<?php echo $i; ?>][descripcion]" value="<?php isset($puestos[$i]) ? htmlout($puestos[$i]["descripcion"]) : ""; ?>">

							<label for="puestos[<?php echo $i; ?>][ciclos]">Ciclos:</label>
							<input type="number" name="puestos[<?php echo $i; ?>][ciclos]" id="puestos[<?php echo $i; ?>][ciclos]" value="<?php isset($puestos[$i]) ? htmlout($puestos[$i]["ciclos"]) : ""; ?>">
						</div>
						<?php endfor; ?>
					</fieldset>
					<br>

					<div>
						<label for="mantenimiento">Programas de mantenimiento de maquinaria y equipos generadores de vibración:</label>
						<input type="text" name="mantenimiento" id="mantenimiento" value="<?php isset($rec['mantenimiento']) ? htmlout($rec['mantenimiento']) : "";?>">
					</div>
					<br>

					<fieldset>
						<legend>Registros de producción:</legend>
						<?php for ($i=0; $i<10; $i++) :?>
						<div>
							<label for="produccion[<?php echo $i; ?>][depto]">Depto/area:</label>
							<input type="text" name="produccion[<?php echo $i; ?>][depto]" id="produccion[<?php echo $i; ?>][depto]" value="<?php isset($produccion[$i]) ? htmlout($produccion[$i]["depto"]) : ""; ?>">

							<label for="produccion[<?php echo $i; ?>][cnormales]">En condiciones normales:</label>
							<input type="text" name="produccion[<?php echo $i; ?>][cnormales]" id="produccion[<?php echo $i; ?>][cnormales]" value="<?php isset($produccion[$i]) ? htmlout($produccion[$i]["cnormales"]) : ""; ?>">

							<label for="produccion[<?php echo $i; ?>][preal]">Producción real:</label>
							<input type="text" name="produccion[<?php echo $i; ?>][preal]" id="produccion[<?php echo $i; ?>][preal]" value="<?php isset($produccion[$i]) ? htmlout($produccion[$i]["preal"]) : ""; ?>">
						</div>
						<?php endfor; ?>
					</fieldset>
					<br>

					<fieldset>
						<legend>Número de POE por área y por proceso de trabajo y tiempos de exposición:</legend>
						<?php for ($i=0; $i<10; $i++) :?>
						<div>
							<label for="poes[<?php echo $i; ?>][area]">Área y/o proceso:</label>
							<input type="text" name="poes[<?php echo $i; ?>][area]" id="poes[<?php echo $i; ?>][area]" value="<?php isset($poes[$i]) ? htmlout($poes[$i]["area"]) : ""; ?>">

							<label for="poes[<?php echo $i; ?>][numero]">Número de trabajadores:</label>
							<input type="number" name="poes[<?php echo $i; ?>][numero]" id="poes[<?php echo $i; ?>][numero]" value="<?php isset($poes[$i]) ? htmlout($poes[$i]["numero"]) : ""; ?>">

							<label for="poes[<?php echo $i; ?>][expo]">Tiempo de exposición:</label>
							<input type="text" id="24h" name="poes[<?php echo $i; ?>][expo]" id="poes[<?php echo $i; ?>][expo]" value="<?php isset($poes[$i]) ? htmlout($poes[$i]["expo"]) : ""; ?>" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="hh:mm">
						</div>
						<?php endfor; ?>
					</fieldset>
					<br>

					Datos de los instumentos:
					<div>
						<?php crearForma(
		                    'Equipo de vibraciones', //Texto del label
		                    'eqvibracion', //Texto a colocar en los atributos id y name
		                    isset($rec['eqvibracion']) ? $rec['eqvibracion'] : '', //Valor extraido de la bd
		                    '', //Atributos extra de la etiqueta
		                    'select', //Tipo de etiqueta
		                    $eqvibraciones //Options para los select
		                ); ?>
					</div>

					<div>
						<?php crearForma(
		                    'Acelerómetro', //Texto del label
		                    'acelerometro', //Texto a colocar en los atributos id y name
		                    isset($rec['acelerometro']) ? $rec['acelerometro'] : '', //Valor extraido de la bd
		                    '', //Atributos extra de la etiqueta
		                    'select', //Tipo de etiqueta
		                    ''//$acelerometros //Options para los select
		                ); ?>
					</div>

					<div>
						<?php crearForma(
		                    'Calibrador', //Texto del label
		                    'calibrador', //Texto a colocar en los atributos id y name
		                    isset($rec['calibrador']) ? $rec['calibrador'] : '', //Valor extraido de la bd
		                    '', //Atributos extra de la etiqueta
		                    'select', //Tipo de etiqueta
		                    $calibradores //Options para los select
		                ); ?>
					</div>

					<div>
						<input type="hidden" name="id" value="<?php htmlout($id); ?>">
						<input type="hidden" name="accion" value="<?php htmlout($boton); ?>">
						<input type="submit" name="boton" value="Guardar">
					</div> 
				</form>
				<p><a href="../rci">Regresa a los reconocimientos iniciales de la orden</a></p>
				<p><a href="../../vibracion">Regresa a la búsqueda de ordenes</a></p>
			</div>  <!-- cuerpoprincipal -->
			<div id="footer">
				<?php include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/pie_pag.inc.php'; ?>
			</div>  <!-- footer -->
		</div> <!-- contenedor -->
	</body>
</html>