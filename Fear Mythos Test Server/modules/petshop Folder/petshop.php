<?php
/**************
Name: Pet Shop
Author: Eth - ethstavern(at)gmail(dot)com 
Version: 3.81
Release Date: 12-21-2005
About: A place to buy and sell pets. Editor included.
Translation compatible. Mostly. 
*****************/
require_once("common.php");
require_once("lib/showform.php");
require_once("lib/http.php");
require_once("lib/villagenav.php");
require_once("lib/sanitize.php");
require_once("lib/buffs.php");

function petshop_getmoduleinfo(){
	$info = array(
		"name"=>"Pet Shop",
		"version"=>"3.81",
		"author"=>"Eth",
		"category"=>"Pets",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=258",
		"vertxtloc"=>"http://dragonprime.net/users/Eth/",
		"settings"=>array(
			"Pet Shop Settings - Main,title",
			"petshopname"=>"Name of petshop|Ye Olde Pet Shoppe",			
			"petshoploc"=>"Where does the pet shop appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"Pet Shop Settings - Misc,title",
			"givegift"=>"Allow players to buy pets as gifts?,bool|1",
			"dklose"=>"Allow chance to lose pet after a DK?,bool|0",
			"battlelose"=>"Allow chance to kill pet after a lost battle?,bool|0",			
			"petdiechance"=>"1 in x chance for pet to die in battle,int|15",							
		),
		"prefs"=>array(
			"Pet Shop User Preferences,title",
			"haspet"=>"Does user have pet?,bool|0",
			"petid"=>"Pet's ID,int|0",
			"petname"=>"Pet's Type|Unknown",
			"petgender"=>"Pet Gender,enum,0,Male,1,Female",	
			"customname"=>"Pet's Name|Unnamed",			
			"petage"=>"Pet's Age - Unused,int|0",
			"neglect"=>"Neglected pet today?,bool|0",
			"Pet Shop Prefs - Battle Settings,title",
			"petattack"=>"Can this pet attack?,bool|0",
			"attacktype"=>"Manual or Auto Attack?,enum,0,Manual,1,Auto",
			"mindamage"=>"Min damage of attack,int|0",
			"maxdamage"=>"Max damage of attack,int|0",
			"petturns"=>"Number of turns,int|0",
			"Pet Shop Prefs - Gift Settings,title",
			"giftedpet"=>"Did player recieve a gifted pet?,bool|0",
			"giftid"=>"ID of pet gifted?,int|0"								
		),
	);
	return $info;
}
function petshop_install(){
	if (db_table_exists(db_prefix("pets"))) {
		$sql = "Select petattack FROM ".db_prefix("pets")." LIMIT 1";
		$result = mysql_query($sql);
	    if (!$result) db_query("ALTER TABLE ".db_prefix("pets")." ADD `petattack` tinyint(3) NOT NULL default '0'");
	    $sql = "Select attacktype FROM ".db_prefix("pets")." LIMIT 1";
		$result = mysql_query($sql);
	    if (!$result) db_query("ALTER TABLE ".db_prefix("pets")." ADD `attacktype` tinyint(3) NOT NULL default '0'");
	    $sql = "Select mindamage FROM ".db_prefix("pets")." LIMIT 1";
		$result = mysql_query($sql);
	    if (!$result) db_query("ALTER TABLE ".db_prefix("pets")." ADD `mindamage` int(11) NOT NULL default '0'");
	    $sql = "Select maxdamage FROM ".db_prefix("pets")." LIMIT 1";
		$result = mysql_query($sql);
	    if (!$result) db_query("ALTER TABLE ".db_prefix("pets")." ADD `maxdamage` int(11) NOT NULL default '0'");
	    $sql = "Select petturns FROM ".db_prefix("pets")." LIMIT 1";
		$result = mysql_query($sql);
	    if (!$result) db_query("ALTER TABLE ".db_prefix("pets")." ADD `petturns` int(11) NOT NULL default '0'");
	
	}else{
	output("`6Installing the pets database now.`n");	
	output("`6Presto. Done.`n`n");	
	$sql = array(
	"CREATE TABLE ".db_prefix("pets")." (petid INT(11) NOT NULL AUTO_INCREMENT ,petname VARCHAR(25) DEFAULT 'Unknown' NOT NULL ,petbreed TINYINT(3) DEFAULT '0' NOT NULL ,valuegold INT(11) DEFAULT '0' NOT NULL ,valuegems INT(11) DEFAULT '0' NOT NULL, upkeepgold INT(11) DEFAULT '0' NOT NULL ,upkeepgems INT(11) DEFAULT '0' NOT NULL ,petdk INT(11) DEFAULT '0' NOT NULL ,petcharm INT(11) DEFAULT '0' NOT NULL,petdesc VARCHAR(100) NOT NULL,newdaymsg VARCHAR(100) NOT NULL,villagemsg VARCHAR(100) NOT NULL,gardenmsg VARCHAR(100) NOT NULL,battlemsg VARCHAR(100) NOT NULL,petattack TINYINT(3) DEFAULT '0' NOT NULL, attacktype tinyint(3) default '0' NOT NULL, mindamage INT(11) DEFAULT '0' NOT NULL, maxdamage INT(11) DEFAULT '0' NOT NULL, petturns INT(11) DEFAULT '0' NOT NULL, PRIMARY KEY (petid)) TYPE = InnoDB;",			
	);	
		foreach ($sql as $statement) {
		db_query($statement);	
		}
	}	
	module_addhook("biostat");
	module_addhook("village");
	module_addhook("village-desc");
	module_addhook("charstats");
	module_addhook("superuser");
	module_addhook("dragonkill");
	module_addhook("training-victory");
	module_addhook("training-defeat");
	module_addhook("battle");
	module_addhook("battle-victory");
	module_addhook("battle-defeat");
	module_addhook("fightnav-specialties");
	module_addhook("apply-specialties");
	module_addhook("newday");
	module_addhook("changesetting");	
	module_addhook("forest");
	module_addhook("gardens");
	module_addhook("inn-desc");	
	return true;
}

function petshop_uninstall(){	
	//now lets get rid of that pesky table...		
	output("`6Dropping the pets database. Watch out below!`n`n");	
	$sql = "DROP TABLE IF EXISTS " . db_prefix("pets");
	db_query($sql);
	return true;
}
function petshop_dohook($hookname,$args){
	global $session;
	$from = "runmodule.php?module=petshop&op=editor&";
	$from2 = "runmodule.php?module=petshop&op=entershop";
	$haspet = get_module_pref("haspet");
	$customname = translate_inline(get_module_pref("customname"));
	$petname = translate_inline(get_module_pref("petname"));
	$gender = get_module_pref("petgender");
	$petgender = translate_inline($gender?"Female":"Male");
	$petgender2 = translate_inline($gender?"her":"his");
	$petgender3 = translate_inline($gender?"she":"he");
	$petgender4 = translate_inline($gender?"her":"him");
	//
	$petid = get_module_pref("petid");
	$sql = "SELECT newdaymsg,villagemsg,gardenmsg,battlemsg,upkeepgold FROM " . db_prefix("pets") . " WHERE petid='$petid'";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	if ($row['newdaymsg']>""){
		$msg1 = translate_inline($row['newdaymsg']);
	}else{
		$msg1 = translate_inline("Your pet awakens and is ready for the new day.");
	}
	if ($row['villagemsg']>""){
	$msg2 = translate_inline($row['villagemsg']);
	}else{
		$msg2 = translate_inline("Your pet keeps an eye out as you wander about the village.");
	}
	if ($row['gardenmsg']>""){
	$msg3 = translate_inline($row['gardenmsg']);
	}else{
		$msg3 = translate_inline("Your pet looks for a comfy place to take a nap.");
	}
	if ($row['battlemsg']>""){
		$msg4 = translate_inline($row['battlemsg']);
	}else{
		$msg4 = translate_inline("Frightened, your pet retreats to the forest.");
	}
	$upkeepgold = $row['upkeepgold'];	
	$neglect = get_module_pref("neglect");
	//
	switch ($hookname) {
	case "battle":
	if (($haspet == 1) && ($session['user']['alive'] == true)){
		if ($args['type'] == "dragon") {
			output("%s `2seems visibly nervous at the sight of the `@Green Dragon!`n", $customname);
		}else if ($args['type'] == "pvp") {
			output("%s `2waits to one side while you do battle with a fellow warrior.`n", $customname);	
		//for when you encounter the undead in the forest...					
		}else if ($args['type'] == "graveyard") {
			output("%s `2seems paralyzed at the sight of the undead fiend before you.`n", $customname);			
		}else{			
			output("`n`2%s`n",$msg4);
		}
		if (get_module_pref("petattack")==1 && get_module_pref("attacktype")==1 && get_module_pref("petturns")>0){
			if ($args['type']=="forest" || $args['type'] == "dragon"){						
				set_module_pref("petturns", get_module_pref("petturns") - 1);
				$min = get_module_pref("mindamage");
				$max = get_module_pref("maxdamage");								
				apply_buff('petattack', array(					
					"name"=>"`^Pet Attack",
					"rounds"=>-1,
					"wearoff"=>"$customname `Qis too tired to fight and retreats to the forest.",
					"minioncount"=>1,
					"effectmsg"=>"`@$customname `Qattacks {badguy} and causes `^{damage}`Q damage!",
					"effectnodmgmsg"=>"{badguy} `Qnarrowly dodges your pet's attack.",
					"effectfailmsg"=>"{badguy} `Qnarrowly dodges your pet's attack.",
					"minbadguydamage"=>$min,
					"maxbadguydamage"=>$max,
					"schema"=>"petshop"
				));												
			}
		}	
	}	
	break;	
	case "battle-defeat":
	if ($haspet == 1){
		$petlose = get_module_pref("petdiechance");
		$losechance = e_rand(1,$petlose);
		if (($battlelose == 1) && ($losechance == 1)){
			$deadpet = strtolower($petname);
			output("`^Horrible news! Your pet has been slain!`n");
			addnews("`6%s's `2pet %s `2was slain in battle today!", $session['user']['name'], $deadpet);
			$defaulttype = translate_inline("Unknown");
			$defaultname = translate_inline("Unnamed");
			set_module_pref("haspet",0);
			set_module_pref("petid", 0);
			set_module_pref("petname",$defaulttype);
			set_module_pref("customname",$defaultname);
			set_module_pref("neglect",0);	 
			set_module_pref("petattack",0);
			set_module_pref("attacktype",0);
			set_module_pref("mindamage",0);
			set_module_pref("maxdamage",0);
			set_module_pref("petturns",0);	
			if (get_module_pref("petattack")) strip_buff("petattack");
		}else{	
			output("`2The last thing you remember seeing is your pet running off into the forest.`n");
			output("`3Note: your pet will be reunited with you upon your resurrection.`n");
			if (get_module_pref("petattack")) strip_buff("petattack");
		}
	}
	break;
	case "battle-victory":
	if ($haspet == 1){
		if (get_module_pref("petattack")){
			output("`2%s `^seems a bit tired, but happy over your victory!`n", $customname);
		}
		output("`^%s returns after the battle and is quite happy with your victory!`n", $customname);
		if (get_module_pref("petattack")) strip_buff("petattack");
	}
	break;
	case "biostat":	
    $ownspet = get_module_pref("haspet","petshop",$args['acctid']);			
	$petname = get_module_pref("customname","petshop",$args['acctid']);
	$petbreed =  get_module_pref("petname","petshop",$args['acctid']);
    $pettype = translate_inline("`^Pet: `@$petname `@the $petbreed");
    $petnone = translate_inline("`^Pet: `@None");
	if ($ownspet == 1){
		output("%s`n",$pettype);		
	}else{
		output("%s`n", $petnone);
	}
	break;

	case "changesetting":
		if ($args['setting'] == "villagename") {
			if ($args['old'] == get_module_setting("petshoploc")) {
				set_module_setting("petshoploc", $args['new']);
			}
		}
	break;
	case "charstats":
	if ($haspet==1){
		//Relax! Mood is strictly aesthetic this time! Just something to give the pet a little more personality
		$moodgone = translate_inline("Lonely");
		$moodmad = translate_inline("Irritated");
		$moodnum = e_rand(1,7);
		$moodlist = array(1=>"Happy",2=>"Curious",3=>"Content",4=>"Hyper",5=>"Bored",6=>"Sleepy",7=>"Wants to Potty");
		$mood = translate_inline($moodlist[$moodnum]);
		$turns = get_module_pref("petturns");
		addcharstat("Pet Info");
		addcharstat("Pet Type", $petname);
		addcharstat("Pet Name", $customname);
		addcharstat("Pet Gender", $petgender);
		if ($session['user']['alive'] == false){
			addcharstat("Pet Mood", $moodgone);
		}else if ($neglect == 1){
			addcharstat("Pet Mood", $moodmad);
		}else{
			addcharstat("Pet Mood", $mood);
		}
		if(get_module_pref("petattack")) addcharstat("Pet Turns",$turns);
	}
	break;
	case "dragonkill":	
	$dklose = get_module_setting("dklose");		
	if ($haspet == 1){
		if ($dklose == 1){					
			if (e_rand(1,3)==1){	
				output("`n`2You stop and pause a moment as you see a %s `2running towards the village.", $petname);
				output(" `2You have a vague feeling of having once known this creature.");
				output(" `2Shrugging your shoulders, you continue on your way.`n`n");
				$defaulttype = translate_inline("Unknown");
				$defaultname = translate_inline("Unnamed");
				set_module_pref("haspet",0);
				set_module_pref("petid", 0);
				set_module_pref("petname",$defaulttype);
				set_module_pref("customname",$defaultname);	
				//
				set_module_pref("petattack",0);
				set_module_pref("attacktype",0);
				set_module_pref("mindamage",0);
				set_module_pref("maxdamage",0);
				set_module_pref("petturns",0);	
			}else{
				output("`n`2You stop and pause moment, finding a %s `2by your side.", $petname);
				output(" %s `2looks at you quizically, concern showing in %s eyes.", $customname, $petgender2);
				output(" Deciding you know this critter, you continue on with it by your side.`n`n");					
			}
		}else{
			//changed this up a bit. Had one user report a strange SQL error. This nipped it in the butt
			output("`n`2You stop and pause moment, finding a %s `2by your side.", $petname);
			output(" %s `2looks at you quizically, concern showing in %s eyes.", $customname, $petgender2);
			output(" Deciding you know this critter, you continue on with it by your side.`n`n");			
		}			
	}	
	break;	
	case "fightnav-specialties":
	if ($haspet == 1 && get_module_pref("petattack") == 1 && get_module_pref("attacktype") == 0){
		$uses = get_module_pref("petturns");
		$script = $args['script'];
		$fdskillname = translate_inline("`QCommand Pet to Attack");
		$fd1 = translate_inline("`QPet Attack");
		$sp1 = get_module_setting("fdexp1");	
		if ($uses > 0 && $haspet == 1) {
			addnav(array("%s`0", $fdskillname), "");
			addnav(array("&#149; %s`7 (%s)`0", $fd1, 1), $script."op=fight&fd=1", true);
		}
	}
	break;
	case "apply-specialties":
	if ($haspet == 1){
	$fd = httpget('fd');	
	$min = get_module_pref("mindamage");
	$max = get_module_pref("maxdamage");
	$petname = get_module_pref("customname");
		if (get_module_pref("petturns") >= $fd){
				switch($fd){				
				case 1:				
				apply_buff('petattack', array(
						"startmsg"=>"`QYou command `3$petname `Qto attack {badguy}`Q!",
						"name"=>"`^$petname's `^Attack",
						"rounds"=>1,
						"wearoff"=>"",
						"minioncount"=>1,
						"effectmsg"=>"`2$petname `Qattacks {badguy} `Qfor `^{damage}`Q points of damage.",
						"minbadguydamage"=>$min,
						"maxbadguydamage"=>$max,
						"schema"=>"petshop"
					));
				break;			
			}
			set_module_pref("petturns", get_module_pref("petturns") - $fd);		
		}
	}	
	break;  
	case "forest":
	if ($haspet == 1){
			switch(e_rand(1,3)){
			case 1:
			output("`n%s `2looks about the forest with some apprehension.`n", $customname);
			break;
			case 2:
			output("`n%s `2seems to hear something in the distance.`n", $customname);
			break;
			case 3:
			output("`n%s `2takes off to a nearby bush for a bathroom break.`n", $customname);
			break;
		}
	}
	break;
	case "gardens":
	if ($haspet == 1){
		switch(e_rand(1,5)){
			case 1:
			output("`3%s `2plays among the flowers.`n", $customname);
			break;
			case 2:
			output("`3%s `2tries to chase down a fairy but fails!`n", $customname);
			break;
			case 3:
			output("`3%s `2makes a meal out of a poor fairy!`n", $customname);
			break;
			case 4:
			output("`2Your pet `2flushes out a `&small white rabbit `2from the flower patches and gives chase.`n");
			break;
			case 5:
			output("`2%s`n", $msg3);
			break;
		}
	}
	break;
		case "inn-desc":
	if ($haspet == 1){
		switch(e_rand(1,3)){
			case 1:			
			output("`n`3%s `2seems somewhat unnerved by all the noise.`n", $customname);
			break;
			case 2:
			output("`n`3%s `2eats something off the dirty floor.`n", $customname);
			break;
			case 3:
			$lover = $session['user']['sex']?"Seth":"Violet";
			output("`n`3%s `2draws a few bemused glances from the patrons and `5%s`2.`n", $customname,$lover);
			if (e_rand(1,4)==1){
				output("`#As a result, you gain some charm!`n");
				$session['user']['charm']++;
			}			
			break;
		}
	}
	break;
	case "newday":
	$usergold = $session['user']['gold'];
	$userbank = $session['user']['goldinbank'];	
	if ($haspet == 1){
		$petid = get_module_pref("petid");
		output("`n`2%s`n`n", $msg1);
		$sql = "SELECT petturns,petattack FROM " . db_prefix("pets") . " WHERE petid='$petid'";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		if ($row['petattack']>0 && $row['petturns']>0){
			set_module_pref("petturns",$row['petturns']);
		}		
		if ($upkeepgold>0){			
			//first, if the player doesn't have the gold on hand, let's see if they have any in the bank
			if (($usergold<$upkeepgold) && ($userbank>=$upkeepgold)) {
				output("`2Since you haven't the gold on hand to maintain your pet, a fee of `^%s gold `2has been withdrawn from your bank account.", $upkeepgold);				
				$session['user']['goldinbank']-=$upkeepgold;
				if ($neglect == 1){ set_module_pref("neglect",0); }				
			//I'm going to be nice and give the player one free pass					
			}else if ($usergold<$upkeepgold && $userbank<$upkeepgold && $neglect == 0){
				output("`b`^You can't afford to feed your pet today! However, you do manage to find something meager for %s `^to eat. Be wary though, forget to feed your pet one more time, and you'll lose %s!`n`b", $customname,$petgender4);
				set_module_pref("neglect",1);
			//See, this is what happens when you neglect your pet. You meany, you!				
			}else if ($usergold<$upkeepgold && $userbank<$upkeepgold && $neglect == 1){
				$username = $session['user']['name'];
				output("`^You can't afford to feed your pet today! As a result, %s has run away!`n",$petgender3);
				addnews("`3%s's `2pet ran away today due to neglect!", $username);
				$defaulttype = translate_inline("Unknown");
				$defaultname = translate_inline("Unnamed");
				set_module_pref("haspet",0);
				set_module_pref("petid", 0);
				set_module_pref("petname",$defaulttype);
				set_module_pref("customname",$defaultname);
				set_module_pref("neglect",0);
				set_module_pref("petattack",0);
				set_module_pref("attacktype",0);
				set_module_pref("mindamage",0);
				set_module_pref("maxdamage",0);
				set_module_pref("petturns",0);					
			//now if the player can afford their pet, then all is well with the world and we end up here.		
			}else{
				output("`2It costs you `^%s gold `2to take care of your pet today.`n", $upkeepgold);				
				$session['user']['gold']-=$upkeepgold;				
				set_module_pref("neglect",0);
			}
		}							
	}
	break;			
	case "superuser":
	$edit = translate_inline("Editors");
	$link = translate_inline("Pet Editor");
	if ($session['user']['superuser'] & SU_EDIT_MOUNTS)addnav("$edit");	
	if ($session['user']['superuser'] & SU_EDIT_MOUNTS)addnav("$link", $from."op=view&category=0");	
	break;
	case "village":
	$shopname = translate_inline(get_module_setting("petshopname"));
	if ($session['user']['location'] == get_module_setting("petshoploc")) {	
		addnav($args['marketnav']);			
		addnav("$shopname",$from2);					
	}
	break;
	case "village-desc":
	if ($haspet == 1){
			switch(e_rand(1,3)){			
			case 1:
			output("`n`2%s`n", $msg2);
			break;
			case 2:
			output("`n`3%s `2looks about the village in a state of boredom.`n", $customname);
			break;
			case 3:
			output("`n`2Villagers give `3%s `2a smile as they pass on by.`n", $customname);
			break;
		}
	}
	break;
	}
	return $args;
}
function petshop_run(){	
	global $session;
	$shopname = get_module_setting("petshopname");
	$petshopname = translate_inline($shopname);
	page_header($petshopname);
	$op = httpget('op');
	$id = httpget('id');
	$uid = $session['user']['acctid'];
	$from = "runmodule.php?module=petshop&";
	$cangift = get_module_setting("givegift");	 
	//####PET SHOP####
	if ($op=="entershop"){			
		$petid = get_module_pref("petid");
		$haspet = get_module_pref("haspet");
		$from = "runmodule.php?module=petshop&";
		output("`2Stepping into the small shop, your ears are greeted by the sounds of barking dogs, singing birds, and other curious animal noises.");
		output(" `2A small woman standing behind a counter near the door gives you a smile.`n`n");
		if ($haspet == 0){
			output("`2\"Welcome to my shop, friend,\" she greets you. \"May I help you with anything?\"`n`n");
		}else{
			output("`2\"Welcome back, %s`2,\" she greets you. \"May I help you with anything?\"`n`n", $session['user']['name']);		
		}	
		if ((get_module_pref("giftedpet")==1) && ($haspet == 0)) {
			output("`^\"%s`^!\" the shopkeeper exclaims. \"Someone has bought you a lovely gift!\"`n`n", $session['user']['name']);
			addnav("`^Examine Gift!",$from."op=giftpet&what=preview");
		}		
		modulehook("petshop", array());			
		addnav("View Pets For Sale", $from."op=viewpets&category=0"); 
		if ($haspet == 1) { addnav("Change Pet Name", $from."op=changepetname&what=name"); }
		villagenav();
	//bring up a listing of what the store has for sale and list them by type (common or exotic)	
	}else if ($op=="viewpets"){
		$from = "runmodule.php?module=petshop&";										
		$userdk = $session['user']['dragonkills'];
		$bt = httpget("category");
		$haspet=get_module_pref("haspet");
		$petid = get_module_pref("petid");
		if ($bt == 0){ $list = translate_inline("Common"); 
		}else{ $list = translate_inline("Exotic"); }
		$sql = "SELECT * FROM " . db_prefix("pets") . " WHERE petbreed=$bt and $userdk>=petdk ORDER BY valuegold + 0,valuegems + 0 DESC,petname";	
		$result = db_query($sql);		
		$count = db_num_rows($result);								
		if ($count == 0){
			output("`2There don't appear to be any %s pets for sale yet.`n`n", $list);							
		}else{		
			output("<table cellspacing=0 cellpadding=2 width='100%' align='center'><tr><td>`b`c`2Below is a Listing of $list Pets`c`b</td></tr><tr><td>",true);    
			output("<table cellspacing=0 cellpadding=2 width='100%' align='center'><tr><td>Choice</td><td>`bBreed Name`b</td><td>Cost Gold</td><td>Cost Gems</td></tr>",true);    
			for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			$view = translate_inline("View");
			$sell = translate_inline("Sell Current");	
			$gift = translate_inline("Gift");  		    	  	
			output("<tr class='".($i%2?"trlight":"trdark")."'>",true); 
			if ($haspet>0){
				rawoutput("<td>[<a href='runmodule.php?module=petshop&op=shop&op=sellpet&id=$petid'>$sell</a>] | [<a href='runmodule.php?module=petshop&op=shop&op=giftpet&what=search&id={$row['petid']}'>$gift</a>]</td>");
				addnav("","runmodule.php?module=petshop&op=shop&op=sellpet&id=$petid");
				addnav("","runmodule.php?module=petshop&op=shop&op=giftpet&what=search&id={$row['petid']}");
	        }else{
				rawoutput("<td>[<a href='runmodule.php?module=petshop&op=petdetail&id={$row['petid']}'>$view</a>] | [<a href='runmodule.php?module=petshop&op=giftpet&what=search&id={$row['petid']}'>$gift</a>]</td>");      	 	   
				addnav("","runmodule.php?module=petshop&op=petdetail&id={$row['petid']}");	
				addnav("","runmodule.php?module=petshop&op=giftpet&what=search&id={$row['petid']}");
			}					
			output("<td>%s</td>",$row['petname'],true);	
			output("<td>`^%s Gold</td>",$row['valuegold'],true);
			output("<td>`@%s Gems</td></tr>",$row['valuegems'],true);   	
			//output("<td>`2%s</td></tr>",$atkstatus,true);
				if ($row['petdesc']>""){       	    
					output("<tr><td colspan='5'>`i`3%s`i</td></tr>", $row['petdesc'],true);
				}else{
					output("<tr><td colspan='5'>`i`3There is no information about this pet.`3`i</td></tr>",true);
				}
			}
			//output("
			output("</table>",true);      
			output("</table>",true);
		}			
		$pn1 = translate_inline("Common Pets");    
		$pn2 = translate_inline("Exotic Pets");    
		addnav("$pn1", $from."op=viewpets&category=0");
		addnav("$pn2", $from."op=viewpets&category=1");	
		addnav("Go Back", $from."op=entershop");
				
	}else if ($op=="petdetail"){
		$sql = "SELECT * FROM " . db_prefix("pets") . " WHERE petid='$id'";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		$petlc = strtolower($row['petname']);		
		output("`2Noting your interest in the `^%s`2, the shopkeeper gives you more information about it.`n`n", $petlc);
		//
		output("`0Breed: %s`n",$row['petname']);
		output("`0Cost in Gold: `^%s`n",$row['valuegold']);
		output("`0Cost in Gems: `5%s`n",$row['valuegems']);
		output("`0Cost Per Day in Gold: `^%s`n",$row['upkeepgold']);			
		if ($row['petattack'] == 1){
			if ($row['attacktype'] == 0){
				output("`3This pet can be `2ordered `3to attack in battle.`n`n");
			}else{
				output("`3This pet will attack `2on it's own accord `3in battle.`n`n");
			}			
		}
		output("`0Pet Description:`n");
		if ($row['petdesc']>""){
			output("`3%s`n`n",$row['petdesc']);	
		}else{
			output("`3There is no description for this pet`3.`n`n");
		}	
		//	
		output("`2\"Would you like to purchase this pet?\" the shopkeeper asks you.`n`n");
		addnav("Yes",$from."op=buypet&id=$id");
		addnav("No", $from."op=viewpets&category=0");			
	//Buy and sell your pets here
	//}else if ($op=="buypet"){
	}else if ($op=="buypet" || $op=="sellpet"){
		$sql = "SELECT * FROM " . db_prefix("pets") . " WHERE petid='$id'";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);		
		$pet = translate_inline($row['petname']);
		$petid = $row['petid'];
		$costgold = $row['valuegold'];
		$costgems = $row['valuegems'];		
		$ugold = $session['user']['gold'];
		$ugems = $session['user']['gems'];		
		$petcharm = $row['petcharm'];
		if ($op=="buypet"){	
			//sorry, not enough money...	
			if ($ugold<$costgold){
			output("`2The shopkeeper, giving you a disappointed look, sadly informs you that you haven't enough gold to purchase a `^%s`2.`n`n", $pet);			
			addnav("Go Back", $from."op=entershop");
			}else if ($ugems<$costgems){
				output("`2The shopkeeper, giving you a disappointed look, sadly informs you that you haven't enough gems to purchase a `^%s`2.`n`n", $pet);
				addnav("Go Back", $from."op=entershop");
			//My mistake, carry on...
			}else{
			$petinfo = array(
			"petgender"=>"Pet Gender,enum,0,Male,1,Female",	
			"petname"=>"Pet Name"
			);
			$petlc = strtolower($pet);
			output("`2The shopkeep smiles warmly and congratulates on your purchase of a `3%s`2.", $petlc);
			if ($petcharm>0){
			output(" `2This pet also carries with a charm bonus of %s!", $petcharm);
			}else{
				output("`n`n");
			}			
			output("`2Now, choose a gender and give your new pet a name!`n`n");									
			rawoutput("<form action='runmodule.php?module=petshop&op=after' method='POST'>");
			showform($petinfo,$row);
			addnav("", $from . "op=after");
			rawoutput("</form>");			
			//set prefs and what not
			set_module_pref("haspet",1);
			set_module_pref("petid",$petid);
			set_module_pref("petname",$pet);
			//reward the charm...
			$session['user']['charm']+=$petcharm;
			//battlestuff
			set_module_pref("petattack",$row['petattack']);
			set_module_pref("attacktype",$row['attacktype']);
			set_module_pref("mindamage",$row['mindamage']);
			set_module_pref("maxdamage",$row['maxdamage']);
			set_module_pref("petturns",$row['petturns']);
			//deduct cost of pet
			$session['user']['gold']-=$costgold;
			$session['user']['gems']-=$costgems;			
			addnav("Go Back", $from."op=entershop");						
			}
		//now if we're not buying, we must be selling
		}else{
			//sell price is currently half of buy price
			$sellpricegold = round($row['valuegold']/2);
			$sellpricegems = round($row['valuegems']/2);
			$petname = get_module_pref("customname");
			output("`2With a twinge of regret, you inquire about selling `3%s`2.", $petname);
			output(" `2The shopkeeper asks if you're sure, and quotes you a price of `^%s gold `2and `%%s gems`2.`n`n", $sellpricegold,$sellpricegems);
			addnav("Yes", $from."op=finalsale&choice=yes");
			addnav("No", $from."op=finalsale&choice=no");			
			}			
	 }else if ($op=="finalsale"){
		$petname = get_module_pref("customname"); 
		 	switch (httpget('choice')){
				case "yes":
				$petid = get_module_pref("petid");
				$sql = "SELECT valuegold,valuegems FROM " . db_prefix("pets") . " WHERE petid='$petid'";
				$result = db_query($sql);
				$row = db_fetch_assoc($result);		
				$sellpricegold = round($row['valuegold']/2);
				$sellpricegems = round($row['valuegems']/2);
				output("`2You've come to the decision you would like to sell your pet.");
				output(" `2You relunctantly say goodbye to `^%s `2as the shopkeeper hands you your money.`n`n", $petname);
				output("`2You wonder suddenly if you made the right decision.`n`n");
				$session['user']['gold']+=$sellpricegold;
				$session['user']['gems']+=$sellpricegems;			
				$defaulttype = translate_inline("Unknown");
				$defaultname = translate_inline("Unnamed");
				set_module_pref("haspet",0);
				set_module_pref("petid", 0);
				set_module_pref("petname",$defaulttype);
				set_module_pref("customname",$defaultname);	
				//
				set_module_pref("petattack",0);
				set_module_pref("attacktype",0);
				set_module_pref("mindamage",0);
				set_module_pref("maxdamage",0);
				set_module_pref("petturns",0);				
				//set_module_pref("giftedpet",0);	
				$session['user']['gold']+=$sellpricegold;
				$session['user']['gems']+=$sellpricegems;								
				break;
				case "no":
				output("`2Changing your mind, you decide you just can't bear to part with `^%s `2at the moment.`n`n", $petname);
				break;
			}					
	addnav("Go Back", $from."op=entershop");
	villagenav();	
	//And poof, we're done. Now, onwards!
	//#GIFTING
	//this is a bit of a mess, but it works - so why complain?
	}else if ($op=="changepetname"){	
		switch(httpget('what')){
			case "name":
			$who = get_module_pref("customname");
			$pg = translate_inline($gender?"she":"he");
			output("`2Looking down at %s`2, you decide %s could use a new name.`n", $who,$pg);
			$petinfo = array(
				"petname"=>"Pet Name"
			);
			$row=array(
				"petname"=>"",				
			);
			rawoutput("<form action='runmodule.php?module=petshop&op=changepetname&what=done' method='POST'>");
			showform($petinfo,$row);
			addnav("", $from . "op=changepetname&what=done");
			break;
			case "done":
			$pg = translate_inline($gender?"She":"He");			
			$newname = httppost('petname');							
			if ($newname == ""){ 
				output("`2After having a moment of indecision, you decide not to rename your pet.`n`n"); 				
			}else{			
				set_module_pref("customname",$newname);
				$newname = get_module_pref("customname");
				output("`2You decide to rename your pet \"`3%s\"`2.", $newname);
				output(" `2%s seems happy with your choice.`n`n", $pg);
			}
			break;
		}		
		addnav("Go Back", $from."op=entershop");
	}else if ($op=="giftpet"){
		$givegift = get_module_setting("givegift");
		if ($givegift == 0){
			output("`2The shopkeeper frowns and reluctantly tells you that do to city restrictions, pets can't be given as gifts at this time.`n`n");
			output("`2\"Perhaps another day,\" she suggests.`n");
		}else{	
			switch(httpget('what')){
				case "search":
				$id = httpget('id');
				$sql = "SELECT valuegold,valuegems,petid,petname,petdk FROM " . db_prefix("pets") . " WHERE petid='$id'";
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
				$dk = $row['petdk'];		
				set_module_pref("giftid", $id);
				//run some checks first to see if the player can afford the pet			
				if($session['user']['gold']<$row['valuegold'] && $session['user']['gems']<$row['valuegems']){
					output("`2Sorry, you have neither enough gold or gems to purchase a `6%s `2as a gift.`n`n", $row['petname']);
				}else if ($session['user']['gold']<$row['valuegold']){
					output("`2Sorry, you don't have enough gold to purchase a `^%s `2as a gift.`n`n", $row['petname']);
				}else if ($session['user']['gems']<$row['valuegems']){
					output("`2Sorry, you don't have enough gems to purchase a `^%s `2as a gift.`n`n", $row['petname']);
				}else{
				//if the player can afford to, let's allow them to search	
				output("`2You have chosen to give a `^%s `2as a gift to another player.`n`n",$row['petname']);			
				output(" `2Who would you like to give it to?`n`n");			
				rawoutput("<form action='runmodule.php?module=petshop&op=giftpet&what=search-done&dk=$dk' method='POST'>");
				rawoutput("<input type='text' name='whom' id='gift' size='25'><br>");
				rawoutput("<input type='submit' value='Search Players'>");
				rawoutput("</form>");
				addnav("","runmodule.php?module=petshop&op=giftpet&what=search-done&dk=$dk");	
				}
				break;
				case "search-done":
				//so, let's find the player...
				$dkreq = httpget('dk');
				//#bugfix: now properly selects colored names.
				$sql = "SELECT acctid,login,name,level,dragonkills,sex FROM ".db_prefix("accounts")." WHERE name OR login LIKE '%".$_POST['whom']."%' AND acctid <> ".$session['user']['acctid']." AND locked=0 ORDER BY level,login";
				$result = db_query($sql);
				$count = db_num_rows($result); 			
				rawoutput("<table cellpadding='3' width='500' cellspacing='0' border='0'><tr class='trhead'>");			
				//unable to find player, let's tell the giftee...
				if ($count == 0){
				  	 output("<td>`3Couldn't find a user by that name. Try again.</td>",true); 		
			  	 }
				for ($i=0;$i<db_num_rows($result);$i++){			 
			  	  $row = db_fetch_assoc($result);		  	 
			  	  $ownpet = get_module_pref("haspet","petshop",$row['acctid']);
			  	  $givenpet = get_module_pref("giftedpet","petshop",$row['acctid']);
			  	  $own = $ownpet?"Yes":"No";
			  	  if ($row['dragonkills']>=$dkreq){ $dkmet ="Yes"; }
			  	  else { $dkmet = "No"; }		  	
			  	  $playerid = $row['acctid'];
			  	  $playername = $row['name'];
			  	  //if selected player(s) meet the requirements, let's find 'em and list 'em 
		  	  	  if ($ownpet == 0 && $givenpet == 0 && $dkmet == "Yes"){
	   			  rawoutput("<td>Name</td><td>Level</td><td>Has Pet?</td><td>Meets DK Requirement?</tr>");	
			  	  rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
				  output("<td><a href=\"runmodule.php?module=petshop&op=giftpet&what=done&playerid=$playerid\">$playername</a></td>", true);				
				  output("<td>%s</td>",$row['level'],true);
				  output("<td>%s</td>",$own,true);
				  output("<td>%s</td>",$dkmet,true);
				  output("</tr><tr colspan='5'><td colspan='5'>`3Click on player's name to confirm the sale of pet.</td>",true);																		
					addnav("","runmodule.php?module=petshop&op=giftpet&what=done&playerid=$playerid");
					}else{
						//now, in case they can't be gifted, let's tell them possible reasons why...
						rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
						if (httppost('whom')==""){
							output("<td>`3No name selected!</td>",true);					
						}else if ( $dkmet == "No"){
							output("<td>`3Sorry, %s doesn't meet the dragon kill requirements for that pet.</td>",$playername,true);
						}else if ($ownpet == 1){
							output("<td>`3Sorry, `2%s `3already owns a pet.</td>",$playername,true);					
						}else if ($givenpet == 1){
							output("<td>`3Sorry, `2%s `3has already received one as a gift, and is awaiting pickup.</td>",$playername,true);					
						}										 
					}
				}			
				output("</tr></table>",true);	
				break;
				case "done":
				$playerid = httpget('playerid');
				$giftid = get_module_pref("giftid");			
				$sql = "SELECT * FROM " . db_prefix("pets") . " WHERE petid='$giftid'";
				$result = db_query($sql);
				$row = db_fetch_assoc($result);	
				$giftedpet = $row['petname'];	
				$giftgold = $row['valuegold'];
				$giftgems = $row['valuegems'];
				//take away cost of pet. 
				$session['user']['gold']-=$giftgold;			
				$session['user']['gems']-=$giftgems;		
				//now, let's set their pet data up
				$sql = "SELECT acctid,login,name,level,sex FROM ".db_prefix("accounts")." WHERE acctid='$playerid'";
				$result = db_query($sql);
				$row = db_fetch_assoc($result);	
				$pname = $row['name'];
				$usersex = $row['sex']?"She":"He";
				output("`2You have bought `6%s `2a `^%s `2as a gift!",$pname,$giftedpet);
				output(" %s will receive a mail with details on how to pick it up.`n`n",$usersex);
				//set_module_pref("haspet",1,"petshop",$row['acctid']);			
				set_module_pref("giftedpet",1,"petshop",$row['acctid']);
				set_module_pref("giftid",$giftid,"petshop",$row['acctid']);
				//set id of pet here as to avoid annoying duplicate key entries upon pickup	
				set_module_pref("petid",$giftid,"petshop",$row['acctid']);
				//send a mail with name of pet, shop, and location (for the clueless)
				require_once("lib/systemmail.php");
				$subject = translate_inline("Someone Bought you a Pet!");
				$petshop = get_module_setting("petshopname");
				$loc = get_module_setting("petshoploc");
				$mailmessage="".$session['user']['name']." `2has bought you a `^$giftedpet`2!`n`nYou may pick it up at `^$petshop `2in `^$loc`2.";			
				$message = translate_inline($mailmessage);
			 	systemmail($row['acctid'],$subject,$message);	
				break;
				case "preview":
				output("`2The shopkeeper is more than happy to tell you about the gift you've received.`n`n");
				$giftid = get_module_pref("giftid");			
				$sql = "SELECT * FROM " . db_prefix("pets") . " WHERE petid='$giftid'";
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
				output("Breed: %s`n",$row['petname']);
				output("`0Cost in Gold: `^%s`n",$row['valuegold']);
				output("`0Cost in Gems: `5%s`n",$row['valuegems']);
				output("`0Cost Per Day in Gold: `^%s`n",$row['upkeepgold']);								
				output("`0Pet Description:`n");
				if ($row['petdesc']>""){
					output("`3%s`n`n",$row['petdesc']);	
				}else{
					output("`3There is no description for this pet`3.`n`n");
				}	
				addnav("Accept Gift",$from."op=giftpet&what=pickup");
				addnav("Reject Gift",$from."op=giftpet&what=reject");
				break;
				case "reject":
				$giftid = get_module_pref("giftid");			
				$sql = "SELECT * FROM " . db_prefix("pets") . " WHERE petid='$giftid'";
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
				$pet = translate_inline($row['petname']);
				$what = strtolower($pet);
				output("`2The shopkeeper nods with a slight look of disappointment on her face.`n`n");
				output("`2\"A bit sad, really,\" she says. \"You could've given this %s `2a good home.\"`n`n",$what);
				set_module_pref("giftedpet",0);
				break;
				//now it's time for the player to pick up their gift.
				case "pickup":
				$giftid = get_module_pref("giftid");			
				$sql = "SELECT * FROM " . db_prefix("pets") . " WHERE petid='$giftid'";
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
				$cansee = 1;
				$haspet = get_module_pref("haspet");
				$pet = translate_inline($row['petname']);
				$what = strtolower($pet);
				$petcharm = $row['petcharm'];	
				$petinfo = array(
					"petgender"=>"Pet Gender,enum,0,Male,1,Female",	
					"petname"=>"Pet Name"
				);
				output("`2The shopkeeper brings out your `3%s `2and asks you to give the critter a name.`n`n", $what);
				output("`2\"What would you like to name him or her?\" the shopkeeper asks you.`n`n");
				rawoutput("<form action='runmodule.php?module=petshop&op=after' method='POST'>");
				showform($petinfo,$row);
				addnav("", $from . "op=after");
				rawoutput("</form>");
				//
				set_module_pref("haspet",1);			
				set_module_pref("petname",$pet);				
				set_module_pref("giftedpet",0);
				set_module_pref("neglect",0);
				//battlestuff
				set_module_pref("petattack",$row['petattack']);
				set_module_pref("attacktype",$row['attacktype']);
				set_module_pref("mindamage",$row['mindamage']);
				set_module_pref("maxdamage",$row['maxdamage']);
				set_module_pref("petturns",$row['petturns']);			
				$session['user']['charm']+=$petcharm;
				break;
			}
		}	
		addnav("View Pets For Sale", $from."op=viewpets&category=0");
		addnav("Go Back", $from."op=entershop");
		}else if ($op=="after"){
		$pet = translate_inline(get_module_pref("petname"));		
		$gender = httppost('petgender');
		$name = httppost('petname');							
		if ($name == ""){ $name = "Fido"; }		
		set_module_pref("customname", $name);
		set_module_pref("petgender", $gender);
		$pg = translate_inline($gender?"female":"male");
		$newname = translate_inline(get_module_pref("customname"));
		output("`2You decide to name your new  %s %s \"`^%s\"`2!`n`n", $pg, $pet, $newname);
		output("`2The shopkeer congratulates you on your purchases and wishes you and your pet well.`n`n");
		addnav("Go Back", $from."op=entershop");				
	}
	///////////////////////////////////////////////
	//Pet Shop Editor 	
	//Note to Self: I need to clean this up a bit
	//////////////////////////////////////////////
	if ($op="editor"){			
		$petarray=array(
		"Pet Stats and Costs,title",
		"petid"=>"Pet ID,hidden",
		"petname"=>"Pet Name,Name|Pip",
		"petbreed"=>"Pet Category,enum,0,Common,1,Exotic,2,Special",	
		"valuegold"=>"Pet Cost in Gold,int|0",
		"valuegems"=>"Pet Cost in Gems,int|0",
		"upkeepgold"=>"Daily Upkeep in Gold,int|0",
		"petdk"=>"Dragon Kills Needed to Own,int|0",
		"petcharm"=>"How much charm granted?,int|0",
		"petdesc"=>"Pet Description for Shop,Enter Message Here|",
		"Custom Messages,title",
		"These are optional - A default message will display in their absence.,note",		
		"newdaymsg"=>"New Day Message,Enter Message Here|",
		"villagemsg"=>"Village Message,Enter Message Here|",	
		"gardenmsg"=>"Garden Message,Enter Message Here|",	
		"battlemsg"=>"Battle Message,Enter Message Here|",
		"Battle Settings,title",		
		"petattack"=>"Can pet attack?,enum,0,No,1,Yes",
		"attacktype"=>"Type of Attack,enum,0,Manual,1,Automatically",
		"mindamage"=>"Min damage of attack,int|0",
		"maxdamage"=>"Max damage of attack,int|0",
		"petturns"=>"How many turns does pet get?,int|0",
		);	
		$id = httpget('id');
		//
		$op = httpget('op');
		$from = "runmodule.php?module=petshop&op=editor&";			
		if ($op=="view"){
		$bt = httpget("category");	
		$petview = translate_inline("View");
		$edit = translate_inline("Edit");
		$del = translate_inline("Delete");
		$delconfirm = translate_inline("Are you sure you wish to delete this pet?");	
		
		$sql = "SELECT * FROM " . db_prefix("pets") . " WHERE petid>=0 and petbreed=$bt ORDER BY petbreed";
		$result = db_query($sql);	
		if (db_num_rows($result)<1){
		output("");
		}else{
		$row = db_fetch_assoc($result);
		}
	    output("<table cellspacing=0 cellpadding=2 width='100%' align='center'><tr><td>`bOps`b</td><td>`bPet ID`b</td><td>`bPet Name`b</td><td>Gold Cost</td><td>Gems Cost</td><td>`bBreed Category`b</td></tr>",true);    
	    $result = db_query($sql);
	    for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		$breed = array(0=>Common,1=>Exotic,2=>Special);
		$breed = translate_inline($breed);   		    	  	
	    output("<tr class='".($i%2?"trlight":"trdark")."'>",true); 
	    rawoutput("<td>[<a href='runmodule.php?module=petshop&op=editor&op=viewpet&id={$row['petid']}'>$petview</a>|<a href='runmodule.php?module=petshop&op=editor&op=edit&id={$row['petid']}'>$edit</a>|<a href='runmodule.php?module=petshop&op=editor&op=delete&id={$row['petid']}' onClick='return confirm(\"$delconfirm\");'>$del</a>]</td>");   	   
	    addnav("","runmodule.php?module=petshop&op=editor&op=viewpet&id={$row['petid']}");
	    addnav("","runmodule.php?module=petshop&op=editor&op=edit&id={$row['petid']}");
		addnav("","runmodule.php?module=petshop&op=editor&op=delete&id={$row['petid']}");    
	    output("<td>`6%s</td>",$row['petid'],true);
		output("<td>%s</td>",$row['petname'],true);
		output("<td>`^%s Gold</td>",$row['valuegold'],true);
	    output("<td>`@%s Gems</td>",$row['valuegems'],true); 
	    output("<td>%s</td>",$breed[$row['petbreed']],true);   	           	
	    output("</tr>",true);
	    }    	
	    output("</table>",true);
	    modulehook("petshop", array());      
	    addnav("Functions");
	    addnav("Add a Pet", $from."op=add");
	    addnav("Display");
	    addnav("Display Common", $from."op=view&category=0");
	    addnav("Display Exotic", $from."op=view&category=1");
	    addnav("Display Special", $from."op=view&category=2"); 
	    //addnav("Refresh List", $from."op=view");
	  	if ($session['user']['superuser'] & SU_EDIT_CONFIG){
		   	addnav("Extra");
	    	addnav("Pet Shop Settings","configuration.php?op=modulesettings&module=petshop");			
		}			
		addnav("Other");	
		addnav("Return to the Grotto", "superuser.php");
		}else if ($op=="viewpet"){	
				$sql = "SELECT * FROM " . db_prefix("pets") . " WHERE petid='$id'";
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
				output("Previewing `2`b%s`b`2:`n`n", $row['petname']);
				//output("`0Name: %s`n",$row['petname']);
				output("`0Cost in Gold: %s`n",$row['valuegold']);
				output("`0Cost in Gems: %s`n",$row['valuegems']);
				output("`0Cost Per Day in `^Gold`0: %s.`n",$row['upkeepgold']);
				output("`0Cost Per Day in `2Gems`0: %s.`n",$row['upkeepgems']);
				output("`0DK's Needed to Own: %s.`n",$row['petdk']);
				output("`0Extra charm Granted: %s.`n`n",$row['petcharm']);
				output("`0Pet Description:`n");
				output("`3%s`n`n",$row['petdesc']);
				output("`0Newday Message:`n");
				output("`3%s`n`n",$row['newdaymsg']);
				output("`0Village Message:`n");
				output("`3%s`n`n",$row['villagemsg']);
				output("`0Garden Message:`n");
				output("`3%s`n`n",$row['gardenmsg']);
				output("`0Battle Message:`n");
				output("`3%s`n`n",$row['battlemsg']);
				//
				addnav("Return", $from."op=view&category=0");
				
		}else if($op=="edit" || $op=="add"){
			if ($op=="edit"){
				$sql = "SELECT * FROM " . db_prefix("pets") . " WHERE petid='$id'";
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
			}else{
				$row=array(
				"petid"=>"",
				"petname"=>"",
				"petbreed"=>"",
				"valuegold"=>"",		
				"valuegems"=>"",
				"upkeepgold"=>"",
				"petdk"=>"",
				"petcharm"=>"",
				"petdesc"=>"",
				"newdaymsg"=>"",
				"villagemsg"=>"",
				"gardenmsg"=>"",
				"battlemsg"=>"",
				"petattack"=>"",
				"attacktype"=>"",
				"mindamage"=>"",
				"maxdamage"=>"",
				"petturns"=>"",
				);
			}
		rawoutput("<form action='runmodule.php?module=petshop&op=save' method='POST'>");
		addnav("","runmodule.php?module=petshop&op=save");
		showform($petarray,$row);
		rawoutput("</form>");
		addnav("Go Back","runmodule.php?module=petshop&op=editor&op=view&category=0");
	
		}else if($op=="save"){
			$petid = httppost('petid');
			$petname = httppost('petname');
			$petbreed = httppost('petbreed');
			$valuegold = httppost('valuegold');
			$valuegems = httppost('valuegems');
			$upkeepgold = httppost('upkeepgold');			
			$petdk = httppost('petdk');
			$petcharm = httppost('petcharm');
			$petdesc = httppost('petdesc');
			$newdaymsg = httppost('newdaymsg');
			$villagemsg = httppost('villagemsg');
			$gardenmsg = httppost('gardenmsg');
			$battlemsg = httppost('battlemsg');
			$petattack = httppost('petattack');
			$attacktype = httppost('attacktype');
			$mindamage = httppost('mindamage');
			$maxdamage = httppost('maxdamage');
			$petturns = httppost('petturns');
			
			if ($petid>0){
				$sql = "UPDATE " . db_prefix("pets") . " SET petname=\"$petname\", petbreed=\"$petbreed\", valuegold=\"$valuegold\", valuegems=\"$valuegems\", upkeepgold=\"$upkeepgold\", petdk=\"$petdk\", petcharm=\"$petcharm\", petdesc=\"$petdesc\", newdaymsg=\"$newdaymsg\", villagemsg=\"$villagemsg\", gardenmsg=\"$gardenmsg\", battlemsg=\"$battlemsg\", petattack=\"$petattack\", attacktype=\"$attacktype\", mindamage=\"$mindamage\",maxdamage=\"$maxdamage\", petturns=\"$petturns\" WHERE petid='$petid'";
				output("`6%s `2has been successfully edited.`n`n", $petname);		
			}else{
				$sql = "INSERT INTO " . db_prefix("pets") . " (petname,petbreed,valuegold,valuegems,upkeepgold,petdk,petcharm,petdesc,newdaymsg,villagemsg,gardenmsg,battlemsg,petattack,attacktype,mindamage,maxdamage,petturns) VALUES (\"$petname\",\"$petbreed\",\"$valuegold\",\"$valuegems\",\"$upkeepgold\",\"$petdk\",\"$petcharm\",\"$petdesc\",\"$newdaymsg\",\"$villagemsg\",\"$gardenmsg\",\"$battlemsg\",\"$petattack\",\"$attacktype\",\"$mindamage\",\"$maxdamage\",\"$petturns\")";
				output("`2The pet \"`6$petname\" `2has been saved to the database.`n`n");
			}
			db_query($sql);
			$op = "";
			httpset("op", $op);	
			addnav("Go Back","runmodule.php?module=petshop&op=editor&op=view&category=0");
		}else if($op=="delete"){
			$sql = "DELETE FROM " . db_prefix("pets") . " WHERE petid='$id'";
			db_query($sql);
			output("Pet deleted!`n`n");
			redirect($from."op=view&category=0");
			addnav("Go Back","runmodule.php?module=petshop&op=editor&op=view&category=0");
			$op = "";
			httpset("op", $op);
		}
	}
	page_footer();
}
?>