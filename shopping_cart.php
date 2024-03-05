<?php
require 'template_init.php';
require 'template_header.php';
require 'template_container_begin.php';

$shopping = new Shopping();
?>
<h1 class="mb-4">รถเข็นของคุณ</h1>

<?php

// buying list
$courses = $shopping->dump_courses();

if (sizeof($courses) == 0) {
	?>
	<div class="alert alert-info" role="alert">
		<h4 class="alert-heading">ไม่มีคอร์สในรถเข็นของคุณ</h4>
		<hr>
		<p class="mb-0">เลือกคอร์สที่คุณต้องการซื้อจากหน้า<a href="course_list.php">รายการคอร์ส</a></p>
	</div>
	<?php
} else {
foreach ($courses as $c) {
	?>
	<div class="card mb-3">
		<div class="row g-0">
			<div class="col-md-4" style="height : 250px;">
				<img src="<?php echo $c['cover_url'] ?>" class="img-fluid rounded-start object-fit-cover" alt="course cover" style="width: fit-content; height: 100%;">
			</div>
			<div class="col-md-8">
				<div class="card-body">
				<div class="d-flex justify-content-between">
					<h5 class="card-title"><?php echo $c['name'] ?></h5>
					<h5><span class="badge text-bg-success">฿ <?php echo $c['price'] ?></span></h5>
				</div>
				<p class="card-text"><?php echo $c['brief_desc'] ?></p>
				<p class="card-text">
				<?php $db->generate_category_badge($c['category_id'], $c['category_name']); ?>
				</p>
				<?php $db->generate_course_button($c['id'], "shopping_cart.php", "<a href=\"course_detail.php?id=" . $c['id'] . "\" class=\"btn btn-outline-secondary\">ดูรายละเอียด</a>"); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

?>
<hr/>
<table class="table table-borderless">
	<tr class="fs-3">
		<td>
			ราคารวม
		</td>
		<td class="text-end">
			฿ <?php echo $shopping->get_total_price(); ?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td class="text-end">
			<a href="checkout.php" class="btn btn-success">ดำเนินการชำระเงิน</a>
		</td>
	</tr>
</table>
<?php
}

require 'template_container_end.php';
require 'template_footer.php';
?>