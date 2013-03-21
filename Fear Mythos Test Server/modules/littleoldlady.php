<?php
/**************
Name: The Little Old Lady
Author: Eth - ethstavern(at)gmail(dot)com 
Version: 1.0
Rerelease Date: 01-06-2006
About: Player comes across a little old woman in the streets. 
       Help her, trip her, or do nothing at all.
Translation ready!
*****************/
require_once("lib/villagenav.php");

function littleoldlady_getmoduleinfo(){
	$info = array(
		"name"=>"The Little Old Lady",
		"version"=>"1.0",
		"author"=>"Eth",
		"category"=>"Village Specials",
		"download"=>"http://www.dragonprime.net/users/Eth/littleoldlady.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Eth/",
		"settings"=>array(		
		"chance"=>"Chance of seeing her?,int|50",
		),
		"prefs"=>array(			
			"seenlittleoldlady"=>"Seen the little old lady today?,bool|0",		
		)
	);
	return $info;
}
function littleoldlady_install(){
	module_addeventhook("village", "require_once(\"modules/littleoldlady.php\"); return littleoldlady_test();");
	module_addhook("newday");
	module_addhook("changesetting");
	return true;
}
function littleoldlady_uninstall(){
	return true;
}
function littleoldlady_test(){
	global $session;	
	$chance = get_module_setting("chance","littleoldlady");
	if (get_module_pref("seenlittleoldlady","littleoldlady") == 1) return 0; 
	return $chance; 
}
function littleoldlady_dohook($hookname,$args){
	switch($hookname){
	case "newday":
		set_module_pref("seenlittleoldlady",0);
	break;	
	}
	return $args;
}
function littleoldlady_runevent($type)
{
	global $session;
	$sex = translate_inline($session['user']['sex']?"missy":"sonny");
	$from = "runmodule.php?module=littleoldlady&";
	$town = $session['user']['location'];
	if ($type == "village") $from = "village.php?";	
	$session['user']['specialinc'] = "module:littleoldlady";
	$op = httpget('op');	
	switch ($type) {
	case village:
	if ($op=="" || $op=="search"){
		output("`n");
		output("`2While standing about %s, not doing anything particularly exciting, you catch site of a little old lady slowly ambling towards you with a big bag of groceries in her hands.`n`n", $town);
		set_module_pref("seenlittleoldlady",1);
		addnav("Help Her", $from."op=help");
		addnav("Trip Her", $from."op=trip");
		addnav("Do Nothing", $from."op=nothing");
		//$session['user']['specialinc'] = "";
	}else if ($op == "help"){
		output("`2As the little old woman nears you, you kindly offer to help her carry her groceries.`n`n");
		switch(e_rand(1,6)){
			case 1:
			output("`2She pauses, glowers at you for a moment, then reaches into her bag and produces a cane!");
			output(" `2She soundly whacks you over the head with it, and continues on her way.`n`n");
			output("`3You suddenly feel a little less charming for having got beaten up by that old geezer.`n`n");
			if ($session['user']['hitpoints']>1) $session['user']['hitpoints']--;
			$session['user']['charm']--;
			$session['user']['specialinc'] = "";			
			break;
			case 2:
			output("`2\"Why thank you, %s,\" she responds, thrusting her bag into your arms. \"That's so thoughtful of you!\"`n`n", $sex);
			output("`2She proceeds to talk your ear off as you escort her home.");
			output(" `2When at last you reach her house, she offers her thanks again and quickly disappears inside.`n`n");
			output("`2You shrug and continue on your way.`n`n");
			$session['user']['specialinc'] = "";
			break;
			case 3:
			output("`2Acting as though she didn't hear you, the old woman simply walks right past you!`n`n");
			$session['user']['specialinc'] = "";
			break;
			case 4:
			output("`2She pauses a moment and looks you square in the face with her beady little eyes.`n`n");
			output("`2\"Pervert!\" she suddenly yells, and begins calling for help.`n`n");
			output("`2Not wanting that kind of trouble, you promptly take off running.`n`n");
			output("`3You lose some charm!`n`n");
			$session['user']['charm']--;
			$session['user']['specialinc'] = "";
			break;
			case 5:
			output("`2\"Well thank you kindly,\" she responds, and hands you her bag.`n`n");
			output("`2Upon taking her bag, you're immediately overcome by the over-powering stench of rotting flesh, and you spy a look into her bag.");
			output(" `2It's contains nothing but dead squirrels and rats!`n`n");
			output("`2Fighting back the urge to gag, you thrust the bag back to the old woman and take your leave of her.`n`n");
			output("`3There are limits to how kind you'll be, afterall.`n`n");
			$session['user']['specialinc'] = "";
			break;
			//momma told you not to talk to strangers
			case 6:
			output("`2She kindly thanks you for your act of generosity, and promises to reward you amply when you reach her house.");
			output(" `2Wondering what sort of reward the old woman will give you, you promptly follow behind her.`n`n");
			output("`2Upon reaching her house, you spy a look inside as she opens the door.");
			output(" `2You happen to see all manner of occult items hanging from the walls and rafters, including a few shrunken heads!");
			output(" `2Suddenly fearing for your safety, you turn to leave.`n`n");
			output("`2Before you can, however, a bony hand grabs you by the arm and yanks you inside with terrifying speed and strength.");
			output(" `2After the door slams shut, an ear-shattering scream is heard throughout town.`n`n");
			addnews("`3%s `2mysteriously disappeared after helping a little old lady in %s today!", $session['user']['name'],$town);			
			$session['user']['alive']=false;
			$session['user']['hitpoints']=0;
			$session['user']['specialinc'] = "";
			addnav("Land of Shades","shades.php");
			break;
		}
	}else if ($op == "trip"){
		output("`2As the little old lady passes by, you casually stick out your foot");		
		$session['user']['charm']--;
		switch(e_rand(1,3)){
			case 1:
			output(" `2and watch with a bit of mirth as she tumbles to the ground, her groceries spilling everywhere.`n`n");
			output("`2Before she can look up to see you, you quickly make off down an alley laughing all the way.`n`n");
			addnews("`3%s `2was seen tripping a defenseless old woman in `@%s `2today!",$session['user']['name'],$town);
			break;
			case 2:
			output(" `2and watch with disbelief as she nimbly hops over it and continues on her way!`n`n");
			output("`2Shaking your head, you decide to leave in search of other amusement.");
			if (e_rand(1,3)==1){
			output(" `2Looking down to the ground for a moment, you notice she dropped a gem!`n`n");
			$session['user']['gems']++;
			}else{
				output("`n`n");
			}
			break;
			case 3:
			output(" `2and watch with amusement as she tumbles to ground, her groceries spilling everwhere.`n`n");
			output("`2Before you can escape however, she swiftly hooks you by the ankle with her cane and yanks you forcefully to the ground!`n`n");
			output("`3\"The joke's on you, %s,\" she says, swiftly rising to her feet.", $sex);
			output(" `3\"You aint the only one who knows that game!\"`n`n");
			output("`2With a crooked smile, she whacks you in the head with her cane, gathers her groceries up, and goes on her way.");
			addnewS("`3%s `2was beaten up by an old woman in the streets of `@%s `2today!",$session['user']['name'],$town);
			break;
		}
		$session['user']['specialinc'] = "";
	}else if ($op == "nothing"){
		output("`2Not being the helpful type, you decide to let her simply pass on by.");
		switch(e_rand(1,3)){
			case 1:
			output(" `2As she goes past you, the old woman makes a rude gesture with her hand!`n`n");
			break;
			case 2:
			output(" `2The old woman gives you a dirty look as she passes by you, but says nothing.`n`n");
			break;
			case 3:
			output(" `2She hobbles past you without so much as a word or a look.`n`n");
			break;
		}
		$session['user']['specialinc'] = "";
	}	
	break;		
	}
}
function littleoldlady_run(){}	
?>