<?php 
	global $core, $clsISO, $smarty, $dbconn, $profile_id;
	$clsWorldcupMatch = new WorldcupMatch();
	$clsWorldcupTeam = new WorldcupTeam();
	$clsWorldcupScore = new WorldcupScore();
	$teamRows = $clsWorldcupTeam->getListActive();
	$teams = [];
	foreach ($teamRows as $key => $val) {
		$teams[$val["team_id"]] = $val;
	}
	$lstNextMatch = $clsWorldcupMatch->getAll("is_trash=0 AND status=0 AND `home_team_id` IN (".implode(',',array_keys($teams)).") AND `away_team_id` IN (".implode(',',array_keys($teams)).") ORDER BY kickoff ASC");
	$oneNextMatch = [];
	foreach($lstNextMatch as $key => $val) {
		$oneNextMatch[] = [
			"home"	=>	$teams[$val["home_team_id"]]["short"],
			"hc"	=>	$teams[$val["home_team_id"]]["code"],
			"away"	=>	$teams[$val["away_team_id"]]["short"],
			"ac"	=>	$teams[$val["away_team_id"]]["code"],
			"kickoff"	=>	date('c', $val["kickoff"])
		];
	}
	$smarty->assign("oneNextMatch",json_encode($oneNextMatch));
	$smarty->assign("clsWorldcupScore",$clsWorldcupScore);
?>