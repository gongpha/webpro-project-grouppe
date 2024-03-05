<?php

function prepare_other_data($row, $prefix = "") {
	if (isset($row['profile_pic_hash'])) {
		if ($row['profile_pic_hash'] != "") {
			$row['pfplink'] = $prefix . "avatars/" . $row['profile_pic_hash'] . '.jpg';
			return $row;
		}
	}

	$row['pfplink'] = get_pfplink_from_seed('saltPROJECTGROUPPE' . md5($row['username']));
	return $row;
}

function get_pfplink_from_seed($ident) {
	return "https://api.dicebear.com/7.x/thumbs/svg?seed=" . $ident;
}

function resize_image($file, $w, $h, $crop=FALSE) {
	list($width, $height) = getimagesize($file);
	$r = $width / $height;
	if ($crop) {
		if ($width > $height) {
			$width = ceil($width-($width*abs($r-$w/$h)));
		} else {
			$height = ceil($height-($height*abs($r-$w/$h)));
		}
		$newwidth = $w;
		$newheight = $h;
	} else {
		if ($w/$h > $r) {
			$newwidth = $h*$r;
			$newheight = $h;
		} else {
			$newheight = $w/$r;
			$newwidth = $w;
		}
	}
	$src = imagecreatefromjpeg($file);
	$dst = imagecreatetruecolor($newwidth, $newheight);
	imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	imagejpeg($dst, $file);

	return $dst;
}

?>