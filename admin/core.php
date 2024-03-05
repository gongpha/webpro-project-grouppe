<?php
require '../common.php';

	session_start();
	class Admin extends SQLite3 {
		function __construct() {
			$this->open('../data.sqlite');
		}

		function signin($username, $password) {
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

		function is_logged_in() {
			return isset($_SESSION["admin"]);
		}

		function go_to_home() {
			header('Location: index.php');
			exit();
		}

		function go_to($page) {
			header('Location: ' . $page);
			exit();
		}

		/////////////////////////////////////

		function get_instructor_simple_list() {
			$sql = "SELECT profile_pic_hash, id, username, first_name || ' ' || last_name AS name FROM instructors";
			$ret = $this->query($sql);
			$instructors = array();
			while($row = $ret->fetchArray(SQLITE3_ASSOC)) {
				$row = prepare_other_data($row, '../');
				$instructors[] = $row;
			}

			return $instructors;
		
		}

		function get_instructor($id) {
			$sql = "SELECT *, first_name || ' ' || last_name AS name FROM instructors WHERE id = " . $id;
			$ret = $this->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);
			$row = prepare_other_data($row, '../');
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

	function generate_password($length = 20){
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`-=~!@#$%^&*()_+,./<>?;:[]{}\|';
		$str = '';
		$max = strlen($chars) - 1;

		for ($i=0; $i < $length; $i++)
			$str .= $chars[random_int(0, $max)];

		return $str;
	}

?>