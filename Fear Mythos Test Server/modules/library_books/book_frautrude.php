<?php
//Hooked into Library Card system
function book_frautrude_getmoduleinfo(){
	$info = array(
		"name"=>"Frau Trude",
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

function book_frautrude_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_frautrude_uninstall(){
	return true;
}

function book_frautrude_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("Frau Trude", "runmodule.php?module=book_frautrude");
		break;
	}
	}
	return $args;
}

function book_frautrude_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bFrau Trude`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There was once a little girl who was obstinate and inquisitive, and when her parents told her to do anything, she did not obey them, so how could she fare well. One day she said to her parents, I have heard so much of frau trude, I will go to her some day. People say that everything about her does look so strange, and that there are such odd things in her house, that I have become quite curious. Her parents absolutely forbade her, and said, frau trude is a bad woman, who does wicked things, and if you go to her, you are no longer our child. But the maiden did not let herself be turned aside by her parents, prohibition, and still went to frau trude. And when she got to her, frau trude said, why are you so pale. Ah, she replied, and her whole body trembled, I have been so terrified at what I have seen. What have you seen. I saw a black man on your steps. That was a collier. Then I saw a green man. That was a huntsman. After that I saw a blood-red man. That was a butcher. Ah, frau trude, I was terrified. I looked through the window and saw not you, but, as I verily believe, the devil himself with a head of fire. Oho. Said she, then you have seen the witch in her proper costume. I have been waiting for you, and wanting you a long time already. You shall give me some light. Then she changed the girl into a block of wood, and threw it into the fire. And when it was in a full blaze she sat down close to it, and warmed herself by it, and said, that shines bright for once in a way.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>