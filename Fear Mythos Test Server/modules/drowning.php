<?php
/**************
Name: Drowning Villager
Author: Eth - ethstavern(at)gmail(dot)com 
Version: 1.2
Release Date: 03-15-2005
Rerelease Date: 12-24-2005 (for 1.0.x)
About: Help or Turn away a drowning villager.      
Translation compatible.
*****************/
function drowning_getmoduleinfo(){
	$info = array(
		"name"=>"Drowning Villager",
		"version"=>"1.2",
		"author"=>"Eth",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/users/Eth/drowning.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Eth/",
		"settings"=>array(
			"Drowning Villager - Main,title",
			"findchance"=>"Chance of encountering villager?,int|100",			
			"drownchance"=>"Chance player will drown during rescue?,range,0,100,5|15",			
			"Drowning Villager - Alignment,title",
			"the following requires the alignment module to be installed and active to work,note",
			"aligngood"=>"How many alignment points for rescueing?,int|1",
			"alignbad"=>"How many alignment points lost for refusing?,int|1"
		),				
		"prefs"=>array(	
			"rescued"=>"Rescued villager yet?,bool|0"				
		),
	);
	return $info;
}
function drowning_install(){	
	module_addhook("newday");		
	module_addeventhook("forest", "require_once(\"modules/drowning.php\"); return drowning_test();");	
	return true;
}
function drowning_uninstall(){
	return true;
}
function drowning_test(){
	global $session;		
	$chance = get_module_setting("findchance","drowning");	
	if (get_module_pref("rescued","drowning") == 1) return 0; 	 
	return $chance; 
}
function drowning_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
		case "newday":	
		set_module_pref("rescued",0);	
		break;	
	}
	return $args;
}
function drowning_runevent($type){
	global $session;
	$op = httpget('op');
	if ($type == "forest") $from = "forest.php?";
	//$session['user']['specialinc'] = "";		
	$drownchance = get_module_setting("drownchance");
	$villager = translate_inline($session['user']['sex']?"man":"woman");	
	$gender1 = translate_inline($session['user']['sex']?"his":"her");
	$gender2 = translate_inline($session['user']['sex']?"him":"her");
	$gender3 = translate_inline($session['user']['sex']?"he":"she");	
	$session['user']['specialinc'] = "module:drowning";
	$aligngood = get_module_setting("aligngood");
	$alignbad = get_module_setting("alignbad");
	output("`n");	
	switch($type){
		case forest:
		if ($op=="" || $op=="search") {			
			output("`2A loud and frantic cry for help nearby shatters the peaceful tranquility of the forest.");
			output(" `2Alarmed, you run towards it's direction to find the cause of the problem.`n`n");
			output("`2Moments later, you arrive to find a %s from the village thrashing about in the deep end of a lake, crying desperately for help and trying to keep %s head above the water.`n`n", $villager, $gender1);
			output("`3Will you help?");
			addnav("Save Them!",$from."op=rescue");
			addnav("Refuse and Leave",$from."op=refuse");					
		}else if ($op=="rescue"){
			$roll = e_rand(1,100);
			//see if they drown first...
			if ($roll<$drownchance){
				$pgender = translate_inline($session['user']['sex']?"herself":"himself");
				output("`2Diving valiantly into the cold water, you make it half way to the drowning villager before your leg cramps up horribly and you are unable to continue.`n`n");
				output("`2Unable to swim, you find you yourself are now in danger of drowning!");
				output(" `2Weighed down by your heavy equipment, you quickly sink to the bottom before you have nary a chance to scream out for help.`n`n");
				output("`3You've drowned, and as a result all of your gold is now laying on the bottom of the lake!");
				addnews("`3%s `2%s drowned while trying to rescue a drowning villager!", $session['user']['name'], $pgender);
				$session['user']['hitpoints']=0;
				$session['user']['gold']=0;
				$session['user']['alive']=false;
				$session['user']['specialinc'] = "";
				addnav("View News","news.php");
			//otherwise, rescue that villager!
			}else{				
				output("`2Diving into the cold waters, you valiantly make your wake out to the drowning villager and safely drag %s ashore.`n`n", $gender2);
				switch(e_rand(1,4)){
					case 1:
					output("`3Ever greatful for your valor, %s gives you a tight embrace and a soft kiss on the cheek.", $gender3);
					output(" `3As the villager leaves, you can't help but feel more charming!`n`n");
					output("`#You gain a point of charm!`n`n");
					$session['user']['charm']++;					
					break;
					case 2:
					output("`3Shivering, yet greatful, %s pushes something into the palm of your hand before taking off back to the village.", $gender3);
					output(" `3Looking in your hand, you discover the villager has given you a gem in return for your heroics!`n`n");
					$session['user']['gems']++;
					break;
					case 3:
					output("`3Quite greatful for your act of valor, %s pushes you to the ground and proceeds to show you %s thanks.",$gender3,$gender1);
					output(" `3Some time later you and the villager part company, and you can't help but to feel more energized now!`n`n");
					output("`#You gain a forest fight!`n`n");
					$session['user']['turns']++;
					break;
					case 4:
					output("`3Greatful for your heroic rescue, %s bends down and removes a small sack from %s belongs on the shoreline.", $gender3,$gender1);
					if (is_module_active("altcurrency")){
						$sql = "SELECT name,findmax FROM ".db_prefix("altcurrency")." ORDER BY RAND(".e_rand().") LIMIT 1";
						$result = db_query($sql);
						$row = db_fetch_assoc($result);
						$quantity = e_rand(1,$row['findmax']);
						$currency = $row['name'];	
						output(" `3With a smile, %s hands you `6%s %s`3!`n",$gender3, $quantity,$currency);
						set_module_pref($currency,get_module_pref($currency,"altcurrency")+$quantity, "altcurrency");
					}else{
						$reward = e_rand(100,300);
						output("`3With a smile, %s hands you `^%s gold coins`3!`n`n", $gender3,$reward);
						$session['user']['gold']+=$reward;
					}					
					break;
				}
			if (is_module_active("alignment")) {
				set_module_pref("alignment",get_module_pref("alignment","alignment")+$aligngood,"alignment");
			}	
			set_module_pref("rescued",1);
			addnews("`3%s `2rescued a drowning villager in the forest today!", $session['user']['name']);
			$session['user']['specialinc'] = "";
			addnav("Return to Forest","forest.php");
		}				
		}else if ($op=="refuse"){			
			output("`2Feeling the villager is better off on %s own, you turn your back to %s, and walk away.", $gender1,$gender1);
			output(" `2Soon, the splashing stops and the pleading voice grows quiet.`n`n");
			output("`3You feel a twinge of regret suddenly and wish now you had stopped to rescue %s.`n`n", $gender2);
			if ($session['user']['turns']>0) $session['user']['turns']--;
			if (e_rand(1,3)==1) {
				output("`2You also lose a charm point for your cruel indifference.`n`n");			
				$session['user']['charm']--;
			}
			if (is_module_active("alignment")) {
				set_module_pref("alignment",get_module_pref("alignment","alignment")-$alignbad,"alignment");
			}
			addnews("`3%s `2refused to aid a drowning villager in the forest today!", $session['user']['name']);
			set_module_pref("rescued",1);
			$session['user']['specialinc'] = "";
			addnav("Back to Forest","forest.php");					
		}
		break;
	}
}
function drowning_run(){	
}
?>