/* Tab Tong quan (act=overview) — AngularJS controller, rut gon tu MF DetailCtrl.
   3 cap: project (SOP) / block (luoi toa + sidebar) / building (mat bang + can + tien ich). */
var overviewApp = angular.module('overviewApp', []);
overviewApp.controller('OverviewCtrl', ['$scope', '$http', '$timeout', '$window', '$sce', function ($scope, $http, $timeout, $window, $sce) {
	var initial = window.PROJECT_OVERVIEW_DATA || {};
	$scope.data = initial;
	$scope.loading = false;
	$scope.investor_name = '';
	$scope.parsedFloors = [];
	$scope.activeFloor = '';
	$scope.activeFloorImage = '';
	$scope.filters = { status: '' };
	$scope.isMobile = $window.innerWidth <= 767;

	$scope.trustHtml = function (html) {
		return $sce.trustAsHtml(html || '');
	};
	$scope.stripTags = function (html) {
		if (!html) {
			return '';
		}
		var tmp = document.createElement('DIV');
		tmp.innerHTML = html;
		return tmp.textContent || tmp.innerText || '';
	};
	/* Loc luoi toa theo trang thai mo ban (on_sale: 1 dang mo, 2 sap mo, 0 het) */
	$scope.blockFilter = function (item) {
		if ($scope.filters.status) {
			var statusMap = { 'selling': 1, 'coming': 2, 'soldout': 0 };
			if (item.on_sale !== statusMap[$scope.filters.status]) {
				return false;
			}
		}
		return true;
	};
	/* Sidebar: phan khu khac, dang mo ban len dau */
	$scope.getSoldOutBlocks = function () {
		if (!$scope.data || !$scope.data.list_blocks) {
			return [];
		}
		return $scope.data.list_blocks.slice().sort(function (a, b) {
			var order = { 1: 0, 2: 1, 0: 2 };
			return (order[a.on_sale] || 3) - (order[b.on_sale] || 3);
		});
	};
	angular.element($window).on('resize', function () {
		$scope.$apply(function () {
			$scope.isMobile = $window.innerWidth <= 767;
		});
	});

	/* Phan tich danh sach layout thanh danh sach tang:
	   "Tang 5-6" -> gan anh cho tang 5..6; "dien hinh" -> anh mac dinh moi tang */
	$scope.parseFloors = function (layouts, totalFloorsFromApi) {
		if (!layouts) {
			return [];
		}
		var specificMap = {};
		var typicalImage = '';
		var maxFloorFound = 0;
		angular.forEach(layouts, function (l) {
			var title = (l.title || '').toLowerCase();
			if (title.indexOf('điển hình') !== -1) {
				typicalImage = l.image;
			} else if (title.indexOf('tổng thể') !== -1) {
				// anh tong the phan khu: khong gan vao tang
			} else {
				var rangeMatch = title.match(/(\d+)\s*[-_>]\s*(\d+)/);
				if (rangeMatch) {
					var start = parseInt(rangeMatch[1]);
					var end = parseInt(rangeMatch[2]);
					if (end > maxFloorFound) {
						maxFloorFound = end;
					}
					for (var i = start; i <= end; i++) {
						specificMap[i] = l.image;
					}
				} else {
					var regex = /(\d+[a-zA-Z]?)/g;
					var match;
					while ((match = regex.exec(title)) !== null) {
						var floorNum = parseInt(match[1]);
						if (!isNaN(floorNum) && floorNum > maxFloorFound) {
							maxFloorFound = floorNum;
						}
						specificMap[match[1]] = l.image;
					}
				}
			}
		});
		var totalFloors = (totalFloorsFromApi && totalFloorsFromApi > 0) ? totalFloorsFromApi : maxFloorFound;
		if (!totalFloors && typicalImage) {
			totalFloors = 2; // chi co anh dien hinh: van hien 1 tang dai dien
		}
		var result = [];
		for (var f = 2; f <= totalFloors; f++) {
			var name = f.toString();
			if (f === 13) {
				name = '12A'; // quy uoc bo tang 13
			}
			var display = name;
			if (display.length === 1) {
				display = '0' + display;
			}
			var img = specificMap[f] || specificMap[name] || specificMap[display] || typicalImage;
			result.push({ name: display, val: name, image: img });
		}
		return result;
	};

	$scope.selectFloor = function (fl) {
		$scope.activeFloor = fl.name;
		$scope.activeFloorImage = fl.image;
	};

	$scope.initBuildingLayouts = function () {
		var layouts = $scope.data.list_layouts;
		if (!layouts || !layouts.length) {
			return;
		}
		var totalFloorsFromApi = 0;
		if ($scope.data.more_information && $scope.data.more_information.number_floor) {
			totalFloorsFromApi = parseInt($scope.data.more_information.number_floor) || 0;
		}
		$scope.parsedFloors = $scope.parseFloors(layouts, totalFloorsFromApi);
		if ($scope.parsedFloors.length > 0) {
			var defaultFloor = $scope.parsedFloors.find(function (f) { return f.name === '12'; }) || $scope.parsedFloors[0];
			$scope.selectFloor(defaultFloor);
		}
	};

	$scope.initAptOwl = function () {
		$timeout(function () {
			var $el = $('.owl-apt-details-carousel');
			if ($el.length && window.jQuery && jQuery.fn.owlCarousel) {
				if ($el.data('owl.carousel')) {
					$el.data('owl.carousel').destroy();
					$el.removeClass('owl-loaded owl-drag');
				}
				$el.owlCarousel({
					items: 1,
					margin: 10,
					dots: false,
					nav: false,
					loop: false,
					mouseDrag: true,
					touchDrag: true
				});
			}
		}, 300);
	};

	$scope.initUtilityOwl = function () {
		$timeout(function () {
			var $el = $('.owl-utility-carousel');
			if ($el.length && window.jQuery && jQuery.fn.owlCarousel) {
				if ($el.data('owl.carousel')) {
					$el.data('owl.carousel').destroy();
					$el.removeClass('owl-loaded owl-drag');
				}
				$el.owlCarousel({
					items: 6,
					margin: 15,
					dots: false,
					nav: false,
					mouseDrag: true,
					touchDrag: true,
					responsive: {
						0: { items: 1 },
						576: { items: 2 },
						768: { items: 3 },
						1000: { items: 6 }
					}
				});
			}
		}, 300);
	};

	$scope.openStock = function (st) {
		if (!st || !st.stock_id) {
			return;
		}
		if (typeof $Core !== 'undefined' && $Core.helper && $Core.helper.open_stock) {
			$Core.helper.open_stock(st.stock_id);
		}
	};

	$scope.loadData = function () {
		$scope.loading = true;
		var url = '/index.php?mod=home&sub=project&act=api_overview'
			+ '&project_id=' + (initial.project_id || 0)
			+ '&block_id=' + (initial.block_id || 0)
			+ '&building_id=' + (initial.building_id || 0)
			+ '&show=' + (initial.show || '');
		$http.get(url).then(function (res) {
			if (res.data && res.data.success) {
				$scope.data = res.data.data;
				$scope.investor_name = $scope.data.investor_name || '';
				if ($scope.data.show == 'building') {
					$scope.initBuildingLayouts();
					$scope.initAptOwl();
					$scope.initUtilityOwl();
				}
			}
		}).finally(function () {
			$scope.loading = false;
		});
	};

	/* Zoom mat bang bang smartZoom (co san trong store.min.js) */
	$scope.$watch('activeFloorImage', function (newVal) {
		if (!newVal) {
			return;
		}
		$timeout(function () {
			var $img = $('#floorplan-img');
			if ($img.length && window.jQuery && jQuery.fn.smartZoom) {
				if ($img.smartZoom('isPluginActive')) {
					$img.smartZoom('destroy');
				}
				var initZoom = function () {
					$img.smartZoom({
						'containerClass': 'zoomableContainer',
						'containerBackground': 'transparent',
						'maxScale': 4,
						'dblClickMaxScale': 2
					});
				};
				if ($img[0].complete && $img[0].naturalWidth > 0) {
					initZoom();
				} else {
					$img.off('load.smartzoom').on('load.smartzoom', function () {
						$timeout(initZoom, 100);
					});
				}
			}
		}, 400);
	});

	$timeout(function () {
		$(document).off('click.aptnav-prev').on('click.aptnav-prev', '.js__apt-carousel-prev', function () {
			$('.owl-apt-details-carousel').trigger('prev.owl.carousel');
		});
		$(document).off('click.aptnav-next').on('click.aptnav-next', '.js__apt-carousel-next', function () {
			$('.owl-apt-details-carousel').trigger('next.owl.carousel');
		});
		$(document).off('click.utilnav-prev').on('click.utilnav-prev', '.js__util-carousel-prev', function () {
			$('.owl-utility-carousel').trigger('prev.owl.carousel');
		});
		$(document).off('click.utilnav-next').on('click.utilnav-next', '.js__util-carousel-next', function () {
			$('.owl-utility-carousel').trigger('next.owl.carousel');
		});
		$(document).off('click.zoom-in').on('click.zoom-in', '#btn-zoom-in', function (e) {
			e.preventDefault();
			var $img = $('#floorplan-img');
			if ($img.length && $img.smartZoom('isPluginActive')) {
				$img.smartZoom('zoom', 0.4);
			}
		});
		$(document).off('click.zoom-out').on('click.zoom-out', '#btn-zoom-out', function (e) {
			e.preventDefault();
			var $img = $('#floorplan-img');
			if ($img.length && $img.smartZoom('isPluginActive')) {
				$img.smartZoom('zoom', -0.4);
			}
		});
		$(document).off('click.zoom-reset').on('click.zoom-reset', '#btn-zoom-reset', function (e) {
			e.preventDefault();
			var $img = $('#floorplan-img');
			if ($img.length && $img.smartZoom('isPluginActive')) {
				$img.smartZoom('destroy');
				$img.smartZoom({
					'containerClass': 'zoomableContainer',
					'containerBackground': 'transparent',
					'maxScale': 4,
					'dblClickMaxScale': 2
				});
			}
		});
	}, 500);

	$scope.loadData();
}]);
