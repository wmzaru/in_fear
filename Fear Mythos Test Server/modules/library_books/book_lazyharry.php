<?php
//Hooked into Library Card system
function book_lazyharry_getmoduleinfo(){
	$info = array(
		"name"=>"Lazy Harry",
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

function book_lazyharry_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_lazyharry_uninstall(){
	return true;
}

function book_lazyharry_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("Lazy Harry", "runmodule.php?module=book_lazyharry");
		break;
	}
	}
	return $args;
}

function book_lazyharry_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bLazy Harry`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("Harry was lazy, and although he had nothing else to do but drive his goat daily to pasture, he nevertheless groaned when he went home after his day's work was done. It is indeed a heavy burden, said he, and a wearisome employment to drive a goat into the field this way year after year, till late into the autumn. If one could but lie down and sleep, but no, one must have one's eyes open lest the goat hurts the young trees, or squeezes itself through the hedge into a garden, or runs away altogether. How can one have any rest, or enjoy one's life. He seated himself, collected his thoughts, and considered how he could set his shoulders free from this burden. For a long time all thinking was to no purpose, but suddenly it was as if scales fell from his eyes. I know what I will do, he cried, I will marry fat Trina who has also a goat, and can take mine out with hers, and then I shall have no more need to trouble myself. So Harry got up, set his weary legs in motion, and went right across the street, for it was no farther, to where the parents of fat Trina lived, and asked for their industrious and virtuous daughter in marriage. The parents did not reflect long. Birds of a feather, flock together, they thought, and consented. So fat Trina became Harry's wife, and led out both the goats. Harry had a good time of it, and had no work that he required to rest from but his own idleness. He went out with her only now and then, and said, I merely do it that I may afterwards enjoy rest more, otherwise one loses all feeling for it. But fat Trina was no less idle.`n`nDear Harry, said she one day, why should we make our lives so toilsome when there is no need for it, and thus ruin the best days of our youth. Would it not be better for us to give the two goats which disturb us every morning in our sweetest sleep with their bleating, to our neighbor, and he will give us a beehive for them. We will put the beehive in a sunny place behind the house, and trouble ourselves no more about it. Bees do not require to be taken care of, or driven into the field. They fly out and find the way home again for themselves, and collect honey without giving the very least trouble. You have spoken like a sensible woman, replied Harry. We will carry out your proposal without delay, and besides all that, honey tastes better and nourishes one better than goat's milk, and it can be kept longer too. The neighbor willingly gave a beehive for the two goats. The bees flew in and out from early morning till late evening without ever tiring, and filled the hive with the most beautiful honey, so that in autumn Harry was able to take a whole pitcherful out of it. They placed the jug on a board which was fixed to the wall of their bed-room, and as they were afraid that it might be stolen, or that the mice might find it, Trina brought in a stout hazel-stick and put it beside her bed, so that without unnecessary motion she might reach it with her hand, and drive away the uninvited guests.`n`nLazy Harry did not like to leave his bed before noon. He who rises early, said he, wastes his substance. One morning when he was still lying amongst the feathers in broad daylight, resting after his long sleep, he said to his wife, women are fond of sweet things, and you are always tasting the honey in private. It will be better for us to exchange it for a goose with a young gosling, before you eat up the whole of it. But, answered Trina, not before we have a child to take care of them. Am I to worry myself with the little geese, and spend all my strength on them to no purpose. Do you think, said Harry, that the youngster will look after geese. Now-a-days children no longer obey, they do according to their own fancy, because they consider themselves cleverer than their parents, just like that lad who was sent to seek the cow and chased three blackbirds. Oh, replied Trina, this one shall fare badly if he does not do what I say. I will take a stick and belabor his skin with more blows than I can count. Look, Harry, cried she in her zeal, and seized the stick with which she used to drive the mice away, look, this is the way I will fall on him. She reached her arm out to strike, but unhappily hit the honey-pitcher above the bed. The pitcher struck against the wall and fell down in shards, and the fine honey streamed out on the ground. There lie the goose and the young gosling, said Harry, and want no looking after. But it is lucky that the pitcher did not fall on my head.`n`nWe have all reason to be satisfied with our lot. And then as he saw that there was still some honey in one of the shards he stretched out his hand for it, and said quite gaily, the remains, my wife, we will still eat with relish, and we will rest a little after the fright we have had. What does it matter if we do get up a little later. The day is always long enough. Yes, answered Trina, we shall always get to the end of it at the proper time. You know, the snail was once asked to a wedding and set out to go, but arrived at the christening. In front of the house it fell over the fence, and said, speed does no good.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`n`n`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>