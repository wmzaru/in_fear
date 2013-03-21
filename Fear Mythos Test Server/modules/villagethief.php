<?php
/**************
Name: The Village Thief
Author: Eth - ethstavern(at)gmail(dot)com 
Version: 1.2
Rerelease Date: 01-26-2006
About: Player is accosted by a random stranger, and might
	   end up getting robbed. 	
Translation ready!
*****************/
require_once("lib/villagenav.php");

function villagethief_getmoduleinfo(){
	$info = array(
		"name"=>"Village Thief",
		"version"=>"1.2",
		"author"=>"Eth",
		"category"=>"Village Specials",
		"download"=>"http://www.dragonprime.net/users/Eth/villagethief.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Eth/",
		"settings"=>array(
		"villagechance"=>"Chance of seeing thief in village?,range,0,100,1|50",
		"goldloss"=>"Max percentage of gold player can lose,range,0,100,1|75",
		"gemsloss"=>"Max gem loss from thief,int|5",				
		),
		"prefs"=>array(
			"seenthief"=>"Seen the drunk today?,bool|0",
		)
	);
	return $info;
}
function villagethief_install(){
	module_addeventhook("village", "require_once(\"modules/villagethief.php\"); return villagethief_test();");
	module_addhook("newday");
	//module_addhook("changesetting");
	return true;
}
function villagethief_uninstall(){
	return true;
}
function villagethief_test(){
	global $session;	
	$chance = get_module_setting("villagechance","villagethief");
	if (get_module_pref("seenthief","villagethief") == 1) return 0; 
	return $chance; 
}
function villagethief_dohook($hookname,$args){
	switch($hookname){
		case "newday":
		set_module_pref("seenthief",0);
		break;
	}
	return $args;
}
function villagethief_steal(){
	global $session;	
	$chance = e_rand(1,3);
	$lossperc = e_rand(1,get_module_setting("goldloss"));
	$gemloss = e_rand(1,get_module_setting("gemsloss"));
	output("`3With a flash of sudden insight, you check your money pouch.`n`n");
	if ($chance == 1 && $session['user']['gold']>0){		
		$goldloss = round(($session['user']['gold']*$lossperc)/100);				
		output("`3You've been robbed of `^%s gold`3!", $goldloss);
		$session['user']['gold']-=$goldloss;
	}else if ($chance == 2 && $session['user']['gems']>=$gemloss){
		output("`3You've been robbed of `%%s gems`3!", $gemloss);
		$session['user']['gems']-=$gemloss;
	}else{
		output("`3It appears your money is safe. Phew!`n`n");
	}
	return true;
}
function villagethief_runevent($type){
	global $session;
	$sex = translate_inline($session['user']['sex']?"fellow":"lass");
	$sex1 = translate_inline($session['user']['sex']?"his":"her");
	$sex2 = translate_inline($session['user']['sex']?"he":"she");
	if ($type == "village") $from = "village.php?";
	else $from = "runmodule.php?module=villagethief&";
	//$session['user']['specialinc'] = "module:villagethief";
	$town = $session['user']['location'];
	$session['user']['specialinc'] = "";
	$op = httpget('op');
	switch ($type) {
	case village:
		if ($op=="" || $op=="search"){		
			output_notl("`n");
			villagenav();
			set_module_pref("seenthief",1);		
			switch(e_rand(1,5)){
				case 1:
				output("`2While innocently making your way about %s, a group of dirty children in rags suddenly swarm around you.", $town);
				output(" `2They run around your legs, laughing and giggling all the while.");
				output(" `2Finally having enough, you shoo them away.`n`n");
				villagethief_steal();
				break;
				case 2:				
				output("`2Passing through a group of townsfolk, one of them accidently bumps into you.`n`n");
				output("`2\"Sorry,\" he says with a nod, and continues about his way.`n`n");
				villagethief_steal();
				break;
				case 3:			
				output("`2You stop for a moment to help a rather attractive %s who clumsily has dropped %s basket of food on the ground.", $sex,$sex1);
				output(" `2Being the kind and charming person that you are, you kneel down to help.`n`n");
				output("`2\"Thank you so much!\" %s says, offering you as a smile. Moments later, %s is on %s way.`n`n", $sex2,$sex2,$sex1);
				villagethief_steal();
				break;
				case 4:
				output("`2You suddenly stop, thinking you felt something brush against you.");
				output(" `2Looking around however, you see nothing out of the ordinary and not a soul around.`n`n");
				villagethief_steal();
				break;
				case 5:
				output("`2Lazing about in the middle of town, a petite woman suddenly barrels into you!`n`n");
				output("`2\"Excuse me! I'm so sorry!\" she says, and suddenly continues on her way.`n`n");
				villagethief_steal();			
				break;
			}	
			
		}
		break;
	}
}
function villagethief_run(){}
?>