<?php
require 'template_init.php';
require 'template_header.php';

$category = '0';

if (isset($_GET['category'])) {
	$category = $_GET['category'];
}

$item_per_page = 20;

if (isset($_GET['page'])) {
	$page = $_GET['page'];
} else {
	$page = 1;
}

$begin = ($page - 1) * $item_per_page;

?>
<div class="container">
	<!--form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
		<input type="search" class="form-control form-control-dark text-bg-dark" placeholder="ค้นหาคอร์ส..." aria-label="Search">
	</form-->
	<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
		<div class="col-md-3 mb-2 mb-md-0">
			<a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
				<svg class="bi" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
			</a>
		</div>
		<form method="get" class="btn-group">
			<input type="submit" class="btn-check" name="category" value="0" id="0" autocomplete="off">
			<label class="btn <?php echo $category == 0 ? "btn-primary" : 'btn-outline-primary' ?>" for="0">คอร์สทั้งหมด</label>
			<?php
				$categories = $db->get_anonymous_category_list($category);
				foreach ($categories as $c) {
					?>
					<input type="submit" class="btn-check" name="category" value="<?php echo $c['id'] ?>" id="<?php echo $c['id'] ?>" autocomplete="off">
					<label class="btn <?php echo ($category == $c['id']) ? "btn-primary" : 'btn-outline-primary' ?>" for="<?php echo $c['id'] ?>"><?php echo $c['name'] ?></label>
					<?php
				}
			?>
		</form>
	</header>
	
	<?php
		$i = 0;
		$courses = $db->get_anonymous_course_list($category, $begin, $item_per_page);
		$page_count = $courses['page_count'];
		$courses = $courses['courses'];
		$num = sizeof($courses);
		echo "<h3 class=\"result\">ผลลัพธ์จำนวน {$num} คอร์ส</h3>";
	?>
	<div class="row mb-3">
		<?php
			foreach ($courses as $c) {
				if ($i == 2) {
					$i = 0;
					echo "</div>";
					echo "<div class=\"row mb-3\">";
				}
				?>
				<div class="col-md-6">
					<div class="card">
						<img src="<?php echo $c['cover_url'] ?>" class="card-img-top object-fit-cover" height="400" alt="course cover">
						<div class="card-body">
							<div class="d-flex justify-content-between">
								<h3 class="card-title"><?php echo $c['name'] ?></h3>
								<h3><span class="badge text-bg-success">฿ <?php echo number_format($c['price']) ?></span></h3>
							</div>
							
							<p class="card-text"><?php echo $c['brief_desc'] ?></p>
							<p class="card-text"><?php echo display_stars($c['avg_rating']) ?></p>
							<p class="card-text">
							<?php $db->generate_category_badge($c['category_id'], $c['category_name']); ?>
							</p>
							<?php $db->generate_course_button($c['id'], "course_list.php", "<a href=\"course_detail.php?id=" . $c['id'] . "\" class=\"btn btn-outline-secondary\">ดูรายละเอียด</a>"); ?>
						</div>
					</div>
				</div>
				<?php
				$i++;
			}
		?>
	</div>
	<nav aria-label="pagination">
		<ul class="pagination">
			<li class="page-item">
				<a class="page-link" href="course_list.php?page=1&category=<?php echo $category;?>">
				<span>&laquo;</span>
				</a>
			</li>
			<?php for ($i = 1; $i <= $page_count; $i++){ ?>
			<li class="page-item">
				<a class="page-link" href="course_list.php?page=<?php echo $i; ?>&category=<?php echo $category;?>">
					<?php echo $i; ?>
				</a>
			</li>
			<?php } ?>
			<li class="page-item">
				<a class="page-link" href="course_list.php?page=<?php echo $page_count;?>&category=<?php echo $category;?>">
				<span>&raquo;</span>
				</a>
			</li>
		</ul>
	</nav>
</div>
<?php
require 'template_footer.php';
?>