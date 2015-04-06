<?php
 //********** USUARIOS **********
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';

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
 // Si se va a agregar un usuario
 if (isset($_GET['usuarionuevo']))
 {
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  $pestanapag='Agraga usuario';
  $titulopagina='Agraga a un nuevo usuario';
  $accion='agregausuario';
  $usuario='';
  $nombre='';
  $apellido='';
  $correo='';
  $id='';
  $boton='Agrega usuario';
  
  // Construye lista de actividades y de representantes a las que tiene aceeso el usuario
  try
  {
	$resultado=$pdo->query('SELECT id, descripcion FROM actividadestbl');	
  }
  catch (PDOException $e)
  {
   $mensaje='Hubo un error extrayendo las actividades ';
   include 'error.html.php';
   exit();
  }
  foreach ($resultado as $linea)
  {
   $actividades[]=array('id'=>$linea['id'], 'descripcion'=>$linea['descripcion'],
						'seleccionada'=>FALSE);
  }
    try
  {
    $resultado=$pdo->query('SELECT id, nombre FROM representantestbl');		
  }
  catch (PDOException $e)
  {
   $mensaje='Hubo un error extrayendo los representantes ';
   include 'error.html.php';
   exit();
  }
  foreach ($resultado as $linea)
  {
   $representantes[]=array('id'=>$linea['id'], 'nombre'=>$linea['nombre'],
						'seleccionada'=>FALSE);
  }
  include 'formacaptura.html.php';
  exit();
 }
 
 if (isset($_GET['agregausuario']))
 {
  if ($_POST['clave']!='')
  {
   $clave=md5($_POST['clave'].'ravol');
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
    $sql='INSERT INTO usuariostbl SET
		 usuario=:usuario,
         nombre=:nombre,
		 apellido=:apellido,
		 correo=:correo,
		 clave=:clave';
	$s=$pdo->prepare($sql);
	$s->bindValue(':usuario',$_POST['usuario']);
	$s->bindValue(':nombre',$_POST['nombre']);
	$s->bindValue(':apellido',$_POST['apellido']);
	$s->bindValue(':correo',$_POST['correo']);
	$s->bindValue(':clave',$clave);
	$s->execute();	
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error al tratar de agregar al usuarrio. Favor de intentar nuevamente.';
     include 'error.html.php';
     exit();
    }
	$usuarioid=$pdo->lastInsertid();
   }
   else
   {
    $mensaje='Se requiere de una clave de acceso para poder dar de alta al usuario';
    include 'error.html.php';
    exit();
   }
   if (isset($_POST['actividades']))
   {
    foreach ($_POST['actividades'] as $actividad)
	{
	 try
	 {
	  $sql='INSERT INTO usuarioactivtbl SET
	        usuarioidfk=:usuarioid,
			actividfk=:actividadid';
	  $s=$pdo->prepare($sql);
	  $s->bindValue(':usuarioid',$usuarioid);
	  $s->bindValue(':actividadid',$actividad);
	  $s->execute();
	 }
	 catch (PDOException $e)
	 {
	  $mensaje='Hubo un error tratando de guardar las actividades';
	  include 'error.html.php';
	  exit();
	 }
	}
   }
   if (isset($_POST['representantes']))
   {
    foreach ($_POST['representantes'] as $representante)
	{
	 try
	 {
	  $sql='INSERT INTO usuarioreptbl SET
	        usuarioidfk=:usuarioid,
			representanteidfk=:representanteid';
	  $s=$pdo->prepare($sql);
	  $s->bindValue(':usuarioid',$usuarioid);
	  $s->bindValue(':representanteid',$representante);
	  $s->execute();
	 }
	 catch (PDOException $e)
	 {
	  $mensaje='Hubo un error tratando de guardar los representantes';
	  include 'error.html.php';
	  exit();
	 }
	}
   }
   header('Location: .');
   exit();
  }
  // si se desea hacer un cambio en el usuario
  if (isset($_POST['accion']) and $_POST['accion']=='Edita')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
    $sql='SELECT id, usuario, clave, nombre, apellido, correo FROM usuariostbl WHERE id=:id';
    $s= $pdo->prepare($sql);
    $s->bindValue(':id',$_POST['id']); 
    $s->execute();
   }
   catch (PDOException $e)
  {
   $mensaje='Hubo un error obtenindo la informacion del usuario';
   include 'error.html.php';
   exit();
  }   
  $resultado=$s->fetch();
  $pestanapag='Edita usuario';
  $titulopagina='Edición de la información del usuario';
  $accion='editausuario';
  $id=$resultado['id'];
  $usuario=$resultado['usuario'];
  $nombre=$resultado['nombre'];
  $apellido=$resultado['apellido'];
  $correo=$resultado['correo'];
  $boton='Salva cambios';
  // Trae la lista de actividades asignadas al usuario
  try
  {
   $sql='SELECT actividfk FROM usuarioactivtbl WHERE usuarioidfk=:id';
   $s=$pdo->prepare($sql);
   $s->bindValue(':id',$_POST['id']);
   $s->execute();
  }
  catch (PDOException $e)
  {
   $mensaje='Hubo un errror tratando de leer las actividades permitidas';
   include 'error.html.php';
   exit();
  }
  $actividadesSelec=array();
  foreach ($s as $linea)
  {
   $actividadesSelec[]=$linea['actividfk'];
  }
  // construiremos la lista de actividades
  try
  {
   $resultado=$pdo->query('SELECT id, descripcion FROM actividadestbl');
  }
  catch (PDOException $e)
  {
   $mensaje='Hubo un error obteniendo la base de datos de las actividades';
   include 'error.html.php';
   exit();
  }
  foreach ($resultado as $linea)
  {
   $actividades[]=array('id'=>$linea['id'], 'descripcion'=>$linea['descripcion'],
                   'seleccionada'=>in_array($linea['id'],$actividadesSelec));
  }
  // Trae la lista de representantes asignadas al usuario
  try
  {
   $sql='SELECT representanteidfk FROM usuarioreptbl WHERE usuarioidfk=:id';
   $s=$pdo->prepare($sql);
   $s->bindValue(':id',$_POST['id']);
   $s->execute();
  }
  catch (PDOException $e)
  {
   $mensaje='Hubo un errror tratando de leer los representantes a los que tiene acceso';
   include 'error.html.php';
   exit();
  }
  $representantesSelec=array();
  foreach ($s as $linea)
  {
   $representantesSelec[]=$linea['representanteidfk'];
  }
  // construiremos la lista de representantes
  try
  {
   $resultado=$pdo->query('SELECT id, nombre FROM representantestbl');
  }
  catch (PDOException $e)
  {
   $mensaje='Hubo un error obteniendo la base de datos de las actividades';
   include 'error.html.php';
   exit();
  }
  foreach ($resultado as $linea)
  {
   $representantes[]=array('id'=>$linea['id'], 'nombre'=>$linea['nombre'],
                   'seleccionada'=>in_array($linea['id'],$representantesSelec));
  }
  include 'formacaptura.html.php';
  exit();  
  }
  // guarda usuario editado
  if (isset($_GET['editausuario']))
  { 
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   { 
    $sql='UPDATE usuariostbl SET
	     usuario=:usuario,
         nombre=:nombre,
		 apellido=:apellido,
		 correo=:correo WHERE id=:id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id',$_POST['id']);
	$s->bindValue(':usuario',$_POST['usuario']);
	$s->bindValue(':nombre',$_POST['nombre']);
	$s->bindValue(':apellido',$_POST['apellido']);
	$s->bindValue(':correo',$_POST['correo']);
	$s->execute();
   }
   catch (PDOException $e)
   {
   $mensaje='Hubo un error en la actalización del usuario';
   include 'error.html.php';
   exit();
   }
   if ($_POST['clave']!='')
   {
    $clave=md5($_POST['clave'].'ravol');
	try
	{
	 $sql='UPDATE usuariostbl SET
	  clave=:clave
	  WHERE id=:id';
	 $s=$pdo->prepare($sql);
     $s->bindValue(':clave',$clave);
	 $s->bindValue(':id',$_POST['id']);
	 $s->execute();
	}
	catch (PDOException $e)
	{
	 $mensaje='Hubo un error y no se pudo guardar la nueva clave.';
     include 'errro.html.php';
     exit();
	}
   }
   //guardando las nuevas actividades
   try
   {
	$sql='DELETE FROM usuarioactivtbl WHERE usuarioidfk=:id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id',$_POST['id']);
	$s->execute();
   }
   catch (PDOException $e)
   {
	$mensaje='Hubo un error no se pudieron eliminar las actividades previas.  Intentar de nuevo.';
	include 'error.html.php';
	exit();
   }
   if (isset($_POST['actividades']))
   {
	foreach ($_POST['actividades'] as $actividad)
	{
	 try
	 {
	  $sql='INSERT INTO usuarioactivtbl SET
	    usuarioidfk=:usuarioid,
		actividfk=:activid';
	  $s=$pdo->prepare($sql);
	  $s->bindValue(':usuarioid',$_POST['id']);
	  $s->bindValue(':activid',$actividad);
	  $s->execute();
	 }
	 catch (PDOException $e)
	 {
	  $mensaje='Hubo un error guardendo las nuevas actividades. Favor de reintentar.';
	  include 'error.html.php';
	  exit();
	 }
	}
   }
   //guardando los nuevos representantes
      try
   {
	$sql='DELETE FROM usuarioreptbl WHERE usuarioidfk=:id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id',$_POST['id']);
	$s->execute();
   }
   catch (PDOException $e)
   {
	$mensaje='Hubo un error no se pudieron eliminar los representantes previos.  Intentar de nuevo.';
	include 'error.html.php';
	exit();
   }
   if (isset($_POST['representantes']))
   {
	foreach ($_POST['representantes'] as $representante)
	{
	 try
	 {
	  $sql='INSERT INTO usuarioreptbl SET
	    usuarioidfk=:usuarioid,
		representanteidfk=:representante';
	  $s=$pdo->prepare($sql);
	  $s->bindValue(':usuarioid',$_POST['id']);
	  $s->bindValue(':representante',$representante);
	  $s->execute();
	 }
	 catch (PDOException $e)
	 {
	  $mensaje='Hubo un error guardendo los nuevos representantes. Favor de reintentar.';
	  include 'error.html.php';
	  exit();
	 }
	}
   }
   
   header ('Location: .');
   exit();
  }
  // solicita la confirmación de borrar un usuario
  if (isset($_POST['accion']) and $_POST['accion']=='Borra')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php'; 
   try
   {
    $sql='SELECT id, usuario, nombre, apellido FROM usuariostbl WHERE id=:id';
    $s= $pdo->prepare($sql);
    $s->bindValue(':id',$_POST['id']); 
    $s->execute();
   }
   catch (PDOException $e)
   {
    $mensaje='No se pudo hacer al confirmacion de eliminación';
    include 'error.html.php';
    exit();
   }
   $resultado=$s->fetch();
   $id=$resultado['id'];
   $usuario=$resultado['usuario'];
   $nombre=$resultado['nombre'];
   $apellido=$resultado['apellido'];

   //$resultado=$s->fetchAll();
   //foreach ($resultado as $linea)
   //{
   // $usuarios[]=array ('id'=>$linea['id'],'usuario'=>$linea['usuario'],
	//'nombre'=>$linea['nombre'], 'apellidos'=>$linea['apellidos']);
   //}
   include 'formaconfirma.html.php';
   exit();
  }
  // borrar un usuario
  if (isset($_POST['accion']) and $_POST['accion']=='Continuar borrando')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   exit();
   // borra las activdades del usuario
   $id=$_POST['id'];
   try
   {
    $pdo->beginTransaction();
      $sql='DELETE FROM usuarioactivtbl WHERE usuarioidfk=:id';
	  $s=$pdo->prepare($sql);
	  $s->bindValue(':id',$id);
	  $s->execute();
	  $sql='DELETE FROM usuarioreptbl WHERE usuarioidfk=:id';
	  $s=$pdo->prepare($sql);
	  $s->bindValue(':id',$id);
	  $s->execute();
	  $sql='DELETE FROM usuariostbl where id=:id';
      $s=$pdo->prepare($sql);
      $s->bindValue(':id',$id);
      $s->execute();
	$pdo->commit();
	
   }
   catch (PDOException $e)
   {
    $pdo->rollback();
    $mensaje='Hubo un error borrando el usuario y los sus enlaces. Intente de nuevo. '.$e;
	include 'error.html.php';
	exit();
   }

   header('Location: .');
   exit();
  }
  
  //muestra la lista de los usuarios
  include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
  try
  {
   $resultado=$pdo->query('SELECT id, nombre, apellido FROM usuariostbl');
  }
  catch (PDOException $e)
  {
   $mensaje='Error en la recuperación de la base de datos de usuarios';
   include 'error.html.php';
   exit();
  }
  
  foreach ($resultado as $linea)
  {
   $usuarios[]=array('id'=>$linea['id'], 
                 'nombre'=>$linea['nombre'],
				 'apellido'=>$linea['apellido']);
  }
  include 'formausuarios.html.php';
?> 
    