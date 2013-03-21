<?php
// addnews ready
// mail ready
// translator ready
// 

  /********************************************************************* 
  *                                                                    *
  *                 A LoTGD module [www.lotgd.org]                     *
  *                                                                    *
  *  Written by defproc [www.defproc.co.uk / defproc@defproc.co.uk]    *
  *  Modify it all you like but please leave this comment intact.      *
  *                                                                    *
  *  Copyright (c) 2006, defproc.co.uk                                 *
  *                                                                    *
  **********************************************************************/
  
  // (Sorry about the mess, this script is my lotgd module learning project!)
  // (Fantastic engine, guys. Cheers.)   

/*

This module produces a forest event in which the player encounters a little
girl. This might sound weird but its based on a similar event in LORD2. The
player chooses whether to help the child find her mother or to ignore her.
If the player chooses to help her, he/she must defeat n* creatures to unlock
the 'find mother' village/inn event. If the player dies while accompanying a
girl, the girl's spirit is seen in the shades or graveyard shortly thereafter,
creating/increasing a 'guilt' stat for the player. Guilt deprives the player
of 2 forest fights per day for n* days. If the player manages to return the
child to its mother, he/she is given the option to accept a payment, details
(x** gold and y** gems) of which aren't known until the payment's accepted. If
the player refuses payment, charm is gained, 'guilt' decreases and the
'Unselfishness' buff (from Heidi) is applied for 20 rounds.

While accompanying a girl, the player has the option to kill her (again, from
LORD2 - I'm not a creep!). This option can be disabled* via the module
configuration in the grotto. Killing a girl gives the player 1 experience and
n* guilt days. A charm point is also deducted.

While travelling with a girl, an event may occur in which the girl insists on
taking a break to make a daisy chain. This costs a forest fight but heals the
player and gives 'inspiration' buff.

In the future I would like to add a 'mass murderer' feature, which has some
sort of effect when a player has killed more than n* girls. The lifetime
number of girls a player has killed is already recorded (and currently unused)
for this purpose.

* Admin-configurable value
** gold_reward = base_gold_reward* + (per_lvl_gold_reward* x playerlevel) and
    gem_reward = base_gems_reward* + (per_lvl_gems_reward* x playerlevel)

*/

//V1.1: Added missing $session['user']['gold/gems']+ for the reward by DaveS
//V1.2: Fixed translation readiness by SexyCook, added soulpoint bugfix by kickme
//V1.3: corrected sentenses with \n to `n
//V1.31: translation readied "$name's Mother"
//V1.32: translation readied one $sirmiss and "talk to her" html code
//V1.33: translation readied the buff from the chain of flowers
//V1.4: Added Alignment changes when returning the girl or killing her
//V1.41: Added if-arguments for the alignment module (thx Nightborn)
//V1.42: Added redundant code to case shades, in case you die outside of a battle, for example in stonehedge
//V1.43: Translation readied the numbers for the girl's ages
//V1.44: Translation readied the guityreason
//V1.45: Gradual loosing soulpoints reapeared, corrected
//V1.46: Over maximum hitpoint loss during a pause bug corrected

require_once("lib/http.php");

function littlegirl_getmoduleinfo(){
	$info = array(
		"name"=>"Lost Little Girl",
		"author"=>"`&de`#fpr`3oc, fixes by DaveS, kickme and SexyCook",
		"version"=>"1.46",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=163",
		"description"=>"`2Based on a `@LORD2`2 event.",
	        "settings"=>array(
                         "Lost Little Girl Settings,title",
                         "names"=>"Possible names for lost girls (comma seperated)|Lizzie,Nicole,Lisa,Annie,Kathy,Lorraine,Ellie,Shannon,Katie,Lucy,Melissa",
                         "cankill"=>"Allow players to kill the girls (LORD2 style),bool|0",
                         "killguilt"=>"How many days per kill to guilt trip (2 fight penalty per day) a murderer?,int|4",
                         "loseguilt"=>"How many days to guilt trip a player who died and left a girl behind?,int|2",
                         "goldreward"=>"Amount of gold to reward player for finding mother,int|100",
                         "gemsreward"=>"Amount of gems to reward player for finding mother,int|2",
                         "goldper"=>"Amount of additional gold per level to reward player for finding mother,float|50.43",
                         "gemsper"=>"Amount of additional gems per level to reward player for finding mother,float|0.13",
                         "killsneeded"=>"How many creatures player must defeat before meeting mother,int|8",
                         "maxage"=>"The maximum age of lost girls (only for display on 'something special' encounter),range,6,16|12"
                         ),
        	"prefs"=>array(
                         "Lost Little Girl User Preferences,title",
		         "name"=>"Name of current child, if any|",
		         "lastgirl"=>"Name of most recently killed/failed child, if any|",
		         "guilt"=>"How many days player will lose sleep (2 forest fights) for killing/failing lost girls?,int|0",
		         "guiltreadon"=>"Was the last girl \"killed\" or \"failed\"?",
		         "defeatname"=>"Name of the girl the player might see wandering the land of shades?",
		         "creaturekills"=>"How many creatures has the player defeated after meeting girl,int|0",
		         "kills"=>"How many girls the player has killed lost girls (lifetime),int|0",
		         "lastsaved"=>"Name of the last girl saved."
		         )
		);
	return $info;
}

function littlegirl_randomname()
{
 $names = explode(",",get_module_setting("names"));
 $count = count($names);
 $i = rand(0,$count-1);
 return $names[$i];
}

function littlegirl_findmother($return)
{
  global $session;
  $sirmiss = $session['user']['sex']?'Miss':'Sir';
  $manlady = translate_inline($session['user']['sex']?'lady':'man'); // NOTE: this variable is NOT a crossdresser.
  $name = get_module_pref("name");                 //            (Do NOT be fooled by its name.)
  page_header("%s's Mother",$name);
  output("`#\"Mother!\" `% %s`2 blurts out.`n`n",$name);
  output("`2A delighted young woman comes running over. `#\"Oh, %s! You're safe!\"`n`n",$name);
  output("`2She turns to you with a suspicious look. `#\"This %s helped me to find you\"`2 `% %s`2 smiles.`n`n",$manlady,$name);
  output("`#\"Oh! Thank you, kind %s. Had it not been for your noble actions, who knows what might have happened to her?\"`2 she says, handing you a small leather pouch.`n`n",$sirmiss);  
  set_module_pref("lastsaved",$name);
  set_module_pref("name","");
  addnav("Accept the payment?");
  addnav("Take the money","runmodule.php?module=littlegirl&op=accept&loc=".$return);
  addnav("\"No thanks\"","runmodule.php?module=littlegirl&op=refuse&loc=".$return);
}

function littlegirl_install(){
	module_addhook("graveyard");
	module_addhook("shades");
	module_addhook("battle-victory");
	module_addhook("battle-defeat");
        module_addhook("forest");

	module_addhook("newday");
	module_addhook("village-desc");
	module_addeventhook("forest", "return 100;");
	module_addeventhook("village", "return (get_module_pref(\"name\",\"littlegirl\")!='')&&(get_module_pref('creaturekills','littlegirl')>=get_module_setting('killsneeded','littlegirl'))?90:0;");
	module_addeventhook("inn", "return (get_module_pref(\"name\",\"littlegirl\")!='')&&(get_module_pref('creaturekills','littlegirl')>=get_module_setting('killsneeded','littlegirl'))?90:0;");
	return true;
}

function littlegirl_uninstall(){
	output("`nUninstalling `%littlegirl.`n");
	return true;
}

function littlegirl_dohook($hookname, $args){
	global $session;
	switch($hookname){
	case "newday":
	        $name = get_module_pref("name");
	        set_module_pref("defeatname","");
	        if ($name != ""){
	           output("`n`%Little %s`2 is with you.`n",$name);
	        }
	        $guilt = get_module_pref("guilt");
	        if ($guilt > 0){
	           $guilt--;
	           set_module_pref("guilt",$guilt);
	           $lastgirl = get_module_pref("lastgirl");
	           $session['user']['turns']-=2;
	           $guiltreason = translate_inline(get_module_pref("guiltreason"));
	           output("`n`7You did not sleep well at all last night, being overcome with guilt having %s poor `5%s`4. `4You `\$lose 2 `4forest fights for today.`n",$guiltreason,$lastgirl);
	        }
		break;
	case "village-desc";
	case "forest":
	        $return = "forest";
	        if ($hookname == 'village-desc'){
	          $return = "village";
	        }
	        $name = get_module_pref("name");
	        if ($name != ""){
	           $talklink = translate_inline("talk to her");
	           addnav('',"runmodule.php?module=littlegirl&op=talk&loc=$return");
	           output("`n`%Little %s`2 is merrily humming a tune.",$name);
	           $talkher=translate_inline("Talk to her");
                   output_notl(" [<a href='runmodule.php?module=littlegirl&op=talk&loc=$return'>$talkher</a>]",true);
                   output("`n");
	        }
	        break;
	case "battle-defeat":
	
	        if ($args['type'] == 'train'){
	          break;
	        }
	
	        $name = get_module_pref("name");
	        
	        if ($name != ""){
	           output("`n`%Little %s`2 screams in terror as the final blow is struck.`n`n",$name);
                   set_module_pref("lastgirl",$name);
                   set_module_pref("defeatname",$name);
                   set_module_pref("name","");
                   set_module_pref("guiltreason","failed");
                   $guilt = get_module_pref("guilt");
                   $guilt += get_module_setting("loseguilt");
                   set_module_pref("guilt",$guilt);
                   set_module_pref("creaturekills",0);
                   debug("littlegirl: creaturekills reset to 0");
                   addnews("`%Little %s`2 was reported missing.",$name);
                }
                break;
        case "battle-victory":
                if (get_module_pref("name")!=""){
                 $creaturekills = get_module_pref("creaturekills");
                 set_module_pref("creaturekills",$creaturekills+1);
                 debug("littlegirl: $creaturekills wins out of " . get_module_setting("killsneeded"));
                }
                break;

	case "shades":
	case "graveyard":
	        $name = get_module_pref("defeatname");
	        $soul = $session['user']['soulpoints'];
	        $multi = rand(1,4) / 10;
	        $penalty = round($soul * $multi);
	        if ($penalty >= $soul) {
	         $penalty = $soul - 1;
	         }
          if(get_module_pref("guilt")>0 && $name!="") 
							$session['user']['soulpoints'] -= $penalty;
					if ($name != ""){
	           output("`n`\$Your heart breaks as you notice the ghost of `5little %s`\$ gliding slowly past.`nYou lose %s `3soulpoints`7.`n",$name,$penalty);
	        }
	        set_module_pref("defeatname","");
	
  	        $name = get_module_pref("name");
	        
	        if ($name != ""){
	           output("`n`%Little %s`2 screams in terror as the final blow is struck.`n`n",$name);
                   set_module_pref("lastgirl",$name);
                   set_module_pref("defeatname",$name);
                   set_module_pref("name","");
                   set_module_pref("guiltreason","failed");
                   $guilt = get_module_pref("guilt");
                   $guilt += get_module_setting("loseguilt");
                   set_module_pref("guilt",$guilt);
                   set_module_pref("creaturekills",0);
                   debug("littlegirl: creaturekills reset to 0");
                   addnews("`%Little %s`2 was reported missing.",$name);
           }
  }
	return $args;
}

function littlegirl_runevent($type)
{
        global $session;
	switch($type) {
	case "forest":
	        $sirmiss = $session['user']['sex'] ? 'Miss' : 'Sir';
	        if (get_module_pref("name") != "") {
	         $name = get_module_pref("name");
	         page_header("A Special Gift");
                 output("`n`%Little %s`2 insists on resting for a while, despite your best protests.`n`n",$name);
                 output("You both seat yourselves in a small grassy clearing while `%%s`2 hums to herself, crafting the finest `&d`^a`&i`^s`&y `^c`&h`^a`&i`^n`2 you have ever seen.`n`n",$name);
                 output("After a period of time you could have otherwise spent on a `@forest fight`2, she jumps up and joyfully wraps her `&d`^a`&i`^s`&y `^c`&h`^a`&i`^n`2 around your neck.`n`n");
                 output("`#\"Do you like it, %s?\"`2 she sings. `#\"It's for you!\"`n`n",$sirmiss);
                 output("`2Your anger melts. `#\"I love it! Thank you!\"`2 you bellow. `#\"Come, little one, your mother will be worried sick!\"`n`n");
		 						 if($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
                 
		 apply_buff('Inspiration',
			array(
				"name"=>array("%s's Inspiration",$name),
				"rounds"=>15,
				"defmod"=>1.02,
				"atkmod"=>1.08,
				"roundmsg"=>array("`^Your gift from %s inspires you.",$name),
				"schema"=>"module-littlegirl",
				)
			);
                 
                 
                 addnav("Return","forest.php");
                } else {
                 $numbers = array("zero","one","two","three","four","five","six","seven","eight","nine","ten",
                                 "eleven","twelve","thirteen","fourteen","fifteen","sixteen","seventeen");
                 $age = translate_inline($numbers[rand(6,get_module_setting("maxage"))]);
	         page_header("Little Girl");
		 output("`2You come across a small girl - she couldn't be older than %s.`n`n",$age);
                 output("`#\"%s, will you help me find my mother?\"`2 she asks, pitifully.`n`n",$sirmiss);
		 addnav("Little Girl");
		 addnav("Help her","runmodule.php?module=littlegirl&op=yes");
		 addnav("Ignore the brat","forest.php");
	        }
		break;
	case "village":
	case "inn";
                littlegirl_findmother($type);
                break;
	}
}

function littlegirl_run(){
	global $session;
        $sirmiss = $session['user']['sex'] ? 'Miss' : 'Sir';
        $return = httpget("loc");
	switch(httpget('op')){
	case "accept":
		page_header("A Reward");
	        output("`#\"You're welcome\"`2 you mutter as you take the pouch and wave her off.`n`n");
	        $addgold = round($session['user']['level'] * get_module_setting("goldper")); 
                $addgems = round($session['user']['level'] * get_module_setting("gemsper"));
                $addgold += get_module_setting("goldreward");
                $addgems += get_module_setting("gemsreward");
                output("`2Opening the pouch you find %s `^gold`2 and %s `#gems`2.`n`n",$addgold,$addgems);
                $name = get_module_pref("lastsaved");
                $session['user']['gold']+=$addgold;
                $session['user']['gems']+=$addgems;

                if (is_module_active('alignment')) {
                    require_once("modules/alignment/func.php");
                    $align=get_module_pref("alignment","alignment")+1;
                    set_module_pref("alignment",$align,"alignment");
                 		debuglog("increased alignment points by 1 to $align rescuing a little girl and taking a fee");
                    debug("increased alignment points by 1 to $align rescuing a little girl and taking a fee");
                }

                modulehook("littlegirlacceptpay",array("name"=>$name));
                addnews("`%%s `&returned `%%s`& to her mother. For a `\$fee`&.",$session['user']['name'],$name);  
                addnav("Return to the $return",$return.'.php');
                break;
	case "refuse":
		page_header("A Reward");
	        output("`#\"You needn't pay me,\"`2 you tell her. `#\"I enjoyed the company. You have A fine daughter.\"`n`n");
                output("`2You feel `&charming`2.`n`n");
                $session['user']['charm'] += 3;
                $guilt = get_module_pref("guilt");
                $guiltreason = translate_inline(get_module_pref("guiltreason"));
                $guiltname = get_module_pref("lastgirl");
                $session['user']['gems']+=$addgems;

                if (is_module_active('alignment')) {
                    $align=get_module_pref("alignment","alignment")+3;
                    set_module_pref("alignment",$align,"alignment");
                 		debuglog("increased alignment points by 3 to $align rescuing a little girl");
                    debug("increased alignment points by 3 to $align rescuing a little girl");
                }
                
                if ($guilt > 0){
                  debug("littlegirl: guilt is $guilt");
                  output("`2The guilt of having %s `%Little %s`& subsides`n`n",$guiltreason,$guiltname);
                  $guilt = round($guilt * 0.28);
                  debug("littlegirl: guilt is now $guilt");
                  set_module_pref("guilt",$guilt);  
                }

		apply_buff('heidi', array(
			"name"=>"`QUnselfishness`0",
			"rounds"=>20,
			"defmod"=>1.05,
			"survivenewday"=>1,
			"roundmsg"=>"`QHelping others makes you feel empowered.`0",
			"schema"=>"module-littlegirl",
		));

                $name = get_module_pref("lastsaved");
                modulehook("littlegirlrefusepay",array("name"=>$name));
                addnews("`&Noble `%%s `&returned `%%s`& to her mother, safe and sound.",$session['user']['name'],$name); 
                addnav("Return to the $return",$return.'.php');
                break;
                case "yes":
                $name = littlegirl_randomname();
                while ($name == ''){
                   $name = littlegirl_randomname();
                }
                set_module_pref("name",$name);
                set_module_pref("creatureskilled",0);
		            page_header("Little %s",$name);
                output("`#\"Thank you so much!`\"`2 she pipes.`n`n`#\"And what is your name, little one?\" `2you ask.`n`n`#\"`%%s`#,\" `2she chirps.`n`n",$name);
                output("`#\"Where can we find your mother, `%%s`#?\"`2 you ask. `#\"I don't know, %s, she could be anywhere!\"`2.",$name,$sirmiss); 
                set_module_pref("creaturekills",0);
                debug("littlegirl: creaturekills reset to 0");
                addnav("Return to the forest","forest.php");
                break;
	case "talk":
	  $name = get_module_pref("name");
		$cankill = get_module_setting("cankill");
		page_header("Little %s",$name);
		output("`2You motion the girl to sit down so that you may speak with her.`n`n");
		output("`#\"What were you doing so far from town?\"`2 you ask.`n`n");
		output("`#\"I was picking flowers and... I wanna go home!\"`2 she cries.`n`n");
		addnav(array("Little %s", $name));
		if (get_module_setting("cankill")){
		 addnav("`4Kill her","runmodule.php?module=littlegirl&op=kill&loc=$return");
		}
		addnav("Other");
		addnav("Return","$return.php");
		break;
	case "kill":
	        $name = get_module_pref("name");
		page_header("Little %s",$name);
		output("`#\"Come here, small one. I won't hurt you\"`2 you beckon.`n`n");
		output("`2The child slowly advances toward you. `#\"Yes %s?\"`n`n",$sirmadam);
		addnav("Kill her?");
        	addnav("End her life","runmodule.php?module=littlegirl&op=killyes&loc=$return");
		addnav("Have mercy","runmodule.php?module=littlegirl&op=killno&loc=$return");
		break;
	case "killyes":
	        $name = get_module_pref("name");
	        addnews("`%Little %s`2 was reported missing.",$name);
	        $usrweapon = $session['user']['weapon'];
		page_header("Little " . $name);
		output("`2Checking to make sure nobody's looking you quickly thrust your %s into `%%s`2's tender heart.`n`n",$usrweapon,$name);
		$guilt = get_module_pref("guilt");
		$guilt += get_module_setting("killguilt");
		$kills = get_module_pref("kills");
		$kills++;
		set_module_pref("kills",$kills);
		set_module_pref("guilt",$guilt);
                set_module_pref("guiltreason","murdered");
		set_module_pref("lastgirl",$name);
		set_module_pref("name","");
                set_module_pref("creaturekills",0);
                debug("littlegirl: creaturekills reset to 0");
		output("`^You gain 1 experience`n`n");
		$session['user']['experience']++;
    $session['user']['gems']+=$addgems;

    if (is_module_active('alignment')) {
        $align=get_module_pref("alignment","alignment")-5;
        set_module_pref("alignment",$align,"alignment");
    		debuglog("decreased alignment points by 5 to $align killing a little girl");
        debug("decreased alignment points by 5 to $align killing a little girl");
    }

		output("`2Looking down at her lifeless body you are saddened. Whatever posessed you to do such a thing?`n`n");
		if ($session['user']['charm'] > 0){
                  $session['user']['charm']--;
		  output("`\$You lose a charm point.`n`n");
		}
		addnav("Return","$return.php");
		break;
	case "killno":
	        $name = get_module_pref("name");
		page_header("Little " . $name);
		output("`#\"I just wanted to... Straighten your dress. There, all better.\"`n`n");
		output("`2She thanks you, utterly oblivious to her brush with death.`n`n");
		addnav("Return","$return.php");
		break;
	}
	page_footer();
}
?>
