
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
/* Ir a formulario de nueva planta */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='Nueva')
  {
  	$clientes = getClientes();
	$valores = array('razonsocial'=>'',
				     'planta'=>'',
				     'calle'=>'',
				     'colonia'=>'',
				     'ciudad'=>'',
				     'estado'=>'',
				     'cp'=>'',
				     'rfc'=>'',
				     'Numero_Clienteidfk'=>'');
	$pestanapag ='Agrega planta';
	$titulopagina ='Agregar una nueva planta';
	$boton = 'Guardar Planta';
    include 'formacapturaplanta.html.php';
    exit();
  }

/**************************************************************************************************/
/* Guardar nueva planta */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='Guardar Planta')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
    $pdo->beginTransaction();

    $sql='INSERT INTO plantastbl SET
     razonsocial=:razonsocial,
     planta=:planta,
     calle=:calle,
     colonia=:colonia,
     ciudad=:ciudad,
     estado=:estado,
     cp=:cp,
     rfc=:rfc,
     Numero_Clienteidfk=:cliente';
    $s=$pdo->prepare($sql);
    $s->bindValue(':razonsocial',$_POST['razonsocial']);
    $s->bindValue(':planta',$_POST['planta']);
    $s->bindValue(':calle',$_POST['calle']);
    $s->bindValue(':colonia',$_POST['colonia']);
    $s->bindValue(':ciudad',$_POST['ciudad']);
    $s->bindValue(':estado',$_POST['estado']);
    $s->bindValue(':cp',$_POST['cp']);
    $s->bindValue(':rfc',$_POST['rfc']);
    $s->bindValue(':cliente',$_POST['cliente']);
    $s->execute();

    $pdo->commit();
   }
   catch (PDOException $e)
   {
    $pdo->rollback();
    $mensaje='Hubo un error al tratar de insertar la planta. Favor de intentar nuevamente.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   verPlantas();
  }

/**************************************************************************************************/
/* Entrar a edición de una planta */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='Editar')
  {
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	$id = $_POST['id'];
	try
	{
		$sql='SELECT * FROM plantastbl WHERE id=:id';
		$s=$pdo->prepare($sql);
		$s->bindValue(':id',$id);
		$s->execute();
		$valores = $s->fetch();
	}
	catch (PDOException $e)
	{
		$mensaje='Hubo un error al tratar de insertar la planta. Favor de intentar nuevamente.'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	$clientes = getClientes();
	$pestanapag ='Editar planta';
	$titulopagina ='Editar una planta';
	$boton = 'Salvar Planta';
	include 'formacapturaplanta.html.php';
	exit();
  }

/**************************************************************************************************/
/* Salvar edición de una planta */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='Salvar Planta')
  {
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
    $pdo->beginTransaction();

    $sql='UPDATE plantastbl SET
     razonsocial=:razonsocial,
     planta=:planta,
     calle=:calle,
     colonia=:colonia,
     ciudad=:ciudad,
     estado=:estado,
     cp=:cp,
     rfc=:rfc,
     Numero_Clienteidfk=:cliente
     WHERE id=:id';
    $s=$pdo->prepare($sql);
    $s->bindValue(':razonsocial',$_POST['razonsocial']);
    $s->bindValue(':planta',$_POST['planta']);
    $s->bindValue(':calle',$_POST['calle']);
    $s->bindValue(':colonia',$_POST['colonia']);
    $s->bindValue(':ciudad',$_POST['ciudad']);
    $s->bindValue(':estado',$_POST['estado']);
    $s->bindValue(':cp',$_POST['cp']);
    $s->bindValue(':rfc',$_POST['rfc']);
    $s->bindValue(':cliente',$_POST['cliente']);
    $s->bindValue(':id',$_POST['id']);
    $s->execute();

    $pdo->commit();
   }
   catch (PDOException $e)
   {
    $pdo->rollback();
    $mensaje='Hubo un error al tratar de insertar la planta. Favor de intentar nuevamente.'.$e;
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
    exit();
   }
   verPlantas();
  }

/**************************************************************************************************/
/* Borrar una planta */
/**************************************************************************************************/
if(isset($_POST['accion']) and $_POST['accion']=='Borrar')
{
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php'; 
	$id=$_POST['id'];
	try
	{
		$sql='SELECT * FROM plantastbl WHERE id=:id';
		$s= $pdo->prepare($sql);
		$s->bindValue(':id',$id); 
		$s->execute();
		$valores=$s->fetch();
	}
	catch (PDOException $e)
	{
		$mensaje='No se pudo extraer información de la planta'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	include 'formaconfirmaplanta.html.php';
	exit();
}

/**************************************************************************************************/
/* Confirmar borrado de una planta */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='Continuar')
  {
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php'; 
	$id=$_POST['id'];
	try
	{
		$sql='DELETE FROM plantastbl WHERE id=:id';
		$s= $pdo->prepare($sql);
		$s->bindValue(':id',$id); 
		$s->execute();
	}
	catch (PDOException $e)
	{
		$mensaje='No se pudo hacer la confirmacion de eliminación de la planta'.$e;
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}
	verPlantas();
  }

/**************************************************************************************************/
/* Ver tabla de plantas */
/**************************************************************************************************/
  verPlantas();

function verPlantas(){
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try   
	{
		$sql='SELECT id, razonsocial, planta, ciudad, estado
		    FROM plantastbl';
		$s=$pdo->prepare($sql); 
		$s->execute();
		$plantas = $s->fetchAll();
	}
	catch (PDOException $e)
	{
		$mensaje='Hubo un error extrayendo la lista de ordenes de agua.'.$e;
	  	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
	  	exit();
	}
	include 'formaplantas.html.php';
	exit();
}

function getClientes(){
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try
	{
		$resultados=$pdo->query('SELECT Numero_Cliente, Razon_Social FROM clientestbl ORDER BY Razon_Social');
	}
	catch (PDOException $e)
	{
		$mensaje='Hubo un error tratando de obtener la informacion de los representantes';
		include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
		exit();
	}  
	foreach ($resultados as $resultado)
	{
		$clientes[]=array('id'=>$resultado['Numero_Cliente'],
						'nombre'=>$resultado['Razon_Social']);
	}
	return $clientes;
}