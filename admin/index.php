<?php
require 'template_init.php';
require 'template_header.php';

$dashboard_data = $db->get_dashboard_data();

?>

<div class="container">
	<div class="col-12">
		<h1>แดชบอร์ด</h1>
		<p>ยินดีต้อนรับเข้าสู่หน้าผู้ดูแลระบบ</p>
	</div>
	<div class="row">
		<div class="col-lg-6 col-xl-3 mb-4">
			<div class="card bg-primary text-white h-100">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div class="me-3">
							<div class="text-white-75 small">จำนวนผู้เรียน</div>
							<div class="text-lg fw-bold"><?php echo $dashboard_data['student_count'] ?></div>
						</div>
						<i class="bi bi-person-fill"></i>
					</div>
				</div>
			</div>
		</div>
		<!---------------------------------------------------->
		<div class="col-lg-6 col-xl-3 mb-4">
			<div class="card bg-warning text-white h-100">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div class="me-3">
							<div class="text-white-75 small">จำนวนผู้สอน</div>
							<div class="text-lg fw-bold"><?php echo number_format($dashboard_data['instructor_count']); ?></div>
						</div>
						<i class="bi bi-person-badge-fill"></i>
					</div>
				</div>
			</div>
		</div>
		<!---------------------------------------------------->
		<div class="col-lg-6 col-xl-3 mb-4">
			<div class="card bg-secondary text-white h-100">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div class="me-3">
							<div class="text-white-75 small">จำนวนคอร์ส</div>
							<div class="text-lg fw-bold"><?php echo number_format($dashboard_data['course_count']); ?></div>
						</div>
						<i class="bi bi-book"></i>
					</div>
				</div>
			</div>
		</div>
		<!---------------------------------------------------->
		<div class="col-lg-6 col-xl-3 mb-4">
			<div class="card bg-success text-white h-100">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div class="me-3">
							<div class="text-white-75 small">รายได้รวม</div>
							<div class="text-lg fw-bold"><?php echo number_format($dashboard_data['cumulative_earning']); ?></div>
						</div>
						<i class="bi bi-currency-dollar"></i>
					</div>
				</div>
			</div>
		</div>
		<!---------------------------------------------------->
	</div>
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
		<div class="col">
			<div class="card">
				<div class="card-header">
					ยอดผู้สมัครใหม่
				</div>
				<div class="card-body">
					<canvas id="newStudent"></canvas>
				</div>
			</div>
		</div>
	</div>
	<div class="row mb-4">
		<div class="col">
			<div class="card">
				<div class="card-header">
					รายได้รวม
				</div>
				<div class="card-body">
					<canvas id="cumulativeEarnings"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

<?php

load_chart("courseSold", $dashboard_data['purchase_log']);
load_chart("newStudent", $dashboard_data['student_log']);
load_chart("cumulativeEarnings", $dashboard_data['cumulative_earnings'], true);

require 'template_footer.php';
?>