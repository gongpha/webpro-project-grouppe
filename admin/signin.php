<?php
$noredirect = true;
require 'template_init.php';

	if ($db->is_logged_in()) {
		$db->go_to_home();
	}

	// do login
	if (isset($_POST['signin'])) {
		if(!empty($_POST['username']) && !empty($_POST['password'])) {
			$username = $_POST['username'];
			$password = $_POST['password'];

			$ret = $db->admin_signin($username, $password);
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

<div class="col d-flex justify-content-center" style="margin-top: 64px">
	<div class="card" style="min-width:1000px">
		<div class="card-body">
			<form method="post">
				<h2>หน้าผู้ดูแล</h2>
				<div class="mb-3">
					<label for="username" class="form-label">ชื่อผู้ใช้</label>
					<input type="text" class="form-control" name="username">
				</div>
				<div class="mb-3">
					<label for="password" class="form-label">รหัสผ่าน</label>
					<input type="password" class="form-control" name="password">
				</div>
				<div class="mb-3">
				<button type="submit" class="btn btn-primary" name="signin">เข้าสู่ระบบ</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
require 'template_footer.php';
?>