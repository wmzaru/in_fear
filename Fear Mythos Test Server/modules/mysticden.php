<?php

require_once("lib/villagenav.php");
require_once("lib/http.php");

function mysticden_getmoduleinfo() {
    $info = array(
        "name"=>"The Mystic's Den",
        "version"=>"1.0",
        "author"=>"Sneakabout",
        "category"=>"General",
        "download"=>"http://dragonprime.net/users/Sneakabout/mysticden.txt",
        "settings"=>array(
            "Mystic Den - Settings,title",
            "traintimes"=>"How many times can you train each day?,int|3",
			"traincost"=>"How much does it cost to train per specialty level?,int|200",
			"racebuffcost"=>"How much does it cost to get a racial buff (per level)?,int|200",
			"restoration"=>"How much does the cleansing cost?,int|5",
			"trollbrewcost"=>"How much does the Trollish Brew cost?,int|10",
			"healpotioncost"=>"What is the healing multiple from the base full heal cost?,int|2",
			"dkclear"=>"Do the mystic items clear on Dragon Kill?,bool|1",
			"healclear"=>"Does the healing potion decay on New Day?,bool|1",
        ),
        "prefs"=>array(
            "Mystic Den - User Preferences,title",
			"hastrained"=>"How many times has this person trained today?,int|0",
			"hasbuffed"=>"Has this person gained a racial buff today?,bool|0",
			"trollbrew"=>"How many flasks of Trollish Brew does this person have?,int|0",
			"healpotion"=>"How many healing potions does this person have?,int|0",
			"number"=>"What percentage do they need to buy to enter the shop?,viewonly",
        )
    );
    return $info;
}

function mysticden_install(){
	module_addhook("footer-healer");
	module_addhook("newday");
	module_addhook("gypsy");
	module_addhook("dragonkilltext");
	module_addhook("fightnav-specialties");
	module_addhook("apply-specialties");
    return true;
}

function mysticden_uninstall(){
    return true;
}

function mysticden_dohook($hookname,$args){
    global $session;
    switch($hookname){
		case "newday":
		set_module_pref("hastrained",0);
		set_module_pref("hasbuffed",0);
		if (get_module_setting("healclear")) {
			if (get_module_pref("healpotion")) output("`n`^Your healing potion has turned a nasty shade of brown this morning, and you throw it away.`n");
			set_module_pref("healpotion",0);
		}
		if (get_module_pref("badtrolleffect")) {
			set_module_pref("badtrolleffect",0);
			apply_buff('trollache',array(
				"name"=>"`&Aching Muscles",
				"rounds"=>25,
				"wearoff"=>"You finally start to limber up.",
				"defmod"=>0.9,
				"roundmsg"=>"Your muscles hurt!", 
				"schema"=>"mysticden"
			));
			output("`n`qYour muscles ache from your exertion yesterday!`n");
		}
		break;
		case "dragonkilltext":
		if (get_module_setting("dkclear")) {
			set_module_pref("trollbrew",0);
			set_module_pref("healpotion",0);
		}
		break;
	   	case "fightnav-specialties":
		$script = $args['script'];
		if (get_module_pref("trollbrew") > 0) {
			addnav("`^Anyanka's Goods`0");
			addnav(array("`qTrollish Brew`7 (%s)`0", get_module_pref("trollbrew")), 
					$script."op=fight&skill=mysticden&l=trollbrew", true);
		}
		if (get_module_pref("healpotion") > 0) {
			addnav("`^Anyanka's Goods`0");
			addnav(array("`^Healing Potion`7 (%s)`0", get_module_pref("healpotion")),
					$script."op=fight&skill=mysticden&l=healpotion",true);
		}
		break;
		case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill=="mysticden"){
			switch($l){
				case "trollbrew":
				$session['user']['hitpoints']=($session['user']['maxhitpoints']*2);
				apply_buff('trollbrew',array(
					"startmsg"=>"`qYou enter a trollish rage!",
					"name"=>"`qTrollish Anger",
					"rounds"=>5,
					"wearoff"=>"Your mind clears.",
					"atkmod"=>1.1,
					"roundmsg"=>"Your rage fuels your attacks!", 
					"schema"=>"mysticden"
				));
				set_module_pref("badtrolleffect",1);
				set_module_pref("trollbrew",(get_module_pref("trollbrew")-1));
				break;
				case "healpotion":
				$session['user']['hitpoints']=$session['user']['maxhitpoints'];
				output("`^You quickly quaff down the healing potion, and you can feel your skin knit together again.`0");
				set_module_pref("healpotion",(get_module_pref("healpotion")-1));
				break;
			}
		}
		break;
		case "gypsy":
		$op=httpget("op");
		if ($op=="") {
			$rand=e_rand(3,18);
			if ((get_module_pref("skill","specialtymysticpower")>$rand)&&(get_module_pref("uses","specialtymysticpower")>0)) {
				addnav("Other");
				addnav("Scry the Forest (One Skill Use)","runmodule.php?module=mysticden&op=forestscry");
			}
		}
		break;
		case "footer-healer":
		$pct=httpget("pct");
		$loglev = log($session['user']['level']);
		$cost = ($loglev * ($session['user']['maxhitpoints']-$session['user']['hitpoints'])) + ($loglev*10);
		$cost = round($cost,0);
		$newcost=round($pct*$cost/100,0);
		$number=get_module_pref("number");
		$pct=($pct/10);
		if (($pct!="")&&($session['user']['gold']>=$newcost)) {
			if ($pct==$number) {
				output("`n`nAs you leave the hut, you hear some muttering behind you, and see a portal begin to form in the undergrowth to your right.");
				output("The vines and branches curl to either side, revealing a tunnel down lit by a murky green light.");
				output("Behind you, the healer seems to have resumed making its potions and ignoring you. Will you enter the tunnel?");
				addnav("Enter the Tunnel","runmodule.php?module=mysticden&op=entershop");
			} elseif ($number>0) {
				output("`n`nThe healer looks slightly disappointed as it turns back to it's potions.");
			}
			set_module_pref("number",0);
		}
		break;
	}
    return $args;
}

function mysticden_run() {
    global $session;
	$op = httpget('op');
	
	switch($op){
		case "forestscry":
		page_header("Scrying the Forest");
		set_module_pref("uses",(get_module_pref("uses","specialtymysticpower")-1),"specialtymysticpower");
		$rand=e_rand(1,10);
		output("`@Deciding that you don't need the gypsy's help to scry, you walk over to the bowl and use your own powers to look beyond, into the depths of the Forest....");
		output("`n`nYou gaze deep within the bowl, waiting for the dancing shadows to form an image, some place hidden, to reveal the secrets you desire...");
		switch($rand){
			case 1:
			case 2:
			case 3:
			case 4:
			$rand=e_rand(5,16);
			if (get_module_pref("skill","specialtymysticpower")<$rand) {
				output("`n`n`&After a while of staring with no results, the gypsy coughs noisily and tells you to try again later. Disappointed, you leave the hut.");
			} else {
				output("`n`n`&After a while of staring with no results, the water starts swirling and an image starts to form. You lean in closer, eager to learn more of the Forest.");
				$randtoo=e_rand(1,10);
				set_module_pref("number",$randtoo);
				if ($session['user']['hitpoints']>10) $session['user']['hitpoints']-=10;
				else $session['user']['hitpoints']=1;
				switch($randtoo){
					case 1:
					output("`n`n`!As the image clears, you can see yourself soaring over the forest, beyond the land to a small raft, floating from a strange island.");
					output("It lands on the shore, and a new farmboy is stranded, to start a new life....");
					break;
					case 2:
					output("`n`n`2As the image clears, you find yourself seeing parts of the depths of the forest, deep below the cover of the trees.");
					output("Patterns of light and shadow mesmerise you as they dance across the leaves.");
					break;
					case 3:
					output("`n`n`3As the image clears, you close in on a clearing where some trollish brothers argue over their spoils.");
					output("Their argument soon peters out, and they go their separate ways to find food in the forest.");
					break;
					case 4:
					output("`n`n`$As the image clears your point of view winds along a path, seemingly well travelled, to a lone dwarf seeking the route to take.");
					output("After consulting his map and compass he strides onwards, seemingly sure of his destination, though unaware of the shadows surrounding him....");
					break;
					case 5:
					output("`n`n`5As the image clears, you see a group of elves, searching through the woods for someone.");
					output("The leader examines the path closely for tracks, smelling the air and even tasting the dew for clues as to the direction.");
					output("Eventually one of them hears a lead, and they slip through the trees and away to follow their quarry.");
					break;
					case 6:
					output("`n`n`6As the image clears, the forest briefly appears before your point of view dives down a tunnel revealing a cavern swarming with snakes of all kinds.");
					output("At the center at the points of a snake-covered hexagon, some robed cultists chant dark rituals to unveil the future....");
					break;
					case 7:
					output("`n`n`7As the image clears, your view passes along a path to an adventurer talking to an old man.");
					output("After a long argument, the old man hands over a small sack of gold and curses the adventurer's luck before walking away, muttering to himself while the victor pockets the gold.");
					break;
					case 8:
					output("`n`n`QAs the image clears, you can see deep within the forest, where eight fairies frolic a cloud of sparkling dust.");
					output("As a foolish adventurer stumbles into their midst, they circle him, demanding that he pay them for his interruption.");
					output("Bewildered, he gives them a gem and is thoroughly dusted by them before they fly off, laughing.");
					break;
					case 9:
					output("`n`n`)As the image clears, your vision leads you to the edge of the forest, to the warrior training grounds, where a young warrior has stepped up against Ceiloth.");
					output("Though the fight is long and intense, the young warrior prevails over the man he used to call master, and is commended by the man he defeated.");
					break;
					case 10:
					output("`n`n`0As the image clears, your vision leads you to the edge of the forest, to the warrior training grounds, where a noble is faced against his final opponent, Yoresh.");
					output("With a deft leap to avoid the deadly touch, he brings his sword to Yoresh's throat to the applause of the watching crowd.");
					output("As Bluspring comes out to congratulate him, you can see that he is the best warrior he can be.");
					break;
				}
				output("`n`n`#The vision fades, and you hear a voice calling you before you feel a sharp pain in your mind. You stagger out of the hut, head swirling.");
			}
			villagenav();
			break;
			case 5:
			case 6:
			output("`n`n`#After some time, the water ripples and an image begins to form of the forest, flickering between glades before focusing on a battle inbetween the trees.");
			output("A warrior is in a fierce battle with a loathsome beast, the forest ringing with the sound of the blows of both combatants as they struggle for the upper hand.");
			output("With a mighty yell, the warrior forces the beast to the ground and beheads it with a swift blow. As he looks around, tired from the battle, your vision starts to fade and swirl....");
			output("`n`n`&The gypsy takes the scrying bowl away from you, muttering about having seen enough. As you leave the hut, you feel elated from the battle you winessed!");
			apply_buff('warriorvictory',array(
				"name"=>"`&Shared Victory",
				"rounds"=>5,
				"wearoff"=>"You forget why you were excited.",
				"atkmod"=>1.05,
				"roundmsg"=>"You feel elated!", 
				"schema"=>"mysticden"
			));
			villagenav();
			break;
			case 7:
			output("`n`n`#After some time, the water ripples and an image begins to form of the forest, flickering between glades before focusing on a battle inbetween the trees.");
			output("A warrior, badly injured, is fleeing from some unknown beast, the harsh cold slowing his movements and the breathing of the monster behind him closing in.");
			output("As you see him fall over a concealed tree branch and hit the ground, you are about to yell out in fear, but the vision fades and you return to the hut, shaken.");
			output("`n`n`&The gypsy takes the scrying bowl away from you, muttering about having seen enough. As you leave the hut, you feel horrified at what you have seen....");
			apply_buff('warriorloss',array(
				"name"=>"`&Shared Horror",
				"rounds"=>5,
				"wearoff"=>"You forget why you were scared.",
				"atkmod"=>0.95,
				"roundmsg"=>"You feel terrified!", 
				"schema"=>"mysticden"
			));
			villagenav();
			break;
			case 8:
			output("`n`n`#After some time, the water ripples and an image begins to form of the forest, gliding above the trees and soaring over hills and swamps.");
			output("You enjoy the feel of the air over you, and the sun glinting off you.... before a sudden hunger strikes you at the sight of a herd of deer in a clearing.");
			output("Flying low over them, you breathe fire and roast most of them before landing and tearing into their flesh... You tear yourself away from the scrying bowl at the sight and return to the hut, badly shaken.");
			output("`n`n`&The gypsy takes the scrying bowl away from you, muttering about having seen enough. As you leave the hut, you feel less ready to venture into the forest after seeing the Green Dragon...");
			if ($session['user']['turns']>0) $session['user']['turns']--;
			villagenav();
			break;
			case 9:
			output("`n`n`#After some time, the water ripples and an image begins to form of the forest, darting through the undergrowth following an unused path as it winds its way through the forest.");
			output("You begin to recognise some of the landmarks that it passes, and as the forest ends the vision begins to fade at the edge of your village.");
			output("`n`n`&The gypsy takes the scrying bowl away from you, muttering about having seen enough. As you leave the hut, you realise that you can take the path you saw to get to the depths of the forest faster! You gain a forest fight!");
			$session['user']['turns']++;
			villagenav();
			break;
			case 10:
			output("`n`n`#After some time, the water ripples and an image begins to form of a cave, deep in the forest, where smoke drifts slowly and the stench of burnt bodies fills the air.");
			output("A lone farmboy creeps into the cave, clutching his axe close as he searches for the Dragon. With a blast of fire, the dragon appears and a great battle ensues, magic and axe against fire and claw.");
			output("After a great struggle, the farmboy deals the Dragon a death blow and your vision of the scene begins to fade, just seeing the victor covered in blood triumphant.");
			output("`n`n`&The gypsy takes the scrying bowl away from you, muttering about having seen enough. As you leave the hut, you feel renewed by what you have seen!");
			$session['user']['hitpoints']=($session['user']['maxhitpoints']*1.1);
			villagenav();
			break;
		}
		break;
		case "entershop":
		page_header("Anyanka's Shop");
		$skill=get_module_pref("skill","specialtymysticpower");
		$traincost=get_module_setting("traincost")*$skill;
		$racecost=(get_module_setting("racebuffcost")*$session['user']['level']);
		$restorecost=get_module_setting("restoration");
		$loglev = log($session['user']['level']);
		$cost = ($loglev * ($session['user']['maxhitpoints']) + ($loglev*10));
		$result=modulehook("healmultiply",array("alterpct"=>1.0));
		$cost*=$result['alterpct'];
		$healcost = round($cost,0);
		$healcost=(get_module_setting("healpotioncost")*$healcost);
		$brewcost=get_module_setting("trollbrewcost");
		output("`2You cautiously walk down the tunnel formed of undergrowth and keep your hand on your %s`2 as it twists and turns, a light visible at the end.",$session['user']['weapon']);
		output("Rounding the final corner, you catch the scent of incense before you see a low chamber, lit by fairies flitting across the ceiling with curious aromas emanating from the herbs and rare ingredients in various stages of preparation along surfaces.");
		output("At one of these surfaces grinding something pungent with a wooden pestle and mortar is an elven druidess, who turns to look at you as you enter and motions to a small stool which you nervously sit on, waiting to discover the nature of the place you have stumbled into.");
		output("`n`nEventually she finishes preparing the ingredients and adds it to a flask standing nearby before carefully labelling it and racking it on a nearby branch and saying, \"`^Greetings traveller, I am Anyanka, welcome to my store.");
		output("As the items I sell are of a nature that could be dangerous to the balance, I seek that my customers be aware of the mystic forces in the world... but that is of no matter, you would not be here without some knowledge.");
		output("I can offer you training in the Art if you compensate me for my time, %s gold should be enough at your level, and I can cast spells to enhance your latent racial abilities or to cleanse you magically for %s gold and `%%s gems`^ respectively.",$traincost,$racecost,$restorecost);
		output("However, I see that you are an adventurer, and you'll probably need one of my healing potions if you get hurt today for %s gold, or a brew I get from the trolls if you're up against a real challenge.... expensive to get at `%%s gems`^ and be careful of the after effects if you use it.",$healcost,$brewcost);
		output("`2\" With that said, she turns back to making potions and waits for you to ask about her wares.");
		addnav("Shop Options");
		modulehook("mysticshop");
		if ((get_module_pref("hastrained")<=get_module_setting("traintimes"))AND($session['user']['gold']>$traincost)) addnav("Purchase Training","runmodule.php?module=mysticden&op=train");
		if (($session['user']['gold']>$racecost)&&(!get_module_pref("hasbuffed"))) addnav("Racial Enhancement","runmodule.php?module=mysticden&op=racebuff");
		if ($session['user']['gems']>$restorecost) addnav("Cleansing","runmodule.php?module=mysticden&op=confirmbuffkill");
		if ($session['user']['gold']>$healcost) addnav("Healing Potion","runmodule.php?module=mysticden&op=healpotion");
		if ($session['user']['gems']>$brewcost) addnav("Trollish Brew","runmodule.php?module=mysticden&op=trollbrew");
		villagenav();
		break;
		case "shop":
		page_header("Anyanka's Shop");
		$skill=get_module_pref("skill","specialtymysticpower");
		$traincost=get_module_setting("traincost")*$skill;
		$racecost=(get_module_setting("racebuffcost")*$session['user']['level']);
		$restorecost=get_module_setting("restoration");
		$loglev = log($session['user']['level']);
		$cost = ($loglev * ($session['user']['maxhitpoints']) + ($loglev*10));
		$result=modulehook("healmultiply",array("alterpct"=>1.0));
		$cost*=$result['alterpct'];
		$healcost = round($cost,0);
		$healcost=(get_module_setting("healpotioncost")*$healcost);
		$brewcost=get_module_setting("trollbrewcost");
		output("`2Since you finished talking with `^Anyanka`2, you take this opportunity to have another look around.");
		output("You are in a low chamber, the walls and furniture formed of plants and lit by fairies flitting across the ceiling while pungent odours from the rare potion ingredients greet your nose.");
		output("At a nearby surface sits `^Anyanka`2, the owner of this store, who is busily preparing a potion in one of the corners.");
		output("She would probably train you in the Mystic Arts for `^%s gold`2, or cast spells of cleansing and racial enhancement for `%%s gems`2 and `^%s gold`2 respectively.",$traincost,$restorecost,$racecost);
		output("You can see the potions she has made on one of the branches, and remember that it will cost `^%s gold`2 for a healing potion and `%%s gems`2 for a trollish brew.",$healcost,$brewcost);
		addnav("Shop Options");
		modulehook("mysticshop");
		if ((get_module_pref("hastrained")<=get_module_setting("traintimes"))AND($session['user']['gold']>$traincost)) addnav("Purchase Training","runmodule.php?module=mysticden&op=train");
		if (($session['user']['gold']>$racecost)&&(!get_module_pref("hasbuffed"))) addnav("Racial Enhancement","runmodule.php?module=mysticden&op=racebuff");
		if ($session['user']['gems']>$restorecost) addnav("Cleansing","runmodule.php?module=mysticden&op=confirmbuffkill");
		if ($session['user']['gold']>$healcost) addnav("Healing Potion","runmodule.php?module=mysticden&op=healpotion");
		if ($session['user']['gems']>$brewcost) addnav("Trollish Brew","runmodule.php?module=mysticden&op=trollbrew");
		villagenav();
		break;
		case "train":
		page_header("Anyanka's Shop");
		require_once("lib/increment_specialty.php");
		$oldskill=$session['user']['specialty'];
		$session['user']['specialty']="MA";
		$skill=get_module_pref("skill","specialtymysticpower");
		$cost=get_module_setting("traincost")*$skill;
		if (!$session['user']['gold']) {
			output("`^Anyanka `2looks at you curiously as you approach her, and you realise that your gold pouch is empty! How can you pay without gold?");
		} elseif ($session['user']['gold']<$cost) {
			output("`^Anyanka `2looks at you curiously as you approach her, and she glances pointedly at your gold pouch. You realise you don't have nearly enough for the training and look sheepish.");
		} else {
			output("`^Anyanka `2nods as you ask her for training, and takes the gold you offer before reaching down to take a book from a low shelf entitled \"");
			$session['user']['gold']-=$cost;
			debuglog("spent $cost gold training Mystic Arts.");
			if ($skill<8) {
				output("`@Nature and You`2\" and gives a short lecture from the book about the nature of the world around us and our place within it in regard to the powers you wield.");
				increment_specialty("`3");
				set_module_pref("hastrained",(get_module_pref("hastrained")+1));
				output("At the end of the lecture, you thank `^Anyanka`2 for the lesson and she smiles at you before putting away the book and resuming making potions.");
			} elseif ($skill>35) {
				output("`@Heirophantic Techniques of the Ages`2\" and you talk about your experiences in the forest and the world you have both experienced for a while as you both know the book.");
				output("You don't really learn anything from the talk, but it was fairly interesting so you thank `^Anyanka`2 for her time anyway.");
			} else {
				output("`@Heirophantic Techniques of the Ages`2\" and discusses a few chapters with you on meditative techniques and inner balance to enhance your abilities.");
				increment_specialty("`3");
				set_module_pref("hastrained",(get_module_pref("hastrained")+1));
				output("At the end of the lecture, you thank `^Anyanka`2 for the lesson and she smiles at you before putting away the book and resuming making potions.");
			}
		}
		$session['user']['specialty']=$oldskill;
		addnav("Look Around","runmodule.php?module=mysticden&op=shop");
		villagenav();
		break;
		case "healpotion":
		page_header("Anyanka's Shop");
		$loglev = log($session['user']['level']);
		$cost = ($loglev * ($session['user']['maxhitpoints']) + ($loglev*10));
		$result=modulehook("healmultiply",array("alterpct"=>1.0));
		$cost*=$result['alterpct'];
		$healcost = round($cost,0);
		$healcost=(get_module_setting("healpotioncost")*$healcost);
		if ($session['user']['gold']<$cost) {
			output("`^Anyanka `2looks at you curiously as you approach her, and you realise that you don't have enough gold to purchase a healing potion.");
			output("Perhaps you should buy something you can afford?");
		} else {
			output("`^Anyanka `2nods as you go to her asking for a healing potion, and hands a small flask to you when you produce the gold.");
			if (get_module_setting("healclear")) output("`n`n\"`^Don't forget that this potion will not last long... it is meant to be drunk straight away and will not last to the next day.`2\"");
			output("You pocket the flask and remember to use it in combat, when it matters most.");
			set_module_pref("healpotion",(get_module_pref("healpotion")+1));
			$session['user']['gold']-=$healcost;
			debuglog("spent $healcost gold buying a healing potion.");
		}
		addnav("Look Around","runmodule.php?module=mysticden&op=shop");
		villagenav();
		break;
		case "trollbrew":
		page_header("Anyanka's Shop");
		$cost=get_module_setting("trollbrewcost");
		if ($session['user']['gems']<$cost) {
			output("`^Anyanka `2looks at you curiously as you approach her, and you realise that you don't have enough gems to purchase the Trollish Brew.");
			output("Perhaps you should buy something you can afford?");
		} else {
			output("`^Anyanka `2nods as you go to her asking for a flask of Trollish Brew. \"`^Be warned, you may regret it a little the day after taking it....`2\".");
			output("`n`nYou nod at the warning and place the flask in your pouch, sure to be careful about when you use it.");
			set_module_pref("trollbrew",(get_module_pref("trollbrew")+1));
			$session['user']['gems']-=$cost;
			debuglog("spent $cost gems buying a flask of trollish brew.");
		}
		addnav("Look Around","runmodule.php?module=mysticden&op=shop");
		villagenav();
		break;
		case "racebuff":
		page_header("Anyanka's Shop");
		$race=$session['user']['race'];
		$racecost=(get_module_setting("racebuffcost")*$session['user']['level']);
		if ($session['user']['gold']<$racecost) {
			output("`^Anyanka `2looks at you curiously as you approach her, and you realise that you don't have enough gold to pay her for casting the spell.");
			output("Perhaps you should buy something you can afford?");
		} else {
			output("`^Anyanka `2nods as you go to her asking for her to use her powers to enhance your race's abilities. You hand over the gems, and stay calm as she begins to chant a spell.");
			output("After what seems like an eternity of chanting, a shimmering light envelops you, and you feel exalted!");
			set_module_pref("hasbuffed",1);
			switch($race){
				case "Dwarf":
				output("`n`nThe ground suddenly seems to become a part of you, steadying you, making your Dwarven heritage show itself, toughening your skin and hardening your bones!");
				$session['user']['hitpoints']=($session['user']['maxhitpoints']*1.5);
				break;
				case "Elf":
				output("`n`nThe plants surrounding you suddenly seem to be flowing through your blood... part of you, floating like a drifting leaf. You feel enriched with the Elven spirit and harder to hit!");
				apply_buff('enhanceelf',array(
					"name"=>"`@Elven Enhancement",
					"rounds"=>30,
					"wearoff"=>"Your body feels heavy again.",
					"defmod"=>1.15,
					"roundmsg"=>"You drift like a leaf, avoiding attacks!", 
					"schema"=>"mysticden"
				));
				break;
				case "Human":
				output("`n`nFrom within you, you feel a fire burning, your Human spirit set alight by the ritual. From within you can feel your flesh remoulding, adapting, healing....");
				apply_buff('enhancehuman',array(
					"name"=>"`\&Human Enhancement",
					"rounds"=>20,
					"wearoff"=>"Your spirit fades.",
					"regen"=>$session['user']['level'],
					"effectmsg"=>"You regenerate for {damage} health.",
					"effectnodmgmsg"=>"You have no wounds to regenerate.",
					"schema"=>"mysticden"
				));
				break;
				case "Troll":
				output("`n`nSurrounded by nature, you feel your bestial, Trollish nature rise to the fore, stirring your blood, and burning along your muscles!");
				apply_buff('enhancetroll',array(
					"name"=>"`\$Trollish Enhancement",
					"rounds"=>30,
					"wearoff"=>"Your blood cools.",
					"atkmod"=>1.15,
					"roundmsg"=>"Your blood burns, and you attack with fury!", 
					"schema"=>"mysticden"
				));
				break;
				default:
				output("`n`nThe energy from ancient %s spirits rises through you, and you feel filled with potential for action!",$session['user']['race']);
				$session['user']['turns']++;
				break;
			}
			debuglog("spent $racecost gold getting their racial abilities enhanced.");
			$session['user']['gold']-=$racecost;
		}
		addnav("Look Around","runmodule.php?module=mysticden&op=shop");
		villagenav();
		break;
		case "confirmbuffkill":
		page_header("Anyanka's Shop");
		$cost=get_module_setting("restoration");
		if ($session['user']['gems']<$cost) {
			output("`^Anyanka `2looks at you curiously as you approach her, and you realise that you don't have enough gems to purchase Restoration.");
			output("Perhaps you should buy something you can afford?");
		} else {
			output("`^Anyanka `2nods as you go to her asking to be restored. \"`^Be warned, this will cleanse you of all enchantments, good and bad... do you still wish to go ahead?`2\".");
			output("`n`nYou nod at the warning and think about the enchantments you are currently under....");
			addnav("Be Restored","runmodule.php?module=mysticden&op=buffkill");
		}
		addnav("Look Around","runmodule.php?module=mysticden&op=shop");
		villagenav();
		break;
		case "buffkill":
		page_header("Anyanka's Shop");
		$cost=get_module_setting("restoration");
		if ($session['user']['gems']<$cost) {
			output("`^Anyanka `2looks at you curiously as you approach her, and you realise that you don't have enough gems to purchase Restoration.");
			output("Perhaps you should buy something you can afford?");
		} else {
			output("`^Anyanka `2nods as you go to her asking to be restored. \"`^Very well, you shall be cleansed and restored.`2\".");
			output("`n`nYou shiver as she sprinkes water over you, chanting a ritual that leaves you feeling cold, but clean.");
			output("You hand over the gems and shake yourself as you begin to warm up again.");
			require_once("lib/buffs.php");
			$session['user']['hitpoints']=$session['user']['maxhitpoints'];
			if (is_module_active("darkden")) {
				set_module_pref("hasbeencursed",0,"darkden");
				set_module_pref("darkdeal",0,"darkden");
				set_module_pref("darkness",0,"darkden");
				set_module_pref("redelixirs",0,"darkden");
			}
			if (is_module_active("thiefden")) {
				set_module_pref("pvpdrugs",0,"thiefden");
			}
			strip_all_buffs();
			$session['user']['gems']-=$cost;
			debuglog("spent $cost gems being restored.");
		}
		addnav("Look Around","runmodule.php?module=mysticden&op=shop");
		villagenav();
		break;
		default:
		page_header("How did you get here?");
		output("`@In a shower of sparks and cheap-looking special effects Sneakabout appears before you in a somwehat tatty wizards robe.");
		output("\"`^What are you doing here? Don't you know that you're meant to be shopping at Anyanka's store? Stop lazing around!`@\"");
		output("With that he disappears back to wherever he came from with a wave of his cloak, obviously through a trapdoor pathetically concealed in the floor.");
		output("`n`nThere is nothing interesting here. Why not go back to the village?");
		addnav("Return to the Village","village.php");
		break;
	}
	page_footer();
}
?>