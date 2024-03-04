<?php
require 'shopping.php';

	session_start();
	class App extends SQLite3 {
		function __construct() {
			$this->open('data.sqlite');
		}

		function signUp($username, $email, $phone, $password, $firstname, $lastname) {
			// check phone is valid
			if(!preg_match('/^[0-9]{10}$/', $phone)) {
				return "เบอร์โทรศัพท์ไม่ถูกต้อง";
			}

			// check email
			$sql = "SELECT * FROM students WHERE email = '$email'";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			if($row) {
				return "อีเมลนี้มีผู้ใช้แล้ว";
			}

			$sql = "INSERT INTO students (username, email, phone, password, first_name, last_name) VALUES ('$username', '$email', '$phone', '$password', '$firstname', '$lastname')";
			$ret = $this->exec($sql);
			if(!$ret){
				return "ไม่สามารถลงทะเบียนได้ โปรดลองใหม่อีกครั้ง";
			} else {
				return "";
			}
		}

		function is_logged_in() {
			return isset($_SESSION["user"]);
		}

		function signin($username, $password) {
			// check by bcrypt
			$sql = "SELECT * FROM students WHERE username = '$username'";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			if($row) {
				if(password_verify($password, $row['password'])) {
					$_SESSION['user'] = $row;
					return "";
				}
			}
			return "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
		}

		function signout() {
			session_destroy();
		}

		function go_to_home() {
			header('Location: index.php');
			exit();
		}

		function go_to_with_motd($page, $motdtype, $motd) {
			motd($motdtype, $motd);
			header('Location: ' . $page);
			exit();
		}

		function generate_course_button($course_id, $current_page_name, $other = "") {
			$show_remove = false;
			if ($this->is_logged_in()) {
				$shopping = new Shopping();
				$show_remove = $shopping->is_bought($course_id);
			} else {
				$show_remove = false;
			}
			
			if ($show_remove) {
				// not bought yet
				?>
				<form action="action.php" method="post">
					<input type="hidden" name="action" value="shopping_remove_course">
					<input type="hidden" name="course_id" value="<?php echo $course_id ?>">
					<input type="hidden" name="redirect_page" value="<?php echo $current_page_name ?>">
					
					<button type="submit" class="btn btn-outline-danger ms-auto">
						<i class="bi bi-cart-dash"></i>
						เอาออกจากตะกร้า
					</button>
					<?php echo $other ?>
				</form><?php
			} else {
				// bought
				?>
				<form action="action.php" method="post">
					<input type="hidden" name="action" value="shopping_add_course">
					<input type="hidden" name="course_id" value="<?php echo $course_id ?>">
					<input type="hidden" name="redirect_page" value="<?php echo $current_page_name ?>">
					
					<button type="submit" class="btn btn-success ms-auto">
						<i class="bi bi-cart-plus"></i>
						เพิ่มลงในตะกร้า
					</button>
					<?php echo $other ?>
				</form><?php
			}
		}
		///////////////////
		function get_anonymous_category_list() {
			$sql = "SELECT id, name FROM course_categories;";
			$ret = $this->query($sql);

			$results = array();
			while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
				array_push($results, $row);
			}
			return $results;
		}
		function get_anonymous_course_list($category = 0) {
			$sql = "SELECT courses.id, courses.name, cover_url, brief_desc, category_id, price, course_categories.name as \"category_name\" FROM courses JOIN course_categories ON category_id=course_categories.id;";
			if ($category != 0)
				$sql = $sql . " WHERE category_id = '{$category}'";
			$ret = $this->query($sql);

			$results = array();
			while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
				array_push($results, $row);
			}
			return $results;
		}
		////////////////////

		function get_anonymous_course_detail($id) {
			$sql = "SELECT courses.id, courses.name, cover_url, brief_desc, desc, category_id, price, course_categories.name as \"category_name\" FROM courses JOIN course_categories ON category_id=course_categories.id WHERE courses.id = $id;";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);

			// query instructors
			$sql = "SELECT first_name, last_name, role FROM instructors WHERE id IN (SELECT instructor_id FROM course_instructors WHERE course_id = $id);";
			$ret = $this->query($sql);

			$instructors = array();
			while ($row2 = $ret->fetchArray(SQLITE3_ASSOC)) {
				array_push($instructors, $row2);
			}

			$row['instructors'] = $instructors;

			// query contents
			$sql = "SELECT id, title FROM course_contents WHERE course_id = $id;";
			$ret = $this->query($sql);
			$contents = array();
			while ($row2 = $ret->fetchArray(SQLITE3_ASSOC)) {
				array_push($contents, $row2);
			}

			$row['contents'] = $contents;

			return $row;
		}
	}

	function motd($type, $txt) {
		$_SESSION['motd'] = $txt;
		$_SESSION['motd_class'] = $type;
	}

	function motd_error($txt) {
		motd('danger', $txt);
	}
?>