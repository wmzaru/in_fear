<?php

function zeratul_navs(){
	global $session;
		if (get_module_setting("aug") == 1) {
			addnav("Augmentation");
			if (get_module_pref("augarm") == 0) addnav("Augment Armor","runmodule.php?module=zeratul&op=aug&op2=armor");
			if (get_module_pref("augweap") == 0) addnav("Augment Weapon","runmodule.php?module=zeratul&op=aug&op2=weapon");
		}
		if (get_module_setting("create") == 1){
			addnav("Creation");
			if (get_module_pref("armor") == 0) addnav("Create Armor","runmodule.php?module=zeratul&op=create&op2=armor");
			if (get_module_pref("weapon") == 0) addnav("Create Weapon","runmodule.php?module=zeratul&op=create&op2=weapon");
			if (get_module_pref("extra") == 0) addnav("Create Accessory","runmodule.php?module=zeratul&op=create&op2=extra");
		}
		if (get_module_pref("weapon") == 1 && get_module_pref("armor") == 1 && get_module_pref("extra") == 1 && get_module_pref("augarm") == 1 && get_module_pref("augweap") == 1){
			output("`n`n`i`@Zeratul`i `)walks you from his hideout.");
			output(" \"`i`@I am sorry... but you have already taken everything from me...");
			output(" Please come back when you are done with this run through...`i`)\"");
		}
}

?>