<?php
// translator ready
// addnews ready
// mail ready
require_once("lib/commentary.php");

function Longbeach_getmoduleinfo(){
	$info = array(
		"name"=>"Long Beach",
		"version"=>"1.1",
		"author"=>"Billie Kennedy",
		"category"=>"Village",
		"download"=>"http://orpgs.com/modules.php?name=Downloads&d_op=viewdownload&cid=3",
        "vertxtloc"=>"http://www.orpgs.com/downloads/version.txt",
		"settings"=>array(
			"Long Beach,title",
			"beachname"=>"Name for the Beach|Long Beach",
			"beachloc"=>"Where does the beach appear,location|".getsetting("villagename", LOCATION_FIELDS),	
			"beachclosed"=>"Is the beach private?,bool|0",
			"ticketcost"=>"How much do tickets cost?,int|100",
			"swimsuitcost"=>"How much does a swimsuit cost?,int|1000",
			"tanchance"=>"What is the chance of tanning?,int|10",
			"swimchance"=>"What is the chance of finding something while swimming?,int|10",
			"drownchance"=>"What is the chance of drowning?,int|5",
			
		),
		"prefs"=>array(
			"Long Beach,title",
			"hasticket"=>"Does the player have a ticket?,bool|0",
			"hasswimsuit"=>"Does the player have a swimsuit?,bool|0",
			"wearsuit"=>"Is the player wearing the swimsuit?,bool|0",
			"armordesc"=>"What is the armor description?,text",
			"armorvalue"=>"What value does the armor have?,int|0",
			"armorcost"=>"What cost does the armor have?,int|0",
			"weapondesc"=>"What is the weapon description?,text",
			"weaponvalue"=>"What value does the weapon have?,int|0",
			"weaponcost"=>"What cost does the weapon have?,int|0",
			"pattack"=>"What was the previous attack value?,int|0",
			"pdefense"=>"What was the previous defense value?,int|0",
			"sunbathed"=>"Has the player sunbahted today?,bool|0",
			"swam"=>"Has the player gone for a swim today?,bool|0",
			),
	);
	return $info;
}

function Longbeach_install(){

	module_addhook("changesetting");
	module_addhook("village");
	module_addhook("newday");
	module_addhook("pointsdesc");
	module_addhook("lodge");
	module_addhook("moderate");
	module_addhook("village-desc");
	module_addhook("generalstore");
	module_addhook("backpack");
	module_addhook("dragonkill");
	
	return true;
}

function Longbeach_uninstall(){
	
	return true;
}

function Longbeach_dohook($hookname,$args){

	global $session;

	$mod = httpget("module");
	
	switch($hookname){
		
		case "changesetting":
   			if ($args['setting'] == "villagename") {
    			if ($args['old'] == get_module_setting("beachloc")) {
       				set_module_setting("beachloc", $args['new']);
				}
			}
		break;
		case "dragonkill":
			
			set_module_pref('weaponcost',0);
			set_module_pref('weaponvalue',0);
			set_module_pref('armorcost',0);
			set_module_pref('armorvalue',0);
			set_module_pref('pattack',0);
			set_module_pref('pdefense',0);
			set_module_pref('wearsuit',0);
			
		break;
			
		case "moderate":
			$args['beach'] = translate_inline("Beach");
		break;
		case "newday":
			set_module_pref('sunbathed',0);
			set_module_pref('swam',0);
		break;
		case "village":
			if($session['user']['location'] == get_module_setting("beachloc")){
				addnav($args['marketnav']);

				addnav("Small Sandy Path","runmodule.php?module=longbeach&op=enter");
			}
		break;
		case "generalstore":
			$gold = get_module_setting("swimsuitcost");
			if (!get_module_pref('hasswimsuit')){
				addnav("Buy Items");
				addnav("SwimSuit","runmodule.php?module=longbeach&op=buyitem");
				output("`@Buy a swimsuit for the beach.  The swimsuit costs %s gold.`n",$gold);	
			}
			if (get_module_pref('hasswimsuit')){
				addnav("Sell Items");
				addnav("SwimSuit","runmodule.php?module=longbeach&op=sellitem");
			}
		break;
		case "backpack":
			
			if (get_module_pref('hasswimsuit') && !get_module_pref('wearsuit')){
				output("SwimSuit`n");
			}
		break;
		
	}
	return $args;
}

function Longbeach_run(){
	global $session;
	
	page_header("White Sandy Beach");
	$gold = get_module_setting("swimsuitcost");
	switch(httpget("op")){
		
		case "buyitem":
			addnav("Return to the Store","runmodule.php?module=generalstore");
			if(!get_module_pref('hasbackpack','backpack')){
				output("Sorry but you need a backpack to carry it with.`n");
				break;
				}
			if (get_module_pref('hasswimsuit')){
				output ("You already have a swimsuit.`n");
			}elseif (get_module_setting('swimsuitcost') > $session['user']['gold']){
				output ("You do not have enough gold to purchase a backpack.`n");
			}else{
				output ("You have just purchased a skimpy swimsuit. You will have to remember to put it on when you get to the beach.`n");
				$session['user']['gold'] -= $gold;
				set_module_pref('hasswimsuit',1);
			}
		break;
		case "change":
			
			if(!get_module_pref('hasswimsuit')){
				output("Nudity just isn't allowed here yet.");
				break;
			}
			if(!get_module_pref('wearsuit')){
				output("You change into your swimsuit.");
				set_module_pref('armordesc',$session['user']['armor']);
				set_module_pref('weapondesc',$session['user']['weapon']);
				set_module_pref('weaponvalue',$session['user']['weapondmg']);
				set_module_pref('weaponcost',$session['user']['weaponvalue']);
				set_module_pref('armorvalue',$session['user']['armordef']);
				set_module_pref('armorcost',$session['user']['armorvalue']);
				set_module_pref('pattack',$session['user']['attack']);
				set_module_pref('pdefense',$session['user']['defense']);
				$pattack = $session['user']['attack'];
				$pweapon = $session['user']['weapondmg'];
				$pdefense = $session['user']['defense'];
				$parmor = $session['user']['armordef'];
				debuglog("changed into swimsuit: $pattack, $pweapon, $pdefense, $parmor");
				$session['user']['weapondmg'] = 0;
				$session['user']['weaponvalue'] = 0;
				$session['user']['armordef'] = 0;
				$session['user']['armorvalue'] = 0;
				
				$session['user']['attack'] = $pattack - $pweapon;
				$session['user']['defense'] = $pdefense - $parmor;
				set_module_pref('wearsuit',1);
				if($session['user']['sex']== 0){
					if($session['user']['charm']<30){
						$session['user']['armor'] = "Grandpa's Waders";
						$session['user']['weapon'] = "Ugly Hat";
					}elseif($session['user']['charm']<60){
						$session['user']['armor'] = "Two Piece";
						$session['user']['weapon'] = "Lawn Chair";
					}elseif($session['user']['charm'] < 90){
						$session['user']['armor'] = "Small Neon Yellow Shorts";
						$session['user']['weapon']= "Sunscreen";
					}elseif($session['user']['charm'] <120){
						$session['user']['armor'] = "Swim Trunks";
						$session['user']['weapon'] = "Water Bottle";
					}elseif($session['user']['charm'] < 150){
						$session['user']['armor'] = "Speedo";
						$session['user']['weapon'] = "Shiny Sunglasses";
					}else{
						$session['user']['armor'] = "Completely Nude";
						$session['user']['weapon'] = "Tanning Oil";
					}
				}else{
					if($session['user']['charm']<30){
						$session['user']['armor'] = "Grandma's Swimsuit";
						$session['user']['weapon'] = "Beach Umbrealla";
					}elseif($session['user']['charm']<60){
						$session['user']['armor'] = "One Piece";
						$session['user']['weapon'] = "Lawn Chair";
					}elseif($session['user']['charm'] < 90){
						$session['user']['armor'] = "Two Piece";
						$session['user']['weapon']= "Sunscreen";
					}elseif($session['user']['charm'] <120){
						$session['user']['armor'] = "String Bikini";
						$session['user']['weapon'] = "Water Bottle";
					}elseif($session['user']['charm'] < 150){
						$session['user']['armor'] = "Topless";
						$session['user']['weapon'] = "Shiny Sunglasses";
					}else{
						$session['user']['armor'] = "Completely Nude";
						$session['user']['weapon'] = "Tanning Oil";
					}
				}
						
			}else{
				output("You change back into your armor and strap your weapon to your back.");
				$pattack = get_module_pref('pattack');
				$pweapon = get_module_pref('weaponvalue');
				$pdefense = get_module_pref('pdefense');
				$parmor = get_module_pref('armorvalue');
				debuglog("changed into swimsuit: $pattack, $pweapon, $pdefense, $parmor");
				$session['user']['armor'] = get_module_pref('armordesc');
				$session['user']['weapon'] = get_module_pref('weapondesc');
				$session['user']['weapondmg'] = get_module_pref('weaponvalue');
				$session['user']['weaponvalue'] = get_module_pref('weaponcost');
				$session['user']['armordef'] = get_module_pref('armorvalue');
				$session['user']['armorvalue'] = get_module_pref('armorcost');
				$session['user']['attack'] = get_module_pref('pattack');
				$session['user']['defense'] = get_module_pref('pdefense');
				set_module_pref('weaponcost',0);
				set_module_pref('weaponvalue',0);
				set_module_pref('armorcost',0);
				set_module_pref('armorvalue',0);
				set_module_pref('pattack',0);
				set_module_pref('pdefense',0);
				set_module_pref('wearsuit',0);
				set_module_pref('weapondesc','');
				set_module_pref('armordesc','');
				
			}
			addnav("Back to the Beach", "runmodule.php?module=longbeach&op=enter");	
		break;
		case "enter":
			modulehook("beachtop",$texts);
			output("The path leads to a well groomed white sand beach. ");
			output("Tall sea oats help shelter the dunes along the path and create a haven for small creatures scuttling about as you walk down the path.");
			output(" At the end of the path, the sand becomes soft under foot and a bit difficult to walk on.");
			output(" Various vendors hawk their wares as you lay out your blanket and gear.`n`n");
			if(get_module_pref('hasswimsuit')){
				addnav("Changing Rooms","runmodule.php?module=longbeach&op=change");
				if(get_module_pref('wearsuit')){
					addnav("Go Swimming","runmodule.php?module=longbeach&op=swim");
					addnav("Sunbathe","runmodule.php?module=longbeach&op=sunbathe");			
				}
			}
			addcommentary();
			output("`2You chat idly as you watch the view and soak up the rays of the sun.`n`n");
			viewcommentary("Beach","",15,"says");
			modulehook("beach");
			
		break;
		case "sellitem":
			addnav("Return to the Store","runmodule.php?module=generalstore");
			if (!get_module_pref('hasswimsuit')){
				output ("You don't have a swimsuit to sell.`n");
				break;
			}
			$sellprice = get_module_setting('swimsuitcost')*.4;
						
			if (get_module_setting('allowcharm')){
				$charmmodifier = $session['user']['charm'] / get_module_pref('generalstore','charmfactor');
				$sellprice =$sellprice + ($sellprice * $charmmodifier);
				$sellgems = $sellgems + ($sellgems * $charmmodifier);
			}
			set_module_pref('hasswimsuit',0);
			
		break;
		case "sunbathe":
			if(get_module_pref('sunbathed')){
				output("You realize you are on the verge of burning and decide not to tan anymore today.`n");
				addnav("Back to the Conversation", "runmodule.php?module=longbeach&op=enter");
				break;
				}
			$chancemod = 0;
			modulehook('sunbathe');
			$tanchance = get_module_setting('tanchance') + $chancemod;
			
			if($tanchance >= e_rand(1,100)){
				switch(e_rand(1,10)){
					case 1:
						output("You sunbathe just a little too long and get a nasty burn.`n");
						output("You lose one charm!");
						$session['user']['charm'] -= 1;
					break;
					case 2:
					case 3:
						output("Laying in the sun just does not seem to have helped today.");
						break;
					case 4:
					case 5:
					case 6:
					case 7:
					case 8:
					case 9:
					case 10:
						output("You bake in the sun just long enough not to burn.`n");
						output("You gain one charm.");
						$session['user']['charm'] += 1;
					break;
				}
			}else{
				switch(e_rand(1,5)){
					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
						output("You decide to quit sunbathing just a little bit too soon.");
					break;
				}
			}	
			set_module_pref('sunbathed',1);		
			addnav("Back to the Conversation", "runmodule.php?module=longbeach&op=enter");
		break;
		case "swim":
			if(get_module_pref('swam')){
				output("You are all worn out by the surf and salt water.  Enough swimming for one day.`n");
				addnav("Back to the Conversation", "runmodule.php?module=longbeach&op=enter");
				break;
				}

			$swimchance = get_module_setting('swimchance');
			$drownchance = get_module_setting('drownchance');
			
			if($swimchance >= e_rand(1,100)){
				switch(e_rand(1,10)){
					case 1:
						output("A great day for swimming!  You finish up and feel fully refreshed.`n");
						$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
					break;
					case 2:
					case 3:
						output("After that brisk swim, you feel energized and ready to take on another trip to the forest.");
						++$session['user']['turns'];
					break;
					case 4:
					case 5:
					case 6:
					case 7:
					case 8:
					case 9:
					case 10:
						output("You go out and enjoy yourself in the surf, but you just can't bring yourself to go past your knees.`n");
					break;
				}
			}else{
				switch(e_rand(1,5)){
					case 1:
						if($drownchance >= e_rand(1,100)){
							$session['user']['alive']=false;
							addnav("Daily news","news.php");
							addnews("%s was swept out to sea and drowned.",$session['user']['name']);
						}else{
							output("The surf was quite rough.  It took you a bit to make it back to shore after being pulled out into the sea.`n`n");
							if($session['user']['turns']>0){
								output("You lose 1 turn.");
								--$session['user']['turns'];
							}else{
								output("You look like a raisin now.  You lose one charm.`n");
								--$session['user']['charm'];
							}
						}
					break;
					case 2:
						if($drownchance >= e_rand(1,100)){
							$session['user']['alive']=false;
							addnav("Daily news","news.php");
							addnews("A large shark happened along and %s was eaten.",$session['user']['name']);
						}else{
							output("The surf was quite rough.  It took you a bit to make it back to shore after being pulled out into the sea.`n`n");
							if($session['user']['turns']>0){
								output("You lose 1 turn.`n");
								--$session['user']['turns'];
							}else{
								output("You look like a raisin now.  You lose one charm.`n");
								--$session['user']['charm'];
							}
						}
					break;
					case 3:
					case 4:
					case 5:
						output("You are exhausted by the pounding of the serf.");
					break;
				}
			}	
			set_module_pref('swam',1);		
			addnav("Back to the Conversation", "runmodule.php?module=longbeach&op=enter");
		break;
	
	}
	addnav("Leave");
   	villagenav();
   	
    page_footer();
}
?>
