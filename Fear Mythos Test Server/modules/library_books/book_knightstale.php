<?php
//Hooked into Library Card system
function book_knightstale_getmoduleinfo(){
	$info = array(
		"name"=>"The Knights Tale",
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

function book_knightstale_install(){
	if (!is_module_installed("library")) {
         output("This module requires the Library module to be installed.");
         return false;
      }
	module_addhook("library");
	return true;
}

function book_knightstale_uninstall(){
	return true;
}

function book_knightstale_dohook($hookname, $args){
	global $session;
	$card = get_module_pref("card","library");
	switch($hookname){
	case "library":
	if ($card==1){
			addnav("Grimms Fairy Tales");
			addnav("The Knights Tale", "runmodule.php?module=book_knightstale");
		break;
	}
	}
	return $args;
}

function book_knightstale_run(){
	global $session;
	$op = httpget('op');
	page_header("Town Library");
	output("`#`c`bThe Knights Tale`b`c`n");
	output("`!`cWritten by Geoffrey Chaucer`c`n`n");
	output(" Once on a time, as old tales tell to us,
There was a duke whose name was Theseus:
Of Athens he was lord and governor,
And in his time was such a conqueror
That greater was there not beneath the sun.
Full many a rich country had he won;
What with his wisdom and his chivalry
He gained the realm of Femininity,
That was of old time known as Scythia.
There wedded he the queen, Hippolyta,
And brought her home with him to his country.
In glory great and with great pageantry,
And, too, her younger sister, Emily.
And thus, in victory and with melody,
Let I this noble duke to Athens ride
With all his armed host marching at his side.
And truly, were it not too long to hear,
I would have told you fully how, that year,
Was gained the realm of Femininity
By Theseus and by his chivalry;
And all of the great battle that was wrought
Where Amazons and the Athenians fought;
And how was wooed and won Hippolyta,
That fair and hardy queen of Scythia;
And of the feast was made at their wedding,
And of the tempest at their home-coming;
But all of that I must for now forbear.
I have, God knows, a large field for my share,
And weak the oxen, and the soil is tough.
The remnant of the tale is long enough.
I will not hinder any, in my turn;
Let each man tell his tale, until we learn
Which of us all the most deserves to win;
So where I stopped, again I'll now begin.
This duke of whom I speak, of great renown,
When he had drawn almost unto the town,
In all well-being and in utmost pride,
He grew aware, casting his eyes aside,
That right upon the road, as suppliants do,
A company of ladies, two by two,
Knelt, all in black, before his cavalcade;
But such a clamorous cry of woe they made
That in the whole world living man had heard
No such a lamentation, on my word;
Nor would they cease lamenting till at last
They'd clutched his bridle reins and held them fast.
'What folk are you that at my home-coming
Disturb my triumph with this dolorous thing?'
Cried Theseus. 'Do you so much envy
My honour that you thus complain and cry?
Or who has wronged you now, or who offended?
Come, tell me whether it may be amended;
And tell me, why are you clothed thus, in black?'
The eldest lady of them answered back,
After she'd swooned, with cheek so deathly drear
That it was pitiful to see and hear,
And said: 'Lord, to whom Fortune has but given
Victory, and to conquer where you've striven,
Your glory and your honour grieve not us;
But we beseech your aid and pity thus.
Have mercy on our woe and our distress.
Some drop of pity, of your gentleness,
Upon us wretched women, oh, let fall!
For see, lord, there is no one of us all
That has not been a duchess or a queen;
Now we are captives, as may well be seen:
Thanks be to Fortune and her treacherous wheel,
There's none can rest assured of constant weal.
And truly, lord, expecting your return,
In Pity's temple, where the fires yet burn,
We have been waiting through a long fortnight;
Now help us, lord, since it is in your might.
'I, wretched woman, who am weeping thus,
Was once the wife of King Capaneus,
Who died at Thebes, oh, cursed be the day!
And all we that you see in this array,
And make this lamentation to be known,
All we have lost our husbands at that town
During the siege that round about it lay.
And now the old Creon, ah welaway!
The lord and governor of Thebes city,
Full of his wrath and all iniquity,
He, in despite and out of tyranny,
To do the dead a shame and villainy,
Of all our husbands, lying among the slain,
Has piled the bodies in a heap, amain,
And will not suffer them, nor give consent,
To buried be, or burned, nor will relent,
But sets his dogs to eat them, out of spite.'
And on that word, at once, without respite,
They all fell prone and cried out piteously:
'Have on us wretched women some mercy,
And let our sorrows sink into your heart!'
This gentle duke down from his horse did start
With heart of pity, when he'd heard them speak.
It seemed to him his heart must surely break,
Seeing them there so miserable of state,
Who had been proud and happy but so late.
And in his arms he took them tenderly,
Giving them comfort understandingly:
And swore his oath, that as he was true knight,
He would put forth so thoroughly his might
Against the tyrant Creon as to wreak
Vengeance so great that all of Greece should speak
And say how Creon was by Theseus served,
As one that had his death full well deserved.
This sworn and done, he no more there abode;
His banner he displayed and forth he rode
Toward Thebes, and all his host marched on beside;
Nor nearer Athens would he walk or ride,
Nor take his ease for even half a day,
But onward, and in camp that night he lay;
And thence he sent Hippolyta the queen
And her bright sister Emily, I ween,
Unto the town of Athens, there to dwell
While he went forth. There is no more to tell.
The image of red Mars, with spear and shield,
So shone upon his banner's snow-white field
It made a billowing glitter up and down;
And by the banner borne was his pennon,
On which in beaten gold was worked, complete,
The Minotaur, which he had slain in Crete.
Thus rode this duke, thus rode this conqueror,
And in his host of chivalry the flower,
Until he came to Thebes and did alight
Full in the field where he'd intent to fight.
But to be brief in telling of this thing,
With Creon, who was Thebes' dread lord and king,
He fought and slew him, manfully, like knight,
In open war, and put his host to flight;
And by assault he took the city then,
Levelling wall and rafter with his men;
And to the ladies he restored again
The bones of their poor husbands who were slain,
To do for them the last rites of that day.
But it were far too long a tale to say
The clamour of great grief and sorrowing
Those ladies raised above the bones burning
Upon the pyres, and of the great honour
That Theseus, the noble conqueror,
Paid to the ladies when from him they went;
To make the story short is my intent.
When, then, this worthy duke, this Theseus
Had slain Creon and won Thebes city thus,
Still on the field he took that night his rest,
And dealt with all the land as he thought best.
In searching through the heap of enemy dead,
Stripping them of their gear from heel to head,
The busy pillagers could pick and choose,
After the battle, what they best could use;
And so befell that in a heap they found,
Pierced through with many a grievous, bloody wound,
Two young knights lying together, side by side,
Bearing one crest, wrought richly, of their pride,
And of those two Arcita was the one,
The other knight was known as Palamon.
Not fully quick, nor fully dead they were,
But by their coats of arms and by their gear
The heralds readily could tell, withal,
That they were of the Theban blood royal,
And that they had been of two sisters born.
Out of the heap the spoilers had them torn
And carried gently over to the tent
Of Theseus; who shortly had them sent
To Athens, there in prison cell to lie
For ever, without ransom, till they die.
And when this worthy duke had all this done,
He gathered host and home he rode anon,
With laurel crowned again as conqueror;
There lived he in all joy and all honour
His term of life; what more need words express?
And in a tower, in anguish and distress,
Palamon and Arcita, day and night,
Dwelt whence no gold might help them to take flight.
Thus passed by year by year and day by day,
Till it fell out, upon a morn in May,
That Emily, far fairer to be seen
Than is the lily on its stalk of green,
And fresher than is May with flowers new
(For with the rose's colour strove her hue,
I know not which was fairer of the two),
Before the dawn, as was her wont to do,
She rose and dressed her body for delight;
For May will have no sluggards of the night.
That season rouses every gentle heart
And forces it from winter's sleep to start,
Saying: 'Arise and show thy reverence.'
So Emily remembered to go thence
In honour of the May, and so she rose.
Clothed, she was sweeter than any flower that blows;
Her yellow hair was braided in one tress
Behind her back, a full yard long, I guess.
And in the garden, as the sun up-rose,
She sauntered back and forth and through each close,
Gathering many a flower, white and red,
To weave a delicate garland for her head;
And like a heavenly angel's was her song.
The tower tall, which was so thick and strong,
And of the castle was the great donjon,
(Wherein the two knights languished in prison,
Of whom I told and shall yet tell, withal),
Was joined, at base, unto the garden wall
Whereunder Emily went dallying.
Bright was the sun and clear that morn in spring,
And Palamon, the woeful prisoner,
As was his wont, by leave of his gaoler,
Was up and pacing round that chamber high,
From which the noble city filled his eye,
And, too, the garden full of branches green,
Wherein bright Emily, fair and serene,
Went walking and went roving up and down.
This sorrowing prisoner, this Palamon,
Being in the chamber, pacing to and fro,
And to himself complaining of his woe,
Cursing his birth, he often cried 'Alas!'
And so it was, by chance or other pass,
That through a window, closed by many a bar
Of iron, strong and square as any spar,
He cast his eyes upon Emilia,
And thereupon he blenched and cried out 'Ah!'
As if he had been smitten to the heart.
And at that cry Arcita did up-start,
Asking: 'My cousin, why what ails you now
That you've so deathly pallor on your brow?
Why did you cry out? Who's offended you?
For God's love, show some patience, as I do,
With prison, for it may not different be;
Fortune has given this adversity.
Some evil disposition or aspect
Of Saturn did our horoscopes affect
To bring us here, though differently 'twere sworn;
But so the stars stood when we two were born;
We must endure it; that, in brief, is plain.'
This Palamon replied and said again:
'Cousin, indeed in this opinion now
Your fancy is but vanity, I trow.
It's not our prison that caused me to cry.
But I was wounded lately through the eye
Down to my heart, and that my bane will be.
The beauty of the lady that I see
There in that garden, pacing to and fro,
Is cause of all my crying and my woe.
I know not if she's woman or goddess;
But Venus she is verily, I guess.'
And thereupon down on his knees he fell,
And said: 'O Venus, if it be thy will
To be transfigured in this garden, thus
Before me, sorrowing wretch, oh now help us
Out of this prison to be soon escaped.
And if it be my destiny is shaped,
By fate, to die in durance, in bondage,
Have pity, then, upon our lineage
That has been brought so low by tyranny.'
And on that word Arcita looked to see
This lady who went roving to and fro.
And in that look her beauty struck him so
That, if poor Palamon is wounded sore,
Arcita is as deeply hurt, and more.
And with a sigh he said then, piteously:
'The virgin beauty slays me suddenly
Of her that wanders yonder in that place;
And save I have her pity and her grace,
That I at least may see her day by day,
I am but dead; there is no more to say.'
This Palamon, when these words he had heard,
Pitilessly he watched him, and answered:
'Do you say this in earnest or in play?'
'Nay,' quoth Arcita, 'earnest, now, I say!
God help me, I am in no mood for play!'
Palamon knit his brows and stood at bay.
'It will not prove,' he said, 'to your honour
After so long a time to turn traitor
To me, who am your cousin and your brother,
Sworn as we are, and each unto the other,
That never, though for death in any pain,
Never, indeed, till death shall part us twain,
Either of us in love shall hinder other,
No, nor in any thing, O my dear brother;
But that, instead, you shall so further me
As I shall you. All this we did agree.
Such was your oath and such was mine also.
You dare not now deny it, well I know.
Thus you are of my party, beyond doubt.
And now you would all falsely go about
To love my lady, whom I love and serve,
And shall while life my heart's blood may preserve.
Nay, false Arcita, it shall not be so.
I loved her first, and told you all my woe,
As to a brother and to one that swore
To further me, as I have said before.
For which you are in duty bound, as knight,
To help me, if the thing lie in your might,
Or else you're false, I say, and downfallen.'
Then this Arcita proudly spoke again:
'You shall,' he said, 'be rather false than I;
And that you're so, I tell you utterly;
For par amour I loved her first, you know.
What can you say? You know not, even now,
Whether she is a woman or goddess!
Yours is a worship as of holiness,
While mine is love, as of a mortal maid;
Wherefore I told you of it, unafraid,
As to my cousin and my brother sworn.
Let us assume you loved her first, this morn;
Know you not well the ancient writer's saw
Of 'Who shall give a lover any law?'
Love is a greater law, aye by my pan,
Than man has ever given to earthly man.
And therefore statute law and such decrees
Are broken daily and in all degrees.
A man must needs have love, maugre his head.
He cannot flee it though he should be dead,
And be she maid, or widow, or a wife.
And yet it is not likely that, in life,
You'll stand within her graces; nor shall I;
For you are well aware, aye verily,
That you and I are doomed to prison drear
Perpetually; we gain no ransom here.
We strive but as those dogs did for the bone;
They fought all day, and yet their gain was none.
Till came a kite while they were still so wroth
And bore the bone away between them both.
And therefore, at the king's court, O my brother,
It's each man for himself and not for other.
Love if you like; for I love and aye shall;
And certainly, dear brother, that is all.
Here in this prison cell must we remain
And each endure whatever fate ordain.'
Great was the strife, and long, betwixt the two,
If I had but the time to tell it you,
Save in effect. It happened on a day
(To tell the tale as briefly as I may),
A worthy duke men called Pirithous,
Who had been friend unto Duke Theseus
Since each had been a little child, a chit,
Was come to visit Athens and visit
His play-fellow, as he was wont to do,
For in this whole world he loved no man so;
And Theseus loved him as truly- nay,
So well each loved the other, old books say,
That when one died (it is but truth I tell),
The other went and sought him down in Hell;
But of that tale I have no wish to write.
Pirithous loved Arcita, too, that knight,
Having known him in Thebes full many a year;
And finally, at his request and prayer,
And that without a coin of ransom paid,
Duke Theseus released him out of shade,
Freely to go where'er he wished, and to
His own devices, as I'll now tell you.
The compact was, to set it plainly down,
As made between those two of great renown:
That if Arcita, any time, were found,
Ever in life, by day or night, on ground
Of any country of this Theseus,
And he were caught, it was concerted thus,
That by the sword he straight should lose his head.
He had no choice, so taking leave he sped
Homeward to Thebes, lest by the sword's sharp edge
He forfeit life. His neck was under pledge.
How great a sorrow is Arcita's now!
How through his heart he feels death's heavy blow,
He weeps, he wails, he cries out piteously;
He thinks to slay himself all privily.
Said he: 'Alas, the day that I was born!
I'm in worse prison, now, and more forlorn;
Now am I doomed eternally to dwell
No more in Purgatory, but in Hell.
Alas, that I have known Pirithous!
For else had I remained with Theseus,
Fettered within that cell; but even so
Then had I been in bliss and not in woe.
Only the sight of her that I would serve,
Though I might never her dear grace deserve,
Would have sufficed, oh well enough for me!
O my dear cousin Palamon,' said he,
'Yours is the victory, and that is sure,
For there, full happily, you may endure.
In prison? Never, but in Paradise!
Oh, well has Fortune turned for you the dice,
Who have the sight of her, I the absence.
For possible it is, in her presence,
You being a knight, a worthy and able,
That by some chance, since Fortune's changeable.
You may to your desire sometime attain.
But I, that am in exile and in pain,
Stripped of all hope and in so deep despair
That there's no earth nor water, fire nor air,
Nor any creature made of them there is
To help or give me comfort, now, in this-
Surely I'll die of sorrow and distress;
Farewell, my life, my love, my joyousness!
'Alas! Why is it men so much complain
Of what great God, or Fortune, may ordain,
When better is the gift, in any guise,
Than men may often for themselves devise?
One man desires only that great wealth
Which may but cause his death or long ill-health.
One who from prison gladly would be free,
At home by his own servants slain might be.
Infinite evils lie therein, 'tis clear;
We know not what it is we pray for here.
We fare as he that's drunken as a mouse;
A drunk man knows right well he has a house,
But he knows not the right way leading thither;
And a drunk man is sure to slip and slither.
And certainly, in this world so fare we;
We furiously pursue felicity,
Yet we go often wrong before we die.
This may we all admit, and specially I,
Who deemed and held, as I were under spell,
That if I might escape from prison cell,
Then would I find again what might heal,
Who now am only exiled from my weal.
For since I may not see you, Emily,
I am but dead; there is no remedy.'
And on the other hand, this Palamon,
When that he found Arcita truly gone,
Such lamentation made he, that the tower
Resounded of his crying, hour by hour.
The very fetters on his legs were yet
Again with all his bitter salt tears wet.
'Alas!' said he, 'Arcita, cousin mine,
With all our strife, God knows, you've won the wine.
You're walking, now, in Theban streets, at large,
And all my woe you may from mind discharge.
You may, too, since you've wisdom and manhood,
Assemble all the people of our blood
And wage a war so sharp on this city
That by some fortune, or by some treaty,
You shall yet have that lady to your wife
For whom I now must needs lay down my life.
For surely 'tis in possibility,
Since you are now at large, from prison free,
And are a lord, great is your advantage
Above my own, who die here in a cage.
For I must weep and wail, the while I live,
In all the grief that prison cell may give,
And now with pain that love gives me, also,
Which doubles all my torment and my woe.'
Therewith the fires of jealousy up-start
Within his breast and burn him to the heart
So wildly that he seems one, to behold,
Like seared box tree, or ashes, dead and cold.
Then said he: 'O you cruel Gods, that sway
This world in bondage of your laws, for aye,
And write upon the tablets adamant
Your counsels and the changeless words you grant,
What better view of mankind do you hold
Than of the sheep that huddle in the fold?
For man must die like any other beast,
Or rot in prison, under foul arrest,
And suffer sickness and misfortune sad,
And still be ofttimes guiltless, too, by gad!
'What management is in this prescience
That, guiltless, yet torments our innocence?
And this increases all my pain, as well,
That man is bound by law, nor may rebel,
For fear of God, but must repress his will,
Whereas a beast may all his lust fulfill.
And when a beast is dead, he feels no pain;
But, after death, man yet must weep amain,
Though in this world he had but care and woe:
There is no doubt that it is even so.
The answer leave I to divines to tell,
But well I know this present world is hell.
Alas! I see a serpent or a thief,
That has brought many a true man unto grief,
Going at large, and where he wills may turn,
But I must lie in gaol, because Saturn,
And Juno too, both envious and mad,
Have spilled out well-nigh all the blood we had
At Thebes, and desolated her wide walls.
And Venus slays me with the bitter galls
Of fear of Arcita, and jealousy.'
Now will I leave this Palamon, for he
Is in his prison, where he still must dwell,
And of Arcita will I forthwith tell.
Summer being passed away and nights grown long,
Increased now doubly all the anguish strong
Both of the lover and the prisoner.
I know not which one was the woefuller.
For, to be brief about it, Palamon
Is doomed to lie for ever in prison,
In chains and fetters till he shall be dead;
And exiled (on the forfeit of his head)
Arcita must remain abroad, nor see,
For evermore, the face of his lady.
You lovers, now I ask you this question:
Who has the worse, Arcita or Palamon?
The one may see his lady day by day,
But yet in prison must he dwell for aye.
The other, where he wishes, he may go,
But never see his lady more, ah no.
Now answer as you wish, all you that can.
For I will speak right on as I began.
Explicit prima pars.
Sequitur pars secunda.
Now when Arcita unto Thebes was come,
He lay and languished all day in his home,
Since he his lady nevermore should see,
But telling of his sorrow brief I'll be.
Had never any man so much torture,
No, nor shall have while this world may endure.
Bereft he was of sleep and meat and drink,
That lean he grew and dry as shaft, I think.
His eyes were hollow and ghastly to behold,
His face was sallow, all pale and ashen-cold,
And solitary kept he and alone,
Wailing the whole night long, making his moan.
And if he heard a song or instrument,
Then he would weep ungoverned and lament;
So feeble were his spirits, and so low,
And so changed was he, that no man could know
Him by his words or voice, whoever heard.
And in this change, for all the world he fared
As if not troubled by malady of love,
But by that humor dark and grim, whereof
Springs melancholy madness in the brain,
And fantasy unbridled holds its reign.
And shortly, all was turned quite upside-down,
Both habits and the temper all had known
Of him, this woeful lover, Dan Arcite.
Why should I all day of his woe indite?
When he'd endured all this a year or two,
This cruel torment and this pain and woe,
At Thebes, in his own country, as I said,
Upon a night, while sleeping in his bed,
He dreamed of how the winged God Mercury
Before him stood and bade him happier be.
His sleep-bestowing wand he bore upright;
A hat he wore upon his ringlets bright.
Arrayed this god was (noted at a leap)
As he'd been when to Argus he gave sleep.
And thus he spoke: 'To Athens shall you wend;
For all your woe is destined there to end.'
And on that word Arcita woke and started.
'Now truly, howsoever sore I'm smarted,'
Said he, 'to Athens right now will I fare;
Nor for the dread of death will I now spare
To see my lady, whom I love and serve;
I will not reck of death, with her, nor swerve.'
And with that word he caught a great mirror,
And saw how changed was all his old colour,
And saw his visage altered from its kind.
And right away it ran into his mind
That since his face was now disfigured so,
By suffering endured (as well we know),
He might, if he should bear him low in town,
Live there in Athens evermore, unknown,
Seeing his lady well-nigh every day.
And right anon he altered his array,
Like a poor labourer in mean attire,
And all alone, save only for a squire,
Who knew his secret heart and all his case,
And who was dressed as poorly as he was,
To Athens was he gone the nearest way.
And to the court he went upon a day,
And at the gate he proffered services
To drudge and drag, as any one devises.
And to be brief herein, and to be plain,
He found employment with a chamberlain
Was serving in the house of Emily;
For he was sharp and very soon could see
What every servant did who served her there.
Right well could he hew wood and water bear,
For he was young and mighty, let me own,
And big of muscle, aye and big of bone,
To do what any man asked, in a trice.
A year or two he was in this service,
Page of the chamber of Emily the bright;
He said 'Philostrates' would name him right.
But half so well beloved a man as he
Was never in that court, of his degree;
His gentle nature was so clearly shown,
That throughout all the court spread his renown.
They said it were but kindly courtesy
If Theseus should heighten his degree
And put him in more honourable service
Wherein he might his virtue exercise.
And thus, anon, his name was so up-sprung,
Both for his deeds and sayings of his tongue,
That Theseus had brought him nigh and nigher
And of the chamber he had made him squire,
And given him gold to maintain dignity.
Besides, men brought him, from his own country,
From year to year, clandestinely, his rent;
But honestly and slyly it was spent,
And no man wondered how he came by it.
And three years thus he lived, with much profit,
And bore him so in peace and so in war
There was no man that Theseus loved more.
And in such bliss I leave Arcita now,
And upon Palamon some words bestow.
In darksome, horrible, and strong prison
These seven years has now sat Palamon,
Wasted by woe and by his long distress.
Who has a two-fold evil heaviness
But Palamon? whom love yet tortures so
That half out of his wits he is for woe;
And joined thereto he is a prisoner,
Perpetually, not only for a year.
And who could rhyme in English, properly,
His martyrdom? Forsooth, it is not I;
And therefore I pass lightly on my way.
It fell out in the seventh year, in May,
On the third night (as say the books of old
Which have this story much more fully told),
Were it by chance or were it destiny
(Since, when a thing is destined, it must be),
That, shortly after midnight, Palamon,
By helping of a friend, broke from prison,
And fled the city, fast as he might go;
For he had given his guard a drink that so
Was mixed of spice and honey and certain wine
And Theban opiate and anodyne,
That all that night, although a man might shake
This gaoler, he slept on, nor could awake.
And thus he flees as fast as ever he may.
The night was short and it was nearly day,
Wherefore he needs must find a place to hide;
And to a grove that grew hard by, with stride
Of furtive foot, went fearful Palamon.
In brief, he'd formed his plan, as he went on,
That in the grove he would lie fast all day,
And when night came, then would he take his way
Toward Thebes, and there find friends, and of them pray
Their help on Theseus in war's array;
And briefly either he would lose his life,
Or else win Emily to be his wife;
This is the gist of his intention plain.
Now I'll return to Arcita again,
Who little knew how near to him was care
Till Fortune caught him in her tangling snare.
The busy lark, the herald of the day,
Salutes now in her song the morning grey;
And fiery Phoebus rises up so bright
That all the east is laughing with the light,
And with his streamers dries, among the greves,
The silver droplets hanging on the leaves.
And so Arcita, in the court royal
With Theseus and his squire principal,
Is risen, and looks on the merry day.
And now, to do his reverence to May,
Calling to mind the point of his desire,
He on a courser, leaping high like fire,
Is ridden to the fields to muse and play,
Out of the court, a mile or two away;
And to the grove, whereof I lately told,
By accident his way began to hold,
To make him there the garland that one weaves
Of woodbine leaves and of green hawthorn leaves.
And loud he sang within the sunlit sheen:
'O May, with all thy flowers and all thy green,
Welcome be thou, thou fair and freshening May:
I hope to pluck some garland green today.'
And from his courser, with a lusty heart,
Into the grove right hastily did start,
And on a path he wandered up and down,
Near which, and as it chanced, this Palamon
Lay in the thicket, where no man might see,
For sore afraid of finding death was be.
He knew not that Arcita was so near:
God knows he would have doubted eye and ear,
But it has been a truth these many years
That 'Fields have eyes and every wood has ears.'
It's well for one to bear himself with poise;
For every day unlooked-for chance annoys.
And little knew Arcita of his friend,
Who was so near and heard him to the end,
Where in the bush lie sat now, keeping still.
Arcita, having roamed and roved his fill,
And having sung his rondel, lustily,
Into a study fell he, suddenly,
As do these lovers in their strange desires,
Now in the trees, now down among the briers,
Now up, now down, like bucket in a well.
Even as on a Friday, truth to tell,
The sun shines now, and now the rain comes fast,
Even so can fickle Venus overcast
The spirits of her people; as her day,
Is changeful, so she changes her array.
Seldom is Friday quite like all the week.
Arcita, having sung, began to speak,
And sat him down, sighing like one forlorn.
'Alas,' said he, 'the day that I was born!
How long, O Juno, of thy cruelty,
Wilt thou wage bitter war on Thebes city?
Alas! Confounded beyond all reason
The blood of Cadmus and of Amphion;
Of royal Cadmus, who was the first man
To build at Thebes, and first the town began,
And first of all the city to be king;
Of his lineage am I, and his offspring,
By true descent, and of the stock royal:
And now I'm such a wretched serving thrall,
That he who is my mortal enemy,
I serve him as his squire, and all humbly.
And even more does Juno give me shame,
For I dare not acknowledge my own name;
But whereas I was Arcita by right,
Now I'm Philostrates, not worth a mite.
Alas, thou cruel Mars! Alas, Juno!
Thus have your angers all our kin brought low,
Save only me, and wretched Palamon,
Whom Theseus martyrs yonder in prison.
And above all, to slay me utterly,
Love has his fiery dart so burningly
Struck through my faithful and care-laden heart,
My death was patterned ere my swaddling-shirt.
You slay me with your two eyes, Emily;
You are the cause for which I now must die.
For on the whole of all my other care
I would not set the value of a tare,
So I could do one thing to your pleasance!'
And with that word he fell down in a trance
That lasted long; and then he did up-start.
This Palamon, who thought that through his heart
He felt a cold and sudden sword blade glide,
For rage he shook, no longer would he hide.
But after he had heard Arcita's tale,
As he were mad, with face gone deathly pale,
He started up and sprang out of the thicket,
Crying: 'Arcita, oh you traitor wicked,
Now are you caught, that crave my lady so,
For whom I suffer all this pain and woe,
And are my blood, and know my secrets' store,
As I have often told you heretofore,
And have befooled the great Duke Thesues,
And falsely changed your name and station thus:
Either I shall be dead or you shall die.
You shall not love my lady Emily,
But I will love her, and none other, no;
For I am Palamon, your mortal foe.
And though I have no weapon in this place,
Being but out of prison by God's grace,
I say again, that either you shall die
Or else forgo your love for Emily.
Choose which you will, for you shall not depart.'
This Arcita, with scornful, angry heart,
When he knew him and all the tale had heard,
Fierce as a lion, out he pulled a sword,
And answered thus: 'By God that sits above!
Were it not you are sick and mad for love,
And that you have no weapon in this place,
Out of this grove you'd never move a pace,
But meet your death right now, and at my hand.
For I renounce the bond and its demand
Which you assert that I have made with you.
What, arrant fool, love's free to choose and do,
And I will have her, spite of all your might!
But in as much as you're a worthy knight
And willing to defend your love, in mail,
Hear now this word: tomorrow I'll not fail
(Without the cognizance of any wight)
To come here armed and harnessed as a knight,
And to bring arms for you, too, as you'll see;
And choose the better and leave the worse for me.
And meat and drink this very night I'll bring,
Enough for you, and clothes for your bedding.
And if it be that you my lady win
And slay me in this wood that now I'm in,
Then may you have your lady, for all of me.'
This Palamon replied: 'I do agree.'
And thus they parted till the morrow morn,
When each had pledged his honour to return.
O Cupido, that know'st not charity!
O despot, that no peer will have with thee!
Truly, 'tis said, that love, like all lordship,
Declines, with little thanks, a partnership.
Well learned they that, Arcite and Palamon.
Arcita rode into the town anon,
And on the morrow, ere the dawn, he bore,
Secretly, arms and armour out of store,
Enough for each, and proper to maintain
A battle in the field between the twain.
So on his horse, alone as he was born,
He carried out that harness as he'd sworn;
And in the grove, at time and place they'd set,
Arcita and this Palamon were met.
Each of the two changed colour in the face.
For as the hunter in the realm of Thrace
Stands at the clearing with his ready spear,
When hunted is the lion, or the bear,
And through the forest hears him rushing fast,
Breaking the boughs and leaves, and thinks aghast.
'Here comes apace my mortal enemy!
Now, without fail, he must be slain, or I;
For either I must kill him ere he pass,
Or he will make of me a dead carcass'-
So fared these men, in altering their hue,
So far as each the strength of other knew.
There was no 'good-day' given, no saluting,
But without word, rehearsal, or such thing,
Each of them helping, so they armed each other
As dutifully as he were his own brother;
And afterward, with their sharp spears and strong,
They thrust each at the other wondrous long.
You might have fancied that this Palamon,
In battle, was a furious, mad lion,
And that Arcita was a tiger quite:
Like very boars the two began to smite,
Like boars that froth for anger in the wood.
Up to the ankles fought they in their blood.
And leaving them thus fighting fast and fell,
Forthwith of Theseus I now will tell.
Great destiny, minister-general,
That executes in this world, and for all,
The needs that God foresaw ere we were born,
So strong it is that, though the world had sworn
The contrary of a thing, by yea or nay,
Yet sometime it shall fall upon a day,
Though not again within a thousand years.
For certainly our wishes and our fears,
Whether of war or peace, or hate or love,
All, all are ruled by that Foresight above.
This show I now by mighty Theseus,
Who to go hunting is so desirous,
And specially of the hart of ten, in May,
That, in his bed, there dawns for him no day
That he's not clothed and soon prepared to ride
With hound and horn and huntsman at his side.
For in his hunting has he such delight,
That it is all his joy and appetite
To be himself the great hart's deadly bane:
For after Mars, he serves Diana's reign.
Clear was the day, as I have told ere this,
When Theseus, compact of joy and bliss,
With his Hippolyta, the lovely queen,
And fair Emilia, clothed all in green,
A-hunting they went riding royally.
And to the grove of trees that grew hard by,
In which there was a hart, as men had told,
Duke Theseus the shortest way did hold.
And to the glade he rode on, straight and right,
For there the hart was wont to go in flight,
And over a brook, and so forth on his way.
This duke would have a course at him today,
With such hounds as it pleased him to command.
And when this duke was come upon that land,
Under the slanting sun he looked, anon,
And there saw Arcita and Palamon,
Who furiously fought, as two boars do;
The bright swords went in circles to and fro
So terribly, that even their least stroke
Seemed powerful enough to fell an oak;
But who the two were, nothing did he note.
This duke his courser with the sharp spurs smote,
And in one bound he was between the two,
And lugged his great sword out, and cried out: 'Ho!
No more, I say, on pain of losing head!
By mighty Mars, that one shall soon be dead
Who smites another stroke that I may see!
But tell me now what manner of men ye be
That are so hardy as to fight out here
Without a judge or other officer,
As if you-rode in lists right royally?'
This Palamon replied, then, hastily,
Saying: 'O Sire, what need for more ado?
We have deserved our death at hands of you.
Two woeful wretches are we, two captives
That are encumbered by our own sad lives;
And as you are a righteous lord and judge,
Give us not either mercy or refuge,
But slay me first, for sacred charity;
But slay my fellow here, as well, with me.
Or slay him first; for though you learn it late,
This is your mortal foe, Arcita- wait!-
That from the land was banished, on his head.
And for the which he merits to be dead.
For this is he who came unto your gate,
Calling himself Philostrates- nay, wait!-
Thus has he fooled you well this many a year,
And you have made him your chief squire, I hear:
And this is he that loves fair Emily.
For since the day is come when I must die,
I make confession plainly and say on,
That I am that same woeful Palamon
Who has your prison broken, viciously.
I am your mortal foe, and it is I
Who love so hotly Emily the bright
That I'll die gladly here within her sigh!
Therefore do I ask death as penalty,
But slay my fellow with the same mercy,
For both of us deserve but to be slain.'
This worthy duke presently spoke again,
Saying: 'This judgment needs but a short session:
Your own mouth, aye, and by your own confession,
Has doomed and damned you, as I shall record.
There is no need for torture, on my word.
But you shall die, by mighty Mars the red!'
But then the queen, whose heart for pity bled,
Began to weep, and so did Emily
And all the ladies in the company.
Great pity must it be, so thought they all,
That ever such misfortune should befall:
For these were gentlemen, of great estate,
And for no thing, save love, was their debate.
They saw their bloody wounds, so sore and wide,
And all cried out- greater and less, they cried:
'Have mercy, lord, upon us women all!'
And down upon their bare knees did they fall,
And would have kissed his feet there where he stood,
Till at the last assuaged was his high mood;
For soon will pity flow through gentle heart.
And though he first for ire did shake and start,
He soon considered, to state the case in brief,
What cause they had for fighting, what for grief;
And though his anger still their guilt accused,
Yet in his reason he held them both excused;
In such wise: he thought well that every man
Will help himself in love, if he but can,
And will himself deliver from prison;
And, too, at heart he had compassion on
Those women, for they cried and wept as one,
And in his gentle heart he thought anon,
And softly to himself he said then: 'Fie
Upon a lord that will have no mercy,
But acts the lion, both in word and deed,
To those repentant and in fear and need,
As well as to the proud and pitiless man
That still would do the thing that he began!
That lord must surely in discretion lack
Who, in such case, can no distinction make,
But weighs both proud and humble in one scale.'
And shortly, when his ire was thus grown pale,
He looked up to the sky, with eyes alight,
And spoke these words, as he would promise plight:
'The god of love, ah benedicite!
How mighty and how great a lord is he!
Against his might may stand no obstacles,
A true god is he by his miracles;
For he can manage, in his own sweet wise,
The heart of anyone as he devise.
Lo, here, Arcita and this Palamon,
That were delivered out of my prison,
And might have lived in Thebes right royally,
Knowing me for their mortal enemy,
And also that their lives lay in my hand;
And yet their love has wiled them to this land,
Against all sense, and brought them here to die!
Look you now, is not that a folly high?
Who can be called a fool, except he love?
And see, for sake of God who sits above,
See how they bleed! Are they not well arrayed?
Thus has their lord, the god of love, repaid
Their wages and their fees for their service!
And yet they are supposed to be full wise
Who serve love well, whatever may befall!
But this is yet the best jest of them all,
That she for whom they have this jollity
Can thank them for it quite as much as me;
She knows no more of all this fervent fare,
By God! than knows a cuckoo or a hare.
But all must be essayed, both hot and cold,
A man must play the fool, when young or old;
I know it of myself from years long gone:
For of love's servants I've been numbered one.
And therefore, since I know well all love's pain,
And know how sorely it can man constrain,
As one that has been taken in the net,
I will forgive your trespass, and forget,
At instance of my sweet queen, kneeling here,
Aye, and of Emily, my sister dear.
And you shall presently consent to swear
That nevermore will you my power dare,
Nor wage war on me, either night or day,
But will be friends to me in all you may;
I do forgive this trespass, full and fair.'
And then they swore what he demanded there,
And, of his might, they of his mercy prayed,
And he extended grace, and thus he said:
'To speak for royalty's inheritress,
Although she be a queen or a princess,
Each of you both is worthy, I confess,
When comes the time to wed: but nonetheless,
I speak now of my sister Emily,
The cause of all this strife and jealousy-
You know yourselves she may not marry two,
At once, although you fight or what you do:
One of you, then, and be he loath or lief,
Must pipe his sorrows in an ivy leaf.
That is to say, she cannot have you both,
However jealous one may be, or wroth.
Therefore I put you both in this decree,
That each of you shall learn his destiny
As it is cast; and hear, now, in what wise
The word of fate shall speak through my device.
'My will is this, to draw conclusion flat,
Without reply, or plea, or caveat
(In any case, accept it for the best),
That each of you shall follow his own quest,
Free of all ransom or of fear from me;
And this day, fifty weeks hence, both shall be
Here once again, each with a hundred knights,
Armed for the lists, who stoutly for your rights
Will ready be to battle, to maintain
Your claim to love. I promise you, again,
Upon my word, and as I am a knight,
That whichsoever of you wins the fight,
That is to say, whichever of you two
May with his hundred, whom I spoke of, do
His foe to death, or out of boundary drive,
Then he shall have Emilia to wive
To whom Fortune gives so fair a grace.
The lists shall be erected in this place.
And God so truly on my soul have ruth
As I shall prove an honest judge, in truth.
You shall no other judgment in me waken
Than that the one shall die or else be taken.
And if you think the sentence is well said,
Speak your opinion, that you're well repaid.
This is the end, and I conclude hereon.'
Who looks up lightly now but Palamon?
Who leaps for you but Arcita the knight?
And who could tell, or who could ever write
The jubilation made within that place
Where Theseus has shown so fair a grace?
But down on knee went each one for delight
And thanked him there with all his heart and might,
And specially those Thebans did their part.
And thus, with high hopes, being blithe of heart,
They took their leave; and homeward did they ride
To Thebes that sits within her old walls wide.
Explicit secunda pars.
Sequitur pars tercia.
I think that men would deem it negligence
If I forgot to tell of the expense
Of Theseus, who went so busily
To work upon the lists, right royally;
For such an amphitheatre he made,
Its equal never yet on earth was laid.
The circuit, rising, hemmed a mile about,
Walled all of stone and moated deep without.
Round was the shape as compass ever traces,
And built in tiers, the height of sixty paces,
That those who sat in one tier, or degree,
Should hinder not the folk behind to see.
Eastward there stood a gate of marble white.
And westward such another, opposite.
In brief, no place on earth, and so sublime,
Was ever made in so small space of time;
For in the land there was no craftsman quick
At plane geometry or arithmetic,
No painter and no sculptor of hard stone,
But Theseus pressed meat and wage upon
To build that amphitheatre and devise.
And to observe all rites and sacrifice,
Over the eastern gate, and high above,
For worship of Queen Venus, god of love,
He built an altar and an oratory;
And westward, being mindful of the glory
Of Mars, he straightway builded such another
As cost a deal of gold and many a bother.
And northward, in a turret on the wall,
Of alabaster white and red coral,
An oratory splendid as could be,
In honour of Diana's chastity,
Duke Theseus wrought out in noble wise.
But yet have forgot to advertise
The noble carvings and the portraitures,
The shapes, the countenances, the figures
That all were in these oratories three.
First, in the fane of Venus, one might see,
Wrought on the wall, and piteous to behold,
The broken slumbers and the sighing cold,
The sacred tears and the lamenting dire,
The fiery throbbing of the strong desire,
That all love's servants in this life endure;
The vows that all their promises assure;
Pleasure and hope, desire, foolhardiness,
Beauty, youth, bawdiness, and riches, yes,
Charms, and all force, and lies, and flattery,
Expense, and labour; aye, and Jealousy
That wore of marigolds a great garland
And had a cuckoo sitting on her hand;
Carols and instruments and feasts and dances,
Lust and array, and all the circumstances
Of love that I may reckon or ever shall,
In order they were painted on the wall,
Aye, and more, too, than I have ever known.
For truly, all the Mount of Citheron,
Where Venus has her chief and favoured dwelling,
Was painted on that wall, beyond my telling,
With all the gardens in their loveliness.
Nor was forgot the gate-guard Idleness,
Nor fair Narcissus of the years long gone,
Nor yet the folly of King Solomon,
No, nor the giant strength of Hercules,
Nor Circe's and Medea's sorceries,
Nor Turnus with his hardy, fierce courage,
Nor the rich Croesus, captive in his age.
Thus may be seen that wisdom, nor largess,
Beauty, nor skill, nor strength, nor hardiness,
May with Queen Venus share authority;
For as she wills, so must the whole world be.
Lo, all these folk were so caught in her snare
They cried aloud in sorrow and in care.
Here let suffice examples one or two,
Though I might give a thousand more to you.
The form of Venus, glorious as could be,
Was naked, floating on the open sea,
And from the navel down all covered was
With green waves, bright as ever any glass.
A citole in her small right hand had she,
And on her head, and beautiful to see,
A garland of red roses, sweet smelling,
Above her swirled her white doves, fluttering.
Before her stood her one son, Cupido,
Whose two white wings upon his shoulders grow;
And blind he was, as it is often seen;
A bow he bore, and arrows bright and keen.
Why should I not as well, now, tell you all
The portraiture that was upon the wall
Within the fane of mighty Mars the red?
In length and breadth the whole wall was painted
Like the interior of that grisly place,
The mighty temple of great Mars in Thrace,
In that same cold and frosty region where
Mars to his supreme mansion may repair.
First, on the wall was limned a vast forest
Wherein there dwelt no man nor any beast,
With knotted, gnarled, and leafless trees, so old
The sharpened stumps were dreadful to behold;
Through which there ran a rumbling, even now,
As if a storm were breaking every bough;
And down a hill, beneath a sharp descent,
The temple stood of Mars armipotent,
Wrought all of burnished steel, whereof the gate
Was grim like death to see, and long, and strait.
And therefrom raged a wind that seemed to shake
The very ground, and made the great doors quake.
The northern light in at those same doors shone,
For window in that massive wall was none
Through which a man might any light discern.
The doors were all of adamant eterne,
Rivetted on both sides, and all along,
With toughest iron; and to make it strong,
Each pillar that sustained this temple grim
Was thick as tun, of iron bright and trim.
There saw I first the dark imagining
Of felony, and all the compassing;
And cruel anger, red as burning coal;
Pickpurses, and the dread that eats the soul;
The smiling villain, hiding knife in cloak;
The farm barns burning, and the thick black smoke;
The treachery of murder done in bed;
The open battle, with the wounds that bled;
Contest, with bloody knife and sharp menace;
And loud with creaking was that dismal place.
The slayer of himself, too, saw I there,
His very heart's blood matted in his hair;
The nail that's driven in the skull by night;
The cold plague-corpse, with gaping mouth upright
In middle of the temple sat Mischance,
With gloomy, grimly woeful countenance.
And saw I Madness laughing in his rage;
Armed risings, and outcries, and fierce outrage;
The carrion in the bush, with throat wide carved;
A thousand slain, nor one by plague, nor starved.
The tyrant, with the spoils of violent theft;
The town destroyed, in ruins, nothing left.
And saw I burnt the ships that dance by phares,
The hunter strangled by the fierce wild bears;
The sow chewing the child right in the cradle;
The cook well scalded, spite of his long ladle.
Nothing was lacking of Mars' evil part:
The carter over-driven by his cart,
Under a wheel he lay low in the dust.
There were likewise in Mars' house, as needs must,
The surgeon, and the butcher, and the smith
Who forges sharp swords and great ills therewith.
And over all, depicted in a tower,
Sat Conquest, high in honour and in power,
Yet with a sharp sword hanging o'er his head
But by the tenuous twisting of a thread.
Depicted was the death of Julius,
Of Nero great, and of Antonius;
And though at that same time they were unborn,
There were their deaths depicted to adorn
The menacing of Mars, in likeness sure;
Things were so shown, in all that portraiture,
As are fore-shown among the stars above,
Who shall be slain in war or dead for love.
Suffice one instance from old plenitude,
I could not tell them all, even if I would.
Mars' image stood upon a chariot,
Armed, and so grim that mad he seemed, God wot;
And o'er his head two constellations shone
Of stars that have been named in writings known.
One being Puella, and one Rubeus.
This god of armies was companioned thus:
A wolf there was before him, at his feet,
Red-eyed, and of a dead man he did eat.
A cunning pencil there had limned this story
In reverence of Mars and of his glory.
Now to the temple of Diana chaste,
As briefly as I can, I'll pass in haste,
To lay before you its description well.
In pictures, up and down, the wall could tell
Of hunting and of modest chastity.
There saw I how Callisto fared when she
(Diana being much aggrieved with her)
Was changed from woman into a she-bear,
And after, made into the lone Pole Star;
There was it; I can't tell how such things are.
Her son, too, is a star, as men may see.
There saw I Daphne turned into a tree
(I do not mean Diana, no, but she,
Peneus' daughter, who was called Daphne)
I saw Actaeon made a hart all rude
For punishment of seeing Diana nude;
I saw, too, how his fifty hounds had caught
And him were eating, since they knew him not.
And painted farther on, I saw before
How Atalanta hunted the wild boar;
And Meleager, and many another there,
For which Diana wrought him woe and care.
There saw I many another wondrous tale
From which I will not now draw memory's veil.
This goddess on an antlered hart was set,
With little hounds about her feet, and yet
Beneath her perfect feet there was a moon,
Waxing it was, but it should wane full soon.
In robes of yellowish green her statue was,
She'd bow in hand and arrows in a case.
Her eyes were downcast, looking at the ground.
Where Pluto in his dark realm may be found.
Before her was a woman travailing,
Who was so long in giving birth, poor thing,
That pitifully Lucina did she call,
Praying, 'Oh help, for thou may'st best of all!'
Well could he paint, who had this picture wrought,
With many a florin he'd his colours bought,
But now the lists were done, and Theseus,
Who at so great cost had appointed thus
The temples and the circus, as I tell,
When all was done, he liked it wondrous well.
But hold I will from Theseus, and on
To speak of Arcita and Palamon.
The day of their return is forthcoming,
When each of them a hundred knights must bring
The combat to support, as I have told;
And into Athens, covenant to uphold,
Has each one ridden with his hundred knights,
Well armed for war, at all points, in their mights.
And certainly, 'twas thought by many a man
That never, since the day this world began,
Speaking of good knights hardy of their hands,
Wherever God created seas and lands,
Was, of so few, so noble company.
For every man that loved all chivalry,
And eager was to win surpassing fame,
Had prayed to play a part in that great game;
And all was well with him who chosen was.
For if there came tomorrow such a case,
You know right well that every lusty knight
Who loves the ladies fair and keeps his might,
Be it in England, aye or otherwhere,
Would wish of all things to be present there
To fight for some fair lady. Ben'cite!
'Twould be a pleasant goodly sight to see!
And so it was with those with Palamon.
With him there rode of good knights many a one;
Some would be armoured in a habergeon
And in a breastplate, under light jupon;
And some wore breast-and back-plates thick and large;
And some would have a Prussian shield, or targe;
Some on their very legs were armoured well,
And carried axe, and some a mace of steel.
There is no new thing, now, that is not old.
And so they all were armed, as I have told,
To his own liking and design, each one.
There might you see, riding with Palamon,
Lycurgus' self, the mighty king of Thrace;
Black was his beard and manly was his face.
The eyeballs in the sockets of his head,
They glowed between a yellow and a red.
And like a griffon glared he round about
From under bushy eyebrows thick and stout.
His limbs were large, his muscles hard and strong.
His shoulders broad, his arms both big and long,
And, as the fashion was in his country,
High in a chariot of gold stood he,
With four white bulls in traces, to progress.
Instead of coat-of-arms above harness,
With yellow claws preserved and bright as gold,
He wore a bear-skin, black and very old.
His long combed hair was hanging down his back,
As any raven's feather it was black:
A wreath of gold, arm-thick, of heavy weight,
Was on his head, and set with jewels great,
Of rubies fine and perfect diamonds.
About his car there circled huge white hounds,
Twenty or more, as large as any steer,
To hunt the lion or the antlered deer;
And so they followed him, with muzzles bound,
Wearing gold collars with smooth rings and round.
A hundred lords came riding in his rout,
All armed at point, with hearts both stern and stout
With Arcita, in tales men call to mind,
The great Emetreus, a king of Ind,
Upon a bay steed harnessed all in steel,
Covered with cloth of gold, all diapered well,
Came riding like the god of arms, great Mars.
His coat-of-arms was cloth of the Tartars,
Begemmed with pearls, all white and round and great.
Of beaten gold his saddle, burnished late;
A mantle from his shoulders hung, the thing
Close-set with rubies red, like fire blazing.
His crisp hair all in bright ringlets was run,
Yellow as gold and gleaming as the sun.
His nose was high, his eyes a bright citrine,
His lips were full, his colouring sanguine.
And a few freckles on his face were seen,
None either black or yellow, but the mean;
And like a lion he his glances cast.
Not more than five-and-twenty years he'd past.
His beard was well beginning, now, to spring;
His voice was as a trumpet thundering.
Upon his brows he wore, of laurel green,
A garland, fresh and pleasing to be seen.
Upon his wrist he bore, for his delight,
An eagle tame, as any lily white.
A hundred lords came riding with him there,
All armed, except their heads, in all their gear,
And wealthily appointed in all things.
For, trust me well, that dukes and earls and kings
Were gathered in this noble company
For love and for increase of chivalry.
About this king there ran, on every side,
Many tame lions and leopards in their pride.
And in such wise these mighty lords, in sum,
Were, of a Sunday, to the city come
About the prime, and in the town did light.
This Theseus, this duke, this noble knight,
When he'd conducted them to his city,
And quartered them, according to degree,
He feasted them, and was at so much pains
To give them ease and honour, of his gains,
That men yet hold that never human wit,
Of high or low estate, could better it.
The minstrelsy, the service at the feast,
The great gifts to the highest and the least,
The furnishings of Theseus, rich palace,
Who highest sat or lowest on the dais,
What ladies fairest were or best dandling,
Or which of them could dance the best, or sing,
Or who could speak most feelingly of love,
Or what hawks sat upon the perch above,
Or what great hounds were lying on the floor-
Of all these I will make no mention more;
But tell my tale, for that, I think, is best;
Now comes the point, and listen if you've zest.
That Sunday night, ere day began to spring,
When Palamon the earliest lark heard sing,
Although it lacked two hours of being day,
Yet the lark sang, and Palamon sang a lay.
With pious heart and with a high courage
He rose, to go upon a pilgrimage
Unto the blessed Cytherea's shrine
(I mean Queen Venus, worthy and benign).
And at her hour he then walked forth apace
Out to the lists wherein her temple was,
And down he knelt in manner to revere,
And from a full heart spoke as you shall hear.
'Fairest of fair, O lady mine, Venus,
Daughter of Jove and spouse to Vulcanus,
Thou gladdener of the Mount of Citheron,
By that great love thou borest to Adon,
Have pity on my bitter tears that smart
And hear my humble prayer within thy heart.
Alas! I have no words in which to tell
The effect of all the torments of my hell;
My heavy heart its evils can't bewray;
I'm so confused I can find naught to say.
But mercy, lady bright, that knowest well
My heart, and seest all the ills I feel,
Consider and have ruth upon my sore
As truly as I shall, for evermore,
Well as I may, thy one true servant be,
And wage a war henceforth on chastity.
If thou wilt help, thus do I make my vow,
To boast of knightly skill I care not now,
Nor do I ask tomorrow's victory,
Nor any such renown, nor vain glory
Of prize of arms, blown before lord and churl,
But I would have possession of one girl,
Of Emily, and die in thy service;
Find thou the manner how, and in what wise.
For I care not, unless it better be,
Whether I vanquish them or they do me,
So I may have my lady in my arms.
For though Mars is the god of war's alarms,
Thy power is so great in Heaven above,
That, if it be thy will, I'll have my love.
In thy fane will I worship always, so
That on thine altar, where'er I ride or go,
I will lay sacrifice and thy fires feed.
And if thou wilt not so, O lady, cede,
I pray thee, that tomorrow, with a spear,
Arcita bear me through the heart, just here.
For I'll care naught, when I have lost my life,
That Arcita may win her for his wife.
This the effect and end of all my prayer,
Give me my love, thou blissful lady fair.'
Now when he'd finished all the orison,
His sacrifice he made, this Palamon,
Right piously, with all the circumstance,
Albeit I tell not now his observance.
But at the last the form of Venus shook
And gave a sign, and thereupon he took
This as acceptance of his prayer that day.
For though the augury showed some delay,
Yet he knew well that granted was his boon;
And with glad heart he got him home right soon.
Three hours unequal after Palamon
To Venus' temple at the lists had gone,
Up rose the sun and up rose Emily,
And to Diana's temple did she hie.
Her maidens led she thither, and with them
They carefully took fire and each emblem,
And incense, robes, and the remainder all
Of things for sacrifice ceremonial.
There was not one thing lacking; I'll but add
The horns of mead, as was a way they had.
In smoking temple, full of draperies fair,
This Emily with young heart debonnaire,
Her body washed in water from a well;
But how she did the rite I dare not tell,
Except it be at large, in general;
And yet it was a thing worth hearing all;
When one's well meaning, there is no transgression;
But it is best to speak at one's discretion.
Her bright hair was unbound, but combed withal;
She wore of green oak leaves a coronal
Upon her lovely head. Then she began
Two fires upon the altar stone to fan,
And did her ceremonies as we're told
In Statius' Thebaid and books as old.
When kindled was the fire, with sober face
Unto Diana spoke she in that place.
'O thou chaste goddess of the wildwood green,
By whom all heaven and earth and sea are seen,
Queen of the realm of Pluto, dark and low,
Goddess of maidens, that my heart dost know
For all my years, and knowest what I desire,
Oh, save me from thy vengeance and thine ire
That on Actaeon fell so cruelly.
Chaste goddess, well indeed thou knowest that I
Desire to be a virgin all my life,
Nor ever wish to be man's love or wife.
I am, thou know'st, yet of thy company,
A maid, who loves the hunt and venery,
And to go rambling in the greenwood wild,
And not to be a wife and be with child.
I do not crave the company of man.
Now help me, lady, since thou may'st and can,
By the three beings who are one in thee.
For Palamon, who bears such love to me,
And for Arcita, loving me so sore,
This grace I pray thee, without one thing more,
To send down love and peace between those two,
And turn their hearts away from me: so do
That all their furious love and their desire,
And all their ceaseless torment and their fire
Be quenched or turned into another place;
And if it be thou wilt not show this grace,
Or if my destiny be moulded so
That I must needs have one of these same two,
Then send me him that most desires me.
Behold, O goddess of utter chastity,
The bitter tears that down my two cheeks fall.
Since thou art maid and keeper of us all,
My maidenhead keep thou, and still preserve,
And while I live a maid, thee will I serve.'
The fires blazed high upon the altar there,
While Emily was saying thus her prayer,
But suddenly she saw a sight most quaint,
For there, before her eyes, one fire went faint,
Then blazed again; and after that, anon,
The other fire was quenched, and so was gone.
And as it died it made a whistling sound,
As do wet branches burning on the ground,
And from the brands' ends there ran out, anon,
What looked like drops of blood, and many a one;
At which so much aghast was Emily
That she was near dazed, and began to cry,
For she knew naught of what it signified;
But only out of terror thus she cried
And wept, till it was pitiful to hear.
But thereupon Diana did appear,
With bow in hand, like any right huntress,
And said: 'My daughter, leave this heaviness.
Among the high gods it has been affirmed,
And by eternal written word confirmed,
That you shall be the wife of one of those
Who bear for you so many cares and woes;
But unto which of them may not tell.
I can no longer tarry, so farewell.
The fires that on my altar burn incense
Should tell you everything, ere you go hence,
Of what must come of love in this your case.'
And with that word the arrows of the chase
The goddess carried clattered and did ring,
And forth she went in mystic vanishing;
At which this Emily astonished was,
And said she then: 'Ah, what means this, alas!
I put myself in thy protection here,
Diana, and at thy disposal dear.'
And home she wended, then, the nearest way.
This is the purport; there's no more to say.
At the next hour of Mars, and following this,
Arcita to the temple walked, that is
Devoted to fierce Mars, to sacrifice
With all the ceremonies, pagan-wise.
With sobered heart and high devotion, on
This wise, right thus he said his orison.
'O mighty god that in the regions cold
Of Thrace art honoured, where thy lordships hold,
And hast in every realm and every land
The reins of battle in thy guiding hand,
And givest fortune as thou dost devise,
Accept of me my pious sacrifice.
If so it be that my youth may deserve,
And that my strength be worthy found to serve
Thy godhead, and be numbered one of thine,
Then pray I thee for ruth on pain that's mine.
For that same pain and even that hot fire
Wherein thou once did'st burn with deep desire,
When thou did'st use the marvelous beauty
Of fair young wanton Venus, fresh and free,
And had'st her in thine arms and at thy will
(Howbeit with thee, once, all the chance fell ill,
And Vulcan caught thee in his net, whenas
He found thee lying with his wife, alas!)-
For that same sorrow that was in thy heart,
Have pity, now, upon my pains that smart.
I'm young, and little skilled, as knowest thou,
With love more hurt and much more broken now
Than ever living creature was, I'm sure;
For she who makes me all this woe endure,
Whether I float or sink cares not at all,
And ere she'll hear with mercy when I call,
I must by prowess win her in this place;
And well I know, too, without help and grace
Of thee, my human strength shall not avail
Then help me, lord, tomorrow not to fail,
For sake of that same fire that once burned thee,
The which consuming fire so now burns me;
And grant, tomorrow, I have victory.
Mine be the toil, and thine the whole glory!
Thy sovereign temple will I honour most
Of any spot, and toil and count no cost
To pleasure thee and in thy craft have grace,
And in thy fane my banner will I place,
And all the weapons of my company;
And evermore, until the day I die,
Eternal fire shalt thou before thee find.
Moreover, to this vow myself I bind:
My beard, my hair that ripples down so long,
That never yet has felt the slightest wrong
Of razor or of shears, to thee I'll give,
And be thy loyal servant while I live.
Now, lord, have pity on my sorrows sore;
Give me the victory. I ask no more.'
With ended prayer of Arcita the young,
The rings that on the temple door were hung,
And even the doors themselves, rattled so fast
That this Arcita found himself aghast.
The fires blazed high upon the altar bright,
Until the entire temple shone with light;
And a sweet odour rose up from the ground;
And Arcita whirled then his arm around,
And yet more incense on the fire he cast,
And did still further rites; and at the last
The armour of God Mars began to ring,
And with that sound there came a murmuring,
Low and uncertain, saying: 'Victory!'
For which he gave Mars honour and glory.
And thus in joy and hope, which all might dare,
Arcita to his lodging then did fare,
Fain of the fight as fowl is of the sun.
But thereupon such quarrelling was begun,
From this same granting, in the heaven above,
'Twixt lovely Venus, goddess of all love,
And Mars, the iron god armipotent,
That Jove toiled hard to make a settlement;
Until the sallow Saturn, calm and cold,
Who had so many happenings known of old,
Found from his full experience the art
To satisfy each party and each part.
For true it is, age has great advantage;
Experience and wisdom come with age;
Men may the old out-run, but not out-wit.
Thus Saturn, though it scarcely did befit
His nature so to do, devised a plan
To quiet all the strife, and thus began:
'Now my dear daughter Venus,' quoth Saturn,
'My course, which has so wide a way to turn,
Has power more than any man may know.
Mine is the drowning in sea below;
Mine is the dungeon underneath the moat;
Mine is the hanging and strangling by the throat;
Rebellion, and the base crowd's murmuring,
The groaning and the private poisoning,
And vengeance and amercement- all are mine,
While yet I dwell within the Lion's sign.
Mine is the ruining of all high halls,
And tumbling down of towers and of walls
Upon the miner and the carpenter.
I struck down Samson, that pillar shaker;
And mine are all the maladies so cold,
The treasons dark, the machinations old;
My glance is father of all pestilence.
Now weep no more. I'll see, with diligence,
That Palamon, who is your own true knight,
Shall have his lady, as you hold is right.
Though Mars may help his man, yet none the less
Between you two there must come sometime peace,
And though you be not of one temperament,
Causing each day such violent dissent,
I am your grandsire and obey your will;
Weep then no more, your pleasure I'll fulfill.'
Now will I cease to speak of gods above,
Of Mars and Venus, goddess of all love,
And tell you now, as plainly as I can,
The great result, for which I first began.
Explicit tercia pars.
Sequitur pars quarta.
Great was the fete in Athens on that day,
And too, the merry season of the May
Gave everyone such joy and such pleasance
That all that Monday they'd but joust and dance,
Or spend the time in Venus' high service.
But for the reason that they must arise
Betimes, to see the heralded great fight,
All they retired to early rest that night.
And on the morrow, when that day did spring,
Of horse and harness, noise and clattering,
There was enough in hostelries about.
And to the palace rode full many a rout
Of lords, bestriding steeds and on palfreys.
There could you see adjusting of harness,
So curious and so rich, and wrought so well
Of goldsmiths' work, embroidery, and of steel;
The shields, the helmets bright, the gay trappings,
The gold-hewn casques, the coats-of-arms, the rings,
The lords in vestments rich, on their coursers,
Knights with their retinues and also squires;
The rivetting of spears, the helm-buckling,
The strapping of the shields, and. thong-lacing-
In their great need, not one of them was idle;
The frothing steeds, champing the golden bridle,
And the quick smiths, and armourers also,
With file and hammer spurring to and fro;
Yeoman, and peasants with short staves were out,
Crowding as thick as they could move about;
Pipes, trumpets, kettledrums, and clarions,
That in the battle sound such grim summons;
The palace full of people, up and down,
Here three, there ten, debating the renown
And questioning about these Theban knights,
Some put it thus, some said, 'It's so by rights.'
Some held with him who had the great black beard,
Some with the bald-heads, some with the thick haired;
Some said, 'He looks grim, and he'll fight like hate;
He has an axe of twenty pound in weight.'
And thus the hall was full of gossiping
Long after the bright sun began to spring.
The mighty Theseus, from sleep awakened
By songs and all the noise that never slackened,
Kept yet the chamber of this rich palace,
Till the two Theban knights, with equal grace
And honour, were ushered in with flourish fitting.
Duke Theseus was at a window sitting,
Arrayed as he were god upon a throne.
Then pressed the people thitherward full soon,
To see him and to do him reverence,
Aye, and to hear commands of sapience.
A herald on a scaffold cried out 'Ho!'
Till all the people's noise was stilled; and so,
When he observed that all were fallen still,
He then proclaimed the mighty ruler's will.
'The duke our lord, full wise and full discreet,
Holds that it were but wanton waste to meet
And fight, these gentle folk, all in the guise
Of mortal battle in this enterprise.
Wherefore, in order that no man may die,
He does his earlier purpose modify.
No man, therefore, on pain of loss of life,
Shall any arrow, pole-axe, or short knife
Send into lists in any wise, or bring;
Nor any shortened sword, for point-thrusting,
Shall a man draw, or bear it by his side.
Nor shall knight against opponent ride,
Save one full course, with any sharp-ground spear;
Unhorsed, a man may thrust with any gear.
And he that's overcome, should this occur,
Shall not be slain, but brought to barrier,
Whereof there shall be one on either side;
Let him be forced to go there and abide.
And if by chance the leader there must go,
Of either side, or slay his equal foe,
No longer, then, shall tourneying endure.
God speed you; go forth now, and lay on sure.
With long sword and with maces fight your fill.
Go now your ways; this is the lord duke's will.'
The voices of the people rent the skies,
Such was the uproar of their merry cries:
'Now God save such a lord, who is so good
He will not have destruction of men's blood!'
Up start the trumpets and make melody.
And to the lists rode forth the company,
In marshalled ranks, throughout the city large,
All hung with cloth of gold, and not with serge.
Full like a lord this noble duke did ride,
With the two Theban knights on either side;
And, following, rode the queen and Emily,
And, after, came another company
Of one and other, each in his degree.
And thus they went throughout the whole city,
And to the lists they came, all in good time.
The day was not yet fully come to prime
When throned was Theseus full rich and high,
And Queen Hippolyta and Emily,
While other ladies sat in tiers about.
Into the seats then pressed the lesser rout.
And westward, through the gate of Mars, right hearty,
Arcita and the hundred of his party
With banner red is entering anon;
And in that self-same moment, Palamon
Is under Venus, eastward in that place,
With banner white, and resolute of face.
In all the world, searching it up and down,
So equal were they all, from heel to crown,
There were no two such bands in any way.
For there was no man wise enough to say
How either had of other advantage
In high repute, or in estate, or age,
So even were they chosen, as I guess.
And in two goodly ranks, they did then dress.
And when the name was called of every one,
That cheating in their number might be none,
Then were the gates closed, and the cry rang loud:
'Now do your devoir, all you young knights proud!'
The heralds cease their spurring up and down;
Now ring the trumpets as the charge is blown;
And there's no more to say, for east and west
Two hundred spears are firmly laid in rest;
And the sharp spurs are thrust, now, into side.
Now see men who can joust and who can ride!
Now shivered are the shafts on bucklers thick;
One feels through very breast-bone the spear's prick;
Lances are flung full twenty feet in height;
Out flash the swords like silver burnished bright.
Helmets are hewed, the lacings ripped and shred;
Out bursts the blood, gushing in stern streams red.
With mighty maces bones are crushed in joust.
One through the thickest throng begins to thrust.
There strong steeds stumble now, and down goes all.
One rolls beneath their feet as rolls a ball.
One flails about with club, being overthrown,
Another, on a mailed horse, rides him down.
One through the body's hurt, and haled, for aid.
Spite of his struggles, to the barricade,
As compact was, and there he must abide;
Another's captured by the other side.
At times Duke Theseus orders them to rest,
To eat a bite and drink what each likes best.
And many times that day those Thebans two
Met in the fight and wrought each other woe;
Unhorsed each has the other on that day.
No tigress in the vale of Galgophey,
Whose little whelp is stolen in the light,
Is cruel to the hunter as Arcite
For jealousy is cruel to Palamon;
Nor in Belmarie, when the hunt is on
Is there a lion, wild for want of food,
That of his prey desires so much the blood
As Palamon the death of Arcite there.
Their jealous blows fall on their helmets fair;
Out leaps the blood and makes their two sides red.
But sometime comes the end of every deed;
And ere the sun had sunk to rest in gold,
The mighty King Emetreus did hold
This Palamon, as he fought with Arcite,
And made his sword deep in the flesh to bite;
And by the force of twenty men he's made,
Unyielded, to withdraw to barricade.
And, trying hard to rescue Palamon,
The mighty King Lyburgus is borne down;
And King Emetreus, for all his strength,
Is hurled out of the saddle a sword's length,
So hits out Palamon once more, or ere
(But all for naught) he's brought to barrier.
His hardy heart may now avail him naught;
He must abide there now, being fairly caught
By force of arms, as by provision known.
Who sorrows now but woeful Palamon,
Who may no more advance into the fight?
And when Duke Theseus had seen this sight,
Unto the warriors fighting, every one,
He cried out: 'Hold! No more! For it is done!
Now will I prove true judge, of no party.
Theban Arcita shall have Emily,
Who, by his fortune, has her fairly won.'
And now a noise of people is begun
For joy of this, so loud and shrill withal,
It seems as if the very lists will fall.
But now, what can fair Venus do above?
What says she now? What does this queen of love
But weep so fast, for thwarting of her will,
Her tears upon the lists begin to spill.
She said: 'Now am I shamed and over-flung.'
But Saturn said: 'My daughter, hold your tongue.
Mars has his will, his knight has all his boon,
And, by my head, you shall be eased, and soon.'
The trumpeters and other minstrelsy,
The heralds that did loudly yell and cry,
Were at their best for joy of Arcita.
But hear me further while I tell you- ah!-
The miracle that happened there anon.
This fierce Arcita doffs his helmet soon,
And mounted on a horse, to show his face,
He spurs from end to end of that great place,
Looking aloft to gaze on Emily;
And she cast down on him a friendly eye
(For women, generally speaking, go
Wherever Fortune may her favor show)
And she was fair to see, and held his heart.
But from the ground infernal furies start,
From Pluto sent, at instance of Saturn,
Whereat his horse, for fear, began to turn
And leap aside, all suddenly falling there;
And Arcita before he could beware
Was pitched upon the ground, upon his head,
And lay there, moving not, as he were dead,
His chest crushed in upon the saddle-bow.
And black he lay as ever coal, or crow,
So ran the surging blood into his face.
Anon they carried him from out that place,
With heavy hearts, to Theseus' palace.
There was his harness cut away, each lace,
And swiftly was he laid upon a bed,
For he was yet alive and some words said,
Crying and calling after Emily.
Duke Theseus, with all his company,
Is come again to Athens, his city,
With joyous heart and great festivity.
And though sore grieved for this unhappy fall,
He would not cast a blight upon them all.
Men said, too, that Arcita should not die,
But should be healed of all his injury.
And of another thing they were right fain,
Which was, that of them all no one was slain,
Though each was sore, and hurt, and specially one
Who'd got a lance-head thrust through his breastbone.
For other bruises, wounds and broken arms,
Some of them carried salves and some had charms;
And medicines of many herbs, and sage
They drank, to keep their limbs from hemorrhage.
In all of which this duke, as he well can,
Now comforts and now honours every man,
And makes a revelry the livelong night
For all these foreign lords, as was but right.
Nor was there held any discomfiting,
Save from the jousts and from the tourneying.
For truly, there had been no cause for shame,
Since being thrown is fortune of the game;
Nor is it, to be led to barrier,
Unyielded, and by twenty knights' power,
One man alone, surrounded by the foe,
Driven by arms, and dragged out, heel and toe,
And with his courser driven forth with staves
Of men on foot, yeomen and serving knaves-
All this imputes to one no kind of vice,
And no man may bring charge of cowardice.
For which, anon, Duke Theseus bade cry,
To still all rancour and all keen envy,
The worth, as well of one side as the other,
As equal both, and each the other's brother;
And gave them gifts according to degree,
And held a three days' feast, right royally;
And then convoyed these kings upon their road
For one full day, and to them honour showed.
And home went every man on his right way.
There was naught more but 'Farewell' and 'Good-day.'
I'll say no more of war, but turn upon
My tale of Arcita and Palamon.
Swells now Arcita's breast until the sore
Increases near his heart yet more and more.
The clotted blood, in spite of all leech-craft,
Rots in his bulk, and there is must be left,
Since no device of skillful blood-letting,
Nor drink of herbs, can help him in this thing.
The power expulsive, or virtue animal
Called from its use the virtue natural,
Could not the poison void, nor yet expel.
The tubes of both his lungs began to swell,
And every tissue in his breast, and down,
Is foul with poison and all rotten grown.
He gains in neither, in his strife to live,
By vomiting or taking laxative;
All is so broken in that part of him,
Nature Tetains no vigour there, nor vim.
And certainly, where Nature will not work,
It's farewell physic, bear the man to kirk!
The sum of all is, Arcita must die,
And so he sends a word to Emily,
And Palamon, who was his cousin dear;
And then he said to them as you shall hear.
'Naught may the woeful spirit in my heart
Declare one point of how my sorrows smart
To you, my lady, whom I love the most;
But I bequeath the service of my ghost
To you above all others, this being sure
Now that my life may here no more endure.
Alas, the woe! Alas, the pain so strong
That I for you have suffered, and so long!
Alas for death! Alas, my Emily!
Alas, the parting of our company!
Alas, my heart's own queen! Alas, my wife!
My soul's dear lady, ender of my life!
What is this world? What asks a man to have?
Now with his love, now in the cold dark grave
Alone, with never any company.
Farewell, my sweet foe! O my Emily!
Oh, take me in your gentle arms, I pray,
For love of God, and hear what I will say.
'I have here, with my cousin Palamon,
Had strife and rancour many a day that's gone,
For love of you and for my jealousy.
May Jove so surely guide my soul for me,
To speak about a lover properly,
With all the circumstances, faithfully-
That is to say, truth, honour, and knighthood,
Wisdom, humility and kinship good,
And generous soul and all the lover's art-
So now may Jove have in my soul his part
As in this world, right now, I know of none
So worthy to be loved as Palamon,
Who serves you and will do so all his life.
And if you ever should become a wife,
Forget not Palamon, the noble man.'
And with that word his speech to fail began,
For from his feet up to his breast had come
The cold of death, making his body numb.
And furthermore, from his two arms the strength
Was gone out, now, and he was lost, at length.
Only the intellect, and nothing more.
Which dwelt within his heart so sick and sore,
Began to fail now, when the heart felt death,
And his eyes darkened, and he failed of breath.
But on his lady turned he still his eye,
And his last word was, 'Mercy, Emily!'
His spirit changed its house and went away.
As I was never there, I cannot say
Where; so I stop, not being a soothsayer;
Of souls here naught shall I enregister;
Nor do I wish their notions, now, to tell
Who write of them, though they say where they dwell.
Arcita's cold; Mars guides his soul on high;
Now will I speak forthwith of Emily.
Shrieked Emily and howled now Palamon,
Till Theseus his sister took, anon,
And bore her, swooning, from the corpse away.
How shall it help, to dwell the livelong day
In telling how she wept both night and morrow?
For in like cases women have such sorrow,
When their good husband from their side must go,
And, for the greater part, they take on so,
Or else they fall into such malady
That, at the last, and certainly, they die.
Infinite were the sorrows and the tears
Of all old folk and folk of tender years
Throughout the town, at death of this Theban;
For him there wept the child and wept the man;
So great a weeping was not, 'tis certain,
When Hector was brought back, but newly slain,
To Troy. Alas, the sorrow that was there!
Tearing of cheeks and rending out of hair.
'Oh why will you be dead,' these women cry,
'Who had of gold enough, and Emily?'
No man might comfort then Duke Theseus,
Excepting his old father, AEgeus,
Who knew this world's mutations, and men's own.
Since he had seen them changing up and down,
Joy after woe, and woe from happiness:
He showed them, by example, the process.
'Just as there never died a man,' quoth he,
'But he had lived on earth in some degree,
Just so there never lived a man,' he said,
'In all this world, but must be sometime dead.
This world is but a thoroughfare of woe,
And we are pilgrims passing to and fro;
Death is the end of every worldly sore.'
And after this, he told them yet much more
To that effect, all wisely to exhort
The people that they should find some comfort.
Duke Theseus now considered and with care
What place of burial he should prepare
For good Arcita, as it best might be,
And one most worthy of his high degree.
And at the last concluded, hereupon,
That where at first Arcita and Palamon
Had fought for love, with no man else between,
There, in that very grove, so sweet and green,
Where he mused on his amorous desires
Complaining of love's hot and flaming fires,
He'd make a pyre and have the funeral
Accomplished there, and worthily in all.
And so he gave command to hack and hew
The ancient oaks, and lay them straight and true
In split lengths that would kindle well and burn.
His officers, with sure swift feet, they turn
And ride away to do his whole intent.
And after this Duke Theseus straightway sent
For a great bier, and had it all o'er-spread
With cloth of gold, the richest that he had.
Arcita clad he, too, in cloth of gold;
White gloves were on his hands where they did fold;
Upon his head a crown of laurel green,
And near his hand a sword both bright and keen.
Then, having bared the dead face on the bier,
The duke so wept, 'twas pitiful to hear.
And, so that folk might see him, one and all,
When it was day he brought them to the hall,
Which echoed of their wailing cries anon.
Then came this woeful Theban, Plamon,
With fluttery beard and matted, ash-strewn hair,
All in black clothes wet with his tears; and there,
Surpassing all in weeping, Emily,
The most affected of the company.
And so that every several rite should be
Noble and rich, and suiting his degree,
Duke Theseus commanded that they bring
Three horses, mailed in steel all glittering,
And covered with Arcita's armour bright.
Upon these stallions, which were large and white,
There rode three men, whereof one bore the shield.
And one the spear he'd known so well to wield;
The third man bore his Turkish bow, nor less
Of burnished gold the quiver than harness;
And forth they slowly rode, with mournful cheer,
Toward that grove, as you shall further hear.
The noblest Greeks did gladly volunteer
To bear upon their shoulders that great bier,
With measured pace and eyes gone red and wet,
Through all the city, by the wide main street,
Which was all spread with black, and, wondrous high,
Covered with this same cloth were houses nigh.
Upon the right hand went old AEgeus,
And on the other side Duke Theseus,
With vessels in their hands, of gold right fine,
All filled with honey, milk, and blood, and wine;
And Palamon with a great company;
And after that came woeful Emily,
With fire in hands, as use was, to ignite
The sacrifice and set the pyre alight.
Great labour and full great apparelling
Went to the service and the fire-making,
For to the skies that green pyre reached its top,
And twenty fathoms did the arms out-crop,
That is to say, the branches went so wide.
Full many a load of straw they did provide.
But how the fire, was made to climb so high;
Or what names all the different trees went by.
As oak, fir, birch, asp, alder, poplar, holm,
Willow, plane, ash, box, chestnut, linden, elm,
Laurel, thorn, maple, beech, yew, dogwood tree,
Or how they were felled, sha'n't be told by me.
Nor how the wood-gods scampered up and down,
Driven from homes that they had called their own,
Wherein they'd lived so long at ease, in peace,
The nymphs, the fauns, the hamadryades;
Nor how the beasts, for fear, and the birds, all
Fled, when that ancient wood began to fall;
Nor how aghast the ground was in the light,
Not being used to seeing the sun so bright;
Nor how the fire was started first with straw,
And then with dry wood, riven thrice by saw,
And then with green wood and with spicery,
And then with cloth of gold and jewellery,
And garlands hanging with full many a flower,
And myrrh, and incense, sweet as rose in bower;
Nor how Arcita lies among all this,
Nor what vast wealth about his body is;
Nor how this Emily, as was their way,
Lighted the sacred funeral fire, that day,
Nor how she swooned when men built up the fire,
Nor what she said, nor what was her desire;
No, nor what gems men on the fire then cast,
When the white flame went high and burned so fast;
Nor how one cast his shield, and one his spear,
And some their vestments, on that burning bier,
With cups of wine, and cups of milk, and blood,
Into that flame, which burned as wild-fire would;
Nor how the Greeks, in one huge wailing rout,
Rode slowly three times all the fire about,
Upon the left hand, with a loud shouting,
And three times more, with weapons clattering,
While thrice the women there raised up a cry;
Nor how was homeward led sad Emily;
Nor how Arcita burned to ashes cold;
Nor aught of how the lichwake they did hold
All that same night, nor how the Greeks did play
Who, naked, wrestled best, with oil anointed,
Nor who best bore himself in deeds appointed.
I will not even tell how they were gone
Home, into Athens, when the play was done;
But briefly to the point, now, will I wend
And make of this, my lengthy tale, an end.
With passing in their length of certain years,
All put by was the mourning and the tears
Of Greeks, as by one general assent;
And then it seems there was a parliament
At Athens, upon certain points in case;
Among the which points spoken of there was
The ratifying of alliances
That should hold Thebes from all defiances.
Whereat this noble Theseus, anon,
Invited there the gentle Palamon,
Not telling him what was the cause, and why;
But in his mourning clothes, and sorrowfully,
He came upon that bidding, so say I.
And then Duke Theseus sent for Emily.
When they were seated and was hushed the place,
And Theseus had mused a little space,
Ere any word came from his full wise breast,
His two eyes fixed on whoso pleased him best,
Then with a sad face sighed he deep and still,
And after that began to speak his will.
'The Primal Mover and the Cause above,
When first He forged the goodly chain of love,
Great the effect, and high was His intent;
Well knew He why, and what thereof He meant;
For with that goodly chain of love He bound
The fire, the air, the water, and dry ground
In certain bounds, the which they might not flee;
That same First Cause and Mover,' then quoth he,
'Has stablished in this base world, up and down,
A certain length of days to call their own
For all that are engendered in this place,
Beyond the which not one day may they pace,
Though yet all may that certain time abridge;
Authority there needs none, I allege,
For it is well proved by experience,
Save that I please to clarify my sense.
Then may men by this order well discern
This Mover to be stable and eterne.
Well may man know, unless he be a fool,
That every part derives but from the whole.
For Nature has not taken his being
From any part and portion of a thing,
But from a substance perfect, stable aye,
And so continuing till changed away.
And therefore, of His Wisdom's Providence,
Has He so well established ordinance
That species of all things and all progressions,
If they'd endure, it must be by successions,
Not being themselves eternal, 'tis no lie:
This may you understand and see by eye.
'Lo now, the oak, that has long nourishing
Even from the time that it begins to spring,
And has so long a life, as we may see,
Yet at the last all wasted is the tree.
'Consider, too, how even the hard stone
Under our feet we tread each day upon
Yet wastes it, as it lies beside the way.
And the broad river will be dry some day.
And great towns wane; we see them vanishing.
Thus may we see the end to everything.
'Of man and woman just the same is true:
Needs must, in either season of the two,
That is to say, in youth or else in age,
All men perish, the king as well as page;
Some in their bed, and some in the deep sea,
And some in the wide field- as it may be;
There's naught will help; all go the same way. Aye,
Then may I say that everything must die.
Who causes this but Jupiter the King?
He is the Prince and Cause of everything,
Converting all back to that primal well
From which it was derived, 'tis sooth to tell.
And against this, for every thing alive,
Of any state, avalls it not to strive.
'Then is it wisdom, as it seems to me,
To make a virtue of necessity,
And calmly take what we may not eschew,
And specially that which to all is due.
Whoso would balk at aught, he does folly,
And thus rebels against His potency.
And certainly a man has most honour
In dying in his excellence and flower,
When he is certain of his high good name;
For then he gives to friend, and self, no shame.
And gladder ought a friend be of his death
When, in much honour, he yields up his breath,
Than when his name's grown feeble with old age;
For all forgotten, then, is his courage.
Hence it is best for all of noble name
To die when at the summit of their fame.
The contrary of this is wilfulness.
Why do we grumble? Why have heaviness
That good Arcita, chivalry's fair flower,
Is gone, with honour, in his best-lived hour.
Out of the filthy prison of this life?
Why grumble here his cousin and his wife
About his welfare, who loved them so well?
Can he thank them? Nay, God knows, not! Nor tell
How they his soul and their own selves offend,
Though yet they may not their desires amend.
'What may I prove by this long argument
Save that we all turn to merriment,
After our grief, and give Jove thanks for grace.
And so, before we go from out this place,
I counsel that we make, of sorrows two
One perfect joy, lasting for aye, for you;
And look you now, where most woe is herein,
There will we first amend it and begin.
'Sister,' quoth he, 'you have my full consent,
With the advice of this my Parliament,
That gentle Palamon, your own true knight,
Who serves you well with will and heart and might,
And so has ever, since you knew him first-
That you shall, of your grace, allay his thirst
By taking him for husband and for lord:
Lend me your hand, for this is our accord.
Let now your woman's pity make him glad.
For he is a king's brother's son, by gad;
And though he were a poor knight bachelor,
Since he has served you for so many a year,
And borne for you so great adversity,
This ought to weigh with you, it seems to me,
For mercy ought to dominate mere right.'
Then said he thus to Palamon the knight:
'I think there needs but little sermoning
To make you give consent, now, to this thing.
Come near, and take your lady by the hand.'
Between them, then, was tied that nuptial band,
Which is called matrimony or marriage,
By all the council and the baronage.
And thus, in all bliss and with melody,
Has Palamon now wedded Emily.
And God Who all this universe has wrought,
Send him His love, who has it dearly bought.
For now has Palamon, in all things, wealth,
Living in bliss, in riches, and in health;
And Emily loved him so tenderly,
And he served her so well and faithfully,
That never word once marred their happiness,
No jealousy, nor other such distress.
Thus ends now Palamon and Emily;
And may God save all this fair company! Amen.
HERE ENDS THE KNIGHT'S TALE ");
	$number = e_rand(1,3);
	if ($number == 3) {
		$session[user][experience]*=1.05;
		output("`!The book has filled you with wisdom beyond your years!  You gain some Experience!`n");
	}
	    addnav("Return Book to Shelf","runmodule.php?module=library");
	    page_footer();
}
?>