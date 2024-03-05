<?php
	require 'core.php';

	$OPTIONS = array();
	$db = new App('data.sqlite', "/avatars/");
	//print_r($db->getStudent(1));

	?>
	<div class="alert alert-info">
		<h1>debug shit</h1>
		<pre><?php print_r($_SESSION); ?></pre>
	</div>
	<?php
?>