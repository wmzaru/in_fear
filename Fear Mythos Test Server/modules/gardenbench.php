<?php
#	Garden Bench 2Aug2005
#	Author: Robert of Maddrio dot com
#	Something to see in the garden
#	v1.2 corrects download link 10/2009

function gardenbench_getmoduleinfo(){
	$info = array(
	"name"=>"Garden Bench",
	"version"=>"1.2",
	"author"=>"`2Robert",
	"category"=>"Gardens",
	"download"=>"http://dragonprime.net/index.php?topic=2215.0",
	);
	return $info;
}
function gardenbench_install(){
	module_addhook("gardens");
	return true;
}
function gardenbench_uninstall(){
	return true;
}
function gardenbench_dohook($hookname,$args){
	switch($hookname){
	case "gardens":
	addnav("View");
	addnav("Sit on a bench","runmodule.php?module=gardenbench");
	break;
    }
return $args;
}
function gardenbench_run(){
	global $session;
	page_header("The Gardens");
	addnav("(C) Continue","gardens.php");
	output("`c`b`2The Gardens`b`c`n`n");
	output("`2 Walking through the Gardens you find a bench under a large shade tree, `n`n");
	switch (e_rand(1,10)) {
		case 1: case 7:
		output("`2 You decide to sit down for awhile. `n`n");
		output(" After a few minutes of sitting, boredom sets in.");
		break;
		case 2: case 8:
		output("`2 You decide to sit down for awhile. `n`n");
		output(" You watch as a bee goes from flower to flower at a nearby flowerbed. ");
		break;
		case 3: case 9:
		output("`2 You decide to sit down for awhile. `n`n");
		output(" You watch as some children chase after a puppy. ");
		break;
		case 4: case 10:
		output("`2 You decide to sit down for awhile. `n`n");
		output(" You watch a butterfly flutter its way until it is no longer in sight. ");
		break;
		case 5:
		output("`2 you decide you dont have time to relax while vile creatures still run rampant in the forest. ");
		break;
		case 6:
		output("`2 you notice the bird poo covering the bench and decide not to sit down. ");
		break;
	}
page_footer();
}
?>