<?php
require 'template_init.php';
	
	$form_data = array();

	if (isset($_POST['signup'])) {
		if(
			!empty($_POST['username']) &&
			!empty($_POST['email']) &&
			!empty($_POST['phone']) &&
			!empty($_POST['password']) &&
			!empty($_POST['password2']) &&
			!empty($_POST['firstname']) &&
			!empty($_POST['lastname'])
		){
			$username = $_POST['username'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$password = $_POST['password'];
			$password2 = $_POST['password2'];
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			if($password == $password2){
				$ret = $db->signUp($username, $email, $phone, $password, $firstname, $lastname);
				if ($ret == "") {
					motd('success', 'ลงทะเบียนสำเร็จ โปรดเข้าสู่ระบบ');
					header('Location: index.php');
					exit();
				} else {
					motd_error($ret);
				}
			} else {
				motd_error('รหัสผ่านไม่ตรงกัน');
			}
		} else {
			motd_error('กรุณากรอกข้อมูลให้ครบ');
		}

		// restore form data
		$form_data = $_POST;
	}

	function gets($key) {
		global $form_data;
		if(isset($form_data[$key])) {
			return $form_data[$key];
		}
		return "";
	}

require 'template_header.php';
?>

<body>
	<form action="" method="post">
		<input type="text" name="username" placeholder="Username" value="<?php echo gets('username'); ?>" >
		<input type="firstname" name="firstname" placeholder="First Name" value="<?php echo gets('firstname'); ?>" >
		<input type="lastname" name="lastname" placeholder="Last Name" value="<?php echo gets('lastname'); ?>" >
		<input type="email" name="email" placeholder="Email" value="<?php echo gets('email'); ?>" >
		<input type="text" name="phone" placeholder="Phone" value="<?php echo gets('phone'); ?>" >
		<input type="password" name="password" placeholder="Password" value="<?php echo gets('password'); ?>" >
		<input type="password" name="password2" placeholder="Confirm Password" value="<?php echo gets('password2'); ?>" >
		<input type="submit" value="Sign-up" name="signup">
	</form>

<?php
require 'template_footer.php';
?>