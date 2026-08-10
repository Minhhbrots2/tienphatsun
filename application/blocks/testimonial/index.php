<?
	global $core, $smarty;
	/* Testimonial */
	$clsTestimonial = new Testimonial();
	$smarty->assign('clsTestimonial', $clsTestimonial);
	
	$lstTestimonial = $clsTestimonial->GetAll("is_trash=0 and is_online='1' order by order_no DESC");
	$smarty->assign('lstTestimonial', $lstTestimonial); unset($lstTestimonial);
?>