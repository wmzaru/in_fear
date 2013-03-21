<?php

// Based slightly on Apple Bobbing Stand code

require_once("lib/villagenav.php");
require_once("lib/http.php");

function sabusnake_getmoduleinfo(){
    $info = array(
        "name"=>"Sabu the Snakecharmer",
        "version"=>"1.02",
        "author"=>"Enhas, based on applebob by Chris Vorndran",
        "category"=>"Village",
        "download"=>"http://www.dragonprime.net/users/Enhas/sabusnake.txt",
        "settings"=>array(
            "Sabu the Snakecharmer - Settings,title",
			"ffgain"=>"Number of forest fights to gain if successful,range,2,20,2|10",
                  "pvpgain"=>"Number of PvP fights to gain if successful,range,1,3,1|1",
			"gemcost"=>"Cost in gems,range,2,20,2|10",
                  "charmallowed"=>"Number of times a day allowed to visit the Snakecharmer,range,1,3,1|1",
			"sabusnakeloc"=>"Where does the Snakecharmer appear,location|".getsetting("villagename", LOCATION_FIELDS)
        ),
        "prefs"=>array(
            "Sabu the Snakecharmer - User Preferences,title",
			"charmtoday"=>"How many times has the player visited the Snakecharmer today,int|0",
        )
    );
    return $info;
}

function sabusnake_install(){
	module_addhook("changesetting");
	module_addhook("newday");
	module_addhook("village");
    return true;
}

function sabusnake_uninstall(){
    return true;
}

function sabusnake_dohook($hookname,$args){
    global $session;
    switch($hookname){
   	case "changesetting":
		if ($args['setting'] == "villagename") {
			if ($args['old'] == get_module_setting("sabusnakeloc")) {
				set_module_setting("sabusnakeloc", $args['new']);
			}
		}
	break;
   	case "newday":
		set_module_pref("charmtoday",0);
	break;
	case "village":
		if ($session['user']['location'] == get_module_setting("sabusnakeloc")) {
            tlschema($args['schemas']['marketnav']);
			addnav($args['marketnav']);
            tlschema();
			addnav("k?Sabu the Snakecharmer","runmodule.php?module=sabusnake");
		}
		break;
	}
    return $args;
}

function sabusnake_run() {
      global $session;
	$op = httpget('op');
	$cost=get_module_setting("gemcost");
	$charmallowed=get_module_setting("charmallowed");
	$charmtoday=get_module_pref("charmtoday");
      $ffgain=get_module_setting("ffgain");
      $pvpgain=get_module_setting("pvpgain");
	page_header("Sabu the Snakecharmer");
	output("`c`b`2Sabu the Snakecharmer`0`b`c");
      output("`n");
	if ($charmtoday >= $charmallowed){
		output("`3As you enter, Sabu looks up at you.`n");
            output("'`2I do not think you should tempt your luck against `!Ubas`2 again today, my friend.'`n`n");
            output("`3The Cobra hisses at you from the side of the room, bearing its fangs.  Not wanting to tempt your life, you leave.`0");
            addnav("Leave","village.php");
	}elseif ($op==""){
		output("`3Moving towards the secluded hut near the end of the Village, you feel a bit uneasy.`n");
            output("A small sign reads:`n`n");
            output("`2SABU THE SNAKECHARMER - LOYAL SERVENT OF UBAS - THE GOD OF SNAKES`n`n");
            output("`3You enter the hut, and are immediately confronted with a very large, poisonous Cobra!");
            output("Drawing your `7%s`3, you are ready to attack the beast when a strangely dressed man wearing a turban enters the room from the back.`n`n", $session['user']['weapon']);
            output("`2'Greetings!  Do not mind him, he doesn't bite.. much.'`3 he says. `2'Oh, excuse my manners.  I am Sabu, the Snakecharmer.  Do you wish for me to pray to the God of Snakes, `!Ubas`2, for you?`n`n");
            output("`@'Isn't Garlus the Snake God?'`3 you ask.`n`n");
            output("`3Sabu leaps in fury.  `2'Do not mention that name here, blasphemy.. pure blasphemy!  Anyway, do you wish for me to pray to the Snake God, `!Ubas`2?  He has been known to reward his followers.. sometimes.  This does not come cheap though, I will need some.. compensation for my services.`0");
            addnav(array("Pay %s gems to Sabu",$cost),"runmodule.php?module=sabusnake&op=pray");
            addnav("Leave","village.php");
	}elseif ($op=="pray"){
            if ($session['user']['gems']<$cost){
            output("`3You search your pockets for the required fee.  However, you do not find enough!`n");
            output("`3The Cobra looks angry! You slowly back away, but trip over a box on your way out.`n`n`0");
            $hploss = round($session['user']['hitpoints'] * 0.1 ,0);
            output("`\$You have lost some hitpoints!`0");
            $session['user']['hitpoints'] -= $hploss;
            if ($session['user']['hitpoints']<1)
	      $session['user']['hitpoints']=1;
            addnav("Back away","village.php");
            }else{
		$charmtoday++;
		set_module_pref("charmtoday",$charmtoday);
		$session['user']['gems']-=$cost;
		debuglog("spent $cost gems on Sabe the Snakecharmer.");
		output("`3You give Sabu your `^%s`3 gems, and he motions you to sit down on a small straw mat on the floor.  The Cobra slithers between you and Sabu.`n`n",$cost);
		output("Sabu takes out a small flute, and begins to play...`n`n");
		$result=(e_rand(1,4));
		if ($result==1){
                  addnav("Leave","village.php");
			output("`3The Cobra begins to calm down, and dance to the tune!  Sabu continues playing for a few moments, then the Cobra slithers away.`n`n");
			output("`2'Well done!'`3 Sabu says.  `2'`!Ubas`2 is pleased with you today, very pleased!'`n`n");
                  output("`3You feel a sudden, sharp tingling over your entire body!`n`n");
                  output("`!Ubas`3's power flows through you!  You feel as if you can slay `^%s`3 more monsters in the `@forest!`n`n", $ffgain);
                  $session['user']['turns']+=$ffgain;
                  $sabubuff = array(
				"name"=>"`!Ubas's Blessing",
				"rounds"=>($ffgain + 10),
				"wearoff"=>"`!You feel Ubas's power leave you.",
				"atkmod"=>(1+($ffgain / 100)),
				"roundmsg"=>"`!You feel a godly power within your body!",
				"schema"=>"module-sabusnake"
			      );
			      apply_buff("sabubuff",$sabubuff);
                  output("`3You bid Sabu farewell, quickly exiting to be rid of that awful Cobra.`0");
                  }elseif ($result==2){
                  addnav("Leave","village.php");
			output("`3The Cobra begins to anger, but soon subsides and begins to move in a fluid motion.  Sabu continues playing for a few moments, then the Cobra slithers away.`n`n");
			output("`2'Well done!'`3 Sabu says.  `2'`!Ubas`2 is pleased with you today, very pleased!'`n`n");
                  output("`3You feel a sudden, sharp tingling over your entire body!`n`n");
                  output("`3You feel `\$VICIOUS`3!  You feel the need to slay `^%s`3 more of your foes in `\$PvP combat!`n`n", $pvpgain);
                  $session['user']['playerfights']+=$pvpgain;
                  output("`3You bid Sabu farewell, quickly exiting to be rid of that awful Cobra.`0");
                  }elseif ($result==3  || $result==4){
                  output("`3The Cobra begins to anger, and thrash around!  Sabu continues playing for a few moments, and the Cobra slithers towards you!`n");
			output("`3Not having enough time to move, the Cobra drives one of its large fangs deep into your leg!`n`n");
                  output("`3You can feel the poison already taking effect.`n`n");
			output("Sabu frowns at you.  `2'`!Ubas`2 was not pleased with you today, oh no!'`n`n");
                  output("`3As your vision begins to go black, you can see the Cobra's mouth opening wide, preparing for its meal...`n`n");
			output("`b`4You have died!`n");
			output("Sabu steals your gold off your body, before letting the Cobra eat you!`n");
			output("10% of your experience has been lost!`n");
			output("You may continue playing again tomorrow.`0");
			$session['user']['alive']=false;
			$session['user']['hitpoints']=0;
			$session['user']['experience']*=0.9;
			$gold = $session['user']['gold'];
			$session['user']['gold'] = 0;
			addnav("Daily News","news.php");
			addnews("`\$%s`7 became a Cobra's meal after failing to please the Snake God, `!Ubas`0.",$session['user']['name']);
			debuglog("lost $gold gold from Sabu the Snakecharmer.");
	}
      }
      }
	page_footer();
      }
?>