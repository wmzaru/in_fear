<?php
//Hooked into Library Card system
function book_ione_getmoduleinfo(){
	$info = array(
		"name"=>"Courting Ione - A Love Ballad",
		"author"=>"Script by WebPixie<br>Author `!Enderandrew",
		"version"=>"1.1",
		"category"=>"Library",
		"download"=>"http://dragonprime.net/users/enderwiggin/library-books.zip",
		"vertxtloc"=>"http://dragonprime.net/users/enderwiggin/",
		"prefs" => array(
			"bookread" => "Has the player read this book?, bool|false",
		),
	);
	return $info;
}

function book_ione_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_ione_uninstall(){
	return true;
}

function book_ione_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Book Shelf");
			addnav("Courting Ione - A Love Ballad", "runmodule.php?module=book_ione");
		break;
	}
	}
	return $args;
}

function book_ione_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bCourting Ione - A Love Ballad`b`c`n");
	output("`!`cWritten by Enderandrew aka T. J. Brumfield`c`n`n");

	switch ($op){
		case "1":
		output("`#`bCourting Ione`b`n");
		set_module_pref('readleft',(get_module_pref('readleft','library')-1),'library');
		output("`&O'Connor was a simple man,`n");
		output("`&Fisherman, born of County Cork`n");
		output("`&Gone were the travelling days of youth,`n");
		output("`&He had sworn, married to his work.`n`n");
		output("`&Ione was a simple woman,`n");
		output("`&A dancer, born in Leningrad--`n");
		output("`&Forever seeking to reclaim`n");
		output("`&The lost beauty she once had.`n`n");
		break;
	case "2":
		output("`#`bCourting Ione - Pg. 2`b`n");
		output("`&Swept inland by soft tidal touch,`n");
		output("`&Along the rocky island shore--`n");
		output("`&One life anew ahead of her,`n");
		output("`&And one life gone forever more.`n`n");
		output("`&Her slender frame erect on deck,`n");
		output("`&Soft and frail, tired and oh, so weak,`n");
		output("`&The sun shone from the cross round her neck.`n");
		output("`&The boat slid in with a sense of peace.`n`n");
		break;
	case "3":
		output("`#`bCourting Ione - Pg. 3`b`n");
		output("`&A swarm of curious onlookers`n");
		output("`&Came to examine the wayward ship.`n");
		output("`&Its passenger had caught Erik's eye.`n");
		output("`&A smile had caught up with Erik's lips.`n`n");
		output("`&Eternal test`ment of beauty,`n");
		output("`&True love I know my heart did find.`n");
		output("`&And as I stand on God's greeen earth,`n");
		output("`&Oh yes, I swear she will be mine!`n`n");
		break;
	case "4":
		output("`#`bCourting Ione - Pg. 4`b`n");
		output("`&Though he had sworn to bachelorhood,`n");
		output("`&Irish pride, true it did conceed.`n");
		output("`&For in his eye was the purest gem`n");
		output("`&As ever one has, or will conceive.`n`n");
		output("`&And after her his heart did follow,`n");
		output("`&With his feet just slightly behind,`n");
		output("`&Hoping always she was unaware,`n");
		output("`&Or praying that she wouldn't mind.`n`n");
		break;
	case "5":
		output("`#`bCourting Ione - Pg. 5`b`n");
		output("`&And then one day she stopped and turned,`n");
		output("`&So deftly catching Erik's eye.`n");
		output("`&'Why do you follow me', she asked.`n");
		output("`&Soft, yet stern, her look demanded reply.`n`n");
		output("`&A smile had crossed Erik's face.`n");
		output("`&A reply, why he'd give her one.`n");
		output("`&He'd craft quite a lofty verse.`n");
		output("`&Worthy to be angel-sung.`n");
		break;
	case "6":
		output("`#`bCourting Ione - Pg. 6`b`n");
		output("`&He said, 'hope you'll forgive me stare, but`n");
		output("`&Even of an isle such as ours,`n");
		output("`&Mine eyes have never seen such beauty.`n");
		output("`&Truly you belong among the stars.'`n`n");
		output("`&'So, I hope you'll forve me again.`n");
		output("`&But my eyes lust for your likeness.`n");
		output("`&I'd even say the sun but shines,`n");
		output("`&That your face be not sheathed in darkness.'`n`n");
		break;
	case "7":
		output("`#`bCourting Ione - Pg. 7`b`n");
		output("`&With redd'ning cheeks, she returned his gaze.`n");
		output("`&Her heart it seemed he had won.`n");
		output("`&'But I'm not looking for a man,' she said,`n");
		output("`&'Since I used to be one.'`n`n");
		$number = e_rand(1,3);
		if ($number == 3) {
			$session[user][charm]+=1;
			output("`!The book has inspired both your wit and heart!  You gain some Charm!`n"); }
		break;
		}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    addnav("Chapters");
	    addnav("Page 1","runmodule.php?module=book_ione&op=1");
	    addnav("Page 2","runmodule.php?module=book_ione&op=2");
	    addnav("Page 3","runmodule.php?module=book_ione&op=3");
	    addnav("Page 4","runmodule.php?module=book_ione&op=4");
	    addnav("Page 5","runmodule.php?module=book_ione&op=5");
	    addnav("Page 6","runmodule.php?module=book_ione&op=6");
	    addnav("Page 7","runmodule.php?module=book_ione&op=7");
	page_footer();
}
?>