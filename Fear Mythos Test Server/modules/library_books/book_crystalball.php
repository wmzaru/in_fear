<?php
//Hooked into Library Card system
function book_crystalball_getmoduleinfo(){
	$info = array(
		"name"=>"The Crystal Ball",
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

function book_crystalball_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_crystalball_uninstall(){
	return true;
}

function book_crystalball_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Crystal Ball", "runmodule.php?module=book_crystalball");
		break;
	}
	}
	return $args;
}

function book_crystalball_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Crystal Ball`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There was once an enchantress, who had three sons who loved each other as brothers, but the old woman did not trust them, and thought they wanted to steal her power from her. So she changed the eldest into an eagle, which was forced to dwell in the rocky mountains, and was often seen flying in great circles in the sky. The second, she changed into a whale, which lived in the deep sea, and all that was seen of it was that it sometimes spouted up a great jet of water in the air. Each of them bore his human form for only two hours daily. The third son, who was afraid she might change him into a raging wild beast - a bear perhaps, or a wolf, went secretly away. He had heard that a king's daughter who was bewitched, was imprisoned in the castle of the golden sun, and was waiting to be set free. Those, however, who tried to free her risked their lives. Three-and-twenty youths had already died a miserable death, and now only one other might make the attempt, after which no more must come. And as his heart was without fear, he made up his mind to seek out the castle of the golden sun. He had already traveled about for a long time without being able to find it, when he came by chance into a great forest, and did not know the way out of it. All at once he saw in the distance two giants, who made a sign to him with their hands, and when he came to them they said, we are quarreling about a cap, and which of us it is to belong to, and as we are equally strong, neither of us can get the better of the other. The small men are cleverer than we are, so we will leave the decision to you. How can you dispute about an old cap, said the youth. You do not know what properties it has. It is a wishing-cap, whosoever puts it on, can wish himself away wherever he likes, and in an instant he will be there. Give me the cap, said the youth, I will go a short distance off, and when I call you, you must run a race, and the cap shall belong to the one who gets first to me. He put it on and went away, and thought of the king's daughter, forgot the giants, and walked continually onward. At length he sighed from the very bottom of his heart, and cried, ah, if I were but at the castle of the golden sun. And hardly had the words passed his lips than he was standing on a high mountain before the gate of the castle. He entered and went through all the rooms, until in the last he found the king's daughter. But how shocked he was when he saw her. She had an ashen-gray face full of wrinkles, bleary eyes, and red hair. Are you the king's daughter, whose beauty the whole world praises, cried he. Ah, she answered, this is not my form, human eyes can only see me in this state of ugliness, but that you may know what I am like, look in the mirror - it does not let itself be misled - it will show you my image as it is in truth. She gave him the mirror in his hand, and he saw therein the likeness of the most beautiful maiden on earth, and saw, too, how the tears were rolling down her cheeks with grief. Then said he, how can you be set free. I fear no danger. She said, he who gets the crystal ball, and holds it before the enchanter, will destroy his power with it, and I shall resume my true shape. Ah, she added, so many have already gone to meet death for this, and you are so young, I grieve that you should encounter such great danger. Nothing can keep me from doing it, said he, but tell me what I must do. You shall know everything, said the king's daughter, when you descend the mountain on which the castle stands, a wild bull will stand below by a spring, and you must fight with it, and if you have the luck to kill it, a fiery bird will spring out of it, which bears in its body a red-hot egg, and in the egg the crystal ball lies as its yolk. The bird, however, will not let the egg fall until forced to do so, and if it falls on the ground, it will flame up and burn everything that is near, and even the egg itself will melt, and with it the crystal ball, and then your trouble will have been in vain. The youth went down to the spring, where the bull snorted and bellowed at him. After a long struggle he plunged his sword in the animal's body, and it fell down. Instantly a fiery bird arose from it and was about to fly away, but the young man's brother, the eagle, who was passing between the clouds, swooped down, hunted it away to the sea, and struck it with his beak until, in its extremity, it let the egg fall. The egg, however, did not fall into the sea, but on a fisherman's hut which stood on the shore and the hut began at once to smoke and was about to break out in flames. Then arose in the sea waves as high as a house, which streamed over the hut, and subdued the fire. The other brother, the whale, had come swimming to them, and had driven the water up on high. When the fire was extinguished, the youth sought for the egg and happily found it, it was not yet melted, but the shell was broken by being so suddenly cooled with the water, and he could take out the crystal ball unhurt. When the youth went to the enchanter and held it before him, the latter said, my power is destroyed, and from this time forth you are the king of the castle of the golden sun. With this you can likewise give back to your brothers their human form. Then the youth hastened to the king's daughter, and when he entered the room, she was standing there in the full splendor of her beauty, and joyfully they exchanged rings with each other.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`n`n`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>