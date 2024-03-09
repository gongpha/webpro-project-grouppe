<?php
require 'template_init.php';

if (!$db->is_logged_in()) {
	$db->go_to_home();
}

if ($db->is_student()) {
	$db->go_to_home();
}

if (!isset($_GET['id'])) {
	// new content
	
	if (isset($_GET['for'])) {
		// make a new content for the course id 'for'
		$for = $_GET['for'];
		if (!$db->is_owned_course($for)) {
			$db->go_to_home();
		}
	} else {
		$db->go_to_home();
	}

	if (isset($_POST['apply'])) {
		$ret = $db->new_course_content($_POST);
		if ($ret) {
			motd_error($ret);
			$content_data = $_POST;
		} else {
			$db->go_to_with_motd("course_edit_contents.php?id=" . $for, "success", "เนื้อหาใหม่ถูกสร้างเรียบร้อย");
		}
	} else {
		$content_data = array(
			'title' => '',
			'desc' => '',
			'attachments_yt' => array(''),
		);
		$id = $for;
	}
	$course_id = $for;
} else {
	// edit exist content
	$id = $_GET['id'];
	if ($id != 0) {
		$content_data = $db->get_course_content_for_edit($id);

		if (!$db->is_owned_course($content_data['course_id'])) {
			$db->go_to_home();
		}
	} else {
		$db->go_to_home();
	}

	if (isset($_POST['apply'])) {
		$ret = $db->edit_course_content($_POST);
		if ($ret) {
			motd_error($ret);
			$content_data = $_POST;
		} else {
			$db->go_to_with_motd("course_edit_contents.php?id=" . $content_data['course_id'], "success", "เนื้อหาถูกแก้ไขเรียบร้อย");
		}
	} else if (isset($_POST['delete'])) {
		$ret = $db->delete_course_content($id);
		if ($ret) {
			motd_error($ret);
		} else {
			$db->go_to_with_motd("course_edit_contents.php?id=" . $content_data['course_id'], "success", "เนื้อหาถูกลบเรียบร้อย");
		}
	}
	$course_id = $content_data['course_id'];
}

///////////////

$attachments_yt_first = '';
if (isset($content_data['attachments_yt'])) {
	$attachments_yt_first = $content_data['attachments_yt'][0];
}


require 'template_header.php';

?>


<div class="container">
	<?php if ($id == 0) { ?>
		<h1>สร้างคอร์สใหม่</h1>
	<?php } else { ?>
		<h1>แก้ไขคอร์ส</h1>
	<?php } ?>


	<div class="d-flex gap-4">
		<div class="list-group" style="min-width: 250px;">
			<a href="course_edit.php?id=<?php echo isset($course_id) ? $course_id : '0'; ?>" class="list-group-item list-group-item-action"><i class="bi bi-info-circle-fill"></i> ข้อมูลคอร์ส</a>
			<?php if ($id != 0) { ?>
				<a href="course_statistics.php?id=<?php echo isset($course_id) ? $course_id : '0'; ?>" class="list-group-item list-group-item-action"><i class="bi bi-bar-chart-line-fill"></i> สถิติ</a>
				<a href="course_edit_contents.php?id=<?php echo isset($course_id) ? $course_id : '0'; ?>" class="list-group-item list-group-item-action active"><i class="bi bi-pencil-fill mt-auto mb-auto"></i> แก้ไขเนื้อหา</a>
				<a href="course_detail.php?id=<?php echo isset($course_id) ? $course_id : '0'; ?>" class="list-group-item list-group-item-action"><i class="bi bi-arrow-up-right-square"></i> ดูหน้าคอร์ส</a>
			<?php } ?>
		</div>

		<div class="container">
			<div class="card" style="margin-bottom: 30px;">
				<div class="card-header">
					<?php if (isset($_GET['for'])) { ?>
						เพิ่มเนื้อหาใหม่
					<?php } else { ?>
						แก้ไขข้อมูลคอร์ส
					<?php } ?>
				</div>
					<div class="card-body">
						<form method="post">
							<?php if (isset($_GET['for'])) { ?>
								<input type="hidden" name="for" value="<?php echo $_GET['for'] ?>">
							<?php } else { ?>
								<input type="hidden" name="id" value="<?php echo $id; ?>">
							<?php } ?>
							<div class="mb-3">
								<label for="title" class="form-label">ชื่อเนื้อหา</label>
								<div class="mb-3 row">
									<div class="col">
										<input class="form-control" name="title" value="<?php echo $content_data["title"] ?>">
									</div>
								</div>
							</div>
							<div class="mb-3">
								<label for="desc" class="form-label">รายละเอียดคอร์ส</label>
								<textarea class="form-control" id="course-description" name="desc" rows="3"><?php echo $content_data["desc"] ?></textarea>
							</div>
							<div class="mb-3">
								<label for="name" class="form-label">ลิงก์ YouTube</label>
								<div class="mb-3 row">
									<div class="col">
										<input class="form-control" name="yturl" value="<?php echo $attachments_yt_first ?>">
									</div>
								</div>
							</div>
							<button type="submit" class="btn btn-primary" name="apply">บันทึก</button>
							<?php if (!isset($_GET['for'])) { ?>
								<button type="submit" class="btn btn-outline-danger" name="delete">ลบ</button>
							<?php } ?>
						</form>
					</div>
				</div>




			<!---------------------------------------------------->
			</div>
		</div>
	</div>
</div>

<?php
require 'template_footer.php';
?>