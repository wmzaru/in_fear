<?php

require_once("lib/villagenav.php");
require_once("lib/http.php");

function thiefden_getmoduleinfo() {
    $info = array(
        "name"=>"The Thieves Den and Upstairs",
        "version"=>"1.0",
        "author"=>"Sneakabout",
        "category"=>"Inn",
        "download"=>"http://dragonprime.net/users/Sneakabout/thiefden.txt",
        "settings"=>array(
            "Thieves Den and Upstairs - Settings,title",
            "traintimes"=>"How many times can you train each day?,int|3",
			"traincost"=>"How much does it cost to train per specialty level? (this is the lower limit.),int|200",
			"storelockcost"=>"How much does it cost to get the key to the storeroom (per level)?,int|500",
			"inviscost"=>"How much does the potion of invisibility cost?,int|10",
			"poisoncost"=>"How much does the vial of poison cost?,int|8",
			"dkclear"=>"Do the thief items clear on Dragon Kill?,bool|1",
			"truegemcost"=>"How much does the Truesight Gem cost?,int|15",
			"pvpdrug"=>"Can the players buy more PvP fights here?,bool|1",
			"pvpdruggemcost"=>"How much do PvP fights cost in gems?,int|4",
			"pvpdruggoldcost"=>"How much do PvP fights cost in gold?,int|1500",
			"pvpdrugbuff"=>"How long do the side-effects last on new day per dose?,int|35",
			"pvpdrugamount"=>"How many doses can they take each day?,int|2",
        ),
        "prefs"=>array(
            "Thieves Den and Upstairs - User Preferences,title",
			"hastrained"=>"How many times has this person trained today?,int|0",
			"invispotions"=>"How many potions of invisibility does this person have?,int|0",
			"poisonvials"=>"How many poison vials does this person have?,int|0",
			"storekey"=>"Does this person have a key to Cedrik's Storeroom?,bool|0",
			"truegem"=>"Does this person have a Truesight Gem?,bool|0",
			"pvpdrugs"=>"How many doses of the PvP drug have they taken?,int|0",
        )
    );
    return $info;
}

function thiefden_install(){
	module_addhook("footer-inn");
	module_addhook("newday");
	module_addhook("dragonkilltext");
	module_addhook("footer-runmodule");
	module_addhook("fightnav-specialties");
	module_addhook("apply-specialties");
    return true;
}

function thiefden_uninstall(){
    return true;
}

function thiefden_dohook($hookname,$args){
    global $session;
    switch($hookname){
		case "newday":
		set_module_pref("hastrained",0);
		set_module_pref("truegem",0);
		if (get_module_pref("pvpdrugs")) {
			$pvpdrugs=get_module_pref("pvpdrugs");
			output("`n`\$As you pry your eyelids apart, your whole body regrets whatever that stuff Randall sold to you was.");
			output("You can barely stand, and you'll ache for hours!`0`n");
			$session['user']['turns']-=$pvpdrugs;
			apply_buff('pvpdrugbuff',array(
				"name"=>"`%Aching Limbs",
				"rounds"=>(get_module_setting("pvpdrugbuff")*$pvpdrugs),
				"wearoff"=>"Your joints stop screaming.",
				"atkmod"=>0.8,
				"defmod"=>0.8,
				"roundmsg"=>"Your muscles protest your movement!", 
				"schema"=>"thiefden"
			));
			set_module_pref("pvpdrugs",0);
		}
		break;
		case "creatureencounter":
		if (get_module_pref("truegem")) {
			$gain = 135/100;
			$args['creaturegold']=round($args['creaturegold']*$gain,0);
		}
		break;
		case "dragonkilltext":
		if (get_module_setting("dkclear")) {
			set_module_pref("invispotions",0);
			set_module_pref("poisonvials",0);
			set_module_pref("storekey",0);
		}
		break;
		case "fightnav-specialties":
		$script = $args['script'];
		if (get_module_pref("invispotions") > 0) {
			addnav("`7Randall's Goods`0");
			addnav(array("`&Potion of Invisibility`7 (%s)`0", get_module_pref("invispotions")), 
					$script."op=fight&skill=thiefden&l=invis", true);
		}
		if (get_module_pref("poisonvials") > 0) {
			addnav("`7Randall's Goods`0");
			addnav(array("`@Vial of Poison`7 (%s)`0", get_module_pref("poisonvials")),
					$script."op=fight&skill=thiefden&l=poison",true);
		}
		break;
		case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill=="thiefden"){
			switch($l){
			case "invis":
			apply_buff('potinvis',array(
				"startmsg"=>"`&You quickly quaff the potion and fade out of {badguy}'s sight.",
				"name"=>"`&Invisibility",
				"rounds"=>6,
				"wearoff"=>"The potion wears off.",
				"roundmsg"=>"{badguy} cannot see you, and swings wildly!",
				"badguyatkmod"=>0,
				"schema"=>"thiefden"
			));
			set_module_pref("invispotions",(get_module_pref("invispotions")-1));
			break;
			case "poison":
			apply_buff('vialpois',array(
				"startmsg"=>"`@You apply some poison to your {weapon}.",
				"name"=>"`@Poison Attack",
				"rounds"=>3,
				"wearoff"=>"Your victim's blood has washed the poison from your {weapon}.",
				"atkmod"=>2.5,
				"roundmsg"=>"Your attack is multiplied!", 
				"schema"=>"thiefden"
			));
			set_module_pref("poisonvials",(get_module_pref("poisonvials")-1));
			break;
			}
		}
		break;
		case "footer-inn":
		$act=httpget("act");
		$op=httpget("op");
		if ((($act=="listupstairs")OR(($session['user']['boughtroomtoday'])&&($op=="room")))&&($session['user']['turns']>0)) {
            output("To one side, a dark narrow stairway leads to the maze of passages which Cedrik calls an Inn. You could take this chance to explore them, though it could take a while.");
			addnav("Prowl the Corridors","runmodule.php?module=thiefden&op=prowl");
		}
		break;
		case "footer-runmodule":
		$op=httpget("op");
		$module=httpget("module");
		if (($module=="breakin")&&($op=="force")&&($session['user']['turns']>0)) {
			output("To the other side, a dark narrow stairway leads to the maze of passages which Cedrik calls an Inn. You could take this chance to explore them, though it could take a while.");
			addnav("Prowl the Corridors","runmodule.php?module=thiefden&op=prowl");
		}
		break;
		}
    return $args;
}

function thiefden_run() {
    global $session;
	$op = httpget('op');
	
	switch($op){
		case "prowl":
		$session['user']['turns']--;
		$rand=e_rand(1,10);
		output("`7You make your way up the staircase and hurry past the first few corridors to be well away of the main room before you start exploring. Within minutes you are lost in a maze of small passages with rooms seemingly numbered at random.");
		page_header("Upstairs In The Inn");
		switch($rand){
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			if (!get_module_pref("skill","specialtythiefskills")) {
				output("`n`nOnce you start looking around, one door seems to stick out from the others in some way, with curious markings on the door jamb.");
				output("However, since you have no key you cannot get into the room. After a while, you find a way out through the stables and return to the village.");
			} else {
				$rand=e_rand(5,16);
				if (get_module_pref("skill","specialtythiefskills")<$rand) {
					output("`n`nOnce you start looking around, you notice one door as being marked as of interest by another thief, and you can roughly make out some coded markings on the jamb, telling of some kind of store.");
					output("However, although you struggle with the lock for a while, it is simply too difficult for you to open. You leave in frustration, finding a way out through the stables and back to the village.");
				} else {
					output("`n`nOnce you start looking around, you notice one door as being marked as of interest by another thief, and in clear code on the jamb it states that there is a seller of sneaksman's goods inside the room.");
					output("`n`n`&The lock puts up a brief struggle, but you soon hear the tell-tale click of a lock well picked. Do you wish to enter the room?");
					addnav("Enter the Room","runmodule.php?module=thiefden&op=entershop");
				}
			}
			villagenav();
			break;
			case 6:
			output("`n`nThrough these passages you keep following the most worn path, seeking to find something interesting at the end.");
			output("Eventually, after descending through a twisty staircase, you find yourself in front of a sturdy doorway, covered in warning signs, but with the tell-tale aroma of ale wafting from beyond it.");
			output("This is obviously Cedrik's storeroom, filled with alcohol your liver twinges to even think about. However, it could be risky - it was rumoured the last person who broke in ended up as an ingredient in one of Cedrik's drinks.");
			addnav("Enter the Storeroom","runmodule.php?module=thiefden&op=storeroom");
			villagenav();
			break;
			case 7:
			output("`n`nAfter trying various doors and finding them all locked, you grow frustrated and start trying to force your way into a room which looks to be expensive.");
			output("When the door opens by itself, you realise you may have made a mistake. The presence of most of the bodyguards hired by Cedrik and a distinct atmosphere of coffee and cards leads you to believe that this must be where they go when they relax. And they don't like interruptions.");
			output("After some time which is best described as involving beatings, they throw you out of a window and leave you in the Square, bereft of gold and with a surfeit of bruises.");
			$session['user']['hitpoints']=1;
			debuglog("lost all gold on hand ({$session['user']['gold']}) to some angry bodyguards.");
			$session['user']['gold']=0;
			villagenav();
			break;
			case 8:
			output("`n`n`&One door draws your attention as being rather more used than the others, and you push on it to find that it is unlocked!");
			output("Inside you find all sorts of %s things, and %s. You realise that this is %s`&'s room, and catch sight of a diary on a small table.",$session['user']['sex']?"manly":"girly",$session['user']['sex']?"some sheet music":"a pile of slippers",$session['user']['sex']?"`^Seth":"`%Violet");
			output("After some useful reading, you sneak away, sure that you can use what you learnt in attracting %s`&'s attention next time.",$session['user']['sex']?"`^Seth":"`%Violet");
			$session['user']['charm']+=2;
			villagenav();
			break;
			case 9:
			output("`n`nAfter wandering through what seems like endless passages, you start seeing familiar doors and corridors. Determined to find something worth your time, you keep looking and eventually find a strange chute at the end of a corridor.");
			output("You lean in to try and see what lies at the bottom, when someone shoves you from behind and you go tumbling down. After a bruising journey, you land in something soft. Something very smelly.");
			output("Once you manage to dig yourself out, you find that you are in the stables, in the corner filled with the dung of various animals, all of which stinks. You feel less charming as you squelch back to the village!");
			$session['user']['charm']-=2;
			villagenav();
			break;
			case 10:
			$gold=e_rand(10,$session['user']['level']*20);
			$session['user']['gold']+=$gold;
			output("`n`nLooking through various unlocked rooms, you manage to scavenge %s gold and are about to leave the Inn with your ill-gotten gains when a guard spots you coming out of someone's room!",$gold);
			debuglog("gained $gold gold from the Inn.");
			output("He rushes towards you, sword drawn - you'll have to fight him!");
			addnav("Fight the guard","runmodule.php?module=thiefden&fight=guard");
			break;
		}
		break;
		case "storeroom":
		page_header("Cedrik's Storeroom");
		$rand=e_rand(1,12);
		if (get_module_pref("storekey")) {
			output("`2Remembering the key you bought from Randall (at great expense), you fish it out of your pouch and turn it in the lock.");
			output("As you hear the lock click and the door swing open, the key crumbles to dust in your hand. You curse Randall's trickery for a second, before admiring him for his business savvy.");
			set_module_pref("storekey",0);
			switch($rand){
				case 1:
				case 2:
				case 3:
				output("You walk into the storeroom, your liver trembling..... so much alcohol........");
				output("`n`n`n`&You wake up in a forest clearing evidently some time later. You can't remember a thing about the last couple of hours, and that must be a good thing, right?");
				output("You stagger back to the edges of the forest, still completely blotto and very cheerful indeed.");
				set_module_pref("drunkeness",100,"drinks");
				$session['user']['hitpoints']*=2;
				$session['user']['turns']--;
				apply_buff('blotto',array(
					"name"=>"`&Total Drunkeness",
					"rounds"=>30,
					"wearoff"=>"You can see straight again!",
					"atkmod"=>1.4,
					"defmod"=>0.9,
					"roundmsg"=>"You may be unable to stand up straight, but seeing that you're outnumbered, you fight furiously!",
					"schema"=>"thiefden"
				));
				$vloc = modulehook('validforestloc', array());
				$key = array_rand($vloc);
				$session['user']['location'] = $key;
				addnav("Go to the Forest","forest.php");
				break;
				case 4:
				case 5:
				case 6:
				output("You walk into the storeroom, your liver trembling..... so much alcohol........");
				output("`n`nInterested in trying some of Cedrik's rarer stock, you go straight for a small keg near the door, and quaff down half the contents in one go...........");
				output("`n`n`n`&You wake up in a forest clearing evidently some time later. You can't remember a thing about the last couple of hours, and from the way your head feels, that's a good thing.");
				output("You stagger back to the edges of the forest, still completely hammered and very unhappy indeed.");
				set_module_pref("drunkeness",100,"drinks");
				$session['user']['hitpoints']*=0.5;
				$session['user']['turns']--;
				apply_buff('unhappydrunk',array(
					"name"=>"`3Total Drunkeness",
					"rounds"=>30,
					"wearoff"=>"You can see straight again!",
					"atkmod"=>0.75,
					"defmod"=>0.9,
					"roundmsg"=>"You can't even stand up straight, let alone fight in this condition.",
					"schema"=>"thiefden"
				));
				$vloc = modulehook('validforestloc', array());
				$key = array_rand($vloc);
				$session['user']['location'] = $key;
				addnav("Go to the Forest","forest.php");
				break;
				case 7:
				output("You sneak in, getting very happily drunk indeed, but not drunk enough to dent your searching skills. One keg feels odd when you're drinking from it, and you investigate further....");
				output("After a few minutes of tapping and prying, the bottom falls off to reveal a stash of gold and gems! You stare confused for a moment, before grabbing the wealth and scarpering.");
				$gold=e_rand(100,$session['user']['level']*100);
				$gems=e_rand(2,5);
				output("Later in the square you count your ill-gotten gains and find that you managed to snatch %s gold and %s gems! That'll buy a few beers....",$gold,$gems);
				$session['user']['gold']+=$gold;
				set_module_pref("drunkeness",100,"drinks");
				$session['user']['hitpoints']*=1.5;
				$session['user']['gems']+=$gems;
				apply_buff('happydrunk',array(
					"name"=>"`&Drunkeness",
					"rounds"=>15,
					"wearoff"=>"You can see straight again!",
					"atkmod"=>1.2,
					"defmod"=>0.95,
					"roundmsg"=>"You may be unable to stand up straight, but seeing that you're outnumbered, you fight furiously!",
					"schema"=>"thiefden"
				));
				debuglog("gained $gold gold and $gems gems from Cedrik's storeroom.");
				villagenav();
				break;
				case 8:
				output("Amazed at your good luck, you creep in to find Cedrik, struggling to lift a gigantic keg of ale!");
				output("Luckily, you think fast and grab the days takings Cedrik left by the door and flee the storeroom.");
				$gold=e_rand(200,$session['user']['level']*500);
				output("Later in the square you count your ill-gotten gains and find that you managed to snatch %s gold! That'll buy a few beers....",$gold);
				$session['user']['gold']+=$gold;
				debuglog("gained $gold gold from Cedrik's storeroom.");
				villagenav();
				break;
				case 9:
				case 10:
				output("Amazed at your good luck, you creep in to find Cedrik, struggling to lift a gigantic keg of ale!");
				output("Luckily, you think fast and grab the pouch of gold Cedrik left by the door and flee the storeroom.");
				$gold=e_rand(100,$session['user']['level']*100);
				output("Later in the square you count your ill-gotten gains and find that you managed to snatch %s gold! That'll buy a few beers....",$gold);
				$session['user']['gold']+=$gold;
				debuglog("gained $gold gold from Cedrik's storeroom.");
				villagenav();
				break;
				case 11:
				case 12:
				output("You walk into the storeroom, your liver trembling..... so much alcohol........");
				output("`n`nYou immediately start quaffing away from the nearest keg, quite unaware of Cedrik watching you from a corner, his face darkening in apoplectic anger.");
				set_module_pref("drunkeness",100,"drinks");
				output("However, you become aware of his presence when he grabs you by the scruff of your neck and forcibly throws you out of the inn, through the door, shouting about thieves all the way.");
				output("The wall which \"breaks\" your fall quite takes any enjoyment which you might have gained from the ale, as well as and bones which were still intact.");
				output("You stagger away from the inn, thinking that you had best wait a bit for Cedrik to forget this incident, as well as get that door fixed, before going in there again.");
				set_module_pref("guilt",2,"breakin");
				$session['user']['hitpoints']=2;
				$session['user']['turns']--;
				villagenav();
				break;
			}
		} else {
			switch($rand){
				case 1:
				case 2:
				case 3:
				case 4:
				case 5:
				output("`3As you go to open the door, your liver trembling in anticipation, you slowly turn the handle and... nothing. The door is locked of course.");
				output("Desperately seeking the precious alcohol, you start battering on the door in vain, as the old oak holds firm.");
				output("Your efforts to open the door are too feeble to open the door.... and too feeble to hide the sound of approaching footsteps!");
				output("You rush upstairs and dive out of a handy window, just in time to avoid Cedrik, going to fetch another cask for his thirsty patrons.");
				output("Tired and covered in dust, you feel and look terrible, but at least you're still alive. You stagger back to the village, determined to drink less in the future.");
				if ($session['user']['charm']>0) $session['user']['charm']--;
				if ($session['user']['turns']>0) $session['user']['turns']--;
				villagenav();
				break;
				case 6:
				case 7:
				case 8:
				output("`3As you go to open the door, your liver trembling in anticipation, you slowly turn the handle and... nothing. The door is locked of course.");
				output("Desperately seeking the precious alcohol, you start battering on the door, to have the rotten timbers give way before your too-sober might! You are into the storeroom...........");
				output("`n`n`n`&You wake up in a forest clearing evidently some time later. You can't remember a thing about the last couple of hours, and that must be a good thing, right?");
				output("You stagger back to the edges of the forest, still completely blotto and very cheerful indeed.");
				set_module_pref("drunkeness",100,"drinks");
				if (is_module_active("breakin")) set_module_pref("guilt",2,"breakin");
				$session['user']['hitpoints']*=2;
				$session['user']['turns']--;
				apply_buff('happydrunk',array(
					"name"=>"`&Total Drunkeness",
					"rounds"=>30,
					"wearoff"=>"You can see straight again!",
					"atkmod"=>1.4,
					"defmod"=>0.9,
					"roundmsg"=>"You may be unable to stand up straight, but seeing that you're outnumbered, you fight furiously!",
					"schema"=>"thiefden"
				));
				$vloc = modulehook('validforestloc', array());
				$key = array_rand($vloc);
				$session['user']['location'] = $key;
				addnav("Go to the Forest","forest.php");
				break;
				case 9:
				output("`3As you go to open the door, your liver trembling in anticipation, you slowly turn the handle and... the door swings open!");
				output("Amazed at your good luck, you creep in to find Cedrik, struggling to lift a gigantic keg of ale!");
				output("Luckily, you think fast and grab the pouch of gold Cedrik left by the door and flee the storeroom.");
				$gold=e_rand(100,$session['user']['level']*100);
				output("Later in the square you count your ill-gotten gains and find that you managed to snatch %s gold! That'll buy a few beers....",$gold);
				$session['user']['gold']+=$gold;
				debuglog("gained $gold gold from Cedrik's storeroom.");
				villagenav();
				break;
				case 10:
				output("`3As you go to open the door, your liver trembling in anticipation, you slowly turn the handle and... nothing. The door is locked of course.");
				output("Desperately seeking the precious alcohol, you start battering on the door, to have the rotten timbers give way before your too-sober might! You are into the storeroom...........");
				output("`n`nInterested in trying some of Cedrik's rarer stock, you go straight for a small keg near the door, and quaff down half the contents in one go....");
				output("`n`n`n`&You wake up in a forest clearing evidently some time later. You can't remember a thing about the last couple of hours, and from the way your head feels, that's a good thing.");
				output("You stagger back to the edges of the forest, still completely hammered and very unhappy indeed.");
				set_module_pref("drunkeness",100,"drinks");
				if (is_module_active("breakin")) set_module_pref("guilt",2,"breakin");
				$session['user']['hitpoints']*=0.5;
				$session['user']['turns']--;
				apply_buff('unhappydrunk',array(
					"name"=>"`3Total Drunkeness",
					"rounds"=>30,
					"wearoff"=>"You can see straight again!",
					"atkmod"=>0.75,
					"defmod"=>0.9,
					"roundmsg"=>"You can't even stand up straight, let alone fight in this condition.",
					"schema"=>"thiefden"
				));
				$vloc = modulehook('validforestloc', array());
				$key = array_rand($vloc);
				$session['user']['location'] = $key;
				addnav("Go to the Forest","forest.php");
				break;
				case 11:
				case 12:
				output("`3As you go to open the door, your liver trembling in anticipation, you slowly turn the handle and... nothing. The door is locked of course.");
				output("Desperately seeking the precious alcohol, you start battering on the door, to have the rotten timbers give way before your too-sober might! You are into the storeroom...........");
				output("You walk into the storeroom, your liver trembling..... so much alcohol........");
				output("`n`nYou immediately start quaffing away from the nearest keg, quite unaware of Cedrik watching you from a corner, his face darkening in apoplectic anger.");
				set_module_pref("drunkeness",100,"drinks");
				output("However, you become aware of his presence when he grabs you by the scruff of your neck and forcibly throws you out of the inn, through the door, shouting about thieves all the way.");
				output("The wall which \"breaks\" your fall quite takes any enjoyment which you might have gained from the ale, as well as and bones which were still intact.");
				output("You stagger away from the inn, thinking that you had best wait a bit for Cedrik to forget this incident, as well as get that door fixed, before going in there again.");
				if (is_module_active("breakin")) set_module_pref("guilt",2,"breakin");
				$session['user']['hitpoints']=2;
				$session['user']['turns']--;
				villagenav();
				break;
			}
		}
		break;
		case "entershop":
		page_header("Randall's Shop");
		$pvpdrug=get_module_setting("pvpdrug");
		output("`3You nervously edge into the room, eyes searching for a threat somewhere in a room which seems sparse, with a small rack of potions and vials near the bed and a selection of books on a small table.");
		output("As you close the door behind you, someone clears their throat to your left and you almost jump out of your skin as you whip round to see who it is.");
		output("In one corner of the room, shrouded in shadows and obscured by the coat and hat hanging on the door is a red-headed man, presumably the \"Randall\" mentioned in the code on the jamb.");
		output("`n`nHe straightens up, somehow keeping his face concealed throughout, and says, \"`\$Greetings, sneaksman.... you're past the lock, so you may well be worth my time, take a look around, tell me what you want... if you've the funds for it.`3\"");
		output("With that he leans back, further into the shadows. As you look around the room, he watches you from the darkness, assessing you.");
		output("In the room you can see the tools of your trade, almost-hidden beneath innocent-looking objects, one of which interests you in particular, a key with a label reading \"Storeroom\" on it and the number `^%s`3 as well.",(get_module_setting("storelockcost")*$session['user']['level']));
		output("On the rack of potions you can see potions of concealment and vials of poison neatly racked into rows and with tags of `%%s`3 and `%%s gems`3 respectively, with a few small prisms stacked beneath labelled with \"TrueSight\" and `%%s gems`3.",get_module_setting("inviscost"),get_module_setting("poisoncost"),get_module_setting("truegemcost"));
		if ($pvpdrug) output("Near the rack of potions, a pestle and mortar looks out of place, with dried roots next to it, and papers with the crushed powder nearby.");
		output("Randall seems to know a lot about thievery, you could probably learn a lot from him, if he chose to teach you... and you made it worth his while.");
		addnav("Shop Options");
		modulehook("thiefshop");
		if ((get_module_pref("hastrained")<=get_module_setting("traintimes"))&&($session['user']['specialty']=="TS")) addnav("Purchase Training","runmodule.php?module=thiefden&op=train");
		if (!get_module_pref("storekey")) addnav("Storeroom Key","runmodule.php?module=thiefden&op=storekey");
		addnav("Potion of Concealment","runmodule.php?module=thiefden&op=invisibility");
		addnav("Vial of Poison","runmodule.php?module=thiefden&op=poison");
		if (!get_module_pref("truegem")) addnav("Gem of Truesight","runmodule.php?module=thiefden&op=truegem");
		if ($pvpdrug && get_module_pref("pvpdrugs")<get_module_pref("pvpdrugamount")) addnav("Strange Powder","runmodule.php?module=thiefden&op=pvpdrugcheck");
		villagenav();
		break;
		case "shop":
		page_header("Randall's Shop");
		$pvpdrug=get_module_setting("pvpdrug");
		output("`3Your transaction with Randall completed, you look around the room for what else there is to buy.");
		output("In the room you can see the tools of your trade, almost-hidden beneath innocent-looking objects, one of which interests you in particular, a key with a label reading \"Storeroom\" on it and the number `^%s`3 as well.",(get_module_setting("storelockcost")*$session['user']['level']));
		output("On the rack of potions you can see potions of concealment and vials of poison neatly racked into rows and with tags of `%%s`3 and `%%s gems`3 respectively, with a few small prisms stacked beneath labelled with \"TrueSight\" and `%%s gems`3.",get_module_setting("inviscost"),get_module_setting("poisoncost"),get_module_setting("truegemcost"));
		if ($pvpdrug) output("Near the rack of potions, a pestle and mortar looks out of place, with dried roots next to it, and papers with the crushed powder nearby.");
		output("Randall seems to know a lot about thievery, you could probably learn a lot from him, if he chose to teach you... and you made it worth his while.");
		addnav("Shop Options");
		modulehook("thiefshop");
		if ((get_module_pref("hastrained")<=get_module_setting("traintimes"))&&($session['user']['specialty']=="TS")) addnav("Purchase Training","runmodule.php?module=thiefden&op=train");
		if (!get_module_pref("storekey")) addnav("Storeroom Key","runmodule.php?module=thiefden&op=storekey");
		addnav("Potion of Concealment","runmodule.php?module=thiefden&op=invisibility");
		addnav("Vial of Poison","runmodule.php?module=thiefden&op=poison");
		if (!get_module_pref("truegem")) addnav("Gem of Truesight","runmodule.php?module=thiefden&op=truegem");
		if ($pvpdrug && get_module_pref("pvpdrugs")<get_module_pref("pvpdrugamount")) addnav("Strange Powder","runmodule.php?module=thiefden&op=pvpdrugcheck");
		villagenav();
		break;
		case "train":
		page_header("Randall's Shop");
		require_once("lib/increment_specialty.php");
		$multiply=e_rand(10,20)/10;
		$oldskill=$session['user']['specialty'];
		$session['user']['specialty']="TS";
		$skill=get_module_pref("skill","specialtythiefskills");
		$cost=$multiply*get_module_setting("traincost")*$skill;
		if (!$session['user']['gold']) {
			output("`3You go to ask Randall for training, but remember that you have no gold. Asking him when you can pay for his services could be a better idea.");
		} elseif ($session['user']['gold']<$cost) {
			output("`3You go to Randall with your gold pouch open which he lazily takes from you and glances inside.`n`nHe chuckles for a moment before saying, \"`\$Well, you do have some funds I guess, I can teach you a short lesson...");
			output("Lesson One: Never give your gold pouch to anyone, especially if you don't have enough gold to truly interest them...`3\". He throws you the empty pouch back, and you are about to complain when you remember that you are in his territory.");
			output("Nervously looking around for traps, you thank him for the lesson, and pocket your empty pouch, muttering to yourself.");
			debuglog("lost {$session['user']['gold']} asking for training.");
			$session['user']['gold']=0;
		} else {
			output("`3You go to Randall with your gold pouch open which he lazily takes from you and glances inside.`n`nHe chuckles for a moment before saying, \"`\$Well, you do have some funds I guess, I can teach you a few things...");
			output("Lesson One: Never give your gold pouch to anyone... anyone except me of course.`3\". He throws you the much lighter pouch back,");
			$session['user']['gold']-=$cost;
			debuglog("spent $cost gold on training");
			if ($skill<8) {
				output("and begins to talk at length on the benefits of certain lockpicks, the choice and care of equipment and the use of concealment.");
				increment_specialty("`3");
				set_module_pref("hastrained",(get_module_pref("hastrained")+1));
				output("At the end of the lecture, you know where you've been going wrong, and thank Randall for his time and expertise spent educating you.");
			} elseif ($skill>35) {
				output("and begins to go on about techniques of robbing, strategy, use of poisons and all sorts of useful topics. However, you've heard it all before, and it isn't really of use to you.");
				output("At the end of the lecture, you're a little bored, but you thank Randall for his time anyway.");
			} else {
				output("and starts to enlighten you on robbing, the use of shadows, use of poisons and all sorts of useful thieving topics.");
				increment_specialty("`3");
				set_module_pref("hastrained",(get_module_pref("hastrained")+1));
				output("At the end of the lecture, you know more about the mechanics of thievery, and you thank Randall for his time and expertise spent educating you.");
			}
		}
		$session['user']['specialty']=$oldskill;
		addnav("Look Around","runmodule.php?module=thiefden&op=shop");
		villagenav();
		break;
		case "storekey":
		page_header("Randall's Shop");
		$cost=get_module_setting("storelockcost")*$session['user']['level'];
		if ($session['user']['gold']<$cost) {
			output("`3You start to go to Randall, asking about the keys, then notice his gaze go to your gold pouch. You look again at the label on the key, and feel ashamed as you realise that you don't have enough gold.");
			output("Perhaps you should buy something you can afford?");
		} else {
			output("`3You go to Randall, and ask for the key to the storeroom. He nods and says, \"`\$Remember, if you get caught with this.....`3\". You nod, and reply, \"`&Yeah, I know, you don't exist.`3\".");
			output("Both of you blink for a moment before he takes the gold and you get the key. You secure it in your pouch, hoping to run into the fabled storeroom some time so you can use it.");
			set_module_pref("storekey",1);
			$session['user']['gold']-=$cost;
			debuglog("spent $cost gold on a key");
		}
		addnav("Look Around","runmodule.php?module=thiefden&op=shop");
		villagenav();
		break;
		case "truegem":
		page_header("Randall's Shop");
		$cost=get_module_setting("truegemcost");
		if ($session['user']['gold']<$cost) {
			output("`3You start to go to Randall, asking about the Truesight Gems, then notice his gaze go to your gems pouch. You look again at the label on the gem, and feel ashamed as you realise that you don't have enough gems.");
			output("Perhaps you should buy something you can afford?");
		} else {
			output("`3You go to Randall, and ask for one of the gems of Truesight. He nods and says, \"`\$This will help you earn gold... spot what others miss. It only lasts for one day, so use it wisely.`3\". You nod, and hand over the gems.");
			output("You secure the gem in your pouch, remembering to look through it when looting bodies.");
			set_module_pref("truegem",1);
			$session['user']['gems']-=$cost;
			debuglog("spent $cost gems on a Truesight Gem");
		}
		addnav("Look Around","runmodule.php?module=thiefden&op=shop");
		villagenav();
		break;
		case "invisibility":
		page_header("Randall's Shop");
		$cost=get_module_setting("inviscost");
		if ($session['user']['gems']<$cost) {
			output("`3You start to go to Randall, asking about the invisibility potion, then notice his gaze go to your gems pouch. You look again at the label on the potion, and feel ashamed as you realise that you don't have enough gems.");
			output("Perhaps you should buy something you can afford?");
		} else {
			output("`3You go to Randall, and ask for the potion of invisibility. He takes the gems from you, and warns you not to use it in here... here has his own ways of detecting thieves.");
			output("You stammer that you hadn't even considered such a thing, while eyeing the rack of potions, before taking the potion and slipping it into your pouch.");
			set_module_pref("invispotions",(get_module_pref("invispotions")+1));
			$session['user']['gems']-=$cost;
			debuglog("spent $cost gems on an invisibility potion.");
		}
		addnav("Look Around","runmodule.php?module=thiefden&op=shop");
		villagenav();
		break;
		case "poison":
		page_header("Randall's Shop");
		$cost=get_module_setting("poisoncost");
		if ($session['user']['gems']<$cost) {
			output("`3You start to go to Randall, asking about the vial of poison, then notice his gaze go to your gems pouch. You look again at the label on the vial, and feel ashamed as you realise that you don't have enough gems.");
			output("Perhaps you should buy something you can afford?");
		} else {
			output("`3You go to Randall, and ask for one of the vials of poison, muttering about the price as you hand over the gems.");
			output("He smiles for a moment, then pours a little of the vial onto the table. As the table starts to smoke he explains that his solution is more potent than what you will have been able to come up with on your own.");
			output("Nodding at his demonstration, you pocket the vial with great care.");
			set_module_pref("poisonvials",(get_module_pref("poisonvials")+1));
			$session['user']['gems']-=$cost;
			debuglog("spent $cost gems on a vial of poison.");
		}
		addnav("Look Around","runmodule.php?module=thiefden&op=shop");
		villagenav();
		break;
		case "pvpdrugcheck":
		page_header("Randall's Shop");
		$gemcost=get_module_setting("pvpdruggemcost");
		$goldcost=get_module_setting("pvpdruggoldcost");
		output("`3You go to Randall, asking him about the stragne powder and he looks slightly bored as he says, \"`\$Aye, something I've been experimenting with... not too much success as yet, I was trying to get something to keep me alert when I have to work.... long nights.");
		output("I \"acquired\" a strange root from a foreign merchant, he seemed to think it would perk me up, but it turns out it merely gives you a burst of energy, useful if you need to kill someone, but the effects the next day put me off.");
		output("You might want to use it, but be aware of the side effects. It'll cost you too:");
		if ($gemcost) output("%s gems,");
		if ($goldcost) output("%s gold,");
		output("and a terrible feeling the following morning. You'll have to take it now if ye buy, it doesn't travel well.`3\"");
		if ($session['user']['gems']<$gemcost) {
			output("`n`nAlthough you are interested, you just don't have the gems to buy this.");
		} elseif ($session['user']['gold']<$goldcost) {
			output("`n`nAlthough you are interested, you just don't have the gold to buy this.");
		} else {
			output("`n`nAlthough you are interested, you're a bit wary of the side effects. Do you want to buy this stuff?");
			addnav("Take the Powder","runmodule.php?module=thiefden&op=pvpdrug");
		}
		addnav("Look Around","runmodule.php?module=thiefden&op=shop");
		villagenav();
		break;
		case "pvpdrug":
		page_header("Randall's Shop");
		$gemcost=get_module_setting("pvpdruggemcost");
		$goldcost=get_module_setting("pvpdruggoldcost");
		if ($session['user']['gold']<$goldcost) {
			output("`3You ask for the powder, but the gold in your pouches has gone! You feel very embarrassed.");
		} else {
			output("`3You ask for the powder, and Randall hands it to you carefully after taking your money.");
			output("Although it smells a little disgusting, having a greenish tinge to it, you down it anyway, and feel energised!");
			output("Randall looks at you almost concerned as strength flows into you.... though you know that it will not feel so good tomorrow.");
			output("`n`n`&You gain a player vs player fight!");
			$session['user']['playerfights']++;
			$session['user']['gold']-=$goldcost;
			$session['user']['gems']-=$gemcost;
			debuglog("spent $goldcost gold and $gemcost gems on a PvP from Randall.");
			set_module_pref("pvpdrugs",(get_module_pref("pvpdrugs")+1));
		}
		addnav("Look Around","runmodule.php?module=thiefden&op=shop");
		villagenav();
		break;
		default:
		page_header("How did you get here?");
		output("`@In a shower of sparks and cheap-looking special effects Sneakabout appears before you in a somwehat tatty wizards robe.");
		output("\"`^What are you doing here? Don't you know that you're meant to be shopping at Randall's store? Stop lazing around!`@\"");
		output("With that he disappears back to wherever he came from with a wave of his cloak, obviously through a trapdoor pathetically concealed in the floor.");
		output("`n`nThere is nothing interesting here. Why not go back to the village?");
		addnav("Return to the Village","village.php");
		break;
	}
	$fight=httpget("fight");
	if ($fight!="") {
		page_header("Inn Guard");
		require_once("lib/fightnav.php");
		if ($fight=="guard") {
			$session['user']['turns']--;
			$badguy = array(
				"creaturename"=>"Inn Guard",
				"creaturelevel"=>$session['user']['level'],
				"creatureweapon"=>"Sharp Sword",
				"creatureattack"=>round($session['user']['attack']*0.8, 0),
				"creaturedefense"=>round($session['user']['defense']*0.8, 0),
				"creaturehealth"=>round($session['user']['maxhitpoints']*0.8, 0), 
				"diddamage"=>0,
				"type"=>"guard"
			);
			$session['user']['badguy']=createstring($badguy);
			$battle=true;
		}
		include("battle.php");
		if ($victory) {
			output("The guard falls to the floor, bleeding from the many wounds you gave him. You quickly grab his gold pouch and run back to the village.");
			$goldgain=round(e_rand(90,($session['user']['level']*100)),0);
			output("`n`nYou gain %s gold!",$goldgain);
			$session['user']['gold']+=$goldgain;
			debuglog("gained $goldgain gold from a guard in the inn.");
			villagenav();
		} elseif ($defeat) {
			output("The guard's sword pierces you as you breath your last... you are dead! The guard takes your gold and leaves! Your soul drifts to the shades.");
			$session['user']['gold']=0;
			debuglog("was killed by a guard in the Inn.");
			addnav("Return to the News","news.php");
		} else {
			fightnav(true,true,"runmodule.php?module=thiefden&fight=ongoing");
		}
	}
	page_footer();
}
?>