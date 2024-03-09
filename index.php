<?php
require 'template_init.php';
require 'template_header.php';
require 'template_container_begin.php';
?>

<div class="card text-bg-dark" style="overflow: hidden; margin-bottom: 20px;">
	<div class="card-body p-5" style="background-image: linear-gradient(#00000078, #00000078), url(assets/main_cover.jpg); background-size: cover;">
		<div class="col-lg-6 px-0">
			<h1 class="display-4 fst-italic">@@@ไปเรียน ไอควายกหฟทยกฟ</h1>
			<p class="lead my-3">@@@พัฒนาประเทสชาต</p>
			<p class="lead mb-0"><a href="#" class="text-body-emphasis fw-bold">Continue edging...</a></p>
		</div>
	</div>
</div>

<h1 class="text-center mt-5">@@@ เรียนกับเรา ได้เหี้ไรบ้างวะ</h1>

<div class="row g-4 py-5 row-cols-1 row-cols-lg-3 text-center">
	<div class="feature col">
	<div class="feature-icon d-inline-flex align-items-center justify-content-center fs-2 mb-3 w-100">
		<img src="assets/certificate.png" alt="certificate" width="140" height="140">
	</div>
	<h3 class="fs-2 text-body-emphasis">ได้รับใบ Certificate หลังเรียนจบ</h3>
	<p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
	</div>
	<div class="feature col">
	<div class="feature-icon d-inline-flex align-items-center justify-content-center fs-2 mb-3 w-100">
		<img src="assets/infinity.png" alt="certificate" width="140" height="140">
	</div>
	<h3 class="fs-2 text-body-emphasis">ไม่จำกัดครั้งที่เข้าเรียนและเวลาการดู</h3>
	<p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
	</div>
	<div class="feature col">
	<div class="feature-icon d-inline-flex align-items-center justify-content-center fs-2 mb-3 w-100">
		<img src="assets/level-up.png" alt="certificate" width="140" height="140">
	</div>
	<h3 class="fs-2 text-body-emphasis">การันตีว่าเวลคุณต้องอัพ !</h3>
	<p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
	</div>
</div>

<h2 class="text-center mt-5">@@@ ดูดิ ว่าิอยากเรีนอะไ</h2>

<form action="course_list.php" method="get" class="text-center">
	<input type="submit" class="btn-check" name="category" value="0" id="0" autocomplete="off">
	<div class="row mb-3">
	<?php
		$i = 0;
		$categories = $db->get_anonymous_category_list(0);
		foreach ($categories as $c) {
			if ($i == 2) {
				$i = 0;
				echo "</div>";
				echo "<div class=\"row mb-3\">";
			}
			?>
			<div class="col-md-6 d-grid gap-2">
				<input type="submit" class="btn-check" name="category" value="<?php echo $c['id'] ?>" id="<?php echo $c['id'] ?>" autocomplete="off">
				<label class="btn btn-warning" for="<?php echo $c['id'] ?>"><?php echo $c['name'] ?></label>
			</div>
			<?php
			$i++;
		}
	?>
	</div>
</form>

</div>

<?php
require 'template_container_end.php';
require 'template_footer.php';
?>