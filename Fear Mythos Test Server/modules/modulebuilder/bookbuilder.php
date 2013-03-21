<?php
if ($savemodule){
	$completecode = str_replace("\n","<br />", $completecode);
	$fgc = file_get_contents("./modules/modulebuilder/savedmodules/{$session['user']['acctid']}.txt");
	$user_savedmodules = unserialize($fgc);
	$user_savedmodules[$op][$modulename] = $completecode;
	$f = fopen("./modules/modulebuilder/savedmodules/{$session['user']['acctid']}.txt","w+");
	fwrite($f,serialize($user_savedmodules));
	fclose($f);
	
	$fgd = file_get_contents("./modules/modulebuilder/savedmodules/{$session['user']['acctid']}_vals.txt");
	$user_post = unserialize($fgd);
	$user_post[$op][$modulename] = $_POST;
	unset($user_post[$op][$modulename]['savemodule']);
	unset($user_post[$op][$modulename]['completecode']);
	$f = fopen("./modules/modulebuilder/savedmodules/{$session['user']['acctid']}_vals.txt","w+");
	fwrite($f,serialize($user_post));
	fclose($f);
	
	output("`@`bSAVED!`b`0`n");
}
if ($modulename == "" or $bookname == "" or $bookauthor == "" or $booktext == "" or $modulesubmit == ""){
	output("`3`c`bBook Builder`b`c`n");
	output("To build a book simply input the modulename, book title, and author name.  With an html editor create your book text and format it in a way that looks good and is very legible, veiw the source and select everything between the body tags. In the book text section you should paste in the html source code that you have copied from your html editor.`n`n");
	if ($modulename <> "" or $bookname <> "" or $bookauthor <> "" or $booktext <> "") output("`4Required Information Missing! Try Again!`n");
	rawoutput("<form name='book' action='runmodule.php?module=modulebuilder&op=book' method='post'>");
	rawoutput("Module Name: <input value='".$modulename."' name='modulename'><br>");
	rawoutput("Book Name: <input value='".$bookname."' name='bookname'><br>");
	rawoutput("Book Author: <input value='".$bookauthor."' name='bookauthor'><br>");
	rawoutput("Book Text: (formated HTML source)<br>&nbsp;<textarea class='input' cols='50' rows='20' name='booktext'>$booktext</textarea><br><br>");
	rawoutput("<input type='hidden' name='modulesubmit' value='done'> ");
	rawoutput("<input name='Submit' value='Submit' type='submit'></form>");
	addnav("","runmodule.php?module=modulebuilder&op=book");
}else{
	$modulename = strtolower($modulename);
	$bookname = ucfirst($bookname);
	$bookauthor = ucfirst($bookauthor);
	$code = "function book_".$modulename."_getmoduleinfo(){\r\n";
	$code .= "\t\$info = array(\n";
	$code .= "\t\"name\"=>\"".$bookname."\",\n";
	$code .= "\t\"author\"=>\"".get_module_pref("author")." - Built with Module Builder by `3Lonny Luberts`0\",\n";
	$code .= "\t\"version\"=>\"1.0\",\n";
	$code .= "\t\"category\"=>\"Library\",\n";
	$code .= "\t\"download\"=>\"".get_module_pref("downfold")."\",\n";
	$code .= "\t\"vertxtloc\"=>\"".get_module_pref("vertxtloc")."\",\n";
	$code .= "\t\"prefs\" => array(\n";
	$code .= "\t\t\"bookread\" => \"Has the player read this book?, bool|false\",\n";
	$code .= "\t\t),\n";
	$code .= "\t);\n";
	$code .= "\treturn \$info;\n";
	$code .= "}\n";
	$code .= "\n";
	$code .= "function book_".$modulename."_install(){\n";
	$code .= "	if (!is_module_installed(\"library\")) {\n";
	$code .= "         output(\"This module requires the Library module to be installed.\");\n";
	$code .= "         return false;\n";
	$code .= "      }\n";
	$code .= "	module_addhook(\"library\");\n";
	$code .= "	return true;\n";
	$code .= "}\n";
	$code .= "\n";
	$code .= "function book_".$modulename."_uninstall(){\n";
	$code .= "	return true;\n";
	$code .= "}\n";
	$code .= "\n";
	$code .= "function book_".$modulename."_dohook(\$hookname, \$args){\n";
	$code .= "	global \$session;\n";
	$code .= "	\$card = get_module_pref(\"card\",\"library\");\n";
	$code .= ".	\$ca = get_module_setting(\"ca\",\"library\");\n";
	$code .= "	switch(\$hookname){\n";
	$code .= "	case \"library\":\n";
	$code .= "	if (\$card==1 or \$ca==0){\n";
	$code .= "			addnav(\"Book Shelf\");\n";
	$code .= "			addnav(\"".$bookname."\", \"runmodule.php?module=book_".$modulename."\");\n";
	$code .= "		break;\n";
	$code .= "	}\n";
	$code .= "	}\n";
	$code .= "	return \$args;\n";
	$code .= "}\n";
	$code .= "\n";
	$code .= "function book_".$modulename."_run(){\n";
	$code .= "	global \$session;\n";
	$code .= "	\$op = httpget('op');\n";
	$code .= "	page_header(\"Town Library\");\n";
	$code .= "	output(\"`#`c`b".$bookname."`b`c`n\");\n";
	$code .= "	output(\"`!`cWritten by ".$bookauthor."`c`n`n\");\n";
	$code .= "	rawoutput(\"";
	
	$code2  = "\");\n";
	$code2 .= "	\$number = e_rand(1,3);\n";
	$code2 .= "	if (\$number == 3) {\n";
	$code2 .= "		\$session[user][experience]*=1.05;\n";
	$code2 .= "		output(\"`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n\");\n";
	$code2 .= "	}\n";
	$code2 .= "	    addnav(\"Return Book to Shelf\",\"runmodule.php?module=library&op=enter\");\n";
	$code2 .= "	    page_footer();\n";
	$code2 .= "}";
	$progcode = $code.htmlentities($booktext).$code2;
	
	rawoutput("<form method='post' action='runmodule.php?module=modulebuilder&op=book' name='bookbuilder'>");
	rawoutput("<input type='hidden' name='modulename' value='".$modulename."'> ");
	rawoutput("<input type='hidden' name='moduletitle' value='".$moduletitle."'> ");
	rawoutput("<input type='hidden' name='bookauthor' value='".$bookauthor."'> ");
	rawoutput("<input type='hidden' name='booktext' value='".$booktext."'> ");
	rawoutput("<input type='hidden' name='modulesubmit' value=''> ");
	rawoutput("<input type='hidden' name='completecode' value='".htmlspecialchars($progcode, ENT_QUOTES)."'> ");
	rawoutput("<input name='Submit' value='Make Changes' type='submit'>");
	rawoutput("<input name='savemodule' value='Save Module' type='submit'></form>");
	addnav("","runmodule.php?module=modulebuilder&op=book");
	
	rawoutput("<table style='width: 100%; text-align: left;' border='0' cellpadding='2' cellspacing='2'><tbody>");
	rawoutput("<tr><td style='vertical-align: top;'><pre><code>".highlight_string("<?php\n$progcode\n?>",true)."</code></pre><br></td></tr></tbody></table>");
}
?>