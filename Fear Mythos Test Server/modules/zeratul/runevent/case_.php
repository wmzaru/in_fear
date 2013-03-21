<?php

	if (get_module_pref("red") == 1 && get_module_pref("blue") == 1 && get_module_pref("green") == 1 && get_module_pref("white") == 1 && get_module_pref("black") == 1){
		$session['user']['specialinc'] = "";
		redirect("runmodule.php?module=zeratul&op=full");
	}
	$session['user']['specialinc'] = "module:zeratul";
	output("`)You wander into a small clearing and see a small portal.");
	output(" Out from the portal, a crippled hand extends towards you.");
	output(" A tall figure appears and takes a step towards you.");
	output(" He rolls up his cloak sleeve, and down comes a green blade.");
	output(" He speaks from underneath the hood, \"`i`@Why hath ye tread upon my woods?`i`)\"");
	output(" Utterly confused, you point to yourself, and he nods.");
	output(" \"`i`@The toll for trespassing, is death.`i`)\" he says with a maniacal laughter afterwards.");
	output(" \"`i`@Do you care to fight?`i`)\" he asks of you, eyeing your %s`0.",$session['user']['weapon']);
	addnav("Duel?");
	addnav("Yes","forest.php?op=yes");
	addnav("Decline","forest.php?op=no");

?>