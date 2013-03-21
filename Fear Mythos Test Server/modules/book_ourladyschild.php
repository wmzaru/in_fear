<?php
//Hooked into Library Card system
function book_ourladyschild_getmoduleinfo(){
	$info = array(
		"name"=>"Our Ladys Child",
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

function book_ourladyschild_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_ourladyschild_uninstall(){
	return true;
}

function book_ourladyschild_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("Our Ladys Child", "runmodule.php?module=book_ourladyschild");
		break;
	}
	}
	return $args;
}

function book_ourladyschild_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bOur Ladys Child`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("Hard by a great forest dwelt a wood-cutter with his wife, who had an only child, a little girl three years old. They were so poor, however, that they no longer had daily bread, and did not know how to get food for her. One morning the wood-cutter went out sorrowfully to his work in the forest, and while he was cutting wood, suddenly there stood before him a tall and beautiful woman with a crown of shining stars on her head, who said to him 'I am the virgin mary, mother of the child jesus. You are poor and needy, bring your child to me, I will take her with me and be her mother, and care for her.' The wood-cutter obeyed, brought his child, and gave her to the virgin mary, who took her up to heaven with her. There the child fared well, ate sugar-cakes, and drank sweet milk, and her clothes were of gold, and the little angels played with her. And when she was fourteen years of age, the virgin mary called her one day and said 'dear child, I am about to make a long journey, so take into your keeping the keys of the thirteen doors of heaven. Twelve of these you may open, and behold the glory which is within them, but the thirteenth, to which this little key belongs, is forbidden you. Take care not to open it, or you will be unhappy.' The girl promised to be obedient, and when the virgin mary was gone, she began to examine the dwellings of the kingdom of heaven. Each day she opened one of them, until she had made the round of the twelve. In each of them sat one of the apostles in the midst of a great light, and she rejoiced in all the magnificence and splendor, and the little angels who always accompanied her rejoiced with her. Then the forbidden door alone remained, and she felt a great desire to know what could be hidden behind it, and said to the angels 'I will not open it entirely, and I will not go inside, but I will unlock it so that we can see just a little through the opening.' 'Oh'no, said the little angels, 'that would be a sin. The virgin mary has forbidden it, and it might easily cause your unhappiness.' Then she was silent, but the desire in her heart was not stilled, but gnawed there and tormented her, and let her have no rest. And once when the angels had all gone out, she thought 'now I am quite alone, and I could peep in. If I do, no one will ever know.' She sought out the key, and when she had got it in her hand, she put it in the lock, and when she had put it in, she turned it round as well. Then the door sprang open, and she saw there the trinity sitting in fire and splendor. She stayed there awhile, and looked at everything in amazement, then she touched the light a little with her finger, and her finger became quite golden. Immediately a great fear fell on her. She shut the door violently, and ran hi there. But her terror would not quit her, let her do what she 'Yes, said the girl, for the second time. Then she perceived the finger which had become golden from touching the fire of heaven, and saw well that the child had sinned, and said for the third time 'have you not done it.' 'No, said the girl for the third time. Then said the virgin mary 'you have not obeyed me, and besides that you have lied, you are no longer worthy to be in heaven.' Then the girl fell into a deep sleep, and when she awoke she lay on the earth below, and in the midst of a wilderness. She wanted to cry out, but she could bring forth no sound. She sprang up and wanted to run away, but whithersoever she turned herself, she was continually held back by thick hedges of thorns through which she could not break. In the desert, in which she was imprisoned, there stood an old hollow tree, and this had to be her dwelling-place. Into this she crept when night came, and here she slept. Here, too, she found a shelter from might, and her heart beat continually and would not be still, the gold too stayed on her finger, and would not go away, let her rub it and wash it never so much. It was not long before the virgin mary came back from her journey. She called the girl before her, and asked to have the keys of heaven back. When the maiden gave her the bunch, the virgin looked into her eyes and said 'have you not opened the thirteenth door also.' 'No, she replied. Then she laid her hand on the girl's heart, and felt how it beat and beat, and saw right well that she had disobeyed her order and had opened the door. Then she said once again 'are you certain that you have not done it.' storm and rain, but it was a miserable life, and bitterly did she weep when she remembered how happy she had been in heaven, and how the angels had played with her. Roots and wild berries were her only food, and for these she sought as far as she could go. In the autumn she picked up the fallen nuts and leaves, and carried them into the hole. The nuts were her food in winter, and when snow and ice came, she crept amongst the leaves like a poor little animal that she might not freeze. Before long her clothes were all torn, and one bit of them after another fell off her. As soon, however, as the sun shone warm again, she went out and sat in front of the tree, and her long hair covered her on all sides like a mantle. Thus she sat year after year, and felt the pain and the misery of the world. One day, when the trees were once more clothed in fresh green, the king of the country was hunting in the forest, and followed a roe, and as it had fled into the thicket which shut in this part of the forest, he got off his horse, tore the bushes asunder, and cut himself a path with his sword. When he had at last forced his way through, he saw a wonderfully beautiful maiden sitting under the tree, and she sat there and was entirely covered with her golden hair down to her very feet. He stood still and looked at her full of surprise, then he spoke to her and said 'who are you. Why are you sitting here in the wilderness.' But she gave no answer, for she could not open her mouth. The king continued 'will you go with me to my castle. Then she just nodded her head a little. The king took her in his arms, carried her to his horse, and rode home with her, and when he reached the royal castle he caused her to be dressed in beautiful garments, and gave her all things in abundance. Although she could not speak, she was still so beautiful and charming that he began to love her with all his heart, and it was not long before he married her. After a year or so had passed, the queen brought a son into the world. Thereupon the virgin mary appeared to her in the night when she lay in her bed alone, and said 'if you will tell the truth and confess that you did unlock the forbidden door, I will open your mouth and give you back your speech, but if you persevere in your sin, and deny obstinately, I will take your new-born child away with me.' The the queen was permitted to answer, but she remained hard, and said 'no, I did not open the forbidden door, and the virgin mary took the new-born child from her arms, and vanished with it. Next morning when the child was not to be found, it was whispered among the people that the queen was a man-eater, and had put her own child to death. She heard all this and could say nothing to the contrary, but the king would not believe it, for he loved her so much. When a year had gone by the queen again bore a son, and in the night the virgin mary again came to her, and said 'if you will confess that you opened the forbidden door, I will give you your child back and untie your tongue but if you continue in sin and deny it, I will take away with me this new child also.' Then the queen again said 'no, I did not open the forbidden door.' And the virgin took the child out of her arms, and away with her to heaven. Next morning, when this child also had disappeared, the people declared quite loudly that the queen had devoured it, and the king's councillors demanded that she should be brought to justice. The king however, loved her so dearly that he would not believe it, and commanded the councillors under pain of death not to say any more about it. The following year the queen gave birth to a beautiful little daughter, and for the third time the virgin mary appeared to her in the night and said 'follow me.' She took the queen by the hand and led her to heaven, and showed her there her two eldest children, who smiled at her, and were playing with the ball of the world. When the queen rejoiced thereat, the virgin mary said 'is your heart not yet softened. If you will own that you opened the forbidden door, I will give you back your two little sons.' But for the third time the queen answered 'no, I did not open the forbidden door.' Then the virgin let her sink down to earth once more, and took from her likewise her third child.  `n`nNext morning, when the loss was reported abroad, all the people cried loudly 'the queen is a man-eater. She must be judged, and the king was no longer able to restrain his councillors. Thereupon a trial was held, and as she could not answer, and defend herself, she was condemned to be burnt at the stake. The wood was got together, and when she was fast bound to the stake, and the fire began to burn round about her, the hard ice of pride melted, her heart was moved by repentance, and she thought 'if I could but confess before my death that I opened the door.' Then her voice came back to her, and she cried out loudly 'yes, mary, I did it, and straight-way rain fell from the sky and extinguished the flames of fire, and a light broke forth above her, and the virgin mary descended with the two little sons by her side, and the new-born daughter in her arms. She spoke kindly to her, and said 'he who repents his sin and acknowledges it, is forgiven.' Then she gave her the three children, untied her tongue, and granted her happiness for her whole life. ");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>