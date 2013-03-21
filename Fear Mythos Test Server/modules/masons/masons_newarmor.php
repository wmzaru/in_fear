<?php
function masons_newarmor(){
	global $session;
	output("`&You take a walk to visit the `&B`)lacksmith`&. He looks at you with a smile.`n`nThe `&B`)lacksmith`& examines your current armor.");
	output("`n`n`7'I think you should try a suit of our custom armor.'`&`n`n");
	if ($session['user']['armordef'] >= 2) output("You give him your current armor.`n`n`7'I wish I could give you something for your old armor, but we have to sell it on the market for `&T`)he `&O`)rder `&C`)offers`7.'`&`n`n");
	$session['user']['defense']-=$session['user']['armordef'];
	switch(e_rand(1,13)){
		case 1: case 2: case 3: case 4: case 5:
			if ($session['user']['armordef'] >= 7) $upset=1;
			$session['user']['armor']="`&M`)ason `&S`)tudded `&L`)eather";
			$session['user']['armorvalue']=2250;
			$session['user']['armordef'] = 6;
			$session['user']['defense']+=6;
			output("You try out your `&M`)ason `&S`)tudded `&L`)eather`& (defense 6).`n`n ");
			debuglog("received armor defense 6 as a Mason Benefit.");
		break;
		case 6: case 7: case 8: case 9:
			if ($session['user']['armordef'] >= 8) $upset=1;
			$session['user']['armor']="`&M`)ason `&C`)hail `&M`)ail";
			$session['user']['armorvalue']=2790;
			$session['user']['armordef'] = 7;
			$session['user']['defense']+=7;
			output("You try out your `&M`)ason `&C`)hail `&M`)ail`& (defense 7).`n`n ");
			debuglog("received armor defense 7 as a Mason Benefit.");
		break;
		case 10: case 11: case 12:
			if ($session['user']['armordef'] >= 9) $upset=1;
			$session['user']['armor']="`&M`)ason `&S`)cale `&M`)ail";
			$session['user']['armorvalue']=3420;
			$session['user']['armordef'] = 8;
			$session['user']['defense']+=8;
			output("You try out your `&M`)ason `&S`)cale `&M`)ail`& (defense 8).`n`n ");
			debuglog("received armor defense 8 as a Mason Benefit.");
		break;
		case 13:
			$session['user']['armor']="`&M`)ason `&B`)lack `&A`)rmor";
			$session['user']['armorvalue']=get_module_setting("blackarmorgold");
			$session['user']['armordef'] =get_module_setting("blackarmordef");
			$session['user']['defense']+=get_module_setting("blackarmordef");
			output("The `&B`)lacksmith `& hands you the most amazing `&A`)rmor`& you've ever seen.`n`n `7'I think you'll appreciate this.");
			output("This is	one of the rarest suits of armor ever seen and I have chosen you to wear it. It is called `&M`)ason `&B`)lack `&A`)rmor`7, and it is a very unique suit of armor.'");
			output("`&`n`n You bow very low and allow the `&B`)lacksmith`& to help you put the armor on (defense %s).",get_module_setting("blackarmordef"));
			output("`n`nYou've never seen armor like this before, and you give a low bow of appreciation to the `&B`)lacksmith`&.`n`n");
			output("`7'I just ask that you do not tell anyone who gave you this armor. It should remain a secret.'`&`n`n");
			debuglog("received Mason Black Armor as a Mason Benefit.");
		break;
		}
	if ($upset==1) output("`7'Well, I'm sorry if it isn't the same quality as your last suit of armor. Usually, I only see `&M`)asons`7 that are looking for a starter suit of armor. I'm sure I'll see you around after your next encounter with the `@Green Dragon`7.'`n`n");
	else output("This is an excellent suit of armor and you compliment the `&B`)lacksmith`&.`n`n");
	masons_masonbenefit1();
}
?>