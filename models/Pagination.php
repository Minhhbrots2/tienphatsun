<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
class Pagination{
	var $total;
	var $number_per_page;
	var $current_page;
	var $class;
	var $current_class;
	var $link;
	var $link_page_1;
	function __construct(){	}
	function initianize($config = array()){
		if(is_array($config) && count($config)>0){
			foreach($config as $k=>$v){
				$this->$k = $v;
			}
		}else{
			print_r('Initialize pagination object faild !'); die();
		}
	}
    function getTotalPage(){
        if($this->number_per_page <= 0){$this->number_per_page = 1;}
        return ceil($this->total/$this->number_per_page);
    }
	function create_links(){
		global $core;
		$pagination_system = '';
		$pagination_stages = 2;
		$start_page = ($this->current_page - 1) * $this->number_per_page;
		//This initializes the page setup
		$previous_page = $this->current_page - 1;	
		$next_page = $this->current_page + 1;							
		$last_page = @ceil($this->total / $this->number_per_page);
		$last_paged = $last_page - 1;
		// Start
		if($last_page > 1){
			// Trang trước
			if($this->current_page > 1){
				$pagination_system.= '<li><a class="prev paginate_button" href="'.$this->link.'&page='.$previous_page.'" title="'.$previous_page.'">Trước</a></li>'; 
			}else{
				$pagination_system.= '<li><a class="prev disabled paginate_button" href="javascript:void(0);">'.$core->makeIcon('angle-left','&nbsp;').'</a></li>';
			}
			// Nếu không đủ trang tới lúc điểm ngắt
			if ($last_page < 7 + ($pagination_stages * 2)){
				for ($page_counter = 1; $page_counter <= $last_page; $page_counter++){
					if($page_counter == $this->current_page){
						$pagination_system.= '<li><a class="paginate_active">'.$page_counter.'</a></li>';
					}else{
						$pagination_system.= '<li><a class="paginate_button" href="'.$this->link.'&page='.$page_counter.'">'.$page_counter.'</a></li>';
					}
				}
			}
			elseif($last_page > 5 + ($pagination_stages * 2)){
				if($this->current_page < 1 + ($pagination_stages * 2)){
					for ($page_counter = 1; $page_counter < 4 + ($pagination_stages * 2); $page_counter++){
						if($page_counter == $this->current_page){
							$pagination_system.= '<li><a class="paginate_active" >'.$page_counter.'</a></li>';
						}else{
							$pagination_system.= '<li><a class="paginate_button" href="'.$this->link.'&page='.$page_counter.'" title="'.$page_counter.'">'.$page_counter.'</a></li>';	
						}
					}
					$pagination_system.= '<li><a class="paginate_button disabled" href="javascript:void();">...</a></li>';
					$pagination_system.= '<li><a class="paginate_button" href="'.$this->link.'&page='.$last_paged.'" title="'.$last_paged.'">'.$last_paged.'</a></li>';
					$pagination_system.= '<li><a class="paginate_button" href="'.$this->link.'&page='.$last_page.'" title="'.$last_page.'">'.$last_page.'</a></li>';
				}elseif($last_page-($pagination_stages*2) > $this->current_page 
					&& $this->current_page > ($pagination_stages*2)){
					$pagination_system .= '<li><a class="paginate_button" href="'.$this->link.'&page=1" title="1">1</a></li>';
					$pagination_system .= '<li><a class="paginate_button" href="'.$this->link.'&page=2" title="2">2</a></li>';
					$pagination_system .= '<li><a class="paginate_button" href="javascript:void();">...</a></li>';
					for($page_counter =($this->current_page - $pagination_stages);$page_counter<=($this->current_page + $pagination_stages); $page_counter++){
						if ($page_counter == $this->current_page){
							$pagination_system.= '<li><a class="paginate_active" href="javascript:void();">'.$page_counter.'</a></li>';
						}else{
							$pagination_system.= '<li><a class="paginate_button" href="'.$this->link.'&page='.$page_counter.'" title="'.$page_counter.'">'.$page_counter.'</a></li>';
						}					
					}
					$pagination_system.= '<li><a class="paginate_button disabled" href="javascript:void();">...</a></li>';
					$pagination_system.= '<li><a class="paginate_button" href="'.$this->link.'&page='.$last_paged.'" title="'.$last_paged.'">'.$last_paged.'</a></li>';
					$pagination_system.= '<li><a class="paginate_button" href="'.$this->link.'&page='.$last_page.'" title="'.$last_page.'">'.$last_page.'</a></li>';
				}else{
					$pagination_system .= '<li><a class="paginate_button" href="'.$this->link.'&page=1" title="1">1</a></li>';
					$pagination_system .= '<li><a class="paginate_button" href="'.$this->link.'&page=2" title="2">2</a></li>';
					$pagination_system .= '<li><a class="paginate_button" href="javascript:void();" >...</a>';
					for($page_counter = $last_page - (2+($pagination_stages*2)); $page_counter <= $last_page; $page_counter++){
						if ($page_counter == $this->current_page)
						{
							$pagination_system .= '<li><a class="paginate_active" href="javascript:void();">'.$page_counter.'</a></li>';
						}else{
							$pagination_system .= '<li><a class="paginate_button" href="'.$this->link.'&page='.$page_counter.'" title="'.$page_counter.'">'.$page_counter.'</a></li>';
						}					
					}
				}
			}
			//Trang tiếp
			if ($this->current_page < $page_counter - 1){
				$pagination_system.= '<li><a class="next paginate_button" href="'.$this->link.'&page='.$next_page.'" title="'.$next_page.'">'.$core->makeIcon('angle-right','&nbsp;').'</a></li>'; 
			}else{
				$pagination_system.= '<li><a class="next disabled paginate_button" rel="nofollow" href="javascript:void();">
					'.$core->makeIcon('angle-right','&nbsp;').'</a></li>';
			}
		}
		return $pagination_system;	
	}
	function create_links_page($allow_html=false, $query_string=""){
		$pagination_system = '';
		$pagination_stages = 1;
		$start_page = ($this->current_page - 1) * $this->number_per_page;
		//This initializes the page setup
		$previous_page = $this->current_page - 1;	
		$next_page = $this->current_page + 1;							
		$last_page = @ceil($this->total / $this->number_per_page);
		$last_paged = $last_page - 1;
		$last_link = ($allow_html?'.html':'/');
		if($this->link_page_1==''){
			$this->link_page_1 = $this->link.'trang-1'.$last_link;
		}
		// Start
		if($last_page > 1){
			// Trang trước
			if($this->current_page > 1){
				$pagination_system.= '<li class="page-item"><a class="prev page-link" href="'.($previous_page==1?$this->link_page_1.$query_string:$this->link.'trang-'.$previous_page.$last_link.$query_string).'" title="'.$previous_page.'">&lsaquo;</a></li>'; 
			}else{
				$pagination_system.= '<li class="page-item"><a class="prev page-link disabled" href="javascript:void(0);">&lsaquo;</a></li>';
			}
			// Nếu không đủ trang tới lúc điểm ngắt
			if ($last_page < 7 + ($pagination_stages * 2)){
				for ($page_counter = 1; $page_counter <= $last_page; $page_counter++){
					if ($page_counter == $this->current_page) {
						$pagination_system.= '<li class="page-item active"><a class="current page page-link">'.$page_counter.'</a></li>';
					}else {
						$pagination_system.= '<li class="page-item"><a class="page page-link" href="'.($page_counter==1? $this->link_page_1.$query_string : $this->link.'trang-'.$page_counter.$last_link.$query_string).'">'.$page_counter.'</a></li>';
					}
				}
			}
			elseif($last_page > 5 + ($pagination_stages * 2)){
				if($this->current_page < 1 + ($pagination_stages * 2)){
					for ($page_counter = 1; $page_counter < 4 + ($pagination_stages * 2); $page_counter++){
						if ($page_counter == $this->current_page){
							$pagination_system.= '<li class="page-item active"><a class="current page page-link" href="javascript:void();">'.$page_counter.'</a></li>';
						}else{
							$pagination_system.= '<li class="page-item"><a class="page page-link" href="'.($page_counter==1? $this->link_page_1 : $this->link.'trang-'.$page_counter.$last_link.$query_string).'" title="'.$page_counter.'">'.$page_counter.'</a></li>';
						}	
					}
					$pagination_system.= '<li class="page-item"><a class="page page-link disabled" href="javascript:void();" class="page-item">...</a></li>';
					$pagination_system.= '<li class="page-item"><a class="page page-link" href="'.$this->link.'trang-'.$last_paged.$last_link.$query_string.'" title="'.$last_paged.'">'.$last_paged.'</a></li>';
					$pagination_system.= '<li class="page-item"><a class="page page-link" href="'.$this->link.'trang-'.$last_page.$last_link.$query_string.'" title="'.$last_page.'">'.$last_page.'</a></li>';
				}elseif($last_page-($pagination_stages*2) > $this->current_page && $this->current_page > ($pagination_stages*2)){
					$pagination_system .= '<li class="page-item"><a class="page page-link" href="'.$this->link_page_1.$query_string.'" title="1">1</a></li>';
					$pagination_system .= '<li class="page-item"><a class="page page-link" href="'.$this->link.'trang-2'.$last_link.$query_string.'" title="2">2</a></li>';
					$pagination_system .= '<li class="page-item"><a class="page page-link" href="javascript:void();">...</a></li>';
					for($page_counter =($this->current_page - $pagination_stages); $page_counter<=($this->current_page + $pagination_stages); $page_counter++){
						if ($page_counter == $this->current_page){
							$pagination_system.= '<li class="page-item active"><a class="current page page-link" href="javascript:void();">'.$page_counter.'</a></li>';
						}else{
							$pagination_system.= '<li class="page-item"><a class="page page-link" href="'.($page_counter==1? $this->link_page_1 : $this->link.'trang-'.$page_counter.$last_link.$query_string).'" title="'.$page_counter.'">'.$page_counter.'</a></li>';
						}					
					}
					$pagination_system.= '<li class="page-item"><a class="page page-link disabled" href="javascript:void();">...</a></li>';
					$pagination_system.= '<li class="page-item"><a class="page page-link" href="'.$this->link.'trang-'.$last_paged.$last_link.$query_string.'" title="'.$last_paged.'">'.$last_paged.'</a></li>';
					$pagination_system.= '<li class="page-item"><a class="page page-link" href="'.$this->link.'trang-'.$last_page.$last_link.$query_string.'" title="'.$last_page.'">'.$last_page.'</a></li>';
				}else{
					$pagination_system.= '<li class="page-item"><a class="page page-link" href="'.$this->link_page_1.$query_string.'" title="1">1</a></li>';
					$pagination_system.= '<li class="page-item"><a class="page page-link" href="'.$this->link.'trang-2'.$last_link.$query_string.'" title="2">2</a></li>';
					$pagination_system .= '<li class="page-item"><a class="page page-link" href="javascript:void();" >...</a></li>';
					for($page_counter = $last_page - (2+($pagination_stages*2)); $page_counter <= $last_page; $page_counter++){
						if ($page_counter == $this->current_page){
							$pagination_system .= '<li class="page-item active"><a class="current page page-link" href="javascript:void();" >'.$page_counter.'</a></li>';
						}else{
							$pagination_system .= '<li class="page-item"><a class="page page-link" href="'.($page_counter==1? $this->link_page_1 : $this->link.'trang-'.$page_counter.$last_link.$query_string).'" title="'.$page_counter.'">'.$page_counter.'</a></li>';
						}				
					}
				}
			}
			//Trang tiếp
			if ($this->current_page < $page_counter - 1){
				$pagination_system.= '<li class="page-item"><a class="next page-link" href="'.$this->link.'trang-'.$next_page.$last_link.$query_string.'" title="'.$next_page.'">&rsaquo;</a></li>'; 
			}else{
				$pagination_system.= '<li class="page-item"><a class="next page-link disabled" rel="nofollow" href="javascript:void();">&rsaquo;</a></li>';
			}
		}
		return $pagination_system;	
	}
	function pagination_ajax($total, $number_per_page, $current_page = 1, $class='paginate_button', $classCurrent = 'paginate_active', $is_showinfo=true){
		$pagination_system = '';
		$pagination_stages = 2;
		$start_page = ($current_page - 1) * $number_per_page;
		
		//This initializes the page setup
		$previous_page = $current_page - 1;	
		$next_page = $current_page + 1;							
		$last_page = @ceil($total/$number_per_page);
		$last_paged = $last_page - 1;
		// Start
		if($is_showinfo){
			$pagination_system .='<div class="dataTable_length">';
			$pagination_system .= 'Số bản gi';
			$pagination_system .= '<select class="paginate_length">';
			$pagination_system .= '<option '.($number_per_page==5?'selected="selected"':'').' value="5">5</option>';
			$pagination_system .= '<option '.($number_per_page==10?'selected="selected"':'').' value="10">10</option>';
			$pagination_system .= '<option '.($number_per_page==20?'selected="selected"':'').' value="20">20</option>';
			$pagination_system .= '<option '.($number_per_page==30?'selected="selected"':'').' value="30">30</option>';
			$pagination_system .= '<option '.($number_per_page==40?'selected="selected"':'').' value="40">40</option>';
			$pagination_system .= '</select>';
			$pagination_system .=' / trang';
			$pagination_system .= '</div>';
		}
		if($last_page > 1){
			$pagination_system .= '<div class="dataTable_paginate">';
			// Trang trước
			if($current_page > 1){
				$pagination_system.= "<a class=\"first paginate_button\" href=\"javascript:void(0);\" page='".$previous_page."'>Trước</a>"; }
			else{
				$pagination_system.= "<a class=\"first paginate_button disabled paginate_button_disabled\" href=\"javascript:void(0);\">Trước</a>";
			}
			// Nếu không đủ trang tới điểm ngắt
			if ($last_page < 7 + ($pagination_stages * 2)){	
				$pagination_system .='<span>';
				for ($page_counter = 1; $page_counter <= $last_page; $page_counter++){
					if ($page_counter == $current_page) {
						$pagination_system.= "<a class='paginate_active'>$page_counter</a>";
					}else {
						$pagination_system.= "<a class='paginate_button' page=".$page_counter." href='javascript:void(0);'>$page_counter</a>";
					}
				}
				$pagination_system .='</span>';
			}elseif($last_page > 5 + ($pagination_stages * 2)){
				if($current_page < 1 + ($pagination_stages * 2)){
					$pagination_system .='<span>';
					for ($page_counter = 1; $page_counter < 4 + ($pagination_stages * 2); $page_counter++){
						if ($page_counter == $current_page) {
							$pagination_system.= "<a class=\"paginate_active\" href=\"javascript:void();\">$page_counter</a>";
						}else {
							$pagination_system.= "<a class=\"paginate_button\" href='javascript:void();' page=".$page_counter.">$page_counter</a>";
						}	
					}
					$pagination_system.= "<a class=\"paginate_button disabled\" href=\"javascript:void();\">...</a>";
					$pagination_system.= "<a class=\"paginate_button\" href=\"avascript:void();\" page=".$last_paged.">$last_paged</a>";
					$pagination_system.= "<a class=\"paginate_button\" href=\"javascript:void();\" page=".$last_page.">$last_page</a>";
					$pagination_system .='</span>';
				}elseif($last_page-($pagination_stages*2) > $current_page && $current_page > ($pagination_stages*2)){
					$pagination_system .='<span>';
					$pagination_system .= "<a class=\"paginate_button\" href=\"javascript:void();\" page=\"1\">1</a>";
					$pagination_system .= "<a class=\"paginate_button\" href=\"javascript:void();\" page=\"2\">2</a>";
					$pagination_system .= "<a class=\"paginate_button disabled\" href=\"javascript:void();\">...</a>";
					for ($page_counter =($current_page-$pagination_stages);$page_counter<=($current_page+$pagination_stages); $page_counter++){
						if ($page_counter == $current_page) {
							$pagination_system.= "<a class=\"paginate_active\" href=\"javascript:void();\">$page_counter</a>";
						}else {
							$pagination_system.= "<a class=\"paginate_button\" href='javascript:void(0);' page=".$page_counter.">$page_counter</a>";
						}					
					}
					$pagination_system.= "<a class=\"paginate_button disabled\" href=\"javascript:void();\">...</a>";
					$pagination_system.= "<a class=\"paginate_button\" href=\"javascript:void();\" page=".$last_paged.">$last_paged</a>";
					$pagination_system.= "<a class=\"paginate_button\" href=\"javascript:void();\" page=".$last_page.">$last_page</a>";
					$pagination_system .='</span>';
				}else{
					$pagination_system .='<span>';
					$pagination_system.= "<a class=\"paginate_button\" href=\"javascript:void();\" page=\"1\">1</a>";
					$pagination_system.= "<a class=\"paginate_button\" href=\"javascript:void();\" page=\"2\">2</a>";
					$pagination_system.= "<a class=\"paginate_button disabled\" href=\"javascript:void();\">...</a>";
					for($page_counter = $last_page - (2+($pagination_stages*2)); $page_counter <= $last_page; $page_counter++){
						if ($page_counter == $current_page) {
							$pagination_system.= "<a class=\"paginate_active\" href=\"javascript:void();\">$page_counter</a>";
						}else {
							$pagination_system.= "<a class=\"paginate_button\" href=\"javascript:void();\" page='".$page_counter."'>$page_counter</a>";
						}					
					}
					$pagination_system .='</span>';
				}
			}
			//Trang tiếp
			if ($current_page < $page_counter - 1) {
				$pagination_system.= "<a class=\"next paginate_button\" href=\"javascript:void();\" page=".$next_page.">Tiếp theo</a>"; 
			}else{
				$pagination_system.= "<a class=\"next paginate_button disabled paginate_button_disabled\" href=\"javascript:void();\">Tiếp theo</a>";
			}
			$pagination_system .='<input type="hidden" value="'.$current_page.'" name="paginate_current_page" class="paginate_current_page">';
			$pagination_system .= '</div>';
		}
		return $pagination_system;	
	}
}
?>