<?php
		require_once("modules/marriage/marriage_func.php");
		set_module_pref('flirtsToday',0);
		set_module_pref('inShack',0);
		if ($session['user']['marriedto']!=0 && $session['user']['marriedto']!=4294967295) {
			$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=".$session['user']['marriedto']." AND locked=0";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			$namepartner = $row['name'];
			if (db_num_rows($res)<1) {
				$session['user']['marriedto']=0;
				debuglog("divorced with no notice due to death of the spouse");
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
				return $args;
			}
			if (marriage_getflirtpoints($session['user']['marriedto']) < get_module_setting('flirtmuch') && get_module_setting('pointsautodivorceactive')) {
				output_notl("`n");
				output("`bWhen  you  wake  up, you find a note next to you, reading`n`5Dear %s`5,`n",$session['user']['name']);
				output("Despite  many  great  kisses, I find that I'm simply no longer attracted to you the way I used to be.`n`n");
				output("Call  me fickle, call me flakey, but I need to move on.");
				output("There are other warriors in the land, and I think some of them are really hot.");
				output("So it's not you, it's me, etcetera etcetera.`n`n");
				output("No hard feelings, Love,`n%s`b`n",$namepartner);
				require_once("./modules/marriage/divorce.php");
				marriage_divorce();
				return $args;
			} else {
				output("`n`@You and %s`0`@ spend the night in the inn together, and you both emerge positively glowing!",$namepartner);
				apply_buff('marriage-married',
					array(
						"name"=>array("`\$%s`^'s Love",$namepartner),
						"rounds"=>60,
						"wearoff"=>"`%You feel lonely.`@",
						"defmod"=>1.03,
						//"survivenewday"=>1,
						"roundmsg"=>array("`4 %s`@ watches over you",$namepartner),
						)
					);
			}
			//decrease points per day
			$howmuch=get_module_setting('charmnewday');
			if ($howmuch) {
				marriage_modifyflirtpoints($session['user']['marriedto'],-$howmuch);
				output("`@Your flirt points with %s`@ decrease as you are getting more used to each other.",$namepartner);
			}
			if (get_module_setting('flirtAutoDiv')==1&&get_module_setting('flirtAutoDivT')>0) {
				if (get_module_pref('flirtsfaith')>=get_module_setting('flirtAutoDivT')) {
					set_module_pref('flirtsfaith',0);
					if (get_module_setting('oc')==1) {
						$location = 'oldchurch';
					} else {
						$location = 'chapel';
					}
					$location='chapel';
					$t = array("`%Uh oh!");
					require_once("lib/systemmail.php");
					if ($session['user']['marriedto']!=0&&$session['user']['marriedto']!=4294967295) {
						$mailmessage=array("%s`0`@ was forced by you to get a divorce, due to being unfaithful.",$session['user']['name']);
						systemmail($session['user']['marriedto'],$t,$mailmessage);
					}
					$mailmessage=array("You were forced to get a divorce, due to being unfaithful.","");
					systemmail($session['user']['acctid'],$t,$mailmessage);
					redirect('runmodule.php?module=marriage&op=chapel&op2=divorce','Auto-Divorce');
				}
			}
		}
		marriage_seencleanup($session['user']['acctid']);
		marriage_receivedcleanup($session['user']['acctid']);
?>