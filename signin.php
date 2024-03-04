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
<style>
	.h-100 {
    height: 67% !important;
	}
	.center-container {
		margin-top: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
<body>
<form action="" method="post">
  <div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-12 col-xl-11">
        <div class="card text-black" style="border-radius: 25px;">
          <div class="card-body p-md-5">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4" style="color: white;">เข้าสู่ระบบ</p>

                <form class="mx-1 mx-md-4">

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
						
					  <input type="text" id="form31" class="form-control" name="username" placeholder="ชื่อผู้ใช้">
					  <label class="form-label" for="form32"> </label>
					  <input type="password" id="form31" class="form-control" name="password" placeholder="รหัสผ่าน">
					  <label class="form-label" for="form32"> </label>
					  <div class="center-container">
					  <div class="auth-section">
					  	<input type="submit" class="btn btn-primary" value="เข้าสู่ระบบ" name="signin">
					  	<a href="signup.php" class="btn btn-outline-light me-2">ลงทะเบียน</a>
					  </div>
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
</div>
<?php
require 'template_footer.php';
?>