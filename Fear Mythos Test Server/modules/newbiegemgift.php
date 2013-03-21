<?php

function newbiegemgift_getmoduleinfo(){
	$defaultmessage = "`%As our way of saying thank you for playing on our server,";
	$defaultmessage = $defaultmessage." we are pleased to present you with two gems.`n`^Thank you!";
	$info = array(
		"name"=>"Newbie gem gift",
		"version"=>"1.0",
		"author"=>"Qwyxzl",
		"category"=>"Rewards",
		"download"=>"http://www.geocities.com/qwyxzl/newbiegemgift.zip",
		"settings"=>array(
			"Newbie gem gift - Settings,title",
			"givetoold"=>"Give to the players already on the server?,bool|1",
			"givetodked"=>"Give the gift to players who have killed the dragon?,bool|0",
			"message"=>"Message to display when giving out the gems,text|$defaultmessage",
			"target"=>"Number of days to wait before giving out the gems,int|5",
			"numgems"=>"Number of gems to give as a reward,int|2"
			),
		"prefs"=>array(
			"Newbie gem gift - Preferences,title",
			"isold"=>"Was the player already created when this was installed,bool|0",
			"gotgems"=>"Has the player received their gems yet?,bool|0",
			"numdays"=>"Number of days that have passed,int|0"
		)
	);
	return $info;
}

function newbiegemgift_install(){
	module_addhook("newday");
	set_module_pref("isold",1);
	return true;
}

function newbiegemgift_uninstall(){
	return true;
}

function newbiegemgift_dohook($hookname,$args){	
	if($hookname == "newday"){
		global $session;

		$allow = false;
		if($session['user']['dragonkills'] == 0 || get_module_setting("givetodked")){
			$allow = true;
		}
		if(!get_module_setting("givetoold") && get_module_pref("isold")){
			$allow = false;
		}
		if (!get_module_pref("gotgems") && $allow) {
			$numdays = get_module_pref("numdays");
			if($numdays >= get_module_setting("target")){
				$session['user']['gems']+= get_module_setting("numgems");
				output("`n`n`c`Q* * * * * * * * * * * * * * * * * * * * * * * * *");
				output("`n`n%s",get_module_setting("message"));
				output("`n`n`Q* * * * * * * * * * * * * * * * * * * * * * * * *`c");
				set_module_pref("gotgems",1);
			}else{
				set_module_pref("numdays",$numdays + 1);
			}				
		}
	}
	return $args;
}

?>
