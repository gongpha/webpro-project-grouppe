<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
	<script type="text/javascript" src="moment.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.27.0"></script>
	<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@0.1.1"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<title>project grouppe</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai+Looped:wght@100;200;300;400;500;600;700&family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

	<style>
		html {
			--bs-body-font-family: "IBM Plex Sans Thai Looped", sans-serif !important;
		}
	</style>

	<script><?php require "template.js" ?></script>
</head>
<body>
	<header class="p-3">
		<div class="container">
			<div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
				<a href="index.php" style="" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
					<img src="assets/logo.png" alt="logo" width="40" height="40">
					<h3 class="mt-auto mb-auto ms-2 me-5">OnFuckingDemand</h3>
				</a>
				<ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
					<li class="nav-item">
						<a href="course_list.php" class="nav-link text-white">คอร์สเรียน</a>
					</li>
				</ul>

				<!--?php
					
					if (!isset($OPTIONS['nosearch'])) {
						?><form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
						<input type="search" class="form-control form-control-dark text-bg-dark" placeholder="ค้นหาคอร์ส..." aria-label="Search">
						</form>< ?php
					}
				?-->

				<?php
					if (!$db->is_logged_in()) {
						// login & sign-up buttons
						?>
						<div class="auth-section">
							<a href="signin.php" class="btn btn-outline-light me-2">เข้าสู่ระบบ</a>
							<a href="signup.php" class="btn btn-primary">ลงทะเบียน</a>
						</div>
						<?php
					} else {
						// profile
						if ($db->is_student()) {
							$shopping = new Shopping();
							$cart = $shopping->get_count();
						}
						?>
						<div class="d-flex gap-5">
							<?php if ($db->is_student()) { ?>
							<a href="shopping_cart.php" class="btn position-relative">
								<i class="bi bi-cart"></i>
								<?php if ($cart > 0) { ?>
									<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
									<?php echo $cart; ?>
									<span class="visually-hidden">courses in the cart</span>
								<?php } ?>
								</span>
							</a><?php } ?>
							<div class="dropdown text-end mt-auto mb-auto">
								<a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
									<img src="<?php echo $db->get_my_avatar() ?>" alt="my avatar" width="32" height="32" class="rounded-circle">
								</a>
								<ul class="dropdown-menu text-small" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(0px, 36.6667px, 0px);" data-popper-placement="bottom-start">
									<li><a class="dropdown-item" href="profile.php"><i class="bi bi-file-person-fill"></i> โปรไฟล์ของฉัน</a></li>
									<?php if (!$db->is_student()) { ?>
									<li><a class="dropdown-item" href="course_edit.php"><i class="bi bi-journal-plus"></i> เพิ่มคอร์สใหม่</a></li>
									<?php } ?>
									<li><hr class="dropdown-divider"></li>
									<li><a class="dropdown-item" href="signout.php"><i class="bi bi-door-closed-fill"></i> ลงชื่อออก</a></li>
								</ul>
							</div>
						</div>
						<?php
					}
				?>
			</div>
		</div>
	</header>
	<div class="container">
		<?php
			if(isset($_SESSION['motd'])) {
				$motd_class = $_SESSION['motd_class'];
				echo "<div class=\"alert alert-" . $motd_class . "\" role=\"alert\">";
				echo $_SESSION['motd'];
				echo "</div>";
				unset($_SESSION['motd']);
				unset($_SESSION['motd_class']);
			}
		?>
	</div>