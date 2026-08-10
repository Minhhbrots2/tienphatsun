<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/**
 * Model: QuizTestRanking
 * Table: default_quiz_test_rankings
 *
 * Manages the Qualification Score (QS) and tier for each user.
 * QS = weighted average of best scores across all active tests.
 * Weights: video = 1, module = 2, project = 3.
 */
class QuizTestRanking extends dbBasic {

	/** @var array Tier thresholds (min QS => tier label) — evaluated top-down */
	private static $TIER_THRESHOLDS = array(
		90 => 'S',
		80 => 'A',
		70 => 'B',
		60 => 'C',
	);
	private static $DEFAULT_TIER = 'D';

	/** @var array Ordinal value per tier for comparisons */
	private static $TIER_ORDER = array('D' => 1, 'C' => 2, 'B' => 3, 'A' => 4, 'S' => 5);

	function __construct(){
		$this->pkey = "user_id";
		$this->tbl  = DB_PREFIX . "quiz_test_rankings";
	}

	/* ========== Core: recalculate ========== */

	/**
	 * Recalculate QS for a given user and persist.
	 *
	 * @param  int   $user_id
	 * @return array ['qs' => float, 'tier' => string]
	 */
	function recalculate($user_id){
		$clsQuizTest = new QuizTest();
		$clsAttempt  = new QuizTestAttempt();

		$lstTests           = $clsQuizTest->getAll("`is_trash`='0'");
		$totalWeight        = 0;
		$totalWeightedScore = 0;

		foreach ($lstTests as $test) {
			$bestScore = $clsAttempt->getBestScore($user_id, $test['test_id']);
			if ($bestScore <= 0) continue;

			$w = $clsQuizTest->getWeight($test['test_type']);
			$totalWeightedScore += $bestScore * $w;
			$totalWeight        += $w;
		}

		$qs   = ($totalWeight > 0) ? round($totalWeightedScore / $totalWeight, 2) : 0;
		$tier = $this->determineTier($qs);

		// Upsert
		$payload = array(
			'qualification_score' => $qs,
			'tier'                => $tier,
			'last_updated'        => time(),
		);

		$existing = $this->getOne($user_id);
		if (!empty($existing)) {
			$this->updateOne($user_id, $payload);
		} else {
			$payload['user_id'] = $user_id;
			$this->insert($payload);
		}

		return array('qs' => $qs, 'tier' => $tier);
	}

	/* ========== Tier helpers ========== */

	/**
	 * Determine tier label from a QS value.
	 */
	function determineTier($qs){
		foreach (self::$TIER_THRESHOLDS as $min => $label) {
			if ($qs >= $min) return $label;
		}
		return self::$DEFAULT_TIER;
	}

	/**
	 * Compare two tier labels.
	 * @return int  negative if $a < $b, zero if equal, positive if $a > $b
	 */
	static function compareTiers($a, $b){
		$oa = isset(self::$TIER_ORDER[$a]) ? self::$TIER_ORDER[$a] : 0;
		$ob = isset(self::$TIER_ORDER[$b]) ? self::$TIER_ORDER[$b] : 0;
		return $oa - $ob;
	}

	/* ========== Permission check ========== */

	/**
	 * Check whether a user passes the feature-rule gate.
	 *
	 * @param  int    $user_id
	 * @param  string $feature_key
	 * @return bool
	 */
	function checkPermission($user_id, $feature_key){
		$clsRule = new QuizTestFeatureRule();
		$ruleRow = $clsRule->getByCond("`feature_key`='" . addslashes($feature_key) . "'");
		if (empty($ruleRow)) return true; // no rule → allowed

		$rules = json_decode($ruleRow['rule_json'], true);
		if (empty($rules)) return true;

		$rank = $this->getOne($user_id);
		$qs   = !empty($rank) ? (float)$rank['qualification_score'] : 0;

		// min_qs
		if (isset($rules['min_qs']) && $qs < $rules['min_qs']) {
			return false;
		}

		// require_pass_project
		if (!empty($rules['require_pass_project'])) {
			if (!$this->_hasPassedAnyProjectTest($user_id)) {
				return false;
			}
		}

		// min_tier
		if (!empty($rules['min_tier'])) {
			$userTier = !empty($rank['tier']) ? $rank['tier'] : self::$DEFAULT_TIER;
			if (self::compareTiers($userTier, $rules['min_tier']) < 0) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @internal Check if a user has passed at least one project-type test.
	 */
	private function _hasPassedAnyProjectTest($user_id){
		$clsQuizTest = new QuizTest();
		$clsAttempt  = new QuizTestAttempt();

		$projectTests = $clsQuizTest->getAll("`test_type`='project' AND `is_trash`='0'");
		foreach ($projectTests as $pt) {
			if ($clsAttempt->getBestScore($user_id, $pt['test_id']) >= $pt['pass_score']) {
				return true;
			}
		}
		return false;
	}
}
