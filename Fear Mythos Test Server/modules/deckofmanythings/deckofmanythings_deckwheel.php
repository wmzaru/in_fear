<?php
function deckofmanythings_deckwheel(){
	global $session;
	output("`c`!<click>`#<click>`%<click>`\$<click>`Q<click>`^<click>`@<click>`)<click>`c");
	switch(e_rand(1,9)){
		case 1:	
			output("`c`!<click>`#<click>`%<click>`\$<click>`Q<click>`^<click>`@<click>`n");
			output("`!<click>`#<click>`%<click>`\$<click>`Q<click>`^<click>`n");
			output("`!<click>`#<click>`%<click>`\$<click>`Q<click>`n");
			output("`!<click>`#<click>`%<click>`\$<click>`n");
			output("`!<click>`#<click>`%<click>`n");
			output("`!<click>`#<click>`n");
			output("`!<click>`n`n");
			output("`bHealth!`b`c`n");
			if (is_module_active('potions') && get_module_pref('potion','potions')<5){
				set_module_pref('potion', 5, 'potions');
				output("You notice your backpack is filled with healing potions!");
				debuglog("gained maximum healing potions from the Deck of Many Things.");
			}elseif (get_module_setting("givepermhp")==1) {
				output("You gain `\$A Permanent Hitpoint`! and `\$you are restored to full health`!!");
				$session['user']['maxhitpoints']++;
				$session['user']['hitpoints']=$session['user']['maxhitpoints'];
				debuglog("received 1 Permanent Hitpoint from the Deck of Many Things.");
			}else{
				output("You gain `\$20 Hitpoints`^!`n`n`n");
				$session['user']['hitpoints']=$session['user']['maxhitpoints']+20;
				debuglog("received 20 temp hitpoints from the Deck of Many Things.");	
			}
		break;
		case 2:
			output("`c`)<click>`!<click>`#<click>`%<click>`\$<click>`Q<click>`^<click>`n");
			output("`@<click>`)<click>`!<click>`#<click>`%<click>`\$<click>`n");
			output("`^<click>`@<click>`)<click>`!<click>`#<click>`n");
			output("`Q<click>`^<click>`@<click>`)<click>`n");
			output("`\$<click>`Q<click>`^<click>`n");
			output("`%<click>`\$<click>`n");
			output("`#<click>`n`n");
			output("`bFear!`b`c`n");
			output("A deep `\$shiver`# runs through you and you start to notice the `)shadows`# are creeping in all around you.`n`nThis is going to be a `\$s`)c`#a`\$r`)y`# day for you!");
			apply_buff('fear',array(
				"name"=>"`#F`)e`\$a`#r",
				"rounds"=>50,
				"wearoff"=>"`#Your fears are alleviated.",
				"atkmod"=>.90,
				"defmod"=>.90,
				"roundmsg"=>"`#Your attack is less affective because you're afraid you'll hurt yourself.",
			));
			debuglog("became terrified with a fear buff from the Deck of Many Things.");
		break;
		case 3:
			output("`c`!<click>`\$<click>`@<click>`#<click>`Q<click>`)<click>`%<click>`n");
			output("`^<click>`!<click>`\$<click>`@<click>`#<click>`Q<click>`n");
			output("`)<click>`%<click>`^<click>`!<click>`\$<click>`n");
			output("`@<click>`#<click>`Q<click>`)<click>`n");
			output("`%<click>`^<click>`!<click>`n");
			output("`\$<click>`@<click>`n");
			output("`%<click>`n`n");
			output("`bFriendship!`b`c`n");
			if ($session['user']['sex']==0){
				output("Since a `qdog`% is man's best friend, you get a little puppy to help you out for a little while!");
				apply_buff('wizardminions', array(
					"startmsg"=>"`qYour best friend fights by your side!",
					"name"=>"`%Little `qDog",
					"rounds"=>25,
					"minioncount"=>1,
					"minbadguydamage"=>3,
					"maxbadguydamage"=>3+$session['user']['level'],
					"effectmsg"=>"`%The Little Dog takes a Big Bite out of crime and does {damage} damage!",
				));
				debuglog("received the help of a little dog from the Deck of Many Things.");
			}else{
				output("Since `&Diamonds`% are a girl's best friend, you find yourself holding `b5 Gems`b!");
				$session['user']['gems']+=5;
				debuglog("received 5 gems from the Deck of Many Things.");
			}	
		break;
		case 4:
			output("`c`!<click>`#<click>`%<click>`\$<click>`Q<click>`^<click>`@<click>`n");
			output("`)<click>`!<click>`#<click>`%<click>`\$<click>`Q<click>`n");
			output("`^<click>`@<click>`)<click>`!<click>`#<click>`n");
			output("`%<click>`\$<click>`Q<click>`^<click>`n");
			output("`@<click>`)<click>`!<click>`n");
			output("`#<click>`%<click>`n");
			output("`\$<click>`n`n");
			output("`bPain!`b`c`n");
			output("A sudden shock from the card shoots out at you.  Excruciating unbearable pain that you've never dreamed of before courses through your body.`n`nYou");
			if (get_module_setting("givepermhp")==1 && ($session['user']['maxhitpoints']>($session['user']['level']*11)+10)) {
				output("lose `b5 Permanent Hitpoints`b.  You also ");
				$session['user']['maxhitpoints']-=5;
				debuglog("lost 5 maxhitpoints and all hitpoints except 1 for the next 5 days from the Deck of Many Things.");
			}	
			output("lose all your hitpoints except 1.  For the next 5 days the aching memory will cause you to start each day with only 1 hitpoint.");
			$allprefs=unserialize(get_module_pref('allprefs'));
			$allprefs['deckpain']=5;
			set_module_pref('allprefs',serialize($allprefs));
			$session['user']['hitpoints']=1;
			debuglog("lost all hitpoints except 1 for the next 5 days from the Deck of Many Things.");
		break;
		case 5:
			output("`c`#<click>`%<click>`\$<click>`Q<click>`^<click>`@<click>`)<click>`n");
			output("`\$<click>`Q<click>`^<click>`@<click>`)<click>`!<click>`n");
			output("`@<click>`)<click>`!<click>`#<click>`%<click>`n");
			output("`%<click>`\$<click>`Q<click>`^<click>`n");
			output("`)<click>`!<click>`#<click>`n");
			output("`^<click> `@<click>`n");
			output("`Q<click>`n`n");
			output("`bBeauty!`b`c`n");
			output("The `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^a`%n`Q looks around for any witnesses.  She takes out her `qstick`Q and hits you `\$Hard`Q on the head!  In fact, it costs you `\$1/2 of your hitpoints`Q!`n`n");
			output("You look at her and get ready to beat her silly, but notice that actually, you are looking good! She smiles.`n`n`%'You're lucky I decided to hit you with my pretty stick.'`n`n`QYou gain `&4 Charm`Q!");
			$session['user']['charm']+=4;
			$session['user']['hitpoints']=round($session['user']['hitpoints']*.5);
			debuglog("lost 1/2 hitpoints and 4 charm points from the Deck of Many Things.");
		break;
		case 6:
			output("`c`!<click>`\$<click>`@<click>`#<click>`Q<click>`)<click>`%<click>`n");
			output("`^<click>`!<click>`\$<click>`@<click>`#<click>`Q<click>`n");
			output("`)<click>`%<click>`^<click>`!<click>`\$<click>`n");
			output("`@<click>`#<click>`Q<click>`)<click>`n");
			output("`%<click>`^<click>`!<click>`n");
			output("`\$<click>`@<click>`n");
			output("`6<click>`n`n");
			output("`bSkill!`b`c`n");
			output("The `bSpecialty Fairy`b comes flying around and touches you on the nose!`n`nYou Advance in your Specialty.");
			require_once("lib/increment_specialty.php");
			increment_specialty("`6");
			debuglog("incremented their specialty from the Deck of Many Things.");
		break;
		case 7:
			output("`c`!<click>`\$<click>`@<click>`#<click>`Q<click>`)<click>`%<click>`n");
			output("`^<click>`!<click>`\$<click>`@<click>`#<click>`Q<click>`n");
			output("`)<click>`%<click>`^<click>`!<click>`\$<click>`n");
			output("`@<click>`#<click>`Q<click>`)<click>`n");
			output("`%<click>`^<click>`!<click>`n");
			output("`\$<click>`@<click>`n");
			output("`^<click>`n`n");
			output("`bWealth!`b`c`n");
			output("You receive");
			if ($session['user']['gold']==0) {
				output("1,000,000");
			}elseif($session['user']['gold']<=100){
				output("20");
				$session['user']['gold']*=20;
			}elseif($session['user']['gold']<=1000){
				output("3");
				$session['user']['gold']*=3;
			}else{
				output("2");
				$session['user']['gold']*=2;
			}
			output("times your current amount of gold!!!");	
		break;
		case 8:
			output("`c`%<click>`\$<click>`Q<click>`^<click>`@<click>`)<click>`!<click>`n");
			output("`Q<click>`^<click>`@<click>`)<click>`!<click>`#<click>`n");
			output("`@<click>`)<click>`!<click>`#<click>`%<click>`n");
			output("`!<click>`#<click>`%<click>`\$<click>`n");
			output("`%<click>`\$<click>`Q<click>`n");
			output("`Q<click>`^<click>`n");
			output("`@<click>`n`n");
			output("`bEnergy!`b`c`n");
			output("A blinding `&L`^i`&g`^h`&t`@ pulses out of the Wheel of Fortune. Soon enough, you're engulfed by the `&l`^i`&g`^h`&t`@.  Covering your eyes doesn't help!`n`nThe `&l`^i`&g`^h`&t`@ fades as you notice a glow spreads across your weapon and your armor.`n`nYou can `b5 extra turns`b!");
			$session['user']['weapon']="`b`&Bright`b ".$session['user']['weapon'];
			$session['user']['armor']="`b`&Bright`b ".$session['user']['armor'];
			$session['user']['turns']+=5;
		break;
		case 9:
			output("`c`#<click>`%<click>`\$<click>`Q<click>`^<click>`@<click>`)<click>`n");
			output("`%<click>`\$<click>`Q<click>`^<click>`@<click>`)<click>`n");
			output("`\$<click>`Q<click>`^<click>`@<click>`)<click>`n");
			output("`Q<click>`^<click>`@<click>`)<click>`n");
			output("`^<click>`@<click>`)<click>`n");
			output("`@<click>`)<click>`n");
			output("`)<click>`n`n");
			output("Death!`b`c`n");
			$exploss = round($session['user']['experience']*.05);
			output("`b`) You lose `#%s experience`).`n`n",$exploss);
			output("You lose all your `^gold`) and `@1/4 of your remaining turns`).`n");
			output("`c`b`n`@You are `\$MOSTLY dead`@... Which means you're still really alive.`b`c");
			addnews("%s `@was rendered `\$MOSTLY dead...`@ by the `^Deck `\$of `^Many Things",$session['user']['name']);
			$session['user']['experience']-=$exploss;
			$session['user']['hitpoints']=1;
			$session['user']['gold']=0;
			$session['user']['turns']=round($session['user']['turns']*.75);
		break;
	}
	output("`n`n`#The `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^a`%n`# looks at you and shrugs, pokes you with her `qstick`#, and disappears into the `@forest.`n`n");
	$session['user']['specialinc']="";
	addnav("Back to the Forest","forest.php");
	addnews("%s`^ drew the `\$Wheel of Fortune Card`^.",$session['user']['name']);
}
?>