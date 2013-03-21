<?php
//Hooked into Library Card system
function book_howdoyou_getmoduleinfo(){
	$info = array(
		"name"=>"How Do You...",
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

function book_howdoyou_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_howdoyou_uninstall(){
	return true;
}

function book_howdoyou_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("How Do You...", "runmodule.php?module=book_howdoyou");
		break;
	}
	}
	return $args;
}

function book_howdoyou_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bHow Do You...`b`c`n");
	output("`!`cWritten by Unknown`c`n`n");
	output("How do you explain?`nHow do you describe?`nA love that runs deep`nAs far as it is wide`nYou know all my hopes`nYou know all my fears`nNothing can express`nThe love that I feel`nBut I long for you to hear.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>