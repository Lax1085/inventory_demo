<?php

	if(file_exists("products.json")){
		$string = file_get_contents("products.json");
		$products = json_decode($string, true);
	}
	else
		$products=array();

	echo json_encode($products);
?>