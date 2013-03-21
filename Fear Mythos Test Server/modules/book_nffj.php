<?php

function book_nffj_getmoduleinfo(){
	$info = array(
		"name"=>"No Food for Jenny (Book)",
		"author"=>"Story written by Jessica Riviere<br>Converted to LotGD Library Book by Alex Riviere (aka Fimion)<br>Webpixie Template",
		"version"=>"1.0",
		"category"=>"Library",
		"download"=>""
	);
	return $info;
}

function book_nffj_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_nffj_uninstall(){
	return true;
}

function book_nffj_dohook($hookname, $args){
	global $session;
	switch($hookname){
	case "library":
		addnav("Children's Section");
		addnav("No Food for Jenny", "runmodule.php?module=book_nffj");
		break;

	}
	return $args;
}

function book_nffj_run(){
	global $session;
	$session['user']['specialinc'] = "module:book_nffj";
	$op = httpget('op');
	page_header("Town Library");

	output("`^`c`bNo Food for Jenny`b`c`n");

	if ($op == "") {

	    $session['user']['specialinc'] = "";
		output("`@`cWritten by Jessica Riviere`c`n`cConverted to LotGD Library Book by Alex Riviere (aka Fimion)`c`n`n`n");
		//output("`&`c<a href=\"runmodule.php?module=book_nffj&op=1\">Next Page</a>`c`n");
		}


	if ($op == "1") {

	    $session['user']['specialinc'] = "";
		output("`@Jenny is a darling dog.  She lives in a big house with a Mommy, a Daddy, a Girl and a Boy. What Jenny loves most besides her people are her bed and her food.`n`n");
		//output("`&`c<a href=\"runmodule.php?module=book_nffj\">Previous Page</a> | Page 1 | <a href=\"runmodule.php?module=book_nffj&op=2\">Next Page</a>`c`n");
		}


	if ($op == "2") {

	    $session['user']['specialinc'] = "";
		output("`@Jenny woke up from a nap one day, and was very hungry. She decided to check her bowl to see if there was any food left.`n`n");
		//output("`&`c<a href=\"runmodule.php?module=book_nffj&op=1\">Previous Page</a> | Page 2 | <a href=\"runmodule.php?module=book_nffj&op=3\">Next Page</a>`c`n");
		}


	if ($op == "3") {

	    $session['user']['specialinc'] = "";
		output("`@It was empty.  No food for Jenny!`n`n`n");
		//output("`&`c<a href=\"runmodule.php?module=book_nffj&op=2\">Previous Page</a> | Page 3 | <a href=\"runmodule.php?module=book_nffj&op=4\">Next Page</a>`c`n");
		}


	if ($op == "4") {

	    $session['user']['specialinc'] = "";
		output("`@Jenny looked on the counter.  Sometimes Mommy got food from there.  But this time, there was no food for Jenny.`n`n");
		//output("`&`c<a href=\"runmodule.php?module=book_nffj&op=3\">Previous Page</a> | Page 4 | <a href=\"runmodule.php?module=book_nffj&op=5\">Next Page</a>`c`n");
		}

	if ($op == "5") {

	    $session['user']['specialinc'] = "";
		output("`@No one else was home.  Jenny was sad and hungry.`n`n`n");
		//output("`&`c<a href=\"runmodule.php?module=book_nffj&op=4\">Previous Page</a> | Page 5 | <a href=\"runmodule.php?module=book_nffj&op=6\">Next Page</a>`c`n");
		}



	if ($op == "6") {

	    $session['user']['specialinc'] = "";
		output("`@Then, her Boy came home! Jenny loved her Boy.  Jenny wagged her tail she was so happy.  But there was still no food for Jenny.`n`n");
		//output("`&`c<a href=\"runmodule.php?module=book_nffj&op=".$op-1."\">Previous Page</a> | Page ".$op." | <a href=\"runmodule.php?module=book_nffj&op=".$op+1."\">Next Page</a>`c`n");
		}
		
	if ($op == "7") {

	    $session['user']['specialinc'] = "";
		output("`@Later, her Girl came home! Jenny loved her Girl.  Jenny was excited and ran to her Girl.  But there was still no food for Jenny.`n`n");
		//output("`&`c<a href=\"runmodule.php?module=book_nffj&op=".$op-1."\">Previous Page</a> | Page ".$op." | <a href=\"runmodule.php?module=book_nffj&op=".$op+1."\">Next Page</a>`c`n");
		}
		
	if ($op == "8") {

	    $session['user']['specialinc'] = "";
		output("`@When Daddy came home, Jenny was glad.  Jenny loved her Daddy.  Jenny danced for her Daddy! But there was still no food for Jenny.`n`n");
		//output("`&`c<a href=\"runmodule.php?module=book_nffj&op=".$op-1."\">Previous Page</a> | Page ".$op." | <a href=\"runmodule.php?module=book_nffj&op=".$op+1."\">Next Page</a>`c`n");
		}
		
	if ($op == "9") {

	    $session['user']['specialinc'] = "";
		output("`@Then Mommy came home.  Jenny loved her Mommy.  She was so excited, Jenny yodeled:`n \"Aah-roo-ah-rooo-ahrouf!\"`n`n");
		//output("`&`c<a href=\"runmodule.php?module=book_nffj&op=".$op-1."\">Previous Page</a> | Page ".$op." | <a href=\"runmodule.php?module=book_nffj&op=".$op+1."\">Next Page</a>`c`n");
		}
		
	if ($op == "10") {

	    $session['user']['specialinc'] = "";
		output("`@Mommy said,`n \"Why Jenny, are you hungry?\"`n`n");
		//output("`&`c<a href=\"runmodule.php?module=book_nffj&op=".$op-1."\">Previous Page</a> | Page ".$op." | <a href=\"runmodule.php?module=book_nffj&op=".$op+1."\">Next Page</a>`c`n");
		}
		
	if ($op == "11") {

	    $session['user']['specialinc'] = "";
		output("`@Jenny couldn’t say yes, but she looked as excited as she could be!`n`n");
		//output("`&`c<a href=\"runmodule.php?module=book_nffj&op=".$op-1."\">Previous Page</a> | Page ".$op." | <a href=\"runmodule.php?module=book_nffj&op=".$op+1."\">Next Page</a>`c`n");
		}
		
	if ($op == "12") {

	    $session['user']['specialinc'] = "";
		output("`@Mommy gave Jenny her supper.  Food for Jenny at last!`n`n`n");
		//output("`&`c<a href=\"runmodule.php?module=book_nffj&op=".$op-1."\">Previous Page</a> | Page ".$op." | <a href=\"runmodule.php?module=book_nffj&op=".$op+1."\">Next Page</a>`c`n");
		}
		
	if ($op == "13") {

	    $session['user']['specialinc'] = "";
		output("`@After she ate her food, and licked the bowl clean, Jenny lay down close to her Family.  Jenny was happy at last.`n`n");
		//output("`&`c<a href=\"runmodule.php?module=book_nffj&op=".$op-1."\">Previous Page</a> | Page ".$op." | <a href=\"runmodule.php?module=book_nffj&op=".$op+1."\">Next Page</a>`c`n");
		}
		
	if ($op == "14") {

	    $session['user']['specialinc'] = "";
		output("`@`cThe End.`c`n`n`n");
		//output("`&`c<a href=\"runmodule.php?module=book_nffj&op=".$op-1."\">Previous Page</a> | <a href=\"runmodule.php?module=book_nffj\">Title Page</a>`c`n");
		}
		addnav("Return Book to Shelf","runmodule.php?module=library");
		addnav(" Page ".$op);
		switch($op)
		{
			case "":
			{
				addnav("Next","runmodule.php?module=book_nffj&op=1");	
			}break;
			
			case "1":
			{
				output("`@`cPage 1`c`n");
				addnav("Next","runmodule.php?module=book_nffj&op=2");
				addnav("Previous","runmodule.php?module=book_nffj");
					
			}break;
			
			case "14":
			{
				addnav("Previous","runmodule.php?module=book_nffj&op=13");
			}break;
			
			default:
			{
				output("`@`cPage ".$op."`c`n");
				addnav("Next","runmodule.php?module=book_nffj&op=".($op+1));
				addnav("Previous","runmodule.php?module=book_nffj&op=".($op-1));
			}break;
		}
	    addnav("Table of Contents");
	    addnav("Title Page","runmodule.php?module=book_nffj");
	    addnav("Page 1","runmodule.php?module=book_nffj&op=1");
	    addnav("Page 2","runmodule.php?module=book_nffj&op=2");
	    addnav("Page 3","runmodule.php?module=book_nffj&op=3");	    
	    addnav("Page 4","runmodule.php?module=book_nffj&op=4");
	    addnav("Page 5","runmodule.php?module=book_nffj&op=5");
	    addnav("Page 6","runmodule.php?module=book_nffj&op=6");
	    addnav("Page 7","runmodule.php?module=book_nffj&op=7");
	    addnav("Page 8","runmodule.php?module=book_nffj&op=8");
	    addnav("Page 9","runmodule.php?module=book_nffj&op=9");
	    addnav("Page 10","runmodule.php?module=book_nffj&op=10");
	    addnav("Page 11","runmodule.php?module=book_nffj&op=11");
	    addnav("Page 12","runmodule.php?module=book_nffj&op=12");
	    addnav("Page 13","runmodule.php?module=book_nffj&op=13");
	    addnav("Page 14","runmodule.php?module=book_nffj&op=14");

	page_footer();
}

?>