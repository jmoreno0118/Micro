<?php
/**************************************************************************************************/
/* Funcion para hacer una busqueda de ordenes */
/**************************************************************************************************/
function buscaordenes($estudio, $otsproceso = '', $ot = '', $supervisada = ''){
	global $pdo;
	$usuarioactivo=$_SESSION['usuario'];
   	try   
    {
		$sql='';
		$select='SELECT ordenestbl.id, ot, ordenestbl.plantaidfk, ordenestbl.clienteidfk
		      FROM ordenestbl
		      INNER JOIN estudiostbl ON ordenidfk=ordenestbl.id
		      INNER JOIN representantestbl ON representantestbl.id=ordenestbl.representanteidfk
		      INNER JOIN usuarioreptbl ON usuarioreptbl.representanteidfk = representantestbl.id
		      INNER JOIN usuariostbl ON usuariostbl.id = usuarioreptbl.usuarioidfk
		      WHERE estudiostbl.nombre="'.$estudio.'" and usuariostbl.usuario=:usuario';
		$where = '';
		if ($otsproceso){
			$where .=' AND fechafin IS NULL';
		}else{
			$_SESSION['terminada'] = 1;
			$where .=' AND fechafin IS NOT NULL AND fecharevision IS NULL';
		}
		if ($supervisada){
			$_SESSION['supervisada'] = 1;
			$where =' AND fechafin IS NOT NULL AND fecharevision IS NOT NULL';
		}
		if ($ot !=''){
			$where .='  AND ot=:ot';
			$placeholders[':ot']=$_GET['ot'];
		}
		$sql=$select.$where;
		$placeholders[':usuario']=$usuarioactivo;
		$s=$pdo->prepare($sql); 
		$s->execute($placeholders);
    }catch (PDOException $e){
		$mensaje='Hubo un error extrayendo la lista de ordenes.';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php'.$e;
		exit();
    }
    foreach ($s as $linea){
      if($linea['plantaidfk'] !== NULL){
          $sql='SELECT id, razonsocial, ciudad, estado
              FROM plantastbl
              WHERE plantastbl.id = :id';
          $s=$pdo->prepare($sql);
          $s->bindValue(':id', $linea['plantaidfk']);
          $s->execute();
          $resultado = $s->fetch();

          $cliente = array('id' => $resultado['id'],
                          'Razon_Social' => $resultado['razonsocial'],
                          'Ciudad' => $resultado['ciudad'],
                          'Estado' => $resultado['estado']);

      }else{
          $sql='SELECT Razon_Social, Ciudad, Estado
              FROM clientestbl
              WHERE clientestbl.Numero_Cliente = :id';
          $s=$pdo->prepare($sql);
          $s->bindValue(':id', $linea['clienteidfk']);
          $s->execute();
          $cliente = $s->fetch();
      }



      $ordenes[] = array('id' => $linea['id'],
                        'ot' => $linea['ot'],
                        'idplanta' => $cliente['id'],
                        'razonsocial' => $cliente['Razon_Social'],
                        'ciudad' => $cliente['Ciudad'],
                        'estado' => $cliente['Estado']);
    }
    if (isset($ordenes)){
      return $ordenes;
    }else{
      return;
    }
}

/**************************************************************************************************/
/* Funcion para mostrar los datos de una orden */
/**************************************************************************************************/
function ordendatos($ordenid){
  global $pdo, $id, $ot;
  try
  {
    $sql='SELECT id, clienteidfk, ot, fechalta, tipo, Razon_Social,  Calle_Numero, Colonia, Ciudad, Estado, Codigo_Postal, RFC, atencion, atenciontel, atencioncorreo FROM ordenestbl
	  INNER JOIN clientestbl ON clienteidfk=Numero_Cliente
	  WHERE id=:id';
      $s=$pdo->prepare($sql);
      $s->bindValue(':id',$ordenid);
      $s->execute();

	}
	catch(PDOException $e)
	{
      $mensaje='Hubo un error extrayendo la información del cliente'.$e;
	  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	  return;
	}
	$resultado=$s->fetch();
	$datos=array('Razon social'=>$resultado['Razon_Social'],
				//'Planta'=>$resultado['planta'],
				'Planta'=>'Planta',
				'Dirección'=>$resultado['Calle_Numero'],
				'Colonia'=>$resultado['Colonia'],
				'Municipio'=>$resultado['Ciudad'],
				'Estado'=>$resultado['Estado'],
				'C.P.'=>$resultado['Codigo_Postal'],
				'RFC'=>$resultado['RFC'],
				'Atencion a'=>$resultado['atencion'],
				'Tels'=>$resultado['atenciontel'],
				'Correo'=>$resultado['atencioncorreo'],
				'Tipo de estudio'=>$resultado['tipo'],
				'Fecha de alta'=>$resultado['fechalta']);
	$id=$resultado['id'];
	$ot=$resultado['ot'];
	return $datos;
}

/**************************************************************************************************/
/* Funcion para mostrar los estudios de una orden */
/**************************************************************************************************/
function ordenestudios($ordenid){
  global $pdo;
  try
  {
    $sql='SELECT count(*), nombre FROM estudiostbl WHERE ordenidfk=:id';
      $s=$pdo->prepare($sql);
      $s->bindValue(':id',$ordenid);
      $s->execute();
	}
  catch(PDOException $e)
	{
    $mensaje='Hubo un error extrayendo la información de los esatudios';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    return;
	}
	$informes=array();
	foreach ($s as $dato){
	  $informes[]=$dato['nombre'];
	}
  return $informes;
}

/**************************************************************************************************/
/* Limpia los valores de idot, idrci, idpunto y quien */
/**************************************************************************************************/
function limpiasession(){
  if (isset($_SESSION['idot'])){
    unset($_SESSION['idot']);
  }
  if (isset($_SESSION['quien'])){
    unset($_SESSION['quien']);
  } 
}

/**************************************************************************************************/
/* Función obtener el numero de OT  partir del id de ordenestbl */
/**************************************************************************************************/
function otdeordenes($id="")
{
  global $pdo;
  try   
  {
    $sql='SELECT ot FROM ordenestbl WHERE id = :id';
    $s=$pdo->prepare($sql); 
    $s->bindValue(':id', $id);
    $s->execute();
  }catch (PDOException $e){
    $mensaje='Hubo un error extrayendo informacion de la orden.';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
  }
  $resultado = $s->fetch();
  return $resultado['ot']; 
}

/**************************************************************************************************/
/* Funcion para ver si un rec. ini. tiene puntos */
/**************************************************************************************************/
function vaciorci($tablaenlace='',$campoarci='',$id=''){
	try
   {
   	global $pdo;
    $sql='SELECT COUNT(*) as Puntos FROM '.$tablaenlace.
	     ' WHERE '.$campoarci.'=:id';
    $s= $pdo->prepare($sql);
    $s->bindValue(':id',$id); 
    $s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='No se pudo hacer el conteo de puntos'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
	$cuenta = $s->fetch();
   if($cuenta["Puntos"] > 0){
     return false; }
   else {
     return true;}
}
?>