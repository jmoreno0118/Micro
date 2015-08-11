<?php
 /********** Norma 001 **********/
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/funcionesecol.inc.php';
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/ayudas.inc.php';

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
  if(isset($_POST['regreso']) AND $_POST['regreso'] === '2')
  {
    $cantidad = intval($_POST['cantidad']);
    $id = $_POST['id'];
    $mcompuestas = json_decode($_POST['mcompuestas'], TRUE);
    $muestreoid = $_POST['muestreoid'];
  }
  else
  {
    try
    {
      $pdo->beginTransaction();

      setAcreditacion($_POST['acreditacion']['id']);

      setGiroEmpresa($_POST['id'], $_POST['empresagiro']);

      setSignatario($_POST['signatario']);

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
      $s->bindValue(':estrategia', $_POST['estrategia']);
      $s->bindValue(':numuestras', $_POST['numuestras']);
      $s->bindValue(':observaciones', $_POST['observaciones']);
      $s->bindValue(':tipomediciones', $_POST['tipomediciones']);
      $s->execute();
      $id=$pdo->lastInsertid();

      $correccion = getCorreccion($_POST['termometro']);
      $calibracion1 = getCalibracion1($correccion, $_POST['temperatura']);
      $calibracion2 = getCalibracion2($correccion, $_POST['temperatura']);

      $sql='INSERT INTO muestreosaguatbl SET
            generalaguaidfk=:generalaguaidfk,
            fechamuestreo=:fechamuestreo,
            identificacion=:identificacion,
            temperatura=:temperatura,
            caltermometro=:caltermometro,
            caltermometro2=:caltermometro2,
            pH=:pH,
            conductividad=:conductividad,
            mflotante=:mflotante,
            equipoidfk=:equipoidfk,
            correccionterm=:correccionterm';
      $s=$pdo->prepare($sql);
      $s->bindValue(':generalaguaidfk', $id);
      $s->bindValue(':fechamuestreo', $_POST['fechamuestreo']);
      $s->bindValue(':identificacion', $_POST['identificacion']);
      $s->bindValue(':temperatura', $_POST['temperatura']);
      $s->bindValue(':caltermometro', $calibracion1);
      $s->bindValue(':caltermometro2', $calibracion2);
      $s->bindValue(':pH', $_POST['pH']);
      $s->bindValue(':conductividad', $_POST['conductividad']);
      $s->bindValue(':mflotante', $_POST['mflotante']);
      $s->bindValue(':equipoidfk', $_POST['termometro']);
      $s->bindValue(':correccionterm', $correccion);
      $s->execute();
      $muestreoid=$pdo->lastInsertid();

      setResponsables($id);

      if(isset($_POST['fechamuestreofin']) AND $_POST['fechamuestreofin'] !== "")
      {
        $sql='UPDATE muestreosaguatbl SET
            fechamuestreofin=:fechamuestreofin
            WHERE generalaguaidfk=:generalaguaidfk';
        $s=$pdo->prepare($sql);
        $s->bindValue(':generalaguaidfk', $muestreoid);
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
    if($_POST['tipomediciones'] === '4')
    {
        $cantidad = 2;
    }else if($_POST['tipomediciones'] === '8')
    {
        $cantidad = 4;
    }else if($_POST['tipomediciones'] === '12')
    {
        $cantidad = 6;
    }
  }
  fijarAccionUrl('guardar');

  if($cantidad === 1)
  {
    formularioParametros($id, $muestreoid, $cantidad, "", "", "", "", 1);
  }
  formularioMediciones($id, $muestreoid, $cantidad, $mcompuestas, 2);
}

/**************************************************************************************************/
/* Agregar una nueva medicion a una orden de trabajo */
/**************************************************************************************************/
if (isset($_GET['accion']) and $_GET['accion']=='capturar')
{
  fijarAccionUrl('capturar');

	$id = $_SESSION['ot'];
	$pestanapag = 'Agrega medición';
	$titulopagina = 'Agregar una nueva medición';
	$boton = 'guardar';
	$descargaen = getMaximos();
  $signatarios = getSignatarios();
  $muestreadores = getMuestradores();
  $acreditaciones = getAcreditaciones();
  $termometros = getEquipos("Term&oacute;metro", $id);
	if(isset($_POST['valores']))
  {
		$valores = json_decode($_POST['valores'],TRUE);
	}
  else
  {
		$valores = array("empresagiro" => getEGiro($id),
				             "descargaen" => "",
				             "uso" => "",
                     "signatario" => getSignatario($id),
				             "responsable" => getResponsables($id),
                     "acreditacion" => getAcreditacion($id)
              );
	}
	include 'formacapturarmeds.html.php';
	exit();
}

/**************************************************************************************************/
/* Editar reconocimiento inicial de una orden de trabajo */
/**************************************************************************************************/
if((isset($_POST['accion']) and $_POST['accion']=='editar') OR (isset($_POST['accion']) and $_POST['accion']=='ver') OR (isset($_POST['accion']) AND $_POST['accion'] == 'volver' AND isset($_POST['meds'])))
{
  fijarAccionUrl('editar');

	$id = $_POST['id'];
  if(isset($_POST['valores']))
  {
    $valores = json_decode($_POST['valores'],TRUE);
  }
  else
  {
  	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  	try
  	{
  		$sql='SELECT * FROM generalesaguatbl
  		INNER JOIN muestreosaguatbl ON generalesaguatbl.id=muestreosaguatbl.generalaguaidfk
  		WHERE generalesaguatbl.id = :id';
  		$s=$pdo->prepare($sql); 
  		$s->bindValue(':id', $id);
  		$s->execute();
  		$linea = $s->fetch();

  		$sql='SELECT descargaen, uso FROM nom01maximostbl WHERE id=:id';
  		$s=$pdo->prepare($sql); 
  		$s->bindValue(':id', $linea["nom01maximosidfk"]);
  		$s->execute();
  		$nom01maximos = $s->fetch();
  	}
    catch (PDOException $e)
    {
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
			           "estrategia" => $linea["estrategia"],
			           "numuestras" => $linea["numuestras"],
			           "observaciones" => $linea["observaciones"],
			           "fechamuestreo" => $linea["fechamuestreo"],
                 "fechamuestreofin" => $linea["fechamuestreofin"],
			           "identificacion" => $linea["identificacion"],
			           "temperatura" => $linea["temperatura"],
			           "pH" => $linea["pH"],
			           "conductividad" => $linea["conductividad"],
                 "nombresignatario" => getNombreSignatario($linea["ordenaguaidfk"]),
                 "signatario" => getSignatario($linea["ordenaguaidfk"]),
			           "responsable" => getResponsables($linea["ordenaguaidfk"], $id),
			           "mflotante" => $linea["mflotante"],
                 "acreditacion" => getAcreditacion($linea["ordenaguaidfk"]),
                 "termometro" => $linea["equipoidfk"]);
	}
  $signatarios = getSignatarios();
  $muestreadores = getMuestradores();
  $acreditaciones = getAcreditaciones();
  $termometros = getEquipos("Term&oacute;metro", $_SESSION['ot']);
  $descargaen = getMaximos();
	$pestanapag='Editar medicion';
	$titulopagina='Editar medicion';
	$boton = 'salvar';
  if($_POST['accion']=='ver')
    $boton = 'siguiente';
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
  }
  catch (PDOException $e)
  {
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

      $sql='DELETE FROM adicionalestbl WHERE parametroidfk IN (SELECT parametrostbl.id FROM parametrostbl INNER JOIN muestreosaguatbl ON muestreosaguatbl.id=parametrostbl.muestreoaguaidfk WHERE muestreosaguatbl.generalaguaidfk= :id)';
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

      $sql='DELETE FROM documentos001tbl WHERE generalaguaidfk=:id';
      $s=$pdo->prepare($sql);
      $s->bindValue(':id',$_POST['id']);
      $s->execute(); 

      $sql='DELETE FROM responsables WHERE muestreoaguaidfk IN (SELECT id FROM muestreosaguatbl WHERE generalaguaidfk = :id)';
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
  }
  catch (PDOException $e)
  {
      $pdo->rollback();
      $errorlink = 'http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/generales';
      $errornav = 'Volver a norma 001';
      $mensaje='Hubo un error borrando la medición. Intente de nuevo. '.$e;
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
  }
  verMeds($_POST['ot']);
}

/**************************************************************************************************/
/* Guardar la edición de una orden de trabajo */
/**************************************************************************************************/
if((isset($_POST['accion']) AND $_POST['accion'] == 'salvar') OR (isset($_POST['accion']) AND $_POST['accion'] == 'siguiente') OR (isset($_POST['accion']) AND $_POST['accion'] == 'volver' AND isset($_POST['coms'])))
{
  /*$mensaje='Error Forzado 1.';
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
  exit();*/

  fijarAccionUrl('salvar');

  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  $id = $_POST['id'];
  if(!isset($_POST['regreso']))
  { /* If regreso */
      
      if($_POST['accion'] !== 'siguiente')
      { /* If siguiente */
          try
          {
              $pdo->beginTransaction();
             
              setAcreditacion($_POST['acreditacion']['id']);

              setGiroEmpresa($_POST['id'], $_POST['empresagiro']);

              if(strcmp($_POST['signatario'], '') !== 0)
              {
                setSignatario($_POST['signatario']);
              }

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
              $s->bindValue(':estrategia', $_POST['estrategia']);
              $s->bindValue(':numuestras', $_POST['numuestras']);
              $s->bindValue(':observaciones', $_POST['observaciones']);
              $s->bindValue(':tipomediciones', $_POST['tipomediciones']);
              $s->execute();

              $correccion = getCorreccion($_POST['termometro']);
              $calibracion1 = getCalibracion1($correccion, $_POST['temperatura']);
              $calibracion2 = getCalibracion2($correccion, $_POST['temperatura']);

              $sql='UPDATE muestreosaguatbl SET
                    fechamuestreo=:fechamuestreo,
                    identificacion=:identificacion,
                    temperatura=:temperatura,
                    caltermometro=:caltermometro,
                    caltermometro2=:caltermometro2,
                    pH=:pH,
                    conductividad=:conductividad,
                    mflotante=:mflotante,
                    equipoidfk=:equipoidfk,
                    correccionterm=:correccionterm
                    WHERE generalaguaidfk=:generalaguaidfk';
              $s=$pdo->prepare($sql);
              $s->bindValue(':generalaguaidfk', $_POST['id']);
              $s->bindValue(':fechamuestreo', $_POST['fechamuestreo']);
              $s->bindValue(':identificacion', $_POST['identificacion']);
              $s->bindValue(':temperatura', $_POST['temperatura']);
              $s->bindValue(':caltermometro', $calibracion1);
              $s->bindValue(':caltermometro2', $calibracion2);
              $s->bindValue(':pH', $_POST['pH']);
              $s->bindValue(':conductividad', $_POST['conductividad']);
              $s->bindValue(':mflotante', $_POST['mflotante']);
              $s->bindValue(':equipoidfk', ($_POST['termometro'] !== '') ? $_POST['termometro'] : 0);
              $s->bindValue(':correccionterm', $correccion);
              $s->execute();

              setResponsables($_POST['id']);

              if(isset($_POST['fechamuestreofin']) AND $_POST['fechamuestreofin'] !== "")
              {
                $sql='UPDATE muestreosaguatbl SET
                    fechamuestreofin=:fechamuestreofin
                    WHERE generalaguaidfk=:generalaguaidfk';
                $s=$pdo->prepare($sql);
                $s->bindValue(':generalaguaidfk', $_POST['id']);
                $s->bindValue(':fechamuestreofin', $_POST['fechamuestreofin']);
                $s->execute();
              }

              $pdo->commit();
          }
          catch (PDOException $e)
          {
            $pdo->rollback();
            $mensaje='Hubo un error al tratar de actulizar la medicion. Favor de intentar nuevamente.'.$e;
            include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
            exit();
          }
      } /* If siguiente */

      $cantidad = 1;
      if($_POST['tipomediciones'] === '4')
      {
          $cantidad = 2;
      }else if($_POST['tipomediciones'] === '8')
      {
          $cantidad = 4;
      }else if($_POST['tipomediciones'] === '12')
      {
          $cantidad = 6;
      }
  }  /* If regreso */
  else
  { // cierre de if(!isset($_POST['regreso']))
    $cantidad = intval($_POST['cantidad']);
  }
  //var_dump($cantidad);
  //exit();
  if($cantidad === 1)
  { /* If cantidad = 1 */
      if($_POST['accion'] == 'volver' AND isset($_POST['coms']))
      {  /* If volvercoms */
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
        try
        {
          $sql='SELECT *, muestreosaguatbl.id as "muestreoid" FROM generalesaguatbl
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
        }
        catch (PDOException $e)
        {
          $mensaje='Hubo un error extrayendo la información de reconocimiento inicial.';
          include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
          exit();
        }
       
        $signatarios = getSignatarios();
        $muestreadores = getMuestradores();
        $descargaen = getMaximos();
        $egiro = getEGiro($linea["ordenaguaidfk"]);
        $valores = array("empresagiro" => getEGiro($linea["ordenaguaidfk"]),
                   "descargaen" => $nom01maximos["descargaen"],
                   "uso" => $nom01maximos["uso"],
                   "numedicion" => $linea["numedicion"],
                   "lugarmuestreo" => $linea["lugarmuestreo"],
                   "descriproceso" => $linea["descriproceso"],
                   "tipomediciones" => $linea["tipomediciones"],
                   "materiasusadas" => $linea["materiasusadas"],
                   "tratamiento" => $linea["tratamiento"],
                   "Caracdescarga" => $linea["Caracdescarga"],
                   "estrategia" => $linea["estrategia"],
                   "numuestras" => $linea["numuestras"],
                   "observaciones" => $linea["observaciones"],
                   "fechamuestreo" => $linea["fechamuestreo"],
                   "fechamuestreofin" => $linea["fechamuestreofin"],
                   "identificacion" => $linea["identificacion"],
                   "temperatura" => $linea["temperatura"],
                   "pH" => $linea["pH"],
                   "conductividad" => $linea["conductividad"],
                   "nombresignatario" => getNombreSignatario($linea["ordenaguaidfk"]),
                   "signatario" => getSignatario($linea["ordenaguaidfk"]),
                   "responsable" => getResponsables($linea["ordenaguaidfk"], $id),
                   "mflotante" => $linea["mflotante"]);
        $pestanapag='Editar medicion';
        $titulopagina='Editar medicion';
        $accion='';
        $boton = 'salvar';
        $regreso = 1;
        include 'formacapturarmeds.html.php';
        exit();
      } /* If volvercoms */

      if(isset($_POST['regreso']) AND $_POST['regreso'] === '2')
      {
        formularioParametros($_POST['id'], $_POST['muestreoid'], intval($_POST['cantidad']), $_POST['idparametro'], json_decode($_POST['valores'], TRUE), json_decode($_POST['parametros'], TRUE), json_decode($_POST['adicionales'], TRUE), 1);
      }
      formularioParametros($_POST['id'], $linea["muestreoid"], $cantidad, "", "", "", "", 1);
  } /* If cantidad = 1 */
  else
  { /* Else cantidad != 1 */
    if(isset($_POST['regreso']) AND $_POST['regreso'] === '2')
    {
      $mcompuestas = json_decode($_POST['mcompuestas'], TRUE);
      formularioMediciones($id, $_POST['muestreoid'], $cantidad, $mcompuestas, 1);
    }
    else
    {
      try
      {
        $sql='SELECT muestreosaguatbl.id as "muestreoid"
              FROM muestreosaguatbl
              WHERE generalaguaidfk = :id';
        $s=$pdo->prepare($sql); 
        $s->bindValue(':id',$_POST['id']);
        $s->execute();
        $linea = $s->fetch();
      }
      catch (PDOException $e)
      {
        $mensaje='Hubo un error extrayendo la información de reconocimiento inicial.';
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
        exit();
      }

      formularioMediciones($id, $linea["muestreoid"], $cantidad, "", 1);
    }
  } /* Else cantidad != 1 */
  exit();
}

/**************************************************************************************************/
/* Formulario de parametros de una medicion una orden de trabajo */
/**************************************************************************************************/
if (isset($_POST['accion']) and $_POST['accion']=='parametros')
{
  fijarAccionUrl('parametros');

  if(isset($_POST['regreso']) AND $_POST['regreso'] === '2')
  {
	  formularioParametros($_POST['id'], $_POST['muestreoid'], $_POST['cantidad'], $_POST['idparametro'], json_decode($_POST['valores'],TRUE), json_decode($_POST['parametros'],TRUE), json_decode($_POST['adicionales'],TRUE), $_POST['regreso'], $_POST['accionparam']);
  }else{
  		$cantidad = 1;
      if($_POST['tipomedicion'] === '4')
      {
          $cantidad = 2;
      }
      else if($_POST['tipomedicion'] === '8')
      {
          $cantidad = 4;
      }
      else if($_POST['tipomedicion'] === '12')
      {
          $cantidad = 6;
      }
  }
  if(isset($_POST['idparametro']) AND $_POST['idparametro'] !== "")
  {
	  formularioParametros($_POST['id'], $_POST['muestreoid'], $cantidad, $_POST['idparametro']);
  }
  else
  {
    formularioParametros($_POST['id'], $_POST['muestreoid'], $cantidad);
  }
}

/**************************************************************************************************/
/* Formulario de parametros de una medicion una orden de trabajo */
/**************************************************************************************************/
if (isset($_POST['accion']) and $_POST['accion']=='captura siralab')
{
  fijarAccionUrl('captura siralab');

  if(isset($_POST['regreso']) AND $_POST['regreso'] === '2')
  {
    formularioSiralab($_POST['id'], json_decode($_POST['valores'], TRUE), json_decode($_POST['mcompuestas'], TRUE), $_POST['cantidad'], $_POST['regreso'], $_POST['accion']);
  }
  else
  {
      $cantidad = 1;
      if($_POST['tipomedicion'] === '4')
      {
          $cantidad = 2;
      }else if($_POST['tipomedicion'] === '8')
      {
          $cantidad = 4;
      }else if($_POST['tipomedicion'] === '12')
      {
          $cantidad = 6;
      }
  }
  formularioSiralab($_POST['muestreoid'], '', '', $cantidad, 0);
}

/**************************************************************************************************/
/* Dar por terminada una orden */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='Enviar')
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  if(isset($_POST['terminada']))
  {
      try
      {
        $sql='UPDATE ordenestbl SET
          fechafin = CURDATE()
          WHERE id = :id';
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
          fechafin = NULL,
          fecharevision  = NULL
          WHERE id = :id';
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
  $_SESSION['ot'] = $_POST['ot'];
  header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/');
  exit();
}

/**************************************************************************************************/
/* Dar visto bueno a una orden */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='Vo. Bo.')
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
      if (isset($_POST['comentario']) AND $_POST['comentario'] !== '')
      {
        setObservacion($pdo, 'NOM 001', $_POST['ot'], $_POST['comentario']);
      }
      
      $sql='UPDATE ordenestbl SET
        fecharevision = CURDATE()
        WHERE id = :id';
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
  $_SESSION['ot'] = $_POST['ot'];
  header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/');
  exit();
}

/**************************************************************************************************/
/* Poner comentarios a una orden */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='Comentar y Regresar Orden')
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
      if (isset($_POST['comentario']) AND $_POST['comentario'] !== '')
      {
        setObservacion($pdo, 'NOM 001', $_POST['ot'], $_POST['comentario']);
      }

      $sql='UPDATE ordenestbl SET
            fechafin = NULL,
            fecharevision  = NULL
            WHERE id = :id';
      $s=$pdo->prepare($sql);
      $s->bindValue(':id', $_POST['ot']);
      $s->execute();
  }
  catch(PDOException $e)
  {
    $mensaje='Hubo un error al tratar de terminar la orden. Intentar nuevamente y avisar de este error a sistemas.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  $_SESSION['ot'] = $_POST['ot'];
  header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/');
  exit();
}

/**************************************************************************************************/
/* Ir a subir documentos */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='documentos')
{
  $_SESSION['post'] = $_POST;
  header('Location: http://'.$_SERVER['HTTP_HOST'].'/reportes/nom001/documentos');
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
	try
  {
		$sql='SELECT ot, fechafin
			FROM ordenestbl
			WHERE id = :id';
		$s=$pdo->prepare($sql); 
		$s->bindValue(':id',$ot);
		$s->execute();
		$nombreot = $s->fetch();

		$sql='SELECT generalesaguatbl.id, numedicion, lugarmuestreo, descriproceso, tipomediciones, muestreosaguatbl.id as "muestreoid"
			FROM generalesaguatbl
      INNER JOIN muestreosaguatbl ON generalesaguatbl.id = muestreosaguatbl.generalaguaidfk
			WHERE ordenaguaidfk = :id';
		$s=$pdo->prepare($sql); 
		$s->bindValue(':id',$ot);
		$s->execute();

    $sql='SELECT id, observacion, fecha, supervisor
      FROM observacionestbl
      WHERE ordenesidfk = :id
      AND estudio = "NOM 001"
      ORDER BY id DESC';
    $c=$pdo->prepare($sql); 
    $c->bindValue(':id',$ot);
    $c->execute();
    $comentarios = $c->fetchAll();

	}
  catch (PDOException $e)
  {
		$mensaje='Hubo un error extrayendo la lista de ordenes de agua.'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}

	foreach ($s as $linea)
  {
  		try   
  		{
  			$sql='SELECT parametrostbl.id
  				FROM parametrostbl
  				INNER JOIN muestreosaguatbl ON parametrostbl.muestreoaguaidfk = muestreosaguatbl.id
  				WHERE muestreosaguatbl.generalaguaidfk = :id';
  			$p=$pdo->prepare($sql); 
  			$p->bindValue(':id',$linea['id']);
  			$p->execute();
  		}
      catch (PDOException $e)
      {
  			$mensaje='Hubo un error extrayendo el parametro.'.$e;
  			include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
  			exit();
  		}
  		$medsagua[]=array('id'=>$linea['id'],
  						'numedicion'=>$linea['numedicion'],
  						'lugarmuestreo'=>$linea['lugarmuestreo'],
  						'descriproceso'=>$linea['descriproceso'],
  						'tipomediciones'=>$linea['tipomediciones'],
  						'parametros'=> ($params = $p->fetch()) ? $params['id'] : "",
              'muestreoid'=> $linea['muestreoid']);
	}

	if($params = $s->fetch())
  {
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
	}
  catch (PDOException $e)
  {
		return "";
	}
}

/**************************************************************************************************/
/* Función para obtener el responsable de la medición */
/**************************************************************************************************/
function getResponsables($ot, $id = ''){
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try{
  		$sql='SELECT responsables.id, muestreadoridfk, nombre, ap, am
            FROM responsables
            INNER JOIN muestreosaguatbl ON muestreosaguatbl.id = responsables.muestreoaguaidfk
            INNER JOIN generalesaguatbl ON muestreosaguatbl.generalaguaidfk = generalesaguatbl.id
            WHERE generalesaguatbl.ordenaguaidfk = :ot AND generalesaguatbl.id = :id';
      if($id != '')
      {
          $placeholders[':id'] = $id;
      }
      else
      {
          $sql1 = 'SELECT id
            FROM generalesaguatbl
            WHERE ordenaguaidfk = :ot
            ORDER BY id DESC';
          $s=$pdo->prepare($sql1); 
          $s->bindValue(':ot', $ot);
          $s->execute();
          $e = $s->fetch();

          $placeholders[':id'] = $e['id'];
      }
  		$s=$pdo->prepare($sql);
      $placeholders[':ot'] = $ot;
      $s->execute($placeholders);

      $responsables = array();
  		foreach ($s as $value)
      {
        $responsables[] = array('id' => $value['id'],
                                'responsable' => $value['muestreadoridfk'],
                                'nombre' => $value['nombre'].' '.$value['ap'].' '.$value['am']);
      }
  		return $responsables;
	}
  catch (PDOException $e)
  {
		return array();
	}
}

/**************************************************************************************************/
/* Función para obtener el responsable de la medición */
/**************************************************************************************************/
function getMuestradores(){
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try
    {
        $sql='SELECT muestreadorestbl.id, nombre, ap, am
          FROM  muestreadorestbl
          INNER JOIN estudiosmuestreadortbl ON muestreadorestbl.id = estudiosmuestreadortbl.muestreadoridfk
          INNER JOIN muestreadorrepresentantetbl r ON  r.muestreadoridfk = muestreadorestbl.id
          WHERE estudiosmuestreadortbl.estudio = "NOM 001"AND
          r.representanteidfk = (SELECT representanteidfk FROM ordenestbl WHERE id = :id)';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id',$_SESSION['ot']);
        $s->execute();
        $signatarios = '';
        foreach ($s as $value) {
          $signatarios[$value['id']] = $value['nombre'].' '.$value['ap'].' '.$value['am'];
        }
        return $signatarios;
    }
    catch (PDOException $e)
    {
      return "";
    }
}

/**************************************************************************************************/
/* Función para obtener el listado de signatarios */
/**************************************************************************************************/
function getSignatarios(){
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try
    {
        $sql='SELECT muestreadorestbl.id, nombre, ap, am
          FROM  muestreadorestbl
          INNER JOIN estudiossignatariotbl ON muestreadorestbl.id = estudiossignatariotbl.muestreadoridfk
          INNER JOIN muestreadorrepresentantetbl r ON  r.muestreadoridfk = muestreadorestbl.id 
          WHERE  muestreadorestbl.signatario = 1 AND estudiossignatariotbl.estudio = "NOM 001" AND
          r.representanteidfk = (SELECT representanteidfk FROM ordenestbl WHERE id = :id)';
        $s=$pdo->prepare($sql); 
        $s->bindValue(':id',$_SESSION['ot']);
        $s->execute();
        $signatarios = '';
        foreach ($s as $value) {
          $signatarios[$value['id']] = $value['nombre'].' '.$value['ap'].' '.$value['am'];
        }
        return $signatarios;
    }
    catch (PDOException $e)
    {
      return "";
    }
}

/**************************************************************************************************/
/* Función para obtener el signatario de la orden */
/**************************************************************************************************/
function getNombreSignatario($ot){
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try
    {
        $sql='SELECT signatarionombre, signatarioap, signatarioam
          FROM  ordenestbl
          WHERE id=:id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $ot);
        $s->execute();
        $value = $s->fetch();

        return $value['signatarionombre'].' '.$value['signatarioap'].' '.$value['signatarioam'];
    }
    catch (PDOException $e)
    {
      return "";
    }
}

/**************************************************************************************************/
/* Función para obtener el signatario de la orden */
/**************************************************************************************************/
function getSignatario($ot){
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try
    {
        $sql='SELECT signatarioidfk
          FROM  ordenestbl
          WHERE id=:id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $ot);
        $s->execute();
        $value = $s->fetch();

        return $value['signatarioidfk'];
    }
    catch (PDOException $e)
    {
      return "";
    }
}

/**************************************************************************************************/
/* Función para obtener el signatario de la orden */
/**************************************************************************************************/
function setSignatario($signatarioid){
    global $pdo;
    $sql='SELECT nombre, ap, am
          FROM  muestreadorestbl
          WHERE id =:id';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':id', $signatarioid);
    $s->execute();
    $signatario = $s->fetch();

    $sql='UPDATE ordenestbl SET
          signatarionombre = :nombre,
          signatarioap = :ap,
          signatarioam = :am,
          signatarioidfk = :signatarioidfk
          WHERE id = :id';
    $s=$pdo->prepare($sql);
    $s->bindValue(':id', $_SESSION['OT']);
    $s->bindValue(':nombre', $signatario['nombre']);
    $s->bindValue(':ap', $signatario['ap']);
    $s->bindValue(':am', $signatario['am']);
    $s->bindValue(':signatarioidfk', $signatarioid);
    $s->execute();
}

/**************************************************************************************************/
/* Función para obtener el signatario de la orden */
/**************************************************************************************************/
function setGiroEmpresa($id, $giro){
    global $pdo;
    $sql='UPDATE clientestbl SET
          Giro_Empresa=:empresagiro
          WHERE Numero_Cliente = (SELECT clienteidfk
                                  FROM ordenestbl
                                  WHERE id = :id)';
    $s=$pdo->prepare($sql);
    $s->bindValue(':id', $id);
    $s->bindValue(':empresagiro', $giro);
    $s->execute();
}

/**************************************************************************************************/
/* Función para obtener el signatario de la orden */
/**************************************************************************************************/
function setResponsables($id){
    global $pdo;
    $sql='SELECT id FROM muestreosaguatbl WHERE generalaguaidfk=:generalaguaidfk';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':generalaguaidfk', $id);
    $s->execute();
    $muestreoagua = $s->fetch();

    try{
        foreach ($_POST['responsable'] as $value):
            if(strcmp($value['responsable'], '') !== 0)
            {
              $sql = 'SELECT nombre, ap, am
                        FROM  muestreadorestbl
                        WHERE id =:id';
                $s = $pdo->prepare($sql);
                $s->bindValue(':id', $value['responsable']);
                $s->execute();
                $muestreador = $s->fetch();

                if(isset($value['id']))
                {
                    $sql='UPDATE responsables SET
                      muestreoaguaidfk=:muestreoaguaidfk,
                      muestreadoridfk=:muestreadoridfk,
                      nombre=:nombre,
                      ap=:ap,
                      am=:am
                      WHERE id=:id';
                    $s=$pdo->prepare($sql);
                    $s->bindValue(':id', $value['id']);
                    $s->bindValue(':muestreoaguaidfk', $muestreoagua['id']);
                    $s->bindValue(':muestreadoridfk', $value['responsable']);
                    $s->bindValue(':nombre', $muestreador['nombre']);
                    $s->bindValue(':ap', $muestreador['ap']);
                    $s->bindValue(':am', $muestreador['am']);
                    $s->execute();
                }
                else
                {
                    $sql='INSERT INTO responsables SET
                      muestreoaguaidfk=:muestreoaguaidfk,
                      muestreadoridfk=:muestreadoridfk,
                      nombre=:nombre,
                      ap=:ap,
                      am=:am';
                    $s=$pdo->prepare($sql);
                    $s->bindValue(':muestreoaguaidfk', $muestreoagua['id']);
                    $s->bindValue(':muestreadoridfk', $value['responsable']);
                    $s->bindValue(':nombre', $muestreador['nombre']);
                    $s->bindValue(':ap', $muestreador['ap']);
                    $s->bindValue(':am', $muestreador['am']);
                    $s->execute();
                }
            }
            else
            {
                if(isset($value['id']))
                {
                  $sql='UPDATE responsables SET
                    muestreadoridfk = 0
                    WHERE id=:id';
                  $s=$pdo->prepare($sql);
                  $s->bindValue(':id', $value['id']);
                  $s->execute();
                }
            }
        endforeach;
    }
    catch (PDOException $e)
    {
      $mensaje = 'Hay reponsables repetidos, favor de verificar la informacion';
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
    }
}

/**************************************************************************************************/
/* Función para obtener la lista de acreditaciones */
/**************************************************************************************************/
function getAcreditaciones(){
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try
    {
        $sql='SELECT * from acreditaciontbl ORDER BY id DESC';
        $s=$pdo->prepare($sql); 
        $s->execute();
        $signatarios = '';
        foreach ($s as $value) {
          $acreditaciones[$value['id']] = $value['nombre'];
        }
        return $acreditaciones;
    }
    catch (PDOException $e)
    {
      return "";
    }
}

/**************************************************************************************************/
/* Función para obtener la acreditacion de la orden */
/**************************************************************************************************/
function getAcreditacion($ot){
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try
    {
        $sql='SELECT acreditacionidfk, nombre
          FROM ordenacreditaciontbl
          WHERE ordenidfk=:id';
        $s=$pdo->prepare($sql);
        $s->bindValue(':id', $ot);
        $s->execute();
        $value = $s->fetch();

        if($value){
          return array('id' => $value['acreditacionidfk'], 'nombre' => $value['nombre']);
        }
        return '';
    }
    catch (PDOException $e)
    {
      return "";
    }
}

/**************************************************************************************************/
/* Función para guardar la acreditacion en la orden */
/**************************************************************************************************/
function setAcreditacion($acreditacionid){
    global $pdo;
    $sql='SELECT nombre, fecha
          FROM  acreditaciontbl
          WHERE id =:id';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':id', $acreditacionid);
    $s->execute();
    $acreditacion = $s->fetch();

    $sql='SELECT *
          FROM  ordenacreditaciontbl
          WHERE ordenidfk =:ordenidfk';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':ordenidfk', $_SESSION['OT']);
    $s->execute();

    if(!$s->fetch())
    {
      $sql='INSERT INTO ordenacreditaciontbl SET
          ordenidfk = :ordenidfk,
          acreditacionidfk = :acreditacionidfk,
          nombre = :nombre,
          fecha = :fecha';
    }else{
      $sql='UPDATE ordenacreditaciontbl SET
          acreditacionidfk = :acreditacionidfk,
          nombre = :nombre,
          fecha = :fecha
          WHERE ordenidfk = :ordenidfk';
    }
    $s=$pdo->prepare($sql);
    $s->bindValue(':ordenidfk', $_SESSION['OT']);
    $s->bindValue(':acreditacionidfk', $acreditacionid);
    $s->bindValue(':nombre', $acreditacion['nombre']);
    $s->bindValue(':fecha', $acreditacion['fecha']);
    $s->execute();
    
}

/**************************************************************************************************/
/* Función para obtener la calibracion1 del equipo */
/**************************************************************************************************/
function getCalibracion1($correccion, $parametro)
{
    $intervalos = json_decode($correccion, true);
    for ($i = count($intervalos)-1; $i >= 0; $i--)
    {
        if($i === 0)
        {
          return $intervalos[$i]['Correccion1'];
        }
        elseif($parametro >= $intervalos[$i]['Rango'])
        {
          return $intervalos[$i]['Correccion1'];
        }
    }
    return 0;
}

/**************************************************************************************************/
/* Función para obtener la calibracion2 del equipo */
/**************************************************************************************************/
function getCalibracion2($correccion, $parametro)
{
    $intervalos = json_decode($correccion, true);
    for ($i = count($intervalos)-1; $i >= 0; $i--)
    {
        if($i === 0)
        {
          return $intervalos[$i]['Correccion2'];
        }
        elseif($parametro < $intervalos[$i]['Rango'])
        {
          continue;
        }
        else
        {
          return $intervalos[$i]['Correccion2'];
        }
    }
    return 0;
}