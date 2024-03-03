<?php
require 'template_init.php';

	// do login
	if (isset($_POST['signin'])) {
		if(!empty($_POST['username']) && !empty($_POST['password'])) {
			$username = $_POST['username'];
			$password = $_POST['password'];

			$ret = $db->signin($username, $password);
			if ($ret == "") {
				header('Location: index.php');
				exit();
			} else {
				motd_error($ret);
			}
			
		} else {
			motd_error('กรุณากรอกชื่อผู้ใช้และรหัสผ่าน');
		}
	}

require 'template_header.php';
?>

<body>
	<form action="" method="post">
		<input type="text" name="username" placeholder="Username">
		<input type="password" name="password" placeholder="Password">
		<input type="submit" value="เข้าสู่ระบบ" name="signin">
	</form>

<?php
require 'template_footer.php';
?>