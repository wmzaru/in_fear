<?php
//Hooked into Library Card system
function book_queenbee_getmoduleinfo(){
	$info = array(
		"name"=>"The Queen Bee",
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

function book_queenbee_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_queenbee_uninstall(){
	return true;
}

function book_queenbee_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Queen Bee", "runmodule.php?module=book_queenbee");
		break;
	}
	}
	return $args;
}

function book_queenbee_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Queen Bee`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("Two kings' sons once went out in search of adventures, and fell into a wild, disorderly way of living, so that they never came home again. The youngest, who was called simpleton, set out to seek his brothers, but when at length he found them they mocked him for thinking that he with his simplicity could get through the world, when they two could not make their way, and yet were so much cleverer.`n`nThey all three traveled away together, and came to an ant-hill. The two elder wanted to destroy it, to see the little ants creeping about in their terror, and carrying their eggs away, but simpleton said, leave the creatures in peace, I will not allow you to disturb them.`n`nThen they went onwards and came to a lake, on which a great number of ducks were swimming. The two brothers wanted to catch a couple and roast them, but simpleton would not permit it, and said, leave the creatures in peace, I will not suffer you to kill them.`n`nAt length they came to a bee's nest, in which there was so much honey that it ran out of the trunk of the tree where it was. The two wanted to make a fire beneath the tree, and suffocate the bees in order to take away the honey, but simpleton again stopped them and said, leave the creatures in peace, I will not allow you to burn them.`n`nAt length the three brothers arrived at a castle where stone horses were standing in the stables, and no human being was to be seen, and they went through all the halls until, quite at the end, they came to a door in which were three locks. In the middle of the door, however, there was a little pane, through which they could see into the room. There they saw a little grey man, who was sitting at a table. They called him, once, twice, but he did not hear, at last they called him for the third time, when he got up, opened the locks, and came out. He said nothing, however, but conducted them to a handsomely-spread table, and when they had eaten and drunk, he took each of them to a bedroom.`n`nNext morning the little grey man came to the eldest, beckoned to him, and conducted him to a stone table, on which were inscribed three tasks, by the performance of which the castle could be delivered from enchantment.`n`nThe first was that in the forest, beneath the moss, lay the princess's pearls, a thousand in number, which must be picked up, and if by sunset one single pearl was missing, he who had looked for them would be turned into stone. The eldest went thither, and sought the whole day, but when it came to an end, he had only found one hundred, and what was written on the table came true, and he was turned into stone. Next day, the second brother undertook the adventure, but it did not fare much better with him than with the eldest, he did not find more than two hundred pearls, and was changed to stone. At last it was simpleton's turn to seek in the moss, but it was so difficult for him to find the pearls, and he got on so slowly, that he seated himself on a stone, and wept. And while he was thus sitting, the king of the ants whose life he had once saved, came with five thousand ants, and before long the little creatures had got all the pearls together, and laid them in a heap.`n`nThe second task, however, was to fetch out of the lake the key of the king's daughter's bed-chamber. When simpleton came to the lake, the ducks which he had saved, swam up to him, dived down, and brought the key out of the water.`n`nBut the third task was the most difficult, from amongst the three sleeping daughters of the king was the youngest and dearest to be sought out. They, however, resembled each other exactly, and were only to be distinguished by their having eaten different sweetmeats before they fell asleep, the eldest a bit of sugar, the second a little syrup, and the youngest a spoonful of honey.`n`nThen the queen of the bees, whom simpleton had protected from the fire, came and tasted the lips of all three, and at last she remained sitting on the mouth which had eaten honey, and thus the king's son recognized the right princess. Then the enchantment was at an end, everything was delivered from sleep, and those who had been turned to stone received once more their natural forms.`n`nSimpleton married the youngest and sweetest princess, and after her father's death became king, and his two brothers received the two other sisters.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`n`n`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>