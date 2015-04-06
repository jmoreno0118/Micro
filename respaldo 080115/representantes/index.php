<?php
 //********** REPRESENTANES **********
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';
   $estados= array('Aguascalientes','Baja California','Baja California Sur','Campeche','Coahuila de Zaragoza','Colima','Chiapas','Chihuahua','Distrito Federal','Durango','Guanajuato','Guerrero','Hidalgo','Jalisco','México','Michoacán','Morelos','Nayarit','Nuevo León','Oaxaca','Puebla','Querétaro','Quintana Roo','San Luis Potosí','Sinaloa','Sonora','Tabasco','Tamaulipas','Tlaxcala','Veracruz','Yucatán','Zacatecas');
 if (!usuarioRegistrado())
 {
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/direccionaregistro.inc.php';
  exit();
 } if (!usuarioConPermiso('Administra usuarios'))
 {
  $mensaje='Solo el Admistrador de usuarios tiene acceso a esta parte del programa';
  include '../accesonegado.html.php';
  exit();
 } 
 // Si se va a agregar un representante
 if (isset($_GET['representantenuevo']))
 {
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  $pestanapag='Agraga representante';
  $titulopagina='Agraga a un nuevo representante';
  $accion='agregarepresentante';
  $nombre='';
  $estado='';
  $tel='';
  $id='';
  $boton='Agrega representnte';
  include 'formacapturarep.html.php';
  exit();
 }

 if (isset($_GET['agregarepresentante']))
 {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
    $sql='INSERT INTO representantestbl SET
		 nombre=:nombre,
         estado=:estado,
		 tel=:tel';
	$s=$pdo->prepare($sql);
	$s->bindValue(':nombre',$_POST['nombre']);
	$s->bindValue(':estado',$_POST['estado']);
	$s->bindValue(':tel',$_POST['tel']);
	$s->execute();	
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error al tratar de agregar al representante. Favor de intentar nuevamente.';
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
    }
   header('Location: .');
   exit();
  }
  // si se desea hacer un cambio en el representante
  if (isset($_POST['accion']) and $_POST['accion']=='Edita')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
    $sql='SELECT id, nombre, estado, tel FROM representantestbl WHERE id=:id';
    $s= $pdo->prepare($sql);
    $s->bindValue(':id',$_POST['id']); 
    $s->execute();
   }
   catch (PDOException $e)
  {
   $mensaje='Hubo un error obtenindo la informacion del representante';
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
   exit();
  }   
  $resultado=$s->fetch();
  $pestanapag='Edita representante';
  $titulopagina='Edición de la información del representante';
  $accion='editarepresentante';
  $id=$resultado['id'];
  $nombre=$resultado['nombre'];
  $estado=$resultado['estado'];
  $tel=$resultado['tel'];
  $boton='Salva cambios';

  include 'formacapturarep.html.php';;
  exit();  
  }
  // guarda representante editado
  if (isset($_GET['editarepresentante']))
  { 
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   { 
    $sql='UPDATE representantestbl SET
         nombre=:nombre,
		 estado=:estado,
		 tel=:tel WHERE id=:id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id',$_POST['id']);
	$s->bindValue(':nombre',$_POST['nombre']);
	$s->bindValue(':estado',$_POST['estado']);
	$s->bindValue(':tel',$_POST['tel']);
	$s->execute();
   }
   catch (PDOException $e)
   {
   $mensaje='Hubo un error en la actalización del representante';
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
   exit();
   }   
   header ('Location: .');
   exit();
  }
  // solicita la confirmación para borrar un representante
  if (isset($_POST['accion']) and $_POST['accion']=='Borra')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php'; 
   try
   {
    $sql='SELECT id, nombre, estado FROM representantestbl WHERE id=:id';
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
   $nombre=$resultado['nombre'];
   $estado=$resultado['estado'];
   include 'formaconfirma.html.php';
   exit();
  }
  // borrar un representante
  if (isset($_POST['accion']) and $_POST['accion']=='Continuar borrando')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   // verifica que no tenga ordenes abiertas
   echo 'entro a verificacion de ordenes';
   try
   {
     $sql='SELECT COUNT(*) FROM ordenestbl
	       WHERE representanteidfk=:id';
	 $s=$pdo->prepare($sql);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();
    }
   catch (PDOexception $e)
   {
    $mensaje='Existe un error localizando los datos de las ordenes de trabajo del
		representante.  Favor de volver a intentar.';
    include 'error.html.php';
    exit();
   }
   echo ' salgo de verificacion de ordenes'; 
  $linea=$s->fetch();
  if ($linea[0]>0)
  {echo ' entro al if de que encontro ordenes'; 
	$mensaje='Lo sentimos no se pude dar de baja a este representante por tener
			ordenes asociadas a el';
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
  else
  {
   try
   {
     $sql='DELETE FROM representantestbl WHERE id=:id';
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
  }
  
  //muestra la lista de los representantes
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
   $resultado=$pdo->query('SELECT id, nombre, estado FROM representantestbl');
  }
  catch (PDOException $e)
  {
   $mensaje='Error en la recuperación de la base de datos de representantes';
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
   exit();
  }
  
  foreach ($resultado as $linea)
  {
   $representantes[]=array('id'=>$linea['id'], 
                 'nombre'=>$linea['nombre'],
				 'estado'=>$linea['estado']);
  }
  include 'formarepresentantes.html.php';
?> 
    