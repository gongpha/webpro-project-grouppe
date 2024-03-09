<?php
require 'template_init.php';
require 'template_header.php';
require 'template_container_begin.php';

$courses = $shopping->dump_courses();

if (sizeof($courses) == 0) {
	?>
	<div class="alert alert-info" role="alert">
		<h4 class="alert-heading">ไม่มีคอร์สในรถเข็นของคุณ</h4>
		<hr>
		<p class="mb-0">เลือกคอร์สที่คุณต้องการซื้อจากหน้า<a href="course_list.php">รายการคอร์ส</a></p>
	</div>
	<?php
exit();
}
?>

<h1>สรุปการสั่งซื้อ</h1>
<table class="table fs-5">
	<tr>
		<th>
			ลำดับ
		</th>
		<th>
			รายการคอร์สที่คุณจะซื้อ
		</th>
		<th class="text-end">
			ราคา
		</th>
	</tr>
	<?php
	$i = 1;
	foreach ($courses as $c) {
		?>
		<tr>
			<td>
				<?php echo $i; ?>
			</td>
			<td>
				<?php echo $c['name']; ?>
			</td>
			<td class="text-end">
				฿ <?php echo number_format($c['price']); ?>
			</td>
		</tr>

		<?php
		$i++;
	}
	?>
	<tr>
		<th>ราคารวม</th>
		<th></th>
		<th class="text-end">฿ <?php echo number_format($shopping->get_total_price()); ?></th>
	</tr>
</table>

<!-- payment form -->
<h1>ชำระเงิน</h1>
<form action="action.php" method="post">
	<div class="mb-3">
		<label for="holderName" class="form-label">ชื่อผู้ถือบัตร</label>
		<input type="text" class="form-control" id="holderName" name="card_name" required>
	</div>
	<div class="mb-3">
		<label for="cardNumber" class="form-label">หมายเลขบัตร</label>
		<input type="text" class="form-control" id="cardNumber" name="card_number" required>
	</div>
	<div class="row">
		<div class="col-md-3 mb-3">
			<label for="cardExpire" class="form-label">วันหมดอายุ</label>
			<input type="text" class="form-control" id="cardExpire" name="card_expire" required>
		</div>
		<div class="col-md-3 mb-3">
			<label for="cardCVV" class="form-label">CVV</label>
			<input type="text" class="form-control" id="cardCVV" name="card_cvv" required>
		</div>
	</div>
	<input type="hidden" name="action" value="checkout">
	<button type="submit" class="btn btn-primary btn-lg">ชำระเงิน</button>
</form>

<?php
require 'template_container_end.php';
require 'template_footer.php';
?>