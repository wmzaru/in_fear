<?php
//Hooked into Library Card system
function book_themoon_getmoduleinfo(){
	$info = array(
		"name"=>"The Moon",
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

function book_themoon_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_themoon_uninstall(){
	return true;
}

function book_themoon_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Moon", "runmodule.php?module=book_themoon");
		break;
	}
	}
	return $args;
}

function book_themoon_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Moon`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("In days gone by there was a land where the nights were always dark, and the sky spread over it like a black cloth, for there the moon never rose, and no star shone in the gloom. At the creation of the world, the light at night had been sufficient. Three young fellows once went out of this country on a traveling expedition, and arrived in another kingdom, where, in the evening when the sun had disappeared behind the mountains, a shining globe was placed on an oak-tree, which shed a soft light far and wide. By means of this, everything could very well be seen and distinguished, even though it was not so brilliant as the sun. The travelers stopped and asked a countryman who was driving past with his cart, what kind of a light that was. That is the moon, answered he, our mayor bought it for three talers, and fastened it to the oak-tree. He has to pour oil into it daily, and to keep it clean, so that it may always burn clearly. He receives a taler a week from us for doing it. When the countryman had driven away, one of them said, we could make some use of this lamp, we have an oak-tree at home, which is just as big as this, and we could hang it on that. What a pleasure it would be not to have to feel about at night in the darkness. I'll tell you what we'll do, said the second, we will fetch a cart and horses and carry away the moon. The people here may buy themselves another. I'm a good climber, said the third, I will bring it down. The fourth brought a cart and horses, and the third climbed the tree, bored a hole in the moon, passed a rope through it, and let it down. When the shining ball lay in the cart, they covered it over with a cloth, that no one might observe the theft. They conveyed it safely into their own country, and placed it on a high oak. Old and young rejoiced, when the new lamp let its light shine over the whole land, and bed-rooms and sitting-rooms were filled with it. The dwarfs came forth from their caves in the rocks, and the tiny elves in their little red coats danced in rings on the meadows. The four took care that the moon was provided with oil, cleaned the wick, and received their weekly taler, but they became old men, and when one of them grew ill, and saw that he was about to die, he appointed that one quarter of the moon, should, as his property, be laid in the grave with him. When he died, the mayor climbed up the tree, and cut off a quarter with the hedge-shears, and this was placed in his coffin. The light of the moon decreased, but still not visibly. When the second died, the second quarter was buried with him, and the light diminished. It grew weaker still after the death of the third, who likewise took his part of it away with him, and when the fourth was borne to his grave, the old state of darkness recommenced, and whenever the people went out at night without their lanterns they knocked their heads together in collision. When, however, the pieces of the moon had united themselves together again in the world below, where darkness had always prevailed, it came to pass that the dead became restless and awoke from their sleep. They were astonished when they were able to see again, the moonlight was quite sufficient for them, for their eyes had become so weak that they could not have borne the brilliance of the sun. They rose up and were merry, and fell into their former ways of living. Some of them went to the play and to dance, others hastened to the public-houses, where they asked for wine, got drunk, brawled, quarreled, and at last took up cudgels, and belabored each other. The noise became greater and greater, and at last reached even to heaven. St. Peter, who guards the gate of heaven, thought the lower world had broken out in revolt and gathered together the heavenly hosts, which were employed to drive back the evil one when he and his associates storm the abode of the blessed. As these, however, did not come, he got on his horse and rode through the gate of heaven, down into the world below. There he reduced the dead to subjection, bade them lie down in their graves again, took the moon away with him, and hung it up in heaven.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`n`n`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>