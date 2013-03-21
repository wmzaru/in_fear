<?php

function slayerguild_getmoduleinfo(){
	$info = array(
		"name"=>"Dark Slayer's Guild",
		"author"=>"Chris Vorndran",
		"version"=>"1.51",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=31",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"User can collect the souls of creatures, in order to trade them in and be booned.",
		"settings"=>array(
			"Dark Slayer's Guild General Settings,title",
				"hploss"=>"How much HP is lost if innocent is killed,int|1",
				"maxhold"=>"Max souls that can be held by a person,int|30",
				"mult"=>"Multiply by souls to produce gold take from bank at Newday,int|100",
				"Set to zero to disable.,note",
			"Soul Pricings,title",
				"special"=>"Souls to increase specialty uses,int|3",
				"gems"=>"Souls to forge a gem,int|7",
				"atkdef"=>"Souls to increase Attack/Defense/Hitpoints,int|20",
				"hpgain"=>"How much HP is gained at trade-in,int|5",
			"Dark Slayer's Guild Location,title",
				"mindk"=>"What is the minimum DK before this shop will appear to a user?,int|0",
				"slayerloc"=>"Where does Slayer's Guild appear,location|".getsetting("villagename", LOCATION_FIELDS)
		),
		"prefs"=>array(
			"Dark Slayer's Guild Preferences,title",
				"convert"=>"Has the player been converted to the new pref system?,bool|0",
				"slayerguild_prefs"=>"All prefs contained within this module.,viewonly|",
				"explanation"=>",viewonly|manygems - How many gems did Leon take when joining.`n".
				"holding - Souls currently held by person.`n".
				"apply - Has person applied to the Slayer's Guild?`n".
				"atk - Has user built up attack?`n".
				"def - Has user built up defense?`n".
				"hp - Has user built up HP?",
				"user_showsoul"=>"Do you wish to see how many Souls you have,bool|1",
		),
	);
	return $info;
}
function slayerguild_install(){
	module_addhook("village");
	module_addhook("changesetting");
	module_addhook("battle-victory");
	module_addhook("bioinfo");
	module_addhook("newday");
	module_addhook("charstats");
	module_addhook("dragonkilltext");
	return true;
}
function slayerguild_uninstall(){
	return true;
}
function slayerguild_dohook($hookname,$args){
	global $session,$badguy;
	$mult = get_module_setting("mult");
	$maxhold = get_module_setting("maxhold");
	$cost = get_module_setting("cost");
	$guild = unserialize(get_module_pref("slayerguild_prefs"));
	switch ($hookname){
	case "charstats":
		if (get_module_pref("user_showsoul")){
				$title = translate_inline("Personal Info");
				$name = translate_inline("Souls");
			if (!$guild['apply']){
				$amnt = translate_inline("Not a Member");
			}else{
				$amnt = get_module_pref("holding");
			}
			setcharstat($title,$name,$guild['holding']);
		}
		break;
	case "village":
		if (is_module_active("holyguild")){
			$grab = unserialize(get_module_pref("holyguild_prefs","holyguild"));
			if ($grab['apply']) break;
		}
        if ($session['user']['location'] == get_module_setting("slayerloc")
			&& $session['user']['dragonkills'] >= get_module_setting("mindk")){
			tlschema($args['schemas']['marketnav']);
			addnav($args['marketnav']);
			tlschema();
			addnav("Dark Slayer's Guild","runmodule.php?module=slayerguild&op=enter");
		}
        break;
	case "changesetting":
		if ($args['setting'] == "villagename"){
			if ($args['old'] == get_module_setting("slayerloc")){
				set_module_setting("slayerloc",$args['new']);
			}
		}
		break;
	case "battle-victory":
		if ($args['type'] == 'forest'){
			$hploss = get_module_setting("hploss");
			$apply = $guild['apply'];
			$holding = $guild['holding'];
			$maxhold = get_module_setting("maxhold");
			if ($apply && $holding < $maxhold){
				if ($badguy['graveyard']){
					output("`n`b`)You have rended their soul!`b`n`n");
					$guild['holding']++;
					set_module_pref("slayerguild_prefs",serialize($guild));
					if (is_module_active('alignment')) {
						require_once("./modules/alignment/func.php");
						align("-1");
					}
				}else{
					output("`n`b`&You have spilt the blood of an innocent!`b`n`n");
					$session['user']['hitpoints']-=$hploss;
					if ($session['user']['hitpoints']<=$hploss){
						debuglog("died from spilling the blood of an innocent");
						$session['user']['hitpoints']=0;
						$session['user']['alive']=false;
						redirect("runmodule.php?module=slayerguild&op=dead&op2=forest");
					}
				}
			}
		}
		break;
	case "dragonkilltext":
		$guild['atk'] = 0;
		$guild['def'] = 0;
		$guild['hitpoint'] = 0;
		break;
	case "newday":
		if (!get_module_pref("convert")){
			$guild['manygems'] = get_module_pref("manygems");
			$guild['holding'] = get_module_pref("holding");
			$guild['apply'] = get_module_pref("apply");
			$guild['atk'] = get_module_pref("atk");
			$guild['def'] = get_module_pref("def");
			$guild['hitpoint'] = get_module_pref("hitpoint");
			db_query("DELETE FROM ".db_prefix("module_userprefs")." 
					WHERE modulename='slayerguild' 
					AND setting != 'user_showsoul' 
					AND setting != 'slayerguild_prefs'
					AND setting != 'convert'
					AND userid='{$session['user']['acctid']}'");
			set_module_pref("convert",1);
		}
		if ($guild['apply']){
			if ($mult){
				if ($guild['holding'] > 0){
					$take = ($guild['holding']*$mult);
					if ($session['user']['goldinbank']>=$take){
						output("`n`)The freakishness of the souls has caused `^%s `)gold in damages to the town.`n",$take);
						$session['user']['goldinbank']-=$take;
					}
				}
			}
		}			
		break;
	case "bioinfo":
		$grab = unserialize(get_module_pref("slayerguild_prefs","slayerguild",$args['acctid']));
		if ($grab['apply']){
			output_notl("`n");
			output("`&%s `7is a member of the `)Dark Slayer's Guild.`n",$args['name']);
		}
		break;
	}
	set_module_pref("slayerguild_prefs",serialize($guild));
	return $args;
}
function slayerguild_run(){
	global $session;
	$maxhold = get_module_setting("maxhold");
	$special = get_module_setting("special");
	$gems = get_module_setting("gems");
	$atkdef = get_module_setting("atkdef");
	$guild = unserialize(get_module_pref("slayerguild_prefs"));
	$holding = $guild['holding'];
	$apply = $guild['apply'];
	$atk = $guild['atk'];
	$def = $guild['def'];
	$op = httpget('op');
	$op2 = httpget('op2');
	page_header("Dark Slayer's Guild");
	
	switch ($op){
		case "enter":
			output("`)You walk into a dark and dank chamber, in the middle of the square.");
			output(" `)Looking around, you see a tall figure, cloaked in a black raiment.");
			output(" `)The Figure floats right next to you and places a cold hand on your shoulder.");
			output(" `)It bends down and whispers, it's voice chilling to the bone.`n`n");
			if (!$apply) {
				output("`)\"`7So, you have come to join the `)Dark Slayer's`7...?`)\" a male's voice is emitted.");
				addnav("Choices");
				if ($session['user']['gems'] > 1){
					addnav("Yes","runmodule.php?module=slayerguild&op=yes");
				}else{
					output("`n`n`)As you have no gems on your person, the Figure sees this and says, \"`7Come back when you have some gems.`)\"");
				}
				addnav("Not Today","runmodule.php?module=slayerguild&op=no");
			}else{
				output("`)\"`7Welcome back to the `)Dark Slayer's Guild`7...`)\" Leon says.");
				addnav("Proceed");
				addnav("Inner Hollows","runmodule.php?module=slayerguild&op=inner");
				addnav("Give up Dark Slayerhood","runmodule.php?module=slayerguild&op=giveup");
				blocknav("runmodule.php?module=slayerguild&op=no");
				blocknav("runmodule.php?module=slayerguild&op=yes");
			}
			break;
		case "yes":
			$take = (int)($session['user']['gems']/2);
			output("`)The Dark Figure takes a look at you and draws back his hood.");
			output(" `)A handsome Elf is looking back at you, smirking with a devlish mouth.`n`n");
			output("`)\"`7Welcome to the Dark Slayer's Guild. I am your Lord and Master, Leon Valian,`)\" he sets out his hand.");
			output(" He whisks half of your gems, `^%s `)gems, into his stores.",$take);
			$session['user']['gems']-=$take;
			if (is_module_active('alignment')) {
				output(" You can feel that something is draining from you...");
				require_once("./modules/alignment/func.php");
				align("-50");
			}
			set_module_pref("manygems",$take);
			set_module_pref("apply",1);
			$guild['apply'] = 1;
			$guild['manygems'] = $take;
			$guild['holding'] = 0;
			break;
		case "no":
			output("`)The Dark Figure sweeps it's arm and points a retched finger through it's sleeve.");
			output(" `)A low growl comes forthwith, \"`7Leave now...`)\"");
			break;
		case "dead":
			if ($op2=="forest")	output("`&You have spilt the blood of an innocent...`n`n");
			if ($op2=="give") output("`&You have tried to leave, and the souls that you couldn't provide, sealed your death.`n`n");
			output("`)You have paid for your transgressions...");
			output(" `)The ultimate punishment...`\$death`).");
			addnav("Continue","shades.php");
			blocknav("village.php");
			blocknav("runmodule.php?module=slayerguild&op=inner");
			break;
		case "giveup":
			if (httpget('confirm')){
				output("`)You nod and talk about how you don't wish to be a soul hunter...");
				output(" `)You  also talk about how you do not want to lose your good name, by becoming evil, in the eyes of society.");
				output("`n`n`)Leon nods, and rolls up your shirt sleeve.");
				output(" `)He tears a tattoo that had been put on their from you initiation, and tosses your `^%s `)gems to you.",get_module_pref("manygems"));
				if ($holding > 0){
					output("He also takes all of the souls that you have, and uses them to spare you the pain of death.");
					$guild['apply'] = 0;
					$guild['holding'] = 0;
				}else{
					output("He shakes his head, noticing you have no souls left over... the removal of the tattoo destroys your fragile body.");
					$guild['apply'] = 0;
					$guild['holding'] = 0;
					$session['user']['hitpoints']=0;
					$session['user']['alive']=false;
					addnav("Death...","runmodule.php?module=slayerguild&op=dead&op2=give");
					blocknav("village.php");
				}
				$session['user']['gems']+=$guild['manygems'];
				blocknav("runmodule.php?module=slayerguild&op=inner");
			}else{
				output("`)Leon crosses you, \"`7So, you wish to leave the Guild?`)\"`n`n");
				addnav("Are you sure?","runmodule.php?module=slayerguild&op=giveup&confirm=1");
				addnav("Leave","runmodule.php?module=slayerguild&op=inner");
			}
			break;
		case "inner":
			output("`)You follow Lord Leon deep into the hollows of the Slayer's Guild.");
			output(" `)He speaks, \"`7The purpose of the Dark Slayer's Guild, is to rend the souls of evil, and use them for our own good.");
			output(" `7Such creatures have broken from the Graveyard and walk amongst the forest.");
			output(" `7You shall know which is which... and the Gods are watching over the innocents...");
			output(" `7It is our duty to destroy them... but I shall give a little gift to those that rend souls.");
			output(" `7Now, go from this place, and come back with the souls of the damned.`)\"`n`n");
			output("`)You pick up your `&%s`), and look towards the door and the forest.",$session['user']['weapon']);
			output(" `)Leon casts a look at you and smiles, as he has brought another Dark Slayer into the world.");
			output("`n`n`)Leon notes that you currently have `&%s `)souls.",$holding);
			if (!$holding) output("`)You have no use being here... might as well leave.");
			addnav("Spend Souls");
			if ($holding>=$special) addnav(array("Increase Specialty Uses - %s Souls",$special),"runmodule.php?module=slayerguild&op=special");
			if ($holding>=$gems) addnav(array("Forge a Gem - %s Souls",$gems),"runmodule.php?module=slayerguild&op=gems");
			if ($holding>=$atkdef){
				if (!$atk) addnav(array("Increase Attack - %s Souls",$atkdef),"runmodule.php?module=slayerguild&op=atkdef&op2=atk");
				if (!$def) addnav(array("Increase Defense - %s Souls",$atkdef),"runmodule.php?module=slayerguild&op=atkdef&op2=def");
				if (!get_module_pref("hitpoint")) addnav(array("Increase Hitpoints - %s Souls",$atkdef),"runmodule.php?module=slayerguild&op=atkdef&op2=hp");
			}
			if ($atk && $def && get_module_pref("hitpoint")){
				output("`n`nLeon looks at you and arches a brow.");
				output(" You have already built up attack, hitpoints and defense for this time through... try some other time.");
			}
			break;
		case "special":
			$specialty = modulehook("specialtynames");
			$color = modulehook("specialtycolor");
			$spec = $specialty[$session['user']['specialty']];
			$ccode = $color[$session['user']['specialty']];
			output("`)Leon walks over and carresses his Ragnorok in it's holster and grins.`n`n");
			output(" `)He speaks, \"`7So, you have decided to increase your skills in %s%s`7.",$ccode,$spec);
			output(" `7I commend you for rending `&%s`7 souls so far, but I must take `&%s`7 to increase your Specialty.`)\"",$holding,$special);
			output("`n`n`)Leon takes your %s souls, and throws pure energy into your soul.",$special);
			output("You gain an extra use point!");
			$mods = modulehook("specialtymodules");
			$name = $mods[$session['user']['specialty']];
			increment_module_pref("skill", 3, $name);
			increment_module_pref("uses", 1, $name);
			$new = $holding-$special;
			$guild['holding'] = $new;
			break;
		case "gems":
			output("`)Leon walks over to you, smirking evilly.`n`n");
			output(" `)\"`7I am quite proud of you.. for rending `&%s `7souls thus far.",$holding);
			output(" `7But to forge this gem you requested, I will need to take `&%s `7souls.`)\"",$gems);
			output("`n`n`)Leon takes the %s souls from you, and forges a gem.",$gems);
			output("With a burst of light, Leon hands you a single gem.");
			$new = $holding-$gems;
			$guild['holding'] = $new;
			$session['user']['gems']++;
			break;
		case "atkdef":
			switch ($op2){
				case "atk":
					output("`)Leon looks you onceover, and smirks down at your `&%s`).",$session['user']['weapon']);
					output(" `)Taking in each dimension, and every nook and ding on it, he smiles.");
					output("`)\"`7I commend you thus far, for rending `&%s `7souls.",$holding);
					output(" And I see that your `&%s `7has taken much damage... perhaps you are now wielding it with enough strength.",$session['user']['weapon']);
					output(" I will need `&%s `7souls to increase your strength.`)\"",$atkdef);
					output("`n`n`)Leon takes the %s souls from you, and taps his blade to your arm.",$atkdef);
					output("With a burst of light, you feel a bit more stronger.");
					$new = $holding-$atkdef;
					$guild['holding'] = $new;
					$session['user']['attack']++;
					$guild['atk'] = 1;
					break;
				case "def":
					output("`)Leon looks you over, and smiles at your `&%s`).",$session['user']['armor']);
					output(" `)Looks over his glasses and stares deep into your body.");
					output("\"`7You truly lack the fortitude to back your `&%s`7 up.",$session['user']['armor']);
					output(" `7It has been desecrated... but I can increase your resilience with the use of `&%s `7souls.`)\"",$atkdef);
					output("`n`n`)Leon takes the %s souls from you, and taps his blade to your chest.",$atkdef);
					output("With a burst of light, you feel a bit more adamant.");
					$new = $holding-$atkdef;
					$guild['holding'] = $new;
					$session['user']['defense']++;
					$guild['def'] = 1;
					break;
				case "hp":
					output("`)Leon looks over at you, almost measuring your stamina.`n`n");
					output("`)\"`7You must be quite weak to seek the aid of souls to increase your stamina.");
					output(" `7But, you aren't so weak... I see that you have rended `&%s `7so far, quite admirable.",$holding);
					output(" `7But, to increase your stamina, I will need to take `&%s `7souls from your holding.`)\"",$atkdef);
					output("`n`n`)Leon takes the %s souls from you, and taps his blade to your heart.",$atkdef);
					output("With a burst of light, you feel a bit more intense.");
					output("You gained `\$%s `)Maximum Hitpoints!",get_module_setting("hpgain"));
					$new = $holding-$atkdef;
					$guild['holding'] = $new;
					$guild['hitpoint'] = 1;
					$session['user']['maxhitpoints']+=get_module_setting("hpgain");
					break;
			}
			break;
}			
if ($holding>=1 && $op != "inner" && $op != "enter"){
	addnav("Continue");
	addnav("Proceed to the Inners","runmodule.php?module=slayerguild&op=inner");
}
addnav("Leave");
villagenav();
set_module_pref("slayerguild_prefs",serialize($guild));
page_footer();
}
function slayerguild_endbattle(){
	global $session,$badguy;
}
?>