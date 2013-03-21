<?php
//Hooked into Library Card system
function book_goodbargain_getmoduleinfo(){
	$info = array(
		"name"=>"The Good Bargain",
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

function book_goodbargain_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_goodbargain_uninstall(){
	return true;
}

function book_goodbargain_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Good Bargain", "runmodule.php?module=book_goodbargain");
		break;
	}
	}
	return $args;
}

function book_goodbargain_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Good Bargain`b`c`n");
	output("`!`cWritten by The Brothers Grimm`c`n`n");
	output("There was once a peasant who had driven his cow to the fair, and sold her for seven talers. On the way home he had to pass a pond, and already from afar he heard the frogs crying, aik, aik, aik, aik. Well, said he to himself, they are talking without rhyme or reason, it is seven that I have received, not eight. When he got to the water, he cried to them, stupid animals that you are. Don't you know better than that. It is seven thalers and not eight. The frogs, however, stuck to their, aik aik, aik, aik. Come, then, if you won't believe it, I can count it out to you. And he took his money out of his pocket and counted out the seven talers, always reckoning four and twenty groschen to a taler. The frogs, however, paid no attention to his reckoning, but still cried, aik, aik, aik, aik. What, cried the peasant, quite angry, if you know better than I, count it yourselves, and threw all the money at them into the water. He stood still and wanted to wait until they were through and had returned to him what was his, but the frogs maintained their opinion and cried continually, aik, aik, aik, aik. And besides that, did not throw the money out again. He still waited a long while until evening came on and he was forced to go home. Then he abused the frogs and cried, you water-splashers, you thick-heads, you goggle-eyes, you have great mouths and can screech till you hurt one's ears, but you cannot count seven talers. Do you think I'm going to stand here till you get through. And with that he went away, but the frogs still cried, aik, aik, aik, aik, after him till he went home sorely vexed. After a while he bought another cow, which he slaughtered, and he made the calculation that if he sold the meat well he might gain as much as the two cows were worth, and have the hide into the bargain. When therefore he got to the town with the meat, a great pack of dogs were gathered together in front of the gate, with a large greyhound at the head of them, which jumped at the meat, sniffed at it, and barked, wow, wow, wow. As there was no stopping him, the peasant said to him, yes, yes, I know quite well that you are saying wow, wow, wow, because you want some of the meat, but I should be in a fine state if I were to give it to you. The dog, however, answered nothing but wow, wow. Will you promise not to devour it all then, and will you go bail for your companions. Wow, wow, wow, said the dog. Well, if you insist on it, I will leave it for you, I know you well, and know whom you serve, but this I tell you, I must have my money in three days or else it will go ill with you, you can just bring it out to me. Thereupon he unloaded the meat and turned back again. The dogs fell upon it and loudly barked, wow, wow. The countryman, who heard them from afar, said to himself, hark, now they all want some, but the big one is responsible to me for it. When three days had passed, the countryman thought, to-night my money will be in my pocket, and was quite delighted. But no one would come and pay it. There is no trusting any one now, said he. At last he lost patience, and went into the town to the butcher and demanded his money. The butcher thought it was a joke, but the peasant said, jesting apart, I will have my money. Did not the big dog bring you the whole of the slaughtered cow three days ago. Then the butcher grew angry, snatched a broomstick and drove him out. Wait, said the peasant, there is still some justice in the world, and went to the royal palace and begged for an audience. He was led before the king, who sat there with his daughter, and asked him what injury he had suffered. Alas, said he, the frogs and the dogs have taken from me what is mine, and the butcher has paid me for it with the stick. And he related at full length what had happened. Thereupon the king's daughter began to laugh heartily, and the king said to him, I cannot give you justice in this, but you shall have my daughter to wife for it - in her whole life she has never yet laughed as she has just done at you, and I have promised her to him who could make her laugh. You may thank God for your good fortune. Oh, answered the peasant, I do not want her at all. I have a wife already, and she is one too many for me, when I go home, it is just as if I had a wife standing in every corner. Then the king grew angry, and said, you are a boor. Ah, lord king, replied the peasant, what can you expect from an ox, but beef. Stop, answered the king, you shall have another reward. Be off now, but come back in three days, and then you shall have five hundred counted out in full. When the peasant went out by the gate, the sentry said, you have made the king's daughter laugh, so you will certainly receive something good. Yes, that is what I think, answered the peasant, five hundred are to be counted out to me. Listen, said the soldier, give me some of it. What can you do with all that money. As it is you, said the peasant, you shall have two hundred, present yourself in three days, time before the king, and let it be paid to you. A Jew, who was standing by and had heard the conversation, ran after the peasant, held him by the coat, and said, oh, wonder of God, what a child of fortune you are. I will change it for you, I will change it for you into small coins, what do you want with the great talers. Jew, said the countryman, three hundred can you still have, give it to me at once in coin, in three days from this, you will be paid for it by the king. The Jew was delighted with the small profit, and brought the sum in bad groschen, three of which were worth two good ones. After three days had passed, according to the king's command, the peasant went before the king. Pull his coat off, said the latter, and he shall have his five hundred. Ah, said the peasant, they no longer belong to me, I presented two hundred of them to the sentry, and three hundred the Jew has changed for me, so by right nothing at all belongs to me. In the meantime the soldier and the Jew entered and claimed what they had gained from the peasant, and they received the blows strictly counted out. The soldier bore it patiently and knew already how it tasted, but the Jew said sorrowfully, alas, alas, are these the heavy talers. The king could not help laughing at the peasant, and when all his anger was spent, he said, as you have already lost your reward before it fell to your lot, I will give you compensation. Go into my treasure chamber and get some money for yourself, as much as you will. The peasant did not need to be told twice, and stuffed into his big pockets whatsoever would go in. Afterwards he went to an inn and counted out his money. The Jew had crept after him and heard how he muttered to himself, that rogue of a king has cheated me after all, why could he not have given me the money himself, and then I should have known what I had. How can I tell now if what I have had the luck to put in my pockets is right or not. Good heavens, said the Jew to himself, that man is speaking disrespectfully of our lord the king, I will run and inform, and then I shall get a reward, and he will be punished as well. When the king heard of the peasant's words he fell into a passion, and commanded the Jew to go and bring the offender to him. The Jew ran to the peasant, you are to go at once to the lord king in the very clothes you have on. I know what's right better than that, answered the peasant, I shall have a new coat made first. Do you think that a man with so much money in his pocket should go there in his ragged old coat. The Jew, as he saw that the peasant would not stir without another coat, and as he feared that if the king's anger cooled, he himself would lose his reward, and the peasant his punishment, said, I will out of pure friendship lend you a coat for the short time. What people will not do for love. The peasant was contented with this, put the Jew's coat on, and went off with him. The king reproached the countryman because of the evil speaking of which the Jew had informed him. Ah, said the peasant, what a Jew says is always false - no true word ever comes out of his mouth. That rascal there is capable of maintaining that I have his coat on. What is that, shrieked the Jew, is the coat not mine. Have I not lent it to you out of pure friendship, in order that you might appear before the lord king. When the king heard that, he said, the Jew has assuredly deceived one or the other of us, either myself or the peasant. And again he ordered something to be counted out to him in hard thalers. The peasant, however, went home in the good coat, with the good money in his pocket, and said to himself, this time I have made it.");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>