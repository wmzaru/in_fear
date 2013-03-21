<?php
function deckofmanythings_carddraw(){
	global $session;
	$allprefs=unserialize(get_module_pref('allprefs'));
	output("You draw...`n");
	switch(e_rand(1,20)){
	//switch(5){
		case 1:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtfool.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^The Fool`b`c");
			rawoutput("</big>");
			output("`n`nThe `@F`%o`!o`\$l`^ and his `bMoney`b are quickly parted... As are you and yours! `n`n You lose `bAll your gold`b and `%10% of your gems`^!`n`n");
			$session['user']['gold']=0;
			$gems=round($session['user']['gems']*.9);
			$session['user']['gems']=$gems;
			$session['user']['specialinc']="";
			debuglog("lost all gold and $gems from the Deck of Many Things.");
			addnav("Back to the Forest","forest.php");
			addnews("%s`^ drew the `\$Fool Card`^.",$session['user']['name']);
		break;
		case 2:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtmagician.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^The Magician`b`c");
			rawoutput("</big>");
			output("`nSuddenly, you find yourself face to face with a very powerful magician...  His name is `!The Wizard of Yendor`^!`n`n");
			$allprefs['monsternum']=2;
			set_module_pref('allprefs',serialize($allprefs));
			addnav("Fight the `!Wizard","runmodule.php?module=deckofmanythings&op=attack");
		break;
		case 3:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmthighpriestess.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^The High Priestess`b`c");
			rawoutput("</big>");
			if (get_module_setting("givepermhp")==1) {
				output("`n`nYour `\$hitpoints`^ are restored to full!  Plus you gain `\$3 Permanent Hitpoints`^ and `\$10 additional hitpoints`^!`n`n`n");
				$session['user']['maxhitpoints']+=3;
				$session['user']['hitpoints']=$session['user']['maxhitpoints']+10;
				debuglog("received 10 temp hitpoints and 3 Permanent Hitpoints from the Deck of Many Things.");
			}else{
				output("`n`nYour `\$hitpoints`^ are restored to full!  Plus you gain `\$50 Hitpoints`^!`n`n`n");
				$session['user']['hitpoints']=$session['user']['maxhitpoints']+50;
				debuglog("received 50 temp hitpoints from the Deck of Many Things.");	
			}
			addnav("Back to the Forest","forest.php");
			$session['user']['specialinc']="";
			addnews("%s`^ drew the `\$High Priestess Card`^.",$session['user']['name']);
		break;
		case 4:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtoracle.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^The Oracle`b`c");
			rawoutput("</big>");
			output("`n`nYou may ask the Oracle about any player in the game.`n`n");
			addnav("Ask the Oracle","runmodule.php?module=deckofmanythings&op=deckoracle");	
			addnews("%s`^ drew the `\$Oracle Card`^.",$session['user']['name']);
		break;
		case 5:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtlovers.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^The Lovers`b`c");
			rawoutput("</big>");
			output("`n`nStanding before you is `\$%s`^.  I'd advise you to defeat %s in battle.`n`n",translate_inline($session['user']['sex']?"an Incubus":"a Succubus"),translate_inline($session['user']['sex']?"him":"her"));
			addnav("`\$Fight!","runmodule.php?module=deckofmanythings&op=attack");
			if (get_module_setting("deckseduction")==1){
				if ($session['user']['gold']>=500) addnav (array("`0Pay `^500 Gold`0 to the %s",translate_inline($session['user']['sex']?"Incubus":"Succubus")),"runmodule.php?module=deckofmanythings&op=payment");
			}
			$allprefs['monsternum']=3;
			set_module_pref('allprefs',serialize($allprefs));
		break;
		case 6:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtchariot.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^The Chariot`b`c");
			rawoutput("</big>");
			output("`n`nYou hop aboard the Chariot and are transported to the `#Local Watering Hole`^.");
			output("This may sound like a strange place to visit, but then you realize that driving a Chariot must be a lot of work. `n`nSounds like a good excuse to go get a `QMead`^!");
			$session['user']['specialinc']="";
			$vname = getsetting("villagename", LOCATION_FIELDS);
			$capital = $session['user']['location']==$vname;
			$session['user']['location']=$vname;
			addnav("Visit the Inn","inn.php?op=strolldown");
			addnews("%s`^ drew the `\$Chariot Card`^.",$session['user']['name']);
		break;
		case 7:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtstrength.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^Strength`b`c");
			rawoutput("</big>");
			output("`n`nThe `%g`^y`%p`^s`%y`^ looks at you and nods.`n`n`%'Yes, you look quite powerful!'`n`n`^You gain `&One Attack`^.  You eat a can of `@Spinach`^ and also `&Gain A Powerful Buff`^.`n`n");
			$session['user']['attack']++;
			apply_buff('evileye',array(
				"name"=>"`@Spinach",
				"rounds"=>10,
				"wearoff"=>"`@The power of the Spinach wears off",
				"atkmod"=>1.25,
				"roundmsg"=>"`@'I Yam what I Yam!!' becomes your battle cry...",
			));
			addnav("Back to the Forest","forest.php");
			$session['user']['specialinc']="";
			addnews("%s`^ drew the `\$Strength Card`^.",$session['user']['name']);
		break;
		case 8:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmthermit.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^The Hermit`b`c");
			rawoutput("</big>");
			output("`n`nSuddenly you feel like hiding!`n`nYou are invisible to the world.`n`n");
			apply_buff('invisibility',array(
				"name"=>"Invisibility",
				"rounds"=>15,
				"wearoff"=>"`&You are suddenly seen by your enemy.",
				"defmod"=>1.25,
				"roundmsg"=>"`&Your invisibility makes you more difficult for the enemy to defeat!",
			));
			addnav("Back to the Forest","forest.php");
			$session['user']['specialinc']="";
			addnews("%s`^ drew the `\$Hermit Card`^.",$session['user']['name']);
		break;
		case 9:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtwheel.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^Wheel of Fortune`b`c");
			rawoutput("</big>");
			output("`n`nThe `%g`^y`%p`^s`%y`^ watches you as the card you picked enlarges to a full sized spinning wheel.  You take several seconds to carefully read the wedges:`n`n");
			output("`c`!Health`n`#Fear`n`%Friendship`n`\$Pain`n`QBeauty`n`6Skill`n`^Wealth`n`@Energy`n`)Death`c`n");
			output("`^You reach out to touch the wheel and it suddenly starts spinning.  You nervously watch it slow down...");
			addnav("Wheel of Fortune","runmodule.php?module=deckofmanythings&op=deckwheel");	
		break;
		case 10:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtjustice.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^Justice`b`c");
			rawoutput("</big>");
			output("`n`nA terrible creature appears before you, it's head wreathed with `@serpents`^, eyes dripping with `4blood`^, it's body terrific and appalling.");
			output("It has the `)wings of a bat`^ and the `qbody of a dog`^.`n`n It is one of the `&Erinyes`^ standing before you and calling for Justice to be served against you!");
			$allprefs['monsternum']=4;
			set_module_pref('allprefs',serialize($allprefs));
			addnav("Fight the Erinys","runmodule.php?module=deckofmanythings&op=attack");			
		break;
		case 11:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtpunishment.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^Punishment`b`c");
			rawoutput("</big>");
			output("`n`nYou are being `)`bPunished`b`^!");
			output("You hear the sound of shackles and feel a tightening sensation around your leg.");
			output("You look down to see a very heavy `)Ball `&and `)Chain`^ hindering your every movement.");
			output("You sigh and realize that it's going to be a VERY long day.`n`n");
			apply_buff('ballnchain',array(
				"name"=>"`)Ball `&and `)Chain",
				"rounds"=>50,
				"wearoff"=>"`)The shackles break and you're free!",
				"defmod"=>.75,
				"roundmsg"=>"`)Your defensive maneuvers are hindered by the huge Ball attached to your leg.",
			));
			addnav("Back to the Forest","forest.php");
			$session['user']['specialinc']="";
			addnews("%s`^ drew the `\$Punishment Card`^.",$session['user']['name']);
		break;
		case 12:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtdevil.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^The Devil`b`c");
			rawoutput("</big>");
			output("`n`nThe smell of `)brimstone`^ surrounds you.  You turn to find yourself facing a`\$ Major Demon`^!");
			$allprefs['monsternum']=5;
			set_module_pref('allprefs',serialize($allprefs));
			addnav("`4Fight `\$Asmodeus","runmodule.php?module=deckofmanythings&op=attack");				
		break;
		case 13:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtsorcery.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^Sorcery`b`c");
			rawoutput("</big>");
			switch(e_rand(1,2)){
				case 1:
					output("`n`nA great and powerful wizard in `@Emerald`^ appears before you.");
					output("He sizes you up and decides that you are standing a little too proudly and need to be taken down a notch.");
					output("The wizard casts a terrible spell on you!`n`n`nOh No!  What will it be?!?!?`n`n`n Some horrible curse?!?!`n`n`nA plague of boils?!?!`n`n`n");
					output("Or maybe Locusts?!?!`n`nYou cringe in anticipation and fear!`n`n`nBefore you know it, there's a darn pebble in your shoe poking you with every step!");
					output("`n`n`\$Oh the Wickedness of it all!!!");
					apply_buff('shoepebble',array(
						"name"=>"Pebble in Your Shoe",
						"rounds"=>25,
						"wearoff"=>"`)The pebble pops out of your shoe",
						"minioncount"=>1,
						"mingoodguydamage"=>1,
						"maxgoodguydamage"=>1,
						"effectmsg"=>"`\$Darn that little pebble is annoying! You lose 1 hitpoint from it!",
					));	
				break;
				case 2:
					output("`n`nA large floating head appears out of no where.  It requests that you retrieve a `qbroom`^ from a `)w`2itch`^ or something like that.");
					output("You get kinda bored with all the hoo-ha.`n`nAs you turn to leave, you see a `0small dog`^ tugging at a curtain revealing a man operating come complicated machinery.");
					output("`n`nSince you are not a `%little `\$g`&i`\$r`&l`^ from Kansas you realize you've popped into the wrong story!`n`nThe `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^a`%n`^ apologizes and mentions that she can't always understand");
					output("what happens with this crazy deck.  She gives you back your gold plus a little more to get you on your `%merry way`^.`n`nYou receive `b750 gold`b and go on your `%Merry Way`^.");
					$session['user']['gold']+=750;
					apply_buff('merryway',array(
						"name"=>"`%Merry Way",
						"rounds"=>10,
						"wearoff"=>"`%Your way isn't quite so merry anymore.",
						"atkmod"=>1.1,
						"defmod"=>1.1,
						"roundmsg"=>"`%Tra la la la la la! What a Merry Way!",
					));
				break;
			}
			addnav("Back to the Forest","forest.php");
			$session['user']['specialinc']="";
			addnews("%s`^ drew the `\$Sorcery Card`^.",$session['user']['name']);
		break;
		case 14:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtdeath.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^Death`b`c");
			rawoutput("</big>");
			output("`n`n`\$Pain`^ surrounds you and a crushing sensation overcomes you. `)Death Incarnate`^ grows from out of the card holding a horrible scythe dripping with the blood of the uncountable dead.`n`n");
			output("Without words, the skull under the hood looks at you and raises a hand and points.");
			output("`n`nIn an instant, you are `\$Dead`^.`n`n`)Death Incarnate`^ disappears and you give a silly little smile.");
			output("He killed you too quickly! You're not fully dead, you're `\$MOSTLY Dead`^!`n`n");
			$exploss = round($session['user']['experience']*.07);
			output("`bYou lose `#%s experience`^, all your `\$hitpoints except 1`^, all your gold, and you lost `@1/2 of your remaining turns`^.`b`n`n",$exploss);
			output("But you still get to return to the `@Forest`^!`n`n`n");
			$session['user']['experience']-=$exploss;
			$session['user']['hitpoints']=1;
			$session['user']['gold']=0;
			$session['user']['turns']=round($session['user']['turns']*.5);
			$session['user']['specialinc']="";
			addnav("Back to the Forest","forest.php");
			addnews("%s`^ drew the `\$Death Card`^.",$session['user']['name']);
		break;
		case 15:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmttower.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^The Tower`b`c");
			rawoutput("</big>");
			output("`n`nThe card drops to the ground and a spiraling tower grows from it.");
			output("The monolith rises above you.  You stare at it with a sense of fear but an even greater sense of adventure.");
			output("`n`n The first step is always the hardest.  Are you ready to go up the stairway?`n`n");
			addnav("Enter the Tower","runmodule.php?module=deckofmanythings&op=decktower");
			addnav("Leave for the Forest","runmodule.php?module=deckofmanythings&op=exitstageleft");
		break;
		case 16:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtstar.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^The Star`b`c");
			rawoutput("</big>");
			$monthnumb=date("n");			
			$gemnumb=e_rand(0,1);
			$gemnumbera=array("One","Two");
			$gemadds=array("","s");
			$gemprecious=array("a `\$P`Qr`^e`@c`#i`!o`%`\$u`Qs `%Gem","`%Two `\$P`Qr`^e`@c`#i`!o`%`\$u`Qs `%Gems");
			$monthname=array("","January","February","March","April","May","June","July","August","September","October","November","December");
			$monthgem=array("","`4Garnet","`%`bAmethyst`b","`#Aquamarine","`&Diamond","`@Emerald","`&O`%p`\#a`&l","`\$Ruby","`^Chrysoberyl","`!Sapphire","`)B`0l`)a`0c`)k `0O`)p`0a`)l","`1T`#o`1p`#a`1z","`2T`@u`2r`@q`2u`@o`2i`@s`2e");
			$gemvalue=array("","700","600","1500","4000","2500","800","3500","700","3000","2500","900","2000");
			$gemn=array("","","n","n","","n","n","","","","","","");
			output("`n`nThe `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^a`%n`^ looks at you playfully and grabs for your ear.  She pulls out %s`^ and smiles at you.`n`n",$gemprecious[$gemnumb]);
			output("`%'Since it is `b`&%s`b`% you get `&%s %s%s`%.",$monthname[$monthnumb],$gemnumbera[$gemnumb],$monthgem[$monthnumb],$gemadds[$gemnumb]);
			output("A%s %s`% is worth `^%s Gold`%.'`n`n`^After thinking about it for a second, you happily accept the gold instead of the `\$P`Qr`^e`@c`#i`!o`%`\$u`Qs `%Gem%s`^.",$gemn[$monthnumb],$monthgem[$monthnumb],$gemvalue[$monthnumb],$gemadds[$gemnumb]);
			$getgold=$gemvalue[$monthnumb]*($gemnumb+1);
			$session['user']['gold']+=$getgold;
			output("`n`n`^You gain `b%s Gold`b!`n`n",$getgold);
			$session['user']['specialinc']="";
			addnav("Back to the Forest","forest.php");
			addnews("%s`^ drew the `\$Star Card`^.",$session['user']['name']);
		break;
		case 17:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtmoon.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^The Moon`b`c");
			rawoutput("</big>");
			output("`n`n");
			if (is_module_active("moons")){
				//From moons by JT Traub
				$msg = "`%'The moon `&%s`% is %s`%.'`n";
				$moon1 = get_module_setting("moon1","moons");
				$moon2 = get_module_setting("moon2","moons");
				$moon3 = get_module_setting("moon3","moons");
				//require_once("modules/deckofmanythings/deckofmanythings_phase.php");
				if ($moon1) {
					$place = get_module_setting("moon1place","moons");
					$max = get_module_setting("moon1cycle","moons");
					output($msg, get_module_setting("moon1name","moons"),deckofmanythings_phase($place, $max));
				}
				if ($moon2) {
					$place = get_module_setting("moon2place","moons");
					$max = get_module_setting("moon2cycle","moons");
					output($msg, get_module_setting("moon2name","moons"),deckofmanythings_phase($place, $max));
				}
				if ($moon3) {
					$place = get_module_setting("moon3place","moons");
					$max = get_module_setting("moon3cycle","moons");
					output($msg, get_module_setting("moon3name","moons"),deckofmanythings_phase($place, $max));
				}
				//end of moons
			}else{
				$today=date("j");
				$moonphased=array("","`)new","`)new","`)new","`)new","`7waxing crescent","`7waxing crescent","`7waxing crescent","`7waxing crescent","`6half full","`6half full","`6half full","`6waxing gibbous","`6waxing gibbous","`6waxing gibbous","`6waxing gibbous","`^full","`^full","`^full","`^full","`6waning gibbous","`6waning gibbous","`6waning gibbous","`6waning gibbous","`6half full and waning","`6half full and waning","`6half full and waning","`6half full and waning","a `7waning crescent","`7waning crescent","`7waning crescent","`7waning crescent");
				output("`%'It is a `b%s Moon`b`%.'`n",$moonphased[$today]);
			}
			output("`n`#You look at `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^a`%n`# and start complaining to her.`n`n`@'THAT is your amazing deck?!? It tells me what kind of moon it is?!? This sucks!'`n`n");
			output("`#The `%g`^y`%p`^s`%y`# doesn't really seem to care, shrugs, and disappears into the `@forest`#.`n`n");
			$session['user']['specialinc']="";
			addnav("Back to the Forest","forest.php");
			addnews("%s`^ drew the `\$Moon Card`^.",$session['user']['name']);
		break;
		case 18:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtsun.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^The Sun`b`c");
			rawoutput("</big>");
			output("`n`nOut of nowhere, some `%music`^ starts to play.");
			output("You turn to see the `%g`^y`%p`^s`%y`^ standing before you with a `)top hat`^ on and a `&cane`^ in her hand.");
			output("Before you have a chance to escape, she starts dancing around you and singing...`n`n`%'The `^Sun`% will come out... Tomorrow! Bet your bottom `@dollar`% that Tomorrow, there'll be `^sun`%!'`n`n'Tomorrow! Tomorrow!");
			output("I love you! Tomorrow! You're only a Dayyyyyyyyy AAaaaaawwwwayyyyyyy!!'`n`n");
			output("`#Wow! That was some inspirational singing! You gain `@3 Extra turns`#!`n`n");
			$session['user']['turns']+=3;
			$session['user']['specialinc']="";
			addnav("Back to the Forest","forest.php");
			addnews("%s`^ drew the `\$Sun Card`^.",$session['user']['name']);
		break;
		case 19:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtjudgement.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^Judgement`b`c");
			rawoutput("</big>");
			output("`n`nYou suddenly find yourself standing in a courtroom full of `)crows`^.  You are going to be judged by the crows on your ability to tell a joke.`n`nWhich one will you tell?`n`n");
			output("`@1. The Crazy Patient`n`n`^2. Ducks and Elephants`n`n`\$3. The Hunchback and the Bell`n`n");
			addnav("`@1. The Crazy Patient","runmodule.php?module=deckofmanythings&op=deckjoke1");
			addnav("`^2. Ducks and Elephants","runmodule.php?module=deckofmanythings&op=deckjoke2");
			addnav("`\$3. The Hunchback and the Bell","runmodule.php?module=deckofmanythings&op=deckjoke3");
		break;
		case 20:
			if (get_module_setting("usepics")==1) rawoutput("<br><center><table><tr><td align=center><img src=modules/deckofmanythings/dmtinfinity.jpg></td></tr></table></center><br>"); 
			rawoutput("<big>");
			output("`c`b`^Infinity`b`c");
			rawoutput("</big>");
			output("`n`nThe `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^a`%n`^ looks at the card and starts to nod.`n`n`%'You may buy a `@W`^i`&s`%h for only `^1000 Gold`%.'`n`n");
			if ($session['user']['gold']<1000) {
				output("'Oh, I see you don't have `^1000 Gold`%.  That is sad. Your loss.  Well, the least I can do is give you a little something because I will take your wish.'`n`n");
				output("`^The `%g`^y`%p`^s`%y `^gives you `b1000 gold`b, `%1 gem`^, and a potion that gives you `&2 Charm`^.");
				$session['user']['gold']+=1000;
				$session['user']['gems']++;
				$session['user']['charm']+=2;
				$session['user']['specialinc']="";
				addnav("Back to the Forest","forest.php");
			}else{
				output("'Give me your `^Gold`% and you can make your wish.'`n`n");
				addnav("Make a `@W`^i`&s`%h","runmodule.php?module=deckofmanythings&op=deckwish");
				addnav("No thanks!","runmodule.php?module=deckofmanythings&op=deckleave");
			}
			addnews("%s`^ drew the `\$Infinity Card`^.",$session['user']['name']);
		break;
	}
}
?>