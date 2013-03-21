<?php

        if ($weap){
                output("`i`@Zeratul `i`)takes your %s `)and brings it into his back room.",$session['user']['weapon']);
                output(" When he returns, a new sheen is left on your weapon.");
                output("`i`@Zeratul`i`) grins with satisfaction and hands it back to you.");
                output(" \"`i`@This, is a work of art.");
                output(" Please take good care of it...`i`)\"");
                $session['user']['gold'] -= $auggold;
                $session['user']['gems'] -= $auggems;
                $session['user']['attack']++;
                $session['user']['weapondmg']++;
                set_module_pref("augweap",1);
        }
        if ($arm){
                output("`i`@Zeratul`i`) spins about and tears off your %s`).",$session['user']['armor']);
                output(" Whilst you stand there naked, he whisks about, making alterations.");
                output(" He walks back to you, and slips your armor back onto you.");
                output(" With a satisfied nod, he takes your crystal from you.");
                $session['user']['gold'] -= $auggold;
                $session['user']['gems'] -= $auggems;
                $session['user']['defense']++;
                $session['user']['armordef']++;
                set_module_pref("augarm",1);
        }
        switch ($crys){
                case "red":
                        set_module_pref("red",0);
                        break;
                case "blue":
                        set_module_pref("blue",0);
                        break;
                case "green":
                        set_module_pref("green",0);
                        break;
                case "white":
                        set_module_pref("white",0);
                        break;
                case "black":
                        set_module_pref("black",0);
                        break;
        }

?>
