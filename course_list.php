<?php
require 'template_init.php';
require 'template_header.php';
?>
<div class="container">
	<!--form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
		<input type="search" class="form-control form-control-dark text-bg-dark" placeholder="ค้นหาคอร์ส..." aria-label="Search">
	</form-->
	<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
		<div class="col-md-3 mb-2 mb-md-0">
			<a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
				<svg class="bi" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
			</a>
		</div>
		<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
			<label class="btn btn-outline-info" for="btnradio1">เขียนโปรแกรม</label>

			<input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
			<label class="btn btn-outline-info" for="btnradio2">ทำเหี้ย 1</label>

			<input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
			<label class="btn btn-outline-info" for="btnradio3">ทำเหี้ย 2</label>

			<input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off">
			<label class="btn btn-outline-info" for="btnradio4">ทำเหี้ย 3</label>
		</div>
		<div class="col-md-3 text-end">
		</div>
	</header>
	<h3 class="result">ผลลัพธ์จำนวน 3 คอร์ส</h3>

	<!-- NEW !!! using cards -->
	<div class="card mb-3">
		<img src="https://media.discordapp.net/attachments/746159419091582997/1194887870960377957/FB_IMG_1704953799917.jpg?ex=65f2968c&is=65e0218c&hm=4f9620e8ae1722774bc4cba156079d4f8eaf310463610ebd5b3865502c98e926&=&format=webp&width=473&height=200" class="card-img-top" alt="...">
		<div class="card-body">
			<div class="d-flex justify-content-between">
				<h3 class="card-title">@@@ภาษาซี ทำกูกลายเป็น femboy</h3>
				<h3><span class="badge text-bg-success">฿ 555</span></h3>
			</div>
			
			<p class="card-text">@@@ไอสัส ชาวไทย</p>
			<p class="card-text">
				<span class="badge text-bg-secondary">เขียนโปรแกรม</span>
			</p>
			<?php $db->echo_course_button(666); ?>
			<a href="#" class="btn btn-outline-secondary">ดูรายละเอียด</a>
		</div>
	</div>


	<div class="p-5 mb-4 bg-body-tertiary rounded-3">
		<div class="container-fluid py-5">
			<h1 class="display-5 fw-bold">@@@ภาษาซี ทำกูกลายเป็น femboy</h1>
			<p class="col-md-8 fs-4">@@@ไอสัส ชาวไทย</p>
			<button class="btn btn-primary btn-lg" type="button"> &nbsp;&nbsp;ซื้อ&nbsp;&nbsp; </button>
		</div>
	</div>
	<div class="p-5 mb-4 bg-body-tertiary rounded-3">
		<div class="container-fluid py-5">
			<h1 class="display-5 fw-bold">@@@ภาษาซี ทำกูกลายเป็น femboy</h1>
			<p class="col-md-8 fs-4">@@@ไอสัส ชาวไทย</p>
			<p class="price">฿ 555</p>
			<button class="btn btn-primary btn-lg" type="button"> &nbsp;&nbsp;ซื้อ&nbsp;&nbsp; </button>
		</div>
	</div>
	<div class="p-5 mb-4 bg-body-tertiary rounded-3">
		<div class="container-fluid py-5">
			<h1 class="display-5 fw-bold">@@@ภาษาซี ทำกูกลายเป็น femboy</h1>
			<p class="col-md-8 fs-4">@@@ไอสัส ชาวไทย</p>
			<p class="price">฿ 555</p>
			<button class="btn btn-primary btn-lg" type="button"> &nbsp;&nbsp;ซื้อ&nbsp;&nbsp; </button>
		</div>
	</div>
</div>
<?php
require 'template_footer.php';
?>