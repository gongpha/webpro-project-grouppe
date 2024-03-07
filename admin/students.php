<?php

require 'template_init.php';
require 'template_header.php';

$header = "รายชื่อผู้เรียน";
$button = "";
$show_course = false;
$show_cate = false;
$editpage = "student";
if (isset($_GET['begin'])) {
	$begin = $_GET['begin'];
} else {
	$begin = 0;
}

$search = "";
if (isset($_GET['search'])) {
	$search = $_GET['search'];
}

$list = $db->get_student_simple_list($begin, $search);
require 'si_list.php';
?>