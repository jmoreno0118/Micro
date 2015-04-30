
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
/* Búsqueda de ordenes de la norma 001 */
/**************************************************************************************************/
  if (isset($_GET['accion']) and $_GET['accion']=='buscar')
  {
    $tablatitulo = 'Ordenes de noma 001';
    $otsproceso = (isset($_GET['otsproceso']))? TRUE : FALSE;
    $ot = (isset($_GET['ot']))? $_GET['ot'] : '';
    $ordenes = buscaordenes($otsproceso, $ot);
    include 'formabuscaordenesnom001.html.php';
    exit();
  }

/**************************************************************************************************/
/* Ver mediciones de una orden de trabajo */
/**************************************************************************************************/
  if(isset($_POST['accion']) and $_POST['accion']=='ver mediciones')
  {
    $_SESSION['ot'] = $_POST['ot'];
  	header('Location: http://'.$_SERVER['HTTP_HOST'].str_replace('?','',$_SERVER['REQUEST_URI']).'generales');
    exit();
  }

/**************************************************************************************************/
/* Acción por defualt, llevar a búsqueda de ordenes */
/**************************************************************************************************/
  $ordenes=buscaordenes(TRUE,'');
  $tablatitulo = 'Ordenes de noma 001';
  include 'formabuscaordenesnom001.html.php';
  exit();

/**************************************************************************************************/
/* Función para buscar ordenes */
/**************************************************************************************************/
  function buscaordenes($otsproceso='', $ot=''){
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    $usuarioactivo = $_SESSION['usuario'];
    try   
    {
      $sql='';
      $select='SELECT ordenestbl.id, ot, Razon_Social, Ciudad, clientestbl.Estado
              FROM ordenestbl
              INNER JOIN clientestbl ON clienteidfk=clientestbl.Numero_Cliente
              INNER JOIN estudiostbl ON ordenidfk=ordenestbl.id
              INNER JOIN representantestbl ON representantestbl.id=ordenestbl.representanteidfk
              INNER JOIN usuarioreptbl ON usuarioreptbl.representanteidfk = representantestbl.id
              INNER JOIN usuariostbl ON usuariostbl.id = usuarioreptbl.usuarioidfk
              WHERE estudiostbl.nombre="NOM 001" and usuariostbl.usuario=:usuario';
      $where = '';
      if ($otsproceso){
        $where .=' AND fechafin IS NULL';
      }else{
        $where .=' AND fechafin IS NOT NULL';
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
      $mensaje='Hubo un error extrayendo la lista de ordenes.'.$e;
      include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php'.$e;
      exit();
    }
    foreach ($s as $linea){
      $ordenes[]=array('id'=>$linea['id'],'ot'=>$linea['ot'],
      'razonsocial'=>$linea['Razon_Social'],
      'ciudad'=>$linea['Ciudad'],
      'estado'=>$linea['Estado']);
    }
    if (isset($ordenes)){
      return $ordenes;
    }else{
      return;
    }
  }