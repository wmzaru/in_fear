<?php
//Hooked into Library Card system
function book_theraven_getmoduleinfo(){
	$info = array(
		"name"=>"The Raven",
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

function book_theraven_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_theraven_uninstall(){
	return true;
}

function book_theraven_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Raven", "runmodule.php?module=book_theraven");
		break;
	}
	}
	return $args;
}

function book_theraven_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Raven`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There was once upon a time a queen who had a little daughter who was still so young that she had to be carried. One day the child was naughty, and the mother might say what she liked, but the child would not be quiet. Then she became impatient, and as the ravens were flying about the palace, she opened the window and said, I wish you were a raven and would fly away, and then I should have some rest. Scarcely had she spoken the words, before the child was changed into a raven, and flew from her arms out of the window. It flew into a dark forest, and stayed in it a long time, and the parents heard nothing of their child.`n`nThen one day a man was on his way through this forest and heard the raven crying, and followed the voice, and when he came nearer, the bird said, I am a king's daughter by birth, and am bewitched, but you can set me free. What am I to do, asked he. She said, go further into the forest, and you will find a house, wherein sits an aged woman, who will offer you meat and drink, but you must accept nothing, for if you eat and drink anything, you will fall into a sleep, and then you will not be able to set me free. In the garden behind the house there is a great heap of tan, and on this you shall stand and wait for me. For three days I will come every afternoon at two o'clock in a carriage. On the first day four white horses will be harnessed to it, then four chestnut horses, and lastly four black ones, but if you are not awake, but sleeping, I shall not be set free. The man promised to do everything that she desired, but the raven said, alas, I know already that you will not set me free, you will accept something from the woman. Then the man once more promised that he would certainly not touch anything either to eat or to drink.`n`nBut when he entered the house the old woman came to him and said, poor man, how faint you are, come and refresh yourself, eat and drink. No, said the man, I will not eat or drink. She, however, let him have no peace, and said, if you will not eat, take one drink out of the glass, one is nothing. Then he let himself be persuaded, and drank. Shortly before two o'clock in the afternoon he went into the garden to the tan heap to wait for the raven. As he was standing there, his weariness all at once became so great that he could not struggle against it, and lay down for a short time, but he was determined not to go to sleep. Hardly, however, had he lain down, than his eyes closed of their own accord, and he fell asleep and slept so soundly that nothing in the world could have aroused him.`n`nAt two o'clock the raven came driving up with four white horses, but she was already in deep grief and said, I know he is asleep. And when she came into the garden, he was indeed lying there asleep on the heap of tan. She alighted from the carriage, went to him, shook him, and called him, but he did not awake. Next day about noon, the old woman came again and brought him food and drink, but he would not take any of it. But she let him have no rest and persuaded him until at length he again took one drink out of the glass. Towards two o'clock he went into the garden to the tan heap to wait for the raven, but all at once felt such a great weariness that his limbs would no longer support him. He could not help himself, and was forced to lie down, and fell into a heavy sleep.`n`nWhen the raven drove up with four brown horses, she was already full of grief, and said, I know he is asleep. She went to him, but there he lay sleeping, and there was no wakening him. Next day the old woman asked what was the meaning of this. He was neither eating nor drinking anything, did he want to die. He replied, I am not allowed to eat or drink, and will not do so. But she set a dish with food, and a glass with wine before him, and when he smelt it he could not resist, and swallowed a deep draught. When the time came, he went out into the garden to the heap of tan, and waited for the king's daughter, but he became still more weary than on the day before, and lay down and slept as soundly as if he had been a stone. At two o'clock the raven came with four black horses, and the coachman and everything else was black. She was already in the deepest grief, and said, I know that he is asleep and cannot set me free.`n`nWhen she came to him, there he was lying fast asleep. She shook him and called him, but she could not waken him. Then she laid a loaf beside him, and after that a piece of meat, and thirdly a bottle of wine, and he might consume as much of all of them as he liked, but they would never grow less. After this she took a gold ring from her finger, and put it on his, and her name was graven on it. Lastly, she laid a letter beside him wherein was written what she had given him, and that none of the things would ever grow less, and in it was also written, I see right well that here you will never be able to set me free, but if you are still willing to do so, come to the golden castle of Stromberg; it lies in your power, of that I am certain. And when she had given him all these things, she seated herself in her carriage, and drove to the golden castle of Stromberg.`n`nWhen the man awoke and saw that he had slept, he was sad at heart, and said, she has certainly driven by, and I have not set her free. Then he perceived the things which were lying beside him, and read the letter wherein was written how everything had happened. So he arose and went away, intending to go to the golden castle of Stromberg, but he did not know where it was. After he had walked about the world for a long time, he entered into a dark forest, and walked for fourteen days, and still could not find his way out. Then it was once more evening, and he was so tired that he lay down in a thicket and fell asleep. Next day he went onwards, and in the evening, as he was again about to lie down beneath some bushes, he heard such a howling and crying that he could not go to sleep. And at the time when people light the candles, he saw one glimmering, and arose and went towards it.`n`nThen he came to a house which seemed very small, for in front of it a great giant was standing. He thought to himself, if I go in, and the giant sees me, it will very likely cost me my life. At length he ventured it and went in. When the giant saw him, he said, it is well that you come, for it is long since I have eaten, I will at once devour you for my supper. I'd rather you did not, said the man, I do not like to be eaten, but if you have any desire to eat, I have quite enough here to satisfy you. If that be true, said the giant, you may be easy, I was only going to devour you because I had nothing else.`n`nThen they went, and sat down to the table, and the man took out the bread, wine, and meat which would never come to an end. This pleases me well, said the giant, and ate to his heart's content. Then the man said to him, can you tell me where the golden castle of Stromberg is. The giant said, I will look at my map, all the towns, and villages, and houses are to be found on it.`n`nHe brought out the map which he had in the room and looked for the castle, but it was not to be found on it. It's no matter, said he, I have some still larger maps in my cupboard upstairs, and we will look at them. But there, too, it was in vain. The man now wanted to set out again, but the giant begged him to wait a few days longer until his brother, who had gone out to bring some provisions, came home. When the brother came home they inquired about the golden castle of Stromberg. He replied, when I have eaten and have had enough, I will look at the map.`n`nThen he went with them up to his chamber, and they searched on his map, but could not find it. Then he brought out still older maps, and they never rested until they found the golden castle of Stromberg, but it was many thousand miles away. How am I to get there, asked the man. The giant said, I have two hours, time, during which I will carry you into the neighborhood, but after that I must be at home to suckle the child that we have.`n`nSo the giant carried the man to about a hundred leagues from the castle, and said, you can very well walk the rest of the way alone. And he turned back, but the man went onwards day and night, until at length he came to the golden castle of Stromberg.`n`nIt stood on a glass-mountain, and the bewitched maiden was driving in her carriage round the castle, and then went inside it. He rejoiced when he saw her and wanted to climb up to her, but when he began to do so he always slipped down the glass again. And when he saw that he could not reach her, he was very worried, and said to himself, I will stay down here below, and wait for her. So he built himself a hut and stayed in it for a whole year, and every day saw the king's daughter driving about above, but never could reach her.`n`nThen one day he saw from his hut three robbers who were beating each other, and cried to them, God be with you. They stopped when they heard the cry, but as they saw no one, they once more began to beat each other, and that too most dangerously. So he again cried, God be with you. Again they stopped, looked round about, but as they saw no one they went on beating each other. Then he cried for the third time, God be with you, and thought, I must see what these three are about, and went thither and asked why they were beating each other so furiously. One of them said that he found a stick, and that when he struck a door with it, that door would spring open. The next said that he had found a mantle, and that whenever he put it on, he was invisible, but the third said he had found a horse on which a man could ride everywhere even up the glass-mountain. And now they did not know whether they ought to have these things in common, or whether they ought to divide them.`n`nThen the man said, I will give you something in exchange for these three things. Money indeed have I not, but I have other things of more value, but first I must make an experiment to see if you have told the truth. Then they put him on the horse, threw the mantle round him, and gave him the stick in his hand, and when he had all these things they were no longer able to see him. So he gave them some vigorous blows and cried, now, vagabonds, you have got what you deserve, are you satisfied. And he rode up the glass-mountain, but when he came in front of the castle at the top, it was shut.`n`nThen he struck the door with his stick, and it sprang open immediately. He went in and ascended the stairs until he came to the hall where the maiden was sitting with a golden globlet of wine before her. She, however, could not see him because he had the mantle on. And when he came up to her, he drew from his finger the ring which she had given him, and threw it into the goblet so that it rang. Then she cried, that is my ring, so the man who is to set me free must be here.`n`nThey searched the whole castle and did not find him, but he had gone out, and had seated himself on the horse and thrown off the mantle. When they came to the door, they saw him and cried aloud in their delight. Then he alighted and took the king's daughter in his arms, but she kissed him and said, now have you set me free, and to-morrow we will celebrate our wedding.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`n`n`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>