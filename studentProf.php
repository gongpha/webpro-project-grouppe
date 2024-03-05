

<?php
require 'template_init.php';
require 'template_header.php';
require 'template_container_begin.php';
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

<body>
<ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0" height= "1000">
           
   
<div class="container">
	<div class="card text-bg-dark">
		<img style="filter: brightness(25%);" src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fobs.line-scdn.net%2F0hStzQr5r9DFtHKCNuO2BzDH1-DzR0RB9YIx5dWBtGUm9iHEgLe04UNWQuAjk5TEsFLk5LP2IhF2ptER8Fck8U%2Fw1200&f=1&nofb=1&ipt=2eaad6cce529a4c3dbb3fcc19c31f035c8a8c977ae8431e98b1a690710cbcd41&ipo=images;height=140" height="320" class="card-img" alt="...">
		<div class="card-img-overlay">

			<div class="d-flex align-items-center text-white text-decoration-none justify-content-between">
				<img  src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fimage.tnews.co.th%2Fnewscenter%2Fimages%2Fuserfiles%2Fimages%2F3-1(479).jpg&f=1&nofb=1&ipt=afa2128701b4e431b0e1ef3b4de7de5e80c6fa38d005486ad096ecc3709aec87&ipo=images" alt="avatar" width="256" height="256" class="rounded-circle me-4">
				<div class="profDetail" width="400">
					<h4>โจ๊ค ไอศกรีม</h4>
					<small>สร้างเมื่อ 93 กันยาคม 2024</small>
				</div>
                

				<br><br>
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
		$courses = $db->get_owned_course_list();
	?>
	
	<h3 class="result">คอร์สของฉัน</h3>	<div class="col-md-3 text-end"></div>
	<div class="row">
		<div class="col-sm">
			<?php

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
								<span class="badge text-bg-secondary"><?php echo $c['category_name'] ?></span>
							</p>
							<a href="course_detail.php?id="<?php echo $c['id'] ?>" class="btn btn-outline-primary">ดูคอร์ส</a>
						</div>
					</div>
					<?php
					$i++;
				}
			?>
		</div>
	</div>
</div>
</div>

</ul>


<?php
require 'template_container_end.php';
require 'template_footer.php';
?>