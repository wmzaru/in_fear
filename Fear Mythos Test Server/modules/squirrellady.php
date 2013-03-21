<?php
// version info in readme.txt
function squirrellady_getmoduleinfo(){
	$info = array(
		"name"=>"The Squirrel Lady",
		"version"=>"1.4",
		"author"=>"`@CortalUX",
		"category"=>"Forest Specials",
		"vertxtloc"=>"http://dragonprime.net/users/CortalUX/",
		"download"=>"http://dragonprime.net/users/CortalUX/squirrellady.zip",
		"prefs"=>array(
			"Squirrel Lady - Preferences,title",
			"squirrelLady"=>"Seen the Lady this newday?,bool|0",
			"squirrels"=>"Seen the Squirrels?,bool|0",
		),
		"settings"=>array(
			"Squirrel Lady - Settings,title",
			"damn"=>"What is the exlamation? (IE: Damn),text|Damn",
			"(this will be uppercased by the script automatically),note",
		),
	);
	return $info;
}

function squirrellady_chance($x) {
	global $session;
	if (get_module_pref('squirrels','squirrellady',$session['user']['acctid'])==1) return 0;
	if ($x=='forest') {
		if (get_module_pref('squirrelLady','squirrellady',$session['user']['acctid'])==0) return 0;
	} else {
		if (get_module_pref('squirrelLady','squirrellady',$session['user']['acctid'])==1) return 0;
	}
	return 100;
}

function squirrellady_install(){
	module_addeventhook("forest","require_once(\"modules/squirrellady.php\"); return squirrellady_chance('forest');");
	module_addeventhook("village","require_once(\"modules/squirrellady.php\"); return squirrellady_chance('village');");
	module_addhook("newday");
	return true;
}

function squirrellady_uninstall(){
	return true;
}

function squirrellady_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
		case "newday":
			set_module_pref("squirrelLady",0);
			set_module_pref("squirrels",0);
		break;
	}
	return $args;
}

function squirrellady_runevent($type) {
	global $session;
	if ($type=='village') {
		output("`7As you walk around the village, you hear a mad woman running around, screaming \"`^%s THOSE `bSQUIRRELS`b`^!!!!`7\"`n`n",strtoupper(get_module_setting("damn")));
		output("After a while, the sound hurts your ears, so you walk up to the woman and decide to see what she wants.`n`n");
		output("`3\"`@How can I help you, my fair Lady? I am %s`0`3,`3\" you say.`n`n",$session['user']['name']);
		output("`7\"`^I'm not your fair anything! I am Lady Kate Grillet! Squirrels stole my bird-feeder!`7\" she explains.`n`n");
		output("`3Attempting to humour her, you say, \"`@Right. What exactly happened?`3\"`n`n");
		output("`7\"`^I'm not mad. Don't humour me. First- these darn squirrels ate my nuts, then they stole my knitting kit, and now they've stolen my SUPPOSEDLY squirrel-proof bird feeder!`7\" she yells.`n`n");
		output("`3Hoping for a reward, you walk off in an attempt to earn some cash.`n`n");
		set_module_pref("squirrelLady",1);
	} else {
		$session['user']['specialinc']="module:squirrellady";
		$op = httpget('op');
		if ($op=='') {
			set_module_pref("squirrels",1);
			output("`7Stumbling around the forest, you hear a chittering sound, and a nut hits you on the head!`n");
			output("Looking up, you see three evil squirrels... with a bird feeder!`n");
			output("Hoping for a reward from the Lady Kate, you attempt to catch them!`n");
			addnav("Hunt them down!","forest.php?op=hunt");
			addnav("Try to Bribe them!","forest.php?op=bribe");
		} elseif ($op=='hunt'||$op=="fight"||$op=="run") {
			squirrellady_battle();
		} elseif ($op=='bribe') {
			squirrellady_bribe();
		}
	}
}

function squirrellady_bribe() {
	global $session;
	output("`6Thinking the Squirrels look pretty smart, you plead with them to help you.`n`3\"`^Please! I'll buy you a whole room full of Nuts at the inn!`3\"");
	$num = e_rand(1,10);
	switch ($num) {
		case "1":
		case "2":
		case "3":
		case "4":
			switch (e_rand(1,3)) {
				case "1":
					$session['user']['gold']=0;
					$session['user']['gems']++;
					output("`@Showing the Squirrels to Lady Kate, you ask for a reward. Replying that they don't look dead, and she wants her birdfeeder, she whacks you over the head with her Umbrella and stomps off.");
					output("`nYou buy a room full of nuts for your Squirrelly friends, and they hand you a shiny acorn they found in the forest.");
					output("`nYou stamp off to the forest.");
					debuglog("gained a Gem and lost all gold when they attempted to bribe Evil Squirrels",false,false,"squirrellady",0);
				break;
				case "2":
					$session['user']['gold']=100;
					$session['user']['gems']++;
					output("`@Showing the Squirrels to Lady Kate, you ask for a reward. Taking all of your Gold for having to wait, she then hands you 100 gold back, as a reward.");
					output("`nYou buy a room full of nuts for your Squirrelly friends, and they hand you a shiny acorn they found in the forest.");
					debuglog("gained a Gem when they attempted to bribe Evil Squirrels and gained 100 gold",false,false,"squirrellady",0);
				break;
				case "3":
					$session['user']['gold']=0;
					$session['user']['gems']=0;
					output("`@Showing the Squirrels to Lady Kate, you ask for a reward. Taking all of your Gold, she ignores you.");
					output("`nYou buy a room full of nuts for your Squirrelly friends, and they shove you out of the room.");
					debuglog("lost all gold and gems when they attempted to bribe Evil Squirrels",false,false,"squirrellady",0);
				break;
			}
			$session['user']['specialinc']="";
		break;
		case "5":
		case "6":
		case "7":
			output("`@The Squirrels ignore you, and attack!");
			squirrellady_battle();
		break;
		case "8":
		case "9":
		case "10":
			output("`@Pleading with the Squirrels, they ignore you. You can't be bothered with it, and stomp off.");
			$session['user']['gems']++;
			$session['user']['hitpoints']=5;
			output("`n`@Walking off, you get a stone thrown at you from behind.`nAngrily, you fly head over heels into a river.`nHarming yourself, you step on a shiny stone, while getting out.. `%It's a Gem!");
			debuglog("gained a Gem when they attempted to bribe Evil Squirrels",false,false,"squirrellady",1);
			debuglog("lost all hitpoints except 5 when they attempted to bribe Evil Squirrels",false,false,"squirrellady",1);
		break;
	}
}

function squirrellady_run(){
}

function squirrellady_battle() {
	$op = httpget("op");
	global $session;
	if ($op=="hunt"||$op=="bribe"){
		$dkb = round($session['user']['dragonkills']*.1);
		$level = $session['user']['level']-1;
		if ($level<=0) $level=1;
		$badguy = array(
			"creaturename"=>"`@Evil Squirrels`0",
			"creaturelevel"=>$level,
			"creatureweapon"=>"`QTiny Acorns",
			"creatureattack"=>$session['user']['attack'],
			"creaturedefense"=>$session['user']['defense'],
			"creaturehealth"=>round($session['user']['maxhitpoints']*0.75,0),
			"diddamage"=>0,
			"type"=>"squirrellady");
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
		httpset('op', "fight");
	}
	if ($op=="run"){
		output("`@The Evil Squirrels jump from trees and hurl themselves at you! No chance to run!");
		$op="fight";
		httpset('op', "fight");
	}
	if ($op=="fight"){
		$battle=true;
	}
	if ($battle){
		include("battle.php");
		if ($victory){
			output("`n`6Many `@Evil Squirrels`6 lie slain. Seeing a Bird Feeder slink away with a suspiciously brushy-looking tail appearing from below it, you pick the Bird Feeder up, bonk the Squirrel lying under it against the wall, and go to collect your reward.");
			output("While walking to the village, you taste some of the Acorns... they taste really good, and you feel healthy.");
			if ($session['user']['hitpoints'] <	$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];
			$badguy=array();
			$session['user']['badguy']="";
			addnews("`%%s`@ hunted for `7Lady Kate Grillet's`@ evil Squirrels, and gained a huge reward for defeating them!",$session['user']['name']);
			$session['user']['specialinc']="";
			$session['user']['specialmisc']="";
			$gold = e_rand(1500,3000);
			output("`n`@Talking to the `^Lady Kate Grillet`@, she hands you a huge reward! She then goes off to shoot some Rabbits.`n`3\"`&Tally Ho!`3\"`n`^You gain `&%s`^ gold!",$gold);
			debuglog("gained gold when they slew evil squirrels",false,false,"squirrellady",$gold);
			$session['user']['gold']+=$gold;
		}elseif ($defeat){
			require_once("lib/taunt.php");
			$taunt = select_taunt_array();
			addnews("`%%s`@ hunted for `7Lady Kate Grillet's`@ evil Squirrels, but was slain by a `%Bird Feeder`@!`n%s",$session['user']['name'],$taunt);
			$session['user']['specialinc']="";
			$session['user']['specialmisc']="";
			addnav("Daily news","news.php");
			$session['user']['alive']=false;
			debuglog("lost gold when they were slain by Evil Squirrels",false,false,"squirrellady",-$session['user']['gold']);
			$session['user']['gold']=0;
			$session['user']['hitpoints']=0;
			$session['user']['experience']=round($session['user']['experience']*.9,0);
			$session['user']['badguy']="";
			output("`b`&You have been slain by `%%s`&!!!`n",$badguy['creaturename']);
			output("`4All gold on hand has been lost!`n");
			output("`410% of experience has been lost!`b`n");
			output("You may begin fighting again tomorrow.");
			$badguy=array();
		}else{
			fightnav(true,true);
		}
	}
}
?>