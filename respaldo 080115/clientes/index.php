<?php
 //********** CLIENTES **********
 include_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/magicquotes.inc.php';
 require_once $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/acceso.inc.php';
 $estados= array('Aguascalientes','Baja California','Baja California Sur','Campeche','Coahuila de Zaragoza','Colima','Chiapas','Chihuahua','Distrito Federal','Durango','Guanajuato','Guerrero','Hidalgo','Jalisco','México','Michoacán','Morelos','Nayarit','Nuevo León','Oaxaca','Puebla','Querétaro','Quintana Roo','San Luis Potosí','Sinaloa','Sonora','Tabasco','Tamaulipas','Tlaxcala','Veracruz','Yucatán','Zacatecas');
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
 // Si se va a agregar un cliente nuevo
 if (isset($_GET['clientenuevo']))
 {
  $pestanapag='Agraga cliente';
  $titulopagina='Agraga a un nuevo cliente';
  $accion='agregacliente';
  $razonsocial='';
  $planta='';
  $calle='';
  $colonia='';
  $municipio='';
  $estado='';
  $cp='';
  $atencion='';
  $rfc='';
  $tel='';
  $id='';
  $boton='Agrega cliente';
  include 'formacapturacliente.html.php';
  exit();
 }
 // se va a salvar un cliente nuevo
 if (isset($_GET['agregacliente']))
 {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
    $sql='INSERT INTO clientestbl SET
		 razonsocial=:razonsocial,
         planta=:planta,
		 calle=:calle,
		 colonia=:colonia,
		 municipio=:municipio,
		 estado=:estado,
		 cp=:cp,
	     atencion=:atencion,
		 rfc=:rfc,
		 tel=:tel';
	$s=$pdo->prepare($sql);
	$s->bindValue(':razonsocial',$_POST['razonsocial']);
	$s->bindValue(':planta',$_POST['planta']);
	$s->bindValue(':calle',$_POST['calle']);
	$s->bindValue(':colonia',$_POST['colonia']);
	$s->bindValue(':municipio',$_POST['municipio']);
	$s->bindValue(':estado',$_POST['estado']);
	$s->bindValue(':cp',$_POST['cp']);
	$s->bindValue(':atencion',$_POST['atencion']);
	$s->bindValue(':rfc',$_POST['rfc']);
	$s->bindValue(':tel',$_POST['tel']);
	$s->execute();	
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error al tratar de agregar al cliente. Favor de intentar nuevamente.';
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
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
    $sql='SELECT id, razonsocial, planta, calle, colonia, municipio, estado, cp, atencion,
			 rfc, tel FROM clientestbl WHERE id=:id';
    $s= $pdo->prepare($sql);
    $s->bindValue(':id',$_POST['id']); 
    $s->execute();
   }
   catch (PDOException $e)
  {
   $mensaje='Hubo un error obtenindo la informacion del cliente';
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
   exit();
  }   
  $resultado=$s->fetch();
  $pestanapag='Edita cliente';
  $titulopagina='Edición de la información del cliente';
  $accion='editacliente';
  $id=$resultado['id'];
  $razonsocial=$resultado['razonsocial'];
  $planta=$resultado['planta'];
  $calle=$resultado['calle'];
  $colonia=$resultado['colonia'];
  $municipio=$resultado['municipio'];
  $estado=$resultado['estado'];
  $cp=$resultado['cp'];
  $atencion=$resultado['atencion'];
  $rfc=$resultado['rfc'];
  $tel=$resultado['tel'];
  $boton='Salva cambios';

  include 'formacapturacliente.html.php';
  exit();  
  }
  // guarda cliente editado
  if (isset($_GET['editacliente']))
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   { 
    $sql='UPDATE clientestbl SET
		 razonsocial=:razonsocial,
         planta=:planta,
		 calle=:calle,
		 colonia=:colonia,
		 municipio=:municipio,
		 estado=:estado,
		 cp=:cp,
		 atencion=:atencion,
		 rfc=:rfc,
		 tel=:tel WHERE id=:id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id',$_POST['id']);
	$s->bindValue(':razonsocial',$_POST['razonsocial']);
	$s->bindValue(':planta',$_POST['planta']);
	$s->bindValue(':calle',$_POST['calle']);
	$s->bindValue(':colonia',$_POST['colonia']);
	$s->bindValue(':municipio',$_POST['municipio']);
	$s->bindValue(':estado',$_POST['estado']);
	$s->bindValue(':cp',$_POST['cp']);
	$s->bindValue(':atencion',$_POST['atencion']);
	$s->bindValue(':rfc',$_POST['rfc']);
	$s->bindValue(':tel',$_POST['tel']);
	$s->execute();
   }
   catch (PDOException $e)
   {
   $mensaje='Hubo un error en la actalización del cliene.';
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
   $select='SELECT id, razonsocial, municipio, estado, rfc';
   $from=' FROM clientestbl';
   $where=' WHERE TRUE';
   $placeholders=array();
     // si se selecciona razon social
   if ($_GET['razonsocial']!='')
   {
    $where .= " AND razonsocial LIKE :razonsocial";
	$placeholders[':razonsocial']='%'.$_GET['razonsocial'].'%';
   }
     // si se selecciona municipio
   if ($_GET['municipio']!='')
   {
    $where .= " AND municipio LIKE :municipio";
	$placeholders[':municipio']='%'.$_GET['municipio'].'%';
   }
     // si se selecciona rfc
   if ($_GET['rfc']!='')
   {
    $where .= " AND rfc LIKE :rfc";
	$placeholders[':rfc']='%'.$_GET['rfc'].'%';
   }
     // si se selecciono una estado
   if ($_GET['estado'] !='')
   {
    $where .= " AND estado= :estado";
	$placeholders[':estado']=$_GET['estado'];
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
    $clientes[]=array('id'=>$linea['id'],
					'razonsocial'=>$linea['razonsocial'],
					'municipio'=>$linea['municipio'],
					'estado'=>$linea['estado'],
					'rfc'=>$linea['rfc']);
   }
   include 'formaclientes.html.php';
   exit();
  }
    // solicita la confirmación para borrar un cliente
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
    $mensaje='Hubo un error borrando al representante. Intente de nuevo. ';
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	exit();
   }
  }
   header('Location: .');
   exit();
  }
  
  include 'formabuscacliente.html.php';
?>