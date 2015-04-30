<?php 
$a = array('Rango' => 0,
			  'Correccion1' => 1.03,
			  'Correccion2' => 0);

$b = array('Rango' => 0,
			  'Correccion1' => 1.03,
			  'Correccion2' => 0);

$c = array('Rango' => 0,
			  'Correccion1' => 1.03,
			  'Correccion2' => 0);

$array = array();

array_push($array, $a, $b, $c);

$json = json_encode($array);

echo $json;

$array2 = json_decode($json, true);

echo "<br>";

echo "<pre>";
var_dump($array2);
echo "</pre>";


?>