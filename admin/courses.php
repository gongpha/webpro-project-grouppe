<?php

require 'template_init.php';
require 'template_header.php';

require 'si_list_init.php';

$pagename = "courses.php";
$header = "รายชื่อคอร์ส";
$button = "";
$show_course = true;
$show_cate = false;
$editpage = "course";

$search = "";
if (isset($_GET['search'])) {
	$search = $_GET['search'];
}

$list = $db->get_course_simple_list($begin, $search, $item_per_page);
require 'si_list.php';
?>