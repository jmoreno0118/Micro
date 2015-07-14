<?php
/* **************************************************************** */
/* ***** Limpia los valores de idot, idrci, idpunto y quien ******* */
/* **************************************************************** */
  function limpiasession(){
   if (isset($_SESSION['idot'])){
     unset($_SESSION['idot']);
   }
   if (isset($_SESSION['quien'])){
	 unset($_SESSION['quien']);
	} 
  }


/**************************************************************************************************/
/* Función para ver formulario de parametros de una medicion de una orden de trabajo */
/**************************************************************************************************/
function formularioParametros($id = "", $muestreoid = "", $cantidad = "", $idparametro = "", $valores = "", $parametros = "", $adicionales = "", $regreso = "", $accion = ""){
	if($accion === "" OR $accion === "salvar parametros"){
		$pestanapag='Editar Parametros';
		$titulopagina='Editar Parametros';
		$boton = 'salvar parametros';
	}elseif($accion === "guardar parametros"){
		$pestanapag='Agregar Parametros';
		$titulopagina='Agregar Parametros';
		$boton = 'guardar parametros';
	}

	if($valores === "" AND $parametros === "" AND $adicionales === ""){
	    try{
	    	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	        	
	    	$sql = 'SELECT id
	    			FROM muestreosaguatbl
	                WHERE generalaguaidfk = :id';
	        $s=$pdo->prepare($sql);
	        $s->bindValue(':id', $id);
	        $s->execute();
	        $muestreoid = $s->fetch();
	        $muestreoid = $muestreoid['id'];

	        $sql='SELECT * FROM parametrostbl
	              WHERE muestreoaguaidfk = :id';
	        $s=$pdo->prepare($sql);
	        $s->bindValue(':id', $muestreoid);
	        $s->execute();
			if($param1 = $s->fetch()){
				$pestanapag='Editar Parametros';
	    		$titulopagina='Editar Parametros';
				include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
				$idparametro = $param1['id'];
				$valores = array("ssedimentables" => $param1["ssedimentables"],
									"ssuspendidos" => $param1["ssuspendidos"],
									"dbo" => $param1["dbo"],
									"nkjedahl" => $param1["nkjedahl"],
									"nitritos" => $param1["nitritos"],
									"nitratos" => $param1["nitratos"],
									"nitrogeno" => $param1["nitrogeno"],
									"fosforo" => $param1["fosforo"],
									"arsenico" => $param1["arsenico"],
									"cadmio" => $param1["cadmio"],
									"cianuros" => $param1["cianuros"],
									"cobre" => $param1["cobre"],
									"cromo" => $param1["cromo"],
									"mercurio" => $param1["mercurio"],
									"niquel" => $param1["niquel"],
									"plomo" =>$param1["plomo"],
									"zinc" => $param1["zinc"],
									"hdehelminto" => $param1["hdehelminto"],
									"fechareporte" => $param1["fechareporte"]);

				try{
					$sql='SELECT * FROM metodosparametrostbl WHERE parametrosidfk = :id';
					$s=$pdo->prepare($sql);
					$s->bindValue(':id',$param1['id']);
					$s->execute();
					$mets = $s->fetch();
					$metodos = array("ssedimentables" => $mets["ssedimentables"],
									"ssuspendidos" => $mets["ssuspendidos"],
									"dbo" => $mets["dbo"],
									"nkjedahl" => $mets["nkjedahl"],
									"nitritos" => $mets["nitritos"],
									"nitratos" => $mets["nitratos"],
									"nitrogeno" => $mets["nitrogeno"],
									"fosforo" => $mets["fosforo"],
									"arsenico" => $mets["arsenico"],
									"cadmio" => $mets["cadmio"],
									"cianuros" => $mets["cianuros"],
									"cobre" => $mets["cobre"],
									"cromo" => $mets["cromo"],
									"mercurio" => $mets["mercurio"],
									"niquel" => $mets["niquel"],
									"plomo" =>$mets["plomo"],
									"zinc" => $mets["zinc"],
									"hdehelminto" => $mets["hdehelminto"],
									"GyA" => $mets["GyA"],
									"coliformes" => $mets["coliformes"]);

					$sql='SELECT * FROM parametros2tbl WHERE parametroidfk = :id';
					$s=$pdo->prepare($sql);
					$s->bindValue(':id',$param1['id']);
					$s->execute();
					foreach ($s as $key => $linea) {
						$parametros[$key] = array("GyA" => $linea["GyA"],
													"coliformes" => $linea["coliformes"],
													"enabled" => FALSE);
					}

					$sql='SELECT * FROM adicionalestbl WHERE parametroidfk = :id';
					$s=$pdo->prepare($sql); 
					$s->bindValue(':id',$param1['id']);
					$s->execute();
					foreach ($s as $linea) {
						$adicionales[]=array("nombre" => $linea["nombre"],
											"unidades" => $linea["unidades"],
											"resultado" => $linea["resultado"],
											"metodo" => $linea["metodo"]);
					}
		        }catch (PDOException $e){
			        $mensaje='Hubo un error extrayendo la información de parametros y adicionales.';
			        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
			        exit();
		        }
			}else{
				$pestanapag='Agregar Parametros';
				$titulopagina='Agregar Parametros';
				$boton = 'guardar parametros';
			}
		}catch (PDOException $e){
			$mensaje='Hubo un error extrayendo la información de parametros.';
			include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
			exit();
		}
	}

	$sql="SELECT  mcompuestastbl.flujo
            FROM  mcompuestastbl
            WHERE mcompuestastbl.muestreoaguaidfk  = :id";
	$s=$pdo->prepare($sql);
	$s->bindValue(':id', $muestreoid);
	$s->execute();
	foreach ($s as $key => $linea) {
		if( strcmp($linea['flujo'], "S/F") !== 0 AND strcmp($linea['flujo'], "s/f") !== 0 ){
			$parametros[$key]['enabled'] = TRUE;
		}
	}

	if(isset($_SESSION['supervisada'])){
		$pestanapag='Parametros';
		$titulopagina='Parametros';
	}

	$_SESSION['parametros'] = array('id' => $id,
									'muestreoid' => $muestreoid,
									'cantidad' => $cantidad,
									'valores' => $valores,
									'metodos' => $metodos,
									'parametros' => $parametros,
									'adicionales' =>$adicionales,
									'idparametro' => $idparametro,
									'boton' => $boton,
									'regreso' => $regreso,
									'pestanapag' => $pestanapag,
									'titulopagina' => $titulopagina);
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/parametros');
	exit();
}

/**************************************************************************************************/
/* Función para ver formulario de las mediciones de una orden de trabajo */
/**************************************************************************************************/
function formularioMediciones($id = "", $muestreoid = "", $cantidad = "", $mcompuestas = "", $regreso = ""){
	$pestanapag='Editar muestras compuestas';
	$titulopagina='Editar muestras compuestas';
	$boton = "salvar";

	if($regreso == 2){
		$pestanapag='Agregar muestras compuestas';
		$titulopagina='Agregar muestras compuestas';
		$boton = "guardar";
	}

	if($mcompuestas === ""){
		try
	    {
	    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	      $sql="SELECT id, DATE_FORMAT(mcompuestastbl.hora, '%H:%i') as 'hora', mcompuestastbl.flujo, mcompuestastbl.volumen, mcompuestastbl.observaciones,
	            mcompuestastbl.caracteristicas
	            FROM  mcompuestastbl
	            WHERE mcompuestastbl.muestreoaguaidfk  = :id";
	      $s=$pdo->prepare($sql);
	      $s->bindValue(':id', $muestreoid);
	      $s->execute();
	    }catch (PDOException $e){
	      $mensaje='Hubo un error extrayendo la información de parametros.';
	      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	      exit();
	    }
	    if($param1 = $s->fetchAll()){
	    	foreach($param1 as $linea){
				$mcompuestas[] = array("id" => $linea["id"],
										"hora" => $linea["hora"],
										"flujo" => $linea["flujo"],
										"volumen" => $linea["volumen"],
										"observaciones" => $linea["observaciones"],
										"caracteristicas" => $linea["caracteristicas"]);
			}
	    }else{
	    	$pestanapag='Agregar muestras compuestas';
			$titulopagina='Agregar muestras compuestas';
	    	$boton = "guardar";
	    }
	}

	if(isset($_SESSION['supervisada'])){
		$pestanapag='Muestras compuestas';
		$titulopagina='Muestras compuestas';
		$boton = "siguiente";
	}

	$_SESSION['mediciones'] = array('id' => $id,
									'muestreoid' => $muestreoid,
									'mcompuestas' => $mcompuestas,
									'cantidad' => $cantidad,
									'boton' => $boton,
									'regreso' => $regreso,
									'pestanapag' => $pestanapag,
									'titulopagina' => $titulopagina);
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/compuestas');
	exit();
}

/**************************************************************************************************/
/* Función para ver formulario de la informacion de siralab */
/**************************************************************************************************/
function formularioSiralab($id = "", $valores = "", $mcompuestas = "", $cantidad = "", $regreso = "", $accion = ""){
	if($accion === "" OR $accion === "salvar"){
		$pestanapag='Editar Siralab';
		$titulopagina='Editar Siralab';
		$boton = "salvar";
	}elseif($accion === "guardar"){
		$pestanapag='Agregar Siralab';
			$titulopagina='Agregar Siralab';
	    	$boton = "guardar";
	}

	if($valores === ""){
		try
	    {
	    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	      $sql="SELECT *
	            FROM siralabtbl
	            WHERE muestreoaguaidfk = :id";
	      $s=$pdo->prepare($sql);
	      $s->bindValue(':id', $id);
	      $s->execute();
	    }catch (PDOException $e){
	      $mensaje='Hubo un error extrayendo la información de parametros.';
	      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	      exit();
	    }
	    if(!$valores = $s->fetch()){
	    	$valores['datumgps'] = "WGS84";
	    	$pestanapag='Agregar Siralab';
			$titulopagina='Agregar Siralab';
	    	$boton = "guardar";
	    }
	}
	if($mcompuestas === ""){
		try
	    {
	    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	      $sql="SELECT mcompuestastbl.id, mcompuestastbl.fecharecepcion, DATE_FORMAT(mcompuestastbl.horarecepcion, '%H:%i') as 'horarecepcion',
	      				mcompuestastbl.identificacion, mcompuestastbl.temperatura, mcompuestastbl.pH
	            FROM  mcompuestastbl
	            INNER JOIN muestreosaguatbl ON mcompuestastbl.muestreoaguaidfk = muestreosaguatbl.id
	            WHERE muestreosaguatbl.id = :id";
	      $s=$pdo->prepare($sql);
	      $s->bindValue(':id', $id);
	      $s->execute();
	    }catch (PDOException $e){
	      $mensaje='Hubo un error extrayendo la información de siralab.'.$e;
	      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	      exit();
	    }
	    if($param1 = $s->fetchAll()){
	    	foreach($param1 as $linea){
				$mcompuestas[] = array("id" => $linea["id"],
										"fechalab" => $linea["fecharecepcion"],
										"horalab" => $linea["horarecepcion"],
										"identificacion" => $linea["identificacion"],
										"temperatura" => $linea["temperatura"],
										"pH" => $linea["pH"]);
			}
	    }
	}

	if(isset($_SESSION['supervisada'])){
		$pestanapag='Siralab';
		$titulopagina='Siralab';
	}

	$_SESSION['siralab'] = array('id' => $id,
									'valores' => $valores,
									'mcompuestas' => $mcompuestas,
									'cantidad' => $cantidad,
									'boton' => $boton,
									'regreso' => $regreso,
									'pestanapag' => $pestanapag,
									'titulopagina' => $titulopagina);
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/siralab');
	exit();
}