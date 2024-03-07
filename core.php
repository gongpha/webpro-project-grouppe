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

			$password = password_hash($password, PASSWORD_DEFAULT);

			$sql = "INSERT INTO students (username, email, phone, password, first_name, last_name, created_date) VALUES ('$username', '$email', '$phone', '$password', '$firstname', '$lastname', CURRENT_TIMESTAMP)";
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
					$_SESSION = array();
					$_SESSION['user'] = $row;
					$_SESSION['user']['table'] = "students";
					return "";
				}
			}
			return "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
		}

		function signin_instructor($username, $password) {
			// check by bcrypt
			$sql = "SELECT * FROM instructors WHERE username = '$username'";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			if($row) {
				if(password_verify($password, $row['password'])) {
					$_SESSION = array();
					$_SESSION['user'] = $row;
					$_SESSION['user']['table'] = "instructors";
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

		function get_profile($id) {
			if ($_SESSION['user']['table'] == "students") {
				$sql = "SELECT *, first_name || ' ' || last_name AS name FROM students WHERE id = " . $id;
			} else if ($_SESSION['user']['table'] == "instructors") {
				$sql = "SELECT *, first_name || ' ' || last_name AS name FROM instructors WHERE id = " . $id;
			}
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			$row = prepare_other_data($row);
			return $row;
		}

		function go_to_with_motd($page, $motdtype, $motd) {
			motd($motdtype, $motd);
			header('Location: ' . $page);
			exit();
		}

		function generate_category_badge($id, $name) {
			?>
			<a href="course_list.php?category=<?php echo $id ?>" class="badge text-bg-secondary" style="text-decoration: none;"><?php echo $name ?></a>
			<?php
		}

		function generate_course_button($course_id, $current_page_name, $other = "") {
			$show = 0;
			if ($this->is_logged_in()) {
				if (!$this->is_student()) {
					if ($this->is_owned_course($course_id))
						$show = 3;
					else
						$show = 4;
				} else if ($this->is_course_bought($course_id))
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
			} else if ($show == 2) {
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
			} else if ($show == 3) {
				// edit
				?>
				<form action="course_edit.php" method="get">
					<input type="hidden" name="id" value="<?php echo $course_id ?>">
					<button type="submit" class="btn btn-warning ms-auto">
						<i class="bi bi-pencil-fill"></i>
						แก้ไขคอร์ส
					</button>
					<?php echo $other ?>
				</form><?php
			} else if ($show == 4) {
				// show nothing
				echo $other;
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
			
			$filter = "";
			if ($category != 0)
				$filter = " AND category_id = $category";

			$sql = "SELECT courses.id, courses.name, cover_url, brief_desc, category_id, price, course_categories.name as \"category_name\" FROM courses JOIN course_categories ON category_id=course_categories.id " . $filter;
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

		function get_course_info($content_id) {
			// fetch course info
			$sql = "SELECT id, name, cover_url, brief_desc, desc, category_id, price, visibility FROM courses WHERE id = $content_id;";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);

			return $row;
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
				$sql = "SELECT courses.id AS id, courses.name, cover_url, brief_desc, category_id, course_categories.name as \"category_name\" FROM courses JOIN course_categories ON category_id=course_categories.id WHERE courses.id IN (SELECT course_id FROM student_owned_courses WHERE student_id = " . $_SESSION['user']['id'] . ");";
				$ret = $this->query($sql);

				$results = array();
				while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
					array_push($results, $row);
				}
				return $results;
			}
			return array();
		
		}

		function get_created_course_list() {
			if ($this->is_logged_in()) {
				$sql = "SELECT courses.id AS id, courses.name, cover_url, brief_desc, category_id, course_categories.name as \"category_name\" FROM courses JOIN course_categories ON category_id=course_categories.id WHERE owner = " . $_SESSION['user']['id'] . ";";
				$ret = $this->query($sql);

				$results = array();
				while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
					array_push($results, $row);
				}
				return $results;
			}
			return array();
		}

		function is_owned_course($course_id) {
			if ($this->is_logged_in()) {
				$sql = "SELECT id FROM courses WHERE id = $course_id AND owner = " . $_SESSION['user']['id'];
				$ret = $this->query($sql);
				$row = $ret->fetchArray(SQLITE3_ASSOC);
				if ($row) {
					return true;
				}
			}
			return false;
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

		function is_student() {
			return $_SESSION['user']['table'] == "students";
		}

		function get_my_profile() {
			// for students and instructors
			if ($this->is_logged_in()) {
				$sql = "SELECT id, first_name || ' ' || last_name AS name, profile_pic_hash, created_date FROM " . $_SESSION['user']['table'] . " WHERE id = " . $_SESSION['user']['id'];
				$ret = $this->query($sql);
				$row = $ret->fetchArray(SQLITE3_ASSOC);
				$row = prepare_other_data($row);

				// for students, get owned course list
				if ($_SESSION['user']['table'] == "students") {
					$sql = "SELECT courses.id, courses.name, cover_url, brief_desc, category_id, course_categories.name as \"category_name\" FROM courses JOIN course_categories ON category_id=course_categories.id WHERE courses.id IN (SELECT course_id FROM student_owned_courses WHERE student_id = " . $_SESSION['user']['id'] . ");";
					$ret = $this->query($sql);

					$results = array();
					while ($row2 = $ret->fetchArray(SQLITE3_ASSOC)) {
						array_push($results, $row2);
					}
					$row['my_courses'] = $results;
				}

				// for instructors, get created course list
				if ($_SESSION['user']['table'] == "instructors") {
					$sql = "SELECT courses.id, courses.name, cover_url, brief_desc, category_id, course_categories.name as \"category_name\" FROM courses JOIN course_categories ON category_id=course_categories.id WHERE courses.id IN (SELECT course_id FROM course_instructors WHERE owner = " . $_SESSION['user']['id'] . ");";
					$ret = $this->query($sql);

					$results = array();
					while ($row2 = $ret->fetchArray(SQLITE3_ASSOC)) {
						array_push($results, $row2);
					}
					$row['my_courses'] = $results;
				}

				return $row;
			}
			return array();
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
					$_SESSION = array();
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

		function get_course_simple_list($begin, $search) {
			$filter = "";
			if ($search != "") {
				$filter = " WHERE name LIKE '%$search%' OR brief_desc LIKE '%$search%' OR desc LIKE '%$search%'";
			}
			$sql = "SELECT courses.id, name, created_datetime, first_name || ' ' || last_name AS owner_name, total_sales FROM courses JOIN instructors ON owner=instructors.id" . $filter;
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

		function get_dashboard_data() {
			$sql = "SELECT COUNT(*) AS count FROM students";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			$student_count = $row['count'];

			$sql = "SELECT COUNT(*) AS count FROM instructors";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			$instructor_count = $row['count'];

			$sql = "SELECT COUNT(*) AS count FROM courses";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			$course_count = $row['count'];

			//////////////////
			// graph

			// import data from purchase_log table last 30 days by added_datetime column
			$sql = "SELECT date(added_datetime) as date, COUNT(*) as count FROM purchase_log WHERE added_datetime >= date('now', '-30 day') GROUP BY date(added_datetime);";
			$ret = $this->query($sql);
			$purchase_log = array();
			while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
				$purchase_log[$row['date']] = $row['count'];
			}

			// import data from students table last 30 days by created_datetime column
			$sql = "SELECT date(created_date) as date, COUNT(*) as count FROM students WHERE created_date >= date('now', '-30 day') GROUP BY date(created_date);";
			$ret = $this->query($sql);
			$student_log = array();
			while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
				$student_log[$row['date']] = $row['count'];
			}

			// import cumulative_earnings from purchase_log
			$sql = "SELECT date(added_datetime) as date, SUM(amount) as sum FROM purchase_log WHERE added_datetime >= date('now', '-30 day') GROUP BY date(added_datetime);";
			$ret = $this->query($sql);
			$cumulative_earnings = array();
			while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
				$cumulative_earnings[$row['date']] = $row['sum'];
			}

			// import cumulative_earning from purchase_log (no limit)
			$sql = "SELECT SUM(amount) as sum FROM purchase_log;";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			$cumulative_earning = $row['sum'];

			return array(
				'student_count' => $student_count,
				'instructor_count' => $instructor_count,
				'course_count' => $course_count,
				'cumulative_earning' => $cumulative_earning,

				'purchase_log' => json_encode($purchase_log),
				'student_log' => json_encode($student_log),
				'cumulative_earnings' => json_encode($cumulative_earnings),
			);
		}

		/////////////////////////////////////

	}
?>