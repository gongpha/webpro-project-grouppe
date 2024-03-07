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

	function is_in_cart($id) {
		$this->make_sure();
		return in_array($id, $_SESSION["shopping_cart_courses"]);
	}

	function add_course($id, $redirect_page) {
		global $db;

		if ($db->is_course_bought($id)) {
			$db->go_to_with_motd($redirect_page, "danger", "คอร์สนี้ถูกซื้อไปแล้ว");
		}

		if ($this->is_in_cart($id)) {
			// already exist
		} else {
			array_push($_SESSION["shopping_cart_courses"], $id);
		}

		$db->go_to_with_motd($redirect_page, "success", "เพิ่มคอร์สลงในรถเข็นแล้ว สามารถดูได้ที่<a href=\"shopping_cart.php\">รถเข็น</a>");
	}
	function remove_course($id, $redirect_page) {
		global $db;

		if ($this->is_in_cart($id)) {
			if (($key = array_search($id, $_SESSION["shopping_cart_courses"])) !== false) {
				unset($_SESSION["shopping_cart_courses"][$key]);
			}
		} else {
			// not exist
		}

		$db->go_to_with_motd($redirect_page, "success", "ลบคอร์สออกไปจากรถเข็นแล้ว");
	}

	function dump_courses() {
		$this->make_sure();

		// query
		global $db;
		$ids = $_SESSION["shopping_cart_courses"];
		$ids = implode(",", $ids);
		$sql = "SELECT courses.id, courses.name, cover_hash, brief_desc, desc, category_id, price, course_categories.name as \"category_name\" FROM courses JOIN course_categories ON category_id=course_categories.id WHERE courses.id IN ($ids)";
		$ret = $db->query($sql);

		// dump
		$courses = array();
		while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
			$row['cover_url'] = "avatars/" . $row['cover_hash'] . '.jpg';
			array_push($courses, $row);
		}
		return $courses;
	}

	function get_total_price() {
		$this->make_sure();

		// query
		global $db;
		$ids = $_SESSION["shopping_cart_courses"];
		$ids = implode(",", $ids);
		$sql = "SELECT SUM(price) as \"total_price\" FROM courses WHERE id IN ($ids)";
		$ret = $db->query($sql);

		// dump
		$row = $ret->fetchArray(SQLITE3_ASSOC);
		return $row["total_price"];
	}

	function commit_payment() {
		$this->make_sure();

		// pretend to do something

		global $db;
		$ids = $_SESSION["shopping_cart_courses"];

		// transaction
		$db->exec("BEGIN TRANSACTION");

		// add to user's course list
		$sql = "INSERT INTO student_owned_courses (student_id, course_id) VALUES ('{$_SESSION['user']['id']}', ?)";
		$stmt = $db->prepare($sql);
		foreach ($ids as $id) {
			$stmt->bindValue(1, $id, SQLITE3_INTEGER);
			$stmt->execute();
		}

		// update total_sales in courses
		$sql = "UPDATE courses SET total_sales = total_sales + 1 WHERE id = ?";
		$stmt = $db->prepare($sql);
		foreach ($ids as $id) {
			$stmt->bindValue(1, $id, SQLITE3_INTEGER);
			$stmt->execute();
		}

		// add to purchase_log
		$joined = implode(",", $ids);
		$total_price = strval($this->get_total_price());
		$sql = "INSERT INTO purchase_log (from_student_id, type, object_ids, added_datetime, amount) VALUES ('{$_SESSION['user']['id']}', 'BUY_COURSE', '{$joined}', datetime('now'), '{$total_price}')";
		//echo $sql;
		$ret = $db->exec($sql);
		//return false;

		if (!$ret) {
			$db->exec("ROLLBACK");
			$db->go_to_with_motd("shopping_cart.php", "danger", "ไม่สามารถทำการซื้อได้ กรุณาลองใหม่อีกครั้ง");
		}

		// commit
		$ret = $db->exec("COMMIT");

		// clear cart
		$_SESSION["shopping_cart_courses"] = array();

		return true;
	}
}

?>