<?php
function book_dsosmichael_getmoduleinfo(){
    $info = array(
    "name"=>"Darker Side of Sympathy - The Story of Michael",
    "author"=>"`2Harbinger of Fear `4W`6ere`4M`6agi`0 - Built with Module Builder by `3Lonny Luberts`0",
    "version"=>"1.0",
    "category"=>"Library",
    "download"=>"http://www.pqcomp.com/modules/mydownloads/visit.php?cid=3&lid=175",
    "vertxtloc"=>"",
    "prefs" => array(
        "bookread" => "Has the player read this book?, bool|false",
        ),
    );
    return $info;
}

function book_dsosmichael_install(){
    if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
    module_addhook("library");
    return true;
}

function book_dsosmichael_uninstall(){
    return true;
}

function book_dsosmichael_dohook($hookname, $args){
    global $session;
    $card = get_module_pref("card","library");
    $ca = get_module_setting("ca","library");
    switch($hookname){
    case "library":
    if ($card==1 or $ca==0){
            addnav("Darker Side of Sympathy");
            addnav("1. The Story of Michael", "runmodule.php?module=book_dsosmichael");
        break;
    }
    }
    return $args;
}

function book_dsosmichael_run(){
    global $session;
    $op = httpget('op');
    page_header("Town Library");
    output("`#`c`bThe Story of Michael`b`c`n");
    output("`!`cWritten by ~`4RedRockingHood`c`n`n");
    rawoutput("<body>

<p>There was a young man, and at the tender age of nineteen, he became an orphan.</p>

 

<p>When most imagine a child who has lost his parents, they think much younger than that. Past the stage of pacifiers and diapers, before the age of first loves and diplomas; most think of yellow school busses, stuffed animals.</p>



<p>They underestimate how lost one can feel, when you’re supposed to be an ‘adult’, but haven’t a clue how the world works, just yet. When you’re told that now is the time to be independent, to developing the man you are meant to be, but you still need a guide every step of the way. 



<p>A young man by the name of Michael was still finding his feet. He did not live at home. He wasn’t going to university, just yet; he didn’t know which direction he wanted to take, in his life. He worked at a store that sold musical instruments, and used the money to save for a car. Half of it went towards his rent. He only needed fifty percent of his paycheque, for that. 



<p>One half of his rent paid by a roommate he rarely saw. One quarter paid by his mother and father, who scraped the bottom of the bank to help him get a solid start. One quarter from his own pocket. 



<p>He did his laundry at their house. He was slowly learning how to cook. Michael still felt very much like a child, shoved in front of his father’s closet and told to pick the suit he wanted to wear for the rest of his life. 



<p>They’d perished in a house fire. Michael was told it was because someone had forgotten to turn off a small electrical heater before they went to sleep that night, and it had sparked. The curtains had caught, and the alarm didn’t go off in time. Perhaps it had been faulty. 



<p>No matter what the cause, Michael’s parents were dead, and he had never felt quite so lost and alone…not even when he was very small, not in any circumstance he’d ever faced. 



<p>There were financial matters to go over. Confusing paperwork thrust under his nose, and he signed it all without knowing what any of it meant. He would nod, when presented with numbers, and people used terms that didn’t mean a thing to him. He had to scavenge through the burnt shell of his house and go through his parents’ things. He had to determine what could be salvaged – and what of that should be kept, or sold, or simply thrown away. 



<p>His workplace gave him one week to grieve, and paid him on the day of the funeral. 



<p>When Michael went to the graveyard, on that day, he stared at his mother and father’s name carved into stone, and felt weaker than a little boy. Weaker than a boy whose world was yellow school busses and stuffed animals; even weaker than a little boy whose world was pacifiers and diapers.



<p>When he left the graveyard, he was still lost. But he was no longer alone.



<p><center>~~~~~~~~</center>





<p>In the worst of times, people display a strange tendency. It’s not commonly found in any species with a less complex brain, and honestly, I can’t be certain if that’s to our credit, or theirs. Humans can ignore their will to survive. Humans can fight it. 



<p>Michael began to flounder. His roommate tried to be understanding, but he didn’t make enough money to cover more than his half of the rent. His landlord was less forgiving, and reminded Michael that, at nineteen, he was an adult. He ought to know how the world works. He should be responsible. 



<p>Each day became a battle. Then, they became wars. From the first skirmish he’d have with himself when fighting to lift his head off his pillow, to the brawl he’d engage in when trying not to simply fall into bed at the end of the day, Michael was locked in combat against his own overwhelmed mind. His exhaustion was winning. His hygiene suffered, his performance at work was barely passable, and days would pass where he’d simply forget to eat. 



<p>It shouldn’t have come as a surprise to anyone when he needed to be rushed to the hospital, following an attempt on his own life. 



<p>They placed him on suicide watch. They held him for two weeks. He couldn’t afford the hospital fees, he argued, and why would he try to commit suicide again? He’d learned, hadn’t he? He’d been the one to make the call. He’d called his own ambulance, he’d tried to bandage his own wounds. For safety’s sake, they held him. 



<p>But he seemed better. The psychologist on-call asked him why, and Michael told him (a variation of) the truth: 



<p>He’d come home from work that day, having broken a guitar string that slashed open his finger. It wasn’t bleeding much, by that point, and he was home alone. He went to the kitchen, took the sharpest knife they owned, and locked himself in his room. He’d poked at the slim cut until it reopened and was dripping flesh blood down his palm. Then, with the edge of the knife, he followed the trail down. Slicing open his palm, cutting into his wrist, digging out the weakness and helplessness and pain. 



<p>It all came clear to him, then; he’d wanted to die. 



<p>He cut across the veins, slit himself right to the elbow, tried to follow suit along the other arm. That was when she came to him. 



<p>From there, Michael would get cagey. ‘It was like’, ‘I imagined’, ‘I pretended’. Those words were used out of fear. They’d call him insane, he knew, if he told them the truth. 



<p>His mother had come to him, as an angel. 



<p>Michael had gone to church, as a boy. He still went, on special occasion; Good Friday, Easter Sunday, Christmas. He’d always liked to think that angels watched over them, and now he knew. 



<p>His mother had embraced him, and he had known what bliss awaited him, if he would be the dutiful son. A man who took his own life would never know that unknowable Heaven, the kind he’d found in his angel-mother’s arms. 



<p>He was already beginning to forget what Heaven felt like, by the time he left the hospital.



<p>He knew what kind of man he wanted to be. He wanted to be his mother’s good boy. He wanted to be a man of the Lord, so he would once again find Heaven.



<p><center>~~~~~~~~</center>



<p>The stairway to Heaven needs to be built. 



<p>God created mankind for the same reasons a mother and father would couple to make a child. They wanted love. 



<p>And yet, all around, there was sin. Blights against mankind, displays of hatred towards a loving and caring creator. 



<p>The stairway to Heaven can be built with bone. It can be carpeted with flesh. It can be painted with blood. 



<p>Michael began building his stairway, so that he might once again be welcomed into his mother’s warm embrace.



<p>And that is how Michael’s story began.

</body>");
    $number = e_rand(1,3);
    if ($number == 3) {
        $session[user][experience]*=1.05;
        output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
    }
        addnav("Return Book to Shelf","runmodule.php?module=library&op=enter");
        page_footer();
}
?>