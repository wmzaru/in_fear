<?php
//Hooked into Library Card system
function book_theriddle_getmoduleinfo(){
	$info = array(
		"name"=>"The Riddle",
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

function book_theriddle_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_theriddle_uninstall(){
	return true;
}

function book_theriddle_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Riddle", "runmodule.php?module=book_theriddle");
		break;
	}
	}
	return $args;
}

function book_theriddle_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Riddle`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There was once a king's son who was seized with a desire to travel about the world, and took no one with him but a faithful servant. One day he came to a great forest, and when darkness overtook him he could find no shelter, and knew not where to pass the night. Then he saw a girl who was going towards a small house, and when he came nearer, he saw that the maiden was young and beautiful. He spoke to her, and said, dear child, can I and my servant find shelter for the night in the little house. Oh, yes, said the girl in a sad voice, that you certainly can, but I do not advise you to venture it. Do not go in. Why not, asked the king's son. The maiden sighed and said, my step-mother practises wicked arts. She is ill-disposed toward strangers. Then he saw very well that he had come to the house of a witch, but as it was dark, and he could not go farther, and also was not afraid, he entered. The old woman was sitting in an armchair by the fire, and looked at the stranger with her red eyes. Good evening, growled she, and pretended to be quite friendly. Take a seat and rest yourselves. She fanned the fire on which she was cooking something in a small pot. The daughter warned the two to be prudent, to eat nothing, and drink nothing, for the old woman brewed evil drinks. They slept quietly until early morning. When they were making ready for their departure, and the king's son was already seated on his horse, the old woman said, stop a moment, I will first hand you a parting draught. Whilst she fetched it, the king's son rode away, and the servant who had to buckle his saddle tight, was the only one present when the wicked witch came with the drink. Take that to your master, said she. But at that instant the glass broke and the poison spirted on the horse, and it was so strong that the animal immediately fell down dead. The servant ran after his master and told him what had happened, but as he did not want to leave his saddle behind, he ran back to fetch it. When he came to the dead horse, however, a raven was already sitting on it devouring it. Who knows whether we shall find anything better to-day, said the servant. So he killed the raven, and took it with him. And now they journeyed onwards into the forest the whole day, but could not get out of it. By nightfall they found an inn and entered it. The servant gave the raven to the innkeeper to prepare for supper. They had stumbled, however, on a den of murderers, and during the darkness twelve of these came, intending to kill the strangers and rob them. But before they set about this work, they sat down to supper, and the innkeeper and the witch sat down with them, and together they ate a dish of soup in which was cut up the flesh of the raven. Hardly had they swallowed a couple of mouthfuls, before they all fell down dead, for the raven had communicated to them the poison from the horse-flesh. There was no no one else left in the house but the innkeeper's daughter, who was honest, and had taken no part in their godless deeds. She opened all doors to the stranger and showed him the store of treasures. But the king's son said she might keep everything, he would have none of it, and rode onwards with his servant. After they had traveled about for a long time, they came to a town in which was a beautiful but proud princess, who had made it known that whosoever should set her a riddle which she could not guess, that man should be her husband. But if she guessed it, his head must be cut off. She had three days to guess it in, but was so clever that she always found the answer to the riddle given her before the appointed time. Nine suitors had already perished in this manner, when the king's son arrived, and blinded by her great beauty, was willing to stake his life for it. Then he went to her and laid his riddle before her. What is this, said he. One slew none, and yet slew twelve. She did not know what that was. She thought and thought, but she could not solve it. She opened her riddle-books, but it was not in them - in short, her wisdom was at an end. As she did not know how to help herself, she ordered her maid to creep into the lord's sleeping-chamber, and listen to his dreams, and thought that he would perhaps speak in his sleep and reveal the riddle. But the clever servant had placed himself in the bed instead of his master, and when the maid came there, he tore off from her the mantle in which she had wrapped herself, and chased her out with rods. The second night the king's daughter sent her maid-in-waiting, who was to see if she could succeed better in listening, but the servant took her mantle also away from her, and hunted her out with rods. Now the master believed himself safe for the third night, and lay down in his own bed. Then came the princess herself, and she had put on a misty-grey mantle, and she seated herself near him. And when she thought that he was asleep and dreaming, she spoke to him, and hoped that he would answer in his sleep, as many do, but he was awake, and understood and heard everything quite well. Then she asked, one slew none, what is that. He replied, a raven, which ate of a dead and poisoned horse, and died of it. She inquired further, and yet slew twelve, what is that. He answered, that means twelve murderers, who ate the raven and died of it. When she knew the answer to the riddle she wanted to steal away, but he held her mantle so fast that she was forced to leave it behind her. Next morning, the king's daughter announced that she had guessed the riddle, and sent for the twelve judges and expounded it before them. But the youth begged for a hearing, and said, she stole into my room in the night and questioned me, otherwise she could not have discovered it. The judges said, bring us a proof of this. Then were the three mantles brought thither by the servant, and when the judges saw the misty-grey one which the king's daughter usually wore, they said, let the mantle be embroidered with gold and silver, and then it will be your wedding-mantle.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>