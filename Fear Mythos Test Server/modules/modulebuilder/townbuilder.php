<?php
if ($savemodule){
	$completecode = str_replace("\n","<br />", $completecode);
	$fgc = file_get_contents("./modules/modulebuilder/savedmodules/{$session['user']['acctid']}.txt");
	$user_savedmodules = unserialize($fgc);
	$user_savedmodules[$op][$modulename] = $completecode;
	$f = fopen("./modules/modulebuilder/savedmodules/{$session['user']['acctid']}.txt","w+");
	fwrite($f,serialize($user_savedmodules));
	fclose($f);
	
	$fgd = file_get_contents("./modules/modulebuilder/savedmodules/{$session['user']['acctid']}_vals.txt");
	$user_post = unserialize($fgd);
	$user_post[$op][$modulename] = $_POST;
	unset($user_post[$op][$modulename]['savemodule']);
	unset($user_post[$op][$modulename]['completecode']);
	$f = fopen("./modules/modulebuilder/savedmodules/{$session['user']['acctid']}_vals.txt","w+");
	fwrite($f,serialize($user_post));
	fclose($f);
	
	output("`@`bSAVED!`b`0`n");
}

if ($modulename == "" or $modulesubmit == ""){
//let make our form here
if ($pvpnav == "") $pvpnav = "Name the place for PVP";
if ($townpvpquip == "") $townpvpquip = "in the depths of";
if ($townpvptext == "") $townpvptext = "Describe the place where pvp takes place.";
if ($towntext == "") $towntext = "Describe entering the City ect....";
if ($clocktext == "") $clocktext = "The time on the clock is";
if ($caltext == "") $caltext = "The calendar shows";
if ($talkdesc == "") $talkdesc = "Nearby some villagers talk:";
if ($sayline == "") $sayline = "says";
if ($allowlodge == 1){
	$lodge1 = " selected='selected'";
}else{
	$lodge0 = " selected='selected'";
}
if ($allowweapshop == 1){
	$weapshop1 = " selected='selected'";
}else{
	$weapshop0 = " selected='selected'";
}
if ($allowarmshop == 1){
	$armshop1 = " selected='selected'";
}else{
	$armshop0 = " selected='selected'";
}
if ($allowforest == 1){
	$forest1 = " selected='selected'";
}else{
	$forest0 = " selected='selected'";
}
if ($allowgardens == 1){
	$gardens1 = " selected='selected'";
}else{
	$gardens0 = " selected='selected'";
}
if ($allowgypsy == 1){
	$gypsy1 = " selected='selected'";
}else{
	$gypsy0 = " selected='selected'";
}
if ($allowbank == 1){
	$bank1 = " selected='selected'";
}else{
	$bank0 = " selected='selected'";
}
if ($towntype == "") $towntype = "small town";
if ($gatenav == "") $gatenav = "City Gates";
if ($fightnav == "") $fightnav = "Battle Boulevard";
if ($marketnav == "") $marketnav = "Sellers Street";
if ($tavernnav == "") $tavernnav = "Anebriation Alley";
if ($infonav == "") $infonav = "Information";
rawoutput("<form method='post' action='runmodule.php?module=modulebuilder&op=town' name='townbuilder'>");
rawoutput("Module Name: <input value = '".$modulename."' size='25' name='modulename'><br>");
rawoutput("Module Title: <input value = '".$moduletitle."' size='25' name='moduletitle'><br>");
rawoutput("Default City Name: <input value = '".$townname."' size='30' name='townname'><br>");
rawoutput("City Short Desc: <input value = '".$towntype."' size='40' name='towntype'><br><br>");
rawoutput("<span style='font-weight: bold; text-decoration: underline;'>PVP</span><br>");
rawoutput("PVP Nav: <input value='".$pvpnav."' size='25' name='pvpnav'><br>");
rawoutput("PVP News Quip: <input size='50' name='townpvpquip' value='".$townpvpquip."'><br>");
rawoutput("PVP Text: <br><textarea class='input' cols='50' rows='6' name='townpvptext'>".$townpvptext."</textarea><br><br>");
rawoutput("<span style='font-weight: bold; text-decoration: underline;'>Town Texts</span><br>");
rawoutput("City Description Text:<br><textarea class='input' cols='50' rows='6' name='towntext'>".$towntext."</textarea><br>");
rawoutput("Clock Text: <input size='50' name='clocktext' value='".$clocktext."'><br>");
rawoutput("Calendar Text: <input size='50' name='caltext' value='".$caltext."'><br>");
rawoutput("Commentary Quip: <input size='50' name='talkdesc' value='".$talkdesc."'><br>");
rawoutput("Talkline: <input size='15' name='sayline' value='".$sayline."'><br><br>");
rawoutput("<span style='font-weight: bold; text-decoration: underline;'>City Contents</span><br>");
rawoutput("Allow Lodge? <select name='allowlodge'><option".$lodge1." value='1'>Yes</option><option".$lodge0." value='0'>No</option></select><br>");
rawoutput("Allow Weapon Shop? <select name='allowweapshop'><option".$weapshop1." value='1'>Yes</option><option".$weapshop0." value='0'>No</option></select><br>");
rawoutput("Allow Armor Shop? <select name='allowarmshop'><option".$armshop1." value='1'>Yes</option><option".$armshop0." value='0'>No</option></select><br>");
rawoutput("Allow Forest? <select name='allowforest'><option".$forest1." value='1'>Yes</option><option".$forest0." value='0'>No</option></select><br>");
rawoutput("Allow Gardens? <select name='allowgardens'><option".$gardens1." value='1'>Yes</option><option".$gardens0." value='0'>No</option></select><br>");
rawoutput("Allow Gypsy Tent? <select name='allowgypsy'><option".$gypsy1." value='1'>Yes</option><option".$gypsy0." value='0'>No</option></select><br>");
rawoutput("Allow Bank? <select name='allowbank'><option".$bank1." value='1'>Yes</option><option".$bank0." value='0'>No</option></select><br><br>");
rawoutput("<span style='font-weight: bold; text-decoration: underline;'>Nav Headers</span><br>");
rawoutput("City Gates: <input size='20' name='gatenav' value='".$gatenav."'><br>");
rawoutput("Fight Navs: <input size='20' name='fightnav' value='".$fightnav."'><br>");
rawoutput("Market Navs: <input size='20' name='marketnav' value='".$marketnav."'><br>");
rawoutput("Tavern Navs: <input size='20' name='tavernnav' value='".$tavernnav."'><br>");
rawoutput("Info Navs: <input size='20' name='infonav' value='".$infonav."'><br><br>");
rawoutput("<input type='hidden' name='modulesubmit' value='done'> ");
rawoutput("<input name='Submit' value='Submit' type='submit'></form>");
addnav("","runmodule.php?module=modulebuilder&op=town");
}else{
$code = "function ".$modulename."_getmoduleinfo(){\n";
$code .= "	\$info = array(\n";
$code .= "		\"name\"=>\"".$moduletitle."\",\n";
$code .= "		\"version\"=>\"1.0\",\n";
$code .= "		\"author\"=>\"".get_module_pref("author")." - Built with Module Builder by `3Lonny Luberts`0\",\n";
$code .= "		\"category\"=>\"Village\",\n";
$code .= "		\"download\"=>\"".get_module_pref("downloc")."\",\n";
if (get_module_pref("vertxtloc") <> "") $code .= "		\"vertxtloc\"=>\"".get_module_pref("vertxtloc")."\",\n";
$code .= "		\"settings\"=>array(\n";
$code .= "			\"".$moduletitle." Settings,title\",\n";
$code .= "			\"villagename\"=>\"Name for the ".$towntype."|".$townname."\",\n";
$code .= "			\"allowtravel\"=>\"Allow 'standard' travel to town?,bool|1\",\n";
$code .= "		),\n";
$code .= "		\"prefs\"=>array(\n";
$code .= "			\"".$moduletitle." User Preferences,title\",\n";
$code .= "			\"allow\"=>\"Is player allowed in?,bool|0\",\n";
$code .= "		),\n";
$code .= "	);\n";
$code .= "	return \$info;\n";
$code .= "}\n";
$code .= "\n";
$code .= "function ".$modulename."_install(){\n";
$code .= "	module_addhook(\"villagetext\");\n";
$code .= "	module_addhook(\"village\");\n";
$code .= "	module_addhook(\"travel\");\n";
$code .= "	module_addhook(\"validlocation\");\n";
$code .= "	module_addhook(\"moderate\");\n";
$code .= "	module_addhook(\"changesetting\");\n";
$code .= "	module_addhook(\"pvpstart\");\n";
$code .= "	module_addhook(\"pvpwin\");\n";
$code .= "	module_addhook(\"pvploss\");\n";
$code .= "	return true;\n";
$code .= "}\n";
$code .= "\n";
$code .= "function ".$modulename."_uninstall(){\n";
$code .= "	global \$session;\n";
$code .= "	\$vname = getsetting(\"villagename\", LOCATION_FIELDS);\n";
$code .= "	\$gname = get_module_setting(\"villagename\");\n";
$code .= "	\$sql = \"UPDATE \" . db_prefix(\"accounts\") . \" SET location='\$vname' WHERE location = '\$gname'\";\n";
$code .= "	db_query(\$sql);\n";
$code .= "	if (\$session['user']['location'] == \$gname)\n";
$code .= "		\$session['user']['location'] = \$vname;\n";
$code .= "	return true;\n";
$code .= "}\n";
$code .= "\n";
$code .= "function ".$modulename."_dohook(\$hookname,\$args){\n";
$code .= "	global \$session,\$resline;\n";
$code .= "	\$city = get_module_setting(\"villagename\");\n";
$code .= "	switch(\$hookname){\n";
$code .= "	case \"pvpwin\":\n";
$code .= "		if (\$session['user']['location'] == \$city) {\n";
$code .= "			\$args['handled']=true;\n";
$code .= "			addnews(\"`4%s`3 defeated `4%s`3 in fair combat ".$townpvpquip." %s.\", \$session['user']['name'],\$args['badguy']['creaturename'], \$args['badguy']['location']);\n";
$code .= "		}\n";
$code .= "		break;\n";
$code .= "	case \"pvploss\":\n";
$code .= "		if (\$session['user']['location'] == \$city) {\n";
$code .= "			\$args['handled']=true;\n";
$code .= "			addnews(\"`%%s`5 has been slain while attacking `^%s`5 ".$townpvpquip." `&%s`5.`n%s`0\", \$session['user']['name'], \$args['badguy']['creaturename'], \$args['badguy']['location'], \$args['taunt']);\n";
$code .= "		}\n";
$code .= "		break;\n";
$code .= "	case \"pvpstart\":\n";
$code .= "		if (\$session['user']['location'] == \$city) {\n";
$code .= "			\$args['atkmsg'] = \"`7".$townpvptext."`n`nYou have `^%s`7 PvP fights left for today.`n`n\";\n";
$code .= "			\$args['schemas']['atkmsg'] = 'module-".$modulename."';\n";
$code .= "		}\n";
$code .= "		break;\n";
$code .= "	case \"travel\":\n";
$code .= "		\$allow = get_module_pref(\"allow\") || get_module_setting(\"allowtravel\");\n";
$code .= "		\$capital = getsetting(\"villagename\", LOCATION_FIELDS);\n";
$code .= "		\$hotkey = substr(\$city, 0, 1);\n";
$code .= "		tlschema(\"module-cities\");\n";
$code .= "		if (\$session['user']['location']!=\$city && \$allow){\n";
$code .= "			addnav(\"More Dangerous Travel\");\n";
$code .= "			addnav(array(\"%s?Go to %s\", \$hotkey, \$city),\n";
$code .= "					\"runmodule.php?module=cities&op=travel&city=\$city&d=1\");\n";
$code .= "		}\n";
$code .= "		if (\$session['user']['superuser'] & SU_EDIT_USERS && \$allow){\n";
$code .= "			addnav(\"Superuser\");\n";
$code .= "			addnav(array(\"%s?Go to %s\", \$hotkey, \$city),\n";
$code .= "					\"runmodule.php?module=cities&op=travel&city=\$city&su=1\");\n";
$code .= "		}\n";
$code .= "		tlschema();\n";
$code .= "		break;\n";
$code .= "	case \"changesetting\":\n";
$code .= "		if (\$args['setting']==\"villagename\" && \$args['module']==\"".$modulename."\") {\n";
$code .= "			if (\$session['user']['location'] == \$args['old']) {\n";
$code .= "				\$session['user']['location'] = \$args['new'];\n";
$code .= "			}\n";
$code .= "			\$sql = \"UPDATE \" . db_prefix(\"accounts\") . \" SET location='\" .\n";
$code .= "				\$args['new'] . \"' WHERE location='\" . \$args['old'] . \"'\";\n";
$code .= "			db_query(\$sql);\n";
$code .= "		}\n";
$code .= "		break;\n";
$code .= "	case \"validlocation\":\n";
$code .= "		\$canvisit = 0;\n";
$code .= "		if (get_module_pref(\"allow\") || get_module_setting(\"allowtravel\")) \$canvisit = 1;\n";
$code .= "		if (!\$canvisit && (!isset(\$arg['all']) || !\$args['all'])) break;\n";
$code .= "		if (is_module_active(\"cities\")) \$args[\$city]=\"village-".$modulename."\";\n";
$code .= "		break;\n";
$code .= "	case \"moderate\":\n";
$code .= "		if (is_module_active(\"cities\")) {\n";
$code .= "			tlschema(\"commentary\");\n";
$code .= "			\$args[\"village-".$modulename."\"]=sprintf_translate(\"City of %s\", \$city);\n";
$code .= "			tlschema();\n";
$code .= "		}\n";
$code .= "		break;\n";
$code .= "	case \"villagetext\":\n";
$code .= "		\$deface = get_module_setting(\"defacedname\");\n";
$code .= "		if (\$session['user']['location'] == \$city){\n";
$code .= "			\$args['text']=array(\"`&`c`b%s`b`c`n`7".$towntext."`n\", \$city, \$city, \$deface);\n";
$code .= "			\$args['schemas']['text'] = \"module-".$modulename."\";\n";
$code .= "			\$args['clock']=\"`n`7".$clocktext." `&%s`7.`n\";\n";
$code .= "			\$args['schemas']['clock'] = \"module-".$modulename."\";\n";
$code .= "			if (is_module_active(\"calendar\")) {\n";
$code .= "				\$args['calendar']=\"`n`7".$caltext." `&%s`7, `&%s %s %s`7.`n\";\n";
$code .= "				\$args['schemas']['calendar'] = \"module-".$modulename."\";\n";
$code .= "			}\n";
$code .= "			\$args['title']=array(\"%s, the ".$moduletitle."\", \$city);\n";
$code .= "			\$args['schemas']['title'] = \"module-".$modulename."\";\n";
$code .= "			\$args['sayline']=\"`7".$sayline."`3\";\n";
$code .= "			\$args['schemas']['sayline'] = \"module-".$modulename."\";\n";
$code .= "			\$args['talk']=\"`n`&".$talkdesc."`n\";\n";
$code .= "			\$args['schemas']['talk'] = \"module-".$modulename."\";\n";
$code .= "			\$args['newest'] = \"\";\n";
//add yes/no for some of these blocked items
if ($allowlodge == 0) $code .= "			blocknav(\"lodge.php\");\n";
if ($allowweapshop == 0) $code .= "			blocknav(\"weapons.php\");\n";
if ($allowarmshop == 0) $code .= "			blocknav(\"armor.php\");\n";
$code .= "			blocknav(\"clan.php\");\n";
$code .= "			blocknav(\"pvp.php\");\n";
if ($allowforest == 0) $code .= "			blocknav(\"forest.php\");\n";
if ($allowgardens == 0) $code .= "			blocknav(\"gardens.php\");\n";
if ($allowgypsy == 0) $code .= "			blocknav(\"gypsy.php\");\n";
if ($allowbank == 0) $code .= "			blocknav(\"bank.php\");\n";
$code .= "			\$allow = get_module_pref(\"allow\") || get_module_setting(\"allowtravel\");\n";
$code .= "			if(!\$allow) {\n";
$code .= "				blockmodule(\"cities\");\n";
$code .= "			}\n";
$code .= "			blockmodule(\"tynan\");\n";
$code .= "			blockmodule(\"spookygold\");\n";
$code .= "			blockmodule(\"scavenge\");\n";
$code .= "			blockmodule(\"caravan\");\n";
$code .= "			blockmodule(\"clantrees\");\n";
$code .= "			\$args['schemas']['newest'] = \"module-".$modulename."\";\n";
$code .= "			\$args['gatenav']=\"".$gatenav."\";\n";
$code .= "			\$args['schemas']['gatenav'] = \"module-".$modulename."\";\n";
$code .= "			\$args['fightnav']=\"".$fightnav."\";\n";
$code .= "			\$args['schemas']['fightnav'] = \"module-".$modulename."\";\n";
$code .= "			\$args['marketnav']=\"".$marketnav."\";\n";
$code .= "			\$args['schemas']['marketnav'] = \"module-".$modulename."\";\n";
$code .= "			\$args['tavernnav']=\"".$tavernnav."\";\n";
$code .= "			\$args['schemas']['tavernnav'] = \"module-".$modulename."\";\n";
$code .= "			\$args['section']=\"village-".$modulename."\";\n";
$code .= "			\$args['infonav']=\"".$infonav."\";\n";
$code .= "			\$args['schemas']['infonav'] = \"module-".$modulename."\";\n";
$code .= "		}\n";
$code .= "		break;\n";
$code .= "	case \"village\":\n";
$code .= "		if (\$session['user']['location']==\$city){\n";
$code .= "			tlschema(\$args['schemas']['gatenav']);\n";
$code .= "			addnav(\$args['gatenav']);\n";
$code .= "			tlschema();\n";
$code .= "			addnav(\"S?".$pvpnav."\",\"pvp.php?campsite=1\");\n";
$code .= "		}\n";
$code .= "		break;\n";
$code .= "	}\n";
$code .= "	return \$args;\n";
$code .= "}\n";
$code .= "\n";
$code .= "function ".$modulename."_run(){\n";
$code .= "}\n";

rawoutput("<form method='post' action='runmodule.php?module=modulebuilder&op=town' name='citybuilder'>");
rawoutput("<input type='hidden' name='modulename' value='".$modulename."'> ");
rawoutput("<input type='hidden' name='moduletitle' value='".$moduletitle."'> ");
rawoutput("<input type='hidden' name='towntitle' value='".$towntitle."'> ");
rawoutput("<input type='hidden' name='townname' value='".$townname."'> ");
rawoutput("<input type='hidden' name='pvpnav' value='".$pvpnav."'> ");
rawoutput("<input type='hidden' name='townpvpquip' value='".$townpvpquip."'> ");
rawoutput("<input type='hidden' name='townpvptext' value='".$townpvptext."'> ");
rawoutput("<input type='hidden' name='towntext' value='".$towntext."'> ");
rawoutput("<input type='hidden' name='clocktext' value='".$clocktext."'> ");
rawoutput("<input type='hidden' name='caltext' value='".$caltext."'> ");
rawoutput("<input type='hidden' name='talkdesc' value='".$talkdesc."'> ");
rawoutput("<input type='hidden' name='sayline' value='".$sayline."'> ");
rawoutput("<input type='hidden' name='allowlodge' value='".$allowlodge."'> ");
rawoutput("<input type='hidden' name='allowweapshop' value='".$allowweapshop."'> ");
rawoutput("<input type='hidden' name='allowarmshop' value='".$allowarmshop."'> ");
rawoutput("<input type='hidden' name='allowforest' value='".$allowforest."'> ");
rawoutput("<input type='hidden' name='allowgardens' value='".$allowgardens."'> ");
rawoutput("<input type='hidden' name='allowgypsy' value='".$allowgypsy."'> ");
rawoutput("<input type='hidden' name='allowbank' value='".$allowbank."'> ");
rawoutput("<input type='hidden' name='gatenav' value='".$gatenav."'> ");
rawoutput("<input type='hidden' name='fightnav' value='".$fightnav."'> ");
rawoutput("<input type='hidden' name='marketnav' value='".$marketnav."'> ");
rawoutput("<input type='hidden' name='tavernnav' value='".$tavernnav."'> ");
rawoutput("<input type='hidden' name='infonav' value='".$infonav."'> ");
rawoutput("<input type='hidden' name='completecode' value='".htmlspecialchars($code, ENT_QUOTES)."'> ");
rawoutput("<input type='hidden' name='modulesubmit' value=''> ");
rawoutput("<input name='Submit' value='Make Changes' type='submit'>");
rawoutput("<input name='savemodule' value='Save Module' type='submit'></form>");
addnav("","runmodule.php?module=modulebuilder&op=town");

rawoutput("<table style='width: 100%; text-align: left;' border='0' cellpadding='2' cellspacing='2'><tbody>");
rawoutput("<tr><td style='vertical-align: top;'><pre><code>".highlight_string("<?php\n$code\n?>",true)."</code></pre><br></td></tr></tbody></table>");
}
?>