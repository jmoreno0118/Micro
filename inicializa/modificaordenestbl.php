<?php
   include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
   try
   {
     $pdo->beginTransaction();
	 $sql='ALTER TABLE ordenestbl ADD COLUMN atencion VARCHAR(64) NOT NULL AFTER fecharevision';
	 $pdo->exec($sql);
	 $sql='ALTER TABLE ordenestbl ADD COLUMN atenciontel VARCHAR(32) NOT NULL AFTER atencion';
	 $pdo->exec($sql);
	 $sql='ALTER TABLE ordenestbl ADD COLUMN atencioncorreo VARCHAR(64) NOT NULL AFTER atenciontel';
	 $pdo->exec($sql);	 
     $pdo->commit();
   }
   catch(PDOException $e)
   {
     echo 'no se hizo lo solicitado'.$e;
	 $pdo->rollBack();
	 exit();
   }
   exit();
?>