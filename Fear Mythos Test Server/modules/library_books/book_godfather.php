<?php
//Hooked into Library Card system
function book_godfather_getmoduleinfo(){
	$info = array(
		"name"=>"The Godfather",
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

function book_godfather_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_godfather_uninstall(){
	return true;
}

function book_godfather_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Godfather", "runmodule.php?module=book_godfather");
		break;
	}
	}
	return $args;
}

function book_godfather_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Godfather`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("A poor man had so many children that he had already asked everyone in the world to be godfather, and when still another child was born, no one else was left whom he could invite. He knew not what to do, and, in his perplexity, he lay down and fell asleep. Then he dreamt that he was to go outside the gate, and ask the first person he met to be godfather. When he awoke, he determined to obey his dream, and went outside the gate, and asked the first person who came up to him to be godfather. The stranger presented him with a little glass of water, and said, this is a wonderful water, with it you can heal the sick, only you must see where death is standing. If he is standing by the patient's head, give the patient some of the water and he will be healed, but if death is standing by his feet, all trouble will be in vain, for the sick man must die. From this time forth, the man could always say whether a patient could be saved or not, and became famous for his skill, and earned a great deal of money. Once he was called in to the child of the king, and when he entered, he saw death standing by the child's head and cured it with the water, and he did the same a second time, but the third time death was standing by its feet, and then he knew the child had to die.`n`nOnce the man thought he would visit the godfather, and tell him how he had succeeded with the water. But when he entered the house, the strangest things were going on within. On the first flight of stairs, the broom and shovel were disputing, and knocking each other about violently. He asked them, where does the godfather live. The broom replied, one flight of stairs higher up. When he came to the second flight, he saw a heap of dead fingers lying. He asked, where does the godfather live. One of the fingers replied, one flight of stairs higher. On the third flight lay a heap of dead heads, which again directed him to the flight beyond. On the fourth flight, he saw fishes on the fire, which frizzled in pans and baked themselves. They, too, said, one flight of stairs higher. And when he had ascended the fifth, he came to the door of a room and peeped through the keyhole, and there he saw the godfather who had a pair of long horns. When he opened the door and went in, the godfather got into bed in a great hurry and covered himself up. Then said the man, sir godfather, what a strange house-hold you have. When I came to your first flight of stairs, the shovel and broom were quarreling, and beating each other violently. How stupid you are, said the godfather. That was the boy and the maid talking to each other. But on the second flight I saw dead fingers lying. Oh, how silly you are. Those were some roots of scorzonera. On the third flight lay a heap of dead men's heads. Foolish man, those were cabbages. On the fourth flight I saw fishes in a pan, which were hissing and baking themselves. When he had said that, the fishes came and served themselves up. And when I got to the fifth flight, I peeped through the keyhole of a door, and there, godfather, I saw you and you had long, long horns. Oh, that is not true. The man became alarmed, and ran out, and if he had not, who knows what the godfather would have done to him.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>