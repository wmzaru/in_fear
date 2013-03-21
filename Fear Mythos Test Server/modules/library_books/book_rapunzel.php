<?php
//Hooked into Library Card system
function book_rapunzel_getmoduleinfo(){
	$info = array(
		"name"=>"Rapunzel",
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

function book_rapunzel_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_rapunzel_uninstall(){
	return true;
}

function book_rapunzel_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("Rapunzel", "runmodule.php?module=book_rapunzel");
		break;
	}
	}
	return $args;
}

function book_rapunzel_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bRapunzel`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There were once a man and a woman who had long in vain wished for a child. At length the woman hoped that God was about to grant her desire. These people had a little window at the back of their house from which a splendid garden could be seen, which was full of the most beautiful flowers and herbs. It was, however, surrounded by a high wall, and no one dared to go into it because it belonged to an enchantress, who had great power and was dreaded by all the world.`n`nOne day the woman was standing by this window and looking down into the garden, when she saw a bed which was planted with the most beautiful rampion - rapunzel, and it looked so fresh and green that she longed for it, and had the greatest desire to eat some. This desire increased every day, and as she knew that she could not get any of it, she quite pined away, and began to look pale and miserable. Then her husband was alarmed, and asked, what ails you, dear wife. Ah, she replied, if I can't eat some of the rampion, which is in the garden behind our house, I shall die.`n`nThe man, who loved her, thought, sooner than let your wife die, bring her some of the rampion yourself, let it cost what it will. At twilight, he clambered down over the wall into the garden of the enchantress, hastily clutched a handful of rampion, and took it to his wife. She at once made herself a salad of it, and ate it greedily. It tasted so good to her - so very good, that the next day she longed for it three times as much as before. If he was to have any rest, her husband must once more descend into the garden. In the gloom of evening, therefore, he let himself down again. But when he had clambered down the wall he was terribly afraid, for he saw the enchantress standing before him.`n`nHow can you dare, said she with angry look, descend into my garden and steal my rampion like a thief. You shall suffer for it. Ah, answered he, let mercy take the place of justice, I only made up my mind to do it out of necessity. My wife saw your rampion from the window, and felt such a longing for it that she would have died if she had not got some to eat. Then the enchantress allowed her anger to be softened, and said to him, if the case be as you say, I will allow you to take away with you as much rampion as you will, only I make one condition, you must give me the child which your wife will bring into the world. It shall be well treated, and I will care for it like a mother.`n`nThe man in his terror consented to everything, and when the woman was brought to bed, the enchantress appeared at once, gave the child the name of rapunzel, and took it away with her. Rapunzel grew into the most beautiful child under the sun. When she was twelve years old, the enchantress shut her into a tower, which lay in a forest, and had neither stairs nor door, but quite at the top was a little window. When the enchantress wanted to go in, she placed herself beneath it and cried, rapunzel, rapunzel, let down your hair to me.`n`nRapunzel had magnificent long hair, fine as spun gold, and when she heard the voice of the enchantress she unfastened her braided tresses, wound them round one of the hooks of the window above, and then the hair fell twenty ells down, and the enchantress climbed up by it. After a year or two, it came to pass that the king's son rode through the forest and passed by the tower.`n`nThen he heard a song, which was so charming that he stood still and listened. This was rapunzel, who in her solitude passed her time in letting her sweet voice resound. The king's son wanted to climb up to her, and looked for the door of the tower, but none was to be found. He rode home, but the singing had so deeply touched his heart, that every day he went out into the forest and listened to it.`n`nOnce when he was thus standing behind a tree, he saw that an enchantress came there, and he heard how she cried, rapunzel, rapunzel, let down your hair. Then rapunzel let down the braids of her hair, and the enchantress climbed up to her. If that is the ladder by which one mounts, I too will try my fortune, said he, and the next day when it began to grow dark, he went to the tower and cried, rapunzel, rapunzel, let down your hair. Immediately the hair fell down and the king's son climbed up.`n`nAt first rapunzel was terribly frightened when a man, such as her eyes had never yet beheld, came to her. But the king's son began to talk to her quite like a friend, and told her that his heart had been so stirred that it had let him have no rest, and he had been forced to see her. Then rapunzel lost her fear, and when he asked her if she would take him for her husband, and she saw that he was young and handsome, she thought, he will love me more than old dame gothel does. And she said yes, and laid her hand in his. She said, I will willingly go away with you, but I do not know how to get down.`n`nBring with you a skein of silk every time that you come, and I will weave a ladder with it, and when that is ready I will descend, and you will take me on your horse. They agreed that until that time he should come to her every evening, for the old woman came by day. The enchantress remarked nothing of this, until once rapunzel said to her, tell me, dame gothel, how it happens that you are so much heavier for me to draw up than the young king's son - he is with me in a moment. Ah.`n`nYou wicked child, cried the enchantress. What do I hear you say. I thought I had separated you from all the world, and yet you have deceived me. In her anger she clutched rapunzel's beautiful tresses, wrapped them twice round her left hand, seized a pair of scissors with the right, and snip, snap, they were cut off, and the lovely braids lay on the ground. And she was so pitiless that she took poor rapunzel into a desert where she had to live in great grief and misery.`n`nOn the same day that she cast out rapunzel, however, the enchantress fastened the braids of hair, which she had cut off, to the hook of the window, and when the king's son came and cried, rapunzel, rapunzel, let down your hair, she let the hair down. The king's son ascended, but instead of finding his dearest rapunzel, he found the enchantress, who gazed at him with wicked and venomous looks. Aha, she cried mockingly, you would fetch your dearest, but the beautiful bird sits no longer singing in the nest. The cat has got it, and will scratch out your eyes as well.`n`nRapunzel is lost to you. You will never see her again. The king's son was beside himself with pain, and in his despair he leapt down from the tower. He escaped with his life, but the thorns into which he fell pierced his eyes. Then he wandered quite blind about the forest, ate nothing but roots and berries, and did naught but lament and weep over the loss of his dearest wife. Thus he roamed about in misery for some years, and at length came to the desert where rapunzel, with the twins to which she had given birth, a boy and a girl, lived in wretchedness.`n`nHe heard a voice, and it seemed so familiar to him that he went towards it, and when he approached, rapunzel knew him and fell on his neck and wept. Two of her tears wetted his eyes and they grew clear again, and he could see with them as before. He led her to his kingdom where he was joyfully received, and they lived for a long time afterwards, happy and contented.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>