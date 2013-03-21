<?php
	if ($args['setting'] == "villagename") {
		if ($args['old'] == get_module_setting("masonsloc")) set_module_setting("masonsloc", $args['new']);
	}
?>