<?php
/**************
Name: Duel of Honor
Author: Eth - ethstavern(at)gmail(dot)com 
Version: 1.0
Release Date: 03-15-2005
Rerelease Date: 12-24-2005 (for 1.0.x)
About: Battle an offended nobleman to reclaim your honor!      
Translation compatible.
*****************/
function duelofhonor_getmoduleinfo(){
	$info = array(
		"name"=>"Duel of Honor",
		"version"=>"1.0",
		"author"=>"Eth",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/users/Eth/duelofhonor.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Eth/",
		"settings"=>array(
			"Duel of Honor - Main,title",
			"findchance"=>"Chance of encountering noble?,int|60",	
			"Duel of Honor - Alignment,title",
			"the following requires the alignment module to be installed and active to work,note",
			"aligngood"=>"How many alignment points for sparing his life?,int|1",
			"alignbad"=>"How many alignment points lost for killing him?,int|1"					
		),				
		"prefs"=>array(	
			"dueled"=>"Dueled the noble yet?,bool|0"				
		),
	);
	return $info;
}
function duelofhonor_install(){	
	module_addhook("newday");		
	module_addeventhook("forest", "require_once(\"modules/duelofhonor.php\"); return duelofhonor_test();");	
	return true;
}
function duelofhonor_uninstall(){
	return true;
}
function duelofhonor_test(){
	global $session;		
	$chance = get_module_setting("findchance","duelofhonor");	
	if (get_module_pref("dueled","duelofhonor") == 1) return 0; 	 
	return $chance; 
}
function duelofhonor_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
		case "newday":	
		set_module_pref("dueled",0);	
		break;	
	}
	return $args;
}
function duelofhonor_runevent($type){
	global $session;
	$op = httpget('op');
	if ($type == "forest") $from = "forest.php?";
	//$session['user']['specialinc'] = "";			
	$title = translate_inline($session['user']['sex']?"Good Madam":"Good Sir");	
	$session['user']['specialinc'] = "module:duelofhonor";	
	output("`n");	
	switch($type){
		case forest:
		if ($op=="" || $op=="search") {	
			output("`2Stumbling into an open field, you are alarmed when suddenly a pheasant flies harriedly past you.");
			output(" `2A distance away you hear someone yell in disgust.");
			output("`2Turning to look, you see a young fop dressed in regal finery approaching you with a look of utter contempt on his face.`n`n");
			output("`3\"%s, you have ruined my hunt and disgraced me in front of my father!\" he snorts.", $title);
			output(" `3\"For this, I must duel you to reclaim my sullied honor!\"`n`n");
			output("`2He removes one of his gloves and proceeds slaps you across the face!");						
			addnav("Duel Him!",$from."op=duel");
			addnav("Refuse and Leave",$from."op=refuse");					
		}else if ($op=="refuse"){
			output("`2Shaking your head, you turn your back to the inscensed fop and walk away.`n`n");
			output("`3\"This is an outrage!\" he yells at you. \"You are a coward of the highest degree!\"`n`n");
			output("`2Stopping, you turn back around and draw your %s.", $session['user']['weapon']);
			output(" `2The young nobleman's face pales, and he dashes into the forest at a frantic pace.");
			output(" `2You chuckle to yourself and return to your journeys.`n`n");
			$session['user']['specialinc'] = "";
			addnav("Return to Forest","forest.php");
		}else if ($op=="spare"){
			set_module_pref("dueled",1);
			$expreward = round($session['user']['level']*15);
			$align = get_module_setting("aligngood");
			output("`2Stowing your %s`2, you offer a hand to the quivering young noble.", $session['user']['weapon']);
			output(" `2Assisting him to his feet, he brushes off his now tattered clothes and breathes a sigh of relief.`n`n");
			output("`3\"Though I have been defeated by a mere commoner,\" he says, \"I accept it so with dignity.\"`n`n");
			output("`2He reaches into a pouch by his side and hands you a small, glittering gemstone.`n`n");
			output("`3\"I would ask that you speak of this to no one, %s.\" he adds, and staggers away.`n`n", $title);
			output("`#You also gain `3%s experience `#from this battle as well.`n`n", $expreward);
			$session['user']['gems']++;
			$session['user']['experience']+=$expreward;
			//award alignment if activated
			if (is_module_active("alignment")) {
				set_module_pref("alignment",get_module_pref("alignment","alignment")+$align,"alignment");
			}
			addnews("`3%s `2dueled a young noble in the forest today and spared his life!", $session['user']['name']);
			$session['user']['specialinc'] = "";
			addnav("Return to Forest","forest.php");
		}else if ($op=="dispatch"){	
			$align = get_module_setting("alignbad");		
			$gold = $session['user']['level']*35;			
			set_module_pref("dueled",1);
			output("`2With no one around to bear witness to your actions, you coldly dispatch the quivering young fop.");
			output(" `2Bending down, you relieve his body of it's possessions.`n`n");
			if (e_rand(1,2)==1){ 
				output("`3You happen to find a glimmering gemstone among his things, in addition to a sum of `^%s gold`3!", $gold);
				$session['user']['gold']+=$gold;
				$session['user']['gems']++;				
			}else{
				output("`3You happen to find a small coin pouch containing `^%s gold `3among his things.", $gold);
				$session['user']['gold']+=$gold;
			}
			output(" `3However, since you so coldly dispatched an unarmed foe, you gain no experience from this battle.`n`n");
			//penalize alignment if activated
			if (is_module_active("alignment")) {
				set_module_pref("alignment",get_module_pref("alignment","alignment")-$align,"alignment");
			}
			addnews("`3%s `2dueled a young noble in the forest today and coldly struck him down!", $session['user']['name']);
			$session['user']['specialinc'] = "";
			addnav("Return to Forest","forest.php");	
		//lets set up the fops stats						
		}else if ($op=="duel"){
			$attack = array(1,3,5,7,9,11,13,15,17,19,21,23,25,27,29);
			$attack = $attack[$session['user']['level']];
			$defense = array(1,3,4,6,7,8,9,11,13,14,16,18,20,21,22);
			$defense = $defense[$session['user']['level']];
			$badguy = array(
			"creaturename"=>"`6Insolent Noble",
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>"`6a Rapier",
			"creatureattack"=>$attack,
			"creaturedefense"=>$defense,
			"creaturehealth"=>$session['user']['maxhitpoints'],							
			"diddamage"=>0,
			"type"=>"forest");
			$session['user']['badguy']=createstring($badguy);
			$op="fight";
			httpset('op', "fight");
		}
		if ($op=="run"){
			output("`3Moving to escape, the nobleman blocks your way!`n`n");
			$op="fight";
			httpset('op', "fight");			
		}
		if ($op=="fight"){ $battle=true; }
		if ($battle){		
		include("battle.php");	
			if ($victory){	
				output("`n`3Just as you wind back to deliver the final blow, the young fop throws up his hands up.`n");
				output("`#\"I concede this fight, %s!\" he cries. \"Please, spare me!\"`n", $title);
				output("`3Will you spare his life or dispatch this insolent pug?`n`n");
				addnav("Spare Him",$from."op=spare");
				addnav("Dispatch Him",$from."op=dispatch");				
			}else if ($defeat){
				output("`3Having underestimated the young noble's dueling skill, you are knocked to the ground by a series of masterfully placed blows.");
				output("`2Dazed and bloody, he pauses over your fallen form, rapier leveled at your throat.`n`n");
				//live or die?
				if (e_rand(1,2)==1){
					output("`2With a sneer, he dispatches you with a quick and merciful blow.");
					output(" `2The last thing you remember before you lose consciousness is the sound of his laughter as he relieves you of your coin purse.`n`n");
					$session['user']['hitpoints']=0;
					$session['user']['alive']=false;
					$session['user']['gold']=0;
					$session['user']['specialinc'] = "";
					addnav("View News","news.php");
				}else{
					output("`2He suddenly sheathes his sword, and extends a hand to you.`n`n");
					output(" `2Helping you to your feet, the young noble expresses his gratitude and congratulates you on your fine skills.`n`n");
					output("`3\"Not all of us are cut out to be duelists,\" he offers as he walks away.`n`n");
					$session['user']['hitpoints']=1;
					$session['user']['turns']--;
					$session['user']['specialinc'] = "";
					addnav("Return to Forest","forest.php");
				}
				addnews("`3%s `2dueled a young noble in the forest today and lost!", $session['user']['name']);				
			//}
			}else{
				fightnav(true,true);
			}
		}
		break;
	}
}
function duelofhonor_run(){	
}
?>