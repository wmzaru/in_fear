<?
function dkreq4gldldrs_getmoduleinfo(){
	$info = array(
		"name" => "Dragon kill requirement for guild leaders",
		"version" => "20070207",
		"vertxtloc"=>"http://www.legendofsix.com/",
		"author" => "Sixf00t4",
		"category" => "Clan",
		"download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1087",
	
"settings"=>array(
			"Dragon Kill for Guild Creation Settings,title",
			"dkreq"=>"Dragonkills required to create new guild?,int|3",
         ),

);
	return $info;
}

function dkreq4gldldrs_install(){
	module_addhook("footer-clan");
	return true;
}

function dkreq4gldldrs_uninstall(){
	return true;
}

function dkreq4gldldrs_dohook($hookname, $args){
global $session;
	switch($hookname){
        case footer-clan:
            if (get_module_setting('dkreq') > $session['user']['dragonkills']){	
                blocknav("clan.php?op=new");
                addnav("Clan Options");
                addnav("Apply for a new Clan","runmodule.php?module=dkreq4gldldrs");
            break;
            }
        }
return $args;
}

function dkreq4gldldrs_run(){
global $session;
$op = httpget('op');
if ($op==""){
page_header("Guild Creation");
output("You still got farmboy written all over you.  What kind of a leader would you be if you can't even tell members how to slay a dragon?");
addnav("Back to Clans","clan.php");
page_footer();
}
}
?>