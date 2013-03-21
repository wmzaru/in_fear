<?php
function masons_benefits(){
	global $session;
	$allprefs=unserialize(get_module_pref('allprefs'));
	$dksincego=$allprefs['dksincego'];
	$benefit=$allprefs['benefit'];
	$dkstart=get_module_setting("dkstart");
	$golddues=get_module_setting("dues");
	$mbstarts=get_module_setting("mbstarts");
	$masongivemason=get_module_setting("masongivemason");
	$masongivenonmason=get_module_setting("masongivenonmason");
	$extraben=get_module_setting("extraben");
	$dragonkills=$session['user']['dragonkills'];
	output("`7'The benefits of being a member of `&T`)he `&O`)rder`7 improve as");
	if ($dkstart==0)output("you show excellence in killing the `@Green Dragon`7.");
	if ($dkstart==1){
		output("the number of times you've killed the `@Green Dragon`7 since joining the `&O`)rder`7 increases. You have killed the `@Green Dragon");
		if ($dksincego==1)output("`& one time`7 since joining.");
		if ($dksincego>1)output("`&%s times`7 since joining.",$dksincego);
	}
	output("After each dragon kill, it is highly recommended that you come to review which benefits are available to you.'`n`n");
	output("'You may request your benefits with each");
	if (get_module_setting("resetnd")==1) output("system");
	output("new day.'`n`n");
	if ($golddues>0 && $allprefs['duespaid']<$golddues) {
		output("'Unfortunately, none of your benefits are available to you until you pay your dues.");
		output("In fact, there is little need to review the benefit options until you have paid your dues in full.'");
		output("`n`n 'Please see the `&T`)reasurer`7 if you wish to settle this matter today.'`n`n");
		addnav("Dues");
		addnav("`&Review Dues","runmodule.php?module=masons&op=reviewdues");
		addnav("Pay Dues","runmodule.php?module=masons&op=duestopay");
	}else{
		if (($dkstart==0&&$dragonkills<$mbstarts) || ($dkstart==1&&$dksincego<$mbstarts)){
			output("'Benefits will not become available to you until you have reached `@");
			if ($dkstart==0) output("%s Dragon Kills`7.",$mbstarts);
			if ($dkstart==1) output("%s Dragon Kills`7 since joining the society.",$mbstarts);
			output("Please come back after you have reached this milestone to review your benefits.'");
		}else{
			output("'I must tell you that although the resources of `&T`)he `&O`)rder`7 are vast, they are not endless.'");
			output("`n`n'With your expertise, you can safely ask to use `&");
			if (($dkstart==0&&$dragonkills<$masongivemason) || ($dkstart==1&&$dksincego<$masongivemason) || ($extraben==0)) {
				output("`b1 benefit`b a day`7 and you have");
				if ($benefit==0) output(" `&not used your benefit `7for today.'");
				if ($benefit==1) output(" `&used your benefit `7for today.'");
				if ($benefit>=2) output(" used %s benefits`7 today.'",$benefit);
			}elseif ((($dkstart==0&&$dragonkills<$masongivenonmason) || ($dkstart==1&&$dksincego<$masongivenonmason)) && ($extraben==1)) {
				output("`b2 benefits`b a day`7 and you have ");
				if ($benefit==0) output(" `&not used any benefits");
				if ($benefit==1) output(" `&`bused one benefit`b");
				if ($benefit>=2) output(" `&`bused %s benefits`b",$benefit);
				output("`7today.'`n`n'Giving a gift to a fellow `&M`)ason`7 uses `&2 Benefits`7 for the day. The wise `&M`)ason`7 always knows how many benefits they have left for the day.'");
				output("`n`n'You may `&NOT`7 tell anyone that you sent them a gift. The gift of a `&M`)ason`7 is `b`&ALWAYS ANONYMOUS`7`b. This applies to gifts to `&M`)asons `7and Non-Masons alike.'");
			}elseif ((($dkstart==0&&$dragonkills>=$masongivenonmason) || ($dkstart==1&&$dksincego>=$masongivenonmason)) && ($extraben==1)){
				output("`b3 benefits`b a day`7 and you have ");
				if ($benefit==0) output(" `&not used any benefits");
				if ($benefit==1) output(" `&`bused one benefit`b");
				if ($benefit>=2) output(" `&`bused %s benefits`b",$benefit);
				output("`7today.' `n`n'Giving a gift to a fellow `&M`)ason`7 uses `&2 Benefits`7 while giving to a Non-Mason uses `&3 Benefits`7.");
				output("The wise `&M`)ason`7 always knows how many benefits they have left for the day.'`n`n'You may `&NOT`7 tell anyone that you sent them a gift.");
				output("The gift of a `&M`)ason`7 is `b`&ALWAYS ANONYMOUS`7`b. This applies to gifts to `&M`)asons `7and Non-Masons alike.'");
			}
			if (get_module_setting("mildexcede")<100) output("`n`n'Nobody will stop you from taking more. But if you abuse this too often, you may not find yourself welcome here anymore.'`n`n");
			else output("`n`nYou are responsible for monitoring your benefit use. If you ask for more benefits than you are allowed, you will be summarily dismissed from `&T`)he `&O`)rder`7.");
			output("'I will not explain what the benefits are before you ask for them. After all, what are we if we don't have any secrets?'`n`n");
			if (($dkstart==0&&$dragonkills>=$masongivemason || $dkstart==1&&$dksincego>=$masongivemason) && (($dkstart==0 && $dragonkills<$masongivenonmason) || ($dkstart==1 && $dksincego<$masongivenonmason)) && $extraben==1) {
				output("`7'Since you have shown excellence in `@Dragon Killing`7, you now have the opportunity to share gifts with other `&M`)asons`7.");
				output("`n`nOnce again, you will not know what the benefits are until after you have chosen them.`n`n");
			}
			if ((($dkstart==0&&$dragonkills>=$masongivenonmason) || ($dkstart==1&&$dksincego>=$masongivenonmason)) && ($extraben==1)) {
				output("`7'Since you have shown excellence in `@Dragon Killing`7, you now have the opportunity to share gifts with not only other `&M`)asons`7, but also to Non-Masons`7.");
				output("Once again, you may not know what the benefits are until after you have chosen them.'`n`n");
			}
		}
	}
	masons_masonnav1();
}
?>