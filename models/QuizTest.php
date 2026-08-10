<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/**
 * Model: QuizTest
 * Table: default_quiz_test
 *
 * Core model for the Qualification Engine.
 * Handles test metadata, URL generation, type labels, weights, and reference names.
 */
class QuizTest extends dbBasic {

	/** @var array Type labels (Vietnamese) */
	private static $TYPE_LABELS = array(
		'video'   => 'Video',
		'module'  => 'Module',
		'project' => 'Dự án',
	);

	/** @var array QS weights per test type */
	private static $TYPE_WEIGHTS = array(
		'video'   => 1,
		'module'  => 2,
		'project' => 3,
	);

	function __construct(){
		$this->pkey = "test_id";
		$this->tbl  = DB_PREFIX . "quiz_test";
	}

	/* ========== URL helpers ========== */

	function getLinkEdit($test_id){
		return "/trac-nghiem-test/edit/" . (int)$test_id;
	}

	function getLinkDetail($test_id, $one = null){
		if (empty($one['slug'])) {
			$one = $this->getOne($test_id);
		}
		return "/trac-nghiem-test/" . $one['slug'] . "-t" . (int)$test_id . ".html";
	}

	function getLinkTake($test_id){
		return "/trac-nghiem-test/take/" . (int)$test_id;
	}

	function getLinkResult($attempt_id){
		return "/trac-nghiem-test/result/" . (int)$attempt_id;
	}

	/* ========== Type helpers ========== */

	function getTypeLabel($test_type){
		return isset(self::$TYPE_LABELS[$test_type]) ? self::$TYPE_LABELS[$test_type] : $test_type;
	}

	function getWeight($test_type){
		return isset(self::$TYPE_WEIGHTS[$test_type]) ? self::$TYPE_WEIGHTS[$test_type] : 1;
	}

	/**
	 * Resolve human-readable reference name for a test row.
	 *
	 * @param  array $one  A single test record
	 * @return string
	 */
	function getRefName($one){
		switch ($one['test_type']) {
			case 'module':
				if (!empty($one['category_id'])) {
					$clsCat = new QuizTestCategory();
					return $clsCat->getTitle($one['category_id']);
				}
				break;
			case 'project':
				if (!empty($one['project_id'])) {
					$clsProject = new Project();
					return $clsProject->getTitle($one['project_id']);
				}
				break;
			case 'training':
				if (!empty($one['training_id'])) {
					$clsTraining = new Training();
					return $clsTraining->getTitle($one['training_id']);
				}
				break;
			case 'video':
				if (!empty($one['video_url']))  return 'Link video';
				if (!empty($one['video_file'])) return 'Video upload';
				return 'Video';
		}
		return '-';
	}

	/**
	 * Build a YouTube / direct-link video embed string.
	 *
	 * @param  array $one  A single test record
	 * @return string      HTML embed or empty
	 */
	function buildVideoEmbed($one){
		if ($one['test_type'] !== 'video') return '';

		$src = '';
		if ($one['video_source'] === 'link' && !empty($one['video_url'])) {
			$url = $one['video_url'];
			if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
				preg_match('/(?:v=|\/embed\/|\.be\/)([a-zA-Z0-9_-]+)/', $url, $m);
				if (!empty($m[1])) {
					return '<iframe width="100%" height="400" src="https://www.youtube.com/embed/' . $m[1] . '" frameborder="0" allowfullscreen></iframe>';
				}
			}
			$src = $url;
		} elseif ($one['video_source'] === 'upload' && !empty($one['video_file'])) {
			$src = $one['video_file'];
		}

		return $src ? '<video controls width="100%" height="400"><source src="' . $src . '"></video>' : '';
	}
}
