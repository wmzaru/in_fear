<?php
//Hooked into Library Card system
function book_thenail_getmoduleinfo(){
	$info = array(
		"name"=>"The Nail",
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

function book_thenail_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_thenail_uninstall(){
	return true;
}

function book_thenail_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Nail", "runmodule.php?module=book_thenail");
		break;
	}
	}
	return $args;
}

function book_thenail_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Nail`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("A merchant had done good business at the fair. He had sold his wares, and lined his money-bags with gold and silver. Then he wanted to travel homewards, and be in his own house before nightfall. So he packed his trunk with the money on his horse, and rode away. At noon he rested in a town, and when he wanted to go farther the stable-boy brought out his horse and said, a nail is wanting, sir, in the shoe of its near hind foot. Let it be wanting, answered the merchant. The shoe will certainly stay on for the six miles I have still to go. I am in a hurry. In the afternoon, when he once more alighted and had his horse fed, the stable-boy went into the room to him and said, sir, a shoe is missing from your horse's near hind foot. Shall I take him to the blacksmith. Let it be wanting, answered the man. The horse can very well hold out for the couple of miles which remain. I am in haste. He rode forth, but before long the horse began to limp. It had not limped long before it began to stumble, and it had not stumbled long before it fell down and broke its leg. The merchant was forced to leave the horse where it was, and unbuckle the trunk, take it on his back, and go home on foot. And there he did not arrive until quite late at night. And that cursed nail, said he to himself, has caused all this disaster. The more haste the less speed.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`n`n`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>