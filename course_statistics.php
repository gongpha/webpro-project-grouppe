<?php
require 'template_init.php';

if (!$db->is_logged_in()) {
	$db->go_to_home();
}

if ($db->is_student()) {
	$db->go_to_home();
}

if (!isset($_GET['id'])) {
	$id = 0;
} else {
	$id = $_GET['id'];
	if ($id != 0) {
		if (!$db->is_owned_course($id)) {
			$db->go_to_home();
		}
	}
}

if ($id == 0) {
	$db->go_to_home();
}

///////////////

$dashboard_data = $db->get_course_statistics_data($id);

require 'template_header.php';


/*
echo "<pre>";
print_r($forminfo);
echo "POST: ";
print_r($_POST);
echo "</pre>";
*/

?>


<div class="container">
	<?php if ($id == 0) { ?>
		<h1>สร้างคอร์สใหม่</h1>
	<?php } else { ?>
		<h1>แก้ไขคอร์ส</h1>
	<?php } ?>


	<div class="d-flex gap-4">
		<div class="list-group" style="min-width: 250px;">
			<a href="course_edit.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>" class="list-group-item list-group-item-action"><i class="bi bi-info-circle-fill"></i> ข้อมูลคอร์ส</a>
			<?php if ($id != 0) { ?>
				<a href="course_statistics.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>" class="list-group-item list-group-item-action active"><i class="bi bi-bar-chart-line-fill"></i> สถิติ</a>
				<a href="course_edit_contents.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>" class="list-group-item list-group-item-action"><i class="bi bi-pencil-fill mt-auto mb-auto"></i> แก้ไขเนื้อหา</a>
				<a href="course_detail.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>" class="list-group-item list-group-item-action"><i class="bi bi-arrow-up-right-square"></i> ดูหน้าคอร์ส</a>
			<?php } ?>
		</div>

		<div class="container">
		<div class="row mb-4">
		<div class="col">
			<div class="card">
				<div class="card-header">
					ยอดการสั่งซื้อคอร์ส
				</div>
				<div class="card-body">
					<canvas id="courseSold"></canvas>
				</div>
			</div>
		</div>
	</div>
	<div class="row mb-4">
		<div class="col">
			<div class="card">
				<div class="card-header">
					รายได้รวมเฉพาะคอร์สนี้
				</div>
				<div class="card-body">
					<canvas id="cumulativeEarningsSpecific"></canvas>
				</div>
			</div>
		</div>
	</div>
		</div>
	</div>
</div>






<?php

load_chart("courseSold", $dashboard_data['purchase_log']);
load_chart("cumulativeEarningsSpecific", $dashboard_data['cumulative_earning_specific']);

require 'template_footer.php';
?>