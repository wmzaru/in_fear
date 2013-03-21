<?php
	if ($args['setting'] == "villagename") {
		if ($args['old'] == get_module_setting("storeloc")) {
			set_module_setting("storeloc", $args['new']);
		}
	}
?>