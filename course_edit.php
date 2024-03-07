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
	if (!$db->is_owned_course($id)) {
		$db->go_to_home();
	}
}

require 'template_header.php';

if ($id == 0) {
	$forminfo = array("name" => "", "brief_desc" => "", "desc" => "", "price" => 399);
} else {
	$course = $db->get_course_info($id);
	$forminfo = $course;
}

?>

<body>


<div class="container">
	<?php if ($id == 0) { ?>
		<h1>สร้างคอร์สใหม่</h1>
	<?php } else { ?>
		<h1>แก้ไขคอร์ส <?php echo $id ?> pls</h1>
	<?php } ?>


	<div class="d-flex gap-4">
		<div class="list-group" style="min-width: 250px;">
			<a href="index.php" class="list-group-item list-group-item-action active">ข้อมูลคอร์ส</a>
			<?php if ($id != 0) { ?>
				<a href="students.php" class="list-group-item list-group-item-action ">สถิติ</a>
				<a href="students.php" class="list-group-item list-group-item-action ">แก้ไขเนื้อหา</a>
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
                    <form action="profEdit.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="1">
                        
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
                            <label for="name" class="form-label">ราคาคอร์ส</label>
                            <div class="mb-3 row">
                                <div class="col">
                                    <input type="number" class="form-control" id="price" name="price" value="<?php echo $forminfo["price"] ?>">
                                </div>
                            </div>
                        </div>

                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">รูปปก</label>
                            <div class="card text-bg-dark" style="overflow: hidden;">
								<div class="card-body" style="height: 300px;background-image: linear-gradient(#00000078, black), url(https://media.discordapp.net/attachments/746159419091582997/1194887870960377957/FB_IMG_1704953799917.jpg?ex=65f2968c&amp;is=65e0218c&amp;hm=4f9620e8ae1722774bc4cba156079d4f8eaf310463610ebd5b3865502c98e926&amp;=&amp;format=webp&amp;width=473&amp;height=140); background-size: cover;">
									<div class="d-flex justify-content-between">
									</div>
								</div>
							</div>
						</div>
                        <div class="mb-3">
                            <label for="coverpiclink" class="form-label">อัปโหลด</label>
                            <input type="file" class="form-control mb-4" id="coverPicFile" name="coverpicfile" accept="image/jpeg">
                        </div>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </form>
                </div>
            </div>




		<!---------------------------------------------------->


		<script>
			applyChart({
				data: {"2024-02-19":1,"2024-02-20":1,"2024-02-21":1,"2024-02-22":1,"2024-02-23":1,"2024-02-24":1,"2024-02-25":1,"2024-02-26":1,"2024-02-27":1,"2024-02-28":1,"2024-02-29":1,"2024-03-01":1,"2024-03-02":1,"2024-03-03":1,"2024-03-04":1,"2024-03-05":1,"2024-03-06":1},
				canvasID: "courseSold"
			}, false);
		</script>

		
		<script>
			applyChart({
				data: {"2024-02-06":1,"2024-02-11":1,"2024-02-14":1,"2024-02-21":1,"2024-02-27":1,"2024-02-29":1,"2024-03-01":2,"2024-03-02":1,"2024-03-03":1,"2024-03-05":2,"2024-03-06":1},
				canvasID: "newStudent"
			}, false);
		</script>

		
		<script>
			applyChart({
				data: {"2024-02-19":0,"2024-02-20":0,"2024-02-21":0,"2024-02-22":0,"2024-02-23":0,"2024-02-24":0,"2024-02-25":0,"2024-02-26":0,"2024-02-27":0,"2024-02-28":0,"2024-02-29":0,"2024-03-01":0,"2024-03-02":0,"2024-03-03":0,"2024-03-04":0,"2024-03-05":0,"2024-03-06":111},
				canvasID: "cumulativeEarnings"
			}, true);
		</script>

		</div>
</div>
</div>






<?php
require 'template_footer.php';
?>