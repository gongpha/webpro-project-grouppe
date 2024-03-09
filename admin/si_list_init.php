<?php

$item_per_page = 10;

if (isset($_GET['page'])) {
	$page = $_GET['page'];
} else {
	$page = 1;
}

$begin = ($page - 1) * $item_per_page;



?>