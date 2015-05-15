<?php
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	if($_POST['fecha'])
	{
		try
		{
			$pdo->beginTransaction();
			$sql='SELECT * FROM limitestbl WHERE fecha<=:fecha ORDER BY id DESC';
			$s=$pdo->prepare($sql);
			$s->bindValue(':fecha',$_POST['fecha']);
			$s->execute();
			$limite = $s->fetch();
			echo json_encode($limite);
		}
		catch (PDOException $e)
		{
			echo $e;
		}
	}
?>