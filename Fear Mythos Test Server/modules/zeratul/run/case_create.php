<?php

        if ($session['user']['gold'] >= $cregold || $session['user']['gems'] >= $cregems){
                if (get_module_pref("red") == 1 && get_module_pref("blue") == 1 && get_module_pref("green") == 1 && get_module_pref("white") == 1 && get_module_pref("black") == 1){
                        switch ($op2){
                                case "armor":
                                        output("`i`@Zeratul `i`)looks at you and ponders.");
                                        output(" He looks upon your %s and asks, \"`i`@Are ye sure that ya wish to rid yourself of this terribly crafted armor?`i`)\"",$session['user']['armor']);
                                        output(" You nod, and then take a step forward.");
                                        output("`i`@Zeratul`i`) responds, \"`i`@Okay, this will cost ALL of your crystals... are you sure now?`i`)\"");
                                        addnav("Choices");
                                        addnav("Yes","runmodule.php?module=zeratul&op=finish&arm=1");
                                        break;
                                case "weapon":
                                        output("`i`@Zeratul `i`)looks out the window.");
                                        output("\"`i`@Ya know... MightyE is losing business, because you are turning to me.");
                                        output(" Of course, that is better for me.`i`)\"");
                                        output(" He grins and clasps his hands together.");
                                        output("`i`@Zeratul`i`) responds, \"`i`@Okay, this will cost ALL of your crystals... are you sure now?`i`)\"");
                                        addnav("Choices");
                                        addnav("Yes","runmodule.php?module=zeratul&op=finish&weap=1");
                                        break;
                                case "extra":
                                        output("`i`@Zeratul `i`) arches a brow.");
                                        output(" He walks to his shelf, and pulls down a small talisman.");
                                        output(" \"`i`@So, you wish to have something like this?`i`)\" he inquires.");
                                        output(" He awaits your decision.");
                                        output("`i`@Zeratul`i`) hurries up, \"`i`@Okay, this will cost ALL of your crystals... are you sure now?`i`)\"");
                                        addnav("Choices");
                                        addnav("Yes","runmodule.php?module=zeratul&op=finish&ext=1");
                                        break;
                        }
                        addnav("Choices");
                        addnav("No","runmodule.php?module=zeratul&op=enter");
                }else{
                        output("`i`@Zeratul`i`) shakes his head at you.");
                        output(" He points to a sign.");
                        output(" To create a piece of equipment, all of the crystals must be collected.");
                        output(" He then points to the door, staring at the ground.");
                }
        }else{
                output("`i`@Zeratul `i`)looks at you and frowns.");
                if ($session['user']['gold'] < $cregold) output("`n`n\"`i`@I am sorry... but you need `^%s `@more gold, until you can create anything...`i`)\"",$cregold - $session['user']['gold']);
                if ($session['user']['gems'] < $cregems){
                        output("`n`n\"`i`@I am terribly sorry... but you do not have enough gems to complete this creation.");
                        output(" You need `%%s `@more.`i`)\"",$cregems - $session['user']['gems']);
                }
        }

?>
