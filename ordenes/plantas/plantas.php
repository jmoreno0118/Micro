<?php
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	if($_POST['id'])
	{
		try
		{
			$pdo->beginTransaction();
			$sql='SELECT id, planta FROM plantastbl WHERE Numero_Clienteidfk=:id';
			$s=$pdo->prepare($sql);
			$s->bindValue(':id',$_POST['id']);
			$s->execute();
			$plantas = $s->fetchAll();
			if(count($plantas) > 0){
		 		foreach ($plantas as $key => $value) {
		 			$selected = ($_POST['id'] === $_POST['cliente'] && $_POST['planta'] === $value['id']) ? 'selected' : '';
					echo '<option value="'.$value['id'].'"'.$selected.'>'.$value['planta'].'</option>';
				}
			}else{
				echo '<option selected="selected">--Selecciona planta--</option>';
			}
		}
		catch (PDOException $e)
		{
			echo $e;
		}
	}
?>