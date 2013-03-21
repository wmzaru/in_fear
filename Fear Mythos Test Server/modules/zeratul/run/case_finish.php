<?php

        if ($weap){
                output("`i`@Zeratul `i`)gathers all of your crystals and walks into his back room.");
                output(" You can hear some low chanting and a dulcet drone.");
                output(" He returns several hours later, with a glowing blade.");
                output(" \"`i`@This is an `&Archon Warp Blade`@.");
                output(" The pride of the  Dark Templar's force.");
                output(" Thanks for proving yourself worthy of this blade...`i`)\"");
                output("`i`@Zeratul `i`)hands you the blade, and smiles.");
                output(" You give it a quick whirl around and then wander out the front door.");
                $atk = $session['user']['weapondmg'];
                $final = 17-$atk;
                $session['user']['weapon'] = "Archon Warp Blade";
                $session['user']['attack']+=$final;
                $session['user']['weapondmg']=17;
                $session['user']['gold'] -= $cregold;
                $session['user']['gems'] -= $cregems;
                set_module_pref("weapon",1);
        }
        if ($arm){
                output("`i`@Zeratul `i`)takes all of your crystals and walks over to his portal.");
                output(" He chants into the burning blue flames and punches his hand into it.");
                output(" He pulls his hand back, and has a long flowing cloak, clutched in his hand.");
                output(" \"`i`@This is the cloak of my kind.");
                output(" This is made of the highest strength cloth.");
                output(" Man nor beast is able to rend it from it's seams.");
                output(" You have proven yourself worthy of this... and I am glad.`i`)\"");
                output(" You take the cloak, and throw it around your shoulders, smiling at the weight... for there is none.");
                output(" You wander out from the shoppe, and see that it is disappearing around you.");
                $def = $session['user']['armordef'];
                $final = 17-$def;
                $session['user']['gold'] -= $cregold;
                $session['user']['gems'] -= $cregems;
                $session['user']['armor'] = "Dark Templar's Cloak";
                $session['user']['defense']+=$final;
                $session['user']['armordef']=17;
                set_module_pref("armor",1);
        }
        if ($ext){
                output("`i`@Zeratul`i`) grabs your crystals, and takes the small talisman.");
                output(" He casts them into the fiery pits of his portal.");
                output(" Hours later, a shimmering talisman is regurgitated back out.");
                output(" `i`@Zeratul `i`)strings a golden rope through it, and walks over to you.");
                output(" \"`i`@Well, this is a good piece...");
                output(" This is my own personal creation...");
                output(" It shall aid you in battle...");
                output(" The effects shall take place, starting tomorrow... for it needs to channel the powers of my realm.`i`)\"");
                output(" He sets the talisman around your neck and smiles.");
                output(" You take it into your hand, and begin to walk out.");
                set_module_pref("extra",1);
        }
        set_module_pref("red",0);
        set_module_pref("blue",0);
        set_module_pref("green",0);
        set_module_pref("white",0);
        set_module_pref("black",0);
?>
