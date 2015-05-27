<?php
 /********** Norma 001 **********/
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/funcionesecol.inc.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';

 if (!usuarioRegistrado())
 {
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/direccionaregistro.inc.php';
  exit();
 }
 if (!usuarioConPermiso('Captura'))
 {
  $mensaje='Solo el Capturista tiene acceso a esta parte del programa';
  include '../accesonegado.html.php';
  exit();
 }
 limpiasession();

/**************************************************************************************************/
/* Guardar una nueva medición de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='guardar')
  {
    /*$mensaje='Error Forzado 1.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();*/

    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    $mcompuestas = "";
    if(isset($_POST['regreso']) AND $_POST['regreso'] === '2'){
      $cantidad = intval($_POST['cantidad']);
      $id = $_POST['id'];
      $mcompuestas = json_decode($_POST['mcompuestas'], TRUE);
    }else{
      try
      {
        $pdo->beginTransaction();

        $sql='UPDATE clientestbl SET
              Giro_Empresa=:empresagiro
              WHERE Numero_Cliente = (SELECT clienteidfk
                                      FROM ordenestbl
                                      WHERE id = :id)';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id',$_POST['id']);
        $s->bindValue(':empresagiro',$_POST['empresagiro']);
        $s->execute();

        $sql='SELECT id FROM nom01maximostbl WHERE descargaen =:descargaen AND uso=:uso';
        $s=$pdo->prepare($sql); 
        $s->bindValue(':descargaen', isset($_POST['descargaen'])? $_POST['descargaen'] : "");
        $s->bindValue(':uso', isset($_POST['uso'])? $_POST['uso'] : "");
        $s->execute();
        $nom01maximosidfk = $s->fetch();

        $sql='INSERT INTO generalesaguatbl SET
              ordenaguaidfk=:id,
              nom01maximosidfk=:nom01maximosidfk,
              numedicion=:numedicion,
              lugarmuestreo=:lugarmuestreo,
              descriproceso=:descriproceso,
              materiasusadas=:materiasusadas,
              tratamiento=:tratamiento,
              Caracdescarga=:Caracdescarga,
              receptor=:receptor,
              estrategia=:estrategia,
              numuestras=:numuestras,
              observaciones=:observaciones,
              tipomediciones=:tipomediciones';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->bindValue(':nom01maximosidfk', $nom01maximosidfk['id']);
        $s->bindValue(':numedicion', intval($_POST['numedicion']),PDO::PARAM_INT );
        $s->bindValue(':lugarmuestreo', $_POST['lugarmuestreo']);
        $s->bindValue(':descriproceso', $_POST['descriproceso']);
        $s->bindValue(':materiasusadas', $_POST['materiasusadas']);
        $s->bindValue(':tratamiento', $_POST['tratamiento']);
        $s->bindValue(':Caracdescarga', $_POST['Caracdescarga']);
        $s->bindValue(':receptor', $_POST['receptor']);
        $s->bindValue(':estrategia', $_POST['estrategia']);
        $s->bindValue(':numuestras', $_POST['numuestras']);
        $s->bindValue(':observaciones', $_POST['observaciones']);
        $s->bindValue(':tipomediciones', $_POST['tipomediciones']);
        //$s->bindValue(':proposito', $_POST['proposito']);
        $s->execute();
        $id=$pdo->lastInsertid();

        $sql='INSERT INTO muestreosaguatbl SET
              generalaguaidfk=:generalaguaidfk,
              fechamuestreo=:fechamuestreo,
              identificacion=:identificacion,
              temperatura=:temperatura,
              caltermometro=:caltermometro,
              pH=:pH,
              conductividad=:conductividad,
              responsable=:responsable,
              mflotante=:mflotante,
              olor=:olor,
              color=:color,
              turbiedad=:turbiedad,
              GyAvisual=:GyAvisual,
              burbujas=:burbujas';
        $s=$pdo->prepare($sql);
        $s->bindValue(':generalaguaidfk',$id);
        $s->bindValue(':fechamuestreo',$_POST['fechamuestreo']);
        $s->bindValue(':identificacion',$_POST['identificacion']);
        $s->bindValue(':temperatura',$_POST['temperatura']);
        $s->bindValue(':caltermometro',$_POST['caltermometro']);
        $s->bindValue(':pH',$_POST['pH']);
        $s->bindValue(':conductividad',$_POST['conductividad']);
        $s->bindValue(':responsable',$_POST['responsable']);
        $s->bindValue(':mflotante',$_POST['mflotante']);
        $s->bindValue(':olor',$_POST['olor']);
        $s->bindValue(':color',$_POST['color']);
        $s->bindValue(':turbiedad',$_POST['turbiedad']);
        $s->bindValue(':GyAvisual',$_POST['GyAvisual']);
        $s->bindValue(':burbujas',$_POST['burbujas']);
        $s->execute();

        if(isset($_POST['fechamuestreofin']) AND $_POST['fechamuestreofin'] !== ""){
          $sql='UPDATE muestreosaguatbl SET
              fechamuestreofin=:fechamuestreofin
              WHERE generalaguaidfk=:generalaguaidfk';
          $s = $pdo->prepare($sql);
          $s->bindValue(':generalaguaidfk', $id);
          $s->bindValue(':fechamuestreofin', $_POST['fechamuestreofin']);
          $s->execute();
        }

        $pdo->commit();
      }
      catch (PDOException $e)
      {
        $pdo->rollback();
        $mensaje='Hubo un error al tratar de insertar la medicion. Favor de intentar nuevamente.'.$e;
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
        exit();
      }
      $cantidad = 1;
      if($_POST['tipomediciones'] === '8'){
        $cantidad = 4;
      }else if($_POST['tipomediciones'] === '16' || $_POST['tipomediciones'] === '24'){
        $cantidad = 6;
      }
    }
    $_SESSION['accion'] = 'guardar';
    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
    $host     = $_SERVER['HTTP_HOST'];
    $script   = $_SERVER['SCRIPT_NAME'];
    $params   = $_SERVER['QUERY_STRING'];
    $currentUrl = $protocol . '://' . $host . $script . '?' . $params;
    $_SESSION['url'] = $currentUrl;

    if($cantidad === 1){
      formularioParametros($id, $cantidad, "", "", "", "", 1);
    }
    formularioMediciones($id, $cantidad, $mcompuestas, 2);
  }

/**************************************************************************************************/
/* Agregar una nueva medicion a una orden de trabajo */
/**************************************************************************************************/
	if (isset($_GET['accion']) and $_GET['accion']=='capturar')
	{
    $_SESSION['accion'] = 'parametros';
    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
    $host     = $_SERVER['HTTP_HOST'];
    $script   = $_SERVER['SCRIPT_NAME'];
    $params   = $_SERVER['QUERY_STRING'];
    $currentUrl = $protocol . '://' . $host . $script . '?' . $params;
    $_SESSION['url'] = $currentUrl;

		$id = $_SESSION['ot'];
		$pestanapag = 'Agrega medicón';
		$titulopagina = 'Agregar una nueva medición';
		$boton = 'guardar';
		$descargaen = getMaximos();
		if(isset($_POST['valores'])){
			$new = "";
			$valores = json_decode($_POST['valores'],TRUE);
		}else{
			$valores = array("empresagiro" => getEGiro($id),
  				             "descargaen" => "",
  				             "uso" => "",
  				             "responsable" => getResponsable($id)
                );
		}
		include 'formacapturarmeds.html.php';
		exit();
	}

/**************************************************************************************************/
/* Editar reconocimiento inicial de una orden de trabajo */
/**************************************************************************************************/
  if((isset($_POST['accion']) and $_POST['accion']=='editar') OR (isset($_POST['accion']) AND $_POST['accion'] == 'volver' AND isset($_POST['meds'])))
  {
	$id = $_POST['id'];
  if(isset($_POST['valores'])){
    $valores = json_decode($_POST['valores'],TRUE);
  }else{
  	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  	try
  	{
  		$sql='SELECT * FROM generalesaguatbl
  		INNER JOIN muestreosaguatbl ON generalesaguatbl.id=muestreosaguatbl.generalaguaidfk
  		WHERE generalesaguatbl.id = :id';
  		$s=$pdo->prepare($sql); 
  		$s->bindValue(':id',$_POST['id']);
  		$s->execute();
  		$linea = $s->fetch();

  		$sql='SELECT descargaen, uso FROM nom01maximostbl WHERE id=:id';
  		$s=$pdo->prepare($sql); 
  		$s->bindValue(':id', $linea["nom01maximosidfk"]);
  		$s->execute();
  		$nom01maximos = $s->fetch();
  	}catch (PDOException $e){
  		$mensaje='Hubo un error extrayendo la información de reconocimiento inicial.';
  		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
  		exit();
  	}
		$valores = array("empresagiro" => getEGiro($linea["ordenaguaidfk"]),
			           "descargaen" => $nom01maximos["descargaen"],
			           "uso" => $nom01maximos["uso"],
			           "numedicion" => $linea["numedicion"],
			           "lugarmuestreo" => $linea["lugarmuestreo"],
			           "descriproceso" => $linea["descriproceso"],
			           "tipomediciones" => $linea["tipomediciones"],
			           //"proposito" => $linea["proposito"],
			           "materiasusadas" => $linea["materiasusadas"],
			           "tratamiento" => $linea["tratamiento"],
			           "Caracdescarga" => $linea["Caracdescarga"],
			           "receptor" => $linea["receptor"],
			           "estrategia" => $linea["estrategia"],
			           "numuestras" => $linea["numuestras"],
			           "observaciones" => $linea["observaciones"],
			           "fechamuestreo" => $linea["fechamuestreo"],
                 "fechamuestreofin" => $linea["fechamuestreofin"],
			           "identificacion" => $linea["identificacion"],
			           "temperatura" => $linea["temperatura"],
			           "caltermometro" => $linea["caltermometro"],
			           "pH" => $linea["pH"],
			           "conductividad" => $linea["conductividad"],
			           "responsable" => $linea["responsable"],
			           "mflotante" => $linea["mflotante"],
			           "olor" => $linea["olor"],
			           "color" => $linea["color"],
			           "turbiedad" => $linea["turbiedad"],
			           "GyAvisual" => $linea["GyAvisual"],
			           "burbujas" => $linea["burbujas"]);
	}
  $descargaen = getMaximos();
	$pestanapag='Editar medicion';
	$titulopagina='Editar medicion';
	$boton = 'salvar';
	$regreso = 1;
	include 'formacapturarmeds.html.php';
	exit();
  }

/**************************************************************************************************/
/* Borrar una medición de una orden de trabajo */
/**************************************************************************************************/
  if (isset($_POST['accion']) and $_POST['accion']=='borrar')
  {
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php'; 
	try
	{
		$sql='SELECT generalesaguatbl.numedicion, generalesaguatbl.lugarmuestreo, generalesaguatbl.descriproceso, muestreosaguatbl.fechamuestreo, muestreosaguatbl.identificacion
		  FROM generalesaguatbl
		  INNER JOIN muestreosaguatbl ON generalesaguatbl.id = muestreosaguatbl.generalaguaidfk
		  WHERE generalesaguatbl.id=:id';
		$s= $pdo->prepare($sql);
		$s->bindValue(':id',$_POST['id']); 
		$s->execute();
	}catch (PDOException $e){
		$mensaje='No se pudo hacer la confirmacion de eliminación'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	$id=$_POST['id'];
	$resultado=$s->fetch();
	$nummedicion=$resultado['numedicion'];
	$lugarmuestreo=$resultado['lugarmuestreo'];
	$descriproceso=$resultado['descriproceso'];
	$fechamuestreo=$resultado['fechamuestreo'];
	$identificacion=$resultado['identificacion'];
	include 'formaconfirmamed.html.php';
	exit();
  }

/**************************************************************************************************/
/* Confirmación de borrado de una medición de una orden de trabajo */
/**************************************************************************************************/
  if (isset($_POST['accion']) and $_POST['accion']=='Continuar borrando medicion')
  {
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try
	{
	    $pdo->beginTransaction();
	    $sql='DELETE FROM parametros2tbl WHERE parametroidfk IN (SELECT parametrostbl.id FROM parametrostbl INNER JOIN muestreosaguatbl ON muestreosaguatbl.id=parametrostbl.muestreoaguaidfk WHERE muestreosaguatbl.generalaguaidfk= :id)';
	    $s=$pdo->prepare($sql);
	    $s->bindValue(':id',$_POST['id']);
	    $s->execute();

	    $sql='DELETE FROM mcompuestastbl WHERE muestreoaguaidfk IN (SELECT id FROM muestreosaguatbl WHERE generalaguaidfk = :id)';
	    $s=$pdo->prepare($sql);
	    $s->bindValue(':id',$_POST['id']);
	    $s->execute(); 

	    $sql='DELETE FROM parametrostbl WHERE muestreoaguaidfk IN (SELECT id FROM muestreosaguatbl WHERE generalaguaidfk = :id)';
	    $s=$pdo->prepare($sql);
	    $s->bindValue(':id',$_POST['id']);
	    $s->execute(); 

	    $sql='DELETE FROM muestreosaguatbl WHERE generalaguaidfk=:id';
	    $s=$pdo->prepare($sql);
	    $s->bindValue(':id',$_POST['id']);
	    $s->execute();

	    $sql='DELETE FROM generalesaguatbl WHERE id=:id';
	    $s=$pdo->prepare($sql);
	    $s->bindValue(':id',$_POST['id']);
	    $s->execute();
	    $pdo->commit();
	}catch (PDOException $e){
	    $pdo->rollback();
	    $mensaje='Hubo un error borrando la medición. Intente de nuevo. ';
	    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	    exit();
	}
	verMeds($_POST['ot']);
  }

/**************************************************************************************************/
/* Guardar la edición de una orden de trabajo */
/**************************************************************************************************/
  if((isset($_POST['accion']) AND $_POST['accion'] == 'salvar') OR (isset($_POST['accion']) AND $_POST['accion'] == 'volver' AND isset($_POST['coms'])))
  {
    /*$mensaje='Error Forzado 1.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();*/

    $_SESSION['accion'] = 'salvar';
    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
    $host     = $_SERVER['HTTP_HOST'];
    $script   = $_SERVER['SCRIPT_NAME'];
    $params   = $_SERVER['QUERY_STRING'];
    $currentUrl = $protocol . '://' . $host . $script . '?' . $params;
    $_SESSION['url'] = $currentUrl;

    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    $id = $_POST['id'];
    if(!isset($_POST['regreso'])){
      try
      {
        $pdo->beginTransaction();
       
        $sql='UPDATE clientestbl SET
              Giro_Empresa=:Giro_Empresa
              WHERE Numero_Cliente = (SELECT clienteidfk
                                      FROM ordenestbl
                                      WHERE id = :id)';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id',$_SESSION['OT']);
        $s->bindValue(':Giro_Empresa',$_POST['empresagiro']);
        $s->execute();

        $sql='SELECT id FROM nom01maximostbl WHERE descargaen =:descargaen AND uso=:uso';
        $s=$pdo->prepare($sql); 
        $s->bindValue(':descargaen',$_POST['descargaen']);
        $s->bindValue(':uso',$_POST['uso']);
        $s->execute();
        $nom01maximosidfk = $s->fetch();

        $sql='UPDATE generalesaguatbl SET
              nom01maximosidfk=:nom01maximosidfk,
              numedicion=:numedicion,
              lugarmuestreo=:lugarmuestreo,
              descriproceso=:descriproceso,
              materiasusadas=:materiasusadas,
              tratamiento=:tratamiento,
              Caracdescarga=:Caracdescarga,
              receptor=:receptor,
              estrategia=:estrategia,
              numuestras=:numuestras,
              observaciones=:observaciones,
              tipomediciones=:tipomediciones
              WHERE id=:id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id',$_POST['id']);
        $s->bindValue(':nom01maximosidfk', $nom01maximosidfk['id']);
        $s->bindValue(':numedicion', intval($_POST['numedicion']), PDO::PARAM_INT );
        $s->bindValue(':lugarmuestreo', $_POST['lugarmuestreo']);
        $s->bindValue(':descriproceso', $_POST['descriproceso']);
        $s->bindValue(':materiasusadas', $_POST['materiasusadas']);
        $s->bindValue(':tratamiento', $_POST['tratamiento']);
        $s->bindValue(':Caracdescarga', $_POST['Caracdescarga']);
        $s->bindValue(':receptor', $_POST['receptor']);
        $s->bindValue(':estrategia', $_POST['estrategia']);
        $s->bindValue(':numuestras', $_POST['numuestras']);
        $s->bindValue(':observaciones', $_POST['observaciones']);
        $s->bindValue(':tipomediciones', $_POST['tipomediciones']);
        //$s->bindValue(':proposito', $_POST['proposito']);
        $s->execute();

        $sql='UPDATE muestreosaguatbl SET
              fechamuestreo=:fechamuestreo,
              identificacion=:identificacion,
              temperatura=:temperatura,
              caltermometro=:caltermometro,
              pH=:pH,
              conductividad=:conductividad,
              responsable=:responsable,
              mflotante=:mflotante,
              olor=:olor,
              color=:color,
              turbiedad=:turbiedad,
              GyAvisual=:GyAvisual,
              burbujas=:burbujas
              WHERE generalaguaidfk=:generalaguaidfk';
        $s=$pdo->prepare($sql);
        $s->bindValue(':generalaguaidfk', $_POST['id']);
        $s->bindValue(':fechamuestreo', $_POST['fechamuestreo']);
        $s->bindValue(':identificacion', $_POST['identificacion']);
        $s->bindValue(':temperatura', $_POST['temperatura']);
        $s->bindValue(':caltermometro', $_POST['caltermometro']);
        $s->bindValue(':pH', $_POST['pH']);
        $s->bindValue(':conductividad', $_POST['conductividad']);
        $s->bindValue(':responsable', $_POST['responsable']);
        $s->bindValue(':mflotante', $_POST['mflotante']);
        $s->bindValue(':olor', $_POST['olor']);
        $s->bindValue(':color', $_POST['color']);
        $s->bindValue(':turbiedad', $_POST['turbiedad']);
        $s->bindValue(':GyAvisual', $_POST['GyAvisual']);
        $s->bindValue(':burbujas', $_POST['burbujas']);
        $s->execute();

        if(isset($_POST['fechamuestreofin']) AND $_POST['fechamuestreofin'] !== ""){
          $sql='UPDATE muestreosaguatbl SET
              fechamuestreofin=:fechamuestreofin
              WHERE generalaguaidfk=:generalaguaidfk';
          $s=$pdo->prepare($sql);
          $s->bindValue(':generalaguaidfk', $_POST['id']);
          $s->bindValue(':fechamuestreofin', $_POST['fechamuestreofin']);
          $s->execute();
        }

        $pdo->commit();
      }catch (PDOException $e){
        $pdo->rollback();
        $mensaje='Hubo un error al tratar de actulizar la medicion. Favor de intentar nuevamente.'.$e;
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
        exit();
      }
      $cantidad = 1;
      if($_POST['tipomediciones'] === '8'){
        $cantidad = 4;
      }elseif($_POST['tipomediciones'] === '16' OR $_POST['tipomediciones'] === '24'){
        $cantidad = 6;
      }
    }else{ // cierre de if(!isset($_POST['regreso']))
      $cantidad = intval($_POST['cantidad']);
    }
    if($cantidad === 1){
      if($_POST['accion'] == 'volver' AND isset($_POST['coms'])){
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
        try
        {
          $sql='SELECT * FROM generalesaguatbl
             INNER JOIN muestreosaguatbl ON generalesaguatbl.id=muestreosaguatbl.generalaguaidfk
             WHERE generalesaguatbl.id = :id';
          $s=$pdo->prepare($sql); 
          $s->bindValue(':id',$_POST['id']);
          $s->execute();
          $linea = $s->fetch();

          $sql='SELECT descargaen, uso FROM nom01maximostbl WHERE id=:id';
          $s=$pdo->prepare($sql); 
          $s->bindValue(':id', $linea["nom01maximosidfk"]);
          $s->execute();
          $nom01maximos = $s->fetch();
        }catch (PDOException $e){
          $mensaje='Hubo un error extrayendo la información de reconocimiento inicial.';
          include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
          exit();
        }
       
        $descargaen = getMaximos();
        $egiro = getEGiro($linea["ordenaguaidfk"]);
        $valores = array("empresagiro" => $egiro,
                        "descargaen" => $nom01maximos["descargaen"],
                        "uso" => $nom01maximos["uso"],
                        "numedicion" => $linea["numedicion"],
                        "lugarmuestreo" => $linea["lugarmuestreo"],
                        "descriproceso" => $linea["descriproceso"],
                        "tipomediciones" => $linea["tipomediciones"],
                        //"proposito" => $linea["proposito"],
                        "materiasusadas" => $linea["materiasusadas"],
                        "tratamiento" => $linea["tratamiento"],
                        "Caracdescarga" => $linea["Caracdescarga"],
                        "receptor" => $linea["receptor"],
                        "estrategia" => $linea["estrategia"],
                        "numuestras" => $linea["numuestras"],
                        "observaciones" => $linea["observaciones"],
                        "fechamuestreo" => $linea["fechamuestreo"],
                        "fechamuestreofin" => $linea["fechamuestreofin"],
                        "identificacion" => $linea["identificacion"],
                        "temperatura" => $linea["temperatura"],
                        "caltermometro" => $linea["caltermometro"],
                        "pH" => $linea["pH"],
                        "conductividad" => $linea["conductividad"],
                        "responsable" => $linea["responsable"],
                        "mflotante" => $linea["mflotante"],
                        "olor" => $linea["olor"],
                        "color" => $linea["color"],
                        "turbiedad" => $linea["turbiedad"],
                        "GyAvisual" => $linea["GyAvisual"],
                        "burbujas" => $linea["burbujas"]);
        $pestanapag='Editar medicion';
        $titulopagina='Editar medicion';
        $accion='';
        $boton = 'salvar';
        $regreso = 1;
        include 'formacapturarmeds.html.php';
        exit();
      } //cierre de if($_POST['accion'] == 'volvercoms')

      if(isset($_POST['regreso']) AND $_POST['regreso'] === '2'){
        formularioParametros($_POST['id'], intval($_POST['cantidad']), $_POST['idparametro'], json_decode($_POST['valores'], TRUE), json_decode($_POST['parametros'], TRUE), json_decode($_POST['adicionales'], TRUE), 1);
      }
      formularioParametros($_POST['id'], $cantidad, "", "", "", "", 1);
    }else{ //cierre de if($cantidad === 1)
      if(isset($_POST['regreso']) AND $_POST['regreso'] === '2'){
        $mcompuestas = json_decode($_POST['mcompuestas'], TRUE);
        formularioMediciones($id, $cantidad, $mcompuestas, 1);
      }else{
        formularioMediciones($id, $cantidad, "", 1);
      }
    }
    exit();
  }

/**************************************************************************************************/
/* Formulario de parametros de una medicion una orden de trabajo */
/**************************************************************************************************/
  if (isset($_POST['accion']) and $_POST['accion']=='parametros')
  {
    $_SESSION['accion'] = 'parametros';
    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
    $host     = $_SERVER['HTTP_HOST'];
    $script   = $_SERVER['SCRIPT_NAME'];
    $params   = $_SERVER['QUERY_STRING'];
    $currentUrl = $protocol . '://' . $host . $script . '?' . $params;
    $_SESSION['url'] = $currentUrl;

    if(isset($_POST['regreso']) AND $_POST['regreso'] === '2'){
		  formularioParametros($_POST['id'], $_POST['cantidad'], $_POST['idparametro'], json_decode($_POST['valores'],TRUE), json_decode($_POST['parametros'],TRUE), json_decode($_POST['adicionales'],TRUE), $_POST['regreso'], $_POST['accionparam']);
    }else{
  		$cantidad = 1;
  		if($_POST['tipomedicion'] === '8'){
  			$cantidad = 4;
  		}else if($_POST['tipomedicion'] === '16' || $_POST['tipomedicion'] === '24'){
  			$cantidad = 6;
  		}
    }
    if(isset($_POST['idparametro']) AND $_POST['idparametro'] !== ""){
		  formularioParametros($_POST['id'], $cantidad, $_POST['idparametro']);
    }else{
      formularioParametros($_POST['id'], $cantidad);
    }
    exit();
  }

/**************************************************************************************************/
/* Dar por terminada una orden */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='Enviar')
  {
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    if(isset($_POST['terminada'])){
      try
      {
        $sql='UPDATE ordenestbl SET
          fechafin=CURDATE()
          WHERE id=:id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id',$_POST['ot']);
        $s->execute();
      }
      catch(PDOException $e)
      {
        $mensaje='Hubo un error al tratar de terminar la orden. Intentar nuevamente y avisar de este error a sistemas.';
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
        exit(); 
      }
    }else{
      try
      {
        $sql='UPDATE ordenestbl SET
          fechafin = NULL
          WHERE id=:id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id',$_POST['ot']);
        $s->execute();
      }
      catch(PDOException $e)
      {
        $mensaje='Hubo un error al tratar de terminar la orden. Intentar nuevamente y avisar de este error a sistemas.';
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
        exit(); 
      }
    }
    verMeds($_POST['ot']);
  }

/**************************************************************************************************/
/* Ir a subir croquis */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='croquis')
  {
    $_SESSION['post'] = $_POST;
    header('Location: http://'.$_SERVER['HTTP_HOST'].str_replace('/generales','',$_SERVER['REQUEST_URI']).'croquis');
    exit();
  }

/**************************************************************************************************/
/* Función para ver mediciones de una orden de trabajo */
/**************************************************************************************************/
verMeds($_SESSION['ot']);

/**************************************************************************************************/
/* Función para ver mediciones de una orden de trabajo */
/**************************************************************************************************/
  function verMeds($ot = ""){
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	$_SESSION['OT'] = $ot;
	try{
		$sql='SELECT ot, fechafin
			FROM ordenestbl
			WHERE id = :id';
		$s=$pdo->prepare($sql); 
		$s->bindValue(':id',$ot);
		$s->execute();
		$nombreot = $s->fetch();

		$sql='SELECT id, numedicion, lugarmuestreo, descriproceso, tipomediciones
			FROM generalesaguatbl
			WHERE ordenaguaidfk = :id';
		$s=$pdo->prepare($sql); 
		$s->bindValue(':id',$ot);
		$s->execute();
	}catch (PDOException $e){
		$mensaje='Hubo un error extrayendo la lista de ordenes de agua.'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}

	foreach ($s as $linea){
		try   
		{
			$sql='SELECT parametrostbl.id
				FROM parametrostbl
				INNER JOIN muestreosaguatbl ON parametrostbl.muestreoaguaidfk = muestreosaguatbl.id
				WHERE muestreosaguatbl.generalaguaidfk = :id';
			$p=$pdo->prepare($sql); 
			$p->bindValue(':id',$linea['id']);
			$p->execute();
		}catch (PDOException $e){
			$mensaje='Hubo un error extrayendo el parametro.'.$e;
			include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
			exit();
		}
		$medsagua[]=array('id'=>$linea['id'],
						'numedicion'=>$linea['numedicion'],
						'lugarmuestreo'=>$linea['lugarmuestreo'],
						'descriproceso'=>$linea['descriproceso'],
						'tipomediciones'=>$linea['tipomediciones'],
						'parametros'=> ($params = $p->fetch()) ? $params['id'] : "");
	}
	if($params = $s->fetch()){
		$paramid = $params['id'];
	}
	include 'formameds.html.php';
	exit();
  }

/**************************************************************************************************/
/* Función para obtener valores máximos */
/**************************************************************************************************/
  function getMaximos(){
  	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  	try{
  		$s=$pdo->prepare('SELECT descargaen FROM nom01maximostbl group by descargaen;');
  		$s->execute();
  		foreach ($s as $value) {
  			$maximos[] = $value['descargaen'];
  		}
  		return $maximos;
  	}catch (PDOException $e){
  		return FALSE;
  	}
  }

/**************************************************************************************************/
/* Función para obtener el giro de la empresa */
/**************************************************************************************************/
  function getEGiro($ot){
  	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  	try   
  	{
  		$sql='SELECT Giro_Empresa
  			FROM clientestbl
  			WHERE Numero_Cliente = (SELECT clienteidfk
  									FROM ordenestbl
  									WHERE id = :id)';
  		$s=$pdo->prepare($sql); 
  		$s->bindValue(':id',$ot);
  		$s->execute();
  		$e = $s->fetch();
  		return $e['Giro_Empresa'];
  	}catch (PDOException $e){
  		return "";
  	}
  }

/**************************************************************************************************/
/* Función para obtener el responsable de la medición */
/**************************************************************************************************/
	function getResponsable($ot){
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
		try{
			$sql='SELECT generalesaguatbl.id, muestreosaguatbl.responsable
				FROM  generalesaguatbl 
				INNER JOIN muestreosaguatbl ON generalesaguatbl.id = muestreosaguatbl.generalaguaidfk
				WHERE  generalesaguatbl.ordenaguaidfk = :id
				ORDER BY id ASC LIMIT 1';
			$s=$pdo->prepare($sql); 
			$s->bindValue(':id',$ot);
			$s->execute();
			$e = $s->fetch();
			return ($e['responsable'])? $e['responsable'] : "";
		}catch (PDOException $e){
			return "";
		}
	}