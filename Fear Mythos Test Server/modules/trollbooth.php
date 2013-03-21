<?php

function trollbooth_getmoduleinfo() {
	$stuff = array(
		"author"=>"`4Th`\$ri`Qce`^bo`&r`^nP`Qho`\$en`4ix",
		"category"=>"Travel Specials",
		"description"=>"You encounter a troll booth on the road. Trolls may take over the booth for a while, but others must pay or try to sneak by!",
		"download"=>"",
		"name"=>"The Troll Booth",
		"version"=>"1.0",
	);
	return $stuff;
}

function trollbooth_checkroad() {
	global $session;
	$val = 80;

	if (!is_module_active("racetroll")) {
		$val = 20;
	}
	else {
		$trollhome = get_module_setting("villagename", "racetroll");
		if ($session['user']['location'] == $trollhome || httpget('city') == $trollhome) {
			$val = 100;
		}
		else {
			$val = 60;
		}
	}
	return $val;
}

function trollbooth_install() {
	module_addeventhook("travel", "require_once(\"modules/trollbooth.php\"); return trollbooth_checkroad();");
	return true;
}

function trollbooth_uninstall() {
	return true;
}

function trollbooth_runevent($type, $link) {
	global $session;

	$op = httpget('op');
	$dest = httpget('city');
	$cost = httpget('cost');
	if ($dest == '') {
		$dest = translate_inline("`\$Hell");
	}
	$session['user']['specialinc'] = "module:trollbooth";

	switch($op) {
		case "yes":
			output("`2You agree to take over the booth for a while. The troll thanks you and starts walking.");
			output("You wait for quite some time for something to happen.`n`n");
			$rand = e_rand(0, 3);
			switch($rand) {
				case "0":
					output("You continue to wait for a long time.`n`n");
					output("You wait some more.`n`n");
					output("After a long time, nothing happens.`n`n");
					output("Eventually you just give up and resume your journey, having wasted much time.");
					$session['user']['turns'] = ceil($session['user']['turns'] * 0.5);
				break;

				case "1":
					$gold = e_rand($session['user']['level'] * 10, $session['user']['level'] * 15);
					$session['user']['gold'] += $gold;
					output("Finally, a weary adventurer passes by, and you shake him down.");
					output("It's then that you remember that adventurers aren't usually wealthy.");
					output("How can you be when all your gold is spent on drinks, equipment, and more drinks?`n`n");
					output("Still, he had some gold, and now it's yours. Well, half of it.");
					output("You pocket `^%s gold`2, and give the other half to the booth's owner when he returns.", $gold);
				break;

				case "2":
					output("You're about to leave when you see a large group approaching. At first you wonder how much gold they're carrying.");
					output("As they approach, you start to wonder why they're all armed and heading straight for you.`n`n");
					output("It seems the city watch was alerted about your operation by some concerned citizen!");
					output("You hotfoot back to %s leaving a small cloud of dust behind you.`n`nMaybe that wasn't such a good idea.", $session['user']['location']);
					$session['user']['specialinc'] = "";
					villagenav();
				break;

				case "3":
					$gold = e_rand($session['user']['level'] * 15, $session['user']['level'] * 25);
					$session['user']['gold'] += $gold;
					output("As luck would have it, the first person to approach is a travelling merchant, on the way to restock his wares.");
					output("You take most of his gold, leaving him just enough to stay in business and be robbed another day.`n`n");
					output("Your share amounts to `^%s gold`2, not a bad afternoon's work. The rest you leave for the troll who runs the booth.", $gold);
				break;
			}
			$session['user']['turns']--;
		break;

		case "no":
			output("`2You continue your journey, shaking your head bemusedly.");
			$session['user']['specialinc'] == "";
		break;

		case "sneak":
			output("`2You keep your eyes on the road, your pace steady, your resolve firm, and hope that he doesn't wake up.");
			output("You breathe a sigh of relief as you hear it snore. You saunter right by the booth, and the troll");
			$sneakchance = $session['user']['specialty'] == "TS" ? 1 : 2;// Thieves can be sneaky sometimes.
			if (e_rand(0, $sneakchance) == 0) {
				output("doesn't so much as twitch. Sleeping on the job? Brigands these days are so `blazy`b, you think to yourself.");
			}
			else {
				output("suddenly bounds over to you and blocks the path!");
				output("His threatening glare is all you need to see, but he shows you his razor-sharp teeth and the club as well, just in case.`n`n");
				if ($session['user']['gold'] >= $cost) {
					$session['user']['gold'] -= $cost;
					output("He takes `^%s gold `2and laughs as you run away.", $cost);
				}
				elseif ($session['user']['gold'] > 0) {
					$session['user']['gold'] = 0;
					$session['user']['hitpoints'] = ceil($session['user']['hitpoints'] * 0.5);
					output("He takes all of your gold and gives you a complimentary bump on the head.");
				}
				else {
					output("You explain your plight, and when he's satisfied that you're really broke, he lets you go.");
				}
			}
		break;

		case "pay":
			$session['user']['gold'] -= $cost;
			output("`2You walk over to the booth and drop `^%s gold `2on it. The troll never moves.", $cost);
		break;

		default:
			$cost = e_rand($session['user']['level'] * 25, $session['user']['level'] * 40);
			output("`2On the highway to %s`2, you encounter a rickety wooden booth occupied by a huge troll.", $dest);
			output("The booth dominates this fairly open stretch of road, and is in fact several feet to the side.`n`n");
			output("A sign indicates a fee of `^%s gold `2 for non-trolls to pass, and a huge spiked club rests against it.", $cost);
			output("You've heard of troll brigands lurking by well-travelled roads to rob adventurers, but this is ridiculous.`n`n");
			if ($session['user']['race'] == "Troll") {
				output("Curious, you walk over to the booth. As you approach, the troll opens one eye to look at you.");
				output("Seeing that you're a fellow troll, he asks if you'd like to take over while he gets a bite to eat.");
				if ($session['user']['turns'] == 0) {
					output("Unfortunately, you dont have the time.");
				}
				else{
					output("You can even keep half of any gold you `iearn`i. Of course, it'll definitely delay your journey.");
					addnav("Okay", $link . "op=yes");
					addnav("Sorry", $link . "op=no");
				}
			}
			else {
				output("He appears to be sleeping. If you're lucky, you might sneak past.");
				addnav("Sneak by", $link . "op=sneak&cost=$cost");
				if ($session['user']['gold'] < $cost) {
					output("In fact, you realise to your dismay, you'll have to, since you don't have that much gold!");
				}
				else {
					output("Of course, you could just pay the fee.");
					addnav("Pay up", $link . "op=pay&cost=$cost");
				}
			}
		break;
	}
}

?>