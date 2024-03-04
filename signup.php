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

<style>
	.h-100 {
    height: 67% !important;
	}
	.center-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
<body>
<form action="" method="post">
  <div class="container">
    <div class="row d-flex justify-content-center align-items-center">
      <div class="col-lg-12 col-xl-11">
        <div class="card text-black" style="border-radius: 25px;">
          <div class="card-body p-md-5">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4" style="color: white;">ลงทะเบียน</p>

                <form class="mx-1 mx-md-4">

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
					  <input type="text" id="form31" class="form-control" name="username" placeholder="ชื่อผู้ใช้" value="<?php echo gets('username'); ?>" >
					  <label class="form-label" for="form32"> </label>
					  <input type="firstname" id="form31" class="form-control" name="firstname" placeholder="ชื่อ" value="<?php echo gets('firstname'); ?>">
					  <label class="form-label" for="form32"> </label>
					  <input type="lastname" id="form31" class="form-control" name="lastname" placeholder="นามสกุล" value="<?php echo gets('lastname'); ?>" >
					  <label class="form-label" for="form32"> </label>
					  <input type="email" id="form31" class="form-control" name="email" placeholder="อีเมล" value="<?php echo gets('email'); ?>" >
					  <label class="form-label" for="form32"> </label>
					  <input type="text"  id="form31" class="form-control" name="phone" placeholder="เบอร์โทรศัพท์" value="<?php echo gets('phone'); ?>" >
					  <label class="form-label" for="form32"> </label>
					  <input type="password" id="form31" class="form-control" name="password" placeholder="รหัสผ่าน" value="<?php echo gets('password'); ?>" >
					  <label class="form-label" for="form32"> </label>
					  <input type="password" id="form31" class="form-control" name="password2" placeholder="ยืนยันรหัสผ่าน" value="<?php echo gets('password2'); ?>" >
					  <label class="form-label" for="form32"> </label>
					  <div class="center-container">
					  	<input type="submit" class="btn btn-primary" value="ลงทะเบียน" name="signup" style="margin-top:20px;">
					  </div>
				</form>
					  
                      
                    </div>
                  </div>
				  </div>
				  </div>
				  </div>
				  </div>
	
		
 
		
	</form>
</div>
</div>
</div>

	

<?php
require 'template_footer.php';
?>