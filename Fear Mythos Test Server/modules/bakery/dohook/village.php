<?php
	if ($session['user']['location'] == get_module_setting("bakeryloc")) {
		tlschema($args['schemas']['marketnav']);
		addnav($args['marketnav']);
		tlschema();
		addnav("B?Hara's Bakery","runmodule.php?module=bakery&op=food");
	}
?>