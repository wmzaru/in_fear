<?php

function clan_avatar_getimage($clanid) {
	$usenone = 0;
	$file = get_module_objpref("clans",$clanid,"filename","clan_avatars");
	if (!file_exists($file)) $usenone = 1;
	if (!$usenone){
		if (get_module_objpref("clans",$clanid,"validate","clan_avatars")){
			$image = "<img align='center' src='$file' ";
			if (get_module_setting("restrictsize")) {
			//stripped lines from Anpera's avatar module =)
				$maxwidth = get_module_setting("maxwidth");
				$maxheight = get_module_setting("maxheight");
				$pic_size = @getimagesize($file); // GD2 required here - else size always is recognized as 0
				$pic_width = $pic_size[0];
				$pic_height = $pic_size[1];
				//other arguments are channels,bits etc
				
				//aspect ratio. We are scaling for height/width ratio
				$resizedwidth=$pic_width;
				$resizedheight=$pic_height;
				if ($pic_height > $maxheight) {
					$resizedheight=$maxheight;
					$resizedwidth=round($pic_width*($maxheight
/$pic_height));
				}
				if ($resizedwidth > $maxwidth) {
					$resizedheight=round($resizedheight*($maxwidth
/$resizedwidth));
					$resizedwidth=$maxwidth;
					
				}
				$image.=" height=\"$resizedheight\"  width=\"$resizedwidth\" ";
			}
			$image .= ">";
		} else {
			$image = translate_inline("Avatar not validated yet");
		}
	}
	return $image;
}

?>