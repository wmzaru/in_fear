<?php
		if (!is_module_active('oldchurch')) set_module_setting('oc',0);
		$module = httpget('module');
		$op = httpget('op');
		if (get_module_setting('oc')&&$module=='oldchurch'&&$op=='enter') addnav("Marriage Wing","runmodule.php?module=marriage&op=oldchurch");

?>