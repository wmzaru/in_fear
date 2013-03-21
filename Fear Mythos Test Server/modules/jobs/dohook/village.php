<?php
	if ($session['user']['dragonkills']>=get_module_setting("dkmin")){
		if (get_module_setting("industrialpark")==1 && $session['user']['location'] == get_module_setting("indusloc")){
			tlschema($args['schemas']['marketnav']);
			addnav($args['marketnav']);
			tlschema();
			addnav("Industrial Park","runmodule.php?module=jobs&place=industrialpark");	
		}elseif (get_module_setting("industrialpark")==0){
			$limit=get_module_setting("maxnum");
			if (is_module_active("furniture")==0 && $limit==10) $limit=9;
			for ($i=1;$i<=$limit;$i++) {
				if ($session['user']['location'] == get_module_setting("loc".$i)){
					tlschema($args['schemas']['marketnav']);
					addnav($args['marketnav']);
					tlschema();
					addnav(array("%s", get_module_setting("type".$i)),"runmodule.php?module=jobs&place=$i");
				}
			}
			if ($session['user']['location'] == get_module_setting("jobserviceloc")){
				tlschema($args['schemas']['marketnav']);
				addnav($args['marketnav']);
				tlschema();
				addnav("Job Services","runmodule.php?module=jobs&place=jobservice");
			}
			if ($session['user']['location'] == get_module_setting("marketloc")){
				tlschema($args['schemas']['marketnav']);
				addnav($args['marketnav']);
				tlschema();
				addnav("Industrial Market","runmodule.php?module=jobs&place=market");
			}
		}
	}
?>