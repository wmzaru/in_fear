<?php
global $session;
$op = httpget('op');
$dogcost = get_module_setting("dogcost");
$bowcost = get_module_setting("bowcost");
$bc = get_module_setting("bonecost");
$arrowcost = get_module_setting("arrowcost");
$arrows = get_module_setting("arrows");
$ht = get_module_setting("huntingturns");
$hd=get_module_pref("hasdog");
$dn=translate_inline(array("","`!Max","`@Rover","`#Jake","`%Buddy","`^Sam","`&Rocky","`QBuster","`1Cody","`2Duke","`3Charlie","`4Jack","`5Rusty","`6Toby","`7Sparky","`qWinston"));
$ha=get_module_pref("arrows");
page_header("Poveas's Pheasant Hunt");
if ($op!="hof") output("`c`b`@Poveas's `^Pheasant Hunt`b`c`0");
if ($op == "enter"){
	if (get_module_pref("feedbone")==1){
		output("Your `&dog, %s`0, wants a `qbone`0 and `@Poveas`0 just happens to have some `qdog bones`0 for sale.`n They only cost `^%s gold`0.",$hd[$dn],$bc);
		addnav(array("Buy a bone - `^%s Gold", $bc),"runmodule.php?module=poveas&op=bone");
	}else{
		if (get_module_pref("usedhuntingturns")<get_module_setting("huntingturns")){
			if (get_module_pref("hasdog")==0 && get_module_pref("hasbow")==0 && $ha==0){
				output("`@Poveas`0 will let you hunt on her lands for free, but you need a few things.`n`n");
				output("First you have to have a `&hunting dog`0 to bring back the birds you kill. `n`n");
				output("After you have a dog you can purchase a `qbow`0. Without the bow you can't hunt.`n`n");
				output("After you have a bow you can purchase `@arrows`0. Without arrows you can't hunt.`n`n");
				output("After you have a dog, a bow and arrows you may hunt `^%s`0 `2times per new day`0.`n`n",$ht);
			}else{
				output("`@Poveas`0 welcomes you back and wishes you luck hunting.`n`n");
			}
			output("`n`b`#`cYour Hunting Status:`0`b`n");
			if (get_module_pref("hasdog")==0){
				output("You need a `&hunting dog`0.`n");
			}else{
				output("You have a `&hunting dog`0 named %s`0.`n",$dn[$hd]);
			}
			if (get_module_pref("hasbow")==0){
				output("You need a `qhunting bow`0.`n");
			}else{
				output("You have a `qhunting bow`0.`n");
			}
			if ($ha==0){
				output("You need some `@hunting arrows`0.`n");
			}else{
				output("You have `^%s `@hunting arrows`0 in your quiver.`n",$ha);
			}
			if ($ha>0 && get_module_pref("hasdog")>0 && get_module_pref("hasbow")==1){
				output("You have `^%s `2hunting turns`0 left today.",get_module_setting("huntingturns")-get_module_pref("usedhuntingturns"));
			}
			output_notl("`c");
			if (get_module_pref("hasdog")==0) addnav(array("`0Buy a `&Hunting Dog`0 - `^%s Gold", $dogcost),"runmodule.php?module=poveas&op=dog");
			if (get_module_pref("hasbow")==0 && get_module_pref("hasdog")>0) addnav(array("`0Buy a `qHunting Bow`0 - `^%s Gold", $bowcost),"runmodule.php?module=poveas&op=bow");
			if (get_module_pref("hasdog")>0 && get_module_pref("hasbow")==1 && get_module_pref("arrows")>=1) addnav("Let's Go Hunting","runmodule.php?module=poveas&op=hunt");
			if (get_module_pref("hasbow")==1 && get_module_pref("arrows")==0) addnav(array("`0Buy `@Arrows`0 - `^%s Gold", $arrowcost),"runmodule.php?module=poveas&op=arrow");
		}else{
			output("Your are out of `2hunting turns`0 for today.");
		}
	}
}elseif ($op == "continue"){
	if (get_module_pref("usedhuntingturns")<get_module_setting("huntingturns")){
		if (get_module_pref("feedbone")==1){
			output("`@Poveas`0 just happens to have some `Qdog bones`0 for sale. Would you like to buy a bone?`n They only cost `^%s gold`0.",$bc);
			addnav(array("`0Buy a `QBone`0 - `^%s Gold", $bc),"runmodule.php?module=poveas&op=bone");
		}elseif (get_module_pref("hasdog")==0){
			addnav(array("`0Buy a `&Hunting Dog`0 - `^%s Gold", $dogcost),"runmodule.php?module=poveas&op=dog");
			output("You need a `&Hunting Dog`0.");
		}elseif (get_module_pref("hasbow")==0){
			output("After you have a dog you can purchase a `qbow`0. Without the bow you can't hunt.`n`n");
			addnav(array("`0Buy a `qHunting Bow`0 - `^%s Gold", $bowcost),"runmodule.php?module=poveas&op=bow");
		}elseif (get_module_pref("hasbow")==1 && get_module_pref("arrows")==0){
			 addnav(array("`0Buy `@Arrows`0 - `^%s Gold", $arrowcost),"runmodule.php?module=poveas&op=arrow");
			output("You have to purchase `@arrows`0. Without arrows you can't hunt.`n`n");
		}elseif (get_module_pref("hasdog")>0 && get_module_pref("hasbow")==1 && get_module_pref("arrows")>=1) {
			addnav("Let's go hunting","runmodule.php?module=poveas&op=hunt");
			output("Good Luck Hunting!!");
		}
	}else{
		output("You are out of `2hunting turns`0 for today.");
	}
}elseif ($op == "dog"){
	if($session['user']['gold'] >= $dogcost){
		$hd=e_rand(1,15);
		set_module_pref("hasdog",$hd);
		output("You are now the happy owner of a great hunting dog named %s`0. `n`@Poveas`0 thanks you for your business and asks you to take good care of %s.",$dn[$hd],$dn[$hd]);
		$session['user']['gold'] -= $dogcost;
		addnav("Continue","runmodule.php?module=poveas&op=continue");
		debuglog("spent $dogcost gold to buy a dog at Poveas's.");
	}else{
		output("You need more `^gold`0."); 
	}
}elseif ($op == "bow"){
	if($session['user']['gold'] >= $bowcost){
		output("You pay `^%s gold`0 to `@Poveas`0.`n`nYou hold your new `qhunting bow`0 in your hands and you feel like you could shoot anything that moves.",$bowcost);
		set_module_pref("hasbow",1);
		$session['user']['gold'] -= $bowcost;
		addnav("Continue","runmodule.php?module=poveas&op=continue");
		debuglog("spent $bowcost gold to buy a bow at Poveas's.");
	}else{
		output("You need more gold.");
	}
}elseif ($op == "arrow"){
	$buy = httppost('buy');
	if ($buy+get_module_pref("arrows")>20) $buy=20-get_module_pref("arrows");
	if ($buy<1){
		output("How many arrows would you like to buy?`n");
		output("`iNote: Your quiver can hold a maximum of `^20`@ arrows`0.");
		if (get_module_pref("arrows")==0) output("You don't have any arrows yet.`i");
		else output("You currently have `^%s `@arrows`0 in your quiver.`i",get_module_pref("arrows"));
		$buy=translate_inline("Purchase");
		rawoutput("<form action='runmodule.php?module=poveas&op=arrow' method='POST'><input name='buy' id='buy'><input type='submit' class='button' value='$buy'></form>",true);
		addnav("","runmodule.php?module=poveas&op=arrow");
	}else{
		if ($session['user']['gold'] < ($buy * $arrowcost)) {
			output("Poveas can't give her arrows away for free.`n`n");
		}else{
			if ($buy+get_module_pref("arrows")>20) $buy=20-get_module_pref("arrows");
			$cost=($buy * $arrowcost);
			$session['user']['gold']-=$cost;
			set_module_pref("arrows",get_module_pref("arrows")+$buy);
			output("`@Poveas`0 takes your `^%s gold`0 and hands you `^%s `@%s`0.",$cost,$buy,translate_inline($buy>1?"arrows":"arrow"));
			debuglog("spent $cost gold to buy $buy arrows at Poveas's.");
		}
	}
	addnav("Continue","runmodule.php?module=poveas&op=continue");
}elseif ($op == "bone"){ 
	if($session['user']['gold'] >= $bc){
		output("You have made %s`0 very happy and he is ready to hunt again.",$dn[$hd]);
		set_module_pref("feedbone",0);
		$session['user']['gold'] -= $bc;
		addnav("Continue","runmodule.php?module=poveas&op=continue");
		debuglog("spent $bc gold to buy a bone at Poveas's.");
	}else{
		output("You need more `^gold`0."); 
	}
}elseif ($op == "hunt"){
	if (get_module_pref("arrows")>=1){
		output("You shoot your `@arrow`0.....`n`n");
		switch(e_rand(1,50)){
			case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8: case 9: case 10: case 11:
				$session['user']['gold']+=10;
				output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He brings back an old purse and there's `^10 gold`0 in it.`n",$dn[$hd]);
				debuglog("lost gained 10 gold while hunting at Poveas's.");
				increment_module_pref("hunthof",1);
			break;
			case 12: case 13: case 14: case 15: case 16: case 17: case 18:
				output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He brings back a `)dead bird`0!`n",$dn[$hd]);
				increment_module_pref("hunthof",1);
			break;
			case 19: case 20: case 21: case 22: case 23: case 24: case 25:
				output("..... and miss a `4pheasant`0. %s`0 runs out to retrieve nothing. He comes back and `6pees`0 on your leg.`n",$dn[$hd]);
			break;
			case 26: case 27:
				$session['user']['charm']--;
				output("..... and miss a `4pheasant`0. %s`0 runs out to retrieve nothing. He comes back and bites your face. You `&lose a charm point`0.`n",$dn[$hd]);
				debuglog("lost a charm while hunting at Poveas's.");
			break;
			case 28: case 29: case 30: case 31:
				if ($session['user']['turns']>0){
					$session['user']['turns']--;
					output("..... and miss a pheasant. %s`0 runs out to retrieve it. He comes back and takes a `qdump`0 at your feet. The sight of the crap makes you sick. You `@lose a forest fight`0.`n",$dn[$hd]);
					debuglog("lost a turn while hunting at Poveas's.");
				}else{
					$session['user']['charm']--;
					output("..... and miss a pheasant. %s`0 runs out to retrieve it. He comes back and gives you a huge wet sloppy dog kiss. EWWWWW! You `&lose a charm`0.",$dn[$hd]);
					debuglog("lost a charm while hunting at Poveas's.");
				}
			break;
			case 32: case 33: case 34:
				$session['user']['gold']+=2;
				output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He brings back a dead bird. After examining the bird, you find a couple of `^gold coins`0 tucked away in it's feathers.`n",$dn[$hd]);
				debuglog("gained 2 gold while hunting at Poveas's.");
				increment_module_pref("hunthof",1);
			break;
			case 35: case 36: case 37:
				$session['user']['turns']++;
				output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He brings back a nice ripe `2pear`0. You eat it and `@gain a forest fight`0.`n",$dn[$hd]);
				debuglog("gained a turn while hunting at Poveas's.");
				increment_module_pref("hunthof",1);
			break;
			case 38: case 39:
				if ($session['user']['hitpoints'] < $session['user']['maxhitpoints']){
					$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
					output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He comes back with a cooked `\$t-bone steak`0. You eat it and are `@fully healed`0.`n",$dn[$hd]);
					debuglog("was fully healded while hunting at Poveas's.");
				}else{
					output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He brings back a `)dead bird`0!`n",$dn[$hd]);
				}
				increment_module_pref("hunthof",1);
			break;
			case 40: case 41:
				$session['user']['gold']+=5;
				output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He comes back with a `^`igolden egg`i`0 worth `^5 gold`0.`n",$dn[$hd]);
				debuglog("gained 5 gold while hunting at Poveas's.");
				increment_module_pref("hunthof",1);
			break;
			case 42: case 43:
				$session['user']['hitpoints']+=10;
				output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He comes back with a `4strawberry`0. You eat it and `\$gain some hitpoints`0.`n",$dn[$hd]);
				debuglog("gained 10 hitpoints while hunting at Poveas's.");
				increment_module_pref("hunthof",1);
			break;
			case 44:
				output("..... and miss a `4pheasant`0. %s`0 runs out to retrieve nothing. He comes back with a `qstick`0.`n",$dn[$hd]);
			break;
			case 45:
				output("..... and miss a `4pheasant`0. %s`0 runs out to retrieve nothing. `n`n`bHe never comes back. You have to get a new dog.`b`n",$dn[$hd]);
				set_module_pref("hasdog",0);
				debuglog("lost their dog while hunting at Poveas's.");
			break;
			case 46: case 47:
				if($session['user']['hitpoints']>=11){
					$session['user']['hitpoints']-=10;
					debuglog("lost 10 hitpoints while hunting at Poveas's.");
				}else{
					$session['user']['hitpoints']=1;
					debuglog("lost all hitpoints except 1 while hunting at Poveas's.");
				}
				output("..... and miss a `4pheasant`0. %s`0 runs out to retrieve nothing. He comes back and bites you. You `\$lose some hitpoints`0.`n",$dn[$hd]);
			break;
			case 48:
				switch(e_rand(1,3)){
					case 1:
						$total=$session['user']['level'] * 5;
						$session['user']['gold']+=$total;
						output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He brings back a large sack with `^%s gold`0 in it.`n",$dn[$hd],$total);
						debuglog("gained $total gold while hunting at Poveas's.");
						increment_module_pref("hunthof",1);
					break;
					case 2:
						$total=$session['user']['level'] * 2;
						$session['user']['deathpower']+=$total;
						output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He brings back a mug of `1magical ale`0. You drink it and gain `^%s `)favor`0 in the afterlife.`n",$dn[$hd],$total);
						debuglog("gained $total favor while hunting at Poveas's.");
						increment_module_pref("hunthof",1);
					break;
					case 3:
						set_module_pref("feedbone",1);
						output("..... and %s`0 doesn't move. He wants a `Qdog bone`0. If you don't get him one he will not hunt anymore.`n",$dn[$hd]);
					break;
				}
			break;
			case 49:
				switch(e_rand(1,4)){
					case 1:
						$total=$session['user']['level'] * 5+ e_rand(1,3);
						$session['user']['experience']-=$total;
						if ($session['user']['experience']<$total) $total=$session['user']['experience'];
						output("..... and miss a `4pheasant`0. %s`0 runs out to retrieve it. He comes back and knocks you down in the mud. You lose `^%s `#experience points`0 from the bad ordeal.`n",$dn[$hd],$total);
						debuglog("lost $total experience while hunting at Poveas's.");
					break;
					case 2:
						$total=$session['user']['level'] * 5 + e_rand(1,3);
						$session['user']['experience']+=$total;
						output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He brings back a dead bird in perfect shape for cooking. You gain `^%s `#experience`0 for having a good hunting trip.`n",$dn[$hd],$total);
						debuglog("gained $total experience while hunting at Poveas's.");
						increment_module_pref("hunthof",1);
					break;
					case 3:
						$session['user']['maxhitpoints']++;
						output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He brings back a vial of liquid. You drink it and `\$gain a permanent hit point`0.`n",$dn[$hd]);
						debuglog("gained a max hitpoint favor while hunting at Poveas's.");
						increment_module_pref("hunthof",1);
					break;
					case 4:
						if ($session['user']['maxhitpoints']>$session['user']['level']*10+2){
							$session['user']['maxhitpoints']--;
							output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He brings back a vial of liquid. You drink it and `\$lose a permanent hit point`0.`n",$dn[$hd]);
							debuglog("lost a maxhitpoint while hunting at Poveas's.");
						}else{
							output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He brings back a `)dead bird`0!`n",$dn[$hd]);
						}
						increment_module_pref("hunthof",1);
					break;
				}
			break;
			case 50:
				switch(e_rand(1,4)){
					case 1:
						$session['user']['attack']++;
						output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He brings back a vial of liquid. You drink it and gain `&extra attack power`0.`n",$dn[$hd]);
						debuglog("gained an attack while hunting at Poveas's.");
					break;
					case 2:
						$session['user']['defense']++;
						output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He brings back a vial of liquid. You drink it and gain `&extra defense power`0.`n",$dn[$hd]);
						debuglog("gained a defense while hunting at Poveas's.");
					break;
					case 3:
						$session['user']['gems']++;
						output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He brings back a sack. There is a `%GEM`0 inside!!`n",$dn[$hd]);
						debuglog("gained a gem while hunting at Poveas's.");
					break;
					case 4:
						$session['user']['gems']+=2;
						output("..... and hit a `4pheasant`0. %s`0 runs out to retrieve it. He brings back a sack. There are `%TWO GEMS`0 inside!!`n",$dn[$hd]);
						addnews("%s`0 found `%two gems`0 while hunting for `4pheasants`0.",$session['user']['name']);
						debuglog("gained 2 gems while hunting at Poveas's.");
					break;
				}
				increment_module_pref("hunthof",1);
			break;
		}
		set_module_pref("arrows",get_module_pref("arrows")-1);
		set_module_pref("usedhuntingturns",get_module_pref("usedhuntingturns")+1);
		$hd=get_module_pref("hasdog");
		$ha=get_module_pref("arrows");
		output_notl("`n`n");
		if (get_module_pref("usedhuntingturns")<get_module_setting("huntingturns")){
			if (get_module_pref("feedbone")==1){
				output("`@Poveas`0 just happens to have some `Qdog bones`0 for sale. Would you like to buy a bone?`n They only cost `^%s gold`0.`n`n",$bc);
				addnav(array("`0Buy a `QBone`0 - `^%s Gold", $bc),"runmodule.php?module=poveas&op=bone");
			}elseif (get_module_pref("hasdog")==0){
				addnav(array("`0Buy a `&Hunting Dog`0 - `^%s Gold", $dogcost),"runmodule.php?module=poveas&op=dog");
				output("You need a `&Hunting Dog`0.`n`n");
			}elseif (get_module_pref("hasbow")==0){
				output("After you have a dog you can purchase a `qbow`0. Without the bow you can't hunt.`n`n");
				addnav(array("`0Buy a `qHunting Bow`0 - `^%s Gold", $bowcost),"runmodule.php?module=poveas&op=bow");
			}elseif (get_module_pref("hasbow")==1 && get_module_pref("arrows")==0){
				 addnav(array("`0Buy `@Arrows`0 - `^%s Gold", $arrowcost),"runmodule.php?module=poveas&op=arrow");
				output("You have to purchase `@arrows`0. Without arrows you can't hunt.`n`n");
			}elseif (get_module_pref("hasdog")>0 && get_module_pref("hasbow")==1 && get_module_pref("arrows")>=1) {
				addnav("Let's go hunting","runmodule.php?module=poveas&op=hunt");
			}
		}
		output("`n`b`3`cYour Hunting Status:`0`b`n");
		if (get_module_pref("hasdog")==0){
			output("You need a `&hunting dog`0.`n");
		}else{
			output("You have a `&hunting dog`0 named %s`0.`n",$dn[$hd]);
		}
		if (get_module_pref("hasbow")==0){
			output("You need a `qhunting bow`0.`n");
		}else{
			output("You have a `qhunting bow`0.`n");
		}
		if ($ha==0){
			output("You need some `@hunting arrows`0.`n");
		}else{
			output("You have `^%s `@hunting arrows`0 in your quiver.`n",$ha);
		}
		if ($ha>0 && get_module_pref("hasdog")>0 && get_module_pref("hasbow")==1){
			output("You have `^%s `2hunting turns`0 left today.`n",get_module_setting("huntingturns")-get_module_pref("usedhuntingturns"));
		}else{
			output("Your are out of `2hunting turns`0 for today.");
		}
		output_notl("`c`n`n");
	}else{
		output("You need arrows to hunt.`n");
	}
}elseif ($op == "hof") {
	page_header("Hall of Fame");
	$page = httpget('page');
	$pp = 50;
	$pageoffset = (int)$page;
	if ($pageoffset > 0) $pageoffset--;
	$pageoffset *= $pp;
	$limit = "LIMIT $pageoffset,$pp";
	$sql = "SELECT COUNT(*) AS c FROM " . db_prefix("module_userprefs") . " WHERE modulename = 'poveas' AND setting = 'hunthof' AND value > 0";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$total = $row['c'];
	$count = db_num_rows($result);
	if (($pageoffset + $pp) < $total){
		$cond = $pageoffset + $pp;
	}else{
		$cond = $total;
	}
	$sql = "SELECT ".db_prefix("module_userprefs").".value, ".db_prefix("accounts").".name FROM " . db_prefix("module_userprefs") . "," . db_prefix("accounts") . " WHERE acctid = userid AND modulename = 'poveas' AND setting = 'hunthof' AND value > 0 ORDER BY (value+0) DESC $limit";
	$result = db_query($sql);
	$rank = translate_inline("Rank");
	$name = translate_inline("Name");
	$hunthof = translate_inline("Pheasants Hit");
	$none = translate_inline("No Pheasants Hit");
	output("`b`c`@Best`$ Pheasant Hunters `@in the Land`c`b`n");
	rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
	rawoutput("<tr class='trhead'><td>$rank</td><td>$name</td><td>$hunthof</td></tr>");
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
			addnav(array("Page %s (%s-%s)", ($p/$pp+1), ($p+1), min($p+$pp,$total)), "runmodule.php?module=poveas&op=hof&page=".($p/$pp+1));
		}
	}
	addnav("Return");
	addnav("Back to HoF", "hof.php");
	villagenav();
	blocknav("forest.php");
}
addnav("Return to the Forest","forest.php");
page_footer();
?>