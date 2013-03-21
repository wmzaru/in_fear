<?php
	global $session;
	require_once("modules/masons/masons_func.php");
	$op2 = httpget('op2');
	$op = httpget('op');
	page_header("Secret Order of Masons");
	if ($op!="superuser") output("`n`c`b`&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons`n`n`c`b");
	$dragonkills=$session['user']['dragonkills'];
	$golddues=get_module_setting("dues");
	$dkban=get_module_setting("dkban");
	$dismisstime=get_module_setting("dismisstime");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$dksincego=$allprefs['dksincego'];
if ($op=="superuser"){
	require_once("modules/allprefseditor.php");
	allprefseditor_search();
	page_header("Allprefs Editor");
	$subop=httpget('subop');
	$id=httpget('userid');
	addnav("Navigation");
	addnav("Return to the Grotto","superuser.php");
	villagenav();
	addnav("Edit user","user.php?op=edit&userid=$id");
	modulehook('allprefnavs');
	$allprefse=unserialize(get_module_pref('allprefs',"masons",$id));
	if (!isset($allprefse['dksincego'])) $allprefse['dksincego']=0;
	if (!isset($allprefse['duespaid'])) $allprefse['duespaid']=0;
	if (!isset($allprefse['duestime'])) $allprefse['duestime']=0;
	if (!isset($allprefse['dismisscount'])) $allprefse['dismisscount']=0;
	if (!isset($allprefse['dksinceban'])) $allprefse['dksinceban']=0;
	if (!isset($allprefse['benefit'])) $allprefse['benefit']=0;
	if (!isset($allprefse['visited'])) $allprefse['visited']=0;
	if (!isset($allprefse['donated'])) $allprefse['donated']=0;
	if (!isset($allprefse['gifttoname'])) $allprefse['gifttoname']="";
	if (!isset($allprefse['gifttoid'])) $allprefse['gifttoid']=0;
	set_module_pref('allprefs',serialize($allprefse),'masons',$id);
	if ($subop!='edit'){
		$allprefse=unserialize(get_module_pref('allprefs',"masons",$id));
		$allprefse['offermember']= httppost('offermember');
		$allprefse['masonmember']= httppost('masonmember');
		$allprefse['dksincego']= httppost('dksincego');
		$allprefse['duespaid']= httppost('duespaid');
		$allprefse['duestime']= httppost('duestime');
		$allprefse['dismisscount']= httppost('dismisscount');
		$allprefse['permaban']= httppost('permaban');
		$allprefse['dksinceban']= httppost('dksinceban');
		$allprefse['benefit']= httppost('benefit');
		$allprefse['visited']= httppost('visited');
		$allprefse['tatpain']= httppost('tatpain');
		$allprefse['donated']= httppost('donated');
		$allprefse['superspiel']= httppost('superspiel');
		$allprefse['defenseboost']= httppost('defenseboost');
		$allprefse['attackboost']= httppost('attackboost');
		$allprefse['gdefenseboost']= httppost('gdefenseboost');
		$allprefse['gattackboost']= httppost('gattackboost');
		$allprefse['expboost']= httppost('expboost');
		$allprefse['incspecialty']= httppost('incspecialty');
		$allprefse['gifttoname']= httppost('gifttoname');
		$allprefse['gifttoid']= httppost('gifttoid');
		set_module_pref('allprefs',serialize($allprefse),'masons',$id);
		output("Allprefs Updated`n");
		$subop="edit";
	}
	if ($subop=="edit"){
		require_once("lib/showform.php");
		$form = array(
			"Secret Order of Masons,title",
			"offermember"=>"Has player been offered membership while at the Quarry?,bool",
			"masonmember"=>"Has player been granted membership?,bool",
			"dksincego"=>"How many dks has player had since joining the order?,int",
			"duespaid"=>"How much of the dues have they paid?,int",
			"duestime"=>"How many days left in this cycle of dues?,int",
			"dismisscount"=>"How many times have they been dismissed from the Order?,int",
			"permaban"=>"Has the player been banned from the order?,bool",
			"dksinceban"=>"Number of dks since the ban:,int",
			"DKS SINCE THE BAN may not matter if the Module Setting is for Permanent Ban,note",
			"benefit"=>"Number of benefits used today:,int",
			"visited"=>"Number of days since The Order was visited:,int",
			"tatpain"=>"Number of days until the tattoo heals:,range,0,55,1",
			"donated"=>"Total amount of Gold donated during membership:,int",
			"superspiel"=>"Was Superuser given notice of access?,bool",
			"Benefit Details,title",
			"defenseboost"=>"Has player Improved their Defense this dk?,bool",
			"attackboost"=>"Has player Improved their Attack this dk?,bool",
			"gdefenseboost"=>"Has player Improved Anyone Else's Defense this dk?,bool",
			"gattackboost"=>"Has player Improved Anyone Else's Attack this dk?,bool",
			"expboost"=>"Has player received Exp Boost today?,bool",
			"incspecialty"=>"Has player received Specialty Increase this dk?,bool",
			"gifttoname"=>"Who did the player give the last gift to?,text",
			"gifttoid"=>"What is the id of the last player given a gift?,int",
		);
		$allprefse=unserialize(get_module_pref('allprefs',"masons",$id));
		rawoutput("<form action='runmodule.php?module=masons&op=superuser&userid=$id' method='POST'>");
		showform($form,$allprefse,true);
		$click = translate_inline("Save");
		rawoutput("<input id='bsave' type='submit' class='button' value='$click'>");
		rawoutput("</form>");
		addnav("","runmodule.php?module=masons&op=superuser&userid=$id");
	}
}

if ($op=="superon") {
	output("`^`b`cSuperuser Notice`b`c`n");
	output("`^You now have access to `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons`^.");
	output("Please forward any problems to me on dragonprime.");
	output("`n`nThanks,`n`nDaveS");
	set_module_pref("supermember",1);
	addnav("Order Lounge");
	addnav("`^Return to The Order","runmodule.php?module=masons&op=enter");
	addnav("Village");
	addnav("Return to Village","village.php");
}
if ($op=="superoff") {
	output("`^`b`cSuperuser Notice`b`c`n");   
	output("`^In order to access `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons`^ you will need to be invited through the quarry as all players must.");
	output("However, you can still access the `&M`)asons `&O`)rder`^ by adjusting the user preferences in the Grotto.");
	output("`n`nPlease forward any problems to me on dragonprime.");
	output("`n`nThanks,`n`nDaveS");
	addnav("Village");
	addnav("Return to Village","village.php");
}
if ($op=="superuserwarning") {
	require_once("modules/masons/masons_suwarning.php");
	masons_suwarning();
}
if ($op=="warningsharing" || $op=="nonspecwarning") {
	masons_masonnav1();
	addnav("Masons To Discipline");
	addnav("Search Again","runmodule.php?module=masons&op=superuserwarning");
	$id = get_module_setting("disciplineid");
	$name = get_module_setting("disciplinename");	
	output("`^A warning letter has been sent to %s`^.",get_module_setting("disciplinename"));
	$subj = array("`\$Warning Letter `&From `&T`)he `&M`)asons");
	if ($op=="warningsharing"){
		$body = array("`&Dear %s`&,`n`nYou are receiving this letter as a `\$Warning`& that you are suspected of sharing secrets about `&T`)he `&O`)rder`&. If this behavior is found to be true, you will be dismissed.  This is the only warning you will receive.`n`nSincerely,`n`n `&N`)armyan`&",$name);
		debuglog("sent a Masons Order warning letter to $name for sharing Mason Information.");
		debuglog($session['user']['name'] . "`0 sent a Masons Order warning letter to $name`0 for sharing Mason Information.");	
	}else{
		$body = array("`&Dear %s`&,`n`nYou are receiving this letter as a `\$Warning`& that you are suspected of violating one of the rules of `&T`)he `&O`)rder`&. If this behavior is found to be true, you will be dismissed.  This is the only warning you will receive.`n`nSincerely,`n`n `&N`)armyan`&",$name);
		debuglog("sent a non-specific Masons Order letter to $name for sharing information.");
		debuglog($session['user']['name'] . "`0 sent a Masons Order warning letter to $name`0 for non-specific Mason violation.", $id);
	}
	require_once("lib/systemmail.php");
	systemmail($id,$subj,$body);
}
if ($op=="dismisssharing" || $op=="nonspecdismiss") {
	masons_masonnav1();
	addnav("Masons To Discipline");
	addnav("Search Again","runmodule.php?module=masons&op=superuserwarning");
	$id = get_module_setting("disciplineid");
	$name = get_module_setting("disciplinename");
	output("`^A dismissal letter has been sent to %s`^.",get_module_setting("disciplinename"));
	$subj = array("`\$Dismissal Notice`& from `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons");
	//If banning is allowed this adds one to the number of times dismissed
	$allprefs=unserialize(get_module_pref('allprefs','masons',$id));
	if ($dismisstime>0){
		$allprefs['dismisscount']=$allprefs['dismisscount']+1;
		set_module_pref('allprefs',serialize($allprefs),'masons',$id);
	}
	$allprefs=unserialize(get_module_pref('allprefs','masons',$id));
	//If banning is allowed and player has exceeded the allowed dismissals, triggers the banning
	if ($dismisstime>0 && $allprefs['dismisscount']>=$dismisstime){
		$allprefs['permaban']=1;
		set_module_pref('allprefs',serialize($allprefs),'masons',$id);
	}
	$allprefs=unserialize(get_module_pref('allprefs','masons',$id));
	if ($op=="dismisssharing"){
		//This sends a letter that informs player of PERMANENT ban
		if (($dismisstime>0)&&($dkban==0)) $body = array("`n`&D`)ear `^%s`&,`n`nYou have violated the first rule of `&T`)he `&O`)rder`& by sharing information about us.  We have removed your tattoo and revoked your membership.`n`n This ban is permanent. You will `\$NEVER`& be invited back into `&T`)he `&O`)rder`&. `n`n  My decision is non-negotiable.`n`nSincerely,`n`n`&N`)armyan`n",$session['user']['name']);
		//This sends a letter that informs player of Ban for a setting number of dks
		if ($dismisstime>0 && $dkban>0 && $allprefs['dismisscount']>=$dismisstime) $body = array("`n`&D`)ear `^%s`&,`n`nYou have violated the first rule of `&T`)he `&O`)rder`& by sharing information about us.  We have removed your tattoo and revoked your membership. `n`n This ban will be in effect until you have completed %s `@Dragon Kills`&. After you have completed them, you may one day get invited back into`&T`)he `&O`)rder`&. My decision is non-negotiable.`n`nSincerely,`n`n`&N`)armyan`n",$session['user']['name'],$dkban);
		//This sends a letter that informs the player of a simple dismissal
		if ($dismisstime==0||($dismisstime>0 && $dkban>0  && $allprefs['dismisscount']<$dismisstime)) $body = array("`n`&D`)ear `^%s`&,`n`nYou have violated the first rule of `&T`)he `&O`)rder`& by sharing information about us.  We have removed your tattoo and revoked your membership.  Perhaps you can join again some other day. My decision is non-negotiable.`n`nSincerely,`n`n`&N`)armyan`n",$session['user']['name']);
		debuglog("dismissed $name from Masons Order for sharing information.");
		debuglog($session['user']['name'] . "`0 dismissed $name`0 from the Masons Order for sharing Information");
	}else{
		//This sends a letter that informs player of PERMANENT ban
		if (($dismisstime>0)&&($dkban==0))$body = array("`n`&D`)ear `^%s`&,`n`nYou have violated the rules of `&T`)he `&O`)rder`&.  We have removed your tattoo and revoked your membership. `n`n This ban is permanent. You will `\$NEVER`& be invited back into `&T`)he `&O`)rder`&. `n`n  My decision is non-negotiable.`n`nSincerely,`n`n`&N`)armyan`n",$session['user']['name']);
		//This sends a letter that informs player of Ban for a setting number of dks
		if (($dismisstime>0)&&($dkban>0) && ($allprefs['dismisscount']>=$dismisstime))$body = array("`n`&D`)ear `^%s`&,`n`nYou have violated the rules of `&T`)he `&O`)rder`&.  We have removed your tattoo and revoked your membership.  `n`n This ban will be in effect until you have completed %s `@Dragon Kills`&. After you have completed them, you may one day get invited back into`&T`)he `&O`)rder`&. My decision is non-negotiable.`n`nSincerely,`n`n`&N`)armyan`n",$session['user']['name'],$dkban);
		//This sends a letter that informs the player of a simple dismissal
		if ($dismisstime==0 || ($dismisstime>0 && $dkban>0 && $allprefs['dismisscount']<$dismisstime))$body = array("`n`&D`)ear `^%s`&,`n`nYou have violated the rules of `&T`)he `&O`)rder`&.  We have removed your tattoo and revoked your membership.  Perhaps you can join again some other day. My decision is non-negotiable.`n`nSincerely,`n`n`&N`)armyan`n",$session['user']['name']);
		debuglog("dismissed $name from Masons Order for non-specific reason.");
		debuglog($session['user']['name'] . "`0 dismissed $name`0 from the Masons Order for a non-specific violation", $id);
	}
	require_once("lib/systemmail.php");
	systemmail($id,$subj,$body);
	$allprefs['masonmember']=0;
	$allprefs['tatpain']=0;
	$allprefs['benefit']=0;
	$allprefs['donated']=0;
	set_module_pref('allprefs',serialize($allprefs),'masons',$id);
	set_module_pref("masonnumber",0,"masons",$id);
	modulehook("masons-dismissalnotice");
	if (get_module_setting("newestid")==$id) set_module_setting("newestmember","Nobody");
}
if ($op=="enter") {
	require_once("modules/masons/masons_enter.php");
	masons_enter();
}

if ($op=="reviewdues"){
	masons_masonnav1();
	output("`&The T`)reasurer`& reviews the current policy regarding dues for `&T`)he `&O`)rder`&.`n`n");
	output("`7'Dues are to be paid every `b`&%s days`b`7.'`n`n",get_module_setting("duetime"));
	if ($allprefs['duestime']==0) output("'Today is the `b`&last day`b left in this cycle. New dues will be assessed tomorrow.'`n`n");
	elseif ($allprefs['duestime']==1) output("'There is currently `b`& one day`7`b left in this cycle until new dues will be assessed.'`n`n");
	else output("'There are currently `b`&%s days`7`b left in this cycle until new dues will be assessed.'`n`n",$allprefs['duestime']);
	if ($allprefs['duespaid']<$golddues) output("`7'The dues are `^%s gold`7 and you still owe `^%s gold`7.  Benefits cannot be accessed until you have paid all of your dues.'",$golddues,$golddues-$allprefs['duespaid']);
	else output("`7'Your current dues are paid in full.'`n`n");
}
if ($op=="duestopay"){
	masons_masonnav1();
	output("`&The T`)reasurer`& takes you to his office to review your dues.");
	output("`n`n`7'The dues are `^%s gold`7 and you still owe `^%s gold`7.",$golddues,$golddues-$allprefs['duespaid']);
	output("Benefits cannot be accessed until you have paid all of your dues.'");
	output("`n`n'How much would you like to contribute to your dues today?'");
	output("<form action='runmodule.php?module=masons&op=payoffdues' method='POST'><input name='duesp' id='duesp'><input type='submit' class='button' value='Pay Dues'></form>",true);
	addnav("","runmodule.php?module=masons&op=payoffdues");
}
if ($op=="payoffdues"){
	require_once("modules/masons/masons_payoffdues.php");
	masons_payoffdues();
}
if ($op=="chair") {
	$allprefs['benefit']=$allprefs['benefit']+1;
	set_module_pref('allprefs',serialize($allprefs));
	$turns=get_module_setting("turns");
	switch ($turns) {
		case 0: 
			$turngain=e_rand(0,1);
		break;
		case 1: 
			$turngain=1;
		break;
		case 2: 
			$turngain=e_rand(0,2);
		break;
		case 3: 
			$turngain=2;
		break;
		case 4: 
			$turngain=e_rand(1,3);
		break;
		case 5: 
			$turngain=3;
		break;
		case 6: 
			$turngain=e_rand(2,3);
		break;
		case 7: 
			$turngain=e_rand(0,4);
		break;

	}
	output("`&You are guided to the `@'Relaxation Lounge'`&.`n`n");
	if ($turngain==0) output("You have a nice time sitting in the chair, but nothing else happens.");
	else output("By using a special massage chair, you `@Gain %s Extra %s`&.`n`n",$turngain,translate_inline($turngain>1?"Turns":"Turn"));
	$session['user']['turns']+=$turngain;
	debuglog("gained $turngain extra turns as a Mason Benefit.");
	masons_masonbenefit1();
}
if ($op=="wealth") {
	if (get_module_setting("goldnone")>=e_rand(1,100)) $goldgain=0;
	else $goldgain=e_rand(get_module_setting("goldmin"),get_module_setting("goldmax"));
	$allprefs['benefit']=$allprefs['benefit']+1;
	set_module_pref('allprefs',serialize($allprefs));
	output("`&You are guided to the `&O`)rder `&C`)offers`&.`n`n");
	if ($goldgain==0) output("The `&T`)reasurer `&sadly informs you that the coffers are empty today.`n`n");
	else output("The `&T`)reasurer`& hands you `^%s Gold`&.`n`n",$goldgain);
	$session['user']['gold']+=$goldgain;
	debuglog("gained $goldgain gold as a Mason Benefit.");
	masons_masonbenefit1();
}
if ($op=="health") {
	if (get_module_setting("hpnone")>=e_rand(1,100)) $hpgain=0;
	else $hpgain=e_rand(get_module_setting("hpmin"),get_module_setting("hpmax"));
	$allprefs['benefit']=$allprefs['benefit']+1;
	set_module_pref('allprefs',serialize($allprefs));
	output("`&You are guided to the `&O`)rder `&A`)pothecary`&.`n`n");
	if ($hpgain==0) output("The `&A`)pothecary`& hands you a delicious elixir, but it doesn't do anything.  He shrugs and says `)'Yeah, sometimes that happens'`&.`n`n");
	else output("The `&A`)pothecary`& hands you a delicious elixir that gives you `\$%s %s`&.`n`n",$hpgain,translate_inline($hpgain>1?"Hitpoints":"Hitpoint"));
	$session['user']['hitpoints']+=$hpgain;
	debuglog("gained $hpgain hps as a Mason Benefit.");
	masons_masonbenefit1();
}
if ($op=="sparkle") {
	$allprefs['benefit']=$allprefs['benefit']+1;
	set_module_pref('allprefs',serialize($allprefs));
	output("`&You are guided to the `&O`)rder `&G`)emologist`&.`n`n");
	$gems=get_module_setting("gems");
	if ($gems==0) $gemgain=e_rand(0,1);
	elseif ($gems==1) $gemgain=1;
	elseif ($gems==2) $gemgain=e_rand(0,2);
	elseif ($gems==3) $gemgain=e_rand(1,2);
	elseif ($gems==4) $gemgain=e_rand(0,3);
	if ($gemgain==0) output("Unfortunately, there are no `%gems`& available today.");
	else output("Thanks to the work of several of the best `&M`)asons `&in the `@Q`3uarry `&you receive `^%s `%%s`&.",$gemgain,translate_inline($gemgain>1?"Gems":"Gem"));
	$session['user']['gems']+=$gemgain;
	debuglog("gained $gemgain gems as a Mason Benefit.");
	masons_masonbenefit1();
}
if ($op=="occult") {
	if (get_module_setting("occultnone")>=e_rand(1,100)) $occultgain=0;
	else $occultgain=e_rand(get_module_setting("occultmin"),get_module_setting("occultmax"));
	$allprefs['benefit']=$allprefs['benefit']+1;
	set_module_pref('allprefs',serialize($allprefs));
	output("`&You are guided to the `&L`)ibrary`&.`n`n");
	if ($occultgain==0) output("You study some of the books in the `&L`)ibrary`& but can't find anything that will help you.`n`n");
	output("With some study of the books in the `&L`)ibrary`&, you read a spell that gives you`\$ %s Favor`&.`n`n",$occultgain);
	$session['user']['deathpower']+=$occultgain;
	debuglog("gained $occultgain favor as a Mason Benefit.");
	masons_masonbenefit1();
}
if ($op=="charisma") {
	$allprefs['benefit']=$allprefs['benefit']+1;
	set_module_pref('allprefs',serialize($allprefs));
	output("`&You are guided to the `&O`)rder `&C`)osmetologist`&.`n`n");
	$charm=get_module_setting("charm");
	if ($charm==0) $charmgain=e_rand(0,1);
	elseif ($charm==1) $charmgain=1;
	elseif ($charm==2) $charmgain=e_rand(0,2);
	elseif ($charm==3) $charmgain=e_rand(1,2);
	elseif ($charm==4) $charmgain=e_rand(0,3);
	output("You apply a thick white cream all over your face.");
	if ($charmgain==0) output ("`n`nYou look like you're wearing white cream on your face.  It really doesn't do anything for you.");
	else output("You improve your `bCharm by %s`b.`n`n",$charmgain);
	$session['user']['charm']+=$charmgain;
	debuglog("gained $charmgain charm as a Mason Benefit.");
	masons_masonbenefit1();
}
if ($op=="travel") {
	$allprefs['benefit']=$allprefs['benefit']+1;
	set_module_pref('allprefs',serialize($allprefs));
	output("`&You are guided to the `&E`)xperimental `&L`)ab`&.`n`n");
	$travel=get_module_setting("travel");
	if ($travel==0) $travelgain=e_rand(0,1);
	elseif ($travel==1) $travelgain=1;
	elseif ($travel==2) $travelgain=e_rand(0,2);
	elseif ($travel==3) $travelgain=e_rand(1,2);
	elseif ($travel==4) $travelgain=e_rand(0,3);
	output("You are given one of `&T`)he `&O`)rder's`& newest discoveries. It's a `#salve`& that you apply to your legs.");
	if ($travelgain==0) output("`n`nYou apply the salve but you don't notice any effect.  Oh well!");
	else output("`n`nYou apply the salve and gain `#%s Extra %s`& for the day.`n`n",$travelgain,translate_inline($travelgain>1?"Travels":"Travel"));
	increment_module_pref("traveltoday",-$travelgain,"cities");
	debuglog("gained $travelgain travel as a Mason Benefit.");
	masons_masonbenefit1();
}
if ($op=="purehealth") {
	output("`&You are guided to the `&W`)ine `&R`)oom`&.`n`n");
	if (get_module_pref('potion', 'potions')>=5){
		output("You look at `&T`)he `&O`)rder's`& Special Select Bottles. They work well as `@Healing `!Potions`&, but you don't have any room to carry one.");
		output("`n`n The `&W`)inesmith`& shrugs and tells you that this won't count as a use of one of your benefits for today.`n`n");
		masons_masonnav1();
	}else{
		output("You are given one of `&T`)he `&O`)rder's`& Special Select Bottles. This will work as a `@Healing `!Potion`&.`n`n");
		if (get_module_setting("potionnone")>=e_rand(1,100)){
			output("You are about to put it in your backpack when you drop it and it shatters!!");
			output("`n`n The `&W`)inesmith`& gives you an evil look and accuses you of alcohol abuse.  Oh well.");
			debuglog("broke a healing potion trying to get a Mason Benefit.");
		}else{
			increment_module_pref('potion',1,'potions');
			debuglog("gained a healing potion as a Mason Benefit.");
		}
		$allprefs['benefit']=$allprefs['benefit']+1;
		set_module_pref('allprefs',serialize($allprefs));
		masons_masonbenefit1();
	}
}
if ($op=="goodevil"){
	$allprefs['benefit']=$allprefs['benefit']+1;
	set_module_pref('allprefs',serialize($allprefs));
	require_once("modules/masons/masons_goodevil.php");
	masons_goodevil();
}
if ($op=="alignevil") {
	$alignment=get_module_pref("alignment","alignment");
	if ($alignment<=get_module_setting("evilalign","alignment")) output("`&You walk down to the 'K`)itten `&P`)oking`&' room and get busy.`n`n  After an adequate amount of poking, you feel ");
	elseif(($alignment>get_module_setting("evilalign","alignment"))&&($alignment<get_module_setting("goodalign","alignment"))) output("`&You run over to the orphanage, snag some candy, and run back to `&T`)he `&O`)rder`&.`n`n  Yes, this definately makes you ");
	elseif($alignment>=get_module_setting("goodalign","alignment")) output("`&With a twinkle in your eye, you go outside, steal the blind man's pencils, and even drop some `Qslugs`& in so he thinks the pleasant 'plinking' sound is `^gold`& being dropped in. `n`n My, you really are ");
	if (get_module_setting("alignnone")>=e_rand(1,100)){
		output("`\$Evil`&! Unfortunately, nobody notices your deed so nothing happens to your alignment!");
		debuglog("tried to worsen alignment as a Mason Benefit but it wasn't noticed.");
	}else {
		output("`\$More Evil`&! Bwa ha ha ha!`n`n");
		increment_module_pref("alignment",-3,"alignment");
		debuglog("worsened alignment as a Mason Benefit.");
	}
	masons_masonbenefit1();
}
if ($op=="aligngood") {
	$alignment=get_module_pref("alignment","alignment");
	if ($alignment<=get_module_setting("evilalign","alignment")) output("`&You walk down to the 'K`)itten `&C`)uddling`&' room and get busy. `n`n The kittens purr and purr with your love!  You are a ");
	elseif(($alignment>get_module_setting("evilalign","alignment"))&&($alignment<get_module_setting("goodalign","alignment"))) output("`&After a stop at the `&C`)andy `&S`)toreroom`&, you take the candy over to see the smiling faces of the orphans.  `n`nYes, this definately makes you a ");
	elseif($alignment>=get_module_setting("goodalign","alignment")) output("`&Some intensive labor in the P`)encil `&F`)actory`&, you produce a very impressive %s pencils!`n`n You are a ",e_rand(10,100));
	output("`@Better Person`&! Awwwww!`n`n");
	if (get_module_setting("alignnone")>=e_rand(1,100)){
		output("Unfortunately, you end up stepping on the tail of a cat heading back to the `)M`&asons, thereby negating the benefit of the good deed you did! Oh well!");
		debuglog("tried to better alignment as a Mason Benefit but it didn't work.");
	}else{
		increment_module_pref("alignment",3,"alignment");
		debuglog("improved alignment as a Mason Benefit.");
	}
	masons_masonbenefit1();
}
if ($op=="alignnothing"){
	output("`&Yeah, you like the way you are.");
	output("Why mess with perfection?");
	output("`n`nJust so you know, this counts for a use of a benefit for today.`n`n");
	masons_masonbenefit1();
	debuglog("did nothing for their alignment as a Mason Benefit.");
}
if ($op=="afterlife"){
	$allprefs['benefit']=$allprefs['benefit']+1;
	set_module_pref('allprefs',serialize($allprefs));
	$torment=get_module_setting("torment");
	if ($torment==0) $tormentgain=e_rand(0,1);
	elseif ($torment==1) $tormentgain=1;
	elseif ($torment==2) $tormentgain=e_rand(0,2);
	elseif ($torment==3) $tormentgain=e_rand(1,2);
	elseif ($torment==4) $tormentgain=e_rand(0,3);
	output("`&You enter a dark room with a large mirror. In front of the mirror is a strange incantation. You read the incantation out loud. Suddenly `\$Ramius`& appears in the mirror!!`n`n`\$");
	if ($tormentgain==0) output("'I'm sorry I'm not able to respond to your call right now.  Please leave a message and I'll get back to you as soon as possible. Thanks!'`&`n`nDarn! You got the answering mirror.  Seems like you're not going to get anything out of this visit.");
	else{
		output("'Hello, `&M`)ason`\$. Your `&O`)rder`\$ has pleased me, and therefore I grant you `b%s Extra %s`b if you decide to visit me today.'",$tormentgain,translate_inline($torementgain>1?"Torments":"Torment"));
		output("`n`n`&The mirror fades back to your reflection and you leave the room.`n`n");
		$session['user']['gravefights']+=$tormentgain;
	}
	debuglog("gained $gravefights torments as a Mason Benefit.");
	masons_masonbenefit1();
}
if ($op=="dexterity"){
	if ($allprefs['defenseboost']==0){
		$allprefs['benefit']=$allprefs['benefit']+1;
		$allprefs['defenseboost']=1;
		set_module_pref('allprefs',serialize($allprefs));
		if (get_module_setting("defensenone")>=e_rand(1,100)){
			output("`&You walk over to the `&G`)ymnasium`& but nobody is able to help you.  It seems like you're not going to get a chance to improve your dexterity.");
			debuglog("tried to gain one defense as a Mason Benefit but didn't get it.");
		}else{
			output("`&You walk over to the `&G`)ymnasium`& and meet G`)irard `&T`)hibault`&, one of the greatest fencing masters in the land.");
			output("`n`nHe looks at your `%s and shows you a couple of tricks for defending yourself against attacks.`n`nYou gain `bOne Defense`b.",$session['user']['weapon']);
			$session['user']['defense']++;
			debuglog("gained one defense as a Mason Benefit.");
		}
		masons_masonbenefit1();
	}else{
		output("`&You walk over to the `&G`)ymnasium`& and chat with G`)irard `&T`)hibault`&.`n`n`7'Unfortunately I cannot teach you any more right now.");
		output("You would require much more intensive study to learn anything more from me. Perhaps you should stop by after you kill the `@Green Dragon`7.'`n`n`&You won't be charged a benefit for your visit.`n`n");
		masons_masonnav1();
	}
}
if ($op=="strength"){
	if ($allprefs['attackboost']==0){
		$allprefs['benefit']=$allprefs['benefit']+1;
		$allprefs['attackboost']=1;
		set_module_pref('allprefs',serialize($allprefs));
		if (get_module_setting("attacknone")>=e_rand(1,100)){
			output("`&You walk over to the `&G`)ymnasium`& but nobody is able to help you.  It seems like you're not going to get a chance to improve your strength.");
			debuglog("tried to gain one attack as a Mason Benefit but didn't get it.");
		}else{
			output("`&You walk over to the `&G`)ymnasium`& and meet `&R`)idolfe `&C`)apo `&F`)erro`&, one of the greatest fencing masters in the land.");
			output("`n`nHe looks at your `%s and shows you a couple of tricks for attacking with more efficiency.`n`nYou gain `bOne Attack`b.",$session['user']['weapon']);
			$session['user']['attack']++;
			debuglog("gained one attack as a Mason Benefit.");
		}
		masons_masonbenefit1();
	}else{
		output("`&You walk over to the `&G`)ymnasium`& and chat with `&R`)idolfe `&C`)apo `&F`)erro`&.`n`n`7'Unfortunately I cannot teach you any more right now.");
		output("You would require much more intensive study to learn anything more from me. Perhaps you should stop by after you kill the `@Green Dragon`7.'`n`n`&You won't be charged a benefit for your visit.`n`n");
		masons_masonnav1();
	}
}
if ($op=="exercise"){
	if ($allprefs['expboost']==0){
		$allprefs['benefit']=$allprefs['benefit']+1;
		$allprefs['expboost']=1;
		set_module_pref('allprefs',serialize($allprefs));
		if (get_module_setting("expnone")>=e_rand(1,100)){
			output("`&You get lost trying to find the training facility.  You don't gain any experience from today's benefit.");
			debuglog("tried to gain experience as a Mason Benefit but didn't get any.");
		}else{
			$expbonus=$session['user']['dragonkills']*e_rand(4,6);
			$expgain =(($session['user']['level']*e_rand(7,14))+$expbonus);
			output("`&In the `&B`)ack `&Y`)ard `&is a training facility. You have a great workout and gain `#%s Experience`&.`n`n",$expbonus);
			$session['user']['experience']+=$expgain;
			debuglog("gained $expgain exp as a Mason Benefit.");
		}
		masons_masonbenefit1();
	}else{
		output("`&When you arrive in the `&B`)ack `&Y`)ard`&, you take one look at the exercise equipment and decide there is no way you can tolerate another workout today.");
		output("`n`nYou'll be able to come back tomorrow.`n`n");
		output("`&You won't be charged a benefit for your visit.`n`n");
		masons_masonnav1();
	}
}
if ($op=="artistry") {
	if ($allprefs['incspecialty']==0){
		$allprefs['benefit']=$allprefs['benefit']+1;
		$allprefs['incspecialty']=1;
		set_module_pref('allprefs',serialize($allprefs));
		output("`&The resident `&M`)agical `&F`)airy`& taps you on the head with her `QWand`&.");
		if (get_module_setting("specnone")>=e_rand(1,100)){
			output("Her wand doesn't do anything, so she hits you harder with it.  It STILL doesn't work.  She shrugs and mutters something about how some people just aren't worthy.");
			debuglog("tried to increment specialty as a Mason Benefit but it didn't work.");
		}else{
			output("The magic surges through you, and you feel your magical art grow inside.`n`n`QYou Advance in your Specialty`&.");
			require_once("lib/increment_specialty.php");
			increment_specialty("`Q");
			output("`n");
			debuglog("gained incremented specialty as a Mason Benefit.");
		}
		masons_masonbenefit1();
	}else{
		output("`&The `&F`)airy `&looks at you and whacks you with her `QWand`&.`n`nNothing happens.`n`nShe hits you again.`n`nAnd again.");
		output("`n`nAnd again.`n`nShe looks at you and shrugs.`n`n`7'Oh yeah, this only works once a dragon kill.");
		output("Well, you won't be charged a benefit for your visit.'`n`n`&You give a weak smile, rub your head, and go on your merry way.`n`n");
		masons_masonnav1();
	}
}
if ($op=="newweapon") {
	$allprefs['benefit']=$allprefs['benefit']+1;
	set_module_pref('allprefs',serialize($allprefs));
	require_once("modules/masons/masons_newweapon.php");
	masons_newweapon();
}
if ($op=="newarmor"){
	$allprefs['benefit']=$allprefs['benefit']+1;
	set_module_pref('allprefs',serialize($allprefs));
	require_once("modules/masons/masons_newarmor.php");
	masons_newarmor();
}
if ($op=="givemason") {
	require_once("modules/masons/masons_givemason.php");
	masons_givemason();
}
if ($op=="givenonmason") {
	require_once("modules/masons/masons_givenonmason.php");
	masons_givenonmason();
}
if ($op=="givepeace") {
	$name = $allprefs['gifttoname'];
	if ($op2==1) $chance=get_module_setting("masonchance");
	else $chance=get_module_setting("nonmasonchance");
	output("`&You are guided to the `&M`)ason `&S`)pell `&R`)oom`&.`n`n");
	output("The resident `&M`)agic `&U`)ser`& attempts to cast a `@Spell of Energy`& on %s`&.",$name);
	if ($chance>=e_rand(1,100)){
		output("`n`nFailure!`n`n%s`& won't receive an `@extra turn`& from your attempt.`n`n",$name);
		debuglog("tried to give a ff to $name but the spell failed by the Mason Magic User.");
	}else{
		output("`n`nSuccess!`n`n%s`& receives `@1 Extra Turn`&.`n`n",$name);
		$id = $allprefs['gifttoid'];
		$subj = array("`&A Special Gift from T`)he `&M`)asons");
		$body = array("`&Dear %s`&,`n`nYou have received `@1 Extra Turn`& through an anonymous gift from one of the members of `&T`)he `&O`)rder`&.  Please enjoy them.`n`nSincerely,`n`n `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons",$name);
		require_once("lib/systemmail.php");
		systemmail($id,$subj,$body);
		$sql = "UPDATE ". db_prefix("accounts") . " SET turns=turns+1 WHERE acctid='$id'";
		db_query($sql);
		debuglog("gave 1 ff to $name as a Mason gift.");
	}
	masons_giftmasons();
	masons_masonbenefit1();
}
if ($op=="givewealth") {
	$name = $allprefs['gifttoname'];
	if ($op2==1) $chance=get_module_setting("masonchance");
	else $chance=get_module_setting("nonmasonchance");
	output("`&You are guided to the `&M`)ason `&S`)pell `&R`)oom`&.`n`n");
	output("The resident `&M`)agic `&U`)ser`& attempts to cast a `^Spell of Wealth`& on %s`&.",$name);
	if ($chance>=e_rand(1,100)){
		output("`n`nFailure!`n`n%s`& won't receive any `^gold`& from your attempt.`n`n",$name);
		debuglog("tried to give gold to $name but the spell failed by the Mason Magic User.");
	}else{
		output("`n`nSuccess!`n`n%s`& receives `^400 Extra Gold`&.`n`n",$name);
		$id = $allprefs['gifttoid'];
		$subj = array("`&A Special Gift from T`)he `&M`)asons");
		$body = array("`&Dear %s`&,`n`nYou have received `^400 Extra Gold`& through an anonymous gift from one of the members of `&T`)he `&O`)rder`&.  Please enjoy it.`n`nSincerely,`n`n `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons",$name);
		require_once("lib/systemmail.php");
		systemmail($id,$subj,$body);
		$sql = "UPDATE ". db_prefix("accounts") . " SET gold=gold+400 WHERE acctid='$id'";
		db_query($sql);
		debuglog("gave 400 gold to $name as a Mason gift.");
	}
	masons_giftmasons();
	masons_masonbenefit1();
}
if ($op=="givehealth") {
	$name = $allprefs['gifttoname'];
	if ($op2==1) $chance=get_module_setting("masonchance");
	else $chance=get_module_setting("nonmasonchance");
	output("`&You are guided to the `&M`)ason `&S`)pell `&R`)oom`&.`n`n");
	output("The resident `&M`)agic `&U`)ser`& attempts to cast a `\$Spell of Health`& on %s`&.",$name);
	if ($chance>=e_rand(1,100)){
		output("`n`nFailure!`n`n%s`& won't receive any `\$hitpoints`& from your attempt.`n`n",$name);
		debuglog("tried to give hitpoints to $name but the spell failed by the Mason Magic User.");
	}else{
		output("`n`nSuccess!`n`n%s`& receives `\$20 Extra Temporary Hitpoints`&.`n`n",$name);
		$id = $allprefs['gifttoid'];
		$subj = array("`&A Special Gift from T`)he `&M`)asons");
		$body = array("`&Dear %s`&,`n`nYou have received `\$20 Extra Temporary Hitpoints`& through an anonymous gift from one of the members of `&T`)he `&O`)rder`&.  Please enjoy them.`n`nSincerely,`n`n `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons",$name);
		require_once("lib/systemmail.php");
		systemmail($id,$subj,$body);
		$sql = "UPDATE ". db_prefix("accounts") . " SET hitpoints=hitpoints+20 WHERE acctid='$id'";
		db_query($sql);
		debuglog("gave 20 temporary hitpoints to $name as a Mason gift.");
	}
	masons_giftmasons();
	masons_masonbenefit1();
}
if ($op=="givesparkle") {
	$name = $allprefs['gifttoname'];
	if ($op2==1) $chance=get_module_setting("masonchance");
	else $chance=get_module_setting("nonmasonchance");
	output("`&You are guided to the `&M`)ason `&S`)pell `&R`)oom`&.`n`n");
	output("The resident `&M`)agic `&U`)ser`& attempts to cast a `%Sparkly Spell`& on %s`&.",$name);
	if ($chance>=e_rand(1,100)){
		output("`n`nFailure!`n`n%s`& won't receive a `%gem`& from your attempt.`n`n",$name);
		debuglog("tried to give a gem to $name but the spell failed by the Mason Magic User.");
	}else{
		output("`n`n%s`& receives `%One Gem`&.`n`n",$name);
		$id = $allprefs['gifttoid'];
		$subj = array("`&A Special Gift from T`)he `&M`)asons");
		$body = array("`&Dear %s`&,`n`nYou have received `5One Gem`& through an anonymous gift from one of the members of `&T`)he `&O`)rder`&.  Please enjoy it.`n`nSincerely,`n`n `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons",$name);
		require_once("lib/systemmail.php");
		systemmail($id,$subj,$body);
		$sql = "UPDATE ". db_prefix("accounts") . " SET gems=gems+1 WHERE acctid='$id'";
		db_query($sql);
		debuglog("gave one gem to $name as a Mason gift.");
	}
	masons_giftmasons();
	masons_masonbenefit1();
}
if ($op=="giveoccult") {
	$name = $allprefs['gifttoname'];
	if ($op2==1) $chance=get_module_setting("masonchance");
	else $chance=get_module_setting("nonmasonchance");
	output("`&You are guided to the `&M`)ason `&S`)pell `&R`)oom`&.`n`n");
	output("The resident `&M`)agic `&U`)ser`& attempts to cast a `\$Spell of the Occult`& on %s`&.",$name);
	if ($chance>=e_rand(1,100)){
		output("`n`nFailure!`n`n%s`& won't receive any `\$favor`& from your attempt.`n`n",$name);
		debuglog("tried to give favor to $name but the spell failed by the Mason Magic User.");
	}else{
		output("`n`n%s`& receives `\$25 Favor`&.`n`n",$name);
		$id = $allprefs['gifttoid'];
		$subj = array("`&A Special Gift from T`)he `&M`)asons");
		$body = array("`&Dear %s`&,`n`nYou have received `\$25 Favor`& through an anonymous gift from one of the members of `&T`)he `&O`)rder`&.  We hope you don't need it.`n`nSincerely,`n`n `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons",$name);
		require_once("lib/systemmail.php");
		systemmail($id,$subj,$body);
		$sql = "UPDATE ". db_prefix("accounts") . " SET deathpower=deathpower+25 WHERE acctid='$id'";
		db_query($sql);
		debuglog("gave 25 favor to $name as a Mason gift.");
	}
	masons_giftmasons();
	masons_masonbenefit1();
}
if ($op=="givecharisma") {
	$name = $allprefs['gifttoname'];
	if ($op2==1) $chance=get_module_setting("masonchance");
	else $chance=get_module_setting("nonmasonchance");
	output("`&You are guided to the `&M`)ason `&S`)pell `&R`)oom`&.`n`n");
	output("The resident `&M`)agic `&U`)ser`& attempts to cast a `bSpell of Charisma`b on %s`&.",$name);
	if ($chance>=e_rand(1,100)){
		output("`n`nFailure!`n`n%s`& won't receive any Charm from your attempt.`n`n",$name);
		debuglog("tried to give charm to $name but the spell failed by the Mason Magic User.");
	}else{
		output("`n`n%s`& receives `bOne Charm`b.`n`n",$name);
		$id = $allprefs['gifttoid'];
		$subj = array("`&A Special Gift from T`)he `&M`)asons");
		$body = array("`&Dear %s`&,`n`nYou have received `bOne Charm`b through an anonymous gift from one of the members of `&T`)he `&O`)rder`&.  You look Marvelous!`n`nSincerely,`n`n `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons",$name);
		require_once("lib/systemmail.php");
		systemmail($id,$subj,$body);
		$sql = "UPDATE ". db_prefix("accounts") . " SET charm=charm+1 WHERE acctid='$id'";
		db_query($sql);
		debuglog("gave 1 charm to $name as a Mason gift.");
	}
	masons_giftmasons();
	masons_masonbenefit1();
}
if ($op=="givetravel") {
	$name = $allprefs['gifttoname'];
	if ($op2==1) $chance=get_module_setting("masonchance");
	else $chance=get_module_setting("nonmasonchance");
	output("`&You are guided to the `&M`)ason `&S`)pell `&R`)oom`&.`n`n");
	output("The resident `&M`)agic `&U`)ser`& attempts to cast a `QSpell of Swiftness`& on %s`&.",$name);
	if ($chance>=e_rand(1,100)){
		output("`n`nFailure!`n`n%s`& won't receive any travel from your attempt.`n`n",$name);
		debuglog("tried to give 2 travel to $name but the spell failed by the Mason Magic User.");
	}else{
		output("`n`n%s`& receives `QTwo Extra Travels`&.`n`n",$name);
		$id = $allprefs['gifttoid'];
		$subj = array("`&A Special Gift from T`)he `&M`)asons");
		$body = array("`&Dear %s`&,`n`nYou have received `QTwo Extra Travels`& through an anonymous gift from one of the members of `&T`)he `&O`)rder`&.  We hope you don't need it.`n`nSincerely,`n`n `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons",$name);
		require_once("lib/systemmail.php");
		systemmail($id,$subj,$body);
		increment_module_pref("traveltoday",-2,"cities",$id);
		debuglog("gave 2 extra travels to $name as a Mason gift.");
	}
	masons_giftmasons();
	masons_masonbenefit1();
}
if ($op=="givepurehealth") {
	$name = $allprefs['gifttoname'];
	if ($op2==1) $chance=get_module_setting("masonchance");
	else $chance=get_module_setting("nonmasonchance");
	output("`&You are guided to the `&M`)ason `&S`)pell `&R`)oom`&.`n`n");
	output("The resident `&M`)agic `&U`)ser`& attempts to cast a `@Spell `!of `@Pure `!Health`& on %s`&.`n`n",$name);
	if ($chance>=e_rand(1,100)){
		output("Failure!`n`n%s`& won't receive a healing potion from your attempt.`n`n",$name);
		debuglog("tried to give a healing potion to $name but the spell failed by the Mason Magic User.");
	}else{
		$id = $allprefs['gifttoid'];
		$subj = array("`&A Special Gift from T`)he `&M`)asons");
		require_once("lib/systemmail.php");
		if (get_module_pref("potion","potions",$id) < 5) {
			output("Success!!`n`n%s`& receives a `@Healing `!Potion`&.`n`n",$name);
			$body = array("`&Dear %s`&,`n`nYou have received a free `@Healing `!Potion`& through an anonymous gift from one of the members of `&T`)he `&O`)rder`&.  We hope you don't need it.`n`nSincerely,`n`n `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons",$name);
			systemmail($id,$subj,$body);
			increment_module_pref("potion",1,"potions",$id);
			debuglog("gave a healing potion to $name as a Mason gift.");
		}else{
			output("%s`& already has `b`@5 Healing Potions`b`& so is unable to receive the benefit of this spell.",$name);
			output("`n`n The `&M`)agic `&U`)ser`& apologizes, but tells you that he has done everything that was required of him.`n`n");
			$body = array("`&Dear %s`&,`n`nOne of our `&M`)asons`& tried to send you a free `@Healing `!Potion`& as an anonymous gift. However, you already are carrying as many potions as you could hold, so we are unable to deliver it. We hope you never need to use any of them.`n`nSincerely,`n`n `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons",$name);
			systemmail($id,$subj,$body);
		}
	}
	masons_giftmasons();
	masons_masonbenefit1();
}
if ($op=="giveafterlife") {
	$name = $allprefs['gifttoname'];
	if ($op2==1) $chance=get_module_setting("masonchance");
	else $chance=get_module_setting("nonmasonchance");
	output("`&You are guided to the `&M`)ason `&S`)pell `&R`)oom`&.`n`n");
	output("The resident `&M`)agic `&U`)ser`& attempts to cast a `\$Spell of Deference to Ramius`& on %s`&.",$name);
	if ($chance>=e_rand(1,100)){
		output("`n`nFailure!`n`n%s`& won't receive any torments from your attempt.`n`n",$name);
		debuglog("tried to give 3 torments to $name but the spell failed by the Mason Magic User.");
	}else{
		output("`n`nSuccess!!`n`n%s`& receives `\$3 Torments`&.`n`n",$name);
		$id = $allprefs['gifttoid'];
		$subj = array("`&A Special Gift from T`)he `&M`)asons");
		$body = array("`&Dear %s`&,`n`nYou have received `\$3 Extra Torments`& through an anonymous gift from one of the members of `&T`)he `&O`)rder`&.  We hope you don't need them.`n`nSincerely,`n`n `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons",$name);
		require_once("lib/systemmail.php");
		systemmail($id,$subj,$body);
		$sql = "UPDATE ". db_prefix("accounts") . " SET gravefights=gravefights+3 WHERE acctid='$id'";
		db_query($sql);
		debuglog("gave 3 torments to $name as a Mason gift.");
	}
	masons_giftmasons();
	masons_masonbenefit1();
}
if ($op=="givedexterity") {
	require_once("modules/masons/masons_givedexterity.php");
	masons_givedexterity();
}
if ($op=="givestrength") {
	require_once("modules/masons/masons_givestrength.php");
	masons_givestrength();
}
if ($op=="accept") {
	output("`&You barely hesitate before you stand up proudly to accept the offer.`n`nA slow smile comes across `&N`)armyan`&'s face and she takes your hand and walks you down a series of long corridors.");
	output("Soon you are lost in the maze of hallways. `&N`)armyan`& opens a door and before you know what's happening, you find yourself sitting on a chair with your legs and hands bound.");
	output("`n`n`7'Unfortunately, one of the most painful costs of membership is the tattoo you noticed on my hand earlier. Unlike other tattoos, the ink we use is a little, how can I explain this?");
	output("Well, it's a little more `4painful`7 than usual.'`n`n`&A strange man with a VERY mean looking needle starts to jab deep into your hand. The pain is unbearable, and before you can scream, you lose consciousness.`n`n");
	addnav("`&T`)he `&T`)attoo","runmodule.php?module=masons&op=tattoo");
}
if ($op=="tattoo") {
	//adopted from Shannon Brown's Petra's Tattoo parlor
	output("`&After what may have been minutes or hours, you wake to find yourself lying on a luxurious bed. Disoriented and alarmed, you sit up and notice that `&N`)armyan `&is close by.");
	output("`n`n`7'The tattoo will take quite a while to heal. And for the next %s days when you wake, it will remind you of the price our `&O`)rder`7 requires for membership.",$allprefs['tatpain']);
	output("I offer no apologies for this. I suffered through the same, as did all other members. You will wear this mark with pride as it will remind you that you are one of the elite.");
	output("Another cost is that all `^your gold on hand`7 has been	accepted as a `^'donation'`7 to the `&O`)rder `&T`)attoo `&A`)rtist`7.'`n`n`7");
	output("`&You look down at your hand and notice the same tattoo on your hand that you saw on `&N`)armyan`&.`n`n`c`b`&S`)o`&M`b`c`n");
	output("`&She pauses and presses her hand firmly against your tattoo, sending a wave of`\$ unbearable pain`& through you. Before you lose consciousness once again, you hear `&N`)armyan`& whisper into your ear...");
	output("`n`n`7'Welcome to `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons`7.'`n`n`&The `\$pain`& reduces your `\$hitpoints to one`&.");
	output("You will not have the strength to return to the forest today and you `@Lose all your Turns`&.`n`nWill this be worth it?");
	increment_module_setting("masonnum",1);
	set_module_pref("masonnumber",get_module_setting("masonnum"));
	$allprefs['tatpain']=get_module_setting("healnumber")+ e_rand(0,5);
	$allprefs['masonmember']=1;
	$allprefs['duespaid']=0;
	$allprefs['duestime']=get_module_setting("duetime");
	set_module_pref('allprefs',serialize($allprefs));
	$session['user']['hitpoints']=1;
	$session['user']['turns']=0;
	$session['user']['gold']=0;
	modulehook ("masons-tattoo"); 
	addnav("`&T`)he `&P`)arlor","runmodule.php?module=masons&op=rules");
	debuglog("received a tattoo to the Masons and became a member of the order.");
}
if ($op=="rules") {
	require_once("modules/masons/masons_rules.php");
	masons_rules();
}
if ($op=="office") {
	output("`&You go back to `&N`)armyan's`& office.");
	output("She looks up at you and smiles.`n`n`7'Welcome back.");
	output("How can I help you today?'`n`n ");
	addnav("Order Issues");
	addnav("`&Discuss Benefits","runmodule.php?module=masons&op=benefits");
	addnav("`&Review Rules","runmodule.php?module=masons&op=rules");
	addnav("`&View Membership List","runmodule.php?module=masons&op=memberlist");
	addnav("`&Return To The Lounge","runmodule.php?module=masons&op=enter");
	addnav("Quit The Order");
	addnav("Quit","runmodule.php?module=masons&op=quit");
	addnav("Village");
	addnav("Return to Village","village.php");
}
if ($op=="benefits") {
	require_once("modules/masons/masons_benefits.php");
	masons_benefits();
}
if ($op=="donate") {
	addnav("The Order");
	addnav("`&Top Donors List","runmodule.php?module=masons&op=donorlist");
	addnav("`&Narmyan's Office","runmodule.php?module=masons&op=office");
	addnav("`&The Lounge","runmodule.php?module=masons&op=enter");
	addnav("Village");
	addnav("Return to Village","village.php");
	output("`&You go to the `&T`)reasurer`& to offer a donation.");
	output("`n`nHe takes out a ledger and looks at you happily.");
	output("`n`n`7'Although we can't offer any special gifts for donating, it does help maintain the fiscal strength of `&T`)he `&O`)rder`7.'");
	output("`n`n'How much will you be able to donate today?'");
	output("<form action='runmodule.php?module=masons&op=donategold' method='POST'><input name='donate' id='donate'><input type='submit' class='button' value='donate'></form>",true);
	addnav("","runmodule.php?module=masons&op=donategold");
}
if ($op=="donategold"){
	addnav("The Order");
	addnav("`&Donate More","runmodule.php?module=masons&op=donate");
	addnav("`&Top Donors List","runmodule.php?module=masons&op=donorlist");
	addnav("`&Narmyan's Office","runmodule.php?module=masons&op=office");
	addnav("`&The Lounge","runmodule.php?module=masons&op=enter");
	addnav("Village");
	addnav("Return to Village","village.php");
	$donate = httppost('donate');
	$max = $session['user']['gold'];
	if ($donate < 0) $donate = 0;
	if ($donate >= $max) $donate = ($max);
	if ($max < $donate) {
		output("`&The `&T`)reasurer `&looks at you bewildered.");
		output("`7'You know, you don't have that much gold!'`n`n");
	}else{
		$allprefs['donated']=$allprefs['donated']+$donate;
		set_module_pref('allprefs',serialize($allprefs));
		output("`&The `&T`)reasurer `&happily accepts your donation of`^ %s gold`& and records the amount for the T`)op `&D`)onors `&L`)ist`&.",$donate);
		output("`n`n`7'So far you've donated `^%s gold`7 to `&T`)he `&O`)rder`7.",$allprefs['donated']);
		output("Thank you very much!'");
		$session['user']['gold']-=$donate;
		debuglog("donated $donate gold to the Masons Order.");
	 }
}
if ($op=="donorlist") {
	require_once("modules/masons/masons_donorlist.php");
	masons_donorlist();
}
if ($op=="quit") {
	output("`&N`)armyan`& looks at you with a bit of concern.`n`n");
	output("`7'Very few members decide that they would rather not participate in our `&O`)rder`7.");
	output("I understand if you do not wish to remain with us, but I want you to consider your decision carefully.");
	output("We do not penalize members for leaving and you may find membership granted to you again in the future if you continue to show the qualities that we value.");
	output("There are no refunds of dues unfortunately and your	tattoo will be removed.'");
	output("`n`n'I would like to give you one more chance to reconsider.'");
	output("`n`n'Are you sure you want to quit?'");
	addnav("Yes, I want to Leave","runmodule.php?module=masons&op=quitforgood");
	addnav("No, Let me return to the Lounge","runmodule.php?module=masons&op=enter");
}
if ($op=="quitforgood") {
	output("`&You explain that you're really not into this kinda thing.");
	output("You're sorry, you've made a mistake.`n`n");
	output("`&N`)armyan`& doesn't seem too disappointed.");
	output("`n`n`7'Well, some people aren't really worthy to be here anyways.");
	output("You	just go on your merry way.");
	output("Perhaps if you ever get another chance, you'll reconsider.'");
	addnav("Village");
	addnav("Return to Village","village.php");
	$allprefs['offermember']=0;
	$allprefs['masonmember']=0;
	$allprefs['tatpain']=0;
	$allprefs['benefit']=0;
	$allprefs['donated']=0;
	$allprefs['duespaid']=0;
	$allprefs['duestime']=get_module_setting("duetime");
	set_module_pref('allprefs',serialize($allprefs));
	set_module_pref("masonnumber",0);
	if (get_module_setting("newestid")==$session['user']['acctid']) set_module_setting("newestmember","Nobody");
	debuglog("voluntarily quit the Masons Order.");
	modulehook("masons-dismiss");
}
if ($op=="memberlist") {
	require_once("modules/masons/masons_memberlist.php");
	masons_memberlist();
}
if ($op=="decline") {
	output("`&You explain that you're really not into this kinda thing.");
	output("You're sorry, you've made a mistake.`n`n");
	output("`&N`)armyan`& doesn't seem too disappointed.");
	output("`n`n`7'Well, some people aren't really worthy to be here anyways.");
	output("You just go on your merry way.");
	output("Perhaps if you ever get another chance, you'll reconsider.'");
	addnav("Village");
	addnav("Return to Village","village.php");
	$allprefs['offermember']=0;
	set_module_pref('allprefs',serialize($allprefs));
	debuglog("declined membership into the Masons Order.");
}
page_footer();
?>