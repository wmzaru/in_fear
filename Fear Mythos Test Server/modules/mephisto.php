<?php
// addnews ready
// mail ready
// translator ready
/* Mischief and Mayhem, Attorneys-at-Law, Bounty Removal */
/* ver 1.0 by Silverfox aka S. Sheggrud*/
/* March 2005 */

require_once("common.php");
require_once("lib/http.php");
require_once("lib/villagenav.php");

function mephisto_getmoduleinfo(){
	$info = array(
		"name"=>"Mischief and Mayhem, Attorneys-at-Law",
		"author"=>"Silverfox",
		"version"=>"1.0",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/users/ladymari/mephisto.zip",
		"vertxtloc"=>"http://dragonprime.net/users/ladymari/",
		"settings"=>array(
			"mephistoloc"=>"Where does Mischief and Mayhem, Attorneys-at-Law appear?,location|".getsetting("villagename", LOCATION_FIELDS),
		)
	);
	return $info;
}

function mephisto_install(){
	debug("Installing Mischief and Mayhem, Attorneys-at-Law.`n");
	module_addhook("newday");
	module_addhook("village");
	module_addhook("changesetting");
	return true;
}

function mephisto_uninstall(){
	output("Uninstalling Mischief and Mayhem, Attorneys-at-Law.`n");
	return true;
}

function mephisto_dohook($hookname,$args){
	global $session;
	
	switch($hookname){
	case "changesetting":
		if ($args['setting'] == "villagename") {
			if ($args['old'] == get_module_setting("mephistoloc")) {
				set_module_setting("mephistoloc", $args['new']);
			}
		}
		break;
	case "newday":
		set_module_pref("refresh",0);
		break;
	case "village":
		if ($session['user']['location'] == get_module_setting("mephistoloc")){
			tlschema($args['schemas']['marketnav']);
			addnav($args['marketnav']);
			tlschema();
			addnav("Mischief and Mayhem, Attorneys-at-Law","runmodule.php?module=mephisto");
		}
		break;
	}
	return $args;
}

function mephisto_run() {
	global $session;
	$op = httpget("op");
	
	page_header("Law Offices");
	output("`bMischief and Mayhem, Attorneys at Law`b`n`n");
	
	if ($op=="") {
		output("You walk down a side street, passing the bank.");
		output("The district seems safe enough but there's a hint of a seedy undertone to it.");
		output("You come to a somewhat dusty door where you can barely make out what was once gold lettering on the window.");
		output("Squinting, you can see it reads `i`6\"Mischief and Mayhem, Attorneys at Law.\"`i`6");
		addnav("Enter the building","runmodule.php?module=mephisto&op=enter");
	}elseif ($op=="enter"){
		output("Shaking your head slightly, you push the door open to find fairly non-discript waiting area before you.");
		output("Scanning the room, you see a voluptious blonde behind the desk, the name plate reads \"Miss Demeanor.\"");
		output("Smiling vacantly, she looks up at you and her voice practically drips with sweetness as she speaks, \"Hiya honey, what can I do you for?\"");
		output("`n`n");
		output("Rather hesitantly, still unsure you're in the right place you tell her you were looking for Mischief & Mayhem as you'd been told they could help with a small legal issue.`n`n");
		output("\"Oh I'm sorry, but they're not available but we got someone in covering things so maybe Mr. Meff... Mr. Moffy... Mr. Memphis... oh fooey.\"`n`n");
		output("She pushes away from the desk and walks across the room on impossibly high, impossibly slender heels.");
		output("Pausing for a moment at an oaken door, she takes a deep breath as if steeling herself.");
		output("Her voice carries across the room as she leans in slightly to talk to whomever is in the office, \"Meffie honey, got a minute for a client?\"");
		output("A low rumble is all you can hear of the occupant's reply.`n`n");
		output("Stepping back slightly, she pushes the door wide as she motions you in.");
		output("\"Go on in honey, he won't bite... much, will you Meffie baby?\"");
		addnav("Go into the back room","runmodule.php?module=mephisto&op=room");
		addnav("Flee","village.php");
	}elseif ($op=="room"){
		output("As you pass her, a shadow draped form deeper in the room rumbles, \"Thank you Bambi, that will be all.\"");
		output("Saying nothing further, she closes the door behind you, leaving you in the fairly dark room with gods only know what.`n`n");
		output("The man leans fowards slightly, resting his arms on the smooth mahogany desk revealing that he is a Drow, darkly elegant and somehow sinister all at once.");
		output("A platium brow wings upward as he shakes his head slightly, \"She's as pretty as a painting but not the brightest flame in the candelabra if you catch my meaning.\"`n`n");
		output("Unsure how to answer, you nod mutely trying not to back away as you catch a hint of fiery red in the depths of his eyes.`n`n");
		output("\"Please, come in, my name is Faustus Mephistopheles and I'm an associate of Mischief and Mayhem. Now, %s what shall we do about that bounty on your head?\"",$session['user']['name']);
		output("`n`n");
		output("His rich laughter fills the room as you stutter about how could he have possibly known.");
		output("`n`n");
		output("\"I make it my business to know such things, %s. So, do you wish the bounty removed or not? The fee is a single, solitary gem.\"",$session['user']['name']);
		addnav("Pay the fee","runmodule.php?module=mephisto&op=pay");
		addnav("Flee!","runmodule.php?module=mephisto&op=leave");
	}elseif($op=="pay") {
		if($session['user']['gems']>1)
		$session['user']['gems']-=1;
		$sql = "UPDATE ".db_prefix("bounty")." SET status=1 WHERE target=".$session['user']['acctid']."";
               db_query($sql);
		output("Not wishing to try Mephistopheles' patience you cautiously lay a gem on the desk trying not to flinch as he reaches out to take it.");
		output("\"A little skittish for a warrior, aren't you? It's alright, I have that affect on people.\"");
		output("Tossing the gem up in the air, he catches the spinning bauble easily.");
		output("\"Very well, our business is concluded.");
		output("Away with you before I decide to renew the bounty myself.\"");
		addnav("Leave!","runmodule.php?module=mephisto&op=leave");
	}elseif($session['user']['gems']==0){
		output("A hint of smoke curls around Mephistopheles head as he glares at you.");
		output ("\"You waste my time. Come back when you have payment for services rendered.\"`n`n");
		villagenav();	
	}elseif($op=="leave") {
		output("As you leave, you hear faint laughter from behind you.");
		output("A quick glance over your shoulder reveals a completely different couple from Miss Demeanor and Mephistopheles standing there.");
		output("As you watch, the woman lightly brushes her hand across the man's cheek and you can just barely hear her as she speaks.");
		output("\"Shall we go home now, mine own?\"`n`n");
		output("His dark laughter sends chills down your spine as the two disappear in a shower of `7silvery `\$red `&sparks.");
		output("`n`n");
		output("It is only then that you realize you've had a brush with higher powers this day.`n`n");
		output("Not wishing to be there if or when Chaos and Darkness returned, you head back to the village as fast as you can.");
		villagenav();
	}
	page_footer();
}
?>