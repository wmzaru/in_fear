<?php
// translator ready
// mail ready
// addnews ready
function punjistakes_getmoduleinfo(){
	$info = array(
		"name"=>"Punji Stakes",
		"version"=>"1.2",
		"author"=>"Peter Corcoran",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/users/R4000/punjistakes.txt",
	);
	return $info;
}

function punjistakes_install(){
	module_addeventhook("travel", "return 100;");
	module_addeventhook("forest", "return 100;");
	return true;
}

function punjistakes_uninstall(){
	return true;
}

function punjistakes_dohook($hookname,$args){
	return $args;
}

function punjistakes_runevent($type,$link) {
	global $session;
	$from = $link;
	$op = httpget('op');
	$session['user']['specialinc'] = "module:punjistakes";
	$rand = rand(1,6);
	if($rand == 1){
		output("`2As you travel though the forest you suddenly feel a sharp pain in the chest`n`n");
		output("As you look down you relise you have been trapped in an old Punji stake trap");
 		output("The pain is tearing your body apart!`n");
 		output("Soon, you are far too weak to stand, and as you draw your last breath you can hear faint laughter coming from the forest!`n");
 		output("`^You have died!`n");
 		output("All gold on hand has been lost!`n");
 		output("10% of your experience has been lost!`n");
 		output("You may continue playing again tomorrow.");
 		$session['user']['alive']=false;
 		$session['user']['hitpoints']=0;
 		$session['user']['experience']*=0.9;
 		$gold = $session['user']['gold'];
 		$session['user']['gold'] = 0;
 		addnav("Daily News","news.php");
 		addnews("%s was caught in an punji stake trap.",$session['user']['name']);
 		debuglog("lost $gold from the punji stakes.");
	}elseif($rand == 6){
		output("`2As you travel though the forest you suddenly notice a ton of sharp spikes in the ground.`n");
		output("`\$You slowly walk around them watching your step.`n`n");
		output("`\$When you reach the other side a small boy runs up and says, \"Boy was you lucky!\"");
	}else{
		output("`2As you travel though the forest you suddenly feel a sharp pain in the chest`n`n");
		output("As you look down you relise you have been trapped in an old Punji stake trap");
		$amt = rand(1,$session['user']['hitpoints']);
		output("`n`%Luckly it misses your heart and other important parts of your body.`n");
		output("`\$You lose ".$amt." hitpoints.");
		$session['user']['hitpoints']=$session['user']['hitpoints']-$amt;
	}
}

function punjistakes_run(){
}
?>