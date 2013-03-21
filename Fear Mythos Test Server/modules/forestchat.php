<?php
require_once('lib/commentary.php');
function forestchat_getmoduleinfo(){
	$info = array(
		"name"=>"Forest Chat (The Small Useless Code)",
		"version"=>"1.2",
		"author"=>"Jack Robinson,Added on by Brendan",
		"category"=>"Forest",
		"prefs"=>array(
		"User Prefs,title",
		"chaton"=>"Player has forest chat on?,bool|1",
		),
						);
	return $info;
}

function forestchat_install(){
module_addhook("forest");
return true;
}

function forestchat_uninstall(){
return true;
}

function forestchat_dohook($hookname,$args){
global $session;
switch($hookname){
	case "forest":
	//code by brendan
	$chaton = get_module_pref("chaton");
	if ($chaton == false){
	addnav("Turn Forest Chat On","runmodule.php?module=forestchat&op=on");
	}
	if ($chaton == true){
	addnav("Turn Forest Chat Off","runmodule.php?module=forestchat&op=off");
	//end of code by brendan
	output("`n`n`2-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-");
	output("`n`nIn a clearing, fellow adventurers speak...`n`n");
	addcommentary();
	commentdisplay("", "forestchat","Speak",30,"says");
	}
	break;
}
return $args;
}

function forestchat_run(){
//code by brendan
global $session;
page_header("Forest Chat");
$op = httpget('op');
if ($op == "on"){
addnav("Return to the Forest","forest.php");
output("You turned on the forest chat, you will be able to view the commentary within the forest");
set_module_pref("chaton",true);
}
if ($op == "off"){
addnav("Return to the Forest","forest.php");
output("You turned off the forest chat, you will no longer be able to view the commentary within the forest");
set_module_pref("chaton",false);
}
page_footer();
}
//end code by brendan
	?>