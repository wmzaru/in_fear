<?php
/**************
Name: Lich Encounter
Author: Eth - ethstavern(at)gmail(dot)com 
Version: 1.1
Release Date: 03-11-2005
Rerelease Date: 12-24-2005 (for 1.0.x)
About: Encounter a lich, give him a gem.      
Translation compatible.
*****************/
function lichencounter_getmoduleinfo(){
	$info = array(
		"name"=>"Lich Encounter",
		"version"=>"1.1",
		"author"=>"Eth",
		"category"=>"Graveyard Specials",
		"download"=>"http://dragonprime.net/users/Eth/lichencounter.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Eth/",
		"settings"=>array(
			"Lich Encounter - Main,title",
			"lichchance"=>"Chance of encountering lich?,int|50"		
		),				
		"prefs"=>array(	
			"seenlich"=>"Run into Lich Yet?,bool|0"				
		),
	);
	return $info;
}
function lichencounter_install(){	
	module_addhook("newday");		
	module_addeventhook("graveyard", "require_once(\"modules/lichencounter.php\"); return lichencounter_test();");
	return true;
}
function lichencounter_uninstall(){
	return true;
}
function lichencounter_test(){
	global $session;		
	$chance = get_module_setting("lichchance","lichencounter");	
	if (get_module_pref("seenlich","lichencounter") == 1) return 0; 	 
	return $chance; 
}
function lichencounter_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
		case "newday":	
		set_module_pref("seenlich",0);	
		break;	
	}
	return $args;
}
function lichencounter_runevent($type){
	global $session;
	$op = httpget('op');
	if ($type == "graveyard") $from = "graveyard.php?";
	else if ($type == "village") $from = "village.php?";
	//$session['user']['specialinc'] = "";			
	$session['user']['specialinc'] = "module:lichencounter";
	output("`n");	
	switch($type){		
		case grayeyard:
		if ($op=="" || $op=="search") {
			output("`2Sitting upon a rock, lamenting your fate, you are approached by a tall figure wrapped in bandages and a dark robe.");
			output(" `2Looking into the creature's burning blue eyes, you have no doubt a lich is standing before you.`n`n");
			output("`3\"Fear me not, spiritling,\" says the lich in a sepulchural voice.");
			output(" `3\"I ask only a favor of ye. A gem I seek to extend my magicks. A favor I shall grant, in return.\"`n`n");
			if ($session['user']['gems']<1){
				output("`2However, upon sensing you have none, the lich merely grunts his disgust, and walks away.`n`n");
				$session['user']['specialinc'] = "";
				addnav("Return to Lamenting","graveyard.php");	
			}else{
				addnav("Give Gem",$from."op=givegem");
				addnav("Decline",$from."op=decline");
			}			
		}else if ($op=="givegem"){
			$session['user']['gems']--;
			set_module_pref("seenlich",1);
			switch(e_rand(1,3)){
				case 1:
				output("`2Relunctantly parting with one of your precious gemstones, you hand it to the lich.");
				output(" `2Papery lips pull back to form a smile and he reaches into his robes.`n`n");
				output("`3\"My thanks, spiritling. Take this,\" he says, and hands you a small, faintly glowing gem.");
				output(" `3\"'Tis but a bauble, and I have no need for it.\"`n`n");
				output("`2As you cradle the `4soul gem `2in your hands, the lich calmy walks away.`n`n");
				$session['user']['gravefights']++;
				output("`&Your soul feels as if its capacity for torment has been increased.`n");
				$session['user']['deathpower']+=e_rand(4,10);
				output("`&You feel that Ramius is pleased.`n`0");
				$session['user']['specialinc'] = "";
				addnav("Return to Lamenting","graveyard.php");
				break;
				case 2:
				output("`2You relunctantly part with one of your gems and hand it to the lich.");
				output(" `2His paper-like lips pull back to form a smile and he gives a soft laugh.`n`n");
				output("`3\"So foolish, these young folk,\" he says with a sneer and suddenly vanishes, his mocking laughter still faintly echoing in the chilled air.`n`n");
				output("`2That horrid thing tricked you!");
				output(" `2No matter you conclude, as a desire for revenge takes hold in the core of your soul.`n`n");
				output("`&You gain a torment!`n`n");
				$session['user']['gravefights']++;
				$session['user']['specialinc'] = "";
				addnav("Return to Lamenting","graveyard.php");
				break;
				case 3:
				output("`2With a bit of hesitation, you hand one of your precious gems to the lich.");
				output(" `2He gives you a bemused smirk and begins muttering a soft incantation.`n`n");
				output("`2Moments later, you feel a burning agony deep within your soul, and you cry out in absolute pain.`n`n");
				output("`2After what seems an eternity, the lich finishes his incantation, and then vanishes in a swirl of smoke.");
				output(" `2Fighting to get to your knees, you struggle to stay conscious.");
				output(" `2Just as you open your mouth to curse the foul being, you hear his words echoing in your mind:`n`n");
				output("\"Ye shall thank me when life once again embraces ye...\"`n`n");
				$reward = e_rand(1,3);
				if ($reward == 1) { $what = "hitpoint"; }
				else if ($reward>1) { $what = "hitpoints"; }
				output("`#You've gained %s permanent %s!",$reward,$what);
				$session['user']['maxhitpoints']+=$reward;
				$session['user']['specialinc'] = "";
				addnav("Return to Lamenting","graveyard.php");
				break;
			}
		}else if ($op=="decline"){
			output("`2The lich grunts his disapproval and stares at you with his burning eyes.`n`n");
			output("`3\"What need have ye of riches here?\" he chides you, sweeping an emaciated arm about the barren graveyard.`n`n");
			output("`2Annoyed with you, he simply sighs and walks away.`n`n");			
			$session['user']['specialinc'] = "";
			addnav("Return to Lamenting","graveyard.php");
		}
		break;
	}
}
function lichencounter_run(){	
}
?>