<?php
//Hooked into Library Card system
function book_threebrothers_getmoduleinfo(){
	$info = array(
		"name"=>"The Three Brothers",
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

function book_threebrothers_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_threebrothers_uninstall(){
	return true;
}

function book_threebrothers_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Three Brothers", "runmodule.php?module=book_threebrothers");
		break;
	}
	}
	return $args;
}

function book_threebrothers_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Three Brothers`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There was once a man who had three sons, and nothing else in the world but the house in which he lived. Now each of the sons wished to have the house after his father's death, but the father loved them all alike, and did not know what to do, he did not wish to sell the house, because it had belonged to his forefathers, else he might have divided the money amongst them. At last he conceived a plan, and he said to his sons, 'Go into the world, and try each of you to learn a trade, and, when you all come back, he who makes the best masterpiece shall have the house.'`n`nThe sons were well content with this, and the eldest determined to be a blacksmith, the second a barber, and the third a fencing-master. They fixed a time when they should all come home again, and then each went his way.`n`nIt chanced that they all found skillful masters, who taught them their trades well. The blacksmith had to shoe the king's horses, and he thought to himself, 'The house is mine, without doubt.' The barber shaved only distinguished people, and he too already looked upon the house as his own. The fencing-master suffered many a blow, but he grit his teeth, and let nothing vex him, for, said he to himself, 'If you are afraid of a blow, you'll never win the house.'`n`nWhen the appointed time had gone by, the three brothers came back home to their father, but they did not know how to find the best opportunity for showing their skill, so they sat down and consulted together. As they were sitting thus, all at once a hare came running across the field. Ah, ha, just in time, said the barber. So he took his basin and soap, and lathered away until the hare drew near, then he soaped and shaved off the hare's whiskers whilst he was running at the top of his speed, and did not even cut his skin or injure a hair on his body. 'Well done,' said the old man. 'If the others do not make a great effort, the house is yours.'`n`nSoon after, up came a nobleman in his coach, dashing along at full speed. 'Now you shall see what I can do, father,' said the blacksmith. So away he ran after the coach, took all four shoes off the feet of one of the horses whilst he was galloping, and put on four new shoes without stopping him. 'You are a fine fellow, and as clever as your brother,' said his father. 'I do not know to which I ought to give the house.'`n`nThen the third son said, 'Father, let me have my turn, if you please,' and, as it was beginning to rain, he drew his sword, and flourished it backwards and forwards above his head so fast that not a drop fell upon him. It rained still harder and harder, till at last it came down in torrents, but he only flourished his sword faster and faster, and remained as dry as if he were sitting in a house. When his father saw this he was amazed, and said, 'This is the masterpiece, the house is yours.'`n`nHis brothers were satisfied with this, as was agreed beforehand, and, as they loved one another very much, they all three stayed together in the house, followed their trades, and, as they had learnt them so well and were so clever, they earned a great deal of money. Thus they lived together happily until they grew old, and at last, when one of them fell sick and died, the two others grieved so sorely about it that they also fell ill, and soon after died. And because they had been so clever, and had loved one another so much, they were all laid in the same grave.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`n`n`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>