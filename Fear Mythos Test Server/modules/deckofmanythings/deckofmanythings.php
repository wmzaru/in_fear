<?php
global $session;
$op = httpget('op');
if ($op!="superuser"){
	$session['user']['specialinc']="module:deckofmanythings";
	page_header("The Deck of Many Things");
	output("`n`c`b`^The `\$Deck `^of `\$Many `^Things`c`b");
	output("`#`n");
}
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
	$allprefse=unserialize(get_module_pref('allprefs',"deckofmanythings",$id));
	if ($allprefse['decktempdefense']==""){
		$allprefse['decktemparmor']="";
		$allprefse['decktempdefense']=0;
		set_module_pref('allprefs',serialize($allprefse),'deckofmanythings',$id);
	}
	if ($subop!='edit'){
		$allprefse=unserialize(get_module_pref('allprefs',"deckofmanythings",$id));
		$allprefse['deckcheck']= httppost('deckcheck');
		$allprefse['monsternum']=httppost('monsternum');
		$allprefse['deckseduced']=httppost('deckseduced');
		$allprefse['decktemparmor']=httppost('decktemparmor');
		$allprefse['decktempdefense']=httppost('decktempdefense');
		$allprefse['deckpain']=httppost('deckpain');
		set_module_pref('allprefs',serialize($allprefse),'deckofmanythings',$id);
		output("Allprefs Updated`n");
		$subop="edit";
	}
	if ($subop=="edit"){
		require_once("lib/showform.php");
		$form = array(
			"Deck of Many Things,title",
			"deckcheck"=>"Used this newday?,bool",
			"monsternum"=>"What monster did they fight last?,enum,1,The Gypsy,2,The Wizard of Yendor,3,A Foocubus,4,Erinys,5,Asmodeus,6,Vampire",
			"deckseduced"=>"Have they been seduced by the Foocubus?,bool",
			"decktemparmor"=>"What armor do they normally have?,text",
			"decktempdefense"=>"What is the defense value of the armor?,int",
			"deckpain"=>"Days left of pain?,range,0,5,1",
		);
		$allprefse=unserialize(get_module_pref('allprefs',"deckofmanythings",$id));
		rawoutput("<form action='runmodule.php?module=deckofmanythings&op=superuser&userid=$id' method='POST'>");
		showform($form,$allprefse,true);
		$click = translate_inline("Save");
		rawoutput("<input id='bsave' type='submit' class='button' value='$click'>");
		rawoutput("</form>");
		addnav("","runmodule.php?module=deckofmanythings&op=superuser&userid=$id");
	}
}
if ($op=="deckplaygold"){
	$session['user']['gold']-=400;
	output("You hand over your `^400 Gold`# and wonder if this was the right idea.");
	output("`n`nBefore you have a chance to ask for your money back, the `%g`^y`%p`^s`%y`# fans out the deck.`n`nYou slowly reach and pick a card...");
	addnav("Draw a Card","runmodule.php?module=deckofmanythings&op=carddraw");
}
if ($op=="deckplaygem"){
	$session['user']['gems']--;
	output("You hand over your `%One Gem`# and wonder if this was the right idea.");
	output("`n`nBefore you have a chance to ask for your gem back, the `%g`^y`%p`^s`%y`# fans out the deck.`n`nYou slowly reach and pick a card...");
	addnav("Draw a Card","runmodule.php?module=deckofmanythings&op=carddraw");
}
if ($op=="deckplaycharm"){
	$session['user']['charm']-=2;
	increment_module_setting("gypsycharm",2);
	if (get_module_setting("gypsycharm")>=31) set_module_setting("gypsycharm",0); 
	$gypsycharm=get_module_setting("gypsycharm");
	output("You take the potion from the old `%g`^y`%p`^s`%y`# and slowly drink it down.`n`n  You feel your `&charm`# lower to `&%s`# and hers go up to `&%s`#.  You look at her and think",$session['user']['charm'],$gypsycharm);
	if ($gypsycharm<11) output("`@'Wow... too bad she didn't take more than 2 points. She is STILL really ugly.'");
	if ($gypsycharm>=11 && $gypsycharm<21) output("`@'Well, she's not the ugliest `%g`^y`%p`^s`%y`@ I've ever seen.'");
	if ($gypsycharm>=21) output("`@'Actually, for an `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^a`%n`@, she's not really that bad looking.'");
	output("`#`n`nYou wonder if this was the right idea.");
	output("`n`nBefore you have a chance to change your mind, the `%g`^y`%p`^s`%y`# fans out the deck.`n`nYou slowly reach and pick a card...");
	addnav("Draw a Card","runmodule.php?module=deckofmanythings&op=carddraw");
}
if ($op=="carddraw"){
	require_once("modules/deckofmanythings/deckofmanythings_carddraw.php");
	deckofmanythings_carddraw();
}
if ($op=="gypsyattack"){
	output("Showing how brave you are, you decide to attack the `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^a`%n`#.");
	output("She looks at you stunned at the fact that you're drawing your weapon and getting ready to fight her.`n`n`%'Okay, we can fight.  No problem.  However, before we start, I get to cast a curse on you.'`n`n`#You suddenly realize that yes, that is one of the rules, so you");
	output("wait patiently for her to curse you.`n`nShe gives you one of the meanest hairiest eyeball `\$Evil Eyes`# you've ever seen and shiver as the curse takes hold of you.`n`n`%'There ya go! Let's Rumble!'`n`n");
	output("`3(Oh by the way, you `4lose 1/2 your hitpoints`3 just to make it a little more fair.)`n`n");	
	$session['user']['hitpoints']*=.5;
	apply_buff('evileye',array(
		"name"=>"`\$The EVIL Eye",
		"rounds"=>10,
		"wearoff"=>"`\$The Curse of the `%Gypsy`\$ wears off.",
		"atkmod"=>.9,
		"defmod"=>.9,
		"roundmsg"=>"`#That was ONE mean hairy eyeball `\$Evil Eye`# that Gypsy gave you!",
	));
	$allprefs=unserialize(get_module_pref('allprefs'));
	$allprefs['monsternum']=1;
	set_module_pref('allprefs',serialize($allprefs));
	addnav("`%O`^l`%d G`^y`%p`^s`%y`\$ Fight!","runmodule.php?module=deckofmanythings&op=attack");
}
if($op=="deckoracle"){
	require_once("modules/deckofmanythings/deckofmanythings_deckoracle.php");
	deckofmanythings_deckoracle();
}
if ($op=="payment"){
	require_once("modules/deckofmanythings/deckofmanythings_payment.php");
	deckofmanythings_payment();
}
if ($op=="deckwheel"){
	require_once("modules/deckofmanythings/deckofmanythings_deckwheel.php");
	deckofmanythings_deckwheel();
}
if ($op=="deckslayer"){
	require_once("lib/titles.php");
	require_once("lib/names.php");
	$newtitle = "`b`\$Slayer`b";
	$newname = change_player_title($newtitle);
	$session['user']['title'] = $newtitle;
	$session['user']['name'] = $newname;
	$session['user']['specialinc'] = "";
	output("You carry your new title with pride and return to the `@forest`# looking for more adventure.`n`n");
	addnav("Back to the Forest","forest.php");
}
if ($op=="decktower"){
	output("It seems like a never ending walk up the stairs to the tower, but you persevere.");
	output("`n`nAt the top is a tall door with ancient words written across the header:`n`n`c`b`qSanctum `)of `qStrigoi`c`b`n`#");
	output("You decide maybe it's not a good idea to enter and as you're about to turn to leave, the door creaks open with an unlikely sense of timing that makes you sure that it is not accidental. You feel a breath of wind");
	output("across your neck... or was that wings?`n`n");
	addnav("Continue","runmodule.php?module=deckofmanythings&op=decktencounter");
}
if ($op=="decktencounter"){
	output("A voice behind you speaks into your ear.`n`n`Q'Why would you enter my house?  Do you wish to steal from me? Or perhaps to murder me while I sleep?  Strange, I do not see a wooden stake in your hands.  How do you plan");
	output("on defeating me?'`n`n`#You turn to find yourself standing before a creature dressed in black.  A cloak hangs from his shoulders giving him the appearance of wings.  An `\$o`Qr`^n`@a`#t`!e`# necklace fastens the cloak around his neck.`n`nThen as you stare at the man,");
	output("clarity dawns upon you.   Before you stands one of the darkest creatures to curse the land.  You have brought upon the kingdom a `\$V`Qa`\$m`Qp`\$i`Qr`\$e`#!");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$allprefs['monsternum']=6;
	set_module_pref('allprefs',serialize($allprefs));
	addnav("Fight the `\$V`Qa`\$m`Qp`\$i`Qr`\$e","runmodule.php?module=deckofmanythings&op=attack");	
}
if ($op=="deckjoke1"){
	output("`n`#You begin the joke with the best of your skills...`n`n`@Okay, so this man walks into his psychiatrist's office.  He sits down at the desk of the psychiatrist and starts to list his problems.`n`n");
	output("`6'I'm a Teepee.'`n`n'I'm a Wigwam.'`n`n'I'm a Teepee.'`n`n'I'm a Wigman.'`n`n`@The	psychiatrist stops the man and says `5'I think I can help you.  I know what your problem is.'`n`n`6'What is is doc?'`n`n`5'You're too tense!'");
	addnav("Continue","runmodule.php?module=deckofmanythings&op=deckjokeend");
}
if ($op=="deckjoke2"){
	output("`n`#You begin the joke with the best of your skills...`n`n`@Why do ducks have webbed feet?`n`&To stamp out fires.`n`n`@Why do elephants have flat feet?`n`&To stamp out burning ducks!");
	addnav("Continue","runmodule.php?module=deckofmanythings&op=deckjokeend");
}
if ($op=="deckjoke3"){
	output("`n`#You begin the joke with the best of your skills...`n`n`@The local church looks to hire a new Bell Ringer.  A `Qhunchback`@ applies and is warned of the dangers involved in the job - mainly, that of slipping and falling to one's ");
	output("death while bell ringing. `n`nThe applicant seems unimpressed by this, and explains that he comes from a long line of bell ringers, and that his family uses a special bell ringing technique. The priest, eager to see this, asks him to ");
	output("audition. `n`nThe `Qhunchback`@ goes up to a large bell perched high in the tower, pulls it towards him, and smashes his forehead into it to make it sound. Dazed from the impact, he stumbles and falls from the tower to his death below.");
	output("`n`n The priest climbs down to find a crowd gathered and a policeman who says, `%'I see a hunchback fell from your bell tower - do you know who he is?' `@The priest replies, `^'No, but his face rings a bell ...'");
	addnav("Continue","runmodule.php?module=deckofmanythings&op=deckjokeend");
}
if ($op=="deckjokeend"){
	output("`n`#The `)crows `#don't respond for what seems like eternity.`n`n");
	switch(e_rand(1,3)){
		case 1:	
			output("Suddenly, the `)crows`# start laughing uncontrollably.  You feel elated by their laughter and you gain `@4 extra turns`#!`n`n");
			$session['user']['turns']+=4;
		break;
		case 2:
			output("A couple of `)crows`# laugh politely, but, to be honest, your joke really didn't tickle their funny bones.  They dismiss you and send you on your way back to the `@forest`#.");
		break;
		case 3:
			output("`@Crickets`# start to chirp.  The `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^a`%n`# loses consciousness.`n`nThe `)crows`# were not impressed.  You find yourself depressed and");
			if ($session['user']['turns']>2) {
				output("you `@Lose Two Turns`#.");
				$session['user']['turns']-=2;
			}else{
				output("you `@Lose All your remaining turns`#.");	
				$session['user']['turns']=0;
			}
		break;
	}
	output("`n`n");
	$session['user']['specialinc'] = "";
	addnav("Back to the Forest","forest.php");
	addnews("%s`^ drew the `\$Judgement Card`^.",$session['user']['name']);
}
if ($op=="deckwish"){
	$session['user']['gold']-=1000;
	output("`%'Okay, what would you like to `@w`^i`&s`%h for?'`n`n");
	output("`^1.  15,000 Gold`n`%2. 30 Gems`n`@3. 10 Extra Turns`n`#4. Invulnerability for 20 Rounds`&`n5. An Experience Boon`n`Q6. 12 Charm`n");
	addnav("`^1. Gold","runmodule.php?module=deckofmanythings&op=deckwgold");
	addnav("`%2. Gems","runmodule.php?module=deckofmanythings&op=deckwgems");
	addnav("`@3. Turns","runmodule.php?module=deckofmanythings&op=deckwturns");
	addnav("`#4. Invulnerability","runmodule.php?module=deckofmanythings&op=deckwinvul");
	addnav("`&5. Experience","runmodule.php?module=deckofmanythings&op=deckwexp");	
	addnav("`Q6. Charm","runmodule.php?module=deckofmanythings&op=deckwcharm");	
	if (get_module_setting("givepermhp")==1){
		output("`\$7. 8 Permanent Hitpoints");
		addnav("`\$7. Permanent Hitpoints","runmodule.php?module=deckofmanythings&op=deckwpermhp");
	}	
}
if ($op=="deckwgold"){
	output("Before you even speak your wish, `^15,000 Gold`# appears at your feet. `n`nWith a wicked grin, you collect it all and wave to the `%g`^y`%p`^s`%y`# as you go to the bank!`n`n");
	$session['user']['gold']+=15000;
	$session['user']['specialinc'] = "";
	debuglog("wished for 15,000 gold from the Deck of Many Things.");
	addnav("Back to the Forest","forest.php");
}
if ($op=="deckwgems"){
	output("Before you even speak your wish, `%30 Gems`# appears at your feet. `n`nWith a wicked grin, you collect it all and wave to the `%g`^y`%p`^s`%y`# as you go to the bank!`n`n");
	$session['user']['gems']+=30;
	$session['user']['specialinc'] = "";
	debuglog("wished for 30 gems from the Deck of Many Things.");
	addnav("Back to the Forest","forest.php");
}
if ($op=="deckwturns"){
	output("Before you even speak your wish, you feel a surge of energy throughout your body. You gain `@10 turns`#.`n`nWith a wicked grin, wave to the `%g`^y`%p`^s`%y`# as you head back to the `@forest`#!`n`n");
	$session['user']['turns']+=10;
	$session['user']['specialinc'] = "";	
	debuglog("wished for 10 extra turns from the Deck of Many Things.");
	addnav("Back to the Forest","forest.php");
}
if ($op=="deckwinvul"){
	output("Before you even speak your wish, you realize that NOBODY can touch you. You develop that flashy transparency that characters get in video games when they are invincible.  `n`nWith a wicked grin, you wave to the `%g`^y`%p`^s`%y`# as you head back to the `@forest`#!`n`n");
	apply_buff('invulnerability', array(
		"name"=>"`#Invulnerability",
		"rounds"=>20,
		"invulnerable"=>1,
		"roundmsg"=>"`#You are Invulnerable.  Nothing can touch you.",
		"wearoff"=>"`#You stop having that strange transparency.  Watch out! You are no longer Invulnerable.",
	));
	$session['user']['specialinc'] = "";
	debuglog("wished for 20 turns of invulnerability from the Deck of Many Things.");
	addnav("Back to the Forest","forest.php");
}
if ($op=="deckwexp"){
	$expgain =($session['user']['level']*120+$session['user']['dragonkills']*10);
	$session['user']['experience']+=$expgain;
	output("Before you even speak your wish, you feel the flush of learning run through your body as you gain `b%s experience`b.`n`nWith a wicked grin, you wave to the `%g`^y`%p`^s`%y`# as you go to train!`n`n",$expgain);
	$session['user']['specialinc'] = "";
	debuglog("wished for $expgain experience from the Deck of Many Things.");
	addnav("Back to the Forest","forest.php");
}
if ($op=="deckwcharm"){
	output("Before you even speak your wish, you turn much more `QCharming by 12`#! `n`nWith a wave to the `%g`^y`%p`^s`%y`# as you go to strut around in the Village!`n`n");
	$session['user']['charm']+=12;
	$session['user']['specialinc'] = "";
	debuglog("wished for 12 charm from the Deck of Many Things.");
	addnav("Back to the Forest","forest.php");
}
if ($op=="deckwpermhp"){
	output("Before you even speak your wish, you feel your body become tougher. `n`nWith a wave to the `%g`^y`%p`^s`%y`# as you go back to the `@forest`#!`n`n");
	$session['user']['maxhitpoints']+=8;
	$session['user']['specialinc'] = "";
	debuglog("wished for 8 permanent hitpoints from the Deck of Many Things.");	
	addnav("Back to the Forest","forest.php");
}
if ($op=="exitstageleft"){
	require_once("modules/deckofmanythings/deckofmanythings_exitstageleft.php");
	deckofmanythings_exitstageleft();
}
if ($op=="deckleave"){
	output("You return to the forest looking for further adventures.");
	$session['user']['specialinc'] = "";
	addnav("Back to the Forest","forest.php");
}
if ($op=="attack") {
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($allprefs['monsternum']==1){
		$badguy = array(
			"creaturename"=>"The `%O`^l`%d `%G`^y`%p`^s`%y `%W`^o`%m`^a`%n`#",
			"creaturelevel"=>$session['user']['level']+1,
			"creatureweapon"=>"her `#Stupid `qStick",
			"creatureattack"=>round($session['user']['attack'])+2,
			"creaturedefense"=>round($session['user']['defense'])+2,
			"creaturehealth"=>round($session['user']['hitpoints']),
			"diddamage"=>0,
			"type"=>"oldgypsy");
		$session['user']['badguy']=createstring($badguy);
	}
	if ($allprefs['monsternum']==2){
		$badguy = array(
			"creaturename"=>"`!The Wizard of Yendor",
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>"the `&Book`) of `&the `)Dead",
			"creatureattack"=>$session['user']['attack']+1,
			"creaturedefense"=>$session['user']['defense']+1,
			"creaturehealth"=>$session['user']['maxhitpoints'],
			"diddamage"=>0,
			"type"=>"wizardyendor");
		$session['user']['badguy']=createstring($badguy);
	}
	if ($allprefs['monsternum']==3){
		$monster = translate_inline($session['user']['sex']?"`\$the Incubus" : "`\$the Succubus"); 
		$badguy = array(
			"creaturename"=>$monster,
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>"powers of seduction",
			"creatureattack"=>round($session['user']['attack']*.9),
			"creaturedefense"=>1,
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.6),
			"diddamage"=>0,
			"type"=>"foocubus");
		$session['user']['badguy']=createstring($badguy);
	}
	if ($allprefs['monsternum']==4){
		$dkb = round($session['user']['dragonkills']*.15);
		$badguy = array(
			"creaturename"=>"Erinys",
			"creaturelevel"=> $session['user']['level'],
			"creatureweapon"=>"painful blows",
			"creatureattack"=>round($session['user']['attack']*.9),
			"creaturedefense"=>round($session['user']['defense']*.9),
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.3),
			"diddamage"=>0,
			"type"=>"erinys");
		apply_buff('vengeance', array(
			"startmsg"=>"`&I will strike down upon thee with great vengeance and furious anger!`n",
			"name"=>"`4Fury",
			"rounds"=>1,
			"minioncount"=>1+$dkb,
			"mingoodguydamage"=>1,
			"maxgoodguydamage"=>1+$dkb,
			"effectmsg"=>"`&L`^i`&ght`^`&`^n`&i`^n`&g bolts of Vengeance strike you for `\${damage}`) hitpoints`^.",
		));
		$session['user']['badguy']=createstring($badguy);
	}
	if ($allprefs['monsternum']==5){
		if ($session['user']['maxhitpoints']<150) $hitpoints=150;
		else ($hitpoints=$session['user']['maxhitpoints']);
		$badguy = array(
			"creaturename"=>"`\$`bAsmodeus`b",
			"creaturelevel"=>16,
			"creatureweapon"=>"`b`\$Pain`b",
			"creatureattack"=>round($session['user']['attack']*1.5)+1,
			"creaturedefense"=>round($session['user']['defense']*1.5)+1,
			"creaturehealth"=>round($hitpoints*1.5),
			"diddamage"=>0,
			"type"=>"asmodeus");
		$session['user']['badguy']=createstring($badguy);
	}
	if ($allprefs['monsternum']==6){
		$badguy = array(
			"creaturename"=>"`\$V`Qa`\$m`Qp`\$i`Qr`\$e",
			"creaturelevel"=>$session['user']['level']+1,
			"creatureweapon"=>"blood dripping fingernails",
			"creatureattack"=>$session['user']['attack'],
			"creaturedefense"=>$session['user']['defense']-1,
			"creaturehealth"=>round($session['user']['maxhitpoints']*.97),
			"diddamage"=>0,
			"type"=>"thevampire");
		$session['user']['badguy']=createstring($badguy);
	}
	$op="fight";
}
if ($op=="fight"){
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($allprefs['monsternum']==2 ||$allprefs['monsternum']==3 || $allprefs['monsternum']==6){
		blocknav("runmodule.php?module=deckofmanythings&op=fight&auto=five");
		blocknav("runmodule.php?module=deckofmanythings&op=fight&auto=ten");
		blocknav("runmodule.php?module=deckofmanythings&op=fight&auto=full");
	}
	if ($allprefs['monsternum']==2){
		$yendor=array("So thou thought though couldst destroy me, fool...","Hell shall soon claim thy remains!","I chortle at thee, thou art pathetic!","Prepare to die, thou!","Resistance is useless!","Surrender or die, thou!","There shall be no mercy, thou!","Thou shalt repent of thy cunning!","Thou art as a flea to me!","Thou art doomed!","Thy fate is sealed!","Verily, thou shalt be one dead!","Even now thy life force ebbs!","Relinquish the Amulet!");
		$phrase=e_rand(0,13);
		output("`c`i`b`!The Wizard Cries out `@'%s'`i`c`b`n",$yendor[$phrase]);
		if ($phrase==1 || $phrase==2 ||$phrase==3 || $phase==4){
			apply_buff('wizardminions', array(
			"startmsg"=>"`5The `!Wizard `5casts a spell and calls forth some help!",
				"name"=>"`%Evil `\$Minion",
				"rounds"=>1,
				"minioncount"=>1,
				"mingoodguydamage"=>1,
				"maxgoodguydamage"=>10,
				"effectmsg"=>"`%The `!Wizard's `\$Minon `%hits you! You take `\${damage}`% hitpoints`%.`nThe `\$Minion `%dissipates.",
			));
		}
	}
	if ($allprefs['monsternum']==3 && $allprefs['deckseduced']==0){
		$monster = translate_inline($session['user']['sex']?"Incubus" : "Succubus"); 
		rawoutput("<big>");
		output("`b`&The `\$%s `&tries to seduce you...`b",($session['user']['sex']?"Incubus":"Succubus"));
		rawoutput("</big>");
		$phrase=e_rand(1,6);
		if ($phrase==1 && $session['user']['armordef']>1){
			output("`\$and %s suggests that you `#remove your armor`\$.  You realize that this is a great idea and you do.`n`n `b `&You'll have to fight the rest of this fight in your `^T-Shirt`&!`b`n`n`n`n",translate_inline($session['user']['sex']?"he":"she"));
			$allprefs['deckseduced']=1;
			$allprefs['decktempdefense']=$session['user']['armordef'];
			$allprefs['decktemparmor']=$session['user']['armor'];
			set_module_pref('allprefs',serialize($allprefs));
			$allprefs=unserialize(get_module_pref('allprefs'));
			$session['user']['defense']-=$session['user']['armordef']+1;
			$session['user']['armordef'] = 1;
			$session['user']['armor']="T-Shirt";
		}elseif($phrase==3){
			output("`n`nYou stop and consider %s offer. If you'd like, you could stop this fighting and spend some quality time with the %s. `0(That is, if you haven't already died or killed %s.)`n`n",($session['user']['sex']?"his":"her"),($session['user']['sex']?"Incubus":"Succubus"),($session['user']['sex']?"him":"her"));
			addnav(array("`0Spend Quality time with the %s",translate_inline($session['user']['sex']?"Incubus":"Succubus")),"runmodule.php?module=deckofmanythings&op=payment");
		}else{
			output("`%but you are not seduced by such `\$Evils`%!`n`n");
		}
	}
	if ($allprefs['monsternum']==6){
		$bite=e_rand(1,5);
		$damage=round($session['user']['level']*.5);
		if ($bite==5){
			apply_buff('vampiredrain', array(
				"startmsg"=>"`QThe Vampire gets close enough to bite you!",
				"name"=>"Vampire Bite",
				"rounds"=>1,
				"minioncount"=>1,
				"mingoodguydamage"=>$damage,
				"maxgoodguydamage"=>$damage,
				"effectmsg"=>"`QYou are drained for `\${damage} hitpoints`Q while the Vampire gains that many!",
			));
			apply_buff('vampiregain', array(
				"name"=>"Vampire Bite",
				"rounds"=>1,
				"minioncount"=>1,
				"minbadguydamage"=>-$damage,
				"maxbadguydamage"=>-$damage,
			));
		}
	}
	$battle = true;
}
if ($battle){       
	include("battle.php");
	$monster = translate_inline($session['user']['sex']?"Incubus" : "Succubus"); 
	if ($victory){
		$allprefs=unserialize(get_module_pref('allprefs'));
		if ($allprefs['monsternum']==1){
			output("`n`#Well, I have to admit, you did a good job beating up an old lady.  But hey, that's cool.`n`n");
			$expbonus=$session['user']['dragonkills']*4;
			$expgain =($session['user']['level']*e_rand(40,45)+$expbonus);
			$session['user']['experience']+=$expgain;
			output("`@You gain `#%s experience`@.`n",$expgain);
			$goldgain= e_rand(2,5)*400;
			$gemgain=e_rand(1,4);
			$session['user']['gold']+=$goldgain;
			$session['user']['gems']+=$gemgain;
			output("`@You gain `^%s Gold`@ and `%%s Gems`@.`n`n",$goldgain,$gemgain);
			addnews("%s`# killed an `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^a`%n`# that just wanted to play cards.",$session['user']['name']);
			$session['user']['specialinc'] = "";
			debuglog("defeated the Old Gypsy Woman with the Deck of Many Things.");
			addnav("Back to the Forest","forest.php");
		}
		if ($allprefs['monsternum']==2){
			output("`n`^You've killed the Wizard of Yendor!`n`n");
			$expbonus=$session['user']['dragonkills']*5;
			$expgain =($session['user']['level']*e_rand(45,60)+$expbonus);
			$session['user']['experience']+=$expgain;
			output("`@You gain `#%s experience`@.`n`n",$expgain);
			output("`^You search through the remains and find a very strange book.  When you pick it up, it `\$burns your fingers`^!`n`n You recover close from death, but your `\$hitpoints are reduced to 1`^. You were lucky as you notice the title is `HBook of the Dead`H`^.`n`n");
			output("`#The `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^a`%n`# looks at you and shrugs, pokes you with her `qstick`#, and disappears into the `@forest.`n`n");
			$session['user']['hitpoints']=1;
			$session['user']['specialinc'] = "";
			addnews("%s`^ drew the `\$Magician Card`^.",$session['user']['name']);
			debuglog("defeated the Wizard of Yendor from the Deck of Many Things.");
			addnav("Back to the Forest","forest.php");		
		}
		if ($allprefs['monsternum']==3){
			output("`n`#You defeat the `\$%s`#!  Very nice job!`n`n",$monster);
			if ($allprefs['deckseduced']==1){
				$armor=$allprefs['decktemparmor'];
				output("You grab your `b`&%s`#`b back and put it back on. You feel much safer again.`n`n",$armor);
				$allprefs['deckseduced']=0;
				set_module_pref('allprefs',serialize($allprefs));
				$session['user']['defense']+=$allprefs['decktempdefense']-1;
				$session['user']['armordef']=$allprefs['decktempdefense'];
				$session['user']['armor']=$armor;
			}
			$expbonus=$session['user']['dragonkills']*4;
			$expgain =($session['user']['level']*e_rand(30,40)+$expbonus);
			$session['user']['experience']+=$expgain;
			output("`@You gain `#%s experience`@ and also gain an `bExtra Turn`b!`n`n",$expgain);
			$session['user']['turns']++;
			output("`#The `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^a`%n`# looks at you and shrugs, pokes you with her `qstick`#, and disappears into the `@forest.`n`n");
			addnews("%s`^ drew the `\$Lovers Card`^.",$session['user']['name']);
			debuglog("defeated the $monster from the Deck of Many Things.");
			blocknav("runmodule.php?module=deckofmanythings&op=payment");
			$session['user']['specialinc'] = "";
			addnav("Back to the Forest","forest.php");
		}
		if ($allprefs['monsternum']==4){
			output("`n`^Your ability to defeat the `&Erinys`^ proves you innocent of all charges!`n`n");
			$expbonus=$session['user']['dragonkills']*4;
			$expgain =($session['user']['level']*e_rand(30,45)+$expbonus);
			$session['user']['experience']+=$expgain;
			output("`@You gain `#%s experience`@.`n`n",$expgain);
			output("Since you have proven yourself innocent, your hitpoints are restored to full.");
			$session['user']['hitpoints']=$session['user']['maxhitpoints'];
			debuglog("defeated an Erinys from the Deck of Many Things.");
			addnews("%s`^ drew the `\$Justice Card`^.",$session['user']['name']);
			$session['user']['specialinc'] = "";
			addnav("Back to the Forest","forest.php");
		}
		if ($allprefs['monsternum']==5){
			output("`n`4The `\$Demon `4falls before you defeated!!`n`nYou have crushed a power from the Astral Plane, a rare accomplishment indeed!`n`n");
			$expbonus=$session['user']['dragonkills']*15;
			$expgain =($session['user']['level']*e_rand(75,85)+$expbonus);
			$session['user']['experience']+=$expgain;
			$session['user']['gems']+=10;
			output("`@You gain `#%s experience`@ and `%10 Gems`@.`n`n",$expgain);
			output("If you would like, you may take the title of `\$`bSlayer`b.");
			addnav("Change my Title","runmodule.php?module=deckofmanythings&op=deckslayer");
			addnav("No Thank You","runmodule.php?module=deckofmanythings&op=deckleave");
			debuglog("defeated a Demon from the Deck of Many Things.");
			addnews("%s`^ drew the `\$Devil Card`^.",$session['user']['name']);
		}
		if ($allprefs['monsternum']==6){
			output("`n`5The `QVampire `5falls to your feet.  However, you are unable to finish his destruction because you do not have the wooden stake that is needed");
			output("to destroy his undead heart. `n`nYou decide it will be best if you retreat back to the `@forest`5. Before you leave, you take the `\$o`Qr`^n`@a`#t`!e`5 jewelry holding his cloak to his chest.  It is worth at least `%4 Gems`5!`n`n");
			$expbonus=$session['user']['dragonkills']*8;
			$expgain =($session['user']['level']*e_rand(45,55)+$expbonus);
			$session['user']['experience']+=$expgain;
			$session['user']['gems']+=4;
			output("`@You gain `#%s experience`@ and `%4 Gems`@.`n`n",$expgain);
			debuglog("defeated a Vampire from the Deck of Many Things.");
			addnews("%s`^ drew the `\$Tower Card`^.",$session['user']['name']);
			$session['user']['specialinc'] = "";
			addnav("Back to the Forest","forest.php");
		}
	}elseif($defeat){
		$session['user']['specialinc'] = "";
		require_once("lib/taunt.php");
		$taunt = select_taunt_array();
		$session['user']['alive'] = false;
		$session['user']['hitpoints'] = 0;
		$allprefs=unserialize(get_module_pref('allprefs'));
		if ($allprefs['monsternum']==1){	
			$exploss = round($session['user']['experience']*.1);
			output("`n`#The `%O`^l`%d `%G`^y`%p`^s`%y `%W`^o`%m`^a`%n`# stands over you and starts laughing.`n`n`%'Wow... beat up by an old woman.  I guess you won't be bragging to your friends about that, will you?'`#`n`n");
			output("You lose `#%s experience and `^all your gold`#.`b`0`n",$exploss);
			output("`@`n`c`bYou may begin your adventures again tomorrow.`c`b");
			addnav("Daily news","news.php");
			$session['user']['experience']-=$exploss;
			$session['user']['gold']=0;
			addnews("%s `#was defeated by an `%O`^l`%d `%G`^y`%p`^s`%y `%W`^o`%m`^a`%n`# in the forest.  Did I mention it was an `b`%O`^L`%D`b `%G`^y`%p`^s`%y`#? She was using a walker and oxygen to get around.  Seriously, how pitiful is that?",$session['user']['name']);
			debuglog("lost to the Gypsy with the Deck of Many Things.");
		}
		if ($allprefs['monsternum']==2){
			$exploss = round($session['user']['experience']*.05);
			output("`n`^The `!Wizard of Yendor`^ stands over your body.  He searches you and starts yelling `4'Where is the Amulet?!?!'`n`n`^You don't really care where the Amulet (whatever that is) could be because you are dead.`n`n");
			output("`^All your gold is lost.  For some reason, you hope that you left a `)bones`^ file.`n");
			output("You lose `#%s experience and `^all your gold`#.`b`0`n",$exploss);
			output("`@`n`c`bYou may begin your adventures again tomorrow.`c`b");
			addnav("Daily news","news.php");
			$session['user']['experience']-=$exploss;
			$session['user']['gold']=0;
			addnews("%s `^was defeated by `!The Wizard of Yendor`^.",$session['user']['name'],$taunt);
			debuglog("lost to the Wizard of Yendor from the Deck of Many Things.");
		}
		if ($allprefs['monsternum']==3){
			output("`n`#The %s gives you one final `\$`bkiss`b`# goodbye.`^`n",$monster);
			if ($allprefs['deckseduced']==1){
				$allprefs['deckseduced']=0;
				set_module_pref('allprefs',serialize($allprefs));
				output("`nSince you were killed by %s, you aren't able to recover your armor. `b`\$Love`b`^ sucks.`n",translate_inline($session['user']['sex']?"him":"her"));
			}
			$exploss = round($session['user']['experience']*.05);
			output("`nThe %s lets you keep your gold since money can't buy love.`n",$monster);
			output(" `\$You lose `#%s experience`\$.`b`0`n",$exploss);
			output("`@`n`c`bYou may begin your adventures again tomorrow.`c`b");
			addnav("Daily news","news.php");
			$session['user']['experience']-=$exploss;
			addnews("%s `^was seduced to death by a `\$%s`^.",$session['user']['name'],$monster,$taunt);
			blocknav("runmodule.php?module=deckofmanythings&op=payment");
		}
		if ($allprefs['monsternum']==4){
			$exploss = round($session['user']['experience']*.1);
			output("`n`n`^Your defeat at the hands of the Erinys proves to the world you have committed a grave sin against a tie of kinship.`n");
			output(" `n`\$You lose `#%s experience`\$.`b`0`n",$exploss);
			output("`@`n`c`bYou may begin your adventures again tomorrow.`c`b");
			addnav("Daily news","news.php");
			$session['user']['experience']-=$exploss;
			$session['user']['gold']=0;
			addnews("%s `@was found guilty of crimes against kinship.",$session['user']['name']);
		}
		if ($allprefs['monsternum']==5){
			output("`n`^The `\$Demon`^ prepares to strike you but his hand is stilled before the fatal blow falls.`n`n`b`\$'No, I shall not end your existence, for");
			output("I think I will be able to harvest your soul soon enough if I give you just a little more time here.  In fact, let me give you some extra `^gold`\$");
			output("to spend.  I think perhaps that will hasten my victory over you.'`b`n`n");
			output("Asmodeus `^disappears, leaving you with `\$1 hitpoint`^ and `b500 more Gold`b.  You do not lose any experience.");
			$session['user']['gold']+=500;
			$session['user']['hitpoints']=1;
			$session['user']['alive'] = true;
			addnav("Back to the Forest","forest.php");
			addnews("%s `@lost in a fight to a `\$Demon`@ from another plane of existence.",$session['user']['name']);
		}
		if ($allprefs['monsternum']==6){
			$exploss = round($session['user']['experience']*.1);
			output("`n`5Your body is slain by the final bite from the `QVampire`5.  As he feasts on your blood, you realize that this is a transforming loss.  You");
			output("have become one of the `QUndead`5.  It is a title you will carry with you from now on.`n`n");
			output("`\$You lose `#%s experience`\$ and all your `^Gold`\$.`0`n",$exploss);
			if (get_module_setting("givepermhp")==1 && ($session['user']['maxhitpoints']>($session['user']['level']*11)+10)) {
				output("`nYou also lose `b2 Permanent Hitpoints`b because of the drain to your body.`n");
				$session['user']['maxhitpoints']-=2;
				debuglog("lost 2 maxhitpoints because of being defeated by a Vampire from the Deck of Many Things.");
			}
			output("`@`n`c`bYou may begin your adventures again tomorrow.`c`b");
			addnav("Daily news","news.php");
			require_once("lib/titles.php");
			require_once("lib/names.php");
			$session['user']['experience']-=$exploss;
			$newtitle = "`QUndead`0";
			$newname = change_player_title($newtitle);
			$session['user']['title'] = $newtitle;
			$session['user']['name'] = $newname;
			$session['user']['gold']=0;
			addnews("%s `@lost in a fight to a `QVampire`@ discovered in a Tower in the forest.",$session['user']['name']);
		}
	}else{
		require_once("lib/fightnav.php");
		fightnav(true,false,"runmodule.php?module=deckofmanythings");
	}
}
//from moons by JT Traub
function deckofmanythings_phase($cur, $max){
	if ($cur < $max * .12) $phase = "`)new";
	elseif ($cur < $max * .25) $phase = "a `7waxing crescent";
	elseif ($cur < $max * .37) $phase = "`6half full";
	elseif ($cur < $max * .5) $phase = "`6waxing gibbous";
	elseif ($cur < $max * .62) $phase = "`^full";
	elseif ($cur < $max * .75) $phase = "`6waning gibbous";
	elseif ($cur < $max * .87) $phase = "`6half full and waning";
	else $phase = "a `7waning crescent";
	return translate_inline($phase);
}
page_footer();
?>