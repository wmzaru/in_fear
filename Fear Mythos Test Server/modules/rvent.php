<?php
# Date:     17MAR2007
# Author:   Robert of maddrio.com
# Updated   24MAR2007 v1.1 adds new event

function rvent_getmoduleinfo(){
	$info = array(
	"name"=>"Random Events",
	"version"=>"1.1",
	"author"=>"`2Robert",
	"category"=>"Forest Specials",
	"download"=>"http://dragonprime.net/index.php?topic=2215.0",
	);
	return $info;
}

function rvent_install(){
	if (!is_module_active('rvent')){
		output("`^ Installing Random Events - forest event `n`0");
	}else{
		output("`^ Up Dating Random Events - forest event `n`0");
	}
	module_addeventhook("forest","return 50;");
	return true;
}

function rvent_uninstall(){
	output("`^ Un-Installing Random Events - forest event `n`0");
	return true;
}

function rvent_dohook($hookname,$args){
	return $args;
}

function rvent_runevent($type){
	global $session;
	$weapon = $session['user']['weapon'];
	$chance = e_rand(1,3);
	if (is_module_active('alignment')) {
		$evil = get_module_setting('evilalign','alignment');
		$good = get_module_setting('goodalign','alignment');
		$alignment = get_module_pref('alignment','alignment');
	}
	switch(e_rand(1,10)){
		case 1: case 7:
		output("`n`2 You unknowingly step into a rabbit hole, ");
		if ($alignment > $good){
			output("`n`n you carefully check to make sure the resident bunny was not harmed. ");
		}elseif ($alignment < $evil){
			output("`n`n you remove your foot, grab your %s and thrust it in and out till `\$blood `2appears on it! ", $weapon);
		}else{
			output("`n`n your foot is stuck in there for some time. `n`n You lose 1 turn ");
			$session['user']['turns']-=1;
		}
		break;
		case 2: case 7:
		output("`n`2 A peasant staggers towards you, with a Hunters Arrow lodged in his back. ");
		output("`n`n `6Please help me! `2he struggles to say as he falls to your feet. ");
		if ($alignment > $good){
			output("`n`n you remove the arrow from his back and tend to his wounds. ");
			output("`n`n When he regains consciousness, he offers you his pouch as a reward for your kindness. ");
			output("`n`n You open it to find %s gold ",$chance);
			debuglog(" got $chance gold from a random event `0");
			$session['user']['gold']+=$chance;
		}elseif ($alignment < $evil){
			output("`n`n the poor sap is screaming in agonizing pain. `n`n One swift blow of your %s puts an end to his whining! ", $weapon);
			output("`n`n Sending his soul to the underworld in such a manner will earn you some favor there. ");
			$session['user']['deathpower']+=$chance;
		}else{
			output("`n`n you quickly attempt to tend to his wounds but he dies before you are able to. ");
		}		
		break;
		case 3: case 8:
		output("`n`2 Finding some fresh tracks you follow them and find a covered wagon. ");
		if ($alignment > $good){
			output("`n`n A thorough search of the area you find no trace of the owner, you suspect bandits were somehow involved here! ");
		}elseif ($alignment < $evil){
			output("`n`n Searching the wagon you find nothing of value and the axle is broken ...so you cant even steal the wagon, `\$ BLAST! ");
		}else{
			output("`n`n Searching the abandoned wagon you find %s gold ",$chance);
			debuglog(" got $chance gold from a random event `0");
			$session['user']['gold']+=$chance;
		}
		break;
		case 4: case 9:
		output("`n`2 Screams from a maiden sends you to the direction it came. ");
		output("`n`n You discover some common bandits are robbing some travelers. ");
		if ($alignment > $good){
			output("`n`n You gallantly charge forth with your %s and frighten away the bandits! ",$weapon);
			output("`n`n The travelers are grateful and reward you with  %s gold. ",$chance);
			debuglog(" got $chance gold from a random event `0");
			$session['user']['gold']+=$chance;
		}elseif ($alignment < $evil){
			output("`n`n To your hearts delight, those bandits are your closest friends! ");
			output("`n`n You join in the robbery and manage to pillage %s gold from the travelers. ",$chance);
			debuglog(" got $chance gold from a random event `0");
			$session['user']['gold']+=$chance;
		}else{
			output("`n`n There are too many bandits for you to handle in combat, it frustrates you to not be able to do anything. ");
		}		
		break;
		case 5: case 10:
		output("`n`2 You notice in the distance, the Sheriff and a large posse. ");
		if ($alignment > $good){
			output("`n`n You step into a clearing and they give you cheerful waves and smiles. ");
		}elseif ($alignment < $evil){
			output("`n`n You lunge behind some large bushes to hide in the darkness, suspecting they may be after you. ");
		}else{
			output("`n`n They approach to ask if you have seen any bandits in the area, which you have not. ");
		}
		break;
	}
}

function rvent_run(){
}
?>