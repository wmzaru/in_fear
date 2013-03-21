<?php
//version information in readme.txt
require_once("lib/systemmail.php");
require_once("lib/http.php");
require_once("lib/commentary.php");
require_once("lib/villagenav.php");
require_once("lib/addnews.php");

function marriage_getmoduleinfo(){
	$info = array(
		"name"=>"Marriage",
		"version"=>"3.2",
		"author"=>"`@CortalUX`&, with additions by `#Alva`&.",
		"override_forced_nav"=>true,
		"category"=>"General",
		"download"=>"http://dragonprime.net/users/CortalUX/marriage.zip",
		"vertxtloc"=>"http://dragonprime.net/users/CortalUX/",
		"settings"=>array(
			"Marriage - General,title",
			"dmoney"=>"Give all money on hand to divorcee?,bool|1",
			"sg"=>"Allow same-gender marriage and flirting?,bool|1",
			"oc"=>"Hook into 'oldchurch'?,bool|0",
			"(only if installed),note",
			"Marriage - Wedding Rings,title",
			"cost"=>"Cost of Wedding ring?,int|1500",
			"(set to 0 to turn buying rings off),note",
			"cansell"=>"Can a user sell the ring if it's rejected?,bool|1",
			"Marriage - Flirting,title",
			"loveDrinksAdd"=>"Status of Loveshack Drinks?,hidden|0",
			"flirttype"=>"Must a user flirt before Marriage?,bool|0",
			"flirtCharis"=>"Can users lose Charm?,bool|0",
			"flirtAutoDiv"=>"Will being unfaithful auto-divorce you?,bool|0",
			"flirtAutoDivT"=>"If yes- how many times does a user need to be unfaithful before an auto-divorce?,int|2",
			"flirtmuch"=>"How many flirt points must a user have before being able to propose?,range,1,100,1|30",
			"lall"=>"Show the Love Shack in all villages?,bool|1",
			"loveloc"=>"If no- Where does the Love Shack appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"bname"=>"Name of the Bartender?,text|Jatti",
			"gbtend"=>"Gender of the Bartender?,enum,0,Male,1,Female|0",
			"maxDayFlirt"=>"Maximum flirts per day?,range,1,100,1|10",
			"podrink"=>"Flirt points for buying someone a drink?,range,1,100,1|2",
			"prdrink"=>"Cost of buying someone a drink?,int|25",
			"poroses"=>"Flirt points for buying someone some roses?,range,1,100,1|10",
			"prroses"=>"Cost of buying someone some roses?,int|40",
			"poslap"=>"Flirt points lost for slapping someone?,range,1,100,1|5",
			"pokiss"=>"Flirt points for kissing someone?,range,1,100,1|12",
			"chancefail"=>"Chance for someone's flirt to fail?,range,0,100,1|10",
			"Marriage - Chapel,title",
			"name"=>"Name of Chapel/Church?,text|Bluerock",
			"all"=>"Show in all villages?,bool|1",
			"chapelloc"=>"If no- Where does the chapel appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"vname"=>"Name of vicar?,text|Tanat",
			"gvica"=>"Gender of vicar?,enum,0,Male,1,Female|1",
			"(these settings are used when the old church hook is turned off),note",
			"Counselling, title",
			"counsel"=>"Can a user get counselling if he/she is rejected?,bool|1",
			"What could the user gain. Set a value to zero if the item should not be available.,note",
			"counsGems"=>"How many gems?,int|1",
			"counsGold"=>"How much gold? (lvl*amt),int|200",
			"counsExp"=>"How much exp? (percent of current exp),int|10",
			"counsFor"=>"How many forestfights?,int|2",
			"counsLvl"=>"Can a level be gained?,bool|1"
		),
		"prefs"=>array(
			"Marriage - Preferences,title",
			"inShack"=>"Is this user in the Loveshack?,viewonly|0",
			"buyring"=>"`%Has the user bought a Ring?,bool|0",
			"counsel"=>"`%Can this user get Marriage Counselling?,bool|0",
			"user_bio"=>"`%Show spouse in Bios?,bool|1",
			"user_stats"=>"`%Show your spouse under your Character stats?,bool|1",
			"flirtsfaith"=>"`%Amount of times been unfaithful?,int|0",
			"Marriage - Other,title",
			"`b`\$(only edit beyond this point if you know what you are doing!)`b,note",
			"proposals"=>"`%Proposals..,text|",
			"`@(comma seperated for each user id),note",
			"flirtsrec"=>"`^Flirts to me..,text|",
			"`@(comma and pipe seperated for each user id with points),note",
			"flirtssen"=>"`^Flirts from me..,text|",
			"`@(comma seperated for each user id),note",
			"flirtsToday"=>"`^Flirts today?,int|0",
		),
		"prefs-drinks"=>array(
			"Marriage - Drink Preferences,title",
			"drinkLove"=>"Is this drink served in the Loveshack?,bool|1",
			"loveOnly"=>"Is this drink Loveshack only?,enum,1,No,0,Yes|1",
		),
	);
	return $info;
}

function marriage_install(){
	if (!is_module_active('marriage')){
		output("`n`c`b`QMarriage Module - Installed`0`b`c");
	}else{
		output("`n`c`b`QMarriage Module - Updated`0`b`c");
	}
	module_addhook("drinks-text");
	module_addhook("drinks-check");
	module_addhook("moderate");
	module_addhook("newday");
	module_addhook("changesetting");
	module_addhook("footer-runmodule");
	module_addhook("village");
	module_addhook("footer-gardens");
	module_addhook("delete_character");
	module_addhook("charstats");
	module_addhook("faq-toc");
	module_addhook("biostat");
	if ($SCRIPT_NAME == "modules.php"){
		$module=httpget("module");
		if ($module == "marriage"){
			require_once("modules/lib/marriage_func.php");
			marriage_lovedrinks();
		}
	}
	return true;
}

function marriage_uninstall(){
	if ($SCRIPT_NAME == "modules.php"){
		$module=httpget("module");
		if ($module == "marriage"){
			require_once("modules/lib/marriage_func.php");
			marriage_lovedrinksrem();
		}
	}
	output("`n`c`b`QMarriage Module - Uninstalled`0`b`c");
	return true;
}
	
function marriage_dohook($hookname, $args){
	global $session;
	switch($hookname){
		case "drinks-text":
			if (get_module_pref('inShack')) {
				$args['title']="The Loveshack";
				$args['barkeep']=get_module_setting("bname");
				$args['return']="Sit back at the Bar";
				$args['returnlink']="runmodule.php?module=marriage&op=loveshack&ty=bar";
				$args['demand']="Giggling on the floor, you yell for another drink";
				$args['toodrunk']=" and so ".get_module_setting("bname")." places one on the bar.. however, you are too drunk to pick it up, so ".get_module_setting("bname")." leaves it to rot..";
				$args['toomany']=get_module_setting("bname")." `3apologizes, \"`&You've cleaned the place out.`3\"";
				$array = array("title","barkeep","return","demand","toodrunk","toomany","drinksubs");
				$schemas=array();
				foreach ($array as $val) {
					$schemas[$val]="module-marriage";
				}
				$args['schemas']=$schemas;
				$args['drinksubs']=array(
					"/^he/"=>"^".(get_module_setting('gbtend')?translate_inline("she"):translate_inline("he")),
					"/ he/"=>" ".(get_module_setting('gbtend')?translate_inline("she"):translate_inline("he")),
					"/^his/"=>"^".(get_module_setting('gbtend')?translate_inline("her"):translate_inline("his")),
					"/ his/"=>" ".(get_module_setting('gbtend')?translate_inline("her"):translate_inline("his")),
					"/^him/"=>"^".(get_module_setting('gbtend')?translate_inline("her"):translate_inline("him")),
					"/ him/"=>" ".(get_module_setting('gbtend')?translate_inline("her"):translate_inline("him")),
					"/Cedrik/"=>get_module_setting("bname"),
					"/Violet/"=>translate_inline("a stranger"),
					"/Seth/"=>translate_inline("a stranger"),
				);
			}
		break;
		case "drinks-check":
			if (get_module_pref('inShack')) {
				$args['allowdrink'] = get_module_objpref('drinks',$args['drinkid'],'drinkLove');
			} else {
				$args['allowdrink'] = get_module_objpref('drinks',$args['drinkid'],'loveOnly');
			}
		break;
		case "moderate":
			if (get_module_setting('oc')==0) {
				$args['marriage'] = 'The Chapel';
			} else {
				$args['marriage'] = 'The Old Church';
			}
			if (get_module_setting('flirttype')==1) {
				$args['loveshack'] = 'The Loveshack';
			}
		break;
		case "newday":
			set_module_pref('flirtsToday',0);
			if ($session['user']['marriedto']!=0) {
				if ($session['user']['marriedto']==4294967295) {
					if ($session['user']['sex']==SEX_MALE) 
						$n = "Violet";
					else
						$n = "Seth";
				} else {
					$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=".$session['user']['marriedto']." AND locked=0";
					$res = db_query($sql);
					$row = db_fetch_assoc($res);
					$n = $row['name'];
					if (db_num_rows($res)<1) {
						$session['user']['marriedto']=0;
					}
				}
				if ($session['user']['marriedto']!=0) {
					output("`n`@You and %s`0`@ spend the night in the inn together, and you both emerge positively glowing!",$n);
					apply_buff('marriage-married',
						array(
							"name"=>"`^".$n."`@'s vitality!",
							"rounds"=>60,
							"wearoff"=>"`^".$n."`@ looks away...",
							"defmod"=>1.03,
							"survivenewday"=>1,
							"roundmsg"=>"`^".$n."`@ watches over you...",
						)
					);
				} else {
					output("`n`@You feel sorrow for the death of your spouse.");
					apply_buff('marriage-death',
						array(
							"name"=>"`6Sorrow",
							"rounds"=>80,
							"wearoff"=>"`^You start to recover..",
							"defmod"=>1.10,
							"survivenewday"=>1,
							"roundmsg"=>"`6Sorrow gives you pain. Your pain gives you anger. Your anger gives you strength.",
							)
					);
				}
			}
			$xmy = get_module_pref('flirtssen');
			$xmy = explode(',',$xmy);
			$astr = "";
			foreach ($xmy as $bval) {
				if ($bval!='') {
					$ac = $bval;
					$sen = get_module_pref('flirtsrec','marriage',$ac);
					$my = explode(',',$sen);
					$str="";
					$i=false;
					foreach ($my as $val) {
						if ($val!='') {
							$stf = explode('|',$val);
							$pts = $stf[1];
							if ($pts>0) {
								$str .= $val.",";
								$i=true;
							}
						}
					}
					set_module_pref('flirtsrec',$str,'marriage',$ac);
					if ($i===true) $astr .= $bval.",";
				}
			}
			set_module_pref('flirtssen',$astr);
			set_module_pref('inShack',0);
		break;
   		case "changesetting":
			if ($args['setting'] == "villagename") {
				if ($args['old'] == get_module_setting("chapelloc")) {
					set_module_setting("chapelloc", $args['new']);
				}
			}
			if ($args['setting'] == "oc" && $args['module']=='marriage') {
				if ($args['new']==1&&!is_module_active('oldchurch')) {
					$args['new']=0;
					set_module_setting('oc',0);
					output("`n`c`b`QMarriage Module - Old Church is not installed`0`b`c");
				}
			}
		break;
		case "footer-runmodule":
			if (!is_module_active('oldchurch')) set_module_setting('oc',0);
			$module = httpget('module');
			$op = httpget('op');
			if (get_module_setting('oc')&&$module=='oldchurch'&&$op=='enter') addnav("Marriage Wing","runmodule.php?module=marriage&op=oldchurch");
		break;
		case "village":
			tlschema($args['schemas']['tavernnav']);
			addnav($args['tavernnav']);
			tlschema();
			if (get_module_setting('counsel')==1&&get_module_pref('counsel')==1) {
				addnav("Social Counselling","runmodule.php?module=marriage&op=counselling");
			}
			if ($session['user']['location'] == get_module_setting("chapelloc")&&get_module_setting("all")==0&&get_module_setting('oc')==0) {
				addnav(array("%s %s Chapel",$session['user']['location'],get_module_setting('name')),"runmodule.php?module=marriage&op=chapel");
			} elseif (get_module_setting("all")==1&&get_module_setting('oc')==0) {
				addnav(array("%s Chapel",get_module_setting('name')),"runmodule.php?module=marriage&op=chapel");
			}
			if (get_module_setting('flirttype')==1) {
				if ($session['user']['location'] == get_module_setting("loveloc")&&get_module_setting("lall")==0) {
					addnav(array("%s Loveshack",$session['user']['location']),"runmodule.php?module=marriage&op=loveshack");
				} elseif (get_module_setting("lall")==1) {
					addnav(array("The Loveshack"),"runmodule.php?module=marriage&op=loveshack");
				}
			}
			set_module_pref('inShack',0);
			if (get_module_setting('flirtAutoDiv')==1&&get_module_setting('flirtAutoDivT')>0) {
				if (get_module_pref('flirtsfaith')>=get_module_setting('flirtAutoDivT')) {
					set_module_pref('flirtsfaith',0);
					if (get_module_setting('oc')==1) {
						$blah = 'oldchurch';
					} else {
						$blah = 'chapel';
					}
					$t = translate_inline("`%Uh oh!");
					if ($session['user']['marriedto']!=0&&$session['user']['marriedto']!=4294967295) {
						$mailmessage=array(translate_inline("%s`0`@ was forced by you to get a divorce, due to being unfaithful."),$session['user']['name']);
						systemmail($session['user']['marriedto'],$t,$mailmessage);
					}
					$mailmessage=translate_inline("You were forced to get a divorce, due to being unfaithful.");
					systemmail($session['user']['marriedto'],$t,$mailmessage);
					require_once('lib/redirect.php');
					redirect('runmodule.php?module=marriage&ty=divorce&op=$blah','Auto-Divorce');
				}
			}
		break;
		case "footer-gardens":
			addnav("~");
			addnav("Newly Weds","runmodule.php?module=marriage&op=newlyweds");
			set_module_pref('inShack',0);
		break;
		case "delete_character":
			$sql = "SELECT name,marriedto FROM ".db_prefix("accounts")." WHERE acctid='{$args['acctid']}' AND locked=0";
			$res = db_query($sql);
			if (db_num_rows($res)!=0) {
				$row = db_fetch_assoc($res);
				if ($row['marriedto']!=0&&$row['marriedto']!=4294967295) {
					$mailmessage=array(translate_inline("%s`0`@ has committed suicide by jumping off a cliff."),$row['name']);
					$t = translate_inline("`%Suicide!");
					systemmail($row['marriedto'],$t,$mailmessage);
					$sql = "UPDATE " . db_prefix("accounts") . " SET marriedto=0 WHERE acctid='{$row['marriedto']}'";
					db_query($sql);
				}
			}
			$xmy = get_module_pref('flirtssen');
			$xmy = explode(',',$xmy);
			$astr = "";
			foreach ($xmy as $bval) {
				if ($bval!='') {
					$ac = $bval;
					$sen = get_module_pref('flirtsrec','marriage',$ac);
					$my = explode(',',$sen);
					$str="";
					foreach ($my as $val) {
						if ($val!='') {
							$stf = explode('|',$val);
							$pts = $stf[0];
							if ($pts!=$session['user']['acctid']) {
								$str .= $val.",";
							}
						}
					}
					set_module_pref('flirtsrec',$str,'marriage',$ac);
				}
			}
		break;
		case "charstats":
			if ($session['user']['marriedto']!=0&&$session['user']['marriedto']!=4294967295&&get_module_pref('user_stats')) {
				$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid='".$session['user']['marriedto']."' AND locked=0";
				$res = db_query($sql);
				$row = db_fetch_assoc($res);
				setcharstat("Personal Info","Marriage","`^".translate_inline("Married to ").$row['name']);
			}
		break;
		case "faq-toc":
			$t = translate_inline("`@Frequently Asked Questions on Marriage`0");
			output_notl("&#149;<a href='runmodule.php?module=marriage&op=faq'>$t</a><br/>", true);
			addnav("","runmodule.php?module=marriage&op=faq");
		break;
		case "biostat":
			$char = httpget('char');
			$sql = "SELECT marriedto FROM ".db_prefix("accounts")." WHERE login='$char'";
			$results = db_query($sql);
			$row = db_fetch_assoc($results);
			if ($row['marriedto']!=0&&$row['marriedto']!=4294967295&&get_module_pref('user_bio')) {
				$sql2 = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid='".$row['marriedto']."' AND locked=0";
				$res2 = db_query($sql2);
				$row2 = db_fetch_assoc($res2);
				output("`^Spouse: `2%s`n",$row2['name']);
			}
		break;
	}
	return $args;
}

function marriage_run(){
	global $session;
	global $SCRIPT_NAME;
	if ($SCRIPT_NAME == "runmodule.php"){
		$module=httpget("module");
		if ($module == "marriage"){
			require_once("modules/lib/marriage_func.php");
		}
	}
	$op = httpget('op');
	switch($op) {
		case "faq":
			marriage_faq();
		break;
		case "counselling":
			marriage_counselling();
		break;
		case "newlyweds":
			marriage_wholist();
		break;
		case "loveshack":
			marriage_loveshack();
		break;
		case "innflirt":
			marriage_innflirt();
		break;
		default:
			marriage_general();
		break;
	}
}
?>
