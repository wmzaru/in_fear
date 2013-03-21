<?php

/* ver 1.0 by Matt Mullen matt@mattmullen.ent */
/* Thanks to Shannon Brown's code to guide in LoGD API */
/* 28 August 2005 */

require_once("lib/http.php");
require_once("lib/villagenav.php");

function talentwagon_getmoduleinfo(){
	$info = array(
		"name"=>"TalentWagon",
		"version"=>"1.0",
		"author"=>"`7ma`&tt`3@`7matt`&mullen`3.`7net",
		"category"=>"Village",
		"download"=>"www.mattmullen.net",
		"settings"=>array(
			"justice"=>"Amount of justice,range,1,10,1|5",
			"talentwagonloc"=>"Where does the talent wagon appear,location|".getsetting("villagename", LOCATION_FIELDS)
		),
		"prefs"=>array(
			"workedtoday"=>"Has the user worked today,bool|0"
		)
	);
	return $info;
}

function talentwagon_install(){
	module_addhook("changesetting");
	module_addhook("village");
	module_addhook("newday");
	return true;
}

function talentwagon_uninstall(){
	return true;
}

function talentwagon_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "changesetting":
		if ($args['setting'] == "villagename") {
			if ($args['old'] == get_module_setting("talentwagonloc")) {
				set_module_setting("talentwagonloc", $args['new']);
			}
		}
		break;

	case "village":
		if ($session['user']['location'] == get_module_setting("talentwagonloc")) {
			tlschema($args['schemas']['marketnav']);
			addnav($args['marketnav']);
			tlschema();
			addnav("Talent Wagon","runmodule.php?module=talentwagon");
		}
		break;
	case "newday":
		set_module_pref("workedtoday",0);
		break;

	}

	return $args;
}

function talentwagon_run(){
	global $session;

	$op = httpget("op");
	$type = httpget("type");

	page_header("The Talent Wagon");
	output("`&`c`bThe Talent Wagon`b`c`n");

	$navarray = array("M?Minstrel", "A?Actor", "D?Dancer", "P?Piper");
	$jobdesc = array("Minstrel needed to entertain young `2trolls `7while their parents kill breakfast.  Lute provided, but not tuned.  The trolls aren't particular about fine music.", 
				"Actor needed to play `\$Garnet `2in production of `#I Want To Be Your Canary`2.  Must be somewhat attractive to play opposite Marcus.",
				"Dancer needed to entertain at the `&Boar's Head Inn`2.  Only charming dancers need apply.",
				"Rats have overcome Degolburg!  Surely an increibly skilled piper could relieve the towne of this problem!"
				);

	$jobfail = array("You take the borrowed lute, strum a few bars, and sing.  The young trolls growl, take your lute and beat you with it, and make off with any of your gold they can get!",
					"You stumble through your lines, but when it comes time to kiss Marcus, he turns away in disgust.  The audience pelts you with vegetables, and you think you leave some belongings, including a pouch of gold, as you flee the theatre.",
					"You trip and fall off the stage, falling unconscious!  When you awaken, your gold pouch feels lighter.",
					"The rats crawl out of the woodwork when you start to play your flute, then swarm and attack you!  You run, screaming, dropping gold as you go!"
				);

	$jobsucceed = array("You take the borrowed lute, strum a few bars, and sing.  The trolls love you!",
					"You have a tough time remembering your lines, but manage ok, and the audience gasps when you take a sword in your lover's place.",
					"You practice ahead of time with the other dancers, and you're a bit out of synchronisation, but the patrons seem to like you anyway.",
					"You play your pipe with great skill, leading every rat slowly from the city.  You are a hero!"
				);


	if ($op==""){

		$job1 = $session['user']['age'] % 4;
		$job2 = $job1 + 1;
		if ($job2 > 3) $job2 = 0;

		output("`7You stroll over to the wagon, where a man stands nearby studying a parchment.");

		if ( $session['user']['charm'] < 4 ) {

			output("He looks up, considering you, and speaks:`n`n");
			output("\"`&Sorry, I can only employ charming & talented people here.`7\"`n`n" );

		} elseif ($session['user']['charm'] > 40) {

			output("He looks up, considering you, and speaks:`n`n");
			output("\"`&Woah!  I certainly can't afford to hire someone as `^charming`& as you!`7\"`n`n" );

		} else {

			$wrkd = get_module_pref("workedtoday");

			if ($wrkd == 0 || $session['user']['turns'] > 1) {
				output("He looks up, considering you, and speaks:`n`n");
				output("\"`&So, you think you have talent, do ye, %s?`7\"`n`n",($session['user']['sex']?"Lass":"Lad"));
				output("\"`&Well, I do have a couple of jobs to be done.  Take your pick.\"`7`n`n`n`n");

				if ($wrkd) {
					output("`7Tired from working previously, you realize the time you spend working again may rob you of time for `@2 forest fights`7 today.`n`n`n`n");
				}

				addnav($navarray[$job1],"runmodule.php?module=talentwagon&op=job&type=$job1");
				addnav($navarray[$job2],"runmodule.php?module=talentwagon&op=job&type=$job2");
		
				output("`@· `2%s`7`n`n", $jobdesc[$job1]);
				output("`@· `2%s`7`n", $jobdesc[$job2]);
			} else {
				output("He looks up, considering you, and speaks:`n`n");
				output("\"`&You look too tired today to help me anymore, %s.  Go get some sleep.`7\"`n`n",($session['user']['sex']?"Lass":"Lad"));
			}
		}



	} elseif ($op=="job") {

		if (get_module_pref("workedtoday") == 1 ) {
			$session['user']['turns'] -= 2;
			debuglog("burned 2 forest fights on talent wagon " . $navarray[$type] . " job.");
		}

		set_module_pref("workedtoday", 1);

		if ($session['user']['charm'] >= 5*(1+$type) ) {

			$pay = 20 * ((1+$type) * 2 + $session['user']['level']);
			output("`7%s`7`n", $jobsucceed[$type]);
			output("`7You earn `^%s gold `7for your trouble!", $pay);
			debuglog("earned " . $pay . " gold for talent wagon " . $navarray[$type] . " job.");
			$session['user']['gold'] += $pay;

			if (e_rand(0,2) && $session['user']['charm'] <= 5*(2+$type) ) {
				output ("`7You gain `^1 charm`7 point!`7`n");
				$session['user']['charm']++;
				debuglog("earned 1 charm for talent wagon" . $navarray[$type] . "job.");
			}

		} else {
			output("`7%s`7`n`n", $jobfail[$type]);

			$goldloss = min(50 * $session['user']['level'], round($session['user']['gold'] * 0.5) );
			$hploss   = 1 + round($session['user']['hitpoints'] * 0.5);

			output("`&You `4lose`& %s `^gold`& pieces and %s `2hitpoints`&!`7`n", $goldloss, $hploss);

			debuglog("lost " . $goldloss . " gold and " . $hploss . "HP at talent wagon");

			$session['user']['gold']       -= $goldloss;
			$session['user']['hitpoints'] -= $hploss;
		}
	}

	villagenav();
	page_footer();

}

?>
