<?php

function gewtom_getmoduleinfo(){
	$info = array(
		"name"=>"Get even with the old man",
		"version"=>"1.0",
		"author"=>"Damien",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/users/Damien",
		"settings"=>array(
			"Get even with the old man Settings, title",
			"bribecost"=>"Price of the bribe,int|200",
		),
		"prefs"=>array(
			"Get even with the old man User Preferences, title",
			"hasbeaten"=>"Player has beaten the old man,bool|0",
			"hasaccused"=>"Player has accused the old man,bool|0",
			"hasplayed"=>"Player has played the dice game,bool|0",
			"losses"=>"Amount of gold a player has lost to the old man,int|0",
			"lastlose"=>"Amount of gold a player lost last time to the old man,int|0",
		),
	);
	return $info;
}

function gewtom_install(){
	if(!is_module_installed("game_dice") and !is_module_installed("darkhorse")){
	   output("`^This module requires `4game_dice `^and `4darkhorse`^ modules to be installed!");
	   return false;
	}
	module_addhook("header-forest");
	module_addhook("footer-forest");
	module_addhook("header-runmodule");
	module_addhook("footer-runmodule");
	module_addhook("newday");
	module_addeventhook("forest", "return 100;");
	return true;
}

function gewtom_uninstall(){
	return true;
}

function gewtom_dohook($hookname, $args){
	global $session;
	switch ($hookname){
	case "header-forest":
	     if(httpget("op")=="oldman"){
	        if(get_module_pref("hasbeaten")==1 or get_module_pref("hasaccused")==1){
	           httpset("op","oldmanishurt");
	           addnav("Return to the Main Room","forest.php?op=tavern");
		}
	     }
	break;
	
	case "footer-forest":
	     if(httpget("op")=="oldmanishurt"){
	        if(get_module_pref("hasbeaten")==1){
		   output("The old man is still angry with you because of the beating. He refuses to make any kind of business with you.`n");
		   output("Maybe you should try again another day...`n");
		}
		else{
	           if(get_module_pref("hasaccused")==1){
		      output("The old man can't believe that you're even trying to talk with him after those cheating accuses you made him earlier in the forest!`n");
		      output("Maybe you should try again another day...`n");
		   }
		}
	     }
	break;
	
	case "header-runmodule":
	     if(httpget("module")=="game_dice" and httpget("bet")>0 and httpget("what")=="keep"){
		set_module_pref("lastlose",$session['user']['gold']);
		set_module_pref("hasplayed",1);
	     }
	break;
	
	case "footer-runmodule":

	     if(httpget("module")=="game_dice" and httpget("bet")>0 and httpget("what")=="keep"){
                $losses = get_module_pref("losses");
                $lastlose = get_module_pref("lastlose")-$session['user']['gold'];
		if($lastlose>0){
		   set_module_pref("lastlose",$lastlose);
		   set_module_pref("losses",($losses+$lastlose));
		}
	     }
	break;

	case "newday":
	     set_module_pref("hasbeaten",0);
	     set_module_pref("hasaccused",0);
	break;
 	}
	return $args;
}

function gewtom_runevent(){
	page_header("The old man");
	global $session;

	if(get_module_pref("hasplayed")==1){
        if(get_module_pref("hasbeaten")==0 and get_module_pref("hasaccused")==0){
	addnav("Examine his stuff","runmodule.php?module=gewtom&op=examine");
	addnav("Let him be","forest.php");
	output("You're walking on a path in the forest when suddenly some one crashes straigt on to you. After the first confusion you see the old man from Dark Horse Tavern mumbling and collection his stuff. ");
	output("As a honest and kind person you try to help him by collecting his stuff too but you find something you definitely don't like.");
	output("The old man was carrying a pack of marked playing cards. You become more suspicious when you realize that you've been gambling with him too and he has all this material for cheating.`n");
	output("You don't want to make quick conclusions so you should try to find more evidence of his cheating...");
	}
	else{
	   if(get_module_pref("hasbeaten")==1){
	      $gold = e_rand(1,100);
	      output("You run into the old man again! He's so afraid of you after the beating you did him last time that he gives you %s gold and runs away!`n",$gold);
	      addnav("Return to the forest","forest.php");
	      $session['user']['gold']+=$gold;
	   }
	   else{
	      if(get_module_pref("hasaccused")==1){
	         output("You run into the old man again! He's still angry with you after you called him a cheater. This is a good opportunity for you to calm down him.`n");
                 addnav("Give him ".get_module_setting("bribecost")." gold","runmodule.php?module=gewtom&op=bribe");
                 addnav("Nah, Forget it","forest.php");
	      }
	   }
	}
	}
	else{
	    output("You're walking on a path in the forest when you see the old man from Dark Horse Tavern. He's having a break and sitting under a huge oak. ");
	    output("You've heard a lot of gossip about him and his cheating in a gambling but that's not really your business anyway because you don't even gamble. Maybe you should start playing...`n");
	    if(e_rand(1,5)<3){
	       output("`nBeing so neutral makes you look better. You gain 1 charm!`n");
	       $session['user']['charm']++;
	    }
	    else{
	       output("`nBeing so neutral makes you feel a better person. You gain 1 forest fight!`n");
	       $session['user']['turns']++;
	    }
	    addnav("Return to the forest","forest.php");
	}
	page_footer();
}

function gewtom_run(){
        global $session;
	$bribecost = get_module_setting("bribecost");
	switch(httpget('op')){
	case "examine":
             page_header("Examine the old man's stuff");
	     if(e_rand(1,5)<3){
                output("You find also balanced stones and some other cheatin stuff! The old man tries to make up some hasty excuses, but that doesn't convince you at all!`n");
		addnav("Give him a lesson","runmodule.php?module=gewtom&op=beat");
             }
             else{
                output("You didn't find anything unusual from his stuff and actually that mark in the card is just a piece of mud.`n");
		output("The old man is very insulted of your accusation of cheating. He swears that he'll remember you in the future!");
		addnav("Try to bribe him with ".$bribecost." gold","runmodule.php?module=gewtom&op=bribe");
                set_module_pref("hasaccused",1);
	     }

	     addnav("Return to the forest","forest.php");
	break;

	case "beat":
	     $losses = get_module_pref("losses");
	     page_header("Give the old man a lesson");
	     output("You decide to give the old man a lesson what happens if someone is cheating you.!`n");
	     output("After a few minutes beating the old man cries for mercy and you let him go.");
	     output("The old man runs away.");
	     if($losses>0){
	        output("He drops a bag near to you. You find %s gold!. At least you got some of your losses back.`n",round(($losses*=(e_rand(1,50)/100))));
                $session['user']['gold']+=$losses;
                set_module_pref("losses",0);
	     }
	     set_module_pref("hasbeaten",1);
	     addnav("Return to the forest","forest.php");
	break;

	case "bribe":
             page_header("Try to bribe the old man");
	     if(e_rand(1,5)<3){
                if($session['user']['gold']>=$bribecost){
		   output("You promise to give him %s gold if he forgets this whole situation. The old man smiles understanding, tooks the gold and wishes a good luck in the games in the future.`n", $bribecost);
		   output("Somehow you aren't so convinced about his innocence.`n");
		   $session['user']['gold']-=$bribecost;
		   set_module_pref("hasaccused",0);
		}
		else{
		   output("The old man accepts your offer of %s gold and everything seems to work out. When you're trying pay him %s gold you notice that you don't even have that much!`n",$bribecost,$bribecost);
		   output("The old man is more angry than ever and leaves immediately.`n");
		   if($session['user']['gold']>0){
		      output("You're in shock and the old man took advantage of it and took the rest of your gold with him. You lose %s gold.`n",$session['user']['gold']);
                      $session['user']['gold']=0;
		   }
		}
             }
             else
                 output("He won't accept your gold and reminds that you shouldn't make such accuses so lightly. He's still angry with you and leaves the place.`n");
	     addnav("Return to the forest","forest.php");
	break;
	}

	page_footer();
}
?>
