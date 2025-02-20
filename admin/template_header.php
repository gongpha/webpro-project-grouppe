<?php

	$menu = array(
		"index" => '<i class="bi bi-graph-up"></i> แดชบอร์ด',
		"instructors" => '<i class="bi bi-person-badge-fill"></i> ผู้สอน',
		"students" => '<i class="bi bi-person-fill"></i> ผู้เรียน',
		"courses" => '<i class="bi bi-book"></i> คอร์ส',
		"categories" => '<i class="bi bi-tag-fill"></i> หมวดหมู่',
	);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.27.0"></script>
	<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@0.1.1"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<title>Admin Panel</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai+Looped:wght@100;200;300;400;500;600;700&family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

	<script><?php require "../template.js" ?></script>

</head>
<body>
	<?php if ($db->admin_is_logged_in()) { ?>
	<header class="p-3">
		<div class="container">
			<div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
				<a href="index.php" class="d-flex align-items-center mb-2 mb-lg-0 text-decoration-none mb-md-0 me-lg-auto">
					<h2>หน้าผู้ดูแล</h2>
				</a>
				<div class="auth-section">
					<span style="margin-right: 20px">สวัสดี, <?php echo $_SESSION['admin']['username'] ?></span>
					<a href="signout.php" class="btn btn-secondary">ลงชื่อออก</a>
				</div>
			</div>
		</div>
	</header>
	<div class="container">
		<?php
	}
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

<?php if ($db->admin_is_logged_in()) { ?>
<div class="container">
	<div class="d-flex gap-4">
		<div class="list-group" style="min-width: 250px;">
			<?php
			foreach ($menu as $key => $value) {
				$active = ($key . '.php' == basename($_SERVER["PHP_SELF"])) ? "active" : "";
				echo "<a href=\"$key.php\" class=\"list-group-item list-group-item-action $active\">$value</a>";
			}
			?>
		</div>
<?php } ?>