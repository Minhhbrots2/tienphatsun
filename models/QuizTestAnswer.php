<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/**
 * Model: QuizTestAnswer
 * Table: default_quiz_test_answers
 */
class QuizTestAnswer extends dbBasic {

	function __construct(){
		$this->pkey = "answer_id";
		$this->tbl  = DB_PREFIX . "quiz_test_answers";
	}

	function getByQuestionId($question_id){
		return $this->getAll("`question_id`='" . (int)$question_id . "' ORDER BY `answer_id` ASC");
	}

	function getCorrectByQuestionId($question_id){
		return $this->getAll("`question_id`='" . (int)$question_id . "' AND `is_correct`='1'");
	}

	/**
	 * Return an array of correct answer_ids for a question.
	 */
	function getCorrectIds($question_id){
		$rows = $this->getCorrectByQuestionId($question_id);
		return array_column($rows, 'answer_id');
	}

	/**
	 * Determine if a question is multi-choice (more than one correct answer).
	 */
	function isMultiChoice($question_id){
		return count($this->getCorrectByQuestionId($question_id)) > 1;
	}
}
