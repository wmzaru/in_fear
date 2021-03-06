<?php
//Hooked into Library Card system
function book_threespinners_getmoduleinfo(){
	$info = array(
		"name"=>"The Three Spinners",
		"author"=>"Lonny Luberts - based on Script by WebPixie.",
		"version"=>"1.0",
		"category"=>"Library",
		"download"=>"",
		"prefs" => array(
			"bookread" => "Has the player read this book?, bool|false",
		),
	);
	return $info;
}

function book_threespinners_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_threespinners_uninstall(){
	return true;
}

function book_threespinners_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Three Spinners", "runmodule.php?module=book_threespinners");
		break;
	}
	}
	return $args;
}

function book_threespinners_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Three Spinners`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There was once a girl who was idle and would not spin, and let her mother say what she would, she could not bring her to it. At last the mother was once so overcome with anger and impatience, that she beat her, at which the girl began to weep loudly. Now at this very moment the queen drove by, and when she heard the weeping she stopped her carriage, went into the house and asked the mother why she was beating her daughter so that the cries could be heard out on the road. Then the woman was ashamed to reveal the laziness of her daughter and said, I cannot get her to leave off spinning. She insists on spinning for ever and ever, and I am poor, and cannot procure the flax. Then answered the queen, there is nothing that I like better to hear than spinning, and I am never happier than when the wheels are humming. Let me have your daughter with me in the palace. I have flax enough, and there she shall spin as much as she likes. The mother was heartily satisfied with this, and the queen took the girl with her. When they had arrived at the palace, she led her up into three rooms which were filled from the bottom to the top with the finest flax. Now spin me this flax, said she, and when you have done it, you shall have my eldest son for a husband, even if you are poor. I care not for that, your untiring industry is dowry enough. The girl was secretly terrified, for she could not have spun the flax, no, not if she had lived till she was three hundred years old, and had sat at it every day from morning till night. When therefore she was alone, she began to weep, and sat thus for three days without moving a finger. On the third day came the queen, and when she saw that nothing had yet been spun, she was surprised, but the girl excused herself by saying that she had not been able to begin because of her great distress at leaving her mother's house. The queen was satisfied with this, but said when she was going away, tomorrow you must begin to work. When the girl was alone again, she did not know what to do, and in her distress went to the window. Then she saw three women coming towards her, the first of whom had a broad flat foot, the second had such a great underlip that it hung down over her chin, and the third had a broad thumb. They remained standing before the window, looked up, and asked the girl what was amiss with her. She complained of her trouble, and then they offered her their help and said, if you will invite us to the wedding, not be ashamed of us, and will call us your aunts, and likewise will place us at your table, we will spin up the flax for you, and that in a very short time. With all my heart, she replied, do but come in and begin the work at once. Then she let in the three strange women, and cleared a place in the first room, where they seated themselves and began their spinning. The one drew the thread and trod the wheel, the other wetted the thread, the third twisted it, and struck the table with her finger, and as often as she struck it, a skein of thread fell to the ground that was spun in the finest manner possible. The girl concealed the three spinners from the queen, and showed her whenever she came the great quantity of spun thread, until the latter could not praise her enough. When the first room was empty she went to the second, and at last to the third, and that too was quickly cleared. Then the three women took leave and said to the girl, do not forget what you have promised us - it will make your fortune. When the maiden showed the queen the empty rooms, and the great heap of yarn, she gave orders for the wedding, and the bridegroom rejoiced that he was to have such a clever and industrious wife, and praised her mightily. I have three aunts, said the girl, and as they have been very kind to me, I should not like to forget them in my good fortune, allow me to invite them to the wedding, and let them sit with us at table. The queen and the bridegroom said, why should we not allow that. Therefore when the feast began, the three women entered in strange apparel, and the bride said, welcome, dear aunts. Ah, said the bridegroom, how do you come by these odious friends. Thereupon he went to the one with the broad flat foot, and said, how do you come by such a broad foot. By treading, she answered, by treading. Then the bridegroom went to the second, and said, how do you come by your falling lip. By licking, she answered, by licking. Then he asked the third, how do you come by your broad thumb. By twisting the thread, she answered, by twisting the thread. On this the king's son was alarmed and said, neither now nor ever shall my beautiful bride touch a spinning-wheel. And thus she got rid of the hateful flax-spinning.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>