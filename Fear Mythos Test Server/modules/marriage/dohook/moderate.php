<?php
		if (get_module_setting('oc')==0) {
			$args['marriage'] = 'The Chapel';
		} else {
			$args['marriage'] = 'The Old Church';
		}
		if (get_module_setting('flirttype')==1) {
			$args['loveshack'] = 'The Loveshack';
		}
?>