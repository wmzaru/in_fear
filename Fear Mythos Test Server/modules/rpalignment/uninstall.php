<?php
// database table deletion
if (db_table_exists(db_prefix("rpalignment"))){
	$sql = "DROP TABLE `".db_prefix("rpalignment")."`";
	db_query($sql);
}