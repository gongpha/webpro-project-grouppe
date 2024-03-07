<?php
require 'template_init.php';

if (isset($_GET["id"])) {
	$content_id = $_GET["id"];
} else {
	$db->go_to_home();
	exit();
}

$content = $db->get_course_content($content_id);
if (!$content) {
	$db->go_to_home();
	exit();
}

// check authorization
if (!$db->is_course_bought($content['course_id'])) {
	$db->go_to_with_motd("signin.php", "danger", "กรุณาเข้าสู่ระบบ/ลงทะเบียนก่อน");
	exit();
}

require 'template_header.php';
require 'template_container_begin.php';

$yt_ids = $content['attachments_yt'];

?>

<div class="card text-bg-dark" style="overflow: hidden; margin-bottom: 20px;">
	<div class="card-body" style="background-image: linear-gradient(#00000078, #00000078), url(<?php echo $content['cover_url'] ?>); background-size: cover;">
		<div class="d-flex justify-content-between">
			<h3 class="card-title"><?php echo $content['course_name'] ?></h3>
		</div>
	</div>
</div>

<!-- back button-->
<a href="course_detail.php?id=<?php echo $content['course_id']; ?>" class="btn btn-secondary" style="margin-bottom: 20px;">กลับ</a>

<div style="margin-bottom: 20px;">
	<div class="d-flex gap-3 ms-auto me-auto" style="width: fit-content">
	<?php

	$content_ids = $content['content_ids'];

	$i = 1;
	foreach ($content_ids as $c) {
		?>
		<a href="course_content.php?id=<?php echo $c; ?>">
			<button type="button" class="btn btn-sm btn-<?php echo $c == $content_id ? "primary" : "secondary" ?> rounded-pill" style="width: 2rem; height:2rem;"><?php echo $i; ?></button>
		</a>
		<?php
		$i++;
	}
	?>
	</div>
</div>
<?php

foreach ($yt_ids as $yt_id) {
	echo '<iframe style="width:100%; height:100%; min-height: 640px;" src="https://www.youtube.com/embed/' . $yt_id . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
}
?>

<?php
require 'template_container_end.php';
require 'template_footer.php';
?>