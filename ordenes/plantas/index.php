
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