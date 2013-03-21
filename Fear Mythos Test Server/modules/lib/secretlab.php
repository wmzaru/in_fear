<?php
global $session;
$op = httpget('op');
page_header("Secret Laboratory");
$newjar = e_rand(1,7);
if ($newjar == get_module_pref('oldjar')) $newjar = $newjar + 1;
if ($newjar == 8) $newjar = 1;
set_module_pref('oldjar',$newjar);
$mix = get_module_pref('mix') + 0;
	if ($op == ""){
		secretlab_dodisplay("sl_main".$newjar.".gif");
		addnav("Forget it!","forest.php");
		output("`c`n`#You get to mix 3 chemicals into the base chemical to make a potion!`n");
		output("`#Be careful not to make it too strong!  You might not like the`n");
		output("`#results!  Make a good potion and drinking it will make you much`n");
		output("`#stronger!  Make a bad potion and you may just blow yourself up!`n`c");
		//yes this may change as other players enter while someone else is playing
		//that is inconsequetial and actually works to our advantage as potions
		//shoud be very unpredictable.
		$low = e_rand(1,10);
		set_module_setting('jar1',e_rand($low,25));
		$low = e_rand(1,10);
		set_module_setting('jar2',e_rand($low,25));
		$low = e_rand(1,10);
		set_module_setting('jar3',e_rand($low,25));
		$low = e_rand(1,10);
		set_module_setting('jar4',e_rand($low,25));
		$low = e_rand(1,10);
		set_module_setting('jar5',e_rand($low,25));
		$low = e_rand(1,10);
		set_module_setting('jar6',e_rand($low,25));
		//pushing potions up - 1 all the time 2 others once in a while
		$onehigh = e_rand(1,6);
		$onehigh = "jar".$onehigh;
		set_module_setting($onehigh,25);
		if (e_rand(1,10) == 6){
			$twohigh = e_rand(1,6);
			$twohigh = "jar".$twohigh;
			set_module_setting($twohigh,25);
		}
		if (e_rand(1,10) == 6){
			$threehigh = e_rand(1,6);
			$threehigh = "jar".$threehigh;
			set_module_setting($threehigh,25);
		}
		set_module_pref('mix',0);
	}elseif (strstr($op,"jar") != ""){
		$potionstrength = get_module_setting($op);
		switch (e_rand(1,5)){
			case 1:
				$potionstrength = $potionstrength - 2;
			break;
			case 2:
				$potionstrength = $potionstrength - 1;
			break;
			case 3:
				//no change - fluxing potions a bit here...
			break;
			case 4:
				$potionstrength = $potionstrength + 1;
			break;
			case 5:
				$potionstrength = $potionstrength + 2;
			break;
		}
		set_module_pref('strength',get_module_pref('strength') + $potionstrength);
		if ($mix == 2){
			secretlab_nonavdisplay("secret_lab_main".$newjar.".gif");
			$strength = get_module_pref('strength');
			set_module_pref('strength',0);
			secretlab_bigtext("`n`b`c`^You swallow the contents of the bottle!`c`b");
			if ($strength < 10){
				if (e_rand(1,2) == 1){
					secretlab_bigtext("`b`c`!Not much happens, you feel a bit sick.`c`b");
				}else{
					secretlab_bigtext("`b`c`!Not much happens, you don't feel well.`c`b");
				}
				addnav("Continue","forest.php");
			}elseif ($strength < 15){
				if (e_rand(1,2) == 1){
					secretlab_bigtext("`n`b`c`!You feel drained.`c`b");
					if ($session['user']['turns'] > 0) $session['user']['turns'] -= 1;
				}else{
					secretlab_bigtext("`n`b`c`!You feel Very Low.`c`b");
					$session['user']['spirits'] = -2;
				}
				addnav("Continue","forest.php");
			}elseif ($strength < 20){
				$remcharm = e_rand(3,6);	
				secretlab_bigtext("`n`b`c`!You've been disfigured!`c`b");
				$session['user']['charm'] -= $remcharm;
				addnav("Continue","forest.php");
			}elseif ($strength < 25){
				secretlab_bigtext("`n`b`c`!You feel exhausted.`c`b");
				if ($session['user']['turns'] > 2){
					$session['user']['turns'] -= 3;
				}elseif ($session['user']['turns'] > 1){
					$session['user']['turns'] -= 2;
				}elseif ($session['user']['turns'] > 0){
					$session['user']['turns'] -= 1;
				}
				addnav("Continue","forest.php");
			}elseif ($strength < 30){
				$addcharm = e_rand(4,9);
				secretlab_bigtext("`n`b`c`!You feel a bit better looking.`c`b");
				$session['user']['charm'] += $addcharm;
				addnav("Continue","forest.php");
			}elseif ($strength < 35){
				$addturn = e_rand(1,2);
				secretlab_bigtext("`n`b`c`!You feel energized`c`b");
				$session['user']['turns'] += $addturn;
				addnav("Continue","forest.php");
			}elseif ($strength < 40){
				if (e_rand(1,2) == 1){
					secretlab_bigtext("`n`b`c`!You feel a bit stronger.`c`b");
					apply_buff('secretlabstrong1',array("name"=>"`4Chemical Stregth","rounds"=>100,"wearoff"=>"`4Your chemical strength fades.","atkmod"=>1.25,"activate"=>"offense"));
				}else{
					secretlab_bigtext("`n`b`c`!You feel a bit quicker.`c`b");
					apply_buff('secretlabquick1',array("name"=>"`4Chemical Defence","rounds"=>100,"wearoff"=>"`4Your chemical defence fades.","defmod"=>1.25,"activate"=>"defence"));
				}
				addnav("Continue","forest.php");
			}elseif ($strength < 45){
				$addturn = e_rand(2,4);
				secretlab_bigtext("`n`b`c`!You feel invigorated.`c`b");
				$session['user']['turns'] += $addturn;
				addnav("Continue","forest.php");
			}elseif ($strength < 50){
				if (e_rand(1,2) == 1){
					secretlab_bigtext("`n`b`c`!You feel alot stronger!`c`b");
					apply_buff('secretlabstrong2',array("name"=>"`4Chemical Stregth","rounds"=>150,"wearoff"=>"`4Your chemical strength fades.","atkmod"=>1.33,"activate"=>"offense"));
				}else{
					secretlab_bigtext("`n`b`c`!You feel alot quicker!`c`b");
					apply_buff('secretlabquick2',array("name"=>"`4Chemical Defence","rounds"=>150,"wearoff"=>"`4Your chemical defence fades.","defmod"=>1.33,"activate"=>"defence"));
				}
				addnav("Continue","forest.php");
			}elseif ($strength < 55){
				if (e_rand(1,2) == 1){
					secretlab_bigtext("`n`b`c`!You gain a Maxhitpoint!`c`b");
					$session['user']['maxhitpoints'] += 1;
				}else{
					secretlab_bigtext("`n`b`c`!You gain 50 hitpoints!`c`b");
					$session['user']['hitpoints'] += 50;
				}
				addnav("Continue","forest.php");
			}elseif ($strength < 60){
				if (e_rand(1,2) == 1){
					secretlab_bigtext("`n`b`c`!You feel invincible!`c`b");
					apply_buff('secretlabstrong3',array("name"=>"`4Chemical Stregth","rounds"=>200,"wearoff"=>"`4Your chemical strength fades.","atkmod"=>1.5,"activate"=>"offense"));
				}else{
					secretlab_bigtext("`n`b`c`!You feel invincible!`c`b");
					apply_buff('secretlabquick3',array("name"=>"`4Chemical Defence","rounds"=>200,"wearoff"=>"`4Your chemical defence fades.","defmod"=>1.5,"activate"=>"defence"));
				}
				addnav("Continue","forest.php");
			}elseif ($strength < 65){
				secretlab_bigtext("`n`b`c`!You don't feel so good!`c`b");
				$session['user']['hitpoints'] = round($session['user']['hitpoints'] * 0.75);
				addnav("Continue","forest.php");
			}elseif ($strength < 70){
				redirect("runmodule.php?module=secretlab&op=smallexplode");
			}else{
				redirect("runmodule.php?module=secretlab&op=explode");
			}
		}else{
			secretlab_dodisplay("secret_lab_main".$newjar.".gif");
			set_module_setting($op,e_rand(1,5));
			if ($potionstrength < 5){
				secretlab_bigtext("`c`!Not much of a reaction.`c");
			}elseif ($potionstrength < 8){
				secretlab_bigtext("`c`@Hmmm... a little something happened.`c");
			}elseif ($potionstrength < 10){
				secretlab_bigtext("`c`@Hmmm... that's interesting.`c");
			}elseif ($potionstrength < 13){
				secretlab_bigtext("`c`@Interesting, very interesting.`c");
			}elseif ($potionstrength < 15){
				secretlab_bigtext("`c`#Oh... that was Cool.`c");
			}elseif ($potionstrength < 18){
				secretlab_bigtext("`c`@Nice!`c");
			}elseif ($potionstrength < 20){
				secretlab_bigtext("`c`4Major reaction.`c");
			}else{
				secretlab_bigtext("`c`%Now that was scary, better be careful!`c");
			}
		}
		set_module_pref('mix',$mix + 1);
	}elseif ($op == "smallexplode"){
		output("`b`cSecret Laboratory!`c`b`n");
		$display = "<center><img border=\"0\" src=\"./images/secret_lab_explode.gif\" width=\"288\" height=\"215\"></center>";
		rawoutput($display);
		secretlab_bigtext("`c`bOooops!`b`c");
		$session['user']['hitpoints'] = round($session['user']['hitpoints'] * .05);
		addnav("Continue","forest.php");
	}elseif ($op == "explode"){
		output("`b`cSecret Laboratory!`c`b`n");
		$display = "<center><img border=\"0\" src=\"./images/secret_lab_explode.gif\" width=\"288\" height=\"215\"></center>";
		rawoutput($display);
		secretlab_bigtext("`c`bOooops!`b`c");
		$session['user']['hitpoints'] = 0;
		$session['user']['alive'] = 0;
		if (is_module_active("morgue")){
			$diedhow = translate_inline(" - Blown up in the secret laboratory");
			set_module_pref("died",$diedhow,"morgue");
			set_module_pref("killdate",date("Y-m-D h:m:s"),"morgue");
		}
		addnav("Continue","shades.php");
	}else{
		//user should not be here so blow things up
		redirect("runmodule.php?module=secretlab&op=explode");
	}
page_footer();
?>