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

?>

<div class="container">
	<?php if ($id == 0) { ?>
		<h1>สร้างคอร์สใหม่</h1>
	<?php } else { ?>
		<h1>แก้ไขคอร์ส <?php echo $id ?> pls</h1>
	<?php } ?>
</div>

<?php
require 'template_footer.php';
?>