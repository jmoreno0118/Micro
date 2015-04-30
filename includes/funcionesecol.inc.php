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
function formularioParametros($id = "", $cantidad = "", $valores = "", $parametros = "", $adicionales = "", $idparametros = "", $boton = "", $regreso = "", $param1 = ""){
	$pestanapag='Parametros';
    $titulopagina='Parametros';
    var_dump($param1);
	if($valores == "" AND $parametros == "" AND $adicionales == ""){
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
		try{
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

			$sql='SELECT * FROM parametros2tbl WHERE parametroidfk = :id';
			$s=$pdo->prepare($sql);
			$s->bindValue(':id',$param1['id']);
			$s->execute();
			foreach ($s as $linea) {
				$parametros[]=array("GyA" => $linea["GyA"],
									"coliformes" => $linea["coliformes"]);
			}

			$sql='SELECT * FROM adicionalestbl WHERE parametroidfk = :id';
			$s=$pdo->prepare($sql); 
			$s->bindValue(':id',$param1['id']);
			$s->execute();
			foreach ($s as $linea) {
				$adicionales[]=array("nombre" => $linea["nombre"],
									"unidades" => $linea["unidades"],
									"resultado" => $linea["resultado"]);
			}
        }catch (PDOException $e){
	        $mensaje='Hubo un error extrayendo la información de parametros y adicionales.';
	        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	        exit();
        }
	}
	$_SESSION['parametros'] = array('id' => $id,
									'cantidad' => $cantidad,
									'valores' => $valores,
									'parametros' => $parametros,
									'adicionales' =>$adicionales,
									'idparametros' => $idparametros,
									'boton' => $boton,
									'regreso' => $regreso,
									'pestanapag' => $pestanapag,
									'titulopagina' => $titulopagina);
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/parametros');
}