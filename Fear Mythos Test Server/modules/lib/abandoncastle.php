<?php
	global $session;
	global $badguy;
	$op = httpget('op');
	$locale = httpget('loc');
	$skill = httpget('skill');
	page_header("Abandoned Castle");
	$umaze = get_module_pref('maze');
	$umazeturn = get_module_pref('mazeturn');
	$upqtemp = get_module_pref('pqtemp');
	if ($op == "" && $locale == "") {
		for ($j=1;$j<45;$j++){
			output("`nMaze `^%s`0:`n",$j);
			if ($j==1){
				$maze = array(j,d,d,d,b,c,k,o,d,d,k,f,d,b,d,a,n,i,d,b,d,e,i,d,c,k,m,j,d,p,g,o,e,o,b,k,i,k,g,j,n,f,k,g,o,e,g,j,e,i,a,b,a,h,g,j,h,i,h,i,n,g,i,e,j,e,i,d,k,j,d,d,h,l,i,h,g,j,d,h,g,o,b,d,c,d,d,h,i,b,d,c,k,g,j,d,b,d,k,j,c,d,d,e,i,a,d,a,d,e,i,d,d,n,i,k,i,k,i,d,e,o,d,d,b,b,a,k,i,d,d,h,o,d,d,h,m,z,i,d,d,d,n);
			}elseif ($j==2){
				$maze = array(j,d,b,d,n,g,j,d,k,j,k,f,k,i,d,b,c,a,k,i,e,g,g,i,k,j,h,l,i,a,n,g,m,g,l,i,h,o,c,k,f,d,h,l,i,e,l,j,k,z,g,g,j,d,e,j,h,i,e,g,i,e,i,c,d,e,i,d,k,m,i,b,c,d,d,n,g,j,d,c,d,k,g,j,d,d,b,h,i,k,j,k,g,g,g,o,k,i,k,j,h,g,f,h,g,f,d,c,n,g,i,d,h,g,j,e,i,d,d,d,e,j,d,d,h,g,f,d,k,j,k,g,i,d,d,d,c,c,n,i,h,i,h);
			}elseif ($j==3){
				$maze = array(o,b,k,j,k,f,d,d,b,d,k,j,e,f,c,c,a,d,k,f,k,m,g,g,f,n,l,f,n,f,e,f,k,g,g,i,k,f,a,b,h,i,h,g,i,h,l,i,e,m,f,d,d,n,g,j,b,c,k,m,o,a,b,d,k,g,g,i,b,c,b,b,e,i,d,h,g,i,k,g,j,h,g,i,b,b,k,m,l,i,h,m,j,h,j,e,i,c,k,f,b,n,z,g,j,h,f,b,k,g,g,g,j,h,m,g,j,c,e,g,g,g,i,h,o,k,m,m,j,c,e,g,i,d,d,d,c,d,d,c,d,h,m);
			}elseif ($j==4){
				$maze = array(j,d,d,d,b,c,b,b,k,j,k,i,d,d,d,a,d,c,h,i,h,g,j,d,d,n,f,d,d,d,d,d,h,f,d,d,d,a,d,d,d,d,d,k,f,d,d,d,e,j,d,d,d,d,e,f,d,d,d,a,a,d,d,d,d,h,i,d,d,d,a,c,d,d,n,o,k,j,d,d,n,g,j,d,d,d,d,e,i,d,d,d,a,a,b,b,b,b,h,o,d,d,d,e,g,f,e,f,a,n,o,b,d,d,e,g,g,f,a,a,n,j,c,d,d,e,i,e,g,g,f,n,z,d,d,d,h,o,c,c,c,c,n);
			}elseif ($j==5){
				$maze = array(j,b,b,k,j,a,b,b,b,d,k,f,a,a,h,g,g,f,c,e,j,e,f,a,e,j,h,g,f,d,h,g,g,f,c,h,g,o,h,i,d,k,g,m,g,j,d,h,j,d,d,k,g,i,k,g,i,d,k,f,d,k,g,g,j,h,f,b,k,g,i,k,g,g,g,i,k,f,e,g,i,k,g,g,g,g,j,h,f,a,h,l,i,h,g,m,g,i,k,f,a,d,a,d,d,h,z,g,j,h,f,a,k,g,j,b,k,g,g,i,k,g,i,e,f,c,a,e,g,m,j,e,m,o,h,i,d,c,h,i,d,h,m);
			}elseif ($j==6){
				$maze = array(j,d,d,d,d,e,z,d,d,d,k,i,d,d,d,k,m,j,d,d,k,g,j,d,d,k,i,d,h,o,k,g,g,g,j,k,i,d,d,d,d,e,g,g,g,g,i,d,d,d,d,k,g,g,g,g,g,j,d,d,d,k,g,g,g,g,g,g,g,j,d,k,g,g,g,g,g,g,g,g,g,l,g,g,g,g,g,g,g,g,g,g,i,h,g,g,g,g,g,g,g,g,i,d,d,h,g,g,g,g,g,g,i,d,d,d,d,h,g,g,g,g,i,d,d,d,d,d,d,h,g,g,i,d,d,d,d,d,d,d,d,c,h);
			}elseif ($j==7){
				$maze = array(j,d,b,k,j,a,d,k,j,d,k,i,k,g,g,g,g,j,h,f,d,e,j,c,e,g,f,h,i,b,a,b,h,i,b,c,a,e,z,o,h,i,a,n,j,c,n,m,g,g,j,k,o,a,n,g,j,b,n,g,i,h,g,l,i,k,f,h,i,n,i,d,n,g,f,d,e,i,b,d,d,d,d,n,g,i,d,e,j,h,o,d,k,o,k,f,d,k,g,f,b,b,d,c,n,g,i,n,g,g,g,g,g,o,d,k,f,n,j,h,g,g,i,e,j,k,g,g,j,h,j,e,i,n,i,h,i,c,c,h,o,h,m);
			}elseif ($j==8){
				$maze = array(j,b,b,b,b,c,b,b,b,b,k,i,h,g,m,g,j,a,a,a,a,e,j,d,c,n,g,g,g,g,g,g,g,g,j,d,d,h,g,g,g,g,g,g,g,i,d,b,k,g,g,g,g,g,g,g,j,k,m,g,g,g,g,g,g,m,i,h,f,k,g,g,g,g,g,i,k,j,d,h,g,g,g,g,g,i,k,g,g,j,d,h,g,g,g,i,k,g,g,g,g,o,b,e,g,f,k,g,g,g,g,i,n,g,m,m,m,g,g,g,g,g,j,k,i,n,z,o,h,g,g,g,i,h,i,d,d,h,o,d,h,m,m);
			}elseif ($j==9){
				$maze = array(j,d,d,d,d,c,d,d,d,d,k,f,d,d,d,d,d,d,d,d,k,g,f,d,d,d,d,d,d,d,d,h,g,f,d,d,d,d,d,d,d,d,k,g,f,d,d,d,d,d,d,d,k,g,g,f,d,d,d,d,d,d,n,g,g,g,f,d,d,d,d,d,n,j,h,m,m,f,d,d,d,d,d,d,h,j,n,z,f,d,d,d,d,d,d,d,h,l,g,f,d,d,d,d,d,d,d,d,h,g,f,d,d,d,d,d,d,d,d,d,h,f,d,d,d,d,d,d,d,d,d,n,i,d,d,d,d,d,d,d,d,d,n);
			}elseif ($j==10){
				$maze = array(o,b,b,b,b,a,b,b,b,b,n,o,a,e,m,m,g,g,m,f,a,n,o,a,e,j,k,g,i,k,f,a,n,o,a,e,g,g,i,k,g,f,a,n,o,a,e,g,i,k,g,g,f,a,n,o,a,e,i,k,g,g,g,f,a,n,o,a,e,l,g,g,g,g,f,a,n,o,a,e,g,g,g,g,g,f,a,n,o,a,e,g,g,g,g,g,f,a,n,o,a,e,f,e,g,g,g,f,a,n,o,a,a,a,a,a,a,a,a,a,n,j,a,a,a,a,a,a,a,a,a,k,m,m,m,m,m,m,m,m,m,m,z);
			}elseif ($j==11){
				$maze = array(j,k,o,k,j,e,j,b,b,d,k,g,i,k,g,g,g,m,m,i,k,g,i,k,g,g,g,f,d,k,j,h,g,j,h,g,g,g,f,k,i,c,n,g,i,k,f,h,g,g,g,j,d,k,g,j,h,i,k,g,g,g,i,k,i,h,i,k,j,h,g,g,i,k,i,d,k,j,h,i,d,h,g,o,a,d,d,h,i,d,b,d,d,c,d,c,d,b,n,j,n,g,j,n,j,k,l,l,i,k,g,z,m,f,d,h,f,a,e,j,h,g,i,d,c,d,d,h,g,g,i,k,i,d,d,d,d,d,d,h,i,d,h);
			}elseif ($j==12){
				$maze = array(j,n,l,j,d,c,k,j,k,o,k,i,b,a,c,b,b,c,h,f,k,g,j,e,f,d,h,i,k,j,h,f,e,m,g,i,k,l,j,c,e,l,g,g,j,c,k,m,f,c,b,c,h,f,h,f,n,f,d,c,d,e,j,d,c,k,i,b,h,o,b,z,i,c,d,k,g,o,a,b,n,i,k,o,d,d,a,h,j,h,f,n,l,i,d,k,l,i,k,i,b,c,d,c,k,j,a,c,k,g,l,g,l,j,d,h,g,g,j,e,m,f,h,f,e,j,d,a,c,e,i,k,i,d,h,i,c,n,i,n,i,n,m);
			}elseif ($j==13){
				$maze = array(j,k,j,b,s,g,j,d,d,d,k,g,g,g,g,j,a,h,j,d,k,g,g,g,g,g,g,g,l,g,l,g,g,m,i,h,i,h,g,g,g,i,h,g,j,b,d,b,d,e,i,c,d,d,h,f,a,d,a,k,f,d,d,d,d,k,i,h,q,c,h,g,o,d,d,d,h,j,d,d,d,d,a,d,d,d,d,k,g,j,d,d,k,g,j,d,d,k,g,g,g,j,k,g,g,g,j,k,g,g,g,g,z,g,g,g,g,g,m,g,g,g,i,d,h,g,g,g,i,d,h,g,i,d,d,d,h,m,i,d,d,d,h);
			}elseif ($j==14){
				$maze = array(j,d,d,d,d,c,d,d,d,d,k,i,d,k,j,d,d,k,j,d,d,h,j,k,g,g,j,d,e,i,d,d,k,g,i,h,g,g,l,i,d,d,d,h,i,d,d,h,g,g,j,d,d,d,k,j,d,b,d,c,c,c,d,b,d,h,g,o,e,j,d,d,d,d,e,j,k,g,o,e,f,d,d,d,d,e,m,g,g,o,e,g,l,j,k,z,f,d,h,i,d,h,g,g,g,g,g,i,d,k,j,d,d,h,g,g,g,g,j,k,g,f,d,d,d,h,g,g,g,g,g,g,i,d,d,d,d,h,i,c,h,i,h);
			}elseif ($j==15){
				$maze = array(j,d,d,k,j,c,d,d,d,d,k,i,d,k,g,g,l,j,d,d,k,g,j,d,e,i,h,i,c,d,k,i,h,g,j,e,j,k,j,d,n,g,j,k,g,g,i,h,g,i,k,j,h,g,g,g,i,k,j,h,j,h,i,d,h,g,g,o,h,g,j,h,j,d,k,j,h,i,k,j,h,i,b,e,z,g,i,k,j,h,m,j,k,g,i,d,e,j,h,g,j,k,z,g,f,d,n,g,i,k,g,g,g,j,h,g,j,d,c,k,g,f,h,g,g,j,e,g,o,d,h,g,i,d,h,i,h,i,c,d,d,d,h);
			}elseif ($j==16){
				$maze = array(j,d,d,d,d,c,d,d,d,d,k,i,d,d,d,d,b,d,d,d,d,h,j,d,d,d,d,c,d,d,d,d,k,g,j,b,d,d,d,d,d,d,d,h,g,g,g,j,k,j,k,j,k,j,k,g,g,i,h,i,h,i,h,i,h,g,g,i,d,d,d,d,k,j,d,d,h,i,d,d,d,d,k,g,i,d,d,k,j,b,b,b,b,h,g,j,d,d,h,f,a,a,a,e,j,h,f,b,d,n,f,a,r,a,e,g,j,a,e,j,k,f,a,a,a,e,g,i,c,h,g,g,i,c,c,c,h,i,d,d,d,h,z);
			}elseif ($j==17){
				$maze = array(j,b,b,b,b,c,b,b,b,b,k,f,h,m,m,i,p,h,m,m,i,e,f,d,d,d,d,d,d,d,d,d,e,f,n,j,d,d,d,d,d,k,o,e,f,n,g,j,d,d,d,k,g,o,e,f,n,g,g,j,d,k,g,g,o,e,f,n,g,g,g,z,h,g,g,o,e,f,n,g,g,i,d,d,h,g,o,e,f,n,g,i,d,d,d,d,h,o,e,f,n,i,d,d,d,d,d,k,o,e,f,d,k,j,d,d,d,d,h,o,e,f,k,g,g,l,l,l,l,l,j,e,i,c,c,c,c,c,c,c,c,c,h);
			}elseif ($j==18){
				$maze = array(j,b,d,d,k,f,d,d,k,j,k,g,i,d,k,i,c,d,k,i,h,g,g,j,d,c,k,j,d,h,j,k,g,f,h,o,d,h,m,j,d,e,i,h,g,l,j,d,d,k,i,k,i,d,k,g,g,g,j,d,h,j,h,j,n,g,i,e,g,i,k,o,c,k,g,l,g,j,h,i,k,i,d,k,g,i,a,e,g,l,j,h,o,k,z,i,k,f,e,g,g,g,j,n,i,d,k,g,f,h,f,h,g,i,k,j,d,c,h,i,k,g,o,c,k,g,i,d,d,d,d,e,i,d,n,i,c,d,d,d,d,d,h);
			}elseif ($j==19){
				$maze = array(j,d,d,d,k,g,o,d,d,d,k,g,j,d,n,i,a,d,d,d,d,h,g,g,j,d,k,i,b,b,d,d,k,g,g,f,d,a,n,g,i,d,d,e,g,g,g,l,g,j,a,d,d,n,g,g,g,i,h,g,f,h,o,d,d,e,g,g,o,d,a,h,o,d,d,d,e,g,g,j,k,f,d,d,d,d,k,g,g,g,g,i,a,k,l,j,k,g,g,g,g,f,d,h,i,h,i,c,e,g,g,g,g,j,k,z,s,d,k,g,g,g,g,g,g,g,i,d,k,g,m,g,i,h,i,h,i,d,d,h,i,d,h);
			}elseif ($j==20){
				$maze = array(z,j,d,k,j,c,d,d,d,d,k,g,i,d,e,i,d,b,d,d,k,g,i,d,k,i,d,d,h,o,k,g,g,j,k,g,o,k,j,d,d,h,g,g,g,f,e,j,h,i,d,k,j,h,g,g,g,g,i,k,j,d,h,g,j,e,g,g,g,j,h,g,j,b,h,g,g,g,g,g,i,d,c,h,g,j,e,g,i,h,g,j,d,n,j,h,g,i,e,j,d,h,f,d,d,h,o,c,n,g,i,d,k,m,j,d,b,d,d,d,e,j,k,i,d,h,l,g,j,d,s,g,m,i,d,d,d,c,h,i,d,d,h);
			}elseif ($j==21){
				$maze = array(j,k,j,k,o,a,b,b,b,n,z,g,g,f,e,j,s,e,m,f,k,g,g,g,f,e,i,d,a,n,j,h,g,g,g,f,a,b,d,c,b,c,k,g,g,g,f,e,g,j,d,a,n,g,g,g,g,f,e,i,h,o,a,b,k,g,g,g,f,e,l,j,k,m,m,g,g,f,a,a,a,a,h,i,d,d,h,g,g,g,f,e,i,d,d,d,d,k,g,g,g,f,e,j,d,d,d,d,h,g,g,g,f,e,i,d,d,d,d,k,g,f,a,c,h,j,d,d,d,d,h,g,i,c,n,o,c,d,d,d,d,d,h);
			}elseif ($j==22){
				$maze = array(j,d,d,d,d,a,d,d,d,d,k,i,d,d,d,d,a,d,d,d,d,e,j,d,d,d,d,a,d,d,d,d,h,f,d,b,b,b,a,b,b,b,b,k,g,j,a,a,a,a,a,a,a,a,e,i,c,c,c,c,a,c,c,c,c,h,j,d,d,d,d,a,d,d,d,d,k,g,j,d,d,d,a,d,d,d,k,g,g,g,j,b,d,a,d,d,k,g,s,g,g,f,a,d,a,d,d,e,g,z,g,g,i,c,d,a,d,d,h,g,g,g,i,d,d,d,c,d,d,d,h,g,i,d,d,d,d,d,d,d,d,d,h);
			}elseif ($j==23){
				$maze = array(j,b,d,b,d,a,k,j,k,j,k,g,f,k,f,k,g,g,f,h,g,g,g,i,h,i,h,g,i,a,d,c,h,g,j,k,j,k,f,k,f,d,b,k,g,i,e,i,e,i,e,f,k,i,h,i,d,c,b,c,n,g,f,h,j,k,j,b,b,e,j,d,e,f,k,g,g,f,h,i,h,g,j,h,i,c,c,e,f,b,b,k,g,g,j,d,d,k,g,f,a,a,e,g,g,g,j,k,g,g,f,a,a,e,g,g,g,z,g,g,g,f,c,c,h,g,g,i,d,h,g,g,i,d,d,d,h,i,d,d,d,h,m);
			}elseif ($j==24){
				$maze = array(o,d,b,d,d,c,k,j,d,d,k,j,d,a,d,d,n,f,h,o,d,h,g,o,c,n,j,k,f,d,d,b,k,f,d,d,d,c,e,f,d,d,h,m,i,d,d,d,n,g,f,d,d,d,k,j,d,b,b,d,h,g,j,d,d,h,f,k,g,g,l,o,h,i,d,b,n,g,g,g,g,g,z,d,d,k,i,k,g,g,i,h,g,i,d,n,g,j,h,g,g,j,d,a,d,d,d,e,i,k,g,g,g,l,g,j,d,n,g,j,h,g,g,g,g,g,g,o,d,h,i,k,m,i,c,h,m,i,d,d,d,d,h);
			}elseif ($j==25){
				$maze = array(o,d,b,d,d,c,k,j,d,d,k,j,d,a,d,d,n,f,h,o,d,h,g,o,c,n,j,k,f,d,d,b,k,f,d,d,d,c,e,f,d,d,h,m,i,d,d,d,n,g,f,d,d,d,k,j,d,b,b,d,h,g,j,d,d,h,f,k,g,g,l,o,h,i,d,b,n,g,g,g,g,g,z,d,d,k,i,k,g,g,i,h,g,i,d,n,g,j,h,g,g,j,d,a,n,o,d,e,i,k,g,g,g,l,g,j,b,n,g,j,h,g,g,g,g,g,g,i,d,h,i,k,m,i,c,h,m,i,d,d,d,d,h);
			}elseif ($j==26){
				$maze = array(o,d,b,d,d,c,k,j,d,d,k,j,d,a,d,d,n,f,h,o,d,h,g,o,c,n,j,k,f,d,d,b,k,f,d,d,d,c,e,f,d,d,h,m,i,d,d,d,n,g,f,d,d,d,k,j,d,b,b,d,h,g,j,d,d,h,f,k,g,g,l,o,h,i,d,b,n,g,g,g,g,g,z,o,d,k,i,k,g,g,i,h,g,i,b,n,g,j,h,g,g,j,d,a,n,i,d,e,i,k,g,g,g,l,g,j,b,n,g,j,h,g,g,g,g,g,g,i,d,h,i,k,m,i,c,h,m,i,d,d,d,d,h);
			}elseif ($j==27){
				$maze = array(j,k,j,k,j,a,k,j,k,j,k,g,f,e,f,a,a,a,e,f,e,g,g,i,h,g,i,a,h,g,i,h,g,f,d,b,h,j,a,k,i,b,d,e,g,j,c,k,f,a,e,j,c,k,g,g,f,b,h,g,m,g,i,b,e,g,i,e,g,o,h,z,i,n,g,f,h,j,c,c,b,k,g,j,b,c,c,k,g,j,d,h,g,g,g,i,d,k,g,g,i,d,b,h,g,i,b,d,h,g,g,j,k,g,j,c,k,g,j,k,g,g,g,g,f,a,d,a,e,g,g,g,i,h,i,h,i,d,h,i,h,i,h);
			}elseif ($j==28){
				$maze = array(j,d,d,d,d,c,d,d,b,d,k,g,j,k,j,b,z,q,k,g,l,g,g,g,g,g,m,l,g,f,a,c,h,g,g,i,h,j,h,g,g,i,d,k,g,i,d,d,c,k,g,i,b,d,h,g,j,d,d,d,h,f,p,g,j,k,g,i,d,d,d,b,c,k,i,h,g,g,o,d,d,d,h,j,h,j,k,g,g,j,d,d,d,k,g,j,h,f,e,g,i,d,d,k,f,h,i,k,g,g,i,d,d,d,a,r,b,d,h,g,g,j,d,d,d,c,c,c,d,d,h,g,i,d,d,d,d,d,d,d,d,d,h);
			}elseif ($j==29){
			    $maze = array(j,d,d,d,d,c,d,d,b,d,k,g,j,k,j,b,z,q,k,g,l,g,g,g,g,g,m,l,g,f,a,c,h,g,g,i,h,j,h,g,g,i,d,k,g,i,d,d,c,k,g,i,b,d,h,g,j,d,d,d,h,f,p,g,j,k,g,i,d,d,d,b,c,k,i,h,g,g,o,d,d,d,h,j,h,j,k,g,g,j,d,d,d,k,g,j,h,f,e,g,i,d,d,k,f,h,i,k,g,g,i,d,d,d,a,r,b,d,h,g,g,j,d,s,d,c,c,c,d,d,h,g,i,d,d,d,d,d,d,d,d,d,h);
		    }elseif ($j==30){
			    $maze = array(j,d,k,j,k,g,j,d,d,b,k,f,k,i,h,i,c,h,o,d,c,e,g,g,o,b,d,d,k,l,j,d,e,g,i,d,c,d,d,e,i,e,s,e,f,k,o,d,d,d,a,b,a,d,e,g,g,j,d,d,d,a,a,c,n,g,m,g,g,j,k,j,e,g,j,b,h,l,g,m,g,m,g,g,g,g,f,k,g,g,o,c,d,c,e,g,f,e,g,f,a,d,d,k,j,c,a,a,h,m,g,i,d,k,g,f,n,g,g,j,k,i,k,j,h,m,m,j,e,g,m,g,o,h,i,d,n,z,c,h,i,d,h);
			}elseif ($j==31){
			    $maze = array(j,d,d,d,d,a,d,d,d,d,k,f,d,p,b,d,c,d,b,r,d,e,f,d,d,c,n,z,o,c,d,d,e,g,j,d,k,j,c,k,j,d,k,g,g,g,j,h,i,b,h,i,k,g,g,g,g,i,d,n,i,d,d,h,g,g,g,i,d,d,d,b,d,d,d,h,g,f,d,d,d,d,a,d,d,d,d,e,g,l,j,b,k,g,j,d,d,k,g,g,i,h,s,m,f,h,j,k,g,g,g,o,b,h,j,e,j,h,m,g,g,g,o,c,d,h,g,i,d,d,h,g,i,d,d,d,d,c,d,d,d,d,h);
		    }elseif ($j==32){
			    $maze = array(j,d,d,d,z,f,d,d,d,d,k,g,j,d,d,k,f,d,d,d,d,h,g,g,j,k,i,c,b,b,d,d,k,g,g,g,f,d,d,c,a,d,d,h,g,m,e,f,b,b,b,a,b,k,l,f,p,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,f,h,g,g,g,g,g,g,g,g,g,g,j,h,g,g,g,g,g,g,g,g,g,i,k,g,g,g,g,g,g,g,g,g,j,h,g,g,g,g,g,g,g,g,i,c,k,g,m,m,m,m,m,g,i,d,d,h,i,d,d,d,d,d,h);
		    }elseif ($j==33){
			    $maze = array(j,d,d,d,d,a,d,d,d,d,k,r,j,d,d,d,c,k,j,d,d,h,j,h,j,d,d,d,h,g,o,d,k,m,j,h,j,d,d,k,i,d,k,g,z,g,j,h,j,k,i,d,d,h,g,g,g,i,d,h,i,d,d,d,b,e,g,i,b,b,b,b,b,b,k,f,e,i,k,g,f,e,f,e,f,e,f,e,l,g,g,f,e,f,e,f,e,f,e,g,g,g,f,e,f,e,f,e,f,e,g,g,g,f,e,f,e,f,e,f,e,g,g,g,f,e,f,e,f,e,f,e,i,c,h,i,h,i,h,i,h,i,h);
		    }elseif ($j==34){
				$maze = array(j,b,b,b,k,f,b,b,b,b,k,g,g,i,h,i,a,a,a,c,c,h,g,f,d,k,j,a,e,g,j,n,l,g,i,k,m,f,a,e,g,f,k,g,f,n,g,l,i,c,e,g,g,i,e,g,j,a,a,d,k,i,h,f,k,g,g,g,f,a,b,a,d,b,h,g,g,g,f,a,a,h,i,d,c,d,h,g,g,i,a,a,k,j,n,o,d,d,e,f,k,i,a,h,i,k,j,b,b,e,f,e,z,m,o,d,e,i,c,c,e,m,g,f,d,p,b,c,d,d,b,h,o,h,i,d,d,c,n,o,d,c,n);
			}elseif ($j==35){
			    $maze = array(j,d,d,d,d,c,d,d,d,d,k,g,j,d,n,o,d,d,d,d,k,g,f,e,j,d,d,d,b,d,k,f,e,g,g,g,l,o,d,c,k,g,g,g,g,g,f,e,j,k,l,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,z,f,e,g,m,g,g,g,g,g,f,q,e,g,m,j,e,g,g,f,e,i,c,h,g,l,g,g,g,g,g,i,d,d,n,m,g,g,g,g,g,i,d,d,b,d,d,h,g,g,g,i,b,n,o,c,d,d,d,h,g,i,d,c,d,n,o,d,d,d,d,h);
		    }elseif ($j==36){
			    $maze = array(j,b,b,b,b,a,b,b,b,b,k,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,r,a,a,a,a,a,e,p,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,q,a,a,a,a,e,f,a,a,r,a,a,r,a,a,a,e,f,a,a,a,a,z,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,s,f,a,a,a,s,a,a,a,a,a,e,i,c,c,c,c,c,c,c,c,c,h);
		    }elseif ($j==37){
			    $maze = array(j,d,d,d,d,a,d,d,d,d,k,g,j,d,d,d,c,d,d,d,k,g,g,g,j,d,d,b,d,d,k,g,g,g,g,g,j,d,c,d,k,g,g,g,g,g,g,g,j,d,k,g,g,g,g,g,g,g,g,f,p,g,g,g,g,g,g,g,g,f,e,z,e,g,f,e,g,g,g,g,g,g,p,e,g,g,g,g,g,g,g,g,i,d,h,g,g,g,g,g,g,g,i,d,d,d,h,g,g,g,g,g,i,d,d,d,d,d,h,g,g,g,i,d,d,d,b,d,d,d,h,g,i,d,d,d,d,c,d,d,d,d,h);
		    }elseif ($j==38){
			    $maze = array(j,d,d,d,d,a,d,d,d,d,k,g,j,d,d,d,c,d,d,d,k,g,g,g,j,d,q,b,d,d,k,g,g,g,g,g,j,d,c,s,k,g,g,g,g,g,g,g,j,d,k,g,g,g,g,g,g,g,g,f,p,e,g,g,g,g,g,g,g,f,e,z,e,g,f,e,g,g,g,g,p,f,p,e,g,p,g,g,g,g,g,g,i,d,h,g,g,g,g,g,g,g,i,d,d,d,h,g,g,g,g,g,i,d,d,d,d,d,h,g,g,g,i,d,d,d,b,d,d,d,h,g,i,d,d,d,d,c,q,d,d,d,h);
		    }elseif ($j==39){
			    $maze = array(j,d,d,z,o,c,b,d,d,d,k,g,j,d,b,b,d,c,d,d,d,e,g,i,b,e,g,j,b,d,d,d,e,g,j,a,c,c,c,c,d,d,d,e,g,g,f,d,d,d,d,d,d,d,e,g,g,f,d,d,d,d,d,d,d,e,g,g,f,d,d,d,d,d,d,d,e,g,g,f,d,d,d,d,d,d,d,e,g,g,f,d,d,d,d,d,d,d,h,g,g,f,d,d,d,d,d,d,d,s,i,h,f,d,d,d,d,d,d,d,e,j,b,a,d,d,d,d,d,d,d,e,i,c,c,d,d,d,d,d,d,d,h);
		    }elseif ($j==40){
			    $maze = array(j,d,d,d,k,i,d,d,d,d,k,g,j,d,k,g,z,d,d,d,k,g,g,i,k,g,i,d,d,d,k,g,g,g,j,h,i,d,d,d,k,g,g,g,g,g,j,d,d,d,k,g,g,g,g,g,g,g,j,d,k,g,g,g,g,g,g,g,g,g,j,e,g,g,g,g,g,g,g,g,g,g,p,g,g,g,g,g,g,g,g,g,i,d,h,g,g,g,g,g,g,g,i,d,d,d,h,g,g,g,g,g,i,d,d,d,d,d,h,g,g,g,i,d,d,d,d,d,d,d,h,g,i,d,d,d,d,d,d,d,d,d,h);
		    }elseif ($j==41){
			    $maze = array(j,b,k,j,k,g,j,b,b,b,r,i,a,a,e,g,g,f,a,a,a,e,j,a,a,e,g,g,f,a,a,a,e,i,a,a,e,g,g,f,a,a,a,e,j,a,a,e,g,g,f,a,a,a,e,i,a,a,e,g,g,f,a,a,a,e,j,a,a,e,g,g,f,a,a,a,e,i,a,a,e,g,g,f,a,a,a,e,j,c,a,e,g,g,f,a,a,a,e,i,b,a,e,g,g,f,a,c,a,e,j,h,i,e,g,g,i,h,j,e,g,g,j,k,p,i,c,d,d,h,g,g,i,h,i,c,n,z,d,d,d,h,m);
		    }elseif ($j==42){
			    $maze = array(j,d,b,b,b,c,d,d,b,d,k,g,l,g,m,f,b,k,l,i,k,g,g,g,i,k,g,g,g,g,j,h,g,g,g,j,e,g,g,g,f,a,k,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,f,h,g,g,g,g,g,m,g,g,g,g,j,h,g,m,g,g,j,h,m,g,m,i,k,g,j,h,g,g,j,d,e,o,b,h,g,m,j,h,g,m,j,h,j,c,k,g,j,c,k,g,j,c,k,g,z,p,g,g,z,g,g,g,z,g,i,d,h,m,i,d,h,m,i,c,h);
		    }elseif ($j==43){
			    $maze = array(j,d,b,b,b,c,d,d,b,d,k,g,l,g,m,f,b,k,l,i,k,g,g,g,i,k,g,g,g,g,j,h,g,g,g,j,e,g,g,g,f,a,k,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,f,h,g,g,g,g,g,m,g,g,g,g,j,h,g,m,g,g,j,h,m,g,m,i,k,g,j,h,g,g,j,d,e,o,b,h,g,m,j,h,g,m,j,h,j,c,k,g,j,c,k,g,j,c,k,g,z,g,g,f,z,g,g,g,z,g,i,d,h,m,i,d,h,m,i,p,h);
		    }elseif ($j==44){
			    $maze = array(j,d,b,b,b,c,d,d,b,d,k,g,l,g,m,f,b,k,l,i,k,g,g,g,i,k,g,g,g,g,j,h,g,g,g,j,e,g,g,g,f,a,k,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,f,h,g,g,g,g,g,m,g,g,g,g,j,h,g,m,g,g,j,h,m,g,m,i,k,g,j,h,g,g,j,d,e,o,b,h,g,m,j,h,g,m,j,h,j,c,k,g,j,c,k,g,j,c,k,g,z,e,g,p,z,g,g,g,z,g,i,d,h,m,i,d,h,m,i,d,h);
		    }elseif ($j==45){
				$maze = array(q,l,l,l,j,a,k,l,l,l,q,j,c,c,c,c,a,c,c,c,c,k,m,q,l,l,l,g,l,l,l,q,m,p,j,c,c,c,a,c,c,c,k,p,q,m,q,l,l,g,l,l,q,m,q,q,p,j,c,c,a,c,c,k,p,q,q,q,m,q,l,g,l,q,m,q,q,q,q,r,j,c,a,c,k,r,q,q,q,q,q,m,q,g,q,m,q,q,q,q,q,q,p,q,g,q,p,q,q,q,q,q,q,q,j,a,k,q,q,q,q,q,q,q,q,p,g,p,q,q,q,q,q,q,q,q,q,z,q,q,q,q,q);
			}
			$umaze = implode($maze,",");
			set_module_pref("maze", $umaze);
			if (get_module_pref('super')){
				$mapkey2="";
				$mapkey="";
				for ($i=0;$i<143;$i++){
					$keymap=ltrim($maze[$i]);
					$mazemap=$keymap;
					$mazemap.="maze.gif";
					$mapkey.="<img src=\"./images/$mazemap\" title=\"\" alt=\"\" style=\"width: 20px; height: 20px;\">";
					if ($i==10 or $i==21 or $i==32 or $i==43 or $i==54 or $i==65 or $i==76 or $i==87 or $i==98 or $i==109 or $i==120 or $i==131 or $i==142){
						$mapkey="`n".$mapkey;
						$mapkey2=$mapkey.$mapkey2;
						$mapkey="";
					}
				}
				output("%s",$mapkey2,true);
			}
		}
	}
	addnav("Continue","forest.php");
	villagenav();
	page_footer();
?>