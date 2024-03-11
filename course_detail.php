<?php
require 'template_init.php';

if (isset($_POST["rate"])) {
	$rating = $_POST["rating"];
	$course_id = $_POST["course_id"];
	if ($rating < 1 || $rating > 5) {
		// remove post
		$db->go_to_with_motd("course_detail.php?id=" . $course_id, "danger", "คะแนนต้องอยู่ระหว่าง 1 ถึง 5");
		exit();
	}
	$comment = $_POST["comment"];
	$db->rate_course($course_id, $rating, $comment);

	// refresh
	$db->go_to_with_motd("course_detail.php?id=" . $course_id, "success", "รีวิวของคุณถูกบันทึกแล้ว");
}

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
				<h3><span class="badge text-bg-success">฿ <?php echo number_format($detail['price']) ?></span></h3>
			</div>
			<p class="card-text"><?php echo $detail['brief_desc'] ?></p>
			<p class="card-text">
				<?php if ($detail['visibility'] == 0) { ?>
					<span class="badge bg-warning text-dark">ซ่อนจากสาธารณะ</span>
				<?php } ?>
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

	<div class="p-4 gap-4 py-md-5 align-items-center justify-content-center">
		<div class="list-group w-75 ms-auto me-auto">
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

		<?php
			//print_r($detail);
			if (isset($detail['my_review'])) {
				?>
				<div class="card mt-3" style="margin-bottom: 30px;">
					<div class="card-header">
						รีวิวของฉัน
					</div>
					<div class="card-body">
						<p class="card-text">
							<?php $db->display_review($detail['my_review']) ?>
						</p>
					</div>
				</div>
			<?php
			// show form
			} else if (isset($detail['show_review_form'])) {
				?>
				<div class="card mt-3" style="margin-bottom: 30px;">
					<div class="card-header">
						เขียนรีวิว
					</div>
					<div class="card-body">
						<form action="course_detail.php" method="post">
							<input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
							<input type="hidden" name="rating" id="rating" value="0">

							<div class="form-group mb-2">
								<script>
									function setRating(rating) {
										if (rating == 1) {
											document.getElementById("rate1").classList.add("btn-warning");
											document.getElementById("rate2").classList.remove("btn-warning");
											document.getElementById("rate3").classList.remove("btn-warning");
											document.getElementById("rate4").classList.remove("btn-warning");
											document.getElementById("rate5").classList.remove("btn-warning");
											document.getElementById("rating").value = 1;
										} else if (rating == 2) {
											document.getElementById("rate1").classList.add("btn-warning");
											document.getElementById("rate2").classList.add("btn-warning");
											document.getElementById("rate3").classList.remove("btn-warning");
											document.getElementById("rate4").classList.remove("btn-warning");
											document.getElementById("rate5").classList.remove("btn-warning");
											document.getElementById("rating").value = 2;
										} else if (rating == 3) {
											document.getElementById("rate1").classList.add("btn-warning");
											document.getElementById("rate2").classList.add("btn-warning");
											document.getElementById("rate3").classList.add("btn-warning");
											document.getElementById("rate4").classList.remove("btn-warning");
											document.getElementById("rate5").classList.remove("btn-warning");
											document.getElementById("rating").value = 3;
										} else if (rating == 4) {
											document.getElementById("rate1").classList.add("btn-warning");
											document.getElementById("rate2").classList.add("btn-warning");
											document.getElementById("rate3").classList.add("btn-warning");
											document.getElementById("rate4").classList.add("btn-warning");
											document.getElementById("rate5").classList.remove("btn-warning");
											document.getElementById("rating").value = 4;
										} else if (rating == 5) {
											document.getElementById("rate1").classList.add("btn-warning");
											document.getElementById("rate2").classList.add("btn-warning");
											document.getElementById("rate3").classList.add("btn-warning");
											document.getElementById("rate4").classList.add("btn-warning");
											document.getElementById("rate5").classList.add("btn-warning");
											document.getElementById("rating").value = 5;
										}
									}
								</script>
								<button onclick="setRating(1)" id="rate1" type="button" class="btn btn-sm">
									<i class="bi bi-star-fill"></i>
								</button>
								<button onclick="setRating(2)" id="rate2" type="button" class="btn btn-sm">
									<i class="bi bi-star-fill"></i>
								</button>
								<button onclick="setRating(3)" id="rate3" type="button" class="btn btn-sm">
									<i class="bi bi-star-fill"></i>
								</button>
								<button onclick="setRating(4)" id="rate4" type="button" class="btn btn-sm">
									<i class="bi bi-star-fill"></i>
								</button>
								<button onclick="setRating(5)" id="rate5" type="button" class="btn btn-sm">
									<i class="bi bi-star-fill"></i>
								</button>
							</div>

							<div class="form-group mb-2">
								<label for="comment">ความคิดเห็น</label>
								<textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
							</div>
							<button type="submit" name="rate" class="btn btn-primary">ส่ง</button>
						</form>
					</div>
				</div>
			<?php
			}
		?>

		<div class="container mt-3">
				<div class="row">
					<?php if ($detail['avg_rating'] != 0) { ?>
						<div class="col-sm-3">
							<div class="card">
								<div class="card-body">
									<h4>คะแนนเฉลี่ย</h4>
									<h2 class="bold padding-bottom-7"><?php echo number_format($detail['avg_rating'], 1) ?> <small>/ 5</small></h2>
									<?php echo display_stars($detail['avg_rating']); ?>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="col">
					<?php
						// reviews
						if (sizeof($detail['reviews']) == 0) {
							?>
							<h4>ยังไม่มีรีวิว</h4>
							<?php
						}
						foreach ($detail['reviews'] as $r) {
							$db->display_review($r);
							echo "<hr/>";
						}
					?>
					</div>			
				</div>			
				
				
			</div>

	</div>
</div>
<?php
require 'template_footer.php';
?>