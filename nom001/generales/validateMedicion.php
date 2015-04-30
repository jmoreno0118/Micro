<?php
  
if (!empty($_POST['numedicion']) && !empty($_POST['orden']) )
{
    include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
    try   
    {
     $sql='SELECT id FROM generalesaguatbl
          WHERE ordenaguaidfk = :orden AND numedicion = :numedicion';
     $s=$pdo->prepare($sql); 
     $s->bindValue(':orden',$_POST['orden']);
     $s->bindValue(':numedicion',$_POST['numedicion']);
     $s->execute();
    }
    catch (PDOException $e)
    {
     $mensaje='Hubo un error extrayendo la información de parametros.';
     include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/error.html.php';
     exit();
    }
    if($s->fetch())
    {
        echo "false";
    }
    else
    {
        echo "true";
    }
}
else
{
    echo "false";
}
