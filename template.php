<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<title>project grouppe</title>
</head>
<?php
	require 'core.php';

	$db = new Database();
	//print_r($db->getStudent(1));
?>
<body>
	<header class="p-3">
		<div class="container">
			<div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
				<ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
					<li class="nav-item">
						<a href="#" class="nav-link text-white">Home</a>
					</li>
					<li class="nav-item">
						<a href="#" class="nav-link text-white">About</a>
					</li>
					<li class="nav-item">
						<a href="#" class="nav-link text-white">Contact</a>
					</li>
				</ul>

				<form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
					<input type="search" class="form-control form-control-dark text-bg-dark" placeholder="Search..." aria-label="Search">
				</form>

				<div class="auth-section">
					<a href="#" class="btn btn-outline-light me-2">Login</a>
					<a href="#" class="btn btn-primary">Sign-up</a>
				</div>
			</div>
		</div>
	</header>
</body>
</html>