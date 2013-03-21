<?php
//Hooked into Library Card system
function book_quotes_getmoduleinfo(){
	$info = array(
		"name"=>"Famous Quotes (book)",
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

function book_quotes_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_quotes_uninstall(){
	return true;
}

function book_quotes_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Book Shelf");
			addnav("Famous Quotes", "runmodule.php?module=book_quotes");
		break;
	}
	}
	return $args;
}

function book_quotes_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bFamous Quotes`b`c`n");
	output("`!`cWritten by Enderandrew aka T. J. Brumfield`c`n`n");

	switch ($op){
		case "1":
		output("`#`bFamous Quotes`b`n");
		set_module_pref('readleft',(get_module_pref('readleft','library')-1),'library');
		output("`&To laugh often and love much; to win the respect of intelligent persons and the affection of children; to earn the approbation of honest critics and endure the betrayal of false friends; to appreciate beauty; to give of one's self; to know even one life has breathed easier because you have lived ~~ that is to have succeeded.`n");
		output("`&~ Ralph Waldo Emerson ~`n`n");
		output("`&Security is mostly a superstition. It does not exist in nature, nor do the children of men as whole experience it. Avoiding danger is no safer in the long run than outright exposure. Life is either a daring adventure or nothing.`n");
		output("`&~ Helen Keller ~`n`n");
		output("`&A wise man is superior to any insults which can be put upon him, and the best reply to unseemly behavior is patience and moderation.`n");
		output("`&~ Moliere ~`n`n");
		output("`&There was that law of life, so cruel and so just, that one must grow or else pay more for remaining the same.`n");
		output("`&~ Norman Mailer ~`n`n");
		break;
	case "2":
		output("`#`bFamous Quotes - Pg. 2`b`n");
		output("`&In the depth of winter, I finally learned that within me there lay an invincible summer.`n");
		output("`&~ Albert Camus ~`n`n");
		output("`&Failures are divided into two classes- those who thought and never did, and those who did and never thought.`n");
		output("`&~ John Charles Salak ~`n`n");
		output("`&Genius is the ability to reduce the complicated to the simple.`n");
		output("`&~ C.W. Ceran ~`n`n");
		output("`&Where I was born and where and how I have lived is unimportannt. It is what I have done with where I have been that should be of interest.`n");
		output("`&~ Georgia O'Keeffe ~`n`n");
		break;
	case "3":
		output("`#`bFamous Quotes - Pg. 3`b`n");
		output("`&Our lives begin to end the day we become silent about things that matter.`n");
		output("`&~ Martin Luther King, Jr. ~`n`n");
		output("`&Genius is an infinite capacity for taking pains.`n");
		output("`&~ Jane Ellice Hopkins ~`n`n");
		output("`&Without continual growth and progress, such words as improvement, achievement and success have no meaning.`n");
		output("`&~ Benjamin Franklin ~`n`n");
		output("`&When I dare to be powerful -- to use my strength in the service of my vision, then it becomes less and less important whether I am afraid.`n");
		output("`&~ Audre Lorde ~`n`n");
		break;
	case "4":
		output("`#`bFamous Quotes - Pg. 4`b`n");
		output("`&Our strength is often composed of the weakness we're damned if we're going to show.`n");
		output("`&~ Mignon McLaughlin ~`n`n");
		output("`&I pass on to you three rules for life; be friendly, but never tame, misbehave with integrity, and ILLEGETIMATI NON CARBORUNDUM ~~ Don't let the bastards get you down.`n");
		output("`&~ Clarissa Pinkola Estes ~`n`n");
		output("`&The unexamined life is not worth living.`n");
		output("`&~ Socrates ~`n`n");
		output("`&I learn immediately from any speaker how much he has already lived, through the poverty or the splendor of his speech.`n");
		output("`&~ Ralph Waldo Emerson ~`n`n");
		break;
	case "5":
		output("`#`bFamous Quotes - Pg. 5`b`n");
		output("`&The difference between the right word and almost the right word is the difference between lightening and the lightening bug.`n");
		output("`&~ Charles Dickens ~`n`n");
		output("`&Few human beings are proof against the implied flattery of rapt attention.`n");
		output("`&~ Jack Woodford ~`n`n");
		output("`&I love the man who can smile in trouble, who can gather strength from distress, and grows brave by reaction. 'Tis the business of little minds to shrink, but he whose heart is firm, and whose conscience approves his conduct, will pursue his principles unto death.`n");
		output("`&~ Thomas Paine ~`n`n");
		output("`&Cherish your visions; cherish your ideals; cherish the music that stirs in your heart, the beauty that forms in your mind, the loveliness that drapes your purest thoughts, for out of them will grow delightful conditions, all heavenly environment; of these if you but remain true to them, your world will at last be built.`n");
		output("`&~ James Allen ~`n`n");
		break;
	case "6":
		output("`#`bFamous Quotes - Pg. 6`b`n");
		output("`&You cannot be lonely if you like the person you're alone with.`n");
		output("`&~ Wayne Dyer ~`n`n");
		output("`&Books and jealousy tell me wrong`n");
		output("`&~ Eddie Vedder ~`n`n");
		output("`&I am not young enough to know everything.`n");
		output("`&~ James M. Barrie ~`n`n");
		output("`&A threat to justice anywhere is a threat to justice everywhere.`n");
		output("`&~ Martin Luther King Jr. ~`n`n");
		break;
	case "7":
		output("`#`bFamous Quotes - Pg. 7`b`n");
		output("`&Nothing is more frightening that active ignorance.`n");
		output("`&~ Goethke ~`n`n");
		output("`&T. J., you quote people too much.`n");
		output("`&~ Jen Houlden ~`n`n");
		$number = e_rand(1,3);
		if ($number == 3) {
			$session[user][experience]*=1.05;
			output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n"); }
		break;

		}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    addnav("Chapters");
	    addnav("Page 1","runmodule.php?module=book_quotes&op=1");
	    addnav("Page 2","runmodule.php?module=book_quotes&op=2");
	    addnav("Page 3","runmodule.php?module=book_quotes&op=3");
	    addnav("Page 4","runmodule.php?module=book_quotes&op=4");	    
	    addnav("Page 5","runmodule.php?module=book_quotes&op=5");
	    addnav("Page 6","runmodule.php?module=book_quotes&op=6");
	    addnav("Page 7","runmodule.php?module=book_quotes&op=7");
	page_footer();
}
?>