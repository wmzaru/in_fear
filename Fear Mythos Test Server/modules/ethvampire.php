<?php
/**************
Name: The Vampire
Author: Eth - ethstavern(at)gmail(dot)com 
Version: 1.0
Rerelease Date: 02-02-2006
About: Player is attacked by a vampire, and becomes evil. Very evil.
       Credit to DarQness for the idea. 	
Translation ready!
*****************/
require_once("lib/villagenav.php");
require_once("lib/buffs.php");
require_once("./modules/alignment/func.php");

function ethvampire_getmoduleinfo(){
	$info = array(
		"name"=>"The Vampire",
		"version"=>"1.2",
		"author"=>"`@Eth",
		"category"=>"Village Specials",
		"download"=>"http://www.dragonprime.net/users/Eth/ethvampire.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Eth/",
		"requires"=>array(
            "alignment"=>"Basic Alignment Module; by WebPixie, `#Lonny Luberts,`^and Chris Vorndran",
        ),
		"settings"=>array(
			"The Vampire - Main Settings,title",
			"where"=>"Where does this event occur?,enum,0,Village,1,Forest",
			"vamploc"=>"What town should it appear in?,location|".getsetting("villagename", LOCATION_FIELDS),
			"villagechance"=>"Chance of being bitten in village?,range,0,100,1|10",
			"forestchance"=>"Chance of being bitten in forest?,range,0,100,1|10",			
		),
		"prefs"=>array(
			"The Vampire - User Prefs,title",
			"beenbitten"=>"Been bitten yet?,bool|0",
		)
	);
	return $info;
}
function ethvampire_install(){	
	if (get_module_setting("where","ethvampire") == 0) module_addeventhook("village", "require_once(\"modules/ethvampire.php\"); return ethvampire_test();");
	if (get_module_setting("where","ethvampire") == 1) module_addeventhook("forest", "require_once(\"modules/ethvampire.php\"); return ethvampire_test();");
	module_addhook("newday");
	module_addhook("dragonkill");
	module_addhook("changesetting");
	return true;
}
function ethvampire_uninstall(){
	return true;
}
function ethvampire_test(){
	global $session;	
	$where = get_module_setting("where","ethvampire");
	if ($where == 0)$chance = get_module_setting("villagechance","ethvampire");
	else if ($where == 1) $chance = get_module_setting("forestchance","ethvampire");
	if (get_module_pref("beenbitten","ethvampire") == 1) return 0; 
	return $chance; 
}
function ethvampire_dohook($hookname,$args){
	switch($hookname){
		case "changesetting":
		if ($args['setting'] == "villagename") {
			if ($args['old'] == get_module_setting("vamploc")) {
				set_module_setting("vamploc", $args['new']);
			}
		}
		break;
		case "dragonkill":
		set_module_pref("beenbitten",0);
		break;
	}
	return $args;
}

function ethvampire_runevent($type){
	global $session;
	if ($type == "village") $from = "village.php?";
	else if ($type == "forest") $from = "forest.php?";
	else $from = "runmodule.php?module=ethvampire&";
	$session['user']['specialinc'] = "module:ethvampire";
	$town = $session['user']['location'];	
	$op = httpget('op');
	output_notl("`n");
	switch ($type) {
	case village:
		if ($op=="" || $op=="search"){			
			output("`2You come to a halt, a great feeling of dread washing over you suddenly.");
			output(" `2Looking around, you see no one in sight; not a single villager or adventurer, not even a stray dog or cat.");
			output(" `2It's as if %s has become completely abandoned!`n`n", $town);					
			output("`2Your fear intensifies. Your heart begins to race, your body breaks out in a cold sweat, and shivers run down your spine.`n`n");
			output("`2Suddenly a shadow seperates itself from a nearby alley. With blinding speed it is upon you, knocking you to the ground and pinning you there with supernatural strength.`n`n");
			output(" `2Before you can scream, sharp teeth are in your neck!");
			output(" `2Within moments the world around you fades, and you sink into darkness.`n`n");
			addnav("Wake Up",$from."op=wakeup");
		}else if ($op == "wakeup"){
			set_module_pref("beenbitten",1);
			$session['user']['specialinc'] = "";
			switch (e_rand(1,3)){
				case 1:
				case 2:
				$sex = translate_inline($session['user']['sex']?"she":"he");
				output("`2Coming to, you find a group of villagers surrounding you, looks of concern upon their faces. Nearby, you see a nervous-looking priest holding a holy symbol.`n`n");
				output("`@\"Aye, %s appears alright\" `2says one of the villagers, after a quick examination.", $sex);				
				output("`2He helps you to your feet, and departs soon after without another word. The others that had gathered take their leave as well.`n`n");
				output(" `2Though your neck is throbbing and you feel confused and faint, you are otherwise unharmed.`n`n");
				output("`3Consider this your lucky day!`n`n");
				//well, there has to be *some* kind of penalty
				$session['user']['turns'] = round($session['user']['turns']*.50);
				$session['user']['hitpoints'] = round($session['user']['hitpoints']*.50);
				villagenav();
				break;
				case 3:				
				if (is_module_active("racevamp")) $mindk = get_module_setting("mindk","racevamp");
				output("`2You awaken alone in a dark, cold alley way. Your neck throbs, and your head is pounding.");
				output(" `2With a struggle, you slowly rise to your feet.`n`n");
				if (is_module_active("racevamp") && $session['user']['dragonkills']>=$mindk && $session['user']['race']!="Vampire"){
					$favorratio = get_module_setting("favorratio","racevamp");
    				$vamp = max(round($session['user']['deathpower']/$favorratio),3);					
					$session['user']['race'] = "Vampire";
					strip_buff("racialbenefit");
			        apply_buff("racialbenefit",array(
				        "name"=>"`@Vampiric Elders`0",
				        "atkmod"=>"(<attack>?(1+((1+floor($vamp))/<attack>)):0)",
			    	    "allowintrain"=>1,
			        	"allowinpvp"=>1,
			            "rounds"=>-1,)
		            );	            	
      				output("`2Somehow, you feel different. A great darkness has settled in your heart, and a greater air of indifference and malignancy pervades your mind.`n`n");
	            	output("`2Though you aren't fully aware of it at the moment, you have become a vampire!`n`n");
            	}else if (is_module_active("racevamp") && $session['user']['race'] == "Vampire"){
	            	output("`2Quite confused at having been attacked by a fellow vampire, you still feel its foul essence pervading your very being.");
	            	output(" `2You have become even more `4evil `2as a result.`n`n");
            	}else{
	            	$race = strtolower($session['user']['race']);
	            	output("`2Whatever creature attacked you, it has left its foul essence within you.");
	            	output(" `2Though still a `@%s`2, great evil has taken hold within you, and you are forever changed.`n`n", $race);
	            }
            	if (is_module_active('alignment')) {
					set_align("0",false);
				}
				villagenav();
				break;
			}
		}
	break;
	case forest:
		if ($op=="" || $op=="search"){			
			$session['user']['specialinc'] = "module:ethvampire";
			output("`2An unnatural silence settles about the forest. Not a single breeze, not a single noise.");		
			output(" `2A growing sense of dread fills you, causing you to break out in a cold sweat.`n`n");
			output("`2To your right, a shadow suddenly seperates itself from behind a tree. Before you can scream, it is upon you!`n`n");
			output("`2You are slammed to the ground with brutal force and sharp teeth quickly pierce your neck.");
			output(" `2A cold, clammy hand covers your mouth, muffling your screams.`n`n");
			output("Within moments the world around you begins to fade, and you soon sink into darkness.`n`n");
			addnav("Wake Up",$from."op=wakeup");
		}else if ($op == "wakeup"){
			set_module_pref("beenbitten",1);
			$session['user']['specialinc'] = "";
			switch(e_rand(1,3)){				
				case 1:
				case 2:
				output("`2You awaken some time later, with a concerned hunter standing over you.");
				output(" `2He helps you to your feet, never once taking his eyes off the wound on your neck.`n`n");
				output("`@\"Aye, you appear alright,\" `2he notes after a quick examination, and sheathes a dagger he had held in his other hand.");
				output(" `2Without another word, he disappears back into the forest.`n`n");
				output("`2Though you feel faint, and your neck throbs, you are otherwise unharmed.`n`n");
				output("`3Consider this your lucky day!`n`n");
				//well, there has to be *some* kind of penalty
				$session['user']['turns'] = round($session['user']['turns']*.50);
				$session['user']['hitpoints'] = round($session['user']['hitpoints']*.50);
				addnav("Back to Forest","forest.php");
				break;
				case 3:			
				if (is_module_active("racevamp")) $mindk = get_module_setting("mindk","racevamp");	
				output("`2You awaken alone in the shade of a towering oak tree, your neck throbbing and your head pounding.");
				output(" `2Rising slowly to your feet, you suddenly note that you feel changed somehow.`n`n");
				if (is_module_active("racevamp") && $session['user']['dragonkills']>=$mindk && $session['user']['race']!="Vampire"){
					$favorratio = get_module_setting("favorratio","racevamp");
    				$vamp = max(round($session['user']['deathpower']/$favorratio),3);					
					$session['user']['race'] = "Vampire";
					strip_buff("racialbenefit");
			        apply_buff("racialbenefit",array(
				        "name"=>"`@Vampiric Elders`0",
				        "atkmod"=>"(<attack>?(1+((1+floor($vamp))/<attack>)):0)",
			    	    "allowintrain"=>1,
			        	"allowinpvp"=>1,
			            "rounds"=>-1,)
		            );	   
       				output("`2You feel different. A great darkness has settled in your heart, and a greater air of indifference and malignancy pervades your mind.`n`n");         	
	            	output("`2Though you aren't fully aware of it at the moment, you have become a `\$vampire`2!`n`n");
            	}else if (is_module_active("racevamp") && $session['user']['race'] == "Vampire"){
	            	output("`2Confused at having been attacked by a fellow vampire, you still feel it's foul essence pervading your very being.");
	            	output(" `2You have become even more evil as a result.`n`n");
            	}else{
	            	$race = strtolower($session['user']['race']);
	            	output("`2Whatever creature attacked you, it has left its foul essence within you.");
	            	output(" `2Though you are still a `@%s`2, great evil has taken hold within you, and you are forever changed.`n`n", $race);
	            }
            	if (is_module_active('alignment')) {	            	
					set_align("0",false);
				}
				addnav("Back to Forest","forest.php");
				break;
			}
		}
	break;		
	}
}
function ethvampire_run(){}
?>