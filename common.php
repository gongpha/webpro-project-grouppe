<?php

function prepare_other_data($row, $prefix = "") {
	if (isset($row['profile_pic_hash'])) {
		if ($row['profile_pic_hash'] != "") {
			$row['pfplink'] = $prefix . "avatars/" . $row['profile_pic_hash'] . '.jpg';
			return $row;
		}
	}

	if (!isset($row['username'])) {
		return $row;
	}

	$row['pfplink'] = get_pfplink_from_seed('saltPROJECTGROUPPE' . md5($row['username']));
	return $row;
}

function get_pfplink_from_seed($ident) {
	return "https://api.dicebear.com/7.x/thumbs/svg?seed=" . $ident;
}

function resize_image($file, $w, $h) {
	list($width, $height) = getimagesize($file);
	$r = $width / $height;
	if ($w/$h > $r) {
		$newwidth = $h*$r;
		$newheight = $h;
	} else {
		$newheight = $w/$r;
		$newwidth = $w;
	}
	$src = imagecreatefromjpeg($file);
	$dst = imagecreatetruecolor($newwidth, $newheight);
	imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	imagejpeg($dst, $file);

	return $dst;
}

function motd($type, $txt) {
	$_SESSION['motd'] = $txt;
	$_SESSION['motd_class'] = $type;
}

function motd_error($txt) {
	motd('danger', $txt);
}

function load_chart($chart_id, $data, $use_baht = false) {
	?>

	<script>
		applyChart({
			data: <?php echo $data; ?>,
			canvasID: "<?php echo $chart_id; ?>"
		}, <?php echo $use_baht ? "true" : "false"; ?>);
	</script>

	<?php
}

function display_stars($rating) {
	// display star fill, star half, and star
	// for float show half
	// for int show fill
	// for 5 - int show empty

	if ($rating == 0) {
		return '<i>ยังไม่มีคะแนน</i>';
	}

	$rating = floatval($rating);
	$int_rating = intval($rating);
	$half_rating = $rating - $int_rating;

	$html = "";

	for ($i = 0; $i < $int_rating; $i++) {
		$html .= '<i class="bi bi-star-fill"></i>';
	}

	if ($half_rating >= 0.5) {
		$html .= '<i class="bi bi-star-half"></i>';
		$rating += 0.5;
	}

	for ($i = 0; $i < 5 - floor($rating); $i++) {
		$html .= '<i class="bi bi-star"></i>';
	}

	return $html;
}

?>