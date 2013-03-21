<?php
function annoyingkid_getmoduleinfo(){
	$info = array(
		"name"=>"Annoying Kid",
		"version"=>"1.0",
		"author"=>"Eric, edited by Zach, SUPER IDIOT!",
		"dedication"=>"Sichae, Talisman, Elessa, Kickme, Daves, ETC.",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1237",
	    "settings"=>array(
			"Annoying Kid Finds Event Settings,title",
			"gemsgain"=>"Chance to gain a gem (otherwise lose a turn),range,0,100,1|45"
	    ),
	);
	return $info;
}

function annoyingkid_install(){
	module_addeventhook("forest", "return 100;");
	module_addeventhook("travel", "return 20;");
	return true;
}

function annoyingkid_uninstall(){
	return true;
}

function annoyingkid_dohook($hookname,$args){
	return $args;
}

function annoyingkid_runevent($type,$link)
{
	global $session;
		$chancez = get_module_setting("gemsgain");
	$zoll = e_rand(1, 100);
	if ($zoll <= $chancez) {
	output("`! You notice a small kid, and a large warrior, you gaze at the large warrior, who seems to be very annoyed, and the little kid, who keeps whining, \"Please Sichae!\"");
	output("`! \"Shut up Zach!\" yells Sichae. Suddenly he shoves the kid, and he falls over and a gem falls out of his pocket, and you quickly swipe it. The teenager possibly named Sichae, gives out a large laugh and helps the kid up, and suddenly runs off.");
	$session['user']['gems']++;
	} else {
output("`! You notice a small kid, and a large warrior, you gaze at the large warrior, who seems to be very annoyed, and the little kid, who keeps whining, \"Please Sichae!\"");
output("`! \"Shut up Zach!\" yells Sichae, Suddenly he shoves the kid, and he falls over and knocks you into a ditch, you try and yell for help, but they seem to of already ran off, you spend a whole hour trying to get out of the ditch.");
if ($session['user']['turns'] > 0) {
			output("You `%lose one `^turn!`0");
			$session['user']['turns']--;
		}
	}
}
function annoyingkid_run(){
}
?>