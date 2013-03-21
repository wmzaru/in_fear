<?php
/**************
Name: Squash a Fairy
Author: Eth - ethstavern(at)gmail(dot)com 
Version: 1.1
Release Date: 03-04-2005
Rerelease Date: 12-24-2005 (for 1.0.x)
About: Accidently squash a fairy while wandering through the forest.      
Translation compatible.
*****************/

function squashfairy_getmoduleinfo(){
	$info = array(
		"name"=>"Squash a Fairy",
		"version"=>"1.1",
		"author"=>"Eth",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/users/Eth/squashfairy.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Eth/",
		"settings"=>array(
			"Squash a Fairy - Main,title",
			"fairychance"=>"Chance of encountering fairy?,range,0,100,5|50",						
		),				
		"prefs"=>array(	
			"squashedfairy"=>"Squashed a fairy?,bool|0"				
		),
	);
	return $info;
}
function squashfairy_install(){	
	module_addeventhook("forest", "require_once(\"modules/squashfairy.php\"); return squashfairy_test();");	
	module_addhook("newday");	
	return true;
}
function squashfairy_uninstall(){
	return true;
}
function squashfairy_test(){
	global $session;	
	$chance = get_module_setting("fairychance","squashfairy");
	if (get_module_pref("squashedfairy","squashfairy") == 1) return 0; 
	return $chance; 
}
function squashfairy_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
		case "newday":		
		set_module_pref("squashedfairy",0);			
		break;	
	}
	return $args;
}
function squashfairy_runevent($type){
	global $session;
	$op = httpget('op');
	if ($type == "forest") $from = "forest.php?";				
	$session['user']['specialinc'] = "module:squashfairy";
	output("`n");	
	switch($type){
		case forest:
		if ($op=="") {
			switch(e_rand(1,6)){
				case 1:
				output("`2Tired from a long day of adventuring, you decide to take a break under the shade of a looming oak tree.`n`n");
				output(" `2Upon sitting down, however, you hear a high-pitched squeak followed by a faint squishing noise.`n`n");
				output("`2Curious and slightly embarrassed, you think you should stand up to investigate.`n`n");
				addnav("Stand Up",$from."op=standup");				
				break;
				case 2:
				output("`2After a few uneventful hours of wandering around the forest, you decide to take a break on a nearby log and plan out the rest of your day.");
				output(" `2However, as you sit, you hear a high-pitched squeak followed by a soft squishing sound.`n`n");
				output("`2Curious, you think you should stand up to investigate.`n`n");
				addnav("Stand Up",$from."op=standup");				
				break;
				case 3:
				output("`2After staggering into a grassy clearing, the thought of taking a nap weighs heavily on your mind.");
				output(" `2Settling down under the shade of a nice large tree, you suddenly hear a high-pitched squeak followed by a faint squishing sound.`n`n");
				output("`2Scratching your head in confusion, you think perhaps you should stand up to investigate.`n`n");
				addnav("Stand Up",$from."op=standup");				
				break;
				case 4:
				output("`2Wandering about the forest, while staring at the clouds overhead, you suddenly hear a loud squeak and a squishing noise from under your boot.`n`n");
				output("`2Maybe you should lift your boot to see what you just stepped in.`n`n");
				addnav("Lift Boot",$from."op=liftboot");				
				break;
				case 5:
				$foot = array(1=>"left",2=>"right");
				$boot = translate_inline($foot[e_rand(1,2)]);
				output("`2Walking around without much of a care, you look down and notice the laces of your %s boot have come undone.", $boot);
				output(" `2Grumbling to yourself, but not paying much attention, you slam your foot up upon a log to tie your laces.`n`n");
				output("`2As you do so, you notice a red, sparkling substance seeping out from under your boot.");
				output(" `2You seem to have squashed something.`n`n");
				addnav("Lift Boot",$from."op=liftboot");
				break;
				case 6:
				output("`2Feeling quite tired, you wander about the forest in a drowsy stupor.");
				output(" `2Stumbling over snapping twigs, crunching leaves, and the like, you suddenly hear a high-pitched squeak and a faint squishing noise under your boot.`n`n");
				output("`2Apparantly, you just stepped on something you shouldn't have.`n`n");
				addnav("Lift Boot",$from."op=liftboot");
				break;
			}
		}else if ($op=="standup"){
			//lets tell people what happened
			addnews("`3%s `2accidently `@squashed `2a fairy in the forest!", $session['user']['name']);
			set_module_pref("squashedfairy",1);	
			output("`2Standing up you discover");	
			switch(e_rand(1,5)){
				case 1:
				output(" `2that you had sat down upon a fairy and killed the little creature!`n`n");
				output("`2Feeling ashamed of yourself, you flick the poor thing into the bushes and say a small prayer for it.`n`n");
				$session['user']['specialinc'] = "";
				addnav("Back to Forest","forest.php");
				break;
				case 2:
				output(" `2you have managed to squash a fairy!");
				output(" `2However, it seems to have survived but is unconscious.`n`n");
				output("`2Thinking perhaps you don't want to be around when she awakens, you flick the little thing into a bush and casually wander off.`n`n");
				$session['user']['specialinc'] = "";
				addnav("Back to Forest","forest.php");				
				break;
				case 3:
				output(" `2you managed to thoroughly squash a poor fairy.");
				output(" `2However, she appears to be alive and quickly regains consciousness.`n`n");
				output("`3\"You dunderheaded oaf!\" she squeaks. \"You almost killed me!\"`n`n");
				output("`2She smacks you in the face with a small bag of fairy dust and flits off, leaving you to have a sneezing fit.`n`n");				
				$session['user']['specialinc'] = "";
				addnav("Back to Forest","forest.php");
				break;
				case 4:
				output(" `2a small red stain on the ground.");
				output(" `2Feeling your posterior, you peel off the corpse of a small fairy!`n`n");
				output("`2Looking around apprehensively, you toss it's little body into the bushes and casually walk off while whistling a nervous tune.`n`n");
				$session['user']['specialinc'] = "";
				addnav("Back to Forest","forest.php");
				break;
				case 5:
				output(" `2a very irritated but otherwise uninjured fairy!`n`n");
				output("`3\"You blasted fool, you almost killed me!\" she pips. \"I'll get you for that!\"`n`n");
				output("`2She smacks you in the face with a bag of fairy dust, leaving you to have a sneezing fit.");
				output("`2When the dust clears, she's nowhere to be found!`n`n");
				if ($session['user']['gems']>0){
					output("`2Checking your gem pouch however, you discover a gem missing!");
					output(" `2That annoying little fairy just robbed you!`n`n");
					$session['user']['gems']--;
				}
				$session['user']['specialinc'] = "";
				addnav("Back to Forest","forest.php");
				break;
			}
		}else if ($op=="liftboot"){
			set_module_pref("squashedfairy",1);
			//tell people what happened for the fun of it
			addnews("`3%s `2accidently `@squashed `2a fairy in the forest!", $session['user']['name']);
			output("`2Lifting your foot, you discover");
			switch(e_rand(1,5)){
				case 1:
				output(" `2a bright red stain on the sole of your boot.");
				output(" `2Looking down to the ground, you spot the flattened remains of what was once a fairy.`n`n");
				output("`2Scratching your head in bewilderment, you kick some leaves over it's body and casually wander off.`n`n");
				$session['user']['specialinc'] = "";
				addnav("Back to Forest","forest.php");
				break;
				case 2:
				output(" `2a small and very irritated fairy with nary a scratch on her.`n`n");
				output("`3\"You bumbling ox! Watch wear you're going!\" she screams, and smacks you in the face with a bag of fairy dust.`n`n");
				output("When your sneezing fit clears, she's nowhere to be found!`n`n");
				if ($session['user']['gems']>0){
					output("`2Checking your gem pouch however, you discover a gem missing!");
					output(" `2That annoying little fairy just robbed you!`n`n");
					$session['user']['gems']--;
				}
				$session['user']['specialinc'] = "";
				addnav("Back to Forest","forest.php");
				break;
				case 3:
				output(" `2a small, thoroughly squished body of a fairy.`n`n");
				output("`2Feeling slightly ashamed of yourself, you peel the body off and flick it into some nearby bushes.`n`n");
				$session['user']['specialinc'] = "";
				addnav("Back to Forest","forest.php");
				break;
				case 4:
				output(" `2a small, bright, sparkling red stain on the forest floor.");
				output(" `2Scratching your head in confusion, you wander off; not knowing that you have the squished remains of a fairy stuck to your boot.`n`n");
				$session['user']['specialinc'] = "";
				addnav("Back to Forest","forest.php");
				break;
				case 5:
				output(" `2the unconscious form of a fairy you very nearly squashed to death.");
				output(" `2Thinking for a moment, you decide to sweep her under some nearby bushes and be gone before she wakes.`n`n");
				output("`2Squashing a fairy, you've heard, is not good for one's health.`n`n");
				$session['user']['specialinc'] = "";
				addnav("Back to Forest","forest.php");
				break;
			}
		}
		break;
	}
}
function squashfairy_run(){	
}
?>