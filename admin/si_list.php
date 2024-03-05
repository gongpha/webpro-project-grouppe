<div class="container">
<h1><?php echo $header; ?></h1>

	<form class="d-flex" role="search">
		<input class="form-control me-2" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : '' ?>" type="search" placeholder="ค้นหา" aria-label="Search">
		<button class="btn btn-outline-success me-2" type="submit">ค้นหา</button>
		<?php
			if ($button != "") {
				?>
				<a href="instructor.php" style="width: 30%" class="btn btn-success">สร้างบัญชีผู้สอนใหม่</a>
				<?php
			}
		?>
	</form>


	<table class="table">
	<thead>
		<tr>
			<th scope="col">#</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($list as $i) {
				echo "<tr>";
				echo "<td>{$i['id']}</td>";
				echo "<td><img src=\"{$i['pfplink']}\" alt=\"avatar\" width=\"64\" height=\"64\" class=\"rounded-circle me-4\"> ";
				echo "<a href=\"" . $editpage . ".php?id={$i['id']}\">{$i['name']}</a>";
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