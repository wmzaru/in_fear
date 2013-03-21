<?php
//Hooked into Library Card system
function book_twelvehuntsmen_getmoduleinfo(){
	$info = array(
		"name"=>"The Twelve Huntsmen",
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

function book_twelvehuntsmen_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_twelvehuntsmen_uninstall(){
	return true;
}

function book_twelvehuntsmen_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Twelve Huntsmen", "runmodule.php?module=book_twelvehuntsmen");
		break;
	}
	}
	return $args;
}

function book_twelvehuntsmen_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Twelve Huntsmen`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There was once a king's son who had a bride whom he loved very much. And when he was sitting beside her and very happy, news came that his father lay sick unto death, and desired to see him once again before his end. Then he said to his beloved, I must now go and leave you, I give you a ring as a remembrance of me. When I am king, I will return and fetch you.`n`nSo he rode away, and when he reached his father, the latter was dangerously ill, and near his death. He said to him, dear son, I wished to see you once again before my end, promise me to marry as I wish, and he named a certain king's daughter who was to be his wife. The son was in such trouble that he did not think what he was doing, and said, yes, dear father, your will shall be done, and thereupon the king shut his eyes, and died.`n`nWhen therefore the son had been proclaimed king, and the time of mourning was over, he was forced to keep the promise which he had given his father, and caused the king's daughter to be asked in marriage, and she was promised to him. His first betrothed heard of this, and fretted so much about his faithlessness that she nearly died. Then her father said to her, dearest child, why are you so sad. You shall have whatsoever you will. She thought for a moment and said, dear father, I wish for eleven girls exactly like myself in face, figure, and size. The father said, if it be possible, your desire shall be fulfilled, and he caused a search to be made in his whole kingdom, until eleven young maidens were found who exactly resembled his daughter in face, figure, and size.`n`nWhen they came to the king's daughter, she had twelve suits of huntsmen's clothes made, all alike, and the eleven maidens had to put on the huntsmen's clothes, and she herself put on the twelfth suit.`n`nThereupon she took leave of her father, and rode away with them, and rode to the court of her former betrothed, whom she loved so dearly. Then she asked if he required any huntsmen, and if he would take all of them into his service. The king looked at her and did not know her, but as they were such handsome fellows, he said, yes, and that he would willingly take them, and now they were the king's twelve huntsmen.`n`nThe king, however, had a lion which was a wondrous animal, for he knew all concealed and secret things. It came to pass that one evening he said to the king, you think you have twelve huntsmen. Yes, said the king, they are twelve huntsmen. The lion continued, you are mistaken, they are twelve girls.`n`nThe king said, that cannot be true. How will you prove that to me. Oh, just let some peas be strewn in the ante-chamber, answered swered the lion, and then you will soon see. Men have a firm step, and when they walk over the peas none of them stir, but girls trip and skip, and drag their feet, and the peas roll about. The king was well pleased with the counsel, and caused the peas to be strewn.`n`nThere was, however, a servant of the king's who favored the huntsmen, and when he heard that they were going to be put to this test he went to them and repeated everything, and said, the lion wants to make the king believe that you are girls. Then the king's daughter thanked him, and said to her maidens, show some strength, and step firmly on the peas. So next morning when the king had the twelve huntsmen called before him, and they came into the ante-chamber where the peas were lying, they stepped so firmly on them, and had such a strong, sure walk, that not one of the peas either rolled or stirred.`n`nThen they went away again, and the king said to the lion, you have lied to me, they walk just like men. The lion said, they have been informed that they were going to be put to the test, and have assumed some strength. Just let twelve spinning-wheels be brought into the ante-chamber, and they will go to them and be pleased with them, and that is what no man would do. The king liked the advice, and had the spinning-wheels placed in the ante-chamber.`n`nBut the servant, who was well disposed to the huntsmen, went to them, and disclosed the project. So when they were alone the king's daughter said to her eleven girls, show some constraint, and do not look round at the spinning-wheels. And next morning when the king had his twelve huntsmen summoned, they went through the ante-chamber, and never once looked at the spinning wheels.`n`nThen the king again said to the lion, you have deceived me, they are men, for they have not looked at the spinning-wheels. The lion replied, they have learnt that they were going to be put to the test, and have restrained themselves. The king, however, would no longer believe the lion.`n`nThe twelve huntsmen always followed the king to the chase, and his liking for them continually increased. Now it came to pass that once when they were hunting, news came that the king's bride was approaching. When the true bride heard that, it hurt her so much that her heart was almost broken, and she fell fainting to the ground. The king thought something had happened to his dear huntsman, ran up to him, wanted to help him, and drew his glove off. Then he saw the ring which he had given to his first bride, and when he looked in her face he recognized her.`n`nThen his heart was so touched that he kissed her, and when she opened her eyes he said, you are mine, and I am yours, and no one in the world can alter that. He sent a messenger to the other bride, and entreated her to return to her own kingdom, for he had a wife already, and someone who had found an old key did not require a new one. Thereupon the wedding was celebrated, and the lion was again taken into favor, because, after all, he had told the truth.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`n`n`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>