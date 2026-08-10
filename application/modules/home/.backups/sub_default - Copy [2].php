<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is Â©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
function default_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile;
	$clsPost = new Post();
	$clsProfile = new Profile(); 
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$assign_list['clsPost'] = $clsPost;
	#
	$department_fix = ' công ty';
	$cond = "`is_trash`=0";
	if($clsISO->checkPermissionGroup('DIRECTOR')){ 
		// GD
	} else if($clsISO->checkPermissionGroup('BO')){
		$department_fix = '';
		$cond .= " and `staff_id`='{$profile_id}'";
	} else if($clsISO->checkPermissionGroup('SALE')) {
		$role_id = $oneProfile['role_id'];
		$department_id = $oneProfile['department_id'];
		$department_fix = sprintf(' %', $clsProperty->getTitle($department_id));
		$arrRoles = array();
		$clsProperty->getChilds($role_id, $arrRoles);
		if(!empty($arrRoles)){
			$arrRoles[] = $role_id;
			$cond.= " and `staff_id` in(
				select `profile_id` from ".$clsProfile->tbl." 
				where `department_id`='{$department_id}' and `role_id` in (".implode(',', $arrRoles).")
			)";
		}
	} else {
		$department_fix = ' cá»§a bản';
		$cond .= " and `staff_id`='{$profile_id}'";
	}
	$current_year = date('Y');
	$current_month = date('n');
	if($current_month==1){
		$prev_month = 12;
		$prev_year = ($current_year - 1);
	} else {
		$prev_year = $current_year;
		$prev_month = $current_month-1;
	}
	$assign_list['department_fix'] = $department_fix;
	$assign_list['current_year'] = $current_year;
	$assign_list['current_month'] = $current_month;
	$assign_list['prev_year'] = $prev_year;
	$assign_list['prev_month'] = $prev_month;
	###
	$scriptMonthJs = '<script type="text/javascript">
		var list_month_name = [';
	$scriptJs = '<script type="text/javascript">
		var current_year = '.$current_year.',
			data_revenue_charts = [';
	for($i=1; $i<=12; $i++){
		$scriptMonthJs.= '\'T'.$i.'\''.($i<12?',':'');
		$m = sprintf('%s/%s', $clsISO->parseNumber($i), $current_year);
		$total_price = $clsBilling->sumItem("totalgrand", "{$cond} and FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$m}'");
		$scriptJs.= $total_price.($i<12?',':'');
	}
	$scriptJs.= ']; </script>';
	$scriptMonthJs.= ']; </script>';
	$assign_list['scriptJs'] = $scriptJs;
	$assign_list['scriptMonthJs'] = $scriptMonthJs;
	//$clsISO->print_pre($scriptJs); die();
	$clsNews = new News();
	$assign_list['clsNews'] = $clsNews;
	$lstHomeGratitude = $clsNews->getAll("is_trash=0 and cat_id = '"._NEWS_GRATITUDE_CAT_ID."' order by reg_date DESC limit 0,3");
	if(!empty($lstHomeGratitude)){
		$arr_profile_cached = array();
		foreach($lstHomeGratitude as $key => $val){
			$user_id = $val['user_id'];
			if(isset($arr_profile_cached[$user_id])){
				$db_profile = $arr_profile_cached[$user_id];
			} else {
				$db_profile = $clsProfile->getProfile($user_id);
				$arr_profile_cached[$user_id] = $db_profile;
			}
			$lstHomeGratitude[$key]['db_profile'] = $db_profile;
			#
			$total_liked = 0;
			$liked_json = !empty($val['liked_json']) 
				? json_decode(html_entity_decode($val['liked_json'])) 
				: array();
			if(!empty($liked_json)){
				foreach($liked_json as $okey => $ids){
					if(is_array($ids) && !empty($ids)){
						$total_liked+= count($ids);
					}
				}
			}
			$lstHomeGratitude[$key]['total_liked'] = $total_liked;
			$status_liked = $clsNews->checkLiked($val[$clsNews->pkey], $val);
			$lstHomeGratitude[$key]['status_liked'] = $status_liked;
			$total_comments = $clsNews->getTotalComment($val[$clsNews->pkey]);
			$lstHomeGratitude[$key]['total_comments'] = $total_comments;
			$lstHomeGratitude[$key]['total_actions'] = $total_comments + $total_liked;
			
			$more_information = !empty($val['more_informatiion']) ? $clsISO->to_array_json($val['more_informatiion']) : [];
			$scoreDep = !empty($more_information['scoreDep']) ? $more_information['scoreDep'] : [];
			$scoreEmp = !empty($more_information['scoreEmp']) ? $more_information['scoreEmp'] : [];
			if(!empty($scoreDep)) {
				$lstDepartment = $clsProperty->getAllCache("property_id IN (".implode(",",array_keys($scoreDep)).")",$clsProperty->pkey.",title");
				$lstHomeGratitude[$key]['department'] = $lstDepartment;
			}else{
				$lstHomeGratitude[$key]['department'] = [];
			}
			if(!empty($scoreEmp)) {
				$lstStaff = $clsProfile->getAll("profile_id IN (".implode(",",array_keys($scoreEmp)).")",$clsProfile->pkey.',full_name');
				$lstHomeGratitude[$key]['staff'] = $lstStaff;
			}else{
				$lstHomeGratitude[$key]['staff'] = [];
			}
		}
	}
	$assign_list['lstHomeGratitude'] = $lstHomeGratitude;
	$condGratitude = "";
	if(!$clsISO->checkDev()){
		$condGratitude = " AND cat_id <> "._NEWS_GRATITUDE_CAT_ID;
	}
	$lstHomeNews = $clsNews->getAll("is_trash=0 and is_online=1".$condGratitude." order by reg_date DESC limit 0,3");
	if(!empty($lstHomeNews)){
		$arr_profile_cached = array();
		foreach($lstHomeNews as $key => $val){
			$user_id = $val['user_id'];
			if(isset($arr_profile_cached[$user_id])){
				$db_profile = $arr_profile_cached[$user_id];
			} else {
				$db_profile = $clsProfile->getProfile($user_id);
				$arr_profile_cached[$user_id] = $db_profile;
			}
			$lstHomeNews[$key]['db_profile'] = $db_profile;
			#
			$total_liked = 0;
			$liked_json = !empty($val['liked_json']) 
				? json_decode(html_entity_decode($val['liked_json'])) 
				: array();
			if(!empty($liked_json)){
				foreach($liked_json as $okey => $ids){
					if(is_array($ids) && !empty($ids)){
						$total_liked+= count($ids);
					}
				}
			}
			$lstHomeNews[$key]['total_liked'] = $total_liked;
			$status_liked = $clsNews->checkLiked($val[$clsNews->pkey], $val);
			$lstHomeNews[$key]['status_liked'] = $status_liked;
			$total_comments = $clsNews->getTotalComment($val[$clsNews->pkey]);
			$lstHomeNews[$key]['total_comments'] = $total_comments;
			$lstHomeNews[$key]['total_actions'] = $total_comments + $total_liked;
			
			if($val['cat_id'] == _NEWS_GRATITUDE_CAT_ID){
				$more_information = !empty($val['more_informatiion']) ? $clsISO->to_array_json($val['more_informatiion']) : [];
				$department_id = !empty($more_information['department_id']) ? $more_information['department_id'] : [];
				$staff_id = !empty($more_information['staff_id']) ? $more_information['staff_id'] : [];
				if(!empty($department_id)) {
					$lstDepartment = $clsProperty->getAllCache("property_id IN (".implode(",",$department_id).")",$clsProperty->pkey.",title");
					$lstHomeNews[$key]['department'] = $lstDepartment;
				}else{
					$lstHomeNews[$key]['department'] = [];
				}
				if(!empty($staff_id)) {
					$lstStaff = $clsProfile->getAll("profile_id IN (".implode(",",$staff_id).")",$clsProfile->pkey.',full_name');
					$lstHomeNews[$key]['staff'] = $lstStaff;
				}else{
					$lstHomeNews[$key]['staff'] = [];
				}
			}
		}
	}
	$assign_list['lstHomeNews'] = $lstHomeNews;
	#- Tổng số giao dịch
	$clsBilling = new Billing();
	$total_transactions = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and FROM_UNIXTIME(`deposit_date`,'%Y')='".date('Y')."'");
	$smarty->assign('total_transactions', $total_transactions);	
	#
	$list_department_id = $oneProfile['list_department_id'];
	$arr_department_ids = !empty($list_department_id) ? $clsISO->getArrayByTextSlash($list_department_id) : array();
	$checkAddEvent = $clsISO->checkPermission("create_course");
	$assign_list["checkAddEvent"] = $checkAddEvent;
    /*=============Title & Description Page==================*/
	$title_page = $clsConfiguration->getValue('meta_title');
	$assign_list["title_page"] = $title_page;
	$description_page = $clsConfiguration->getValue('meta_description');
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function default_open_filter(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$uid = Input::post('uid');
	$holderG = Input::post('holderG');
	$smarty->assign('uid', $uid);
	$smarty->assign('holderG', $holderG);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.filter.tpl');
	echo $html; die();
}
function default_dashboard(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsStock = new Stock();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	###
	$tp = Input::get('tp');
	$holderG = Input::post('holderG', '_month');
	$year = (int) Input::post('year', date('Y'));
	if($holderG=='_month'){
		$month = (int) Input::post('month', 0);
		if($month > 0 && $year > 0){
			$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		}
	}
	$cond = "`is_trash`=0 and `is_cancel`=0";
	if($clsISO->checkPermissionGroup('DIRECTOR')){
		// GD
	} else if($clsISO->checkPermissionGroup('SALE')) {
		$role_id = $oneProfile['role_id'];
		$department_id = $oneProfile['department_id'];
		$arrRoles = array();
		$clsProperty->getChilds($role_id, $arrRoles);
		if(!empty($arrRoles)){
			$arrRoles[] = $role_id;
			$cond.= " and `staff_id` in(
				select `profile_id` from ".$clsProfile->tbl." 
				where (`department_id`='{$department_id}' 
					or `list_department_id` like '%|{$department_id}|%'
				) and `role_id` in (".implode(',', $arrRoles).")
			)";
		} else {
			$cond .= " and `staff_id`='{$profile_id}'";
		}
	} else {
		$cond .= " and `staff_id`='{$profile_id}'";
	}
	$html = $callback = '';
	if($tp=='total'){
		$total = $clsBilling->countItem($cond." and FROM_UNIXTIME(`deposit_date`,'%Y')='".$year."'");
		$total_prev = $clsBilling->countItem($cond." and FROM_UNIXTIME(`deposit_date`,'%Y')='".($year-1)."'");
		$growth = 'gray'; $symbol = '~'; $percent = 0;
		if($total > $total_prev){
			$diff = $total - $total_prev;
			if($total_prev == 0){
				$percent = 100;
			} else {
				$percent = ($diff/$total_prev) * 100;
			}
			$growth = 'success';
			$symbol = '+';
		} else if($total < $total_prev){
			$diff = $total_prev - $total;
			$percent = ($diff/$total_prev) * 100;
			$growth = 'danger';
			$symbol = '-'; 
		}
		$html.= '<h3 class="card-title mb-2">'.$total.'</h3>
		<!-- <small class="text-'.$growth.' fw-semibold">
			<i class="bx bx-up-arrow-alt"></i> '.$symbol.$percent.'%
		</small> -->';
	} else if($tp=='revenue'){
		$total = $clsBilling->sumItem("totalgrand", $cond." and FROM_UNIXTIME(`deposit_date`,'%Y')='".$year."'");
		$total_prev = $clsBilling->sumItem("totalgrand", $cond." and FROM_UNIXTIME(`deposit_date`,'%Y')='".($year-1)."'");
		$growth = 'gray'; $symbol = '~'; $percent = 0;
		if($total > $total_prev){
			$diff = $total - $total_prev;
			if($total_prev == 0){
				$percent = 100;
			} else {
				$percent = ($diff/$total_prev) * 100;
			}
			$growth = 'success';
			$symbol = '+';
		} else if($total < $total_prev){
			$diff = $total_prev - $total;
			$percent = ($diff/$total_prev) * 100;
			$growth = 'danger';
			$symbol = '-'; 
		}
		$html.= '<h3 class="card-title text-nowrap mb-2">'.shortNumber($total).'</h3>
		<!-- <small class="text-'.$growth.' fw-semibold">
			<i class="bx bx-up-arrow-alt"></i> '.$symbol.$percent.'%
		</small> -->';
	} else if($tp=='chart_revenue'){
		$categories = $series = '[';
		for($i=1; $i<=12; $i++){
			$categories.= '\'T'.$i.'\''.($i<12?',':'');
			$m = sprintf('%s/%s', $clsISO->parseNumber($i), $year);
			$total_prices = $clsBilling->sumItem("totalgrand", "{$cond} and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$m}'");
			$series.= $total_prices.($i<12?',':'');
		}
		$categories.= ']';
		$series.= ']';
		#
		$uid = $clsISO->getUniqid();
		$html.= '<div id="totalRevenueChart_'.$uid.'"></div>';
		$callback = 'const chart = document.querySelector(\'#totalRevenueChart_'.$uid.'\'), options = {
            series:[{name:\'Doanh số\',data: '.$series.'}],
			chart: { height: 300, stacked: true, type: \'bar\', toolbar: { show: true } },
			plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: \'20%\',
                    borderRadius: 12,
                    startingShape: \'rounded\',
                    endingShape: \'rounded\'
                }
            },
			colors: [config.colors.primary, config.colors.info],
            dataLabels: {
                enabled: false
            },/*stroke: {
                curve: \'smooth\',
                width: 6,
                lineCap: \'round\',
                colors: [config.colors.cardColor]
            },*/legend: {
                show: true,
                horizontalAlign: \'left\',
                position: \'top\',
                markers: {
                    height: 8,
                    width: 8,
                    radius: 6,
                    offsetX: -3
                }, labels: {
                    colors: config.colors.axisColor
                }, itemMargin: {
                    horizontal: 10
                }
            }, grid: {
                borderColor: config.colors.borderColor,
                padding: {
                    top: 0,
                    bottom: -8,
                    left: 20,
                    right: 20
                }
            }, xaxis: {
                categories: '.$categories.',
                labels: {
                    style: {
                        fontSize: \'13px\',
                        colors: config.colors.axisColor
                    }
                }, axisTicks: {
                    show: false
                }, axisBorder: {
                    show: false
                }
            }, yaxis: {
                labels: {
                    style: {
                        fontSize: \'13px\',
                        colors: config.colors.axisColor
                    }
                }
            },
			tooltip: {
			  y: {
				formatter: function (val) {
				  return val + "Ä‘"
				}
			  }
			},responsive: [{
                    breakpoint: 1700,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: \'32%\'
                            }
                        }
                    }
                }, {
                    breakpoint: 1580,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: \'35%\'
                            }
                        }
                    }
                }, {
                    breakpoint: 1440,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: \'42%\'
                            }
                        }
                    }
                }, {
                    breakpoint: 1300,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: \'48%\'
                            }
                        }
                    }
                }, {
                    breakpoint: 1200,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: \'40%\'
                            }
                        }
                    }
                }, {
                    breakpoint: 1040,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 11,
                                columnWidth: \'48%\'
                            }
                        }
                    }
                }, {
                    breakpoint: 991,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: \'30%\'
                            }
                        }
                    }
                }, {
                    breakpoint: 840,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: \'35%\'
                            }
                        }
                    }
                }, {
                    breakpoint: 768,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: \'28%\'
                            }
                        }
                    }
                }, {
                    breakpoint: 640,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: \'32%\'
                            }
                        }
                    }
                }, {
                    breakpoint: 576,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: \'37%\'
                            }
                        }
                    }
                }, {
                    breakpoint: 480,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: \'45%\'
                            }
                        }
                    }
                }, {
                    breakpoint: 420,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: \'52%\'
                            }
                        }
                    }
                }, {
                    breakpoint: 380,
                    options: {
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                columnWidth: \'60%\'
                            }
                        }
                    }
                }
            ]
        };
		if (typeof chart !== undefined && chart !== null) {
			new ApexCharts(chart, options).render();
		}';
	} else if($tp=='commission'){
		$total = 0;
		$field = "{$clsBilling->pkey},totalgrand,commission";
		$list_billings = $clsBilling->getAll($cond, $field);
		//$clsISO->print_pre($list_billings);die();
		if(!empty($list_billings)){
			foreach($list_billings as $key => $val){
				$totalgrand = $clsISO->processSmartNumber($val['totalgrand']);
				$commission = $clsISO->processSmartNumber($val['commission']);
				$total+= $totalgrand*$commission/100; 
			}
		}
		$html.= '<div class="mb-3">
			<span class="badge bg-label-warning rounded-pill">NÄƒm '.$year.'</span>
		</div>
		<small class="text-success text-nowrap fw-semibold">
			<i class="bx bx-chevron-up"></i> 0%
		</small>
		<h3 class="mb-0">'.shortNumber($total).'</h3>';
	} else if($tp=='billing_type'){
		$uid = $clsISO->getUniqid();
		$total = $clsBilling->countItem($cond);
		$html.= '<div class="d-flex justify-content-between align-items-center mb-3">
			<div class="d-flex flex-column align-items-center gap-1">
				<h2 class="mb-2">'.$total.'</h2>
				<span>Tổng giao dịch</span>
			</div>
			<div id="billingStatisticsChart_'.$uid.'"></div>
		</div>';
		$field = "{$clsProperty->pkey},title,intro,image";
		$list_types = $clsProperty->getAll("property_type='_BILLING_TYPE'", $field);
		$cond = "`is_trash`=0 and `is_cancel`='0'";
		if($clsISO->checkPermissionGroup('DIRECTOR')){
			// GD
		} else if($clsISO->checkPermissionGroup('SALE')) {
			$role_id = $oneProfile['role_id'];
			$department_id = $oneProfile['department_id'];
			$arrRoles = array();
			$clsProperty->getChilds($role_id, $arrRoles);
			if(!empty($arrRoles)){
				$arrRoles[] = $role_id;
				$cond.= " and `staff_id` in(
					select `profile_id` from ".$clsProfile->tbl." 
					where `department_id`='{$department_id}' and `role_id` in (".implode(',', $arrRoles).")
				)";
			}
		} else {
			$cond .= " and `staff_id`='{$profile_id}'";
		}
		###
		$labels = $series = array();
		if(!empty($list_types)){ $ii = 0;
			$html.= '<ul class="p-0 m-0 ajax _2column">';
			foreach($list_types as $key => $val){
				$prop_id = $val[$clsProperty->pkey];
				$where = $cond . " and `billing_type`='{$prop_id}'";
				$total_billings = $clsBilling->countItem($where);
				$total_prices = $clsBilling->sumItem("totalgrand", $where);
				if($total_billings > 0 && $ii<4){
					$labels[] = $val['title']; 
					$series[] = $total_billings; 
				}
				$html.= '<li class="d-flex mb-2 pb-1">
					<div class="avatar flex-shrink-0 me-3">
						<span class="avatar-initial rounded bg-label-primary">
							<i class="bx '.$val['image'].'"></i>
						</span>
					</div>
					<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
						<div class="me-2">
							<h6 class="mb-0">'.$val['title'].'</h6>
							<small class="text-muted">'.$val['intro'].'</small>
						</div>
						<div class="user-progress">
							<small class="fw-semibold">
								'.shortNumber($total_prices).'
							</small>
						</div>
					</div>
				</li>';
				++$ii;
			}
			$html.= '</div>';
		}
		$callback = 'var options = {
			chart: {height: 185, width: 185, type: \'donut\'},
			labels: [\''.implode('\',\' ', $labels).'\'],
			series: ['.implode(',',$series).'],
			colors: [
				config.colors.primary,
				config.colors.secondary,
				config.colors.info,
				config.colors.success,
				config.colors.warning,
				config.colors.danger,
				config.colors.black
			],
			stroke: {width: 4, colors: config.colors.white}, 
			dataLabels: {
				enabled: true,
				formatter: function(val, opt) {
					return parseInt(val) + \'%\';
				}
			}, legend: {
				show: false
			}, grid: {
				padding: { top: 0, bottom: 0, right: 0}
			}, plotOptions: {
				pie: {
					donut: {
						size: \'65%\',
						labels: {
							show: true,
							value: {
								fontSize: \'1.5rem\',
								fontFamily: \'Public Sans\',
								color: config.colors.headingColor,
								offsetY: -15,
								formatter: function(val) {
									return parseInt(val) + \'%\';
								}
							}, name: {
								offsetY: 20,
								fontFamily: \'Public Sans\'
							}, total: {
								show: true,
								fontSize: \'0.6125rem\',
								color: config.colors.axisColor,
								label: \'Tổng\',
								formatter: function(w) {
									return \'0.0%\';
								}
							}
						}
					}
				}
			}
		};
		var chart = new ApexCharts(document.querySelector(\'#billingStatisticsChart_'.$uid.'\'), options);
		chart.render()';
	} else if($tp=='growth'){
		$uid = $clsISO->getUniqid();
		$cond = "`is_trash`=0 and `is_cancel`=0";
		if($clsISO->checkPermissionGroup('DIRECTOR')){
			// GD
		} else if($clsISO->checkPermissionGroup('SALE')) {
			$role_id = $oneProfile['role_id'];
			$department_id = $oneProfile['department_id'];
			$arrRoles = array();
			$clsProperty->getChilds($role_id, $arrRoles);
			if(!empty($arrRoles)){
				$arrRoles[] = $role_id;
				$cond.= " and `staff_id` in(
					select `profile_id` from ".$clsProfile->tbl." 
					where `department_id`='{$department_id}' and `role_id` in (".implode(',', $arrRoles).")
				)";
			}
		} else {
			$cond .= " and `staff_id`='{$profile_id}'";
		}
		$tmp = $clsBilling->getAll($cond." order by deposit_date ASC", "{$clsBilling->pkey},deposit_date");
		$from_year = !empty($tmp) ? date('Y', $tmp[0]['deposit_date']) : date('Y');
		$tmp = $clsBilling->getAll($cond." order by deposit_date DESC", "{$clsBilling->pkey},deposit_date");
		$to_year = !empty($tmp) ? date('Y', $tmp[0]['deposit_date']) : date('Y');
		###
		$curr_year = Input::post('year', date('Y'));
		$prev_year = $year - 1;
		$total_revenues = $clsBilling->sumItem("totalgrand", $cond." and FROM_UNIXTIME(`reg_date`,'%Y')='".$curr_year."'");
		$total_prev_revenues = $clsBilling->sumItem("totalgrand", $cond." and FROM_UNIXTIME(`reg_date`,'%Y')='".$prev_year."'");
		###
		$diff = $growth = 0;
		if($total_revenues > $total_prev_revenues) {// Tăng trưởng dương
			$growth = 100;
			if($total_prev_revenues > 0){
				$diff = $total_revenues - $total_prev_revenues;
				$growth = round($diff/$total_prev_revenues,2)*100;
			}
			$label_growth = $growth.'% Tăng trưởng doanh thu';
		} else if($total_revenues < $total_prev_revenues){
			$growth = 100;
			if($total_revenues > 0){
				$diff = $total_prev_revenues - $total_revenues; // Tăng trưởng âm
				$growth = @round($diff/$total_prev_revenues,2)*100;
			}
			$label_growth = '-'.$growth.'% Tăng trưởng doanh thu';
		}
		$html.= '<div class="card-body">
			<div class="text-center">
				<div class="dropdown">
					<button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" 
					data-bs-toggle="dropdown">'.$year.'</button>
					<div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">';
						for($i=$from_year; $i<=$to_year; $i++){
							$html.= '<a class="dropdown-item" href="javascript:void(0);">'.$i.'</a>';
						}
					$html.= '</div>
				</div>
			</div>
		</div>
		<div id="growthChart_'.$uid.'"></div>
		<div class="text-center fw-semibold pt-3 mb-2">'.$label_growth.'</div>
		<div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
			<div class="d-flex">
				<div class="me-2">
					<span class="badge bg-label-primary p-2">
						<i class="bx bx-dollar text-primary"></i>
					</span>
				</div>
				<div class="d-flex flex-column">
					<small>'.$year.'</small>
					<h6 class="mb-0">'.shortNumber($total_revenues).'</h6>
				</div>
			</div>
			<div class="d-flex">
				<div class="me-2">
					<span class="badge bg-label-info p-2">
						<i class="bx bx-wallet text-info"></i>
					</span>
				</div>
				<div class="d-flex flex-column">
					<small>'.$prev_year.'</small>
					<h6 class="mb-0">'.shortNumber($total_prev_revenues).'</h6>
				</div>
			</div>
		</div>';
		$callback = 'const growthChartEl = document.querySelector(\'#growthChart_'.$uid.'\'),
			growthChartOptions = {
				series: ['.$growth.'],
				labels: [\'Tăng trưởng\'],
				chart: {height: 300, type: \'radialBar\'},
				plotOptions: {
					radialBar: {
						size: 120,
						offsetY: 10,
						startAngle: -150,
						endAngle: 150,
						hollow: {size: \'55%\'},
						track: {
							background: config.colors.white,
							strokeWidth: \'100%\'
						},dataLabels: {
							name: {
								offsetY: 15,
								color: config.colors.headingColor,
								fontSize: \'15px\',
								fontWeight: \'600\',
								fontFamily: \'Public Sans\'
							}, value: {
								offsetY: -25,
								color: config.colors.headingColor,
								fontSize: \'22px\',
								fontWeight: \'500\',
								fontFamily: \'Public Sans\'
							}
						}
					}
				},
				colors: [config.colors.primary],
				fill: {
					type: \'gradient\',
					gradient: {
						shade: \'dark\',
						shadeIntensity: 0.5,
						gradientToColors: [config.colors.primary],
						inverseColors: true,
						opacityFrom: 1,
						opacityTo: 0.6,
						stops: [30, 70, 100]
					}
				}, stroke: {
					dashArray: 5
				}, grid: {
					padding: {
						top: -35,
						bottom: -10
					}
				}, states: {
					hover: {
						filter: {
							type: \'none\'
						}
					}, active: {
						filter: {
							type: \'none\'
						}
					}
				}
			};
		if (typeof growthChartEl !== undefined && growthChartEl !== null) {
			new ApexCharts(growthChartEl, growthChartOptions).render();
		}';
	} else if($tp=='top_billing'){
		// ini_set('display_errors',1);
		$html = '';
		$field = "{$clsBilling->pkey},billing_code,billing_type,staff_id,reg_date,totalgrand";
		$list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 order
		by `reg_date` DESC,`totalgrand` DESC limit 0,6");
		if(!empty($list_billings)){
			$html.= '<ul class="p-0 m-0">';
			$arr_property_cached = array();
			foreach($list_billings as $key => $val){
				$staff_id = $val['staff_id'];
				$billing_type = $val['billing_type'];
				$oneStaff = $clsProfile->getOne($staff_id, "full_name,first_name,last_name,avatar");
				if(isset($arr_property_cached[$billing_type]) && !empty($arr_property_cached[$billing_type])){
				} else {
					$arr_property_cached[$billing_type] = $clsProperty->getTitle($billing_type);
				}
				$html.= '<li class="d-flex mb-3 pb-1">
					<div class="avatar flex-shrink-0 me-2" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$staff_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="400">
						<img src="'.$clsProfile->getAvatar($staff_id, $oneStaff).'" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" alt="'.$clsProfile->getFullName($staff_id, $oneStaff).'" class="rounded" />
					</div>
					<div class="w-100">
						<div class="d-flex w-100 flex-wrap align-items-center justify-content-between mb-1">
							<small class="text-muted d-block">Mã: '.$val['billing_code'].' - '.$arr_property_cached[$billing_type].'</small>
							<div class="user-progress d-flex align-items-center gap-1">
								<h6 class="mb-0">'.shortNumber($val['totalgrand']).'</h6>
							</div>
						</div>
						<h6 class="mb-0">'.$clsProfile->getFullName($staff_id, $oneStaff).'</h6>
					</div>
				</li>';
			}
			$html.= '</ul>';
		}
		$callback = '';
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	)); die();
}
function age_calculator($birthday){
	$bday = new DateTime(date('Y-m-d', $birthday)); 
	$today = new Datetime('today');
	$diff = $today->diff($bday);
	return $diff->y;
}
function days_to_birth($birthday){
	$today = time();
	$fixedBirthdate = date_create(date("Y", $today) . "-" . date("m", $birthday) . "-" . date("d", $birthday));
	$diff = date_diff(date_create(date("d-m-Y", $today)), $fixedBirthdate);
	return $diff->format("%a ngày nữa");
}
function default_staff_birthday(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$timer = time();
	$holderG = Input::post('holderG', '30days');
	$department_id = (int) Input::post('department_id', 0);
	#
	$field = "{$clsProfile->pkey},full_name,avatar,birthday,FROM_UNIXTIME(`birthday`,'%m-%d') as `birthday_ord`";
	if($department_id > 0){
		$department_id = $oneProfile['department_id'];
		$d1 = new DateTime(date('Y-m-d'));
		$d2 = new DateTime(sprintf('%s-%s-%s', date('Y'), 12, 31));
		$diff = $d2->diff($d1);
		$cond = "`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%') AND `birthday`>0 AND DATE_ADD(FROM_UNIXTIME(`birthday`),INTERVAL YEAR(CURDATE()) - YEAR(FROM_UNIXTIME(`birthday`)) + IF( DAYOFYEAR(CURDATE()) > DAYOFYEAR(FROM_UNIXTIME(`birthday`)),1,0) YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ".$diff->days." DAY)";
		$callback = 'setTimeout(() => {
			const verticalExample = document.getElementById(\'birthday_staffs\');
			if (verticalExample) {
				new PerfectScrollbar(verticalExample, {
					wheelPropagation: false
				});
			}
		},500);';
	} else {
		$cond = "`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND birthday<>'' AND DATE_ADD(FROM_UNIXTIME(birthday),INTERVAL YEAR(CURDATE()) - YEAR(FROM_UNIXTIME(birthday)) + IF( DAYOFYEAR(CURDATE()) > DAYOFYEAR(FROM_UNIXTIME(birthday)), 1, 0) YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ".($holderG=='7days'?'7':'30')." DAY)";
		$callback = '';
	}
	// $clsProfile->setDebug(true);
	$list_staffs = $clsProfile->getAll($cond." order by `birthday_ord` ASC", $field);
	if(!empty($list_staffs)){
		foreach($list_staffs as $key => $val){
			$age = age_calculator($val['birthday']);
			$days_to_birth = days_to_birth($val['birthday']);
			$list_staffs[$key]['age']= $age;
			$list_staffs[$key]['days_to_birth']= $days_to_birth;
		}
	}
	// $clsISO->print_pre($list_staffs); die();
	$smarty->assign('list_staffs', $list_staffs);
	// Return
	$html = $core->build('_ajax.birthday.tpl');
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	)); die();
}
function default_unknow(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang;
	header('Location:'.PCMS_URL.$extLang);
	exit();
}
function default_404(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang;
	/*=============Title & Description Page==================*/
	$title_page = '404 Not Found - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_load_profile_popover(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	//ini_set('display_errors',1);
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	##
	$user_id = (int) Input::get('user_id', 0);
	if($user_id == $profile_id){
		$oProfile = $oneProfile;
	} else {
		$oProfile = $clsProfile->getOne($user_id);
	}
	$isMask = $clsISO->checkSupper() ? false: true;
	$role_id = $oProfile['role_id'];
	$department_id = $oProfile['department_id'];
	$profile_type = $oProfile['profile_type'];
	$IsFH = ($profile_type=='_user') ? 1 : 0;
	$total_billing = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$user_id}'");
	$total_price = $clsBilling->sumItem("totalgrand", "`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$user_id}'");
	$html = '<div class="links-bar-create-edit clearfix">
		<div class="profile-photo-create-edit pull-left mr-2">
			<img src="'.$clsProfile->getAvatar($user_id, $oProfile).'">
		</div>
		<div class="title fs-16 bold">
			'.$clsProfile->getIndentityV2($user_id, $oProfile, false).'
			'.($IsFH?'<span class="awe__post-star">
				'.$clsProfile->genHTMLStar($oProfile['rating_star']).'
			</span>':'').'
		</div>
		<div clas="d-flex align-items-center">
			<span clas="awe__post-level mr-2 text-muted">
				'.$clsProperty->getTitle($role_id).'
			</span>
		</div>
		'.($IsFH?'<div class="subtitle mt-1 textred">
			Phòng ban: '.$clsProperty->getTitle($department_id).'
		</div>':'').'
	</div>
	<div class="bg-lighter rounded-2 p-3 mt-2">
		'.($profile_type=='_sale' ? '
		<div class="form-group mb-1">
			'.$core->makeIcon('clock-o', $clsISO->convertTimeToText($oProfile['reg_date'], true)).'
		</div>': '').'
		<div class="form-group mb-1">
			'.$core->makeIcon('phone', $clsProfile->getPhone($user_id, $oProfile, $isMask)).'
		</div>
		<div class="form-group mb-1">
			'.$core->makeIcon('envelope', $clsProfile->getEmail($user_id, $oProfile, $isMask)).'
		</div>
		<div class="form-group mb-1">
			'.$core->makeIcon('cc', $clsProfile->getCCID($user_id, $oProfile, $isMask)).'
		</div>
		<div class="form-group">
			'.$core->makeIcon('calendar-o', $clsProfile->getBirthday($user_id, $oProfile)).'
		</div>
	</div>
	'.($IsFH ? '<div class="metadata-page-render mt-2">
		<section class="block metadata-section-render">
			<div class="metadata-row-render">
				<div class="metadata-row-title">
					<span class="title">Ngày bắt đầu</span>
				</div>
				<div class="metadata-row-viewer w-200 d-inline-block">
					'.$oProfile['start_date'].'
				</div>
			</div>
			<div class="metadata-row-render">
				<div class="metadata-row-title">
					<span class="title">Tình trạng</span>
				</div>
				<div class="metadata-row-viewer w-200 d-inline-block">
					'.$clsProperty->getLabel($oProfile['status_id']).'
				</div>
			</div>
			<div class="metadata-row-render">
				<div class="metadata-row-title">
					<span class="title">Tổng giao dịch</span>
				</div>
				<div class="metadata-row-viewer d-inline-block">'.$total_billing.'</div>
			</div>
			<div class="metadata-row-render">
				<div class="metadata-row-title">
					<span class="title">Tổng doanh số</span>
				</div>
				<div class="metadata-row-viewer d-inline-block">
					<strong class="red" title="0">
						'.$clsISO->formatNumberToEasyRead($total_price).'</strong> ₫
				</div>
			</div>
		</section>
	</div>' : '').'';
	// Return
	echo $html; die();
}
/** GIAO DỊCH */
function default_billing(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;###
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	$smarty->assign('clsBilling', $clsBilling);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsCustomer', $clsCustomer);
	###
	$cond  = "`is_trash`=0";
	$start_year = strtotime('01-01-2024');
	$permiss_add = $clsISO->checkPermission('create_billing') ? 1 : 0;
	$permiss_view = $clsISO->checkPermission('view_billing') ? 1 : 0;
	$permiss_edit = $clsISO->checkPermission('edit_billing') ? 1 : 0;
	$permiss_cancel = $clsISO->checkPermission('cancel_billing') ? 1 : 0;
	$permiss_delete = $clsISO->checkPermission('delete_billing') ? 1 : 0;
	$permiss_add_info = $clsISO->checkPermission('add_info_billing') ? 1 : 0;
	$smarty->assign('permiss_add', $permiss_add);
	$smarty->assign('permiss_view', $permiss_view);
	$smarty->assign('permiss_edit', $permiss_edit);
	$smarty->assign('permiss_cancel', $permiss_cancel);
	$smarty->assign('permiss_delete', $permiss_delete);
	$smarty->assign('permiss_add_info', $permiss_add_info);
	if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermission('view_all_billing')){
		// GD
	} else if($clsISO->checkPermissionGroup('SALE_DIRECTOR')) {
		$arrRoles = array();
		$role_id = $oneProfile['role_id'];
		$department_id = $oneProfile['department_id'];
		$clsProperty->getChilds($role_id, $arrRoles);
		$cond.= " and `staff_id` in (
			select `profile_id` from ".$clsProfile->tbl." 
			where (`department_id`='{$department_id}' 
				or `list_department_id` like '%|{$department_id}|%'
			)
		)";
	} else {
		if(in_array($oneProfile['role_id'], array(_ROLE_STAFF_ADMIN, _ROLE_GD_PROJECT))){
			$params = array();
			$more_information = $oneProfile['more_information'];
			$permiss_billing = isset($more_information['permiss_billing']) 
				? $more_information['permiss_billing'] : array();
			if(!empty($permiss_billing)){
				foreach($permiss_billing as $project_id => $arrs){
					if(!empty($arrs)){
						foreach($arrs as $id){
							$params[] = sprintf('|%s_%s|', $project_id, $id);
						}
					}
				}
			}
			if(!empty($params)){
				$cond.= " and `billing_search` in ('".implode('\',\'',$params)."')";
			} else {
				$params[] = sprintf('|%s_%s|', 100000, 100000);
				$cond.= " and `billing_search` in ('".implode('\',\'',$params)."')";
			}
		} else {
			$cond.= " and `staff_id`='{$profile_id}'";
		}
	}
	if(isset($_POST['filter']) && $_POST['filter'] =='filter'){
		$link = '/giao-dich.html';
		$hasCond = false;
		$keyword = Input::post('keyword');
		$staff_id = (int) Input::post('staff_id', 0);
		$contract_status_id = (int) Input::post('contract_status_id', 0);
		$status_id = (int) Input::post('status_id', 0);
		$project_id = (int) Input::post('project_id', 0);
		$billing_type = (int) Input::post('billing_type', 0);
		$billing_source = (int) Input::post('billing_source', 0);
		$start_date = Input::post('start_date', 0);
		$to_date = Input::post('to_date', 0);
		$sort_by = Input::post('sort_by', 'reg_date');
		$per_page = (int) Input::post('per_page', 20);
		###
		if(!empty($keyword)){
			$link .= ($hasCond?'&':'?') . 'keyword='.$keyword;
			$hasCond = true;
		}
		if($staff_id > 0){
			$link .= ($hasCond?'&':'?') . 'staff_id='.$staff_id;
			$hasCond = true;
		}
		if($contract_status_id > 0){
			$link .= ($hasCond?'&':'?') . 'contract_status_id='.$contract_status_id;
			$hasCond = true;
		}
		if($status_id > 0){
			$link .= ($hasCond?'&':'?') . 'status_id='.$status_id;
			$hasCond = true;
		}
		if($project_id > 0){
			$link .= ($hasCond?'&':'?') . 'project_id='.$project_id;
			$hasCond = true;
		}
		if(!empty($start_date)){
			$link .= ($hasCond?'&':'?') . 'start_date='.$clsISO->toTime($start_date);
			$hasCond = true;
		}
		if(!empty($to_date)){
			$link .= ($hasCond?'&':'?') . 'to_date='.$clsISO->toTime($to_date." 23:59:59");
			$hasCond = true;
		}
		if($billing_type > 0){
			$link .= ($hasCond?'&':'?') . 'billing_type='.$billing_type;
			$hasCond = true;
		}
		if($billing_source > 0){
			$link .= ($hasCond?'&':'?') . 'billing_source='.$billing_source;
			$hasCond = true;
		}
		if(!empty($sort_by)){
			$link .= ($hasCond?'&':'?') . 'sort_by='.$sort_by;
			$hasCond = true;
		}
		if($per_page > 0){
			$link .= ($hasCond?'&':'?') . 'per_page='.$per_page;
			$hasCond = true;
		}
		header('Location:' . $link);
		exit();
	}
	$keyword = Input::get('keyword');
	$staff_id = (int) Input::get('staff_id', 0);
	$status_id = (int) Input::get('status_id', 0);
	$contract_status_id = (int) Input::get('contract_status_id', 0);
	$project_id = (int) Input::get('project_id', 0);
	$billing_type = (int) Input::get('billing_type', 0);
	$billing_source = (int) Input::get('billing_source', 0);
	$start_date = (int) Input::get('start_date', 0);
	$to_date = (int) Input::get('to_date', 0);
	$sort_by = Input::get('sort_by', 'reg_date');
	$smarty->assign('keyword', $keyword);
	$smarty->assign('staff_id', $staff_id);
	$smarty->assign('status_id', $status_id);
	$smarty->assign('contract_status_id', $contract_status_id);
	$smarty->assign('project_id', $project_id);
	$smarty->assign('billing_type', $billing_type);
	$smarty->assign('billing_source', $billing_source);
	$smarty->assign('start_date', $start_date);
	$smarty->assign('to_date', $to_date);
	$smarty->assign('sort_by', $sort_by);
	###
	if(!empty($keyword)) {
		$cond.=" and (`billing_code` like '%{$keyword}%' or `stock_code` like '%{$keyword}%')";
	}
	if($staff_id > 0) {
		$cond.= " and `staff_id`='{$staff_id}'";
	}
	if($contract_status_id > 0) {
		$cond.= " and `contract_status_id`='{$contract_status_id}'";
	}
	if($project_id > 0) {
		$cond.= " and `project_id`='{$project_id}'";
	}
	if($billing_type > 0) {
		$cond.= " and `billing_type`='{$billing_type}'";
	}
	if($billing_source > 0) {
		$cond.= " and `billing_source_id`='{$billing_source}'";
	}
	$cond_extra = $cond;
	// Máº·c Ä‘á»‹nh
	$date_field = "deposit_date";
	if($sort_by == 'contract_date'){
		$date_field = 'contract_date'; // Ngày ký HĐMB
	}
	if($start_date > 0 && $to_date ==0){
		$cond.=" and (`{$date_field}` > '{$start_date}')";
	} else if($start_date==0 && $to_date > 0){
		$cond.=" and (`{$date_field}` < '{$to_date}')";
	} else if($start_date > 0 && $to_date > 0){
		$cond.= " and (`{$date_field}` between '{$start_date}' and '{$to_date}')";
	}
	$cnd = $cond;
	if($start_date == 0 && $to_date == 0){
		$cond_extra.= " and (FROM_UNIXTIME(`deposit_date`,'%m/%Y')='".date('m/Y')."')";
	}
	$total_billings = $clsBilling->countItem($cond_extra);
	$totalgrand = $clsBilling->sumItem("totalgrand", $cond_extra." and `is_cancel`=0 and `is_alliance`='0'");
	$total_registed_hdmb = $clsBilling->countItem($cond_extra."  and `is_cancel`=0 and `contract_status_id`='"._CONTRACT_STATUS_DONE_ID."'");
	$total_unregisted_hdmb = $clsBilling->countItem($cond_extra." and `is_cancel`=0 and `contract_status_id`='"._CONTRACT_STATUS_WAIT_ID."' and `estimate_date`<>'0'");
	$total_not_schedule_hdmb = $clsBilling->countItem($cond_extra." and `is_cancel`=0 and `contract_status_id`='"._CONTRACT_STATUS_WAIT_ID."' and `estimate_date`='0'");
	#- Begin pagination
	$per_page = 20;
	$current_page = Input::get('page',1);
	if(isset($_GET['per_page'])){
		$per_page = Input::get('per_page',20);
		vnSessionSetVar('_ss_per_page', $per_page);
	} else if(vnSessionExist('_ss_per_page')){
		$per_page = vnSessionGetVar('_ss_per_page');
	}
	$smarty->assign('current_page', $current_page);
	$smarty->assign('per_page', $per_page);
	$total_record = $clsBilling->countItem($cond);
	$total_page = ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End pagination
	$field = "*";
	$order_by = " order by `{$clsBilling->pkey}` DESC,`reg_date` DESC";
	if($sort_by == 'contract_date'){
		$order_by = " order by `contract_date` ASC";
	}
	$list_billings = $clsBilling->getAll($cond.$order_by.$limitCond, $field);
	if(!empty($list_billings)){
		$arr_property_cached = $arr_projects_cached = array();
		foreach($list_billings as $key => $val){
			$partner_id = $val['partner_id'];
			$project_id = $val['project_id'];
			$billing_type = $val['billing_type'];
			$billing_source_id = $val['billing_source_id'];
			$more_information = $val['more_information'];
			$more_information = !empty($more_information) 
				? json_decode(html_entity_decode($more_information), true) : array();
			$list_billings[$key]['more_information'] = $more_information;
			###
			if($billing_source_id > 0){
				if(!isset($arr_property_cached[$billing_source_id])){
					$arr_property_cached[$billing_source_id] = $clsBilling->getBillingSource($billing_source_id);
				}
				$list_billings[$key]['billing_source'] = $arr_property_cached[$billing_source_id];
			} else {
				$list_billings[$key]['billing_source'] = "";
			}
			if($billing_type > 0){
				if(isset($arr_property_cached[$billing_type])){
					$list_billings[$key]['billing_type'] = $arr_property_cached[$billing_type];
				} else {
					$arr_property_cached[$billing_type] = $clsProperty->getTitle($billing_type);
					$list_billings[$key]['billing_type'] = $arr_property_cached[$billing_type];
				}
			} else {
				$list_billings[$key]['billing_type'] = "";
			}
			###
			if($project_id > 0){
				if(isset($arr_projects_cached[$project_id])){
					$list_billings[$key]['poroject_name'] = $arr_projects_cached[$project_id];
				} else {
					$arr_projects_cached[$project_id] = $clsProject->getTitle($project_id);
					$list_billings[$key]['poroject_name'] = $arr_projects_cached[$project_id];
				}
			} else {
				$list_billings[$key]['poroject_name'] = "";
			}
		}
	}
	$smarty->assign('list_billings', $list_billings);
	$smarty->assign('total_record', $total_record);
	$smarty->assign('total_page', $total_page);
	$smarty->assign('current_page', $current_page);
	$smarty->assign('total_billings', $total_billings);
	$smarty->assign('totalgrand', $totalgrand);
	$smarty->assign('total_registed_hdmb', $total_registed_hdmb);
	$smarty->assign('total_unregisted_hdmb', $total_unregisted_hdmb);
	$smarty->assign('total_not_schedule_hdmb', $total_not_schedule_hdmb);
	
	$params = '';
	$query_string = @$_SERVER['QUERY_STRING'];
	$lst_query_string = @explode('&',$query_string);
	if(!empty($lst_query_string)){ $ii = 0;
		foreach($lst_query_string as $val){
			$tmp = @explode('=', $val);
			if($tmp[0]!='page' && $tmp[0] != 'mod' && $tmp[0] != 'act' && $tmp[0] != 'lang'){
				$params .= ($ii==0)?'?'.$val:'&'.$val;
				++$ii;
			}
		}
	}
	$config = array(
		'total'	=> $total_record,
		'current_page'	=> $current_page,
		'number_per_page'	=> $per_page,
		'link_page_1'	=> '/giao-dich.html',
		'link' => '/giao-dich/'
	);
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links_page(true, $params);
	$assign_list["html_pager"] = $html_pager;
	/*=============Title & Description Page==================*/
	$title_page = 'Lịch sử giao dịch - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_open_calendar(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	$uid = $clsISO->getUniqid();
	// Return
	$smarty->assign('uid', $uid);
	$html = $core->build('_ajax.open_calendar.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function str_replace_first($search, $replace, $subject)	{
	$search = '/'.preg_quote($search, '/').'/';
	return preg_replace($search, $replace, $subject, 1);
}
function mask_code($stock_code){
	global $core, $clsISO;
	if($clsISO->checkContainer($stock_code,"-","")){
		$tmp = @explode('-', $stock_code);
		$code = $tmp[1];
		$code = preg_replace('/[0-9]/','X', $code);
		return sprintf('%s-%s', $tmp[0], $code);
	} else {
		$clsStock = new Stock();
		$field = "{$clsStock->pkey},floor";
		$oStock = $clsStock->getByCond("`ms_code`='{$stock_code}'", $field);
		if(!empty($oStock)){
			$code = $oStock['code'];
			$floor = $oStock['floor'];
			return str_replace_first($floor, 'XX', $stock_code);
		}
	}
	return $stock_code;
}
function default_load_billing_calendar(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	###
	$current_now = time();
	$start = (int) Input::get('start',0);
	$end = (int) Input::get('end',0);
	$billing_type = (int) Input::get('billing_type', 0);
	$sql_string = "`is_trash`=0 and `is_cancel`=0";
	if($billing_type > 0) $sql_string.= " and `billing_type`='{$billing_type}'";	
	###
	$results = array();
	for($i = $start; $i <= $end; $i = strtotime('+1 day',$i)){
		$date = date('d/m/Y',$i);
		$total_billings = $total_registered = $total_unregisted = 0;
		$field = "{$clsBilling->pkey},`stock_code`,`contract_status_id`,`estimate_date`,`billing_source_id`";
		$list_billings = $clsBilling->getAll("{$sql_string} and (FROM_UNIXTIME(`contract_date`,'%d/%m/%Y')='{$date}' 
			or (estimate_date>='{$current_now}' and FROM_UNIXTIME(`estimate_date`,'%d/%m/%Y')='{$date}'))", $field);
		if(!empty($list_billings)){
			$total_billings = count($list_billings);
			foreach($list_billings as $key => $val){
				$billing_source_id = $val['billing_source_id'];
				$contract_status_id = $val['contract_status_id'];
				if($contract_status_id == _CONTRACT_STATUS_DONE_ID){
					$total_registered += 1;
					$tooltip = "Đã ký HĐMB";
					$cls = "bg-blue text-white";
					if($billing_source_id==_BILLING_RESOURCE_F1_ID){
						$stock_code = sprintf('%s %s', 'F1', mask_code($val['stock_code']));
					} else {
						$stock_code = mask_code($val['stock_code']);
					}
				} else {
					$total_unregisted += 1;
					$tooltip = "Chưa ký HĐMB";
					$cls = "bg-yellow text-main";
					if($billing_source_id==_BILLING_RESOURCE_F1_ID){
						$stock_code = sprintf('%s-%s %s', 'F1', date('H:i', $val['estimate_date']), mask_code($val['stock_code']));
					} else {
						$stock_code = sprintf('%s %s', date('H:i', $val['estimate_date']), mask_code($val['stock_code']));
					}
				}
				$results[] = array(
					'flag' => 1,
					'cls' => $cls,
					'tooltip' => $tooltip,
					'total_registered' => $total_registered,
					'total_unregisted' => $total_unregisted,
					'billing_id' => $val[$clsBilling->pkey],
					'title' => $stock_code,
					'date_f' => date('Y-m-d'),
					'start' => date('Y-m-d H:i:s',$i)
				);
			}
		}
	}
	// Return
	echo json_encode($results);
	die();
}
function default_open_billing(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	###
	$uid = $clsISO->getUniqid();
	$tp = Input::post('tp','full');
	$billing_id = (int) Input::post('billing_id', 0);
	###
	$action = '_add';
	$oneBilling = array(
		'billing_type' => 0,
		'ms_value' => 0,
		'partner_id' => 0,
		'product_code' => '',
		'contract_status_id' => _CONTRACT_STATUS_WAIT_ID,
		'commission' => 0
	);
	$more_information = array('sale_agency_id' => _AGENCY_FH_ID);
	if($billing_id > 0){
		$action = '_edit';
		$oneBilling = $clsBilling->getOne($billing_id);
		$more_information = $oneBilling['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
	}
	$smarty->assign('tp', $tp);
	$smarty->assign('action', $action);
	$smarty->assign('billing_id', $billing_id);
	$smarty->assign('oneBilling', $oneBilling);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('clsBilling', $clsBilling);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsCustomer', $clsCustomer);
	// Return
	$html = $core->build('_ajax.billing'.($tp=='quick'?'.quick':'').'.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_view_billing(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProject = new Project();
	###
	$uid = $clsISO->getUniqid();
	$tabfocus = (int) Input::post('tabfocus', 1);
	$billing_id = (int) Input::post('billing_id', 0);
	if($billing_id == 0){
		echo '_invalid';
		die();
	}
	$oneBilling = $clsBilling->getOne($billing_id);
	$more_information = $oneBilling['more_information'];
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) 
		: array();
	$commission = $oneBilling['commission'];
	if((int) $commission > 0){
		$totalgrand = $clsISO->processSmartNumber($oneBilling['totalgrand']);
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('tabfocus', $tabfocus);
	$smarty->assign('billing_id', $billing_id);
	$smarty->assign('oneBilling', $oneBilling);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('clsCustomer', new Customer());
	$smarty->assign('clsStock', new Stock());
	// Return
	$html = $core->build('_ajax.billing.view.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_delete_billing(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	$billing_id = Input::post('billing_id', 0);
	###
	$msg = '_error';
	if($clsBilling->deleteOne($billing_id)){
		$msg = '_success';
	}
	// Return
	echo $msg; die();
}
function default_cancel_billing(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO,$clsProfile;
	$clsBilling = new Billing();
	$billing_id = (int) Input::post('billing_id', 0);
	###
	$oneBilling = $clsBilling->getOne($billing_id, "`is_cancel`,`staff_id`,`logs`");
	if($oneBilling['is_cancel']==1){
		echo '_invalid'; 
		die();
	} else {
		$msg = '_error';
		$logs = $oneBilling['logs'];
		$logs = $clsISO->to_array_json($logs);
		$contentLog = sprintf('<strong>%s</strong> Đã huỷ giao dịch này', $clsProfile->getFullName($profile_id, $oneProfile));
		$logs[$clsISO->getUniqid()] = array(
			'title' => 'Huỷ giao dịch',
			'content' => $contentLog,
			'profile_id' => $profile_id,
			'reg_date' => time()
		);
		if($clsBilling->updateOne($billing_id, array(
			'is_cancel' => 1,
			'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE)
		))){
			$msg = '_success';
			$clsFPoint = new FPoint();
			$clsFPoint->cancel_billing_LPoint($billing_id, $oneBilling);
		}
		// Return
		echo $msg; die();
	}
}
function default_add_info(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	#
	$uid = $clsISO->getUniqid();
	$billing_id = Input::post('billing_id', 0);
	$oBilling = $clsBilling->getOne($billing_id);
	$more_information = $oBilling['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$smarty->assign('billing_id', $billing_id);
	$smarty->assign('oBilling', $oBilling);
	$smarty->assign('more_information', $more_information);	
	// Return
	$smarty->assign('template_type', '_add_info');
	$html = $core->build('_ajax.sync_sold.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_billing_sold(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	#
	$billing_id = Input::post('billing_id', 0);
	$field = "more_information,contract_date,estimate_date";
	$oneBilling = $clsBilling->getOne($billing_id, $field);
	$more_information = $clsBilling->getOneField('more_information', $billing_id);
	$more_information = $clsISO->to_array_json($more_information);
	if(!empty($oneBilling['contract_date'])){
		$contract_date = $oneBilling['contract_date'];
		$estimate_date = $oneBilling['estimate_date'];
		$oneBilling['contract_date'] = date('Y-m-d', $contract_date);
		$oneBilling['estimate_date'] = date('Y-m-d\TH:i', $estimate_date);
	}
	$smarty->assign('billing_id', $billing_id);
	$smarty->assign('oneBilling', $oneBilling);
	$smarty->assign('more_information', $more_information);	
	// Return
	$smarty->assign('template_type', '_info');
	$html = $core->build('_ajax.sync_sold.tpl');
	echo $html; die();
}
function default_upd_billing_sold(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	#
	$msg = "_error";
	$billing_id = Input::post('billing_id', 0);
	$oBilling = $clsBilling->getOne($billing_id);
	$more_information = $oBilling['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	#
	$contract_date = Input::post('contract_date');
	$contract_date = !empty($contract_date) ? $clsISO->toTime($contract_date) : 0;
	###
	$more_information['customer_name'] = Input::post('customer_name');
	$more_information['customer_phone'] = Input::post('customer_phone');
	$more_information['customer_email'] = Input::post('customer_email');
	$more_information['identity_card'] = Input::post('identity_card');
	$more_information['issuance_date'] = Input::post('issuance_date');
	$more_information['issuance_location'] = Input::post('issuance_location');
	$more_information['permanent_address'] = Input::post('permanent_address');
	$more_information['contact_address'] = Input::post('contact_address');
	$more_information['billing_method'] = Input::post('billing_method', 0);
	$more_information['bank_guarantee_id'] = Input::post('bank_guarantee_id');
	$more_information['contract_date'] = $contract_date;
	// $clsBilling->setDebug(true);
	if($clsBilling->updateOne($billing_id, array(
		'upd_date' => time(),
		'billing_method' => Input::post('billing_method'),
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
		//add data customer
		$clsClient = new Client();
		if($more_information['customer_email'] != "" && $more_information['customer_phone'] != ""){
			$check_exist_customer = $clsClient->getByCond("billing_id LIKE '%|{$billing_id}|%'");
			if(!empty($check_exist_customer)){
				$email = ($more_information['customer_email']!= "")?$more_information['customer_email']:$check_exist_customer["email"];
				$phone = ($more_information['customer_phone']!= "")?$more_information['customer_phone']:$check_exist_customer["phone"];
				$full_name = ($more_information['customer_name']!= "")?$more_information['customer_name']:$check_exist_customer["full_name"];
				$address = ($more_information['permanent_address']!= "")? $more_information['permanent_address']:$check_exist_customer["address"];
				$contact_address = ($more_information['contact_address']!= "")? $more_information['contact_address']:$check_exist_customer["contact_address"];
				$identity_card = ($more_information['identity_card']!= "")? $more_information['identity_card']:$check_exist_customer["identity_card"];
				$issuance_date = ($more_information['issuance_date']!= "")? $more_information['issuance_date']:"";
				$issuance_date = ($issuance_date != "")? strtotime(str_replace("/","-",$issuance_date)) : $check_exist_customer["issuance_date"];
				$issuance_location = ($more_information['issuance_location'] != "")?$more_information['issuance_location']:$check_exist_customer["issuance_location"];
				$arr_client = [
					"email"				=>	$email,
					"phone"				=>	$phone,
					"full_name"			=>	$full_name,
					"slug"				=>	$clsISO->make_slug($full_name),
					"address"			=>	$address,
					"contact_address"	=>	$contact_address,
					"identity_card"		=>	$identity_card,
					"issuance_date"		=>	$issuance_date,
					"issuance_location"	=>	$issuance_location,
				];
				$clsClient->updateOne($check_exist_customer['client_id'],$arr_client);
			}else{
				$address = ($more_information['permanent_address'] != "")? $more_information['permanent_address']:$check_exist_customer["address"];
				$contact_address = ($more_information['contact_address'] != "")? $more_information['contact_address']:$check_exist_customer["contact_address"];
				$identity_card = ($more_information['identity_card'] != "")? $more_information['identity_card']:$check_exist_customer["identity_card"];
				$issuance_date = ($more_information['issuance_date'] != "")? $more_information['issuance_date']:$check_exist_customer["issuance_date"];
				$issuance_date = ($issuance_date != "")? strtotime(str_replace("/","-",$issuance_date)) : "";
				$issuance_location = ($more_information['issuance_location'] != "")?$more_information['issuance_location']:$check_exist_customer["issuance_location"];
				
				$arr_client = [
					"client_id"			=>	$clsClient->getMaxID(),
					"email"				=>	$more_information['customer_email'],
					"phone"				=>	$more_information['customer_phone'],
					"full_name"			=>	$more_information['customer_name'],
					"slug"				=>	$clsISO->make_slug($more_information['customer_name']),
					"address"			=>	$more_information['permanent_address'],
					"contact_address"	=>	$more_information['contact_address'],
					"identity_card"		=>	$more_information['identity_card'],
					"issuance_date"		=>	$more_information['issuance_date'],
					"issuance_location"	=>	$more_information['issuance_location'],
					"billing_id"		=>	"|".$billing_id."|",
					"staff_id"			=>	"|".$$oBilling['staff_id']."|",
					"reg_date"			=>	time(),
				];
				$clsClient->insert($arr_client);
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_upload_sp_file(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	#
	$msg = "_error";
	$to_field = Input::post('to_field');
	$billing_id = (int) Input::post('billing_id', 0);
	$oneBilling = $clsBilling->getOne($billing_id, "billing_code,more_information");
	$billing_code = $oneBilling['billing_code'];
	$more_information = $oneBilling['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	if(isset($_POST['hid']) && $_POST['hid'] == 'upload'){
		if(!empty($_FILES['upload_file']['name'])){
			if(@is_uploaded_file($_FILES['upload_file']['tmp_name'])){
				$clsUploadFile = new UploadFile();
				$upload_file = $clsUploadFile->uploadItem($_FILES["upload_file"],"/GD",EXTENSION_FILE_UPLOAD);
				$file_name = $_FILES['upload_file']['name'];
				$file_size = $_FILES['upload_file']['size'];
				// Upload file to google drive
				$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_FOLDER_TTDG_ID, true);
				$folder_id = $clsGoogleUpload->create_folder($billing_code);
				// $clsISO->print_pre($folder_id); die();
				$createdFile = $clsGoogleUpload->upload($file_name,$mimeType,ROOTPATH.$upload_file,$folder_id);
				$uploaded_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
				$uploaded_url = $clsISO->genGoogleURL($createdFile->getId());
				@unlink(ROOTPATH . $upload_file);
				// Update to DB
				$more_information[$to_field] = $uploaded_file;
				// $clsISO->print_pre($more_information); die();
				if($clsBilling->updateOne($billing_id, array(
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
				))){
					$msg = '_success|||';
					if($to_field=='ccid_front' || $to_field=='ccid_back'){
						$msg.= '<img src="'.$uploaded_url.'" class="w-100 h-100 rounded-1" />';
					} else if($to_field=='sale_policy_file'){
						$msg.= '<a class="download" data-fancybox="true" target="_blank" href="'.$uploaded_url.'">'.$uploaded_file.'</a>';
					}
				}
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_open_sp_file(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	##
	$clsBilling = new Billing();
	$uid = $clsISO->getUniqid();
	$toId = Input::post('toId');
	$p_id = (int) Input::post('p_id', 0);
	$p_field = Input::post('p_field', 0);
	$titlePage = 'uỷ nhiệm chi';
	if($p_field=='price_sheet_file'){
		$titlePage = 'File PTG';
	} else if($p_field=='residence_info'){
		$titlePage = 'Xác nhận cư trú';
	}
	##
	$oBilling = $clsBilling->getOne($p_id, "more_information");
	$more_information = $oBilling['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$list_items = isset($more_information[$p_field]) 
		? $more_information[$p_field] : array();
	$smarty->assign('list_items', $list_items);
	##
	$smarty->assign('uid', $uid);
	$smarty->assign('toId', $toId);
	$smarty->assign('p_id', $p_id);
	$smarty->assign('p_field', $p_field);
	$smarty->assign('titlePage', $titlePage);
	// Return
	$smarty->assign('template_type', '_form');
	$html = $core->build('_ajax.sync_sold.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_add_sp_file(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO,$deviceType;
	###
	$toId = Input::post('toId');
	$p_field = Input::post('p_field');
	$p_id = (int) Input::post('p_id', 0);
	$uid = $clsISO->getUniqid();
	if($deviceType=='phone'){
		$html = '<div class="gbox tr_'.$p_field.'_'.$toId.' tr_'.$p_field.'_'.$uid.' mb-2">
			<div class="gbox-body">
				<div class="form-group mb-2">
					<label class="col-form-label mr-2">Tên phiên bản</label>
					<input type="text" placeholder="Nhập tên..." name="'.$p_field.'['.$uid.'][title]" class="form-control required" maxlength="255" value="TiÃªu chuáº©n" />
				</div>			
				<div class="form-group mb-2">
					<label class="col-form-label mr-2">File upload</label>
					<div class="input-group">
						<input type="text" placeholder="Nhập ảnh..." name="'.$p_field.'['.$uid.'][image]" 
							class="form-control required '.$p_field.'_'.$uid.'" maxlength="255" />
						<button type="button" toId="select_image_'.$toId.'" uid="'.$uid.'" onClick="$Core.billing.select_image(this, event)" stock_id="'.$stock_id.'" class="btn btn-icon btn-outline-default">'.$core->makeIcon('upload','Chọn ảnh').'</button>
					</div>
				</div>
				<div class="form-group">
					<button type="button" onClick="$Core.helper.delete_sp_file(this, event)" uid="'.$uid.'" toId="'.$stock_id.'" class="btn btn-outline-default">'.$clsISO->makeIcon('bx-trash-alt', 'Xoá dòng').'</button>
				</div>
			</div>
		</div>';
	} else {
		$html = '<tr class="tr_'.$p_field.'_'.$toId.' tr_'.$p_field.'_'.$uid.'">
			<td class="text-left">
				<input type="text" placeholder="Nhập tên..." name="'.$p_field.'['.$uid.'][title]" 
				class="form-control required" maxlength="255" />
			</td>
			<td class="text-left">
				<div class="input-group">
					<input type="text" placeholder="Nhập áº£nh..." name="'.$p_field.'['.$uid.'][image]" 
					class="form-control required '.$p_field.'_'.$uid.'" maxlength="255" />
					<button type="button" toId="select_image_'.$toId.'" uid="'.$uid.'" onClick="$Core.billing.select_image(this, event)" stock_id="'.$stock_id.'" class="btn btn-icon btn-outline-default">'.$core->makeIcon('upload').'</button>
				</div>
			</td>
			<td class="text-center">
				<button type="button" onClick="$Core.billing.delete_sp_file(this, event)" uid="'.$uid.'" p_field="'.$p_field.'" 
				stock_id="'.$stock_id.'" class="btn p-2 btn-outline-default">'.$clsISO->makeIcon('bx-trash-alt').'</button>
			</td>
		</tr>';
	}
	// return
	echo $html; die();
}
function default_save_sp_file(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	###
	$msg = "_error";
	$p_id = (int) Input::post('p_id', 0);
	$p_field = Input::post('p_field', 'payment_order');
	if($p_id > 0 && in_array($p_field, ['payment_order', 'price_sheet_file','residence_info'])){
		$oBilling = $clsBilling->getOne($p_id, "more_information");
		$more_information = $oBilling['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$more_information[$p_field] = Input::post($p_field);
		###
		if($clsBilling->updateOne($p_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success|||";
			if(isset($more_information[$p_field]) && !empty($more_information[$p_field])){
				foreach($more_information[$p_field] as $key => $val){
					$msg.= '<a class="download" data-fancybox="true" href="'.$clsISO->getGoogleUrl($val['image']).'">
						'.$clsISO->makeIcon('bx-download',$val['title']).'
					<a/>';
				}
			} else {
				$msg.= '<span class="text-muted fs-12">Chưa có File</span>';
			}
		}
	}
	// return
	echo $msg; die();
}
function default_save_quick_notes(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	#
	$msg = "_error";
	$quick_notes = Input::post('quick_notes');
	$billing_id = (int) Input::post('billing_id', 0);
	$more_information = $clsBilling->getOneField('more_information', $billing_id);
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) 
		: array();
	$more_information['quick_notes'] = $quick_notes;
	if($clsBilling->updateOne($billing_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_load_edit_inline_field(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$clsISO,$profile_id;
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	###
	$html = $html_input = "";
	$p_id = (int) Input::post('p_id',0);
	$p_field = Input::post('p_field', "");
	$p_action = Input::post('p_action','_open');
	###
	$more_information = $clsBilling->getOneField('more_information', $p_id);
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) : array();
	###
	if($p_action=='_save'){
		$p_value = Input::post('p_value');
		//$clsISO->print_pre($p_value); die();
		$more_information[$p_field] = $p_value;
		$clsBilling->updateOne($p_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		));
	}
	if($p_field=='notes'){
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.= isset($more_information[$p_field]) && !empty($more_information[$p_field]) ? $more_information[$p_field] : "";
			$html.='<a class="editInlineField" onClick="$Core.billing.edit_inline_field(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<textarea cols="255" rows="2" class="form-control edit_profile_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'">'.$more_information[$p_field].'</textarea>';
		}
	} else if(in_array($p_field, ['sale_date','sale_policy_date'])){
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.= isset($more_information[$p_field]) && !empty($more_information[$p_field]) ? $more_information[$p_field] : "";
			$html.='<a class="editInlineField" onClick="$Core.billing.edit_inline_field(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<div class="input-group-date">
				<input class="form-control isodatepicker no-border-right edit_profile_field_'.$p_field.'_'.$p_id.'" value="'.$more_information[$p_field].'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" placeholder="dd/mm/yyyy" />
			</div>';
		}
	} else {
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.= isset($more_information[$p_field]) && !empty($more_information[$p_field]) ? $more_information[$p_field] : "";
			$html.='<a class="editInlineField" onClick="$Core.billing.edit_inline_field(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$placeholder = "";
			if($p_field=='customer_name') $placeholder = "Nhập tên khách hàng";
			if($p_field=='customer_phone') $placeholder = "Nhập tên điện thoại khách hàng";
			if($p_field=='customer_email') $placeholder = "Nhập tên e-mail khách hàng";
			if($p_field=='stock_resource') $placeholder = "Nguồn căn";
			if($p_field=='commission') $placeholder = "Hoa hồng";
			$html_input = '<input class="form-control edit_profile_field_'.$p_field.'_'.$p_id.'" value="'.$more_information[$p_field].'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" placeholder="'.$placeholder.'" />';
		}
	}
	if($p_action=='_cancel' || $p_action=='_save'){
		echo $html; die();
	} else {
		$html = '<div class="d-flex input-group inline-editor-container w-px-300">
			'.$html_input.'
			<div class="btn-group">
				<button class="btn px-2 rounded-0 btm-sm btn-outline-success" onClick="$Core.billing.save_edit_inline_field(this, event)" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$core->makeIcon('check').'</button>
				<button class="btn px-2 btm-sm btn-outline-danger" onClick="$Core.billing.cancel_edit_inline_field(this, event)" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$core->makeIcon('undo').'</button>
			</div>
		</div>';
	}
	// Return
	echo $html; die();
}
function default_autosave_inline_field(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$clsISO,$profile_id;
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$p_id = (int) Input::post('p_id',0);
	$p_field = Input::post('p_field');
	$p_value = Input::post('p_value');
	//$clsISO->print_pre($p_value); die();
	$more = array();
	$more_information = $clsBilling->getOneField('more_information', $p_id);
	$more_information = $clsISO->to_array_json($more_information);
	if(in_array($p_field, array('estimate_date','contract_date'))){
		$p_value = !empty($p_value) ? $clsISO->toTime($p_value) : 0;
		$more[$p_field] = $p_value;
	} else {
		$more_information[$p_field] = $p_value;
	}
	// $clsBilling->setDebug(true);
	if($clsBilling->updateOne($p_id, array_merge($more, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	)))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_list_logs(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	$billing_id = Input::post('billing_id', 0);
	###
	$logs = $clsBilling->getOneField('logs', $billing_id);
	$list_logs = !empty($logs) ? @json_decode(html_entity_decode($logs), true) : array();
	
	$html = '';
	if(!empty($list_logs)){
		$html.= '<ul class="logs">';
		foreach($list_logs as $key => $val){
			$html.= '<li>'.$clsISO->convertTimeToText($val['reg_date'], true).": ".$val['content'].'</li>';
		}
		$html.= '<ul>';
	} else {
		$html .= '<div class="p-4 text-center">
			<img src="https://cdn-icons-png.flaticon.com/512/833/833602.png" width="50px" />
			<p class="text-muted mt-2">Chưa có lịch sử giao dịch</p>
		</div>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_holder_stock(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$project_id = Input::post('project_id', 0);
	$html = '<select class="iso-selectizeLiveSearch required w-100" placeholder="Tìm căn hộ" data-url="'.PCMS_URL.'/index.php?mod=home&act=load_stock_search&project_id='.$project_id.'" name="stock_id" data-optgroup="false"></select>';
	// Return
	echo $html; die();
}
function default_load_stock_search(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsStock = new Stock();
	$project_id = (int) Input::get('project_id', 0);
	$keysearch = Input::post('keysearch', "");
	####
	$cond = "`is_trash`=0";
	if($project_id > 0){
		$cond.= "  and `project_id`='{$project_id}'";
	}
	####
	$field = "{$clsStock->pkey},`ms_code`";
	$list_stocks = $clsStock->getAll("{$cond} and (`ms_code` like '%{$keysearch}%' or REPLACE(`ms_code`,'.','')='{$keysearch}')", $field);
	###
	$results = array();
	if(!empty($list_stocks)){
		foreach($list_stocks as $key => $val){
			$results[] = array(
				'id' => $val[$clsStock->pkey],
				'text' => $val['ms_code']
			);
		}
	}
	// Return
	echo json_encode($results); 
	die();
}
function default_pop_save_billing(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsStockHug = new StockHug();
	$billing_id = Input::post('billing_id');
	$billing_type = Input::post('billing_type');
	$billing_code = Input::post('billing_code');
	$deposit_date = Input::post('deposit_date');
	$project_id = (int) Input::post('project_id', 0);
	$stock_resource = Input::post('stock_resource');
	$ms_date = Input::post('ms_date');
	$estimate_date = Input::post('estimate_date');
	$contract_date = Input::post('contract_date');
	$totalgrand = Input::post('totalgrand');
	$stock_id = (int) Input::post('stock_id', 0);
	$is_alliance = (int) Input::post('is_alliance', 0);
	$is_fullscore = (int) Input::post('is_fullscore', 0);
	$sale_agency_id = (int) Input::post('sale_agency_id', 0);
	$billing_search = sprintf('|%s_%s|', $project_id, $billing_type);
	$deposit_date = !empty($deposit_date) ? $clsISO->toTime($deposit_date) : 0;
	$ms_date = !empty($ms_date) ? $clsISO->toTime($ms_date) : 0;
	$estimate_date = !empty($estimate_date) ? $clsISO->toTime($estimate_date) : 0;
	$contract_date = !empty($contract_date) ? $clsISO->toTime($contract_date) : 0;
	###
	$staff_id = Input::post('staff_id');
	$stock_code = Input::post('stock_code');
	if($stock_id > 0){
		$clsStock = new Stock();
		$stock_code = $clsStock->getOneField('ms_code', $stock_id);
	}
	###
	$msg = "_error";
	if($billing_id > 0){
		$oBilling = $clsBilling->getOne($billing_id);
		$more_information = $oBilling['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$more_information['stock_resource'] = $stock_resource;
		$more_information['sale_agency_id'] = $sale_agency_id;
		$more_information['customer_name'] = Input::post('customer_name');
		$more_information['customer_email'] = Input::post('customer_email');
		$more_information['customer_phone'] = Input::post('customer_phone');
		$more_information['staff_notes'] = Input::post('staff_notes');
		if($clsBilling->updateOne($billing_id, array(
			'billing_code' => $billing_code,
			'billing_type' => $billing_type,
			'billing_search' => $billing_search,
			'staff_id' => $staff_id,
			'director_sale_id' => Input::post('director_sale_id'),
			'director_block_id' => Input::post('director_block_id'),
			'partner_id' => Input::post('partner_id'),
			'deposit_date' => $deposit_date,
			'ms_date' => $ms_date,
			'ms_value' => Input::post('ms_value',0),
			'estimate_date' => $estimate_date,
			'contract_date' => $contract_date,
			//'customer_id' => (int) Input::post('customer_id', 0),
			'project_id' => (int) Input::post('project_id', 0),
			'billing_source_id' => (int) Input::post('billing_source_id', 0),
			//'stock_id' => $stock_id,
			'stock_code' => $stock_code,
			'is_alliance' => $is_alliance, // Liên minh bán
			'is_fullscore' => $is_fullscore,
			'contract_status_id' => (int) Input::post('contract_status_id', 0),
			'totalgrand' => $clsISO->processSmartNumber($totalgrand),
			//'commission' => (int) Input::post('commission', 0),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			//'notes'	=> Input::post('notes'),
			'upd_date' => time(),
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	} else {
		$more_information = array();
		$billing_id = $clsBilling->getMaxId();
		$more_information['stock_resource'] = $stock_resource;
		$more_information['sale_agency_id'] = $sale_agency_id;
		$more_information['customer_name'] = Input::post('customer_name');
		$more_information['customer_email'] = Input::post('customer_email');
		$more_information['customer_phone'] = Input::post('customer_phone');
		$more_information['staff_notes'] = Input::post('staff_notes');
		//$clsBilling->setDebug(true);
		if($clsBilling->insert(array(
			$clsBilling->pkey => $billing_id,
			'billing_code' => $billing_code,
			'billing_type' => $billing_type,
			'billing_search' => $billing_search,
			'staff_id' => $staff_id,
			'director_sale_id' => Input::post('director_sale_id'),
			'director_block_id' => Input::post('director_block_id'),
			'partner_id' => Input::post('partner_id'),
			'deposit_date' => $deposit_date,
			'ms_date' => $ms_date,
			'ms_value' => Input::post('ms_value',0),
			'estimate_date' => $estimate_date,
			'contract_date' => $contract_date,
			//'customer_id' => (int) Input::post('customer_id', 0),
			'project_id' => (int) Input::post('project_id', 0),
			'billing_source_id' => (int) Input::post('billing_source_id', 0),
			// 'stock_id' => $stock_id,
			'stock_code' => $stock_code,
			'is_alliance' => $is_alliance, // Liên minh bán
			'is_fullscore' => $is_fullscore, // Full điểm
			'contract_status_id' => (int) Input::post('contract_status_id'),
			'totalgrand' => $clsISO->processSmartNumber($totalgrand),
			//'commission' => (int) Input::post('commission', 0),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'notes'	=> $notes,
			'reg_date' => $deposit_date,
			'upd_date' => $deposit_date,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))){
			$msg = "_success";			
			$fpoint = new FPoint();
			$fpoint->insert_billing_LPoint($billing_id);
			// $fpoint->insertPoint(sprintf('billing_type_%s', $billing_type), $staff_id, $billing_id, $stock_code);
			$logs[$clsISO->getUniqid()] = array(
				'reg_date' => time(),
				'profile_id' => $profile_id,
				'content' => sprintf('<strong>%s</strong> tạo giao dịch mới </strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $billing_code
				)
			);
			$clsBilling->updateOne($billing_id, array(
				'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE)
			));
			if($billing_type == _BILLING_TYPE_MWF_ID && !empty($stock_code)){
				$oneStockHug = $clsStockHug->getByCond("`stock_code`='{$stock_code}'");
				$clsStockHug->updateOne($oneStockHug[$clsStockHug->pkey], array(
					'sale_date' => time(),
					'deposit_date' => $deposit_date,
					'sale_agency_id' => $sale_agency_id,
					'status_id' => _STOCK_HUG_STATUS_SOLD_ID
				));
			}
			//add data customer
			$clsClient = new Client();
			if($more_information['customer_email'] != "" && $more_information['customer_phone'] != ""){
				$check_exist_customer = $clsClient->getByCond("email='".$more_information['customer_email']."' or phone='".$more_information['customer_phone']."'");
				if(!empty($check_exist_customer)){
					$billing_ids = $clsISO->getArrayByTextSlash($check_exist_customer['billing_id']);
					$staff_ids = $clsISO->getArrayByTextSlash($check_exist_customer['staff_id']);
					$billing_ids[] = $billing_id;
					$staff_ids[] = $staff_id;
					$clsClient->updateOne($check_exist_customer['client_id'],["billing_id" => $clsISO->makeSlashListFromArrayRoot($billing_ids), "staff_id" => $clsISO->makeSlashListFromArrayRoot($staff_ids)]);
				}else{
					$arr_client = [
						"client_id"		=>	$clsClient->getMaxID(),
						"email"			=>	$more_information['customer_email'],
						"phone"			=>	$more_information['customer_phone'],
						"full_name"		=>	$more_information['customer_name'],
						"slug"			=>	$clsISO->make_slug($more_information['customer_name']),
						"billing_id"	=>	"|".$billing_id."|",
						"staff_id"		=>	"|".$staff_id."|",
						"contract_date"	=>	$contract_date,
						"reg_date"		=>	time(),
					];
					$clsClient->insert($arr_client);
				}
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_load_billing_done(){
    global $profile_id,$core,$clsISO,$clsUser,$clsProperty;
	###
	$clsBilling = new Billing();
    $billing_id = Input::request('billing_id',0);
	$field = "contract_date,estimate_date,otp_date,contract_status_id";
	$oBilling = $clsBilling->getOne($billing_id, $field);
	$otp_date = $oBilling['otp_date'];
	$contract_date = $oBilling['contract_date'];
	$estimate_date = $oBilling['estimate_date'];
	$contract_status_id = $oBilling['contract_status_id'];
	###
    $html = '<form class="p-2" method="post">
		<div class="form-group mb-2">
         	<label class="col-form-label pb-1">Ngày ký OTP</label>
			<input type="date" class="form-control" value="'.(!empty($otp_date)?date('Y-m-d',$otp_date):"").'" 
				name="otp_date" maxlength="255" placeholder="dd/mm/YYYY" />
        </div>
		<div class="form-group mb-2">
         	<label class="col-form-label pb-1">Ngày dự kiến ký HĐMB</label>
			<input type="datetime-local" class="form-control " value="'.(!empty($estimate_date)?date('Y-m-d\TH:i',$estimate_date):"").'" 
				name="estimate_date" maxlength="255" placeholder="dd/mm/YYYY" />
        </div>
        <div class="form-group mb-2">
         	<label class="col-form-label pb-1">Ngày ký HĐMB</label>
			<input type="date" class="form-control" value="'.(!empty($contract_date)?date('Y-m-d',$contract_date):"").'" 
				name="contract_date" maxlength="255" placeholder="dd/mm/YYYY" />
        </div>
		<div class="form-group mb-2">
         	<label class="col-form-label pb-1">Tình trạng</label>
			<select class="form-control form-select" name="contract_status_id">
				'.$clsProperty->getSelectByProperty('_STATUS_CONTRACT',$contract_status_id).'
			</select>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-primary" onclick="$Core.billing.update_field(this,event);" 
			billing_id="'.$billing_id.'">'.$core->makeIcon('check', 'Cập nhật').' </button>
        </div>
    </form>';
    // Return
    echo  $html; die();
}
function default_update_field(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$billing_id = (int) Input::post('billing_id');
	$otp_date = Input::post('otp_date');
	$estimate_date = Input::post('estimate_date');
	$contract_date = Input::post('contract_date');
	$otp_date = Input::post('otp_date');
	$otp_date_in = !empty($otp_date) ? $clsISO->toTime($otp_date) : 0;
	$estimate_date_in = !empty($estimate_date) ? $clsISO->toTime($estimate_date) : 0;
	$contract_date_in = !empty($contract_date) ? $clsISO->toTime($contract_date) : 0;
	$contract_status_id = (int) Input::post('contract_status_id', 0);
	if(!$contract_status_id) $contract_status_id = _CONTRACT_STATUS_WAIT_ID;
	##
	$msg = "_error";
	$oBilling = $clsBilling->getOne($billing_id, "`billing_type`,`ms_code`");
	$billing_type = $oBilling['billing_type'];
	$stock_code   = $oBilling['stock_code'];
	if($clsBilling->updateOne($billing_id, array(
		'otp_date' => $otp_date_in,
		'estimate_date' => $estimate_date_in,
		'contract_date' => $contract_date_in,
		'contract_status_id' => $contract_status_id
	))){
		$msg = "_success";
		$clsStockHug = new StockHug();
		if($billing_type == _BILLING_TYPE_MWF_ID && !empty($stock_code)){
			$oneStockHug = $clsStockHug->getByCond("`stock_code`='{$stock_code}'", $clsStockHug->pkey);
			$clsStockHug->updateOne($oneStockHug[$clsStockHug->pkey], array(
				'otp_date' => $otp_date_in
			));
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'estimate_date' => ($estimate_date_in > 0) ? $estimate_date : "---",
		'contract_date' => ($contract_date_in > 0) ? $contract_date : "---"
	));
}
function default_list_staff(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	####
	$_results = array();
	$holderG = Input::get('holderG',"all");
	$cond= "is_trash=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
	if($holderG == 'permiss'){
		if($clsISO->checkPermissionGroup('DIRECTOR')){
			// GD
		} else if($clsISO->checkPermissionGroup('BO')){
			// BO
		} else if($clsISO->checkPermissionGroup('SALE')) {
			$department_id = $oneProfile['department_id'];
			$cond.= " and (`department_id`='{$department_id}' 
				or `list_department_id` like '%|{$department_id}|%'
			)";
		}
	}
	$field = "{$clsProfile->pkey},code,full_name";
	$list_staffs= $clsProfile->getAll("{$cond} order by reg_date DESC", $field);
	if(!empty($list_staffs)){
		foreach($list_staffs as $key => $val){
			$_results[] = array(
				'id' => $val[$clsProfile->pkey],
				'slug' => $core->replaceSpace($val['full_name']),
				'text' => sprintf('%s-%s', $val['code'], $val['full_name'])
			);
		}
	}
	// return
	echo json_encode($_results); die();
}
function default_list_project(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsProject = new Project();
	####
	$_results = array();
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("is_trash=0 order by reg_date ASC", $field);
	if(!empty($list_projects)){
		foreach($list_projects as $key => $val){
			$_results[] = array(
				'id' => $val[$clsProject->pkey],
				'text' =>$val['title']
			);
		}
	}
	// return
	echo json_encode($_results); die();
}
function default_list_customer(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsCustomer = new Customer();
	####
	$_results = array();
	$field = "{$clsCustomer->pkey},name";
	$list_customers = $clsCustomer->getAll("is_trash=0 order by reg_date ASC", $field);
	if(!empty($list_customers)){
		foreach($list_customers as $key => $val){
			$_results[] = array(
				'id' => $val[$clsCustomer->pkey],
				'text' =>$val['name']
			);
		}
	}
	// return
	echo json_encode($_results); die();
}
function default_open_customer(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id;
	$clsCity = new City();
	$clsCountry = new Country();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCampaign = new Campaign();
	###
	$field = "{$clsCampaign->pkey},title";
	$list_campaigns = $clsCampaign->getAll("`is_trash`=0 and `campaign_type`='_campaign' 
		and `user_id`='{$profile_id}' order by `reg_date` DESC", $field);
	$smarty->assign('list_campaigns', $list_campaigns); unset($list_campaigns);
	###
	$uid = Input::post('uid');
	$openFrom = Input::post('openFrom','_global');
	$smarty->assign('uid', $uid);
	$smarty->assign('openFrom', $openFrom);
	$smarty->assign('clsCity', $clsCity);
	$smarty->assign('clsCountry', $clsCountry);
	$smarty->assign('clsProperty', $clsProperty);
	$_ss_storage = array(
		'status_id' => 0,
		'blocktype_id' => 0,
		'list_block_id' => array(),
		'list_bedroom_id' => array(),
		'resource_id' => _CRM_RESOURCE_ADS_ID
	);
	if(vnSessionExist('_ss_storage')){
		$_ss_storage = vnSessionGetVar('_ss_storage');
	}
	$smarty->assign('_ss_storage', $_ss_storage);
	// Return
	$html = $core->build('_ajax.customer.tpl');
	echo json_encode(array(
		'uid' => 'modal'.$clsISO->getUniqid(),
		'html' => $html
	)); die();
}
function default_pop_save_customer(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $oneProfile,$profile_id,$clsProfile;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	$customer_id = (int) Input::post('customer_id', 0);
	###
	$name = Input::post('name');
	$address = Input::post('address');
	$phone = Input::post('phone');
	$email = Input::post('email');
	$notes = Input::post('notes');
	$begin_need = Input::post('begin_need');
	$admin_id = (int) Input::post('admin_id', 0);
	$status_id = (int) Input::post('status_id', 0);
	$list_block_id = Input::post('list_block_id');
	$list_share_id  = Input::post('list_share_id');
	$list_bedroom_id = Input::post('list_bedroom_id');
	$list_campaign_id = Input::post('list_campaign_id');
	$list_bedroom_id = !empty($list_bedroom_id) 
		? $clsISO->makeSlashListFromArray($list_bedroom_id) : "";
	$list_block_id = !empty($list_block_id) 
		? $clsISO->makeSlashListFromArray($list_block_id) : "";
	$list_campaign_id = !empty($list_campaign_id) 
		? $clsISO->makeSlashListFromArray($list_campaign_id) : "";
	$auto_create_followups = (int) Input::post('auto_create_followups', 0);
	###
	$more_information = $more = array();
	$list_user_notify = $list_share_id;
	if($admin_id != $profile_id){
		$list_share_id[] = $profile_id;
		$more_information['logs'][$clsISO->getUniqid()] = array(
			'_type' => 'assign',
			'from_id' => $profile_id,
			'to_id' => $admin_id,
			'status_id' => $status_id,
			'reg_date' => time()
		);
	}
	###
	if(!empty($notes)){
		$more['notes'] = json_encode(array(
			[$clsISO->getUniqid()] => array(
				'content' => $notes,
				'reg_date' => time(),
				'upd_date' => time(),
				'user_id' => $profile_id,
				'user_id_update' => $profile_id
			)
		), JSON_UNESCAPED_UNICODE);
	}
	###
	$msg = "_error";
	if(!empty($phone) && $clsCustomer->countItem("phone='{$phone}' and admin_id='{$admin_id}'")){
		$msg = "_duplicated";
	} else {
		$_ss_storage = array();
		foreach($_POST as $key => $val){
			if(in_array($key, array('resource_id','status_id','list_bedroom_id','blocktype_id','list_block_id'))){
				$_ss_storage[$key] = $val;
			}
		}
		vnSessionSetVar('_ss_storage', $_ss_storage);
		$customer_id = $clsCustomer->getMaxId();
		$list_share_id = !empty($list_share_id) 
			? $clsISO->makeSlashListFromArray($list_share_id) : "";
		$use_globe = !empty($list_share_id) ? 1 : 0;
		if($clsCustomer->insert(array_merge($more, array(
			$clsCustomer->pkey => $customer_id,
			'name' => $name,
			'name_slug' => $core->replaceSpace($name),
			'address' => $address,
			'phone' => $phone,
			'email' => $email,
			'admin_id' => $admin_id,
			'status_id' => $status_id,
			'use_globe' => $use_globe,
			'begin_need' => $begin_need,
			'list_share_id' => $list_share_id,
			'list_block_id' => $list_block_id,
			'list_bedroom_id' => $list_bedroom_id,
			'list_campaign_id' => $list_campaign_id,
			'blocktype_id' => Input::post('blocktype_id', 0),
			'country_id' => (int) Input::post('country_id', 0),
			'city_id' => (int) Input::post('city_id', 0),
			'resource_id' => (int) Input::post('resource_id', 0),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'reg_date' => time(),
			'upd_date' => time()
		)))){
			$msg = "_success";
			if($auto_create_followups == 1){
				$clsFollowUp = new FollowUp();
				$timer = strtotime(date('d-m-Y'));
				$list_times = array(
					'1day' => strtotime('+1 day', $timer),
					'2day' => strtotime('+2 days', $timer),
					'5day' => strtotime('+5 days', $timer),
					'15day' => strtotime('+15 days', $timer),
					'30day' => strtotime('+30 days', $timer)
				);
				foreach($list_times as $key => $date_id){
					$followup_id = $clsFollowUp->getMaxId();
					if($clsFollowUp->insert(array(
						$clsFollowUp->pkey => $followup_id,
						'type_id' => _FOLLOWUP_CALL_ID,
						'status_id' => _FOLLOWUP_STATUS_PLAN_ID,
						'customer_id' => $customer_id,
						'date_id' => $date_id,
						'intro' => 'Call liên hệ lại khách',
						'admin_id' => $admin_id,
						'user_id' => $profile_id,
						'user_id_update' => $profile_id,
						'reg_date' => time(),
						'upd_date' => time()
					))){
						$titleNoty = sprintf('Lịch hẹn <strong>%s</strong> với khách hàng <strong>%s</strong> vào lúc <strong>%s</strong>', $clsProperty->getTitle(_FOLLOWUP_CALL_ID) .": Call liên hệ lại khách", $clsCustomer->getName($customer_id), $clsISO->convertTimeToText($date_id, true));
						$clsNotify->insertNotify('FollowUp', $clsFollowUp->pkey, $followup_id, $titleNoty, $date_id, '|'.$admin_id.'|');
					}
				}
			}
			$oCustomer = $clsCustomer->getOne($customer_id);	
			if($admin_id != $profile_id){
				$titleNoty = sprintf('<strong>%s</strong> Đã giao bản phụ trách khách hàng <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($customer_id, $oCustomer));
				$clsNotify->insertNotify('Customer',$clsCustomer->pkey,$customer_id,$titleNoty,time(),"|".$admin_id."|");
				if(!empty($list_user_notify) && $admin_id > 0) {
					$list_user_notify = array_diff($list_user_notify, array($admin_id));
				}
			}
			if(!empty($list_user_notify)){
				$titleNoty = sprintf('<strong>%s</strong> giao bản liên quan tới khách hàng <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($customer_id, $oCustomer));
				$clsNotify->insertNotify('Customer',$clsCustomer->pkey,$customer_id,$titleNoty,time(),$list_user_notify);
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'id' => $customer_id,
		'name' => $name
	)); die();
}
function default_open_news(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id;
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$news_id = (int) Input::post('news_id', 0);
	$cat_id = (int) Input::post('cat_id', 0);
	$gid = Input::post('gid', "");
	$action = Input::post('action', "_detail");
	$uid = $clsISO->getUniqid();
	#
	$is_registed = 0;
	$oneNews = $events_config = $regis_info = $list_images = array();
	if($news_id > 0 || $holderG=='_detail'){
		$oneNews = $clsNews->getOne($news_id);
		$images = $oneNews['images'];
		$regis_info = $oneNews['regis_info'];
		$events_config = $oneNews['events_config'];
		$regis_info = !empty($regis_info) 
			? json_decode(html_entity_decode($regis_info), true) 
			: array();
		
		if($oneNews['cat_id']==_NEWS_EVENT_CAT_ID 
			&& !empty($regis_info) && @array_key_exists($profile_id, $regis_info)){
			$is_registed = 1;
		}	
		//$clsISO->print_pre($profile_id); die();
		$events_config = !empty($events_config) 
			? json_decode(html_entity_decode($events_config), true) 
			: array();
		$list_images = !empty($images) 
			? json_decode(html_entity_decode($images), true) : array();
		if($action=='_detail'){
			$user_id = $oneNews['user_id'];
			$db_profile = $clsProfile->getProfile($user_id);
			$oneNews['db_profile'] = $db_profile;
			$status_liked = $clsNews->checkLiked($news_id, $oneNews);			
			$smarty->assign('status_liked', $status_liked);
		}
		
		if($oneNews['cat_id'] == _NEWS_GRATITUDE_CAT_ID){
			$more_information = !empty($oneNews['more_informatiion']) ? $clsISO->to_array_json($oneNews['more_informatiion']) : [];
			$department_id = !empty($more_information['department_id']) ? $more_information['department_id'] : [];
			$staff_id = !empty($more_information['staff_id']) ? $more_information['staff_id'] : [];
			if(!empty($department_id)) {
				$lstDepartment = $clsProperty->getAllCache("property_id IN (".implode(",",$department_id).")",$clsProperty->pkey.",title");
				$oneNews['department'] = $lstDepartment;
			}
			if(!empty($staff_id)) {
				$lstStaff = $clsProfile->getAll("profile_id IN (".implode(",",$staff_id).")",$clsProfile->pkey.',full_name');
				$oneNews['staff'] = $lstStaff;
			}
		}
	}
	
	$smarty->assign('uid', $uid);
	$smarty->assign('cat_id', $cat_id);
	$smarty->assign('news_id', $news_id);
	$smarty->assign('gid', $gid);
	$smarty->assign('action', $action);
	$smarty->assign('oneNews', $oneNews);
	$smarty->assign('is_registed', $is_registed);
	$smarty->assign('list_images', $list_images);
	$smarty->assign('events_config', $events_config);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.news.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'list_images' => $list_images,
	)); die();
}
function default_open_list_gratitude(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id;
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$news_id = (int) Input::post('news_id', 0);
	$uid = $clsISO->getUniqid();
	#
	$is_registed = 0;
	$data = [
		'result'	=>	false,
	];
	if($news_id > 0){
		$oneNews = $clsNews->getOne($news_id);		
		$more_informatiion = !empty($oneNews['more_informatiion']) ? $clsISO->to_array_json($oneNews['more_informatiion']) : [];
		$department_id = $more_informatiion['department_id'];
		$staff_id = $more_informatiion['staff_id'];
		if(!empty($department_id)){
			$lstDepartment = $clsProperty->getAllCache("property_type='_DEPARTMENT' and property_id IN (".implode(',',$department_id).")");	
			foreach($lstDepartment as $key => $value) {
				$total_profile = $clsProfile->countItem("`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND profile_id <> '{$profile_id}' AND department_id ='".$value['property_id']."'");
				$lstDepartment[$key]['total_profile'] = $total_profile;
			}
			$smarty->assign('lstDepartment', $lstDepartment);
		}		
		if(!empty($staff_id)){
			$lstStaff= $clsProfile->getAll("is_trash=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' and profile_id <> '".$profile_id."' and profile_id IN (".implode(",",$staff_id).") order by reg_date DESC", "{$clsProfile->pkey},code,full_name");
			$smarty->assign('lstStaff', $lstStaff);
		}
	
		$smarty->assign('uid', $uid);
		$smarty->assign('news_id', $news_id);
		// Return
		$smarty->assign('core', $core);
		$html = $core->build('_ajax.lstGratitude.tpl');
		$data = [
			'result'	=>	true,
			'uid' 		=> $uid,
			'html' 		=> $html,
		];
	}
	echo json_encode($data); die();
}
function default_historyGratitude(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id;
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$news_id = (int) Input::post('news_id', 0);
	$type = Input::post('type', "dep");
	$id = (int) Input::post('id', 0);
	$uid = $clsISO->getUniqid();
	#
	$is_registed = 0;
	$data = [
		'result'	=>	false,
	];
	if($news_id > 0){
		$oneNews = $clsNews->getOne($news_id);		
		$more_informatiion = !empty($oneNews['more_informatiion']) ? $clsISO->to_array_json($oneNews['more_informatiion']) : [];
		$gratitude = $more_informatiion['gratitude'];
		$arr_history = $arr_cache = [];
		$total_score_gratitude = 0;
		if($type == "dep") {
			$title = $clsProperty->getTitle($id);
		}else{
			$title = $clsProfile->getFullName($id);
		}
		foreach($gratitude as $key => $value) {
			if(!isset($arr_cache[$value['user_id']])) {
				$arr_cache[$value['user_id']] = $clsProfile->getFullName($value['user_id']);
			}
			if($type == "dep") {
				$scoreDep = $value['scoreDep'];
				if(isset($scoreDep[$id])){
					$arr_history[] = [
						"full_name"	=>	$arr_cache[$value['user_id']],
						"score"		=>	$scoreDep[$id],
						"reg_date"		=>	$clsISO->formatDate($value['reg_date'],4)
					];
					$total_score_gratitude += (int)$scoreDep[$id];
				}
				
			}else if($type == "staff") {
				$scoreEmp = $value['scoreEmp'];
				if(isset($scoreEmp[$id])){
					$arr_history[] = [
						"full_name"	=>	$arr_cache[$value['user_id']],
						"score"		=>	$scoreEmp[$id],
						"reg_date"		=>	$clsISO->formatDate($value['reg_date'],4)
					];
					$total_score_gratitude += (int)$scoreEmp[$id];
				}
			}
		}
		
	
		$smarty->assign('type', $type);
		$smarty->assign('id', $id);
		$smarty->assign('title', $title);
		$smarty->assign('uid', $uid);
		$smarty->assign('news_id', $news_id);
		$smarty->assign('arr_history', $arr_history);
		$smarty->assign('total_score_gratitude', $total_score_gratitude);
		// Return
		$smarty->assign('core', $core);
		$html = $core->build('_ajax.historyGratitude.tpl');
		$data = [
			'result'	=>	true,
			'uid' 		=> $uid,
			'html' 		=> $html,
		];
	}
	echo json_encode($data); die();
}
function default_save_gratitude(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$news_id = (int) Input::post('news_id', 0);
	$action = Input::post('action', "_open");
	$uid = $clsISO->getUniqid();
//	var_dump($_POST);die;
	#
	$is_registed = 0;
	$data = [
		'result'	=>	false,
	];
	if($news_id > 0){
		$oneNews = $clsNews->getOne($news_id);			
		$more_informatiion = !empty($oneNews['more_informatiion']) ? $clsISO->to_array_json($oneNews['more_informatiion']) : [];
		
		if($action == "_open") {	
			$is_singer = (int) Input::post('is_singer', 0);
			$id = (int) Input::post('id', 0);
			$type = Input::post('type', "");
			$key_dep = Input::post('key_dep', []);
			$key_staff = Input::post('key_staff', []);
			if($is_singer == 0) {
				if(!empty($key_dep)){
					$lstDepartment = $clsProperty->getAllCache("property_type='_DEPARTMENT' and property_id IN (".implode(',',$key_dep).")");	
					foreach($lstDepartment as $key => $value) {
						$total_profile = $clsProfile->countItem("`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND profile_id <> '{$profile_id}' AND department_id ='".$value['property_id']."'");
						$lstDepartment[$key]['total_profile'] = $total_profile;
					}
					$smarty->assign('lstDepartment', $lstDepartment);
				}
				if(!empty($key_staff)){
					$lstStaff= $clsProfile->getAll("is_trash=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' and profile_id IN (".implode(",",$key_staff).")", "{$clsProfile->pkey},code,full_name");
					$smarty->assign('lstStaff', $lstStaff);
				}	
			}elseif($is_singer == 1 && $id > 0 && $type != ""){
				if($type == 'dep') {
					$lstDepartment = $clsProperty->getAllCache("property_type='_DEPARTMENT' and property_id ='{$id}'");	
					foreach($lstDepartment as $key => $value) {
						$total_profile = $clsProfile->countItem("`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND profile_id <> '{$profile_id}' AND department_id ='".$value['property_id']."'");
						$lstDepartment[$key]['total_profile'] = $total_profile;
					}
					$smarty->assign('lstDepartment', $lstDepartment);
				}else{
					$lstStaff= $clsProfile->getAll("is_trash=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' and profile_id ='{$id}'", "{$clsProfile->pkey},code,full_name");
					$smarty->assign('lstStaff', $lstStaff);
				}
			}
			$smarty->assign('uid', $uid);
			$smarty->assign('news_id', $news_id);
			$html = $core->build('_ajax.save_gratitude.tpl');
			$data = [
				'result'	=>	true,
				'uid' 		=> $uid,
				'html' 		=> $html,
			];
		}else if($action == '_save'){
			$total_Lpoint = $oneProfile['total_Lpoint'];
			$scoreDep = Input::post("scoreDep",[]);
			$scoreEmp = Input::post("scoreEmp",[]);
			foreach($scoreDep as $key => $value) {
				$scoreDep[$key] = str_replace(".","",$value);
			}
			foreach($scoreEmp as $key => $value) {
				$scoreEmp[$key] = str_replace(".","",$value);
			}
			$department_id = array_keys($scoreDep);
			$staff_id = array_keys($scoreEmp);			
			
//			var_dump($department_id,$staff_id);die;

			$total_score_minus = 0; //tong diem tru
			$arr_score_total = $lstTotalProfile = []; //mang nhan vien duoc cong diem
			if(!empty($department_id)) { // co chon phong ban
				foreach ($department_id as $department) {
					$cond = " `is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND profile_id <> '{$profile_id}' AND department_id ='{$department}' ";
					if(!empty($staff_id)) { // neu chon nhan vien co thuoc phong ban thi khong cong diem theo phong ban
						$cond .= " AND profile_id NOT IN (".implode(",",$staff_id).") ";
					}
					$lst_profile = $clsProfile->getAll($cond,$clsProfile->pkey.',full_name,total_Lpoint');
					
					$lstTotalProfile = array_merge($lstTotalProfile,$lst_profile);
					
					$total_score_minus += (count($lst_profile) * (int)$scoreDep[$department]);
					if($department != _USER_DIRECTOR_ID) { // neu khong phai la phong BÐH thi lay danh sách diem cong cua nhan vien phong do
						foreach($lst_profile as $key => $value) {
							$arr_score_total[$value['profile_id']] = $value;
							$arr_score_total[$value['profile_id']]["score_plus"] = (int)$scoreDep[$department];
						}
					}			
				}			
			}

			if(!empty($staff_id)) { // co chon nhan vien
				$cond = " `is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND profile_id <> '{$profile_id}' AND profile_id IN (".implode(",",$staff_id).")";
				$lst_profile = $clsProfile->getAll($cond,$clsProfile->pkey.',full_name,total_Lpoint,role_id');
				foreach ($lst_profile as $key => $value) {
					$total_score_minus += (int)$scoreEmp[$value['profile_id']];
					if(!in_array($value['role_id'], array(_ROLE_GD_MANAGER, _ROLE_PGD_MANAGER))) {
						$arr_score_total[$value['profile_id']] = $value;
						$arr_score_total[$value['profile_id']]["score_plus"] = (int)$scoreEmp[$value['profile_id']];
					}
				}
				$lstTotalProfile = array_merge($lstTotalProfile,$lst_profile);
			};
			if($total_score_minus > $total_Lpoint && !$clsISO->checkSupper()) { // neu khong du diem va khong phai BÐH
				$data = [
					"result"		=>	false,
					"total_score"	=>	$total_Lpoint,
					"type"			=>	"minus_error"
				];
				echo json_encode($data);die;
			}
			$more_informatiion["gratitude"][] = [
				"user_id"	=>	$profile_id,
				"scoreDep"	=>	$scoreDep,
				"scoreEmp"	=>	$scoreEmp,
				"score_minus"	=>	$total_score_minus,
				"reg_date"	=>	time(),
			];
			$clsNews->updateOne($news_id,["more_informatiion" => json_encode($more_informatiion)]);
			foreach($arr_score_total as $key => $value) { // Cong diem loyalty cho doi tuong duoc chon
				$clsProfile->updateOne($value["profile_id"], [
					"total_Lpoint"	=>	(int)$value['total_Lpoint']+(int)$value['score_plus']
				]);
			}
			if(!$clsISO->checkSupper()) { // neu khong phai BDH thi tru diem loyalty
				$clsProfile->updateOne($profile_id, [
					"total_Lpoint"	=>	(($total_Lpoint-$total_score_minus) > 0) ? $total_Lpoint-$total_score_minus : 0
				]);
			}
			
			$arr_profile = [];
			foreach($lstTotalProfile as $key => $value){
				$arr_profile[] = $value['profile_id'];
			}
//			var_dump($arr_profile);die;
			$clsNotify = new Notify();
			$titleNoty = sprintf('<strong>%s</strong> đã tri ân đên bạn <strong>%s</strong>', $clsProfile->getFullName($profile_id), $title);
			$clsNotify->insertNotify('Gratitude',$clsNews->pkey, $news_id, $titleNoty, time(), $arr_profile);
			$data = [
				"result"		=>	true,
				"type"			=>	"add",
			];
		}	
	}
	echo json_encode($data); die();
}
function default_pop_save_news(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id;
	$clsNews = new News();
	$clsProfile = new Profile();
	$fpoint = new FPoint();
	$news_id = (int) Input::post('news_id', 0);
	$cat_id = (int) Input::post('cat_id', 0);
	$title = Input::post('title');
	$content = Input::post('content');
	$images = Input::post('images');
	$more = array();
	if($cat_id==_NEWS_EVENT_CAT_ID){
		$location = Input::post('location');
		$start_date = Input::post('start_date');
		$end_date = Input::post('end_date');
		$start_date = !empty($start_date) ? $clsISO->toTime($start_date) : 0;
		$end_date = !empty($end_date) ? $clsISO->toTime($end_date) : 0;
		$is_staff_regis = Input::post('is_staff_regis', 0);
		$is_cusotmer_regis = Input::post('is_cusotmer_regis', 0);
		$more = array(
			'events_config' => json_encode( array(
				'location' => $location,
				'start_date' => $start_date,
				'end_date' => $end_date,
				'is_staff_regis' => $is_staff_regis,
				'is_cusotmer_regis' => $is_cusotmer_regis
			), JSON_UNESCAPED_UNICODE),
			'start_date' => $start_date,
			'end_date' => $end_date
		);
	}
	$msg = "_error";
	
	if($news_id > 0){
		$oneNews = $clsNews->getOne($news_id);
		$more_information = !empty($oneNews["more_informatiion"]) ? $clsISO->to_array_json($oneNews["more_informatiion"]) : [];
		if($cat_id == _NEWS_GRATITUDE_CAT_ID) {
			$department_id = Input::post("department_id",[]);
			$staff_id = Input::post("staff_id",[]);
			$more_information['department_id'] = $department_id;
			$more_information['staff_id'] = $staff_id;
			$more = array(
				'more_informatiion' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			);
		}
		if($clsNews->updateOne($news_id, array_merge($more, array(
			'cat_id' => $cat_id,
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'content' => $content,
			'images' => json_encode($images, JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update' => $profile_id,
		)))){
			$msg = '_success';
		}
	} else {		
		if($cat_id == _NEWS_GRATITUDE_CAT_ID) {
			$department_id = Input::post("department_id",[]);
			$staff_id = Input::post("staff_id",[]);
			$more = array(
				'more_informatiion' => json_encode( [
					"department_id"	=>	$department_id,
					"staff_id"		=>	$staff_id,
				], JSON_UNESCAPED_UNICODE)
			);
		}
		$news_id = $clsNews->getMaxId();
		if($clsNews->insert( array_merge($more, array(
			$clsNews->pkey => $news_id,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'cat_id' => $cat_id,
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'order_no' => $clsNews->getMaxId(),
			'content' => $content,
			'images' => json_encode($images, JSON_UNESCAPED_UNICODE),
			'reg_date' => time()
		)))){
			$msg = '_success';
			$fpoint->insertPoint('created_news', $profile_id, $news_id, $title);
			
			$lstProfile = $clsProfile->getAll("is_active=1 and is_verified=1 and is_trash=0",$clsProfile->pkey);
			$arr_profile = [];
			foreach($lstProfile as $key => $value){
				$arr_profile[] = $value['profile_id'];
			}
			$clsNotify = new Notify();
			$titleNoty = sprintf('<strong>%s</strong> Đã đăng <strong>%s</strong>', $clsProfile->getFullName($profile_id), $title);
			$clsNotify->insertNotify('News',$clsNews->pkey, $news_id, $titleNoty, time(), $arr_profile);
		}
	}
	// Return
	echo $msg; die();
}
function default_delete_news(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$oneProfile,$clsISO;
	$clsNews = new News();
	
	$msg = '_error';
	$news_id = (int) Input::post('news_id', 0);
	$oneNews = $clsNews->getOne($news_id);
	if($clsNews->deleteOne($news_id)){
		$msg = '_success';
		$images = $clsISO->to_array_json($oneNews['images']);
		if(!empty($images)) {
			foreach($images as $image) {
				if(file_exists(ABSPATH . $image)){
					unlink(ABSPATH . $image);
				}
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_delete_share(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$oneProfile;
	
	$msg = '_error';
	$clsShare = new Share();
	$share_id = (int) Input::post('share_id', 0);
	$images = $clsShare->getOneField('images', $share_id);
	$images = !empty($images) 
		? json_decode(html_entity_decode($images), true) 
		: array();
	if($clsShare->deleteOne($share_id)){
		if(!empty($images)){
			foreach($images as $img){
				if(!empty($img) && file_exists(ROOTPATH . $img)){
					@unlink(ROOTPATH . $img);
				}
			}
		}
		$msg = '_success';
	}
	// Return
	echo $msg; die();
}
/** COMMENT */
function default_add_comment(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$oneProfile;
	$clsISO = new ISO();
	$clsProfile = new Profile();
	$clsComment = new Comment();
	$msg = '_error';
	if(isset($_POST['submit']) && $_POST['submit']=='comment'){
		$timer = time();
		$holderG 	= Input::post('holderG', "_comment");
		$table_id 	= (int) Input::post('table_id', 0);
		$clsTable 	= Input::post('clsTable', 'News');
		$parent_id 	= (int) Input::post('parent_id', 0);
		$message 	= Input::post('message', "");
		$type 		= Input::post('type', "text");
		$comment_id = $clsComment->getMaxId();
		#
		$comment = $message;
		$is_image = 0;
		$link_image = "";
		if($type == "file"){
			if(is_uploaded_file($_FILES['image']['tmp_name'])){
				$clsUploadFile = new UploadFile();
				$image = $clsUploadFile->uploadItem($_FILES["image"],"/COURSE/comment",EXTENSION_FILE_UPLOAD);
				if(!empty($image) && file_exists(ROOTPATH . $image)){
					// Set the file metadata for drive
					$title = $_FILES["image"]["name"];
					$mimeType = $_FILES["image"]["type"];
					$clsGoogleDrive = new GoogleDrive();
					$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$image, GOOGLE_DRIVE_FOLDER_POSTER_ID);
					$upload_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
					if(!empty($upload_file)){
						$link_image = "https://drive.google.com/thumbnail?id=".$createdFile->getId()."&sz=w1000";
						$is_image = 1;
						$comment = "<img class='radius-4' src='".$link_image."' title='comment' style='max-width:350px'>";
					}
					@unlink(ROOTPATH . $image);
				}
			}
		}
		
		if($clsComment->insert(array(
			'comment_id' 	=> $comment_id,
			'profile_id' 	=> $profile_id,
			'table_id' 		=> $table_id,
			'clsTable' 		=> $clsTable,
			'parent_id' 	=> $parent_id,
			'content'		=> $message,
			'reg_date'		=> $timer,
			'image'			=> $link_image,
			'is_image'		=> $is_image,
			'order_no'		=> $clsComment->getMaxOrderNo(),
			'is_active'		=> 1
		))){
			$total_comments = $clsComment->getTotalComment($table_id, $clsTable);
			$msg = '_success|||<div class="awe__comment-item'.($holderG=='_reply'?' awe__comment-reply-group-'.$parent_id:'').'">
				<div class="d-flex w-100">
					<div class="awe__profile-avatar">
						<img class="rounded" src="'.$clsProfile->getAvatar($profile_id, $oneProfile).'" width="40" height="40" />
					</div>
					<div class="awe__comment-item-body">
						<div class="awe__comment-profile d-flex mb-2">
							<h4 class="awe__comment-name mr-2 fs-14">
								'.$clsProfile->getFullName($profile_id, $oneProfile).'
							</h4>
							<span class="awe__comment-time text-muted fs-12">
								'.$core->makeIcon('clock-o', $clsISO->getTimeAgo($timer)).'
							</span>
						</div>
						<div class="awe__comment-content">'.$comment.'</div>
						<div class="awe__comment-item-action d-flex align-items-center mt-1">
							<div class="reactions-wrap">
								<a href="javascript:void(0);" class="awe__comment-like-button control-action" table_id="'.$table_id.'" clsTable="'.$clsTable.'" comment_id="'.$comment_id.'">
									<span class="awe__comment-total-liked">0</span>
								</a>
								<div class="reactions-container d-none">
									<div class="reactions-list reactions-menu" data-selected="">
										<div class="reaction-item">
											<button class="reaction" data-name="like" rel="{$comment_id}"></button>
											<span class="label">Thích</span>
										</div>
										<div class="reaction-item">
											<button class="reaction" data-name="fun" rel="{$comment_id}"></button>
											<span class="label">Vui</span>
										</div>
										<div class="reaction-item">
											<button class="reaction" data-name="surprised" rel="{$comment_id}"></button>
											<span class="label">Ngạc nhiên</span>
										</div>
										<div class="reaction-item">
											<button class="reaction" data-name="sad" rel="{$comment_id}"></button>
											<span class="label">Buồn</span>
										</div>
									</div>
								</div>
							</div>
							'.($holderG=='_comment'?'<a href="javascript:void(0);" class="awe__comment-reply-button" onclick="$Core.news.comment_reply(this, event)" table_id="'.$table_id.'" clsTable="'.$clsTable.'" comment_id="'.$comment_id.'">
								<span>'.$core->makeIcon('angle-down', $core->get_Lang('Reply')).'</span>
							</a>':'').'
						</div>
						'.($holderG=='_comment'?'
						<div class="awe__comment-reply-form awe__comment-reply-form-'.$comment_id.'"></div>
						<div class="awe__comment-reply-wrapper awe__comment-reply-wrapper-'.$comment_id.'"></div>':'').'
					</div>
				</div>
			</div>|||'.$total_comments;
		}
		// Return
		echo $msg; die();
	}
}
function default_list_comments(){	
	global $smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$clsISO,$clsConfiguration;
	global $extLang;
	#- Init Object
	$clsProfile = new Profile();
	$clsComment = new Comment();
	$clsCommentVote = new CommentVote();
	$smarty->assign('clsComment', $clsReviews);
	$smarty->assign('clsCommentVote', $clsCommentVote);
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	#- Params
	$action = Input::post('action', 'reload');
	$table_id = (int) Input::post('table_id', 0);
	$clsTable = Input::post('clsTable', "Post");
	$sort_by = Input::post('sort_by', "desc");
	$smarty->assign('action', $action);
	$smarty->assign('table_id', $table_id);
	$smarty->assign('clsTable', $clsTable);
	$smarty->assign('sort_by', $sort_by);
	#
	$cond = "is_trash=0 and is_active=1 and parent_id='0' 
	and table_id='{$table_id}' and clsTable='{$clsTable}'";
	#- Pagination
	$page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 6);
	$total_record = $clsComment->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	$order_by = " order by reg_date DESC";
	if($sort_by=='asc'){
		$order_by = " order by reg_date ASC";
	}
	$list_comments = $clsComment->getAll($cond.$order_by.$limitCond);
	if(!empty($list_comments)){
		$arr_profile_cached = array();
		foreach($list_comments as $key => $val){
			$profile_id = $val['profile_id'];
			if(isset($arr_profile_cached[$profile_id])){
				$db_profile = $arr_profile_cached[$profile_id];
			} else {
				$db_profile = $clsProfile->getProfile($profile_id);
				$arr_profile_cached[$profile_id] = $db_profile;
			}
			$list_comments[$key]['db_profile'] = $db_profile;
			#
			$sql_string = "is_trash=0 and is_active='1' and table_id='{$table_id}' 
			and clsTable='{$clsTable}' and parent_id='".$val[$clsComment->pkey]."'";
			$total_replys = $clsComment->countItem($sql_string);
			$list_comments[$key]['total_replys'] = $total_replys;
		}
	}
	//$clsISO->print_pre($list_comments); die();
	$smarty->assign('list_comments', $list_comments);
	$smarty->assign('page', $page);
	$smarty->assign('per_page', $per_page);
	$smarty->assign('total_page', $total_page);
	$smarty->assign('total_record', $total_record);
	// Return
	$html = $core->build('_ajax.list_comment.tpl');
	echo @json_encode(array(
		'html' => $html,
		'action' => $action,
		'total_comments' => $clsComment->getTotalComment($table_id, $clsTable)
	)); die();
}
function default_load_replies(){	
	global $smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page,$clsISO,$clsConfiguration;
	global $extLang;
	#- Init Object
	$clsProfile = new Profile();
	$clsComment = new Comment();
	$clsCommentVote = new CommentVote();
	$smarty->assign('clsComment', $clsComment);
	$smarty->assign('clsCommentVote', $clsCommentVote);
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	#- Params
	$table_id = (int) Input::post('table_id', 0);
	$clsTable = Input::post('clsTable', 'Post');
	$comment_id = (int) Input::post('comment_id', 0);
	$page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 5);
	$smarty->assign('table_id', $table_id);
	$smarty->assign('clsTable', $clsTable);
	$smarty->assign('comment_id', $comment_id);
	#
	$cond = "is_trash=0 and is_active='1' and table_id='{$table_id}' and parent_id='{$comment_id}'";
	$total_record = $clsComment->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#
	$order_by = " order by order_no DESC";
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	$list_reply = $clsComment->getAll($cond.$order_by.$limitCond);
	//$clsISO->print_pre($list_reply); die();
	if(!empty($list_reply)){
		foreach($list_reply as $key => $val){
			$profile_id = $val['profile_id'];
			if(isset($arr_profile_cached[$profile_id])){
				$db_profile = $arr_profile_cached[$profile_id];
			} else {
				$db_profile = $clsProfile->getProfile($profile_id);
				$arr_profile_cached[$profile_id] = $db_profile;
			}
			$list_reply[$key]['db_profile'] = $db_profile;
		}
	}
	$smarty->assign('list_reply', $list_reply);
	$smarty->assign('page', $page);
	$smarty->assign('per_page', $per_page);
	$smarty->assign('total_page', $total_page);
	$smarty->assign('total_record', $total_record);
	// Return
	$html = $core->build('_ajax.list_reply.tpl');
	echo @json_encode(array(
		'html' => $html
	)); die();
}
function default_form_comment(){
	global $smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$clsConfiguration;
	global $loggedIn, $profile_id , $oneProfile;
	#
	$table_id = (int) Input::post('table_id', 0);
	$parent_id	= (int) Input::post('comment_id', 0);
	$clsTable = Input::post('clsTable', 'Post');
	$smarty->assign('clsTable', $clsTable);
	$smarty->assign('table_id', $table_id);
	$smarty->assign('parent_id', $parent_id);
	// Return
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	$html = $core->build('_ajax.form.comment.tpl');
	echo $html; die();
}
function default_comment_vote(){
	global $smarty,$core,$dbconn,$_LANG_ID,$clsISO,$deviceType,$now_day,$is_agent;
	$clsComment = new Comment();
	$clsCommentVote = new CommentVote();
	#
	$ip_log = $_SERVER['REMOTE_ADDR'];
	$comment_id = $_POST['comment_id'];
	#
	$msg = '_error';
	$lstcheck = $clsCommentVote->getAll("is_trash=0 and comment_id='{$comment_id}' and ip_vote='{$ip_log}'");
	if(!empty($lstcheck)){
		$msg = '_success|||unlike';
		$clsComment->updateOne($comment_id, "number_voted=number_voted-1");
		$clsCommentVote->deleteOne($lstcheck[0][$clsCommentVote->pkey]);
	} else {
		$msg = '_success|||like';
		$f = "comment_vote_id,comment_id,reg_date,is_trash,ip_vote";
		$v = "'".$clsCommentVote->getMaxId()."','{$comment_id}','".time()."','0','{$ip_log}'";
		$clsCommentVote->insertOne($f,$v);
		
		$clsComment->updateOne($comment_id, "number_voted=number_voted+1");
	}
	#--End
	echo $msg; die();	
}
function default_list_comment_more(){
	global $core,$assign_list,$profile_id,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	global $clsISO,$clsConfiguration,$clsProduct;
	$clsComment = new Comment(); $assign_list['clsComment'] = $clsComment;
	#
	$for_id = (int) Input::post('for_id', 0);
	$type_id = Input::post('type_id', '_review');
	$order_no = (int) Input::post('order_no', 0);
	$reg_date = (int) Input::post('reg_date', 0);
	$sort_type = Input::post('sort_type', 'desc');
	$number_star = (int) Input::post('number_star', 0);
	$assign_list['for_id'] = $for_id;
	$assign_list['type_id'] = $type_id;
	#---
	$where = "is_trash=0 and is_active=1 and for_id='{$for_id}' and type_id='{$type_id}'";
	if($number_star > 0){
		$where .= " and number_star='{$number_star}'";
	}
	if($sort_type=='asc'){
		$where .= " and order_no>'{$order_no}'";
	} else {
		$where .= " and order_no<'{$order_no}'";
	}
	//echo $where; die();
	$lstComment = $clsComment->getAll("{$where} order by order_no {$sort_type} limit 0,5");
	$assign_list['lstComment'] = $lstComment; unset($lstComment);
	// Return
	$assign_list['core'] = $core;
	$html = $core->build('list_more.tpl');
	echo $html; die();
}
function default_news(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsNews', $clsNews);
	$smarty->assign('clsProfile', $clsProperty);
	$smarty->assign('clsProperty', $clsProperty);
	###
	$show = isset($_GET['show'])?$_GET['show']:'';
	$cat_id = isset($_GET['topic'])? (int) $_GET['topic']:0;
	$assign_list["show"] = $show;
	$assign_list["cat_id"] = $cat_id;
	
	if($show=="detail"){
		$news_id = Input::get('news_id', 0);
		$scriptJs.= '<a class="autoclick_'.$news_id.'"" news_id="'.$news_id.'" onClick="open_news(this, event)" action="_detail" ></a>
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					history.pushState("", "", "/ban-tin.html");
					$(\'.autoclick_'.$news_id.'\').trigger(\'click\');
				}, 500);
			})
		</script>';
	}
	$smarty->assign("scriptJs",$scriptJs);
	
	###
	$cond = "is_trash=0 and is_online=1";
	if($cat_id > 0) $cond.= " and cat_id='{$cat_id}'";
	if(!$clsISO->checkDev()){
		$cond .= " AND cat_id <> "._NEWS_GRATITUDE_CAT_ID;
	}
	
	/** Pagination */
	$per_page = 10;
	$current_page = (int) Input::get('page',1);
	$total_record = $clsNews->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	#
	$offset = ($current_page-1) * $per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";
	/** End pagination */
	$order_by = " ORDER BY `order_no` DESC";
	$field = "{$clsNews->pkey},title,content,images,user_id,reg_date,order_no,liked_json";
	$field.= ",cat_id,events_config,start_date,end_date,regis_info,more_informatiion";
	$list_post = $clsNews->getAll($cond.$order_by.$limitCond, $field);
	if(!empty($list_post)){
		$arr_profile_cached = array();
		foreach($list_post as $key => $val){
			$user_id = $val['user_id'];
			if(isset($arr_profile_cached[$user_id])){
				$db_profile = $arr_profile_cached[$user_id];
			} else {
				$db_profile = $clsProfile->getProfile($user_id);
				$arr_profile_cached[$user_id] = $db_profile;
			}
			$list_post[$key]['db_profile'] = $db_profile;
			$liked_json = !empty($val['liked_json']) 
				? json_decode(html_entity_decode($val['liked_json']), true) : array();
			
			$total_liked = 0;
			if(!empty($liked_json)){
				foreach($liked_json as $okey => $ids){
					if(is_array($ids) && !empty($ids)){
						$total_liked+= count($ids);
					}
				}
			}
			$list_post[$key]['total_liked'] = $total_liked;
			$status_liked = $clsNews->checkLiked($val[$clsNews->pkey], $val);
			$list_post[$key]['status_liked'] = $status_liked;
			###
			$events_config = !empty($val['events_config']) 
				? json_decode(html_entity_decode($val['events_config']), true) : array();
			$list_post[$key]['events_config'] = $events_config;
			$regis_info = $val['regis_info'];
			$regis_info = !empty($regis_info) 
				? json_decode(html_entity_decode($regis_info), true) : array();
			$is_registed = 0;
			if($val['cat_id'] == _NEWS_EVENT_CAT_ID && !empty($regis_info) 
				&& array_key_exists($profile_id, $regis_info)){
				$is_registed = 1;
			}
			$list_post[$key]['is_registed'] = $is_registed;
			###
			$total_comments = $clsNews->getTotalComment($val[$clsNews->pkey]);
			$list_post[$key]['total_comments'] = $total_comments;
			$list_post[$key]['total_actions'] = $total_liked + $total_comments;
			$list_post[$key]['link'] = "/ban-tin/".$val['news_id'].".html";
			
			if($val['cat_id'] == _NEWS_GRATITUDE_CAT_ID){
				$more_information = !empty($val['more_informatiion']) ? $clsISO->to_array_json($val['more_informatiion']) : [];
				$department_id = !empty($more_information['department_id']) ? $more_information['department_id'] : [];
				$staff_id = !empty($more_information['staff_id']) ? $more_information['staff_id'] : [];
				if(!empty($department_id)) {
					$lstDepartment = $clsProperty->getAllCache("property_id IN (".implode(",",$department_id).")",$clsProperty->pkey.",title");
					$list_post[$key]['department'] = $lstDepartment;
				}else{
					$list_post[$key]['department'] = [];
				}
				if(!empty($staff_id)) {
					$lstStaff = $clsProfile->getAll("profile_id IN (".implode(",",$staff_id).")",$clsProfile->pkey.',full_name');
					$list_post[$key]['staff'] = $lstStaff;
				}else{
					$list_post[$key]['staff'] = [];
				}
			}
			
		}
	}
//	 $clsISO->print_pre($list_post); die();
	$smarty->assign('per_page', $per_page);
	$smarty->assign('list_post', $list_post);
	$smarty->assign('total_record', $total_record);
	/*=============Title & Description Page==================*/
	$title_page = 'Bản tin Future Homes';
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $title_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_more(){
	global $smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$assign_list["clsNews"] = $clsNews;
	$assign_list["clsMember"] = $clsMember;
	#
	$per_page = 6;
	$cat_id = (int) Input::post('cat_id', 0);
	$order_no = (int) Input::post('order_no', 0);
	$total_loaded = (int) Input::post('total_loaded', 0);
	#
	$cond = "is_trash=0 and is_online=1";
	if($cat_id > 0){
		$cond .= " and cat_id='{$cat_id}'";
	}
	$cond .= " and order_no<'{$order_no}'";
	#
	$limitCond = " LIMIT 0,{$per_page}";
	$order_by = " ORDER BY `order_no` DESC";
	$field = "{$clsNews->pkey},title,content,images,user_id,reg_date,order_no,liked_json";
	$list_news = $clsNews->getAll($cond.$order_by.$limitCond, $field);
	//$clsISO->print_pre($list_news); die();
	if(!empty($list_news)){
		$total_loaded += count($list_news);
		$arr_profile_cached = array();
		foreach($list_news as $key => $val){
			$user_id = $val['user_id'];
			if(isset($arr_profile_cached[$user_id])){
				$db_profile = $arr_profile_cached[$user_id];
			} else {
				$db_profile = $clsProfile->getProfile($user_id);
				$arr_profile_cached[$user_id] = $db_profile;
			}
			$list_news[$key]['db_profile'] = $db_profile;
			#
			$total_liked = 0;
			$liked_json = !empty($val['liked_json']) 
				? json_decode(html_entity_decode($val['liked_json'])) : array();
			if(!empty($liked_json)){
				$arr_status = array_keys($liked_json);
				foreach($liked_json as $okey => $ids){
					if(is_array($ids) && !empty($ids)){
						$total_liked+= count($ids);
					}
				}
			}
			$list_news[$key]['total_liked'] = $total_liked;
			$status_liked = $clsNews->checkLiked($val[$clsNews->pkey], $val);
			$list_news[$key]['status_liked'] = $status_liked;
			###
			$total_comments = $clsNews->getTotalComment($val[$clsNews->pkey]);
			$list_news[$key]['total_comments'] = $total_comments;
			$list_news[$key]['total_actions'] = $total_liked+$total_comments;
		}
	}
	$smarty->assign('list_news', $list_news);
	// Return
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	$html = $core->build('_ajax.load_more.tpl');
	echo json_encode(array(
		'html' => $html,
		'total_loaded' => $total_loaded
	)); die();
}
function default_ms_register(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$oneProfile,$clsISO;
	$clsNews = new News();
	$news_id = (int) Input::post('news_id', 0);
	$uid = Input::post('uid', $clsISO->getUniqid());
	###
	$html = "";
	$msg = "_error";
	if($news_id == 0){
		$msg = '_invalid';
	} else {
		// $clsISO->print_pre($oneProfile); die();
		$oneNews = $clsNews->getOne($news_id);
		$cat_id = $oneNews['cat_id'];
		$regis_info = $oneNews['regis_info'];
		$events_config = $oneNews['events_config'];
		$regis_info = !empty($regis_info) 
			? @json_decode(html_entity_decode($regis_info), true) : array();
		$events_config = !empty($events_config) 
			? @json_decode(html_entity_decode($events_config), true) : array();
		$is_staff_regis = isset($events_config['is_staff_regis']) 
			? (int) $events_config['is_staff_regis'] : 0;
		$is_cusotmer_regis = isset($events_config['is_cusotmer_regis']) 
			? (int) $events_config['is_cusotmer_regis'] : 0;
		// $clsISO->print_pre($events_config); die();
		if($is_staff_regis == 1 && $is_cusotmer_regis == 0){
			$regis_info[$profile_id] = array(
				'full_name' => $oneProfile['full_name'],
				'email' => $oneProfile['email'],
				'phone' => $oneProfile['phone'],
				'reg_date' => time(),
				'profile_id' => $profile_id
			);
			if($clsNews->updateOne($news_id, array(
				'regis_info' => json_encode($regis_info, JSON_UNESCAPED_UNICODE)
			))){
				$msg = '_success';
				$html = '<a href="javascript:void(0);" onClick="cancel_register(this, event)" news_id="'.$news_id.'" class="awe__post-action awe__post-cancel-action text-danger" title="Huỷ đăng ký" data-bs-toggle="tooltip">
					<i class="bx bxs-trash"></i> Huỷ đăng ký
				</a>';
			}
		} else if($is_staff_regis==1 && $is_cusotmer_regis==1){
			$participants = array();
			if(!empty($regis_info) && array_key_exists($profile_id, $regis_info)){
				$participants = $regis_info[$profile_id]['participants'];
			}
			$smarty->assign('participants', $participants);
			###
			$msg = "_modal";
			$smarty->assign('uid', $uid);
			$smarty->assign('news_id', $news_id);
			$smarty->assign('oneNews', $oneNews);
			$smarty->assign('events_config', $events_config);
			$html = $core->build('_ajax.register.tpl');
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_pop_event_register(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$oneProfile,$clsISO;
	$clsNews = new News();
	$news_id = (int) Input::post('news_id', 0);
	
	$regis_info = $clsNews->getOneField('regis_info', $news_id);
	$regis_info = !empty($regis_info) 
		? json_decode(html_entity_decode($regis_info), true) 
		: array();
	###	
	$msg = "_error";
	$participants = Input::post('participants');
	$regis_info[$profile_id] = array(
		'full_name' => $oneProfile['full_name'],
		'email' => $oneProfile['email'],
		'phone' => $oneProfile['phone'],
		'reg_date' => time(),
		'profile_id' => $profile_id,
		'participants' => $participants
	);
	if($clsNews->updateOne($news_id, array(
		'regis_info' => json_encode($regis_info, JSON_UNESCAPED_UNICODE)
	))){
		$msg = '_success';
	}
	// Return
	echo $msg; die();
}
function default_add_customer_line(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$oneProfile,$clsISO;
	$clsNews = new News();
	$uid = Input::post('uid');
	$total_customer = (int) Input::post('total_customer', 1);
	$regis_id = $clsISO->getUniqid();
	$html = '<div class="widget-block '.$uid.' mb-3">
				<div class="widget-header d-flex align-items-center justify-content-between">
					<h4 class="m-0 fs-11">Khách hàng '.($total_customer+1).'</h4>
					<button type="button" title="Xoá" data-bs-toggle="tooltip" regis_id="'.$regis_id.'" class="btn btn-primary p-1"><i class="bx bx-trash fs-11"></i></button>
				</div>
				<div class="widget-content">
					<div class="form-group mb-3">
						<div class="form-floating">
							<input type="text" class="form-control required" id="full_name_'.$regis_id.'" 
							name="participants['.$regis_id.'][full_name]" maxlength="255" placeholder="Họ và tên" value="">
							<label for="full_name_'.$regis_id.'">Họ và tên</label>
						</div>
					</div>
					<div class="form-group mb-3">
						<div class="form-floating">
							<input type="text" class="form-control required" id="phone_'.$regis_id.'" 
							name="participants['.$regis_id.'][phone]" maxlength="255" placeholder="Điện thoại" value="">
							<label for="phone_'.$regis_id.'">Điện thoại</label>
						</div>
					</div>
				</div>
			</div>';
	// return
	echo $html; die();
}
function default_cancel_register(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$clsISO;
	$clsNews = new News();
	$news_id = (int) Input::post('news_id', 0);
	$regis_info = $clsNews->getOneField('regis_info', $news_id);
	$regis_info = !empty($regis_info) 
		? json_decode(html_entity_decode($regis_info), true) 
		: array();
	if(!empty($regis_info) && array_key_exists($profile_id, $regis_info)){
		unset($regis_info[$profile_id]);
	}
	$msg = "_error"; $html = '';
	if($clsNews->updateOne($news_id, array(
		'regis_info' => json_encode($regis_info, JSON_UNESCAPED_UNICODE)
	))){
		$msg = '_success';
		$html = '<a href="javascript:void(0);" onClick="ms_register(this, event)" tp="register" news_id="'.$news_id.'" class="awe__post-action awe__post-share-action text-success" title="Đăng ký tham gia" data-bs-toggle="tooltip"><i class="bx bx-plus"></i> Đăng ký</a>';
	}
	// return
	echo json_encode(array(
		'msg' => $msg,
		'html' => $html
	)); die();
}
function default_open_rlist_register(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$clsISO;
	$clsNews = new News();
	###
	$uid = $clsISO->getUniqid();
	$news_id = (int) Input::post('news_id', 0);
	$smarty->assign('uid', $uid);
	###
	$regis_info = $clsNews->getOneField('regis_info', $news_id);
	$regis_info = !empty($regis_info) 
		? json_decode(html_entity_decode($regis_info), true) : array();
	##
	$list_registers = array();
	if(!empty($regis_info)){
		$list_registers = @array_reverse($regis_info);
	}
	$smarty->assign('list_registers', $list_registers);
	//$clsISO->print_pre($list_registers); die();
	// return
	$html = $core->build('_ajax.rlist_register.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_like(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$clsISO;
	$gid = Input::post('gid', '');
	$name = Input::post('name', 'like');
	$action = Input::post('action', 'like');
	$clsTable = Input::post('clsTable');
	$table_id = (int) Input::post('table_id', 0);
	#
	if($table_id == 0){
		echo '_invalid';
		die();
	} else {
		$clsClassTable = new $clsTable();
		$oneTable = $clsClassTable->getOne($table_id, "liked_json");
		$liked_json = $oneTable['liked_json'];
		$liked_json = !empty($liked_json) ? json_decode(html_entity_decode($liked_json), true) : array();
		// $clsISO->print_pre($liked_json); die();
		if($action == 'unlike'){
			foreach($liked_json as $key => $ids){
				if(!empty($ids) && in_array($profile_id, $ids)){
					$liked_json[$key] = array_diff($ids, array($profile_id));
				}
			}
		} else {
			if(!empty($liked_json)){
				foreach($liked_json as $key => $ids){
					if(!empty($ids) && in_array($profile_id, $ids)){
						$liked_json[$key] = array_diff($ids, array($profile_id));
					}
				}
			}
			$liked_json[$name][] = $profile_id;
			// $clsISO->print_pre($liked_json); die();
		}
		$html = '_error';
		if($clsClassTable->updateOne($table_id, array(
			'liked_json' => json_encode($liked_json, JSON_UNESCAPED_UNICODE)
		))){
			$html = '_success|||<a gid="'.$gid.'" href="javascript:void(0);" news_id="'.$table_id.'" onClick="$Core.news.like(this, event)" data-name="like" data-clsTable="'.$clsTable.'" data-table_id="'.$table_id.'" class="awe__post-action awe__post-like-action'.($action=='like' ? ' liked': '').'"><i class="bx '.($action=='like' ? 'bxs-heart' : 'bx-heart').'"></i> Thích</a>|||'.$clsClassTable->genTotalLike($table_id);
		}
		// Return
		echo $html; die();
	}
}
function shortNumber($num) {
	global $clsISO;
    $units = [$clsISO->getRate(), 'K', 'triệu', 'tỷ', 'T'];
    for ($i = 0; $num >= 1000; $i++) {
        $num /= 1000;
    }
    return round($num, 1) ." ". $units[$i];
}
function default_uploadImage(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,
	$clsConfiguration,$IsoEditor,$clsSiteCrop;
	$msg = '_error';
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$images = $_FILES['images'];
		if(!empty($images['name'])){ $ii = 0; //Init
			$results = array();
			for($i = 0; $i<count($images); $i++){
				$clsUploadFile = new UploadFile();
				$image = array();
				$image["name"] = $images['name'][$i];
				$image["type"] = $images['type'][$i];
				$image["tmp_name"] = $images['tmp_name'][$i];
				$image["error"] = $images['error'][$i];
				$image["size"] = $images['size'][$i];
				$up = $clsUploadFile->uploadItem($image,"/Ban_Tin","jpg,jpeg,gif,png", array(
					'resize' => false,
					'resize_x' => 847,
					'resize_y' => 510,
					'watermark' => true,
				));
				if(!empty($up) && @file_exists(ABSPATH . $up)){
					$results[] = $up;
				}
			}
			// Return
			$html = '';
			if(!empty($results)){
				foreach($results as $image){
					$html .= '<span class="item">
						<img src="'.$image.'" />
						<input type="hidden" name="images[]" value="'.$image.'" />
						<a class="delete" src="'.$image.'" onClick="$Core.upload.delete(this, event)"></a>
					</span>';
				}
			}
			$msg = '_success|||' .$html;
		}
	}
	// Return
	echo $msg; die();
}
function default_uploadShare(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,
	$clsConfiguration,$IsoEditor,$clsSiteCrop;
	###
	$msg = '_error'; $html = '';
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$uid = Input::post('uid');
		if(!empty($_FILES['image']['name'])){
			if(is_uploaded_file($_FILES['image']['tmp_name'])){
				$clsUploadFile = new UploadFile();
				$image = $clsUploadFile->uploadItem($_FILES["image"],"/test","jpeg,jpg,gif,png");
				if(!empty($image) && file_exists(ROOTPATH.$image)){
					$msg = '_success';
					$html = '<input type="hidden" name="images[]" value="'.$image.'" />
					<div class="we-filedrop__image" uid="'.$uid.'">
						<a class="delete" src="'.$image.'" uid="'.$uid.'" onClick="re_upload_share(this, event)"></a>
						<img class="img-responsive" style="max-width:100%" src="'.$image.'" />
					</div>';
				}
			}
		}
	}
	// return
	echo sprintf('%s|||%s', $msg, $html); die();
}
function default_deleteImage(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,
	$clsConfiguration,$IsoEditor,$clsSiteCrop;
	#
	$src = Input::post('src');
	if(!empty($src) && file_exists(ROOTPATH . $src)){
		@unlink(ROOTPATH . $src);
	}
	// Return
	echo 1; die();
}
function default_top_staff(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	###
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', 0);
	$cond = " and `t2`.`is_cancel`='0'";
	if($month > 0 && $year > 0){
		$f = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= " and FROM_UNIXTIME(`t2`.`deposit_date`,'%m/%Y')='{$f}'";
	} else {
		$cond.= " and FROM_UNIXTIME(`t2`.`deposit_date`,'%Y')='".date('Y')."'";
	}
	$field = "t1.profile_id,t1.code,t1.full_name,t1.avatar,t1.department_id,t1.role_id";
	$field.= ",SUM(`t2`.`totalgrand`) as `total_price`,count(`t2`.`staff_id`) as `total_billing`";
	$list_top_staff = $dbconn->getAll("select {$field} from {$clsProfile->tbl} as `t1` 
		left join {$clsBilling->tbl} as`t2` on `t1`.`profile_id`=`t2`.`staff_id`{$cond} 
		where t1.is_trash=0 and `t1`.`is_active`=1 group by `t1`.`profile_id` 
		having `total_billing`>0 order by `total_price` DESC limit 0,6");
	if(!empty($list_top_staff)){
		$html = '<ul class="p-0 m-0">';
			$arrCached = array();
			foreach($list_top_staff as $key => $val){
				$role_id = $val['role_id'];
				$department_id = $val['department_id'];
				#
				$department_name = '';
				if(isset($arrCached[$department_id])){
					$department_name = $arrCached[$department_id];
				} else {
					$arrCached[$department_id] = $clsProperty->getTitle($department_id);
					$department_name = $arrCached[$department_id];
				}
				$html .= '<li class="d-flex mb-3 pb-1">
					<div class="avatar flex-shrink-0 me-2" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$val[$clsProfile->pkey].'" data-toggle="webui-popover" data-trigger="hover" data-width="400">
						<img src="'.$val['avatar'].'" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" alt="'.$val['full_name'].'" class="rounded" />
					</div>
					<div class="w-100">
						<div class="d-flex w-100 flex-wrap align-items-center justify-content-between mb-1">
							<small class="text-muted d-block">'.$val['code'].'-'.$department_name.'</small>
							<div class="user-progress d-flex align-items-center gap-1">
								<h6 class="mb-0">'.shortNumber($val['total_price']).'</h6>
							</div>
						</div>
						<h6 class="mb-0">'.$val['full_name'].'</h6>
					</div>
				</li>';
			}
		$html .= '</ul>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_top_shares(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$deviceType;
	global $profile_id, $oneProfile;
	$clsShare = new Share(); $assign_list['clsShare'] = $clsShare;
	$clsProfile = new Profile(); $assign_list['clsProfile'] = $clsProfile;
	
	$cond = "`is_trash`=0 and `holderG`='share'";
	$holderG = Input::get('holderG','_all');
	if($holderG=='_sale_director'){
		$role_id = $oneProfile['role_id'];
		$department_id = $oneProfile['department_id'];
		$cond.= " and `user_id` in (
			select `profile_id` from ".$clsProfile->tbl." 
			where (`department_id`='{$department_id}' 
				or `list_department_id` like '%|{$department_id}|%'
			)
		)";
	}
	$limitCond = " LIMIT 0,10";
	$order_by = " ORDER BY `reg_date` DESC";
	$field = "{$clsShare->pkey},title,images,reg_date,user_id";
	$list_shares = $clsShare->getAll($cond.$order_by.$limitCond, $field);
	if(!empty($list_shares)){
		$arr_profile_cached = array();
		foreach($list_shares as $key => $value){
			$user_id = $value['user_id'];
			$list_shares[$key]['images'] = json_decode($value['images']);
			if(!isset($arr_profile_cached[$user_id])){
				$arr_profile_cached[$user_id] = $clsProfile->getFullName($user_id);
			}
			$list_shares[$key]['full_name'] = $arr_profile_cached[$user_id];
		}
	}
	$smarty->assign('holderG', $holderG);
	$smarty->assign('list_shares', $list_shares);
	$smarty->assign('number_item', ($holderG=='_all'?6:6));
	// Return
	$html = $core->build("_ajax.load_top_share.tpl");
	echo json_encode(array(
		'html' => $html,
		'holderG' => $holderG
	)); die();
}
function default_share(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsShare = new Share();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsShare', $clsShare);
	$smarty->assign('clsProfile', $clsProperty);
	$smarty->assign('clsProperty', $clsProperty);
	###
	$staff_placeholders = array();
	for($i=0; $i<=6; $i++){
		$staff_placeholders[] = $i;
	}
	$smarty->assign('staff_placeholders', $staff_placeholders);
	
	$current_month = date('n');
	$current_year = date('Y');
	$prev_month = $current_month - 1;
	$prev_year = $current_year;
	if($current_month==1){
		$prev_month = 12;
		$prev_year = ($current_year - 1);
	}
	$smarty->assign('current_month', $current_month);
	$smarty->assign('current_year', $current_year);
	$smarty->assign('prev_month', $prev_month);
	$smarty->assign('prev_year', $prev_year);
	##
	$holderG = Input::get('holderG','share');
	$titlePage = 'Hoạt động tiếp khách';
	if($holderG=='honor'){
		$titlePage = 'Vinh danh bán hàng';
	}
	$smarty->assign('holderG', $holderG);
	$smarty->assign('titlePage', $titlePage);
	$share_id = Input::get("share_id",0);
	if($share_id > 0){
		$scriptJs= '<a class="autoclick_'.$share_id.'""  share_id="'.$share_id.'" 
		onClick="$Core.share.open_share(this, event)"></a>
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					$(\'.autoclick_'.$share_id.'\').trigger(\'click\').remove();
				}, 200);
			});
		</script>';
		$smarty->assign('scriptJs', $scriptJs);
	}
	###
	$cond = "`is_trash`=0 and `holderG`='{$holderG}'";
	/** Pagination */
	$per_page = 6;
	$current_page = (int) Input::get('page',1);
	$total_record = $clsShare->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	#
	$offset = ($current_page-1) * $per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";
	/** End pagination */
	$order_by = " ORDER BY `reg_date` DESC";
	$field = "{$clsShare->pkey},holderG,title,images,user_id,reg_date,liked_json";
	$list_shares = $clsShare->getAll($cond.$order_by.$limitCond, $field);
	// $clsISO->print_pre($cond); die();
	if(!empty($list_shares)){
		$arr_profile_cached = array();
		foreach($list_shares as $key => $val){
			$user_id = $val['user_id'];
			if(isset($arr_profile_cached[$user_id])){
				$db_profile = $arr_profile_cached[$user_id];
			} else {
				$db_profile = $clsProfile->getProfile($user_id);
				$arr_profile_cached[$user_id] = $db_profile;
			}
			$list_shares[$key]['db_profile'] = $db_profile;
			#
			$liked_json = !empty($val['liked_json']) 
				? json_decode(html_entity_decode($val['liked_json'])) : array();
			$total_liked = !empty($liked_json) ? count($liked_json) : 0;
			$list_shares[$key]['total_liked'] = $total_liked;
		}
	}
	$smarty->assign('per_page', $per_page);
	$smarty->assign('list_shares', $list_shares);
	$smarty->assign('total_record', $total_record);
	/*=============Title & Description Page==================*/
	$title_page = $titlePage;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_share_more(){
	global $smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	$clsShare = new Share();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsShare', $clsShare);
	$smarty->assign('clsProfile', $clsProfile);
	#
	$per_page = 6;
	$holderG = Input::post('holderG', 'share');
	$reg_date = (int) Input::post('reg_date', 0);
	$total_loaded = (int) Input::post('total_loaded', 0);
	###
	$cond = "is_trash=0 and reg_date<'{$reg_date}'";
	###
	$limitCond = " LIMIT 0,{$per_page}";
	$order_by = " ORDER BY `reg_date` DESC";
	$field = "{$clsShare->pkey},holderG,title,images,user_id,reg_date,liked_json";
	$list_shares = $clsShare->getAll($cond.$order_by.$limitCond, $field);
	//$clsISO->print_pre($list_shares); die();
	if(!empty($list_shares)){
		$total_loaded += count($list_shares);
		$arr_profile_cached = array();
		foreach($list_shares as $key => $val){
			$user_id = $val['user_id'];
			if(isset($arr_profile_cached[$user_id])){
				$db_profile = $arr_profile_cached[$user_id];
			} else {
				$db_profile = $clsProfile->getProfile($user_id);
				$arr_profile_cached[$user_id] = $db_profile;
			}
			$list_shares[$key]['db_profile'] = $db_profile;
			#
			$liked_json = !empty($val['liked_json']) 
				? json_decode(html_entity_decode($val['liked_json'])) : array();
			$total_liked = !empty($liked_json) ? count($liked_json) : 0;
			$list_shares[$key]['total_liked'] = $total_liked;
		}
	}
	$smarty->assign('list_shares', $list_shares);
	// Return
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	$smarty->assign('template_type', '_list');
	$html = $core->build('_ajax.share.tpl');
	echo json_encode(array(
		'html' => $html,
		'total_loaded' => $total_loaded
	)); die();
}
function default_share_staff(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsShare = new Share();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$year = (int) Input::post('year', date('Y'));
	$month = (int) Input::post('month', date('n'));
	$f = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
	$holderG = Input::get('holderG','staff');
	
	$field = "t1.profile_id,t1.code,t1.full_name,t1.avatar,t1.department_id,t1.role_id";
	if($holderG=='share'){
		$field_join = 'user_id';
		$field.= ",t2.staff_id,count(`t2`.`user_id`) as `total_share`";
	} else if($holderG=='honor'){
		$field_join = 'staff_id';
		$field.= ",t2.user_id,count(`t2`.`staff_id`) as `total_share`";
	}
	$list_staffs = $dbconn->getAll("select {$field} from {$clsProfile->tbl} as t1 
	left join {$clsShare->tbl} as t2 on t1.profile_id=t2.{$field_join} and t2.holderG='{$holderG}' and FROM_UNIXTIME(t2.reg_date,'%m/%Y')='{$f}' 
	where `t1`.`is_trash`=0 and `t1`.`is_active`=1 and `t1`.`status_id`<>'"._STATUS_STAFF_OFF_ID."' 
	group by `t1`.`profile_id` order by `total_share` DESC");
	if(!empty($list_staffs)){ $ii = 0;
		$html = '<ul style="list-style:none" class="p-0 m-0">';
		$arrCached = array();
		$total_staffs = count($list_staffs);
		foreach($list_staffs as $key => $val){
			$role_id = $val['role_id'];
			$department_id = $val['department_id'];
			#
			$department_name = '';
			if(isset($arrCached[$department_id])){
				$department_name = $arrCached[$department_id];
			} else {
				$arrCached[$department_id] = $clsProperty->getTitle($department_id);
				$department_name = $arrCached[$department_id];
			}
			$html .= '<li class="d-flex mb-3 pb-1'.($ii>6?' toggleView d-none':'').'">
				<div class="avatar flex-shrink-0 me-2" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$val['profile_id'].'" data-toggle="webui-popover" data-trigger="hover" data-width="400">
					<img src="'.$val['avatar'].'" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" alt="'.$val['full_name'].'" class="rounded" />
				</div>
				<div class="w-100">
					<div class="d-flex w-100 flex-wrap align-items-center justify-content-between">
						<small class="text-muted d-block mb-1">'.$val['code'].'-'.$department_name.'</small>
						<div class="user-progress d-flex align-items-center gap-1">
							<span class="fs-13 mb-0">'.$val['total_share'].' lần</span>
						</div>
					</div>
					<h6 class="mb-0 fs-14">'.$val['full_name'].'</h6>
				</div>	
			</li>';
			++$ii;
		}
		if($total_staffs > 10){
			$html.= '<li>
				<button type="button" onClick="toggleItem(this, event)" toClass="toggleView" 
				class="btn btn-block btn-outline-primary">
					<span>Hiển thị thêm</span>
				</button>
			</li>';
		}
		$html .= '</ul>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_open_share(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsShare = new Share();
	$clsProfile = new Profile();
	$uid = $clsISO->getUniqid();
	$holderG = Input::post('holderG', "share");
	$share_id = (int) Input::post('share_id', 0);
	$action = Input::post('action', "_detail");
	#
	$oneShare = $list_images = array();
	if($share_id > 0){
		$oneShare = $clsShare->getOne($share_id);
		$list_images = !empty($oneShare['images']) 
			? json_decode(html_entity_decode($oneShare['images']), true) 
			: array();
		//$clsISO->print_pre($list_images); die();
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('share_id', $share_id);
	$smarty->assign('holderG', $holderG);
	$smarty->assign('action', $action);
	$smarty->assign('oneShare', $oneShare);
	$smarty->assign('list_images', $list_images);
	// Return
	$smarty->assign('core', $core);
	$smarty->assign('template_type', '_form');
	$html = $core->build('_ajax.share.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_pop_save_share(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id;
	$clsShare = new Share();
	$share_id = (int) Input::post('share_id', 0);
	$staff_id = (int) Input::post('staff_id', 0);
	$holderG = Input::post('holderG', 'share');
	$title = Input::post('title');
	$images = Input::post('images'); 
	###
	$msg = "_error";
	if($share_id > 0){
		if($clsShare->updateOne($share_id, array(
			'title' => $title,
			'staff_id' => $staff_id,
			'images' => json_encode($images, JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update' => $profile_id,
		))){
			$msg = '_success';
		}
	} else {
		$share_id = $clsShare->getMaxId();
		if($clsShare->insert(array(
			$clsShare->pkey => $share_id,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'staff_id' => $staff_id,
			'title' => $title,
			'holderG' => $holderG,
			'images' => json_encode($images, JSON_UNESCAPED_UNICODE),
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$msg = '_success';
			$fpoint = new FPoint();
			$fpoint->insertPoint('consulting', $profile_id, $share_id, $title);
			$clsProfile = new Profile();
			$clsNotify = new Notify();
			$share_id = $clsShare->getMaxId();
			$content = sprintf("<strong>%s</strong> Đã thêm mới vinh danh bán hàng <strong>%s</strong>",$clsProfile->getFullName($profile_id), $title) ;
			$clsNotify->insertNotify($clsShare->tbl, $clsShare->pkey, $share_id, $content, time(), sprintf('|%s|', $profile_id));
		}
	}
	// Return
	echo $msg; die();
}

function default_open_share_detail(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id;
	$clsShare = new Share();
	$clsProfile = new Profile();
	$share_id = (int) Input::post('share_id', 0);
	$uid = $clsISO->getUniqid();
	#
	$is_registed = 0;
	$oneShare = $events_config = $regis_info = $list_images = array();
	if($share_id > 0 || $holderG=='_detail'){
		$oneShare = $clsShare->getOne($share_id);
		$images = $oneShare['images'];
		$regis_info = $oneShare['regis_info'];
		$events_config = $oneShare['events_config'];
		$regis_info = !empty($regis_info) 
			? json_decode(html_entity_decode($regis_info), true) 
			: array();
		
		$liked_json = !empty($oneShare['liked_json']) 
			? json_decode(html_entity_decode($oneShare['liked_json'])) : array();
		$total_liked = !empty($liked_json) ? count($liked_json) : 0;
		$oneShare['total_liked'] = $total_liked;
			
		//$clsISO->print_pre($profile_id); die();
		$events_config = !empty($events_config) 
			? json_decode(html_entity_decode($events_config), true) 
			: array();
		$list_images = !empty($images) 
			? json_decode(html_entity_decode($images), true) : array();
		$user_id = $oneShare['user_id'];
		$db_profile = $clsProfile->getProfile($user_id);
		$oneShare['db_profile'] = $db_profile;
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('share_id', $share_id);
	$smarty->assign('clsShare', $clsShare);
	$smarty->assign('oneShare', $oneShare);
	$smarty->assign('list_images', $list_images);
	$smarty->assign('events_config', $events_config);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.share_detail.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'list_images' => $list_images,
	)); die();
}
function default_share_like(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$clsISO;
	$clsShare = new Share();
	$share_id = (int) Input::post('share_id', 0);
	#
	if($share_id == 0){
		echo '_invalid';
		die();
	} else {
		$liked_json = $clsShare->getOneField('liked_json', $share_id);
		$liked_json = !empty($liked_json) 
			? json_decode(html_entity_decode($liked_json), true) : array();
		//$clsISO->print_pre($liked_json); die();
		if(in_array($profile_id, $liked_json)){
			$action = 'unlike';
			$liked_json = array_diff($liked_json, array($profile_id));
		} else {
			$action = 'like';
			$liked_json[] = $profile_id;
		}
		$html = '_error';
		if($clsShare->updateOne($share_id, array(
			'liked_json' => json_encode($liked_json, JSON_UNESCAPED_UNICODE)
		))){
			$total_likes = !empty($liked_json) ? count($liked_json) : 0;
			$html = '<a href="javascript:void(0);" share_id="'.$share_id.'" class="awe__post-action awe__share-like-action"><i class="bx '.($action=='like' ? 'bxs-heart' : 'bx-heart').'"></i> '.($total_likes > 0 ? $total_likes . " " : '').'Thích</a>';
		}
		// Return
		echo $html; die();
	}
}
function truncate($string) {
	global $core, $dbconn, $clsISO;
	if(strlen($string) > 50){
		$l_string = substr($string, 0, 25);
		$r_string = substr($string, -25);
		return sprintf('%s...%s', $l_string, $r_string);
	} else {
		return $string;
	}
}
function default_search(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;
	$clsSlide = new Slide();
	$clsNews = new News();
	$clsStock = new Stock();
	
	$json_results = array();
	$maxQuerySuggestions = Input::get('maxQuerySuggestions', 50);
	$query = Input::get('query');
	if(!empty($query)){
		$slug = $core->replaceSpace($query);
		$field = "{$clsStock->pkey},ms_code";
		$list_stocks = $clsStock->getAll("ms_code='{$query}'", $field);
		if(!empty($list_stocks)){
			foreach($list_stocks as $key => $val){
				$link = $val['link'];
				$slide_type = $val['slide_type'];
				$json_results['suggests']['Căn hộ'][] = array(
					'id' => $val[$clsStock->pkey],
					'name' => $val['ms_code'],
					'link' => '/my-favourite/'.$val['ms_code'],
					//'image' => PCMS_URL . $clsProject->getImage($val[$clsProject->pkey], 60, 40, $val)
				);
			}
		}
		$list_slides = $clsSlide->getAll("slug like '%{$slug}%'");
		if(!empty($list_slides)){
			foreach($list_slides as $key => $val){
				$link = $val['link'];
				$slide_type = $val['slide_type'];
				$json_results['suggests']['Kho tÃ i liá»‡u'][] = array(
					'id' => $val[$clsSlide->pkey],
					'name' => $val['title'],
					'link' => $link,
					//'image' => PCMS_URL . $clsProject->getImage($val[$clsProject->pkey], 60, 40, $val)
				);
			}
		}
	}
	// Return
	echo json_encode($json_results, JSON_UNESCAPED_UNICODE); die();
}
function default_load_person_campaign(){
    global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
    $clsProfile = new Profile();
    $clsCampaign = new Campaign();
    $clsProperty = new Property();
    $clsBilling  = new Billing();
    $smarty->assign('clsProfile', $clsProfile);
    $smarty->assign('clsProperty', $clsProperty);
    $smarty->assign('clsCampaign', $clsCampaign);
    #
	$html = "";
    $currentNow = time();
	$list_campaigns = $clsCampaign->getAll("`start_date`<'{$currentNow}' and `end_date`>'{$currentNow}' order by end_date ASC");
	if(!empty($list_campaigns)){
		foreach($list_campaigns as $oneCampaign){
			$campaign_id = $oneCampaign[$clsCampaign->pkey];
			$selector = $oneCampaign['selector'];
			$start_date = $oneCampaign['start_date'];
			$end_date = $oneCampaign['end_date'];
			$campaign_info = $oneCampaign['campaign_info'];
			$campaign_info = $clsISO->to_array_json($campaign_info);
			if($selector == 'staff'){
				if(!empty($campaign_info)){
					$cond = "`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
					$cond.= " and {$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."'";
					if($campaign_info['opt_staff']=='_sale'){
						$cond.= " and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."%|' and `role_id`<>'"._ROLE_GD_PROJECT."'";
					} else if($campaign_info['opt_staff']=='_sale2'){
						 $cond.= " and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."%|'";
					}  else if($campaign_info['opt_staff']=='_sale2'){
						 $cond.= " and (`list_department_id` like '%|"._DEPARTMENT_SALE_ID."%|' or department_id='"._DEPARTMENT_DIRECTOR_ID."')";
					} else if($campaign_info['opt_staff']=='_select'){
						$ids = isset($campaign_info['staff']) && !empty($campaign_info['staff']) 
							? $campaign_info['staff'] : array();
						if(!empty($ids)){
							$cond.= " and {$clsProfile->pkey} in (".implode(',', $ids).")";
						}
					}
					if($clsProfile->countItem("{$cond} and `profile_id`='{$profile_id}'") > 0){
						if($oneCampaign['is_terms']==1){
							$campaign_terms = $oneCampaign['campaign_terms'];
							$campaign_terms = !empty($campaign_terms) 
								? json_decode(html_entity_decode($campaign_terms), true) : array();
							$list_terms = array();
							if(!empty($campaign_terms)){
								foreach($campaign_terms as $key => $val){
									if(!empty($val)){
										$list_terms[$key] = $val;
									}
								}
							}
							$smarty->assign('list_terms', $list_terms);
							#
							$total_scores = $total_transactions = 0;
							$field = "{$clsBilling->pkey},`billing_type`,`stock_code`,`billing_source_id`,`is_fullscore`,`deposit_date`";
							$list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$profile_id}' 
							and (`deposit_date` between '{$start_date}' AND '{$end_date}')", $field);
							if(!empty($list_billings)){
								$total_transactions = count($list_billings);
								foreach($list_billings as $okey => $oval){
									$is_fullscore = $oval['is_fullscore'];
									$billing_type = (int) $oval['billing_type'];
									$billing_source_id = (int) $oval['billing_source_id'];
									if($billing_source_id == _BILLING_RESOURCE_F1_ID && $is_fullscore == 1 
										&& $billing_type == _BILLING_TYPE_MWF_ID){
										$promos_start_date = strtotime('01-07-2024');
										$end_promos_day = cal_days_in_month(CAL_GREGORIAN, 9, 2024);
										$promos_end_date = strtotime(sprintf('%s-9-2024 23:59:59', $end_promos_day));
										if($oval['deposit_date'] >= $promos_start_date 
											&& $oval['deposit_date'] <= $promos_end_date){
											
											$total_scores += 10;
										} else if($oval['deposit_date'] <= $promos_start_date){
											$total_scores += floatval(7.5);
										} else {
											$total_scores += floatval($campaign_terms[$billing_type]);
										}
									} else {
										$total_scores += floatval($campaign_terms[$oval['billing_type']]);
									}
								}
							}
							if($campaign_id==4){
								$campaign_target = $oneCampaign['campaign_target'];
								$campaign_target = !empty($campaign_target) 
									? json_decode(html_entity_decode($campaign_target), true) : array();
								$num_target = isset($campaign_target[$profile_id]) 
									? $campaign_target[$profile_id] : 0;
								#
								$html.= '<div class="card gotoLink mb-2 h-100" href="'.$clsCampaign->getLink($campaign_id).'">
									<div class="card-body">
										<div class="d-flex align-items-center justify-content-between">
											<div class="p_left">
												 <h4 class="mb-1 text-main"><a class="text-main fs-5" href="'.$clsCampaign->getLink($campaign_id).'">'.$clsProfile->getFullName($profile_id, $oneProfile).'</a></h4>
												 <p class="m-0">'.$oneCampaign['title'].'</p>
											</div>
											<div class="d-flex align-items-center justify-content-between">
												<div class="p_right'.($deviceType=='phone'?' pr-2 mr-2':' pr-4 mr-4').'  border-end">
													<p class="mb-n1 fs-tiny text-muted">Điểm Bắc Kinh</p>
													<strong class="fs-3 text-main">'.$total_scores.'</strong>/'.$clsConfiguration->getValue('total_score').' điểm
												</div>
												<div class="p_right">
													<p class="mb-n1 fs-tiny text-muted">KPI giao dịch</p>
													<strong class="fs-3 text-main">'.$total_transactions.'</strong>/'.$num_target.' căn
												</div>
											</div>
										</div>
									</div>
								</div>';
							} else {
								$html.='<div class="card mb-3 gotoLink h-100" href="'.$clsCampaign->getLink($campaign_id).'">
									<div class="card-body">
										<div class="d-flex align-items-center justify-content-between">
											<div class="p_left">
												 <h4 class="mb-1"><a class="text-main fs-5" href="'.$clsCampaign->getLink($campaign_id).'">'.$clsISO->makeIcon('bx-user', $clsProfile->getFullName($profile_id, $oneProfile)).'</a></h4>
												 <p class="m-0 pl-4">'.$oneCampaign['title'].'</p>
											</div>
											<div class="p_right">
												<strong class="fs-3 text-main">'.$total_scores.'</strong>/
												'.$clsConfiguration->getValue('total_score').'
											</div>
										</div>
									</div>
								</div>';		
							}
						} 
					}
				}
			} else if($selector=='group_staff'){
				if(!empty($campaign_info)){
					if($clsISO->checkSupper()){
						foreach($campaign_info as $key => $val){
							$group_members = $val['group_members'];
							$group_product = $val['group_product'];
							$group_target = $val['group_target'];
							if(!empty($group_members)){
								$cond = "";
								if($group_product=='CAO_TANG') $cond.= " and `billing_type`='"._BILLING_TYPE_CT_ID."'";
								if($group_product=='THAP_TANG') $cond.= " and `billing_type`='"._BILLING_TYPE_TT_ID."'";
								if($group_product=='MWF') $cond.= " and `billing_type`='"._BILLING_TYPE_MWF_ID."'";
								if($group_product=='CHO_THUE') $cond.= " and `billing_type` in (".implode(',', array(_BILLING_TYPE_LEASING_ID,_BILLING_TYPE_LEASING_OCP2_ID,_BILLING_TYPE_LEASING_MGW_ID)).")";
								$total_scores = $clsBilling->countItem("`is_trash`=0 and `is_cancel`='0' 
									and FROM_UNIXTIME(`deposit_date`,'%Y')='".date('Y')."'".$cond);
								$html .= '<div class="card mb-2 gotoLink h-100" href="'.$clsCampaign->getLink($campaign_id).'">
									<div class="card-body">
										<div class="d-flex align-items-center justify-content-between">
											<div class="p_left">
												 <h4 class="mb-1"><a class="text-main fs-5" href="'.$clsCampaign->getLink($campaign_id).'">'.$clsISO->makeIcon('bx-group', $val['group_name']).'</a></h4>
											</div>
											<div class="p_right">
												<strong class="fs-3 text-main">'.$total_scores.'</strong>/'.$group_target.' giao dịch
											</div>
										</div>
									</div>
								</div>';
							}
						}
					} else {
						foreach($campaign_info as $key => $val){
							$group_target = $val['group_target'];
							$group_members = $val['group_members'];
							$group_product = $val['group_product'];
							if(!empty($group_members)){
								if(@in_array($profile_id, $group_members)){
									$cond = "";
									if($group_product=='CAO_TANG') $cond.= " and `billing_type`='"._BILLING_TYPE_CT_ID."'";
									if($group_product=='THAP_TANG') $cond.= " and `billing_type`='"._BILLING_TYPE_TT_ID."'";
									if($group_product=='MWF') $cond.= " and `billing_type`='"._BILLING_TYPE_MWF_ID."'";
									if($group_product=='CHO_THUE') $cond.= " and `billing_type` in (".implode(',', array(_BILLING_TYPE_LEASING_ID,_BILLING_TYPE_LEASING_OCP2_ID,_BILLING_TYPE_LEASING_MGW_ID)).")";
									$total_scores = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id` in (".implode(',',$group_members).") and (`deposit_date` between '{$start_date}' AND '{$end_date}')".$cond);
									$html .= '<div class="card d-none mb-3 gotoLink h-100" href="'.$clsCampaign->getLink($campaign_id).'">
										<div class="card-body">
											<div class="d-flex align-items-center justify-content-between">
												<div class="p_left">
													<h4 class="mb-1"><a class="text-main fs-5" href="'.$clsCampaign->getLink($campaign_id).'">'.$clsISO->makeIcon('bx-group', $val['group_name']).'</a></h4>
													 <p class="m-0 pl-4">Tổng số thÃ nh viÃªn: '.count($group_members).'</p>
												</div>
												<div class="p_right">
													<strong class="fs-3 text-main">'.$total_scores.'</strong>/'.$group_target.' giao dịch
												</div>
											</div>
										</div>
									</div>';
								}
							}
						}
					}
				}
			}
		}
	} else {
		 $html = '_empty';
	}
    // Return
    echo json_encode(array(
        'html' => $html
    )); die();
}
function default_load_crm_desktop(){
	 global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsShare = new Share();
	$clsBilling = new Billing();
	$clsCustomer = new Customer();
	$currentYear = date('Y');
	$currentMonth = date('n');
	// Total
	$total_customer = $clsCustomer->countItem("(`admin_id`='{$profile_id}' or `use_globe`='1' or `list_share_id` like '%|{$profile_id}|%') and FROM_UNIXTIME(`reg_date`,'%m')='{$clsISO->parseNumber($currentMonth)}'");
	// Tiếp khách
	$total_share = $clsShare->countItem("`staff_id`='{$profile_id}' and FROM_UNIXTIME(`reg_date`,'%m')='{$clsISO->parseNumber($currentMonth)}'");
	// Billing
	$total_transaction = $clsBilling->countItem("`is_trash`=0 and `is_cancel`='0' and `staff_id`='{$profile_id}' 
	and FROM_UNIXTIME(`deposit_date`,'%m')='{$clsISO->parseNumber($currentMonth)}'");
	// Billing
	$total_grand = $clsBilling->sumItem("totalgrand","`is_trash`=0 and `is_cancel`='0' and `staff_id`='{$profile_id}' 
	and FROM_UNIXTIME(`deposit_date`,'%m')='{$clsISO->parseNumber($currentMonth)}'");
	###
	$html = '<div class="card mb-3 h-100">
		<div class="card-header">
			<h5 class="card-title mb-1 text-main">Thống kê hiệu suất bán hàng cá nhân T'.$currentMonth.'</h5>
			<ul class="nav pqkmaItZyp nav-default">';
			for($i=1; $i<=12; $i++){
				$html.= '<li class="nav-item oxGryDemjr'.($currentMonth==$i?' active':'').'">
					<a href="javascript:void(0);"'.($currentMonth>=$i?' onClick="$Core.crm.load_data_month(this,event)"':'').' 
					month="'.$i.'" title="Tháng '.$i.'" class="nav-link uFrQLkRcYk">T'.$i.'</a>
				</li>';
			}
		$html.= '</ul>
		</div>
		<div class="card-body">
			<div id="hPImzYKEjR" class="hPImzYKEjR small-briefs d-flex gap-2 flex-wrap">
				<div class="brief-item m-0 overflow-hidden position-relative" style="background:#ab0303">
					<p class="fs-12 text-white mb-2">Khách mới</p>
					<h3 class="fs-5 mb-0 text-white">'.$total_customer.' <span class="fs-tiny font-normal">lead</span></h3>
				</div>
				<div class="brief-item m-0 overflow-hidden position-relative" style="background:#1d6a01">
					<p class="fs-12 text-white mb-2">Tiếp khách</p>
					<h3 class="fs-5 mb-0 text-white">'.$total_share.' <span class="fs-tiny font-normal">lần</h3>
				</div>
				<div class="brief-item m-0 overflow-hidden position-relative" style="background:#eba000">
					<p class="fs-12 text-white mb-2">Giao dịch</p>
					<h3 class="fs-5 mb-0 text-white">'.$total_transaction.' <span class="fs-tiny font-normal">căn</span></h3>
				</div>
				<div class="brief-item m-0 overflow-hidden position-relative" style="background:#c9c313">
					<p class="fs-12 text-white mb-2">Doanh số</p>
					<h3 class="fs-5 mb-0 text-white">'.shortNumber($total_grand).'</h3>
				</div>
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
        'html' => $html
    )); die();
}
function default_load_data_month_desktop(){
	 global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsShare = new Share();
	$clsBilling = new Billing();
	$clsCustomer = new Customer();
	$month = Input::post('month', date('m'));
	// Total
	$total_customer = $clsCustomer->countItem("(`admin_id`='{$profile_id}' or `use_globe`='1' or `list_share_id` like '%|{$profile_id}|%') and FROM_UNIXTIME(`reg_date`,'%m')='{$clsISO->parseNumber($month)}'");
	// Tiếp khách
	$total_share = $clsShare->countItem("`staff_id`='{$profile_id}' and FROM_UNIXTIME(`reg_date`,'%m')='{$clsISO->parseNumber($month)}'");
	// Billing
	$total_transaction = $clsBilling->countItem("`is_trash`=0 and `is_cancel`='0' and `staff_id`='{$profile_id}' 
	and FROM_UNIXTIME(`deposit_date`,'%m')='{$clsISO->parseNumber($month)}'");
	// Billing
	$total_grand = $clsBilling->sumItem("totalgrand","`is_trash`=0 and `is_cancel`='0' and `staff_id`='{$profile_id}' 
	and FROM_UNIXTIME(`deposit_date`,'%m')='{$clsISO->parseNumber($month)}'");
	###
	$html = '<div class="brief-item m-0 overflow-hidden position-relative" style="background:#ab0303">
		<p class="fs-12 text-white mb-2">Khách mới</p>
		<h3 class="fs-5 mb-0 text-white">'.$total_customer.' <span class="fs-tiny font-normal">lead</span></h3>
	</div>
	<div class="brief-item m-0 overflow-hidden position-relative" style="background:#1d6a01">
		<p class="fs-12 text-white mb-2">Tiếp khách</p>
		<h3 class="fs-5 mb-0 text-white">'.$total_share.' <span class="fs-tiny font-normal">lần</span></h3>
	</div>
	<div class="brief-item m-0 overflow-hidden position-relative" style="background:#eba000">
		<p class="fs-12 text-white mb-2">Giao dịch</p>
		<h3 class="fs-5 mb-0 text-white">'.$total_transaction.' <span class="fs-tiny font-normal">căn</span></h3>
	</div>
	<div class="brief-item m-0 overflow-hidden position-relative" style="background:#c9c313">
		<p class="fs-12 text-white mb-2">Doanh số</p>
		<h3 class="fs-5 mb-0 text-white">'.shortNumber($total_grand).'</h3>
	</div>';
	// Return
	echo $html; die();
}
function default_load_desktop_stock_sold(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsStock = new Stock();
	$start_date = strtotime(sprintf('%s 00:00:00', date('d-m-Y')));
	$due_date = strtotime(sprintf('%s 23:59:59', date('d-m-Y')));
	
	$list_days = array(
		'today' => array(
			'title' => 'Hôm nay',
			'start_date' => $start_date,
			'end_date' => $due_date
		), 
		'yesterday' => array(
			'title' => 'Hôm qua',
			'start_date' => strtotime('-1 days', $start_date),
			'end_date' => strtotime('-1 days', $due_date)
		), 
		'7day' => array(
			'title' => '07 ngày',
			'start_date' => strtotime("-7 days", $start_date),
			'end_date' => $due_date,
		), 
		'30day' => array(
			'title' => '30 ngày',
			'start_date' => strtotime("-30 days", $start_date),
			'end_date' => $due_date
		)
	);
	$html = '<div class="card mb-'.($deviceType=='phone'?'2':'3').' gotoLink cursor-pointer" href="/report/stock.html">
		<h5 class="card-header">Thống kê căn bán cao tầng</h5>
		<div class="card-body">
			<div class="d-flex flex-wrap gap-2">';
			foreach($list_days as $key => $val){
				$start_date = $val['start_date'];
				$end_date = $val['end_date'];
				//$clsStock->setDebug(true);
				$total_stocks = $clsStock->countItem("`status_id`='"._STOCK_STATUS_SOLD_ID."' and (`ms_date` between {$start_date} and {$end_date})");
				$html.= '<div class="gbox '.$key.' flex-fill p-'.($deviceType=='phone'?'2':'3').'">
					<h5 class="mb-2 fs-14">'.$val['title'].'</h5>
					<h3 class="fs-4 mb-0 fw-bold text-main">
						<span class="countTo" data-from="0" data-to="'.$total_stocks.'" data-speed="5000">'.$total_stocks.'</span>
						<span class="fs-12 fw-normal text-muted">căn</span></h3>
				</div>';
			}
		$html .= '
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_top_search_stock(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsLog = new Log();
	$clsStock = new Stock();
	$clsProperty = new Property();
	###
	$due_date = time();
	$start_date = strtotime("-3 days", $due_date);
	$list_blocks = array(73 => 'Zurich', 71 => 'Zenpark', 75 => 'Masteri', 1163 => 'Beverly', '_BLOCK_TYPE_LOWFLOOR_SALE' => 'Thấp tầng');
	$html = '<div class="card mb-'.($deviceType=='phone'?'2':'3').' gotoLink cursor-pointer" href="'.PCMS_URL.'/logs-sale.html">
		<h5 class="card-header">Thống kê lượt tra cứu 24h qua</h5>
		<div class="card-body">
			<div class="d-flex flex-wrap gap-2">';
			foreach($list_blocks as $block_id => $block_name){
				if($block_id == '_BLOCK_TYPE_LOWFLOOR_SALE'){
					$cnd = "`t2`.`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."'";
				} else {
					$cnd = "t2.block_id='{$block_id}' and `t2`.`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
					and `t2`.`project_id`='"._PROJECT_DEF_ID."'";
				}
				$tmp = $dbconn->getRow("SELECT count(`t1`.`target_id`) AS `total_stocks` FROM {$clsLog->tbl} AS `t1` 
				INNER JOIN {$clsStock->tbl} AS `t2` ON `t1`.`target_id`=`t2`.`stock_id` AND (`t1`.`type`='view' OR `t1`.`type`='view_stock') 
				WHERE {$cnd} and (`t1`.`reg_date` BETWEEN {$start_date} AND {$due_date})");
				
				$total_stocks = !empty($tmp) ? $tmp['total_stocks'] : 0;
				$html.= '<div class="gbox '.$key.' flex-fill '.($deviceType=='phone'?'p-2':'px-2 py-3').'">
					<h5 class="mb-2 fs-14">'.$block_name.'</h5>
					<h3 class="fs-5 mb-0 fw-bold text-main">
						<span data-from="0" data-to="'.$total_stocks.'" data-speed="1000">'.$clsISO->formatNumber2($total_stocks).'</span>
					</h3>
				</div>';
			}
		$html .= '
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_log_check_stock(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsLog = new Log();
	$clsStock = new Stock();
	$clsMember = new Member();
	$clsProperty = new Property();
	###
	$month = (int) Input::post('month', date('m'));
	$year = (int) Input::post('year', date('Y'));
	$f = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
	$fs = sprintf('%s/%s', $clsISO->parseNumber($month), date('y'));
	$total_members_all = $clsMember->countItem("`profile_type`='_sale'"); 
	$total_logs_all = $clsLog->countItem("(`type`='view_stock' or `type`='view')");
	$total_members = $clsMember->countItem("`profile_type`='_sale' and FROM_UNIXTIME(`reg_date`,'%m/%Y')='".$f."'"); 
	$total_logs = $clsLog->countItem("(`type`='view_stock' or `type`='view') and FROM_UNIXTIME(`reg_date`,'%m/%Y')='".$f."'"); 
	
	$html = '<div class="d-flex flex-wrap gap-1">
		<div class="gbox gotoLink flex-fill px-2 py-3">
			<h5 class="mb-2 fs-14">Tổng user</h5> 
			<h3 class="fs-5 mb-0 fw-bold text-main">
				<span data-from="0" data-to="'.$total_members_all.'">'.$clsISO->formatNumber2($total_members_all).'</span>
			</h3>
		</div>
		<div class="gbox gotoLink flex-fill px-2 py-3">
			<h5 class="mb-2 fs-14">Tổng tra cứu</h5>
			<h3 class="fs-5 mb-0 fw-bold text-main">
				<span data-from="0" data-to="'.$total_logs_all.'">'.$clsISO->formatNumber2($total_logs_all).'</span>
			</h3>
		</div>
		<div class="gbox gotoLink flex-fill px-2 py-3">
			<h5 class="mb-2 fs-14">Tổng user T.'.$month.'</h5> 
			<h3 class="fs-5 mb-0 fw-bold text-main">
				<span data-from="0" data-to="'.$total_members.'" data-speed="1000">'.$clsISO->formatNumber2($total_members).'</span>
			</h3>
		</div>
		<div class="gbox gotoLink flex-fill px-2 py-3">
			<h5 class="mb-2 fs-14">Tra cứu T.'.$month.'</h5>
			<h3 class="fs-5 mb-0 fw-bold text-main">
				<span data-from="0" data-to="'.$total_logs.'" data-speed="1000">'.$clsISO->formatNumber2($total_logs).'</span>
			</h3>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_euro2024(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	
	$theme = 'purpleskin';
	$assign_list['theme'] = $theme;
	
	require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
	/** Init Client */
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET);
	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
	$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
	$service = new Google_Service_Sheets($client);
	// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
	// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
	// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
	// get all the rows of a sheet
	$range = 'BXH'; // here we use the name of the Sheet to get all the rows
	$spreadsheetId = "1JLPVsJ5b0eIbJ4Rl5tn2cXm4mmdjWiE0ucSwxLwmlmQ";
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$tblData = $response->getValues();
	##
	$max_score = $second_score = $three_score = $top_4_score = $top_5_score = 0;
	$list_staffs = array();
	if(!empty($tblData)){
		$total_columns = count($tblData[1]);
		for($i=2; $i<count($tblData); $i++){
			$total_score = $total_goal = $total_match = 0;
			for($k= 1; $k < ($total_columns - 1); $k++ ){
				if(!empty($tblData[$i][$k])){
					$total_goal += 1;
					$total_score += $tblData[$i][$k];
				}
				$total_match += 1;
			}
			if($total_score > $max_score) {
				$max_score = $total_score;
			}
			$list_staffs[] = array(
				'row' => $i,
				'full_name' => $tblData[$i][0],
				'total_score' => $total_score,
				'total_match' => $total_match,
				'total_goal' => $total_goal
			);
		}
		// $clsISO->print_pre($list_staffs); die();
		$arr_total_score = @array_column($list_staffs, 'total_score');
		@array_multisort($arr_total_score, SORT_DESC, $list_staffs);
		$arr_total_score = @array_unique($arr_total_score);
		/** Second */
		$second_score = findNumberLargest($arr_total_score,2);
		$three_score = findNumberLargest($arr_total_score,3);
		$top_4_score = findNumberLargest($arr_total_score,4);
		$top_5_score = findNumberLargest($arr_total_score,5);
	}
	// $clsISO->print_pre($second_score); die();
	$assign_list['max_score'] = $max_score;
	$assign_list['second_score'] = $second_score;
	$assign_list['three_score'] = $three_score;
	$assign_list['top_4_score'] = $top_4_score;
	$assign_list['top_5_score'] = $top_5_score;
	$assign_list['list_staffs'] = $list_staffs;
	/*=============Title & Description Page==================*/
	$title_page = 'Bảng xếp hạng Euro 2024 | '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
}
function findNumberLargest(array $arr, $pos=2){
	//If array is empty then return
	if(empty($arr)) {
		return;
	}
	//sort the array in ascending order
	sort($arr);
	//save the element from the second last position of sorted array
	$numberLargest = $arr[sizeof($arr)- $pos];
	//return second-largest number
	return $numberLargest;
}
function default_open_euro(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	
	$uid = $clsISO->getUniqid();
	$row = (int) Input::post('row', 2);
	$total_score = (int) Input::post('total_score');
	require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
	/** Init Client */
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET);
	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
	$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
	$service = new Google_Service_Sheets($client);
	// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
	// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
	// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
	// get all the rows of a sheet
	$range = 'BXH'; // here we use the name of the Sheet to get all the rows
	$spreadsheetId = "1JLPVsJ5b0eIbJ4Rl5tn2cXm4mmdjWiE0ucSwxLwmlmQ";
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$tblData = $response->getValues();
	###
	$html = "_error";
	if(!empty($tblData)){
		$arr_matchs = $tblData[1];
		$oneStaff = $tblData[$row];
		$html = '<div class="modal-dialog modal-dialog-scrollable'.($deviceType=='phone'?' modal-dialog-centered':'').'">
			<div class="modal-content modal-euro">
				<div class="modal-header">
					<h5 class="modal-title text-white">'.$oneStaff[0].' ('.$total_score.')</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<table class="table table-euro">
						<thead><tr>
							<th class="align-center text-center" width="3%">STT</th>
							<th class="align-center">Trận đấu</th>
							<th class="align-center text-center">Điểm</th>
						</tr></thead>';
					for($i=1; $i<count($arr_matchs)-1; $i++){
						$html.='<tr>
							<td class="text-center">'.$i.'</td>
							<td>'.$arr_matchs[$i].' '.(isset($oneStaff[$i]) && !empty($oneStaff[$i])?'<i class=\'bx bx-check-double\'></i>':'😝').'</td>
							<td class="text-center">'.(isset($oneStaff[$i]) && !empty($oneStaff[$i])?$oneStaff[$i]:0).'</td>
						</tr>';
					}
					$html.= '</table>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>';
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_config(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	
	$setting = Input::get("setting");
	$Euro_Notes= $clsConfiguration->getValue(sprintf('SiteMsg_%s', $setting));
	// Return
	echo html_entity_decode($Euro_Notes); die();
}
//========Tri ân =============
function default_gratitude(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsNews', $clsNews);
	$smarty->assign('clsProfile', $clsProperty);
	$smarty->assign('clsProperty', $clsProperty);
	###
	$cat_id = input::get("cat_id",0);
	$assign_list["cat_id"] = $cat_id;
	if($cat_id == 0 || $cat_id != _NEWS_GRATITUDE_CAT_ID) {
		header("Location: /");exit();
	}
	
	$show = Input::get("show", "_default");
	if($show=="detail"){
		$news_id = Input::get('news_id', 0);
		$scriptJs.= '<a class="autoclick_'.$news_id.'"" news_id="'.$news_id.'" onClick="open_gratitude(this, event)" action="_detail" ></a>
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					history.pushState("", "", "/tri-an.html");
					$(\'.autoclick_'.$news_id.'\').trigger(\'click\');
				}, 500);
			})
		</script>';
	}
	$smarty->assign("scriptJs",$scriptJs);
	
	###
	$cond = "is_trash=0 and is_online=1 and cat_id='"._NEWS_GRATITUDE_CAT_ID."'";
	/** Pagination */
	$per_page = 10;
	$current_page = (int) Input::get('page',1);
	$total_record = $clsNews->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	#
	$offset = ($current_page-1) * $per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";
	/** End pagination */
	$order_by = " ORDER BY `order_no` DESC";
	$field = "{$clsNews->pkey},title,content,images,user_id,reg_date,order_no,liked_json";
	$field.= ",cat_id,events_config,start_date,end_date,regis_info,more_informatiion";
	$list_post = $clsNews->getAll($cond.$order_by.$limitCond, $field);
	if(!empty($list_post)){
		$arr_profile_cached = array();
		foreach($list_post as $key => $val){
			$user_id = $val['user_id'];
			if(isset($arr_profile_cached[$user_id])){
				$db_profile = $arr_profile_cached[$user_id];
			} else {
				$db_profile = $clsProfile->getProfile($user_id);
				$arr_profile_cached[$user_id] = $db_profile;
			}
			$list_post[$key]['db_profile'] = $db_profile;
			$liked_json = !empty($val['liked_json']) 
				? json_decode(html_entity_decode($val['liked_json']), true) : array();
			
			$total_liked = 0;
			if(!empty($liked_json)){
				foreach($liked_json as $okey => $ids){
					if(is_array($ids) && !empty($ids)){
						$total_liked+= count($ids);
					}
				}
			}
			$list_post[$key]['total_liked'] = $total_liked;
			$status_liked = $clsNews->checkLiked($val[$clsNews->pkey], $val);
			$list_post[$key]['status_liked'] = $status_liked;
			###
			$events_config = !empty($val['events_config']) 
				? json_decode(html_entity_decode($val['events_config']), true) : array();
			$list_post[$key]['events_config'] = $events_config;
			$regis_info = $val['regis_info'];
			$regis_info = !empty($regis_info) 
				? json_decode(html_entity_decode($regis_info), true) : array();
			$is_registed = 0;
			if($val['cat_id'] == _NEWS_EVENT_CAT_ID && !empty($regis_info) 
				&& array_key_exists($profile_id, $regis_info)){
				$is_registed = 1;
			}
			$list_post[$key]['is_registed'] = $is_registed;
			###
			$total_comments = $clsNews->getTotalComment($val[$clsNews->pkey]);
			$list_post[$key]['total_comments'] = $total_comments;
			$list_post[$key]['total_actions'] = $total_liked + $total_comments;
			
			$more_information = !empty($val['more_informatiion']) ? $clsISO->to_array_json($val['more_informatiion']) : [];
			$scoreDep = !empty($more_information['scoreDep']) ? $more_information['scoreDep'] : [];
			$scoreEmp = !empty($more_information['scoreEmp']) ? $more_information['scoreEmp'] : [];
			if(!empty($scoreDep)) {
				$lstDepartment = $clsProperty->getAllCache("property_id IN (".implode(",",array_keys($scoreDep)).")",$clsProperty->pkey.",title");
				$list_post[$key]['department'] = $lstDepartment;
			}else{
				$list_post[$key]['department'] = [];
			}
			if(!empty($scoreEmp)) {
				$lstStaff = $clsProfile->getAll("profile_id IN (".implode(",",array_keys($scoreEmp)).")",$clsProfile->pkey.',full_name');
				$list_post[$key]['staff'] = $lstStaff;
			}else{
				$list_post[$key]['staff'] = [];
			}
		}
	}
//	 $clsISO->print_pre($list_post); die();
	$smarty->assign('per_page', $per_page);
	$smarty->assign('list_post', $list_post);
	$smarty->assign('total_record', $total_record);
	/*=============Title & Description Page==================*/
	$title_page = 'Bản tin tri ân Future Homes';
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $title_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_list_department(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	####
	$_results = array();
	$lstPartment = $clsProperty->getAllCache("property_type='_DEPARTMENT' and property_id NOT IN ("._DEPARTMENT_SALE_ID.","._DEPARTMENT_CTV_ID.")");
	if(!empty($lstPartment)){
		foreach($lstPartment as $key => $val){
			$_results[] = array(
				'id' => $val[$clsProperty->pkey],
				'slug' => $core->replaceSpace($val['slug']),
				'text' => sprintf('%s', $val['title'])
			);
		}
	}
	// return
	echo json_encode($_results); die();
}
function default_open_gratitude(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id;
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$news_id = (int) Input::post('news_id', 0);
	$action = Input::post('action', "_detail");
	$gid = Input::post('gid', "");
	$uid = $clsISO->getUniqid();
	#
	$is_registed = 0;
	$cat_id = _NEWS_GRATITUDE_CAT_ID;
	vnSessionDelVar("gratitude");
	$oneNews = $events_config = $regis_info = $list_images = array();
	if($news_id > 0 || $holderG=='_detail'){
		$oneNews = $clsNews->getOne($news_id);
		$images = $oneNews['images'];
		$regis_info = $oneNews['regis_info'];
		$events_config = $oneNews['events_config'];
		$regis_info = !empty($regis_info) 
			? json_decode(html_entity_decode($regis_info), true) 
			: array();
		
		if($oneNews['cat_id']==_NEWS_EVENT_CAT_ID 
			&& !empty($regis_info) && @array_key_exists($profile_id, $regis_info)){
			$is_registed = 1;
		}	
		//$clsISO->print_pre($profile_id); die();
		$events_config = !empty($events_config) 
			? json_decode(html_entity_decode($events_config), true) 
			: array();
		$list_images = !empty($images) 
			? json_decode(html_entity_decode($images), true) : array();
		if($action=='_detail' || $action == '_edit'){
			$user_id = $oneNews['user_id'];
			$db_profile = $clsProfile->getProfile($user_id);
			$oneNews['db_profile'] = $db_profile;
			
			$more_information = !empty($oneNews['more_informatiion']) ? $clsISO->to_array_json($oneNews['more_informatiion']) : [];
			$scoreDep = !empty($more_information['scoreDep']) ? $more_information['scoreDep'] : [];
			$scoreEmp = !empty($more_information['scoreEmp']) ? $more_information['scoreEmp'] : [];
			if(!empty($scoreDep)) {
				$lstDepartment = $clsProperty->getAllCache("property_id IN (".implode(",",array_keys($scoreDep)).")",$clsProperty->pkey.",title");
				foreach($lstDepartment as $key => $value) {
					$total_profile = $clsProfile->countItem("`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND profile_id <> '{$profile_id}' AND department_id ='".$value['property_id']."'");
					$lstDepartment[$key]['total_profile'] = $total_profile;
					$lstDepartment[$key]['score'] = $scoreDep[$value['property_id']];
				}
			}else{
				$lstDepartment = [];
			}
			if(!empty($scoreEmp)) {
				$lstStaff = $clsProfile->getAll("profile_id IN (".implode(",",array_keys($scoreEmp)).")",$clsProfile->pkey.',full_name');
				foreach($lstStaff as $key => $value) {
					$lstStaff[$key]['score'] = $scoreEmp[$value['profile_id']];
				}
			}else{
				$lstStaff = [];
			}
			$status_liked = $clsNews->checkLiked($news_id, $oneNews);			
			$smarty->assign('status_liked', $status_liked);
			$smarty->assign('lstDepartment', $lstDepartment);
			$smarty->assign('lstStaff', $lstStaff);
		}
	}	
	
	$more_informatiion = !empty($oneNews['more_informatiion']) ? $clsISO->to_array_json($oneNews['more_informatiion']) : [];
	$scoreDep = $more_informatiion['scoreDep'];
	$scoreEmp = $more_informatiion['scoreEmp'];
	$smarty->assign('department_id', json_encode(array_keys($scoreDep))); 
	$smarty->assign('employ_id', json_encode(array_keys($scoreEmp)));
	$lstPartment = $clsProperty->getAllCache("property_type='_DEPARTMENT' and property_id IN (".implode(',',array_keys($scoreDep)).")");
	$smarty->assign('lstPartment', $lstPartment);
	$list_staffs= $clsProfile->getAll("is_trash=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' and profile_id IN (".implode(",",array_keys($scoreEmp)).") order by reg_date DESC", "{$clsProfile->pkey},code,full_name");
	$smarty->assign('list_staffs', $list_staffs);
	
	$smarty->assign('uid', $uid);
	$smarty->assign('news_id', $news_id);
	$smarty->assign('action', $action);
	$smarty->assign('gid', $gid);
	$smarty->assign('oneNews', $oneNews);
	$smarty->assign('is_registed', $is_registed);
	$smarty->assign('list_images', $list_images);
	$smarty->assign('events_config', $events_config);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.gratitude.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'list_images' => $list_images,
	)); die();
}
function default_updateNumberPoint(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$assign_list;
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$type = Input::post('type', "");
	$objID = Input::post('objID',0);
	$score = Input::post('score',0);
	$score = (int)str_replace(".","",$score);
	
	$gratitude = (!empty(vnSessionGetVar("gratitude"))) ? vnSessionGetVar("gratitude") : [];
	$arr_staff = !empty($gratitude['arr_staff']) ? $gratitude['arr_staff'] : [];
	$arr_department = !empty($gratitude['arr_department']) ? $gratitude['arr_department'] : [];	
	if($type != "" && $objID > 0) {
		if($type == "dep") {
			$arr_department[$objID] = $score;
		}else if($type == 'emp') {
			$arr_staff[$objID] = $score;
		}
	}
	vnSessionSetVar("gratitude",['arr_staff' => $arr_staff, 'arr_department' => $arr_department]);
}
function default_loadTableEmploy(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$assign_list;
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$department_ids = Input::post('department_ids', []);
	$staff_ids = Input::post('staff_ids', []);
	$uid = $clsISO->getUniqid();
	$gratitude = (!empty(vnSessionGetVar("gratitude"))) ? vnSessionGetVar("gratitude") : [];
	$arr_staff = !empty($gratitude['arr_staff']) ? $gratitude['arr_staff'] : [];
	$arr_department = !empty($gratitude['arr_department']) ? $gratitude['arr_department'] : [];
	#
	if(!empty($staff_ids)) {
		$cond = " `is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND profile_id <> '{$profile_id}' AND profile_id IN (".implode(",",$staff_ids).")";
		$lst_profile = $clsProfile->getAll($cond,$clsProfile->pkey.',full_name');
		foreach ($lst_profile as $key => $value) {
			if(!in_array($value['profile_id'],array_keys($arr_staff))){
				$arr_staff[$value['profile_id']] = 0;
			}
		}
	}
	foreach ($arr_staff as $key => $value) {
		if(!in_array($key,$staff_ids)){
			unset($arr_staff[$key]);
		}
	}	
	
	if(!empty($department_ids)) {
		$lstDepartment = $clsProperty->getAllCache("property_id IN (".implode(",",$department_ids).")");
		foreach ($lstDepartment as $key => $value) {
			$total_profile = $clsProfile->countItem("`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND profile_id <> '{$profile_id}' AND department_id ='".$value['property_id']."'");
			$lstDepartment[$key]['total_profile'] = $total_profile;
			unset($total_profile);
			if(!in_array($value['property_id'],array_keys($arr_department))){
				$arr_department[$value['property_id']] = 0;
			}
		}	
	}
	foreach ($arr_department as $key => $value) {
		if(!in_array($key,$department_ids)){
			unset($arr_department[$key]);
		}
	}		
	vnSessionSetVar("gratitude",['arr_staff' => $arr_staff, 'arr_department' => $arr_department]);
	
	$assign_list['uid'] = $uid;
	$assign_list['lst_profile'] = $lst_profile;
	$assign_list['lstDepartment'] = $lstDepartment;
	$assign_list['arr_staff'] = $arr_staff;
	$assign_list['arr_department'] = $arr_department;	
	
	// Return
	$html = $core->build('_ajax.loadTableEmploy.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'list_images' => $list_images,
	)); die();
}
function default_pop_save_gratitude(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsNews = new News();
	$clsProfile = new Profile();
	$fpoint = new FPoint();
	$news_id = (int) Input::post('news_id', 0);
	$cat_id = _NEWS_GRATITUDE_CAT_ID;
	$title = Input::post('title');
	$content = Input::post('content');
	$department_id = Input::post('department_id',[]);
	$staff_id = Input::post('staff_id',[]);
	$scoreDep = Input::post('scoreDep',[]);
	$scoreEmp = Input::post('scoreEmp',[]);
	$images = Input::post('images');
	$total_Lpoint = $oneProfile['total_Lpoint'];
	foreach($scoreDep as $key => $value) {
		$scoreDep[$key] = str_replace(".","",$value);
	}
	foreach($scoreEmp as $key => $value) {
		$scoreEmp[$key] = str_replace(".","",$value);
	}
	
	$total_score_minus = 0; //tong diem tru
	$arr_score_total = []; //mang nhan vien duoc cong diem
	if(!empty($department_id)) { // co chon phong ban
		foreach ($department_id as $department) {
			$cond = " `is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND profile_id <> '{$profile_id}' AND department_id ='{$department}' ";
			if(!empty($staff_id)) { // neu chon nhan vien co thuoc phong ban thi khong cong diem theo phong ban
				$cond .= " AND profile_id NOT IN (".implode(",",$staff_id).") ";
			}
			$lst_profile = $clsProfile->getAll($cond,$clsProfile->pkey.',full_name,total_Lpoint');
			$total_score_minus += (count($lst_profile) * (int)$scoreDep[$department]);
			if($department != _USER_DIRECTOR_ID) { // neu khong phai la phong BÐH thi lay danh sách diem cong cua nhan vien phong do
				foreach($lst_profile as $key => $value) {
					$arr_score_total[$value['profile_id']] = $value;
					$arr_score_total[$value['profile_id']]["score_plus"] = (int)$scoreDep[$department];
				}
			}			
		}			
	}
	
	if(!empty($staff_id)) { // co chon nhan vien
		$cond = " `is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND profile_id <> '{$profile_id}' AND profile_id IN (".implode(",",$staff_id).")";
		$lst_profile = $clsProfile->getAll($cond,$clsProfile->pkey.',full_name,total_Lpoint,role_id');
		foreach ($lst_profile as $key => $value) {
			$total_score_minus += (int)$scoreEmp[$value['profile_id']];
			if(!in_array($value['role_id'], array(_ROLE_GD_MANAGER, _ROLE_PGD_MANAGER))) {
				$arr_score_total[$value['profile_id']] = $value;
				$arr_score_total[$value['profile_id']]["score_plus"] = (int)$scoreEmp[$value['profile_id']];
			}
		}		
	};
	
	if($total_score_minus > $total_Lpoint && !$clsISO->checkSupper()) { // neu khong du diem va khong phai BÐH
		$data = [
			"result"		=>	false,
			"total_score"	=>	$total_Lpoint,
			"type"			=>	"minus_error"
		];
		echo json_encode($data);die;
	}
	
	$more_information = [
		"scoreDep"	=>	$scoreDep,
		"scoreEmp"	=>	$scoreEmp,
	];
	$msg = "_error";
	
	if($news_id > 0){
		if($clsNews->updateOne($news_id, array(
			'cat_id' => $cat_id,
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'content' => $content,
			'images' => json_encode($images, JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update' => $profile_id,
		))){
			$data = [
				"result"		=>	true,
				"type"			=>	"update",
			];
		}
	} else { 
		$news_id = $clsNews->getMaxId();
		$arr_data = array(
			$clsNews->pkey => $news_id,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'cat_id' => $cat_id,
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'order_no' => $clsNews->getMaxId(),
			'content' => $content,
			'images' => json_encode($images, JSON_UNESCAPED_UNICODE),
			'reg_date' => time(),
			'more_informatiion' => json_encode($more_information),
		);
		if($clsNews->insert($arr_data)){
			$data = [
				"result"		=>	true,
				"type"			=>	"add",
			];
			foreach($arr_score_total as $key => $value) { // Cong diem loyalty cho doi tuong duoc chon
				$clsProfile->updateOne($value["profile_id"], [
					"total_Lpoint"	=>	(int)$value['total_Lpoint']+(int)$value['score_plus']
				]);
			}
			if(!$clsISO->checkSupper()) { // neu khong phai BDH thi tru diem loyalty
				$clsProfile->updateOne($profile_id, [
					"total_Lpoint"	=>	(($total_Lpoint-$total_score_minus) > 0) ? $total_Lpoint-$total_score_minus : 0
				]);
			}
			
			$lstProfile = $clsProfile->getAll("is_active=1 and is_verified=1 and is_trash=0",$clsProfile->pkey);
			$arr_profile = [];
			foreach($lstProfile as $key => $value){
				$arr_profile[] = $value['profile_id'];
			}
			$clsNotify = new Notify();
			$titleNoty = sprintf('<strong>%s</strong> đã đăng bài tri ân <strong>%s</strong>', $clsProfile->getFullName($profile_id), $title);
//			$clsNotify->insertNotify('Gratitude',$clsNews->pkey, $news_id, $titleNoty, time(), $arr_profile);
			$clsNotify->insertNotify('Gratitude',$clsNews->pkey, $news_id, $titleNoty, time(), [289]);
		}
	}
	// Return	
	echo json_encode($data);die;
}
function default_today(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile;
	$clsProfile = new Profile(); 
	$clsBilling = new Billing();
	$clsProperty = new Property();
	
	$scriptJs = "";
	$cmd = Input::get('cmd', "");
	if($cmd=="_detail"){
		$course_id = Input::get('course_id', 0);
		$scriptJs.= '<a class="autoclick_'.$course_id.'"" course_id="'.$course_id.'" 
		onClick="$Core.course.open(this, event)"></a>
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					$(\'.autoclick_'.$course_id.'\').trigger(\'click\').remove();
				}, 200);
			});
		</script>';
	}
	$assign_list["scriptJs"] = $scriptJs;
	$list_department_id = $oneProfile['list_department_id'];
	$arr_department_ids = !empty($list_department_id) 
		? $clsISO->getArrayByTextSlash($list_department_id) : array();
	
	$permiss_add = $clsISO->checkPermission("create_course");
	$permiss_edit = $clsISO->checkPermission("edit_code");
	$assign_list["permiss_add"] = $permiss_add;
	$assign_list["permiss_edit"] = $permiss_edit; 
	###
	$list_preloaders = array();
	for($i= 0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
    /*=============Title & Description Page==================*/
	$title_page = 'Can ho noi bat - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
?>