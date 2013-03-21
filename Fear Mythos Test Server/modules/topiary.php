<?php
/* the Topiary  19April2005
   Author: Robert of Maddrio dot com
   Something to see in the garden
   Version: 1.2 April2006 - adds admin settngs
*/

function topiary_getmoduleinfo(){
	$info = array(
	"name"=>"Topiary",
	"version"=>"1.2",
	"author"=>"`2Robert",
	"category"=>"Gardens",
	"download"=>"http://dragonprime.net/index.php?topic=2215.0",
	"description"=>"Something to see while in the Gardens",
	"settings"=>array(
		"Garden Topiary - Settings,title",
		"top1"=>"Shape of topiary 1?,|a round ball",
		"top2"=>"Shape of topiary 2?,|a Horse",
		"top3"=>"Shape of topiary 3?,|Lion",
		"top4"=>"Shape of topiary 4?,|Rhino",
		"top5"=>"Shape of topiary 5?,|Elephant",
		"top6"=>"Shape of topiary 6?,|Dragon",
		"female"=>"Name of female seen in Garden?,|Violet",
		"male"=>"Name of male seen in Garden?,|Cedrik"
		)
	);
	return $info;
}

function topiary_install(){
	module_addhook("gardens");
	return true;
}
function topiary_uninstall(){
	return true;
}

function topiary_dohook($hookname,$args){
	switch($hookname){
	case "gardens":
	addnav("View");
	addnav("The Topiary","runmodule.php?module=topiary");
	break;  
    }
return $args;
}

function topiary_run(){
	global $session;
	
	$top1 = get_module_setting("top1");
	$top2 = get_module_setting("top2");
	$top3 = get_module_setting("top3");
	$top4 = get_module_setting("top4");
	$top5 = get_module_setting("top5");
	$top6 = get_module_setting("top6");
	$male = get_module_setting("male");
	$female = get_module_setting("female");

	page_header("The Gardens");
	output("`c`b`!Topiary`0`b`c`n`n");
	addnav("(C) Continue","gardens.php");
	output("`2 You come upon a section of the Gardens where hedges were skillfully cut and trimmed into beautiful works of art. ");
	output(" You pass through an archway of hedge and ivy. ");
	output(" Past several tall towers, resembling the glorious columns of Roman architecture. `n");
	output(" You stop for a moment to savor the essence of fragrant flowers. `n`n");
	output(" Onward to several hedge's carefully trimmed into the shape of %s. ",$top1);
	output(" Beyond, there is a variety of topiary skillfully crafted into the shapes of %s, %s, %s, %s and a %s. `n`n",$top2,$top3,$top4,$top5,$top6);
	output(" While walking through the topiary, you see %s and %s, they wave hello to you. ",$female,$male);
	output(" You now realize how relaxing a walk through the Gardens really is. ");
page_footer();
}
?>