<?php
/*
	Skinning and Tanning...
		,,,a cultural experience for new races on my own
		server but perhaps something that everyone else
		might want too.  Enjoy.
	
	~ History ~
	
	1.0.0
		Initial release.
	  
	1.1.0
		Fixed a bug in the gold that always resulted in
		zero gold, no matter what the case.
	  
	1.1.1
		Fixed a bug in buying the knife where it didn't
		check the user actually had the gold, this could
		result in always being able to get the knife and
		negative gold counts.
		
	2.0.0
		This version borrows heavily from Lays.php by
		Hex.  Please take a look at Hex's module to thank
		him.  It really is very, very good.
		
		http://dragonprime.net/users/Hex/
		
		Thanks primarily to that module, I learned how
		to do new things.  This version now has a hook
		in the Hall of Fame for hunters and it awards
		work with titles.  The store owner can now be
		configured too.
		
	2.0.1
		Minor fix that stops the 'Skin your Prey' nav
		from appearing in non-forest fights.  So if you
		had this turning up in PvsP fights or places
		that you shouldn't, then this new version should
		fix that.  All thanks go to Kendaer.
		
		Also, I noted that I hadn't thanked Spider who
		gave me the example from which I based the
		skin encounter off of originally.  So I'll
		add that in as credit too.
		
		If I've forgotten anyone, let me know.  I don't
		not credit out of anything other than addle-
		brainedness.  Trust me to rely on anything and
		you might aswell trust me equally to forget.
		
	2.0.2
		Fixed the messy village navs.
		
	2.1.0
		Complete overhaul of the experience system and
		values, added charstats for the tracking of
		the amount of skins the player has.  Attempts
		now also report the level of experience gained
		and the experience needed to level.
		
*/

require_once "common.php";
require_once "lib/villagenav.php";
require_once "lib/http.php";
require_once "lib/addnews.php";

function skintan_getmoduleinfo(){
	$info = array(
		"name"=>"Skinning and Tanning",
		"version"=>"2.1.0",
		"author"=>"Rowne-Wuff Mastaile",
		"category"=>"Village",
		"settings"=>array(
			"General Settings,title",
				"skintanloc"=>"Where does the tent appear?,location|".getsetting("villagename", LOCATION_FIELDS),
				"payhowmuch"=>"Pay ? for each skin (* skinning level twice):,int|35",
				"knifecost"=>"Skinning knife cost (* user level):,int|200",
				"skinchance"=>"Chance for success (* skinning level):,int|2",
				"levelpoints"=>"Points needed to level:,int|15",
			"Owner Settings,title",
				"skinowner"=>"The owner's name:,int|Darkmane",
				"sex"=>"The owner's gender:,int|male",
				"race"=>"The owner's race:,int|Wolf",
				"eyes"=>"The owner's eye-colour:,int|amber",
			"Hall of Fame,title",
				"usehof"=>"Use Hall of Fame?,bool|1",
				"toplist"=>"Display how many in the Hunters HoF?,range,0,150,10|0",
				"allowtit"=>"Allow title changes?,bool|1",
				"title"=>"The title for GrandHunter,int|GrandHunter",
				"titlevel"=>"Skin level for the title,int|30",
			),
			"prefs"=>array(
				"skinpoints"=>"Skinning experience:,int|0",
				"skinlevel"=>"Skinning level:,int|1",
				"skininv"=>"Skins carried:,int|0",
				"knifeowned"=>"Has knife?,bool|0",
				"givenup"=>"Has given up the hunt?,bool|0",
			),
	);
	return $info;
}

function skintan_install(){
	module_addhook("village");
	module_addhook("changesetting");
	module_addhook("dragonkill");
	module_addhook("battle-victory");
	module_addhook("charstats");
	module_addhook("footer-hof");
	return true;
}

function skintan_uninstall(){
	return true;
}

function skintan_dohook($hookname,$args){
	global $session;
	
	switch($hookname){

	case "changesetting":
		if ($args['setting'] == "villagename") {
			if ($args['old'] == get_module_setting("skintanloc")) {
				set_module_setting("skintanloc", $args['new']);
			}
		}
		break;

	case "village":
		if ($session['user']['location'] == get_module_setting("skintanloc")) {
			tlschema($args['schemas']['marketnav']);
			addnav($args['marketnav']);
			tlschema();
			addnav(array("%s's Tanning", get_module_setting ("skinowner")) ,"runmodule.php?module=skintan");
		}
		break;

	case "dragonkill":
		set_module_pref ("givenup",0);
		set_module_pref ("skinpoints",0);
		set_module_pref ("skinlevel", $skinlvl / 2);
		set_module_pref ("skininv",0);
		break;

	case "battle-victory":
		if ($args['type'] == "forest") {
			$hasknife	= get_module_pref		("knifeowned");
			
			if ($hasknife) addnav("Skin your Prey", "runmodule.php?module=skintan&op=skinprey");
		}
		break;

	case "charstats":
		if (get_module_pref ('skininv') > 0) {
			setcharstat("Equipment Info", "Skins", "`2" . get_module_pref ('skininv'));
		}
		
		else {
			setcharstat("Equipment Info", "Skins:", "`$" . get_module_pref ('skininv'));
		}
		break;

	case "footer-hof":
		if(get_module_setting('usehof')){
			addnav("Warrior Rankings");
			addnav("Hunters", "runmodule.php?module=skintan&op=hof");
		}
		break;
	}

	return $args;
}

function skintan_skinheader() {
	page_header ("Skinning Prey");
	output ("`^`c`bNow for the spoils ...`b");
	output ("`n`n");
}

function skintan_shopheader() {
	page_header ("Tanning Tent");
	$ownername	= get_module_setting	("skinowner");
	output ("`^`c`b%s's Tanning Tent`b", $ownername);
	output ("`n`n");
}

function skintan_run() {
	global $session;

	/* Define Variables Begin. */

	$op			= httpget				('op');
	$cost			= get_module_setting ("knifecost") * $session['user']['level'];
	$hasknife	= get_module_pref		("knifeowned");
	$skinpoints = get_module_pref		("skinpoints");
	$skininv		= get_module_pref		("skininv");
	$skinlvl		= get_module_pref		("skinlevel");
	$lvlpoints	= get_module_setting	("levelpoints") * $skinlvl;
	$skintogo	= $lvlpoints - $skinpoints;
	$critbase	= $skinlvl * $skinlvl + 1;
	$critfail	= e_rand					(1, $critbase);
	$paid			= get_module_setting ("payhowmuch") * $skinlvl * $skinlvl * $skininv;
	$givenup		= get_module_pref		("givenup");
	$tribe		= ($session['user']['sex'] ? "Brother" : "Sister");
	$newskins	= $skininv /8;
	$chancebase	= get_module_setting ("skinchance") * $skinlvl;
	$skinchance	= e_rand (1, $chancebase);
	$titlevel	= get_module_setting	("titlevel");
	$allowtit	= get_module_setting	("allowtit");
	$title		= get_module_setting	("title");
	$huntname	= $session['user']['name'];
	$ownername	= get_module_setting	("skinowner");
	$race			= get_module_setting	("race");
	$eyes			= get_module_setting	("eyes");
	$rank			= translate_inline	("Rank");
	$name			= translate_inline	("Name");
	$race			= translate_inline	("Race");
	$level		= translate_inline	("Hunter Level");
	
	if (get_module_pref ("sex") == "male") {
		$it	= he;
		$its	= his;
		$irm	= him;
	}
	
	else {
		$it	= she;
		$its	= hers;
		$irm	= her;
	}

	/* Define Variables End. */

	if ($op == "") {
		skintan_shopheader();
		if ($givenup) {
			output ("You cast your gaze to the frontal flap of %s's tent ...", $ownername);
			output ("and indeed, you think long and hard about stepping in and asking %s for another chance.", $irm);
			output ("Yet you know, deep down, that after what you did you could not face %s again.", $irm);
			output ("Perhaps ... in another life.");
			villagenav();
		}
		
		if ($hasknife && $skininv > 0) {
			output ("`&You currently have `2%s `&skins on your belt!", $skininv);
			output ("`n`n");
		}

		elseif ($hasknife && skininv == 0) {
			output ("`&You currently have `\$nothing `& to your name ...");
			output ("`n`n");
		}

		output ("`7");
		output ("You lift the leatherhide flap of %s's tent and proceed within.", $ownername);
		output ("The hide is thick and it keeps out most light, once inside there's really not much to be seen.");
		output ("yet you are still aware of the skins that hang there, you can feel them as they brush you");
		output ("and mostly, you can `ismell`i them.  There is also a rythmic slicing sound which you track to %s %sself.", $ownername, $irm);
		output ("You cannot make out much of this Wolf, other than %s eyes with glow luminescently in the darkness of the tent.", $its);
		output ("%s looks at you expectantly...", $ownername);
		output ("`n`n");

		if (!$hasknife) {
			addnav ("K?Buy a Skinning Knife","runmodule.php?module=skintan&op=buyknife");
		}

		elseif ($skininv > 0) {
			addnav ("H?Sell your Hides","runmodule.php?module=skintan&op=sellskin");
			addnav ("T?Turn in your Blade","runmodule.php?module=skintan&op=quit");
		}

		else {
			output ("You cast your gaze down at your feet, it has been an unproductive day.  You don't have any skins to sell.");
			output ("You're not sure why you came to see %s considering this but if you keep doing it,", $ownername);
			output ("regardless of their tolerance, the tribe will start to talk.  You lift the flap and turn to leave.");
			output ("%s simply grunts %s disapproval as you stand around aimlessly in her abode.", $ownername, $its, $its);
			output ("`n`n");
			addnav ("T?Turn in your Blade","runmodule.php?module=skintan&op=quit");
		}

	villagenav();
	}

	if ($op == "buyknife") {
		skintan_shopheader();
		output ("`7");
		output ("You feebly raise your hand and point at one of the knives hanging to a nearby display.");
		output ("You can only see them thanks to the way they glint in the little light provided.");
		output ("The alert eye of %s catches your motionings and %s looks up, setting down %s own tools.", $ownername, $it, $its);
		output ("In a silent motion %s stands and in soft strides, almost like a shadow %s moves to the the rack.", $it, $it);
		output ("\"`6You are a new skinner, I can tell.`7\" %s murmurs softly, her voice however carries much presence,", $it, $its);
		output ("as it rumbles like distant thunder in a low, partially growled baritone.");
		output ("You almost feel as though %s words should be lost to you, so strange is the way %s speaks.", $its, $it);
		output ("\"`6As such, there are things I must speak of and that which your prey awards.");
		output ("At first, your task will be easy but that which you obtain will not be of much worth.");
		output ("Yet as you gain skill, you will find new ways of procuring better skins from your felled prey.");
		output ("I will pay more for these superior skins but they will also be more difficult to come by.");
		output ("Needless to say, it is also a gruesome task but it will help provide warm clothes for our children.");
		output ("... Finally, the knife itself will cost %s gold coins, are you sure this is the road you wish to travel?\"", $cost);
		output ("`n`n");
		addnav ("Y?Yes, it is.","runmodule.php?module=skintan&op=yesbuy");
		addnav ("N?No ...","runmodule.php?module=skintan&op=nobuy");
	}

	if ($op == "nobuy") {
		skintan_shopheader();
		output ("`7");
		output ("Surprisingly, %s smiles.  Black lips curling back over pearl-white fangs.", $ownername);
		output ("\"`6I understand your hesitation my friend.  This road is not for everyone,", $tribe);
		output ("you may return when you are ready.`7\" and just as silently, %s returns to her work.", $it, $its);
		output ("Malika says nothing more about this and you believe for now, it is best to leave.");
		output ("`n`n");
		villagenav();
	}
		
	if ($op == "yesbuy") {
		skintan_shopheader();
		output ("`7");
		output ("%s nods once and takes one of the more impressive knives down from the rack.", $ownername);
		output ("Without a word, %s holds it up by the blade, with its handle facing you.", $it);
		output ("You take it and hold it for a moment, it's surprisingly light,");
		output ("that's all the better though as a light-weight knife is perfect for this work.");
		output ("With the knife in your hand now and not %s, %s holds out her ", $its, $ownername, $its);
		if ($session['user']['gold'] < $cost) {
			output ("now empty hand expectantly. You realize at this point that you don't actually");
			output ("have the coins to give %s so you hand %s back the knife with an expression most sheepish.", $irm, $irm);
			output ("%s gazes at you quizzically for a moment but upon noting your lacking funds %s actually", $ownername, $it);
			output ("lets out something of a laugh, though not a nasty one really.");
			output ("`n`n");
			output ("After such a bumble however, you do decide that perhaps it's best you leave,");
			output ("for the sake of your dignity.");
			villagenav();
		}
		
		else {
			output ("now empty hand expectantly. Realizing her intent, you quickly drop the gold coins into her palm.", $its, $cost, $its);
			output ("As the last coin falls, %s closes %s hand into a fist and returns to her work ...", $it, $its, $its);
			output ("and you, with knife in hand decide that you have reputation and money to gain.");
			output ("`n`n");
			$session['user']['gold'] -= $cost;
			set_module_pref("knifeowned",1);
			villagenav();
		}
	}

	if ($op == "quit") {
		skintan_shopheader();
		output ("`7");
		output ("You wander slowly through the darkness of the tent to %s's table.", $ownername);
		output ("You hold up your knife in open palm and after a moment or two ~ and a few more slices,");
		output ("%s looks up.  There is surprise first in those luminescent, %s eyes", $it, $eyes);
		output ("and then an amount of sadness.  It is never an easy sight to see a hunter");
		output ("who has lost their courage.  This expression, those eyes make you falter and for a moment,");
		output ("you must ask yourself ...");
		output ("`n`n");
		output ("Is this really what you want to do?");
		output ("`n`n");
		addnav ("Y?Yes, it is.","runmodule.php?module=skintan&op=yesquit");
		addnav ("N?No.  I can't.","runmodule.php?module=skintan&op=noquit");
	}
	
	if ($op == "noquit") {
		skintan_shopheader();
		output ("`7");
		output ("You don't know what you were thinking, you cannot give up the hunt so easily.");
		output ("Nor can you cast aside the young of the tribe who need you as a provider.");
		output ("You cast your gaze to the side for a moment sheepishly before pocketing your blade.");
		output ("You spin around and quickly depart. Though whether you knew it or not,");
		output ("your actions brought faith to an old Wolf. There are too many weak these days,");
		output ("who have not the stomach for the hunt or its boons.");
		output ("`n`n");
		villagenav();
	}
	
	if ($op == "yesquit") {
		skintan_shopheader();
		output ("`7");
		output ("You simply cannot skin anymore, you know you don't have it in you to do it.");
		output ("You know you can't ask %s for the money back for the knife", $irm);
		output ("so you simply drop it on the table and run back out of the tent quickly.", $its, $its);
		output ("Pushing the flap up you egress from the tent and leave your life as a skinner behind you.");
		villagenav();
		output ("`n`n");
		set_module_pref ("givenup",1);
		set_module_pref ("knifeowned",0);
	}		

	if ($op == "sellskin") {
		skintan_shopheader();
		output ("`7");
		output ("You unlatch the `^%s `7skins from your belt and wander up to %s's table.", $skininv, $ownername);
		output ("It takes you a moment to spot exactly where you should put them down");
		output ("but after a moment, letting your eyes adjust you spot an empty basket");
		output ("and you drop them in.  %s doesn't even look up from her work as %s reaches over", $ownername, $its, $it);
		output ("to a nearby satchel and draws out `2%s `7coins, which %s drops on the table in front of you.", $paid, $it);
		output ("`n`nYou're smart enough to know not to argue so you pluck up the coins and turn to leave her tent.", $its);
		output ("`n`n");
		villagenav();
		$session['user']['gold'] += $paid;
		set_module_pref ("skininv",0);
	}

	if ($op == "skinprey") {
		skintan_skinheader();
		if ($skinlvl /2 > 1) {
			$pointplural = points;
		}

		else {
			$pointplural = point;
		}
		
		output ("`2");
		output ("You lunge into your felled prey with your knife!");
		output ("`n`n");
		output ("`7");

		if ($skinchance < 2) {
			output ("You produce a perfectly good skin from the remains of your fallen foe.");
			output ("`n`n");
			output ("`&");
			output ("In doing so, you gain `2%s `&experience %s.", $skinlvl / 2, $pointplural);
			if ($skintogo > 0) output ("Yet you still need `$%s `& to gain a level.", $skintogo);
			set_module_pref ("skininv", $skininv + 1);
			set_module_pref ("skinpoints", $skinpoints + $skinlvl / 2);
		}
		
		else {
			output ("You try to wrangle some form of skin from the corpse but it's no good, this skin's ruined.");
			if ($critfail == 1) {
				output ("`&");
				output ("`n`n");
				output ("You botch it so badly that you get `$0 `&experience points this time.");
				if ($skintogo > 0) output ("`n`nThat aside, you still need `$%s `& to gain a level.", $skintogo);
			}
			
			else {
				output ("`&");
				output ("`n`n");				
				output ("Even so, you gain `2%s `&experience %s for trying.", $skinlvl / 4, $pointplural);
				if ($skintogo > 0) output ("Yet you still need `$%s `& to gain a level.", $skintogo);
				set_module_pref ("skinpoints", $skinpoints + $skinlvl / 4);
			}
		}
		
		if ($skinpoints >= $lvlpoints) {
			output ("`n`n");
			addnav ("Continue", "runmodule.php?module=skintan&op=gainlevel");
		}
		
		else {
			output ("`n`n");
			addnav ("Continue", "forest.php");
		}
	}

	if ($op == "gainlevel") {
		skintan_skinheader();
		output ("`^");
		output ("You've gotten really good at skinning!");
		output ("`7");
		output ("`n`n");
		output ("You can use your new experience to find better skins and gain more pay for it, if you wish.");
		output ("However, although the pay will be better, it will also be harder to find suitable skins.");
		output ("Whether you choose to use this new experience or not is up to you.");
		output ("`n`n");
		addnav ("Level up!", "runmodule.php?module=skintan&op=levelup");
		addnav ("Stagnate ...", "runmodule.php?module=skintan&op=stagnate");
	}

	if ($op == "levelup") {			
		skintan_skinheader();
		output ("`$");
		output ("You're one step closer to being the most reknowned hunter of your kind!");
		output ("`n`n");
		output ("`7");
		output ("You have gained a level, hunting's going to be harder now but the gains will be greater.");
		output ("Have faith and patience, young hunter.");
		output ("`n`n");
		output ("You look at your old skins and you can't believe you were ever that bad.");
		output ("With a little work, you could just about make %s good", $newskins);
		if ($newskins > 1) {
			output ("skins.");
		}
		
		else {
			output ("skin.");
		}
		output ("skins out of what you have there. So that's what you do!");
		output ("`n`n");
		output ("`&You are now level `2%s `&in skinning.", $skinlvl);
		output ("`&You've sacrificed your old skins to make `2%s `&good", $newskins);
		
		if ($newskins > 1) {
			output ("skins.");
		}
		
		else {
			output ("skin.");
		}
		
		output ("`n`n");
		set_module_pref ("skininv", $newskins);
		set_module_pref ("skinpoints",0);
		set_module_pref ("skinlevel", $skinlvl + 1);
		
		if ($skinlevel == $titlevel && $allowtit) {
			addnav ("Continue","runmodule.php?module=skintan&op=grand");
		}
			
		else {
			addnav ("Continue","forest.php");
		}
	}
	
	if ($op == "grand") {
		skintan_skinheader();
		output ("`^");
		output ("`b`cYou are truly a %s, revered %s.`b", $title, $huntname);
		output ("`n`n");
		output ("`7");
		output ("Through your diligence in the ways of the hunt");
		output ("and not letting nature's gifts go to waste,");
		output ("you have now become a `i%s`i. Hold your head high!", $title);
		addnews ("The news has spread like wildfire, through all of the land `@%s`0 is being revered and honoured as a `%%s`0.", $huntname, $title);
		require_once("lib/names.php");
		$newtitle	= change_player_title ($title);
		$session['user']['title'] = $title;
		$session['user']['name'] = $newtitle;
		addnav ("Continue","forest.php");
	}
	
	if ($op == "stagnate") {			
		skintan_skinheader();
		output ("`&");
		output ("You have chosen to stay as you are ...");
		output ("`n`n");
		output ("`7");
		output ("... it might not make you a great hunting but at least the hunting's easier this way, eh?");
		addnav ("Continue","forest.php");
	}

	if ($op == "hof") {
		page_header("Hall of Fame");
		
		if(get_module_setting('toplist') > 0){
			$toplist = " LIMIT 0, " . get_module_setting('toplist');
		}else{
			$toplist = "";
		}
		
		output ("`c`b`^The best hunters of our lands!`b`c`n");

		$sql		= "SELECT COUNT(*) AS c FROM " . db_prefix("module_userprefs") . " WHERE modulename='skintan' and value > 0 and setting='skinlevel'";
		$result	= db_query($sql);
		$row		= db_fetch_assoc($result);
		$ranked	= $row['c'];
		$sql		= "SELECT prefs.userid, (prefs.value+0) AS skinlevel, users.name, users.sex, users.race FROM " . db_prefix("module_userprefs") . " AS prefs,  " . db_prefix("accounts") . " AS users WHERE prefs.setting='skinlevel' AND prefs.value>0 AND prefs.modulename='skintan' AND prefs.userid=users.acctid $allowsu ORDER BY (prefs.value+0) DESC, prefs.userid ASC $toplist";
		$result	= db_query($sql);
		$max		= db_num_rows($result);
		
		rawoutput		("<table style='border: none; align: center;' cellpadding='3' cellspacing='0'>");
		output_notl		("<tr class='trhead'><td>`b$rank`b</td><td>`b$name`b</td><td>`b$race`b</td><td>`b$level`b</td>",true);

		for($i=0;$i<$max;$i++){
			$row = db_fetch_assoc($result);
		
			if ($row['name']==$session['user']['name']){
				rawoutput ("<tr class='trhilight'><td>");
			} else {
				rawoutput ("<tr class='".($i%2?"trdark":"trlight")."'><td>");
			}
		
			$rnk=$i+1;
			output_notl	("$rnk");
		
			rawoutput	("</td><td style='align: center;'>");
	
			output_notl ("`&%s`0", $row['name']);
			rawoutput 	("</td>	<td>");
			output_notl	("`#%s`0", $row['race']);
			rawoutput	("</td><td style='align: right;'>");
			output_notl	("`&%s`0", $row['skinlevel']);
			rawoutput	("</td></tr>");
		}

		rawoutput("</table>");

		if($max == 0){
			output ("`&`cAs of yet, our lands have no hunters.`c`n");
		}
	
		addnav ("Back to the HoF", "hof.php");
		villagenav();
	}

	page_footer ();
}

?>