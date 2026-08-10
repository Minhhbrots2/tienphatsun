<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
class QuizTestAnswers extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."quiz_test_answers";
	}
}

