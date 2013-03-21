<?php
/**************
Name: Random Bandit Attacks
Author: Eth - ethstavern(at)gmail(dot)com 
Version: 1.3
Release Date: 02-22-2005
About: Get attacked by bandits in town, once a day.
Translation compatible.
*****************/

// v1.31 SaucyWench 21 June 2006
// bandit name and weapon translation-ready (thanks to Chernobyl)
// corrected missing variable name when gold taken
// added settings for bonus experience and newbiecoddle

require_once("lib/villagenav.php");
require_once("lib/fightnav.php");
function randombandit_getmoduleinfo(){
	$info = array(
		"name"=>"Random Bandit Attack",
		"version"=>"1.31",
		"author"=>"Eth",
		"category"=>"Village Specials",
		"download"=>"http://dragonprime.net/users/Eth/randombandit.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Eth/",
		"settings"=>array(
			"Random Bandit - Main,title",	
			"banditchance"=>"Chance of encountering a bandit?,range,0,100,5|50",
			"goldbribe"=>"Percentage of player's gold to bribe bandit away,range,0,100,5|15",
			"goldonhand"=>"Gold player must have to be able to bribe bandit,int|500",
			"altcurrency"=>"Let player find alternative currency?,bool|0",
			"allcities"=>"Are bandits in all the towns/cities?,bool|1",
			"allowbonus"=>"Allow a bonus turn for flawless?,bool|0",
			"increasebonus"=>"Increase the experience bonus?,bool|0",
			"newbiecoddle"=>"Award 0 DK players extra experience bonus?,bool|1",
			"turnloss"=>"Percentage of turns to lose?,range,0,100,5|50",
			"banditloc"=>"Otherwise where do bandits appear?,location|".getsetting("villagename", LOCATION_FIELDS)
		),				
		"prefs"=>array(	
			"beenmugged"=>"Been mugged yet?,bool|0",				
		),
	);
	return $info;
}
function randombandit_install(){		
	module_addhook("newday");
	module_addhook("changesetting");
	module_addeventhook("village", "require_once(\"modules/randombandit.php\"); return randombandit_test();");			
	return true;
}
function randombandit_uninstall(){
	return true;
}

function randombandit_test(){
	global $session;	
	
	$loc = get_module_setting("banditloc","randombandit");
	$chance = get_module_setting("banditchance","randombandit");
	if (get_module_setting("allcities","randombandit") == 0) {
		if ($loc !=$session['user']['location']) return 0;		
	}	
	if (get_module_pref("beenmugged","randombandit") == 1) return 0; 
	return $chance; 
}
function randombandit_dohook($hookname,$args){
	global $session;
	$allowall = get_module_setting("allcities");
	switch ($hookname) {
		case "newday":	
		set_module_pref("beenmugged",0);	
		break;
		case "changesetting":
		if ($allowall == 1){
        	if ($args['setting'] == "villagename") {
            	if ($args['old'] == get_module_setting("banditloc")) {
                	set_module_setting("banditloc", $args['new']);
            	}
        	}
    	}
    	break;	
	}
	return $args;
}

function randombandit_runevent($type){
	global $session;
	$from = "village.php?";
	$session['user']['specialinc'] = "module:randombandit";
	$op = httpget('op');
	$loc = $session['user']['location'];
	$goldonhand = get_module_setting("goldonhand");
	$bribe = get_module_setting("goldbribe");
	$bribe = round($session['user']['gold']*$bribe/100);
	$mugged = get_module_pref("beenmugged");	
	$turnloss = get_module_setting("turnloss") /100;	
	$newbiecoddle = get_module_setting("newbiecoddle");	
	$increasebonus = get_module_setting("increasebonus");	
	if ($op == ""){		
			output("`n`2As you make your way through the town of %s, you catch a sudden movement from the corner of your eye!", $loc);
			output(" `2Feeling apprehensive, you quicken your stride only to be accosted a moment later by a sinister fellow in a dark cloak.`n`n");
			output("`2Sword drawn, he advances upon you.`n`n");
			addnav("Options");
			addnav("Fight Bandit!",$from."op=fightbandit"); 
			if ($session['user']['gold']>=$goldonhand) {addnav("Bribe `^$bribe Gold",$from."op=bribe"); }
			addnav("Run Away",$from."op=runaway");		
	}elseif ($op == "bribe"){
		output("`n`2Thinking quickly, you fumble with your money pouch and offer the bandit a hefty bribe in exchange for your life.");
		output(" `2He smirks for a moment, then lunges for your gold and quickly makes off.`n`n");
		addnews("%s `2was attacked by a bandit in `^%s`2 and bribed the devil to get away!",$session['user']['name'],$loc);
		$session['user']['gold']-=$bribe;
		$session['user']['specialinc'] = "";		
	}elseif ($op == "runaway"){
		//offer player a chance to run away
		if (e_rand(1,5)==1){
			output("`n`2You suddenly lunge at the bandit, pushing the devil aside, and make a break for it."); 	
			output(" `2After a few moments, you realize you aren't being followed any longer and breathe a sigh of relief.`n`n");
			addnews("%s `2was attacked by a bandit in `^%s`2 and safely escaped!",$session['user']['name'],$loc);
			set_module_pref("beenmugged",1);
			$session['user']['specialinc'] = "";		
		}else{
			output("`n`2Lunging at the bandit in attempt to shove him aside and run, you find he is too nimble for you.");
			output(" `2You have no choice now but to fight the ruffian!`n`n");
			addnav("Fight Bandit!",$from."op=fightbandit");
		}
	}elseif ($op == "fightbandit"){
		//set up the fight...
		$bandit_name = translate_inline('`6Sinister Bandit');
		$bandit_weapon = translate_inline('`6a Long Sword');
		$attack = array(1,3,5,7,9,11,13,15,17,19,21,23,25,27,29);
		$attack = $attack[$session['user']['level']];
		$defense = array(1,3,4,6,7,8,9,11,13,14,16,18,20,21,22);
		$defense = $defense[$session['user']['level']];
		$badguy = array(
			"creaturename"=>"`6Sinister Bandit",
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>"`6a Long Sword",
			"creatureattack"=>$attack,
			"creaturedefense"=>$defense,
			"creaturehealth"=>$session['user']['maxhitpoints'],							
			"diddamage"=>0,
			"type"=>"bandit");
			$session['user']['badguy']=createstring($badguy);
			$op="fight";
			httpset('op', "fight");			
	}	
	if ($op=="run"){
		if (e_rand(1,4)==1){
		output("`3You deftly dodge the bandit's swing and safely escape!`n`n");
		addnews("%s `2was attacked by a bandit in `^%s`2 and safely escaped!",$session['user']['name'],$loc);
		set_module_pref("beenmugged",1);		
		$session['user']['specialinc'] = "";		
		}else{
		output("`3Moving to escape, the crafty bandit blocks your way!`n`n");
		$op="fight";
		httpset('op', "fight");
		}
	}
	if ($op=="fight"){ $battle=true; }
	if ($battle){		
		include("battle.php");	
		if ($victory){	
			$exp = array(1=>14,2=>24,3=>24,4=>45,5=>55,6=>66,7=>77,8=>88,9=>99,10=>101,11=>114,12=>127,13=>141,14=>156,15=>172);
			$expbonus = $exp[$session['user']['level']];
			if ($newbiecoddle == 1) $expbonus *= 2;
			if ($increasebonus == 1) $expbonus *= 2;
			$gold = array(1=>36,2=>97,3=>148,4=>162,5=>198,6=>234,7=>268,8=>302,9=>336,10=>369,11=>402,12=>435,13=>467,14=>499,15=>531);
			$goldbonus = $gold[$session['user']['level']];
			$findalt = get_module_setting("altcurrency");
			output("`2With one last blow, you dispatch the vile bandit."); 	
			output(" `2Searching his body afterwards");	
			//can they find an alternate currency instead?
			if (is_module_active("altcurrency") AND $findalt == 1){				
				$sql = "SELECT name,findmax FROM ".db_prefix("altcurrency")." ORDER BY RAND(".e_rand().") LIMIT 1";
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
				$quantity = e_rand(1,$row['findmax']);
				$currency = $row['name'];	
				output(" `2you discover `^%s %s `2tucked away under his belt!`n",$quantity,$currency);								
				set_module_pref($currency,get_module_pref($currency,"altcurrency")+$quantity, "altcurrency");				
			//guess not...
			}else{			
				output(" `2you discover `^%s gold", $goldbonus); 			
				if (e_rand(1,15)==1){
					output("`3and a `%gem`2 tucked away under his belt!`n");
					$session['user']['gems']++;				
				}else{
					output(" `2tucked away under his belt!`n");
				}			
			}
			output("`3You also gain `#%s experience `3from this battle.`n`n", $expbonus);			
			if ($badguy['diddamage'] == 0 && get_module_setting("allowbonus") == 1){
				output("`#Flawless fight! You gain an extra turn.`n`n");
				$session['user']['turns']++;
			}
			$session['user']['gold']+=$goldbonus;
			$session['user']['experience']+=$expbonus;
			addnews("%s `2was attacked by a bandit in `^%s`2 and slew the vile fiend!",$session['user']['name'],$loc);
			set_module_pref("beenmugged",1);
			villagenav();
			$session['user']['specialinc'] = "";			
		}elseif ($defeat){	
			output("`2The bandit leaves you bloody and broken on the ground as he rifles through your coin purse.");
			if ($session['user']['gold']>100){
				//lose half of your gold
				$goldloss = round($session['user']['gold']*.50);
				$session['user']['gold']-=$goldloss;						
			output("`2He gladly helps himself to %s gold, and then disappears into an alley.`n`n",$goldloss);	
			}else{
				output("`2Finding nothing, he scowls and disappears; leaving you for dead.`n`n");
			}	
			//if alternative currency module is installed, let's have the chance to lose some of that as well		
			if (is_module_active("altcurrency") AND e_rand(1,3)==1){				
				$sql = "SELECT name,findmax FROM ".db_prefix("altcurrency")." ORDER BY RAND(".e_rand().") LIMIT 1";
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
				$quantity = e_rand(1,$row['findmax']);
				$currency = $row['name'];	
				if (get_module_pref($currency,"altcurrency")>=$quantity){
				output("`2Checking your coinpurse afterwards, you discover `^%s %s `2missing as well!`n",$quantity,$currency);								
				set_module_pref($currency,get_module_pref($currency,"altcurrency")-$quantity, "altcurrency");
				}				
			}
			//don't kill the player, just leave them badly beaten
			$session['user']['hitpoints']=1;
			$session['user']['turns']=round($session['user']['turns']*$turnloss);
			addnews("%s `2was attacked by a bandit in `^%s`2 and was mugged!",$session['user']['name'],$loc);
			set_module_pref("beenmugged",1);
			villagenav();
			$session['user']['specialinc'] = "";						
		}else{
			fightnav(true,true);
		}
	}		
}
function randombandit_run(){	
}
?>