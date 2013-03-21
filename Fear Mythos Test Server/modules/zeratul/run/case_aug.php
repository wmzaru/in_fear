<?php

	if ($session['user']['gold'] >= $auggold || $session['user']['gems'] >= $auggems){
		switch ($op2){
			case "armor":
				output("`i`@Zeratul `i`)grins as he looks upon you.");
				output(" He guides you into a small room, labeled \"Armor\".");
				output(" Smiling, he begins to speak, \"`i`@So, you wish to augment your armor...");
				output(" Well, I will have to use one of your crystals in order to use it.");
				output("If you are sure, then select a crystal to use.`i`)\"");
				addnav("Crystals");
				if (get_module_pref("red"))
					addnav("Red Crystal","runmodule.php?module=zeratul&op=done&crys=red&arm=1");
				if (get_module_pref("blue")) 
					addnav("Blue Crystal","runmodule.php?module=zeratul&op=done&crys=blue&arm=1");
				if (get_module_pref("green")) 
					addnav("Green Crystal","runmodule.php?module=zeratul&op=done&crys=green&arm=1");
				if (get_module_pref("white")) 
					addnav("White Crystal","runmodule.php?module=zeratul&op=done&crys=white&arm=1");
				if (get_module_pref("black")) 
					addnav("Black Crystal","runmodule.php?module=zeratul&op=done&crys=black&arm=1");
				addnav("Return");
				addnav("Return to the Storefront","runmodule.php?module=zeratul&op=enter");
				break;
			case "weapon":
				output("`i`@Zeratul `i`)look at you, and tilts his head to the left.");
				output(" \"`i`@So, you have chosen to mar your precious weapon, in order to best those evil creatures of the forest?`i`)\"");
				output(" He grins evilly and rubs his hands together.");
				output("Nodding, he begins to speak once more.");
				output(" \"`i`@Which crystal do you wish to use, in order to augment your `i%s`i`@.`i`)\"",$session['user']['weapon']);
				addnav("Crystals");
				if (get_module_pref("red"))
					addnav("Red Crystal","runmodule.php?module=zeratul&op=done&crys=red&weap=1");
				if (get_module_pref("blue")) 
					addnav("Blue Crystal","runmodule.php?module=zeratul&op=done&crys=blue&weap=1");
				if (get_module_pref("green")) 
					addnav("Green Crystal","runmodule.php?module=zeratul&op=done&crys=green&weap=1");
				if (get_module_pref("white")) 
					addnav("White Crystal","runmodule.php?module=zeratul&op=done&crys=white&weap=1");
				if (get_module_pref("black")) 
					addnav("Black Crystal","runmodule.php?module=zeratul&op=done&crys=black&weap=1");
						addnav("Return");
						addnav("Return to the Storefront","runmodule.php?module=zeratul&op=enter");
				break;
		}
	}else{
		output("`i`@Zeratul `i`)looks at you and frowns.");
		if ($session['user']['gold'] < $auggold) 
			output("`n`n\"`i`@I am sorry... but you need `^%s `@more gold, until you can augment anything...`i`)\"",$auggold - $session['user']['gold']);
		if ($session['user']['gems'] < $auggems){
			output("`n`n\"`i`@I am terribly sorry... but you do not have enough gems to complete this augmentation.");
			output(" You need `%%s `@more.`i`)\"",$auggems - $session['user']['gems']);
		}
	}

?>