<?php
/*
History Log:
 v0.3:
	4/24/05
 o Navs complete, tested
*/


//Code will switch based on choice, and change alignment, charm, hitpoints
function forestcorpse_getmoduleinfo(){
	$info = array(
		"name"=>"The Corpse in the Forest",
		"version"=>"0.1",
		"author"=>"`2Jon Jagan, Six",
		"category"=>"Forest Specials",
		"settings"=>array(
			"Forest Corpse - Preferences,title",
			"aligngood"=>"How many alignment points for burying?,int|5",
			"alignbad"=>"How many alignment points for searching?,int|5",
			"alignworse"=>"How many alignment points for grilling?,int|10",
			"alignworst"=>"How many alignment points for violating?,int|15",				
		),
	        "requires"=>array( 
                        "alignment"=>"1.0|By Lonnyl, available on DragonPrime",
                        ), 
		"prefs"=>array(
			"corpse"=>"Seen the Corpse this newday?,bool|0",
			),
	);
	return $info;
}

function forestcorpse_chance() {
	return 100;
}

function forestcorpse_install(){
	module_addeventhook("forest","require_once(\"modules/forestcorpse.php\"); 
	return forestcorpse_chance();");
	module_addhook("newday");
	return true;
}

function forestcorpse_uninstall(){
	return true;
}

function forestcorpse_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
		case "newday":
			set_module_pref("corpse",0);
			break;
	}
	return $args;
}

function forestcorpse_runevent($type) {
	global $session;
	$session['user']['specialinc']="module:forestcorpse";
	$op = httpget('op');

		addnav("What do you do?");	//copied this outside everything else, since it will always be there -sixf00t4
		addnav("Leave","forest.php?op=leavecorpse");//moved this outside everything else, since it will always be there -sixf00t4
	
	if ($op==""){	//added by sixf00t4

		output("`n`7As you walk along a trail in the forest, you smell a `&strange odor, `7and come upon a fresh animal corpse. The poor animal looks to have been struck down and trampled by a speeding stallion, probably blinded by the headlights. The animal looks plump, freshly killed, yet `@cute `7to you for some reason.`n`n");
		output("`7You look around but see no witnesses...`n`n");

		addnav("Search the corpse","forest.php?op=searchcorpse");
		addnav("Bury the poor thing","forest.php?op=burycorpse");
		addnav("Fire up the grill and cook it!","forest.php?op=grillcorpse");
		addnav("No witnesses? Violate the corpse!", "forest.php?op=violatecorpse");
	}

	elseif ($op=='leavecorpse') { 			//sixf00t4 added 'else' 
		blocknav("forest.php?op=leavecorpse");	//needed so they dont get the option to reenter function
		global $session;
		output("`n`&You duck back into the forest before another recklessly driven stallion comes along.");
		$session['user']['specialinc']="";
		addnav("To the forest","forest.php");
	}

	elseif ($op=='searchcorpse') {
		global $session;
		output("`n`&You decide to search the corpse... `n");
		output("When the `%bloody heap `&is rolled over, a putrid smell assaults your nose.");
		output("A swarming pile of maggots and flies burst out of the carcass and you `^retch `&in disgust.`n`n");
		
		output("`n`n You `% lose one `^ charm `&for your foul odor! ");
		$session['user']['charm']--; 

		output("`n You feel a little evil for searching a `4dead animal `&too! What were you thinking? Did you really think animals carry around valuables?");
		$align = get_module_setting("alignbad");
		set_module_pref("alignment",get_module_pref("alignment","alignment")-$align,"alignment");
        }

	elseif ($op=='burycorpse') {
		//alignment up a little (good)
		global $session;
		output("`n`&Feeling pity for the creature so helplessly slaughtered by advances in `%technology `&which it simply could not comprehend, you give the poor creature the dignified burial it deserves. As you work, the `^light `&filters down through the trees and marks the burial spot. You feel a `4surge `&of goodwill emanating from the forest.`n`n");

		output("Your alignment has increased!!");
		// increase alignment
		$align = get_module_setting("aligngood");
		set_module_pref("alignment",get_module_pref("alignment","alignment")+$align,"alignment");
		
                output("`n`n You `% gain one `^ charm `&for your noble deed! ");
		$session['user']['charm']++; 
		$session['user']['turns']--;


		}
	elseif ($op=='grillcorpse'){
		//alignment down a little more (worse), HP back to max
		global $session;
		output("`n`&You gather wood and helpess woodland creatures, start a `#roaring fire, `&and throw the corpse on a spit. While the fat sizzles and the smell permeates the area, you search for `2various herbs `&to spice up the meat. Half an hour later you dig in with your %s.", $session['user']['weapon']);
		output("`n`n`&Well that was `@tasty, `&but you feel pretty guilty, don't you? Well, don't you?`n`n");

		// take alignment down, increase HP to maximum
		$align = get_module_setting("alignworse");
		set_module_pref("alignment",get_module_pref("alignment","alignment")-$align,"alignment");
		$session['user']['hitpoints']=$session['user']['maxhitpoints'];
		$session['user']['turns']--;
		}
	elseif ($op=='violatecorpse') {
		//alignment down a lot, gain a skill level
		global $session;
		output("`n`&You double check for witnesses, see none, and pull out your %s.", $session['user']['weapon']);
		output("`&After bashing the corpse around the head, you feel more skillful in your evil ways. `&Eventually you have had your fill of pointless violence against an `%already dead `&animal. `n`n");
	        //increase skill level
		require_once("lib/increment_specialty.php");
		increment_specialty("`3");

		//take alignment down a lot (worst), increase skill level
		$align = get_module_setting("alignworst");
		set_module_pref("alignment",get_module_pref("alignment","alignment")-$align,"alignment");	
		$session['user']['turns']--;

		output("`n`n You `% lose one `^ charm `&for your blood soaked clothing! ");
		$session['user']['charm']--; 
		}
		
}

//function added by sixf00t4
function forestcorpse_leavecorpse(){
//lets them leave
}

?>