<?php
function randomdragon_getmoduleinfo(){
	$info = array(
		"name"=>"Random Dragon",
		"author"=>"`i`)Ae`7ol`&us`i`0, using code from dragon.php",
		"version"=>"1.1",
		"category"=>"Dragon",
		"download" => "http://dragonprime.net/index.php?topic=12263.0",
		"settings"=>array(
			"Random Dragons (General),title",
				"maxlevel" => "Maximum level to be reached on your server,int|15",
				"genname" => "General name for all badguys?,text|Realm's Enemy",
				"randdef" => "Generate badguys in a random or defined order?,enum,1,Random,2,Defined|1",
				"align" => "Base badguys on users alignment?,bool|0",
			"Random Dragons (Replacements),title",
				"`b`^Read the README for instuctions on this part!`b,note",
				"replacements" => "List of badguys to replace dragon with,textarea|",
			"Random Dragons (README),title",
				"`^If there are no badbuys listed then the module will return to the default Green Dragon.`n`n
				   The order that the badguys are placed into the settings does not matter.`n`n
				   Do not put the word 'the' before the badguys names - this is already dealt with!`n`n
				   Set the badguys with the following format: {badguy1}/{weapon1}&#44; {badguy2}/{weapon2}&#44; etc.`n`n
				   If badguys appear in a defined order then add /{min. dks required} after each weapon.`n
				   If they are in a defined order as well and multiple badguys are submitted for the same minimum DK then only the last one submitted will be used.`n`n
				   You `bMUST`b have a badguy set with a minimum of 0 DKs if badguys appear in defined order!!`n`n
				   If badguys are based on players alignment:`n
				   - Add ~{align} right at the end.`n
				   - Replace {align} with 0 for Evil alignment&#44; 1 for Neutral alignment&#44; and 2 for Good alignment
				   - Be sure to set badguys for Evil AND Neutral AND Good (even if it's just copy and paste)!,note"
		),
		"prefs" => array(
			"Random Dragons (Prefs),title",
				"bginfo" => "Which is the current badguy user is fighting (if any)?,text|",
		),
	);
	return $info;
}
function randomdragon_install(){
	module_addhook("forest");
	module_addhook("everyheader-loggedin");
	return true;
}
function randomdragon_uninstall(){
	return true;
}
function randomdragon_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "forest":
			$maxlevel = get_module_setting('maxlevel');
			$genname = get_module_setting('genname');
			blocknav("forest.php?op=dragon");
			if ($session['user']['level'] >= $maxlevel && $session['user']['seendragon']==0){
				addnav("Fight");
				addnav("G?`@Seek Out the $genname","runmodule.php?module=randomdragon&op=dragon");
			}
		break;
		case "everyheader-loggedin":
			if (strstr($_SERVER['REQUEST_URI'],"forest.php?op=dragon")){
				require_once('lib/redirect.php');
				redirect("runmodule.php?module=randomdragon&op=dragon");
			}
		break;
	}
	return $args;
}
function randomdragon_run(){
	global $session,$badguy;
	require_once("common.php");
	require_once("lib/fightnav.php");
	require_once("lib/titles.php");
	require_once("lib/http.php");
	require_once("lib/buffs.php");
	require_once("lib/taunt.php");
	require_once("lib/names.php");
	
	$genname = get_module_setting("genname");
	$randdef = get_module_setting("randdef");
	$align = get_module_setting("align");
	
	if ($align && is_module_active("alignment")){
		$gooda = get_module_setting('goodalign','alignment');
		$evila = get_module_setting('evilalign','alignment');
		$youra = get_module_pref('alignment','alignment');
		
		if ($youra <= $evila){
			$alignval = 0;
		}
		else if ($youra > $evila && $youra < $gooda){
			$alignval = 1;
		}
		else if ($youra >= $gooda){
			$alignval = 2;
		}
	}
	
	if (get_module_pref("bginfo") == ""){
		$replacements0 = explode(",",get_module_setting("replacements"));
		
		$replacements = array();
		$badguys = array();
		$count = count($replacements0);
		
		if (get_module_setting("replacements") == ""){ 
			$thisdragon = "`@Green Dragon";
			$thisweapon = "Great Flaming Maw";
		} else {
			for ($i = 0; $i < $count; $i++){
				$replacements[$i] = explode("~",$replacements0[$i]);
			}
			
			if ($align && is_module_active("alignment")){
				for ($i = 0; $i < $count; $i++){
					if ($replacements[$i][1] == $alignval) $badguys[] = $replacements[$i][0];
				}
			} else {
				$badguys = $replacements;
			}
			$count1 = count($badguys);
			
			if ($randdef == 1){
				$pickdragon = e_rand(0,$count1);
				$explode = $badguys[$pickdragon];
				
				if (is_array($explode)) $explode1 = explode("/",$explode[0]);
				else $explode1 = explode("/",$explode);
				
				$thisdragon = $explode1[0];
				$thisweapon = $explode1[1];
			}
			
			if ($randdef == 2){
				$explode1 = array();
				$dk_values = array();
				
				for ($i = 0; $i < $count1; $i++){
					if (is_array($badguys[$i]))
						$explode1[$i] = explode("/",$badguys[$i][0]);
					if (!is_array($badguys[$i]))
						$explode1[$i] = explode("/",$badguys[$i]);
					$dk_values[$i] = $explode1[$i][2];
				}
				
				$yourdks = $session['user']['dragonkills'];
				$newelement = false;
				
				if (!in_array($yourdks,$dk_values)){
					$dk_values[] = $yourdks;
					$newelement = true;
				}
				sort($dk_values);
				$i = array_search($yourdks, $dk_values);
				if ($newelement) $key = $i-1;
				else $key = $i;
				
				$thisdragon = $explode1[$key][0];
				$thisweapon = $explode1[$key][1];
			}
		}
                
                $lastdker = get_module_setting("hero", "statue");
                $sql = "SELECT name,weapon FROM ".db_prefix('accounts')." WHERE acctid = ".$lastdker."";
                $res = db_query($sql);
                $row = db_fetch_assoc($res);
                $thisdragon = $row['name'];
                $thisweapon = $row['weapon'];
		$bginfo0 = $thisdragon."/".$thisweapon;
		set_module_pref("bginfo",$bginfo0);
	}
	
	$bginfo = get_module_pref("bginfo");
	$yourinfo = explode("/",$bginfo);
	$yourdragon = $yourinfo[0];
	$yourweapon = $yourinfo[1];

	tlschema("dragon");
	$battle = false;
	page_header("$yourdragon");
	$op = httpget('op');
	if ($op== ""){
		if (!httpget('nointro')) {
			output("`\$Fighting down every urge to flee, you cautiously enter the cave entrance, intent on catching the great $yourdragon `\$sleeping, so that you might slay it with a minimum of pain.");
			output("Sadly, this is not to be the case, for as you round a corner within the cave you discover the great beast sitting on its haunches on a huge pile of gold, picking its teeth with a rib.");
		}
		$badguy = array(
			"creaturename"=>translate_inline("`@$yourdragon`0"),
			"creaturelevel"=>18,
			"creatureweapon"=>translate_inline("$yourweapon"),
			"creatureattack"=>45,
			"creaturedefense"=>25,
			"creaturehealth"=>300,
			"diddamage"=>0, 
			"type"=>"dragon"
		);

		//toughen up each consecutive dragon.
		// First, find out how each dragonpoint has been spent and count those
		// used on attack and defense.
		// Coded by JT, based on collaboration with MightyE
		$points = 0;
		restore_buff_fields();
		reset($session['user']['dragonpoints']);
		while(list($key,$val)=each($session['user']['dragonpoints'])){
			if ($val== "at" || $val == "de") $points++;
		}

		// Now, add points for hitpoint buffs that have been done by the dragon
		// or by potions!
		$points += (int)(($session['user']['maxhitpoints'] - 150)/5);

		$points = round($points*.75,0);

		$atkflux = e_rand(0, $points);
		$defflux = e_rand(0,$points-$atkflux);

		$hpflux = ($points - ($atkflux+$defflux)) * 5;
		debug("DEBUG: $points modification points total.`0`n");
		debug("DEBUG: +$atkflux allocated to attack.`n");
		debug("DEBUG: +$defflux allocated to defense.`n");
		debug("DEBUG: +". ($hpflux/5) . "*5 to hitpoints.`0`n");
		calculate_buff_fields();
		$badguy['creatureattack']+=$atkflux;
		$badguy['creaturedefense']+=$defflux;
		$badguy['creaturehealth']+=$hpflux;

		$badguy = modulehook("buffdragon", $badguy);

		$session['user']['badguy']=createstring($badguy);
		$battle=true;
	} else if($op == "dragon"){
		require_once("lib/partner.php");
		addnav("Enter the cave","runmodule.php?module=randomdragon");
		addnav("Run away like a baby","inn.php?op=fleedragon");
		output("`\$You approach the warped entrance of a room in the middle of nowhere, with nothing else to obscure it within miles.");
		output("The area around the room suddenly changes in a pulsing manner as you stand there observing, with a pulse every few minutes as if like a slowly beating heart.");
		output("The doorway of the room leads to pure white room where a figure you can barely make out stands in the center, back to the entrance.");
		output("The way the Empty City around this area seems to invite you makes you paranoid and nervous`n`n");
		output("You cautiously approach the entrance of the room, and as you do, you hear, or perhaps feel a deep rumble that lasts thirty seconds or so, before silencing to a breeze of wind that comes from the entrance.");
		output("The closer you apparoach, the more forceful the winds blows.`n`n");
		output("You force your way forwards, the thought of being ousted by wind when you are close to freedom isn't something you wish to endure.`n`n");
		output("Every instinct in your body wants to give up, and go back to town, back to the warm inn, and the even warmer %s`\$.", get_partner());
		output("What do you do?`0");
		$session['user']['seendragon']=1;
	} else if($op == "prologue1"){
		output("`@Victory!`n`n");
		$flawless = (int)(httpget('flawless'));
		if ($flawless) {
			output("`b`c`&~~ Flawless Fight ~~`0`c`b`n`n");
		}
		output("`2Before you, the $yourdragon `2lies immobile, its heavy breathing like acid to your lungs.");
		output("You are covered, head to toe, with the foul creature's thick black blood.");
		output("The great beast begins to move its mouth.  You spring back, angry at yourself for having been fooled by its ploy of death, and watch for its huge tail to come sweeping your way.");
		output("But it does not.");
		output("Instead the $yourdragon `2begins to speak.`n`n");
		output("\"`^Why have you come here mortal?  What have I done to you?`2\" it says with obvious effort.");
		output("\"`^Always my kind are sought out to be destroyed.  Why?  Because of stories from distant lands that tell of dragons preying on the weak?  I tell you that these stories come only from misunderstanding of us, and not because we devour your children.`2\"");
		output("The beast pauses, breathing heavily before continuing, \"`^I will tell you a secret.  Behind me now are my eggs.  They will hatch, and the young will battle each other.  Only one will survive, but she will be the strongest.  She will quickly grow, and be as powerful as me.`2\"");
		output("Breath comes shorter and shallower for the great beast.`n`n");
		output("\"`#Why do you tell me this?  Don't you know that I will destroy your eggs?`2\" you ask.`n`n");
		output("\"`^No, you will not, for I know of one more secret that you do not.`2\"`n`n");
		output("\"`#Pray tell oh mighty beast!`2\"`n`n");
		output("The great beast pauses, gathering the last of its energy.  \"`^Your kind cannot tolerate the blood of my kind.  Even if you survive, you will be a feeble creature, barely able to hold a weapon, your mind blank of all that you have learned.  No, you are no threat to my children, for you are already dead!`2\"`n`n");
		output("Realizing that already the edges of your vision are a little dim, you flee from the cave, bound to reach the healer's hut before it is too late.");
		output("Somewhere along the way you lose your weapon, and finally you trip on a stone in a shallow stream, sight now limited to only a small circle that seems to float around your head.");
		output("As you lay, staring up through the trees, you think that nearby you can hear the sounds of the village.");
		output("Your final thought is that although you defeated the $yourdragon, `2you reflect on the irony that it defeated you.`n`n");
		output("As your vision winks out, far away in the $yourdragon`2's lair, an egg shuffles to its side, and a small crack appears in its thick leathery skin.");

		if ($flawless) {
			output("`n`nYou fall forward, and remember at the last moment that you at least managed to grab some of the $yourdragon`2's treasure, so maybe it wasn't all a total loss.");
		}
		addnav("It is a new day","news.php");
		strip_all_buffs();
		$sql = "DESCRIBE " . db_prefix("accounts");
		$result = db_query($sql);

		reset($session['user']['dragonpoints']);
		$dkpoints = 0;
		while(list($key,$val)=each($session['user']['dragonpoints'])){
			if ($val== "hp") $dkpoints+=5;
		}

		restore_buff_fields();
		$hpgain = array(
				'total' => $session['user']['maxhitpoints'],
				'dkpoints' => $dkpoints,
				'extra' => $session['user']['maxhitpoints'] - $dkpoints -
						($session['user']['level']*10),
				'base' => $dkpoints + ($session['user']['level'] * 10),
				);
		$hpgain = modulehook("hprecalc", $hpgain);
		calculate_buff_fields();

		$nochange=array("acctid"=>1
					   ,"name"=>1
					   ,"sex"=>1
					   ,"password"=>1
					   ,"marriedto"=>1
					   ,"title"=>1
					   ,"login"=>1
					   ,"dragonkills"=>1
					   ,"locked"=>1
					   ,"loggedin"=>1
					   ,"superuser"=>1
					   ,"gems"=>1
					   ,"hashorse"=>1
					   ,"gentime"=>1
					   ,"gentimecount"=>1
					   ,"lastip"=>1
					   ,"uniqueid"=>1
					   ,"dragonpoints"=>1
					   ,"laston"=>1
					   ,"prefs"=>1
					   ,"lastmotd"=>1
					   ,"emailaddress"=>1
					   ,"emailvalidation"=>1
					   ,"gensize"=>1
					   ,"bestdragonage"=>1
					   ,"dragonage"=>1
					   ,"donation"=>1
					   ,"donationspent"=>1
					   ,"donationconfig"=>1
					   ,"bio"=>1
					   ,"charm"=>1
					   ,"banoverride"=>1
					   ,"referer"=>1
					   ,"refererawarded"=>1
					   ,"ctitle"=>1
					   ,"beta"=>1
					   ,"clanid"=>1
					   ,"clanrank"=>1
					   ,"clanjoindate"=>1
					   ,"regdate"=>1);

		$nochange = modulehook("dk-preserve", $nochange);

		$session['user']['dragonage'] = $session['user']['age'];
		if ($session['user']['dragonage'] <  $session['user']['bestdragonage'] ||
				$session['user']['bestdragonage'] == 0) {
			$session['user']['bestdragonage'] = $session['user']['dragonage'];
		}
		$number=db_num_rows($result);
		for ($i=0;$i<$number;$i++){
			$row = db_fetch_assoc($result);
			if (array_key_exists($row['Field'],$nochange) &&
					$nochange[$row['Field']]){
			}elseif($row['Field'] == "location"){
				$session['user'][$row['Field']] = getsetting("villagename", LOCATION_FIELDS);
			}else{
				$session['user'][$row['Field']] = $row["Default"];
			}
		}
		$session['user']['gold'] = getsetting("newplayerstartgold",50);

		$newtitle = get_dk_title($session['user']['dragonkills'], $session['user']['sex']);

		$restartgold = $session['user']['gold'] +
			getsetting("newplayerstartgold", 50)*$session['user']['dragonkills'];
		$restartgems = 0;
		if ($restartgold > getsetting("maxrestartgold", 300)) {
			$restartgold = getsetting("maxrestartgold", 300);
			$restartgems = max(0,($session['user']['dragonkills'] -
					(getsetting("maxrestartgold", 300)/
					 getsetting("newplayerstartgold", 50)) - 1));
			if ($restartgems > getsetting("maxrestartgems", 10)) {
				$restartgems = getsetting("maxrestartgems", 10);
			}
		}
		$session['user']['gold'] = $restartgold;
		$session['user']['gems'] += $restartgems;

		if ($flawless) {
			$session['user']['gold'] += 3*getsetting("newplayerstartgold",50);
			$session['user']['gems'] += 1;
		}

		$session['user']['maxhitpoints'] = 10 + $hpgain['dkpoints'] +
			$hpgain['extra'];
		$session['user']['hitpoints']=$session['user']['maxhitpoints'];

		// Sanity check
		if ($session['user']['maxhitpoints'] < 1) {
			// Yes, this is a freaking hack.
			die("ACK!! Somehow this user would end up perma-dead.. Not allowing MK to proceed!  Notify admin and figure out why this would happen so that it can be fixed before MK can continue.");
			exit();
		}

		// Set the new title.
		$newname = change_player_title($newtitle);
		$session['user']['title'] = $newtitle;
		$session['user']['name'] = $newname;

		reset($session['user']['dragonpoints']);
		while(list($key,$val)=each($session['user']['dragonpoints'])){
			if ($val== "at"){
				$session['user']['attack']++;
			}
			if ($val== "de"){
				$session['user']['defense']++;
			}
		}
		$session['user']['laston']=date("Y-m-d H:i:s",strtotime("-1 day"));
		$session['user']['slaydragon'] = 1;
		$companions = array();
		$session['user']['companions'] = array();

		output("`n`nYou wake up in the midst of some trees.  Nearby you hear the sounds of a village.");
		output("Dimly you remember that you are a new warrior, and something of a dangerous $yourdragon`2 that is plaguing the area.  You decide you would like to earn a name for yourself by perhaps some day confronting this vile creature.");

		// allow explanative text as well.
		modulehook("dragonkilltext");

		$regname = get_player_basename();
		output("`n`n`^You are now known as `&%s`^!!",$session['user']['name']);
		if ($session['user']['dragonkills'] == 1) {
			addnews("`#%s`# has earned the title `&%s`# for having slain a `3$genname`& `^%s`# time!",$regname,$session['user']['title'],$session['user']['dragonkills']);
			output("`n`n`&Because you have slain the $yourdragon `&%s time, you start with some extras.  You also keep additional permanent hitpoints you've earned.`n",$session['user']['dragonkills']);
		} else {
			addnews("`#%s`# has earned the title `&%s`# for having slain a `3$genname`& `^%s`# times!",$regname,$session['user']['title'],$session['user']['dragonkills']);
			output("`n`n`&Because you have slain the $yourdragon `&%s times, you start with some extras.  You also keep additional permanent hitpoints you've earned.`n",$session['user']['dragonkills']);
		}
		$session['user']['charm']+=5;
		output("`^You gain FIVE charm points for having defeated the $yourdragon!`n");
		debuglog("slew the $yourdragon and starts with {$session['user']['gold']} gold and {$session['user']['gems']} gems");

		// Moved this hear to make some things easier.
		modulehook("dragonkill", array());
		invalidatedatacache("list.php-warsonline");
		set_module_pref("bginfo","");
	}

	if ($op== "run"){
		output("The creature's tail blocks the only exit to its lair!");
		$op="fight";
		httpset('op', 'fight');
	}
	if ($op== "fight" || $op== "run"){
		$battle=true;
	}
	if ($battle){
		require_once("battle.php");

		if ($victory){
			$flawless = 0;
			if ($badguy['diddamage'] != 1) $flawless = 1;
			$session['user']['dragonkills']++;
			output("`&With a mighty final blow, the $yourdragon`& lets out a tremendous bellow and falls at your feet, dead at last.");
			addnews("`&%s`# has slain one of the hideous creatures known as the `3$genname`#.  All across the land, people rejoice!",$session['user']['name']);
			tlschema("nav");
			addnav("Continue","runmodule.php?module=randomdragon&op=prologue1&flawless=$flawless");
			tlschema();
		}else{
			if($defeat){
				tlschema("nav");
				addnav("Daily news","news.php");
				tlschema();
				$taunt = select_taunt_array();
				if ($session['user']['sex']){
					addnews("`%%s`5 has been slain when she encountered a $genname`5!!!  Her bones now litter the cave entrance, just like the bones of those who came before.`n%s",$session['user']['name'],$taunt);
				}else{
					addnews("`%%s`5 has been slain when he encountered a $genname`5!!!  His bones now litter the cave entrance, just like the bones of those who came before.`n%s",$session['user']['name'],$taunt);
				}
				$session['user']['alive']=false;
				debuglog("lost {$session['user']['gold']} gold when they were slain");
				$session['user']['gold']=0;
				$session['user']['hitpoints']=0;
				output("`b`&You have been slain by the $yourdragon`&!!!`n");
				output("`4All gold on hand has been lost!`n");
				output("You may begin fighting again tomorrow.");
				output("`b");

				page_footer();
			}else{
			  fightnav(true,false,"runmodule.php?module=randomdragon");
			}
		}
	}
	page_footer();
}
?>