<?php
 //********** CLIENTES **********
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';
 
 $especialidades= array('Higiene','Ecologia','Medicas');
 $higiene= array('iluminacion','Nivel sonoro equivalente','Dosis de ruido', 'temperaturas extremas/abatidas','Radiaciones NO ionizantes','Vibraciones mano-brazo','Vibraciones cuerpo completo','Radiaciones ionizantes');
 $ecologia=array('NOM 001','NOM 002','Fuentes fijas','Ruido periferico','suelos',' CRETIB');

 if (!usuarioRegistrado())
 {
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/direccionaregistro.inc.php';
  exit();
 }
 if (!usuarioConPermiso('Administra usuarios'))
 {
  $mensaje='Solo el Admistrador de usuarios tiene acceso a esta parte del programa';
  include '../accesonegado.html.php';
  exit();
 }
 // Si se va a agregar una nueva orden de trabajo
 if (isset($_GET['ordenueva']))
 {
 include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  $pestanapag='Agraga OT';
  $titulopagina='Agraga una nueva orden de trabajo';
  $accion='agregaot';
  $ot='';
  $cliente='';
  $representante='';
  $especialidad='';
  $id='';
  $boton='Agrega orden';
   // Construye lista de representantes y estudios
   try
  {
   $resultados=$pdo->query('SELECT id, nombre FROM representantestbl');
  }
  catch (PDOException $e)
  {
   $mensaje='Hubo un error tratando de obtener la informacion de los representantes';
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
   exit();
  }  
  foreach ($resultados as $resultado)
  {
   $representantes[]=array('id'=>$resultado['id'],
						'nombre'=>$resultado['nombre']);
  }
  for ($x=0; $x<count($higiene); $x++)
  {
   $higienestudios[]=array('id'=>$x, 'nombre'=>$higiene[$x],
						'seleccionada'=>FALSE);
  }
  for ($x=0; $x<count($ecologia); $x++)
  {
   $ecologiaestudios[]=array('id'=>$x, 'nombre'=>$ecologia[$x],
						'seleccionada'=>FALSE);
  }
  include 'formacapturaorden.html.php';
  exit();
 }
 // se va a salvar una orden nueva
 if (isset($_GET['agregaot']))
 {
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
    $sql='INSERT INTO ordenestbl SET
		 ot=:ot,
         fechalta=CURDATE(),
		 representanteidfk=:represetanteid,
		 clienteidfk=:clienteid,
		 supervisada=:supervisada,
		 tipo=:tipo';
	$s=$pdo->prepare($sql);
	$s->bindValue(':ot',$_POST['ot']);
	$s->bindValue(':represetanteid',$_POST['representante']);
	$s->bindValue(':clienteid',$_POST['cliente']);
	$s->bindValue(':supervisada',FALSE);
	$s->bindValue(':tipo',$_POST['tipo']);
	$s->execute();	
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error al tratar de agregar la orden. Favor de intentar nuevamente. '.$e;
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
    }
	$ordenid=$pdo->lastInsertId();
    try
	{
	 $pdo->beginTransaction();
	  if (isset($_POST['higienestudios']))
	  {
	   $sql='INSERT INTO estudiostbl SET
			nombre=:nombre,
			ordenidfk=:ordenid';
	   $s=$pdo->prepare($sql);
	   foreach ($_POST['higienestudios'] as $estudio)
	   {
	    $s->bindValue(':nombre',$estudio);
	    $s->bindValue(':ordenid',$ordenid);
	    $s->execute();
	   }   
	  }
	  if (isset($_POST['ecologiaestudios']))
	  {
	   $sql='INSERT INTO estudiostbl SET
			nombre=:nombre,
			ordenidfk=:ordenid';
	   $s=$pdo->prepare($sql);
	   foreach ($_POST['ecologiaestudios'] as $estudio)
	   {
	    $s->bindValue(':nombre',$estudio);
	    $s->bindValue(':ordenid',$ordenid);
	    $s->execute();
	   }	   
	  }
	 $pdo->commit();
	}
	catch (PDOException $e)
    {
     $mensaje='Hubo un error al tratar de guardar los estudios. Favor de intentar nuevamente.'.$e;
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    } 
   header('Location: .');
   exit();
} 
  // si se desea hacer un cambio en el clliente
  if (isset($_POST['accion']) and $_POST['accion']=='Edita')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
    $sql='SELECT id, ot, clienteidfk, representanteidfk, tipo, fechalta FROM ordenestbl
	WHERE id=:id';
    $s= $pdo->prepare($sql);
    $s->bindValue(':id',$_POST['id']); 
    $s->execute();
   }
   catch (PDOException $e)
  {
   $mensaje='Hubo un error obtenindo la informacion de las ordenes de trabajo';
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
   exit();
  }   
  $resultado=$s->fetch();
  $pestanapag='Edita OT';
  $titulopagina='Edición de la información de la orden de trabajo';
  $accion='editaorden';
  $id=$resultado['id'];
  $ot=$resultado['ot'];
  $cliente=$resultado['clienteidfk'];
  $fechalta=$resultado['fechalta'];
  $representante=$resultado['representanteidfk'];
  $especialidad=$resultado['tipo'];
  $boton='Salva cambios';
   // Construye lista de representantes y estudios
   try
  {
   $resultados=$pdo->query('SELECT id, nombre FROM representantestbl');
  }
  catch (PDOException $e)
  {
   $mensaje='Hubo un error tratando de obtener la informacion de los representantes';
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
   exit();
  }  
  foreach ($resultados as $resultado)
  {
   $representantes[]=array('id'=>$resultado['id'],
						'nombre'=>$resultado['nombre']);
  }
   try
  {
   $sql='SELECT id, nombre FROM estudiostbl
				WHERE ordenidfk=:id';
   $s=$pdo->prepare($sql);
   $s->bindValue(':id',$id);
   $s->execute();
  }
  catch (PDOException $e)
  {
   $mensaje='Hubo un error tratando de obtener la informacion de los estudios solicitados';
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
   exit();
  }
  $estudioSelec=array();
  foreach ($s as $resultado)
  {
    $estudioSelec[]=$resultado['nombre'];
  }
  for ($x=0; $x<count($higiene); $x++)
  {
   $higienestudios[]=array('id'=>$x, 'nombre'=>$higiene[$x],
					'seleccionada'=>in_array($higiene[$x],$estudioSelec));
  }
  for ($x=0; $x<count($ecologia); $x++)
  {
   $ecologiaestudios[]=array('id'=>$x, 'nombre'=>$ecologia[$x],
						'seleccionada'=>in_array($ecologia[$x],$estudioSelec));
  }
  include 'formacapturaorden.html.php';
  exit();  
 }
    
 // guarda cliente editado
 //************************************************************************************
 //*nota.- se requiere adicionar si se acepta el cambio o                             *
 //* para aceptar el cambio esta orden no debe de tener ningun estudio capturado de lo*
 //*que se desea cambiar                                                              *
 //************************************************************************************
  if (isset($_GET['editaorden']))
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
    $pdo->beginTransaction();
     $sql='UPDATE ordenestbl SET
		 ot=:ot,
         clienteidfk=:clienteid,
		 representanteidfk=:representanteid,
		 tipo=:tipo WHERE id=:id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->bindValue(':ot',$_POST['ot']);
	 $s->bindValue(':clienteid',$_POST['cliente']);
	 $s->bindValue(':representanteid',$_POST['representante']);
	 $s->bindValue(':tipo',$_POST['tipo']);
	 $s->execute();
	 //borrra los estudios anteriores y garda los nuevos
	 $sql='DELETE FROM estudiostbl WHERE ordenidfk=:id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();
	 if (isset($_POST['higienestudios']))
	 {
	  $sql='INSERT INTO estudiostbl SET
			nombre=:estudio,
			ordenidfk=:ordenid';
	  $s=$pdo->prepare($sql);
	  foreach ($_POST['higienestudios'] as $estudio)
	  {
	   $s->bindValue(':estudio',$estudio);
	   $s->bindValue(':ordenid',$_POST['id']);
	   $s->execute();
	  }
	 }
	 if (isset($_POST['ecologiaestudios']))
	 {
	  $sql='INSERT INTO estudiostbl SET  
			nombre=:estudio,
			ordenidfk=:ordenid';
	  $s=$pdo->prepare($sql);
	  foreach ($_POST['ecologiaestudios'] as $estudio)
	  {
	   $s->bindValue(':estudio',$estudio);
	   $s->bindValue(':ordenid',$_POST['id']);
	   $s->execute();
	  }
	 }		 
	$pdo->commit();
   }
   catch (PDOException $e)
   {
    $pdo->rollback();
    $mensaje='Hubo un error en la actalización de la orden.  Favor de intentarlo nuevamente '.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }   
   header ('Location: .');
   exit();
  }
  // rutina de busqueda  
  if (isset($_GET['accion']) and $_GET['accion']=='buscar')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
     // instruccion basica
   $select='SELECT id, ot, tipo, fechalta, clienteidfk, representanteidfk';
   $from=' FROM ordenestbl';
   $where=' WHERE TRUE';
   $placeholders=array();
     // si se selecciona ot
   if ($_GET['ot']!='')
   {
    $where .= " AND ot LIKE :ot";
	$placeholders[':ot']='%'.$_GET['ot'].'%';
   }
     // si se selecciona fecha inicio y/o fecha fin
   if ($_GET['fechaini']!='')
   {
    $where .= " AND fechalta > :fechaini";
	$placeholders[':fechaini']=$_GET['fechaini'];
   }
   if ($_GET['fechafin']!='')
   {
    $where .= " AND fechalta < :fechafin";
	$placeholders[':fechafin']=$_GET['fechafin'];
   }   
     // si se selecciona tipo
   if ($_GET['tipo']!='')
   {
    $where .= " AND tipo = :tipo";
	$placeholders[':tipo']=$_GET['tipo'];
   }
     // si se selecciono un representante
   if ($_GET['representante'] !='')
   {
    $where .= " AND representanteidfk= :representante";
	$placeholders[':representante']=$_GET['representante'];
   }
     // se hace la busqueda en donde s->execute($placeholders) sustituye la funcion bindValue pag 218
   try
   {
    $sql=$select.$from.$where;
	$s=$pdo->prepare($sql);
	$s->execute($placeholders);
   }
   catch (PDOException $e)
   {
    $mensaje='hubo un error obteneindo a los clientes';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   foreach ($s as $linea)
   {	
    $ordenes[]=array('id'=>$linea['id'],
					'ot'=>$linea['ot'],
					'clienteid'=>$linea['clienteidfk'],
					'representanteid'=>$linea['representanteidfk'],
					'tipo'=>$linea['tipo'],
					'fechalta'=>$linea['fechalta']);
   }
   include 'formaordenes.html.php';
   exit();
  }
/*    // solicita la confirmación para borrar un cliente
  if (isset($_POST['accion']) and $_POST['accion']=='Borra')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php'; 
   try
   {
    $sql='SELECT id, razonsocial, estado, rfc FROM clientestbl WHERE id=:id';
    $s= $pdo->prepare($sql);
    $s->bindValue(':id',$_POST['id']); 
    $s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='No se pudo hacer al confirmacion de eliminación';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   $resultado=$s->fetch();
   $id=$resultado['id'];
   $razonsocial=$resultado['razonsocial'];
   $estado=$resultado['estado'];
   $rfc=$resultado['rfc'];
   include 'formaconfirma.html.php';
   exit();
  }
  // borrar un cliente
  if (isset($_POST['accion']) and $_POST['accion']=='Continuar borrando')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   // verifica que no tenga ordenes abiertas
   echo 'entro a verificacion de ordenes';
   try
   {
     $sql='SELECT COUNT(*) FROM ordenestbl
	       WHERE clienteidfk=:id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();
    }
   catch (PDOexception $e)
   {
    $mensaje='Existe un error localizando las ordenes hechas al cliente.  Favor de volver a intentar.';
    include 'error.html.php';
    exit();
   }
  $linea=$s->fetch();
  if ($linea[0]>0)
  {
	$mensaje='Lo sentimos no se pude dar de baja a este representante por tener
			ordenes asociadas a el';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
  else
  {
   try
   {
     $sql='DELETE FROM clientestbl WHERE id=:id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='Hubo un error borrando al representante. Intente de nuevo. '.$e;
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
   }
  }
   header('Location: .');
   exit();
  } */
  // genera la lista de representantes  
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
    $resultado=$pdo->query('SELECT id, nombre FROM representantestbl');
  }
  catch (PDOException $e)
  {
    $mensaje='Hubo un error extrayendo la lista de representantes.';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
  }
  foreach ($resultado as $linea)
  {
   $representantes[]=array('id'=>$linea['id'],'nombre'=>$linea['nombre']);
  }
  include 'formabuscaorden.html.php';
?>