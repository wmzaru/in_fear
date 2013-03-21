<?php
//Hooked into Library Card system
function book_thepink_getmoduleinfo(){
	$info = array(
		"name"=>"The Pink",
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

function book_thepink_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_thepink_uninstall(){
	return true;
}

function book_thepink_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Pink", "runmodule.php?module=book_thepink");
		break;
	}
	}
	return $args;
}

function book_thepink_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Pink`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There was once upon a time a queen to whom God had given no children. Every morning she went into the garden and prayed to God in heaven to bestow on her a son or a daughter. Then an angel from heaven came to her and said, be at rest, you shall have a son with the power of wishing, so that whatsoever in the world he wishes for, that shall he have. Then she went to the king, and told him the joyful tidings, and when the time was come she gave birth to a son, and the king was filled with gladness.`n`nEvery morning she went with the child to the garden where the wild beasts were kept, and washed herself there in a clear stream. It happened once when the child was a little older, that it was lying in her arms and she fell asleep. Then came the old cook, who knew that the child had the power of wishing, and stole it away, and he took a hen, and cut it in pieces, and dropped some of its blood on the queen's apron and on her dress. Then he carried the child away to a secret place, where a nurse was obliged to suckle it, and he ran to the king and accused the queen of having allowed her child to be taken from her by the wild beasts. When the king saw the blood on her apron, he believed this, fell into such a passion that he ordered a high tower to be built, in which neither sun nor moon could be seen, and had his wife put into it, and walled up. Here she was to stay for seven years without meat or drink, and die of hunger. But God sent two angels from heaven in the shape of white doves, which flew to her twice a day, and carried her food until the seven years were over.`n`nThe cook, however, thought to himself, if the child has the power of wishing, and I am here, he might very easily get me into trouble. So he left the palace and went to the boy, who was already big enough to speak, and said to him, wish for a beautiful palace for yourself with a garden, and all else that pertains to it. Scarcely were the words out of the boy's mouth, when everything was there that he had wished for. After a while the cook said to him, it is not well for you to be so alone, wish for a pretty girl as a companion. Then the king's son wished for one, and she immediately stood before him, and was more beautiful than any painter could have painted her.`n`nThe two played together, and loved each other with all their hearts, and the old cook went out hunting like a nobleman. The thought occurred to him, however, that the king's son might some day wish to be with his father, and thus bring him into great peril. So he went out and took the maiden aside, and said, to-night when the boy is asleep, go to his bed and plunge this knife into his heart, and bring me his heart and tongue, and if you do not do it, you shall lose your life.`n`nThereupon he went away, and when he returned next day she had not done it, and said, why should I shed the blood of an innocent boy who has never harmed anyone. The cook once more said, if you do not do it, it shall cost you your own life.`n`nWhen he had gone away, she had a little hind brought to her, and ordered her to be killed, and took her heart and tongue, and laid them on a plate, and when she saw the old man coming, she said to the boy, lie down in your bed, and draw the clothes over you. Then the wicked wretch came in and said, where are the boy's heart and tongue. The girl reached the plate to him, but the king's son threw off the quilt, and said, you old sinner, why did you want to kill me. Now will I pronounce thy sentence. You shall become a black poodle and have a gold collar round your neck, and shall eat burning coals, till the flames burst forth from your throat. And when he had spoken these words, the old man was changed into a poodle dog, and had a gold collar round his neck, and the cooks were ordered to bring up some live coals, and these he ate, until the flames broke forth from his throat.`n`nThe king's son remained there a short while longer, and he thought of his mother, and wondered if she were still alive. At length he said to the maiden, I will go home to my own country, if you will go with me, I will provide for you.`n`nAh, she replied, the way is so long, and what shall I do in a strange land where I am unknown. As she did not seem quite willing, and as they could not be parted from each other, he wished that she might be changed into a beautiful pink, and took her with him. Then he went away to his own country, and the poodle had to run after him.`n`nHe went to the tower in which his mother was confined, and as it was so high, he wished for a ladder which would reach up to the very top. Then he mounted up and looked inside, and cried, beloved mother, lady queen, are you still alive, or are you dead. She answered, I have just eaten, and am still satisfied, for she thought the angels were there. Said he, I am your dear son, whom the wild beasts were said to have torn from your arms, but I am alive still, and will soon set you free.`n`nThen he descended again, and went to his father, and caused himself to be ammounced as a strange huntsman, and asked if he could offer him service. The king said yes, if he was skilful and could get game for him, he should come to him, but that deer had never taken up their quarters in any part of the district or country. Then the huntsman promised to procure as much game for him as he could possibly use at the royal table. So he summoned all the huntsmen together, and bade them go out into the forest with him. And he went with them and made them form a great circle, open at one end where he stationed himself, and began to wish.`n`nTwo hundred deer and more came running inside the circle at once, and the huntsmen shot them. Then they were all placed on sixty country carts, and driven home to the king, and for once he was able to deck his table with game, after having had none at all for years.`n`nNow the king felt great joy at this, and commanded that his entire household should eat with him next day, and made a great feast. When they were all assembled together, he said to the huntsmen, as you are so clever, you shall sit by me. He replied, lord king, your majesty must excuse me, I am a poor huntsman. But the king insisted on it, and said, you shall sit by me, until he did it. Whilst he was sitting there, he thought of his dearest mother, and wished that one of the king's principal servants would begin to speak of her, and would ask how it was faring with the queen in the tower, and if she were alive still, or had perished.`n`nHardly had he formed the wish than the marshal began, and said, your majesty, we live joyously here, but how is the queen living in the tower. Is she still alive, or has she died? But the king replied, she let my dear son be torn to pieces by wild beasts, I will not have her named. Then the huntsman arose and said, gracious lord father, she is alive still, and I am her son, and I was not carried away by wild beasts, but by that wretch the old cook, who tore me from her arms when she was asleep, and sprinkled her apron with the blood of a chicken.`n`nThereupon he took the dog with the golden collar, and said, that is the wretch, and caused live coals to be brought, and these the dog was compelled to devour before the sight of all, until flames burst forth from its throat. On this the huntsman asked the king if he would like to see the dog in his true shape, and wished him back into the form of the cook, in the which he stood immediately, with his white apron, and his knife by his side. When the king saw him he fell into a passion, and ordered him to be cast into the deepest dungeon.`n`nThen the huntsman spoke further and said, father, will you see the maiden who brought me up so tenderly and who was afterwards to murder me, but did not do it, though her own life depended on it. The king replied, yes, I would like to see her. The son said, most gracious father, I will show her to you in the form of a beautiful flower, and he thrust his hand into his pocket and brought forth the pink, and placed it on the royal table, and it was so beautiful that the king had never seen one to equal it. Then the son said, now will I show her to you in her own form, and wished that she might become a maiden, and she stood there looking so beautiful that no painter could have made her look more so.`n`nAnd the king sent two waiting-maids and two attendants into the tower, to fetch the queen and bring her to the royal table. But when whe was led in she ate nothing, and said, the gracious and merciful God who has supported me in the tower, will soon set me free. She lived three days more, and then died happily, and when she was buried, the two white doves which had brought her food to the tower, and were angels of heaven, followed her body and seated themselves on her grave. The aged king ordered the cook to be torn in four pieces, but grief consumed the king's own heart, and he soon died. His son married the beautiful maiden whom he had brought with him as a flower in his pocket, and whether they are still alive or not, is known to God.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`n`n`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>