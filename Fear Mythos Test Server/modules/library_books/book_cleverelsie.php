<?php
//Hooked into Library Card system
function book_cleverelsie_getmoduleinfo(){
	$info = array(
		"name"=>"Clever Elsie",
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

function book_cleverelsie_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_cleverelsie_uninstall(){
	return true;
}

function book_cleverelsie_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("Clever Elsie", "runmodule.php?module=book_cleverelsie");
		break;
	}
	}
	return $args;
}

function book_cleverelsie_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bClever Elsie`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There was once a man who had a daughter who was called clever elsie. And when she had grown up her father said, we will get her married. Yes, said the mother, if only someone would come who would have her. At length a man came from a distance and wooed her, who was called Hans, but he stipulated that clever elsie should be really smart. Oh, said the father, she has plenty of good sense. And the mother said, oh, she can see the wind coming up the street, and hear the flies coughing.`n`nWell, said Hans, if she is not really smart, I won't have her. When they were sitting at dinner and had eaten, the mother said, elsie, go into the cellar and fetch some beer. Then clever elsie took the pitcher from the wall, went into the cellar, and tapped the lid briskly as she went, so that the time might not appear long. When she was below she fetched herself a chair, and set it before the barrel so that she had no need to stoop, and did not hurt her back or do herself any unexpected injury. Then she placed the can before her, and turned the tap, and while the beer was running she would not let her eyes be idle, but looked up at the wall, and after much peering here and there, saw a pick-axe exactly above her, which the masons had accidentally left there.`n`nThen clever elsie began to weep, and said, if I get Hans, and we have a child, and he grows big, and we send him into the cellar here to draw beer, then the pick-axe will fall on his head and kill him. Then she sat and wept and screamed with all the strength of her body, over the misfortune which lay before her. Those upstairs waited for the drink, but clever elsie still did not come. Then the woman said to the servant, just go down into the cellar and see where elsie is. The maid went and found her sitting in front of the barrel, screaming loudly. Elsie, why do you weep, asked the maid. Ah, she answered, have I not reason to weep. If I get Hans, and we have a child, and he grows big, and has to draw beer here, the pick-axe will perhaps fall on his head, and kill him. Then said the maid, what a clever elsie we have. And sat down beside her and began loudly to weep over the misfortune. After a while, as the maid did not come back, those upstairs were thirsty for the beer, the man said to the boy, just go down into the cellar and see where elsie and the girl are.`n`nThe boy went down, and there sat clever elsie and the girl both weeping together. Then he asked, why are you weeping, ah, said elsie, have I not reason to weep. If I get Hans, and we have a child, and he grows big, and has to draw beer here, the pick-axe will fall on his head and kill him. Then said the boy, what a clever elsie we have. And sat down by her, and likewise began to howl loudly. Upstairs they waited for the boy, but as he still did not return, the man said to the woman, just go down into the cellar and see where elsie is.`n`nThe woman went down, and found all three in the midst of their lamentations, and inquired what was the cause, then elsie told her also that her future child was to be killed by the pick-axe, when it grew big and had to draw beer, and the pick-axe fell down. Then said the mother likewise, what a clever elsie we have. And sat down and wept with them. The man upstairs waited a short time, but as his wife did not come back and his thirst grew ever greater, he said, I must go into the cellar myself and see where elsie is. But when he got into the cellar, and they were all sitting together crying, and he heard the reason, and that elsie's child was the cause, and that elsie might perhaps bring one into the world some day, and that he might be killed by the pick-axe, if he should happen to be sitting beneath it, drawing beer just at the very time when it fell down, he cried, oh, what a clever elsie. And sat down, and likewise wept with them.`n`nThe bridegroom stayed upstairs alone for a long time, then as no one would come back he thought, they must be waiting for me below, I too must go there and see what they are about. When he got down, the five of them were sitting screaming and lamenting quite piteously, each out-doing the other. What misfortune has happened then, he asked. Ah, dear Hans, said elsie, if we marry each other and have a child, and he is big, and we perhaps send him here to draw something to drink, then the pick-axe which has been left up there might dash his brains out if it were to fall down, so have we not reason to weep. Come, said Hans, more understanding than that is not needed for my household, as you are such a clever elsie, I will have you. And he seized her hand, took her upstairs with him, and married her.`n`nAfter Hans had had her some time, he said, wife, I am going out to work and earn some money for us, go into the field and cut the corn that we may have some bread. Yes, dear Hans, I will do that. After Hans had gone away, she cooked herself some good broth and took it into the field with her. When she came to the field she said to herself, what shall I do, shall I cut first, or shall I eat first. Oh, I will eat first. Then she drank her cup of broth, and when she was fully satisfied, she once more said, what shall I do. Shall I cut first, or shall I sleep first. I will sleep first. Then she lay down among the corn and fell asleep. Hans had been at home for a long time, but elsie did not come, then said he, what a clever elsie I have, she is so industrious that she does not even come home to eat. But when evening came and she still stayed away, Hans went out to see what she had cut, but nothing was cut, and she was lying among the corn asleep. Then Hans hastened home and brought a fowler's net with little bells and hung it round about her, and she still went on sleeping. Then he ran home, shut the house-door, and sat down in his chair and worked. At length, when it was quite dark, clever elsie awoke and when she got up there was a jingling all round about her, and the bells rang at each step which she took. Then she was alarmed, and became uncertain whether she really was clever elsie or not, and said, is it I, or is it not I. But she knew not what answer to make to this, and stood for a time in doubt, at length she thought, I will go home and ask if it be I, or if it be not I, they will be sure to know. She ran to the door of her own house, but it was shut, then she knocked at the window and cried, Hans, is elsie within. Yes, answered Hans, she is within. Hereupon she was terrified, and said, ah, heavens. Then it is not I. And went to another door, but when the people heard the jingling of the bells they would not open it, and she could get in nowhere. Then she ran out of the village, and no one has seen her since.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>