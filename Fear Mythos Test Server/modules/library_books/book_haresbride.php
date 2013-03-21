<?php
//Hooked into Library Card system
function book_haresbride_getmoduleinfo(){
	$info = array(
		"name"=>"The Hare's Bride",
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

function book_haresbride_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_haresbride_uninstall(){
	return true;
}

function book_haresbride_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Hare's Bride", "runmodule.php?module=book_haresbride");
		break;
	}
	}
	return $args;
}

function book_haresbride_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Hare's Bride`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There was once a woman and her daughter who lived in a pretty garden with cabbages. And a little hare came into it, and during the winter time ate all the cabbages. Then says the mother to the daughter, go into the garden, and chase the hare away.`n`nThe girl says to the little hare, sh-sh, hare, you will be eating all our cabbages. Says the hare, come, maiden, and seat yourself on my little hare's tail, and come with me into my little hare's hut. The girl will not do it.`n`nNext day the hare comes again and eats the cabbages, then says the mother to the daughter, go into the garden, and drive the hare away. The girl says to the hare, sh-sh, little hare, you will be eating all the cabbages. The little hare says, maiden, seat yourself on my little hare's tail, and come with me into my little hare's hut. The maiden refuses.`n`nThe third day the hare comes again, and eats the cabbages. On this the mother says to the daughter, go into the garden, and hunt the hare away. Says the maiden, sh-sh, little hare, you will be eating all our cabbages. Says the little hare, come, maiden, seat yourself on my little hare's tail, and come with me into my little hare's hut.`n`nThe girl seats herself on the little hare's tail, and then the hare takes her far away to his little hut, and says, now cook green cabbage and millet-seed, and I will invite the wedding-guests. Then all the wedding-guests assembled. Who were the wedding-guests? That I can tell you as another told it to me. They were all hares, and the crow was there as parson to marry the bride and bridegroom, and the fox as clerk, and the altar was under the rainbow.`n`nThe girl, however, was sad, for she was all alone. The little hare comes and says, open the doors, open the doors, the wedding-guests are merry. The bride says nothing, but weeps. The little hare goes away. The little hare comes back and says, take off the lid, take off the lid, the wedding-guests are hungry. The bride again says nothing, and weeps. The little hare goes away. The little hare comes back and says, take off the lid, take off the lid, the wedding-guests are waiting. Then the bride says nothing, and the hare goes away, but she dresses a straw-doll in her clothes, and gives her a spoon to stir with, and sets her by the pan with the millet-seed, and goes back to her mother. The little hare comes once more and says, take off the lid, take off the lid, and gets up, and strikes the doll on the head so that her cap falls off. Then the little hare sees that it is not his bride, and goes away and is sorrowful.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`n`n`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>