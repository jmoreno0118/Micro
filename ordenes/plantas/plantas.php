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
		 	$selected = ($_POST['planta'] === '0') ? 'selected' : '';
			echo '<option '.$selected.' disabled value="0">--Selecciona planta--</option>';
			if(count($plantas) > 0){
		 		foreach ($plantas as $key => $value) {
		 			$selected = ($_POST['id'] === $_POST['cliente'] && $_POST['planta'] === strval($value['id']
		 				)) ? 'selected' : '';
					echo '<option value="'.$value['id'].'"'.$selected.'>'.$value['planta'].'</option>';
				}
			}
		}
		catch (PDOException $e)
		{
			echo $e;
		}
	}
?>