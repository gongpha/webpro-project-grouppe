<?php
require 'template_init.php';

if (isset($_GET["id"])) {
	$course_id = $_GET["id"];
} else {
	$db->go_to_home();
	exit();
}

$detail = $db->get_anonymous_course_detail($course_id);

if (!$detail) {
	$db->go_to_home();
	exit();
}

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
				<?php $db->generate_category_badge($detail['category_id'], $detail['category_name']); ?>
			</p>

			<p class="card-text" style="min-height: 90px;">
				<?php echo $detail['desc'] ?>
			</p>
			<div class="d-flex align-items-center text-white text-decoration-none">
				<div class="d-flex flex-column gap-2">
					<?php
						// instructors
						foreach ($detail['instructors'] as $i) {
							?>
								<div class="d-flex align-items-center">
									<img src="<?php echo $i["pfplink"]; ?>" alt="avatar" width="64" height="64" class="rounded-circle me-4">
									<div>
										<h4><?php echo $i["first_name"] . ' ' . $i["last_name"] ?></h4>
										<h6><?php echo $i["role"] ?></h6>
									</div>
								</div>
							<?php
						}
					?>
				</div>
				<span class="ms-auto mt-auto">
					<?php $db->generate_course_button($detail['id'], "course_detail.php?id=" . $detail['id']); ?>
				</span>
			</div>
		</div>
	</div>

	<div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">
		<div class="list-group w-75">
			<?php
				// contents
				$is_bought = $db->is_course_bought($course_id);
				foreach ($detail['contents'] as $c) {
					if ($is_bought) {
					?>
						<a href="course_content.php?id=<?php echo $c['id'] ?>" class="list-group-item list-group-item-action">
							<span class="me-auto d-flex">
								<span class="w-100"><?php echo $c['title'] ?></span>
							</span>
						</a>
					<?php
					} else {
					?>
						<a class="list-group-item list-group-item-action disabled">
							<span class="me-auto d-flex">
								<span class="w-100"><?php echo $c['title'] ?></span>
								<i class="bi bi-lock-fill mt-auto mb-auto"></i>
							</span>
						</a>
					<?php
					}
				}
			?>
		</div>
	</div>
</div>
<?php
require 'template_footer.php';
?>