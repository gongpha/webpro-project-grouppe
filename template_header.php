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

	<script><?php require "template.js" ?></script>
</head>
<body>
	<header class="p-3">
		<div class="container">
			<div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
				<a href="index.php" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-backpack2-fill" viewBox="0 0 16 16">
						<path d="M5 13h6v-3h-1v.5a.5.5 0 0 1-1 0V10H5z"/>
						<path d="M6 2v.341C3.67 3.165 2 5.388 2 8v1.191l-1.17.585A1.5 1.5 0 0 0 0 11.118V13.5A1.5 1.5 0 0 0 1.5 15h1c.456.607 1.182 1 2 1h7c.818 0 1.544-.393 2-1h1a1.5 1.5 0 0 0 1.5-1.5v-2.382a1.5 1.5 0 0 0-.83-1.342L14 9.191V8a6 6 0 0 0-4-5.659V2a2 2 0 1 0-4 0m2-1a1 1 0 0 1 1 1v.083a6 6 0 0 0-2 0V2a1 1 0 0 1 1-1m0 3a4 4 0 0 1 3.96 3.43.5.5 0 1 1-.99.14 3 3 0 0 0-5.94 0 .5.5 0 1 1-.99-.14A4 4 0 0 1 8 4M4.5 9h7a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-4a.5.5 0 0 1 .5-.5"/>
					</svg>
				</a>
				<ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
					<li class="nav-item">
						<a href="course_list.php" class="nav-link text-white">คอร์ส</a>
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
							<div class="dropdown text-end">
								<a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
									<img src="<?php echo $db->get_my_avatar() ?>" alt="my avatar" width="32" height="32" class="rounded-circle">
								</a>
								<ul class="dropdown-menu text-small" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(0px, 36.6667px, 0px);" data-popper-placement="bottom-start">
									<li><a class="dropdown-item" href="profile.php">โปรไฟล์ของฉัน</a></li>
									<?php if (!$db->is_student()) { ?>
									<li><a class="dropdown-item" href="course_edit.php">เพิ่มคอร์สใหม่</a></li>
									<?php } ?>
									<li><hr class="dropdown-divider"></li>
									<li><a class="dropdown-item" href="signout.php">ลงชื่อออก</a></li>
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