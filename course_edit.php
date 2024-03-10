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

if (isset($_POST['apply'])) {
	if ($id != 0) {
		$ret = $db->edit_course($_POST, $_FILES);
	} else {
		$ret = $db->new_course($_POST, $_FILES);
	}
	
	if ($ret) {
		motd_error($ret);

		if ($id == 0)
			$forminfo = array();
		else
			$forminfo = $db->get_course_info($id);

		$forminfo = array_merge($forminfo, $_POST);
	} else if ($id == 0) {
		$db->go_to_with_motd("profile.php", "success", "คอร์สใหม่ถูกสร้างเรียบร้อย แต่จะยังไม่ถูกเปิดเผยต่อผู้เรียน กรุณาเพิ่มเนื้อหาและเปิดให้ผู้เรียนเห็น");
	} else {
		$id = $_POST['id'];
		$db->go_to_with_motd("course_edit.php?id=" . $id, "success", "คอร์สถูกแก้ไขเรียบร้อย");
	}

} else {
	if ($id == 0) {
		$forminfo = array("name" => "", "brief_desc" => "", "desc" => "", "price" => 399, "cover_hash" => "", "visibility" => 1);
	} else {
		$course = $db->get_course_info($id);
		$forminfo = $course;
	}
}

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
			<a href="course_edit.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>" class="list-group-item list-group-item-action active"><i class="bi bi-info-circle-fill"></i> ข้อมูลคอร์ส</a>
			<?php if ($id != 0) { ?>
				<a href="course_statistics.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>" class="list-group-item list-group-item-action"><i class="bi bi-bar-chart-line-fill"></i> สถิติ</a>
				<a href="course_edit_contents.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>" class="list-group-item list-group-item-action"><i class="bi bi-pencil-fill mt-auto mb-auto"></i> แก้ไขเนื้อหา</a>
				<a href="course_detail.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>" class="list-group-item list-group-item-action"><i class="bi bi-arrow-up-right-square"></i> ดูหน้าคอร์ส</a>
			<?php } ?>
		</div>

		<div class="container">
		<div class="col-12">
			<h1>ข้อมูลคอร์ส</h1>
		</div>
		<div class="card" style="margin-bottom: 30px;">
			<div class="card-header">
				ข้อมูล
			</div>
				<div class="card-body">
					<form action="course_edit.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : '0'; ?>" method="post" enctype="multipart/form-data">
						<?php if ($id != 0) { /* visible to an edit course form */?>
							<input type="hidden" name="id" value="<?php echo $id; ?>">
						<?php } ?>
						
						<div class="mb-3">
							<label for="name" class="form-label">ชื่อคอร์ส</label>
							<div class="mb-3 row">
								<div class="col">
									<input class="form-control" id="course-name" name="name" value="<?php echo $forminfo["name"] ?>">
								</div>
							</div>
						</div>
						<div class="mb-3">
							<label for="brief_desc" class="form-label">คำอธิบายคอร์ส</label>
							<div class="mb-3 row">
								<div class="col">
									<input class="form-control" id="course-brief-description" name="brief_desc" value="<?php echo $forminfo["brief_desc"] ?>">
								</div>
							</div>
						</div>
						<div class="mb-3">
  							<label for="desc" class="form-label">รายละเอียดคอร์ส</label>
  							<textarea class="form-control" id="course-description" name="desc" rows="3"><?php echo $forminfo["desc"] ?></textarea>
						</div>

						<div class="mb-3">
							<label for="desc" class="form-label">หมวดหมู่</label>
							<select class="form-select" name="category_id">
								<option value="0" selected>(เลือกหมวดหมู่)</option>
								<?php
									$categories = $db->get_category_list();
									foreach ($categories as $c) {
										?>
										<option value="<?php echo $c['id'] ?>" <?php echo (isset($forminfo["category_id"]) && $forminfo["category_id"] == $c['id']) ? 'selected' : '' ?> ><?php echo $c['name'] ?></option>
										<?php
									}
								?>
							</select>
						</div>
							
						<div class="mb-3">
							<label for="name" class="form-label">ราคาคอร์ส</label>
							<div class="mb-3 row">
								<div class="col">
									<input type="number" class="form-control" id="price" name="price" value="<?php echo $forminfo["price"] ?>">
								</div>
							</div>
						</div>

						<?php if ($id != 0) { /* visible to an edit course form */?>
						<div class="mb-3">
							<label for="phone" class="form-label">รูปปก</label>
							<div class="card text-bg-dark" style="overflow: hidden;">
								<div class="card-body" style="height: 300px;background-image: url(<?php echo isset($forminfo["cover_url"]) ? $forminfo["cover_url"] : '' ?>); background-size: cover;">
									<div class="d-flex justify-content-between">
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
						
						<div class="mb-3">
							<label for="coverpiclink" class="form-label"><?php echo ($id == 0) ? "อัปโหลดรูปปก" : "อัปโหลด" ?></label>
							<input type="file" class="form-control mb-4" id="coverPicFile" name="coverpicfile" accept="image/jpeg">
						</div>

						<?php if ($id != 0) { /* visible to an edit course form */?>
							<div class="mb-3">
								<input type="checkbox" class="form-check-input" id="visibility" name="visibility" value="1" <?php if (isset($forminfo["visibility"]) && $forminfo["visibility"] == 1) { echo "checked"; } ?>>
								<label for="visibility">เปิดให้ผู้เรียนเห็น</label>
							</div>
						<?php } else { ?>
						<input type="hidden" name="visibility" value="0">
						<div class="alert alert-warning">
							คอร์สที่คุณสร้างจะถูกเปิดให้ผู้เรียนเห็นเมื่อคุณเพิ่มเนื้อหาและเปิดให้ผู้เรียนเห็น
						</div>
						<?php } ?>

						<button type="submit" class="btn btn-primary" name="apply">บันทึก</button>
					</form>
				</div>
			</div>




		<!---------------------------------------------------->
		</div>
</div>
</div>






<?php
require 'template_footer.php';
?>