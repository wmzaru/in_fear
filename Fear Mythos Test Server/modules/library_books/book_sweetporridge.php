<?php
//Hooked into Library Card system
function book_sweetporridge_getmoduleinfo(){
	$info = array(
		"name"=>"Sweet Porridge",
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

function book_sweetporridge_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_sweetporridge_uninstall(){
	return true;
}

function book_sweetporridge_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("Sweet Porridge", "runmodule.php?module=book_sweetporridge");
		break;
	}
	}
	return $args;
}

function book_sweetporridge_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bSweet Porridge`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There was a poor but good little girl who lived alone with her mother, and they no longer had anything to eat. So the child went into the forest, and there an aged woman met her who was aware of her sorrow, and presented her with a little pot, which when she said, cook, little pot, cook, would cook good, sweet porridge, and when she said, stop, little pot, it ceased to cook. The girl took the pot home to her mother, and now they were freed from their poverty and hunger, and ate sweet porridge as often as they chose. Once on a time when the girl had gone out, her mother said, cook, little pot, cook. And it did cook and she ate till she was satisfied, and then she wanted the pot to stop cooking, but did not know the word. So it went on cooking and the porridge rose over the edge, and still it cooked on until the kitchen and whole house were full, and then the next house, and then the whole street, just as if it wanted to satisfy the hunger of the whole world, and there was the greatest distress, but no one knew how to stop it. At last when only one single house remained, the child came home and just said, stop, little pot, and it stopped and gave up cooking, and whosoever wished to return to the town had to eat his way back.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`n`n`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>