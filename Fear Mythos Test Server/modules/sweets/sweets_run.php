<?php
	$times = get_module_setting("times");
	$stuff = unserialize(get_module_pref("stuff"));
	$menu = array(1=>"bag o' `%S`\$k`^i`@t`#t`!l`%e","`qChocolate Bar","`@S`2o`#d`3a `&Pop","`qRoot `QBeer `6Float","`qM`&i`ql`&k`qs`&h`qa`&k`qe","`!I`#c`3e `&Cream `qS`Qu`&n`\$d`Qa`qe");
	$menu = translate_inline($menu);
	$cost = get_module_setting("menu_cost");
	$gun_cost = get_module_setting("gun_cost");
	$name = httppost('name');
	$op = httpget('op');
	$id = httpget('id');
	page_header("Mystie's Sweets Shoppe");
	
	switch ($op){
		case "enter":
			output("`3A bell rings on the shop door as you step in to investigate this new establishment.");
			output("Sweet aromas assault your senses and a warm angelic seeming voice greets you.");
			output("You turn to see a woman, of godlike beauty and an ageless face.");
			output("She smiles brightly, \"`%Hey hey!");
			output("I am Mystie. So, Welcome to my Sweets Shop!");
			output("Would you care to sample one of my wares?");
			output("I would offer you a choice, but I have been feeling quite good at picking out the right things lately,`3\" she giggles.");
			if ($stuff['used'] < $times) addnav("Sample Mystie's Wares","runmodule.php?module=sweets&op=sample");
			addnav("Talk Amongst Others","runmodule.php?module=sweets&op=talk");
			if (get_module_setting("dump")){
				if (!get_module_pref("gun_use")) addnav("Test Choco-Guns","runmodule.php?module=sweets&op=gun");
			}else{
				if ($stuff['dump'] < get_module_setting("maxdump"))
					addnav("Dump Chocolate","runmodule.php?module=sweets&op=dump");
			}
			break;
		case "dump":
			$targ = httpget('target');
			if ($targ){
				$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid='$targ'";
				$res = db_query($sql);
				$row = db_fetch_assoc($res);
				output("`3You look below you, as %s `3is covered in chocolate!",$row['name']);
				$val = sprintf("%s `3decided to be funny and poured `qchocolate `3all over you from Mystie's Sweets Shoppe!",$session['user']['name']);
				$other_stuff = unserialize(get_module_pref('internal','sweets',$row['acctid']));
				if ($other_stuff['event'] == 1) continue;
				$other_stuff['event_msg'] = $val;
				if (get_module_setting('displaynews'))
					addnews("%s `3dumped `qChocolate Syrup `3from Mystie's Sweets Shoppe on %s!",$session['user']['name'],$row['name']);
				$other_stuff['event'] = 1;
				set_module_pref("stuff",serialize($other_stuff),"sweets",$row['acctid']);
			}else{
				output("`3To your left stands a plethora of buckets, all filled to the brim with `qchocolate`3.");
				output("All you have to do now is pick your target...`n`n");
				$sql = "SELECT name,acctid FROM ".db_prefix("accounts")." WHERE loggedin=1 AND alive=1 AND laston > '".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",300)." seconds"))."' AND acctid<>'{$session['user']['acctid']}'";
				$res = db_query($sql);
				rawoutput("<table align='left' cellpadding='1' cellspacing='1' border='0' bgcolor='#999999'>");
				rawoutput("<tr class='trhead'><td>".translate_inline("Name")."</td></tr>");
				$i = 0;
				while($row = db_fetch_assoc($res)){
					$i++;
					$name = $row['name'];
					$acctid = $row['acctid'];
					rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
					rawoutput("<a href='runmodule.php?module=sweets&op=dump&target=$acctid'>");
					output_notl($name);
					addnav("","runmodule.php?module=sweets&op=dump&target=$acctid");
					rawoutput("</a></td></tr>");
				}
				rawoutput("</table>");
			}
			break;
		case "talk":
			output("`3You enter a adjoining room off the sweet shop and others seem to be sitting and enjoying their treats.");
			output("A roaring fire is surrounded by many plush chairs.");
			output("Soft music is creeping between all of the people and into your ears, mixed with the conversations of others.`n`n"); 
			require_once("lib/commentary.php");
			addcommentary();
			viewcommentary("sweettalk","Talking Amongst Others",25,"says sweetly");
			break;
		case "sample":
			if ($session['user']['gold'] >= $cost){
				$sample = array_rand($menu);
				$sample = $menu[$sample];
				output("`3Mystie walks towards you with a smile on her face.");
				output("\"`%Here is your %s`%. I hope you enjoy it!`3\"",$sample);
				output("You sit down and start to inbibe your sweets, as Mystie rifles through your coin purse and extracts `^%s `3gold.`n`n",$cost);
				$session['user']['gold']-=$cost;
				$stuff['used']++;
				require_once("modules/sweets/sweets_lib.php");
				sweets_boon();
			}else{
				output("`3Mystie looks at you and feels your change purse.");
				output("\"`%I am afraid that you don't have enough gold to sample anything on my menu.");
				output("Please come back once you do.`3\"");
			}		
			break;
		case "gun":
			if ($name == ""){
				output("`3Mystie smiles at you and begins to orate what this is all about.");
				output("\"`%For the minor cost of `^%s `%gold, I shall supply you with the ability to coat someone in chocolate during their sleep.",$gun_cost);
				output("This will cause them to be sluggish when they awake and they will lose a bit of their energy.");
				output("Just put in the person's name and we will ring up a list for you!`3\"`n`n");
				$search = translate_inline("Search");
				rawoutput("<form action='runmodule.php?module=sweets&op=gun' method='post'>");
				rawoutput("<input name='name'><input type='submit' class='button' value='$search'></form>");
			}else{
				$search = "%";
				for ($i=0;$i<strlen($name);$i++){
					$search.=substr($name,$i,1)."%";
				}
				$last = date("Y-m-d H:i:s", strtotime("-".getsetting("LOGINTIMEOUT", 900)." sec"));
				$sql = "SELECT acctid,name FROM ".db_prefix("accounts")." WHERE loggedin=0 AND laston < '$last' AND (name LIKE '$search' OR login LIKE '$search') LIMIT 25";
				$res = db_query($sql);
				$name = translate_inline("Name");
				rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
				rawoutput("<tr class='trhead'><td>$name</td></tr>");
				if (db_num_rows($res) > 0){
					for($i = 0; $i < db_num_rows($res); $i++) {
						$row = db_fetch_assoc($res);
						rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
						rawoutput("<a href='runmodule.php?module=sweets&op=fire&id={$row['acctid']}'>");
						output_notl("`&%s`0",$row['name']);
						rawoutput("</a>");
						addnav("","runmodule.php?module=sweets&op=fire&id={$row['acctid']}");
						rawoutput("</td></tr>");
					}
				}else{
					rawoutput("<tr><td align='center'><i>None</i></td></tr>");
				}
				rawoutput("</table>");
			}
			addnav("","runmodule.php?module=sweets&op=gun");
			break;
		case "fire":
			if ($session['user']['gold'] >= $gun_cost){
				output("`3Mystie leads you to a large cannon and places your hand on the trigger.");
				output("She removes `^%s `3gold from your pouch.",$gun_cost);
				output("\"`%Ready? Aim... FIRE!`3\"");
				$session['user']['gold']-=$gun_cost;
				$other_stuff = unserialize(get_module_pref("stuff","sweets",$id));
				$other_stuff['covered'] = 1;
				set_module_pref("stuff",serialize($other_stuff),"sweets",$id);
				$stuff['gun_use'] = 1;
				$subject = translate_inline("You have been covered!");
				$body = sprintf_translate("%s `qdecided to play a prank on you! Funny how you woke up covered in chocolate, eh?",$session['user']['name']);
				require_once("lib/systemmail.php");
				systemmail($id,$subject,$body);
			}else{
				output("`3Mystie arches a brow, \"`%I show you my latest prototype and you don't even have the correct amount of gold?");
				output("Leave this room. Now!`3\"");
			}
			break;
		}
	addnav("Leave");
	set_module_pref("stuff",serialize($stuff));
	if ($op != "enter") addnav("Return to the Entrance","runmodule.php?module=sweets&op=enter");
	villagenav();
?>