<?php
if ($modulename == "" or $modulesubmit == ""){
	if ($goldgem == 0){
		$usegold = " selected='selected'";
	}elseif ($goldgem == 1){
		$usegem = " selected='selected'";
	}else{
		$usenone = " selected='selected'";
	}
	if ($raceboon == "defense"){
		$rbdef = " selected='selected'";
	}elseif ($raceboon == "attack"){
		$rbatk = " selected='selected'";
	}else{
		$rbreg = " selected='selected'";
	}
	if ($buffseed == 1){
		$bs1 = " selected='selected'";
	}elseif ($buffseed == 2){
		$bs2 = " selected='selected'";
	}elseif ($buffseed == 3){
		$bs3 = " selected='selected'";
	}elseif ($buffseed == 4){
		$bs4 = " selected='selected'";
	}else{
		$bs5 = " selected='selected'";
	}
	if ($pvpseed == 1){
		$pv1 = " selected='selected'";
	}elseif ($pvpseed == 2){
		$pv2 = " selected='selected'";
	}elseif ($pvpseed == 3){
		$pv3 = " selected='selected'";
	}elseif ($pvpseed == 4){
		$pv4 = " selected='selected'";
	}else{
		$pv5 = " selected='selected'";
	}
	if ($usevillage == 0){
		$usevil1 = " selected='selected'";
	}else{
		$usevil2 = " selected='selected'";
	}
	if ($villagemessage == "") $villagemessage = "Please input the text here that will describe entering the village and what is going on.";
	if ($clockmessage == "") $clockmessage = "Looking at the Sundial, you can tell that it is `@%s";
	if ($calmessage == "") $calmessage = "A calendar on the wall of a hut tells you that it is ";
	if ($ownnewestmessage == "") $ownnewestmessage = "In your newly born state you wander the village, eyes full of wonder.";
	if ($othernewestmessage == "") $othernewestmessage = "You see %s wandering the village, eyes wide and full of wonder.";
	if ($fightnavtxt == "") $fightnavtxt = "Blades Boulevard";
	if ($marketnavtxt == "") $marketnavtxt = "Market St";
	if ($tavernnavtxt == "") $tavernnavtxt = "Tavern St";
	if ($infonavtxt == "") $infonavtxt = "Information";
	rawoutput("<form method='post' action='runmodule.php?module=modulebuilder&op=race' name='racebuilder'>");
	rawoutput("Module Name: <input value = '".$modulename."' size='25' name='modulename'><br>");
	rawoutput("Module Title: <input value = '".$moduletitle."' size='40' name='moduletitle'><br>");
	rawoutput("<small style='font-style: italic;'>Leave shared city blank if you are building a city.</small><br>");
	rawoutput("Required Race for Shared City: <input value = '".$sharedrace."' size='25' name='sharedrace'><br><br>");
	rawoutput("Race finds extra Gold or Gems? <select name='goldgem'><option".$usegold." value='0'>Gold</option><option".$usegem." value='1'>Gem</option><option".$usenone." value='2'>None</option></select><br>");
	rawoutput("Race Selection message for finding Extra gold or gem: <input value = '".$extragemgoldmessage."' size='60' name='extragemgoldmessage'><br>");
	rawoutput("Race Buff Type: <select name='raceboon'><option".$rbdef." value='defense'>Defense</option><option".$rbatk." value='attack'>Attack</option><option".$rbreg." value='regen'>Regeneration</option></select><br>");
	rawoutput("Buff Name: <input value = '".$buffname."' size='25' name='buffname'><br>");
	rawoutput("Buff Seed (Higher is Stronger): <select name='buffseed'><option".$bs1.">1</option><option".$bs2.">2</option><option".$bs3.">3</option><option".$bs4.">4</option><option".$bs5.">5</option></select><br><br>");
	rawoutput("PVP Seed (Higher is Stronger): <select name='pvpseed'><option".$pv1.">1</option><option".$pv2.">2</option><option".$pv3.">3</option><option".$pv4.">4</option><option".$pv5.">5</option></select><br><br>");
	rawoutput("Build City with Race?<select name='usevillage'><option".$usevil1." value='0'>No</option><option".$usevil2." value='1'>Yes</option></select><br><br>");
	rawoutput("<span style='font-style: italic;'>If you are not building a city ignore the rest of this form.</span><br><br>");
	rawoutput("Default city name: <input value = '".$villagename."' name='villagename'><br>");
	rawoutput("<span style='font-weight: bold; text-decoration: underline;'>Village Texts</span><br>");
	rawoutput("Village Header&nbsp;Text: <br><textarea cols='60' rows='20' name='villagemessage'>".$villagemessage."</textarea><br>");
	rawoutput("<small style='font-style: italic;'>%s should be in your string to get the game to display the time at that location.</small><br>");
	rawoutput("Village Time Message: <input size='60' name='clockmessage' value='".$clockmessage."'><br>");
	rawoutput("Village Calendar Message: <input size='60' name='calmessage' value='".$calmessage."'><br><br>");
	rawoutput("Village Title Description: <br>");
	rawoutput("Talkline: <input value = '".$villagesayline."' size='20' name='villagesayline' value='says'><br>");
	rawoutput("<span style='text-decoration: underline;'>Newest Player Message</span><br>");
	rawoutput("Self: <input size='60' name='ownnewestmessage' value='".$ownnewestmessage."'><br>");
	rawoutput("<small style='font-style: italic;'>%s inserts the players name into this line for others to see.</small><br>");
	rawoutput("Others: <input size='60' name='othernewestmessage' value='".$othernewestmessage."'><br><br>");
	rawoutput("<span style='text-decoration: underline;'>Village Navs</span><br>");
	rawoutput("Fight Navs: <input size='25' name='fightnavtxt' value='".$fightnavtxt."'><br>");
	rawoutput("Market Navs: <input size='25' name='marketnavtxt' value='".$marketnavtxt."'><br>");
	rawoutput("Tavern Navs: <input size='25' name='tavernnavtxt' value='".$tavernnavtxt."'><br>");
	rawoutput("Info Navs: <input name='infonavtxt' value='".$infonavtxt."'><br><br>");
	rawoutput("<input type='hidden' name='modulesubmit' value='done'> ");
	rawoutput("<input name='Submit' value='Submit' type='submit'><br></form>");
	addnav("","runmodule.php?module=modulebuilder&op=race");
}else{
	$code = "function race".$modulename."_getmoduleinfo(){\n";
	$code .= "	\$info = array(\n";
	$code .= "		\"name\"=>\"Race - ".$moduletitle."\",\n";
	$code .= "		\"version\"=>\"1.0\",\n";
	$code .= "		\"author\"=>\"".get_module_pref("author")." - Built with Module Builder by `3Lonny Luberts`0\",\n";
	$code .= "		\"category\"=>\"Races\",\n";
	$code .= "		\"download\"=>\"".get_module_pref("downloc")."\",\n";
	if (get_module_pref("vertxtloc") <> "") $code .= "		\"vertxtloc\"=>\"".get_module_pref("vertxtloc")."\",\n";
	$code .= "		\"settings\"=>array(\n";
	$code .= "			\"".$moduletitle." Race Settings,title\",\n";
	$code .= "			\"minedeathchance\"=>\"Percent chance for ".$moduletitle."s to die in the mine,range,0,100,1|40\",\n";
	if ($usevillage == 1) $code .= "\"villagename\"=>\"Name for the ".$moduletitle." village|".$villagename."\",\n";
	$code .= "			\"goldgemchance\"=>\"Percent chance for ".$moduletitle."s to find";
	
	if ($goldgem == 1){
	$code .= " a gem";
	}elseif ($goldgem == 0){
	$code .= " extra gold";
	} 
	$code .= " on battle victory,range,0,100,1|5\",\n";
	if ($goldgem < 2){
	$code .= "			\"goldgemmessage\"=>\"Message to display when finding ";
	if ($goldgem == 1){
	$code .= "a gem";
	}elseif ($goldgem == 0){
	$code .= "gold";
	}
	$code .= "|`&Your ".$moduletitle." senses tingle, and you notice ";
	if ($goldgem == 1){
	$code .= "a `%gem`&!\",\n";
	}elseif ($goldgem == 0){
	$code .= "a bag of gold`&!\",\n";
	}
	}
	if ($goldgem == 1){
	$code .= "			\"goldloss\"=>\"How much less gold (in percent) do the ".$moduletitle."s find?,range,0,100,1|15\",\n";
	}
	$code .= "			\"mindk\"=>\"How many DKs do you need before the race is available?,int|0\",\n";
	$code .= "		),\n";
	$code .= "	);\n";
	$code .= "	return \$info;\n";
	$code .= "}\n";
	$code .= "\n";
	$code .= "function race".$modulename."_install(){\n";
	if ($usevillage == 0){
	$code .= "	if (!is_module_installed(\"race".$sharedrace."\")) {\n";
	$code .= "		output(\"The ".$moduletitle." choose to live with ".$sharedrace."s.   You must install that race module.\");\n";
	$code .= "		return false;\n";
	}
	$code .= "	}\n";
	$code .= "\n";
	$code .= "	module_addhook(\"chooserace\");\n";
	$code .= "	module_addhook(\"setrace\");\n";
	$code .= "	module_addhook(\"newday\");\n";
	$code .= "	module_addhook(\"charstats\");\n";
	$code .= "	module_addhook(\"raceminedeath\");\n";
	$code .= "	module_addhook(\"battle-victory\");\n";
	$code .= "	module_addhook(\"creatureencounter\");\n";
	$code .= "	module_addhook(\"pvpadjust\");\n";
	$code .= "	module_addhook(\"adjuststats\");\n";
	$code .= "	module_addhook(\"racenames\");\n";
	if ($usevillage == 1){
	$code .= "module_addhook(\"villagetext\");\n";
	$code .= "module_addhook(\"travel\");\n";
	$code .= "module_addhook(\"validlocation\");\n";
	$code .= "module_addhook(\"validforestloc\");\n";
	$code .= "module_addhook(\"moderate\");\n";
	$code .= "module_addhook(\"changesetting\");\n";
	}
	$code .= "	return true;\n";
	$code .= "}\n";
	$code .= "\n";
	$code .= "function race".$modulename."_uninstall(){\n";
	$code .= "	global \$session;\n";
	if ($usevillage == 1){
	$code .= "\$vname = getsetting(\"villagename\", LOCATION_FIELDS);\n";
	$code .= "\$gname = get_module_setting(\"villagename\");\n";
	$code .= "\$sql = \"UPDATE \" . db_prefix(\"accounts\") . \" SET location='\$vname' WHERE location = '\$gname'\";\n";
	$code .= "db_query(\$sql);\n";
	$code .= "if (\$session['user']['location'] == \$gname) \$session['user']['location'] = \$vname;\n";
	}
	$code .= "	\$sql = \"UPDATE  \" . db_prefix(\"accounts\") . \" SET race='\" . RACE_UNKNOWN . \"' WHERE race='".$moduletitle."'\";\n";
	$code .= "	db_query(\$sql);\n";
	$code .= "	if (\$session['user']['race'] == '".$moduletitle."')\n";
	$code .= "		\$session['user']['race'] = RACE_UNKNOWN;\n";
	$code .= "	return true;\n";
	$code .= "}\n";
	$code .= "\n";
	$code .= "function race".$modulename."_dohook(\$hookname,\$args){\n";
	$code .= "	global \$session,\$resline;\n";
	$code .= "\n";
	if ($usevillage == 1){
	$code .= "\$city = get_module_setting(\"villagename\");\n";
	}else{
	$code .= "	if (is_module_active(\"race".$sharedrace."\")) {\n";
	$code .= "		\$city = get_module_setting(\"villagename\", \"race".$sharedrace."\");\n";
	$code .= "	} else {\n";
	$code .= "		\$city = getsetting(\"villagename\", LOCATION_FIELDS);\n";
	$code .= "	}\n";
	}
	$code .= "	\$race = \"".$moduletitle."\";\n";
	$code .= "	switch(\$hookname){\n";
	if ($usevillage == 1){
	$code .= "case \"changesetting\":\n";
	$code .= "if (\$args['setting'] == \"villagename\" && \$args['module']==\"race".$modulename."\") {\n";
	$code .= "if (\$session['user']['location'] == \$args['old'])\n";
	$code .= "	\$session['user']['location'] = \$args['new'];\n";
	$code .= "\$sql = \"UPDATE \" . db_prefix(\"accounts\") .\n";
	$code .= "	\" SET location='\" . \$args['new'] .\n";
	$code .= "	\"' WHERE location='\" . \$args['old'] . \"'\";\n";
	$code .= "db_query(\$sql);\n";
	$code .= "if (is_module_active(\"cities\")) {\n";
	$code .= "	\$sql = \"UPDATE \" . db_prefix(\"module_userprefs\") .\n";
	$code .= "		\" SET value='\" . \$args['new'] .\n";
	$code .= "		\"' WHERE modulename='cities' AND setting='homecity'\" .\n";
	$code .= "		\"AND value='\" . \$args['old'] . \"'\";\n";
	$code .= "	db_query(\$sql);\n";
	$code .= "}\n";
	$code .= "}\n";
	$code .= "break;\n";
	$code .= "case \"moderate\":\n";
	$code .= "	if (is_module_active(\"cities\")) {\n";
	$code .= "		tlschema(\"commentary\");\n";
	$code .= "		\$args[\"village-\$race\"]=sprintf_translate(\"City of %s\", \$city);\n";
	$code .= "		tlschema();\n";
	$code .= "	}\n";
	$code .= "	break;\n";
	$code .= "case \"validforestloc\":\n";
	$code .= "case \"validlocation\":\n";
	$code .= "	if (is_module_active(\"cities\"))\n";
	$code .= "		\$args[\$city] = \"village-\$race\";\n";
	$code .= "	break;\n";
	$code .= "case \"travel\":\n";
	$code .= "\$capital = getsetting(\"villagename\", LOCATION_FIELDS);\n";
	$code .= "\$hotkey = substr(\$city, 0, 1);\n";
	$code .= "tlschema(\"module-cities\");\n";
	$code .= "if (\$session['user']['location']==\$capital){\n";
	$code .= "	addnav(\"Safer Travel\");\n";
	$code .= "	addnav(array(\"%s?Go to %s\", \$hotkey, \$city),\"runmodule.php?module=cities&op=travel&city=\$city\");\n";
	$code .= "}elseif (\$session['user']['location']!=\$city){\n";
	$code .= "	addnav(\"More Dangerous Travel\");\n";
	$code .= "	addnav(array(\"%s?Go to %s\", \$hotkey, \$city),\"runmodule.php?module=cities&op=travel&city=\$city&d=1\");\n";
	$code .= "}\n";
	$code .= "if (\$session['user']['superuser'] & SU_EDIT_USERS){\n";
	$code .= "	addnav(\"Superuser\");\n";
	$code .= "	addnav(array(\"%s?Go to %s\", \$hotkey, \$city),\"runmodule.php?module=cities&op=travel&city=\$city&su=1\");\n";
	$code .= "}\n";
	$code .= "tlschema();\n";
	$code .= "break;\n";
	$code .= "case \"villagetext\":\n";
	$code .= "race".$modulename."_checkcity();\n";
	$code .= "if (\$session['user']['location'] == \$city){\n";
	$code .= "\$args['text']=array(\"`@`b`c".$villagemessage."`c`b\");\n";
	$code .= "\$args['schemas']['text'] = \"module-race".$modulename."\";\n";
	$code .= "\$args['clock']=\"`n`2".$clockmessage." `2.`n\";\n";
	$code .= "\$args['schemas']['clock'] = \"module-race".$modulename."\";\n";
	$code .= "if (is_module_active(\"calendar\")) {\n";
	$code .= "\$args['calendar'] = \"`n`2".$calmessage." `@%1\ \$s`2, `@%3\ \$s %2\ \$s`2, `@%4\ \$s`2.`n\";\n";
	$code .= "\$args['schemas']['calendar'] = \"module-race".$modulename."\";\n";
	$code .= "}\n";
	$code .= "\$args['title']=array(\"".$villagedescmessage." %s\", \$city);\n";
	$code .= "\$args['schemas']['title'] = \"module-race".$modulename."\";\n";
	$code .= "\$args['sayline']=\"".$villagesayline."\";\n";
	$code .= "\$args['schemas']['sayline'] = \"module-race".$modulename."\";\n";
	$code .= "\$args['talk']=\"`n`@Nearby some villagers ".$villagesayline.":`n\";\n";
	$code .= "\$args['schemas']['talk'] = \"module-race".$modulename."\";\n";
	$code .= "\$new = get_module_setting(\"newest-\$city\", \"cities\");\n";
	$code .= "if (\$new != 0) {\n";
	$code .= "\$sql =  \"SELECT name FROM \" . db_prefix(\"accounts\") .\n";
	$code .= "\" WHERE acctid='\$new'\";\n";
	$code .= "\$result = db_query_cached(\$sql, \"newest-\$city\");\n";
	$code .= "\$row = db_fetch_assoc(\$result);\n";
	$code .= "\$args['newestplayer'] = \$row['name'];\n";
	$code .= "\$args['newestid']=\$new;\n";
	$code .= "} else {\n";
	$code .= "\$args['newestplayer'] = \$new;\n";
	$code .= "\$args['newestid']=\"\";\n";
	$code .= "}\n";
	$code .= "if (\$new == \$session['user']['acctid']) {\n";
	$code .= "\$args['newest']=\"`n`2".$ownnewestmessage."\";\n";
	$code .= "} else {\n";
	$code .= "\$args['newest']=\"`n`2".$othernewestmessage."\";\n";
	$code .= "}\n";
	$code .= "\$args['schemas']['newest'] = \"module-race".$modulename."\";\n";
	$code .= "\$args['gatenav']=\"Village Gates\";\n";
	$code .= "\$args['schemas']['gatenav'] = \"module-race".$modulename."\";\n";
	$code .= "\$args['fightnav']=\"".$fightnavtxt."\";\n";
	$code .= "\$args['schemas']['fightnav'] = \"module-race".$modulename."\";\n";
	$code .= "\$args['marketnav']=\"".$marketnavtxt."\";\n";
	$code .= "\$args['schemas']['marketnav'] = \"module-race".$modulename."\";\n";
	$code .= "\$args['tavernnav']=\"".$tavernnavtxt."\";\n";
	$code .= "\$args['schemas']['tavernnav'] = \"module-race".$modulename."\";\n";
	$code .= "\$args['infonav']=\"".$infonavtxt."\";\n";
	$code .= "\$args['schemas']['infonav'] = \"module-race".$modulename."\";\n";
	$code .= "\$args['section']=\"village-\$race\";\n";
	$code .= "}\n";
	$code .= "break;\n";
	}
	$code .= "	case \"racenames\":\n";
	$code .= "		\$args[\$race] = \$race;\n";
	$code .= "		break;\n";
	$code .= "	case \"pvpadjust\":\n";
	$code .= "		if (\$args['race'] == \$race) {\n";
	$code .= "			\$args['creaturedefense']+=(".$pvpseed."+floor(\$args['creaturelevel']/5));\n";
	$code .= "		}\n";
	$code .= "		break;\n";
	$code .= "	case\"adjuststats\":\n";
	$code .= "		if (\$args['race'] == \$race) {\n";
	if ($raceboon == "defense"){
	$code .= "			\$args['defense'] += (2+floor(\$args['level']/5));\n";
	$code .= "			\$args['maxhitpoints'] -= round(\$args['maxhitpoints']*.05, 0);\n";
	}elseif ($raceboon == "attack"){
	$code .= "			\$args['attack'] += (2+floor(\$args['level']/5));\n";
	}
	$code .= "		}\n";
	$code .= "		break;\n";
	$code .= "	case \"raceminedeath\":\n";
	$code .= "		if (\$session['user']['race'] == \$race) {\n";
	$code .= "			\$args['chance'] = get_module_setting(\"minedeathchance\");\n";
	$code .= "			\$args['racesave'] = \"Fortunately your ".$moduletitle." skills let you escape unscathed.`n\";\n";
	$code .= "			\$args['schema']=\"module-race".$modulename."\";\n";
	$code .= "		}\n";
	$code .= "		break;\n";
	$code .= "	case \"charstats\":\n";
	$code .= "		if (\$session['user']['race']==\$race){\n";
	$code .= "			addcharstat(\"Vital Info\");\n";
	$code .= "			addcharstat(\"Race\", translate_inline(\$race));\n";
	$code .= "		}\n";
	$code .= "		break;\n";
	$code .= "	case \"chooserace\":\n";
	$code .= "		if (\$session['user']['dragonkills'] < get_module_setting(\"mindk\"))\n";
	$code .= "			break;\n";
	$code .= "		output(\"<a href='newday.php?setrace=\$race\$resline'>In the city of %s</a>, your race of `5".$moduletitle."s`0, ".$whatracedid." \", true);\n";
	$code .= "		addnav(\"`5".$moduletitle."`0\",\"newday.php?setrace=\$race\$resline\");\n";
	$code .= "		addnav(\"\",\"newday.php?setrace=\$race\$resline\");\n";
	$code .= "		break;\n";
	$code .= "	case \"setrace\":\n";
	$code .= "		if (\$session['user']['race']==\$race){\n";
	if ($raceboon == "defense"){
	$code .= "			output(\"`&As a ".$moduletitle.", your amazing reflexes allow you to respond very quickly to any attacks.`n\");\n";
	$code .= "			output(\"You gain extra defense!`n\");\n";
	}elseif ($raceboon == "attack"){
	$code .= "			output(\"`&As a ".$moduletitle.", your amazing dexterity allows you to attack very quickly.`n\");\n";
	$code .= "			output(\"You gain extra attack!`n\");\n";
	}elseif ($raeboon == "regen"){
	$code .= "			output(\"`&As a ".$moduletitle.", your amazing healing powers allow you to heal much more quickly.`n\");\n";
	$code .= "			output(\"You gain regeneration powers!`n\");\n";
	}
	$code .= "			output(\"".$extragemgoldmessage."`n\");\n";
	$code .= "			if (is_module_active(\"cities\")) {\n";
	$code .= "				if (\$session['user']['dragonkills']==0 &&\n";
	$code .= "						\$session['user']['age']==0){\n";
	$code .= "					set_module_setting(\"newest-\$city\",\n";
	$code .= "							\$session['user']['acctid'],\"cities\");\n";
	$code .= "				}\n";
	$code .= "				set_module_pref(\"homecity\",\$city,\"cities\");\n";
	$code .= "				if (\$session['user']['age'] == 0)\n";
	$code .= "					\$session['user']['location']=\$city;\n";
	$code .= "			}\n";
	$code .= "		}\n";
	$code .= "		break;\n";
	$code .= "	case \"battle-victory\":\n";
	$code .= "		if (\$session['user']['race'] != \$race) break;\n";
	$code .= "		if (\$args['type'] != \"forest\") break;\n";
	if ($goldgem < 2){
	$code .= "		if (\$session['user']['level'] < 15 &&\n";
	if ($goldgem == 1){
	$code .= "			e_rand(1,100) <= get_module_setting(\"goldgemchance\")) {\n";
	$code .= "			output(get_module_setting(\"goldgemmessage\").\"`n`0\");\n";
	$code .= "			\$session['user']['gems']++;\n";
	$code .= "			//debuglog(\"found a gem when slaying a monster, for being a ".$moduletitle.".\");\n";
	}elseif ($goldgem == 0){
	$code .= "			e_rand(1,100) <= get_module_setting(\"goldgemchance\")) {\n";
	$code .= "			output(get_module_setting(\"goldgemmessage\").\"`n`0\");\n";
	$code .= "			\$session['user']['gold']+= e_rand(1,100);\n";
	$code .= "			//debuglog(\"found extra gold when slaying a monster, for being a ".$moduletitle.".\");\n";
	}
	$code .= "		}\n";
	}
	$code .= "		break;\n";
	$code .= "\n";
	$code .= "	case \"creatureencounter\":\n";
	$code .= "		if (\$session['user']['race']==\$race){\n";
	$code .= "\n";
	$code .= "			race".$modulename."_checkcity();\n";
	$code .= "			\$loss = (100 - get_module_setting(\"goldloss\"))/100;\n";
	$code .= "			\$args['creaturegold']=round(\$args['creaturegold']*\$loss,0);\n";
	$code .= "		}\n";
	$code .= "		break;\n";
	$code .= "	case \"newday\":\n";
	$code .= "		if (\$session['user']['race']==\$race){\n";
	$code .= "			race".$modulename."_checkcity();\n";
	$code .= "			apply_buff(\"racialbenefit\",array(\n";
	$code .= "				\"name\"=>\"`@".$buffname."`0\",\n";
	if ($raceboon == "defense"){
	$code .= "				\"defmod\"=>\"(".htmlentities("<defense>")."?(1+((".$buffseed."+floor(".htmlentities("<level>")."/5))/".htmlentities("<defense>").")):0)\",\n";
	}elseif ($raceboon == "attack"){
	$code .= "				\"atkmod\"=>\"(".htmlentities("<attack>")."?(1+((".$buffseed."+floor(".htmlentities("<level>")."/5))/".htmlentities("<attack>").")):0)\",\n";
	}elseif ($raceboon == "regen"){
	//this will propably need tweaking
	$code .= "				\"regen\"=>\"(".htmlentities("<defense>")."?(1+((".$buffseed."+floor(".htmlentities("<level>")."/5))/".htmlentities("<defense>").")):0)\",\n";
	}
	$code .= "				\"badguydmgmod\"=>1.05,\n";
	$code .= "				\"allowinpvp\"=>1,\n";
	$code .= "				\"allowintrain\"=>1,\n";
	$code .= "				\"rounds\"=>-1,\n";
	$code .= "				\"schema\"=>\"module-race".$modulename."\",\n";
	$code .= "				)\n";
	$code .= "			);\n";
	$code .= "		}\n";
	$code .= "		break;\n";
	$code .= "	}\n";
	$code .= "\n";
	$code .= "	return \$args;\n";
	$code .= "}\n";
	$code .= "\n";
	$code .= "function race".$modulename."_checkcity(){\n";
	$code .= "	global \$session;\n";
	$code .= "	\$race=\"".$moduletitle."\";\n";
	if ($usevillage == 1){
	$code .= "\$city=get_module_setting(\"villagename\");\n";
	}else{
	$code .= "	if (is_module_active(\"race".$sharedrace."\")) {\n";
	$code .= "		\$city = get_module_setting(\"villagename\", \"race".$sharedrace."\");\n";
	$code .= "	} else {\n";
	$code .= "		\$city = getsetting(\"villagename\", LOCATION_FIELDS);\n";
	$code .= "	}\n";
	}
	$code .= "	\n";
	$code .= "	if (\$session['user']['race']==\$race && is_module_active(\"cities\")){\n";
	$code .= "		if (get_module_pref(\"homecity\",\"cities\")!=\$city){ \n";
	$code .= "			set_module_pref(\"homecity\",\$city,\"cities\");\n";
	$code .= "		}\n";
	$code .= "	}	\n";
	$code .= "	return true;\n";
	$code .= "}\n";
	$code .= "\n";
	$code .= "function race".$modulename."_run(){\n";
	$code .= "}\n";
$code = nl2br($code);
output("<?php");
rawoutput("<table style='width: 100%; text-align: left;' border='0' cellpadding='2' cellspacing='2'><tbody>");
rawoutput("<tr><td style='vertical-align: top;'><code>".$code."</code><br></td></tr></tbody></table>");
rawoutput("<form method='post' action='runmodule.php?module=modulebuilder&op=race' name='racebuilder'>");
rawoutput("<input type='hidden' name='modulename' value='".$modulename."'> ");
rawoutput("<input type='hidden' name='moduletitle' value='".$moduletitle."'> ");
rawoutput("<input type='hidden' name='villagemessage' value='".$villagemessage."'> ");
rawoutput("<input type='hidden' name='villagename' value='".$villagename."'> ");
rawoutput("<input type='hidden' name='extragemgoldmessage' value='".$extragemgoldmessage."'> ");
rawoutput("<input type='hidden' name='buffname' value='".$buffname."'> ");
rawoutput("<input type='hidden' name='sharedrace' value='".$sharedrace."'> ");
rawoutput("<input type='hidden' name='clockmessage' value='".$clockmessage."'> ");
rawoutput("<input type='hidden' name='calmessage' value='".$calmessage."'> ");
rawoutput("<input type='hidden' name='ownnewestmessage' value='".$ownnewestmessage."'> ");
rawoutput("<input type='hidden' name='othernewestmessage' value='".$othernewestmessage."'> ");
rawoutput("<input type='hidden' name='fightnavtxt' value='".$fightnavtxt."'> ");
rawoutput("<input type='hidden' name='marketnavtxt' value='".$marketnavtxt."'> ");
rawoutput("<input type='hidden' name='tavernnavtxt' value='".$tavernnavtxt."'> ");
rawoutput("<input type='hidden' name='infonavtxt' value='".$infonavtxt."'> ");
rawoutput("<input type='hidden' name='goldgem' value='".$goldgem."'> ");
rawoutput("<input type='hidden' name='raceboon' value='".$raceboon."'> ");
rawoutput("<input type='hidden' name='buffseed' value='".$buffseed."'> ");
rawoutput("<input type='hidden' name='pvpseed' value='".$pvpseed."'> ");
rawoutput("<input type='hidden' name='usevillage' value='".$usevillage."'> ");
rawoutput("<input type='hidden' name='modulesubmit' value=''> ");
rawoutput("<input name='Submit' value='Make Changes' type='submit'></form>");
addnav("","runmodule.php?module=modulebuilder&op=race");
}
?>