<?php
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try
	{
		$pdo->beginTransaction();
		$sql='SELECT uso FROM nom01maximostbl WHERE descargaen=:descargaen group by uso';
		$s=$pdo->prepare($sql);
		$s->bindValue(':descargaen', $_POST['descargaen']);
		$s->execute();
		$uso = $s->fetchAll();
	 	$selected = ($_POST['uso'] === '') ? 'selected' : '';
		echo '<option '.$selected.' disabled value="">Seleccionar</option>';
		if(count($uso) > 0){
	 		foreach ($uso as $key => $value) {
	 			$selected = ($_POST['descargaen'] === $_POST['descarga'] && $_POST['uso'] === strval($value['uso'])) ? 'selected' : '';
				echo '<option value="'.$value['uso'].'" '.$selected.'>'.$value['uso'].'</option>';
			}
		}
	}
	catch (PDOException $e)
	{
		echo $e;
	}
?>