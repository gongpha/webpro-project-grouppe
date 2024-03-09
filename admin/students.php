<?php

require 'template_init.php';
require 'template_header.php';

require 'si_list_init.php';

$pagename = "students.php";
$header = "รายชื่อผู้เรียน";
$button = "";
$show_course = false;
$show_cate = false;
$editpage = "student";

$search = "";
if (isset($_GET['search'])) {
	$search = $_GET['search'];
}

$list = $db->get_student_simple_list($begin, $search, $item_per_page);
require 'si_list.php';
?>