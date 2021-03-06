<?php
function book_dsosemily_getmoduleinfo(){
	$info = array(
	"name"=>"Darker Side of Sympathy - The Story of Emily",
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

function book_dsosemily_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_dsosemily_uninstall(){
	return true;
}

function book_dsosemily_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	$ca = get_module_setting("ca","library");
	switch($hookname){
	case "library":
	if ($card==1 or $ca==0){
			addnav("Darker Side of Sympathy");
			addnav("2. The Story of Emily", "runmodule.php?module=book_dsosemily");
		break;
	}
	}
	return $args;
}

function book_dsosemily_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Story of Emily`b`c`n");
	output("`!`cWritten by ~4RedRockingHood`c`n`n");
	rawoutput("There was a girl who was far too young for adult concepts, but children start remarkably early, nowadays. 

Emily was shy. She had been shuffled through three different schools � one per grade. Eight years old and going into the third grade, her only friends (or so it seemed) were the make-believe ones she�d constructed. She was imaginative, and clever; she would write out her daydreams by hand, every night, in a journal she hid under her mattress. When it would fill up, she�d ask for a new one, and over time she had filled so many that her mattress was a good inch higher than it should be. 

She came from a busy family; a mother who worked full-time, to put food on the table. A father who was nearly absentee, a volunteer teacher running classes down at the community center or hosting various clubs in their home. An older sister who, at thirteen, was starting to find her rebellious streak. A younger brother, at five, just entering kindergarten, and not dealing very well with the separation anxiety. 

Regardless, Emily was not a lonely girl. She found all the company she needed in her own mind, as neglected children are wont to do, and found that she preferred their imaginary company to what real children provided. 

The warfare among children was more conniving and brutal than one might expect � unless, of course, the reader lived it, and can recall it with clarity. Emily learned that the playground was a place of fragile allegiances. There was a fairly even divide between packs of friends, with stragglers that went between immature cliques. Emily, as �the quiet one�, was one of those stragglers. Girls who were friends one day would be ex-friends the next, and Emily would spend her recess going back and forth, conveying messages. 

Dutiful, unassuming, and all but invisible. The perfect child spy. 

She blended, and while nothing stayed secret for very long, with children, Emily was privy to everything. And, alongside her daydreams, she would write down the things she�d learned in her journal. As time wore on and she moved on from grade to grade, she became more involved with real people�and more involved with their secrets. Soon, her diaries were filled with nothing but gossip. 

It was around that time that Emily began to lie.

~~~~~~~~~

As so many sayings go, telling one lie often sparks another. Then another, and another, until the lies snowball out of control, and become all the teller of those tales know. 

Emily simply started with a fib that she had no need of telling. When her mother asked why she seemed so tired, that morning, she responded that she was feeling sick. Not ill enough to stay home from school that day, she assured her. The truth had been that she�d stayed up late, reading by the glow of her night-light (she was afraid of the dark). Her mother had nodded, and believed her. What reason was there not to? 

It was, as the term went, �a little white lie��but one that she hadn�t needed to tell. She wouldn�t have been punished, for staying up late, and she�d had nothing to gain. 

Still, that one, needless lie began opening doors to other ones. Emily was simply testing waters, seeing how far she could go while still being believed. When she did feel sick and didn�t want to go to school, she told her father she was being bullied by other students. When she tripped and scraped her knee, she told her teacher she�d been pushed. When she misplaced things, she told her siblings they�d been stolen. 

Any loving parent would rush to their daughter�s aid, when they think she�s being targeted. 

For the first time in her memory, quiet, invisible Emily was being listened to. People weren�t overlooking her so much, anymore. Her father gave up a few of his club meetings; she was accustomed to groups of adults coming over, on the weekends, but now that time was being set aside for her, instead. 

She hadn�t realized how much she�d missed her parents� attention until she began to fear losing it. It was then that she pushed too far. 

Emily came home with a bruise on her cheek. She�d been playing on the swings, after school, and landed poorly when she�d jumped off, at the highest point she could go. She told her now-attentive father that her teacher had held her after class, and struck her across the face.

She didn�t know why she told the lie. By now, they simply slipped out. 

The teacher lost her job, and Emily was wracked with guilt. It was too late to tell the truth. 

But she tried, anyway. 

No one believed her. 

When she tried to explain that she�d lied, that she was sorry, that she hadn�t meant for her teacher to be fired�they shook their heads, and anxiously told her she didn�t have to feel responsible. They wouldn�t let anything else happen to her. They would keep her safe from whoever was trying to hurt their little girl. 

For, by that time, the police were involved. There was a warrant on an innocent woman. The parents suspected her daughter�s violent, vengeful teacher of being the one to break into their home and leave tokens in Emily�s room. Assignments, art projects, a class photo. Who else � what else � would torment their daughter, so? What, aside from her own guilt?

~~~~~~~~

Even though her parents were attentive, now, there was so much they managed to miss. 

Emily grew more gaunt and listless, as her remorse ate away at her. Her truths were unheard, and her lies only brought trouble. She was only a child. She was not prepared for adult consequences to her mistruths. 

She bore the blame just as heavily and readily as anyone twice her age, and her overactive imagination caused her to see things, so she thought. 

A hulking black dog, snarling and growling, at the foot of her bed. 

She didn't tell anyone. She didn't think she would be believed. 

The truth of it was, she didn't even believe herself. 

She had an active imagination. Emily couldn'�t trust her own two eyes. 

Every night, since the lie that had cost a woman her livelihood and smeared her good name, she saw that dog. It steadily aged her mind and drove her mad. 

Her parents found her one morning, still in bed, reading her journals full of secrets. 

One long scar raked down each cheek, and a bloody needle and spool on her bedside table, the entire reel of thread used to sew her mouth tightly shut. No more lies would leave her lips.

And that is how Emily's story began.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library&op=enter");
	    page_footer();
}
?>