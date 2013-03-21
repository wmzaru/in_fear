<?php

function tomeofdestruction_getmoduleinfo(){
	$info = array(
		"name"=>"Tome of Destruction",
		"version"=>"1.0",
		"author"=>"shadowblack",
		"category"=>"Library Specials",
		"download"=>"",
		"description"=>"Just a small Library special",
    "requires"=>array("library"=>"2.73|Chris Vorndran<br>Original Idea: `QCleodelia<br>`#modifications by `!shadowblack`0, http://dragonprime.net/users/Sichae/librarypack.zip",
    ),
	);
	return $info;
}

function tomeofdestruction_install(){
	module_addeventhook("library", "return 100;");
	return true;
}

function tomeofdestruction_uninstall(){
	return true;
}

function tomeofdestruction_dohook($hookname,$args){
	return $args;
}

function tomeofdestruction_runevent($type){
	global $session;
	$session['user']['specialinc'] = "module:tomeofdestruction";

  output("As you walk between the sheves looking at the books you notice one that immediately catches your attention.");
  output("You quickly go to where the book is, and take it off the shelf.");
  output("The book is heavy, but you barely notice the weight as you read the title in a trance-like state.");
  output("\"Tome of... destruction?\" It takes a few seconds to comprehend what you have just read, and when you finally do it's already too late...");
  output("As soon as you read the book's title your hands start moving on their own, without any conscious command from you.");
  output("When the meaning of the three words written on the book's cover finally reaches your brain a feeling of dread passes through your body.");
  output("You finally realize what you are doing and stop turning over the pages. Then you see what is on the page you have reached...`n`n");
  $tome = e_rand(1,10);
  Switch ($tome){

  Case 1:

  output("You see the drawing of a dagger. However this one is so good that the dagger looks almost real,");
  output("almost as if you can take it in your hand...");
  output("Suddenly there is a flash, and then the dagger is floating in the air before you! Before you can react it stabs you in the chest!");
  output("The pain is so intense that you loose consciousness for a bit. When you wake up there is no sign of the book or the dagger.");
  output("You are left with the wound as a reminder of what happened. It is quite serious, but not deadly.");
  output("`n`nYou loose most of your hit points!");
  $session['user']['hitpoints'] = ceil($session['user']['hitpoints']*0.4);
  debuglog("Lost most hit points to Tome of Destruction!");
  break;

  Case 2:

  output("You see what looks like a drawing of a forest glade.");
  output("You stare at it... and stare... and stare... and stare... until you realize you are staring at your empty hands!");
  output("The book is nowhere to be seen! Worse, hours have passed with you standing there doing nothing!");
  $ffloss = e_rand(1,5);
  $session['user']['turns'] -= $ffloss;
  if ($ffloss==1){
  output("You have lost the time for a forest fight.");
  }else{
  output("You have lost the time for %s forest fights.",$ffloss);
  }
  debuglog("Lost $ffloss forest fights to Tome of Destruction.");
  break;

  Case 3:

  output("You have reached two pages with text written in a language you do not recognize.");
  output("On each page, above the text, is the drawing of a wraith.");
  output("As you try to figure out what the text could mean a ghostly hand reaches out of the book and grabs you by the throat!");
  output("You feel the hand draining your life enrgy! You quickly throw the book away and jump back.");
  output("Both the book and the hand vanish without a trace, which is good.");
  output("What is good is the fact that the ghostly hand drained some of your experience...");
  $exploss = round($session['user']['experience']*0.05);
  $session['user']['experience'] -= $exploss;
  output("`n`n You have lost %s experience!",$exploss);
  debuglog("Lost $exploss experience to Tome of Destruction.");
  break;

  Case 4:

  output("The picture of a rusty old sword is there, and it looks almost real.");
  output("As you look at it the picture emits a dark brown light that completely envelops you for a few seconds.");
  output("Then the book and light vanish without a trace... but your %s keeps glowing in the same dark brown light!",$session['user']['weapon']);
  output("You realize it's been cursed!");
  apply_buff('weaponcurse',
  	array(
			"name"=>"`qWeapon Curse`0",
			"rounds"=>40,
			"wearoff"=>"Your {weapon} is no longer cursed.",
			"atkmod"=>0.8,
      "roundmsg"=>"Due to the curse your {weapon} strikes with less force than normal.",
			"schema"=>"module-tomeofdestruction"
			)
		);
  break;

  Case 5:

  output("The picture of a rusty old armorplate is there, and it looks almost real.");
  output("As you look at it the picture emits a dark brown light that completely envelops you for a few seconds.");
  output("Then the book and light vanish without a trace... but your %s keeps glowing in the same dark brown light!",$session['user']['armor']);
  output("You realize it's been cursed!");
  apply_buff('armorcurse',
  	array(
			"name"=>"`qArmor Curse`0",
			"rounds"=>40,
			"wearoff"=>"Your {armor} is no longer cursed.",
			"defmod"=>0.8,
      "roundmsg"=>"Due to the curse your {armor} does not protect you so well...",
			"schema"=>"module-tomeofdestruction"
			)
		);
  break;

  Case 6:

  output("On the page you see the drawing of a face very much like your own, but with a nasty ugly scar on one cheek.");
  output("Suddenly the scar emits a bright red glow, and an identical scar is burned into `iyour`i face...");
  output("You scream from surprise and drop the book. It vanishes into the air... along with some of your charm.");
  output("You are left with the unpleasant memory and an ugly scar...`n`n");
  $charmloss = e_rand(1,5);
  $session['user']['charm'] -= $charmloss;
  output("You loose %s Charm.",$charmloss);
  debuglog("Lost $charmloss charm to Tome of Destruction.");
  break;

  Case 7:

  output("On the page you can see a well-drawn picture of a heap of gold and gems.");
  output("Suddenly there's a flash of light, and then the book is gone!");
  output("After a quick check you discover that all of your gold is gone as well! As is one of your prescious gems!");
  $gold = $session['user']['gold'];
  $session['user']['gold']=0;
  $session['user']['gems']--;
  debuglog("Lost $gold gold and a gem to Tome of Destruction.");
  break;

  Case 8:

  output("The picture of a black sword is drawn on the page. It looks almost real...");
  output("Suddenly there's a flash of black light and the book is replaced by the same black sword you just saw!");
  output("It strikes at you and you are unable to react on time.");
  output("It sinks into your flesh, sending a wave of pain through your bosy.");
  output("Then the black sword vanishes without a trace, leaving you with a shallow, but constantly bleeding, wound.");
  apply_buff('bleed',
  	array(
			"name"=>"`\$Bleeding`0",
			"rounds"=>20,
			"wearoff"=>"The bleeding stops at last.",
      "minioncount"=>1,
      "mingoodguydamage"=>"<level>",
      "maxgoodguydamage"=>"<level>",
      "effectmsg"=>"Due to your bleeding wound you loose {damage} hit points.",
			"schema"=>"module-tomeofdestruction"
			)
		);
  break;

  Case 9:

  output("Lines of text written in a language you do not recognize are on the page you have reached. There's nothing else.");
  output("As you wonder what language might that be an invisible force lifts you in the air and slams you at the floor... hard.");
  output("You just lie down for a few minutes, recovering. While you are not really hurt you are stunned and will be unable to fight at your best for some time.");
  output("As you get up you realize that the book you found has vanished...");
  apply_buff('stun',
  	array(
			"name"=>"`%Stun`0",
			"rounds"=>20,
			"wearoff"=>"You are no longer stunned.",
			"atkmod"=>0.8,
			"defmod"=>0.8,
      "roundmsg"=>"`%You are stunned and cannot fight at your best.",
			"schema"=>"module-tomeofdestruction"
			)
		);
  break;

  Case 10:

  output("The picture of a Fireball is drawn on the page, with several lines of text underneath.");
  output("As you look at the text it glows fiery red, then something hits you in the back and fire engulfs you!");
  output("You scream in pain as the fire burns your flesh! Then it ends as suddenly as it began.");
  output("The whole accident has ended in just a few seconds, but to you they feel morelike days.");
  output("As you look at your burned hands you realize they are empty! The book has vanished!");
  output("You soon discover that the book is not the only missing thing. Your gold is gone as well, as is one of your gems!");
  output("As a result of the multiple burns you loose most of your hit points, but at least you are not dead.");
  output("You are left with a couple of nasty burns, and your Charm has suffered as a result. You loose 2 Charm.");
  output("You also loose the time for 2 forest fights while you recover, and you have been stunned, so for a short time you won't be at your best during combat.");
  apply_buff('stun',
  	array(
			"name"=>"`%Stun`0",
			"rounds"=>10,
			"wearoff"=>"You are no longer stunned.",
			"atkmod"=>0.8,
			"defmod"=>0.8,
      "roundmsg"=>"`%You are stunned and cannot fight at your best.",
			"schema"=>"module-tomeofdestruction"
			)
		);
	$gold = $session['user']['gold'];
  $session['user']['gold']=0;
  $session['user']['gems']--;
  $session['user']['charm'] -= 2;
  $session['user']['turns'] -= 2;
  $session['user']['hitpoints'] = ceil($session['user']['hitpoints']*0.2);
  debuglog("Lost $gold gold, 2 Charm, 2 forest fights, 80% of current hitpoints and a gem to Tome of Destruction.");
  break;
  }
  $session['user']['specialinc'] = "";
}

function tomeofdestruction_run(){
}

?>
