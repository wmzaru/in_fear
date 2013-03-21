<?php
#  Village Idiot Pak - 26Jun2006
#  Author: Robert of Maddrio dot com
#  combines village idiot 1 thru 6 into one file
#  converted from an 097 forest event 17April2005

function villageidiot_getmoduleinfo(){
	$info = array(
		"name"=>"Village Idiot Pak",
		"version"=>"2.1",
		"author"=>"`2Robert",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?topic=2215.0",
		"settings"=>array(
			"Village Idiot Pak - Settings,title",
			"turnlose"=>"How many turns to lose?,range,1,10,1|1",
			"hplose"=>"How many HP to lose?,range,2,10,1|2",
			"chlose"=>"How many charm to lose?,range,1,10,1|2",
		),
			"prefs"=>array(
			"Village Idiot Pak - User Prefs,title",
			"event"=>"Which event is coming next (6 possible)?,int|1",
		)
	);
	return $info;
}

function villageidiot_install(){
	if (!is_module_active('villageidiot')){
		output("`^ Installing Village Idiot Pak - forest event `n`0");
	}else{
		output("`^ Up Dating Village Idiot Pak - forest event `n`0");
	}
	module_addeventhook("forest","return 100;");
	return true;
}

function villageidiot_uninstall(){
	output("`^ Un-Installing Village Idiot Pak - forest event `n`0");
	return true;
}

function villageidiot_runevent($type){
	global $session;
	$turns=$session['user']['turns'];
	$charm=$session['user']['charm'];
	$turnlose=get_module_setting("turnlose");
	$hplose=get_module_setting("hplose");
	$chlose=get_module_setting("chlose");
	$event=get_module_pref("event");
	$rand = e_rand(1,100);

	output("`n`n`2 You come upon that ninny you know all too well, the `& Village Idiot`2. ");
	if ($event == 1){
		$chance = 75;
		output("`n`n You notice he is holding and playing with a `i poisonous snake`i! ");
		output("`n`n He comes towards you asking if you would like to pet his new found friend. ");
		if ($rand <= $chance) {
			output("`n`n The snake strikes out and bites `byou`b! ");
			if ($turns >= $turnlose) {
				output("`n`n`2 You lose time for `^%s `2forest fight as you recover! ",$turnlose);
				$session['user']['turns']-=$turnlose;
			}else{
				output("`n`n OUCH! That must hurt!");
				$session['user']['hitpoints']-=$hplose;
			}
		}else{
			output(" You watch as the snake bites the Idiot and ...you snicker quietly to yourself! ");
		}
		set_module_pref("event",2);
	}elseif ($event == 2){
		$chance = 65;
		output("`n`n You notice he is making mud balls and tossing them randomly. ");
		if ($rand <= $chance) {
			output("`n`n`2 He tosses one your way and it strikes your %s. ",$session['user']['armor']);
			output("`n`n You take notice that is NOT mud balls, it is dragon poo! ");
			if ($charm >= $chlose) {
				output("`n`n`2 As you wipe off the poo from your %s`2, you feel less charming.", $session['user']['armor']);
				$session['user']['charm']-=chlose;
			}else{
				output("`n`n You mumble to your self as you wipe off the poo.");
			}
		}else{
			output("`n`n He tosses one your way and you quickly step out of the way. ");
			output("`n`n You snicker to yourself as you notice it is not mud but rather dragon poo, he is playing with! ");
		}
		set_module_pref("event",3);
	}elseif ($event == 3){
		$chance = 70;
		output("`n`n You notice he is randomly swinging a wooden sword. ");
		if ($rand <= $chance) {
			output("`n`n His sword swings wild and strikes a hornets nest which flies off the tree limb and hits your %s`2! ",$session['user']['armor']);
			output("`n`n A swarm of angry hornets attack you! ");
			if ($session['user']['hitpoints'] >= hplose ) {
				output("`n`n`2 Your %s`2 fails to protect you from the angry hornets. ", $session['user']['armor']);
				output("`n`n`6 You lost some hitpoints.");
				$session['user']['hitpoints']-=$hplose;
			}else{
				output("`n`n You try to swat away the angry hornets as they attack you.");
			}
		}else{
		output("`n`n His sword swings wild and strikes a hornets nest which falls to the ground. ");
		output("`n`n You snicker as you watch the `&Village Idiot `2run away from the swarm of angry hornets. ");
		}
		set_module_pref("event",4);
	}elseif ($event == 4){
		$chance = 75;
		output("`n`n`2 Within a large tree, you see him hanging from a branch. ");
		if ($rand <= $chance) {
			output("`n`n You notice he is about to fall ...oops, too late! ");
			if ($session['user']['hitpoints'] >=$hplose ) {
				output("`n`n The `&Village Idiot `2falls on top you! `n`n`6 You lost some hitpoints.");
				$session['user']['hitpoints']-=$hplose;
			}else{
				output("`n`n You quickly step off to the side as the `&Village Idiot `2falls to the ground.");
			}
		}else{
			output("`n`n You continue to watch ...when suddenly the branch breaks and he falls to the ground. ");
			output("`n`n You shake your head and continue on your way. ");
		}
		set_module_pref("event",5);
	}elseif ($event == 5){
		$chance = 55;
		output("`n`n You notice he is chasing after  a `i skunk`i! ");
		output("`n`n He shouts for you to help him catch the cute kitty cat. ");
		if ($rand <= $chance) {
			output("`n`n The skunk quickly turns and sprays the both of you! ");
			output("`n`n`6 You reek of the odorous fumes of a skunk! ");
			if ($charm >= $chlose ) {
				output("`6 You definately are less charming! ");
				$session['user']['charm']-=chlose;
			}else{
				output("`6 Cant be any less charming than you already are!!");
			}
		}else{
			output(" You watch as the skunk sprays the Idiot, ...you snicker quietly to yourself! ");
		}
		set_module_pref("event",6);
	}else{
		$chance = 60;
		$a="blueberry";
		$b="gooseberry";
		$c="raspberry";
		$randx=e_rand(1,4);
		if ($randx==1 or 4){ $berry=$a;}
		if ($randx==2){ $berry=$b;}
		if ($randx==3){ $berry=$c;}
		$loss=$session['user']['hitpoints']*.1;
		output("`n`n You notice he is eating un-ripened berries from a %s bush, ",$berry);
		output("`n`n while standing in the middle of poison ivy! ");
		output("`n`n You call out to the idiot in order to help him. ");
		if ($rand <= $chance) {
			output("`n`n For your efforts he tosses a handful of %s's towards you, in anger you chase after him. ",$berry);
			output("`n`n`6 You slip on the berries on the ground, landing into the poison ivy! ");
			if ($session['user']['hitpoints'] >=10 ) {
				output("`6 You lost a few hitpoints! ");
				$session['user']['hitpoints']-=$loss;
			}else{
				output("`6 Ouch! That must have hurt!");
			}
		}else{
			output("`n`n You watch as he slips on the berries on the ground, landing into the poison ivy! ");
			output("`n`n`6 You quietly snicker to yourself and continue on your way. ");
		}
		set_module_pref("event",1);
	}
}

function villageidiot_run(){
}
?>