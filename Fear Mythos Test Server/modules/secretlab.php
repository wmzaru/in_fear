<?php
//6 potions of different color mix for effects
//either will blow up or Create a cool buff
//settings and prefs for the module are dynamic no info arrays needed for most

function secretlab_getmoduleinfo(){
	$info = array(
		"name"=>"Secret Laboratory",
		"version"=>"2.21",
		"author"=>"`#Lonny Luberts",
		"category"=>"PQcomp",
		"download"=>"http://www.pqcomp.com/modules/mydownloads/visit.php?cid=3&lid=85",
		"vertxtloc"=>"http://www.pqcomp.com/",
	);
	return $info;
}

function secretlab_install(){
	if (!is_module_active('secretlab')){
		output("`4Installing Secret Laboratory Module.`n");
	}else{
		output("`4Updating Secret Laboratory Module.`n");
	}
	module_addeventhook("forest", "return 100;");
	return true;
}

function secretlab_uninstall(){
	output("`4Un-Installing Secret Laboratory Module.`n");
	return true;
}

function secretlab_dohook($hookname,$args){
	//just left this here for no good reason
	return $args;
}

function secretlab_runevent($type){
	output("`#While wandering the forest you stumble upon a small brick building.`n");
	output("`#What would you like to do enter or continue on your way?");
	addnav("Continue On","forest.php");
	addnav("Enter","runmodule.php?module=secretlab");
}

function secretlab_run(){
	global $SCRIPT_NAME;
	if ($SCRIPT_NAME == "runmodule.php"){
		$module=httpget("module");
		if ($module == "secretlab"){
			require_once("modules/lib/secretlab_func.php");
			include("modules/lib/secretlab.php");
		}
	}
}
?>