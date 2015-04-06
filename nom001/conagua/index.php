<?php
 /********** Norma 001 **********/
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';

/**************************************************************************************************/
/* Búsqueda de ordenes de la norma 001 */
/**************************************************************************************************/
 if (isset($_POST['accion']) AND ($_POST['accion']=='buscar' OR $_POST['accion']=='conagua'))
 {	
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  $datos = explode('-', $_POST['otm']);
  try   
  {
   $sql='SELECT clientestbl.RFC, clientestbl.Razon_Social, ordenestbl.ot, ordenestbl.fechalta, ordenestbl.signatario,
				generalesaguatbl.tipomediciones, generalesaguatbl.Caracdescarga, generalesaguatbl.numedicion,
				generalesaguatbl.nom01maximosidfk, muestreosaguatbl.fechamuestreo, muestreosaguatbl.identificacion, muestreosaguatbl.id as "muestreoaguaid"
		FROM clientestbl
		INNER JOIN ordenestbl ON clientestbl.Numero_Cliente = ordenestbl.clienteidfk
		INNER JOIN generalesaguatbl ON ordenestbl.id = generalesaguatbl.ordenaguaidfk
		INNER JOIN muestreosaguatbl ON generalesaguatbl.id = muestreosaguatbl.generalaguaidfk
		INNER JOIN estudiostbl ON ordenestbl.id = estudiostbl.ordenidfk
		WHERE estudiostbl.nombre="NOM 001" AND ordenestbl.ot = :ot AND generalesaguatbl.numedicion = :numedicion;';
   $s=$pdo->prepare($sql);
   $s->bindValue(':ot',$datos[0]);
   $s->bindValue(':numedicion',$datos[1]);
   $s->execute();
   $orden = $s->fetch();

   $sql='SELECT identificacion
		FROM nom01maximostbl
		WHERE id = :id';
   $s=$pdo->prepare($sql);
   $s->bindValue(':id',$orden['nom01maximosidfk']);
   $s->execute();
   $maximos = $s->fetch();

   $sql='SELECT *
    FROM parametrostbl
    WHERE muestreoaguaidfk = :id';
   $s=$pdo->prepare($sql);
   $s->bindValue(':id',$orden['muestreoaguaid']);
   $s->execute();
   $parametros = $s->fetch();

   $sql='SELECT * FROM reportes.limitestbl WHERE fecha <= :fecha ORDER BY id DESC LIMIT 1;';
   $s=$pdo->prepare($sql);
   $s->bindValue(':fecha',$parametros['fechareporte']);
   $s->execute();
   $limite = $s->fetch();

   $sql='SELECT *
		FROM adicionalestbl
		WHERE parametroidfk = :id';
   $s=$pdo->prepare($sql);
   $s->bindValue(':id',$parametros['id']);
   $s->execute();
   $adicionales = "";
   foreach ($s as $linea) {
    $adicionales[]=array("nombre" => $linea["nombre"],
                         "unidades" => $linea["unidades"],
                         "resultado" => $linea["resultado"]);
   }

	$sql='SELECT mcompuestastbl.*, laboratoriotbl.*
          FROM laboratoriotbl
          INNER JOIN mcompuestastbl ON laboratoriotbl.mcompuestaidfk = mcompuestastbl.id
          WHERE mcompuestastbl.muestreoaguaidfk = :id';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':id',$orden['muestreoaguaid']);
    $s->execute();
    $mcompuestas = "";
	foreach($s as $linea){
    $mcompuestas[] = array("hora" => $linea["hora"],
               "flujo" => $linea["flujo"],
               "volumen" => $linea["volumen"],
               "observaciones" => $linea["observaciones"],
               "caracteristicas" => $linea["caracteristicas"],
               "fechalab" => $linea["fecharecepcion"],
               "horalab" => $linea["horarecepcion"]);
   }
  }
  catch (PDOException $e)
  {
   $mensaje='Hubo un error extrayendo la orden.'.$e;
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
   exit();
  }
  include 'formaorden.html.php';
  exit();
 }

/**************************************************************************************************/
/* Acción por defualt, llevar a búsqueda de ordenes */
/**************************************************************************************************/
  include 'formabuscaordenes.html.php';
  exit();