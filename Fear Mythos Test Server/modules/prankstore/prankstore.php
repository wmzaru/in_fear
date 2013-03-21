<?php
require_once("modules/prankstore/prankstore_func.php");
global $session;
$op = httpget("op");
$op2 = httpget("op2");
$op3 = httpget("op3");
$op4 = httpget("op4");
$storename=get_module_setting("storename");
page_header("%s",color_sanitize($storename));
$owner=get_module_setting("owner");
$sex=get_module_setting("sex");
$hisher=translate_inline($sex?"his":"her");
if ($op=="enter") {
	output("`c`b%s`b`c`n",$storename);
	if (get_module_pref("pranker")==0){
		output("You walk up to a store and look at the door.  It's painted with over a hundred different colors as if it was covered with the discarded paints of a kindergarten class.");
		output("A sign on the door says `^'Wet Paint'`0 and you tempt fate by running your finger across the door. Nope, no wet paint here. Either the sign is very old or someone's trying to be funny.");
		addnav("Enter the Store","runmodule.php?module=prankstore&op=door&op2=5");
	}elseif (get_module_pref("pranker")==19){
		output("You approach the door with a bit of apprehension, knowing that %s`0 can be quite the trickster.",$owner);
		output("What would you like to do?");
		addnav("Ring the Bell","runmodule.php?module=prankstore&op=door&op2=1");
		addnav("Turn the Doorknob","runmodule.php?module=prankstore&op=door&op2=2");
		addnav("Push Open the Door","runmodule.php?module=prankstore&op=door&op2=3");
		addnav("Crawl Through the Doggie Door","runmodule.php?module=prankstore&op=door&op2=4");
	}elseif (get_module_pref("pranker")==20){
		output("You roll your eyes at all the little tricks to getting into the store and walk in without a problem.");
		addnav("Enter","runmodule.php?module=prankstore&op=shop");
	}else{
		output("You remember that you've already purchased a prank today.  You'll have to wait until the newday before you can buy another one.");
	}
}

if ($op=="door"){
	output("`c`b%s`b`c`n",$storename);
	$chance=e_rand(1,(6*$op2));
	//spray with water to lose 1 charm
	if ($op2==1){
		output("You get hit in the face with a spray of water.");
		if ($chance==1) prankstore_water();
		else output("Luckily, nobody notices.");
		output("`n`nYou push the door open to enter the store.");
	//Turn the nob to lose 10% of hp
	}elseif($op2==2){
		output("You reach for the door knob and feel an electric charge shoot through your arm.");
		if ($chance==1) prankstore_shock();
		else output("You jerk your hand back quick enough to avoid getting hurt.");
		output("`n`nYou push the door open to enter the store.");
	//Push the door to lose 250 gold
	}elseif($op2==3){
		output("You decide to just open the door and feel a bellows blow a huge puff of air");
		if ($chance==1) prankstore_bellows();
		else output("that hits your hand.  It tickles, but doesn't do anything else to you.");
		output("`n`nYou push the door open to enter the store.");
	//Crawl through the window to lose 1 gem
	}elseif($op2==4){
		output("Thinking that the safest way to enter this wacky store is through the doggie door, you start to crawl in.");
		if ($chance==1) prankstore_doggiedoor();
		output("Now this is a noble way to enter a store!");
		output("`n`nYou enter the store with grace.");
	//First time through one of those 4 happens
	}elseif($op2==5){
		switch(e_rand(1,10)){
			case 1: case 2: case 3: case 4:
				output("You ring the bell to be let in and you're blasted by a stream of water.`n`n");
				prankstore_water();
				output("`n`nYou push the door open to enter the store.");
			break;
			case 5: case 6: case 7:
				output("You reach for the door knob and feel an electric charge shoot through your arm.");
				prankstore_shock();
				output("`n`nYou push the door open to enter the store.");
			break;
			case 8: case 9:
				output("You open the door and feel a bellows blow a huge puff of air");
				prankstore_bellows();
				output("`n`nYou push the door open to enter the store.");
			break;
			case 10:
				output("Thinking that the safest way to enter this wacky store is through the doggie door, you start to crawl in.");
				prankstore_doggiedoor();
				output("Now this is a noble way to enter a store!");
				output("`n`nYou enter the store with grace.");
			break;
		}
	}
	set_module_pref("pranker",20);
	addnav("Continue","runmodule.php?module=prankstore&op=shop");
}
if ($op=="shop"){
	output("`c`b%s`b`c`n",$storename);
	for ($i = 1; $i < 19; $i++){
		$res=get_module_setting("result".$i);
		if (get_module_setting($res."prank".$i)>"" || get_module_setting("result".$i)==0) $total++;
		if (get_module_setting("prankon".$i)>"0"|| get_module_setting("result".$i)==0) $used++;
	}
	addnav("Refresh","runmodule.php?module=prankstore&op=shop");
	if ($total==0){
		output("The shopkeeper looks at you with sadness. `#'I'm sorry.  I'm expecting to receive a shipment of quality pranks but it hasn't arrived yet.  Please check back later.'");
	}elseif($used>=$total){
		output("The shopkeeper looks at you and throws %s hands up. `#'Well, I've already sold my last quality prank for the day.  Please stop by early tomorrow and you can get a crack at the pranks first thing.'",$hisher);
	}else{
		output("The shopkeeper looks at you with a calm serenity. `#'Well, what can I help you with today? Pick carefully, because I don't offer refunds. Some pranks are more rare than others and therefore may not be available everyday. Pranks last a day and you can only purchase one prank per day.'`n`n");
		output("'Each prank can only be pulled once per day, and once I've run out of pranks then that's it.'`n`n`0");
		for ($i = 1; $i < 4; $i++){
			$yomres=get_module_setting("result".$i);
			if (get_module_setting("prankon".$i)<>0 || get_module_setting("result".$i)==0) $usedyom++;
			if (get_module_setting($yomres."prank".$i)<>"" || get_module_setting("result".$i)==0) $yomprank++;
		}
		if ($usedyom<$yomprank){
			output("`b`c`^Send a YoM to EVERYONE`b`c");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
			rawoutput("<tr class='trhead'><td><center>");
			output("Gold");
			rawoutput("</center></td><td><center>");
			output("Message");
			rawoutput("<center/></td></tr>");
			for ($i = 1; $i < 4; $i++){
				$mesres=get_module_setting("result".$i);
				if (get_module_setting($mesres."prank".$i)>"" && get_module_setting("prankon".$i)==0 && $mesres>0){
					rawoutput("<tr class='trlight'><td><center>");
					rawoutput("<a href='runmodule.php?module=prankstore&op=buy&op2=$i'>");
					output("`^%s",get_module_setting("cost".$i));
					rawoutput("</a></center></td><td>");
					if ($i==1) $mail=translate_inline("You're weaker than");
					elseif ($i==2) $mail=translate_inline("Your mother is so old that");
					else $mail=translate_inline("You are dumber than");
					rawoutput("<a href='runmodule.php?module=prankstore&op=buy&op2=$i'>");
					output("%s %s.",$mail,get_module_setting($mesres."prank".$i));
					rawoutput("</a></td></tr>"); 
					addnav("","runmodule.php?module=prankstore&op=buy&op2=$i");
				}
			}
			rawoutput("</table>");
			output_notl("`n`n");
		}
		for ($i = 4; $i < 7; $i++){
			$newsres=get_module_setting("result".$i);
			if (get_module_setting("prankon".$i)<>0 || get_module_setting("result".$i)==0) $usednews++;
			if (get_module_setting($newsres."prank".$i)<>"" || get_module_setting("result".$i)==0) $newsprank++;
		}
		if ($usednews<$newsprank){
			output("`b`c`^Report in the News`b`c");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
			rawoutput("<tr class='trhead'><td><center>");
			output("Gold");
			rawoutput("</center></td><td><center>");
			output("News");
			rawoutput("</center></td></tr>");
			for ($i = 4; $i < 7; $i++){
				$tonewsres=get_module_setting("result".$i);
				if (get_module_setting($tonewsres."prank".$i)<>"" && get_module_setting("prankon".$i)==0 && $tonewsres>0){
					rawoutput("<tr class='trlight'><td><center>");
					rawoutput("<a href='runmodule.php?module=prankstore&op=buy&op2=$i'>");
					output("`^%s",get_module_setting("cost".$i));
					rawoutput("</a></center></td><td>");
					if ($i==4) $report=translate_inline("You have a horrible disease: ");
					elseif ($i==5) $report=translate_inline("You were seen");
					else $report=translate_inline("You were seen");
					rawoutput("<a href='runmodule.php?module=prankstore&op=buy&op2=$i'>");
					output("%s %s.",$report,get_module_setting($tonewsres."prank".$i));
					rawoutput("</a></td></tr>"); 
					addnav("","runmodule.php?module=prankstore&op=buy&op2=$i");
				}
			}
			rawoutput("</table>");
			output_notl("`n`n");
		}
		for ($i = 7; $i < 10; $i++){
			$scrollres=get_module_setting("result".$i);
			if (get_module_setting("prankon".$i)<>0 || get_module_setting("result".$i)==0) $usedscroll++;
			if (get_module_setting($scrollres."prank".$i)<>"" || get_module_setting("result".$i)==0) $scrollprank++;
		}
		if ($usedscroll<$scrollprank){
			output("`b`c`^Scroll in the Village`b`c");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
			rawoutput("<tr class='trhead'><td><center>");
			output("Gold");
			rawoutput("</center></td><td><center>");
			output("Scrolling Message");
			rawoutput("</center></td></tr>");
			for ($i = 7; $i < 10; $i++){
				$scrores=get_module_setting("result".$i);
				//output("Prank: %s . prankon: %s . Result: %s`n",get_module_setting($scrores."prank".$i),get_module_setting("prankon".$i),$scrores);
				if (get_module_setting($scrores."prank".$i)>"" && get_module_setting("prankon".$i)==0 && $scrores>0){
					rawoutput("<tr class='trlight'><td><center>");
					rawoutput("<a href='runmodule.php?module=prankstore&op=buy&op2=$i'>");
					output("`^%s",get_module_setting("cost".$i));
					rawoutput("</a></center></td><td>");
					addnav("","runmodule.php?module=prankstore&op=buy&op2=$i");
					if ($i==7) $scrolling=translate_inline("You have");
					elseif ($i==8) $scrolling=translate_inline("You were seen");
					else $scrolling=translate_inline("You");
					rawoutput("<a href='runmodule.php?module=prankstore&op=buy&op2=$i'>");
					addnav("","runmodule.php?module=prankstore&op=buy&op2=$scroll");
					output("%s %s.",$scrolling,get_module_setting($scrores."prank".$i));
					rawoutput("</a></td></tr>");
				}
			}
			rawoutput("</table>");
			output_notl("`n`n");
		}
		for ($i = 10; $i < 13; $i++){
			$weaponres=get_module_setting("result".$i);
			if (get_module_setting("prankon".$i)<>0 || get_module_setting("result".$i)==0) $usedweapon++;
			if (get_module_setting($weaponres."prank".$i)<>"" || get_module_setting("result".$i)==0) $weaponprank++;
		}
		if ($usedweapon<$weaponprank){
			output("`b`c`^Change Their Weapon for a Day To:`b`c");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
			rawoutput("<tr class='trhead'><td><center>");
			output("Gold");
			rawoutput("</center></td><td><center>");
			output("Weapon");
			rawoutput("</center></td></tr>");
			for ($i = 10; $i < 13; $i++){
				$weapres=get_module_setting("result".$i);
				if (get_module_setting($weapres."prank".$i)>"" && get_module_setting("prankon".$i)==0 && $weapres>0){
					rawoutput("<tr class='trlight'><td><center>");
					rawoutput("<a href='runmodule.php?module=prankstore&op=buy&op2=$i'>");
					output("`^%s",get_module_setting("cost".$i));
					addnav("","runmodule.php?module=prankstore&op=buy&op2=$i");
					rawoutput("</a></center></td><td>");
					rawoutput("<a href='runmodule.php?module=prankstore&op=buy&op2=$i'>");
					output("%s",get_module_setting($weapres."prank".$i));
					rawoutput("</a></td></tr>"); 
					addnav("","runmodule.php?module=prankstore&op=buy&op2=$i");
				}
			}
			rawoutput("</table>");
			output_notl("`n`n");
		}
		for ($i = 13; $i < 16; $i++){
			$vitalres=get_module_setting("result".$i);
			if (get_module_setting("prankon".$i)<>0 || get_module_setting("result".$i)==0) $usedvital++;
			if (get_module_setting($vitalres."prank".$i)<>"" || get_module_setting("result".$i)==0) $vitalprank++;
		}
		if ($usedvital<$vitalprank){
			output("`b`c`^Give Fake Vital Information`b`c");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
			rawoutput("<tr class='trhead'><td><center>");
			output("Gold");
			rawoutput("</center></td><td><center>");
			output("Vital");
			rawoutput("</center></td></tr>");
			for ($i = 13; $i < 16; $i++){
				$vitpres=get_module_setting("result".$i);
				if (get_module_setting($vitpres."prank".$i)>"" && get_module_setting("prankon".$i)==0 && $vitpres>0){
					rawoutput("<tr class='trlight'><td><center>");
					rawoutput("<a href='runmodule.php?module=prankstore&op=buy&op2=$i'>");
					addnav("","runmodule.php?module=prankstore&op=buy&op2=$i");
					output("`^%s",get_module_setting("cost".$i));
					rawoutput("</a></center></td><td>");
					if ($i==13) $vitaldisp=translate_inline("Bowel Movement:");
					elseif ($i==14) $vitaldisp=translate_inline("Back Hair:");
					else $vitaldisp=translate_inline("Potency:");
					rawoutput("<a href='runmodule.php?module=prankstore&op=buy&op2=$i'>");
					addnav("","runmodule.php?module=prankstore&op=buy&op2=$i");
					output("%s %s",$vitaldisp,get_module_setting($vitpres."prank".$i));
					rawoutput("</a></td></tr>"); 
				}
			}
			rawoutput("</table>");
			output_notl("`n`n");
		}
		for ($i = 16; $i < 19; $i++){
			$titleres=get_module_setting("result".$i);
			if (get_module_setting("prankon".$i)<>0 || get_module_setting("result".$i)==0) $usedtitle++;
			if (get_module_setting($titleres."prank".$i)<>"" || get_module_setting("result".$i)==0) $titleprank++;
		}
		if ($usedtitle<$titleprank){
			output("`b`c`^Change Their Title for a Day To:`b`c");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
			rawoutput("<tr class='trhead'><td><center>");
			output("Gold");
			rawoutput("</center></td><td><center>");
			output("Weapon");
			rawoutput("</center></td></tr>");
			for ($i = 16; $i < 19; $i++){
				$titleres=get_module_setting("result".$i);
				if (get_module_setting($titleres."prank".$i)>"" && get_module_setting("prankon".$i)==0 && $titleres>0){
					rawoutput("<tr class='trlight'><td><center>");
					rawoutput("<a href='runmodule.php?module=prankstore&op=buy&op2=$i'>");
					output("`^%s",get_module_setting("cost".$i));
					addnav("","runmodule.php?module=prankstore&op=buy&op2=$i");
					rawoutput("</a></center></td><td>");
					rawoutput("<a href='runmodule.php?module=prankstore&op=buy&op2=$i'>");
					output("%s",get_module_setting($titleres."prank".$i));
					rawoutput("</a></td></tr>"); 
					addnav("","runmodule.php?module=prankstore&op=buy&op2=$i");
				}
			}
			rawoutput("</table>");
			output("`i`b`c`\$WARNING:`^`b If a player has a customized title you will NOT be able to change that.  There are no refunds.`i`c");
			output_notl("`n`n");
		}
	}
}
if ($op=="buy"){
	output("`c`b%s`b`c`n",$storename);
	$cost=get_module_setting("cost".$op2);
	if ($op3==""){
		if ($session['user']['gold']<$cost){
			output("`#'Whoa there %s.  You don't have enough gold to play that prank.  Maybe you should pick a prank within your means.'",translate_inline($session['user']['sex']?"cowgirl":"cowboy"));
			addnav("Store Front","runmodule.php?module=prankstore&op=shop");
		}elseif (get_module_setting("prankon".$op2)>0){
			output("`#'Whoops.  It looks like someone already purchased that prank while you were staring at the paint drying.  Try a different prank.'");
			addnav("Store Front","runmodule.php?module=prankstore&op=shop");
		}else{
			output("%s`0 takes `^%s gold`0 from you. `#'Well, now you just have to pick who you're going to pull this prank on!'`n`n",$owner,$cost);
			$session['user']['gold']-=$cost;
			$op3=1;
		}
	}
	if ($op3==1){
		$who = httpget('who');
		if ($who==""){
			blocknav("village.php");
			$subop = httpget('subop');
			if ($subop!="search"){
				output("`#'Who would you like to pull this prank on?'");
				$search = translate_inline("Search");
				rawoutput("<form action='runmodule.php?module=prankstore&op=buy&op2=$op2&op3=1&subop=search' method='POST'><input name='name' id='name'><input type='submit' class='button' value='$search'></form>");
				addnav("","runmodule.php?module=prankstore&op=buy&op2=$op2&op3=1&subop=search");
				rawoutput("<script language='JavaScript'>document.getElementById('name').focus();</script>");
			}else{
				addnav("Search Again","runmodule.php?module=prankstore&op=buy&op2=$op2&op3=1");
				$search = "%";
				$name = httppost('name');
				for ($i=0;$i<strlen($name);$i++){
					$search.=substr($name,$i,1)."%";
				}
				$sql = "SELECT name,acctid FROM " . db_prefix("accounts") . " WHERE (locked=0 AND name LIKE '$search') ORDER BY level DESC";
				$result = db_query($sql);
				$max = db_num_rows($result);
				if ($max==0){
					output("`#'Actually, I don't know of anyone with that name.  You should try someone else.'");
					$search = translate_inline("Search");
					rawoutput("<form action='runmodule.php?module=prankstore&op=buy&op2=$op2&op3=1&subop=search' method='POST'><input name='name' id='name'><input type='submit' class='button' value='$search'></form>");
					addnav("","runmodule.php?module=prankstore&op=buy&op2=$op2&op3=1&subop=search");
					rawoutput("<script language='JavaScript'>document.getElementById('name').focus();</script>");
				}else{
					output("`#'Okay, this is the list of people I've come up with that you can pick from.'`n`n");
					if ($max > 100) {
						output("`#'The only problem is that there are too many names to pick from. I'll let you choose from the first couple.'`n`n");
						$max = 100;
					}
					rawoutput("<table cellpadding='3' cellspacing='0' align='center' border='0'>");
					rawoutput("<tr class='trhead'><td><center>".translate_inline("Name")."</center></td></tr>");
					for ($i=0;$i<$max;$i++){
						$row = db_fetch_assoc($result);
						$id=$row['acctid'];
						rawoutput("<tr><td><a href='runmodule.php?module=prankstore&op=buy&op2=$op2&op3=1&who=".rawurlencode($row['acctid'])."'>");
						output_notl("%s", $row['name']);
						rawoutput("</a></td></tr>");
						addnav("","runmodule.php?module=prankstore&op=buy&op2=$op2&op3=1&who=".rawurlencode($row['acctid']));
					}
					rawoutput("</table>");
					output("`n`i`^`bNote:`b Title Changes will only work on players who do not have a customized title.  Please purchase with care as there are no refunds.`i");
				}
			}
		}else{
			$sql = "SELECT name,weapon,title,login,ctitle FROM " . db_prefix("accounts") . " WHERE acctid='$who'";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$name = $row['name'];
			$weapon= $row['weapon'];
			$title= $row['title'];
			$ctitle= $row['ctitle'];
			if ($who==$session['user']['acctid']){
				blocknav("village.php");
				output("`#'Yeah, that's hilarious.  PicK yourself to pull the prank on. Ha ha.  That's a knee-slapper.  I've never heard THAT one before.'");
				output("`n`n'NOT! Try someone else.'");
				addnav("Search Again","runmodule.php?module=prankstore&op=buy&op2=$op2&op3=1");
			}elseif ($op2>=16 && $ctitle>""){
				output("`#'Like I said, you can't change someone's title who has a customized title.  You'll have to pick someone else.'");
				addnav("Search Again","runmodule.php?module=prankstore&op=buy&op2=$op2&op3=1");
				blocknav("village.php");
			}else{
				if (get_module_setting("prankon".$op2)>0){
					output("`#'Well, this is bad news.  Unfortunately, someone's already picked that prank while you were picking your victim.  I'll give you your money back and you can pick something else.'");
					$cost=get_module_setting("cost".$op2);
					$session['user']['gold']+=$cost;
					addnav("Store Front","runmodule.php?module=prankstore&op=shop");
				}elseif ($op2>9 && $op2<13 && $weapon=="`\$C`^h`!r`&o`)m`6a`@t`\$i`^c`0 Sword"){
					output("`#'I'm sorry, but you can't pull that prank on this player. The weapon they carry is too powerful to rename through one of my simple prank spells.");
					output("I'm going to give you a refund on your purchase.  Please pick a different prank or person.'");
					$cost=get_module_setting("cost".$op2);
					$session['user']['gold']+=$cost;
					addnav("Store Front","runmodule.php?module=prankstore&op=shop");
				}elseif ($op2>9 && $op2<13 && ($who==get_module_setting("prankon10") || $who==get_module_setting("prankon11") || $who==get_module_setting("prankon12"))){
					output("`#'I'm sorry, but a person can only have that prank pulled on them once a day, and %s`# has already had that done to them today.  Please try another prank.",$name);
					output("`n`n`0You get a refund on your money.");
					$cost=get_module_setting("cost".$op2);
					$session['user']['gold']+=$cost;
					addnav("Store Front","runmodule.php?module=prankstore&op=shop");
				}else{
					set_module_setting("prankon".$op2,$who);
					increment_module_pref("totalpranks",1);
					blocknav("runmodule.php?module=prankstore&op=leave&op2=$op2");
					if ($op2<=3){
						require_once("lib/systemmail.php");
						$sql = "SELECT acctid FROM " . db_prefix("accounts");
						$result = db_query($sql);
						$staff= get_module_setting("frwhosend");
						for ($i=0;$i<db_num_rows($result);$i++){
							$row = db_fetch_assoc($result);
							$id = $row['acctid'];
							if ($op2==1){
								$res1=get_module_setting("result1");
								$body=sprintf("`#This just in:`n`n`^%s`# is weaker than %s`#!!`n`n`n`n`)(You may turn off these notices in your Preferences)",$name,get_module_setting($res1."prank1"));
								$subj = sprintf("`^Exciting News Flash!");
							}elseif ($op2==2){
								$res2=get_module_setting("result2");
								$body=sprintf("`#This just in:`n`n`^%s's`# mother is so old that %s`#!!`n`n`n`n`)(You may turn off these notices in your Preferences)",$name,get_module_setting($res2."prank2"));
								$subj = sprintf("`^New Record Set!");
							}else{
								$res3=get_module_setting("result3");
								$body=sprintf("`#This just in:`n`n`^%s`# is dumber than %s`#!!`n`n`n`n`)(You may turn off these notices in your Preferences)",$name,get_module_setting($res3."prank3"));
								$subj = sprintf("`^Breaking News!");							
							}
							if (get_module_pref("user_prank","prankstore",$id)==0) systemmail($id,$subj,$body);
						}
						output("`#'Your message has been sent to everyone in the kingdom. Nicely done!");
					}elseif($op2<=6){
						if ($op2==4){
							$res4=get_module_setting("result4");
							addnews("`n`n`n`b%s`^ has a horrible disease: `% %s!`b`n`n`n",$name,get_module_setting($res4."prank4"));
						}elseif ($op2==5){
							$res5=get_module_setting("result5");
							addnews("`n`n`n`b%s`^ was seen %s!`b`n`n`n",$name,get_module_setting($res5."prank5"));
						}else{
							$res6=get_module_setting("result6");
							addnews("`n`n`n`b%s`^ was seen %s!`b`n`n`n",$name,get_module_setting($res6."prank6"));
						}
						output("`#'Go ahead and check the news... I think you'll like this one!");
					}elseif($op2<=9){
						output("`#'Yes, that's a popular one.  Go ahead and check the village square.  I think you'll be pleasantly suprised by the results.");
					}elseif($op2<=12){
						for ($i = 10; $i < 13; $i++){
							if ($i==$op2){
								$weaponnote=get_module_setting("result".$i);
								$weaponprank=get_module_setting($weaponnote."prank".$i);
								set_module_setting("weapon".$i,$weapon);
								output("`#'You should see them running around wielding a `^%s`#! It's quite a laugh!",$weaponprank);
							}
						}
						$sql = "UPDATE " . db_prefix("accounts") . " SET weapon='$weaponprank' WHERE acctid='$who'";
						db_query($sql);
					}elseif($op2<=15){
						output("`#'I think that's my favorite prank.  Let's just hope they notice before the new day comes!");
					}else{
						for ($i = 16; $i < 19; $i++){
							if ($i==$op2){
								$titlenote=get_module_setting("result".$i);
								$newtitle=get_module_setting($titlenote."prank".$i);
								set_module_setting("title".$i,$title);
								output("`#'I hope this works. You have changed `^%s`^'s`# title to `^%s`#.  Remember, it only works if they don't have a custom title!",$name,$newtitle);
							}
						}
						//From Lonny's Code from Lonny's Castle
						require_once("lib/names.php");
						require_once("lib/titles.php");
						//output("`nHere's the Saved title: %s`n",$newtitle);
						$newname = str_replace($title,$newtitle,$row['name']);
						$newtitle = str_replace($title,$newtitle,$row['title']);
						//output("`nHere's the str replace newname: %s`n",$newname);
						//output("`nHere's the str replace newtitle: %s`n",$newtitle);
						$sql2 = ("UPDATE ".db_prefix("accounts")." SET title=\"$newtitle\" WHERE login = '".$row['login']."'");
						db_query($sql2);
						if ($row['ctitle'] == ""){
							$sql2 = ("UPDATE ".db_prefix("accounts")." SET name=\"$newname\" WHERE login = '".$row['login']."'");
							db_query($sql2);
						}
					}
					output("Just a reminder, you can't pull any more pranks today.'");
					addnews("`^Someone just made a purchase from the Prankatorium.");
					set_module_pref("pranker",$op2);
				}
			}
		}
		addnav("Leave","runmodule.php?module=prankstore&op=leave&op2=$op2");
	}
}
if ($op=="leave"){
	output("`c`b%s`b`c`n",$storename);
	output("`#'No problem. Why don't you stop by when you're ready to purchase something.'`0 You get your money back.");
	addnav("Back to Storefront","runmodule.php?module=prankstore&op=shop");
	$cost=get_module_setting("cost".$op2);
	$session['user']['gold']-=$cost;
}
if ($op == "hof") {
	page_header("Hall of Fame");
	$pp = 40;
	$pageoffset = (int)$page;
	if ($pageoffset > 0) $pageoffset--;
	$pageoffset *= $pp;
	$limit = "LIMIT $pageoffset,$pp";
	$sql = "SELECT COUNT(*) AS c FROM " . db_prefix("module_userprefs") . " WHERE modulename = 'prankstore' AND setting = 'totalpranks' AND value > 0";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$total = $row['c'];
	$count = db_num_rows($result);
	if (($pageoffset + $pp) < $total){
		$cond = $pageoffset + $pp;
	}else{
		$cond = $total;
	}
	$sql = "SELECT prefs.value, users.name FROM ".db_prefix("module_userprefs")." AS prefs , ".db_prefix("accounts")." AS users WHERE acctid = userid AND modulename = 'prankstore' AND setting = 'totalpranks' AND value > 0 ORDER BY (value+0) DESC $limit";
	$result = db_query($sql);
	$rank = translate_inline("Rank");
	$name = translate_inline("Name");
	$totalpranks = translate_inline("Pranks Pulled");
	$none = translate_inline("No Pranks PUlled");
	output("`b`c`@Most Mischievous Pranksters`c`b`n");
	rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
	rawoutput("<tr class='trhead'><td>$rank</td><td>$name</td><td>$totalpranks</td></tr>");
	if (db_num_rows($result)==0) output_notl("<tr class='trlight'><td colspan='4' align='center'>`&$none`0</td></tr>",true);
	else{
		for($i = $pageoffset; $i < $cond && $count; $i++) {
			$row = db_fetch_assoc($result);
			if ($row['name']==$session['user']['name']){
				rawoutput("<tr class='trhilight'><td>");
			}else{
				rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
			}
			$j=$i+1;
			output_notl("$j.");
			rawoutput("</td><td>");
			output_notl("`&%s`0",$row['name']);
			rawoutput("</td><td>");
			output_notl("`c`b`Q%s`c`b`0",$row['value']);
			rawoutput("</td></tr>");
        }
	}
	rawoutput("</table>");
	if ($total>$pp){
		addnav("Pages");
		for ($p=0;$p<$total;$p+=$pp){
			addnav(array("Page %s (%s-%s)", ($p/$pp+1), ($p+1), min($p+$pp,$total)), "runmodule.php?module=prankstore&op=hof&page=".($p/$pp+1));
		}
	}
	addnav("Return");
	addnav("Back to HoF", "hof.php");
}
villagenav();
page_footer();
?>