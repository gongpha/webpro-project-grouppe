<?php

require 'template_init.php';
require 'template_header.php';

$header = "รายชื่อผู้สอน";
$button = "สร้างบัญชีผู้สอนใหม่";
$show_course = false;
$editpage = "instructor";
if (isset($_GET['begin'])) {
	$begin = $_GET['begin'];
} else {
	$begin = 0;
}

$search = "";
if (isset($_GET['search'])) {
	$search = $_GET['search'];
}

$list = $db->get_instructor_simple_list($begin, $search);
require 'si_list.php';
?>