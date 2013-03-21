<?php
		if ($session['user']['marriedto']!=0&&get_module_pref('user_stats')) {
			require_once("lib/partner.php");
			$partner=get_partner(true);
			setcharstat("Personal Info","Marriage","`^".translate_inline("Married to ").$partner);
		}

?>