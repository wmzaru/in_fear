<?php

function cellar_getmoduleinfo(){
	$info = array(
		"name"=>"Inn Cellar",
		"author"=>"Spider",
		"version"=>"1.4",
		"category"=>"Inn",
		"download"=>"http://dragonprime.net/users/Spider/cellar.zip",
		"settings"=>array(
			"specialchance"=>"Chance for an event in the Cellar,range,0,100,1|75",
			"cellarmoves"=>"How many cellar moves do users have per day?,int|15",
		),
		"prefs"=>array(
			"Inn Cellar User Preferences,title",
			"moves"=>"Cellar moves left today,int|15",
		)
	);
	return $info;
}

function cellar_install(){
	module_addhook("inn");
	module_addhook("newday");
	module_addeventhook("cellar","return 100;");
	return true;
}

function cellar_uninstall(){
	return true;
}

function cellar_dohook($hookname, $args){
	global $session;
	switch($hookname){
	case "inn":
		addnav("Things to do");
		addnav("E?Explore the Cellar","runmodule.php?module=cellar");
		break;
	case "newday":
		set_module_pref("moves",get_module_setting("cellarmoves"));
		break;
	}
	return $args;
}

function cellar_run(){
	global $session;
	require_once("lib/commentary.php");
	require_once("lib/events.php");
	require_once("lib/http.php");
	addcommentary();
	$op = httpget('op');
	page_header("Inn Cellar");

	$cm = get_module_pref("moves");
	if ($cm<1){
		output("`c`bThe Cellar`b`c");
		output("You take a step forward in the dark and hear a noise behind you, spinning around you hear another sound in the other direction.  ");
		output("Gripped by fear you cannot face exploring the cellar any more today and run towards the stairs.`n`n");
		addnav("Return to the inn","inn.php");
	}
	elseif ($op=="search"){
		$cm--;
		set_module_pref("moves",$cm);
		output("`c`bThe Cellar`b`c");
		output("Feeling extra brave, you look around in the dark cellar for something interesting...`n`n");
		$skipcellardesc = handle_event("cellar");
		if (module_events("cellar", get_module_setting("specialchance", "cellar")) != 0) {
			if (checknavs()) {
				page_footer();
			} else {
				$session['user']['specialinc'] = "";
				$session['user']['specialmisc'] = "";
				$skipcellardesc=true;
				$op = "";
				httpset("op", "");
			}
		}

		if ($cm>0){
			addnav("Explore the cellar","runmodule.php?module=cellar&op=search");
		}
		addnav("Return to the inn","inn.php");

		if (!$skipcellardesc) {
			output("`%But you find nothing of interest.`n`n");
		}
		output("`0You have `6%s `0cellar moves left today`n`n",$cm);
		module_display_events("cellar", "runmodule.php?module=cellar");
	}
	else{
		output("`c`bThe Cellar`b`c");
		output("You step down into the dark cellar, taking a moment for your eyes to accustom themselves to the darkness you prepare to venture out in search of... something...`n`n");
		output("You have `6%s `0cellar moves left today`n`n",$cm);
		addnav("Explore the cellar","runmodule.php?module=cellar&op=search");
		addnav("Return to the inn","inn.php");
	}
	page_footer();
}

function cellar_runevent($type){
	global $session;
	$move = e_rand(1,9);
	switch($move){
		case 1:
			output("`%In the darkness of the cellar you drop a little of your gold.`n`n");
			$sgold=$session['user']['gold'];
			$gold=round($session['user']['level']*20*r_rand(0.9,1.1));
			$session['user']['gold']-=$gold;
			if ($session['user']['gold']<0){
				$session['user']['gold']=0;
				output("You lose all of your gold.`n`n");
				debuglog("lost " . $sgold . " gold in the cellar");
			}
			else{
				output("You lose `^%s `%gold.`n`n",$gold);
				debuglog("lost " . $gold . " gold in the cellar");
			}
			break;
		case 2:
			output("`%You stumble across some gold another adventurer appears to have dropped.`n`n");
			$gold=round($session['user']['level']*20*r_rand(0.9,1.1));
			$session['user']['gold']+=$gold;
			debuglog("found " . $gold . " gold in the cellar");
			output("You find `^%s `%gold.`n`n",$gold);
			break;
		case 3:
			output("`%You trip over in the darkness and hurt yourself, you lose half of your hitpoints.`n`n");
			$hploss=round($session['user']['hitpoints']*0.5);
			$session['user']['hitpoints']-=$hploss;
			output("You lose `^%s `%hitpoints.`n`n",$hploss);
			break;
		case 4:
			output("`%You bump into an old man in the cellar, he appears to be lost so out of the kindness of your heart you lead him to the entrance.`n");
			output("Your noble deed makes you feel great, and you gain a charm point!`n`n");
			$session['user']['charm']++;
			break;
		case 5:
			output("`%You bump into an old man in the cellar, he appears to be lost so out of the kindness of your heart you lead him to the entrance.`n");
			output("The old man giggles with delight when you reach the entrance and whacks you his ugly stick to say thankyou, you lose a charm point!`n`n");
			if ($session['user']['charm']>0){
				$session['user']['charm']--;
			}
			break;
		case 6:
			output("`%You bump into an old man in the cellar, he appears to be lost so out of the kindness of your heart you lead him to the entrance.`n");
			output("The old man giggles with delight when you reach the entrance and whacks you his pretty stick to say thankyou, you gain a charm point!`n`n");
			$session['user']['charm']++;
			break;
		case 7:
			output("`%You bump into an old man in the cellar, he appears to be lost so out of the kindness of your heart you lead him to the entrance.`n");
			output("When you reach the entrance the old man is so grateful that he hands you a gem.`n`n");
			$session['user']['gems']++;
			debuglog("found 1 gem in the cellar");
			break;
		case 8:
			output("`%You bump into an old man in the cellar, he appears to be lost so out of the kindness of your heart you lead him to the entrance.`n`n");
			break;
		case 9:
			output("`%You bump into an old man in the cellar, he appears to be lost so out of the kindness of your heart you lead him to the entrance.`n");
			output("The old man seems very pleased when you reach the entrance, but soon after he has left you realise he picked your pockets!  ");
			if ($session['user']['gems']==0){
				$sgold=$session['user']['gold'];
				$gold=round($session['user']['level']*50*r_rand(0.9,1.1));
				$session['user']['gold']-=$gold;
				if ($session['user']['gold']<0){
					$session['user']['gold']=0;
					output("You lose all your gold!`n`n");
					debuglog("lost " . $sgold . " gold in the cellar");
				}
				else{
					output("You lose `^%s `%gold!`n`n",$gold);
					debuglog("lost " . $gold . " gold in the cellar");
				}
			}
			else{
				$session['user']['gems']--;
				debuglog("lost 1 gem in the cellar");
				output("You lose a gem!`n`n");
			}
			break;
	}
}

?>