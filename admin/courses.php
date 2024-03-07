<?php

require 'template_init.php';
require 'template_header.php';

$header = "รายชื่อคอร์ส";
$button = "";
$show_course = true;
$show_cate = false;
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

$list = $db->get_course_simple_list($begin, $search);
require 'si_list.php';
?>