<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	
    <style lang="en" type="text/css" id="dark-mode-custom-style"></style>
    <title>project grouppe</title>
</head>
<style>
    .bg-body-secondary{
        width: 70%;
        margin-left: auto;
        margin-right: auto;
    }
    .container{
        background-color: white;
    }
    h2 {
        margin-left: 2em;
    }
    .row {
    --bs-gutter-x: 1.5rem;
    --bs-gutter-y: 0;
    display: flex;
    flex-wrap: wrap;
    margin-top: calc(-1 * var(--bs-gutter-y));
    }
    .col-lg-4 {
    flex: 0 0 auto;
    width: 33.33333333%;
    }
    .container {
        background-color: transparent;
    }

    
</style>
<?php
	require 'core.php';

	$db = new App();
	//print_r($db->getStudent(1));
?>
<body>
	<header class="p-3">
		<div class="container">
			<div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
				<ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
               
        <img src="https://th.bing.com/th/id/R.0806f2a68705915c82ace87e931d90f8?rik=0t7sV8TKj0xsUg&riu=http%3a%2f%2fpngimg.com%2fuploads%2fletter_L%2fletter_L_PNG106.png&ehk=J5Kqs7w%2bNgGEGMXfWGWCUv%2bhYMUDpZHgy%2f%2fxTf9ied0%3d&risl=&pid=ImgRaw&r=0" alt="" width="32" height="32" class="rounded-circle me-2">
      </a>
				</ul>

				<form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
					<input type="search" class="form-control form-control-dark text-bg-dark" placeholder="ค้นหาคอร์ส" aria-label="Search">
				</form>

				<div class="auth-section">
					<a href="#" class="btn btn-outline-light me-2">ลงชื่อเข้าใช้</a>
					<a href="#" class="btn btn-primary">สมัครสมาชิก</a>
				</div>
			</div>
		</div>
	</header>
    <div class="p-4 p-md-5 mb-4 rounded text-body-emphasis bg-body-secondary">
    <div class="col-lg-6 px-0">
      <h1 class="display-4 fst-italic">@@@ไปเรียน ไอควายกหฟทยกฟ</h1>
      <p class="lead my-3">@@@พัฒนาประเทสชาต</p>
      <p class="lead mb-0"><a href="#" class="text-body-emphasis fw-bold">Continue edging...</a></p>

    </div>
  </div>
  <h2>@@@ เรียนกับเรา ได้เหี้ไรบ้างวะ</h2>
  
  <div class="container marketing">

    <!-- Three columns of text below the carousel -->
    <div class="row">
      <div class="col-lg-4">
        <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-color)"></rect></svg>
        <h3 class="fw-normal">ได้รับใบ Certificate หลังเรียนจบ</h3>
      </div><!-- /.col-lg-4 -->
      <div class="col-lg-4">
        <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-color)"></rect></svg>
        <h3 class="fw-normal">ไม่จำกัดครั้งที่เข้าเรียนและเวลาการดู</h3>
      </div><!-- /.col-lg-4 -->
      <div class="col-lg-4">
        <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-color)"></rect></svg>
        <h3 class="fw-normal">การันตีว่าเวลคุณต้องอัพ !</h3>
      </div><!-- /.col-lg-4 -->
    </div><!-- /.row -->


    <!-- START THE FEATURETTES -->


    <!-- /END THE FEATURETTES -->

  </div>

  <h2>@@@ ดูดิ ว่าิอยากเรีนอะไ</h2>
  <div class="row mb-2">
    <div class="col-md-6">
      <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div class="col p-4 d-flex flex-column position-static">
          <h3 class="mb-0">เขียนโค้ด</h3>
        </div>
        <div class="col-auto d-none d-lg-block">
          <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div class="col p-4 d-flex flex-column position-static">
          <h3 class="mb-0">ปั้นโมเดล 3D</h3>
        </div>
        <div class="col-auto d-none d-lg-block">
          <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
        </div>
      </div>
    </div>
  </div>
  <div class="row mb-2">
    <div class="col-md-6">
      <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div class="col p-4 d-flex flex-column position-static">
          <h3 class="mb-0">การพัฒนาเกม</h3>
        </div>
        <div class="col-auto d-none d-lg-block">
          <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div class="col p-4 d-flex flex-column position-static">
          <h3 class="mb-0">วาดรูป</h3>
        </div>
        <div class="col-auto d-none d-lg-block">
          <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
        </div>
      </div>
    </div>
  </div>
</div>
</footer>
</body>
</html>