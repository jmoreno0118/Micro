
<?php
 /********** Norma 001 **********/
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

/**************************************************************************************************/
/* Búsqueda de ordenes de la norma 001 */
/**************************************************************************************************/
 if (isset($_GET['accion']) and $_GET['accion']=='buscar')
 {	
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   $usuarioactivo = $_SESSION['usuario'];
   try   
    {
     $sql='';
	   $select='SELECT ordenestbl.id, ot, Razon_Social, Ciudad, clientestbl.Estado
	 		  FROM ordenestbl
		 	  INNER JOIN clientestbl ON clienteidfk=clientestbl.Numero_Cliente
			  INNER JOIN estudiostbl ON ordenidfk=ordenestbl.id
			  INNER JOIN representantestbl ON representantestbl.id=ordenestbl.representanteidfk
			  INNER JOIN usuarioreptbl ON usuarioreptbl.representanteidfk = representantestbl.id
			  INNER JOIN usuariostbl ON usuariostbl.id = usuarioreptbl.usuarioidfk
			  WHERE estudiostbl.nombre="NOM 001" and usuariostbl.usuario=:usuario';
	 $where = '';
	 if (isset($_GET['otsproceso']))
	 {
	   $where .=' AND fechafin IS NULL';
	 }else{
	 	$where .=' AND fechafin IS NOT NULL';
	 }
	 if ($_GET['ot'] !='')
	 {
	   $where .='  AND ot=:ot';
	   $placeholders[':ot']=$_GET['ot'];
	 }
	 $sql=$select.$where;
	 $placeholders[':usuario']=$usuarioactivo;
	 $s=$pdo->prepare($sql); 
	 $s->execute($placeholders);	
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error extrayendo la lista de ordenes.'.$e;
	 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	 exit();
    }
    foreach ($s as $linea)
    {
     $ordenes[]=array('id'=>$linea['id'],'ot'=>$linea['ot'],
				'razonsocial'=>$linea['Razon_Social'],
				'ciudad'=>$linea['Ciudad'],
        'estado'=>$linea['Estado']);
    }
   include 'formaordenesnom001.html.php';
   exit();
  }

/**************************************************************************************************/
/* Ver mediciones de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='ver mediciones' || $_POST['accion']=='volvermed' || $_POST['accion']=='no guardar parametros' || $_POST['accion']=='Cancelar borrar medicion')
  {
  	verMeds($_POST['ot']);
  }

/**************************************************************************************************/
/* Agregar una nueva medicion a una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='capturarmed')
  {
    $id = $_POST['id'];
    $pestanapag='Agrega medicón';
    $titulopagina='Agregar una nueva medición';
    $accion='';
    $boton = 'guardargenmed';
    $egiro = getEGiro($id);
    $maximos = getMaximos();
    $responsable = getResponsable($id);
    $valores = array("empresagiro" => $egiro,
                     "nom01maximosidfk" => "",
                     "numedicion" => "",
                     "lugarmuestreo" => "",
                     "descriproceso" => "",
                     "tipomediciones" => "",
                     "proposito" => "",
                     "materiasusadas" => "",
                     "tratamiento" => "",
                     "Caracdescarga" => "",
                     "receptor" => "",
                     "estrategia" => "",
                     "numuestras" => "",
                     "observaciones" => "",
                     "fechamuestreo" => "",
                     "identificacion" => "",
                     "temperatura" => "",
                     "caltermometro" => "",
                     "pH" => "",
                     "conductividad" => "",
                     "responsable" => $responsable,
                     "mflotante" => "",
                     "olor" => "",
                     "color" => "",
                     "turbiedad" => "",
                     "GyAvisual" => "",
                     "burbujas" => "");
    include 'formacapturarmeds.html.php';
    exit();
  }

/**************************************************************************************************/
/* Guardar una nueva medición de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='guardargenmed')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
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
     tipomediciones=:tipomediciones,
     proposito=:proposito';
    $s=$pdo->prepare($sql);
    $s->bindValue(':id',$_POST['id']);
    $s->bindValue(':nom01maximosidfk',$_POST['nom01maximosidfk']);
    $s->bindValue(':numedicion',intval($_POST['numedicion']),PDO::PARAM_INT );
    $s->bindValue(':lugarmuestreo',$_POST['lugarmuestreo']);
    $s->bindValue(':descriproceso',$_POST['descriproceso']);
    $s->bindValue(':materiasusadas',$_POST['materiasusadas']);
    $s->bindValue(':tratamiento',$_POST['tratamiento']);
    $s->bindValue(':Caracdescarga',$_POST['Caracdescarga']);
    $s->bindValue(':receptor',$_POST['receptor']);
    $s->bindValue(':estrategia',$_POST['estrategia']);
    $s->bindValue(':numuestras',$_POST['numuestras']);
    $s->bindValue(':observaciones',$_POST['observaciones']);
    $s->bindValue(':tipomediciones',$_POST['tipomediciones']);
    $s->bindValue(':proposito',$_POST['proposito']);
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
    $cantidad = 5;
   }else if($_POST['tipomediciones'] === '16' || $_POST['tipomediciones'] === '24'){
    $cantidad = 7;
   }
   if($cantidad === 1){
    $pestanapag='Agregar parametros';
    $titulopagina='Agregar parametros';
    $accion='';
    $boton = 'guardar nuevos parametros';
    $valores = array("ssedimentables" => "",
                    "ssuspendidos" => "",
                    "dbo" => "",
                    "nkjedahl" => "",
                    "nitritos" => "",
                    "nitratos" => "",
                    "nitrogeno" => "",
                    "fosforo" => "",
                    "arsenico" => "",
                    "cadmio" => "",
                    "cianuros" => "",
                    "cobre" => "",
                    "cromo" => "",
                    "mercurio" => "",
                    "niquel" => "",
                    "plomo" => "",
                    "zinc" => "",
                    "hdehelminto" => "",
                    "fechareporte" => "");
    include 'formacapturarparametros.html.php';
    exit();
   }
   $pestanapag='Agregar muestras compuestas';
   $titulopagina='Agregar muestras compuestas';
   $accion='';
   $boton = 'guardarmcomp';
   include 'formacapturarcompuestas.html.php';
   exit();
  }

/**************************************************************************************************/
/* Guardar nuevas muestras compuestas de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='guardarmcomp')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
    $pdo->beginTransaction();
    foreach ($_POST["mcompuestas"] as $key => $value) {
     if($value["hora"] != "" && $value["flujo"] != "" && $value["volumen"]!="" && $value["observaciones"]!="" && $value["fechalab"]!="" && $value["horalab"]!="")
     {
      $sql='INSERT INTO mcompuestastbl SET
         muestreoaguaidfk=:id,
         hora=:hora,
         flujo=:flujo,
         volumen=:volumen,
         observaciones=:observaciones,
         caracteristicas=:caracteristicas';
      $s=$pdo->prepare($sql);
      $s->bindValue(':id', $_POST["id"]);
      $s->bindValue(':hora', $value["hora"]);
      $s->bindValue(':flujo', $value["flujo"]);
      $s->bindValue(':volumen', $value["volumen"]);
      $s->bindValue(':observaciones', $value["observaciones"]);
      $s->bindValue(':caracteristicas', $value["caracteristicas"]);
      $s->execute();
      $mcompuesta = $pdo->lastInsertid();

      $sql='INSERT INTO laboratoriotbl SET
         mcompuestaidfk=:id,
         fecharecepcion=:fecharecepcion,
         horarecepcion=:horarecepcion';
      $s=$pdo->prepare($sql);
      $s->bindValue(':id', $mcompuesta);
      $s->bindValue(':fecharecepcion', $value["fechalab"]);
      $s->bindValue(':horarecepcion', $value["horalab"]);
      $s->execute();
     }
    }
    $pdo->commit();
   }
   catch (PDOException $e)
   {
    $pdo->rollback();
    $mensaje='Hubo un error al tratar de insertar las muestras compuestas. Favor de intentar nuevamente.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   $id = $_POST["id"];
   $cantidad = $_POST['cantidad'];
   $pestanapag='Agregar parametros';
   $titulopagina='Agregar parametros';
   $accion='';
   $boton = 'guardar nuevos parametros';
   $valores = array("ssedimentables" => "",
                    "ssuspendidos" => "",
                    "dbo" => "",
                    "nkjedahl" => "",
                    "nitritos" => "",
                    "nitratos" => "",
                    "nitrogeno" => "",
                    "fosforo" => "",
                    "arsenico" => "",
                    "cadmio" => "",
                    "cianuros" => "",
                    "cobre" => "",
                    "cromo" => "",
                    "mercurio" => "",
                    "niquel" => "",
                    "plomo" => "",
                    "zinc" => "",
                    "hdehelminto" => "",
                    "fechareporte" => "");
   include 'formacapturarparametros.html.php';
   exit();
  }

/**************************************************************************************************/
/* Editar reconocimiento inicial de una orden de trabajo */
/**************************************************************************************************/
  if((isset($_POST['accion']) and $_POST['accion']=='editarmed') OR (isset($_POST['accion']) and $_POST['accion'] == 'volvercmeds'))
  {
   $id = $_POST['id'];
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
    $sql='SELECT * FROM generalesaguatbl
       INNER JOIN muestreosaguatbl ON generalesaguatbl.id=muestreosaguatbl.generalaguaidfk
       WHERE generalesaguatbl.id = :id';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':id',$_POST['id']);
    $s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='Hubo un error extrayendo la información de reconocimiento inicial.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   $linea = $s->fetch();
   $maximos = getMaximos();
   $egiro = getEGiro($linea["ordenaguaidfk"]);
   $valores = array("empresagiro" => $egiro,
                   "nom01maximosidfk" => $linea["nom01maximosidfk"],
                   "numedicion" => $linea["numedicion"],
                   "lugarmuestreo" => $linea["lugarmuestreo"],
                   "descriproceso" => $linea["descriproceso"],
                   "tipomediciones" => $linea["tipomediciones"],
                   "proposito" => $linea["proposito"],
                   "materiasusadas" => $linea["materiasusadas"],
                   "tratamiento" => $linea["tratamiento"],
                   "Caracdescarga" => $linea["Caracdescarga"],
                   "receptor" => $linea["receptor"],
                   "estrategia" => $linea["estrategia"],
                   "numuestras" => $linea["numuestras"],
                   "observaciones" => $linea["observaciones"],
                   "fechamuestreo" => $linea["fechamuestreo"],
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
   $boton = 'salvarmed';
   $regreso = 1;
   include 'formacapturarmeds.html.php';
   exit();
  }

/**************************************************************************************************/
/* Borrar una medición de una orden de trabajo */
/**************************************************************************************************/
  if (isset($_POST['accion']) and $_POST['accion']=='borrarmed')
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
   }
   catch (PDOException $e)
   {
    $pdo->rollback();
    $mensaje='Hubo un error borrando la medición. Intente de nuevo. '.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   verMeds($_POST['ot']);
  }

/**************************************************************************************************/
/* Formulario de parametros de una medicion una orden de trabajo */
/**************************************************************************************************/
  if (isset($_POST['accion']) and $_POST['accion']=='parametros')
  {
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    $cantidad = 1;
    if($_POST['tipomedicion'] === '8'){
     $cantidad = 5;
    }else if($_POST['tipomedicion'] === '16' || $_POST['tipomedicion'] === '24'){
     $cantidad = 7;
    }

    if(isset($_POST['idparametro'])){
     try   
     {
      $sql='SELECT * FROM parametrostbl WHERE id = :id';
      $s=$pdo->prepare($sql); 
      $s->bindValue(':id',$_POST['idparametro']);
      $s->execute();
     }
     catch (PDOException $e)
     {
      $mensaje='Hubo un error extrayendo la información de parametros.';
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
     }
     $param1 = $s->fetch();
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
     try   
     {
      $sql='SELECT * FROM parametros2tbl WHERE parametroidfk = :id';
      $s=$pdo->prepare($sql); 
      $s->bindValue(':id',$_POST['idparametro']);
      $s->execute();
     }
     catch (PDOException $e)
     {
      $mensaje='Hubo un error extrayendo la información de parametros2.';
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
     }
     $parametros = "";
     foreach ($s as $linea) {
       $parametros[]=array("GyA" => $linea["GyA"],
                           "coliformes" => $linea["coliformes"]);
     }
     try   
     {
      $sql='SELECT * FROM adicionalestbl WHERE parametroidfk = :id';
      $s=$pdo->prepare($sql); 
      $s->bindValue(':id',$_POST['idparametro']);
      $s->execute();
     }
     catch (PDOException $e)
     {
      $mensaje='Hubo un error extrayendo la información de adicionales.';
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
     }
     $adicionales = "";
     foreach ($s as $linea) {
       $adicionales[]=array("nombre" => $linea["nombre"],
                           "unidades" => $linea["unidades"],
                           "resultado" => $linea["resultado"]);
     }
     formularioParametros($_POST['id'], $cantidad, $valores, $parametros, $adicionales, $_POST['idparametro'],'salvar parametros');
    }else{
      formularioParametros($_POST['id'], $cantidad, "", "", "", "",'guardar nuevos parametros');
    }
  }

/**************************************************************************************************/
/* Guardar nuevos parametros de una medicion de una orden de trabajo */
/**************************************************************************************************/
  if (isset($_POST['accion']) and $_POST['accion']=='guardar nuevos parametros')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try   
   {
    $sql='SELECT id FROM muestreosaguatbl WHERE generalaguaidfk = :id';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':id',$_POST['id']);
    $s->execute();
    $idmuestreo = $s->fetch();
   }
   catch (PDOException $e)
   {
    $mensaje='Hubo un error extrayendo la información de parametros.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
    $pdo->beginTransaction();
    $sql='INSERT INTO parametrostbl SET
     muestreoaguaidfk=:id,
     ssedimentables=:ssedimentables,
     ssuspendidos=:ssuspendidos,
     dbo=:dbo,
     nkjedahl=:nkjedahl,
     nitritos=:nitritos,
     nitratos=:nitratos,
     nitrogeno=:nitrogeno,
     fosforo=:fosforo,
     arsenico=:arsenico,
     cadmio=:cadmio,
     cianuros=:cianuros,
     cobre=:cobre,
     cromo=:cromo,
     mercurio=:mercurio,
     niquel=:niquel,
     plomo=:plomo,
     zinc=:zinc,
     hdehelminto=:hdehelminto,
     fechareporte=:fechareporte';
    $s=$pdo->prepare($sql);
    $s->bindValue(':id', $idmuestreo['id']);
    $s->bindValue(':ssedimentables', $_POST['ssedimentables']);
    $s->bindValue(':ssuspendidos', $_POST['ssuspendidos']);
    $s->bindValue(':dbo', $_POST['dbo']);
    $s->bindValue(':nkjedahl', $_POST['nkjedahl']);
    $s->bindValue(':nitritos', $_POST['nitritos']);
    $s->bindValue(':nitratos', $_POST['nitratos']);
    $s->bindValue(':nitrogeno', $_POST['nitrogeno']);
    $s->bindValue(':fosforo', $_POST['fosforo']);
    $s->bindValue(':arsenico', $_POST['arsenico']);
    $s->bindValue(':cadmio', $_POST['cadmio']);
    $s->bindValue(':cianuros', $_POST['cianuros']);
    $s->bindValue(':cobre', $_POST['cobre']);
    $s->bindValue(':cromo', $_POST['cromo']);
    $s->bindValue(':mercurio', $_POST['mercurio']);
    $s->bindValue(':niquel', $_POST['niquel']);
    $s->bindValue(':plomo', $_POST['plomo']);
    $s->bindValue(':zinc', $_POST['zinc']);
    $s->bindValue(':hdehelminto', $_POST['hdehelminto']);
    $s->bindValue(':fechareporte', $_POST['fechareporte']);
    $s->execute();
    $id=$pdo->lastInsertid();

    foreach ($_POST["parametros"] as $key => $value) {
     if($value["GyA"] != "" && $value["coliformes"] != "")
     {
      $sql='INSERT INTO parametros2tbl SET
             parametroidfk=:id,
             GyA=:GyA,
             coliformes=:coliformes';
      $s=$pdo->prepare($sql);
      $s->bindValue(':id', $id);
      $s->bindValue(':GyA', $value["GyA"]);
      $s->bindValue(':coliformes', $value["coliformes"]);
      $s->execute();
     }
    }

    foreach ($_POST["adicionales"] as $key => $value) {
     if($value["nombre"] != "" && $value["unidades"] != "" && $value["resultado"] != "")
     {
      $sql='INSERT INTO adicionalestbl SET
             parametroidfk=:id,
             nombre=:nombre,
             unidades=:unidades,
             resultado=:resultado';
      $s=$pdo->prepare($sql);
      $s->bindValue(':id', $id);
      $s->bindValue(':nombre', $value["nombre"]);
      $s->bindValue(':unidades', $value["unidades"]);
      $s->bindValue(':resultado', $value["resultado"]);
      $s->execute();
     }
    }

    $pdo->commit();
   }
   catch (PDOException $e)
   {
    $pdo->rollback();
    $mensaje='Hubo un error al tratar de insertar el reconocimiento. Favor de intentar nuevamente.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   verMeds($_SESSION['OT']);
  }

/**************************************************************************************************/
/* Formulario de parametros de una medicion una orden de trabajo */
/**************************************************************************************************/
  if (isset($_POST['accion']) and $_POST['accion']=='salvar parametros')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
    $pdo->beginTransaction();
    $sql='UPDATE parametrostbl SET
     ssedimentables=:ssedimentables,
     ssuspendidos=:ssuspendidos,
     dbo=:dbo,
     nkjedahl=:nkjedahl,
     nitritos=:nitritos,
     nitratos=:nitratos,
     nitrogeno=:nitrogeno,
     fosforo=:fosforo,
     arsenico=:arsenico,
     cadmio=:cadmio,
     cianuros=:cianuros,
     cobre=:cobre,
     cromo=:cromo,
     mercurio=:mercurio,
     niquel=:niquel,
     plomo=:plomo,
     zinc=:zinc,
     hdehelminto=:hdehelminto,
     fechareporte=:fechareporte
     WHERE id = :id';
    $s=$pdo->prepare($sql);
    $s->bindValue(':id', $_POST['idparametro']);
    $s->bindValue(':ssedimentables', $_POST['ssedimentables']);
    $s->bindValue(':ssuspendidos', $_POST['ssuspendidos']);
    $s->bindValue(':dbo', $_POST['dbo']);
    $s->bindValue(':nkjedahl', $_POST['nkjedahl']);
    $s->bindValue(':nitritos', $_POST['nitritos']);
    $s->bindValue(':nitratos', $_POST['nitratos']);
    $s->bindValue(':nitrogeno', $_POST['nitrogeno']);
    $s->bindValue(':fosforo', $_POST['fosforo']);
    $s->bindValue(':arsenico', $_POST['arsenico']);
    $s->bindValue(':cadmio', $_POST['cadmio']);
    $s->bindValue(':cianuros', $_POST['cianuros']);
    $s->bindValue(':cobre', $_POST['cobre']);
    $s->bindValue(':cromo', $_POST['cromo']);
    $s->bindValue(':mercurio', $_POST['mercurio']);
    $s->bindValue(':niquel', $_POST['niquel']);
    $s->bindValue(':plomo', $_POST['plomo']);
    $s->bindValue(':zinc', $_POST['zinc']);
    $s->bindValue(':hdehelminto', $_POST['hdehelminto']);
    $s->bindValue(':fechareporte', $_POST['fechareporte']);
    $s->execute();
    
    $sql="DELETE FROM parametros2tbl
       WHERE parametroidfk = :id";
    $s=$pdo->prepare($sql);
    $s->bindValue(':id',$_POST['idparametro']);
    $s->execute();

    foreach ($_POST["parametros"] as $key => $value) {
     if($value["GyA"] != "" && $value["coliformes"] != "")
     {
      $sql='INSERT INTO parametros2tbl SET
             parametroidfk=:id,
             GyA=:GyA,
             coliformes=:coliformes';
      $s=$pdo->prepare($sql);
      $s->bindValue(':id', $_POST['idparametro']);
      $s->bindValue(':GyA', $value["GyA"]);
      $s->bindValue(':coliformes', $value["coliformes"]);
      $s->execute();
     }
    }

    $sql="DELETE FROM adicionalestbl
       WHERE parametroidfk = :id";
    $s=$pdo->prepare($sql);
    $s->bindValue(':id',$_POST['idparametro']);
    $s->execute();

    foreach ($_POST["adicionales"] as $key => $value) {
     if($value["nombre"] != "" && $value["unidades"] != "" && $value["resultado"] != "")
     {
      $sql='INSERT INTO adicionalestbl SET
             parametroidfk=:id,
             nombre=:nombre,
             unidades=:unidades,
             resultado=:resultado';
      $s=$pdo->prepare($sql);
      $s->bindValue(':id', $_POST['idparametro']);
      $s->bindValue(':nombre', $value["nombre"]);
      $s->bindValue(':unidades', $value["unidades"]);
      $s->bindValue(':resultado', $value["resultado"]);
      $s->execute();
     }
    }
    $pdo->commit();
   }
   catch (PDOException $e)
   {
    $pdo->rollback();
    $mensaje='Hubo un error al tratar de insertar el reconocimiento. Favor de intentar nuevamente.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   verMeds($_SESSION['OT']);
  }

/**************************************************************************************************/
/* Guardar la edición de una orden de trabajo */
/**************************************************************************************************/
  if((isset($_POST['accion']) AND $_POST['accion'] == 'salvarmed') OR (isset($_POST['accion']) and $_POST['accion'] == 'volvercoms'))
  {
   $id = $_POST['id'];
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
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
      tipomediciones=:tipomediciones,
      proposito=:proposito
      WHERE id=:id';
     $s=$pdo->prepare($sql);
     $s->bindValue(':id',$_POST['id']);
     $s->bindValue(':nom01maximosidfk',$_POST['nom01maximosidfk']);
     $s->bindValue(':numedicion',intval($_POST['numedicion']),PDO::PARAM_INT );
     $s->bindValue(':lugarmuestreo',$_POST['lugarmuestreo']);
     $s->bindValue(':descriproceso',$_POST['descriproceso']);
     $s->bindValue(':materiasusadas',$_POST['materiasusadas']);
     $s->bindValue(':tratamiento',$_POST['tratamiento']);
     $s->bindValue(':Caracdescarga',$_POST['Caracdescarga']);
     $s->bindValue(':receptor',$_POST['receptor']);
     $s->bindValue(':estrategia',$_POST['estrategia']);
     $s->bindValue(':numuestras',$_POST['numuestras']);
     $s->bindValue(':observaciones',$_POST['observaciones']);
     $s->bindValue(':tipomediciones',$_POST['tipomediciones']);
     $s->bindValue(':proposito',$_POST['proposito']);
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

     $pdo->commit();
    }
    catch (PDOException $e)
    {
     $pdo->rollback();
     $mensaje='Hubo un error al tratar de actulizar la medicion. Favor de intentar nuevamente.'.$e;
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
    }
     $cantidad = 1;
     if($_POST['tipomediciones'] === '8'){
      $cantidad = 5;
     }else if($_POST['tipomediciones'] === '16' || $_POST['tipomediciones'] === '24'){
      $cantidad = 7;
     }
   }else{
    $cantidad = intval($_POST['cantidad']);
   }
   if($cantidad === 1){
    if($_POST['accion'] == 'volvercoms'){
       include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
       try
       {
        $sql='SELECT * FROM generalesaguatbl
           INNER JOIN muestreosaguatbl ON generalesaguatbl.id=muestreosaguatbl.generalaguaidfk
           WHERE generalesaguatbl.id = :id';
        $s=$pdo->prepare($sql); 
        $s->bindValue(':id',$_POST['id']);
        $s->execute();
       }
       catch (PDOException $e)
       {
        $mensaje='Hubo un error extrayendo la información de reconocimiento inicial.';
        include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
        exit();
       }
       $linea = $s->fetch();
       $maximos = getMaximos();
       $egiro = getEGiro($linea["ordenaguaidfk"]);
       $valores = array("empresagiro" => $egiro,
                       "nom01maximosidfk" => $linea["nom01maximosidfk"],
                       "numedicion" => $linea["numedicion"],
                       "lugarmuestreo" => $linea["lugarmuestreo"],
                       "descriproceso" => $linea["descriproceso"],
                       "tipomediciones" => $linea["tipomediciones"],
                       "proposito" => $linea["proposito"],
                       "materiasusadas" => $linea["materiasusadas"],
                       "tratamiento" => $linea["tratamiento"],
                       "Caracdescarga" => $linea["Caracdescarga"],
                       "receptor" => $linea["receptor"],
                       "estrategia" => $linea["estrategia"],
                       "numuestras" => $linea["numuestras"],
                       "observaciones" => $linea["observaciones"],
                       "fechamuestreo" => $linea["fechamuestreo"],
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
       $boton = 'salvarmed';
       $regreso = 1;
       include 'formacapturarmeds.html.php';
       exit();
    }
    try   
    {
     $sql='SELECT * FROM parametrostbl
          WHERE muestreoaguaidfk = (SELECT id 
                      FROM muestreosaguatbl
                      WHERE generalaguaidfk = :id)';
     $s=$pdo->prepare($sql); 
     $s->bindValue(':id',$_POST['id']);
     $s->execute();
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error extrayendo la información de parametros.';
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
    }
    if($param1 = $s->fetch()){
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
     try   
     {
      $sql='SELECT * FROM parametros2tbl WHERE parametroidfk = :id';
      $s=$pdo->prepare($sql); 
      $s->bindValue(':id',$param1['id']);
      $s->execute();
     }
     catch (PDOException $e)
     {
      $mensaje='Hubo un error extrayendo la información de parametros2.';
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
     }
     $parametros = "";
     foreach ($s as $linea) {
       $parametros[]=array("GyA" => $linea["GyA"],
                           "coliformes" => $linea["coliformes"]);
     }
     try   
     {
      $sql='SELECT * FROM adicionalestbl WHERE parametroidfk = :id';
      $s=$pdo->prepare($sql); 
      $s->bindValue(':id',$param1['id']);
      $s->execute();
     }
     catch (PDOException $e)
     {
      $mensaje='Hubo un error extrayendo la información de adicionales.';
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
     }
     $adicionales = "";
     foreach ($s as $linea) {
       $adicionales[]=array("nombre" => $linea["nombre"],
                           "unidades" => $linea["unidades"],
                           "resultado" => $linea["resultado"]);
     }
     formularioParametros($_POST['id'], $cantidad, $valores, $parametros, $adicionales, $param1['id'],'salvar parametros', 1);
    }else{
      formularioParametros($_POST['id'], $cantidad, "", "", "", "",'guardar nuevos parametros', 1);
    }
   }else{
     try   
     {
      $sql="SELECT DATE_FORMAT(mcompuestastbl.hora, '%H:%i') as 'hora', mcompuestastbl.flujo, mcompuestastbl.volumen, mcompuestastbl.observaciones,
              mcompuestastbl.caracteristicas, laboratoriotbl.fecharecepcion, DATE_FORMAT(laboratoriotbl.horarecepcion, '%H:%i') as 'horarecepcion'
            FROM laboratoriotbl
            INNER JOIN mcompuestastbl ON laboratoriotbl.mcompuestaidfk = mcompuestastbl.id
            INNER JOIN muestreosaguatbl ON mcompuestastbl.muestreoaguaidfk = muestreosaguatbl.id
            WHERE muestreosaguatbl.generalaguaidfk = :id";
      $s=$pdo->prepare($sql); 
      $s->bindValue(':id',$_POST['id']);
      $s->execute();
     }
     catch (PDOException $e)
     {
      $mensaje='Hubo un error extrayendo la información de muestras compuestas.'.$e;
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
     }
     foreach($s as $linea){
      $mcompuestas[] = array("hora" => $linea["hora"],
                 "flujo" => $linea["flujo"],
                 "volumen" => $linea["volumen"],
                 "observaciones" => $linea["observaciones"],
                 "caracteristicas" => $linea["caracteristicas"],
                 "fechalab" => $linea["fecharecepcion"],
                 "horalab" => $linea["horarecepcion"]);
     }
     $pestanapag='Editar muestras compuestas';
     $titulopagina='Editar muestras compuestas';
     $accion='';
     $boton = 'salvarmcomp';
     $regreso = 1;
     include 'formacapturarcompuestas.html.php';
     exit();
   }
  }

/**************************************************************************************************/
/* Guardar la edicion de muestras compuestas de una medicion de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='salvarmcomp')
  {
   $id = $_POST['id'];
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try
    {
     $pdo->beginTransaction();

      $sql='SELECT id
            FROM muestreosaguatbl
            WHERE generalaguaidfk = :id';
     $s=$pdo->prepare($sql);
     $s->bindValue(':id',$_POST['id']);
     $s->execute();
     $muestreo = $s->fetch();

     $sql='DELETE FROM laboratoriotbl WHERE mcompuestaidfk IN (SELECT mcompuestastbl.id
                                                                FROM mcompuestastbl
                                                                INNER JOIN muestreosaguatbl ON mcompuestastbl.muestreoaguaidfk = muestreosaguatbl.id
                                                                WHERE muestreosaguatbl.generalaguaidfk = :id)';
     $s=$pdo->prepare($sql);
     $s->bindValue(':id', $muestreo['id']);
     $s->execute();

     $sql='DELETE FROM mcompuestastbl WHERE muestreoaguaidfk = :id';
     $s=$pdo->prepare($sql);
     $s->bindValue(':id', $muestreo['id']);
     $s->execute();

     foreach ($_POST["mcompuestas"] as $key => $value) {
      if($value["hora"] != "" && $value["flujo"] != "" && $value["volumen"] != "" && $value["observaciones"] != "")
      {
       $sql='INSERT INTO mcompuestastbl SET
         muestreoaguaidfk=:id,
         hora=:hora,
         flujo=:flujo,
         volumen=:volumen,
         observaciones=:observaciones,
         caracteristicas=:caracteristicas';
       $s=$pdo->prepare($sql);
       $s->bindValue(':id', $muestreo['id']);
       $s->bindValue(':hora', $value["hora"]);
       $s->bindValue(':flujo', $value["flujo"]);
       $s->bindValue(':volumen', $value["volumen"]);
       $s->bindValue(':observaciones', $value["observaciones"]);
       $s->bindValue(':caracteristicas', $value["caracteristicas"]);
       $s->execute();
       $mcompuesta = $pdo->lastInsertid();

       $sql='INSERT INTO laboratoriotbl SET
         mcompuestaidfk=:id,
         fecharecepcion=:fecharecepcion,
         horarecepcion=:horarecepcion';
       $s=$pdo->prepare($sql);
       $s->bindValue(':id', $mcompuesta);
       $s->bindValue(':fecharecepcion', $value["fechalab"]);
       $s->bindValue(':horarecepcion', $value["horalab"]);
       $s->execute();
      }
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
   $cantidad = $_POST['cantidad'];
   try   
   {
    $sql='SELECT * FROM parametrostbl
          WHERE muestreoaguaidfk = (SELECT id 
                      FROM muestreosaguatbl
                      WHERE generalaguaidfk = :id)';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':id',$_POST['id']);
    $s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='Hubo un error extrayendo la información de parametros.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   if($param1 = $s->fetch()){
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
     try   
     {
      $sql='SELECT * FROM parametros2tbl WHERE parametroidfk = :id';
      $s=$pdo->prepare($sql); 
      $s->bindValue(':id',$param1['id']);
      $s->execute();
     }
     catch (PDOException $e)
     {
      $mensaje='Hubo un error extrayendo la información de parametros2.';
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
     }
     $parametros = "";
     foreach ($s as $linea) {
       $parametros[]=array("GyA" => $linea["GyA"],
                           "coliformes" => $linea["coliformes"]);
     }
     try   
     {
      $sql='SELECT * FROM adicionalestbl WHERE parametroidfk = :id';
      $s=$pdo->prepare($sql); 
      $s->bindValue(':id',$param1['id']);
      $s->execute();
     }
     catch (PDOException $e)
     {
      $mensaje='Hubo un error extrayendo la información de adicionales.';
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
     }
     $adicionales = "";
     foreach ($s as $linea) {
       $adicionales[]=array("nombre" => $linea["nombre"],
                           "unidades" => $linea["unidades"],
                           "resultado" => $linea["resultado"]);
     }
     formularioParametros($_POST['id'], $cantidad, $valores, $parametros, $adicionales, $param1['id'],'salvar parametros', 1);
    }else{
      formularioParametros($_POST['id'], $cantidad, "", "", "", "",'guardar nuevos parametros', 1);
    }
  }

/**************************************************************************************************/
/* Ir a subir croquis */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='croquis')
  {
    $ot = $_POST['ot'];
    $numedicion = $_POST['numedicion'];
    $id = $_POST['id'];
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try
     {
      $sql='SELECT ot
        FROM ordenestbl
        WHERE id = :id';
      $s=$pdo->prepare($sql); 
      $s->bindValue(':id',$ot);
      $s->execute();
      $nombreot = $s->fetch();
      
      $sql='SELECT * FROM croquistbl WHERE generalaguaidfk = :id';
      $s=$pdo->prepare($sql); 
      $s->bindValue(':id', $id);
      $s->execute();
      $croquis = $s->fetch();
     }
     catch (PDOException $e)
     {
      $mensaje='Hubo un error extrayendo la información de adicionales.'.$e;
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
     }
    include 'formacroquis.html.php';
    exit();
  }


/**************************************************************************************************/
/* Subir croquis */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='subir')
  {
    // verifica que el archivo se haya subido
    if (!is_uploaded_file($_FILES['archivo']['tmp_name'])) {
    $mensaje='Hubo un error tratando de subir el archivo.  Favor de revisar la conexión a internet y que el archivo sea menor a 2Mb e intenta de nevo.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  // verifica que el archivo sea gif, jpeg, png o bmp
    /*  $archivotipo=exif_imagetype($_FILES['archivo']['tmp_name']);
    $tiposaceptados=array(IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG,IMAGETYPE_BMP);
    if (!in_array($archivotipo,$tiposaceptados)){
    $mensaje='el archivo no se acepto por no ser tipo GIF, JPEG, PNG O BMP'; 
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit(); 
  } */  
  // se verifica que el nombre del archivo solo contenga caracteres validos
  $nombrearch=preg_replace('/[^A-Z0-9._-]/i','_',$_FILES['archivo']['name']);
  $partes=pathinfo($nombrearch);
  $extension=$partes['extension'];
  $nombrearchivar=$_POST['ot'].'_'.$_POST['id'].'_'.$_POST['numedicion'].'.'.$extension;
  $nombre=$partes['filename'];
  // verifica que el archivo sea pdf.
  /*if ($extension!=='jpeg' AND $extension!=='jpg' AND $extension!=='png' AND $extension!=='gif'){
    $mensaje='el archivo no se acepto por no ser tipo imagen'; 
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }*/
  $archivotipo=exif_imagetype($_FILES['archivo']['tmp_name']);
    $tiposaceptados=array(IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG,IMAGETYPE_BMP);
    if (!in_array($archivotipo,$tiposaceptados)){
    $mensaje='el archivo no se acepto por no ser tipo GIF, JPEG, PNG O BMP'; 
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit(); 
  }
  //se guarda el archivo en la carpeta deseada
  $semovio=move_uploaded_file($_FILES['archivo']['tmp_name'],
  $_SERVER['DOCUMENT_ROOT'].'/reportes/nom001/croquis/'.$nombrearchivar);
  if (!$semovio){
    $mensaje='Vuelva a intentar de nuevo.  Hubo un error tratando de guardar el archivo';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  // Colocar los permisos de lectura al archivo
  chmod ($_SERVER['DOCUMENT_ROOT'].'/reportes/nom001/croquis/'.$nombrearchivar, 7777);
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  if($_POST['update'] == '0'){
    try
    {
      $sql='INSERT INTO croquistbl SET
        nombre=:nombre,
        nombrearchivado=:nombrearchivar,
        generalaguaidfk=:id';
      $s=$pdo->prepare($sql);
      $s->bindValue(':nombre',$nombre);
      $s->bindValue(':nombrearchivar',$nombrearchivar);
      $s->bindValue(':id',$_POST['id']);
      $s->execute();
    }
    catch(PDOException $e)
    {
      $mensaje='Hubo un error al tratar de guardar la informacion del plano.  intentar nuevamente y avisar de este error a sistemas.';
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit(); 
    }
  }else{
    try
    {
      $sql='UPDATE croquistbl SET
        nombre=:nombre,
        nombrearchivado=:nombrearchivar
        WHERE generalaguaidfk=:id';
      $s=$pdo->prepare($sql);
      $s->bindValue(':nombre',$nombre);
      $s->bindValue(':nombrearchivar',$nombrearchivar);
      $s->bindValue(':id',$_POST['id']);
      $s->execute();
    }
    catch(PDOException $e)
    {
      $mensaje='Hubo un error al tratar de guardar la informacion del plano.  intentar nuevamente y avisar de este error a sistemas.';
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit(); 
    }
  }
  
    $ot = $_POST['ot'];
    $numedicion = $_POST['numedicion'];
    $id = $_POST['id'];
    try
     {
      $sql='SELECT * FROM croquistbl WHERE generalaguaidfk = :id';
      $s=$pdo->prepare($sql); 
      $s->bindValue(':id', $id);
      $s->execute();
      $croquis = $s->fetch();
     }
     catch (PDOException $e)
     {
      $mensaje='Hubo un error extrayendo la información de adicionales.'.$e;
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
     }
    include 'formacroquis.html.php';
   exit();
  }



/**************************************************************************************************/
/* Acción por defualt, llevar a búsqueda de ordenes */
/**************************************************************************************************/
  include 'formabuscaordenesnom001.html.php';
  exit();

/**************************************************************************************************/
/* Función para ver mediciones de una orden de trabajo */
/**************************************************************************************************/
  function verMeds($ot = ""){
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   $_SESSION['OT'] = $ot;
   try   
   {
    $sql='SELECT ot
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
             'parametros'=> ($params = $p->fetch()) ? $params['id'] : "");
   }

   if($params = $s->fetch()){
    $paramid = $params['id'];
   }

   include 'formameds.html.php';
   exit();
  }

/**************************************************************************************************/
/* Función para ver formulario de parametros de una medicion de una orden de trabajo */
/**************************************************************************************************/
  function formularioParametros($id = "", $cantidad = "", $valor = "", $params = "", $adicional = "", $idparametro = "",$boton = "", $regreso = ""){
   $pestanapag='Parametros';
   $titulopagina='Parametros';
   $accion='';
   if($valor !== ""){
    $valores = $valor;
   }
   if($params !== ""){
    $parametros = $params; 
   }
   if($idparametro !== ""){
    $idparametros = $idparametro;
   }
   if($adicional !== ""){
    $adicionales = $adicional;
   }
   include 'formacapturarparametros.html.php';
   exit();
  }

/**************************************************************************************************/
/* Función para obtener valores máximos */
/**************************************************************************************************/
  function getMaximos(){
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try   
    {
     $s=$pdo->prepare('SELECT id, identificacion FROM nom01maximostbl');
     $s->execute();
     $e = $s->fetchAll();
     foreach ($e as $value) {
      $maximos[] = array("id" => $value['id'],
                         "identificacion" => $value['identificacion']);
     }
     return $maximos;
    }
    catch (PDOException $e)
    {
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
  function getResponsable($ot){
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try   
   {
    $sql='SELECT generalesaguatbl.id, muestreosaguatbl.responsable
        FROM  generalesaguatbl 
        INNER JOIN muestreosaguatbl ON generalesaguatbl.id = muestreosaguatbl.generalaguaidfk
        WHERE  generalesaguatbl.ordenaguaidfk = :id
        ORDER BY id ASC;';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':id',$ot);
    $s->execute();
    $e = $s->fetch();
    return ($e['responsable'])? $e['responsable'] : "";
   }
   catch (PDOException $e)
   {
    return "";
   }
  }