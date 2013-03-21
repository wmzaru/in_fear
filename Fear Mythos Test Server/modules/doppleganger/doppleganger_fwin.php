<?php
function doppleganger_fwin(){
	global $session;
	$allprefs=unserialize(get_module_pref('allprefs'));
	output("`c`b`@The Doppleganger`b`c`n");
	output("`#'That was very impressive. I");
	$dks=$session['user']['dragonkills'];
	$name=$allprefs['dgname'];
	$expbonus=$dks*3;
	$expgain =($session['user']['level']*30+$expbonus);
	$session['user']['experience']+=$expgain;
	$ap1=$allprefs['approve1'];
	$ap2=$allprefs['approve2'];
	$ap3=$allprefs['approve3'];
	if ($allprefs['dgid']==$session['user']['acctid']){
		output("think I will have to use your current weapon and armor in my next imitation of you. In addition, I");
		$allprefs['weapon']=$session['user']['weapon'];
		$allprefs['armor']=$session['user']['armor'];
		$allprefs['name']=$session['user']['name'];
		set_module_pref('allprefs',serialize($allprefs));
		$allprefs=unserialize(get_module_pref('allprefs'));
	}
	if (($ap1==0 && $ap2==0 && $ap3==0) || ($ap1==2 && $ap2==2 && $dks>= get_module_setting("phrase3dk") && $ap3==2) || ($ap1==2 && $ap2==2 && $dks< get_module_setting("phrase3dk"))) output("give you my highest compliments on an excellent duel!'`n");
	elseif($dks>= get_module_setting("phrase2dk") || $dks>= get_module_setting("phrase3dk")){
		output("would like to offer you a chance to change one of the battle cries I use for you.'");
		if ($ap1==4 && $ap2==4 && ($ap3==0 || $ap3==4)) output("`n`n`#'You declined to offer any battle cries earlier.'");
		else{
			output("`n`n`^Previous battle cry choices:`n`n");
			if ($ap1==1 || $ap1==3) output("`c`2Phrase 1: `@'%s`@'`c",$allprefs['phrase1']);
			if ($ap1==4) output("`c`2Phrase 1: You Declined to Offer this Phrase");
			if ($ap2==1 || $ap2==3) output("`c`3Phrase 2: `@'%s`@'`c",$allprefs['phrase2']);
			if ($ap2==4) output("`c`3Phrase 2: You Declined to Offer this Phrase`c");
			if ($ap3==1 || $ap3==3) output("`c`4Phrase 3: `@'%s`@'`c",$allprefs['phrase3']);
			if ($ap3==4) output("`c`4Phrase 3: You Declined to Offer this Phrase");
		}
		if ($ap1<>2) addnav("Change Phrase 1","runmodule.php?module=doppleganger&op=changewin&op2=1");
		if ($ap2<>2) addnav("Change Phrase 2","runmodule.php?module=doppleganger&op=changewin&op2=2");
		if($dks>= get_module_setting("phrase3dk") && $ap3<>2) addnav("Change Phrase 3","runmodule.php?module=doppleganger&op=changewin&op2=3");
	}elseif($dks>= get_module_setting("phrase1dk")){
		output("would like to offer you a chance to change your battle cry I use for you.'`n");
		addnav("Change Your Phrase","runmodule.php?module=doppleganger&op=changewin&op2=1");
	}elseif($dks<get_module_setting("doppledk")){
		output("believe one day you will have the honor of my imitation. But not quite yet.'`n");
	}else{
		output("give you my highest compliments on an excellent duel!'`n");
	}
	output("`n`@`bYou've gained `#%s experience`@.`b`n`n",$expgain);
	addnav("Return to the Forest","runmodule.php?module=doppleganger&op=exit");
}
?>