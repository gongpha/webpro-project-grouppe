<?php
require 'shopping.php';
require 'common.php';

	session_start();
	class App extends SQLite3 {

		// avatar dir
		private $avatardir = "";

		function __construct($dbfile, $avatardir) {
			$this->open($dbfile);
			$this->avatardir = __DIR__ . $avatardir;
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
					$_SESSION['user']['table'] = "students";
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

		function go_to($page) {
			header('Location: ' . $page);
			exit();
		}

		function get_student($id) {
			$sql = "SELECT *, first_name || ' ' || last_name AS name FROM students WHERE id = " . $id;
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			$row = prepare_other_data($row, '../');
			return $row;
		}

		function go_to_with_motd($page, $motdtype, $motd) {
			motd($motdtype, $motd);
			header('Location: ' . $page);
			exit();
		}

		function generate_course_button($course_id, $current_page_name, $other = "") {
			$show = 0;
			if ($this->is_logged_in()) {
				if ($this->is_course_bought($course_id))
					$show = 2;
				else {
					$shopping = new Shopping();
					if ($shopping->is_in_cart($course_id))
						$show = 1;
				}
			}
			
			if ($show == 0) {
				// show buy
				?>
				<form action="action.php" method="post">
					<input type="hidden" name="action" value="shopping_add_course">
					<input type="hidden" name="course_id" value="<?php echo $course_id ?>">
					<input type="hidden" name="redirect_page" value="<?php echo $current_page_name ?>">
					
					<button type="submit" class="btn btn-success ms-auto">
						<i class="bi bi-cart-plus"></i>
						เพิ่มลงในรถเข็น
					</button>
					<?php echo $other ?>
				</form><?php
			} else if ($show == 1) {
				// in cart
				?>
				<form action="action.php" method="post">
					<input type="hidden" name="action" value="shopping_remove_course">
					<input type="hidden" name="course_id" value="<?php echo $course_id ?>">
					<input type="hidden" name="redirect_page" value="<?php echo $current_page_name ?>">
					
					<button type="submit" class="btn btn-outline-danger ms-auto">
						<i class="bi bi-cart-dash"></i>
						เอาออกจากรถเข็น
					</button>
					<?php echo $other ?>
				</form><?php
			} else {
				// bought
				?>
				<form action="action.php" method="post">
					<input type="hidden" name="action" value="shopping_remove_course">
					<input type="hidden" name="course_id" value="<?php echo $course_id ?>">
					<input type="hidden" name="redirect_page" value="<?php echo $current_page_name ?>">
					
					<button type="submit" class="btn btn-outline-warning ms-auto" disabled>
						<i class="bi bi-cart-check-fill"></i>
						ซื้อแล้ว
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
			if ($id == null) {
				return null;
			}
			$sql = "SELECT courses.id, courses.name, cover_url, brief_desc, desc, category_id, price, course_categories.name as \"category_name\" FROM courses JOIN course_categories ON category_id=course_categories.id WHERE courses.id = $id;";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);

			if (!$row) {
				return null;
			}

			// query instructors
			$sql = "SELECT username, first_name, last_name, role, profile_pic_hash FROM instructors WHERE id IN (SELECT instructor_id FROM course_instructors WHERE course_id = $id);";
			$ret = $this->query($sql);

			$instructors = array();
			while ($row2 = $ret->fetchArray(SQLITE3_ASSOC)) {
				$row2 = prepare_other_data($row2);
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

		function get_avatar($table, $id) {
			$sql = "SELECT id, username, first_name, last_name, profile_pic_hash FROM " . $table . " WHERE id = $id;";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			$row = prepare_other_data($row);
			return $row['pfplink'];
		}

		function get_my_avatar() {
			if ($this->is_logged_in()) {
				return $this->get_avatar($_SESSION['user']['table'], $_SESSION['user']['id']);
			}
			return '';
		}

		function is_course_bought($course_id) {
			if ($this->is_logged_in()) {
				$sql = "SELECT student_id FROM student_owned_courses WHERE course_id = $course_id AND student_id = " . $_SESSION['user']['id'];
				$ret = $this->query($sql);
				$row = $ret->fetchArray(SQLITE3_ASSOC);
				if ($row) {
					return true;
				}
			}
			return false;
		
		}

		function get_course_content($content_id) {
			$sql = "SELECT * FROM course_contents WHERE id = $content_id;";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);

			if (!$row) {
				return null;
			}

			// course name, cover
			$sql = "SELECT name, cover_url FROM courses WHERE id = (SELECT course_id FROM course_contents WHERE id = " . $row['course_id'] . ")";
			$ret = $this->query($sql);
			$row2 = $ret->fetchArray(SQLITE3_ASSOC);
			$row['course_name'] = $row2['name'];
			$row['cover_url'] = $row2['cover_url'];

			// get attachments
			$sql = "SELECT * FROM content_attachments_youtube WHERE course_content = $content_id;";
			$ret = $this->query($sql);
			$attachments_yt = array();
			while ($row2 = $ret->fetchArray(SQLITE3_ASSOC)) {
				array_push($attachments_yt, $row2['url']);
			}
			$row['attachments_yt'] = $attachments_yt;

			// get content ids for badges
			$sql = "SELECT id FROM course_contents WHERE course_id = " . $row['course_id'] . ";";
			$ret = $this->query($sql);
			$content_ids = array();
			while ($row2 = $ret->fetchArray(SQLITE3_ASSOC)) {
				array_push($content_ids, $row2['id']);
			}

			$row['content_ids'] = $content_ids;

			return $row;
			
		}

		function get_owned_course_list() {
			if ($this->is_logged_in()) {
				$sql = "SELECT courses.id, courses.name, cover_url, brief_desc, category_id, course_categories.name as \"category_name\" FROM courses JOIN course_categories ON category_id=course_categories.id WHERE courses.id IN (SELECT course_id FROM student_owned_courses WHERE student_id = " . $_SESSION['user']['id'] . ");";
				$ret = $this->query($sql);

				$results = array();
				while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
					array_push($results, $row);
				}
				return $results;
			}
			return array();
		
		}

		function change_profile_pic($is_student, $id, $file) {
			// if has file
			if ($file['size'] <= 0) {
				return "";
			}

			if ($is_student) {
				$table = "students";
			} else {
				$table = "instructors";
			}

			// get old profile pic hash
			$sql = "SELECT profile_pic_hash FROM $table WHERE id = " . $id;
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			if ($row) {
				$old_profile_pic_hash = $row['profile_pic_hash'];

				if ($old_profile_pic_hash != "")
					unlink($this->avatardir . $old_profile_pic_hash . '.jpg');
			}

			$filename = $file['name'];
			$tmpname = $file['tmp_name'];
			
			resize_image($tmpname, 128, 128, true);

			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$newname = md5_file($tmpname);
			move_uploaded_file($tmpname, $this->avatardir . $newname . '.jpg');
			return $newname;
		}

		/////////////////////////////////////
		// ADMIN

		function admin_signin($username, $password) {
			// check by bcrypt
			$sql = "SELECT * FROM admin_accounts WHERE username = '$username'";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			if($row) {
				if(password_verify($password, $row['password'])) {
					$_SESSION['admin'] = $row;
					return "";
				}
			}
			return "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
		}

		function admin_is_logged_in() {
			return isset($_SESSION["admin"]);
		}

		function get_instructor_simple_list($begin, $search) {
			return $this->get_simple_list('instructors', $begin, $search);
		}

		function get_student_simple_list($begin, $search) {
			return $this->get_simple_list('students', $begin, $search);
		}

		function get_simple_list($table, $begin, $search) {
			$filter = "";
			if ($search != "") {
				$filter = " WHERE username LIKE '%$search%' OR first_name LIKE '%$search%' OR last_name LIKE '%$search%'";
			}
			$sql = "SELECT profile_pic_hash, id, username, first_name || ' ' || last_name AS name FROM $table" . $filter;
			$ret = $this->query($sql);
			$instructors = array();
			while($row = $ret->fetchArray(SQLITE3_ASSOC)) {
				$row = prepare_other_data($row, '../');
				$instructors[] = $row;
			}

			return $instructors;
		}

		function get_object($id, $table) {
			$sql = "SELECT *, first_name || ' ' || last_name AS name FROM $table WHERE id = \"" . $id . "\"";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			$row = prepare_other_data($row, '../');
			return $row;
		}

		/////////////////////////////////////

	}
?>