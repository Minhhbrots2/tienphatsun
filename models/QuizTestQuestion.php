<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/**
 * Model: QuizTestQuestion
 * Table: default_quiz_test_questions
 */
class QuizTestQuestion extends dbBasic {

	function __construct(){
		$this->pkey = "question_id";
		$this->tbl  = DB_PREFIX . "quiz_test_questions";
	}

	function getByTestId($test_id){
		return $this->getAll("`test_id`='" . (int)$test_id . "' ORDER BY `order_no` ASC");
	}

	function countByTestId($test_id){
		return $this->countItem("`test_id`='" . (int)$test_id . "'");
	}

	/**
	 * Load questions with nested answers for a given test.
	 *
	 * @param  int   $test_id
	 * @return array  Each element has the original columns + 'answers' sub-array
	 */
	function getQuestionsWithAnswers($test_id){
		$clsAnswer = new QuizTestAnswer();
		$rows      = $this->getByTestId($test_id);
		$result    = array();

		foreach ($rows as $q) {
			$q['answers'] = $clsAnswer->getByQuestionId($q['question_id']);
			$result[]     = $q;
		}
		return $result;
	}

	/**
	 * Delete all questions and their answers for a test (hard delete).
	 */
	function deleteByTestId($test_id){
		global $dbconn;
		$test_id = (int)$test_id;
		// Delete answers first (FK)
		$dbconn->Execute(
			"DELETE a FROM `" . DB_PREFIX . "quiz_test_answers` a
			 INNER JOIN `{$this->tbl}` q ON a.`question_id` = q.`question_id`
			 WHERE q.`test_id` = '{$test_id}'"
		);
		// Then delete questions
		$dbconn->Execute("DELETE FROM `{$this->tbl}` WHERE `test_id` = '{$test_id}'");
	}
}
