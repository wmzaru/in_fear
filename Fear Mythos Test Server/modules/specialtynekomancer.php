<?php

function specialtynekomancer_getmoduleinfo() {
	$info = array(
		"name" => "Specialty - Nekomancer",
		"author" => "Iori, some aspects based on 'White Knight' by Admin Lexington",
		"version" => "1.2",
		"download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1125",
		"category" => "Specialties",
		"settings"=> array(
			"Specialty - Nekomancer Settings,title",
			"alignenum" => "What do you want to do with alignment?,enum,0,Ignore,1,Above High,2,Below Low,3,In between|3",
			"alignlo" => "What is the low alignment threshold?,int|-75",
			"alignhi" => "What is the high alignment threshold?,int|50",
			"isfelyne" => "Does player have to be Felyne race to choose this specialty?,bool|0",
			"mindk" => "How many DKs do you need before the specialty is available?,int|10",
			"cost" => "How many donation points do you need before the specialty is available?,int|10",
			"cost2" => "How many donation points are deducted whenever someone chooses this specialty?,int|0",
			"loss" => "How much is Alignment adjusted when specialty is chosen,int|-5",
			"nmlevelincrement" => "How many successful level-ups result in one Class Proficiency point?,int|10",
			"Specialty - Nekomancer - Felyne Settings,title",
			"incchance" => "Chance to gain an extra level increment point if player is Felyne?,range,0,100,1|20",
			"newdaycharm" => "Bonus charm every newday if player is Felyne?,int|1",
		),
		"prefs" => array(
			"Specialty - Nekomancer User Prefs,title",
			"skill"=>"Skill points in Nekomancer,int|0",
			"uses"=>"Uses of Nekomancer allowed,int|0",
			"nmlevelpoints"=>"Current level points - x points = 1 level,int|0",
			"nmlevel"=>"Current Nekomancer level,int|0",
		),
	);
	return $info;
}

function specialtynekomancer_install() {
	module_addhook("choose-specialty");
	module_addhook("set-specialty");
	module_addhook("fightnav-specialties");
	module_addhook("apply-specialties");
	module_addhook("newday");
	module_addhook("incrementspecialty");
	module_addhook("specialtynames");
	module_addhook("specialtymodules");
	module_addhook("specialtycolor");
	module_addhook("dragonkill");
	module_addhook("pointsdesc");
	module_addhook("newday");
	return true;
}

function specialtynekomancer_uninstall() {
	// Reset the specialty of anyone who had this specialty so they get to
	// rechoose at new day
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='NM'";
	db_query($sql);
	return true;
}

function specialtynekomancer_dohook($hookname,$args) {
	global $session,$resline,$companions;
	$spec = "NM";
	$name = "Nekomancer";
	$ccode = "`v";
	$cost = get_module_setting("cost");
	if ($session['user']['race'] == "Felyne") {
		$bonus = 1;
	} else {
		$bonus = 0;
	}
	switch ($hookname) {
		case "fightnav-specialties":
			$uses = get_module_pref("uses");
			$script = $args['script'];
			if ($uses > 0) {
				addnav(array("$ccode$name (%s points)`0",$uses),"");
				addnav(" ?$ccode&#149; `\$R`Qa`ti`^n `@of `#K`Li`1t`Jt`5e`%n`Vs`% (`&1`%)`0",$script."op=fight&skill=$spec&l=1", true);
			}
			if ($uses + $bonus > 2) {
				addnav(" ?$ccode&#149; `\$K`Qe`^e`@n `#S`Li`1g`%h`Vt`% (`&3`%)`0",$script."op=fight&skill=$spec&l=3",true);
			}
			if ($uses + $bonus > 5) {
				addnav(" ?$ccode&#149; `\$C`^0`@P`#y`LK`1a`%T`% (`&6`%)`0",$script."op=fight&skill=$spec&l=6",true);
			}
			if ($uses + $bonus > 9) {
				addnav(" ?$ccode&#149; `\$S`Qu`^m`gm`@o`#n `LT`1i`5g`%e`Vr`% (`&10`%)`0",$script."op=fight&skill=$spec&l=10",true);
			}
			if ($uses + $bonus > 14) {
			addnav(" ?$ccode&#149; `\$N`Qe`^k`go`@mi`#mi `1M`5o`%d`Ve`% (`&15`%)`0",$script."op=fight&skill=$spec&l=15",true);
			}
		break;

		case "specialtynames":
			$args[$spec] = translate_inline($name);
		break;

		case "specialtycolor":
			$args[$spec] = $ccode;
		break;

		case "specialtymodules":
			$args[$spec] = "specialtynekomancer";
		break;

		case "newday":
		$bonususes = getsetting("specialtybonus", 1);
		if ($session['user']['specialty'] == $spec && $bonus && get_module_setting("newdaycharm") > 0) {
			$session['user']['charm'] += get_module_setting("newdaycharm");
			output("`n`RFor being sooo cute, you gain some charm!`n`n");
		}
		if ($session['user']['specialty'] == $spec) {
			if ($bonususes == 1) {
				output("`n`3As you are a little %s%s`3, you gain `^1`3 extra use point of `&%s%s`3 Skills for today!`n",$ccode,$name,$ccode,$name);
			} else {
				output("`n`3As you are a little %s%s`3, you gain `^%s`3 extra uses of `&%s%s`3 Skills for today!`n",$ccode,$name,$bonus,$ccode,$name);
			}
		}
		if (is_array($companions)) {
			foreach ($companions as $name => $companion) {
				if (isset($companion['nonewday']) && $companion['nonewday'] == true) {
					unset($companions[$name]);
				}
			}
		}
		set_module_pref("num", 0);
		$amt = (int)(get_module_pref("skill"));
		if ($session['user']['specialty'] == $spec) $amt++;
		set_module_pref("uses", $amt);
		if (is_module_active('alignment') && $session['user']['specialty'] == 'NM') {
			$loss = get_module_setting("loss");
			if ((int)$loss != 0) {
				output("`n`5Your cat powers have %s`5 your alignment.`n",($loss>0?translate_inline('`&raised'):translate_inline('`4lowered')));
				increment_module_pref("alignment",$loss,"alignment");
			}
		}
		break;

		case "dragonkill":
			set_module_pref("uses", 0);
			set_module_pref("skill", 0);
		break;

		case "choose-specialty":
			if ($session['user']['dragonkills'] < get_module_setting("mindk")) {
				break;
			} elseif ($session['user']['donation'] - $session['user']['donationspent'] < get_module_setting("cost")) {
				break;
			} elseif (get_module_setting("isfelyne") && $bonus > 1) {
				break;
			} elseif (is_module_active("alignment")) {
				$allow = false;
				$align = get_module_pref("alignment","alignment");
				switch (get_module_setting("alignenum")) {
					case 0:
						$allow = true;
					break;
					case 1:
						if ($align <= get_module_setting("alignlo")) {
							$allow = true;
						}
					break;
					case 2:
						if ($align >= get_module_setting("alignhi")) {
							$allow = true;
						}
					break;
					case 3:
						if ($align <= get_module_setting("alignhi") && $align >= get_module_setting("alignlo")) {
							$allow = true;
						}
					break;
				}
			}
			if ($allow == true) {
				addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
				$t1 = translate_inline("Playing and talking with furry felines.");
				$t2 = appoencode(translate_inline("$ccode$name`0"));
				rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
				addnav("","newday.php?setspecialty=$spec$resline");
			}
		break;

		case "set-specialty":
			if ($session['user']['specialty'] == $spec) {
				$nmlevel = get_module_pref("nmlevel");
				page_header($name);
				if (is_module_active("speed")) {
					if ($bonus) {
						$var = 24 + $nmlevel;
					} else {
						$var = 12 + $nmlevel;
					}
					set_module_pref("spspd",$var,"speed");
					increment_module_pref("speed",$var,"speed");
				}
				if ((int)get_module_setting("cost2") != 0) {
					$session['user']['donationspent'] = min($session['user']['donationspent'] + get_module_setting("cost2"), $session['user']['donation']);
					debuglog("spent ".get_module_setting("cost2")." donation points when choosing the ".$spec." specialty");
				}
				output("`%Having grown up with a family of cats, you discovered that you have a special connection with them. ");
				output("`%You possess the miraculous ability to telepathically connect with felines. ");
				output("`%Therefore, hearing about the quest to rid the land of the `@Green Dragon, ");
				output("`%you gladly take up the challenge, thinking it will be something fun to do...");
				output("`n`n`i`lYour current Class Proficiency Rating for this specialty is `^$nmlevel`l.`0`i`n`n");
			}
		break;

		case "incrementspecialty":
			if ($session['user']['specialty'] == $spec) {
				$new = get_module_pref("skill") + 1;
				set_module_pref("skill", $new);
				$c = $args['color'];
				output("`n%sYou gain 1 level in `&%s%s to `#%s%s!", $c, $name, $c, $new, $c);
				output("`n`^You gain an extra use point!`n");
				increment_module_pref("uses",1);
				$inc = 1;
				if ($bonus && get_module_setting("incchance") > 0) {
					if (e_rand(1,100) <= get_module_setting("incchance")) {
						$inc = 2;
					} else {
						$inc = 1;
					}
				}
				increment_module_pref("nmlevelpoints",$inc);
				unset($inc);
				if (get_module_pref("nmlevelpoints") % get_module_setting("nmlevelincrement") == 0)	{
					increment_module_pref("nmlevel",1);
					output("`n`lYour `5Nekomancer `lClass Proficiency Rating has risen to `^%s`l!`0",get_module_pref("nmlevel"));
				}
				output_notl("`0");
			}
		break;

		case "apply-specialties":
			$nmlevel = get_module_pref("nmlevel");
			$nmlevelpoints = get_module_pref("nmlevelpoints");
			$skill = httpget('skill');
			$l = httpget('l');
			if ($skill==$spec && get_module_pref("uses") >= $l) {
				switch($l){
					case 1:
						if ((int)str_replace(".", "", getsetting("installer_version", 0)) >= 111 && getsetting("enablecompanions", true)) {
							apply_buff('Nm1',array(
								"startmsg"=>"`%Feeling playful, you summon all the `#K`Li`1t`Jt`5e`%n`Vs`% in the neighbourhood to tickle {badguy}!",
								"rounds" => 1,
								"minioncount"=> 1,
								"schema" => "specialtynekomancer",
							));
							$num = get_module_pref("num");
							$minioncount = round($session['user']['charm']/$session['user']['dragonkills']/$session['user']['level']) + 1;
							$minions = e_rand(1,$minioncount) + $bonus + $num;
							for ($i = $num; $i <= $minions; $i++) {
								$rand = e_rand(3,7);
								apply_companion('cutekitty'.$i, array(
									"name"=>"`RCute `#K`Li`1t`Jt`5y`0",
									"hitpoints" => $rand + $nmlevel*3 + $session['user']['level'],
									"maxhitpoints" => $rand + $nmlevel*3 + $session['user']['level'],
									"attack" => round(get_module_pref("skill")/5) + 1 + $nmlevel + floor($session['user']['level']/3),
									"defense" => round(get_module_pref("skill")/5) + 1 + $nmlevel + floor($session['user']['level']/3),
									"dyingtext" => "`5A `#K`Li`1t`Jt`5e`%n `5returns home, tired from the day's adventures.`n",
									"abilities" => array(
										"fight" => true,
									),
									"ignorelimit" => true,
									"nonewday" => true,
									"cannotbehealed" => true,
								), true);
							}
							set_module_pref("num",$i);
						} else {
							apply_buff('Nm1',array(
								"startmsg" => "`%Feeling playful, you summon all the `#K`Li`1t`Jt`5e`%n`Vs`% in the neighbourhood to tickle {badguy}`%!",
								"name" => "`i`\$R`Qa`ti`^n `@of `#K`Li`1t`Jt`5e`%n`Vs`i`0",
								"rounds" => 4 + floor($nmlevel/5),
								"wearoff" => "`5The `#K`Li`1t`Jt`5e`%n`Vs `%return home, tired from the day's adventures.",
								"minioncount" => round($session['user']['charm']/$session['user']['dragonkills']/$session['user']['level']) + 1,
								"effectmsg" => "`%A cute kitty tickles {badguy} with its furry paws, `%causing `^{damage}`% damage!",
								"effectnodmgmsg" => "`%{badguy} `%callously wipes away a kitty trying to gnaw on its eyes!",
								"maxbadguydamage" => round(get_module_pref("skill")/8) + 1 + $nmlevel,
								"areadamage" => ($bonus?true:false),
								"schema" => "specialtynekomancer",
							));
						}
					break;
					case 3:
						$session['user']['turns']++;
						apply_buff('Nm3',array(
							"startmsg" => "`%Cat eyes enhance your vision!",
							"name" => "`i`\$K`Qe`^e`@n `#S`Li`1g`%h`Vt`i`0",
							"rounds" => min(3+round($session['user']['gems']/100),15),
							"atkmod" => e_rand(10,12)/10,
							"defmod" => 1.5 + $nmlevel/20,
							"schema" => "specialtynekomancer",
						));
						if ($bonus) {
							apply_buff('Nm3b',array(
								"rounds" => -1,
								"dmgmod" => 1.1,
								"schema" => "specialtynekomancer",
							));
						}
					break;
					case 6:
						apply_buff('Nm6',array(
							"startmsg" => "`% With a swish of your tail, a `\$C`^0`@P`#y`LK`1a`%T appears beside you!",
							"name" => "`i`\$C`^0`@P`#y`LK`1a`%T`i`0",
							"rounds" => 5 + $bonus*3,
							"damageshield" => -1,
							"effectmsg" => "`7{goodguy}`4 hits `^{badguy}`4 for `^{damage}`4 points of damage!",
							"effectnodmgmsg" => "`7{goodguy}`4 hits `^{badguy}`4, but `^MISSES`\$!",
							"roundmsg" => "`%The `\$C`^0`@P`#y`LK`1a`%T mimics your actions.",
							"schema" => "specialtynekomancer",
						));
					break;

					case 10:
						if ((int)str_replace(".", "", getsetting("installer_version", 0)) >= 111 && getsetting("enablecompanions", true)) {
							apply_buff('Nm10',array(
								"startmsg" => "`%You call upon the services of one of the proudest of felines - a `LT`1i`5g`%e`Vr`%!",
								"rounds" => 1,
								"minioncount"=> 1,
								"schema" => "specialtynekomancer",
							));
							apply_companion('nekomancertiger', array(
								"name"=>"`i`\$S`Qu`^m`gm`@o`#n`@e`vd `LT`1i`5g`%e`Vr`i`0",
								"hitpoints" => $session['user']['attack']*3 + $nmlevel*5 + e_rand($bonus*40,40),
								"maxhitpoints" => $session['user']['attack']*3 + $nmlevel*5 + 40,
								"attack" => round((get_module_pref("skill") + $bonus)*$session['user']['level']*0.3) + $nmlevel,
								"defense" => round((get_module_pref("skill") + $bonus)*$session['user']['level']*0.1) + $nmlevel,
								"dyingtext" => "`5The `LT`1i`5g`%e`Vr `5jogs off, having completed its work here.`n",
								"abilities" => array(
									"fight" => true,
								),
								"ignorelimit" => true,
								"nonewday" => true,
								"cannotbehealed" => true,
							), true);
						} else {
							apply_buff('Nm10',array(
								"startmsg" => "`%You call upon the services of one of the proudest of felines - a `LT`1i`5g`%e`Vr`%!",
								"name" => "`i`\$S`Qu`^m`gm`@o`#n `LT`1i`5g`%e`Vr`i`0",
								"rounds" => $bonus + min($nmlevel,9) + 1,
								"wearoff" => "`5The `LT`1i`5g`%e`Vr `5jogs off, having completed its work here.",
								"atkmod" => e_rand(100,130)/100,
								"minioncount" => 1,
								"minbadguydamage" => 7 + round($nmlevel/2),
								"damageshield" => 0.2,
								"effectmsg" => "`%The `LT`1i`5g`%e`Vr`% pounces on {badguy}`%, mauling it for `b`#{damage}`b`% damage!",
								"maxbadguydamage" => round(get_module_pref("skill")*$session['user']['level']*0.4) + $nmlevel,
								"schema" => "specialtynekomancer",
							));
						}
					break;
					case 15:
						apply_buff('Nm15',array(
							"startmsg" => "`%{badguy}`% stands in shock as you begin growing ears, whiskers, paws and a tail!`0",
							"name" => "`i`\$N`Qe`^k`go`@mi`#mi `1M`5o`%d`Ve`i`0",
							"rounds" => 4 + min(floor($nmlevel/10),8),
							"wearoff" => "`%You revert back to normal.`0",
							"badguyatkmod" => 0.5 - $bonus/2,
							"invulnerable" => ($bonus && $session['user']['hitpoints'] < $session['user']['maxhitpoints']/4?1:0),
							"atkmod" => 1.4 + round($nmlevel/15,3),
							"defmod" => 1.3 + $bonus/5,
							"dmgmod" => 1 + $bonus/5,
							"schema" => "specialtynekomancer",
						));
					break;
				}
				increment_module_pref("uses",-max($l-$bonus,1));
			}
		break;
		
		case "pointsdesc":
			if ((int)$cost > 0) {
				$args['count']++;
				$format = $args['format'];
				$str = translate("`7The `vNekomancer `7specialty is available upon reaching %s Dragon Kills and %s points.");
				$str = sprintf($str, get_module_setting("mindk"), $cost);
				output($format, $str, true);
				if (get_module_setting("cost2") != 0) {
					output("This will cost %s points to select each time.",get_module_setting("cost2"));
				}
			}
		break;
	}
	return $args;
}
?>