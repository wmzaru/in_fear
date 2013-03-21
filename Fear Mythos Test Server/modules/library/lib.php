<?php

function library_add_book($author,$title,$content){
	$sql = "INSERT INTO ".db_prefix("librarybooks")." (author, title, content) 
			VALUES ('".$author."', '".$title."', '".$content."')";
	db_query($sql);
}

?>