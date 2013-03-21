<?php
	if(get_module_setting('usehof')==1){
		addnav("Warrior Rankings");
		addnav("Hardest Workers", "runmodule.php?module=jobs&place=jobservice&op=hof");	
	}
?>