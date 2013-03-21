<?php
function masons_newweapon(){
	global $session;
	output("`&You take a walk to visit the `&B`)lacksmith`&. He looks at you with a smile.`n`nThe `&B`)lacksmith`& examines your current weapon.`n`n`7'I think you should try one of our custom weapons.'`&`n`n");
	if ($session['user']['weapondmg'] >= 2) output("He takes your current weapon.`n`n`7'I wish I could give you something for your old weapon, but we have to sell it on the market for `&T`)he `&O`)rder `&C`)offers`7.'`&`n`n");
	$session['user']['attack']-=$session['user']['weapondmg'];
	switch(e_rand(1,13)){
		case 1: case 2: case 3: case 4: case 5:
			if ($session['user']['weapondmg'] >= 7) $upset=1;
			$session['user']['weapon']="`&M`)ason `&M`)ace";
			$session['user']['weaponvalue']=2250;
			$session['user']['weapondmg'] = 6;
			$session['user']['attack']+=6;
			output("You try out your `&M`)ason `&M`)ace`& (attack 6).`n`n ");
			debuglog("received weapon damage 6 as a Mason Benefit.");
		break;
		case 6: case 7: case 8: case 9:
			if ($session['user']['weapondmg'] >= 8) $upset=1;
			$session['user']['weapon']="`&M`)ason `&L`)ong `&S`)word";
			$session['user']['weaponvalue']=2790;
			$session['user']['weapondmg'] = 7;
			$session['user']['attack']+=7;
			output("You try out your `&M`)ason `&L`)ong `&S`)word`& (attack 7).`n`n ");
			debuglog("received weapon damage 7 as a Mason Benefit.");
		break;
		case 10: case 11: case 12:
			if ($session['user']['weapondmg'] >= 9) $upset=1;
			$session['user']['weapon']="`&M`)ason `&B`)road `&S`)word";
			$session['user']['weaponvalue']=3420;
			$session['user']['weapondmg'] = 8;
			$session['user']['attack']+=8;
			output("You try out your `&M`)ason `&B`)road `&S`)word`& (attack 8).`n`n ");
			debuglog("received weapon damage 8 as a Mason Benefit.");
		break;
		case 13:
			$session['user']['weapon']="`b`&M`)adrikon `&M`)ason `&S`)word`b";
			$session['user']['weaponvalue']=get_module_setting("madrikongold");
			$session['user']['weapondmg'] =get_module_setting("madrikonatk");
			$session['user']['attack']+=get_module_setting("madrikonatk");
			output("The `&B`)lacksmith `& hands you the most amazing `&S`)word`& you've ever seen.`n`n `7'I think you'll appreciate this.");
			output("This is one of the greatest swords I have ever made and I have chosen you to wield it. It is named `&M`)adrikon`7, and it is a very unique and rare sword indeed.'");
			output("`&`n`n You bow very low and take a couple of test swings with `b`&M`)adrikon `&M`)ason `&S`)word`b`& (attack %s).",get_module_setting("madrikonatk"));
			output("`n`nYou've never seen a weapon like this before, and you give a low bow of appreciation to the `&B`)lacksmith`&.`n`n");
			output("`7'I just ask that you do not tell anyone who gave you this sword. It should remain a secret.'`&`n`n");
			debuglog("received the Madrikon Mason Sword as a Mason Benefit.");
		break;
		}
	if ($upset==1) output("`7'Well, I'm sorry if it isn't the same quality as your last weapon.  Usually, I only see `&M`)asons`7 that are looking for a starter weapon. I'm sure I'll see you around after your next encounter with the `@Green Dragon`7.'`n`n");
	else output("This is an excellent weapon and you compliment the `&B`)lacksmith`&.`n`n");
	masons_masonbenefit1();
}
?>