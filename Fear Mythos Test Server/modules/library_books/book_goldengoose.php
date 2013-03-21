<?php
//Hooked into Library Card system
function book_goldengoose_getmoduleinfo(){
	$info = array(
		"name"=>"The Golden Goose",
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

function book_goldengoose_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_goldengoose_uninstall(){
	return true;
}

function book_goldengoose_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Golden Goose", "runmodule.php?module=book_goldengoose");
		break;
	}
	}
	return $args;
}

function book_goldengoose_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Golden Goose`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There was a man who had three sons, the youngest of whom was called Dummling, and was despised, mocked, and sneered at on every occasion.`n`nIt happened that the eldest wanted to go into the forest to hew wood, and before he went his mother gave him a beautiful sweet cake and a bottle of wine in order that he might not suffer from hunger or thirst.`n`nWhen he entered the forest he met a little grey-haired old man who bade him good-day, and said, do give me a piece of cake out of your pocket, and let me have a draught of your wine, I am so hungry and thirsty. But the clever son answered, if I give you my cake and wine, I shall have none for myself, be off with you, and he left the little man standing and went on.`n`nBut when he began to hew down a tree, it was not long before he made a false stroke, and the axe cut him in the arm, so that he had to go home and have it bound up. And this was the little grey man's doing.`n`nAfter this the second son went into the forest, and his mother gave him, like the eldest, a cake and a bottle of wine. The little old grey man met him likewise, and asked him for a piece of cake and a drink of wine. But the second son, too, said sensibly enough, what I give you will be taken away from myself, be off, and he left the little man standing and went on. His punishment, however, was not delayed, when he had made a few blows at the tree he struck himself in the leg, so that he had to be carried home.`n`nThen Dummling said, father, do let me go and cut wood. The father answered, your brothers have hurt themselves with it, leave it alone, you do not understand anything about it. But Dummling begged so long that at last he said, just go then, you will get wiser by hurting yourself. His mother gave him a cake made with water and baked in the cinders, and with it a bottle of sour beer.`n`nWhen he came to the forest the little old grey man met him likewise, and greeting him, said, give me a piece of your cake and a drink out of your bottle, I am so hungry and thirsty.`n`nDummling answered, I have only cinder-cake and sour beer, if that pleases you, we will sit down and eat. So they sat down, and when Dummling pulled out his cinder-cake, it was a fine sweet cake, and the sour beer had become good wine. So they ate and drank, and after that the little man said, since you have a good heart, and are willing to divide what you have, I will give you good luck. There stands an old tree, cut it down, and you will find something at the roots. Then the little man took leave of him.`n`nDummling went and cut down the tree, and when it fell there was a goose sitting in the roots with feathers of pure gold. He lifted her up, and taking her with him, went to an inn where he thought he would stay the night. Now the host had three daughters, who saw the goose and were curious to know what such a wonderful bird might be, and would have liked to have one of its golden feathers.`n`nThe eldest thought, I shall soon find an opportunity of pulling out a feather, and as soon as Dummling had gone out she seized the goose by the wing, but her finger and hand remained sticking fast to it.`n`nThe second came soon afterwards, thinking only of how she might get a feather for herself, but she had scarcely touched her sister than she was held fast.`n`nAt last the third also came with the like intent, and the others screamed out, keep away, for goodness, sake keep away. But she did not understand why she was to keep away. The others are there, she thought, I may as well be there too, and ran to them, but as soon as she had touched her sister, she remained sticking fast to her. So they had to spend the night with the goose.`n`nThe next morning Dummling took the goose under his arm and set out, without troubling himself about the three girls who were hanging on to it. They were obliged to run after him continually, now left, now right, wherever his legs took him.`n`nIn the middle of the fields the parson met them, and when he saw the procession he said, for shame, you good-for-nothing girls, why are you running across the fields after this young man. Is that seemly? At the same time he seized the youngest by the hand in order to pull her away, but as soon as he touched her he likewise stuck fast, and was himself obliged to run behind.`n`nBefore long the sexton came by and saw his master, the parson, running behind three girls. He was astonished at this and called out, hi, your reverence, whither away so quickly. Do not forget that we have a christening to-day, and running after him he took him by the sleeve, but was also held fast to it. Whilst the five were trotting thus one behind the other, two laborers came with their hoes from the fields, the parson called out to them and begged that they would set him and the sexton free. But they had scarcely touched the sexton when they were held fast, and now there were seven of them running behind Dummling and the goose.`n`nSoon afterwards he came to a city, where a king ruled who had a daughter who was so serious that no one could make her laugh. So he had put forth a decree that whosoever should be able to make her laugh should marry her. When Dummling heard this, he went with his goose and all her train before the king's daughter, and as soon as she saw the seven people running on and on, one behind the other, she began to laugh quite loudly, and as if she would never stop.`n`nThereupon Dummling asked to have her for his wife, but the king did not like the son-in-law, and made all manner of excuses and said he must first produce a man who could drink a cellarful of wine.`n`nDummling thought of the little grey man, who could certainly help him, so he went into the forest, and in the same place where he had felled the tree, he saw a man sitting, who had a very sorrowful face. Dummling asked him what he was taking to heart so sorely, and he answered, I have such a great thirst and cannot quench it, cold water I cannot stand, a barrel of wine I have just emptied, but that to me is like a drop on a hot stone.`n`nThere, I can help you, said Dummling, just come with me and you shall be satisfied.`n`nHe led him into the king's cellar, and the man bent over the huge barrels, and drank and drank till his loins hurt, and before the day was out he had emptied all the barrels. Then Dummling asked once more for his bride, but the king was vexed that such an ugly fellow, whom everyone called Dummling, should take away his daughter, and he made a new condition, he must first find a man who could eat a whole mountain of bread. Dummling did not think long, but went straight into the forest, where in the same place there sat a man who was tying up his body with a strap, and making an awful face, and saying, I have eaten a whole ovenful of rolls, but what good is that when one has such a hunger as I. My stomach remains empty, and I must tie myself up if I am not to die of hunger.`n`nAt this Dummling was glad, and said, get up and come with me, you shall eat yourself full. He led him to the king's palace, where all the flour in the whole kingdom was collected, and from it he caused a huge mountain of bread to be baked. The man from the forest stood before it, began to eat, and by the end of one day the whole mountain had vanished. Then Dummling for the third time asked for his bride, but the king again sought a way out, and ordered a ship which could sail on land and on water. As soon as you come sailing back in it, said he, you shall have my daughter for wife.`n`nDummling went straight into the forest, and there sat the little grey man to whom he had given his cake. When he heard what Dummling wanted, he said, since you have given me to eat and to drink, I will give you the ship, and I do all this because you once were kind to me. Then he gave him the ship which could sail on land and water, and when the king saw that, he could no longer prevent him from having his daughter. The wedding was celebrated, and after the king's death, Dummling inherited his kingdom and lived for a long time contentedly with his wife.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`n`n`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>