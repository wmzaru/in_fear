<?php
/*
Details:
 * This is a module that allows you to automatically bank gems or gold
 * It can link in with bankgems or bankmod
 * A maximum can be set
 * It can show the Eagle image
*/

function bankeagle_getmoduleinfo() {
	$info = array(
		"name"=>"Banking Eagle",
		"author"=>"`^CortalUX`& - `@Based on Instabank by `^Arune`@.",
		"version"=>"1.2",
		"category"=>"Forest",
		"vertxtloc"=>"http://dragonprime.net/users/CortalUX/",
		"download"=>"http://dragonprime.net/users/CortalUX/bankeagle.zip",
		"settings"=>array(
			"Bankeagle - General Settings,title",
			"goldbank"=>"Allow Gold banking?,bool|1",
			"gembank"=>"Allow Gem banking?,bool|1",
			"dklimit"=>"Dragonkill Limit?,int|0",
			"(set to 0 to ignore),note",
			"gems"=>"Maximum Gems?,int|0",
			"gold"=>"Maximum Gold?,int|0",
			"(this sets a maximum for transferring- set to 0 to ignore),note",
			"(if bankgems or bankmod isn't installed- gems will be ignored),note",
			"eagleshow"=>"Show the Eagle image?,bool|0",
		),
		"prefs"=>array(
			"Bankeagle - Preferences,title",
			"gems"=>"Gems?,int|0",
			"gold"=>"Gold?,int|0",
			"showmsg"=>"Show message?,hidden|0",
		),
	);
	return $info;
}

function bankeagle_install(){
	if (!is_module_active('bankeagle')){
		output("`n`Q`b`cInstalling Banking Eagle Module.`c`b`n");
	}else{
		output("`n`Q`b`cUpdating Banking Eagle Module.`c`b`n");
	}
	module_addhook("forest");
	module_addhook("forest-desc");
	module_addhook("newday");
	return true;
}

function bankeagle_uninstall(){
	output("`n`Q`b`cUninstalling Banking Eagle Module.`c`b`n");
	return true;
}

function bankeagle_dohook($hookname, $args){
	global $session;
	switch($hookname){
		case "forest":
			if (get_module_setting('dklimit')!=0&&get_module_setting('dklimit')>=$session['user']['dragonkills']||get_module_setting('dklimit')==0) {
				addnav("I?Instant Bank","runmodule.php?module=bankeagle");
			}
		break;
		case "forest-desc":
			if (get_module_pref('showmsg')==1) {
				set_module_pref('showmsg',0);
				bankeagle_stuff();
			} else {
				if (get_module_setting('dklimit')!=0&&get_module_setting('dklimit')<$session['user']['dragonkills']) {
					output("`n`@Try as you might, the banking Eagle ignores you... you can't be famous enough!");
				}
			}
		break;
		case "forest-bank":
			if (get_module_setting('dklimit')!=0&&get_module_setting('dklimit')>=$session['user']['dragonkills']||get_module_setting('dklimit')==0) {
				if (get_module_pref('gems')>0&&get_module_setting('gembank')==1) {
					output("`n`6\"`3I see you've deposited %s Gems with our Eagle..`6\", Elessa notes.",get_module_pref('gems'));
					} elseif (get_module_setting('gembank')==1) {
					output("`n`6\"`3I see you haven't yet deposited Gems with our Eagle..`6\", Elessa notes.");
				}
				if (get_module_pref('gold')>0&&get_module_setting('goldbank')==1) {
					output("`n`6\"`3I see you've deposited %s gold with our Eagle..`6\", Elessa notes.",get_module_pref('gold'));
					} elseif (get_module_setting('goldbank')==1) {
					output("`n`6\"`3I see you haven't yet deposited gold with our Eagle..`6\", Elessa notes.");
				}
			}
		break;
		case "newday":
			set_module_pref('gems',0);
			set_module_pref('gold',0);
			if (get_module_setting('dklimit')!=0&&get_module_setting('dklimit')<$session['user']['dragonkills']) {
				output("`n`@Try as you might, the banking Eagle ignores you... you can't be famous enough!");
			} else {
				output("`n`@An Eagle circles overhead... ready to help you bank....");
			}
		break;
	}
	return $args;
}
function bankeagle_run(){
	set_module_pref('showmsg',1);
	redirect("forest.php");
}
function bankeagle_stuff() {
	global $session;
	if (get_module_setting('eagleshow')==1) {
		output("`c<img src='images/eagle.gif' width='200' height='230' align='center' alt='Instant Banking'>`c",true);
	}
	$y = $session['user']['gold'];
	$i = true;
	if (get_module_setting('goldbank')==1) {
		if ($session['user']['goldinbank']>=0){
			output("You take an empty pouch from your back pocket and put all your gold in it. You whistle loudly and suddenly a giant Eagle swoops down from the clouds and takes your sack of gold from you. It flies towards the village and after a little while it returns to you carrying your empty sack and a note on how much money you deposited and how much you have in the bank now.`n");
			if (get_module_setting('gold')!=0) {
				$t = get_module_pref('gold')+$y;
				if (get_module_pref('gold')>get_module_setting('gold')) {
					$t = get_module_setting('gold')-get_module_pref('gold');
					if ($t>0) {
						$y = $t;
						output("`^The Eagle is so tired... you can only deposit `@%s`^ gold.",$y);
						debug("Banking Eagle won't deposit gold- user has limit.");
					} else {
						$y = 0;
						output("\"`3The Eagle is too tired to carry your gold...");
						debug("Banking Eagle won't deposit gold- user has limit.");
					}
				}
			}
			if (get_module_setting('gold')!=0&&get_module_pref('gold')>=get_module_setting('gold')&&$y!=0) {
				output("\"`3The Eagle is too tired to carry your gold...");
				$y = 0;
			}
			if ($y!=0) {
				output("`^`b`nYou deposit `&$y`^ gold into your bank account. ");
				debug("Banking Eagle deposited ".$y." gold.");
				$session['user']['goldinbank']+=$y;
				$session['user']['gold']-=$y;
				set_module_pref('gold',get_module_pref('gold')+$y);
			}
		}else{
			output("\"`3You have have a `&debt`3 of `^".abs($session['user']['goldinbank'])." gold`3 at the bank, you send for the Eagle until you payoff your debt. ");
			debug("Banking Eagle won't deposit gold- user has debt.");
			$i = false;
		}
	}
	if (get_module_setting('gembank')==1) {
		if (is_module_active('bankmod')||is_module_active('bankgems')&&get_module_pref('gemsaccount','bankgems')==1) {
			$x = $session['user']['gems'];
			if (is_module_installed('bankmod')) {
				$mod = "bankmod";
			} else {
				$mod = "bankgems";
			}
			if ($session['user']['goldinbank']>=0) {
				$gems = get_module_pref('gemsinbank',$mod);
				if (get_module_setting('gems')!=0) {
					$t = get_module_pref('gems')+$x;
					if (get_module_pref('gems')>get_module_setting('gems')) {
						$t = get_module_setting('gems')-get_module_pref('gems');
						if ($t>0) {
							$x = $t;
							output("`^The Eagle is so tired... you can only deposit `@%s`^ gems.",$x);
							debug("Banking Eagle won't deposit gems- user has limit.");
						} else {
							$x = 0;
							output("\"`3The Eagle is too tired to carry your gems...");
							debug("Banking Eagle won't deposit gems- user has limit.");
						}
					}
				}
				if (get_module_setting('gems')!=0&&get_module_pref('gems')>=get_module_setting('gems')&&$x!=0) {
					output("\"`3The Eagle is too tired to carry your gems...");
					debug("Banking Eagle won't deposit gems- user has limit.");
					$x = 0;
				}
				if ($x!=0) {
					output("`^`b`nYou deposit `&$x`^ gems into your bank account. ");
					debug("Banking Eagle deposited ".$y." gems.");
					$session['user']['gems']-=$x;
					set_module_pref('gems',get_module_pref('gems')+$x);
					set_module_pref('gemsinbank',get_module_pref('gems')+$x,$mod);
				}
			} else {
				if ($i) {
					output("`n\"`3You have have a `&debt`3 of `^".abs($session['user']['goldinbank'])." gold`3 at the bank, you cannot send for the Eagle until you payoff your debt. ");
				} else {
					output("`n`@Standing staring at the Eagle won't help..");
				}
				debug("Banking Eagle won't deposit gems- user has debt.");
			}
		}
	}
}
?>
