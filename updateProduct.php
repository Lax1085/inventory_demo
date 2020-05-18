<?php

	if(file_exists("products.json")){
		$string = file_get_contents("products.json");
		$products = json_decode($string, true);
	}
	else
		$products=array();

	$price=(float)$_POST['price'];
	$qty=(int)$_POST['qty'];
	$name=$_POST['name'];

	if(isset($products[$_POST['key']])){
		$products[$_POST['key']]['Price per Item']=(float)$_POST['price'];
		$products[$_POST['key']]['Products in Stock']=(int)$_POST['qty'];
		$products[$_POST['key']]['Product Name']=$_POST['name'];
		$products[$_POST['key']]['Time']=date('m/d/Y h:i A');
		
		$fp = fopen('products.json', 'w');
		fwrite($fp, json_encode($products));
		fclose($fp);		
	}
?>