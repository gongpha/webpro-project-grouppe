<?php

require 'template_init.php';
require 'template_header.php';

require 'si_list_init.php';

$pagename = "instructors.php";
$header = "รายชื่อผู้สอน";
$button = "สร้างบัญชีผู้สอนใหม่";
$button_page = "instructor.php";
$show_course = false;
$show_cate = false;
$editpage = "instructor";

$search = "";
if (isset($_GET['search'])) {
	$search = $_GET['search'];
}

$list = $db->get_instructor_simple_list($begin, $search, $item_per_page);
require 'si_list.php';
?>