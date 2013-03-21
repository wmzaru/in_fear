<?php
/*
 * Title:       Default Commentary Color Selection
 * Date:	Sep 06, 2004
 * Version:	1.11
 * Author:      Joshua Ecklund
 * Email:       m.prowler@cox.net
 * Purpose:     Allow user to select a default color for their
 *              commentary and/or emote text.
 *
 * --Change Log--
 *
 * Date:        Sep 06, 2004
 * Version:	1.0
 * Purpose:     Initial Release
 *
 * Date:        Sep 06, 2004
 * Version:	1.1
 * Purpose:     Fixed emote bug, emotes are now colored just like
 *              other commentary.
 *
 * Date:        Sep 06, 2004
 * Version:	1.11
 * Purpose:     Changed to allow each user to set both commentary
 *              color and emote color for themselves.
 *
 */

function defaultcolor_getmoduleinfo() {
	$info = array(
		"name"=>"Default Commentary Color Selection",
		"version"=>"1.11",
		"author"=>"Joshua Ecklund",
		"category"=>"General",
                "download"=>"http://dragonprime.net/users/mProwler/defaultcolor.zip",
		"prefs"=>array(
			"Default Commentary Color Preferences,title",
                        "user_color"=>"Default color code to use for commentary|",
                        "user_emote"=>"Default color code to use for emotes|",
                ),
	);
	return $info;
}

function defaultcolor_install() {
        module_addhook("commentary");
        return(true);
}

function defaultcolor_uninstall() {
	return(true);
}

function defaultcolor_dohook($hookname, $args) {

        $color = get_module_pref("user_color");
        $emote = get_module_pref("user_emote");

	if ($hookname=="commentary" && $color != "" && $color{0} == "`") {
                $comment = $args['commentline'];

                // check if this is an emote line
                $isemote = ($comment{0} == ":" || $comment{0} == "/");
                if ($isemote && $emote != "" && $emote{0} == "`") {
                        $len = strlen($comment);
                        if (substr($comment,0,2) == "::") {
                                $comment = "::" . $emote . substr($comment,2,$len-2);
                        } elseif ($comment{0} == ":") {
                                $comment = ":" . $emote . substr($comment,1,$len-1);
                        } elseif (substr($comment,0,3) == "/me") {
                                $comment = "/me" . $emote . substr($comment,3,$len-3);
                        } else $comment = $emote . $comment;

                        $args['commentline'] = $comment;

                } elseif (!$isemote) $args['commentline'] = $color . $comment;
        }

        return($args);
}

function defaultcolor_run() {
}

?>