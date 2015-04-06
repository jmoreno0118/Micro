
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
/* Guardar una nueva medición de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='guardargenmed')
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
   exit();
  }

include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
try
{
	$sql='SELECT Razon_Social FROM clientestbl WHERE Numero_Cliente =:id';
	$s=$pdo->prepare($sql);
	$s->bindValue(':id', $_POST['idcliente']);
	$s->execute();
	$plantas = $s->fetch();
}
catch (PDOException $e)
{
   $mensaje='Hubo un error tratando de obtener la informacion del cliente';
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php'.$e;
   exit();
}
$pestanapag ='Agrega planta';
$titulopagina ='Agregar una nueva planta al cliente ';
$razonsocial = $plantas['Razon_Social'];
$boton = 'Guardar Planta';
include 'formaplanta.html.php';

?>