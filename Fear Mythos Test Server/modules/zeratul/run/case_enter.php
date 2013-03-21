<?php

	output("`)You wander into a small shoppe.");
	output("Upon the walls, are millions of boxes stacked to the ceiling.");
	output("A blinding blue light appears in the corner, and `i`@Zeratul `i`)walks out from it.");
	output("His green blade upon his wrist, glistening with the blood of a recent kill.");
	output("He walks up to you, and tilts his head to the side, checking behind your ears.");
	if (get_module_pref("red") == 1 || get_module_pref("blue") == 1 || get_module_pref("green") == 1 || get_module_pref("white") == 1 || get_module_pref("black") == 1){
		output(" \"`i`@So... I see that you have some of the crystals that I gave you in the woods.");
		output(" Would you care to use them now?`i`)\"");
		require_once("modules/zeratul/func.php");
		zeratul_navs();
	}else{
		output("`i`@Zeratul `i`)looks at you and shakes his head.");
		output(" \"`i`@You do not have any crystals...");
		output(" If you can best me in the woods, then I shall give you some of my fineries.`i`)\"");
	}

?>