<?php
			$lb = db_prefix("librarybooks");
			$ac = db_prefix("accounts");
			if (httpget('subop')=="xml"){
                header("Content-Type: text/xml");
                $sql = "SELECT content from ".db_prefix('librarybooks')." WHERE bookid=".httpget('bookid');
                $row = db_fetch_assoc(db_query($sql));
                echo "<xml>";
                echo "<name name=\"";
                echo rawurlencode(appoencode("`~".stripslashes($row['content'])));
                echo "\"/>";
              	echo "</xml>";
                exit();
            }
			addnav("Options");
			addnav("Add Book","runmodule.php?module=library&op=tell&su=1");
			addnav("Validate Books","runmodule.php?module=library&op=libval&validate=1");
			addnav("See all books","runmodule.php?module=library&op=libval&validate=0");
			rawoutput("<script language='JavaScript'>
				function getUserInfo(id,divid){
					var filename='runmodule.php?module=library&op=libval&subop=xml&bookid='+id;
					//set up the DOM object
					var xmldom;
					if (document.implementation &&
							document.implementation.createDocument){
						//Mozilla style browsers
						xmldom = document.implementation.createDocument('', '', null);
					} else if (window.ActiveXObject) {
						//IE style browsers
						xmldom = new ActiveXObject('Microsoft.XMLDOM');
					}
						xmldom.async=false;
					xmldom.load(filename);
					var output='';
					for (var x=0; x<xmldom.documentElement.childNodes.length; x++){
						output = output + unescape(xmldom.documentElement.childNodes[x].getAttribute('name').replace(/\\+/g,' ')) +'<br>';
					}
					document.getElementById('user'+divid).innerHTML=output;
				}
				</script>
				");
			$act = httpget('act');
			$title = addslashes(httppost('title'));
			$content = addslashes(httppost('content'));
			switch ($act){
				case "val":
					$val = httppost('val');
					if (httppost('validate')){
						$sql = "UPDATE ".db_prefix("librarybooks")."
								SET validated=1 
								WHERE bookid IN ('".join("','",array_keys($val))."')";
						output("`c`b`#Books (%s) have been Validated.`b`c`0`n",count($val));
					}elseif (httppost('unvalidate')){
						$sql = "UPDATE ".db_prefix("librarybooks")."
								SET validated=0 
								WHERE bookid IN ('".join("','",array_keys($val))."')";
						output("`c`b`#Books (%s) have been Unvalidated.`b`c`0`n",count($val));
					}
					db_query($sql);
					break;
				case "delete":
					$sql = "DELETE FROM $lb WHERE bookid=$id";
					db_query($sql);
					output("Book has been Deleted.`n`n");
					break;
				case "edit":
					$id = httppost('id');
					$sql = "UPDATE $lb SET title='$title', content='$content', authid='$id' WHERE bookid=$id";
					db_query($sql);
					output("Book has been edited.`n`n");
					break;
				case "add":
					$sql = "INSERT INTO $lb (authid, title, content) VALUES ('$id', '$title', '$content')";
					db_query($sql);
					break;
				}
			page_header("Library Book Validation");
			rawoutput("<form action='runmodule.php?module=library&op=libval&act=val' method='post'>");
			output("`c`#You may wish to delete the books, that do not have either a Title or a Content Body.`c`n`n");
			$pp = get_module_setting("pp");
			$pageoffset = (int)$page;
			if ($pageoffset > 0) $pageoffset--;
			$pageoffset *= $pp;
			$from = $pageoffset+1;
			$limit = "LIMIT $pageoffset,$pp";
			$sql = "SELECT COUNT(*) AS c FROM " . db_prefix("librarybooks") . "";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$total = $row['c'];
			$count = db_num_rows($result);
			if ($from + $pp < $total){
				$cond = $pageoffset + $pp;
			}else{
				$cond = $total;
			}
			if (httpget('validate')==0) {
				$sql = "SELECT $lb.title, 
						$lb.authid, 
						$lb.bookid, 
						$ac.name, 
						$lb.validated, 
						$lb.content 
						FROM $lb, $ac 
						WHERE authid = acctid 
						ORDER BY bookid ASC $limit";
			} else {
				$sql = "SELECT $lb.title, 
						$lb.authid, 
						$lb.bookid, 
						$ac.name,
						$lb.validated, 
						$lb.content 
						FROM $lb, $ac 
						WHERE authid = acctid 
						AND $lb.validated=0 
						ORDER BY bookid ASC $limit";			
			}
			$result = db_query($sql);
			$vque = translate_inline("Options");
			$validate = translate_inline("Validate");
			$unvalidate = translate_inline("Unvalidate");
			$delete = translate_inline("Delete");
			$edit = translate_inline("Edit");
			$title = translate_inline("Title");
			$author = translate_inline("Author");
			$length = translate_inline("Length");
			$text = translate_inline("Content");
			$val = translate_inline("Validated");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
			rawoutput("<tr class='trhead'><td>$vque</td><td>$title</td><td>$validate</td><td>$status</td><td>$author</td><td>$length</td>");
			if (get_module_setting("showcon")) rawoutput("<td>$text</td>");
			rawoutput("</tr>");
			if (db_num_rows($result)>0){
				$k = 0;
				//for($i = $pageoffset; $i < $cond && $count; $i++) {
					while ($row = db_fetch_assoc($result)) {
					rawoutput("<tr class='trdark'><td>");
					if ($row['validated'] == 0){
						rawoutput("<a href='runmodule.php?module=library&op=libval&act=validate&id={$row['bookid']}&validate=1'>");
						output_notl($validate);
						rawoutput("</a><br>");
						addnav("","runmodule.php?module=library&op=libval&act=validate&id={$row['bookid']}&validate=1");
					}else{
						rawoutput("<a href='runmodule.php?module=library&op=libval&act=unvalidate&id={$row['bookid']}'>");
						output_notl($unvalidate);
						rawoutput("</a><br>");
						addnav("","runmodule.php?module=library&op=libval&act=unvalidate&id={$row['bookid']}");
					}
					rawoutput("<a href='runmodule.php?module=library&op=edit&id={$row['bookid']}'>");
					output_notl($edit);
					rawoutput("</a><br>");
					addnav("","runmodule.php?module=library&op=edit&id={$row['bookid']}");
					rawoutput("<a href='runmodule.php?module=library&op=libval&act=delete&id={$row['bookid']}'>");
					output_notl($delete);
					rawoutput("</a><br>");
					addnav("","runmodule.php?module=library&op=libval&act=delete&id={$row['bookid']}");
					rawoutput("</td><td>");
					output_notl("%s",stripslashes($row['title']));
					rawoutput("</td><td style='text-align:center;'>");
					rawoutput("<input type='checkbox' name='val[".$row['bookid']."]'/>");
					rawoutput("</td><td style='text-align:center;'>");
					$cond = translate_inline($row['validated']==1?"`b`@Validated`b`0":"`b`\$Pending`b`0");
					output_notl($cond);
					rawoutput("</td><td>");
					output_notl("`&%s`0",$row['name']);
					rawoutput("</td><td>");
					output_notl("%s Characters",strlen($row['content']));
					rawoutput("</td>");
					if (get_module_setting("showcon")){
						rawoutput("</tr><tr><td class='trlight' colspan=6>");
						$text=translate_inline("Click here to view content");
						$book="runmodule.php?module=library&op=libval&subop=xml&bookid={$row['bookid']}";
						addnav("",$book);
						rawoutput("<div id='user$k'><a href='$book' target='_blank' onClick=\"getUserInfo('{$row['bookid']}',$k); return false;\">");
	output_notl("%s", $text, true);
//						output_notl("`c`@%s`c`0",stripslashes($row['content']));
						rawoutput("</td>");
					}
					$k++;
					rawoutput("</tr>");
				}
			}
			rawoutput("</table>");
			rawoutput("<br /><div style='text-align:center;'>");
			rawoutput("<input type='submit' name='validate' class='button' value='".translate_inline("Validate")."'>");
			rawoutput("<input type='submit' name='unvalidate' class='button' value='".translate_inline("Unvalidate")."'></form>");
			addnav("","runmodule.php?module=library&op=libval&act=val");
		if ($total>$pp){
			addnav("Pages");
			for ($p=0;$p<$total;$p+=$pp){
				addnav(array("Page %s (%s-%s)", ($p/$pp+1), ($p+1), min($p+$pp,$total)), "runmodule.php?module=library&op=libval&page=".($p/$pp+1));
			}
		}
		require_once("lib/superusernav.php");
		superusernav();
?>
