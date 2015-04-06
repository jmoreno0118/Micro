<?php
include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
if(!isset($_POST['idcliente'])){
    echo "false";
    exit();
}
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
    $s->bindValue(':cliente',$_POST['idcliente']);
    $s->execute();

    $pdo->commit();
    echo "true";
}
catch (PDOException $e)
{
    $pdo->rollback();
    echo $e;
}
?>