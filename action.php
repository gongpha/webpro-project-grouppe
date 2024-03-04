<?php
require 'template_init.php';

	// general action
	if (!isset($_POST["action"])) {
		// go bacc to home
		$db->go_to_home();
		exit();
	}

	$action = $_POST["action"];
	//////////////////////////////////////////
	switch ($action) {
		case "shopping_add_course" : {
			$shopping = new Shopping();
			$shopping->add_course($_POST["course_id"], $_POST["redirect_page"]);
		} break;
		case "shopping_remove_course" : {
			$shopping = new Shopping();
			$shopping->remove_course($_POST["course_id"], $_POST["redirect_page"]);
		} break;
	}
?>