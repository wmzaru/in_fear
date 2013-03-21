<?php
require_once("lib/http.php");
require_once("lib/villagenav.php");
require_once("lib/systemmail.php");

function clandkrewards_getmoduleinfo() {
	$info = array(
		"name"		=>	"Clan Automatic DK Rewards",
		"author"	=>	"<a href='http://cap.portalkeeper.info/'>`!Nicholas Moline</a>",
		"version"	=>	"1.1.2",
		"vertxtloc"	=>	"http://cap.portalkeeper.info/lotgd/",
		"category"	=>	"Clan",
		"download"	=>	"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1366",
		"prefs-clans"	=>	array(
			"Clan Vault - Automatic Rewards,title",
			"autoStipend"	=>	"Automatically Stipend users who have a DragonKill?,bool|0",
			"autoAmountGold"	=>	"Amount of Gold to automatically stipend?,int|0",
			"autoAmountGems"	=>	"Amount of Gems to automatically stipend?,int|0",
			"daydkbonusgold"	=>	"Amount of EXTRA Gold to automatically stipend per DK for multiple DKs in 24 hours.,int|0",
			"daydkbonusgems"	=>	"Amount of EXTRA Gems to automatically stipend per DK for multiple DKs in 24 hours.,int|0",
		),
		"prefs"		=>	array(
			"lastdkctime"		=>	"Last time dragon was killed in unixtime,int|0",
		),
		"requires"	=>	array(
			"clanvault"	=>	"Clan Vault Mod by `^Damien`3 and `@CortalUX`3, http://dragonprime.net/users/CortalUX/clanvault.zip",
		),
	);
	return $info;
}

function clandkrewards_install() {
	module_addhook("newday");
	module_addhook("footer-clan");
	return true;
}

function clandkrewards_uninstall() {
	return true;
}

function clandkrewards_dohook($hookname,$args) {
	global $session;
	$op = httpget('op');
	$clan = $session['user']['clanid'];
	switch($hookname) {
		case "newday":
			if($session['user']['age'] == 1 && $session['user']['dragonkills']>0) {
				$clangold = get_module_objpref("clans", $session['user']['clanid'], "vaultgold", "clanvault");
				$clangems = get_module_objpref("clans", $session['user']['clanid'], "vaultgems", "clanvault");
				$stipgoldamt = get_module_objpref("clans", $session['user']['clanid'],"autoAmountGold","clandkrewards");
				$stipgemsamt = get_module_objpref("clans", $session['user']['clanid'],"autoAmountGems","clandkrewards");
				$totalstipgold = $stipgoldamt;
				$totalstipgems = $stipgemsamt;
				$bonusgold = 0;
				$bonusgems = 0;
				if (time() - get_module_pref("lastdkctime") < 86400) {
					$bonusgold = get_module_objpref("clans",$session['user']['clanid'],"daydkbonusgold","clandkrewards");
					$bonusgems = get_module_objpref("clans",$session['user']['clanid'],"daydkbonusgems","clandkrewards");
					
					$totalstipgold = $stipgoldamt + $bonusgold;
					$totalstipgems = $stipgemsamt + $bonusgems;
					
					if ($totalstipgold > 0) {
						if ($clangold < 1) {
							output("`n`nYour Clan is set to automatically reward you " . $stipgoldamt . " Gold for this kill, but unfortunately the clan vault has no gold in it at this time, so you cannot be rewarded, please contact the owner of your clan.");
							$totalstipgold = 0;
						} elseif ($totalstipgold > $clangold) {
							output("`n`nYour clan is set to automatically reward you " . $stipgoldamt . " Gold for this kill, and " . $bonusgold . " extra for doing so more then once in a 24 hour period, unfortunatly the clan vault only has " . $clangold . " available at the moment, so you will only be stipended that amount.");
							$totalstipgold = $clangold;
						} else {
							output("`n`nYour clan has been set to automatically reward you " . $stipgoldamt . " Gold for this kill, and, since you have done this more then once in 24 hours, you will also be stipended " . $bonusgold . " additional gold for a total of " . $totalstipgold . " gold.  This gold is being put directly into your hand, congratulations!");
						}
					}
					if ($totalstipgems > 0) {
						if ($clangems < 1) {
							output("`n`nYour clan is set to automatically reward you " . $stipgemsamt . " Gems for this kill, but unfortunately the clan vault has no gems in it at this time, so you cannot be rewarded, please contact the owner of your clan.");
							$totalstipgems = 0;
						} elseif ($totalstipgems > $clangems) {
							output("`n`nYour clan is set to automatically reward you " . $stipgemsamt . " Gems for this kill, and " . $bonusgems . " extra for doing so more then once in a 24 hour period, unfortunately the clan vault only has " . $clangems . " gems available at the moment, so you will only be stipended that amount.");
							$totalstipgems = $clangems;
						} else {
							output("`n`nYour clan has been set to automatically reward you " . $stipgemsamt . " Gems for this kill, and, since you have done this more then once in 24 hours, you will also be stipended " . $bonusgems . " additional gems for a total of " . $totalstipgems . " gems.  These gems are being put directly into your hand, congratulations!");
						}
					}
				} else {
					if ($totalstipgold > 0) {
						if ($clangold < 1) {
							output("`n`nYour Clan is set to automatically reward you " . $stipgoldamt . " Gold for this kill, but unfortunately the clan vault has no gold in it at this time, so you cannot be rewarded, please contact the owner of your clan.");
							$totalstipgold = 0;
						} elseif ($totalstipgold > $clangold) {
							output("`n`nYour clan is set to automatically reward you " . $stipgoldamt . " Gold for this kill, unfortunatly the clan vault only has " . $clangold . " available at the moment, so you will only be stipended that amount.");
							$totalstipgold = $clangold;
						} else {
							output("`n`nYour clan has been set to automatically reward you " . $stipgoldamt . " Gold for this kill.  This gold is being put directly into your hand, congratulations!");
						}
					}
					if ($totalstipgems > 0) {
						if ($clangems < 1) {
							output("`n`nYour clan is set to automatically reward you " . $stipgemsamt . " Gems for this kill, but unfortunately the clan vault has no gems in it at this time, so you cannot be rewarded, please contact the owner of your clan.");
							$totalstipgems = 0;						
						} elseif ($totalstipgems > $clangems) {
							output("`n`nYour clan is set to automatically reward you " . $stipgemsamt . " Gems for this kill, unfortunately the clan vault only has " . $clangems . " gems available at the moment, so you will only be stipended that amount.");
							$totalstipgems = $clangems;						
						} else {
							output("`n`nYour clan has been set to automatically reward you " . $stipgemsamt . " Gems for this kill.  These Gems are being put directly into your hand, congratulations!");
						}
					}
				}
				if ($totalstipgold > 0) {
					set_module_objpref("clans", $session['user']['clanid'], "vaultgold",$clangold - $totalstipgold,"clanvault");
					$session['user']['gold'] = $session['user']['gold'] + $totalstipgold;
					
				}
				if ($totalstipgems > 0) {
					set_module_objpref("clans", $session['user']['clanid'], "vaultgems",$clangems - $totalstipgems,"clanvault");
					$session['user']['gems'] = $session['user']['gems'] + $totalstipgems;
				}
				if ($totalstipgold + $totalstipgems > 1) {
					$sql = "SELECT acctid FROM ".db_prefix("accounts")." WHERE acctid<>".$session['user']['acctid']." AND clanid=".$session['user']['clanid']." and clanrank>=".CLAN_OFFICER;
					$result = db_query($sql);
					$subject = "Automatic Reward to " . $session['user']['name'];
					$msg = $session['user']['name'] . " has been automatically Rewarded " . $totalstipgold . " gold and " . $totalstipgems . " gems for completing a leveling cycle.";
					if ($bonusgold + $bonusgems > 0) {
						$msg .= " Of this, " . $bonusgold . " gold and " . $bonusgems . " gems was a bonus for completing a cycle in under 24 hours.";
					}
					$msg .= " There is now " . get_module_objpref("clans",$session['user']['clanid'],"vaultgold","clanvault") . " gold and " . get_module_objpref("clans",$session['user']['clanid'],"vaultgems","clanvault") . " gems remaining in the clan vault.";
					while ($row = db_fetch_assoc($result)) {
						if (get_module_pref('showNot','clanvault',$row['acctid'])==1) {
							systemmail($row['acctid'],$subject,$msg);
						}
					}
				}
			}
			set_module_pref("lastdkctime",time());
			break;
		case "footer-clan":
			if ($op == 'enter'&&$clan!=0||$op==''&&$clan!=0) {
				if ($session['user']['clanrank'] >= CLAN_LEADER) {
					addnav("~");
					addnav("Automatic Reward Setup","runmodule.php?module=clandkrewards&op=admin");
				}
			}
			break;
	}
	return $args;
}

function clandkrewards_run() {
	global $session;
	$op = httpget('op');
	switch ($op) {
		case "admin":
			page_header("Clan Automatic Rewards");
			global $session;
			$clan = $session['user']['clanid'];
			$type = httpget('type');
			$link = "runmodule.php?module=clandkrewards&op=admin";
			addnav("Navigation");
			addnav("Return to your Clan","clan.php");
			villagenav();
			switch ($type) {
				case "save":
					$gold = httppost('autoAmountGold');
					$gems = httppost('autoAmountGems');
					$bonusgold = httppost('daydkbonusgold');
					$bonusgems = httppost('daydkbonusgems');
					if (is_numeric($gold)) 		{ set_module_objpref("clans",$clan,"autoAmountGold",$gold,"clandkrewards"); }
					if (is_numeric($bonusgold))	{ set_module_objpref("clans",$clan,"daydkbonusgold",$bonusgold,"clandkrewards"); }
					if (is_numeric($gems)) 		{ set_module_objpref("clans",$clan,"autoAmountGems",$gems,"clandkrewards"); }
					if (is_numeric($bonusgold))	{ set_module_objpref("clans",$clan,"daydkbonusgems",$bonusgems,"clandkrewards"); }
					output ("Settings Saved");
					break;
				case "toggle":
					if (get_module_objpref("clans",$clan,"autoStipend","clandkrewards") == 1) {
						set_module_objpref("clans",$clan,"autoStipend",0,"clandkrewards");
						output ("Automatic Stipend has been turned `boff`b for this clan.");
					} else {
						set_module_objpref("clans",$clan,"autoStipend",1,"clandkrewards");
						output ("Automatic Stipending has been turned `bon`b for this clan.");
					}
					break;
			}
			if (get_module_objpref("clans",$clan,"autoStipend","clandkrewards") == 1) {
				$togmsg = "Turn AutoRewards off";
			} else {
				$togmsg = "Turn AutoRewards on";
			}
			addnav("Actions");
			addnav($togmsg,$link . "&type=toggle");
			rawoutput("<form action='".$link."&type=save' method='post'>
			<fieldset>
				<legend>Setup your automatic Stipend amounts:</legend>
				<div><label for='autoAmountGold'>Gold to Automatically Reward:</label><input type='text' name='autoAmountGold' id='autoAmountGold' value='".get_module_objpref("clans",$clan,"autoAmountGold","clandkrewards")."' size='5' /></div>
				<div><label for='autoAmountGems'>Gems to Automatically Reward:</label><input type='text' name='autoAmountGems' id='autoAmountGems' value='".get_module_objpref("clans",$clan,"autoAmountGems","clandkrewards")."' size='5' /></div>
			</fieldset>
			
			<fieldset>
				<legend>Bonus Stipend Amounts:</legend>
				<div>The amounts below are given additionally if the user has multiple DKs in a single 24 hour period</div>
				<div><label for='daydkbonusgold'>Gold:</label><input type='text' name='daydkbonusgold' id='daydkbonusgold' value='".get_module_objpref("clans",$clan,"daydkbonusgold","clandkrewards")."' size='5' /></div>
				<div><label for='daydkbonusgems'>Gems:</label><input type='text' name='daydkbonusgems' id='daydkbonusgems' value='".get_module_objpref("clans",$clan,"daydkbonusgems","clandkrewards")."' size='5' /></div>
			</fieldset>
			<div align='center'><input type='submit' name='submit' value='Save Settings!' /></div>
			</form>");
			addnav("",$link."&type=save");
			break;
	}
	page_footer();
}
?>