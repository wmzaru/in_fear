<?php

function dianfall_getmoduleinfo(){
	$info = array(
		"name"=>"Diancecht's Waterfall",
		"author"=>"Chris Vorndran<br>Idea by: `QJosh Elwell",
		"version"=>"1.41",
		"category"=>"Gardens",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=89",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"Allows for users to resurrect or transfer favor to other players.",
		"settings"=>array(
			"Diancecht's Waterfall Settings,title",
			"trans"=>"Allow the transfer of favor to a dead character?,bool|1",
			"ressing"=>"Allow resurrections?,bool|1",
			"gem"=>"How many gems does it cost to resurrect?,int|10",
			"waterloc"=>"Location of Diancecht's Waterfall,location|".getsetting("villagename", LOCATION_FIELDS),
			"Oddly enough this will be in the Gardens. We will use this setting to check the users location... so if you wish to have only Elven villagers access to it set it to the Elven City,note",
		),
		"prefs"=>array(
			"Diancecht's Waterfall Prefs,title",
			"res"=>"Has this user been resurrected?,bool|0",
			"has"=>"Has this user used any of Diancecht's abilities?,bool|0",
			"who"=>"Who last resurrected this user,text|",
		),
	);
	return $info;
}
function dianfall_install(){
	module_addhook("footer-shades");
	module_addhook("footer-graveyard");
	//module_addhook("footer-runmodule");
	module_addhook("gardens");
	module_addhook("changesetting");
	module_addhook("newday");
	return true;
}
function dianfall_uninstall(){
	return true;
}
function dianfall_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "footer-shades":
		case "footer-graveyard":
			if (get_module_pref("res")) redirect("runmodule.php?module=dianfall&op=intvene");
			break;
		// case "footer-runmodule":
			// if (httpget("module") == "valhalla" && get_module_pref("res")) redirect("runmodule.php?module=dianfall&op=intvene");
			// break;
		case "gardens":
			if ($session['user']['location'] == get_module_setting("waterloc")){
				addnav("Explore");
				addnav("Diancecht's Waterfall","runmodule.php?module=dianfall&op=enter");
			}
			break;
		case "changesetting":
			if ($args['setting'] == "villagename") {
				if ($args['old'] == get_module_setting("waterloc")) {
					set_module_setting("waterloc", $args['new']);
				}
			}
			break;
		case "newday":
			set_module_pref("has",0);
			if (get_module_pref("res")){
				set_module_pref("res",0);
				$session['user']['deathpower']+=100;
				/*
					seeing as how newday will take away 100 favor, if the resurrection is true
					let's give it back to them.
				*/
			}
			break;
		}
	return $args;
}
function dianfall_run(){
	global $session;
	$op = httpget('op');
	$gem = get_module_setting("gem");
	$name = httppost('name');
	$id = httpget('id');
	$dp = httpget('dp');
	page_header("Diancecht's Waterfall");
	
	switch ($op){
		case "enter":
			output("`@Passing and weaving through the entangled vines of the `6%s Gardens`@, you come upon a clearing.",get_module_setting("waterloc"));
			output("At one end, you discover a thick patch of shrubbery; at the other, you find a waterfall.");
			output("Behind the waterfall, you can just make out a small opening.");
			output("Dashing over, you jump from rock to rock, no bounds to your agility.");
			output("You wander inside, and see a dimly lit fire.");
			output("Next to the fire, a tall Druid stands.");
			if (!get_module_pref("has")){
				output("`n`n`@\"`3Welcome, my child.");
				output("Oh... you are not Airmid... who are you, and what do you wish of me?`@\"");
				addnav("Wishes");
				if (get_module_setting("ressing")) addnav("Resurrection","runmodule.php?module=dianfall&op=find");
				if (get_module_setting("trans")) addnav("Transfer Favor","runmodule.php?module=dianfall&op=trans");
			}else{
				output("`n`n`@You step forward, seeing the familiar face of Diancecht.");
				output("With his quickness, he bashes you over the head, and hurls you from behind the Waterfall.");
				output("He casts a glance at you, and then sighs, mumbling under his breath...\"`3Airmid... where are you?`@\"");
			}
			break;
		case "find":
			output("`@The Druid pulls back his hood and smiles, \"`3My name is Diancecht... and I am the Healer of the Gods.");
			output("I see that you have come to me, wishing to resurrect a dear, departed friend... am I correct?`@\"");
			output("`n`nYou nod and then take a step closer to the fire, your body soaked from the Waterfall.");
			output("`n`n\"`3Well, I may be able to help ye...");
			output("Do ye know anything of Resurrection?`@\"");
			output("`n`nYou nod once more, and then pull down your helm.");
			output("`n`n\"`3Good... then this will be much easier...");
			output("This service costs `%%s Gems`3.`@\"",$gem);
			if ($session['user']['gems'] >= $gem){
				rawoutput("<form action='runmodule.php?module=dianfall&op=name' method='POST'>");
				output("`@\"`3Please tell me the name of the person you wish to resurrect...`@\"");
				rawoutput("<input name='name' size='25'>");
				rawoutput("<input type='submit' class='button' value='".translate_inline("Preview List")."'></form>",true);
				addnav("","runmodule.php?module=dianfall&op=name");
			}else{
				output("`@Diancecht looks at you and scoffs.");
				output("\"`3I am sorry, but you do not have the required gems for this action... you need `%%s `3more.`@\"",$gem-$session['user']['gems']);
			}
			break;
		case "trans":
			output("`@Diancecht traces a finger along your chest, and smiles softly.");
			output("\"`3You have decided to part with some of your Favor of Ramius... I see.");
			output("I estimate, that you have a total of `^%s `3Favor.`@\"",$session['user']['deathpower']);
			if ($session['user']['deathpower'] == 0){
				output("`n`n`@Diancecht takes a step back, quite appalled.");
				output("\"`3I am sorry... but my services are of no use to you...");
				output("Ramius would be quite displeased with me...`@\"");
			}else{
				output("`n`n`@\"`3Please enter a name, that you wish to transfer Favor to.");
				output("Remember, I will only bring up names of the departed.`@\"");
				rawoutput("<form action='runmodule.php?module=dianfall&op=tname' method='POST'>");
				rawoutput("<input name='name' size='25'>");
				rawoutput("<input type='submit' class='button' value='".translate_inline("Preview List")."'></form>",true);
				addnav("","runmodule.php?module=dianfall&op=tname");	
			}
			break;
		case "tname":
			$search = "%";
			for ($i=0;$i<strlen($name);$i++){
				$search.=substr($name,$i,1)."%";
			}
			debug($search);
			$sql = "SELECT name,acctid,deathpower FROM ".db_prefix("accounts")." WHERE alive=0 AND (name LIKE '$search' OR login LIKE '$search') LIMIT 25";
			$res = db_query($sql);
			$count = db_num_rows($res);
			$n = translate_inline("Name");
			$fav = translate_inline("Favor");
			rawoutput("<table border=0 cellpadding=0><tr><td>$n</td><td>$fav</td></tr>");
			while($row = db_fetch_assoc($res)){
				$ac = $row['acctid'];
				rawoutput("<tr><td>");
				rawoutput("<a href='runmodule.php?module=dianfall&op=tfin&id=".rawurlencode($ac)."&dp=".rawurlencode($row['deathpower'])."'>");
				output_notl("%s", $row['name']);
				rawoutput("</a>");
				addnav("","runmodule.php?module=dianfall&op=tfin&id=".rawurlencode($ac)."&dp=".rawurlencode($row['deathpower']));
				rawoutput("</td><td>");
				output_notl("`@%s`0",$row['deathpower']);
				rawoutput("</td></tr>");
			}
			rawoutput("</table>");						
			break;
		case "name":
			$search = "%";
			for ($i=0;$i<strlen($name);$i++){
				$search.=substr($name,$i,1)."%";
			}
			debug($search);
			$sql = "SELECT name,acctid,level FROM ".db_prefix("accounts")." WHERE alive=0 AND (name LIKE '$search' OR login LIKE '$search') LIMIT 25";
			$res = db_query($sql);
			$count = db_num_rows($res);
			$n = translate_inline("Name");
			$lev = translate_inline("Level");
			rawoutput("<table border=0 cellpadding=0><tr><td>$n</td><td>$lev</td></tr>");
			while($row = db_fetch_assoc($res)){
				$ac = $row['acctid'];
				rawoutput("<tr><td>");
				rawoutput("<a href='runmodule.php?module=dianfall&op=final&id=".rawurlencode($ac)."'>");
				output_notl("%s", $row['name']);
				rawoutput("</a>");
				addnav("","runmodule.php?module=dianfall&op=final&id=".rawurlencode($ac));
				rawoutput("</td><td>");
				output_notl("`@%s`0",$row['level']);
				rawoutput("</td></tr>");
			}
			rawoutput("</table>");	
			break;
		case "final":
			$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=$id LIMIT 1";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			set_module_pref("has",1);
			set_module_pref("res",1,"dianfall",$id);
			set_module_pref("who",$session['user']['name'],"dianfall",$id);
			output("`@Diancecht looks at you and nods, \"`3It is done... %s `3has been resurrected.`@\"",$row['name']);
			addnews("%s `@has most graciously talked with Ramius. `%%s `@is to be resurrected!",$session['user']['name'],$row['name']);
			$session['user']['gems']-=$gem;
			break;
		case "tfin":
			$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=$id LIMIT 1";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$amount = abs(httppost('amount'));
			if ($amount == NULL){
				output("`@\"`3So, you have selected to transfer Favor to %s?\"`@",$row['name']);
				output("`n`n\"`3How much would you like to transfer?`@\"");
				rawoutput("<form action='runmodule.php?module=dianfall&op=tfin&id=$id&dp=$dp' method='POST'>");
				rawoutput("<input name='amount' size=5>");
				rawoutput("<input type='submit' class='button' value='".translate_inline("Transfer")."'></form>",true);
			}else{
				if ($amount > $session['user']['deathpower']){
						$amount = $session['user']['deathpower'];
					}
					$session['user']['deathpower']-=$amount;
					$d = $amount+$dp;
					$sql = "UPDATE ".db_prefix("accounts")." SET deathpower=$d WHERE acctid=$id LIMIT 1";
					db_query($sql);
					set_module_pref("has",1);
					output("`@\"`3You have transfered `^%s `3Favor to your target, now they have `^%s `3 favor.`@\"",$amount,$d);
				}
				addnav("","runmodule.php?module=dianfall&op=tfin&id=$id&dp=$dp");
			break;
		case "intvene":
			output("`@A flash of light hits you and a Druid appears.");
			output("\"`3It seems that one of your little friends has decided to resurrect you...");
			output("Their name is %s`3... I suggest that ye thank them...`@\"",get_module_pref("who"));
			addnav("Continue","newday.php?resurrection=true");
			blocknav("gardens.php");
			break;
		}
	addnav("Return to the Gardens","gardens.php");
	page_footer();
}
?>