<?php
function rickrolled_getmoduleinfo(){
$info = array(
		"name"=>"Rick Rolled!",
		"version"=>"1.0",
		"author"=>"Jack Robinson",
		"category"=>"Forest Specials",
		);
	return $info;
}

function rickrolled_install(){
	module_addeventhook("forest","return 75;");
return true;
}

function rickrolled_uninstall(){
return true;
}

function rickrolled_runevent(){
	global $user;
	page_header("RICK ROLLED!");
	output("`bOuch..`b`n");
	rawoutput("<object width='640' height='505'><param name='movie' value='http://www.youtube.com/v/oHg5SJYRHA0&hl=en_US&fs=1&rel=0&color1=0x234900&color2=0x4e9e00'></param><param name='allowFullScreen' value='true'></param><param name='allowscriptaccess' value='always'></param><embed src='http://www.youtube.com/v/oHg5SJYRHA0&hl=en_US&fs=1&rel=0&color1=0x234900&color2=0x4e9e00' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='640' height='505'></embed></object>");
	tlschema("nav");
	addnav("Return to Forest (In Shame)","forest.php?");
	tlschema();
	page_footer();
}

function rickrolled_run(){
}
?>