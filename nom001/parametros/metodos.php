<?php
	include $_SERVER['DOCUMENT_ROOT'].'/reportes/includes/conectadb.inc.php';
	try
	{
		$pdo->beginTransaction();
		$sql='SELECT id, metodo FROM metodostbl WHERE parametro=:parametro ORDER BY id DESC';
		$s=$pdo->prepare($sql);
		$s->bindValue(':parametro', $_POST['parametro']);
		$s->execute();
		$metodos = $s->fetchAll();
		//var_dump($metodos);
		echo '<option disabled value="">Seleccionar</option>';
		if(count($metodos) > 0)
		{
	 		foreach ($metodos as $key => $value)
	 		{
	 			$selected = (strcmp($_POST['seleccionado'], $value['metodo']) === 0) ? 'selected' : '';
				echo '<option value="'.$value['metodo'].'" '.$selected.'>'.$value['metodo'].'</option>';
			}
		}
	}
	catch (PDOException $e)
	{
		echo $e;
	}
?>