<?php
	require '../core.php';

	$db = new App('../data.sqlite', "/avatars/");

	if (!isset($noredirect) && !$db->admin_is_logged_in()) {
		$db->go_to("signin.php");
	}
	?>
	<div class="alert alert-info">
		<h1>debug admin shit</h1>
		<pre><?php print_r($_SESSION); ?></pre>
	</div>
	<?php
?>