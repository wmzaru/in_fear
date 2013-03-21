<?php
/*
Hikaru's Literature
File:	hiklit.php
Author:	Chris Vorndran (Sichae)
Date:	9/15/2004
Version:1.4 (10/2/2004)

Hikaru is actually the playing name of my best friend. I made this as a gift to her.
She thoroughly enjoyed it. She used to play, but she left.
With the issue of 9.8, she has returned to playing.

v1.1
Text Fixes

v1.2
I added some new options. Expanded the store to include Books.
New books have double effects, IE Gain one Attack, lose 3 HP/ Gain one Defense, lose 3 HP

v1.3
Grammar and Text fixes

v1.4 
Allowing Admins to change the books used in the book section. Hope ya like ^_^
Made Fully Transalatable

v1.5
Textual Fixes, made more Transalation compatable.

v2.0
Complete Overhaul
*/
function hiklit_getmoduleinfo(){
	$info = array(
		"name"=>"Hikaru's Literature",
		"author"=>"Chris Vorndran",
		"version"=>"2.22",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=23",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"User can enter and read comics or books, in order to be booned. Books and Comics are customizable.",
		"settings"=>array(
			"Hikaru's Literature Settings,title",
			"litallowed"=>"Times players are allowed to get Literature per day,range,1,3,1|2",
			"exp"=>"Multiplier of current experience to evaluate gained,floatrange,0,1,.01|.02",
			"Comic One Criteria,title",
			"com1"=>"Title of the First Comic,text|Angelic Layer",
			"comdesc1"=>"Description of First Comic,textarea|A Manga about a young girl's struggle in growing up, as she finds a new fad.",
			"Comic Two Criteria,title",
			"com2"=>"Title of the Second Comic,text|Green Arrow",
			"comdesc2"=>"Description of Second Comic,textarea|A Comic about a unorthodox Superhero, and his misadventures.",
			"Comic Three Criteria,title",
			"com3"=>"Title of Third Comic,text|Calvin and Hobbes",
			"comdesc3"=>"Description of Third Comic,textarea|The tale of a small child, and his adventure with his good buddy Hobbes, who is a tiger.",
     	    "Book One Criteria,title",
				"bookonetitle"=>"Title of First Book,text|Harry Potter and the Goblet of Fire",
				"bookoneauthor"=>"Author of First Book,text|JK Rowling",
				"bookonedesc"=>"Description of First Book,textarea|A child by the name of Harry Potter, is asked to stay with his friend. Along with the invitation to stay, is an offer to go to the World Cup of the Wizarding Game Quidditch. While at the world cup, the camps are attacked. The Wizarding World is thrown into a frenzy, as they believe it is the return of the greatest Dark Wizard of all time. Harry Potter is thrown into a tournament as well, while in school. No one knows why he was put in the tournament, until he finds out the truth...",
			"Book Two Criteria,title",
				"booktwotitle"=>"Title of Second Book,text|Lord of the Rings: The Fellowship of the Ring",
				"booktwoauthor"=>"Author of Second Book,text|JRR Tolkien",
				"booktwodesc"=>"Description of Second Book,textarea|You read a fascinating tale of 4 halflings, and their perilous journey to save their Shire. Eventually this band of 4 makes it to a grand Elven city, where the Fellowship of the Ring is created. Joined by Gandalf, Aragorn, Boramir, Gimli and Legolas; they set out from Rivendell. On this next leg of the journey, they lose some of their members.",
			"Book Three Criteria,title",
				"charm"=>"After reading Charm is increased by,int|5",
				"bookthreetitle"=>"Title of Third Book,text|Kushiel's Dart",
				"bookthreeauthor"=>"Author of Third Book,text|Jacqueline Carey",
				"bookthreedesc"=>"Description of Third Book,textarea|Phèdre nó Delaunay is a young woman who was born with a scarlet mote in her left eye. Sold into indentured servitude as a child, her bond is purchased by Anafiel Delaunay, a nobleman with very a special mission...and the first one to recognize who and what she is: one pricked by Kushiel's Dart, chosen to forever experience pain and pleasure as one.",
			"Hikaru Location,title",
			"mindk"=>"What is the minimum DK before this shop will appear to a user?,int|0",
			"comicloc"=>"Where does Hikaru appear,location|".getsetting("villagename", LOCATION_FIELDS)
			),
		"prefs"=>array(
			"General Prefs,title",
				"readtoday"=>"How many has player read today,int|0",
				"atkinc"=>"Has player gained their attack bonus,bool|0",
				"definc"=>"Has player gained their defense bonus,bool|0",
				"charminc"=>"Has player gained their charm bonus,bool|0",
			"Daily Stipulations,title",
				"readone"=>"Has player read the first book today?,bool|0",
				"readtwo"=>"Has player read the second book today?,bool|0",
				"readthree"=>"Has player read the third book today?,bool|0",
			)
		);
	return $info;
}
function hiklit_install(){
	module_addhook("changesetting");
	module_addhook("village");
	module_addhook("newday");
	module_addhook("dragonkilltext");
	return true;
}
function hiklit_uninstall(){
	return true;
}
function hiklit_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "changesetting":
			if ($args['setting'] == "villagename"){
				if ($args['old'] == get_module_setting("comicloc")){
					set_module_setting("comicloc", $args['new']);
				}
			}
			break;
		case "village":
			if ($session['user']['location'] == get_module_setting("comicloc")
			&& $session['user']['dragonkills'] >= get_module_setting("mindk")){
				tlschema($args['schemas']['marketnav']);
				addnav($args['marketnav']);
				tlschema();
				addnav("Hikaru's Literature","runmodule.php?module=hiklit&op=enter");
			}
			break;
		case "newday":
			set_module_pref("readtoday",0);
			set_module_pref("readone",0);
			set_module_pref("readtwo",0);
			set_module_pref("readthree",0);
			break;
		case "dragonkilltext":
			set_module_pref("atkinc",0);
			set_module_pref("definc",0);
			set_module_pref("charminc",0);
			break;
		}
	return $args;
}
function hiklit_run(){
	global $session;

	$readtoday = get_module_pref("readtoday");
    $litallowed = get_module_setting("litallowed");

    $bookonetitle = get_module_setting("bookonetitle");
    $bookoneauthor = get_module_setting("bookoneauthor");
    $bookonedesc = get_module_setting("bookonedesc");

	$com1 = get_module_setting("com1");
    $comdesc1 = get_module_setting("comdesc1");

	$com2 = get_module_setting("com2");
    $comdesc2 = get_module_setting("comdesc2");

	$com3 = get_module_setting("com3");
    $comdesc3 = get_module_setting("comdesc3");

    $booktwotitle = get_module_setting("booktwotitle");
    $booktwoauthor = get_module_setting("booktwoauthor");
    $booktwodesc = get_module_setting("booktwodesc");
    
	$charm = get_module_setting("charm");
    $bookthreetitle = get_module_setting("bookthreetitle");
    $bookthreeauthor = get_module_setting("bookthreeauthor");
    $bookthreedesc = get_module_setting("bookthreedesc");

    $atkinc = get_module_pref("atkinc");
    $definc = get_module_pref("definc");
    $charminc = get_module_pref("charminc");

	$op = httpget('op');
	page_header("Hikaru's Literature");
	
	switch ($op){
		case "enter":
			if ($readtoday < $litallowed){
				output("`3You walk into a medium sized shop, in the corner of town.");
				output(" You glance around and see two doors.");
		        output(" One labeled \"`2Comics`3\" and the other labeled \"`2Books`3\".`n`n");
		        output("As the door closes, it dings a little bell.");
		        output(" A short human girl comes out.");
		        output(" She smiles brightly, and looks at you through hard frames.`n`n");
		        output("`3After looking around the shop, she walks over to you.");
		        output(" She opens her mouth and speaks, \"`\$Hello there. Might I interest you in something today?`3\"");
			    addnav("Shelves");
		        addnav("Look at the Comics","runmodule.php?module=hiklit&op=comics");
		        if ($atkinc == 0 || $definc == 0 || $charminc == 0){
					if ($readtoday < 1){
						addnav("Look at the Books","runmodule.php?module=hiklit&op=books");
					}else{
						output("`n`n\"`\$Books take a long time to read, so once you read anything else, won't be able to view any books.`3\"");
					}
				}else{
					output("`n`nYou can see that Hikaru is boarding up the door for \"Books\".");
					output(" She looks back at you and says, \"`\$The books have been sent out.");
					output(" Only upon the time that we can get `@Dragon's Blood`\$ to douse them in, we are unable to read them.`3\"");
				}
		   }else{
		        output("`3\"`\$I'm so sorry, but you aren't welcome here anymore.");
				output(" Try again tomorrow.`3\"");
			    }
				blocknav("runmodule.php?module=hiklit&op=enter");
				break;
		case "comics":
				output("`3Hikaru smiles at you and guides you into a vast room, stocked with Comics.");
                output(" Hikaru looks at you in utter delight, as she guides you in.");
	            output(" \"`\$So, what would you like to read today?`3\"`n`n");
				output("Your eyes dart around, crawling comic to comic.");
	            output(" So many to choose from.");
  		        output(" You see 3 distinct titles.");
			    output(" %s, %s `3and %s`3.`n`n",$com1,$com2,$com3);
	            output("\"`\$So, which would you like?`3\"`n`n");
				addnav("Comics");
		        if (!get_module_pref("readone")) addnav(array("%s",$com1),"runmodule.php?module=hiklit&op=com1");
			    if (!get_module_pref("readtwo")) addnav(array("%s",$com2),"runmodule.php?module=hiklit&op=com2");
	            if (!get_module_pref("readthree")) addnav(array("%s",$com3),"runmodule.php?module=hiklit&op=com3");
				if (get_module_pref("readone")) output("`3You don't wish to read `^%s `3again, as all comics are boring a second time through.",$com1);
				if (get_module_pref("readtwo")) output("`3You don't wish to read `#%s `3again, as you have already read it before.",$com2);
				if (get_module_pref("readthree")) output("`3You don't wish to read `@%s `3again, because you are just `ithat`i lazy.",$com3);
				break;
		case "com1":
				$t = e_rand(1,5);
				output("`3Hikaru hands a small comic to you, that is titled, \"%s`3\".",$com1);
				output("You turn it over and begin to read...");
				output("`n`n%s`n`n",$comdesc1);
				output("`3You sit down in a bean bag chair, and begin to read the wonderful comic.");
				output(" You finally finish it, and look around, seeing that it has grown dark...");
				output(" Strangely, you feel like fighting at this hour.");
				output(" You gain `@%s `3%s.",$t,translate_inline($t==1?"turn":"turns"));
                $session['user']['turns']+=$t;
                $readtoday++;
                set_module_pref("readtoday",$readtoday);
				set_module_pref("readone",1);
				break;
		case "com2":
				$exp = get_module_setting("exp");
				$expgain = round($session['user']['experience']*$exp);
				debug("EXPERIENCE: ".$expgain);
				output("`3Hikaru hands you a large comic, titled, \"%s`3\".",$com2);
				output("You heave it over, reading silently...");
				output("`n`n%s`n`n",$comdesc2);
				output("You walk over and sit upon a couch, thumbing through the pages.");
				output(" Whilst looking through the pages, you see a tiny scroll.");
				output("You unravel the scroll, and begin to unlock many secrets.");
				output("You have gained `^%s `3experience.",$expgain);
				$session['user']['experience']+=$expgain;
				$readtoday++;
				set_module_pref("readtoday",$readtoday);
				set_module_pref("readtwo",1);
				break;
		case "com3":
				$hpgain = e_rand(10,50);
				output("`3Hikaru strides over, handing you a really small comic.");
				output(" This comic is titled, \"%s`3\".",$com3);
				output("You effortlessly turn it over and begin to read...");
				output("`n`n%s`n`n",$comdesc3);
				output("`3You yawn while reading it, and slowly slip into a slumber.");
				output(" You awake several hours later, feeling quite refreshed.");
				output(" You gained `\$%s `3hitpoints.",$hpgain);
				$session['user']['hitpoints']+=$hpgain;
				$readtoday++;
				set_module_pref("readtoday",$readtoday);
				set_module_pref("readthree",1);
				break;
		case "books":
				output("`3Hikaru takes you hand, and walks you down a grand hall.");
				output(" Upon the walls, you see the portraits of many great authors.");
				output(" Hikaru points towards a glass case.");
				output(" Inside of the glass case, you see three books.`n`n");
				if ($atkinc) output("You don't wish to read `^%s `3again, as all books are boring a second time through.",$bookonetitle);
				if ($definc) output("You don't wish to read `#%s `3again, as you have already read it before.",$booktwotitle);
				if ($charminc) output("You don't wish to read `@%s `3again, because you are just `ithat`i lazy.",$bookthreetitle);
				if (!$atkinc) addnav(array("%s by %s",$bookonetitle,$bookoneauthor),"runmodule.php?module=hiklit&op=book1");
		        if (!$definc) addnav(array("%s by %s",$booktwotitle,$booktwoauthor),"runmodule.php?module=hiklit&op=book2");
				if (!$charminc) addnav(array("%s by %s",$bookthreetitle,$bookthreeauthor),"runmodule.php?module=hiklit&op=book3");
				break;
		case "book1":
				output("`3You point towards the book that is titled, \"%s`3\".",$bookonetitle);
				output(" She retrieves the book for you, and you grasp it, walking over to a table.");
				output(" You sit down, and begin to read the lengthy novel.");
				output(" You finish with it, and a small boy walks over to you.");
				output(" He wished to know what the book is about... so you tell him:");
				output("`n`n`6%s`n`n",$bookonedesc);
				output("`3The child reveals himself as Ramius and hands you a small Amulet.");
				output(" The Amulet empowers you, and you can feel your muscles toning.");
				output("`n`nYou gained `^1 `3Attack.");
				$session['user']['attack']++;
				$readtoday = get_module_pref("readtoday")+$litallowed;
				set_module_pref("readtoday",$readtoday);
				set_module_pref("atkinc",1);
				break;
		case "book2":
				output("`3You point towards the book that is titled, \"%s`3\".",$booktwotitle);
				output(" Hikaru retrieves it for you, and walks you over to a couch, so that you can read easier.");
				output(" You begin to read the tale, and smile with each page turn.");
				output(" You finish the novel, and give a brief smile of satisfaction.");
				output(" A small girl walks up to you, grinning from ear to ear.");
				output("`n`n\"`5Hey %s! What was that book about?`3\"",translate_inline($session['user']['sex']==0?"Miss":"Mister"));
				output(" You begin to explain to the child...");
				output("`n`n`^%s`n`n",$booktwodesc);
				output("`3She nods and look at you, grinning even more.");
				output(" She hands you a small Talisman.");
				output(" You feel the power of it coursing in your veins.");
				output("`n`nYou gain `^1 `3Defense.");
				$session['user']['defense']++;
				$readtoday = get_module_pref("readtoday")+$litallowed;
				set_module_pref("readtoday",$readtoday);
				set_module_pref("definc",1);
				break;
		case "book3":
				output("`3You point towards the book that is titled, \"%s`3\".",$bookthreetitle);
				output(" Hikaru retrieves it for you, and walks you over to a couch, so that you can read easier.");
				output(" You begin to read the tale, and smile with each page turn.");
				output(" You finish the novel, and give a brief smile of satisfaction.");
				output(" A teenage girl walks up to you, grinning from ear to ear.");
				output("`n`n\"`5Hey %s! What was that book about?`3\"",translate_inline($session['user']['sex']==0?"Miss":"Mister"));
				output(" You begin to explain to the girl...");
				output("`n`n`^%s`n`n",$bookthreedesc);
				output("`3She nods and look at you, grinning even more.");
				output(" She touches the small of your back, and you feel a magical tattoo infuse into your flesh.");
				output(" The pain brings about satori, or enlightenment, and you like it.");
				output("`n`nYou gain `^%s `3Charm.",$charm);
				$session['user']['charm']+=$charm;
				$readtoday = get_module_pref("readtoday")+$litallowed;
				set_module_pref("readtoday",$readtoday);
				set_module_pref("charminc",1);
				break;
			}
		if ($readtoday<$litallowed) {
			addnav("Return");
			addnav("Return to the Storefront","runmodule.php?module=hiklit&op=enter");
            }
		villagenav();
page_footer();
}
?>