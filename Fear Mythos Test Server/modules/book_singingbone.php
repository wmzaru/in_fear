<?php
//Hooked into Library Card system
function book_singingbone_getmoduleinfo(){
	$info = array(
		"name"=>"The Singing Bone",
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

function book_singingbone_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_singingbone_uninstall(){
	return true;
}

function book_singingbone_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Singing Bone", "runmodule.php?module=book_singingbone");
		break;
	}
	}
	return $args;
}

function book_singingbone_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Singing Bone`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("In a certain country there was once great lamentation over a wild boar that laid waste the farmer's fields, killed the cattle, and ripped up people's bodies with his tusks. The king promised a large reward to anyone who would free the land from this plague, but the beast was so big and strong that no one dared to go near the forest in which it lived. At last the king gave notice that whosoever should capture or kill the wild boar should have his only daughter to wife.`n`nNow there lived in the country two brothers, sons of a poor man, who declared themselves willing to undertake the hazardous enterprise, the elder, who was crafty and shrewd, out of pride, the younger, who was innocent and simple, from a kind heart. The king said, in order that you may be the more sure of finding the beast, you must go into the forest from opposite sides. So the elder went in on the west side, and the younger on the east. When the younger had gone a short way, a little man stepped up to him. He held in his hand a black spear and said, I give you this spear because your heart is pure and good, with this you can boldly attack the wild boar, and it will do you no harm. He thanked the little man, shouldered the spear, and went on fearlessly.`n`nBefore long he saw the beast, which rushed at him, but he held the spear towards it, and in its blind fury it ran so swiftly against it that its heart was cloven in twain. Then he took the monster on his back and went homewards with it to the king. As he came out at the other side of the wood, there stood at the entrance a house where people were making merry with wine and dancing. His elder brother had gone in here, and, thinking that after all the boar would not run away from him, was going to drink until he felt brave. But when he saw his young brother coming out of the wood laden with his booty, his envious, evil heart gave him no peace. He called out to him, come in, dear brother, rest and refresh yourself with a cup of wine.`n`nThe youth, who suspected no evil, went in and told him about the good little man who had given him the spear wherewith he had slain the boar.`n`nThe elder brother kept him there until the evening, and then they went away together, and when in the darkness they came to a bridge over a brook, the elder brother let the other go first, and when he was half-way across he gave him such a blow from behind that he fell down dead. He buried him beneath the bridge, took the boar, and carried it to the king, pretending that he had killed it, whereupon he obtained the king's daughter in marriage. And when his younger brother did not come back he said, the boar must have ripped up his body, and every one believed it. But as nothing remains hidden from God, so this black deed also was to come to light.`n`nYears afterwards a shepherd was driving his herd across the bridge, and saw lying in the sand beneath, a snow-white little bone. He thought that it would make a good mouth-piece, so he clambered down, picked it up, and cut out of it a mouth-piece for his horn, but when he blew through it for the first time, to his great astonishment, the bone began of its own accord to sing - ah, friend thou blowest upon my bone. Long have I lain beside the water, my brother slew me for the boar, and took for his wife the king's young daughter.`n`nWhat a wonderful horn, said the shepherd, it sings by itself, I must take it to my lord the king. And when he came with it to the king the horn again began to sing its little song. The king understood it all, and caused the ground below the bridge to be dug up, and then the whole skeleton of the murdered man came to light. The wicked brother could not deny the deed, and was sewn up in a sack and drowned. But the bones of the murdered man were laid to rest in a beautiful tomb in the churchyard.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>