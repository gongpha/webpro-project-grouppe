<?php

class Shopping {
	function __construct() {
		global $db;
		if (!$db->is_logged_in()) {
			$db->go_to_with_motd("signin.php", "danger", "กรุณาเข้าสู่ระบบ/ลงทะเบียนก่อน");
		}
	}

	function make_sure() {
		if (!isset($_SESSION["shopping_cart_courses"])) {
			$_SESSION["shopping_cart_courses"] = array();
		}
	}

	function get_count() {
		$this->make_sure();
		return sizeof($_SESSION["shopping_cart_courses"]);
	}

	function is_bought($id) {
		$this->make_sure();
		return in_array($id, $_SESSION["shopping_cart_courses"]);
	}

	function add_course($id, $redirect_page) {
		global $db;

		if ($this->is_bought($id)) {
			// already exist
		} else {
			array_push($_SESSION["shopping_cart_courses"], $id);
		}

		$db->go_to_with_motd($redirect_page, "success", "เพิ่มคอร์สลงในตะกร้าแล้ว สามารถดูได้ที่<a href=\"shopping_cart.php\">รถเข็น</a>");
	}
	function remove_course($id, $redirect_page) {
		global $db;

		if ($this->is_bought($id)) {
			if (($key = array_search($id, $_SESSION["shopping_cart_courses"])) !== false) {
				unset($_SESSION["shopping_cart_courses"][$key]);
			}
		} else {
			// not exist
		}

		$db->go_to_with_motd($redirect_page, "success", "ลบคอร์สออกไปจากตะกร้าแล้ว");
	}

	function dump_courses() {
		$this->make_sure();

		// query
		global $db;
		$ids = $_SESSION["shopping_cart_courses"];
		$ids = implode(",", $ids);
		$sql = "SELECT courses.id, courses.name, cover_url, brief_desc, desc, category_id, price, course_categories.name as \"category_name\" FROM courses JOIN course_categories ON category_id=course_categories.id WHERE courses.id IN ($ids)";
		$ret = $db->query($sql);

		// dump
		$courses = array();
		while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
			array_push($courses, $row);
		}
		return $courses;
	}
}

?>