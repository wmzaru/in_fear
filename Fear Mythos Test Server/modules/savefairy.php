<?php
# Updated   24July2006
# Version:  1.0 January 26, 2005
# Author:   Robert of maddrio dot com / v1.0 Converted by: Kevin Hatfield - Arune
# v 1.1     corrected errors
# v 1.2     optimized code July2005
# v 1.3     includes vertexloc Feb2006
# v 1.4     removes vertexloc, not used any more on Dragon Prime

function savefairy_getmoduleinfo(){
	$info = array(
	"name"=>"Save Fairy",
	"version"=>"1.4",
	"author"=>"`2Robert<br> `7Converted by: Arune",
	"category"=>"Forest Specials",
	"download"=>"http://dragonprime.net/index.php?topic=2215.0",
	);
	return $info;
}

function savefairy_install(){
	if (!is_module_active('savefairy')){
		output("`^ Installing Save Fairy - forest event `n`0");
	}else{
		output("`^ Up Dating Save Fairy - forest event `n`0");
	}
	module_addeventhook("forest","return 100;");
	return true;
}

function savefairy_uninstall(){
	output("`^ Un-Installing Save Fairy - forest event `n`0");
	return true;
}

function savefairy_dohook($hookname,$args){
	return $args;
}

function savefairy_runevent($type){
global $session; 
output("`n`n`^ You hear an odd high pitched squeal and decide to investigate the source. ");
output(" Walking carefully and quietly, you come upon a very large spider's web. ");
output(" Upon it, you see a small Fairy who is stuck and entangled, struggling to no avail, to escape its grasp. ");
output(" You whisper in a calm voice to reassure the Fairy you mean her no harm. ");
output(" You rescue the Fairy from the sticky spiders web and most certain demise if you had not. ");
output(" With a bright smile, she flies over your head and sprinkles you with `% Pixie Dust, `^ giggles and flies away! ");
output("`n`n You `% receive one `^ charm! ");
$session['user']['charm']++;
}

function savefairy_run(){
}
?>