<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # Quiz Test – Qualification Engine                                  # ||
|| # Module : quiz_test                                                # ||
|| #################################################################### ||
\*======================================================================*/

/*──────────────────────────────────────────────────────────────────────
 * HELPER: Assign common SEO meta tags to the template.
 *─────────────────────────────────────────────────────────────────────*/
function _qt_assignMeta($title){
	global $assign_list, $title_page, $description_page, $keyword_page, $clsConfiguration;
	$title_page = $title . ' - ' . PAGE_NAME;
	$assign_list['title_page']       = $title_page;
	$assign_list['description_page'] = $clsConfiguration->getValue('meta_description');
	$assign_list['keyword_page']     = $clsConfiguration->getValue('meta_keyword');
}

/**
 * Helper: return JSON and halt execution.
 */
function _qt_json($data){
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($data);
	die();
}

/* ====================================================================
 * ACTION: default (manager) — Admin list all tests
 * URL: /trac-nghiem-test.html
 * ==================================================================*/
function default_default(){
	global $assign_list, $clsISO, $profile_id;

	$clsQuizTest     = new QuizTest();
	$clsQuizQuestion = new QuizTestQuestion();
	$clsPagination   = new Pagination();

	// Pagination
	$cond         = "`is_trash`='0'";
	$current_page = max(1, (int)Input::get('page', 1));
	$per_page     = 50;
	$offset       = ($current_page - 1) * $per_page;

	$total_record = $clsQuizTest->countItem($cond);
	$clsPagination->initianize(array(
		'total'           => $total_record,
		'current_page'    => $current_page,
		'number_per_page' => $per_page,
		'link'            => str_replace(".html", "/", $clsISO->getLink("quiz_test")),
	));
	$assign_list['html_pager'] = $clsPagination->create_links_page(1);

	// Fetch list and enrich
	$lstTest = $clsQuizTest->getAll($cond . " ORDER BY `created_at` DESC LIMIT {$offset},{$per_page}");
	foreach ($lstTest as &$row) {
		$row['total_question'] = $clsQuizQuestion->countByTestId($row['test_id']);
		$row['ref_name']       = $clsQuizTest->getRefName($row);
		$row['type_label']     = $clsQuizTest->getTypeLabel($row['test_type']);
	}
	unset($row);

	$assign_list['clsQuizTest'] = $clsQuizTest;
	$assign_list['lstTest']     = $lstTest;

	_qt_assignMeta('Bài test');
}

/* ====================================================================
 * ACTION: edit — Create / Edit a test
 * URL: /trac-nghiem-test/add  |  /trac-nghiem-test/edit/{id}
 * ==================================================================*/
function default_edit(){
	global $assign_list, $clsISO, $profile_id;

	$clsQuizTest     = new QuizTest();
	$clsQuizQuestion = new QuizTestQuestion();
	$clsQuizCategory = new QuizTestCategory();
	$clsProject      = new Project();
	$clsTraining      = new Training();
	$clsProfile      = new Profile();
	$clsGroupProfile = new GroupProfile();

	$test_id          = (int)Input::get('test_id', 0);
	$oneItem          = array();
	$questions        = array();
	$more_information = array();

	if ($test_id > 0) {
		$oneItem          = $clsQuizTest->getOne($test_id);
		$more_information = !empty($oneItem['more_information'])
			? $clsISO->to_array_json($oneItem['more_information'])
			: array();
		$questions = $clsQuizQuestion->getQuestionsWithAnswers($test_id);
	}

	// Dropdown data
	$selectedCat     = !empty($oneItem['category_id']) ? $oneItem['category_id'] : 0;
	$selectedProject = !empty($oneItem['project_id'])  ? $oneItem['project_id']  : 0;
	$selectedTraining = !empty($oneItem['training_id'])  ? $oneItem['training_id']  : 0;
//	$clsISO->print_pre($questions);die;
	$assign_list += array(
		'test_id'          => $test_id,
		'oneItem'          => $oneItem,
		'questions'        => $questions,
		'more_information' => $more_information,
		'categoryOptions'  => $clsQuizCategory->getSelectOptions($selectedCat),
		'projectOptions'   => $clsProject->getSelectOptions($selectedProject),
		'trainingOptions'   => $clsTraining->getSelectOptions($selectedTraining),
		'list_profiles'    => $clsProfile->getAll("`is_trash`='0' AND `is_active`='1'", "profile_id, full_name"),
		'lstGroupProfile'  => $clsGroupProfile->getAll("`is_trash`='0'"),
		'clsQuizTest'      => $clsQuizTest,
		'clsISO'           => $clsISO,
		'clsProfile'       => $clsProfile,
		'clsGroupProfile'  => $clsGroupProfile,
	);	

	_qt_assignMeta($test_id > 0 ? 'Sửa bài test' : 'Tạo bài test');
}

/* ====================================================================
 * ACTION: saveTest — AJAX save test (create / update)
 * ==================================================================*/
function default_saveTest(){
	global $core, $dbconn, $clsISO, $profile_id;

	$clsQuizTest     = new QuizTest();
	$clsQuizQuestion = new QuizTestQuestion();
	$clsQuizAnswer   = new QuizTestAnswer();

	$test_id = (int)Input::post('test_id', 0);
	$title   = trim(Input::post('title', ''));

	if (empty($title)) {
		_qt_json(array('result' => false, 'msg' => 'Tiêu đề không được để trống'));
	}

	// ── Collect form inputs ──
	$test_type    = Input::post('test_type', 'video');
	$intro        = Input::post('intro', '');
	$category_id  = (int)Input::post('category_id', 0);
	$training_id  = (int)Input::post('training_id', 0);
	$project_id   = (int)Input::post('project_id', 0);
	$video_source = Input::post('video_source', 'link');
	$video_url    = Input::post('video_url', '');
	$video_file   = Input::post('video_file', '');
	$duration     = (int)Input::post('duration', 0);
	$pass_score   = (int)Input::post('pass_score', 50);
	$score        = (int)Input::post('score', 10);
	$questions    = Input::post('questions', array());

	// Target audience
	$is_all_staff          = (int)Input::post('is_all_staff', 1);
	$list_department_id    = Input::post('list_department_id', array());
	$list_profile_id       = Input::post('list_profile_id', array());
	$list_group_profile_id = Input::post('list_group_profile_id', array());
	$submission_count      = (int)Input::post('submission_count', 0);

	// Time-setting toggles
	$is_duration     = (int)Input::post('is_duration', 0);
	$is_start_date   = (int)Input::post('is_start_date', 0);
	$is_end_date     = (int)Input::post('is_end_date', 0);
	$is_split_points = (int)Input::post('is_split_points', 0);
	$start_date      = Input::post('start_date', '');
	$end_date        = Input::post('end_date', '');
	$more_information = json_encode(compact(
		'is_duration', 'is_start_date', 'is_end_date', 'is_split_points'
	));
	// ── Build field array ──
	$isVideo   = ($test_type === 'video');
	$isTraining  = ($test_type === 'training');
	$isModule  = ($test_type === 'module');
	$isProject = ($test_type === 'project');
	$arr_field = array(
		'title'                 => addslashes($title),
		'slug'                  => $core->replaceSpace($title),
		'intro'                 => addslashes($intro),
		'test_type'             => $test_type,
		'category_id'           => $isModule  ? $category_id  : 0,
		'training_id'           => $isTraining  ? $training_id  : 0,
		'project_id'            => $isProject ? $project_id   : 0,
		'video_source'          => $isVideo   ? $video_source : 'link',
		'video_url'             => $isVideo   ? $video_url    : '',
		'video_file'            => $isVideo   ? $video_file   : '',
		'is_all_staff'          => $is_all_staff,
		'list_department_id'    => $clsISO->makeSlashListFromArrayRoot($list_department_id),
		'list_profile_id'       => $clsISO->makeSlashListFromArrayRoot($list_profile_id),
		'list_group_profile_id' => $clsISO->makeSlashListFromArrayRoot($list_group_profile_id),
		'duration'              => $is_duration   ? $duration : 0,
		'start_date'            => ($is_start_date && $start_date) ? strtotime($start_date) : 0,
		'end_date'              => ($is_end_date   && $end_date)   ? strtotime($end_date)   : 0,
		'pass_score'            => $pass_score,
		'score'                 => $score,
		'submission_count'      => $submission_count,
		'more_information'      => $more_information,
		'updated_at'            => time(),
	);

	// ── Insert or Update ──
	if ($test_id === 0) {
		$test_id = $clsQuizTest->getMaxID();
		$arr_field['test_id']    = $test_id;
		$arr_field['created_at'] = time();
		$arr_field['is_trash']   = 0;
		$clsQuizTest->insert($arr_field);
	} else {
		$clsQuizTest->updateOne($test_id, $arr_field);
		$clsQuizQuestion->deleteByTestId($test_id);  // cascade-deletes answers too
	}

	// ── Save questions & answers ──
	if (!empty($questions)) {
		$order = 0;
		foreach ($questions as $qi => $q) {
			$q_id = $clsQuizQuestion->getMaxID();
			$questionType = isset($q['question_type']) ? (int)$q['question_type'] : 1;
			$clsQuizQuestion->insert(array(
				'question_id'   => $q_id,
				'test_id'       => $test_id,
				'question_type'       => $questionType,
				'question_text' => addslashes($q['title']),
				'order_no'      => ++$order,
			));

			if (empty($q['options'])) continue;
			foreach ($q['options'] as $oi => $opt) {
				if ($questionType == 2) {
					$is_correct = (!empty($q['correct']) && $clsISO->checkItemInArray($oi,$q['correct'])) ? 1 : 0;
				} else {
					$is_correct = (isset($q['correct']) && $q['correct'] == $oi) ? 1 : 0;
				}
				$clsQuizAnswer->insert(array(
					'answer_id'   => $clsQuizAnswer->getMaxID(),
					'question_id' => $q_id,
					'answer_text' => addslashes($opt['text']),
					'is_correct'  => $is_correct,
				));
			}
		}
	}

	_qt_json(array('result' => true, 'url' => '/trac-nghiem-test.html'));
}

/* ====================================================================
 * ACTION: deleteTest — AJAX soft-delete
 * ==================================================================*/
function default_deleteTest(){
	$clsQuizTest = new QuizTest();
	$test_id     = (int)Input::post('test_id', 0);
	$ok          = ($test_id > 0) && $clsQuizTest->updateOne($test_id, array('is_trash' => 1));
	_qt_json(array('result' => (bool)$ok));
}

/* ====================================================================
 * ACTION: addCategory — AJAX add new Module-type category
 * ==================================================================*/
function default_addCategory(){
	global $core;

	$clsCategory = new QuizTestCategory();
	$title       = trim(Input::post('title', ''));

	if (empty($title)) {
		_qt_json(array('result' => false));
	}

	$cat_id = $clsCategory->getMaxID();
	$clsCategory->insert(array(
		'category_id' => $cat_id,
		'title'       => addslashes($title),
		'slug'        => $core->replaceSpace($title),
		'order_no'    => 0,
		'is_trash'    => 0,
	));

	_qt_json(array('result' => true, 'category_id' => $cat_id, 'title' => $title));
}

/* ====================================================================
 * ACTION: my_test — User's test list & detail
 * URL: /trac-nghiem-test/me  |  /trac-nghiem-test/{slug}-t{id}.html
 * ==================================================================*/
function default_my_test(){
	global $assign_list, $profile_id, $oneProfile, $clsISO;

	$clsQuizTest     = new QuizTest();
	$clsQuestion     = new QuizTestQuestion();
	$clsAttempt      = new QuizTestAttempt();
	$clsGroupProfile = new GroupProfile();

	$cmd     = Input::get('cmd', '_list');
	$test_id = (int)Input::get('test_id', 0);

	if ($cmd === '_detail' && $test_id > 0) {
		// ── Detail view ──
		$oneItem = $clsQuizTest->getOne($test_id);
		if (!empty($oneItem)) {
			$oneItem['total_question'] = $clsQuestion->countByTestId($test_id);
			$oneItem['ref_name']       = $clsQuizTest->getRefName($oneItem);
			$oneItem['type_label']     = $clsQuizTest->getTypeLabel($oneItem['test_type']);

			$assign_list['oneItem']     = $oneItem;
			$assign_list['lastAttempt'] = QuizTestAttempt::enrich(
				$clsAttempt->getLastAttempt($profile_id, $test_id),
				$oneItem['pass_score']
			);
		}
		$pageTitle = (!empty($oneItem['title']) ? $oneItem['title'] . ' - ' : '') . 'Bài test';
	} else {
		// ── List view: Build permission condition ──
		$now  = time();
		$cond = "`is_trash`='0'";

		// Permission: Target audience filter
		$cond .= " AND ((`is_all_staff` = '1')";

		// is_all_staff = 2: specific departments or profiles
		$lstDepartmentId = !empty($oneProfile['list_department_id'])
			? $clsISO->getArrayByTextSlash($oneProfile['list_department_id'])
			: array();
		// Also include the primary department_id
		if (!empty($oneProfile['department_id'])) {
			$lstDepartmentId[] = $oneProfile['department_id'];
			$lstDepartmentId   = array_unique($lstDepartmentId);
		}

		$deptCond = "`list_profile_id` LIKE '%|{$profile_id}|%'";
		foreach ($lstDepartmentId as $dept_id) {
			$dept_id  = (int)$dept_id;
			$deptCond .= " OR `list_department_id` LIKE '%|{$dept_id}|%'";
		}
		$cond .= " OR (`is_all_staff`='2' AND ({$deptCond}))";

		// is_all_staff = 3: specific employee groups
		$list_profile_groups = $clsGroupProfile->getAll(
			"`list_profile_id` LIKE '%|{$profile_id}|%'",
			$clsGroupProfile->pkey
		);
		if (!empty($list_profile_groups)) {
			$groupConds = array();
			foreach ($list_profile_groups as $gp) {
				$gid = (int)$gp[$clsGroupProfile->pkey];
				$groupConds[] = "`list_group_profile_id` LIKE '%|{$gid}|%'";
			}
			$cond .= " OR (`is_all_staff`='3' AND (" . implode(' OR ', $groupConds) . "))";
		}
		$cond .= ")";

		// Time window filter: exclude tests outside their configured window
		// start_date > 0 means test hasn't opened yet if now < start_date
		// end_date > 0 means test has expired if now > end_date
		$cond .= " AND (`start_date` = '0' OR `start_date` <= '{$now}')";
		$cond .= " AND (`end_date`   = '0' OR `end_date`   >= '{$now}')";

		$cond .= " ORDER BY `created_at` DESC";

		$lstTest    = $clsQuizTest->getAll($cond);
		$attemptMap = array();
		$clsProfile  = new Profile();
		$clsProperty = new Property();

		foreach ($lstTest as &$row) {
			$row['total_question'] = $clsQuestion->countByTestId($row['test_id']);
			$row['ref_name']       = $clsQuizTest->getRefName($row);

			$attemptMap[$row['test_id']] = QuizTestAttempt::enrich(
				$clsAttempt->getLastAttempt($profile_id, $row['test_id']),
				$row['pass_score']
			);

			// Count eligible participants
			$cond_profile = "";
			if ($row['is_all_staff'] == 2) {
				$arr_dept = !empty($row['list_department_id'])
					? $clsISO->getArrayByTextSlash($row['list_department_id']) : array();
				foreach ($arr_dept as $dept) {
					$cond_profile .= ($cond_profile ? " OR " : "") .
						" department_id='{$dept}' OR list_department_id LIKE '%|{$dept}|%'";
				}
				$arr_prof = !empty($row['list_profile_id'])
					? $clsISO->getArrayByTextSlash($row['list_profile_id']) : array();
				foreach ($arr_prof as $pid) {
					$cond_profile .= ($cond_profile ? " OR " : "") . " profile_id='{$pid}'";
				}
				$cond_profile = $cond_profile ? " AND ({$cond_profile})" : "";
			} elseif ($row['is_all_staff'] == 3) {
				$arr_gid = !empty($row['list_group_profile_id'])
					? $clsISO->getArrayByTextSlash($row['list_group_profile_id']) : array();
				if (!empty($arr_gid)) {
					$lstGP = $clsGroupProfile->getAll(
						"`is_trash`='0' AND `group_profile_id` IN (" . implode(',', $arr_gid) . ")",
						"list_profile_id"
					);
					$allPids = array();
					foreach ($lstGP as $gp) {
						$allPids = array_merge($allPids, $clsISO->getArrayByTextSlash($gp['list_profile_id']));
					}
					$allPids = array_unique(array_filter($allPids));
					if (!empty($allPids)) {
						$cond_profile = " AND profile_id IN ('" . implode("','", $allPids) . "')";
					}
				}
			}
			$row['total_profile'] = $clsProfile->countItem(
				"`is_trash`='0' AND `is_active`='1'" . $cond_profile
			);
		}
		unset($row);

		$assign_list['lstTest']    = $lstTest;
		$assign_list['attemptMap'] = $attemptMap;
		$pageTitle = 'Bài test của tôi';
	}

	$assign_list['cmd']         = $cmd;
	$assign_list['clsQuizTest'] = $clsQuizTest;
	$assign_list['clsISO']      = $clsISO;
	_qt_assignMeta($pageTitle);
}

/* ====================================================================
 * ACTION: take — Take a test
 * URL: /trac-nghiem-test/take/{test_id}
 * ==================================================================*/
function default_take(){
	global $assign_list, $profile_id, $oneProfile, $clsISO;

	$clsQuizTest     = new QuizTest();
	$clsQuestion     = new QuizTestQuestion();
	$clsAnswer       = new QuizTestAnswer();
	$clsGroupProfile = new GroupProfile();

	$test_id = (int)Input::get('test_id', 0);
	$oneItem = $clsQuizTest->getOne($test_id);

	if (empty($oneItem) || $oneItem['is_trash'] == 1) {
		$assign_list['error'] = 'Bài test không tồn tại.';
		return;
	}

	// ── Permission gate: check target audience ──
	$allowed = false;
	$staffType = (int)$oneItem['is_all_staff'];

	if ($staffType === 1) {
		$allowed = true;
	} elseif ($staffType === 2) {
		// Check if user is in list_profile_id
		if (strpos($oneItem['list_profile_id'], '|' . $profile_id . '|') !== false) {
			$allowed = true;
		}
		// Check if user's department is in list_department_id
		if (!$allowed) {
			$lstDeptUser = !empty($oneProfile['list_department_id'])
				? $clsISO->getArrayByTextSlash($oneProfile['list_department_id'])
				: array();
			if (!empty($oneProfile['department_id'])) {
				$lstDeptUser[] = $oneProfile['department_id'];
			}
			foreach ($lstDeptUser as $d) {
				if (strpos($oneItem['list_department_id'], '|' . $d . '|') !== false) {
					$allowed = true;
					break;
				}
			}
		}
	} elseif ($staffType === 3) {
		$userGroups = $clsGroupProfile->getAll(
			"`list_profile_id` LIKE '%|{$profile_id}|%'",
			$clsGroupProfile->pkey
		);
		foreach ($userGroups as $ug) {
			$gid = $ug[$clsGroupProfile->pkey];
			if (strpos($oneItem['list_group_profile_id'], '|' . $gid . '|') !== false) {
				$allowed = true;
				break;
			}
		}
	}

	if (!$allowed) {
		$assign_list['error'] = 'Bạn không có quyền làm bài test này.';
		return;
	}

	// ── Time window gate ──
	$now = time();
	if (!empty($oneItem['start_date']) && $oneItem['start_date'] > 0 && $now < $oneItem['start_date']) {
		$assign_list['error'] = 'Bài test chưa đến thời gian bắt đầu.';
		return;
	}
	if (!empty($oneItem['end_date']) && $oneItem['end_date'] > 0 && $now > $oneItem['end_date']) {
		$assign_list['error'] = 'Bài test đã hết hạn.';
		return;
	}

	// ── Training type: load lessons ──
	if($oneItem["test_type"] == "training" && !empty($oneItem["training_id"])) {
		$clsTraining = new Training();
		$oneTrainng = $clsTraining->getOne($oneItem["training_id"]);
		$lstLesson = $clsISO->to_array_json($oneTrainng['lesson']);
		$assign_list['training_id'] = $oneItem["training_id"];
		$assign_list['lstLesson'] = $lstLesson;
	}

	// Build question list for the test-taking UI
	$rawQuestions = $clsQuestion->getByTestId($test_id);
	$questions    = array();
	foreach ($rawQuestions as $q) {
		$answers = $clsAnswer->getByQuestionId($q['question_id']);
		$opts    = array();
		foreach ($answers as $a) {
			$opts[] = array('answer_id' => $a['answer_id'], 'text' => $a['answer_text']);
		}
		$questions[] = array(
			'question_id' => $q['question_id'],
			'title'       => $q['question_text'],
			'type'        => $clsAnswer->isMultiChoice($q['question_id']) ? 'multi' : 'single',
			'options'     => $opts,
		);
	}

	$assign_list['oneItem']    = $oneItem;
	$assign_list['questions']  = $questions;
	$assign_list['videoEmbed'] = $clsQuizTest->buildVideoEmbed($oneItem);

	_qt_assignMeta($oneItem['title'] . ' - Làm bài test');
}

/* ====================================================================
 * ACTION: submitTest — AJAX submit answers, score, recalculate QS
 * ==================================================================*/
function default_submitTest(){
	global $profile_id;

	$clsQuizTest = new QuizTest();
	$clsQuestion = new QuizTestQuestion();
	$clsAnswer   = new QuizTestAnswer();
	$clsAttempt  = new QuizTestAttempt();
	$clsRanking  = new QuizTestRanking();

	$test_id       = (int)Input::post('test_id', 0);
	$result_data   = Input::post('result', array());
	$duration_spent = (int)Input::post('duration_spent', 0);

	$oneItem = $clsQuizTest->getOne($test_id);
	if (empty($oneItem)) {
		_qt_json(array('result' => false));
	}

	// ── Score calculation ──
	$lstQuestions  = $clsQuestion->getByTestId($test_id);
	$totalQ        = count($lstQuestions);
	$correctCount  = 0;

	foreach ($lstQuestions as $q) {
		$correctIds = $clsAnswer->getCorrectIds($q['question_id']);
		$userAnswer = isset($result_data[$q['question_id']]) ? $result_data[$q['question_id']] : null;

		if (is_array($userAnswer)) {
			sort($userAnswer);
			sort($correctIds);
			if ($userAnswer == $correctIds) $correctCount++;
		} else {
			if (in_array($userAnswer, $correctIds)) $correctCount++;
		}
	}

	$score   = ($totalQ > 0) ? round(($correctCount / $totalQ) * 100, 2) : 0;
	$is_pass = ($score >= $oneItem['pass_score']) ? 1 : 0;

	// ── Save attempt ──
	$attempt_id = $clsAttempt->getMaxID();
	$clsAttempt->insert(array(
		'attempt_id'     => $attempt_id,
		'user_id'        => $profile_id,
		'test_id'        => $test_id,
		'score'          => $score,
		'duration_spent' => $duration_spent,
		'finished_at'    => time(),
	));

	// ── Recalculate QS & tier ──
	$rankResult = $clsRanking->recalculate($profile_id);

	_qt_json(array(
		'result'     => true,
		'score'      => $score,
		'is_pass'    => $is_pass,
		'correct'    => $correctCount,
		'total'      => $totalQ,
		'qs'         => $rankResult['qs'],
		'tier'       => $rankResult['tier'],
		'attempt_id' => $attempt_id,
		'url'        => $clsQuizTest->getLinkResult($attempt_id),
	));
}

/* ====================================================================
 * ACTION: result — View result of an attempt
 * URL: /trac-nghiem-test/result/{attempt_id}
 * ==================================================================*/
function default_result(){
	global $assign_list, $profile_id;

	$clsQuizTest = new QuizTest();
	$clsQuestion = new QuizTestQuestion();
	$clsAnswer   = new QuizTestAnswer();
	$clsAttempt  = new QuizTestAttempt();

	$attempt_id = (int)Input::get('attempt_id', 0);
	$attempt    = $clsAttempt->getOne($attempt_id);

	if (empty($attempt)) {
		$assign_list['error'] = 'Không tìm thấy kết quả.';
		return;
	}

	$oneItem = $clsQuizTest->getOne($attempt['test_id']);
	$attempt = QuizTestAttempt::enrich($attempt, $oneItem['pass_score']);
	$attempt['pass_score'] = $oneItem['pass_score'];

	// Build review list
	$rawQuestions = $clsQuestion->getByTestId($attempt['test_id']);
	$questions    = array();
	foreach ($rawQuestions as $q) {
		$answers = $clsAnswer->getByQuestionId($q['question_id']);
		$opts    = array();
		foreach ($answers as $a) {
			$opts[] = array(
				'answer_id'  => $a['answer_id'],
				'text'       => $a['answer_text'],
				'is_correct' => $a['is_correct'],
			);
		}
		$questions[] = array(
			'question_id' => $q['question_id'],
			'title'       => $q['question_text'],
			'type'        => $clsAnswer->isMultiChoice($q['question_id']) ? 'multi' : 'single',
			'options'     => $opts,
		);
	}

	$assign_list['oneItem']     = $oneItem;
	$assign_list['attempt']     = $attempt;
	$assign_list['questions']   = $questions;
	$assign_list['clsQuizTest'] = $clsQuizTest;

	_qt_assignMeta('Kết quả - ' . $oneItem['title']);
}

/* ====================================================================
 * ACTION: ranking — Leaderboard
 * ==================================================================*/
function default_ranking(){
	global $assign_list;

	$clsRanking = new QuizTestRanking();
	$clsProfile = new Profile();

	$lstRanking = $clsRanking->getAll("1=1 ORDER BY `qualification_score` DESC LIMIT 100");
	foreach ($lstRanking as &$row) {
		$row['full_name'] = $clsProfile->getFullName($row['user_id']);
	}
	unset($row);

	$assign_list['lstRanking']  = $lstRanking;
	$assign_list['clsRanking']  = $clsRanking;
	_qt_assignMeta('Bảng xếp hạng');
}

/* ====================================================================
 * ACTION: uploadVideo — AJAX upload video file
 * ==================================================================*/
function default_uploadVideo(){
	global $clsISO;
	if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['video_file']['name'])) {
		echo '_error'; die();
	}
	$video = $_FILES['video_file'];
	if ($video['size'] > 104857600) { // 100 MB
		echo '_limit_size'; die();
	}
	$clsUpload = new UploadFile();
	$uploaded_url = "";
	if(@is_uploaded_file($_FILES['video_file']['tmp_name'])){
		$clsUploadFile = new UploadFile();
		$upload_file = $clsUploadFile->uploadItem($_FILES["video_file"],"/QuizTest",EXTENSION_FILE_UPLOAD);
		$clsISO->print_pre($upload_file);die;
		if(!empty($upload_file)) {
			$file_name = $_FILES['video_file']['name'];
			$file_size = $_FILES['video_file']['size'];
			// Upload file to google drive
			$folder_id = GOOGLE_DRIVE_FOLDER_VIDEO_ID;
			$clsGoogleUpload = new GoogleUpload($folder_id, true);
			$createdFile = $clsGoogleUpload->upload($file_name,$mimeType,ROOTPATH.$upload_file,$folder_id);
			$uploaded_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
			$uploaded_url = $clsISO->genGoogleURL($createdFile->getId());
			@unlink(ROOTPATH . $upload_file);
		}
		
	}
//	$path = $clsUpload->uploadItem($video, '/QuizTest', 'mp4,webm,ogg,mov', array('resize' => false));

	echo (!empty($uploaded_url) && @file_exists(ABSPATH . $uploaded_url))
		? '_success|||' . $uploaded_url
		: '_error';
	die();
}

/* ====================================================================
 * ACTION: importExcel — AJAX upload & parse Excel for questions
 * ==================================================================*/
function default_importExcel(){
	global $core;
	
	if(empty($_FILES['upload_excel']['tmp_name'])){
		_qt_json(array('result' => false, 'msg' => 'Vui lòng chọn file'));
	}

	$target_file = ROOTPATH."/tmp/" . time() . "_" . uniqid() . ".xlsx";
	if (move_uploaded_file($_FILES["upload_excel"]["tmp_name"], $target_file)) {
		require_once DIR_INCLUDES."/phpexcel/PHPExcel.php";
		require_once DIR_INCLUDES."/phpexcel/PHPExcel/IOFactory.php";
		
		try {
			$objPHPExcel = PHPExcel_IOFactory::load($target_file);
			$worksheet = $objPHPExcel->getActiveSheet();
			$highestRow = $worksheet->getHighestRow();
			
			$questions = array();
			$alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
			
			for($row = 2; $row <= $highestRow; $row++) {
				$titleCell = $worksheet->getCellByColumnAndRow(2, $row);
				$title = trim($titleCell->getValue());
				if(empty($title)) continue;
				
				$correctStr = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
				$correctArr = array_map('trim', explode(',', $correctStr));
				
				$opts = array();
				for($col = 4; $col <= 13; $col++) {
					$optCell = $worksheet->getCellByColumnAndRow($col, $row);
					$optText = trim($optCell->getValue());
					if(!empty($optText)) {
						$optLetter = $alphabet[$col - 4];
						$opts[] = array(
							'text' => $optText,
							'is_correct' => in_array($optLetter, $correctArr) ? 1 : 0
						);
					}
				}
				
				if(!empty($opts)) {
					$questions[] = array(
						'title'   => $title,
						'type'    => count($correctArr) > 1 ? 2 : 1,
						'options' => $opts
					);
				}
			}
			
			@unlink($target_file);
			_qt_json(array('result' => true, 'questions' => $questions));
		} catch(Exception $e) {
			@unlink($target_file);
			_qt_json(array('result' => false, 'msg' => $e->getMessage()));
		}
	}
	_qt_json(array('result' => false, 'msg' => 'Upload thất bại'));
}
