<?php
function masons_rules(){
	global $session;
	$golddues=get_module_setting("dues");
	$dismisstime=get_module_setting("dismisstime");
	$dkban=get_module_setting("dkban");
	$allprefs=unserialize(get_module_pref('allprefs'));
   if ($allprefs['offermember']==1) {
		output("`&When you finally recover from the latest shock of `4pain`&, you sit up in the bed and look around the room.");
		output("It is empty except for a simple black robe, similar to the one that `&N`)armyan`& was wearing, draped over a chair.");
		output("You decide to put on the robe and then leave the bedroom.");
		output("Almost instinctively, you walk down the corridor and find yourself standing in `&T`)he `&P`)arlor`&.`n`n");
		output("`&N`)armyan`& walks over to you and offers you a glass of wine and smiles at you.");
		output("`n`n`7'You are now a member.");
		output("There are certain rules that you must remember.");
		output("If you ever need me to review them with you again, please stop by at my office.'`n`n");
	}
	output("`c`&T`)he `&R`)ules`c`n");
	output("`\$1. `&DO NOT TALK ABOUT `&T`)HE `&S`)ECRET `&O`)RDER `&O`)F `&M`)ASONS`&.");
	output("`\$If anyone asks about your membership or about the `&O`)rder`\$ and it is discovered that you have revealed `&ANYTHING`\$ about the `&O`)rder`\$, you will be dismissed.");
	output("There is no negotiation on this point.");
	output("If you suspect or have evidence that a `&M`)ason`\$ is sharing information, please report it to `&Staff`\$ and they will have me evaluate the claim and respond appropriately.`n`n");
	output("`42.  `&DO NOT EXPLAIN BENEFITS TO ANYONE; EVEN OTHER MASONS.");
	output("`4Members that have excelled at `@Dragon Killing `4should not share information about benefits that they have access to.");
	output("These benefits are for members to discover and explore on their own.`n`n");
	output("`\$3. `&VISIT THE `&O`)RDER`& FREQUENTLY.");
	output("`\$Members that are absent for prolonged periods of time may have their membership revoked.`n`n");
	output("`44. `&IF YOUR MEMBERSHIP IS REVOKED YOUR TATTOO WILL BE REMOVED.");
	output("`4  Although this causes no physical pain, it will be a symbol of revocation of your membership.`n`n");
	output("`\$5. `&DO NOT ABUSE BENEFITS.");
	output("`\$Different benefits will become available to you as you excel in killing the `@Green Dragon`\$.");
	output("However, if you are too aggressive or greedy and take too great of advantage of these benefits, you may notice that you are no longer welcome here.");
	if($golddues>0) output("You will not be able to access any of your benefits until you have paid your membership dues. Please see the `&T`)reasurer `\$ for the status of your current dues.");
	output("`n`n");
	if ($dismisstime==0) output("`46. `&YOU CANNOT REJOIN UNLESS YOU ARE RE-INVITED`4 if you are dismissed from `&T`)he `&O`)rder`4.  Keep this in mind if you are thinking about violating any of the rules.`n`n");
	if ($dismisstime==1 && $dkban==0) output("`46. `&YOU CAN NEVER REJOIN IF YOU ARE DISMISSED`4 from `&T`)he `&O`)rder`4 for any violation other than breaking RULE #3.  Our focus is excellence and failure is not tolerated.  If you break RULE #3, you may be dismissed but you will have the chance to join again if you show you are worthy again. Keep this in mind if you are thinking about violating any of the rules.`n`n");
	if ($dismisstime==2 && $dkban==0) output("`46. `&YOU CAN NEVER REJOIN IF YOU ARE DISMISSED MORE THAN 1 TIME from `&T`)he `&O`)rder`4 for any violation other than breaking RULE #3.  Our focus is excellence and failure is not tolerated.  If you break RULE #3, you may be dismissed but you will have the chance to join again if you show you are worthy again. Keep this in mind if you are thinking about violating any of the rules.`n`n");
	if ($dismisstime>2 && $dkban==0) output("`46. `&YOU CAN NEVER REJOIN IF YOU ARE DISMISSED MORE THAN %s TIMES from `&T`)he `&O`)rder`4 for any violation other than breaking RULE #3.  Our focus is excellence and failure is not tolerated.  If you break RULE #3, you may be dismissed but you will have the chance to join again if you show you are worthy again. Keep this in mind if you are thinking about violating any of the rules.`n`n",$dismisstime-1);
	if ($dismisstime==1 && $dkban==1) output("`46.`&YOU CANNOT BE INVITED BACK UNTIL YOU'VE COMPLETED`@ ONE DRAGON KILL`4 if you are dismissed from `&T`)he `&O`)rder`4.  You must prove you are worthy. Keep this in mind if you are thinking about violating any of the rules.`n`n");
	if ($dismisstime==1 && $dkban>1) output("`46.`&YOU CANNOT BE INVITED BACK UNTIL YOU'VE COMPLETED`@ %s DRAGON KILLS`4 if you are dismissed from `&T`)he `&O`)rder`4.  You must prove you are worthy. Keep this in mind if you are thinking about violating any of the rules.`n`n",$dkban);
	if ($dismisstime>1 && $dkban==1) output("`46.`&YOU CANNOT BE INVITED BACK UNTIL YOU'VE COMPLETED`@ ONE DRAGON KILL`4 if you are dismissed from `&T`)he `&O`)rder`4 more than `& %s TIMES`4.  You must prove you are worthy. Keep this in mind if you are thinking about violating any of the rules.`n`n",$dismisstime);
	if ($dismisstime>1 && $dkban>1) output("`46.`&YOU CANNOT BE INVITED BACK UNTIL YOU'VE COMPLETED`@ %s DRAGON KILLS`4 if you are dismissed from `&T`)he `&O`)rder`4 more than`& %s TIMES`4.  You must prove you are worthy. Keep this in mind if you are thinking about violating any of the rules.`n`n",$dkban,$dismisstime);
	output("`\$7.  `&USE THE `&O`)RDER`& COLORS.");
	output("`\$As a sign of respect, if you send a private message to another member, please use our colors in the subject heading.");
	output("Remember, the `&O`)rder `\$ thrives on our ability to recognize each other.");
	output("The first letter of words should use `&'&`\$, and subsequent letters should use `)')`\$.");
	output("When new members enter, send them a note of greeting.");
	if ($allprefs['offermember']==0) {
		addnav("The Order");
		addnav("`&Narmyan's Office","runmodule.php?module=masons&op=office");
		addnav("`&The Lounge","runmodule.php?module=masons&op=enter");
		addnav("Village");
		addnav("Return to Village","village.php");
	}else{
		output("`7`n`n'These are our rules.  Now let me introduce you to our members.'`n`n");
		addnav("`&M`)asons `&O`)rder","runmodule.php?module=masons&op=enter");
		$allprefs['offermember']=0;
		$allprefs['benefit']=0;
		set_module_pref('allprefs',serialize($allprefs));
		set_module_setting("newestmember",$session['user']['name']);
		set_module_setting("newestid",$session['user']['acctid']);
	}
}
?>