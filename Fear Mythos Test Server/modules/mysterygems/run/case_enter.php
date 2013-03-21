<HEAD>
<script type="text/javascript">
/*
 * Notes on hue
 *
 * This script uses hue rotation in the following manner:
 * hue=0   is red (#FF0000)
 * hue=60  is yellow (#FFFF00)
 * hue=120 is green (#00FF00)
 * hue=180 is cyan (#00FFFF)
 * hue=240 is blue (#0000FF)
 * hue=300 is magenta (#FF00FF)
 * hue=360 is hue=0 (#FF0000)
 *
 * Notes on the script
 *
 * This script should function in any browser that supports document.getElementById
 * It has been tested in Netscape7, Mozilla Firefox 1.0, and Internet Explorer 6
 *
 * Accessibility
 *
 * The script does not write the string out, but rather takes it from an existing
 * HTML element. Therefore, users with javascript disabled will not be adverely affected.
 * They just won't get the pretty colors.
 */

/*
 * splits par.firstChild.data into 1 span for each letter
 * ARGUMENTS
 *   span - HTML element containing a text node as the only element
 */
function toSpans(span) {
  var str=span.firstChild.data;
  var a=str.length;
  span.removeChild(span.firstChild);
  for(var i=0; i<a; i++) {
    var theSpan=document.createElement("SPAN");
    theSpan.appendChild(document.createTextNode(str.charAt(i)));
    span.appendChild(theSpan);
  }
}

/*
 * creates a rainbowspan object
 * whose letters will be colored [deg] degrees of hue
 * ARGUMENTS
 *   span - HTML element to apply the effect to (text only, no HTML)
 *   hue - what degree of hue to start at (0-359)
 *   deg - how many hue degrees should be traversed from beginning to end of the string (360 => once around, 720 => twice, etc)
 *   brt - brightness (0-255, 0 => black, 255 => full color)
 *   spd - how many ms between moveRainbow calls (less => faster)
 *   hspd - how many hue degrees to move every time moveRainbow is called (0-359, closer to 180 => faster)
 */
function RainbowSpan(span, hue, deg, brt, spd, hspd) {
    this.deg=(deg==null?360:Math.abs(deg));
    this.hue=(hue==null?0:Math.abs(hue)%360);
    this.hspd=(hspd==null?3:Math.abs(hspd)%360);
    this.length=span.firstChild.data.length;
    this.span=span;
    this.speed=(spd==null?50:Math.abs(spd));
    this.hInc=this.deg/this.length;
    this.brt=(brt==null?255:Math.abs(brt)%256);
    this.timer=null;
    toSpans(span);
    this.moveRainbow();
}

/*
 * sets the colors of the children of [this] as a hue-rotating rainbow starting at this.hue;
 * requires something to manage ch externally
 * I had to make the RainbowSpan class because M$IE wouldn't let me attach this prototype to [Object]
 */
RainbowSpan.prototype.moveRainbow = function() {
  if(this.hue>359) this.hue-=360;
  var color;
  var b=this.brt;
  var a=this.length;
  var h=this.hue;

  for(var i=0; i<a; i++) {

    if(h>359) h-=360;

    if(h<60) { color=Math.floor(((h)/60)*b); red=b;grn=color;blu=0; }
    else if(h<120) { color=Math.floor(((h-60)/60)*b); red=b-color;grn=b;blu=0; }
    else if(h<180) { color=Math.floor(((h-120)/60)*b); red=0;grn=b;blu=color; }
    else if(h<240) { color=Math.floor(((h-180)/60)*b); red=0;grn=b-color;blu=b; }
    else if(h<300) { color=Math.floor(((h-240)/60)*b); red=color;grn=0;blu=b; }
    else { color=Math.floor(((h-300)/60)*b); red=b;grn=0;blu=b-color; }

    h+=this.hInc;

    this.span.childNodes[i].style.color="rgb("+red+", "+grn+", "+blu+")";
  }
  this.hue+=this.hspd;
}
// End -->
</script>
</HEAD>

<?php
if (get_module_setting('levelmultiply')) require_once("modules/mysterygems/run/level_multiply.php");
else require_once("modules/mysterygems/run/level_no_multiply.php");
$ul = $session['user']['level'];
$umhp	= $session['user']['maxhitpoints'];
$uexp	= $session['user']['experience'];
$ugo = $session['user']['gold'];
$uge = $session['user']['gems'];
$udp = $session['user']['deathpower'];
$uhp = $session['user']['hitpoints'];
$utu = $session['user']['turns'];
$greet = 1;
if (get_module_pref('used') < get_module_setting('times')){
	addnav("In the Glass Chest");
	if ($ugo >= $tc && !get_module_setting('lostmount') && $session['user']['hashorse']
	 && !$session['bufflist']['mount']['suspended'])
		addnav(array("`3Turquoise`0.........(`^%s gold`0)", $tc), "runmodule.php?module=mysterygems&op=turquoise");
	if ($ugo >= $mac && $uhp >= $umhp * .80)
		addnav(array("`2Malachite`0..........(`^%s gold`0)", $mac), "runmodule.php?module=mysterygems&op=malachite");
	if ($ugo >= $moc && $uge > 14)
		addnav(array("`7Moonstone`0.......(`^%s gold`0)", $moc), "runmodule.php?module=mysterygems&op=moonstone");
	if ($ugo >= $hc && $utu > 14)
		addnav(array("`)Hematite`0..........(`^%s gold`0)", $hc), "runmodule.php?module=mysterygems&op=hematite");
	if ($ugo >= $sc)
		addnav(array("`1Star Sapphire`0...(`^%s gold`0)", $sc), "runmodule.php?module=mysterygems&op=starsapphire");
	if ($ugo >= $dc && $udp > 149)
		addnav(array("`&Diamond`0.........(`^%s gold`0)", $dc), "runmodule.php?module=mysterygems&op=diamond");
}
else $greet = 0;
if ($greet){
	output("The strange shop is best described as a high fashioned soothsayer's shop. ");
	output("But with a myriad of gems, jewels and baubles displayed within gold adorned cases and shelves.");
	output("`n`nSpringing out of nowhere with a flamboyant turn of toe and wisp of glittery fabric comes Gem. ");
	output("He's as spry as ever with that silly looking grin on his face. ");
	output("What, is he wearing makeup today?`n`n");
	if (get_module_setting('fancy')){
		rawoutput(
			"<p id='r2'>Ah, well heeeeeeelllllllo there. Take a gander at these! </p></div>
			<script type='text/javascript'>var r2=document.getElementById('r2');
			var myRainbowSpan2=new RainbowSpan(r2, 0, 360, 255, 50, 348); myRainbowSpan2.timer=window.setInterval
			('myRainbowSpan2.moveRainbow()', myRainbowSpan2.speed);</script>"
		);
	}
	else output("Ah, well hello there. Take a gander at these!`n`n");
	output("He exclaims in his undying giddiness as he beckons you closer, ");
	output("with a dramatic splay of arms over and across a nearby glass chest.");
}
else output("Just as you approach, you're gently pushed away from the shop.");
addnav("Prance Off");
villagenav();
?>