<?php
function adultchat_getmoduleinfo(){
	$info = array(
		"name"=>"Adult Only Chats",
		"version"=>"1.1",
		"author"=>"`b`i`)N`4o`)body`b`i`^--based off of Xpert's `i`4Pimp Blimp`i<br />Modified by `i`)Ae`7ol`&us`i`0",
		"category"=>"Commentary",
		"settings"=>array(
			"Adult Chat Settings,title",
			"`^To add more navs&#44; simply follow the instructions inside the module code itself,note",
			"nav1"=>"Title for nav 1 here?,text|",
			"navlink1"=>"Link for nav 1 here?,text|",
			"text1"=>"Place Description for adultchat1 here,text|",
			"`@------------------------,note",
			"nav2"=>"Title for nav 2 here?,text|",
			"navlink2"=>"Link for nav 2 here?,text|",
			"text2"=>"Place Description for adultchat2 here,text|",
			"`@------------------------,note",
			// To add more nav's, just copy the above format, changing the numbers
		),
	);
	return $info;
}

function adultchat_install(){
	module_addhook("everyfooter");
	module_addhook("moderate");
	return true;
}

function adultchat_uninstall(){
	return true;
}
function adultchat_dohook($hookname,$args){
	global $session;
	require_once("lib/sanitize.php");
	require_once("modules/rlage.php");
	$sett = get_all_module_settings();
	$c = (count($sett)-1)/3; // -1 is for the showFormTabIndex
	
	$suburl = substr(cmd_sanitize($_SERVER['REQUEST_URI']),1);
	
	switch($hookname){
		case "everyfooter":
			for ($i = 1; $i <= $c; $i++){
				if($sett['navlink'.$i] == $suburl && (isadult($session['user']['acctid']) || $session['user']['superuser'] & SU_EDIT_COMMENTS)){
					addnav("Adult Chat");
					addnav(array("%s",$sett['nav'.$i]),"runmodule.php?module=adultchat&op=".$suburl);
				}
			}
		break;
		case "moderate":
			for ($i = 1; $i <= $c; $i++)
				$args['adultchat-'.urlencode($sett['navlink'.$i])] = "Adult Chat for ".$sett['nav'.$i];
		break;
	}
	return $args;
}

function adultchat_run(){
	require_once("lib/sanitize.php");
	require_once("lib/commentary.php");
	
	page_header("Adult Chat");
	
	$op = cmd_sanitize(httpget('op'));
	$sett = get_all_module_settings();
	$c = (count($sett)-1)/3; // -1 is for the showFormTabIndex
	
	$j = 0;
	for ($i = 1; $i <= $c; $i++)
		if($sett['navlink'.$i] == $op) $j = $i;
	
	
	addnav("Return",$op);

	output("`c%s`c`n`n",$sett['text'.$j]);

	addcommentary();
	viewcommentary("adultchat-".urlencode($sett['navlink'.$j]),"Speak", 35, "says");

	page_footer();
}
?>