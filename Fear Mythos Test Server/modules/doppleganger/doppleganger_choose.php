<?php
function doppleganger_choose(){
	global $session;
	output("`c`b`@The Doppleganger`b`c`n");
	if (get_module_pref("dopplename")==2){
		output("You puff out your chest in a show of power and force. Is there anyone in the land greater than you?");
		output("`n`nYou feel a tap on your shoulder. You turn around and find yourself face to face with yourself!");
		output("`n`nYourself introduces %s to you.",translate_inline($session['user']['sex']?"herself":"himself"));
		output("`n`n`#'Hello.  I am The Doppleganger.  I am a shapeshifter and I am able to assume the appearance of anyone that I deem worthy.'");
		output("`n`n'You are worthy.'");
		output("`n`n'From henceforth, occassionally other warriors will encounter me in the forest, and I will honor your accomplishments by dueling them in your name.");
		output("In addition, I will wield a fascimile of your weapon and wear your armor so that they may see how mighty you are.'`n`n");
		set_module_pref("dopplename",1);
		$allprefs=unserialize(get_module_pref('allprefs'));
		$allprefs['name']=$session['user']['name'];
		$allprefs['armor']=$session['user']['armor'];
		$allprefs['weapon']=$session['user']['weapon'];
		$allprefs['sex']=$session['user']['sex'];
		set_module_pref('allprefs',serialize($allprefs));
	}
	$allprefs=unserialize(get_module_pref('allprefs'));
	$ap=0;
	if ($allprefs['approve1']==2) $ap=1;
	elseif ($allprefs['approve2']==2) $ap=2;
	elseif ($allprefs['approve3']==2) $ap=3;
	if ($ap>0){
		$phrase1 = httpget('phrase1');
		if ($phrase1==""){
			blocknav("news.php");
			blocknav("village.php");
			$subop = httpget('subop');
			$submit1= httppost('submit1');
			if ($subop!="submit1"){
				output("`@The Doppleganger looks you over and notices that you are worthy of greater adoration.`n`n`#'I will use");
				if ($ap==1) {
					output("a battle cry in my attacks when I fight to honor you.  You may choose which battle cry I will use to cause my foe to cringe in fear.'`n`n");
					if ($allprefs['phrase2']!="" && $allprefs['phrase3']!="") output("Current Battle Cries:`n`n`c`@'%s`@'`n`n'%s`@'`n`n",$allprefs['phrase2'],$allprefs['phrase3']);
					elseif ($allprefs['phrase2']!="" || $allprefs['phrase3']!=""){
						if ($allprefs['phrase2']!="") $y=2;
						if ($allprefs['phrase3']!="") $y=3;
						output("Current Battle Cry:`n`n`c`@'%s`@'`c`n`n",$allprefs['phrase'.$y]);
					}
					output("`#'What is your battle cry?'`n`n");				
				}
				if ($ap==2){
					output("another battle cry in my attacks when I fight to honor you.  You may choose another battle cry I will use to cause my foe to cringe in fear.'`n`n");
					if ($allprefs['approve1']==4) output("`#'You declined to offer a battle cry the first time.  Would you like to offer a battle cry now?'");
					else {
						output("'The first battle cry that you chose was:`n`n");
						output("`c`@'%s`@'`c",$allprefs['phrase1']);
						if ($allprefs['phrase3']!="") {
							output("`#'The third battle cry that you chose was:`n`n");
							output("`c`@'%s`@'`c",$allprefs['phrase3']);
						}
						output("`n`#'What is your 2nd battle cry?'`n`n");
					}
				}
				if ($ap==3){
					output("a third battle cry in my attacks when I fight to honor you.  You may choose one more battle cry I will use to cause my foe to cringe in fear.'`n`n");
					if ($allprefs['approve1']==4 && $allprefs['approve2']==4) output("`#'You declined to offer a battle cry earlier.  Would you like to offer a battle cry now?'");
					elseif ($allprefs['approve1']==4 && $allprefs['approve2']!=4) {
						output("`n`n'The first battle cry that you chose was:`n`n");
						output("`c`@'%s`@'`c",$allprefs['phrase1']);
						output("`n`#'You declined to offer a second battle cry.'`n");
						output("`n`#'What is your next battle cry?'`n`n");
					}elseif ($allprefs['approve1']!=4 && $allprefs['approve2']==4) {
						output("`n`n'The declined the first battle cry.`n`n");
						output("`n`n`#'You second battle cry that you chose was:'`n");
						output("`c`@'%s`@'`c",$allprefs['phrase2']);
						output("`n`#'What is your next battle cry?'`n`n");
					}else{
						output("`n`n'The first two battle cries that you chose are:`n`n");
						output("`c`@'%s`@'`c",$allprefs['phrase1']);
						output("`c`n`@'%s`@'`c",$allprefs['phrase2']);
						output("`n`#'What is your 3rd battle cry?'`n`n");
					}
				}
				output("`^`cNote: You may use colors`c`n");
				$submit1 = translate_inline("Submit");
				rawoutput("<form action='runmodule.php?module=doppleganger&op=choose&subop=submit1' method='POST'><input name='submit1' id='submit1'><input type='submit' class='button' value='$submit1'></form>");
				addnav("","runmodule.php?module=doppleganger&op=choose&subop=submit1");	
				addnav("Decline","runmodule.php?module=doppleganger&op=decline&op2=$ap");
			}else{
				if ($submit1==""){
					output("`#'Since you did not submit a phrase I will offer you a chance to try again.  Otherwise, you may decline.'");
					addnav("Try Again","runmodule.php?module=doppleganger&op=choose");
					addnav("Decline","runmodule.php?module=doppleganger&op=decline&op2=$ap");
				}else{
					output("`#'Is this the phrase you wish to use?'");
					$submit1 = stripslashes($submit1);
					output("`n`n`c`@'%s`@'`c",$submit1);
					addnav("Yes","runmodule.php?module=doppleganger&op=choose&phrase1=$submit1");
					addnav("No","runmodule.php?module=doppleganger&op=choose");
				}
			}
		}else{
			output("`^Your phrase will be reviewed for approval.`n");
			$allprefs['approve'.$ap]=3;
			$allprefs['phrase'.$ap]=stripslashes($phrase1);
			set_module_pref('allprefs',serialize($allprefs));
		}
	}
	addnav("News","news.php");
	villagenav();
}
?>