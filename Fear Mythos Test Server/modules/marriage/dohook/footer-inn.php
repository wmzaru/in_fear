<?php
		set_module_pref('inShack',0);
		if (httpget('op')=='' && $session['user']['marriedto']!=0 && $session['user']['marriedto']!=4294967295 && is_module_active('lovers')) {
			addnav("Things to do");
			blocknav("runmodule.php?module=lovers&op=flirt",true);
			require_once("lib/partner.php");
			$namepartner=get_partner();
			addnav(array("F?Flirt with %s`0",$namepartner),"runmodule.php?module=marriage&op=innflirt");
		}

?>