<?php

function thecell_getmoduleinfo(){
	$info = array(
		"name"=>"The Cell",
		"version"=>"2.1",
		"author"=>"`#Lonny Luberts",
		"category"=>"PQcomp",
		"download"=>"http://www.pqcomp.com/modules/mydownloads/visit.php?cid=3&lid=20",
		"vertxtloc"=>"http://www.pqcomp.com/",
		"prefs"=>array(
			"The Cell Module User Preferences,title",
			"incell"=>"In The Cell?,bool|0",
			"commons"=>"Cell Location,int|0",
			"location"=>"backup of user location,text|",
			"thecell"=>"Admin/Moderator Cell Access,bool|0",
		),
	);
	return $info;
}

function thecell_install(){
	if (!is_module_active('thecell')){
		output("`2Installing The Cell Module.`n");
		output("`b`4Be sure to set access for Admin and Moderators from User Settings!`b`n");
	}
	module_addhook("everyhit");
	module_addhook("superuser");
	module_addhook("biostat");
	return true;
}

function thecell_uninstall(){
		output("`2Un-Installing The Cell Module.`n");
	return true;
}

function thecell_dohook($hookname,$args){
	global $session;
	switch($hookname){
		case "everyhit":
			global $SCRIPT_NAME;
			global $mostrecentmodule;
			$op = httpget('op');
			if (get_module_pref('incell')){
			$nono = 0;
			$args = $_SERVER['argv'];
			for ($i=0;$i<$_SERVER['argc'];$i+=1){
				if (strchr($args[$i],"&c=")) $args[$i] = str_replace(strstr($args[$i],"&c="),"",$args[$i]);
				if ($args[$i] == "nonono") $nono = 1;
			}
			if ($nono == 0){
				if ($SCRIPT_NAME == "mail.php" or $SCRIPT_NAME == "petition.php" or $SCRIPT_NAME == "motd.php"){
					redirect("runmodule.php?module=thecell&op=nonono");
				}elseif ($SCRIPT_NAME <> "runmodule.php" and ($SCRIPT_NAME <> "mail.php" or $SCRIPT_NAME <> "petition.php" or $SCRIPT_NAME <> "motd.php")){
					set_module_pref('commons',1);
					redirect("runmodule.php?module=thecell");	
				}
			}
			}
		break;
		case "superuser":
			if (get_module_pref('thecell')) addnav("The Cell","runmodule.php?module=thecell");
		break;
		case "biostat":
			if (get_module_pref('incell')) redirect("runmodule.php?module=thecell&op=restrict");
		break;
	}
	return $args;
}

function thecell_run(){
	global $SCRIPT_NAME;
	if ($SCRIPT_NAME == "runmodule.php"){
		include("modules/lib/thecell.php");
	}
}
?>