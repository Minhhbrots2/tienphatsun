<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| #################################################################### ||
\*======================================================================*/
class PriceSheet extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."price_sheets";
	}
	function getTitle($pval,$_args=array()){
		if(!isset($_args['title'])){
			$_args = $this->getOne($pval,"title");
		}
		return $_args['title'];
	}
	// Tóm tắt phạm vi áp dụng (đọc cột scope JSON) để hiển thị ở danh sách.
	function getScopeText($pval,$_args=array()){
		global $clsISO;
		if(!isset($_args['scope'])){
			$_args = $this->getOne($pval,"scope");
		}
		$scope = isset($_args['scope']) ? $_args['scope'] : '';
		$scope = $clsISO->to_array_json($scope);
		if(empty($scope) || !is_array($scope)) return '---';
		static $cache_project = array();
		$clsProject = new Project();
		$parts = array();
		foreach($scope as $item){
			if(!is_array($item)) continue;
			$project_id = isset($item['project_id']) ? (int)$item['project_id'] : 0;
			if(!$project_id) continue;
			if(!isset($cache_project[$project_id])){
				$cache_project[$project_id] = $clsProject->getOneField('title', $project_id);
			}
			$name = $cache_project[$project_id];
			$n_block = $n_build = 0;
			if(isset($item['block_id'])){
				if(is_array($item['block_id'])) $n_block = count(array_filter($item['block_id']));
				elseif((int)$item['block_id'] > 0) $n_block = 1;
			}
			if(isset($item['building_id']) && is_array($item['building_id'])){
				$n_build = count(array_filter($item['building_id']));
			}
			$extra = array();
			if($n_block) $extra[] = $n_block.' phân khu';
			if($n_build) $extra[] = $n_build.' tòa';
			$parts[] = $name.(!empty($extra) ? ' ('.implode(', ', $extra).')' : '');
		}
		return !empty($parts) ? implode('<br>', $parts) : '---';
	}
}
