<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
class QuizTestFeatureRule extends dbBasic{
	function __construct(){
		$this->pkey = "feature_key";
		$this->tbl = DB_PREFIX."quiz_test_feature_rules";
	}
}
