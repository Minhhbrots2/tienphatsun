<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

class AffiliateClick extends DbBasic {
    function __construct(){
        $this->pkey = "id";
        $this->tbl = DB_PREFIX."affiliate_clicks"; 
    }
}
