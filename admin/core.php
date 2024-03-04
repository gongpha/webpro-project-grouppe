<?php
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
	}

	function motd($type, $txt) {
		$_SESSION['motd'] = $txt;
		$_SESSION['motd_class'] = $type;
	}

	function motd_error($txt) {
		motd('danger', $txt);
	}

?>