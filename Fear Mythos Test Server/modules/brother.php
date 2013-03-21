<?php

function brother_getmoduleinfo(){
	$info = array(
		"name"=>"Brothers in Arms",
		"version"=>"1.1",
		"author"=>"Chris Vorndran",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=85",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"A small Forest Event in which a player will battle against an Old Warrior in a game of Chess.",
		"settings"=>array(
			"mingold"=>"Minimum gold to lose/gain to/from old warrior,range,0,50,1|10",
			"maxgold"=>"Maximum gold to lose/gain to/from old warrior,range,20,150,1|50"
			),
		);
	return $info;
}
function brother_install(){
	module_addeventhook("forest", "return 100;");
	return true;
}
function brother_uninstall(){
	return true;
}
function brother_dohook($hookname,$args){
	return $args;
}
function brother_runevent($type){
	global $session;
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:brother";
	
	$op = httpget('op');
	if ($op=="" || $op=="search"){
		$session['user']['specialinc'] = "module:brother";
		output("`\$You come across a wandering warrior.");
		output(" In his left hand, a small briefcase.");
		output(" You wonder what is inside of this briefcase.");
		output(" You bow to him.");
		output(" He acknowledges you and makes haste to your position.`n`n");
		output("\"`4How would you like to play a game of chess?`\$\" he says.");
		addnav("Play the Game", $from . "op=play");
		addnav("Kindly Decline", $from . "op=dont");
	}elseif ($op=="play"){
		$session['user']['specialinc'] = "";
		output("`\$You accept the old warrior's offer.");
		output(" You begin to play a heated game of chess.");
		output(" He moves forward with a pawn, you move forward with a knight.");
		output(" He ends up taken that knight, but you have his queen...");
		switch(e_rand(1,8)){
			case 1:
				output("`n`n`3You defeat the barby ole codger.");
				output("He hands you a small potion and `^makes you feel healthy!");
				$session['user']['hitpoints']=$session['user']['maxhitpoints'];
				break;
			case 2:
				output("`n`n`3You defy all odds and make an extremely close victory!");
				output(" The Warrior is happy at your skills and takes up arms for you!");
				if (isset($session['bufflist']['wander'])) {
					$session['bufflist']['wander']['rounds'] += 10;
				} else {
					apply_buff('wander',
						array(
							"name"=>"`3Wandering Warrior",
							"rounds"=>20,
							"wearoff"=>"The Wandering Warrior is dismissed.",
							"atkmod"=>1.05,
							"roundmsg"=>"The ferocity of the Wandering Warrior helps you!",
											)
								);
							}
							break;
			case 3:
				$min = get_module_setting("mingold");
				$max = get_module_setting("maxgold");
				$gold = e_rand($min, $max);
				output("`n`n`3You lose to the old man!");
				if ($session['user']['gold'] >= ($session['user']['gold']-$gold)){
					output(" `3He punches you out and steals a bit of gold from you!");
					output(" You notice `^%s `3gold is missing.",$gold);
					$session['user']['gold']-=$gold;
					debuglog("loss $gold gold to old warrior");
				}elseif ($session['user']['gems'] > 0){
					output("`3The old man punches you in the face.");
					output("He scavenges your body, and takes `51 Gem`3.");
					$session['user']['gem']--;
					debuglog("lost 1 gem to old warrior");
				}else{
					$c = e_rand(1,5);
					output("`3The old man punches you quickly, and leaves you disfigured.");
					output("You lost `%%s Charm`3.",$c);
					$session['user']['charm']-=$c;
				}
				break;
			case 4:
			output("`n`n`3You defy all odds and make a really narrow victory!");
			output(" The Warrior is happy at your skills and takes up arms for you!");
				if (isset($session['bufflist']['wander'])) {
					$session['bufflist']['wander']['rounds'] += 10;
				} else {
					apply_buff('wander',
						array(
							"name"=>"`3Wandering Warrior",
							"rounds"=>20,
							"wearoff"=>"The Wandering Warrior is dismissed.",
							"atkmod"=>1.15,
							"roundmsg"=>"The ferocity of the Wandering Warrior helps you!",
											)
								);
							}
							break;
			case 5:
				$loss = $session['user']['level'];
				output("`n`n`3The whole game results in a stalemate!");
				output(" You decide to sneak off, not wishing to lose your mind like he has.");
				output(" He runs after you with a pike and jabs it into your back. `^You lose `\$%s `3hitpoints!",$loss);
				$session['user']['hitpoints']-=$loss;
				break;
			case 6:
				output("`n`n`3You defy all odds and make a stunning comeback!");
				output(" The Warrior is happy at your skills and takes up arms for you!");
				if (isset($session['bufflist']['wander'])) {
					$session['bufflist']['wander']['rounds'] += 10;
				} else {
					apply_buff('wander',
						array(
							"name"=>"`3Wandering Warrior",
							"rounds"=>20,
							"wearoff"=>"The Wandering Warrior is dismissed.",
							"atkmod"=>1.25,
							"roundmsg"=>"The ferocity of the Wandering Warrior helps you!",
											)
								);
							}
							break;
			case 7:
				output("`n`n`3Strangely, you win in under 7 moves. His jaw drops to the table.");
				output(" He sticks out his hand to be shook.");
				output(" You gladly accept his hand, but he lifts you into the air.");
				output(" He whips you around and slams you into a tree.");
				$session['user']['alive']=false;
				$session['user']['hitpoints']=0;
				addnav("Return to the Shades","shades.php");
				addnews("%s has been defeated in the forest by the Wandering Warrior. %s met a grizzly end, as the tree completely shattered their skull.",$session['user']['name'],translate_inline($session['user']['sex'] ? "She" : "He"));
				break;
			case 8:
				$min = get_module_setting("mingold");
				$max = get_module_setting("maxgold");
				$gold = e_rand($min, $max);
				output("`n`n`3Before you can even touch your next piece, a gang of Cops come from the bushes.");
				output(" They rush the old warrior and strip him of all his gold.");
				output(" They hand you a small bit of it.");
				output(" It equates to `^%s `3gold.",$gold);
				$session['user']['gold']+=$gold;
				break;
			}
	}elseif($op=="dont"){
		output("You kindly decline the offer to play Chess.");
		output(" He seems somewhat accosted and throws a rock at your head.");
		output(" `^You lose one experience.");
		$session['user']['experience']-=1;
	}
}
function brother_run(){
}
?>