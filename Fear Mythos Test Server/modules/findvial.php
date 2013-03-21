<?php

/* 
 * Originally created for RPG Forums Online's version of LotGD - http://rpgforumsonline.game-host.org/lotgd/
 *
 * Upon finding the vial, there are various options:
 * Quaff it - randomly (a) loose HP or (b) gain HP
 * Pick it up - depending upon DKs, either (a) gain some charm points or (b) no effect
 * Kick it away - randomly either (a) loose a forest fight, (b) no effect, (c) find gold and loose one charm point or (d) find gem
 * Examine it - (a) if Dwarf, you gain 20 HP
 *		    (b) if Elf, you loose a charm point
 *              (c) if Human, randomly either (c1) gain 100 experience or (c2) no effect, otherwise 
 *		    (d) If Troll, randomly either (d1) gain one forest fight or (d2) gain two forest fights or (d3) gain two forest fights and loose one charm point
 *              (e) no effect
 * Leave it alone - no effect
 */

/*
 * Based on the original ideas of R4000, Sichae and Ville Valtokari.
 */

function findvial_getmoduleinfo(){
	$info = array(
		"name"=>"Find Vial",
		"version"=>"1.4",
		"author"=>"Ozental Gimwald",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=729",
	);
	return $info;
}

function findvial_install(){
	module_addeventhook("travel", "return 100;");
	module_addeventhook("forest", "return 100;");
	return true;
}

function findvial_uninstall(){
	return true;
}

function findvial_dohook($hookname,$args){
	return $args;
}

function findvial_runevent($type,$link)
{
	global $session;
	$from = $link;
	$op = httpget('op');
	$session['user']['specialinc'] = "module:findvial";
	if ($op=="")
		{
		output("`2From the corner of your eye, you see a glowing bottle by the side of the road.`n`n");
		output("What will you do now?`0");
		addnav("Quaff it",$from."op=quaff");
		addnav("Pick it up",$from."op=pickup");
		addnav("Kick it away",$from."op=kick");
		addnav("Examine it further",$from."op=examine");
		addnav("Leave it alone",$from."op=leave");
		}

	elseif ($op=="quaff")
		{
		$rand = e_rand(1,2);
		switch ($rand)
			{
			case 1:
				output("`4You uncork the vial and quaff the potion.");
				output("You become a little light headed and fall to the ground.`n`n");
				output("Before you have a chance to reach the Healers Hut, the potion begins to burn your throat.");
				output("You lose some hitpoints!`n`n`0");
				$hp = $session['user']['hitpoints'];
				$hp = round($hp*0.50, 0);
				$session['user']['hitpoints']-=$hp;
				if ($session['user']['hitpoints'] < 0)
					$session['user']['hitpoints'] = 1;
				$session['user']['specialinc'] = "";
				break;

			case 2:
				output("`4You uncork the vial and quaff the potion.");
				output("A strange sensation fills your body.`n`n");
				output("You feel as if you have grown a few inches talled and your muscles appear a little bigger.");
				output("You gained some hitpoints!`n`n`0");
				$hp = $session['user']['hitpoints'];
				$hp = round($hp*0.40, 0);
				$session['user']['hitpoints']+=$hp;
				$session['user']['specialinc'] = "";
				break;
			}
		}

	elseif ($op=="pickup")
		{
		output("`2You poddle over to the side of the road and look around to see if anyone is watching.`n");
		output("`2Seeing no one nearby, you reach down and pick it up.`n");
		output("`2After picking it up, you give it a quick shake and then remove the cork from top of the bottle.`n");
		output("`2You take a sniff of the potion inside, which you decide smells a little like honey.`n");
		output("`2You feel the urge to quaff it, so without hesitation, you do so.`n`n`0");
				
		$dk = $session['user']['dragonkills'];
		if ($dk == 0) $dk = 1;
		$dkchance = max(5,(ceil($dk / 5)));
		if (e_rand(0,$dkchance) <= 1)
			{
			output("`^The potion has a deeply soothing effect. You feel charmed!`0");
			/* Add 2 to charm */
			$session['user']['charm']+=2;			
			$session['user']['specialinc'] = "";
			}
		else
			{
			output("`^The potion appears to have no effect on you.`0");
			$session['user']['specialinc'] = "";
			}
		}

	elseif ($op=="kick")
		{
		$rand = e_rand(1,6);
		switch ($rand)
			{
			case 1: case 2: case 3:
				output("`2You kick the bottle away and it breaks into a thousand pieces.`n`n");
				output("In a flash of light a fairy appears, picks up the broken glass and snarls at you.`n`n");
				output("The hateful look makes you feel ashamed.`n`n");
				output("`^You lose one forest fight for littering.`n`n`0");
				if ($session['user']['turns']>0) $session['user']['turns']--;
				$session['user']['specialinc']="";
				break;

			case 4:
				output("`2You kick the bottle away and it bounces along the ground.`n`n");
				output("For some reason, the glass does not shatter.`n`n");
				output("`^Perhaps you should have picked up the vial after all.`n`n`0");
				$session['user']['specialinc']="";
				break;

			case 5:
				$gold = e_rand($session['user']['level']*10, $session['user']['level']*40);
				output("`2You kick the bottle away and it breaks into a thousand pieces, making it unusable.`n`n");
				output("`^Luckily, below the vial was a small pile gold coins.`n`n");
				output("`^Upon closer examination, you count that there are %s gold coins!`n`n", $gold);
				$session['user']['gold'] += $gold;
				debuglog("Found $gold gold in the findvial module");
				output("`^However, for bad karma in breaking the vial, you loose one forest fight.`n`n`0");
				if ($session['user']['turns']>0) $session['user']['turns']--;
				$session['user']['specialinc']="";
				break;

			case 6:
				output("`2You kick the bottle away and it breaks into a thousand pieces, making it unusable.`n`n");
				output("`^Luckily, below the vial was a gem, which you promptly pick up.`n`n");
				$session['user']['gems']++;
				break;
			}
		}

	elseif ($op=="examine")
		{
		if ($session['user']['race']== 'Dwarf')
			{
			output("`2You pick up the vial and examine it closely.");
			output("`2There are some strange markings on the side of the vial.`n`n`0");
			output("`2Luckily for you, the markings appear to be dwarven, and being a Dwarf yourself, you understand their meaning.`0");
			output("`2As a result, you realise that this is a health potion and you quaff the potion.`n`n`0");
			output("You gained 20 hitpoints!`n`n`0");
			$session['user']['hitpoints']+=20;
			$session['user']['specialinc'] = "";
			}
		elseif ($session['user']['race']== 'Elf')
			{
			output("`2You pick up the vial and examine it closely.");
			output("`2There are some strange symbols on the side of the vial, which appear to be an ancient form of dwarven.`n`n`0");
			output("`2Unluckily for you, as you are an Elf and very non-Dwarf like, you do not understand the meaning of the symbols.`0");
			output("`2As a result, you are aggrevated by your lack of knowledge.`n`n`0");
			output("You loose one charm point!`n`n`0");
			$session['user']['charm']--;
			if ($session['user']['charm'] < 0)
				$session['user']['charm'] = 0;
			$session['user']['specialinc'] = "";
			}
		elseif ($session['user']['race']== 'Human')
			{
			output("`2You pick up the vial and examine it closely.");
			output("`2There are some strange symbols on the side of the vial, which appear to be an ancient form of dwarven.`n`n`0");
			output("`2Being human, you do not understand the meaning of the symbols.`n`n`0");

			$rand = e_rand(1,2);
			switch ($rand)
				{
				case 1:
					output("`2As a result, you are glad you did not quaff the potion.`n`n`0");
					output("You gain 100 experience points!`n`n`0");
					$session['user']['experience']+=100;
					$session['user']['specialinc']="";
					break;
				case 2:
					output("`2As a result, you place the vial back onto the ground and prepare to continue your journey.`n`n`0");
					$session['user']['specialinc']="";
					break;
				}
			}
		elseif ($session['user']['race']== 'Troll')
			{
			output("`2You pick up the vial and examine it closely.");
			output("`2There are some strange symbols on the side of the vial, which appear to be an ancient form of dwarven.`n`n`0");
			output("`2Being a troll, you do not really understand the meaning of the symbols.`0");
			output("`2However, being a somewhat dense troll, you uncork the vial and quaff the potion.`n`n`0");

			$rand = e_rand(1,3);
			switch ($rand)
				{
				case 1:
					output("`2Luckily for you, the potion was blessed by the Old Ones.`n`n`0");
					output("You gain one forest fight!`n`n`0");
					$session['user']['turns']++;
					$session['user']['specialinc']="";
					break;
				case 2:
					output("`2Luckily for you, the potion was blessed by the Gods.`n`n`0");
					output("You gain two forest fights!`n`n`0");
					/* Add 2 to turns */
					$session['user']['turns']+=2;
					$session['user']['specialinc']="";
					break;
				case 3:
					output("`2Luckily for you, the potion was blessed by the greatest of the Gods.`n`n`0");
					output("You gain two forest fights!`n`n`0");
					output("`2But Gods can always be spiteful too and with their blessing, they added a curse.`n`n`0");
					output("You loose one charm point!`n`n`0");
					/* Add 2 to turns */
					$session['user']['turns']+=2;
					$session['user']['charm']--;
						if ($session['user']['charm'] < 0)
						$session['user']['charm'] = 0;
					$session['user']['specialinc']="";
					break;
				}
			}
		else
			{
			output("`2You pick up the vial and examine it closely.");
			output("`2There are some strange markings on the side of the vial but you do not understand their meaning.`0");
			output("`2As a result, you place the vial back onto the ground where you found it and prepare to continue your journey.`n`n`0");
			$session['user']['specialinc'] = "";
			}
		}

	elseif ($op=="leave")
		{
		output("`2You decide to leave the vial where it is and continue on your way.`0");
		$session['user']['specialinc'] = "";
		}
}

function findvial_run(){
}
?>