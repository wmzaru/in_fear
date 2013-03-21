<?php

function masons_masonbenefit1(){
	global $session;
	$allprefs=unserialize(get_module_pref('allprefs'));
	if (((get_module_setting("extraben")==0) && $allprefs['benefit']>3) || ((get_module_setting("extraben")==1) && $allprefs['benefit']>7)){
		if (e_rand(1,100)<=get_module_setting("severeexcede")){
			masons_dismissed();
		}else{
			output("`&N`)armyan`& walks by and sees you, raises an eyebrow, shrugs, and wanders off.`n`n");
			masons_masonnav1();
		}
	}
	if (((get_module_setting("extraben")==0) && $allprefs['benefit']>2) || ((get_module_setting("extraben")==1) && $allprefs['benefit']>5)){
		if (e_rand(1,100)<=get_module_setting("moderateexcede")){
			masons_dismissed();
		}else{
			output("`&N`)armyan`& walks by and sees you, raises an eyebrow, shrugs, and wanders off.`n`n");
			masons_masonnav1();
		}
	}elseif (((get_module_setting("extraben")==0) && $allprefs['benefit']>1)||(($session['user']['dragonkills']<get_module_setting("masongivemason"))&&($session['user']['dragonkills']<get_module_setting("masongivenonmason"))&& $allprefs['benefit']>1) || (($session['user']['dragonkills']>=(get_module_setting("masongivemason")))&&($session['user']['dragonkills']<get_module_setting("masongivenonmason"))&& $allprefs['benefit']>2)||(($session['user']['dragonkills']>=get_module_setting("masongivenonmason"))&& $allprefs['benefit']>3)) {
		if (e_rand(1,100)<=get_module_setting("mildexcede")){
			masons_dismissed();
		}else{
			output("`&N`)armyan`& walks by and sees you, raises an eyebrow, shrugs, and wanders off.`n`n");
			masons_masonnav1();
		}
	}else{
		masons_masonnav1();
	}
}
function masons_dismissed(){
	global $session;
	$allprefs=unserialize(get_module_pref('allprefs'));
	//If banning is allowed this adds one to the number of times dismissed
	if (get_module_setting("dismisstime")>0){
		$allprefs['dismisscount']=$allprefs['dismisscount']+1;
		set_module_pref('allprefs',serialize($allprefs));
		$allprefs=unserialize(get_module_pref('allprefs'));
	}
	output("`n`n`\$`bYou feel a tap on your shoulder and suddenly a chill runs through you.`b`n`n`&N`)armyan`& appears before you.");
	output("`n`n`7'I hope you enjoyed your benefit. It's too bad that you decided that our rules don't apply to you. Because you violated the 5th rule and abused our generosity, you have been been dismissed from `&T`)he `&O`)rder`7.'");
	output("`n`n'You will receive a confirmation notice from me in the mail.'`n`n'Goodbye.'`n`n`&You have been dismissed from `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons`& due to abuse of benefits.`n`n");
	//If banning is allowed and player has exceeded the allowed dismissals, triggers the banning
	if ((get_module_setting("dismisstime")>0)&&($allprefs['dismisscount']>=get_module_setting("dismisstime"))) {
		 $allprefs['permaban']=$allprefs['permaban']+1;
		 set_module_pref('allprefs',serialize($allprefs));
		 $allprefs=unserialize(get_module_pref('allprefs'));
		 output("'You have been dismissed from `&T`)he `&O`)rder`7 ");
		 if (get_module_setting("dkban")<=0) output("`bPermanently`b.'`n`n");
		 elseif (get_module_setting("dkban")>0) output ("until you kill the `@Green Dragon`7`b %s more times`b.'`n`n",get_module_setting("dkban")-$allprefs['dksinceban']);
	}
	if (get_module_pref("supermember")==1){
		output("`n`^So, you were goofing around? Well, don't worry. Your membership is revoked if you had it but your Superuser Access has not changed.`n`n");
		addnav("The Order");
		addnav("`&The Lounge","runmodule.php?module=masons&op=enter");
	}
	require_once("lib/systemmail.php");
	$id= $session['user']['acctid'];
	$subj = array("`\$Dismissal Notice`& from `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons");
	//This sends a letter that informs player of PERMANENT ban
	if ((get_module_setting("dismisstime")>0)&&(get_module_setting("dkban")==0))$body = array("`n`&D`)ear `^%s`&,`n`nYou have abused the generosity of `&T`)he `&O`)rder`&.  We have removed your tattoo and revoked your membership.  Perhaps you can join again some other day. `n`n This ban is permanent. You will `\$NEVER`& be invited back into `&T`)he `&O`)rder`&. `n`n  My decision is non-negotiable.`n`nSincerely,`n`n`&N`)armyan`n",$session['user']['name']);
	//This sends a letter that informs player of Ban for a setting number of dks
	if ((get_module_setting("dismisstime")>0)&&(get_module_setting("dkban")>0) && ($allprefs['dismisscount']>=get_module_setting("dismisstime")))$body = array("`n`&D`)ear `^%s`&,`n`nYou have abused the generosity of `&T`)he `&O`)rder`&.  We have removed your tattoo and revoked your membership.  `n`n This ban will be in effect until you have completed %s `@Dragon Kills`&. After you have completed them, you may one day get invited back into `&T`)he `&O`)rder`&. My decision is non-negotiable.`n`nSincerely,`n`n`&N`)armyan`n",$session['user']['name'],get_module_setting("dkban"));
	//This sends a letter that informs the player of a simple dismissal
	if ((get_module_setting("dismisstime")<=0)||((get_module_setting("dismisstime")>0)&& get_module_setting("dkban")>0 && $allprefs['dismisscount']<get_module_setting("dismisstime"))) $body = array("`n`&D`)ear `^%s`&,`n`nYou have abused the generosity of `&T`)he `&O`)rder`&. We have removed your tattoo and revoked your membership.  Perhaps you can join again some other day. My decision is non-negotiable.`n`nSincerely,`n`n`&N`)armyan`n",$session['user']['name']);
	systemmail($id,$subj,$body);
	masons_dismissalnotice();
	addnav("Village");
	addnav("Return to Village","village.php");
	debuglog("was dismissed from the Masons Order for abusing benefits.");
}
function masons_giftmasons(){
	output("`&The M`)agic `&U`)ser `&sits down exhausted.");
	output("`n`n`7'I'm sorry, but these spells take a lot out of me.");
	output("Because of that, you have been charged for `&");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$id = $allprefs['gifttoid'];
	$allprefsmas=unserialize(get_module_pref('allprefs','masons',$id));
	if ($allprefsmas['masonmember']==0 && get_module_setting("extraben")==1) {
		output("`bThree Benefits`b`7.'`n`n");
		$allprefs['benefit']=$allprefs['benefit']+3;
	}elseif (get_module_setting("extraben")==1) {
		output("`bTwo Benefits`b`7.'`n`n");
		$allprefs['benefit']=$allprefs['benefit']+2;
	}else{
		$allprefs['benefit']=$allprefs['benefit']+1;
		output("`bOne Benefit`b`7.'`n`n");
	}
	set_module_pref('allprefs',serialize($allprefs));
	output("`7'Remember, you `&CANNOT`7 tell the recipient about mason gifts.");
	output("A `&M`)ason`7 must be loyal first to the secrecy of the `&O`)rder`7.'`n`n");
}

function masons_masonnav1(){
	addnav("The Order");
	addnav("`&Narmyan's Office","runmodule.php?module=masons&op=office");
	addnav("`&The Lounge","runmodule.php?module=masons&op=enter");
	addnav("Village");
	addnav("Return to Village","village.php");
}
function masons_superdiscipline(){
	output("`^Welcome to the Superuser Discipline Area.");
	output("This Area is available for Superusers to send warnings to `&M`)asons`^ that have violated the rules of `&T`)he `&O`)rder`^.`n`n");
	output("`^The following system YoMs may be sent to `&M`)asons `^ if violations are found or suspected:`n`n");
	output("`^1.  A `\$WARNING`^ that they MAY BE kicked out of `&T`)he `&O`)rder`^ for sharing secrets.`n`n");
	output("`^2.  A `\$WARNING`^ that is nonspecific`n`n`");
	output("`^3.  A `\$DISMISSAL`^ that they HAVE BEEN kicked out of `&T`)he `&O`)rder`^ for sharing secrets.`n`n");
	output("`^4.  A `\$DISMISSAL`^ that is nonspecific`n`n`");
}
function masons_dismissalnotice(){
	global $session;
	$allprefs=unserialize(get_module_pref('allprefs'));
	$allprefs['masonmember']=0;
	$allprefs['tatpain']=0;
	$allprefs['benefit']=0;
	$allprefs['donated']=0;
	$allprefs['duespaid']=0;
	$allprefs['dksincego']=0;
	$allprefs['duestime']=get_module_setting("duetime");
	set_module_pref('allprefs',serialize($allprefs));
	set_module_pref("masonnumber",0);
	if (get_module_setting("newestid")==$session['user']['acctid']) set_module_setting("newestmember","Nobody");
	modulehook("masons-dismissal");
}
?>