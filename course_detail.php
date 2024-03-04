<?php
require 'template_init.php';

if (isset($_GET["id"])) {
	$course_id = $_GET["id"];
} else {
	$db->go_to_home();
	exit();
}

$detail = $db->get_anonymous_course_detail($course_id);

require 'template_header.php';

?>

<div class="container">
	<div class="card text-bg-dark" style="overflow: hidden;">
		<div class="card-body" style="background-image: linear-gradient(#00000078, black), url(<?php echo $detail['cover_url'] ?>); background-size: cover;">
			<div class="d-flex justify-content-between">
				<h3 class="card-title"><?php echo $detail['name'] ?></h3>
				<h3><span class="badge text-bg-success">฿ <?php echo $detail['price'] ?></span></h3>
			</div>
			<p class="card-text"><?php echo $detail['brief_desc'] ?></p>
			<p class="card-text">
				<span class="badge text-bg-secondary"><?php echo $detail['category_name'] ?></span>
			</p>

			<p class="card-text" style="min-height: 90px;">
				<?php echo $detail['desc'] ?>
			</p>
			<div class="d-flex align-items-center text-white text-decoration-none">
				<img src="https://cdn.discordapp.com/channel-icons/1038996352278995044/1bd50e406a531de7c83b94a34132bd6f.webp?size=64" alt="avatar" width="64" height="64" class="rounded-circle me-4">
				<div class="me-auto">
					<h4>@@@@@@@@@</h4>
					<h6>@@@นักพัฒนาเกมจากโลกนอก</h6>
				</div>

				<?php $db->generate_course_button(666, "course_detail.php"); ?>
			</div>
		</div>
	</div>

	<div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">
		<div class="list-group w-75">
			<a href="#" class="list-group-item list-group-item-action">บทที่ 1 : @@@พ่อมึงตาน <i class="bi bi-check-lg"></i></a>
			<a href="#" class="list-group-item list-group-item-action">บทที่ 2 : @@@พ่อมึงตาน <i class="bi bi-check-lg"></i></a>
			<a href="#" class="list-group-item list-group-item-action">บทที่ 3 : @@@พ่อมึงตาน <i class="bi bi-check-lg"></i></a>
			<a href="#" class="list-group-item list-group-item-action">บทที่ 4 : @@@พ่อมึงตาน <i class="bi bi-check-lg"></i></a>
			<a href="#" class="list-group-item list-group-item-action">บทที่ 5 : @@@พ่อมึงตาน <i class="bi bi-check-lg"></i></a>
			<a href="#" class="list-group-item list-group-item-action">บทที่ 6 : @@@พ่อมึงตาน</a>
			<a href="#" class="list-group-item list-group-item-action">บทที่ 7 : @@@พ่อมึงตาน</a>
		</div>
	</div>
</div>
<?php
require 'template_footer.php';
?>