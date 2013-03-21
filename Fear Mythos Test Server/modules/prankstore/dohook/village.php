<?php
	$rand=e_rand(1,100);
	if ($rand<=get_module_setting("chanceopen") && $session['user']['location'] == get_module_setting("storeloc") && $session['user']['dragonkills']>=get_module_setting("dks")){ 
		tlschema($args["schemas"]["marketnav"]);
		addnav($args["marketnav"]);
		tlschema();
		addnav(array("%s",get_module_setting("storename")),"runmodule.php?module=prankstore&op=enter");
		if (get_module_setting("chanceopen")<100) output("`n`@It looks like a shop has magically appeared in the village.`n");
	}
	//prank 7/8/9
	for ($i = 7; $i < 10; $i++){
		if ($session['user']['location']==get_module_setting("storeloc") && get_module_setting("prankon".$i)<>0){
			$who=get_module_setting("prankon".$i);
			$sql = "SELECT name FROM " . db_prefix("accounts") . " WHERE acctid='$who'";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$name = $row['name'];
			$last = full_sanitize($last);
			$result=get_module_setting("result".$i);
			rawoutput("<marquee height=\"15\" width=\"100%\" onMouseover=\"this.stop()\" onMouseout=\"this.start()\" direction=left scrollamount=\"5\" style=\"Filter:Alpha(Opacity=50, FinishOpacity=100, Style=1, StartX=0, StartY=100, FinishX=0, FinishY=0); text-align:center\"><font class=body>");
			if ($i==7) output("`#Health Alert: `@%s`^ has %s!",$name,get_module_setting($result."prank7"));
			elseif ($i==8) output("`^%s`\$ was seen %s!",$name,get_module_setting($result."prank8"));
			else output("`!%s`& has %s!",$name,get_module_setting($result."prank9"));
			rawoutput("</font><br></marquee>");
			output("`n`n");
		}
	}
?>