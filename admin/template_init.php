<?php
	require 'core.php';

	$db = new Admin();

	if (!isset($noredirect) && !$db->is_logged_in()) {
		$db->go_to("signin.php");
	}
	?>
	<div class="alert alert-info">
		<h1>debug admin shit</h1>
		<pre><?php print_r($_SESSION); ?></pre>
	</div>
	<?php
?>