<?php
require 'template_init.php';
	$db->signout();
	header('Location: index.php');
?>