<?php
function masons_enter(){
	global $session;
	$allprefs=unserialize(get_module_pref('allprefs'));
	$allprefs['visited']=0;
	set_module_pref('allprefs',serialize($allprefs));
	$allprefs=unserialize(get_module_pref('allprefs'));
	$dksincego=$allprefs['dksincego'];
	$dismisstime=get_module_setting("dismisstime");
	$dkban=get_module_setting("dkban");
	$golddues=get_module_setting("dues");
	if (($session['user']['superuser'] & SU_EDIT_USERS) && $allprefs['superspiel']==0) {
		output("`^`b`cSuperuser Notice`b`c`n");
		output("`&I notice that you are a `^Superuser`&.");
		output("If you would like access to the `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons`& without being identified as a member, please indicate so.");
		output("This will give you access to monitor discussions in the O`)rder `&L`)ounge `&without appearing on the membership roll.");
		output("`n`nPlease Choose:");
		output("`n`n1. Turn On Superuser Access");
		output("`n2. Do NOT Turn On Superuser Access (allows normal play)");
		output("`n`n(Note: these settings may be adjusted in the Grotto.)`n`n");
		addnav("Superuser Options");
		addnav("`^1. Superuser On","runmodule.php?module=masons&op=superon");
		addnav("`^2. Superuser Off","runmodule.php?module=masons&op=superoff");
		$allprefs['superspiel']=1;
		set_module_pref('allprefs',serialize($allprefs));
	}elseif($allprefs['offermember']==1 && $dismisstime>0 && $allprefs['permaban']==1 && ($dkban==0||$allprefs['dksinceban']<=$dkban)) {
		addnav("Return to Village","village.php");
		output("`7'I'm very sorry.");
		output("There has been a mistake.");
		output("You have had enough chances to be a part of our organization.");
		output("We expect excellence in our members. You have been dismissed from `&T`)he `&O`)rder`7 ");
		debuglog("was turned away from Masons Order because of prior dismissal.");	
		if ($dkban==0) output("`bPermanently`b.'`n`n");
		elseif ($dkban>0) output ("until you kill the `@Green Dragon`7`b %s more times`b.'`n`n",$dkban-$allprefs['dksinceban']);
		output("'I apologize for your accidental invitation.'`n`n");
		$allprefs['offermember']=0;
		set_module_pref('allprefs',serialize($allprefs));
	}elseif($allprefs['offermember']==1){
		output("`&After a somewhat prolonged search you find a nondescript building tucked away in a back alley. You look up to notice that this is a huge mansion, but the subtle architecture makes it seem less imposing.");
		output("You step back and the thought crosses your mind that it may even be larger than the palace itself. `n`n With a small tremor, you pull out your `&I`)nvitation `&S`)croll`& and approach the door.`n`n");
		output("`&Before you even get a chance to knock, you are greeted at the door by a beautiful woman wearing a flowing black robe.");
		output("She guides you to a little tiny side room and has you sit down on a large cushioned chair. You take a moment to look around at her stark office.");
		output("`n`nOn a shelf behind her is a miniature replica of a `)Block of Stone `&similar to the ones you worked on in `@T`3he `@Q`3uarry`&.");
		output("You notice a subtle tattoo on her wrist with beautiful calligraphy. After staring at it for several seconds, you decipher that it says:`n`n");
		output("`c`b`&S`)o`&M`b`c`n Before you get a chance to ask any questions, she catches your gaze.`n`n`7'Welcome,`^ %s`7.",$session['user']['name']);
		output("I see you've noticed my tattoo. I'll explain that to you in good time. Perhaps introductions are in order though.");
		output("My name is `&N`)armyan`7. I am the `&H`)ead `&M`)istress `7of `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons`7.'");
		output("`n`n `&You reach to show her the `&I`)nvitation `&S`)croll`& you received in `@T`3he `@Q`3uarry`& but she shakes her head softly.");
		output("`7'You don't need that. I know who you are. You are here because you have received that invitation to join our `&O`)rder`7 by your show of expertise; a trait our `&O`)rder`7 holds in the highest regard.'`n`n");
		output("`&Although you already knew this information, you smile at the confirmation.`n`n`7'I would like to tell you more, but due to the nature of our organization, all I can do at this point is offer you membership.");
		if ($golddues<=0) output("There are no fees or dues for membership. But to be honest, there are other costs.");
		else{
			output("There are dues for membership. You will need to contribute `^%s gold`7 to the society to receive any benefits.",$golddues);
			output("Payment is required every `b`&%s days`b`7. In addition, there are other costs.",get_module_setting("duetime"));
		}
		output("You will learn about those in good time.'`n`n'This offer will not come often, and once you decline, only your expertise in `@T`3he `@Q`3uarry`7 will bring another chance your way.");
		output("`n`n It's time for you to decide. Would you like to join?'");
		addnav("Yes, I will `&J`)oin","runmodule.php?module=masons&op=accept");
		addnav("No, I will `&N`)ot `&J`)oin","runmodule.php?module=masons&op=decline");
	}elseif ($allprefs['masonmember']==1 || get_module_pref("supermember")==1){
		output("`&You enter into the L`)ounge`& area and have a sip of wine with other members.`n`n");
		output("`7`c'Please welcome the newest member to our `&O`)rder`7, %s.'`n`n`c",get_module_setting("newestmember"));
		require_once("lib/commentary.php");
		addcommentary();
		viewcommentary("masons","`&S`)peak `&F`)reely`&, you are amongst friends.",20,"says");
		addnav("The Order");
		addnav("`&Narmyan's Office","runmodule.php?module=masons&op=office");
		addnav("`&How Benefits Work","runmodule.php?module=masons&op=benefits");
		addnav("`&Membership List","runmodule.php?module=masons&op=memberlist");
		addnav("Donations");
		addnav("`&Donate to the Order","runmodule.php?module=masons&op=donate");
		addnav("`&Top Donors List","runmodule.php?module=masons&op=donorlist");
		if ($golddues>0) {
			addnav("Dues");
			addnav("`&Review Dues","runmodule.php?module=masons&op=reviewdues");
		}
		modulehook ("masons-enter"); 
		if ($allprefs['duespaid']<$golddues) addnav("`&Pay Dues","runmodule.php?module=masons&op=duestopay");
		else{
			$dragonkills=$session['user']['dragonkills'];
			$dkstart=get_module_setting("dkstart");
			$mturns=get_module_setting("mturns");
			$mgold=get_module_setting("mgold");
			$mhps=get_module_setting("mhps");
			$mgems=get_module_setting("mgems");
			$mfavor=get_module_setting("mfavor");
			$mcharm=get_module_setting("mcharm");
			$mtrav=get_module_setting("mtrav");
			$mhealpot=get_module_setting("mhealpot");
			$malign=get_module_setting("malign");
			$mtorment=get_module_setting("mtorment");
			$mimpdef=get_module_setting("mimpdef");
			$mimpatk=get_module_setting("mimpatk");
			$mexp=get_module_setting("mexp");
			$mspecialty=get_module_setting("mspecialty");
			$mnweapon=get_module_setting("mnweapon");
			$mnarmor=get_module_setting("mnarmor");
			$masongivemason=get_module_setting("masongivemason");
			$masongivenonmason=get_module_setting("masongivenonmason");
			addnav("Membership Benefits");
			if ((($dkstart==0&&$dragonkills>=$mturns) || ($dksincego>=$mturns))&&($mturns>=0)) addnav("`@Chair","runmodule.php?module=masons&op=chair");
			if ((($dkstart==0&&$dragonkills>=$mgold) || ($dksincego>=$mgold))&&($mgold>=0)) addnav("`^Wealth","runmodule.php?module=masons&op=wealth");
			if ((($dkstart==0&&$dragonkills>=$mhps) || ($dksincego>=$mhps))&&($mhps>=0)) addnav("`&Health","runmodule.php?module=masons&op=health");
			if ((($dkstart==0&&$dragonkills>=$mgems) || ($dksincego>=$mgems))&&($mgems>=0)) addnav("`%Sparkle","runmodule.php?module=masons&op=sparkle");
			if ((($dkstart==0&&$dragonkills>=$mfavor) || ($dksincego>=$mfavor))&&($mfavor>=0)) addnav("`\$Occult","runmodule.php?module=masons&op=occult");
			if ((($dkstart==0&&$dragonkills>=$mcharm)|| ($dksincego>=$mcharm))&&($mcharm>=0)) addnav("`&Charisma","runmodule.php?module=masons&op=charisma");
			if ((is_module_active('cities'))&&(($dkstart==0&&$dragonkills>=$mtrav)|| ($dksincego>=$mtrav))&&($mtrav>=0)) addnav("`QTravel","runmodule.php?module=masons&op=travel");
			if ((is_module_active('potions'))&&(($dkstart==0&&$dragonkills>=$mhealpot)|| ($dksincego>=$mhealpot))&&($mhealpot>=0)) addnav("`&`@Pure `!Health","runmodule.php?module=masons&op=purehealth");
			if ((is_module_active('alignment'))&&(($dkstart==0&&$dragonkills>=$malign)|| ($dksincego>=$malign))&&($malign>=0)) addnav("`@Good `^& `\$Evil","runmodule.php?module=masons&op=goodevil");
			if ((($dkstart==0&&$dragonkills>=$mtorment)|| ($dksincego>=$mtorment))&&($mtorment>=0)) addnav("`\$Afterlife","runmodule.php?module=masons&op=afterlife");
			if ((($dkstart==0&&$dragonkills>=$mimpdef)|| ($dksincego>=$mimpdef))&&($mimpdef>=0)) addnav("`2Dexterity","runmodule.php?module=masons&op=dexterity");
			if ((($dkstart==0&&$dragonkills>=$mimpatk)|| ($dksincego>=$mimpatk))&&($mimpatk>=0)) addnav("`@Strength","runmodule.php?module=masons&op=strength");
			if ((($dkstart==0&&$dragonkills>=$mexp)|| ($dksincego>=$mexp))&&($mexp>=0)) addnav("`&`%Exercise","runmodule.php?module=masons&op=exercise");
			if ((($dkstart==0&&$dragonkills>=$mspecialty)|| ($dksincego>=$mspecialty))&&($mspecialty>=0)) addnav("`QAristry","runmodule.php?module=masons&op=artistry");
			if ((($dkstart==0&&$dragonkills>=$mnweapon)|| ($dksincego>=$mnweapon))&&($mnweapon>=0)) addnav("`^New `#Weapon","runmodule.php?module=masons&op=newweapon");
			if ((($dkstart==0&&$dragonkills>=$mnarmor)|| ($dksincego>=$mnarmor))&&($mnarmor>=0)) addnav("`6New `#Armor","runmodule.php?module=masons&op=newarmor");
			if (($dkstart==0&&$dragonkills>=$masongivemason)|| ($dksincego>=$masongivemason)) {
				addnav("Giving to Others");
				addnav("Give to Other `&Masons","runmodule.php?module=masons&op=givemason");
			}
			if ((($dkstart==0&&$dragonkills>=$masongivenonmason)|| ($dksincego>=$masongivenonmason))||(($dkstart==0&&$dragonkills>=$masongivemason)|| ($dksincego>=$masongivemason))){
				addnav("Giving to Others");
				addnav("Give to Non-Masons","runmodule.php?module=masons&op=givenonmason");
			}
		}
		addnav("Quit The Order");
		addnav("`&Quit","runmodule.php?module=masons&op=quit");
		addnav("Village");
		addnav("`&Return to Village","village.php");
		if ($session['user']['superuser'] & SU_EDIT_USERS) {
			addnav("Superuser");
			addnav("`^Superuser Warnings","runmodule.php?module=masons&op=superuserwarning");
		}
	}else{
		output("`@You have somehow stumbled by a strange building and you knock on the door.");
		output("Nobody answers, so you decide to leave.");
		addnav("`&Return to Village","village.php");
	}
}
?>