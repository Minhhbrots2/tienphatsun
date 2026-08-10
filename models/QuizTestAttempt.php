<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/**
 * Model: QuizTestAttempt
 * Table: default_quiz_test_attempts
 */
class QuizTestAttempt extends dbBasic {

	function __construct(){
		$this->pkey = "attempt_id";
		$this->tbl  = DB_PREFIX . "quiz_test_attempts";
	}

	/**
	 * Get all attempts by a user, optionally filtered by test.
	 */
	function getByUser($user_id, $test_id = 0){
		$cond = "`user_id`='" . (int)$user_id . "'";
		if ($test_id > 0) {
			$cond .= " AND `test_id`='" . (int)$test_id . "'";
		}
		return $this->getAll($cond . " ORDER BY `finished_at` DESC");
	}

	/**
	 * Get the highest score a user achieved on a specific test.
	 */
	function getBestScore($user_id, $test_id){
		$row = $this->getByCond(
			"`user_id`='" . (int)$user_id . "' AND `test_id`='" . (int)$test_id . "' ORDER BY `score` DESC LIMIT 1"
		);
		return !empty($row) ? (float)$row['score'] : 0;
	}

	/**
	 * Get the most recent attempt for a user on a specific test.
	 */
	function getLastAttempt($user_id, $test_id){
		return $this->getByCond(
			"`user_id`='" . (int)$user_id . "' AND `test_id`='" . (int)$test_id . "' ORDER BY `finished_at` DESC LIMIT 1"
		);
	}

	/**
	 * Enrich an attempt row with computed fields (is_pass, score_percent).
	 *
	 * @param  array $attempt   Raw attempt row
	 * @param  int   $pass_score  The test's pass threshold
	 * @return array            Enriched row (or empty array if input was empty)
	 */
	static function enrich($attempt, $pass_score){
		if (empty($attempt)) return array();
		$attempt['is_pass']       = ($attempt['score'] >= $pass_score) ? 1 : 0;
		$attempt['score_percent'] = round($attempt['score'], 1);
		return $attempt;
	}
}
