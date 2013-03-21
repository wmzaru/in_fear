<?php
if (!get_module_setting('runoncemove') && get_module_setting('move')){
	$vloc = array();
	$vname = getsetting("villagename", LOCATION_FIELDS);
	$vloc[$vname] = "village";
	$vloc = modulehook("validlocation", $vloc);
	$key = array_rand($vloc);
	set_module_pref('userplace', $key);
}
if (get_module_pref('lostmount') && !get_module_pref('runoncemount')){
	$lmd = get_module_pref('lostmountdays');
	if ($lmd > 0) increment_module_pref("lostmountdays", -1);
	if (get_module_pref('lostmountdays') > 0){
		$lmd -=1;
		output("`&`n`nYour hand still glows with a turquoise hue. ");
		output("Your mount won't return for another %s days.`n`n`0", $lmd);
	}
	else{
		output("`&`n`nThe turquoise hue vanishes from your hand. ");
		output("With the next breath, you see your mount return to you from around the bend.`n`n`0");
		$session['user']['hashorse'] = get_module_pref('mountid');
		set_module_pref('lostmount', 0);
		set_module_pref('mountid', 0);
	}
}
if (!get_module_setting('runonceused')) set_module_pref('used', 0);
?>