<?php
function foilwench3_getmoduleinfo(){
	$info = array(
		"name"=>"Foil Wench Part 3",
		"version"=>"1.0",
		"author"=>"DaveS, NightBorn; based on FoilWench",
		"category"=>"Forest Specials",
		"download"=>"",
		"settings"=>array(
			"Foil Wench Part 3,title",
			"points"=>"How many skills points in their current specialty before Foil Wench won't increment specialty?,int|10",
			"forest"=>"Chance to encounter in the forest:,range,1,100,1|100",
		),
		"prefs"=>array(
			"Foil Wench Part 3,title",
			"done"=>"Has specialty been refreshed today?,bool|",
		),
	);
	return $info;
}
function foilwench3_chance() {
	$ret= get_module_setting('forest','foilwench3');
	if (get_module_pref("done",'foilwench3')==1) $ret=0;
	return $ret;
}

function foilwench3_install(){
	module_addeventhook("forest","require_once(\"modules/foilwench3.php\");
	return foilwench3_chance();");
	module_addhook("newday");
	return true;
}
function foilwench3_uninstall(){
	return true;
}
function foilwench3_dohook($hookname,$args){
	switch ($hookname) {
		case "newday":
			set_module_pref("done",0);
		break;
	}
	return $args;
}
function foilwench3_runevent($type) {
	global $session;
	$op = httpget('op');
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:foilwench3";
	set_module_pref("done",1);
	if ($session['user']['specialty'] == "") {
		output("You step into a clearing and are surrounded by the most amazingly beautiful fairy you've ever seen.");
		output("She pulls out a huge wand; one that perhaps would be large enough to club an elephant, and she starts to walk towards you.  Then, she notices that you have no direction in your life.");
		output("`n`nShe leaves in a huff, advising you that perhaps you should go find a calling for yourself.");
		$session['user']['specialinc']="";
		return;
	}
	$colors = array(""=>"`5");
	$colors = modulehook("specialtycolor", $colors);
	$c = $colors[$session['user']['specialty']];
	if ($op=="look"){
		output("%sYou give `!Foil `QWench%s the okay to go ahead and she pulls back her wand.`n`n",$c,$c);
		output("`@`b`c<WHAM!!!!>`c`b`n");
		output("`%You suddenly have a terrible headache. The damage is done though, and your hitpoints are reduced to one.`n`n",$c);
		output("However, the good news... Your specialty points have increased!");
		require_once("lib/increment_specialty.php");
		$session['user']['hitpoints']=1;
		increment_specialty("`3");
		debuglog("incremented specialty at the cost of all hitpoints except 1.");
		$session['user']['specialinc']="";
	}elseif($op=="dont"){
		output("%sYou notice the spikes on the wand do NOT look very friendly.  Yes, it's wise to move away from the old lady.", $c);
		$session['user']['specialinc']="";
	}elseif($session['user']['specialty']!=""){
		output("%sQuietly hunting for an easy kill, you see an old woman standing by a tree stump.  She sees you and her eyes widen.",$c);
		output("`n`n\"`@Hey hey hey! I see you over there! Come here and let me take a closer look at you!%s\" she says. \"`@I'm `!Foil `QWench`@.  Would you like to look at my Wand?%s\"`n`n",$c,$c);
		output("From a comfortable distance, you can see that this isn't an ordinary wand.  It looks like it weighs as much as a heavy war axe.`n`n");
		output("\"`#Err... I can see it from here,%s\" you tell her.`n`n", $c);
		output("Moving faster than you would have anticipated, she's holding your nose between her thumb and her finger and twisting it back and forth.");
		//Next 2 lines thanks to Nightborn and concurrently from Sichae's Holy Redeemer's Guild
		$mods = modulehook("specialtymodules");
		$name = $mods[$session['user']['specialty']];
		if (get_module_pref("skill", $name) < get_module_setting("points")) {
			output("\"`@I can help you.  Seriously.  If you want my help, just tell me,%s\" `!Foil `QWench%s says.",$c,$c);
			output("`n`n\"`#Well, what do I have to do?%s\" you ask.",$c);
			output("`n`n\"`@Just let me show you my `iSpecial Wand`i%s\" she says as she waves the mace-like instrument at you.",$c);
			addnav("Examine the Wand", $from."op=look");
			addnav("Don't Examine the Wand",$from."op=dont");
		}else{
			output("\"`@Bah!%s\" she says, \"`@You don't need my help. Perhaps another day.%s\"",$c,$c);
			output("`n`nBefore you know it, she's gone and you're standing in the middle of the forest with a pinched nose.  Oh well!");
			$session['user']['specialinc']="";
		}
	}
}
function foilwench3_run(){
}
?>
