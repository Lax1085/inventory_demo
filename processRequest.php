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

if($name<>''){
	$product=array('Product Name'=>$name
	,'Products in Stock'=>$qty
	,'Price per Item'=>$price
	,'Time'=> date('m/d/Y h:i A')
	);
	$products[]=$product;
	var_dump($product);
	var_dump($products);

	$fp = fopen('products.json', 'w');
	fwrite($fp, json_encode($products));
	fclose($fp);
}
?>