<?php
function prankstore_water(){
	global $session;
	output("The children in the village watch and laugh and point at you and you grumble to yourself.");
	output("`n`nYou lose `&one charm`0 due to the embarrassment.");
	$session['user']['charm']--;
}
function prankstore_shock(){
	global $session;
	output("The charge makes you bite down on your tongue!");
	$hps=round($session['user']['hitpoints']*.1);
	if ($hps<=0){
		output("Although you don't lose any hitpoints, your tongue looks swollen.");
		output("`n`nYou lose `&one charm`0.");
		$session['user']['charm']--;
	}else{
		output("You lose `\$%s hitpoint%s`0.",$hps,translate_inline($hps>1?"s":""));
		$session['user']['hitpoints']-=$hps;
	}
}
function prankstore_bellows(){
	global $session;
	$gold=$session['user']['gold'];
	if ($gold>=100){
		output("that hits your gold bag and you lose`^");
		if ($gold>=250){
			output("250 gold`0 that");
			$session['user']['gold']-=250;
		}else{
			output("all your gold`0 as it");
			$session['user']['gold']=0;
		}
		output("scatters into the village square.  The local children gleefully collect all your `^gold`0 and run off.");
	}else{
		output("hits you square in the forehead, jarring you back.");
		if ($gold>0){
			output("You lose `^all your gold`0 and");
			$session['user']['gold']=0;
		}else output("You lose");
		output("`&2 charm points`0 from the dent made in your head.");
		$session['user']['charm']-=2;
	}
}
function prankstore_doggiedoor(){
	global $session;
	$gems=$session['user']['gems'];
	if ($gems>=1){
		output("As you're crawling through, you hear a ripping sound.  You turn to notice your gem back has a hole in it.  You've lost a gem.");
		$session['user']['gems']--;
	}else{
		output("As you're crawling though, you hear a ripping sound.  You've torn your pants! Well, that's not very charming, and neither are you anymore.");
		output("`n`nYou've lost 2 charm.");
		$session['user']['charm']-=2;
	}
}
?>