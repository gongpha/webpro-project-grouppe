<?php
require 'template_init.php';

if (!$db->is_student()) {
	$db->go_to_home();
}

require 'template_header.php';
require 'template_container_begin.php';
?>

<h1>การสั่งซื้อเสร็จสมบูรณ์</h1>
<p>ขอบคุณที่ใช้บริการ</p>

<p>คุณสามารถดูที่<a href="profile.php">โปรไฟล์ของฉัน</a>เพื่อดูคอร์สที่สั่งซื้อไว้ทั้งหมด</p>

<?php
require 'template_container_end.php';
require 'template_footer.php';
?>