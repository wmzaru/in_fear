<?php
$info = array(
	"name" => "Chess",
	"author" => "`i`)Ae`7ol`&us`i`0",
	"version" => "1.0 BETA",
	"category" => "Games",
	"settings" => array(
		"Chess Settings,title",
			"inactive" => "Seconds of inactivity before game automatically ends,int|300",
			"replays" => "Allow players to view replays or just admins?,enum,0,Just Admins,1,All Players|1",
			"rules" => "Game Rules,textarea|",
	),
	"prefs" => array(
		"Chess Prefs,title",
			"user_image" => "Display images?,bool|1",
			"wins" => "Wins,int|0",
			"loss" => "Loss,int|0",
			"challenges" => "Awaiting challenges,text|",
			"banned" => "Is user banned from playing chess?,bool|0",
		"Chess Playing Prefs,title",
			"current" => "Currently challenging,int|",
			"color" => "Currently challening with what color,text|",
	),
);
?>