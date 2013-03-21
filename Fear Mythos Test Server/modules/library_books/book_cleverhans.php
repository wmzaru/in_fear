<?php
//Hooked into Library Card system
function book_cleverhans_getmoduleinfo(){
	$info = array(
		"name"=>"Clever Hans",
		"author"=>"Lonny Luberts - based on Script by WebPixie.",
		"version"=>"1.0",
		"category"=>"Library",
		"download"=>"",
		"prefs" => array(
			"bookread" => "Has the player read this book?, bool|false",
		),
	);
	return $info;
}

function book_cleverhans_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_cleverhans_uninstall(){
	return true;
}

function book_cleverhans_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("Clever Hans", "runmodule.php?module=book_cleverhans");
		break;
	}
	}
	return $args;
}

function book_cleverhans_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bClever Hans`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("The mother of Hans said, whither away, Hans. Hans answered, to Gretel. Behave well, Hans. Oh, I'll behave well. Good-bye, mother. Good-bye, Hans. Hans comes to Gretel. Good day, Gretel. Good day, Hans. What do you bring that is good. I bring nothing, I want to have something given me. Gretel presents Hans with a needle. Hans says, good-bye, Gretel. Good-bye, Hans. Hans takes the needle, sticks it into a hay-cart, and follows the cart home. Good evening, mother. Good evening, Hans. Where have you been. With Gretel. What did you take her. Took her nothing, had something given me. What did Gretel give you. Gave me a needle. Where is the needle, Hans. Stuck it in the hay-cart. That was ill done, Hans. You should have stuck the needle in your sleeve. Never mind, I'll do better next time.`n`nWhither away, Hans. To Gretel, mother. Behave well, Hans. Oh, I'll behave well. Good-bye, mother. Good-bye, Hans. Hans comes to Gretel. Good day, Gretel. Good day, Hans. What do you bring that is good. I bring nothing, I want to have something given to me. Gretel presents Hans with a knife. Good-bye, Gretel. Good-bye Hans. Hans takes the knife, sticks it in his sleeve, and goes home. Good evening, mother. Good evening, Hans. Where have you been. With Gretel. What did you take her. Took her nothing, she gave me something. What did Gretel give you. Gave me a knife. Where is the knife, Hans. Stuck in my sleeve. That's ill done, Hans, you should have put the knife in your pocket. Never mind, will do better next time.`n`nWhither away, Hans. To Gretel, mother. Behave well, Hans. Oh, I'll behave well. Good-bye, mother. Good-bye, Hans. Hans comes to Gretel. Good day, Gretel. Good day, Hans. What good thing do you bring. I bring nothing, I want something given me. Gretel presents Hans with a young goat. Good-bye, Gretel. Good-bye, Hans. Hans takes the goat, ties its legs, and puts it in his pocket. When he gets home it is suffocated. Good evening, mother. Good evening, Hans. Where have you been. With Gretel. What did you take her. Took nothing, she gave me something. What did Gretel give you. She gave me a goat. Where is the goat, Hans. Put it in my pocket. That was ill done, Hans, you should have put a rope round the goat's neck. Never mind, will do better next time.`n`nWhither away, Hans, to Gretel, mother. Behave well, Hans. Oh, I'll behave well good-bye, mother. Good-bye, Hans. Hans comes to Gretel. Good day, Gretel. Good day, Hans. What good thing do you bring. I bring nothing, I want something given to me. Gretel presents Hans with a piece of bacon. Good-bye, Gretel. Good-bye, Hans. Hans takes the bacon, ties it to a rope, and drags it away behind him. The dogs come and devour the bacon. When he gets home, he has the rope in his hand, and there is no longer anything hanging to it. Good evening, mother. Good evening, Hans. Where have you been. With Gretel. What did you take her. I took her nothing, she gave me something. What did Gretel give you. Gave me a bit of bacon. Where is the bacon, Hans. I tied it to a rope, brought it home, dogs took it. That was ill done, Hans, you should have carried the bacon on your head. Never mind, will do better next time.`n`nWhither away, Hans. To Gretel, mother. Behave well, Hans. I'll behave well. Good-bye, mother. Good-bye, Hans. Hans comes to Gretel. Good day, Gretel. Good day, Hans. What good thing do you bring. I bring nothing, but would have something given. Gretel presents Hans with a calf. Good-bye, Gretel. Good-bye, Hans. Hans takes the calf, puts it on his head, and the calf kicks his face. Good evening, mother. Good evening, Hans. Where have you been. With Gretel. What did you take her. I took nothing, but had something given me. What did Gretel give you. A calf. Where have you the calf, Hans. I set it on my head and it kicked my face. That was ill done, Hans, you should have led the calf, and put it in the stall. Never mind, will do better next time.`n`nWhither away, Hans. To Gretel, mother. Behave well, Hans. I'll behave well. Good-bye, mother. Good-bye, Hans. Hans comes to Gretel. Good day, Gretel. Good day, Hans. What good thing do you bring. I bring nothing, but would have something given. Gretel says to Hans, I will go with you. Hans takes Gretel, ties her to a rope, leads her to the rack and binds her fast. Then Hans goes to his mother. Good evening, mother. Good evening, Hans. Where have you been. With Gretel. What did you take her. I took her nothing. What did Gretel give you. She gave me nothing, she came with me. Where have you left Gretel. I led her by the rope, tied her to the rack, and scattered some grass for her. That was ill done, Hans, you should have cast friendly eyes on her. Never mind, will do better.`n`nHans went into the stable, cut out all the calves, and sheep's eyes, and threw them in Gretel's face. Then Gretel became angry, tore herself loose and ran away, and was no longer the bride of Hans.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>