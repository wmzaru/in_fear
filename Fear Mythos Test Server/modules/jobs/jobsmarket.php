<?php
	page_header("Market");
	output("`c`b`&Market`0`b`c`n");
	$gold=$session['user']['gold'];
	$gems=$session['user']['gems'];
if ($op == ""){
	output("The market sells the items that are created in the factories.`n");
	//fill the inventory
	for ($i=0;$i<1000;$i++){
		if (get_module_setting('shifts1') > 10 and get_module_setting('milk') < $i){
			increment_module_setting('shifts1',-10);
			increment_module_setting('milk',+1);
		}
		if (get_module_setting('shifts1') > 10 and get_module_setting('eggs') < $i){
			increment_module_setting('shifts1',-10);
			increment_module_setting('eggs',+1);
		}
		if (get_module_setting('shifts1') > 10 and get_module_setting('pork') < $i){
			increment_module_setting('shifts1',-10);
			increment_module_setting('pork',+1);
		}
		if (get_module_setting('shifts1') > 10 and get_module_setting('beef') < $i){
			increment_module_setting('shifts1',-10);
			increment_module_setting('beef',+1);
		}
		if (get_module_setting('shifts1') > 10 and get_module_setting('chicken') < $i){
			increment_module_setting('shifts1',-10);
			increment_module_setting('chicken',+1);
		}
		if (get_module_setting('shifts1') < 10) $i=1000;
	}
	for ($i=0;$i<1000;$i++){
		if (get_module_setting('shifts2') > 15 and get_module_setting('bread') < $i){
			increment_module_setting('shifts2',-15);
			increment_module_setting('bread',+1);
		}
		if (get_module_setting('shifts2')<15) $i=1000;
	}
		for ($i=0;$i<1000;$i++){
		if (get_module_setting('shifts3') > 20 and get_module_setting('cloth') < $i){
			increment_module_setting('shifts3',-20);
			increment_module_setting('cloth',+1);
		}
		if (get_module_setting('shifts3') > 20 and get_module_setting('leather') < $i){
			increment_module_setting('shifts3',-25);
			increment_module_setting('leather',+1);
		}
		if (get_module_setting('shifts3')<25) $i=1000;
	}
		for ($i=0;$i<1000;$i++){
		if (get_module_setting('shifts4') > 30 and get_module_setting('ale') < $i){
			increment_module_setting('shifts4',-30);
			increment_module_setting('ale',+1);
		}
		if (get_module_setting('shifts4')<30) $i=1000;
	}
		for ($i=0;$i<1000;$i++){
		if (get_module_setting('shifts5') > 60 and get_module_setting('breastplate') < $i){
			increment_module_setting('shifts5',-60);
			increment_module_setting('breastplate',+1);
		}
		if (get_module_setting('shifts5') > 60 and get_module_setting('longsword') < $i){
			increment_module_setting('shifts5',-60);
			increment_module_setting('longsword',+1);
		}
		if (get_module_setting('shifts5') > 60 and get_module_setting('chainmail') < $i){
			increment_module_setting('shifts5',-65);
			increment_module_setting('chainmail',+1);
		}
		if (get_module_setting('shifts5') > 60 and get_module_setting('duallongsword') < $i){
			increment_module_setting('shifts5',-65);
			increment_module_setting('duallongsword',+1);
		}
		if (get_module_setting('shifts5') > 60 and get_module_setting('fullarmor') < $i){
			increment_module_setting('shifts5',-70);
			increment_module_setting('fullarmor',+1);
		}
		if (get_module_setting('shifts5') > 60 and get_module_setting('duallongsworddaggers') < $i){
			increment_module_setting('shifts5',-70);
			increment_module_setting('duallongsworddaggers',+1);
		}
		if (get_module_setting('shifts5')<70) $i=1000;
	}
	//now list and sell items
	output("`nFinished products are brought here for you to peruse.`n`n");
	$item=translate_inline("Item");
	$available=translate_inline("# Available");
	$cost=translate_inline("Cost");
	$notes=translate_inline("Notes");
	//table heading
	rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
	rawoutput("<tr class='trhead'><td>");
	output_notl($item);
	rawoutput("</td><td><center>");
	output_notl($available);
	rawoutput("</td><td><center>");
	output_notl($cost);
	rawoutput("</td><td><center>");
	output_notl($notes);
	rawoutput("</center></td></tr>");
	$count=0;
	//Milk
	if (get_module_setting("milk")>0){
		$count++;
		rawoutput("<tr class='".($count%2?"trdark":"trlight")."'><td>");
		output("`2Milk");
		rawoutput("</td><td align='center'>");
		output_notl("%s",get_module_setting("milk"));
		rawoutput("</td><td align='center'>");
		output("`^50 Gold");
		rawoutput("</td><td align='center'>");
		rawoutput("</td></tr>");
	}
	//Eggs
	if (get_module_setting("milk")>0){
		$count++;
		rawoutput("<tr class='".($count%2?"trdark":"trlight")."'><td>");
		output("`2Eggs");
		rawoutput("</td><td align='center'>");
		output_notl("%s",get_module_setting("eggs"));
		rawoutput("</td><td align='center'>");
		output("`^50 Gold");
		rawoutput("</td><td align='center'>");
		rawoutput("</td></tr>");
	}
	//Pork
	if (get_module_setting("pork")>0){
		$count++;
		rawoutput("<tr class='".($count%2?"trdark":"trlight")."'><td>");
		output("`2Pork");
		rawoutput("</td><td align='center'>");
		output_notl("%s",get_module_setting("pork"));
		rawoutput("</td><td align='center'>");
		output("`^75 Gold");
		rawoutput("</td><td align='center'>");
		rawoutput("</td></tr>");
	}
	//Beef
	if (get_module_setting("beef")>0){
		$count++;
		rawoutput("<tr class='".($count%2?"trdark":"trlight")."'><td>");
		output("`2Beef");
		rawoutput("</td><td align='center'>");
		output_notl("%s",get_module_setting("beef"));
		rawoutput("</td><td align='center'>");
		output("`^75 Gold");
		rawoutput("</td><td align='center'>");
		rawoutput("</td></tr>");
	}
	//chicken
	if (get_module_setting("chicken")>0){
		$count++;
		rawoutput("<tr class='".($count%2?"trdark":"trlight")."'><td>");
		output("`2Chicken");
		rawoutput("</td><td align='center'>");
		output_notl("%s",get_module_setting("chicken"));
		rawoutput("</td><td align='center'>");
		output("`^75 Gold");
		rawoutput("</td><td align='center'>");
		rawoutput("</td></tr>");
	}
	//bread
	if (get_module_setting("bread")>0){
		$count++;
		rawoutput("<tr class='".($count%2?"trdark":"trlight")."'><td>");
		output("`2Bread");
		rawoutput("</td><td align='center'>");
		output_notl("%s",get_module_setting("bread"));
		rawoutput("</td><td align='center'>");
		output("`^100 Gold");
		rawoutput("</td><td align='center'>");
		rawoutput("</td></tr>");
	}
	//ale
	if (get_module_setting("ale")>0){
		$count++;
		rawoutput("<tr class='".($count%2?"trdark":"trlight")."'><td>");
		output("`2Ale");
		rawoutput("</td><td align='center'>");
		output_notl("%s",get_module_setting("ale"));
		rawoutput("</td><td align='center'>");
		output("`^300 Gold");
		rawoutput("</td><td align='center'>");
		rawoutput("</td></tr>");
	}
	//cloth
	if (get_module_setting("cloth")>0){
		if (is_module_active("trading")) $gems=3;
		else $gems=1;
		$count++;
		rawoutput("<tr class='".($count%2?"trdark":"trlight")."'><td>");
		output("`2Cloth");
		rawoutput("</td><td align='center'>");
		output_notl("%s",get_module_setting("cloth"));
		rawoutput("</td><td align='center'>");
		output("`%%s Gems",$gems);
		rawoutput("</td><td align='center'>");
		rawoutput("</td></tr>");
	}
	//leather
	if (get_module_setting("leather")>0){
		if (is_module_active("trading")) $gems=4;
		else $gems=2;
		$count++;
		rawoutput("<tr class='".($count%2?"trdark":"trlight")."'><td>");
		output("`2Leather");
		rawoutput("</td><td align='center'>");
		output_notl("%s",get_module_setting("leather"));
		rawoutput("</td><td align='center'>");
		output("`%%s Gems",$gems);
		rawoutput("</td><td align='center'>");
		rawoutput("</td></tr>");
	}
	//breastplate Armor
	if (get_module_setting("breastplate")>0){
		$count++;
		rawoutput("<tr class='".($count%2?"trdark":"trlight")."'><td>");
		output("`2Breastplate Armor");
		rawoutput("</td><td align='center'>");
		output_notl("%s",get_module_setting("breastplate"));
		rawoutput("</td><td align='center'>");
		output("`^14,000 Gold");
		rawoutput("</td><td align='center' style=\"width:100px\">");
		output("Defense 16");
		rawoutput("</td></tr>");
	}
	//Chainmail Armor
	if (get_module_setting("chainmail")>0){
		$count++;
		rawoutput("<tr class='".($count%2?"trdark":"trlight")."'><td>");
		output("`2Chainmail Armor");
		rawoutput("</td><td align='center'>");
		output_notl("%s",get_module_setting("chainmail"));
		rawoutput("</td><td align='center'>");
		output("`^16,000 Gold");
		rawoutput("</td><td align='center' style=\"width:100px\">");
		output("Defense 17");
		rawoutput("</td></tr>");
	}
	//Full Armor
	if (get_module_setting("fullarmor")>0){
		$count++;
		rawoutput("<tr class='".($count%2?"trdark":"trlight")."'><td>");
		output("`2Full Armor");
		rawoutput("</td><td align='center'>");
		output_notl("%s",get_module_setting("fullarmor"));
		rawoutput("</td><td align='center'>");
		output("`^18,000 Gold");
		rawoutput("</td><td align='center' style=\"width:100px\">");
		output("Defense 18");
		rawoutput("</td></tr>");
	}
	//Long Sword
	if (get_module_setting("longsword")>0){
		$count++;
		rawoutput("<tr class='".($count%2?"trdark":"trlight")."'><td>");
		output("`2Long Sword");
		rawoutput("</td><td align='center'>");
		output_notl("%s",get_module_setting("longsword"));
		rawoutput("</td><td align='center'>");
		output("`^14,000 Gold");
		rawoutput("</td><td align='center' style=\"width:100px\">");
		output("Attack 16");
		rawoutput("</td></tr>");
	}
	//Dual Long Sword
	if (get_module_setting("duallongsword")>0){
		$count++;
		rawoutput("<tr class='".($count%2?"trdark":"trlight")."'><td>");
		output("`2Dual Long Sword");
		rawoutput("</td><td align='center'>");
		output_notl("%s",get_module_setting("duallongsword"));
		rawoutput("</td><td align='center'>");
		output("`^16,000 Gold");
		rawoutput("</td><td align='center' style=\"width:100px\">");
		output("Attack 17");
		rawoutput("</td></tr>");
	}
	//Dual Long Sword + Daggers
	if (get_module_setting("duallongsworddaggers")>0){
		$count++;
		rawoutput("<tr class='".($count%2?"trdark":"trlight")."'><td>");
		output("`2Dual Long Sword with 2 more small blades");
		rawoutput("</td><td align='center'>");
		output_notl("%s",get_module_setting("duallongsworddaggers"));
		rawoutput("</td><td align='center'>");
		output("`^18,000 Gold");
		rawoutput("</td><td align='center' style=\"width:100px\">");
		output("Attack 18");
		rawoutput("</td></tr>");
	}
	if ($count==0){
		$none=translate_inline("Nothing Available");
		output_notl("<tr class='trlight'><td colspan='4' align='center'>`&$none`0</td></tr>",true);
	}
	rawoutput("</table>");
	if (get_module_setting('milk') > 0 and $gold > 49) addnav("Milk 50 Gold","runmodule.php?module=jobs&place=market&op=buymilk");
	if (get_module_setting('eggs') > 0 and $gold > 49) addnav("Eggs 50 Gold","runmodule.php?module=jobs&place=market&op=buyeggs");
	if (get_module_setting('pork') > 0 and $gold > 74) addnav("Pork 75 Gold","runmodule.php?module=jobs&place=market&op=buypork");
	if (get_module_setting('beef') > 0 and $gold > 74) addnav("Beef 75 Gold","runmodule.php?module=jobs&place=market&op=buybeef");
	if (get_module_setting('chicken') > 0 and $gold > 74) addnav("Chicken 75 Gold","runmodule.php?module=jobs&place=market&op=buychicken");
	if (get_module_setting('bread') > 0 and $gold > 99) addnav("Bread 100 Gold","runmodule.php?module=jobs&place=market&op=buybread");
	if (get_module_setting('ale') > 0 and $gold > 299) addnav("Ale 300 Gold","runmodule.php?module=jobs&place=market&op=buyale");
	if (is_module_active("trading") && get_module_setting('cloth')>0 && $gems > 2) addnav("Cloth 3 Gems","runmodule.php?module=jobs&place=market&op=buycloth");
	elseif (get_module_setting('cloth')>0 && $gems >= 1) addnav("Cloth 1 Gem","runmodule.php?module=jobs&place=market&op=buycloth");
	if (is_module_active("trading") && get_module_setting('leather')>0 && $gems > 3) addnav("Leather 4 Gems","runmodule.php?module=jobs&place=market&op=buyleather");
	elseif (get_module_setting('leather')>0 && $gems >= 2) addnav("Leather 2 Gems","runmodule.php?module=jobs&place=market&op=buyleather");
	if (get_module_setting('breastplate') > 0 and $gold > 13999) addnav("Breastplate 14000 Gold","runmodule.php?module=jobs&place=market&op=buybreastplate");
	if (get_module_setting('chainmail') > 0 and $gold > 15999) addnav("Chainmail 16000 Gold","runmodule.php?module=jobs&place=market&op=buychainmail");
	if (get_module_setting('fullarmor') > 0 and $gold > 17999) addnav("Full Armor 18000 Gold","runmodule.php?module=jobs&place=market&op=buyfullarmor");
	if (get_module_setting('longsword') > 0 and $gold > 13999) addnav("Long Sword 14000 Gold","runmodule.php?module=jobs&place=market&op=buylongsword");
	if (get_module_setting('duallongsword') > 0 and $gold > 15999) addnav("Dual Long Sword 16000 Gold","runmodule.php?module=jobs&place=market&op=buyduallongsword");
	if (get_module_setting('duallongsworddaggers') > 0 and $gold > 17999) addnav("Dual Sword with Daggers 18000 Gold","runmodule.php?module=jobs&place=market&op=buyduallongsworddaggers");
}
if ($op == "buymilk"){
	if ($session['bufflist']['calcboost']==""){
		$session['user']['gold']-=50;
		if (is_module_active('usechow')) increment_module_pref('hunger',- 3,'usechow');
		if (is_module_active('bladder')) increment_module_pref('bladder',1,'bladder');
		output("Milk, it does a body good!  You get a Calcium Boost!");
		$name=translate_inline("`4Calcium Boost");
		$wearoff=translate_inline("`4Your Calcium Boost fades.");
		$roundmsg=translate_inline("`4You've got a Calcuim Boost!.");
		apply_buff('calcboost',array(
			"name"=>$name,
			"rounds"=>10,
			"wearoff"=>$wearoff,
			"defmod"=>1.25,
			"roundmsg"=>$roundmsg,
			"activate"=>"offense"
		));
		increment_module_setting('milk',- 1);
		debuglog("purchased milk from the industrial market for 50 gold and gained a buff");
	}else output("You have had enough milk.");
	addnav("Continue","runmodule.php?module=jobs&place=market");
}
if ($op == "buyeggs"){
	if ($session['bufflist']['eggboost']==""){
		$session['user']['gold']-=50;
		if (is_module_active('usechow')) increment_module_pref('hunger',- 5,'usechow');
		output("Eggs are high in protein!  You get an Egg Boost!");
		$name=translate_inline("`4Egg Boost");
		$wearoff=translate_inline("`4Your Egg Boost fades.");
		$roundmsg=translate_inline("`4You've got a Egg Boost!.");
		apply_buff('eggboost',array(
			"name"=>$name,
			"rounds"=>10,
			"wearoff"=>$wearoff,
			"defmod"=>1.25,
			"roundmsg"=>$roundmsg,
			"activate"=>"offense"
		));
		increment_module_setting('eggs',-1);
		debuglog("purchased eggs from the industrial market for 50 gold and gained a buff");
	}else output("You have had enough eggs.");
	addnav("Continue","runmodule.php?module=jobs&place=market");
}
if ($op == "buypork"){
	if ($session['bufflist']['protboost']==""){
		$session['user']['gold']-=75;
		if (is_module_active('usechow')) increment_module_pref('hunger',- 8,'usechow');
		output("Meat is high in protein!  You get an Protein Boost!");
		$name=translate_inline("`4Protein Boost");
		$wearoff=translate_inline("`4Your Protein Boost fades.");
		$roundmsg=translate_inline("`4You've got a Protein Boost!.");
		apply_buff('protboost',array(
			"name"=>$name,
			"rounds"=>10,
			"wearoff"=>$wearoff,
			"atkmod"=>1.25,
			"roundmsg"=>$roundmsg,
			"activate"=>"offense"
		));
		increment_module_setting('pork',-1);
		debuglog("purchased pork from the industrial market for 75 gold and gained a buff");
	}else output("You have had enough meat.");
	addnav("Continue","runmodule.php?module=jobs&place=market");
}
if ($op == "buybeef"){
	if ($session['bufflist']['protboost']==""){
		$session['user']['gold']-=75;
		if (is_module_active('usechow')) set_module_pref('hunger', get_module_pref('hunger','usechow') - 8,'usechow');
		output("Meat is high in protein!  You get an Protein Boost!");
		$name=translate_inline("`4Protein Boost");
		$wearoff=translate_inline("`4Your Protein Boost fades.");
		$roundmsg=translate_inline("`4You've got a Protein Boost!.");
		apply_buff('protboost',array(
			"name"=>$name,
			"rounds"=>10,
			"wearoff"=>$wearoff,
			"atkmod"=>1.25,
			"roundmsg"=>$roundmsg,
			"activate"=>"offense"
		));
		increment_module_setting('beef',-1);
		debuglog("purchased beef from the industrial market for 75 gold and gained a buff");
	}else output("You have had enough meat.");
	addnav("Continue","runmodule.php?module=jobs&place=market");
}
if ($op == "buychicken"){
	if ($session['bufflist']['protboost']==""){
		$session['user']['gold']-=75;
		if (is_module_active('usechow')) set_module_pref('hunger', get_module_pref('hunger','usechow') - 8,'usechow');
		output("Meat is high in protein!  You get an Protein Boost!");
		$name=translate_inline("`4Protein Boost");
		$wearoff=translate_inline("`4Your Protein Boost fades.");
		$roundmsg=translate_inline("`4You've got a Protein Boost!.");
		apply_buff('protboost',array(
			"name"=>$name,
			"rounds"=>10,
			"wearoff"=>$wearoff,
			"atkmod"=>1.25,
			"roundmsg"=>$roundmsg,
			"activate"=>"offense"
		));
		increment_module_setting('chicken',-1);
		debuglog("purchased chicken from the industrial market for 75 gold and gained a buff");
	}else output("You have had enough meat.");
	addnav("Continue","runmodule.php?module=jobs&place=market");
}
if ($op == "buybread"){
	if ($session['bufflist']['breadboost']==""){
		$session['user']['gold']-=100;
		if (is_module_active('usechow')) set_module_pref('hunger', get_module_pref('hunger','usechow') - 6,'usechow');
		output("Bread is good for you!  You get an Bread Boost!");
		$name=translate_inline("`4Bread Boost");
		$wearoff=translate_inline("`4Your Bread Boost fades.");
		$roundmsg=translate_inline("`4You've got a Bread Boost!.");
		apply_buff('breadboost',array(
			"name"=>$name,
			"rounds"=>10,
			"wearoff"=>$wearoff,
			"atkmod"=>1.25,
			"roundmsg"=>$roundmsg,
			"activate"=>"offense"
		));
		increment_module_setting('bread',-1);
		debuglog("purchased bread from the industrial market for 100 gold and gained a buff");
	}else output("You have had enough bread.");
	addnav("Continue","runmodule.php?module=jobs&place=market");
}
if ($op == "buycloth"){
	if (is_module_active("trading")){
		$allprefs=unserialize(get_module_pref('allprefs','trading'));
		$inventory2=$allprefs['wool']+$allprefs['cloth']+$allprefs['leather'];
		if ($inventory2 < get_module_setting("room2","trading")){
			output("You hand over your 3 gems and receive a bundle of cloth.");
			$session['user']['gems']-=3;
			$allprefs['cloth']=$allprefs['cloth']+1;
			set_module_pref('allprefs',serialize($allprefs),'trading');
			increment_module_setting('cloth',-1);
			debuglog("purchased cloth from the industrial market for 3 gems");
		}else output("You don't have enough space in your pack to carry that.");
	}else{
		output("You hand over a gem and receive a bundle of cloth.`@`n`n");
		$session['user']['gems']--;
		increment_module_setting('cloth',-1);
		switch(e_rand(1,10)){
			case 1: case 2:
				output("You look Mahvelous! You `&Gain 2 Charm`@!");
				$session['user']['charm']+=2;
				debuglog("purchased cloth from the industrial market for 1 gem and gained 2 charm");
			break;
			case 3: case 4:
				output("You look Good! You `&Gain 1 Charm`@!");
				$session['user']['charm']++;
				debuglog("purchased cloth from the industrial market for 1 gem and gained 1 charm");
			break;
			case 5: case 6: case 7: case 8: case 9:
				output("You turn around and sell the cloth to a man on the street for `%2 gems`@... Instant Profit!!");
				$session['user']['gems']+=2;
				debuglog("purchased cloth from the industrial market for 1 gem and gained 2 gems");
			break;
			case 10:
				output("You walk around, proud as a peacock with your new cloth.  Unfortunately, it clashes with your %s`@.",$session['user']['armor']);
				output("`n`nYou `&Lose 1 Charm`@!");
				$session['user']['charm']--;
				debuglog("purchased cloth from the industrial market for 1 gem and lost 1 charm");
			break;
		}
	}
	addnav("Continue","runmodule.php?module=jobs&place=market");
}
if ($op == "buyleather"){
	if (is_module_active("trading")){
		$allprefs=unserialize(get_module_pref('allprefs','trading'));
		$inventory2=$allprefs['wool']+$allprefs['cloth']+$allprefs['leather'];
		if ($inventory2 < get_module_setting("room2","trading")){
			output("You hand over your 4 gems and receive a bundle of leather.");
			$session['user']['gems']-=4;
			$allprefs['leather']=$allprefs['leather']+1;
			set_module_pref('allprefs',serialize($allprefs),'trading');
			increment_module_setting('leather',-1);
			debuglog("purchased leather from the industrial market for 4 gems");
		}else{
			output("You don't have enough space in your pack to carry that.");
		}
	}else{
		output("You hand over two gems and receive a bundle of leather.`@`n`n");
		$session['user']['gems']-=2;
		increment_module_setting('leather',-1);
		switch(e_rand(1,10)){
			case 1:
				if ($session['user']['turns']>2){
					output("You use the leather to reinforce your %s`@.  You spend 2 turns improving your armor.",$session['user']['armor']);
					$session['user']['turns']-=2;
					$session['user']['armordef']++;
					$session['user']['defense']++;
					debuglog("purchased leather from the industrial market for 2 gems and 2 turns to increase attack by 1 and defense by 1");
				}else{
					output("You use the leather to improve your shoes.  You gain a turn!");
					$session['user']['turns']++;
					debuglog("purchased leather from the industrial market for 2 gems to increase turns by 1");
				}
			break;
			case 2:
				output("You use the leather to make your shoes much more durable.  You gain 2 turns!");
				$session['user']['turns']+=2;
				debuglog("purchased leather from the industrial market for 2 gems to gain 2 turns");
			break;
			case 3:
				output("You use the leather to make your shoes extremely durable.  You gain 3 turns!");
				$session['user']['turns']+=3;
				debuglog("purchased leather from the industrial market for 2 gems to gain 3 turns");
			break;
			case 4:
				output("You use the leather to make your shoes really super duper extremely durable.  You gain 4 turns!");
				$session['user']['turns']+=4;
				debuglog("purchased leather from the industrial market for 2 gems to gain 4 turns");
			break;
			case 5: case 6:
				output("You turn around and sell the leather to a man on the street for `%4 gems`@... Instant Profit!!");
				$session['user']['gems']+=4;
				debuglog("purchased leather from the industrial market for 2 gems and gained 4 in return");
			break;
			case 7: case 8: case 9:
				output("You turn around and sell the leather to a man on the street for `%3 gems`@... Instant Profit!!");
				$session['user']['gems']+=3;
				debuglog("purchased leather from the industrial market for 2 gems and gained 3 in return");
			break;
			case 10:
				output("You walk around, proud as a peacock with your new leather.  Unfortunately, it clashes with your %s`@.",$session['user']['armor']);
				output("`n`nYou `&Lose 2 Charm`@!");
				$session['user']['charm']-=2;
				debuglog("purchased leather from the industrial market for 2 gems and then lost 2 charm");
			break;
		}
	}
	addnav("Continue","runmodule.php?module=jobs&place=market");
}
if ($op == "buyale"){
	if (is_module_active("drinks")) $drunk = round(get_module_pref('drunkeness','drinks')/10-.5,0);
	else $drunk=e_rand(8,9);
	if ($drunk>8 && is_module_active("drinks")==0){
		increment_module_setting('ale',- 1);
		$session['user']['gold']-=300;
		output("You spill the drink.  That's alcohol abuse!");
		apply_buff('buzz',array("name"=>"Alcohol Abuse","rounds"=>2,"atkmod"=>.99,"roundmsg"=>"You regret abusing that alcohol.","activate"=>"offense"));
	}elseif ($drunk > 8) output("`1You have already had too much!");
	else{
		$session['user']['gold']-=300;
		if (is_module_active('bladder')) increment_module_pref('bladder',3,'bladder');
		increment_module_setting('ale',- 1);
		increment_module_pref('drunkeness',33,'drinks');
		if (is_module_active("drinks")) $drunk = round(get_module_pref('drunkeness','drinks')/10);
		output("`1You chug down the ale!`n");
		switch(e_rand(1,3)){
			case 1: case 2:
				output("`1You feel healthy!");
				$session['user']['hitpoints']+=round($session['user']['maxhitpoints']*.1,0);
			break;
			case 3:
				output("`&You feel vigorous!");
				$session['user']['turns']++;
		}
		apply_buff('buzz',array(
			"name"=>translate_inline("`#Buzz"),
			"rounds"=>10,
			"wearoff"=>translate_inline("Your buzz fades."),
			"atkmod"=>1.25,
			"roundmsg"=>translate_inline("You've got a nice buzz going."),
			"activate"=>"offense"
		));
		if (is_module_active("drinks")){
			increment_module_pref('drunkeness',33,'drinks');
			$drunkenness = array(
				-1=>translate_inline("stone cold sober"),
				0=>translate_inline("quite sober"),
				1=>translate_inline("barely buzzed"),
				2=>translate_inline("pleasantly buzzed"),
				3=>translate_inline("almost drunk"),
				4=>translate_inline("barely drunk"),
				5=>translate_inline("solidly drunk"),
				6=>translate_inline("sloshed"),
				7=>translate_inline("hammered"),
				8=>translate_inline("really hammered"),
				9=>translate_inline("almost unconscious")
			);
			output("`n`n`1You now feel ".$drunkenness[$drunk]."`n`n");
		}
		if (is_module_active("drinks")) output("`n`n`1You now feel ".$drunkenness[$drunk]."`n`n");
	}
	debuglog("purchased an Ale from the Industrial Market");
	addnav("Continue","runmodule.php?module=jobs&place=market");		
}
if ($op == "buybreastplate"){
	$session['user']['gold']-=14000;
	increment_module_setting('breastplate',-1);
	output("The lady takes your %s and tosses it in a pile of discarded armor.",$session['user']['armor']);
	$session['user']['armor']="Foundry Breastplate";
	$session['user']['armorvalue']=14000;
	$session['user']['defense']-=$session['user']['armordef'];
	$session['user']['armordef'] = 16;
	$session['user']['defense']+=16;
	debuglog("purchased Foundry Breastplate (Defense 16)");
	output("You try on your new breastplate, it fits well, and feels very sturdy.");
	addnav("Continue","runmodule.php?module=jobs&place=market");
}
if ($op == "buychainmail"){
	$session['user']['gold']-=16000;
	increment_module_setting('chainmail',-1);
	output("The lady takes your %s and tosses it in a pile of discarded armor.",$session['user']['armor']);
	$session['user']['armor']="Foundry Chainmail";
	$session['user']['armorvalue']=16000;
	$session['user']['defense']-=$session['user']['armordef'];
	$session['user']['armordef'] = 17;
	$session['user']['defense']+=17;
	debuglog("purchased Foundry Chainmail (Defense 17)");
	output("You try on your new Chainmail, it fits well, and feels very sturdy.");
	addnav("Continue","runmodule.php?module=jobs&place=market");
}
if ($op == "buyfullarmor"){
	$session['user']['gold']-=18000;
	increment_module_setting('fullarmor',-1);
	output("The lady takes your %s and tosses it in a pile of discarded armor.",$session['user']['armor']);
	$session['user']['armor']="Foundry Full Armor";
	$session['user']['armorvalue']=18000;
	$session['user']['defense']-=$session['user']['armordef'];
	$session['user']['armordef'] = 18;
	$session['user']['defense']+=18;
	debuglog("purchased Foundry Full Armor (Defense 18)");
	output("You try on your new Full Armor, it fits well, and feels very sturdy.");
	addnav("Continue","runmodule.php?module=jobs&place=market");
}
if ($op == "buylongsword"){
	$session['user']['gold']-=14000;
	increment_module_setting('longsword',-1);
	output("The lady takes your %s and tosses it in a pile of discarded weapons.",$session['user']['weapon']);
	$session['user']['weapon']="Foundry LongSword";
	$session['user']['weaponvalue']=14000;
	$session['user']['attack']-=$session['user']['weapondmg'];
	$session['user']['weapondmg'] = 16;
	$session['user']['attack']+=16;
	debuglog("purchased a Foundry Longsword (attack 16)");
	output("You try out your new longsword, it feels well balanced, and very sturdy.");
	addnav("Continue","runmodule.php?module=jobs&place=market");
}
if ($op == "buyduallongsword"){
	$session['user']['gold']-=16000;
	increment_module_setting('duallongsword',-1);
	output("The lady takes your %s and tosses it in a pile of discarded weapons.",$session['user']['weapon']);
	$session['user']['weapon']="Foundry Dual LongSword";
	$session['user']['weaponvalue']=16000;
	$session['user']['attack']-=$session['user']['weapondmg'];
	$session['user']['weapondmg'] = 17;
	$session['user']['attack']+=17;
	debuglog("purchased a Foundry Dual Longsword (attack 17)");
	output("You try out your new  dual longsword, it feels well balanced, and very sturdy.");
	addnav("Continue","runmodule.php?module=jobs&place=market");
}
if ($op == "buyduallongsworddaggers"){
	$session['user']['gold']-=18000;
	increment_module_setting('duallongsworddaggers',-1);
	output("The lady takes your %s and tosses it in a pile of discarded weapons.",$session['user']['weapon']);
	$session['user']['weapon']="Foundry Dual LongSword with Daggers";
	$session['user']['weaponvalue']=18000;
	$session['user']['attack']-=$session['user']['weapondmg'];
	$session['user']['weapondmg'] = 18;
	$session['user']['attack']+=18;
	debuglog("purchased a Foundry Dual Longsword with Daggers (attack 18)");
	output("You try out your new  dual longsword with daggers, it feels well balanced, and very sturdy.");
	addnav("Continue","runmodule.php?module=jobs&place=market");
}
addnav("Leave");
if (get_module_setting("industrialpark")==1) addnav("Return to Industrial Park","runmodule.php?module=jobs&place=industrialpark");
villagenav();
page_footer();
?>