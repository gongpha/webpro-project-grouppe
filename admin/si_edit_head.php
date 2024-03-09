<?php


/*
echo "<pre>";
print_r($_POST);
echo "</pre>";*/

if (isset($_POST['id'])) {
	// update/new s/i

	$make_new = isset($_POST['new']);

	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];

	$failed = false;

	if ($make_new) {
		$username = $_POST['username'];

		if ($_POST['username'] == "") {
			motd('danger', 'ชื่อผู้ใช้ไม่ถูกต้อง');
			$failed = true;
		}

		// check username
		$sql = "SELECT * FROM $table WHERE username = '$username'";
		$ret = $db->query($sql);
		$row = $ret->fetchArray(SQLITE3_ASSOC);
		if($row) {
			motd('danger', 'ชื่อผู้ใช้นี้มีผู้ใช้แล้ว');
			$failed = true;
		}

		if (!$failed) {
			// check password
			if ($_POST['password'] == "") {
				motd('danger', 'รหัสผ่านไม่ถูกต้อง');
				$failed = true;
			}

			// check email
			$sql = "SELECT * FROM $table WHERE email = '$email'";
			$ret = $db->query($sql);
			$row = $ret->fetchArray(SQLITE3_ASSOC);

			if($row) {
				motd('danger', 'อีเมลนี้มีผู้ใช้แล้ว');
				$failed = true;
			}

			$pfpname = $db->change_profile_pic(false, 0, $_FILES['pfpfile']);

			if (isset($_POST['role'])) {
				$change_role_name = ", role";
				$change_role = ", " . $_POST['role'];
			} else {
				$change_role_name = "";
				$change_role = "";
			}

			if (!$failed) {
				$sql = "INSERT INTO $table (username, email, phone, password, first_name, last_name, profile_pic_hash" . $change_role_name . ") VALUES ('$username', '$email', '$phone', '" . password_hash($_POST['password'], PASSWORD_DEFAULT) . "', '$first_name', '$last_name', '$pfpname', " . $_POST['role'] . ")";
				$ret = $db->exec($sql);
				if(!$ret){
					motd('danger', 'ไม่สามารถลงทะเบียนได้ โปรดลองใหม่อีกครั้ง');
					$failed = true;
				} else {
					motd('success', 'เพิ่มข้อมูลเรียบร้อย');
					$db->go_to('si_list.php');
				}
			}
		}
	} else {
		// if change password
		if ($_POST['password'] != "") {
			$change_password = ", password = '" . password_hash($_POST['password'], PASSWORD_DEFAULT) . "'";
		} else {
			$change_password = "";
		}

		$pfpname = $db->change_profile_pic(false, $_POST['id'], $_FILES['pfpfile']);
		if ($pfpname != "") {
			$change_profile_pic = ", profile_pic_hash = '$pfpname'";
		} else {
			$change_profile_pic = "";
		}

		if (isset($_POST['role'])) {
			$change_role = ", role = '" . $_POST['role'] . "'";
		} else {
			$change_role = "";
		}

		$sql = "UPDATE $table SET first_name = '$first_name', last_name = '$last_name', email = '$email' " . $change_password . $change_role . ", phone = '$phone' " . $change_profile_pic . " WHERE id = " . $_POST['id'];
		$db->exec($sql);
		motd('success', 'บันทึกข้อมูลเรียบร้อย');
		$db->go_to("$table.php");
	}

	$defform = array(
		'id' => (isset($_POST['id']) ? $_POST['id'] : 0),
		'username' => $_POST['username'],
		'first_name' => $_POST['first_name'],
		'last_name' => $_POST['last_name'],
		'email' => $_POST['email'],
		'phone' => $_POST['phone'],
		'pfplink' => ''
	);
} else {
	$defform = array(
		'id' => 0,
		'username' => '',
		'first_name' => '',
		'last_name' => '',
		'email' => '',
		'phone' => '',
		'pfplink' => ''
	);

}

?>