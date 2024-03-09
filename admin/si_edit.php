<script>
	function passwordgen() {
		var c = document.getElementById("password-input");
		c.value = window.crypto.getRandomValues(new BigUint64Array(4)).reduce(
			(prev, curr, index) => (
				!index ? prev : prev.toString(36)
			) + (
				index % 2 ? curr.toString(36).toUpperCase() : curr.toString(36)
			)
		);
	}
</script>
<style>
	.required-label:after {
		content:" *";
		color:red;
	}
</style>
<?php

$object = null;
if (isset($_GET['id']))
	$object = $db->get_object($_GET['id'], $table);



if ($object != null) {
	// display instructor detail
	$head = $head_1;
	$subhead = "<h2>{$object['name']}</h2>";
	$cardhead = "แก้ไขข้อมูล";
	$required = "";
	$lock = "disabled";
} else {
	// display a new s/i form
	$head = $head_2;
	$subhead = "";
	$cardhead = "เพิ่มข้อมูลใหม่";

	$object = $defform;
	$required = "required-label";
	$lock = "";
}

?>
<div class="container">
	<h1><?php echo $head ?></h1>
	<?php
		echo $subhead;
	?>
	<div class="card">
		<div class="card-header">
			<?php echo $cardhead ?>
		</div>
		<div class="card-body">
			<form action="<?php echo $logicpage; ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="id" value="<?php echo $object['id'] ?>">
				<div class="mb-3 row">
					<div class="col mb-3">
						<?php if ($object['id'] != 0) { ?>
						<label for="username" class="form-label <?php echo $required ?>">ID</label>
						<input type="text" class="form-control" id="id" name="id" value="<?php echo $object['id'] ?>" disabled>
						<?php } ?>
					</div>
					<div class="<?php if ($object['id'] != 0) { echo "col"; } else { echo ""; } ?> mb-3">
						<label for="username" class="form-label <?php echo $required ?>">ชื่อผู้ใช้</label>
						<input type="text" class="form-control" id="username" name="username" value="<?php echo $object['username'] ?>" <?php echo $lock ?> >
					</div>
				</div>
				<!-- for instructor only -->
				<?php
				if ($table == "instructors") {
					?>
					<div class="mb-3">
						<label for="role" class="form-label
						<?php echo $required ?>">ตำแหน่งปัจจุบัน (เช่น อดีตนักพัฒนาซอฟต์แวร์ที่ Google)</label>
						<div class="mb-3 row">
							<div class="col">
								<input class="form-control" name="role" value="<?php echo $object['role'] ?>">
							</div>
						</div>
					</div>
					<?php
				}
				?>
				<div class="mb-3">
					<label for="password" class="form-label <?php echo $required ?>">รหัสผ่าน<?php echo $object['id'] == 0 ? "" : "ใหม่"; ?></label>
					<div class="mb-3 row">
						<div class="col">
							<input class="form-control" id="password-input" name="password">
						</div>
						<div class="col-auto">
							<button type="button" onclick="passwordgen()" class="btn btn-primary">สุ่มรหัสผ่าน</button>
						</div>
					</div>
				</div>
				<div class="mb-3 row">
					<div class="col mb-3">
						<label for="first_name" class="form-label <?php echo $required ?>">ชื่อ</label>
						<input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $object['first_name'] ?>">
					</div>
					<div class="col mb-3">
						<label for="last_name" class="form-label <?php echo $required ?>">นามสกุล</label>
						<input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $object['last_name'] ?>">
					</div>
				</div>
				<div class="mb-3">
					<label for="email" class="form-label <?php echo $required ?>">อีเมล</label>
					<input type="email" class="form-control" id="email" name="email" value="<?php echo $object['email'] ?>">
				</div>
				<div class="mb-3">
					<label for="phone" class="form-label <?php echo $required ?>">เบอร์โทร</label>
					<input type="text" class="form-control" id="phone" name="phone" value="<?php echo $object['phone'] ?>">
				</div>
				<div class="mb-3">
					<label for="pfplink" class="form-label">รูปโปรไฟล์</label>
					<input type="file" class="form-control mb-4" id="pfpFile" name="pfpfile" accept="image/jpeg">
					<?php if ($object['id'] != 0) { ?>
					<img src="<?php echo $object['pfplink'] ?>" alt="avatar" width="64" height="64" class="rounded-circle me-4">
					<?php } else { ?>
					<input type="hidden" name="new">
					<?php } ?>
				</div>
				<button type="submit" class="btn btn-primary">บันทึก</button>
			</form>
		</div>
	</div>
</div>
<?php

require 'template_footer.php';
?>