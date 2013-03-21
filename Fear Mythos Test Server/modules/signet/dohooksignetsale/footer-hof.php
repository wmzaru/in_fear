<?php
	if(get_module_setting('frhof','signetd5')==1){
		addnav("Warrior Rankings");
		addnav("Vanquishers", "runmodule.php?module=signetsale&op=hof");	
	}
	if (get_module_setting("usehof")==1){
		addnav("Warrior Rankings");
		addnav("Signet Quest Status", "runmodule.php?module=signetsale&op=hof2");
	}
?>