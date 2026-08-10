<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

class AffiliateWalletLog extends DbBasic {
    function __construct(){
        $this->pkey = "id";
        $this->tbl = DB_PREFIX."affiliate_wallet_logs"; 
    }
}
