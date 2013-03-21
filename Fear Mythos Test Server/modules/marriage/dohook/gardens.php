<?php
		addnav("Love & Lust");
		//addnav("~");
		addnav("Newly Weds","runmodule.php?module=marriage&op=newlyweds");
		//set_module_pref('inShack',0);
	//case "village":
		//tlschema($args['schemas']['tavernnav']);
		//addnav($args['tavernnav']);
		//tlschema();

		if (get_module_setting('counsel')==1&&get_module_pref('counsel')==1) {
			addnav("Social Counselling","runmodule.php?module=marriage&op=counselling");
		}
		if ($session['user']['location'] == get_module_setting("chapelloc")&&get_module_setting("all")==0&&get_module_setting('oc')==0) {
			addnav(array("%s %s Chapel",$session['user']['location'],get_module_setting('name')),"runmodule.php?module=marriage&op=chapel");
		} elseif (get_module_setting("all")==1&&get_module_setting('oc')==0) {
			addnav(array("%s Chapel",get_module_setting('name')),"runmodule.php?module=marriage&op=chapel");
		}
		if (get_module_setting('flirttype')==1) {
			if ($session['user']['location'] == get_module_setting("loveloc")&&get_module_setting("lall")==0) {
				addnav(array("%s Loveshack",$session['user']['location']),"runmodule.php?module=marriage&op=loveshack");
			} elseif (get_module_setting("lall")==1) {
				addnav(array("The Loveshack"),"runmodule.php?module=marriage&op=loveshack");
			}
		}
		set_module_pref('inShack',0);
?>