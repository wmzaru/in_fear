<?php
function findlodgecoupons_getmoduleinfo(){
	$info = array(
		"name"=>"Find Lodge Coupons",
		"version"=>"1.0",
		"author"=>"umk, based on Find Gold by Eric Stevens",
		"category"=>"Village Specials",
		"download"=>"http://dragonprime.net/users/umk/findlodgecoupons.zip",
		"settings"=>array(
			"Find Lodge Coupons Event Settings,title",
			"minlcoupons"=>"Minimum lodge points to find,range,0,100,1|10",
			"maxlcoupons"=>"Maximum lodge points to find,range,20,150,1|50"
		),
	);
	return $info;
}

function findlodgecoupons_install(){
	module_addeventhook("village", "return 25;");
	return true;
}

function findlodgecoupons_uninstall(){
	return true;
}

function findlodgecoupons_dohook($hookname,$args){
	return $args;
}

function findlodgecoupons_runevent($type,$link)
{
	global $session;
	$chance = e_rand(1,100);
	if ($chance < 81) {
		$min = get_module_setting("minlcoupons");
		$max = get_module_setting("maxlcoupons");
		$points = e_rand($min, $max);
		output("`^As you were walking around, you spon someone handing out what looks like fliers, you quickly move closer and he hands some to you.`0");
		output("`^You look through the fliers and you smile to yourself as you just got free coupons, worth %s points for use in the lodge!`0", $points);
		$session['user']['donation']+=$points;
		debuglog("received $points lodge points from free coupon");
	}
	else {
		output("`^As you were walking around, you spon someone handing out what looks like fliers, you quickly move closer but by the time you reach him, he's already run out of fliers.`0");
	}
	
}

function findlodgecoupons_run(){
}
?>
