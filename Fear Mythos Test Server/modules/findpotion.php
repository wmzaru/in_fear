<?php
// translator ready
// mail ready
// addnews ready
function findpotion_getmoduleinfo(){
	$info = array(
		"name"=>"Find Potion",
		"version"=>"1.1",
		"author"=>"Peter Corcoran",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/users/R4000/findpotion.txt",
	);
	return $info;
}

function findpotion_install(){
	module_addeventhook("travel", "return 100;");
	module_addeventhook("forest", "return 20;");
	return true;
}

function findpotion_uninstall(){
	return true;
}

function findpotion_dohook($hookname,$args){
	return $args;
}

function findpotion_runevent($type,$link) {
	global $session;
	$from = $link;
	$op = httpget('op');
	$session['user']['specialinc'] = "module:findpotion";
	if ($op==""){
		output("`2You trip over a small bottle in the road.`n`n");
		output("You know that the forest is full of surprises, some of them nasty.");
		output("Will you pick it up?`0");
		addnav("Pick it up",$from."op=pickup");
		addnav("Leave it Alone",$from."op=no");
	} elseif ($op=="no") {
		output("`2You don't think it's worth your time to pick up the small bottle, and you travel on your way.`0");
		$session['user']['specialinc'] = "";
	}  else {
		output("`2You lean down and pick up the bottle.`n");
		output("`^After picking it up you take a sniff of the potion inside.`n");
		output("`^You suddenly feel the urge to gulp it all down`n`n`0");
				
		$dk = $session['user']['dragonkills'];
		if ($dk == 0) $dk = 1;
		$dkchance = max(5,(ceil($dk / 5)));
		if (e_rand(0,$dkchance) <= 1) {
			output("`^The potion had an perfect effect. You feel charming!`0");
			$session['user']['charm']++;
			$session['user']['charm']++;			
			$session['user']['specialinc'] = "";
		} else {
			output("`4A strange feeling comes over you.`n`n");
			output("Before you have a chance to reach the healers hut, the potion burns your neck.");
			output("The pain is intense, but in a moment is gone, and as you look down, you see that the bottle is gone too.`n`n");
			output("`4You feel like you have just been hit by a bus.`n`n");			
			output("`#You `\$lose `#some hitpoints!`n`n`0");
			$amt = $session['user']['hitpoints'];
			$amt = round($amt*0.50, 0);
			$session['user']['hitpoints']-=$amt;
			if ($session['user']['hitpoints'] < 0)
				$session['user']['hitpoints'] = 1;
			$session['user']['specialinc'] = "";
		}
	}
}

function findpotion_run(){
}
?>