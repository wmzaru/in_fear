<?php
//Hooked into Library Card system
function book_therose_getmoduleinfo(){
	$info = array(
		"name"=>"The Rose",
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

function book_therose_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_therose_uninstall(){
	return true;
}

function book_therose_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Rose", "runmodule.php?module=book_therose");
		break;
	}
	}
	return $args;
}

function book_therose_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Rose`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There was once a poor woman who had two children. The youngest had to go every day into the forest to fetch wood. Once when she had gone a long way to seek it, a little child, who was quite strong, came and helped her industriously to pick up the wood and carry it home, and then before a moment had passed the strange child disappeared. The child told her mother this, but at first she would not believe it. At length she brought a rose home, and told her mother that the beautiful child had given her this rose, and had told her that when it was in full bloom, he would return. The mother put the rose in water. One morning her child could not get out of bed. The mother went to the bed and found her dead, but she lay looking very happy. On the same morning, the rose was in full bloom.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`n`n`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>