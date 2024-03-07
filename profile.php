

<?php
require 'template_init.php';

if (!$db->is_logged_in()) {
	$db->go_to_home();
}

require 'template_header.php';
require 'template_container_begin.php';

// my profile

$profile = $db->get_my_profile();

?>

<style>

    h4{
        width: 300px;
        font-size: 32px;
    }

    .card-img-overlay{
        width:100px;
        height: 100px;
    }


</style>

<ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0" height= "1000">
           
   
<div class="container">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-center text-white text-decoration-none">
					<img src="<?php echo $profile['pfplink'] ?>" alt="avatar" width="256" height="256" class="rounded-circle me-4">
					<div class="profDetail" width="400">
						<h4><?php echo $profile['name'] ?></h4>
						<small>
							<i class="bi bi-clock"></i>&nbsp;
							เข้าร่วมเมื่อ <?php echo date( 'd M Y', strtotime($profile['created_date']) ); ?>
						</small>
					</div>
					

					<br><br>
        	    </div>
            </div>
            
            
        </div>
        
        
    </div>
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
	</header>

	<?php
		$i = 0;
		if ($db->is_student())
			$courses = $db->get_owned_course_list();
		else
			$courses = $db->get_created_course_list();
	?>
	
	<h3 class="result"><?php echo $db->is_student() ? "คอร์สของฉัน" : "คอร์สที่สร้าง" ?></h3><div class="col-md-3 text-end"></div>
	<div class="row">
		<div class="col-sm">
			<?php
				if (sizeof($courses) == 0) {
					echo "<p>คุณยังไม่ได้เป็นเจ้าของคอร์สใด ๆ</p>";
				} else 
				foreach ($courses as $c) {
					if ($i == 2) {
						$i = 0;
						echo "</div>";
						echo "<div class=\"col-sm\">";
					}
					?>
					<div class="card mb-3">
						<img src="<?php echo $c['cover_url'] ?>" class="card-img-top object-fit-cover" height="400" alt="course cover">
						<div class="card-body">
							<div class="d-flex justify-content-between">
								<h3 class="card-title"><?php echo $c['name'] ?></h3>
							</div>
							
							<p class="card-text"><?php echo $c['brief_desc'] ?></p>
							<p class="card-text">
							<?php if ($c['visibility'] == 0) { ?>
								<span class="badge bg-warning text-dark">ซ่อนจากสาธารณะ</span>
							<?php } ?>
							<?php $db->generate_category_badge($c['category_id'], $c['category_name']); ?>
							</p>
							<a href="course_detail.php?id=<?php echo $c['id']; ?>" class="btn btn-outline-primary">ดูคอร์ส</a>
						</div>
					</div>
					<?php
					$i++;
				}
			?>
		</div>
	</div>
</div>

</ul>


<?php
require 'template_container_end.php';
require 'template_footer.php';
?>