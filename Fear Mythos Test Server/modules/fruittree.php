<?php
# the Fruit Tree
# Author:   Robert of MaddRio dot com
# Date:     April 20, 2005
# v 1.1     optimized code
# v 1.2     optimized code Sep2007

function fruittree_getmoduleinfo(){
	$info = array(
	"name"=>"Fruit Tree",
	"version"=>"1.2",
	"author"=>"`2Robert",
	"category"=>"Forest Specials",
	"download"=>"http://dragonprime.net/index.php?topic=2215.0",
	);
	return $info;
}
function fruittree_install(){
	if (!is_module_active('fruittree')){
		output("`^ Installing: Fruit Tree - forest event `n`0");
	}else{
		output("`^ Up-dating: Fruit Tree - forest event `n`0");
	}
        module_addeventhook("forest","return 100;");
	return true;
}
function fruittree_uninstall(){
	return true;
}
function fruittree_dohook($hookname,$args){
	output("`^ Un-Installing: Fruit Tree - forest event `n`0");
	return $args;
}
function fruittree_runevent($type){
global $session;
$chance = 60;
$a="`qPeach";
$b="`6Pear";
$c="`@Apple";
$randx=e_rand(1,4);
if ($randx==1 or 4){ $tree=$a;}
if ($randx==2){ $tree=$b;}
if ($randx==3){ $tree=$c;}
$rand = e_rand(1,100);
$gain=$session['user']['hitpoints']*.1;
output("`n`n`2 You come upon a wonderful looking %s `2 tree which has fruit that is ready for picking! ",$tree);
output(" Not resisting temptation, you pick and eat several %s's `2 for yourself.",$tree);
if ($rand <= $chance) {
output(" You feel refreshed, however you notice some annoying flies which have taken a liking to you.");
output("`n`n You try to swat away the annoying flies, ...`b`i but now they follow you! `i`b");
$ffly = array(
	"name"=>"`#Fruit Fly",
	"rounds"=>40,
	"defmod"=>.9,
	"atkmod"=>1.0,
	"wearoff"=> "The Fruit Fly is no longer bothering you.",
	"roundmsg"=>"`7A `#Fruit Fly `7is hovering around while you try to fight.",
	"schema"=>"module-fruittree",
	);
apply_buff('ffly',$ffly);

$afly = array(
	"name"=>"`@Annoying Fly",
	"rounds"=>50,
	"defmod"=>1.0,
	"atkmod"=>.9,
	"wearoff"=> "You swat the Annoying Fly to the ground and step on it!",
	"roundmsg"=>"`7An `@Annoying Fly `7is buzzing around, making your fight less effective.",
	"schema"=>"module-fruittree",
	);
apply_buff('afly',$afly);
}else{
	output(" You feel really refreshed! ");
	if ($session['user']['hitpoints'] < $session['user']['maxhitpoints']) {
		output("`n`6 You gain a few hitpoints! ");
		$session['user']['hitpoints']+=$gain;
	}
}
}
function fruittree_run(){
}
?>