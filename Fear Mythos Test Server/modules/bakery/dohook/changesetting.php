<?php
	if ($args['setting'] == "villagename") {
		if ($args['old'] == get_module_setting("bakeryloc")) set_module_setting("bakeryloc", $args['new']);
	}
?>