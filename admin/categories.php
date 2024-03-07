<?php

require 'template_init.php';
require 'template_header.php';

$header = "รายการหมวดหมู่";
$button = "เพื่มหมวดหมู่ใหม่";
$button_page = "category.php";
$show_course = false;
$show_cate = true;
$editpage = "course";
if (isset($_GET['begin'])) {
	$begin = $_GET['begin'];
} else {
	$begin = 0;
}

$search = "";
if (isset($_GET['search'])) {
	$search = $_GET['search'];
}

$list = $db->get_category_simple_list($begin, $search);
require 'si_list.php';
?>