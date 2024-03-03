<?php
	session_start();
	class App extends SQLite3 {
		function __construct() {
			$this->open('data.sqlite');
		}

		function getStudent($id) {
			$sql = "SELECT * FROM students WHERE id = $id";
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			return $row;
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
			unset($_SESSION['user']);
		}

		function echo_course_button($course_id) {
			echo "<a href=\"#\" class=\"btn btn-success ms-auto\">เพิ่มลงในตะกร้า</a>";
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