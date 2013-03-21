<?php

function gameevents_getmoduleinfo(){
        $info = array(
                "name"=>"Game Events",
                "author"=>"`)ShadowRaven",
                "version"=>"0.5",
                "category"=>"Administrative",
                "download"=>"http://www.altereduniverse.net/index.php?action=tpmod;dl=item24",
                "allowanonymous"=>true,
                "settings"=>array(
                        "General settings,title",
                                "The name can be whatever you want(example: Ye Olde Notice Board),note",
                                "name"=>"Name of events board,text|Upcoming events",
                                "where"=>"Where do you want the link to show?,enum,0,village,1,index page,2,both",
                        "Event 1,title",
                                "You must select yes for users to view this event,note",
                                "show"=>"Show this event?,bool|0",
                                "evename"=>"name/title of event,text|",
                                "date"=>"Date of event(eg:Jan 25 or Jan 25 at 7pm etc),text|",
                                "Here tell everyone about the event,note",
                                "event"=>"Describe the event,varchar|",

                        "Event 2,title",
                                "You must select yes for users to view this event,note",
                                "show2"=>"Show this event?,bool|0",
                                "evename2"=>"name/title of event,text|",
                                "date2"=>"Date of event(eg:Jan 25 or Jan 25 at 7pm etc),text|",
                                "Here tell everyone about the event,note",
                                "event2"=>"Describe the event,varchar|",

                        "Event 3,title",
                                "You must select yes for users to view this event,note",
                                "show3"=>"Show this event?,bool|0",
                                "evename3"=>"name/title of event,text|",
                                "date3"=>"Date of event(eg:Jan 25 or Jan 25 at 7pm etc),text|",
                                "Here tell everyone about the event,note",
                                "event3"=>"Describe the event,varchar|",
                        "Event 4,title",
                                "You must select yes for users to view this event,note",
                                "show4"=>"Show this event?,bool|0",
                                "evename4"=>"name/title of event,text|",
                                "date4"=>"Date of event(eg:Jan 25 or Jan 25 at 7pm etc),text|",
                                "Here tell everyone about the event,note",
                                "event4"=>"Describe the event,varchar|",
                         ),
);
return $info;
}
function gameevents_install(){
        module_addhook("village");
        module_addhook("index");
        return true;
}
function gameevents_uninstall(){
        return true;
}

function gameevents_dohook($hookname,$args){
        global $session;
        $name = get_module_setting("name");
        $where = get_module_setting("where");
        switch( $hookname ){
                case "village":
                  if ($where == 0 || $where == 2){
                        output_notl("`c`n`^[");
                        rawoutput("<a href='runmodule.php?module=gameevents&op=show'>$name</a>");
                        addnav("","runmodule.php?module=gameevents&op=show");
                        output_notl("`^]`c`0");
                                                 }
              break;
              case "index":
                   if ($where == 1 || $where == 2) {
                        output_notl("`n`^[");
                        rawoutput("<a href='runmodule.php?module=gameevents&op=show'>$name</a>");
                        addnav("","runmodule.php?module=gameevents&op=show");
                        output_notl("`^]`n`0");
                                                   }
              break;
                     }
return $args;
}

function gameevents_run(){
        global $session;
        $name = get_module_setting("name");
        $evename = get_module_setting("evename");
        $evename2 = get_module_setting("evename2");
        $evename3 = get_module_setting("evename3");
        $date = get_module_setting("date");
        $date2 = get_module_setting("date2");
        $date3 = get_module_setting("date3");
        $date4 = get_module_setting("date4");
        $event = get_module_setting("event");
        $event2 = get_module_setting("event2");
        $event3 = get_module_setting("event3");
        $event4 = get_module_setting("event4");
        $show = get_module_setting("show");
        $show2 = get_module_setting("show2");
        $show3 = get_module_setting("show3");
        $show4 = get_module_setting("show4");
        page_header("%s",$name);
        $op = httpget('op');
        if($op=="show"){
                rawoutput("<b><i><font size='5'>");
                output("`n`c`^ %s `c`n",$name);
                rawoutput("</font></i></b>");
                rawoutput('<center><table width="100%" bgcolor="#E1E1E1" border="1" cellpadding="1" bordercolor="#808080" cellspacing="1">');
                rawoutput('<tr valign="top"><td>');
              if($show == 0 && $show2 == 0 && $show3 == 0 && $show4 == 0){
                        output("`c`4There are no events scheduled at this time`c");
                        rawoutput('</td></tr></table></center>');
              }else{
            if($show == 1){
                rawoutput('<center><table width="95%" bgcolor="#EDEFED" border="1" cellpadding="1" cellspacing="1">');
                rawoutput('<tr><td>');
                output("`c`4Event:`2  %s `n",$evename);
                output("`n`4Date:`2 %s `n",$date);
                output("`n`4 %s`n`c",$event);
                rawoutput('</td></tr></table></center><br>');
                          }
            if($show2 == 1){
                rawoutput('<center><table width="95%" bgcolor="#FFFFFF" border="1" cellpadding="1" cellspacing="1">');
                rawoutput('<tr><td>');
                output("`c`4Event:`2  %s `n",$evename2);
                output("`n`4Date:`2 %s `n",$date2);
                output("`n`4 %s `n`c",$event2);
                rawoutput('</td></tr></table></center><br>');
                           }
            if($show3 == 1){
                rawoutput('<center><table width="95%" bgcolor="#EDEFED" border="1" cellpadding="1" cellspacing="1">');
                rawoutput('<tr><td>');
                output("`c`4Event:`2  %s `n",$evename3);
                output("`n`4Date:`2 %s `n",$date3);
                output("`n`4 %s `c`n",$event3);
                rawoutput('</td></tr></table></center><br>');
                           }
            if($show4 == 1){
                rawoutput('<center><table width="95%" bgcolor="#FFFFFF" border="1" cellpadding="1" cellspacing="1">');
                rawoutput('<tr><td>');
                output("`c`4Event:`2  %s `n",$evename4);
                output("`n`4Date:`2 %s `n",$date2);
                output("`n`4 %s `c`n",$event4);
                rawoutput('</td></tr></table></center><br>');
                           }
            rawoutput('</td></tr></table></center>');
               }
               }
        if ($session['user']['loggedin']) {
                addnav("Return to village","village.php");
        }else{
                addnav("Login Page","index.php");
             }
page_footer();
}
?>
