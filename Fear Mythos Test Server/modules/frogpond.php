<?php
#	Frog Pond  2Aug2005
#	Author: Robert of Maddrio dot com
#	Something to see in the garden
#	v1.2 corrects the download link

function frogpond_getmoduleinfo(){
	$info = array(
	"name"=>"Frog Pond",
	"version"=>"1.2",
	"author"=>"`2Robert",
	"category"=>"Gardens",
	"download"=>"http://dragonprime.net/index.php?topic=2215.0",
	);
	return $info;
}

function frogpond_install(){
	module_addhook("gardens");
	return true;
}
function frogpond_uninstall(){
	return true;
}

function frogpond_dohook($hookname,$args){
	switch($hookname){
	case "gardens":
	addnav("View");
	addnav("Frog Pond","runmodule.php?module=frogpond");
	break;
    }
return $args;
}

function frogpond_run(){
	global $session;
	page_header("The Gardens - Frog Pond");
	output("`c`b`2Frog Pond`b`c`n`n");
	addnav("(C) Continue","gardens.php");
	output("`2 Walking along the frog pond, `n`n");
	switch (e_rand(1,10)) {
		case 1: case 6:
		output("`2 You watch as the frogs jump from lily pads. `n`n");
		output(" After a few minutes of watching the frogs, boredom sets in.");
		break;
		case 2: case 7:
		output("`2  you sense a feeling of calmness and serenity. `n`n");
		output(" Perhaps there is more to life than killing in the forest.");
		break;
		case 3: case 8:
		output("`2 you notice the tadpoles. `n`n");
		output(" You watch for awhile and think how simple life is for them.");
		break;
		case 4: case 9:
		output("`2 you glance up and see a fairy staring back at you. `n`n");
		output(" She smiles and flit's away. ");
		break;
		case 5: case 10:
		output("`2 you notice children trying to catch tadpoles. ");
		break;
	}
page_footer();
}
?>