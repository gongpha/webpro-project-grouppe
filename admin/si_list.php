<?php

$page_count = $list['page_count'];
$list = $list['data'];

?>

<div class="container">
<h1><?php echo $header; ?></h1>

	<form class="d-flex" role="search">
		<input class="form-control me-2" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : '' ?>" type="search" placeholder="ค้นหา" aria-label="Search">
		<button class="btn btn-outline-success me-2" type="submit">ค้นหา</button>
		<?php
			if ($button != "") {
				?>
				<a href="<?php echo $button_page; ?>" style="width: 30%" class="btn btn-success"><?php echo $button; ?></a>
				<?php
			}
		?>
	</form>


	<table class="table">
	<thead>
		<tr>
			<th scope="col">#</th>
			<?php if ($show_course) { ?>
			<th scope="col">ชื่อคอร์ส</th>
			<th scope="col">ผู้สอน</th>
			<th scope="col">หมวดหมู่</th>
			<th scope="col">ยอดขายทั้งหมด</th>
			<?php } else { ?>
			<th scope="col"></th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($list as $i) {
				echo "<tr>";
				echo "<td>{$i['id']}</td>";
				if ($show_cate) {
					echo "<td><a href=\"category.php?id={$i['id']}\">{$i['name']}</a></td>";
					echo "<td>{$i['total_courses']}</td>";
				} else {
					if ($show_course) {
						echo "<td>{$i['name']}</td>";
						echo "<td>{$i['owner_name']}</td>";
						echo "<td>{$i['category_name']}</td>";
						echo "<td>{$i['total_sales']}</td>";
					} else {
						echo "<td><img src=\"{$i['pfplink']}\" alt=\"avatar\" width=\"64\" height=\"64\" class=\"rounded-circle me-4\"> ";
						echo "<a href=\"" . $editpage . ".php?id={$i['id']}\">{$i['name']}</a>";
						echo "</td>";
					}
				}
				echo "</tr>";
			}
		?>
	</tbody>
	</table>

	<nav aria-label="pagination">
		<ul class="pagination">
			<li class="page-item">
				<a class="page-link" href="<?php echo $pagename; ?>?page=1">
				<span>&laquo;</span>
				</a>
			</li>
			<?php for ($i = 1; $i <= $page_count; $i++){ ?>
			<li class="page-item">
				<a class="page-link" href="<?php echo $pagename; ?>?page=<?php echo $i; ?>">
					<?php echo $i; ?>
				</a>
			</li>
			<?php } ?>
			<li class="page-item">
				<a class="page-link" href="<?php echo $pagename; ?>?page=<?php echo $page_count;?>">
				<span>&raquo;</span>
				</a>
			</li>
		</ul>
	</nav>
</div>

<?php

require 'template_footer.php';
?>