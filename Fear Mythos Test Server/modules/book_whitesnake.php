<?php
//Hooked into Library Card system
function book_whitesnake_getmoduleinfo(){
	$info = array(
		"name"=>"The White Snake",
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

function book_whitesnake_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_whitesnake_uninstall(){
	return true;
}

function book_whitesnake_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The White Snake", "runmodule.php?module=book_whitesnake");
		break;
	}
	}
	return $args;
}

function book_whitesnake_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe White Snake`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("A long time ago there lived a king who was famed for his wisdom through all the land. Nothing was hidden from him, and it seemed as if news of the most secret things was brought to him through the air. But he had a strange custom, every day after dinner, when the table was cleared, and no one else was present, a trusty servant had to bring him one more dish. It was covered, however, and even the servant did not know what was in it, neither did anyone know, for the king never took off the cover to eat of it until he was quite alone. This had gone on for a long time, when one day the servant, who took away the dish, was overcome with such curiosity that he could not help carrying the dish into his room. When he had carefully locked the door, he lifted up the cover, and saw a white snake lying on the dish. But when he saw it he could not deny himself the pleasure of tasting it, so he cut off a little bit and put it into his mouth. No sooner had it touched his tongue than he heard a strange whispering of little voices outside his window. He went and listened, and then noticed that it was the sparrows who were chattering together, and telling one another of all kinds of things which they had seen in the fields and woods. Eating the snake had given him power of understanding the language of animals. Now it so happened that on this very day the queen lost her most beautiful ring, and suspicion of having stolen it fell upon this trusty servant, who was allowed to go everywhere. The king ordered the man to be brought before him, and threatened with angry words that unless he could before the morrow point out the thief, he himself should be looked upon as guilty and executed. In vain he declared his innocence, he was dismissed with no better answer. In his trouble and fear he went down into the courtyard and took thought how to help himself out of his trouble. Now some ducks were sitting together quietly by a brook and taking their rest, and, whilst they were making their feathers smooth with their bills, they were having a confidential conversation together. The servant stood by and listened. They were telling one another of all the places where they had been waddling about all the morning, and what good food they had found, and one said in a pitiful tone, something lies heavy on my stomach, as I was eating in haste I swallowed a ring which lay under the queen's window. The servant at once seized her by the neck, carried her to the kitchen, and said to the cook, here is a fine duck, pray, kill her. Yes, said the cook, and weighed her in his hand, she has spared no trouble to fatten herself, and has been waiting to be roasted long enough. So he cut off her head, and as she was being dressed for the spit, the queen's ring was found inside her. The servant could now easily prove his innocence, and the king, to make amends for the wrong, allowed him to ask a favor, and promised him the best place in the court that he could wish for. The servant refused everything, and only asked for a horse and some money for traveling, as he had a mind to see the world and go about a little. When his request was granted he set out on his way, and one day came to a pond, where he saw three fishes caught in the reeds and gasping for water. Now, though it is said that fishes are dumb, he heard them lamenting that they must perish so miserably, and, as he had a kind heart, he got off his horse and put the three prisoners back into the water. They leapt with delight, put out their heads, and cried to him, we will remember you and repay you for saving us. He rode on, and after a while it seemed to him that he heard a voice in the sand at his feet. He listened, and heard an ant-king complain, why cannot folks, with their clumsy beasts, keep off our bodies. That stupid horse, with his heavy hoofs, has been treading down my people without mercy. So he turned on to a side path and the ant-king cried out to him, we will remember you - one good turn deserves another. The path led him into a wood, and here he saw two old ravens standing by their nest, and throwing out their young ones. Out with you, you idle, good-for-nothing creatures, cried they, we cannot find food for you any longer, you are big enough, and can provide for yourselves. But the poor young ravens lay upon the ground, flapping their wings, and crying, oh, what helpless chicks we are. We must shift for ourselves, and yet we cannot fly. What can we do, but lie here and starve. So the good young fellow alighted and killed his horse with his sword, and gave it to them for food. Then they came hopping up to it, satisfied their hunger, and cried, we will remember you - one good turn deserves another. And now he had to use his own legs, and when he had walked a long way, he came to a large city. There was a great noise and crowd in the streets, and a man rode up on horseback, crying aloud, the king's daughter wants a husband, but whoever seeks her hand must perform a hard task, and if he does not succeed he will forfeit his life. Many had already made the attempt, but in vain, nevertheless when the youth saw the king's daughter he was so overcome by her great beauty that he forgot all danger, went before the king, and declared himself a suitor. So he was led out to the sea, and a gold ring was thrown into it, before his eyes, then the king ordered him to fetch this ring up from the bottom of the sea, and added, if you come up again without it you will be thrown in again and again until you perish amid the waves. All the people grieved for the handsome youth, then they went away, leaving him alone by the sea. He stood on the shore and considered what he should do, when suddenly he saw three fishes come swimming towards him, and they were the very fishes whose lives he had saved. The one in the middle held a mussel in its mouth, which it laid on the shore at the youth's feet, and when he had taken it up and opened it, there lay the gold ring in the shell. Full of joy he took it to the king, and expected that he would grant him the promised reward. But when the proud princess perceived that he was not her equal in birth, she scorned him, and required him first to perform another task. She went down into the garden and strewed with her own hands ten sacks-full of millet-seed on the grass, then she said, tomorrow morning before sunrise these must be picked up, and not a single grain be wanting. The youth sat down in the garden and considered how it might be possible to perform this task, but he could think of nothing, and there he sat sorrowfully awaiting the break of day, when he should be led to death. But as soon as the first rays of the sun shone into the garden he saw all the ten sacks standing side by side, quite full, and not a single grain was missing. The ant-king had come in the night with thousands and thousands of ants, and the grateful creatures had by great industry picked up all the millet-seed and gathered them into the sacks. Presently the king's daughter herself came down into the garden, and was amazed to see that the young man had done the task she had given him. But she could not yet conquer her proud heart, and said, although he has performed both the tasks, he shall not be my husband until he has brought me an apple from the tree of life. The youth did not know where the tree of life stood, but he set out, and would have gone on for ever, as long as his legs would carry him, though he had no hope of finding it. After he had wandered through three kingdoms, he came one evening to a wood, and lay down under a tree to sleep. But he heard a rustling in the branches, and a golden apple fell into his hand. At the same time three ravens flew down to him, perched themselves upon his knee, and said, we are the three young ravens whom you saved from starving, when we had grown big, and heard that you were seeking the golden apple, we flew over the sea to the end of the world, where the tree of life stands, and have brought you the apple. The youth, full of joy, set out homewards, and took the golden apple to the king's beautiful daughter, who had no more excuses left to make. They cut the apple of life in two and ate it together, and then her heart became full of love for him, and they lived in undisturbed happiness to a great age.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>