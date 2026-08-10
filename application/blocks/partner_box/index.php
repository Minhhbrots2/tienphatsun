<?php
	global $core, $smarty;
	$clsPartner = new Partner(); $smarty->assign('clsPartner', $clsPartner);
	$lstPartner = $clsPartner->getAll("is_trash=0 and is_online=1 order by order_no desc", "{$clsPartner->pkey},link,title,image");
	if(!empty($lstPartner)){
		foreach($lstPartner as $k => $partner){
			$lstPartner[$k]['image'] = $clsPartner->getImage($partner[$clsPartner->pkey],150,150,$partner);
		}
	}
 	$smarty->assign('lstPartner', $lstPartner); 
	unset($lstPartner);
?>