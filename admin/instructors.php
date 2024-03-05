<?php
require 'template_init.php';
require 'template_header.php';

?>

<div class="container">
<h1>รายชื่อผู้สอน</h1>
<a href="instructor.php" class="btn btn-success">สร้างบัญชีผู้สอนใหม่</a>

	<table class="table">
	<thead>
		<tr>
			<th scope="col">#</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$list = $db->get_instructor_simple_list();
			foreach ($list as $i) {
				echo "<tr>";
				echo "<td>{$i['id']}</td>";
				echo "<td><img src=\"{$i['pfplink']}\" alt=\"avatar\" width=\"64\" height=\"64\" class=\"rounded-circle me-4\"> ";
				echo "<a href=\"instructor.php?id={$i['id']}\">{$i['name']}</a>";
				echo "</td>";
				echo "</tr>";
			}
		?>
	</tbody>
	</table>
</div>

<?php

require 'template_footer.php';
?>