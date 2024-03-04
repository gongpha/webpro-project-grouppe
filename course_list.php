<?php
require 'template_init.php';
require 'template_header.php';
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
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" name="btnradio" id="0" autocomplete="off" checked>
			<label class="btn btn-outline-info" for="0">คอร์สทั้งหมด</label>
			<?php
				$categories = $db->get_anonymous_category_list();
				foreach ($categories as $c) {
					?>
					<input type="radio" class="btn-check" name="btnradio" id="<?php echo $c['id'] ?>" autocomplete="off">
					<label class="btn btn-outline-info" for="<?php echo $c['id'] ?>"><?php echo $c['name'] ?></label>
					<?php
				}
			?>
		</div>
		<div class="col-md-3 text-end">
		</div>
	</header>
	
	<?php
		$i = 0;
		$courses = $db->get_anonymous_course_list();
		$num = sizeof($courses);
		echo "<h3 class=\"result\">ผลลัพธ์จำนวน {$num} คอร์ส</h3>";
	?>
	<div class="row">
		<div class="col-sm">
			<?php

				foreach ($courses as $c) {
					if ($i == 2) {
						$i = 0;
						echo "</div>";
						echo "<div class=\"col-sm\">";
					}
					?>
					<div class="card mb-3">
						<img src="<?php echo $c['cover_url'] ?>" class="card-img-top object-fit-cover" height="400" alt="course cover">
						<div class="card-body">
							<div class="d-flex justify-content-between">
								<h3 class="card-title"><?php echo $c['name'] ?></h3>
								<h3><span class="badge text-bg-success">฿ <?php echo $c['price'] ?></span></h3>
							</div>
							
							<p class="card-text"><?php echo $c['brief_desc'] ?></p>
							<p class="card-text">
								<span class="badge text-bg-secondary"><?php echo $c['category_name'] ?></span>
							</p>
							<?php $db->generate_course_button($c['id'], "course_list.php", "<a href=\"course_detail.php?id=" . $c['id'] . "\" class=\"btn btn-outline-secondary\">ดูรายละเอียด</a>"); ?>
						</div>
					</div>
					<?php
					$i++;
				}
			?>
		</div>
	</div>
</div>
<?php
require 'template_footer.php';
?>