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
class Why extends dbBasic{
	function Why() {
        $this->pkey = "why_id";
        $this->tbl = DB_PREFIX."why";
    }
    function getTitle($why_id) {
        $one = $this->getOne($why_id);
        return $one['title'];
    }
	function getImageUrl($why_id) {
		$one = $this->getOne($why_id);
		return $one['image'];
	}
	function getIntro($why_id) {
		$one = $this->getOne($why_id);
		return html_entity_decode($one['intro']);
	}
}