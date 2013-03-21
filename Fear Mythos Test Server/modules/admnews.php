<?php
require_once("lib/http.php");
require_once("lib/addnews.php");
require_once("lib/sanitize.php");

function admnews_getmoduleinfo(){
        $info = array(
                "name"=>"Add News",
                "version"=>"1.2",
                "author"=>"<a href=\"http://logd.ecsportal.com\" target=_new>Arune - Kevin Hatfield</a>",
                "category"=>"Administrative",
				"vertxtloc"=>"http://www.dragonprime.net/users/khatfield/",
				"description"=>"Allows for adding news to the Daily news logs",
                "download"=>"http://dragonprime.net/users/khatfield/admnews.zip",
                );
        
        return $info;
}

function admnews_install() {
        module_addhook("news-intercept");
        return(true);
}

function admnews_uninstall(){
        return(true);
}
function admnews_dohook($hookname, $args){
        global $session;
        switch($hookname){
        case "news-intercept":
        if ($session['user']['superuser'] & SU_AUDIT_MODERATION){ addnav("A?Add News","runmodule.php?module=admnews");
	}
        break;
        default:
        }
		return $args;
}

function admnews_run(){
        global $session;
        $op = httpget('op');
        switch($op){
        case "":
        page_header("News Additions");
        output("`^`c`bAdd News`b`c`6");
	global $SCRIPT_NAME;
	villagenav();
	output("[Admin] Insert News?`n");
	output("<form action='runmodule.php?module=admnews&op=confirm' method='POST'><input name='addone'><input type='submit' class='button' value='submit'></form>",true);
        addnav("","runmodule.php?module=admnews&op=confirm");
	page_footer();
}
	if ($op == "confirm"){
	page_header("News Additions");
	$addone = httppost('addone');
	villagenav();
        addnav("Daily News","news.php");
	output("`0Admin `^news `0has been added.");
	addnews("`5\"`6%s`5\" %s`5 announces.",stripslashes(httppost('addone')),$session['user']['name']);
	page_footer();
	} 
 return($args);
}
