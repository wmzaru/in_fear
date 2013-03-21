<?php
	if ($session['user']['location'] == get_module_setting("cartographerloc","cartographer")){
		tlschema($args['schemas']['marketnav']);
		addnav($args['marketnav']);
		tlschema();
		addnav(array("%s",get_module_setting("storename")),"runmodule.php?module=cartographer");
	}
	$allprefs=unserialize(get_module_pref('allprefs'));
	for ($i=1;$i<=20;$i++) {
		if ($allprefs['hasmap'.$i]==0 && get_module_pref("super")==0){
			$module=get_module_setting("modulename".$i);
			blocknav("runmodule.php?module=$module"); 
			blocknav("runmodule.php?module=$module&op=enter"); 
			blocknav("runmodule.php?module=$module&op=shop"); 
		}
	}
?>