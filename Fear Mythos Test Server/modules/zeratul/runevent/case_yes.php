<?php

	$session['user']['specialinc'] = "module:zeratul";
	output("`)The figure pulls back it's hood and grins, \"`i`@My name is Zeratul, and I am a Dark Templar.");
	output(" My kind has been hunted down, from realm to realm.");
	output(" My head carries a high fee... if you can best me in battle, I shall give you a precious gem.");
	output(" This gem will show you the path, to my hideout in the village of %s.",get_module_setting("zeraloc"));
	output(" Are you sure you wish to duel?`i`)\" he finishes and looks to his hand.");
	output(" He is holding 5 crystals.");
	output(" Each a different color.");
	addnav("Assurance");
	addnav("I'm Ready","forest.php?op=final");
	addnav("Not Today","forest.php?op=no");

?>