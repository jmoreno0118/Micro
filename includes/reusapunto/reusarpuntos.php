<?php

/**************************************************************************************************/
/* Acción para cargar la tabla de puntos posibles a reusar */
/**************************************************************************************************/
if((isset($_GET['accion']) and $_GET['accion']=='reusarpuntos'))
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
    $sql='SELECT puntostbl.id, puntostbl.medicion, puntostbl.departamento, puntostbl.area, puntostbl.ubicacion
        FROM puntostbl
        LEFT JOIN '.$rectbl.' ON puntostbl.id = '.$rectbl.'.puntoidfk
        INNER JOIN ordenestbl ON puntostbl.ordenidfk = ordenestbl.id
        WHERE '.$rectbl.'.puntoidfk IS NULL AND ordenestbl.id = :id';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':id', $_SESSION['idot']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $mensaje='Hubo un error extrayendo la lista de puntos a reusar.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php'.$e;
    exit();
  }

  foreach ($s as $linea)
  {
    $puntos[]=array('id'=>$linea['id'],
                    'medicion'=>$linea['medicion'],
                    'departamento'=>$linea['departamento'],
                    'area'=>$linea['area'],
                    'ubicacion'=>$linea['ubicacion']);
  }

  $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
  $host     = $_SERVER['HTTP_HOST'];
  $script   = $_SERVER['SCRIPT_NAME'];
  $url = $protocol . '://' . $host . $script;

  $ot=otdeordenes($_SESSION['idot']);
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/reusapunto/formareusapuntos.html.php';
  exit();
}


/**************************************************************************************************/
/* Acción para ver un punto a reusar */
/**************************************************************************************************/
if((isset($_GET['accion']) and $_GET['accion']=='ver'))
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
    $sql='SELECT *
        FROM puntostbl
        WHERE id=:id';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':id', $_GET['id']);
    $s->execute();
    $linea = $s->fetch();
  }
  catch (PDOException $e)
  {
    $mensaje='Hubo un error extrayendo la lista de puntos a reusar.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php'.$e;
    exit();
  }

  $dato = array('id' => $linea['id'],
                  'medicion' => $linea['medicion'],
                  'fecha'=> $linea['fecha'],
                  'departamento' => $linea['departamento'],
                  'area' => $linea['area'],
                  'ubicacion' => $linea['ubicacion']);

  $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
  $host     = $_SERVER['HTTP_HOST'];
  $script   = $_SERVER['SCRIPT_NAME'];
  $url = $protocol . '://' . $host . $script . '?accion=reusarpuntos';

  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/reusapunto/formareusapunto.html.php';
  exit();
}

/**************************************************************************************************/
/* Acción para reusar un punto */
/**************************************************************************************************/
if((isset($_POST['accion']) and $_POST['accion'] == 'Reusar'))
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  agregarPuntoAReconocimiento($_POST['medicion'], $_POST['id'], $_POST['recid']);
}

/**************************************************************************************************/
/* Acción para reusar varios puntos */
/**************************************************************************************************/
if((isset($_POST['accion']) and $_POST['accion'] == 'Reusar Puntos'))
{
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  foreach ($_POST['puntos'] as $key => $punto) {
    agregarPuntoAReconocimiento($_POST['medicion'][$key], $punto, $_POST['recid']);
  }
}

/**************************************************************************************************/
/* Función para actualizar la relación puntos - reconocimientos, aumentael numero de estudios en
un punto y arroja error en caso de existir ese número de medición */
/**************************************************************************************************/
function agregarPuntoAReconocimiento($medicion, $id, $recid){
  global $pdo, $rectbl, $recidfk;
  try
  {
    $sql='SELECT count(*) as medicion
          FROM puntostbl
          RIGHT JOIN '.$rectbl.' ON puntostbl.id = '.$rectbl.'.puntoidfk
          WHERE '.$recidfk.' = :recidfk AND medicion = :medicion';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':medicion', $medicion);
    $s->bindValue(':recidfk', $recid);
    $s->execute();

    $cuenta = $s->fetch();
    
    if($cuenta['medicion'] > 0){
      $mensaje='El número de medición '.$medicion.' ya existe.';
      $errorlink = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?accion=reusarpuntos';
      $errornav = 'Volver a lista de puntos de reuso';
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
      exit();
    } 

    $sql='INSERT INTO '.$rectbl.' SET
          puntoidfk=:puntoidfk,
          '.$recidfk.'=:recidfk';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':puntoidfk', $id);
    $s->bindValue(':recidfk', $recid);
    $s->execute();

    $sql='UPDATE puntostbl SET
          numestudios = numestudios + 1
          WHERE id = :id';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':id', $id);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $mensaje='Hubo un error extrayendo la lista de puntos a reusar.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
}

?>