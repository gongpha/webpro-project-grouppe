<?php
require 'template_init.php';

if (isset($_POST['submit'])) {
	// do update/new category
	$id = $_POST['id'];
	$name = $_POST['name'];

	if ($id == 0) {
		// new category
		$ret = $db->add_category($name);
		if ($ret != '') {
			// failed
			$db->go_to_with_motd("categories.php", "danger", $ret);
		} else {
			$db->go_to_with_motd("categories.php", "success", "เพื่มหมวดหมู่ใหม่สำเร็จ");
		
		}
	} else {
		// update category
		$ret = $db->edit_category($id, $name);
		if ($ret != '') {
			// failed
			$db->go_to_with_motd("categories.php", "danger", $ret);
		} else {
			$db->go_to_with_motd("categories.php", "success", "แก้ไขหมวดหมู่สำเร็จ");
		}
	}
} else if (isset($_POST['deletes'])) {
	// do delete category
	$id = $_POST['id'];
	$ret = $db->remove_category($id);
	if ($ret != '') {
		// failed
		$db->go_to_with_motd("categories.php", "danger", $ret);
	} else {
		$db->go_to_with_motd("categories.php", "success", "ลบหมวดหมู่สำเร็จ");
	}
}

// if has id, then it's edit page
if (isset($_GET['id'])) {
	$id = $_GET['id'];	
} else {
	$id = 0;
}

if ($id == 0) {
	// new category
	$head = "เพิ่มหมวดหมู่ใหม่";
	$formdata = array(
		'name' => ''
	);
	$required = "required-label";
	$allow_remove = false;
} else {
	// edit category
	$head = "แก้ไขหมวดหมู่";
	$formdata = $db->get_category_data($_GET['id']);
	if ($formdata == null) {
		// no data found
		header("Location: categories.php");
		exit();
	}
	$required = "";
	$allow_remove = $formdata['total_courses'] == 0;
}

require 'template_header.php';

?>

<div class="container">
	<h1><?php echo $head ?></h1>
	<div class="card">
		<div class="card-header">
			ข้อมูลหมวดหมู่
		</div>
		<div class="card-body">
			<form action="category.php" method="post" enctype="multipart/form-data">
				<?php echo $id != 0 ? "<input type=\"hidden\" name=\"id\" value=\"$id\">" : "" ?>
				<div class="mb-3">
					<label for="name" class="form-label <?php echo $required ?>">ชื่อ</label>
					<div class="mb-3 row">
						<div class="col">
							<input class="form-control" name="name" value="<?php echo $formdata["name"]; ?>">
						</div>
					</div>
				</div>
				<button type="submit" name="submit" class="btn btn-primary">บันทึก</button>
				<?php if ($allow_remove) { ?>
					<button type="submit" name="deletes" class="btn btn-danger">ลบ</button>
				<?php } ?>
			</form>
		</div>
	</div>
</div>
<?php

require 'template_footer.php';
?>