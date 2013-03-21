<?php
if ($modulename == "" or $modulesubmit == ""){
//need to add code to allow making changes
if ($eventintrostory == "") $eventintrostory = "You come accross something! ";
if ($thebigquestion == "") $thebigquestion = "Whatever shall you do?";
if ($yesoption == "") $yesoption = "Do something?";
if ($nooption == "") $nooption = "Continue on your way.";
if ($notext == "") $notext = "You continue on your way...";
if ($yestext == "") $yestext = "You decide to give it a whirl...";
if ($eventpercent == "") $eventpercent = "40";
if ($goodeffectmessage == "") $goodeffectmessage = "Lucky day, you get \$amt stuff.";
if ($badeffectmessage == "") $badeffectmessage = "Awwww.... you loose \$amt stuff!";
rawoutput("<form method='post' action='runmodule.php?module=modulebuilder&op=event' name='eventbuilder'>");
rawoutput("Module name: <input value = '".$modulename."' size='25' name='modulename'><br>");
rawoutput("Module title: <input value = '".$moduletitle."' size='40' name='moduletitle'><br><br>");
rawoutput("Special Works for...<br>");
rawoutput("forest <select name='fspec'><option value='1'>Yes</option><option value='0'>No</option></select><br>");
rawoutput("travel <select name='tspec'><option value='1'>Yes</option><option value='0'>No</option></select><br>");
rawoutput("village <select name='vspec'><option value='1'>Yes</option><option value='0'>No</option></select><br><br>");
rawoutput("Event Introduction Story:<br><textarea cols='60' rows='20' name='eventintrostory'>".$eventintrostory."</textarea><br>");
rawoutput("The Big Question<br><textarea cols='60' rows='4' name='thebigquestion'>".$thebigquestion."</textarea><br>");
rawoutput("Yes Option <input name='yesoption' value='".$yesoption."'><br>");
rawoutput("No Option <input name='nooption' value='".$nooption."'><br><br>");
rawoutput("Player picks no to running event text:<br><textarea cols='60' rows='8' name='notext'>".$notext."</textarea><br>");
rawoutput("Run Event Text:<br><textarea cols='60' rows='12' name='yestext'>".$yestext."</textarea><br>");
rawoutput("Pecentage for good outcome: <input size='3' name='eventpercent' value='".$eventpercent."'><br><br>");
rawoutput("Good Outcome Reward: <select name='goodeffect'><option value='gold'>Gold</option><option value='gem'>Gem</option><option value='charm'>Charm</option><option value='attack'>Attack</option><option value='regen'>Regeneration</option><option value='hitpoints'>Hitppoints</option><option value='maxhitpoints'>Max Hitpoints</option><option value='defense'>Defense</option></select><br>");
rawoutput("<small><span style='font-style: italic;'>use \$amt in the message to output the amount of loss.<br></span>");
rawoutput("</small>Good Outcome Message: <br><textarea cols='60' rows='6' name='goodeffectmessage'>".$goodeffectmessage."</textarea><br><br>");
rawoutput("Bad Outcome Effect: <select name='badeffect'><option value='gold'>Gold</option><option value='gem'>Gem</option><option value='charm'>Charm</option><option value='attack'>Attack</option><option value='sickness'>Sickness</option><option value='hitpoints'>Hitpoints</option><option value='defense'>Defense</option></select><br>");
rawoutput("<small><span style='font-style: italic;'>use \$amt in the message to output the amount of loss.</span></small><br>");
rawoutput("Bad Outcome Message:<br><textarea cols='60' rows='6' name='badeffectmessage'>".$badeffectmessage."</textarea><br><br>");
rawoutput("<input type='hidden' name='modulesubmit' value='done'> ");
rawoutput("<input name='Submit' value='Submit' type='submit'></form>");
addnav("","runmodule.php?module=modulebuilder&op=event");
}else{
$code .= "function ".$modulename."_getmoduleinfo(){\n";
$code .= "	\$info = array(\n";
$code .= "		\"name\"=>\"".$moduletitle."\",\n";
$code .= "		\"version\"=>\"1.0\",\n";
$code .= "		\"author\"=>\"".get_module_pref("author")." - Built with Module Builder by `3Lonny Luberts`0\",\n";
$howmanyspec = $fspec + $vspec + $tspec;
if ($howmanyspec > 1){
$code .= "		\"category\"=>\"Multi Specials\",\n";
}else{
if ($fspec == 1){
$code .= "		\"category\"=>\"Forest Specials\",\n";
}elseif ($vspec == 1){
$code .= "		\"category\"=>\"Village Specials\",\n";
}elseif ($tspec == 1){
$code .= "		\"category\"=>\"Travel Specials\",\n";
}
}
$code .= "		\"download\"=>\"".get_module_pref("downloc")."\",\n";
if (get_module_pref("vertxtloc") <> "") $code .= "		\"vertxtloc\"=>\"".get_module_pref("vertxtloc")."\",\n";
$code .= "		\"settings\"=>array(\n";
$code .= "			\"".$moduletitle." Settings,title\",\n";
$code .= "			\"basestat\"=>\"Stat to base increase and decrease on,enum,charm,Charm,maxhitpoints,Hitpoints,dragonKills,Dragonkills,attack,Attack,defense,Defense\",\n";
$code .= "			\"baseperc\"=>\"Percent to increase/decrease stat,range,0,100,1|50\",\n";
$code .= "		),\n";
$code .= "	);\n";
$code .= "	return \$info;\n";
$code .= "}\n";
$code .= "\n";
$code .= "function ".$modulename."_install(){\n";
if ($tspec == 1){
$code .= "	module_addeventhook(\"travel\",\n";
$code .= "			\"return (is_module_active('cities')?100:0);\");\n";
}
if ($fspec == 1){
$code .= "	module_addeventhook(\"forest\",\n";
$code .= "			\"return (is_module_active('cities')?0:100);\");\n";
}
if ($vspec == 1){
$code .= "	module_addeventhook(\"village\",\n";
$code .= "			\"return (is_module_active('cities')?0:100);\");\n";
}
$code .= "	return true;\n";
$code .= "}\n";
$code .= "\n";
$code .= "function ".$modulename."_uninstall(){\n";
$code .= "	return true;\n";
$code .= "}\n";
$code .= "\n";
$code .= "function ".$modulename."_dohook(\$hookname,\$args){\n";
$code .= "	return \$args;\n";
$code .= "}\n";
$code .= "\n";
$code .= "function ".$modulename."_runevent(\$type,\$link) {\n";
$code .= "	global \$session;\n";
$code .= "	\$from = \$link;\n";
$code .= "	\$op = httpget('op');\n";
$code .= "	\$session['user']['specialinc'] = \"module:".$modulename."\";\n";
$code .= "	if (\$op==\"\"){\n";
$code .= "		output(\"`2".$eventintrostory."`n`n\");\n";
$code .= "		output(\"".$thebigquestion."`0\");\n";
$code .= "		addnav(\"".$yesoption."\",\$from.\"op=yes\");\n";
$code .= "		addnav(\"".$nooption."\",\$from.\"op=no\");\n";
$code .= "	} elseif (\$op==\"no\") {\n";
$code .= "		output(\"`2".$notext."`0\");\n";
$code .= "		\$session['user']['specialinc'] = \"\";\n";
$code .= "	}  else {\n";
$code .= "		output(\"`2".$yestext."`n`n`0\");\n";
$code .= "\$basestat = get_module_setting(\"basestat\");\n";
$code .= "\$baseperc = get_module_setting(\"baseperc\") * .01;\n";
$code .= "		if (e_rand(0,100) <= ".$eventpercent.") {\n";
if ($goodeffect == "gold"){
$code .= "			\$amt = round(\$session['user'][\$basestat] * \$baseperc);\n";
$code .= "			if (\$amt > \$session['user']['gold']) \$amt = \$session['user']['gold'];\n";
$code .= "			\$session['user']['gold']+=\$amt;\n";
}elseif ($goodeffect == "gems"){
$code .= "			\$session['user']['gems']++;\n";
$code .= "			\$amt = 1;\n";
}elseif ($goodeffect == "charm"){
$code .= "			\$session['user']['charm']++;\n";
$code .= "			\$amt = 1;\n";
}elseif ($goodeffect == "attack"){
$code .= "			\$amt = round(\$session['user']['attack']*0.05, 0);\n";
$code .= "			\$session['user']['attack']+=\$amt;\n";
}elseif ($goodeffect == "regen"){
$code .= "apply_buff('regenerate', array(\n";
$code .= "\"startmsg\"=>\"`^You begin to regenerate!\",\n";
$code .= "\"name\"=>\"`%Regeneration\",\n";
$code .= "\"rounds\"=>erand(1,(\$baseperc * 10)),\n";
$code .= "\"wearoff\"=>\"You have stopped regenerating\",\n";
$code .= "\"regen\"=>\$session['user'][\$basestat],\n";
$code .= "\"effectmsg\"=>\"You regenerate for {damage} health.\",\n";
$code .= "\"effectnodmgmsg\"=>\"You have no wounds to regenerate.\",\n";
$code .= "\"schema\"=>\"module-".$modulename."\"\n";
$code .= "));\n";
}elseif ($goodeffect == "hitpoints"){
$code .= "			\$amt = round(\$session['user']['hitpoints']*0.05, 0);\n";
$code .= "			\$session['user']['hitpoints']+=\$amt;\n";
}elseif ($goodeffect == "maxhitpoints"){
$code .= "			\$session['user']['maxhitpoints']++;\n";
$code .= "			\$amt = 1;\n";
}elseif ($goodeffect == "defense"){
$code .= "			\$amt = round(\$session['user']['defense']*0.05, 0);\n";
$code .= "			\$session['user']['defense']+=\$amt;\n";
}
$code .= "			output(\"`^".$goodeffectmessage."`0\");\n";
$code .= "			\$session['user']['specialinc'] = \"\";\n";
$code .= "		} else {\n";
if ($badeffect == "gold"){
//this amt is not working at all
$code .= "			\$amt = round(\$session['user'][\$basestat] * \$baseperc);\n";
$code .= "			if (\$amt > \$session['user']['gold']) \$amt = \$session['user']['gold'];\n";
$code .= "			\$session['user']['gold']-=\$amt;\n";
}elseif ($badeffect == "gems"){
$code .= "			\$session['user']['gems']--;\n";
$code .= "			if (\$session['user']['gems'] < 0) \$session['user']['gems'] = 0;\n";
}elseif ($badeffect == "charm"){
$code .= "			\$amt = round(\$session['user']['charm']*0.05, 0);\n";
$code .= "			\$session['user']['charm']-=\$amt;\n";
}elseif ($badeffect == "attack"){
$code .= "			\$session['user']['attack']--;\n";
$code .= "			\$amt = 1;\n";
}elseif ($badeffect == "sickness"){
$code .= "apply_buff('sick', array(\n";
$code .= "\"startmsg\"=>\"`^You feel sick!\",\n";
$code .= "\"name\"=>\"`%You feel sick.\",\n";
$code .= "\"rounds\"=>erand(1,(\$baseperc * 10)),\n";
$code .= "\"wearoff\"=>\"You feel much better\",\n";
$code .= "\"defmod\"=>.9,\n";
$code .= "\"atkmod\"=>.9, \n";
$code .= "\"roundmsg\"=>\"`7You feel weak while you try to fight.\",\n";
$code .= "\"schema\"=>\"module-".$modulename."\"\n";
$code .= "));\n";
}elseif ($badeffect == "hitpoints"){
$code .= "			\$amt = round(\$session['user']['hitpoints']*0.05, 0);\n";
$code .= "			\$session['user']['hitpoints']-=\$amt;\n";
$code .= "			if (\$session['user']['hitpoints'] < 0) \$session['user']['hitpoints'] = 1;\n";
}elseif ($badeffect == "defense"){
$code .= "			\$session['user']['defense']--;\n";
$code .= "			\$amt = 1;\n";
}
$code .= "			output(\"`4".$badeffectmessage."`n`n\");\n";
$code .= "			\$session['user']['specialinc'] = \"\";\n";
$code .= "		}\n";
$code .= "	}\n";
$code .= "}\n";
$code .= "\n";
$code .= "function ".$modulename."_run(){\n";
$code .= "}\n";
$code = nl2br($code);
output("<?php");
rawoutput("<table style='width: 100%; text-align: left;' border='0' cellpadding='2' cellspacing='2'><tbody>");
rawoutput("<tr><td style='vertical-align: top;'><code>".$code."</code><br></td></tr></tbody></table>");
output("?>");
rawoutput("<form method='post' action='runmodule.php?module=modulebuilder&op=event' name='eventbuilder'>");
rawoutput("<input type='hidden' name='modulename' value='".$modulename."'> ");
rawoutput("<input type='hidden' name='moduletitle' value='".$moduletitle."'> ");
rawoutput("<input type='hidden' name='eventintrostory' value='".$eventintrostory."'> ");
rawoutput("<input type='hidden' name='thebigquestion' value='".$thebigquestion."'> ");
rawoutput("<input type='hidden' name='yesoption' value='".$yesoption."'> ");
rawoutput("<input type='hidden' name='nooption' value='".$nooption."'> ");
rawoutput("<input type='hidden' name='notext' value='".$notext."'> ");
rawoutput("<input type='hidden' name='yestext' value='".$yestext."'> ");
rawoutput("<input type='hidden' name='eventpercent' value='".$eventpercent."'> ");
rawoutput("<input type='hidden' name='goodeffectmessage' value='".$goodeffectmessage."'> ");
rawoutput("<input type='hidden' name='badeffectmessage' value='".$badeffectmessage."'> ");
rawoutput("<input type='hidden' name='modulesubmit' value=''> ");
rawoutput("<input name='Submit' value='Make Changes' type='submit'></form>");
addnav("","runmodule.php?module=modulebuilder&op=event");
}
?>