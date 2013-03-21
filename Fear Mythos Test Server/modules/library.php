<?php

function library_getmoduleinfo(){
	$info = array(
		"name"=>"The Library",
		"author"=>"Chris Vorndran<br>Original Idea: `QCleodelia",
		"version"=>"2.75",
		"category"=>"Library",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=37",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"Places a Library in a village, in which users can submit their own books, or books can hook into the village.",
			"settings"=>array(
				"Library General Settings,title",
					"allow"=>"Allow users to send in their own books?,bool|1",
					"dk"=>"How many DKs does one need before they can submit a book?,int|25",
					"pp"=>"Display how many books per page?,int|10",
					"max"=>"Max amount of characters in a story,int|10000",
					"showcon"=>"Show content during validation,bool|1",
				"Library Card Settings,title",
					"ca"=>"Are Library Cards needed,bool|0",
					"caco"=>"Cost of Library Card,int|500",
				"Library Location Settings,title",
					"looa"=>"Library exists in all cities?,bool|0",
					"libraryloc"=>"Location of Library,location|".getsetting("villagename", LOCATION_FIELDS),
					"loungeloc"=>"Location of the Library's Main Lounge,location|".getsetting("villagename", LOCATION_FIELDS),
			),
			"prefs"=>array(
				"Library Preferences,title",
				"card"=>"Does this user have a library card,bool|0",
				"libaccess"=>"Has access to Library Validation?,bool|0",
			),
		);
	return $info;
}
function library_install(){
	$library_books = array(
		'bookid'=>array('name'=>'bookid', 'type'=>'int(11) unsigned',	'extra'=>'not null auto_increment'),
		'authid'=>array('name'=>'authid', 'type'=>'int(11) unsigned', 'extra'=>'not null'),
		'title'=>array('name'=>'title', 'type'=>'varchar(255)', 'default'=>'', 'extra'=>'not null'),
		'content'=>array('name'=>'content', 'type'=>'text', 'default'=>'', 'extra'=>'not null'),
		'validated'=>array('name'=>'validated', 'type'=>'tinyint(4) unsigned', 'extra'=>'not null'),
		'key-PRIMARY'=>array('name'=>'PRIMARY', 'type'=>'primary key',	'unique'=>'1', 'columns'=>'bookid'),
		'index-bookid'=>array('name'=>'bookid', 'type'=>'index', 'columns'=>'bookid'),
		'index-authid'=>array('name'=>'authid', 'type'=>'index', 'columns'=>'authid')
	);
	require_once("lib/tabledescriptor.php");
	synctable(db_prefix('librarybooks'), $library_books, true);
	module_addhook("moderate");
	module_addhook("village");
	module_addhook("changesetting");
	module_addhook("superuser");
	module_addhook("header-superuser");
	module_addhook("delete_character");
	return true;
}
function library_uninstall(){
	$sql = "DROP TABLE `".db_prefix("librarybooks")."`";
	db_query($sql);
	return true;
}
function library_dohook($hookname,$args){
	global $session;
	$module = httpget('module');
	switch ($hookname){
		case "village":
			if ($session['user']['location'] == get_module_setting("libraryloc") && !get_module_setting("looa")){
				tlschema($args['schemas']['tavernnav']);
				addnav($args['tavernnav']);
				tlschema();
				addnav(array("%s Public Library",get_module_setting("libraryloc")),"runmodule.php?module=library&op=enter");	
			}elseif (get_module_setting("looa")){
				tlschema($args['schemas']['tavernnav']);
				addnav($args['tavernnav']);
				tlschema();
				addnav(array("%s Public Library",$session['user']['location']),"runmodule.php?module=library&op=enter");
			}
			break;
		case "delete_character":
			$id = $args['acctid'];
			$sql = "DELETE FROM ".db_prefix("librarybooks")." WHERE authid=$id";
			db_query($sql);
			break;
		case "moderate":
			$args['clibrary'] = 'The Library';
			break;
		case "superuser":
			if (get_module_pref("libaccess") && get_module_setting("allow")){
				addnav("Validations");
				addnav("Library Book Validation","runmodule.php?module=library&op=libval&validate=1");
			}
			break;
		case "header-superuser":
			if (get_module_pref("libaccess") && get_module_setting("allow")) {
				$sql = "SELECT count(bookid) as counter FROM ".db_prefix("librarybooks")." WHERE validated=0";
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
				if ($row['counter'] > 0) output("`c`b`\$There are `v%s`\$ books to validate!`b`c`0",$row['counter']);
				}
			break;
		case "changesetting":
			if ($args['setting'] == "villagename") {
				if ($args['old'] == get_module_setting("libraryloc")) {
				   set_module_setting("libraryloc", $args['new']);
				}
			}
			break;
		}
	return $args;
}
function library_run(){
	global $session;
	$op = httpget('op');
	$id = httpget('id');
	$title = addslashes(httppost('title'));
	$content = addslashes(httppost('content'));
	$page = httpget('page');
	if (!get_module_setting("looa")){
		page_header(array("%s Public Library",get_module_setting("libraryloc")));
	}else{
		page_header(array("%s Public Library",$session['user']['location']));
	}

	switch ($op){
		case "enter":
			output("`2You walk silently into the book filled room.");
			output("The smell of old parchment fills the air.");
			output("Lining the walls are huge shelves filled with ancient looking books.");
			output("You just know the knowledge inside them would help you on your travels.");
			output("Off to the left, an elegant woman stands; obviously the legendary librarian, `~SlendySlayer`2.");
			output("She peers over her glasses and smiles at you, \"`^May I help ye today?`2\"");
			addnav("Branches");
			addnav("Help Desk","runmodule.php?module=library&op=desk");
			if ($session['user']['location'] == get_module_setting("loungeloc")) addnav("Lounge","runmodule.php?module=library&op=lounge");
			if (!get_module_setting("ca") || get_module_pref("card")){
				addnav("Shelves","runmodule.php?module=library&op=shelves");
			}else{
				output("`n`nIt seems that you do not have a library card...");
				output("Perhaps you should purchase one?");
			}
			break;
		case "lounge":
			output("`2You wander into a vast cafe, people crowding around the counter.");
			output("In the corner, you see a couple of chairs huddles around a fire.");
			output("Upon hearing the crackling of the fire, you decide to speak amongst your friends.`n`n");
			require_once("lib/commentary.php");
			addcommentary();
			viewcommentary("clibrary","Softly people whisper to each other",15,"speaks softly");
			addnav("Move About");
			if (!get_module_setting("ca") || get_module_pref("card")) addnav("Shelves","runmodule.php?module=library&op=shelves");
			addnav("Return to Main Hall","runmodule.php?module=library&op=enter");
			break;
		case "shelves":
			output("`2Around you, thousands upon thousands of books stand.");
			output("Inside of them, infinite knowledge lie...");
			output("Do ye wish to indulge in the learning process?");
			addnav("Book Shelf");
			modulehook("library");
			if (get_module_setting("allow")) addnav("Player Written Novels","runmodule.php?module=library&op=player");
			addnav("Branches");
			addnav("Lounge","runmodule.php?module=library&op=lounge");
			addnav("Return to Main Hall","runmodule.php?module=library&op=enter");
			break;
		case "player":
		if ($id != ""){
			$sql = "SELECT l.title AS title, l.content AS content, a.name AS name,a.acctid AS acctid FROM ".db_prefix("librarybooks")." AS l INNER JOIN ".db_prefix("accounts")." AS a ON authid=acctid WHERE bookid=$id";
			$res = db_query($sql);
			$row = db_fetch_assoc($res);
			require_once("lib/sanitize.php");
			page_header(array("%s by %s",stripslashes(color_sanitize($row['title'])),color_sanitize($row['name'])));
			output("`c`b%s`0 by %s.`b`c`0",stripslashes($row['title']),$row['name']);
			require_once("lib/nltoappon.php");
			output("`n`n`c%s`c`0",nltoappon(stripslashes($row['content'])));
			addnav(array("More books from %s",$row['name']),"runmodule.php?module=library&op=player&author=".$row['acctid']);
			if (get_module_setting("allow")) addnav("Return to Player Written Novels","runmodule.php?module=library&op=player");			
		}else{
			output("`2Looking around the Library, you find a bunch of novels written by some fellow warriors.");
			output("You glance over all of the titles, and smile.");
			output("\"`^Would you care to read one?`2\"`n`n");
			$pp = get_module_setting("pp");
			$pageoffset = (int)$page;
			$author=(int) httpget('author');
			if ($pageoffset > 0) $pageoffset--;
			$pageoffset *= $pp;
			$from = $pageoffset+1;
			$limit = "LIMIT $pageoffset,$pp";
			if ($author!=0) {
				$authorstring="AND authid=$author";
				$limit='';
			}	else $authorstring='';
			$sql = "SELECT COUNT(*) AS c FROM " . db_prefix("librarybooks") . " WHERE validated=1";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$total = $row['c'];
			$count = db_num_rows($result);
			if ($from + $pp < $total){
				$cond = $pageoffset + $pp;
			}else{
				$cond = $total;
			}
			$sql = "SELECT ".db_prefix("librarybooks").".title, ".db_prefix("librarybooks").".validated, ".db_prefix("librarybooks").".bookid, ".db_prefix("accounts").".name  FROM ".db_prefix("librarybooks")." , ".db_prefix("accounts"). " WHERE authid = acctid AND validated=1 $authorstring ORDER BY bookid ASC $limit";
			$result = db_query($sql);
			$title = translate_inline("Title");
			$author = translate_inline("Author");
				rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
				rawoutput("<tr class='trhead'><td>$title</td><td>$author</tr>");
				if (db_num_rows($result)>0){
				//	for($i = $pageoffset; $i < $cond && $count; $i++) {
					//	$row = db_fetch_assoc($result);
					$i=0;
					while ($row=db_fetch_assoc($result)) {
						$i++;
						rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
						rawoutput("<a href='runmodule.php?module=library&op=player&id={$row['bookid']}'>");
						output_notl("%s",stripslashes($row['title']));
						rawoutput("</a>");
						addnav("","runmodule.php?module=library&op=player&id={$row['bookid']}");
						rawoutput("</td><td>");
						output_notl("`&%s`0",$row['name']);
						rawoutput("</td></tr>");
					}
				}
				rawoutput("</table>");
				$sql="SELECT b.authid AS acctid, a.name AS name, count(b.bookid) AS counter FROM ".db_prefix('accounts')." as a INNER JOIN ".db_prefix('librarybooks')." AS b ON a.acctid=b.authid WHERE validated=1 GROUP BY b.authid";
				$result=db_query($sql);
				addnav("Authors");
				while ($row=db_fetch_assoc($result)) {
					addnav(array("%s (%s books)",$row['name'],$row['counter']),"runmodule.php?module=library&op=player&author=".$row['acctid']);
				}
				if ($total>$pp){
					addnav("Pages");
					for ($p=0;$p<$total;$p+=$pp){
						addnav(array("Page %s (%s-%s)", ($p/$pp+1), ($p+1), min($p+$pp,$total)), "runmodule.php?module=library&op=player&page=".($p/$pp+1));
					}
				}

			}
			addnav("Leave");
			addnav("Return to the Shelves","runmodule.php?module=library&op=shelves");
			break;
		case "buy":
			output("`2The librarian, `~SlendySlayer`2, approaches you and smiles.");
			output("\"`^So, ye wish to purchase a library card...?`2\"");
			output("She smiles once more, and pulls out the right forms.");
			if ($session['user']['gold'] >= get_module_setting("caco")){
				output("`2She pushes the forms closer, to which you sign your name: %s`2.",$session['user']['name']);
				$session['user']['gold']-=get_module_setting("caco");
				set_module_pref("card",1);
			}else{
				output("`2She withdraws the forms and shakes her head...");
				output("\"`^I am sorry %s`^, but you do not have the correct amount of %s gold for this transaction...`2\"",$session['user']['name'],get_module_setting('caco'));
				output("She shuffles around the papers and walks off.");
			}
			addnav("Return to Main Hall","runmodule.php?module=library&op=enter");
			break;
		case "desk":
			output("`2You walk over to the librarian, `~SlendySlayer`2, as she sits behind her desk.");
			output("She looks up at you and smiles.");
			addnav("Help Topics");
			if ($session['user']['dragonkills'] >= get_module_setting("dk") && get_module_setting("allow")){
				output("`n`n\"`^Wow, you are quite the talented warrior.");
				output("I am sure that you have some stories to tell... and I would be more than honored to take them down.");
				output("Would you care to share?`2\"");
				addnav("Storytelling","runmodule.php?module=library&op=tell");
				}
			if (get_module_setting("ca") && !get_module_pref("card")) addnav("Purchase Library Card","runmodule.php?module=library&op=buy");
				addnav("Move About");
				if (!get_module_setting("ca") || get_module_pref("card")) addnav("Shelves","runmodule.php?module=library&op=shelves");
				addnav("Return to Main Hall","runmodule.php?module=library&op=enter");
				break;
		case "tell":
			if ($title == "" && $content == ""){
				if (!httpget('su'))
					rawoutput("<form action='runmodule.php?module=library&op=tell' method='POST'>");
				else{
					rawoutput("<form action='runmodule.php?module=library&op=libval&act=add' method='POST'>");
	                output("`^Author ID:");
	                rawoutput("<input id='input' name='id' width=5>");
					output_notl("`n`n");
					require_once("lib/superusernav.php");
					superusernav();
					addnav("~");
				}
                output("`^What is the title of your story:");
                rawoutput("<input id='input' name='title' width=5>");
				output_notl("`n`n");
				output("`^Here, you can write in your content.");
				output("I highly suggest, that you confirm your spelling and grammar before hand.");
				output("Since we hold true to copyrights, and will not be able to edit your story.`n");
                rawoutput("<textarea name=\"content\" rows=\"10\" cols=\"60\" class=\"input\"></textarea>");
                rawoutput("<input type='submit' class='button' value='".translate_inline("Submit")."'></form>");
                rawoutput("</form>");
			}elseif ($content != "" && strlen($content) >= get_module_setting("max")){
				output("Please do not go beyond %s characters. Thank you.",get_module_setting("max"));
			}else{
				debug("Length of Content " . strlen($content));
				$sql = "INSERT INTO ".db_prefix("librarybooks")." (authid, title, content) VALUES ('".$session['user']['acctid']."', '".$title."', '".$content."')";
				db_query($sql);
				output("`^Thank you very much, for telling me your story.");
				output("I shall have another librarian validate it.");
				output("If it is seen good enough, you will see it in the Library.");
				output("If not, then it will be deleted. Sorry.");
			}
			if (!httpget('su'))
				addnav("","runmodule.php?module=library&op=tell");
			else
				addnav("","runmodule.php?module=library&op=libval&act=add");
			addnav("Return to Main Hall","runmodule.php?module=library&op=enter");
			break;
		case "libval":
			require("modules/library/case_libval.php");
		break;
	case "edit":
		$sql = "SELECT authid, title, content FROM ".db_prefix("librarybooks")." WHERE bookid=$id";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		rawoutput("<form action='runmodule.php?module=library&op=libval&act=edit&id=$id' method='POST'>");
        output("`^Title:");
        rawoutput("<input id='input' name='title' value='".$row['title']."' width=5>");
		output_notl("`n`n");
        output("`^Author ID:");
        rawoutput("<input id='input' name='id' value='".$row['authid']."' width=5>");
		output_notl("`n`n");
		output("`^Content.`n");
        rawoutput("<textarea name='content' rows='10' cols='60' class='input'>".htmlentities($row['content'])."</textarea>");
        rawoutput("<input type='submit' class='button' value='".translate_inline("Submit")."'></form>");
        rawoutput("</form>");
		addnav("Return to Validating","runmodule.php?module=library&op=libval");
		addnav("","runmodule.php?module=library&op=libval&act=edit&id=$id");
		break;
	}
	if ($op != "libval"){
		addnav("Leave");
		villagenav();
	}
page_footer();
}
?>