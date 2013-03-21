<?php
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ((is_module_active("mapmaker") || is_module_active("cartographer")) && get_module_setting("mapmaker")==1 && $allprefs['sigmap5']==0){
	}elseif (($session['user']['location'] == get_module_setting("mapsaleloc")||get_module_pref("super"))&&$allprefs['sigmap5']==0){
		tlschema($args['schemas']['marketnav']);
		addnav($args['marketnav']);
   		tlschema();
		addnav("Antiquities Cart","runmodule.php?module=signetsale&op=enter");
	}
?>