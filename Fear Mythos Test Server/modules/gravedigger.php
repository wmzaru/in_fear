<?php

# Date:     May 17, 2005
# Author:   Robert of Medieval Gamer
# LOGD VER: Module for v1.x.x

function gravedigger_getmoduleinfo(){
   $info = array(
    "name"=>"Grave Digger",
    "version"=>"1.1",
    "author"=>"`2Robert",
    "category"=>"Forest Specials",
    "download"=>"http://dragonprime.net/index.php?topic=2215.0",
    "settings"=>array(
		"Grave Digger Settings,title",
		"chance"=>"Chance player may get sick,range,30,90,5|60",
		),
    );
    return $info;
}

function gravedigger_install(){
	module_addeventhook("forest","return 100;");
	return true;
}

function gravedigger_uninstall(){
	return true;
}

function gravedigger_dohook($hookname,$args){
	return $args;
}
function gravedigger_runevent($type){
global $session;
$chance = get_module_setting("chance");
$a="`&`i the English Sweats `i";
$b="`6`i a cold `i";
$c="`@`i a virus `i";
$randx=e_rand(1,4);
if ($randx==1 or 4){ $sick=$a;}
if ($randx==2){ $sick=$b;}
if ($randx==3){ $sick=$c;}
$rand = e_rand(1,100);
if ($rand <= $chance) {
output("`n`n`2 You come upon the GraveDigger. You see he has a cartful today. ");
output("`n`n As he passes, you notice hundreds of flies cover the rotting flesh corpses. ");
output("`n`n You get a tickle in the back of your throat and do not feel so well. ");
output("`n`n OH MY! You  have contracted %s `2!!`n",$sick);
output("`n`n You are going to be sick for awhile. ");
$sickness = array(
	"name"=>"`7Corpse Sickness",
	"rounds"=>20,
	"defmod"=>.9,
	"atkmod"=>.9,
	"wearoff"=> "You begin to feel much better.",
	"roundmsg"=>"`7You sweat profusely while you try to fight.",
	"schema"=>"module-gravedigger",
	);
apply_buff('sickness',$sickness);

}else{
	output("`n`n`2 You see in the distance the GraveDigger is coming and he has a cartful today! ");
	output("`n`n Not wanting to contract any sickness or disease, you step far out his path. ");
	output("`n`n You feel you did the right thing, you have no desire to be sick today! `n");
}
}

function gravedigger_run(){
}
?>