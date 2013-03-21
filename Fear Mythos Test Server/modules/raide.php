<?php

function raide_getmoduleinfo(){
	$info = array(
		"name"=>"Raimus' Aide",
		"author"=>"Spider",
		"version"=>"1.1",
		"category"=>"Alley",
		"download"=>"http://dragonprime.net/users/Spider/darkalley.zip"
	);
	return $info;
}

function raide_install(){
	if (!is_module_installed("darkalley")) {
    output("This module requires the Dark Alley module to be installed.");
    return false;
	}
	else {
		module_addhook("darkalley");
		return true;
	}
}

function raide_uninstall(){
	return true;
}

function raide_dohook($hookname, $args){
	global $session;
	switch($hookname){
	case "darkalley":
		addnav("Shady Houses");
		addnav("Raimus' Aide's House", "runmodule.php?module=raide");
		break;
	}
	return $args;
}

function raide_run(){
	global $session;
	require_once("lib/http.php");
	$quantities=array(1=>5,10,20);
	$prices=array(1=>1000,1950,3700);
	$op = httpget('op');
	$level = httpget('level');
	
	page_header("Raimus' Aide's House");
	output("`c`bRaimus' Aide's House`b`c");
	addnav("5?Buy 5 Favour (1000 gold)","runmodule.php?module=raide&op=buy&level=1");
	addnav("1?Buy 10 Favour (1950 gold)","runmodule.php?module=raide&op=buy&level=2");
	addnav("2?Buy 20 Favour (3700 gold)","runmodule.php?module=raide&op=buy&level=3");
	addnav("Return to the Alley","runmodule.php?module=darkalley");
	if ($op==""){
		output("You walk towards the house of Raimus' Aide, it has no door so you step inside and call out.  The Aide scuttles towards the front door rapidly when he hears your voice.`n`n");
		output("`3\"oh, oh, %s!  Raimus will be most pleased you're here, he always welcomes donations to his worthy cause.  Please, follow me.\"`0 The Aide blurts out quickly upon seeing you.`n`n",$session['user']['name']);
		output("Within moments he whisks you into the back room and sits you on a hard wooden chair.");
		output("Looking around you see varoius skeletal remains of all kinds of creatures, you hope all of them came from the forest, but some of them look a little closer to home...`n`n");
		output("The Aide heads toward a large wooden bookshelf, and withdraws a substantial volume, he opens it up and scans down a couple of pages, then looks up again.");
		if ($session['user']['deathpower']<30){
			output("`n`n`3\"According to my records Raimus is not pleased with your performance, it seems you need more than most to donate to our cause, lest he become, angry shall we say.\" ");
			output("`0The Aide lets out a little sneer, you don't like the sound of Raimus being angry.");
		}
		else if ($session['user']['deathpower']<100){
			output("`n`n`3\"Ahhhh, I see you have put a little effort towards our cause already, but there is certainly room for improvement, do you really expect Raimus to help you if you do not help him?\"");
			output("`0The Aide says.  Seeing the logic in this, you reach into your pockets.");
		}
		else if ($session['user']['deathpower']<200){
			output("`n`n`3\"Well I must say, I am most impressed with your records, not many are as worthy as you in the eyes of Raimus, of course, there is always room for improvement.\"");
			output("`0Say the Aide, you ponder his words and decide whether Raimus needs more of your money today.");
		}
		else{
			output(" Upon reading the book the Aide's eyes light up.`n`n`3\"A worthy apprentice to Raimus you most certainly are, he is most impressed by your work and willing to do much to help you.");
			output("Of course, you can always donate more if you feel the need.\"");
			output("`0The Aide looks somewhat in awe of you, and clearly Raimus is pleased.");
			output("Your money isn't needed here, although if you feel generous Raimus certainly won't complain.");
		}
	}
	elseif($op=="buy"){
		if ($session['user']['gold']>=$prices[$level]){
			output("The Aide reaches out and snatches your `^%s gold`0 quickly and slinks off through a door to the rear of the room.  ",$prices[$level]);
			output("Moments later he reappears minus your gold.`n`n");
			output("`3\"Raimus has been informed of your donation, and thanks you kindly\"`0 Explains the Aide.");
			$session['user']['gold']-=$prices[$level];
			$session['user']['deathpower']+=$quantities[$level];
		}
		else{
			output("`3\"How dare you!  Raimus would not be pleased if he heard of this trickery, you should be thankful that I fear his anger, else I would surely tell him of this abomination!\" `0exclaims the Aide.`n`n");
			if ($level>1){
				output("At this point you realise you have asked for what you cannot afford, looking rather sheepish you try to recompose yourself and check if you can afford any donations to Raimus at all.`n`n");
			}
			else{
				output("At this point you realise how empty your pockets are, you can't even afford the smallest of donations to Raimus.");
				output("It's probably best if you leave his Aide's house now, before you get yourself into more trouble.`n`n");
			}
		}
	}
	page_footer();
}

?>