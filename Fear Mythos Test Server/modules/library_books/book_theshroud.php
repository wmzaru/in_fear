<?php
//Hooked into Library Card system
function book_theshroud_getmoduleinfo(){
	$info = array(
		"name"=>"The Shroud",
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

function book_theshroud_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_theshroud_uninstall(){
	return true;
}

function book_theshroud_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Shroud", "runmodule.php?module=book_theshroud");
		break;
	}
	}
	return $args;
}

function book_theshroud_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Shroud`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There was once a mother who had a little boy of seven years old, who was so handsome and lovable that no one could look at him without liking him, and she herself worshipped him above everything in the world. Now it so happened that he suddenly became ill, and God took him to himself, and for this the mother could not be comforted and wept both day and night. But soon afterwards, when the child had been buried, it appeared by night in the places where it had sat and played during its life, and if the mother wept, it wept also, and when morning came it disappeared. But as the mother would not stop crying, it came one night, in the little white shroud in which it had been laid in its coffin, and with its wreath of flowers round its head, and stood on the bed at her feet, and said, 'Oh, mother, do stop crying, or I shall never fall asleep in my coffin, for my shroud will not dry because of all your tears, which fall upon it.' The mother was afraid when she heard that, and wept no more. The next night the child came again, and held a little light in its hand, and said, 'Look, mother, my shroud is nearly dry, and I can rest in my grave.' Then the mother gave her sorrow into God's keeping, and bore it quietly and patiently, and the child came no more, but slept in its little bed beneath the earth.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`n`n`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>