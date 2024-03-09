<?php

require 'template_init.php';
require 'template_header.php';

require 'si_list_init.php';

$pagename = "categories.php";
$header = "รายการหมวดหมู่";
$button = "เพื่มหมวดหมู่ใหม่";
$button_page = "category.php";
$show_course = false;
$show_cate = true;
$editpage = "course";

$search = "";
if (isset($_GET['search'])) {
	$search = $_GET['search'];
}

$list = $db->get_category_simple_list($begin, $search, $item_per_page);
require 'si_list.php';
?>