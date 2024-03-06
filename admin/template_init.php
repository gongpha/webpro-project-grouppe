<?php
	require '../core.php';

	$db = new App('../data.sqlite', "/avatars/");

	if (!isset($noredirect) && !$db->admin_is_logged_in()) {
		$db->go_to("signin.php");
	}
?>