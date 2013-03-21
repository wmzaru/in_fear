<?php
function doppleganger_getmoduleinfo(){
	$info = array(
		"name"=>"Doppleganger",
		"version"=>"5.2",
		"author"=>"DaveS",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=993",
		"settings"=>array(
			"Doppleganger Settings,title",
			"forest"=>"Chance to encounter in the forest:,range,5,100,5|100",
			"mindk"=>"Minimum dks for player to encounter the Doppleganger:,int|1",
			"doppledk"=>"Dks needed to have a Doppleganger of the player?,int|1",
			"phrase1dk"=>"Dks needed to have phrase 1?,int|2",
			"phrase2dk"=>"Dks needed to have phrase 2?,int|3",
			"phrase3dk"=>"Dks needed to have phrase 3?,int|4",
			"dopyom"=>"Send the person who the doppleganger copied a YoM for the battle results?,bool|1",
			"Doppleganger,title",
			"perexpl"=>"Percentage of experience lost if defeated by Doppleganger:,range,0,100,1|15",
			"death"=>"Kill player if they lose to the Doppleganger?,enum,0,No,1,Yes,2,50% of the time|2",
			"dghp"=>"Multiplier for Doppleganger's hitpoints:,floatrange,0.7,2.0,0.1|0.9",
			"dgatt"=>"Multiplier for Doppleganger's attack:,floatrange,0.7,2.0,0.1|0.9",
			"dgdef"=>"Multiplier for Doppleganger's defense:,floatrange,0.7,2.0,0.1|1.1",
			"name"=>"What is the Doppleganger's name if no one available?,txt|King Arthur",
			"armor"=>"What is the Doppleganger's armor if no one available?,txt|Plate Mail",
			"weapon"=>"What is the Doppleganger's weapon if no one available?,txt|Excalibur",
			"sex"=>"What is the Doppleganger's sex if no one available?,enum,0,Male,1,Female|0",
		),
		"prefs"=>array(
			"Doppleganger Encounter,title",
			"super"=>"Admin/Moderator Doppleganger Phrase Approval?,bool|0",
			"dopplename"=>"Has the player received a Doppleganger?,enum,0,NA,1,Yes,2,No|0",
			"user_stat"=>"Receive a YoM when your Doppleganger fights another player?,bool|1",
			"This may be overridden by your local Admin,note",
			"Allprefs,title",
			"Note: Please edit with caution. Consider using the Allprefs Editor instead.,note",
			"allprefs"=>"Preferences for Doppleganger,textarea|",
		),
	);
	return $info;
}
function doppleganger_chance() {
	global $session;
	$ret= get_module_setting('forest','doppleganger');
	$allprefs=unserialize(get_module_pref('allprefs','doppleganger',$session['user']['acctid']));
	if ($session['user']['dragonkills']<get_module_setting("mindk","doppleganger")||$allprefs['encountered']==1) $ret=0;
	return $ret;
}
function doppleganger_install(){
	module_addeventhook("forest","require_once(\"modules/doppleganger.php\");
	return doppleganger_chance();");
	module_addhook("newday");
	module_addhook("superuser");
	module_addhook("allprefs");
	module_addhook("allprefnavs");
	return true;
}
function doppleganger_uninstall(){
	return true;
}
function doppleganger_dohook($hookname,$args){
	global $session;
	require("modules/doppleganger/dohook/$hookname.php");
	return $args;
}
function doppleganger_runevent($type) {
	redirect("runmodule.php?module=doppleganger&op=enter");
}
function doppleganger_run(){
	include("modules/doppleganger/doppleganger.php");
}
?>