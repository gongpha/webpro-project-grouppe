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

			// check username
			$sql = "SELECT * FROM students WHERE username = '$username'";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			if($row) {
				return "ชื่อผู้ใช้นี้มีผู้ใช้แล้ว";
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
				<form action="course_edit.php" method="get" class="mb-0">
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
			$sql = "SELECT id, name FROM course_categories WHERE (SELECT COUNT(*) as count FROM courses WHERE category_id = course_categories.id)";
			$ret = $this->query($sql);

			$results = array();
			while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
				array_push($results, $row);
			}
			return $results;
		}
		function get_anonymous_course_list($category, $begin, $item_per_page) {
			
			$filter = "";
			if ($category != 0)
				$filter = " AND category_id = $category";

			// total pages
			$sql = "SELECT COUNT(*) as count FROM courses WHERE visibility = 1" . $filter;
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			$total_pages = ceil($row['count'] / $item_per_page);

			$sql = "SELECT courses.id, courses.name, cover_hash, brief_desc, category_id, price, course_categories.name as \"category_name\" FROM courses JOIN course_categories ON category_id=course_categories.id " . $filter . " WHERE visibility = 1 LIMIT $begin, $item_per_page;";
			if ($category != 0)
				$sql = $sql . " WHERE category_id = '{$category}'";
			$ret = $this->query($sql);

			$results = array();
			while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
				$row['cover_url'] = "avatars/" . $row['cover_hash'] . '.jpg';
				array_push($results, $row);
			}
			return array(
				'page_count' => $total_pages,
				'courses' => $results
			);
		}
		////////////////////

		function get_anonymous_course_detail($id) {
			if ($id == null) {
				return null;
			}

			if ($this->is_owned_course($id)) {
				$more_filter = "";
			} else {
				$more_filter = " AND visibility = 1";
			}

			$sql = "SELECT courses.id, courses.name, cover_hash, brief_desc, desc, category_id, price, course_categories.name as \"category_name\", visibility FROM courses JOIN course_categories ON category_id=course_categories.id WHERE courses.id = $id" . $more_filter;
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);

			if (!$row) {
				return null;
			}

			$row['cover_url'] = "avatars/" . $row['cover_hash'] . '.jpg';

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
				if ($this->is_student()) {
					$sql = "SELECT student_id FROM student_owned_courses WHERE course_id = $course_id AND student_id = " . $_SESSION['user']['id'];
				} else {
					$sql = "SELECT id FROM courses WHERE id = $course_id AND owner = " . $_SESSION['user']['id'];
				}
				
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
			$sql = "SELECT id, name, cover_hash, brief_desc, desc, category_id, price, visibility FROM courses WHERE id = $content_id;";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);

			if (!$row) {
				return null;
			}

			// cover url
			$row['cover_url'] = "avatars/" . $row['cover_hash'] . '.jpg';

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

			if ($this->is_owned_course($row['course_id'])) {
				$more_filter = "";
			} else {
				$more_filter = " AND visibility = 1";
			}

			$sql = "SELECT name, cover_hash FROM courses WHERE id = " . $row['course_id'] . $more_filter;
			$ret = $this->query($sql);
			$row2 = $ret->fetchArray(SQLITE3_ASSOC);

			if (!$row2) {
				return null;
			}

			$row['course_name'] = $row2['name'];
			$row['cover_hash'] = $row2['cover_hash'];

			$row['cover_url'] = "avatars/" . $row['cover_hash'] . '.jpg';

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

		function get_owned_course_list($begin, $item_per_page) {
			if ($this->is_logged_in()) {
				// total pages
				$sql = "SELECT COUNT(*) as count FROM courses WHERE id IN (SELECT course_id FROM student_owned_courses WHERE student_id = " . $_SESSION['user']['id'] . ");";
				$ret = $this->query($sql);
				$row = $ret->fetchArray(SQLITE3_ASSOC);
				$total_pages = ceil($row['count'] / $item_per_page);


				$sql = "SELECT courses.id AS id, courses.name, cover_hash, brief_desc, category_id, course_categories.name as \"category_name\", visibility FROM courses JOIN course_categories ON category_id=course_categories.id WHERE courses.id IN (SELECT course_id FROM student_owned_courses WHERE student_id = " . $_SESSION['user']['id'] . ") LIMIT $begin, $item_per_page;";
				$ret = $this->query($sql);

				$results = array();
				while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
					$row['cover_url'] = "avatars/" . $row['cover_hash'] . '.jpg';
					array_push($results, $row);
				}
				return array(
					'page_count' => $total_pages,
					'courses' => $results
				);
			}
			return array();
		
		}

		function get_created_course_list($begin, $item_per_page) {
			if ($this->is_logged_in()) {
				// total pages
				$sql = "SELECT COUNT(*) as count FROM courses WHERE owner = " . $_SESSION['user']['id'] . ";";
				$ret = $this->query($sql);
				$row = $ret->fetchArray(SQLITE3_ASSOC);
				$total_pages = ceil($row['count'] / $item_per_page);
				
				$sql = "SELECT courses.id AS id, courses.name, cover_hash, brief_desc, category_id, course_categories.name as \"category_name\", visibility FROM courses JOIN course_categories ON category_id=course_categories.id WHERE owner = " . $_SESSION['user']['id'] . " LIMIT $begin, $item_per_page;";
				$ret = $this->query($sql);

				$results = array();
				while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
					$row['cover_url'] = "avatars/" . $row['cover_hash'] . '.jpg';
					array_push($results, $row);
				}
				return array(
					'page_count' => $total_pages,
					'courses' => $results
				);
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

		function change_cover($id, $file) {
			// if has file
			if ($file['size'] <= 0) {
				return "";
			}

			// get old profile pic hash
			$sql = "SELECT cover_hash FROM courses WHERE id = " . $id;
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			if ($row) {
				$old_cover_hash = $row['cover_hash'];

				if ($old_cover_hash != "")
					unlink($this->avatardir . $old_cover_hash . '.jpg');
			}

			$filename = $file['name'];
			$tmpname = $file['tmp_name'];
			
			resize_image($tmpname, 600, 400, true);

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
				if (!$this->is_student())
					$role = ", role";
				else
					$role = "";
				$sql = "SELECT id, first_name || ' ' || last_name AS name, profile_pic_hash " . $role . ", created_date FROM " . $_SESSION['user']['table'] . " WHERE id = " . $_SESSION['user']['id'];
				$ret = $this->query($sql);
				$row = $ret->fetchArray(SQLITE3_ASSOC);
				$row = prepare_other_data($row);

				// for students, get owned course list
				if ($_SESSION['user']['table'] == "students") {
					$sql = "SELECT courses.id, courses.name, cover_hash, brief_desc, category_id, course_categories.name as \"category_name\" FROM courses JOIN course_categories ON category_id=course_categories.id WHERE courses.id IN (SELECT course_id FROM student_owned_courses WHERE student_id = " . $_SESSION['user']['id'] . ");";
					$ret = $this->query($sql);

					$results = array();
					while ($row2 = $ret->fetchArray(SQLITE3_ASSOC)) {
						array_push($results, $row2);
					}
					$row['my_courses'] = $results;
				}

				// for instructors, get created course list
				if ($_SESSION['user']['table'] == "instructors") {
					$sql = "SELECT courses.id, courses.name, cover_hash, brief_desc, category_id, course_categories.name as \"category_name\" FROM courses JOIN course_categories ON category_id=course_categories.id WHERE courses.id IN (SELECT course_id FROM course_instructors WHERE owner = " . $_SESSION['user']['id'] . ");";
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

		function validate_course_post($post) {
			// name
			if (!isset($post['name']) || $post['name'] == "") {
				return "กรุณากรอกชื่อคอร์ส";
			}

			// brief_desc
			if (!isset($post['brief_desc']) || $post['brief_desc'] == "") {
				return "กรุณากรอกคำอธิบายคอร์ส";
			}

			// desc
			if (!isset($post['desc']) || $post['desc'] == "") {
				return "กรุณากรอกรายละเอียดคอร์ส";
			}

			// price
			if (!isset($post['price']) || $post['price'] == "") {
				return "กรุณากรอกราคาคอร์ส";
			} else if (!is_numeric($post['price'])) {
				return "กรุณากรอกราคาคอร์สเป็นตัวเลข";
			}

			if (isset($post['price']) && $post['price'] < 0) {
				return "กรุณากรอกราคาคอร์สเป็นค่าบวก";
			}

			// more than 100
			if (isset($post['price']) && $post['price'] <= 100) {
				return "กรุณากรอกราคาคอร์สอย่างน้อย 100 บาท";
			}

			// category
			if (!isset($post['category_id']) || $post['category_id'] == "") {
				return "กรุณาเลือกหมวดหมู่";
			}

			// check category
			$sql = "SELECT id FROM course_categories WHERE id = " . $post['category_id'];
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);

			if (!$row) {
				return "หมวดหมู่ไม่ถูกต้อง";
			}

			return '';
		}

		function new_course($post, $files) {
			$error = $this->validate_course_post($post);
			if ($error != "") {
				return $error;
			}

			// get data
			$name = $post['name'];
			$brief_desc = $post['brief_desc'];
			$desc = $post['desc'];
			$price = $post['price'];
			$category_id = $post['category_id'];
			//$visibility = $post['visibility'];

			// get cover
			$cover = $files['coverpicfile'];
			$cover_hash = $this->change_cover(0, $cover);

			// transaction
			$this->exec('BEGIN');

			// insert
			$sql = "INSERT INTO courses (name, brief_desc, desc, price, category_id, cover_hash, visibility, owner, created_datetime, total_sales) VALUES ('$name', '$brief_desc', '$desc', $price, $category_id, '$cover_hash', 0, " . $_SESSION['user']['id'] . ", CURRENT_TIMESTAMP, 0);";
			$ret = $this->exec($sql);
			if (!$ret) {
				$this->exec('ROLLBACK');
				return "ไม่สามารถสร้างคอร์สได้";
			}

			// get course id
			$sql = "SELECT last_insert_rowid() as id;";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			$course_id = $row['id'];

			// insert course_instructors
			$sql = "INSERT INTO course_instructors (course_id, instructor_id) VALUES ($course_id, " . $_SESSION['user']['id'] . ");";
			$ret = $this->exec($sql);
			if (!$ret) {
				$this->exec('ROLLBACK');
				return "ไม่สามารถสร้างคอร์สได้";
			}

			// commit
			$this->exec('COMMIT');

			return "";
		}

		function edit_course($post, $files) {
			$error = $this->validate_course_post($post);
			if ($error != "") {
				return $error;
			}

			// get data
			$id = $post['id'];
			$name = $post['name'];
			$brief_desc = $post['brief_desc'];
			$desc = $post['desc'];
			$price = $post['price'];
			$category_id = $post['category_id'];
			$visibility = $post['visibility'];

			if ($files['coverpicfile']['size'] > 0) {
				// get cover
				$cover = $files['coverpicfile'];
				$cover_hash = $this->change_cover($id, $cover);
				$add_cover = ", cover_hash = '$cover_hash'";
			} else {
				$add_cover = "";
			}
			// update
			$sql = "UPDATE courses SET name = '$name', brief_desc = '$brief_desc', desc = '$desc', price = $price, category_id = $category_id" . $add_cover . ", visibility = '$visibility' WHERE id = $id;";
			//echo $sql;
			$ret = $this->exec($sql);
			if (!$ret) {
				return "ไม่สามารถแก้ไขคอร์สได้";
			}

			return "";
		}

		function get_course_statistics_data($id) {
			// session that called this must own the course
			// or is admin

			//////////////////
			// graph

			$where = "
				added_datetime >= date('now', '-30 day')
				AND
				object_ids = '$id' OR
				object_ids LIKE '$id' || ',%' OR
				object_ids LIKE '%,' || '$id' OR
				object_ids LIKE '%,' || '$id' || ',%'
			";

			// import data from purchase_log table last 30 days by added_datetime column
			$sql = "SELECT date(added_datetime) as date, COUNT(*) as count FROM purchase_log WHERE ($where);";
			$ret = $this->query($sql);

			$purchase_log = array();
			while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
				if ($row['count'] == 0)
					continue;
				$purchase_log[$row['date']] = $row['count'];
			}

			// import cumulative_earning_specific from purchase_log last 30 days by added_datetime column
			$cumulative_earning_specific = array();
			$sql = "SELECT date(added_datetime) as date, SUM(amount) as sum FROM purchase_log WHERE ($where) GROUP BY date(added_datetime);";
			$ret = $this->query($sql);
			while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
				if ($row['sum'] == 0)
					continue;
				$cumulative_earning_specific[$row['date']] = $row['sum'];
			}


			return array(
				'purchase_log' => json_encode($purchase_log),
				'cumulative_earning_specific' => json_encode($cumulative_earning_specific)
			);

		}
		
		function get_course_content_list() {
			$sql = "SELECT id, title FROM course_contents WHERE course_id = " . $_GET['id'];
			$ret = $this->query($sql);
			$contents = array();
			while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
				$contents[] = $row;
			}
			return $contents;
		}

		function get_course_content_for_edit($id) {
			$sql = "SELECT * FROM course_contents WHERE id = $id;";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);

			// yt
			$sql = "SELECT url FROM content_attachments_youtube WHERE course_content = $id;";
			$ret = $this->query($sql);
			$attachments_yt = array();
			while ($row2 = $ret->fetchArray(SQLITE3_ASSOC)) {
				array_push($attachments_yt, "https://www.youtube.com/watch?v=" . $row2['url']);
			}
			$row['attachments_yt'] = $attachments_yt;

			return $row;
		}

		function edit_course_content($post) {
			// get data
			$id = $post['id'];
			$title = $post['title'];
			$desc = $post['desc'];

			// extract youtube id from youtub url
			// by regex
			$yturl = $post['yturl'];

			preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $yturl, $match);
			$youtube_id = $match[1];

			// transaction
			$this->exec('BEGIN');

			// update
			$sql = "UPDATE course_contents SET title = '$title', desc = '$desc' WHERE id = $id;";
			$ret = $this->exec($sql);
			if (!$ret) {
				$this->exec('ROLLBACK');
				return "ไม่สามารถแก้ไขเนื้อหาได้";
			}

			// update yt
			$sql = "UPDATE content_attachments_youtube SET url = '$youtube_id' WHERE course_content = $id;";
			$ret = $this->exec($sql);
			if (!$ret) {
				$this->exec('ROLLBACK');
				return "ไม่สามารถแก้ไขเนื้อหาได้";
			}

			// commit
			$this->exec('COMMIT');

			return "";
		}

		function new_course_content($post) {
			print_r($post);
			
			// get data
			$course_id = $post['for'];
			$title = $post['title'];
			$desc = $post['desc'];

			// extract youtube id from youtub url
			// by regex
			$yturl = $post['yturl'];

			preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $yturl, $match);
			$youtube_id = $match[1];

			// transaction
			$this->exec('BEGIN');

			// insert
			$sql = "INSERT INTO course_contents (course_id, title, desc, created_datetime) VALUES ($course_id, '$title', '$desc', CURRENT_TIMESTAMP);";
			$ret = $this->exec($sql);
			if (!$ret) {
				$this->exec('ROLLBACK');
				return "ไม่สามารถเพิ่มเนื้อหาได้";
			}

			// get course content id
			$sql = "SELECT last_insert_rowid() as id;";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			$content_id = $row['id'];

			// insert yt
			$sql = "INSERT INTO content_attachments_youtube (course_content, url) VALUES ($content_id, '$youtube_id');";
			$ret = $this->exec($sql);
			if (!$ret) {
				$this->exec('ROLLBACK');
				return "ไม่สามารถเพิ่มเนื้อหาได้";
			}

			// commit
			$this->exec('COMMIT');

			return "";
		}

		function delete_course_content($id) {
			// transaction
			$this->exec('BEGIN');

			// delete yt
			$sql = "DELETE FROM content_attachments_youtube WHERE course_content = $id;";
			$ret = $this->exec($sql);
			if (!$ret) {
				$this->exec('ROLLBACK');
				return "ไม่สามารถลบเนื้อหาได้";
			}

			// delete
			$sql = "DELETE FROM course_contents WHERE id = $id;";
			$ret = $this->exec($sql);
			if (!$ret) {
				$this->exec('ROLLBACK');
				return "ไม่สามารถลบเนื้อหาได้";
			}

			// commit
			$this->exec('COMMIT');

			return "";
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

		function get_instructor_simple_list($begin, $search, $item_per_page) {
			return $this->get_simple_list('instructors', $begin, $search, $item_per_page);
		}

		function get_student_simple_list($begin, $search, $item_per_page) {
			return $this->get_simple_list('students', $begin, $search, $item_per_page);
		}

		function get_simple_list($table, $begin, $search, $item_per_page) {
			$filter = "";
			if ($search != "") {
				$filter = " WHERE username LIKE '%$search%' OR first_name LIKE '%$search%' OR last_name LIKE '%$search%'";
			}

			// get count
			$sql = "SELECT COUNT(*) as count FROM $table" . $filter;
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			$total = $row['count'];
			
			// pagination
			$page_count = ceil($total / $item_per_page);

			$sql = "SELECT profile_pic_hash, id, username, first_name || ' ' || last_name AS name FROM $table" . $filter . " LIMIT $item_per_page OFFSET $begin";
			$ret = $this->query($sql);
			$instructors = array();
			while($row = $ret->fetchArray(SQLITE3_ASSOC)) {
				$row = prepare_other_data($row, '../');
				$instructors[] = $row;
			}

			return array(
				'page_count' => $page_count,
				'data' => $instructors
			);
		}

		function get_course_simple_list($begin, $search, $item_per_page) {
			$filter = "";
			if ($search != "") {
				$filter = " WHERE name LIKE '%$search%' OR brief_desc LIKE '%$search%' OR desc LIKE '%$search%'";
			}

			// get count
			$sql = "SELECT COUNT(*) as count FROM courses" . $filter;
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			$total = $row['count'];

			// pagination
			$page_count = ceil($total / $item_per_page);

			$sql = "SELECT courses.id, courses.name, created_datetime, first_name || ' ' || last_name AS owner_name, total_sales, course_categories.name AS category_name FROM courses JOIN instructors ON owner=instructors.id JOIN course_categories ON category_id=course_categories.id" . $filter . " LIMIT $item_per_page OFFSET $begin";
			$ret = $this->query($sql);
			$instructors = array();
			while($row = $ret->fetchArray(SQLITE3_ASSOC)) {
				$row = prepare_other_data($row, '../');
				$instructors[] = $row;
			}

			return array(
				'page_count' => $page_count,
				'data' => $instructors
			);
		}

		function get_category_simple_list($begin, $search, $item_per_page) {
			$filter = "";
			if ($search != "") {
				$filter = " WHERE name LIKE '%$search%'";
			}

			// get count
			$sql = "SELECT COUNT(*) as count FROM course_categories" . $filter;
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			$total = $row['count'];

			// pagination
			$page_count = ceil($total / $item_per_page);

			$sql = "SELECT id, name FROM course_categories" . $filter . " LIMIT $item_per_page OFFSET $begin";
			$ret = $this->query($sql);
			$instructors = array();
			while($row = $ret->fetchArray(SQLITE3_ASSOC)) {
				$instructors[] = $row;
			}

			// add total courses
			foreach ($instructors as $key => $value) {
				$sql = "SELECT COUNT(*) as count FROM courses WHERE category_id = " . $value['id'];
				$ret = $this->query($sql);
				$row = $ret->fetchArray(SQLITE3_ASSOC);
				$instructors[$key]['total_courses'] = $row['count'];
			}

			return array(
				'page_count' => $page_count,
				'data' => $instructors
			);
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

		function get_category_data($id) {
			$sql = "SELECT * FROM course_categories WHERE id = " . $id;
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);

			if (!$row) {
				return null;
			}

			// get total courses
			$sql = "SELECT COUNT(*) as count FROM courses WHERE category_id = " . $id;
			$ret = $this->query($sql);
			$row2 = $ret->fetchArray(SQLITE3_ASSOC);
			$row['total_courses'] = $row2['count'];

			return $row;
		}

		function add_category($name) {
			$sql = "INSERT INTO course_categories (name) VALUES ('$name');";
			$ret = $this->exec($sql);
			if (!$ret) {
				return "ไม่สามารถเพิ่มหมวดหมู่ได้";
			}
			return "";
		}

		function edit_category($id, $name) {
			$sql = "UPDATE course_categories SET name = '$name' WHERE id = $id;";
			$ret = $this->exec($sql);
			if (!$ret) {
				return "ไม่สามารถแก้ไขหมวดหมู่ได้";
			}
			return "";
		}

		function remove_category($id) {
			// do not remove if has courses
			$sql = "SELECT COUNT(*) as count FROM courses WHERE category_id = $id;";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			if ($row['count'] > 0) {
				return "ไม่สามารถลบหมวดหมู่ที่มีคอร์สอยู่";
			}

			$sql = "DELETE FROM course_categories WHERE id = $id;";
			$ret = $this->exec($sql);
			if (!$ret) {
				return "ไม่สามารถลบหมวดหมู่ได้";
			}
			return "";
		}

		/////////////////////////////////////

	}
?>