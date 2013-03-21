<?php
   	if ($args['setting'] == "villagename") {
		if ($args['old'] == get_module_setting("cartographerloc")) {
			set_module_setting("cartographerloc", $args['new']);
		}
	}
?>