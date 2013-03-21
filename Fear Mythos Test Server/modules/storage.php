<?php
require_once("lib/http.php");

function storage_getmoduleinfo(){
	$info = array(
	 "name"		=>"Estate Storage",
	 "author"	=>"Thanatos",
	 "version"	=>"1.0",
	 "category"	=>"House System",
	 "download"	=>"www.thanatos.5gigs.com",
	 "settings"	=>array(
   		"goldcost"  =>"Storage Room Gold Price,int|150000",
			"gemcost" 	=>"Storage Room Gem Price,int|750",
		
      "turns"     =>"Extra Turns for owning Store Room,int|0",
      "fox"	      =>"Fox Pelts Capacity,int|0",
      "fish"	    =>"Fish Eyes Capacity,int|0",
      "worm"	    =>"Worms Capacity,int|0",
      "bat"	      =>"Bat Wings Capacity,int|0",
      "newt"      =>"Eye of Newt Capicity,int|0",
      "dog"	      =>"Dog Tail Capacity,int|0",
      "frog"	    =>"Frog Legs Capacity,int|0",
      "wort"	    =>"Wort Capacity,int|0",
	 		"heads"	    =>"Shrunken Heads Capacity,int|0",
      "wool"	    =>"Wool Capacity,int|0",
      "cloth"	    =>"Cloth Capacity,int|0",
      "leather"	  =>"Leather Capacity,int|0",
      
       ),
	 "prefs"	=>array(
	 		"Storage Room"	=>"Does user have Storage Room,bool|0",
			"c_weapon"		  =>"Weapon name,text|Hands",
			"c_weapondmg"	  =>"Weapon Damage,int|1",
			"c_weaponcost"	=>"Weapon Cost,int|0",
			"c_armor"		    =>"Armor Name,text|t-shirt",
			"c_armordef"	  =>"Armor Defense,int|1",
			"c_armorcost"	  =>"Armor Cost,int|0",
			
      "c_fox"	      =>"Fox Pelts,int|0",
      "c_fish"	    =>"Fish Eyes,int|0",
      "c_worm"	    =>"Worm,int|0",
      "c_bat"	      =>"Bat Wings,int|0",
      "c_newt"      =>"Eye of Newt,int|0",
      "c_dog"	      =>"Dog Tail,int|0",
      "c_frog"	    =>"Frog Legs,int|0",
      "c_wort"	    =>"Wort,int|0",
	 		"c_heads"	    =>"Shrunken Heads,int|0",
      "c_wool"	    =>"Wool,int|0",
      "c_cloth"	    =>"Cloth,int|0",
      "c_leather"	  =>"Leather,int|0",
	 	
      ),
	);
	return $info;
}

function storage_install(){
	module_addhook("newday");
	module_addhook("footer-runmodule");
	return true;
}
function storage_uninstall(){
	output("`n`Q`b`cUninstalling Storage Module.`c`b`n");
	return true;
}



function storage_dohook($hookname,$args){
	global $session;
	$id=httpget("id");
	$goldcost=get_module_setting("goldcost");
  $gemcost =get_module_setting("gemcost");
	switch($hookname){
	case "footer-runmodule":
			if (httpget('module')=='house') {
				if (httpget('lo')=='house') {
				    if ($id == $session['user']['acctid']){
              if (get_module_pref("Storage Room")!=1) {
            			addnav(array("`&Buy Storage Room (`1%s gold`&) (`1%s gems`&)",$goldcost,$gemcost),
                  "runmodule.php?module=storage&op=buystoreroom");
              }else{
            			addnav("Storage Room","runmodule.php?module=storage&op=storeroom");	
              }
            }
        }
      }
  break;
  case "newday":
		  if (get_module_pref("Storage Room")==1){
		    if (get_module_pref("turns")<0){
				  $session[user][turns]=$session[user][turns]+get_module_setting("turns");
				}
		  }
	break;
  }		
  return $args;
}

function storage_run(){
	global $session;
	$op = httpget('op');
	$goldcost=get_module_setting("goldcost");
  $gemcost =get_module_setting("gemcost");
  switch ($op) {
	case "storeroom":
		  page_header("Storage Room");
		  addnav("-Equipment-");
		  addnav("Store Weapon","runmodule.php?module=storage&op=dweapon");
      addnav("Store Armor" ,"runmodule.php?module=storage&op=darmor");
      if (get_module_setting("fox")>0){
        addnav("-Fox Pelts-");
        addnav("Deposit Fox Pelts","runmodule.php?module=storage&op=depfox");
        addnav("Withdraw Fox Pelts","runmodule.php?module=storage&op=wfox");
        }
      if (get_module_setting("fish")>0){
        addnav("-Fish Eyes-");
        addnav("Deposit Fish Eyes","runmodule.php?module=storage&op=dfish");
        addnav("Withdraw Fish Eyes","runmodule.php?module=storage&op=wfish");
        }
      if (get_module_setting("worm")>0){
        addnav("-Worms-");
        addnav("Deposit Worms","runmodule.php?module=storage&op=dworm");
        addnav("Withdraw Worms","runmodule.php?module=storage&op=wworm");
        }
      if (get_module_setting("bat")>0){
        addnav("-Bat Wings-");
        addnav("Deposit Bat Wings","runmodule.php?module=storage&op=dbat");
        addnav("Withdraw Bat Wings","runmodule.php?module=storage&op=wbat");
        }
      if (get_module_setting("eye")>0){
        addnav("-Eye of Newt-");
        addnav("Deposit Eye of Newt","runmodule.php?module=storage&op=deye");
        addnav("Withdraw Eye","runmodule.php?module=storage&op=weye");
        }
      if (get_module_setting("dog")>0){
        addnav("-Puppy Dog Tails-");
        addnav("Deposit Puppy Dog Tails","runmodule.php?module=storage&op=ddog");
        addnav("Withdraw Puppy Dog Tails","runmodule.php?module=storage&op=wdog");
        }
      if (get_module_setting("frog")>0){
        addnav("-Frogs-");
        addnav("Deposit Frog Legs","runmodule.php?module=storage&op=dfrog");
        addnav("Withdraw Frog Legs","runmodule.php?module=storage&op=wfrog");
        }
      if (get_module_setting("wort")>0){
        addnav("-Wort-");
        addnav("Deposit Wort","runmodule.php?module=storage&op=dwort");
        addnav("Withdraw Wort","runmodule.php?module=storage&op=wwort");
        }
      if (get_module_setting("head")>0){
        addnav("-Shrunken Heads-");
        addnav("Deposit Shrunken Heads","runmodule.php?module=storage&op=dheads");
        addnav("WithdrawShrunken Heads","runmodule.php?module=storage&op=wheads");
        }
      if (get_module_setting("wool")>0){
        addnav("-Wool-");
        addnav("Deposit Wool","runmodule.php?module=storage&op=dwool");
        addnav("Withdraw Wool","runmodule.php?module=storage&op=wwool");
        }
      if (get_module_setting("cloth")>0){
        addnav("-Cloth-");
        addnav("Deposit Cloth","runmodule.php?module=storage&op=dcloth");
        addnav("Withdraw Cloth","runmodule.php?module=storage&op=wcloth");
        }
      if (get_module_setting("leather")>0){
        addnav("-Leather-");
        addnav("Deposit Leather","runmodule.php?module=storage&op=dleather");
        addnav("Withdraw Leather","runmodule.php?module=storage&op=wleather");
        }
      
      addnav("-Return-");
			addnav("Return to Village","village.php");
	break;
	case "dweapon":
	    page_header("Weapon Storage");
	    $c_weapon 	    = get_module_pref("c_weapon");
	    $c_weapondmg  	= get_module_pref("c_weapondmg");
	    $c_weaponcost 	= get_module_pref("c_weaponcost");
	    $weapon	  	= $session[user][weapon];
	    $weapondmg	= $session[user][weapondmg];
	    $weaponcost	= $session[user][weaponvalue];
	    $session[user][attack]		= $session[user][attack]-$weapondmg;
	    $session[user][weapon]		= $c_weapon;
	    $session[user][weapondmg]	= $c_weapondmg;
	    $session[user][weaponvalue]= $c_weaponcost;
	    $session[user][attack]		= $session[user][attack]+$c_weapondmg;	
	    set_module_pref("c_weapon",$weapon);
	    set_module_pref("c_weapondmg",$weapondmg);
	    set_module_pref("c_weaponcost",$weaponcost);
	    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
	    output(array("%s Deposited",$weapon));
	    
	break;
	case "darmor":
	    page_header("Armor Storage");
	    $c_armor 	     = get_module_pref("c_armor");
	    $c_armordef  	 = get_module_pref("c_armordef");
	    $c_armorcost 	 = get_module_pref("c_armorcost");
	    $armor	  	 = $session[user][armor];
	    $armordef	   = $session[user][armordef];
	    $armorcost	 = $session[user][armorvalue];
	    $session[user][defense]		= $session[user][defense]-$armordef;
	    $session[user][armor]		= $c_armor;
	    $session[user][armordef]	= $c_armordef;
	    $session[user][armorvalue]	= $c_armorcost;	
	    $session[user][defense]		= $session[user][defense]+$c_armordef;
    	set_module_pref("c_armor",$armor);
	    set_module_pref("c_armordef",$armordef);
	    set_module_pref("c_armorcost",$armorcost);
	    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
	    output(array("%s Deposited",$armor));
  break;
  
  case "buystoreroom":
      page_header("Buy Storage Room");
  		if ($goldcost>$session[user][gold] or $gemcost>$session[user][gems]){
    			output("You have Insuffecient funds to purchase the Storage Room.");
    			addnav("Return","runmodule.php?module=house&op=run");
  		}else{
   		 	  output("You purchase a Storage Room.");
    			$session[user][gold]=$session[user][gold]-$goldcost;
    			$session[user][gems]=$session[user][gems]-$gemcost;
    			set_module_pref("Storage Room",1);
    			addnav("Return","runmodule.php?module=house&op=run");
          }
  break;

  case "depfox":
    page_header("Deposit Fox Pelts");
    $held    =get_module_pref("fox","trading");
    $stored  =get_module_pref("c_fox");
    $capicity=get_module_setting("fox");
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("c_fox",($stored+$held));
      set_module_pref("fox",0,"trading");
    }else{
      set_module_pref("c_fox",$capicity);
      set_module_pref("fox",$rem,"trading");
    }
    output(array("%s Fox Pelts are in your Pack",get_module_pref("fox","trading")));
    output(array("%s Fox Pelts are in your Storage",get_module_pref("c_fox")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  case "wfox":
    page_header("Withdraw Fox Pelts");
    $held    =get_module_pref("fox","trading");
    $stored  =get_module_pref("c_fox");
    $capicity=50;
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("fox",($stored+$held),"trading");
      set_module_pref("c_fox",0);
    }else{
      set_module_pref("fox",$capicity,"trading");
      set_module_pref("c_fox",$rem);
    }
    output(array("%s Fox Pelts are in your Pack",get_module_pref("fox","trading")));
    output(array("%s Fox Pelts are in your Storage",get_module_pref("c_fox")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;  
  
    
  case "dfish":
    page_header("Deposit Fish");
    $held    =get_module_pref("fish","trading");
    $stored  =get_module_pref("c_fish");
    $capicity=get_module_setting("fish");
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("c_fish",($stored+$held));
      set_module_pref("fish",0,"trading");
    }else{
      set_module_pref("c_fish",$capicity);
      set_module_pref("fish",$rem,"trading");
    }
    output(array("%s Fish are in your Pack",get_module_pref("fish","trading")));
    output(array("%s Fish are in your Storage",get_module_pref("c_fish")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  case "wfish":
    page_header("Withdraw Fish");
    $held    =get_module_pref("fish","trading");
    $stored  =get_module_pref("c_fish");
    $capicity=50;
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("fish",($stored+$held),"trading");
      set_module_pref("c_fish",0);
    }else{
      set_module_pref("fish",$capicity,"trading");
      set_module_pref("c_fish",$rem);
    }
    output(array("`2%s `&Fish are in your Pack",get_module_pref("fish","trading")));
    output(array("`2%s `&Fish are in your Storage",get_module_pref("c_fish")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  
    case "dworm":
    page_header("Deposit Worm Pelts");
    $held    =get_module_pref("worm","trading");
    $stored  =get_module_pref("c_worm");
    $capicity=get_module_setting("worm");
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("c_worm",($stored+$held));
      set_module_pref("worm",0,"trading");
    }else{
      set_module_pref("c_worm",$capicity);
      set_module_pref("worm",$rem,"trading");
    }
    output(array("%s Worm Pelts are in your Pack",get_module_pref("worm","trading")));
    output(array("%s Worm Pelts are in your Storage",get_module_pref("c_worm")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  case "wworm":
    page_header("Withdraw Worms");
    $held    =get_module_pref("worm","trading");
    $stored  =get_module_pref("c_worm");
    $capicity=50;
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("worm",($stored+$held),"trading");
      set_module_pref("c_worm",0);
    }else{
      set_module_pref("worm",$capicity,"trading");
      set_module_pref("c_worm",$rem);
    }
    output(array("%s Worm are in your Pack",get_module_pref("worm","trading")));
    output(array("%s Worm are in your Storage",get_module_pref("c_worm")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  
  
    case "dbat":
    page_header("Deposit Bat Wings");
    $held    =get_module_pref("bat","trading");
    $stored  =get_module_pref("c_bat");
    $capicity=get_module_setting("bat");
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("c_bat",($stored+$held));
      set_module_pref("bat",0,"trading");
    }else{
      set_module_pref("c_bat",$capicity);
      set_module_pref("bat",$rem,"trading");
    }
    output(array("%s Bat Wings are in your Pack",get_module_pref("bat","trading")));
    output(array("%s Bat Wings are in your Storage",get_module_pref("c_bat")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  case "wbat":
    page_header("Withdraw Bat Wings");
    $held    =get_module_pref("bat","trading");
    $stored  =get_module_pref("c_bat");
    $capicity=50;
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("bat",($stored+$held),"trading");
      set_module_pref("c_bat",0);
    }else{
      set_module_pref("bat",$capicity,"trading");
      set_module_pref("c_bat",$rem);
    }
    output(array("%s Bat Wings are in your Pack",get_module_pref("bat","trading")));
    output(array("%s Bat WIngs are in your Storage",get_module_pref("c_bat")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  
  
  case "dnewt":
    page_header("Deposit Eye of Newt");
    $held    =get_module_pref("newt","trading");
    $stored  =get_module_pref("c_newt");
    $capicity=get_module_setting("newt");
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("c_newt",($stored+$held));
      set_module_pref("newt",0,"trading");
    }else{
      set_module_pref("c_newt",$capicity);
      set_module_pref("newt",$rem,"trading");
    }
    output(array("%s Eye of Newt are in your Pack",get_module_pref("fox","trading")));
    output(array("%s Eye of Newt are in your Storage",get_module_pref("c_fox")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  case "wnewt":
    page_header("Withdraw Eye of Newt");
    $held    =get_module_pref("newt","trading");
    $stored  =get_module_pref("c_newt");
    $capicity=50;
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("newt",($stored+$held),"trading");
      set_module_pref("c_newt",0);
    }else{
      set_module_pref("newt",$capicity,"trading");
      set_module_pref("c_newt",$rem);
    }
    output(array("%s Eye of Newt are in your Pack",get_module_pref("newt","trading")));
    output(array("%s Eye of Newt are in your Storage",get_module_pref("c_newt")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  
  
  case "ddog":
    page_header("Deposit Dog Tails");
    $held    =get_module_pref("dog","trading");
    $stored  =get_module_pref("c_dog");
    $capicity=get_module_setting("dog");
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("c_dog",($stored+$held));
      set_module_pref("dog",0,"trading");
    }else{
      set_module_pref("c_dog",$capicity);
      set_module_pref("dog",$rem,"trading");
    }
    output(array("%s Dog Tails are in your Pack",get_module_pref("dog","trading")));
    output(array("%s Dog Tails are in your Storage",get_module_pref("c_dog")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  case "wdog":
    page_header("Withdraw Dog Tails");
    $held    =get_module_pref("dog","trading");
    $stored  =get_module_pref("c_dog");
    $capicity=50;
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("dog",($stored+$held),"trading");
      set_module_pref("c_dog",0);
    }else{
      set_module_pref("dog",$capicity,"trading");
      set_module_pref("c_dog",$rem);
    }
    output(array("%s Dog Tails are in your Pack",get_module_pref("dog","trading")));
    output(array("%s Dog Tails are in your Storage",get_module_pref("c_dog")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  
  
  case "dfrog":
    page_header("Deposit Frog Legs");
    $held    =get_module_pref("frog","trading");
    $stored  =get_module_pref("c_frog");
    $capicity=get_module_setting("frog");
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("c_frog",($stored+$held));
      set_module_pref("frog",0,"trading");
    }else{
      set_module_pref("c_frog",$capicity);
      set_module_pref("frog",$rem,"trading");
    }
    output(array("%s Frog Legs are in your Pack",get_module_pref("frog","trading")));
    output(array("%s Frog Legs are in your Storage",get_module_pref("c_frog")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  case "wfrog":
    page_header("Withdraw Frog Legs");
    $held    =get_module_pref("frog","trading");
    $stored  =get_module_pref("c_frog");
    $capicity=50;
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("frog",($stored+$held),"trading");
      set_module_pref("c_frog",0);
    }else{
      set_module_pref("frog",$capicity,"trading");
      set_module_pref("c_frog",$rem);
    }
    output(array("%s Frog Legs are in your Pack",get_module_pref("frog","trading")));
    output(array("%s Frog Legs are in your Storage",get_module_pref("c_frog")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  
  case "dwort":
    page_header("Deposit Wort");
    $held    =get_module_pref("wort","trading");
    $stored  =get_module_pref("c_wort");
    $capicity=get_module_setting("wort");
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("c_wort",($stored+$held));
      set_module_pref("wort",0,"trading");
    }else{
      set_module_pref("c_wort",$capicity);
      set_module_pref("wort",$rem,"trading");
    }
    output(array("%s Wort are in your Pack",get_module_pref("wort","trading")));
    output(array("%s Wort are in your Storage",get_module_pref("c_wort")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  case "wwort":
    page_header("Withdraw Wort");
    $held    =get_module_pref("wort","trading");
    $stored  =get_module_pref("c_wort");
    $capicity=50;
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("wort",($stored+$held),"trading");
      set_module_pref("c_wort",0);
    }else{
      set_module_pref("wort",$capicity,"trading");
      set_module_pref("c_wort",$rem);
    }
    output(array("%s Wort are in your Pack",get_module_pref("wort","trading")));
    output(array("%s Wort are in your Storage",get_module_pref("c_wort")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  
  
  case "dheads":
    page_header("Deposit Shrunken Heads");
    $held    =get_module_pref("heads","trading");
    $stored  =get_module_pref("c_heads");
    $capicity=get_module_setting("heads");
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("c_heads",($stored+$held));
      set_module_pref("heads",0,"trading");
    }else{
      set_module_pref("c_heads",$capicity);
      set_module_pref("heads",$rem,"trading");
    }
    output(array("%s Shrunken Heads are in your Pack",get_module_pref("heads","trading")));
    output(array("%s Shrunken Heads are in your Storage",get_module_pref("c_heads")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  case "wheads":
    page_header("Withdraw Shrunken Heads");
    $held    =get_module_pref("heads","trading");
    $stored  =get_module_pref("c_heads");
    $capicity=50;
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("heads",($stored+$held),"trading");
      set_module_pref("c_heads",0);
    }else{
      set_module_pref("heads",$capicity,"trading");
      set_module_pref("c_heads",$rem);
    }
    output(array("%s Shrunken Heads are in your Pack",get_module_pref("heads","trading")));
    output(array("%s Shrunken Heads are in your Storage",get_module_pref("c_heads")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  
  case "dwool":
    page_header("Deposit Wool");
    $held    =get_module_pref("wool","trading");
    $stored  =get_module_pref("c_wool");
    $capicity=get_module_setting("wool");
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("c_wool",($stored+$held));
      set_module_pref("wool",0,"trading");
    }else{
      set_module_pref("c_wool",$capicity);
      set_module_pref("wool",$rem,"trading");
    }
    output(array("%s Wool are in your Pack",get_module_pref("wool","trading")));
    output(array("%s Wool are in your Storage",get_module_pref("c_wool")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  case "wwool":
    page_header("Withdraw Wool");
    $held    =get_module_pref("wool","trading");
    $stored  =get_module_pref("c_wool");
    $capicity=50;
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("wool",($stored+$held),"trading");
      set_module_pref("c_wool",0);
    }else{
      set_module_pref("wool",$capicity,"trading");
      set_module_pref("c_wool",$rem);
    }
    output(array("%s Wool are in your Pack",get_module_pref("wool","trading")));
    output(array("%s Wool are in your Storage",get_module_pref("c_wool")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  
  case "dcloth":
    page_header("Deposit Cloth");
    $held    =get_module_pref("cloth","trading");
    $stored  =get_module_pref("c_cloth");
    $capicity=get_module_setting("cloth");
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("c_cloth",($stored+$held));
      set_module_pref("cloth",0,"trading");
    }else{
      set_module_pref("c_cloth",$capicity);
      set_module_pref("cloth",$rem,"trading");
    }
    output(array("%s Cloth are in your Pack",get_module_pref("cloth","trading")));
    output(array("%s Cloth are in your Storage",get_module_pref("c_cloth")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  case "wcloth":
    page_header("Withdraw Cloth");
    $held    =get_module_pref("cloth","trading");
    $stored  =get_module_pref("c_cloth");
    $capicity=50;
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("cloth",($stored+$held),"trading");
      set_module_pref("c_cloth",0);
    }else{
      set_module_pref("cloth",$capicity,"trading");
      set_module_pref("c_cloth",$rem);
    }
    output(array("%s Cloth are in your Pack",get_module_pref("cloth","trading")));
    output(array("%s Cloth are in your Storage",get_module_pref("c_cloth")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  
  case "dleather":
    page_header("Deposit Leather");
    $held    =get_module_pref("leather","trading");
    $stored  =get_module_pref("c_leather");
    $capicity=get_module_setting("leather");
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("c_leather",($stored+$held));
      set_module_pref("leather",0,"trading");
    }else{
      set_module_pref("c_leather",$capicity);
      set_module_pref("leather",$rem,"trading");
    }
    output(array("%s Leather are in your Pack",get_module_pref("leather","trading")));
    output(array("%s Leather are in your Storage",get_module_pref("c_leather")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;
  case "wleather":
    page_header("Withdraw Leather");
    $held    =get_module_pref("leather","trading");
    $stored  =get_module_pref("c_leather");
    $capicity=50;
    $rem=$stored+$held-$capicity;
    if ($capicity>($held+$stored)){
      set_module_pref("leather",($stored+$held),"trading");
      set_module_pref("c_cloth",0);
    }else{
      set_module_pref("leather",$capicity,"trading");
      set_module_pref("c_leather",$rem);
    }
    output(array("%s Leather are in your Pack",get_module_pref("leather","trading")));
    output(array("%s Leather are in your Storage",get_module_pref("c_leather")));
    addnav("Return to Storage Room","runmodule.php?module=storage&op=storeroom");
  break;  
  }	
	page_footer();
}
?>
