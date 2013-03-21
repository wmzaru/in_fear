<?php
require_once("lib/forest.php");
function bubble_getmoduleinfo(){
	$info = array(
	"name"=>"Bubble",
	"author"=>"`b`&Ka`6laza`&ar`b",
	"ver"=>"1.0",
	"category"=>"Forest Specials",
	"description"=>"Bubbles of Evil from WoT",
	"download"=>"http://dragonprime.net/index.php?module=Downloads;catd=16",
	);
	return $info;
}
function bubble_install(){
	module_addeventhook("forest","return 50;");
	return true;
}
function bubble_uninstall(){
	return true;
}
function bubble_runevent($type){
	global $session;
		$from="forest.php?";
	$session['user']['specialinc']="module:bubble";
	$op==httpget('op');
	if ($op=="" || $op=="search"){
		output("You are grasped by a sudden feeling of foreboding.  A stillness fills the air, something is very wrong here.");
		switch (e_rand(1,13)){
			case 1:
			case 11:
			case 12:
			case 13:
			output("`n`nYou are suddenly accosted, strangely enough, by a playing card, the Queen of Hearts, grinning evilly, she slowly approaches your position, you look around for anything to fend her off, however, nothing comes to hand.  Just as suddenly the wind changes, and the card collapses to the ground once more a simple playing card.  You count your lucky stars and continue on.");
			break;
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			output("`n`nYou are suddenly accosted, strangely enough, by a playing card, the Queen of Hearts, grinning evilly, she slowly approaches your position, you look around for anything to fend her off, however, nothing comes to hand.  Watching the slow advance, you decide to run for it, only to turn and find your way barred by the Jack of Diamonds.  A cold breeze blows up the back of your neck, and you suddenly realise, the Queen has grasped you from behind.  You can feel yourself forgetting something.");
			$el=$session['user']['experience']*0.1;
			$session['user']['experience']-=$el;
			output("`n`nYou've lost $el experience.  Perhaps you should avoid this area in the future");
			break;
			case 8:
			output("The stillness surrounding you suddenly erupts as a myriad of ghosts suddenly lift you into the air.  You are flung bodily to the ground repeatedly hitting your head.  You blissfully loose consciousness, when you awaken, you realise that most of the day has flown by but also so have the ghosts");
			if ($session['user']['turns']>=6){
				$session['user']['turns']=5;
			}else{
				output("Luckily there wasn't much of the day left, and you loose nothing");
			}
			break;
			case 9:
			case 10:
			output("Invisibly fingers seem to clutch at your skin and clothing.  You try to scream, only to realise something is covering your mouth, how can you fight this unseen foe?`n`nJust as suddenly these unseen foes disappear, you breath an audible sigh of relief, just as you realise your gold and gem pouches feel considerably lighter");
			$session['user']['gold']=0;
			$g = $session['user']['gems']*0.1;
			$session['user']['gems']-=$g;
			break;
		}
		addnews("%s came into contact with a evil bubble!?!",$session['user']['name']);
		forest(true);
	}
	$session['user']['specialinc']="";
}
?>