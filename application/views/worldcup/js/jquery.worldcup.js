/* =========================================================================
   jquery.worldcup.js — AngularJS app cho mini game Dự đoán World Cup 2026
   (PHIÊN BẢN PRODUCTION — dữ liệu lấy từ API backend, lưu DB).
   Module wcApp · MainCtrl · filters · directive <ic>/<flag>/<wc-file>.
   Bootstrap data: window.WC_BOOT (inject từ default.tpl).
   AngularJS 1.8.2 đã nạp toàn cục qua store.min.js.
   ========================================================================= */
(function () {
    "use strict";
    if (typeof angular === "undefined") { return; }

    var app = angular.module("wcApp", []);
    var MINUTE = 60 * 1000;
    var BOOT = window.WC_BOOT || {};
    var API = (window.PCMS_URL || "") + "/index.php?mod=worldcup&act=api_";
    var $j = window.jQuery;

    /* ---- helpers ---------------------------------------------------------- */
    function pad(n) { return (n < 10 ? "0" : "") + n; }
    function split(ms) {
        if (ms < 0) { ms = 0; }
        var s = Math.floor(ms / 1000);
        return { d: Math.floor(s / 86400), h: Math.floor((s % 86400) / 3600), m: Math.floor((s % 3600) / 60), s: s % 60 };
    }

    /*=============== [Worldcup - Bộ icon Lucide inline] - START ===============*/
    var WC_ICONS = {
        'swords': '<polyline points="14.5 17.5 3 6 3 3 6 3 17.5 14.5"/><line x1="13" x2="19" y1="19" y2="13"/><line x1="16" x2="20" y1="16" y2="20"/><line x1="19" x2="21" y1="21" y2="19"/><polyline points="14.5 6.5 18 3 21 3 21 6 17.5 9.5"/><line x1="5" x2="9" y1="14" y2="18"/><line x1="7" x2="4" y1="17" y2="20"/><line x1="3" x2="5" y1="19" y2="21"/>',
        'goal': '<path d="M12 13V2l8 4-8 4"/><path d="M20.55 10.23A9 9 0 1 1 8 4.94"/><path d="M8 10a5 5 0 1 0 8.9 2.02"/>',
        'flag': '<path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" x2="4" y1="22" y2="15"/>',
        'history': '<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/>',
        'bar-chart-3': '<path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/>',
        'trophy': '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>',
        'shield-check': '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
        'target': '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
        'gift': '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/>',
        'lock': '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'circle-dot': '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="1"/>',
        'info': '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>',
        'timer': '<line x1="10" x2="14" y1="2" y2="2"/><line x1="12" x2="15" y1="14" y2="11"/><circle cx="12" cy="14" r="8"/>',
        'users': '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'check-check': '<path d="M18 6 7 17l-5-5"/><path d="m22 10-7.5 7.5L13 16"/>',
        'check': '<path d="M20 6 9 17l-5-5"/>',
        'check-circle': '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
        'x': '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
        'clock': '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        'sparkles': '<path d="M9.94 14.06A2 2 0 0 0 8.5 12.6l-5.14-1.32a.5.5 0 0 1 0-.96L8.5 9a2 2 0 0 0 1.44-1.44l1.32-5.14a.5.5 0 0 1 .96 0L13.5 7.56A2 2 0 0 0 14.94 9l5.14 1.32a.5.5 0 0 1 0 .96L14.94 12.6a2 2 0 0 0-1.44 1.44l-1.32 5.14a.5.5 0 0 1-.96 0z"/>',
        'flame': '<path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.07-2.14-.22-4.05 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.15.43-2.29 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>',
        'sliders-horizontal': '<line x1="21" x2="14" y1="4" y2="4"/><line x1="10" x2="3" y1="4" y2="4"/><line x1="21" x2="12" y1="12" y2="12"/><line x1="8" x2="3" y1="12" y2="12"/><line x1="21" x2="16" y1="20" y2="20"/><line x1="12" x2="3" y1="20" y2="20"/><line x1="14" x2="14" y1="2" y2="6"/><line x1="8" x2="8" y1="10" y2="14"/><line x1="16" x2="16" y1="18" y2="22"/>',
        'pencil': '<path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/>',
        'file-spreadsheet': '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M8 13h2"/><path d="M14 13h2"/><path d="M8 17h2"/><path d="M14 17h2"/>',
        'plus': '<path d="M5 12h14"/><path d="M12 5v14"/>',
        'rotate-ccw': '<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/>',
        'alert-circle': '<circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>',
        'alert-triangle': '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/>',
        'download': '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/>',
        'log-in': '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/>',
        '_default': '<circle cx="12" cy="12" r="9"/>'
    };
    /*=============== [Worldcup - Bộ icon Lucide inline] - END ===============*/

    /*=============== [Worldcup - Directive ic/flag/wcFile] - START ===============*/
    app.directive("ic", function () {
        return {
            restrict: "E",
            scope: { name: "@", color: "@", cls: "@" },
            link: function (s, el) {
                function render() {
                    var inner = WC_ICONS[s.name] || WC_ICONS._default;
                    el.html('<svg class="ic ' + (s.cls || "") + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
                        'stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + inner + '</svg>');
                    if (s.color) { el.css("color", s.color); }
                }
                render();
                s.$watch("name", function (n, o) { if (n !== o) { render(); } });
                s.$watch("color", function (n, o) { if (n !== o && n) { el.css("color", n); } });
            }
        };
    });
    app.directive("flag", function () {
        return {
            restrict: "E",
            scope: { code: "@", size: "@" },
            template: '<span class="flag" ng-style="{width: sz()+\'px\', height: sz()+\'px\'}">' +
                '<img ng-src="{{ code ? (\'https://flagcdn.com/w160/\'+code+\'.png\') : \'\' }}" alt=""></span>',
            link: function (s) { s.sz = function () { return s.size ? +s.size : 44; }; }
        };
    });
    // <input wc-file="handler($file)"> — gọi handler khi chọn file (Angular không bind type=file)
    app.directive("wcFile", ["$parse", function ($parse) {
        return {
            restrict: "A",
            link: function (s, el, attrs) {
                var fn = $parse(attrs.wcFile);
                el.on("change", function (e) {
                    var file = e.target.files && e.target.files[0];
                    s.$apply(function () { fn(s, { $file: file }); });
                });
            }
        };
    }]);
    /*=============== [Worldcup - Directive ic/flag/wcFile] - END ===============*/

    /*=============== [Worldcup - Filters] - START ===============*/
    app.filter("kickoff", function () {
        return function (ts) { if (!ts) { return ""; } return new Date(ts).toLocaleTimeString("vi-VN", { hour: "2-digit", minute: "2-digit", hour12: false }); };
    });
    app.filter("dateVi", function () {
        return function (ts) { if (!ts) { return ""; } return new Date(ts).toLocaleDateString("vi-VN", { weekday: "short", day: "2-digit", month: "2-digit" }); };
    });
    app.filter("mmss", function () {
        return function (ms) { var t = split(ms); return pad(t.m + (t.h + t.d * 24) * 60) + ":" + pad(t.s); };
    });
    // HH:MM:SS — đếm ngược còn lại trong cửa sổ bình chọn (giờ:phút:giây)
    app.filter("hms", function () {
        return function (ms) { var t = split(ms); return pad(t.h + t.d * 24) + ":" + pad(t.m) + ":" + pad(t.s); };
    });
    /*=============== [Worldcup - Filters] - END ===============*/

    /*=============== [Worldcup - MainCtrl] - START ===============*/
    app.controller("MainCtrl", ["$interval", "$timeout", "$http", function ($interval, $timeout, $http) {
        var vm = this;

        /* ---- HTTP helpers ---- */
        // X-Requested-With để core->isAjax()=true → server CHỈ trả JSON (không bọc layout)
        var AJAX_HDR = { "X-Requested-With": "XMLHttpRequest" };
        function apiGet(name) { return $http.get(API + name, { headers: AJAX_HDR }); }
        function apiPost(name, data) {
            var body = $j ? $j.param(data || {}) : "";
            return $http.post(API + name, body, { headers: angular.extend({ "Content-Type": "application/x-www-form-urlencoded" }, AJAX_HDR) });
        }

        /* ---- trạng thái phiên (từ WC_BOOT, refine bằng api_state) ---- */
        vm.isLoggedIn = !!BOOT.isLoggedIn;
        vm.isVerified = !!BOOT.isVerified;
        vm.isDev = !!BOOT.isDev;
        vm.loginUrl = BOOT.loginUrl || "";
        vm.loading = true;
        vm.loadError = false;

        /* ---- dữ liệu ---- */
        vm.matches = [];
        vm.teams = [];
        vm.me = { pts: 0, rank: 0, correct: 0, played: 0, streak: 0 };
        vm.picks = {};
        vm.config = BOOT.config || { openMin: 60, lockMin: 30, basePoints: 1, showCommunity: 1, stagePoints: { group: 1, r32: 2, r16: 4, qf: 8, sf: 16, third: 20, final: 30 } };

        /* ---- đồng hồ server (now = serverTime + thời gian trôi từ lúc tải) ---- */
        var serverBase = BOOT.serverTime || Date.now();
        var localBase = Date.now();
        vm.now = serverBase;

        /* ---- UI state ---- */
        vm.tab = "matches";
        vm.adminOpen = false;
        vm.adminTab = "manual";
        vm.panelOpen = false;
        vm.toast = null;
        vm.lbTab = "Tổng";
        vm.added = [];
        vm.imp = { imported: false, fileName: "" };
        vm.importRows = [];
        vm.form = { id: 0, a: null, b: null, date: null, time: null, round: "Vòng bảng", stage: "group", openMin: 60, lockMin: 30 };
        vm.resultForm = {};

        // tw: cấu hình HIỂN THỊ phía client (điểm thực do server tính theo vm.config)
        vm.tw = {
            cardVariant: "classic",
            showCommunity: (vm.config.showCommunity != 0),
            lockMin: vm.config.lockMin,
            basePoints: vm.config.basePoints,
            underdogBonus: true,
            underdogPts: vm.config.underdogPts,
            streakBonus: true
        };

        vm.cd = [{ v: "00", l: "NGÀY" }, { v: "00", l: "GIỜ" }, { v: "00", l: "PHÚT" }, { v: "00", l: "GIÂY" }];
        vm.hasNextMatch = false; // có trận kế tiếp chưa bóng lăn để đếm ngược không

        vm.nav = [
            { k: "matches", l: "Trận đấu", ic: "swords" },
            { k: "results", l: "Kết quả", ic: "flag" },
            { k: "leaderboard", l: "Xếp hạng", ic: "bar-chart-3" },
            { k: "history", l: "Lịch sử", ic: "history" }
        ];
        vm.outcomeLabel = { H: "Đội nhà thắng", D: "Hòa", A: "Đội khách thắng" };
        /*=============== [Worldcup - Danh mục vòng đấu (stage)] - START ===============*/
        vm.stages = [
            { k: "group", l: "Vòng bảng" },
            { k: "r32", l: "Vòng 32 đội (1/16)" },
            { k: "r16", l: "Vòng 16 đội (1/8)" },
            { k: "qf", l: "Tứ kết" },
            { k: "sf", l: "Bán kết" },
            { k: "third", l: "Tranh hạng 3" },
            { k: "final", l: "Chung kết" }
        ];
        vm.stageLabel = function (k) {
            for (var i = 0; i < vm.stages.length; i++) { if (vm.stages[i].k === k) { return vm.stages[i].l; } }
            return k || "Vòng bảng";
        };
        /*=============== [Worldcup - Danh mục vòng đấu (stage)] - END ===============*/

        /* ---- nạp state từ server ---- */
        function applyState(d) {
            vm.matches = d.matches || [];
            vm.teams = d.teams || [];
            vm.config = d.config || vm.config;
            if (vm.loadCfgForm) { vm.loadCfgForm(); }
            vm.isLoggedIn = !!d.isLoggedIn;
            vm.isVerified = !!d.isVerified;
            vm.isDev = !!d.isDev;
            vm.me = d.me || { pts: 0, rank: 0, correct: 0, played: 0, streak: 0 };
            // đồng bộ lại đồng hồ server
            if (d.serverTime) { serverBase = d.serverTime; localBase = Date.now(); }
            // map picks
            vm.picks = {};
            for (var i = 0; i < vm.matches.length; i++) {
                var m = vm.matches[i];
                if (m.myPick) { vm.picks[m.id] = m.myPick; }
            }
            tick();
        }
        vm.loadState = function () {
            vm.loading = true; vm.loadError = false;
            return apiGet("state").then(function (res) {
                vm.loading = false;
                if (res.data && res.data.ok) { applyState(res.data); }
                else { vm.loadError = true; }
            }, function () { vm.loading = false; vm.loadError = true; vm.showToast("Không tải được dữ liệu trận đấu."); });
        };

        /* ---- BXH (lazy) ---- */
        vm.lbLoaded = false;
        vm.lbRest = [];
        vm.podiumOrder = [];
        vm.medal = ["#caa14a", "#b8b8c0", "#c08a52"];
        vm.podiumH = [108, 78, 62]; // chiều cao bệ theo HẠNG (rank-1): #1 cao nhất → #2 → #3
        vm.myRank = 0;
        /*=============== [Worldcup - Placeholder BXH (hiển thị mờ khi trống)] - START ===============*/
        // Dữ liệu MINH HOẠ (không phải thật) — render mờ phía sau thông báo khi BXH chưa có ai ghi điểm
        vm.lbGhost = {
            podium: [
                { rank: 2, name: '', pts: 0, avt:'' },
                { rank: 1, name: '', pts: 0, avt:'' },
                { rank: 3, name: '', pts: 0, avt:'' }
            ],
            rest: [
                { rank: 4, name: '', correct: 0, streak: 0, pts: 0, avt:'' },
                { rank: 5, name: '', correct: 0, streak: 0, pts: 0, avt:'' },
                { rank: 6, name: '', correct: 0, streak: 0, pts: 0, avt:'' },
                { rank: 7, name: '', correct: 0, streak: 0, pts: 0, avt:'' },
                { rank: 8, name: '', correct: 0, streak: 0, pts: 0, avt:'' },
                { rank: 9, name: '', correct: 0, streak: 0, pts: 0, avt:'' },
                { rank: 10, name: '', correct: 0, streak: 0, pts: 0, avt:'' }
            ]
        };
        /*=============== [Worldcup - Placeholder BXH] - END ===============*/
        vm.loadLeaderboard = function () {
            return apiGet("leaderboard").then(function (res) {
                vm.lbLoaded = true;
                if (res.data && res.data.ok) {
                    var list = res.data.list || [];
                    vm.podiumOrder = [list[1], list[0], list[2]].filter(Boolean);
					console.log(vm.podiumOrder);
                    vm.lbRest = list.slice(3);
                    vm.myRank = res.data.myRank || 0;
                }
            });
        };
        vm.setTab = function (k) {
            vm.tab = k;
            if (k === "leaderboard" && !vm.lbLoaded) { vm.loadLeaderboard(); }
        };

        /* ---- đồng hồ + đếm ngược ---- */
        function tick() {
            vm.now = serverBase + (Date.now() - localBase);
            var nk = vm.nextKickoff();                 // trận kế tiếp CHƯA đá (slot > now), bỏ qua trận đã qua giờ
            vm.hasNextMatch = nk !== null;
            var t = split((nk !== null ? nk : vm.now) - vm.now);
            vm.cd[0].v = pad(t.d); vm.cd[1].v = pad(t.h); vm.cd[2].v = pad(t.m); vm.cd[3].v = pad(t.s);
        }

        /* ---- slot logic ---- */
        vm.isFinished = function (m) { return m.status === 1 || m.result != null; };
        // Trận loại trực tiếp (knockout): chỉ chọn đội thắng (không có cửa Hòa)
        vm.isKnockout = function (m) { return !!m && !!m.stage && m.stage !== 'group'; };
        vm.lockAt = function (m) { return m.slot - (m.lockMin || vm.config.lockMin) * MINUTE; };          // mốc ĐÓNG bình chọn
        vm.openAt = function (m) { return m.slot - (m.openMin || vm.config.openMin || 60) * MINUTE; };     // mốc MỞ bình chọn
        vm.toLock = function (m) { return vm.lockAt(m) - vm.now; };   // còn bao lâu thì ĐÓNG
        vm.toOpen = function (m) { return vm.openAt(m) - vm.now; };   // còn bao lâu thì MỞ
        vm.notYetOpen = function (m) { return !vm.isFinished(m) && vm.now < vm.openAt(m); };               // chưa tới giờ mở
        vm.isOpen = function (m) { return !vm.isFinished(m) && vm.now >= vm.openAt(m) && vm.now < vm.lockAt(m); };
        vm.isClosed = function (m) { return !vm.isFinished(m) && vm.now >= vm.lockAt(m); };                // đã đóng, chưa có KQ
        vm.isLocked = function (m) { return !vm.isOpen(m); };   // KHÔNG cho bình chọn (chưa mở / đã đóng / đã có KQ)
        vm.nextSlot = function () {
            var up = vm.matches.filter(function (m) { return !vm.isFinished(m); }).map(function (m) { return m.slot; });
            if (up.length) { return Math.min.apply(null, up); }
            var all = vm.matches.map(function (m) { return m.slot; });
            return all.length ? Math.max.apply(null, all) : vm.now;
        };
        // Trận kế tiếp CHƯA bóng lăn (slot > now) để đếm ngược; null nếu không còn trận nào sắp đá
        vm.nextKickoff = function () {
            var now = vm.now, best = null;
            for (var i = 0; i < vm.matches.length; i++) {
                var m = vm.matches[i];
                if (!vm.isFinished(m) && m.slot > now && (best === null || m.slot < best)) { best = m.slot; }
            }
            return best;
        };
        vm.slotMatches = function () {
            var ns = vm.nextSlot();
            return vm.matches.filter(function (m) { return !vm.isFinished(m) && m.slot === ns; });
        };
        vm.slotLocked = function () { return (vm.nextSlot() - vm.tw.lockMin * MINUTE) - vm.now <= 0; };
        // Danh sách MỌI trận sắp tới (chưa có KQ), sắp theo giờ — gồm cả trận chưa mở bình chọn
        vm.upcomingMatches = function () {
            return vm.matches.filter(function (m) { return !vm.isFinished(m); })
                .sort(function (a, b) { return a.slot - b.slot; });
        };
        /*=============== [Worldcup - Nhóm trận theo ngày + nhãn giờ] - START ===============*/
        function startOfDay(ts) { var d = new Date(ts); d.setHours(0, 0, 0, 0); return d.getTime(); }
        // Khoảng cách ngày lịch giữa ts và "bây giờ" (giờ server): 0=hôm nay, 1=ngày mai...
        vm.dayOffset = function (ts) { return Math.round((startOfDay(ts) - startOfDay(vm.now)) / 86400000); };
        // Nhãn giờ kèm ngày: "HH:MM Hôm nay" / "HH:MM Ngày mai" / "HH:MM DD/MM"
        vm.timeTag = function (ts) {
            if (!ts) { return ""; }
            var d = new Date(ts), off = vm.dayOffset(ts);
            var hhmm = pad(d.getHours()) + ":" + pad(d.getMinutes());
            var day = off === 0 ? "Hôm nay" : (off === 1 ? "Ngày mai" : (pad(d.getDate()) + "/" + pad(d.getMonth() + 1)));
            return {
				'time':hhmm,
				'day':day
			};
        };
        // Tiêu đề nhóm theo ngày
        vm.daySection = function (ts) {
            var off = vm.dayOffset(ts);
            if (off === 0) { return "Lịch thi đấu hôm nay"; }
            if (off === 1) { return "Lịch thi đấu ngày mai"; }
            var d = new Date(ts);
            return "Lịch thi đấu ngày " + pad(d.getDate()) + "/" + pad(d.getMonth() + 1) + "/" + d.getFullYear();
        };
        // Nhóm các trận sắp tới theo ngày lịch.
        // MEMO HÓA: trả về CÙNG mảng/đối tượng cũ khi dữ liệu chưa đổi → $watchCollection của
        // ng-repeat ổn định, tránh [$rootScope:infdig] (đồng hồ chạy mỗi giây cũng không tính lại).
        var _grpCache = { sig: null, val: [] };
        vm.matchGroups = function () {
            var list = vm.upcomingMatches();
            // chữ ký: mốc ngày hiện tại (đổi lúc nửa đêm để đổi nhãn) + (id:slot) từng trận
            var sig = "d" + startOfDay(vm.now);
            for (var i = 0; i < list.length; i++) { sig += "|" + list[i].id + ":" + list[i].slot; }
            if (_grpCache.sig === sig) { return _grpCache.val; }
            var groups = [], lastKey = null, cur = null;
            for (var j = 0; j < list.length; j++) {
                var m = list[j], d = new Date(m.slot);
                var key = d.getFullYear() + "-" + pad(d.getMonth() + 1) + "-" + pad(d.getDate());
                if (key !== lastKey) { cur = { key: key, label: vm.daySection(m.slot), matches: [] }; groups.push(cur); lastKey = key; }
                cur.matches.push(m);
            }
            _grpCache = { sig: sig, val: groups };
            return groups;
        };
        /*=============== [Worldcup - Nhóm trận theo ngày + nhãn giờ] - END ===============*/

        /* ---- votes ---- */
        vm.pct = function (votes, key) { var total = votes.H + votes.D + votes.A || 1; return Math.round((votes[key] / total) * 100); };
        vm.totalVotes = function (votes) { return votes.H + votes.D + votes.A; };
        vm.voteLabel = function (m, key) { return key === "H" ? m.home.short : key === "A" ? m.away.short : "Hòa"; };
        vm.showCheck = function (m, key) { return vm.isFinished(m) ? (m.result === key) : (vm.picks[m.id] === key); };
        vm.voteClass = function (m, key) {
            var fin = vm.isFinished(m);
            var sel = fin ? (m.myPick === key) : (vm.picks[m.id] === key);
            return { on: sel && !fin, locked: vm.isLocked(m), res: fin, correct: fin && m.result === key, wrongsel: fin && sel && m.result !== key };
        };
        vm.pick = function (m, key) {
            if (vm.isFinished(m)) { return; }
            if (vm.isKnockout(m) && key === 'D') { vm.showToast("Trận loại trực tiếp chỉ chọn đội thắng."); return; }
            if (vm.notYetOpen(m)) { vm.showToast("Chưa mở bình chọn — mở " + (m.openMin || vm.config.openMin || 60) + "' trước trận."); return; }
            if (vm.isClosed(m)) { vm.showToast("Đã đóng bình chọn cho trận này."); return; }
            if (!vm.isLoggedIn) {
                vm.showToast("Vui lòng đăng nhập để dự đoán.");
                if (vm.loginUrl) { $timeout(function () { window.location.href = vm.loginUrl; }, 900); }
                return;
            }
            if (!vm.isVerified) { vm.showToast("Tài khoản chưa xác thực — chưa thể bình chọn."); return; }
            if (vm.picks[m.id] === key) { return; } // đã chọn cửa này
            apiPost("pick", { match_id: m.id, pick: key }).then(function (res) {
                var d = res.data || {};
                if (d.ok) {
                    if (d.votes) { m.votes = d.votes; }
                    vm.picks[m.id] = d.myPick; m.myPick = d.myPick;
                    vm.showToast("Đã ghi nhận dự đoán: " + vm.voteLabel(m, d.myPick));
                } else {
                    vm.showToast(d.message || "Không thể dự đoán.");
                    if (d.error === "login_required" && vm.loginUrl) { $timeout(function () { window.location.href = vm.loginUrl; }, 900); }
                }
            }, function () { vm.showToast("Lỗi kết nối, thử lại sau."); });
        };

        /* ---- scoring (hiển thị; điểm thực do server đã chốt) ---- */
        // Điểm cộng khi đúng theo vòng (stage) — đồng bộ với server
        vm.stagePts = function (stage) {
            var sp = (vm.config && vm.config.stagePoints) || {};
            return (sp[stage] != null) ? sp[stage] : ((vm.config && vm.config.basePoints) || 1);
        };
        vm.scoreFor = function (m) {
            if (!vm.isFinished(m) || !m.myPick) { return { pts: 0, correct: false, bonus: 0 }; }
            var correct = m.myCorrect === 1;
            var pts = m.myPoints || 0;
            var base = vm.stagePts(m.stage);
            var bonus = (correct && pts > base) ? (pts - base) : 0;
            return { pts: pts, correct: correct, bonus: bonus };
        };

        /* ---- derived collections ---- */
        vm.lastFinishedSlot = function () {
            var fs = vm.matches.filter(function (m) { return vm.isFinished(m); }).map(function (m) { return m.slot; });
            return fs.length ? Math.max.apply(null, fs) : 0;
        };
        vm.finishedMatches = function () {
            var ls = vm.lastFinishedSlot();
            return vm.matches.filter(function (m) { return vm.isFinished(m) && m.slot === ls; });
        };
        vm.allFinished = function () {
            return vm.matches.filter(function (m) { return vm.isFinished(m); })
                .sort(function (a, b) { return b.slot - a.slot; });
        };
        vm.roundEarned = function () { return vm.finishedMatches().reduce(function (s, m) { return s + vm.scoreFor(m).pts; }, 0); };
        vm.roundCorrect = function () { return vm.finishedMatches().filter(function (m) { return m.myPick && m.myCorrect === 1; }).length; };
        vm.histPlayed = function () { return vm.allFinished().filter(function (m) { return m.myPick; }).length; };
        vm.histCorrect = function () { return vm.allFinished().filter(function (m) { return m.myPick && m.myCorrect === 1; }).length; };
        vm.histAccuracy = function () { var p = vm.histPlayed(); return p ? Math.round(vm.histCorrect() * 100 / p) : 0; };
        vm.initial = function (name) {
            if (!name) { return "?"; }
            var p = name.replace(/\(.*?\)/g, "").trim().split(/\s+/).filter(Boolean);
            return (p.length ? p[p.length - 1][0] : "?").toUpperCase();
        };

        /* ---- toast ---- */
        var toastTimer;
        vm.showToast = function (msg) {
            vm.toast = msg;
            if (toastTimer) { $timeout.cancel(toastTimer); }
            toastTimer = $timeout(function () { vm.toast = null; }, 2800);
        };

        /* ---- admin: chung ---- */
        vm.team = function (id) {
            for (var i = 0; i < vm.teams.length; i++) { if (String(vm.teams[i].id) === String(id)) { return vm.teams[i]; } }
            return null;
        };
        /*=============== [Worldcup - teamsExcept: loại trừ đội đã chọn] - START ===============*/
        // Danh sách đội cho dropdown, ẩn đội đã chọn ở ô còn lại (excludeId có thể null)
        vm.teamsExcept = function (excludeId) {
            if (excludeId === null || excludeId === undefined || excludeId === "") { return vm.teams; }
            return vm.teams.filter(function (t) { return String(t.id) !== String(excludeId); });
        };
        /*=============== [Worldcup - teamsExcept: loại trừ đội đã chọn] - END ===============*/
        function kickoffSeconds(d, t) {
            if (!(d instanceof Date) || !(t instanceof Date)) { return 0; }
            var dt = new Date(d.getFullYear(), d.getMonth(), d.getDate(), t.getHours(), t.getMinutes(), 0, 0);
            return Math.floor(dt.getTime() / 1000);
        }

        /* ---- admin: nhập 1 trận ---- */
        vm.formValid = function () { return vm.form.a && vm.form.b && vm.form.a !== vm.form.b && vm.form.date && vm.form.time; };
        vm.resetForm = function () {
            vm.form = { id: 0, a: null, b: null, date: null, time: null, round: "Vòng bảng", stage: "group",
                openMin: vm.config.openMin || 60, lockMin: vm.config.lockMin || 30 };
        };
        // Nạp 1 trận vào form để SỬA (giờ/đội/vòng/thời lượng mở-đóng)
        vm.editMatch = function (m) {
            var dt = new Date(m.slot);
            vm.form = {
                id: m.id, a: m.homeId, b: m.awayId,
                date: new Date(dt.getFullYear(), dt.getMonth(), dt.getDate()),
                time: dt,
                round: m.round || "Vòng bảng",
                stage: m.stage || "group",
                openMin: m.openMin || vm.config.openMin || 60,
                lockMin: m.lockMin || vm.config.lockMin || 30
            };
            vm.adminTab = "manual";
        };
        vm.addMatch = function () {
            if (!vm.formValid()) { return; }
            var ta = vm.team(vm.form.a), tb = vm.team(vm.form.b);
            var kickoff = kickoffSeconds(vm.form.date, vm.form.time);
            if (kickoff <= 0) { vm.showToast("Ngày/giờ không hợp lệ."); return; }
            var editing = vm.form.id > 0;
            var roundLabel = vm.stageLabel(vm.form.stage || "group"); // round = nhãn vòng (đã bỏ ô nhập riêng)
            apiPost("admin_save_match", {
                match_id: vm.form.id || 0,
                home_team_id: vm.form.a, away_team_id: vm.form.b, kickoff: kickoff,
                round: roundLabel, stage: vm.form.stage || "group",
                open_minutes: vm.form.openMin || 60, lock_minutes: vm.form.lockMin || 30
            }).then(function (res) {
                if (res.data && res.data.ok) {
                    if (!editing) { vm.added.unshift({ home: ta, away: tb, date: vm.form.date, time: vm.form.time, round: roundLabel }); }
                    vm.showToast((editing ? "Đã cập nhật trận: " : "Đã thêm trận: ") + ta.short + " vs " + tb.short);
                    vm.resetForm();
                    vm.loadState();
                } else { vm.showToast((res.data && res.data.message) || "Không lưu được trận."); }
            }, function () { vm.showToast("Lỗi kết nối."); });
        };

        /* ---- admin: import CSV thật ----
           Định dạng cột: Đội nhà , Đội khách , Ngày(dd/mm/yyyy) , Giờ(HH:MM) , Vòng */
        function findTeamId(nameOrShort) {
            if (!nameOrShort) { return 0; }
            var k = nameOrShort.trim().toLowerCase();
            for (var i = 0; i < vm.teams.length; i++) {
                var t = vm.teams[i];
                if (t.name.toLowerCase() === k || t.short.toLowerCase() === k || (t.code || "").toLowerCase() === k) { return t.id; }
            }
            return 0;
        }
        function parseCsvLine(line) {
            // hỗ trợ phân tách bằng dấu phẩy hoặc tab; bỏ quote
            var sep = line.indexOf("\t") >= 0 ? "\t" : ",";
            return line.split(sep).map(function (c) { return c.replace(/^"(.*)"$/, "$1").trim(); });
        }
        function buildImportRow(cols) {
            var a = cols[0] || "", b = cols[1] || "", date = cols[2] || "", time = cols[3] || "", round = cols[4] || "";
            var ha = findTeamId(a), hb = findTeamId(b);
            var kickoff = 0, okDate = false;
            var dm = date.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
            var tm = time.match(/^(\d{1,2}):(\d{2})$/);
            if (dm && tm) {
                var dt = new Date(+dm[3], (+dm[2]) - 1, +dm[1], +tm[1], +tm[2], 0, 0);
                if (!isNaN(dt.getTime())) { kickoff = Math.floor(dt.getTime() / 1000); okDate = true; }
            }
            var ok = ha > 0 && hb > 0 && ha !== hb && okDate;
            return { a: a, b: b, date: date, time: time, round: round, ok: ok, home_team_id: ha, away_team_id: hb, kickoff: kickoff };
        }
        vm.triggerCsv = function () { var el = document.getElementById("wcCsvInput"); if (el) { el.click(); } };
        vm.onCsvFile = function ($file) {
            if (!$file) { return; }
            vm.imp.fileName = $file.name;
            var reader = new FileReader();
            reader.onload = function (e) {
                var text = String(e.target.result || "");
                var lines = text.split(/\r?\n/).filter(function (l) { return l.trim() !== ""; });
                // bỏ dòng tiêu đề nếu có chữ "Đội"
                if (lines.length && /đội|doi|home|nhà/i.test(lines[0])) { lines.shift(); }
                var rows = lines.map(function (l) { return buildImportRow(parseCsvLine(l)); });
                $timeout(function () { vm.importRows = rows; vm.imp.imported = true; });
            };
            reader.readAsText($file, "UTF-8");
        };
        vm.importOkCount = function () { return vm.importRows.filter(function (r) { return r.ok; }).length; };
        vm.resetImport = function () { vm.imp.imported = false; vm.imp.fileName = ""; vm.importRows = []; };
        /*=============== [Worldcup - Tải file CSV mẫu] - START ===============*/
        vm.downloadSample = function () {
            var rows = [
                ["Đội nhà", "Đội khách", "Ngày", "Giờ", "Vòng"],
                ["Mexico", "Croatia", "11/06/2026", "02:00", "Vòng bảng · Bảng A"],
                ["Canada", "Bồ Đào Nha", "11/06/2026", "05:00", "Vòng bảng · Bảng B"],
                ["Mỹ", "Hà Lan", "12/06/2026", "02:00", "Vòng bảng · Bảng C"]
            ];
            var csv = rows.map(function (r) {
                return r.map(function (c) { return /[",\n]/.test(c) ? '"' + c.replace(/"/g, '""') + '"' : c; }).join(",");
            }).join("\r\n");
            var blob = new Blob(["﻿" + csv], { type: "text/csv;charset=utf-8;" });
            var url = (window.URL || window.webkitURL).createObjectURL(blob);
            var a = document.createElement("a");
            a.href = url; a.download = "worldcup_lich_mau.csv";
            document.body.appendChild(a); a.click(); document.body.removeChild(a);
            $timeout(function () { (window.URL || window.webkitURL).revokeObjectURL(url); }, 1500);
        };
        /*=============== [Worldcup - Tải file CSV mẫu] - END ===============*/
        vm.confirmImport = function () {
            var valid = vm.importRows.filter(function (r) { return r.ok; }).map(function (r) {
                return { home_team_id: r.home_team_id, away_team_id: r.away_team_id, kickoff: r.kickoff, round: r.round };
            });
            if (!valid.length) { vm.showToast("Không có dòng hợp lệ để nhập."); return; }
            apiPost("admin_import", { rows: angular.toJson(valid) }).then(function (res) {
                if (res.data && res.data.ok) {
                    vm.showToast("Đã nhập " + res.data.inserted + " trận.");
                    vm.resetImport();
                    vm.loadState();
                } else { vm.showToast((res.data && res.data.message) || "Import thất bại."); }
            }, function () { vm.showToast("Lỗi kết nối."); });
        };

        /* ---- admin: chốt kết quả ---- */
        vm.adminMatches = function () {
            return vm.matches.slice().sort(function (a, b) { return a.slot - b.slot; });
        };
        // Cần nhập luân lưu? = trận knockout + tỉ số (sau hiệp phụ) hòa
        vm.needPen = function (m) {
            if (!vm.isKnockout(m)) { return false; }
            var rf = vm.resultForm[m.id] || {};
            var ok = rf.h !== undefined && rf.h !== null && rf.h !== "" && rf.a !== undefined && rf.a !== null && rf.a !== "";
            return ok && Number(rf.h) === Number(rf.a);
        };
        vm.saveResult = function (m) {
            var rf = vm.resultForm[m.id] || {};
            if (rf.h === undefined || rf.h === null || rf.h === "" || rf.a === undefined || rf.a === null || rf.a === "") {
                vm.showToast("Nhập đủ tỉ số 2 đội."); return;
            }
            var data = { match_id: m.id, home_score: rf.h, away_score: rf.a };
            if (vm.needPen(m)) {
                if (rf.ph === undefined || rf.ph === null || rf.ph === "" || rf.pa === undefined || rf.pa === null || rf.pa === "") {
                    vm.showToast("Hòa sau hiệp phụ — nhập tỉ số luân lưu."); return;
                }
                if (Number(rf.ph) === Number(rf.pa)) { vm.showToast("Luân lưu phải có đội thắng."); return; }
                data.pen_home = rf.ph; data.pen_away = rf.pa;
            }
            apiPost("admin_set_result", data).then(function (res) {
                if (res.data && res.data.ok) {
                    vm.showToast("Đã chốt kết quả & cộng điểm (" + res.data.settled + " lượt).");
                    vm.lbLoaded = false;
                    vm.loadState();
                } else { vm.showToast((res.data && res.data.message) || "Không lưu được kết quả."); }
            }, function () { vm.showToast("Lỗi kết nối."); });
        };

        /*=============== [Worldcup - Admin: chỉnh điểm theo vòng] - START ===============*/
        vm.cfgForm = {};
        vm.loadCfgForm = function () { vm.cfgForm = angular.copy((vm.config && vm.config.stagePoints) || {}); };
        vm.saveConfig = function () {
            apiPost("admin_save_config", { stage_points: angular.toJson(vm.cfgForm) }).then(function (res) {
                if (res.data && res.data.ok) {
                    if (res.data.stagePoints) { vm.config.stagePoints = res.data.stagePoints; }
                    vm.showToast("Đã lưu điểm theo vòng.");
                    vm.loadState();
                } else { vm.showToast((res.data && res.data.message) || "Không lưu được điểm."); }
            }, function () { vm.showToast("Lỗi kết nối."); });
        };
        /*=============== [Worldcup - Admin: chỉnh điểm theo vòng] - END ===============*/

        /* ---- init ---- */
        vm.loadState();
        $interval(tick, 1000);
    }]);
    /*=============== [Worldcup - MainCtrl] - END ===============*/
})();
