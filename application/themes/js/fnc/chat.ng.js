/* =========================================================================
   chat.ng.js — AngularJS 1.8 chat nội bộ (THAY jquery.chat.js).
   Module fhChat · ChatCtrl as c. Backend qua biến __MOD (đổi 1 chỗ để chuyển module; mặc định "chat2")
   (channels, history, send, react, group_..., staff). Realtime: $Core.socket.
   Bootstrap: window.CHAT_BOOT (profile_id, name, ajax). Template: blocks/chat/index.tpl ({literal}).
   Đã port ĐẦY ĐỦ từ jquery.chat.js: widget/tab · danh sách hội thoại · thread · gửi tin
   · thả cảm xúc · realtime · ảnh/album + lightbox · quote/reply · tạo+quản trị nhóm · check-in (camera+GPS).
   ========================================================================= */
(function() {
    "use strict";
    var $j = window.jQuery;
    var FORM = {
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "X-Requested-With": "XMLHttpRequest"
        }
    };
    var EMOJI = {
        love: "❤️",
        like: "👍",
        haha: "😆",
        wow: "😮",
        sad: "😢",
        angry: "😡"
    };
    var REACTS = ["love", "like", "haha", "wow", "sad", "angry"];
    var AV_TINTS = [
        ["#eef0ff", "#4f46e5"],
        ["#fdeee1", "#c2410c"],
        ["#e3f6f6", "#0f766e"],
        ["#fdeaf1", "#be185d"],
        ["#e8f1fd", "#1d4ed8"],
        ["#eef6e3", "#4d7c0f"],
        ["#fbeae7", "#b91c1c"],
        ["#eceaf9", "#5b21b6"]
    ];
    /* Định nghĩa module + bootstrap chạy ở DOMContentLoaded → angular nạp Ở CHỖ KHÁC cũng được
       (miễn là <script> angular đồng bộ chạy TRƯỚC khi DOM sẵn sàng). KHÔNG bail sớm lúc load. */
    function boot() {
        if (typeof angular === "undefined") {
            return;
        } // angular chưa có → bỏ qua êm (không vỡ trang)
        if (boot._done) {
            return;
        }
        boot._done = true;
        var app = angular.module("fhChat", []);
        app.controller("ChatCtrl", ["$scope", "$http", "$timeout", "$interval", function($scope, $http, $timeout, $interval) {
            var c = this;
            // Đọc bootstrap TRONG controller (CHAT_BOOT do block set ở footer — sau khi chat.ng.js load).
            var BOOT = window.CHAT_BOOT || {};
            var ME = parseInt(BOOT.profile_id, 10) || 0;
            var __MOD = "chat"; // module đang chạy: bản test = "chat2"; khi deploy public đổi 1 chỗ này thành "chat"
            var CAN_STATS = !!window.CHAT_CAN_STATS; // cờ quyền xem "Thống kê hôm nay" (server set khi DIRECTOR/BO/BUSINESS_AREA/SALE_DIRECTOR)
            var BASE = (BOOT.ajax || "") + "/index.php?mod=" + __MOD + "&act=";
            c.open = false;
            c.tab = "checkin"; // 'chat' | 'checkin'
            c.view = "list"; // 'list' | 'thread'
            c.chans = [];
            c.convQ = ""; // lọc danh sách hội thoại theo tên
            c.srch = {
                open: false,
                q: "",
                results: null,
                loading: false
            }; // tìm tin nhắn trong nhóm
            c.curCh = 0;
            c.msgs = [];
            c.draft = "";
            c.me = ME;
            c.unreadTotal = 0;
            c.loading = false;
            c.EMOJI = EMOJI;
            c.REACTS = REACTS;
            c.replyTo = null; // tin đang trả lời {message_id,name,preview}
            c.pinnedList = []; // các tin đang GHIM của kênh (mảng snippet, mới ghim trước)
            c.pinOpen = false; // mở/thu danh sách ghim trong banner
            c.seenList = []; // ai đã xem tin mới nhất của kênh {profile_id,name,avatar} (chỉ nhóm)
            c.mentions = []; // @mention đã chọn trong tin đang soạn [{name,pid}]
            c.atOpen = false;
            c.atQuery = "";
            c.atMembers = null;
            c.atActive = 0; // picker @ (atActive = dòng đang chọn bằng phím)
            c.typingName = ""; // ai đang soạn trong kênh hiện tại (realtime)
            c.imgPosting = false; // khoá gửi khi đang upload ảnh
            c.lb = {
                open: false,
                srcs: [],
                idx: 0,
                zoom: false
            }; // lightbox xem ảnh
            c.isDesktop = (typeof deviceType !== 'undefined' && deviceType === 'computer'); // PC (Mobile_Detect) → ẩn nút chụp, hiện text dùng Mobile (cần camera+GPS)
            c.notifyHint = true; // hiện banner nhắc bật thông báo (đến khi cấp quyền hoặc user bấm ẩn)
            c.cg = {
                open: false,
                mode: "create",
                name: "",
                q: "",
                dept: null,
                img: null,
                picked: {},
                addCid: 0,
                staff: [],
                submitting: false
            }; // modal tạo nhóm / thêm TV (dept null = khớp option "Tất cả phòng")
            c.mng = {
                open: false,
                cid: 0,
                info: null,
                loading: false
            }; // panel quản trị nhóm
            c.ck = {
                loading: false,
                my: null,
                feed: null,
                state: "idle",
                geoMsg: "",
                geoGood: false,
                geoReady: false,
                addrLoading: false,
                geoErr: "",
                geoCoarse: 0,
                io: "in",
                subErr: "",
                posting: false,
                photo: "",
                note: "",
                address: "",
                tags: [],
                atOffice: true,
                geoPerm: "",
                journey: null,
                jnDateObj: null,
                gpsLat: 0,
                gpsLng: 0,
                gpsAcc: 0
            }; // geoCoarse>0 = sai số 50–200m, cho nút "Vẫn check-in". tab Check-in: idle → review → gửi · journey = Hành trình
            /* Tag hoạt động (CHỌN NHIỀU) — nạp từ backend (default_setting _CHECKIN_TAGS), KHÔNG hardcode. key = setting_id */
            c.CKTAGS = {}; // map key → {key,label,color,icon}
            c.CKTAG_LIST = []; // mảng có thứ tự cho form ng-repeat
            var CKTAG_FALLBACK = {
                key: "",
                label: "",
                color: "#64748b",
                icon: "bx bx-purchase-tag"
            };
            c.ckCat = function(k) {
                return c.CKTAGS[k] || CKTAG_FALLBACK;
            };
            c.loadCheckinTags = function() { // nạp danh sách tag 1 lần (từ _CHECKIN_TAGS)
                if (c.CKTAG_LIST.length) {
                    return;
                }
                $http.get(url("checkin_tags")).then(function(r) {
                    var list = (r.data && !r.data.error && r.data.tags) ? r.data.tags : [];
                    var map = {},
                        i;
                    for (i = 0; i < list.length; i++) {
                        map[list[i].key] = list[i];
                    }
                    c.CKTAG_LIST = list;
                    c.CKTAGS = map;
                });
            };

            function ckHexRgba(hex, a) {
                hex = ("" + hex).replace("#", "");
                if (hex.length < 6) {
                    return "rgba(100,116,139," + a + ")";
                }
                return "rgba(" + parseInt(hex.substr(0, 2), 16) + "," + parseInt(hex.substr(2, 2), 16) + "," + parseInt(hex.substr(4, 2), 16) + "," + a + ")";
            }
            c.ckSoft = function(k) {
                return ckHexRgba(c.ckCat(k).color, 0.12);
            }; // nền nhạt cho badge/chip đã chọn
            c.ckTagOn = function(k) {
                return (c.ck.tags || []).indexOf(k) !== -1;
            }; // tag đang chọn?
            c.ckToggleTag = function(k) { // chọn nhiều: bật/tắt 1 tag
                if (!c.ck.tags) {
                    c.ck.tags = [];
                }
                var i = c.ck.tags.indexOf(k);
                if (i === -1) {
                    c.ck.tags.push(k);
                } else {
                    c.ck.tags.splice(i, 1);
                }
            };

            function url(act) {
                return BASE + act;
            }

            function post(act, data) {
                return $http.post(url(act), $j.param(data || {}), FORM);
            }
            /* socket fire ngoài digest → đẩy vào digest an toàn (tránh $apply lồng). */
            function apply(fn) {
                $timeout(fn, 0);
            }
            var TYPING_DBG = false; // DEBUG typing — tắt (đổi true để bật lại log)
            function tlog() {
                if (TYPING_DBG && window.console) {
                    console.log.apply(console, ["[typing]"].concat([].slice.call(arguments)));
                }
            }
            /* ---------- avatar fallback (glyph tint theo id) ---------- */
            c.tint = function(id) {
                return AV_TINTS[(parseInt(id, 10) || 0) % AV_TINTS.length];
            };
            c.initial = function(name) {
                return (name || "?").charAt(0).toUpperCase();
            };
            /* ---------- danh sách hội thoại ---------- */
            c.togglePin = function(ch, $event) { // ghim/bỏ ghim hội thoại (per-user) — optimistic + rollback nếu lỗi
                if ($event) {
                    $event.stopPropagation();
                }
                var np = (parseInt(ch.pinned, 10) || 0) ? 0 : 1;
                ch.pinned = np;
                post("pin_channel", {
                    channel_id: ch.channel_id,
                    pin: np
                }).then(function(r) {
                    if (!r.data || r.data.error) {
                        ch.pinned = np ? 0 : 1;
                    }
                }, function() {
                    ch.pinned = np ? 0 : 1;
                });
            };
            c.toggleMute = function(ch, $event) { // tắt/bật thông báo hội thoại (per-user) — optimistic + rollback
                if ($event) {
                    $event.stopPropagation();
                }
                var nm = (parseInt(ch.muted, 10) || 0) ? 0 : 1;
                ch.muted = nm;
                post("mute", {
                    channel_id: ch.channel_id,
                    mute: nm
                }).then(function(r) {
                    if (!r.data || r.data.error) {
                        ch.muted = nm ? 0 : 1;
                    }
                }, function() {
                    ch.muted = nm ? 0 : 1;
                });
            };

            function chanMuted(cid) {
                var ch = findChan(cid);
                return (ch && parseInt(ch.muted, 10)) ? 1 : 0;
            }

            function closeConvMenus() {
                for (var i = 0; i < c.chans.length; i++) {
                    c.chans[i]._cmenu = false;
                }
            }
            c.openConvMenu = function(ch, $event) {
                if ($event) {
                    $event.stopPropagation();
                }
                var was = ch._cmenu;
                closeConvMenus();
                ch._cmenu = !was;
            }; // toggle ⋮ hội thoại
            c.closeConvMenu = function() {
                closeConvMenus();
            };
            c.sysChans = function() {
                var q = (c.convQ || "").toLowerCase();
                return c.chans.filter(function(x) {
                    return parseInt(x.type, 10) !== 4 && (!q || (x.name || "").toLowerCase().indexOf(q) >= 0);
                });
            };
            c.grpChans = function() {
                var q = (c.convQ || "").toLowerCase();
                return c.chans.filter(function(x) {
                        return parseInt(x.type, 10) === 4 && (!q || (x.name || "").toLowerCase().indexOf(q) >= 0);
                    })
                    .sort(function(a, b) {
                        var pa = parseInt(a.pinned, 10) || 0,
                            pb = parseInt(b.pinned, 10) || 0;
                        if (pa !== pb) {
                            return pb - pa;
                        } // hội thoại ghim lên đầu
                        return (parseInt(b.last_ts, 10) || 0) - (parseInt(a.last_ts, 10) || 0);
                    });
            };
            c.curChan = function() {
                for (var i = 0; i < c.chans.length; i++) {
                    if (c.chans[i].channel_id == c.curCh) {
                        return c.chans[i];
                    }
                }
                return null;
            };
            c.curName = function() {
                var ch = c.curChan();
                return ch ? ch.name : "";
            };
            c.refreshBadge = function() {
                var n = 0;
                for (var i = 0; i < c.chans.length; i++) {
                    n += parseInt(c.chans[i].unread, 10) || 0;
                }
                c.unreadTotal = n;
            };
            c.loadChannels = function() {
                $http.get(url("channels")).then(function(r) {
                    if (r.data && !r.data.error) {
                        c.chans = r.data.channels || [];
                        c.refreshBadge();
                        subscribe();
                    }
                });
            };
            /* ---------- widget ---------- */
            c.toggle = function() {
                c.open = !c.open;
                if (c.open) {
                    ensureAudio();
                    ensureNotifyPerm();
                    c.reloadActive();
                }
            };
            c.close = function() {
                c.open = false;
                stopGeo();
            }; // đóng widget → dừng GPS (tránh watch chạy ngầm)
            c.setTab = function(t) {
                if (t === c.tab && c.view === "list") {
                    return;
                }
                stopGeo(); // rời tab (đặc biệt rời check-in) → dừng watch/timer GPS (idempotent)
                c.tab = t;
                c.view = "list";
                c.curCh = 0;
                c.msgs = [];
                c.cancelReply();
                c.pinnedList = [];
                c.pinOpen = false;
                c.seenList = [];
                setTyping("");
                if (t === "checkin") {
                    c.loadCheckin();
                } else {
                    c.loadChannels();
                } // chat → nạp list tươi; checkin → nạp feed
            };
            c.reloadActive = function() {
                if (c.tab === "checkin") {
                    c.loadCheckin();
                } else if (c.tab === "chat" && c.view === "list") {
                    c.loadChannels();
                } else if (c.view === "thread" && c.curCh > 0) {
                    c.loadHistory(c.curCh);
                }
            };
            /* ---------- mở/đóng thread ---------- */
            c.openConv = function(cid) {
                c.curCh = parseInt(cid, 10) || 0;
                if (!c.curCh) {
                    return;
                }
                c.view = "thread";
                c.draft = "";
                c.cancelReply();
                c.loadHistory(c.curCh); // reset quote → không treo reply_to_id sang kênh khác
            };
            c.backToList = function() {
                c.curCh = 0;
                c.view = "list";
                c.msgs = [];
                c.cancelReply();
                c.pinnedList = [];
                c.pinOpen = false;
                c.seenList = [];
                setTyping("");
                c.loadChannels();
            };
            c.isGroup = function() {
                var ch = c.curChan();
                return !!(ch && parseInt(ch.type, 10) === 4);
            };
            /* ---------- Tạo nhóm / thêm thành viên (modal picker dùng chung) ---------- */
            function openCg(mode, addCid) {
                c.cg = {
                    open: true,
                    mode: mode,
                    name: "",
                    q: "",
                    dept: null,
                    img: null,
                    picked: {},
                    addCid: addCid || 0,
                    staff: [],
                    submitting: false
                };
                loadStaff();
            }
            c.createGroup = function() {
                openCg("create", 0);
            };
            c.mngAddOpen = function() {
                var cid = c.mng.cid || c.curCh;
                c.mng.open = false;
                openCg("add", cid);
            }; // ẩn panel quản lý khi mở modal Thêm TV
            c.closeCreate = function() {
                var wasAdd = (c.cg.mode === "add");
                c.cg.open = false;
                if (wasAdd) {
                    c.mng.open = true;
                }
            }; // huỷ Thêm TV → quay lại panel quản lý
            function loadStaff() {
                c.cg.staff = [];
                $http.get(url("staff")).then(function(r) {
                    if (r.data && !r.data.error) {
                        c.cg.staff = r.data.list || [];
                    }
                });
            }
            c.cgDepts = function() { // danh sách phòng distinct cho dropdown — MEMOIZE theo identity staff
                if (c._deptsFor === c.cg.staff) {
                    return c._depts;
                } // trả ref ổn định → tránh infdig ng-options
                var seen = {},
                    out = [];
                for (var i = 0; i < c.cg.staff.length; i++) {
                    var d = parseInt(c.cg.staff[i].dept_id, 10) || 0;
                    if (d > 0 && !seen[d]) {
                        seen[d] = 1;
                        out.push({
                            id: d,
                            name: c.cg.staff[i].dept_name || ("Phòng " + d)
                        });
                    }
                }
                c._deptsFor = c.cg.staff;
                c._depts = out;
                return out;
            };
            c.cgFiltered = function() { // lọc theo phòng + từ khoá
                var dept = parseInt(c.cg.dept, 10) || 0,
                    q = (c.cg.q || "").toLowerCase(),
                    out = [];
                for (var i = 0; i < c.cg.staff.length; i++) {
                    var s = c.cg.staff[i];
                    if (dept > 0 && (parseInt(s.dept_id, 10) || 0) !== dept) {
                        continue;
                    }
                    if (q !== "" && (s.name || "").toLowerCase().indexOf(q) === -1) {
                        continue;
                    }
                    out.push(s);
                }
                return out;
            };
            c.cgToggle = function(s) {
                var id = parseInt(s.id, 10) || 0;
                if (!id) {
                    return;
                }
                if (c.cg.picked[id]) {
                    delete c.cg.picked[id];
                } else {
                    c.cg.picked[id] = 1;
                }
            };
            c.cgIsPicked = function(s) {
                return !!c.cg.picked[parseInt(s.id, 10) || 0];
            };
            c.cgCount = function() {
                var n = 0;
                for (var k in c.cg.picked) {
                    if (c.cg.picked.hasOwnProperty(k)) {
                        n++;
                    }
                }
                return n;
            };
            c.cgPickImage = function(input) { // nén ảnh nhóm ≤256 → data URL preview
                if (!input.files || !input.files[0]) {
                    return;
                }
                imgResize(input.files[0], 256, function(data) {
                    if (!data) {
                        return;
                    }
                    apply(function() {
                        c.cg.img = data;
                    });
                });
                input.value = "";
            };

            function pickedIds() {
                var ids = [];
                for (var k in c.cg.picked) {
                    if (c.cg.picked.hasOwnProperty(k)) {
                        ids.push(parseInt(k, 10) || 0);
                    }
                }
                return ids;
            }
            c.cgSubmit = function() {
                if (c.cg.submitting) {
                    return;
                }
                if (c.cg.mode === "add") {
                    return cgSubmitAdd();
                }
                var name = (c.cg.name || "").trim();
                if (name === "") {
                    window.alert("Nhập tên nhóm");
                    return;
                }
                c.cg.submitting = true;
                post("create_group", {
                    name: name,
                    image: c.cg.img || "",
                    members: pickedIds()
                }).then(function(r) {
                    c.cg.submitting = false;
                    if (!r.data || r.data.error) {
                        window.alert(r.data && r.data.message ? r.data.message : "Tạo nhóm thất bại");
                        return;
                    }
                    addCreatedChannel(r.data.channel);
                }, function() {
                    c.cg.submitting = false;
                    window.alert("Lỗi kết nối");
                });
            };

            function cgSubmitAdd() {
                var cid = c.cg.addCid,
                    ids = pickedIds();
                if (!ids.length) {
                    window.alert("Chưa chọn thành viên");
                    return;
                }
                c.cg.submitting = true;
                post("group_members", {
                    channel_id: cid,
                    op: "add",
                    member_ids: ids
                }).then(function(r) {
                    c.cg.submitting = false;
                    if (!r.data || r.data.error) {
                        window.alert(r.data && r.data.message ? r.data.message : "Lỗi");
                        return;
                    }
                    c.cg.open = false;
                    c.openGroupManage(cid);
                }, function() {
                    c.cg.submitting = false;
                    window.alert("Lỗi kết nối");
                });
            }

            function addCreatedChannel(ch) {
                if (!ch || !ch.channel_id) {
                    c.cg.open = false;
                    return;
                }
                c.chans.unshift(ch);
                subscribe();
                c.cg.open = false;
                c.openConv(parseInt(ch.channel_id, 10)); // mở thread nhóm vừa tạo
            }
            /* ---------- Quản trị nhóm (panel ⋮) ---------- */
            c.manageGroup = function() {
                if (c.isGroup()) {
                    c.openGroupManage(c.curCh);
                }
            };
            c.openGroupManage = function(cid) {
                cid = parseInt(cid, 10) || c.curCh;
                if (!cid) {
                    return;
                }
                c.mng = {
                    open: true,
                    cid: cid,
                    info: null,
                    loading: true
                };
                post("group_info", {
                    channel_id: cid
                }).then(function(r) {
                    c.mng.loading = false;
                    if (!r.data || r.data.error) {
                        window.alert(r.data && r.data.message ? r.data.message : "Lỗi");
                        c.mng.open = false;
                        return;
                    }
                    c.mng.info = r.data;
                }, function() {
                    c.mng.loading = false;
                    c.mng.open = false;
                    window.alert("Lỗi kết nối");
                });
            };
            c.closeManage = function() {
                c.mng.open = false;
            };
            c.mngIsOwner = function() {
                return !!(c.mng.info && parseInt(c.mng.info.is_owner, 10));
            };
            c.mngMembers = function() {
                return (c.mng.info && c.mng.info.members) ? c.mng.info.members : [];
            };
            c.mngIsRoleOwner = function(m) {
                return parseInt(m.role, 10) === 1;
            };
            c.mngRemove = function(m) {
                var pid = parseInt(m.profile_id, 10) || 0,
                    cid = c.mng.cid;
                if (!window.confirm("Xoá thành viên này khỏi nhóm?")) {
                    return;
                }
                post("group_members", {
                    channel_id: cid,
                    op: "remove",
                    member_id: pid
                }).then(function(r) {
                    if (!r.data || r.data.error) {
                        window.alert(r.data && r.data.message ? r.data.message : "Lỗi");
                        return;
                    }
                    c.openGroupManage(cid);
                });
            };
            c.mngTransfer = function(m) {
                var pid = parseInt(m.profile_id, 10) || 0,
                    cid = c.mng.cid;
                if (!window.confirm("Chuyển quyền chủ nhóm cho người này? Bạn sẽ trở thành thành viên.")) {
                    return;
                }
                post("group_transfer", {
                    channel_id: cid,
                    to_id: pid
                }).then(function(r) {
                    if (!r.data || r.data.error) {
                        window.alert(r.data && r.data.message ? r.data.message : "Lỗi");
                        return;
                    }
                    c.openGroupManage(cid);
                });
            };
            /* Đổi tên / đổi ảnh nhóm — DÙNG CHUNG cho panel quản trị (mng*) lẫn thanh nút header (g*).
               cid + tên hiện hành truyền vào → chạy được cả khi panel chưa nạp (từ header dùng c.curCh/c.curName). */
            function groupRename(cid, curName, after) {
                var nv = window.prompt("Tên nhóm mới:", curName || "");
                if (nv === null) {
                    return;
                }
                nv = nv.trim();
                if (nv === "") {
                    return;
                }
                post("group_update", {
                    channel_id: cid,
                    name: nv
                }).then(function(r) {
                    if (!r.data || r.data.error) {
                        window.alert(r.data && r.data.message ? r.data.message : "Lỗi");
                        return;
                    }
                    var ch = findChan(cid);
                    if (ch) {
                        ch.name = nv;
                    } // header title (c.curName) + list tự cập nhật
                    if (after) {
                        after();
                    }
                }, function() {
                    window.alert("Lỗi kết nối, thử lại");
                }); // reject $http (4xx/5xx/mạng) → báo, không im lặng
            }

            function groupPickImage(cid, input, after) {
                if (!input.files || !input.files[0]) {
                    return;
                }
                imgResize(input.files[0], 256, function(data) {
                    if (!data) {
                        window.alert("Ảnh lỗi, chọn ảnh khác");
                        return;
                    }
                    post("group_update", {
                        channel_id: cid,
                        image: data
                    }).then(function(r) {
                        if (!r.data || r.data.error) {
                            window.alert(r.data && r.data.message ? r.data.message : "Lỗi");
                            return;
                        }
                        var ch = findChan(cid);
                        if (ch) {
                            ch.image = data;
                        }
                        if (after) {
                            after();
                        }
                    }, function() {
                        window.alert("Lỗi kết nối, thử lại");
                    });
                });
                input.value = "";
            }
            c.mngRename = function() {
                groupRename(c.mng.cid, (c.mng.info && c.mng.info.name) ? c.mng.info.name : "", function() {
                    c.openGroupManage(c.mng.cid);
                });
            };
            c.mngPickImage = function(input) {
                groupPickImage(c.mng.cid, input, function() {
                    c.openGroupManage(c.mng.cid);
                });
            };
            /* ---------- Nút nhóm trên header (thread nhóm): ＋ thêm TV (MỌI thành viên, kiểu Zalo) + ⚙ cài đặt nhóm (=manageGroup) ---------- */
            c.gAddOpen = function() {
                openCg("add", c.curCh);
            }; // ＋ mở modal thêm TV cho kênh hiện tại (backend: chat_access gate thành viên)
            c.mngLeave = function() {
                var cid = c.mng.cid;
                if (!window.confirm("Rời khỏi nhóm này?")) {
                    return;
                }
                post("group_leave", {
                    channel_id: cid
                }).then(function(r) {
                    if (!r.data || r.data.error) {
                        window.alert(r.data && r.data.message ? r.data.message : "Lỗi");
                        return;
                    }
                    exitGroup(cid);
                });
            };
            c.mngDisband = function() {
                var cid = c.mng.cid;
                if (!window.confirm("Giải tán nhóm? Hành động không thể hoàn tác.")) {
                    return;
                }
                post("group_disband", {
                    channel_id: cid
                }).then(function(r) {
                    if (!r.data || r.data.error) {
                        window.alert(r.data && r.data.message ? r.data.message : "Lỗi");
                        return;
                    }
                    exitGroup(cid);
                });
            };

            function exitGroup(cid) {
                c.mng.open = false;
                if (c.curCh === parseInt(cid, 10)) {
                    c.curCh = 0;
                    c.view = "list";
                    c.msgs = [];
                }
                c.loadChannels();
            }
            /* ---------- Tab Check-in (chụp ảnh + GPS → mod=__MOD&act=checkin) ---------- */
            // Mục tiêu SAI SỐ <= 50m: CHỈ chốt fix khi accuracy <= GEO_TARGET_M → loại fix wifi/IP (accuracy vài km) gây lệch 10-20km.
            // Đạt <= target thì đo thêm GEO_SETTLE_MS lấy fix TỐT NHẤT; <= GEO_GREAT_M chốt ngay.
            // Hết GEO_MAX_WAIT_MS mà vẫn > 50m → KHÔNG gửi (cảnh báo + Thử lại) thay vì gửi toạ độ sai. Server vẫn gate max_accuracy_m (2 lớp).
            var GEO_TARGET_M = 50,
                GEO_GREAT_M = 15,
                GEO_FALLBACK_M = 200,
                GEO_MAX_WAIT_MS = 40000,
                GEO_SETTLE_MS = 5000,
                GEO_TTL_MS = 90000;
            var geoWatch = null,
                geoTimer = null,
                geoSettleTimer = null,
                bestFix = null,
                bestFixT = 0,
                pendingPhoto = null,
                geoReady = false,
                posting = false,
                geoErr = "",
                photoGen = 0,
                addrGen = 0;
            var geoPermWatched = false;

            function watchGeoPerm() { // theo dõi QUYỀN vị trí (Chrome/Android; Safari không hỗ trợ → bỏ qua êm). User cấp lại quyền (Cài đặt) → TỰ đo, khỏi bấm "Chia sẻ vị trí" lại.
                if (geoPermWatched || !navigator.permissions || !navigator.permissions.query) {
                    return;
                }
                geoPermWatched = true;
                try {
                    navigator.permissions.query({
                        name: "geolocation"
                    }).then(function(st) {
                        apply(function() {
                            c.ck.geoPerm = st.state;
                        }); // 'granted' | 'prompt' | 'denied'
                        st.onchange = function() {
                            apply(function() {
                                c.ck.geoPerm = st.state;
                            });
                            if (st.state === "granted" && c.open && c.tab === "checkin" && !geoReady && !posting) {
                                startGeo();
                            } // vừa được cấp quyền → đo ngay
                        };
                    }, function() {
                        geoPermWatched = false;
                    });
                } catch (e) {
                    geoPermWatched = false;
                }
            }
            /* ---------- Thống kê hôm nay (DIRECTOR/BO/BUSINESS_AREA/SALE_DIRECTOR — gate server-side; SALE không dropdown) ---------- */
            c.st = {
                on: CAN_STATS,
                loading: false,
                open: false,
                mode: "",
                regions: [],
                blocks: [],
                saleRootTitle: "",
                regionDepts: [],
                myRegion: null,
                dept: 0,
                dateYmd: null,
                dateObj: null,
                label: "",
                stats: {
                    checked_in: 0,
                    not_checked_in: 0,
                    total: 0
                },
                updated: ""
            };
            c.loadStats = function() {
                if (!c.st.on) {
                    return;
                }
                c.st.loading = true;
                var params = {};
                if (c.st.dept) {
                    params.dept = c.st.dept;
                }
                if (c.st.dateYmd) {
                    params.date = c.st.dateYmd;
                }
                $http.get(url("stats"), {
                    params: params
                }).then(function(r) {
                    c.st.loading = false;
                    var d = (r.data && !r.data.error) ? r.data : null;
                    if (!d) {
                        return;
                    }
                    c.st.mode = d.mode;
                    c.st.regions = d.regions || [];
                    c.st.blocks = d.blocks || [];
                    c.st.regionDepts = d.region_depts || [];
                    c.st.myRegion = d.my_region || null;
                    c.st.saleRootTitle = d.sale_root_title || "";
                    c.st.dept = d.dept || 0;
                    c.st.label = d.label || "";
                    c.st.stats = d.stats || {
                        checked_in: 0,
                        not_checked_in: 0,
                        total: 0
                    };
                    c.st.updated = d.updated || "";
                }, function() {
                    c.st.loading = false;
                });
            };
            c.stToggle = function() {
                if (c.st.mode !== "sale") {
                    c.st.open = !c.st.open;
                }
            }; // SALE_DIRECTOR không có dropdown phạm vi
            c.stPick = function(id) {
                c.st.dept = parseInt(id, 10) || 0;
                c.st.open = false;
                c.loadStats();
            };
            c.stToday = function() {
                c.st.dateObj = null;
                c.st.dateYmd = null;
                c.loadStats();
            };
            c.stDatePick = function() { // input[type=date] ng-model c.st.dateObj → đổi ngày (chặn tương lai)
                var d = c.st.dateObj;
                if (!(d instanceof Date) || isNaN(d.getTime())) {
                    c.st.dateYmd = null;
                    c.loadStats();
                    return;
                }
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                if (d > today) {
                    d = today;
                }
                var ymd = d.getFullYear() * 10000 + (d.getMonth() + 1) * 100 + d.getDate();
                var todayYmd = today.getFullYear() * 10000 + (today.getMonth() + 1) * 100 + today.getDate();
                c.st.dateYmd = (ymd === todayYmd) ? null : ymd; // chọn đúng hôm nay → coi như "Hôm nay" (chip xanh, label "Hôm nay")
                c.loadStats();
            };
            c.stDateLabel = function() {
                if (!c.st.dateYmd) {
                    return "Hôm nay";
                }
                var s = "" + c.st.dateYmd;
                return s.substr(6, 2) + "/" + s.substr(4, 2) + "/" + s.substr(0, 4);
            };
            c.stIsToday = function() {
                return !c.st.dateYmd;
            };
            c.stMax = function() {
                var d = new Date(),
                    m = d.getMonth() + 1,
                    dd = d.getDate();
                return d.getFullYear() + "-" + (m < 10 ? "0" : "") + m + "-" + (dd < 10 ? "0" : "") + dd;
            };
            /* ---- Click số trên card (Đã CI / Chưa CI / Tổng NS) → mở modal danh sách bằng $Core.popup (như report) ---- */
            c.stOpenList = function(type) {
                if (!c.st.on || !window.$Core || !$Core.chat2_checkin) {
                    return;
                }
                $Core.chat2_checkin.open_list(type, c.st.dept || 0, c.st.dateYmd || 0); // dùng chung phạm vi + ngày đang chọn
            };
            /* Controller popup danh sách + hành trình — mở bằng $Core.popup, chuyển step bằng _swap(.modal-content). MIRROR $Core.report_checkin;
               định nghĩa global để onclick trong HTML modal (ngoài Angular) gọi được. Dùng jQuery $.post tới BASE (mod=chat2). */
            if (window.$Core && $Core.popup) {
                $Core.chat2_checkin = {
                    _listParams: {
                        type: "has_checkin",
                        dept: 0,
                        date: 0
                    },
                    _journeyState: {
                        profile_id: 0,
                        day: 0
                    },
                    /* Mở modal NỔI TRÊN panel widget (theme đặt .modal z-index 5, panel 99981). ÉP z-index INLINE lên chính #uid — chắc chắn thắng,
                       KHÔNG phụ thuộc tham số cls (bản $Core.popup.open trên server có thể không nhận) hay CSS. Backdrop đặt DƯỚI modal → dim panel, KHÔNG đè modal. */
                    _open: function(html, uid) {
                        if (!uid) {
                            return;
                        }
                        $Core.popup.open("auto", "auto", html, uid, "fhc-stmodal");
                        $("#" + uid).css("z-index", 5); // NGAY: modal trên panel (99981) — tránh nháy sau panel
                        setTimeout(function() {
                            $(".modal-backdrop").last().css("z-index", 4);
                        }, 30); // backdrop tạo trong show() → chờ 1 nhịp; đặt DƯỚI modal (dim panel, không đè)
                    },
                    open_list: function(type, dept, date) {
                        var self = $Core.chat2_checkin;
                        self._listParams = {
                            type: type || "has_checkin",
                            dept: parseInt(dept, 10) || 0,
                            date: parseInt(date, 10) || 0
                        };
                        if ($Core.util) {
                            $Core.util.toggleIndicatior(1);
                        }
                        $.post(BASE + "stats_list", self._listParams, function(r) {
                            if ($Core.util) {
                                $Core.util.toggleIndicatior(0);
                            }
                            if (r && r.uid) {
                                self._open(r.html, r.uid);
                            }
                        }, "json");
                    },
                    /* Click 1 người trong danh sách → step Hành trình NGAY trong popup (giữ ngày đang xem của danh sách) */
                    load_profile_journey: function(_this, e) {
                        if (e) {
                            e.preventDefault();
                        }
                        var self = $Core.chat2_checkin;
                        self._fetchJourney(parseInt($(_this).data("profile") || 0, 10), self._listParams.date || 0);
                    },
                    /* Thay .modal-content của popup đang mở → chuyển step, GIỮ modal/backdrop */
                    _swap: function(html) {
                        var $cur = $(".modal.show").last();
                        if (!$cur.length) {
                            return false;
                        }
                        var $new = $("<div>").html(html).find(".modal-content").first();
                        if (!$new.length) {
                            return false;
                        }
                        $cur.find(".modal-content").first().replaceWith($new);
                        return true;
                    },
                    _fetchJourney: function(pid, day) {
                        var self = $Core.chat2_checkin;
                        if (!pid) {
                            return;
                        }
                        if ($Core.util) {
                            $Core.util.toggleIndicatior(1);
                        }
                        $.post(BASE + "stats_journey", {
                            pid: pid,
                            date: parseInt(day, 10) || 0
                        }, function(r) {
                            if ($Core.util) {
                                $Core.util.toggleIndicatior(0);
                            }
                            self._journeyState = {
                                profile_id: pid,
                                day: parseInt((r && r.day) || 0, 10)
                            };
                            if (!(r && r.uid)) {
                                return;
                            }
                            if (!self._swap(r.html)) {
                                self._open(r.html, r.uid);
                            }
                        }, "json");
                    },
                    /* Nút ‹ › đổi ngày trong step Hành trình (chặn ngày tương lai) */
                    journey_day: function(delta) {
                        var st = $Core.chat2_checkin._journeyState || {};
                        if (!st.profile_id || !st.day) {
                            return;
                        }
                        var s = "" + st.day;
                        var d = new Date(parseInt(s.substr(0, 4), 10), parseInt(s.substr(4, 2), 10) - 1, parseInt(s.substr(6, 2), 10));
                        d.setDate(d.getDate() + delta);
                        var today = new Date();
                        today.setHours(0, 0, 0, 0);
                        if (d > today) {
                            return;
                        }
                        $Core.chat2_checkin._fetchJourney(st.profile_id, d.getFullYear() * 10000 + (d.getMonth() + 1) * 100 + d.getDate());
                    },
                    /* Nút "Quay lại" trong step Hành trình → tải lại danh sách vào CÙNG popup */
                    back_to_list: function() {
                        var self = $Core.chat2_checkin,
                            p = self._listParams || {};
                        if ($Core.util) {
                            $Core.util.toggleIndicatior(1);
                        }
                        $.post(BASE + "stats_list", {
                            type: p.type || "has_checkin",
                            dept: p.dept || 0,
                            date: p.date || 0
                        }, function(r) {
                            if ($Core.util) {
                                $Core.util.toggleIndicatior(0);
                            }
                            if (!(r && r.uid)) {
                                return;
                            }
                            if (!self._swap(r.html)) {
                                self._open(r.html, r.uid);
                            }
                        }, "json");
                    }
                };
            }
            c.loadCheckin = function() {
                c.loadCheckinTags(); // nạp tag list (1 lần) cho form + map hiển thị journey
                c.loadStats(); // thống kê hôm nay (tự bỏ qua nếu không có quyền)
                watchGeoPerm(); // bám trạng thái quyền vị trí → tự phục hồi khi user cấp lại
                stopGeo();
                bestFix = null;
                pendingPhoto = null;
                geoReady = false;
                posting = false;
                c.ck.loading = true;
                c.ck.my = null;
                c.ck.feed = null;
                c.ck.posting = false;
                c.ck.geoReady = false;
                c.ck.geoErr = "";
                c.ck.subErr = "";
                c.ck.photo = "";
                c.ck.note = "";
                c.ck.address = "";
                c.ck.tags = [];
                c.ck.addrLoading = false;
                $http.get(url("my")).then(function(a) {
                    $http.get(url("feed")).then(function(b) {
                        c.ck.loading = false;
                        c.ck.my = (a.data && !a.data.error) ? a.data : {};
                        c.ck.feed = (b.data && !b.data.error) ? b.data : {};
                        c.ck.feedOffset = (b.data && b.data.next_offset) ? b.data.next_offset : 0;
                        c.ck.feedMore = !!(b.data && b.data.has_more);
                        c.ck.loadingMore = false;
                        ckPartitionFeed(); // tách feed: của tôi / người khác
                        setState("idle"); // KHÔNG khoá "done" — check-in nhiều lần/ngày, luôn cho chụp tiếp
                    }, function() {
                        c.ck.loading = false;
                    });
                }, function() {
                    c.ck.loading = false;
                });
            };

            function setState(s) {
                c.ck.state = s;
            }
            c.ckMapUrl = function(it) {
                return "https://www.google.com/maps?q=" + it.lat + "," + it.lng;
            }; // mở Google Maps tại toạ độ
            c.ckFeedList = function() {
                return (c.ck.feed && c.ck.feed.list) ? c.ck.feed.list : [];
            };

            function ckPartitionFeed() { // "Của tôi" = feed.mine TỪ BACKEND (đầy đủ, không phân trang); "Người khác" = từ list (loại mình + gộp 1 dòng/người)
                if (!c.ck.feed) {
                    return;
                }
                if (!c.ck.feed.mine) {
                    c.ck.feed.mine = [];
                } // KHÔNG lọc mine từ list phân trang (sẽ thiếu khi list chưa tải tới trang có mình)
                var all = c.ck.feed.list || [],
                    others = [],
                    seen = {},
                    i, pid;
                for (i = 0; i < all.length; i++) {
                    pid = parseInt(all[i].profile_id, 10);
                    if (pid === ME) {
                        continue;
                    } // mình đã có ở "Của tôi" → bỏ khỏi "Người khác"
                    else if (!seen[pid]) {
                        seen[pid] = 1;
                        others.push(all[i]);
                    }
                }
                c.ck.feed.others = others;
            }
            c.ckFeedMine = function() {
                return (c.ck.feed && c.ck.feed.mine) ? c.ck.feed.mine : [];
            };
            c.ckFeedOthers = function() {
                return (c.ck.feed && c.ck.feed.others) ? c.ck.feed.others : [];
            };
            /* ---------- Hành trình (Timeline Check-in) ---------- */
            /* Giai đoạn giao diện: dựng từ feed.mine (hôm nay — dữ liệu THẬT). Ngày khác/xem người khác → chờ backend act=journey. */
            var JN_WD = ["Chủ nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"];

            function jnPad(n) {
                return (n < 10 ? "0" : "") + n;
            }

            function jnFmt(d) {
                return jnPad(d.getDate()) + "/" + jnPad(d.getMonth() + 1) + "/" + d.getFullYear();
            }

            function jnInput(d) {
                return d.getFullYear() + "-" + jnPad(d.getMonth() + 1) + "-" + jnPad(d.getDate());
            } // yyyy-mm-dd cho input[type=date]
            function jnToday() {
                var d = new Date();
                d.setHours(0, 0, 0, 0);
                return d;
            }

            function jnYmd(d) {
                return "" + d.getFullYear() + jnPad(d.getMonth() + 1) + jnPad(d.getDate());
            } // yyyymmdd (param date)
            c.ckJnIsToday = function() {
                return !c.ck.journey || c.ck.journey.isToday;
            };
            c.ckOpenJourney = function(it) { // it = feed row → xem hành trình NGƯỜI KHÁC; không truyền = bản thân
                c.ck.jnTarget = (it && parseInt(it.profile_id, 10) && parseInt(it.profile_id, 10) !== ME) ?
                    {
                        pid: parseInt(it.profile_id, 10),
                        profile: {
                            name: it.name,
                            avatar: it.avatar,
                            dept: it.dept,
                            role: "",
                            badge: ""
                        }
                    } :
                    null;
                c.ck.journey = null; // reset → không nháy dữ liệu người/ngày trước
                c.ck.jnDateObj = jnToday();
                c.ck.jnMax = jnInput(jnToday()); // chặn chọn ngày tương lai
                setState("journey");
                c.ckLoadJourney();
            };
            c.ckJourneyBack = function() {
                c.ck.jnTarget = null;
                setState("idle");
            };
            c.ckJnDay = function(delta) { // đổi ngày bằng nút ‹ › (không cho vượt hôm nay)
                var d = c.ck.jnDateObj ? new Date(c.ck.jnDateObj) : jnToday();
                d.setDate(d.getDate() + delta);
                if (d > jnToday()) {
                    return;
                }
                c.ck.jnDateObj = d;
                c.ckLoadJourney();
            };
            c.ckJnPick = function() { // chọn ngày từ input[type=date] (click ô ngày)
                var d = c.ck.jnDate;
                if (!(d instanceof Date) || isNaN(d.getTime())) {
                    return;
                }
                d = new Date(d.getFullYear(), d.getMonth(), d.getDate());
                if (d > jnToday()) {
                    d = jnToday();
                }
                c.ck.jnDateObj = d;
                c.ckLoadJourney();
            };
            c.ckLoadJourney = function() { // nạp hành trình từ backend (mod=__MOD&act=journey) theo ngày
                var d = c.ck.jnDateObj || jnToday();
                var isToday = (jnFmt(d) === jnFmt(jnToday()));
                c.ck.jnDate = new Date(d); // đồng bộ ô input[type=date]
                var j = c.ck.journey || {};
                j.loading = true;
                j.isToday = isToday;
                j.dateLabel = (isToday ? "Hôm nay · " : "") + JN_WD[d.getDay()] + ", " + jnFmt(d);
                if (!j.profile) {
                    j.profile = (c.ck.jnTarget && c.ck.jnTarget.profile) ? angular.copy(c.ck.jnTarget.profile) : {
                        name: "",
                        avatar: "",
                        dept: "",
                        role: "",
                        badge: ""
                    };
                } // prefill từ row → header không nháy
                if (!j.stats) {
                    j.stats = {
                        count: 0,
                        places: 0,
                        photos: 0
                    };
                }
                if (!j.items) {
                    j.items = [];
                }
                c.ck.journey = j;
                var jnParams = {
                    date: jnYmd(d)
                };
                if (c.ck.jnTarget) {
                    jnParams.pid = c.ck.jnTarget.pid;
                } // xem người khác → gửi pid (server enforce scope)
                $http.get(url("journey"), {
                    params: jnParams
                }).then(function(r) {
                    var data = (r.data && !r.data.error) ? r.data : {};
                    c.ck.journey.loading = false;
                    if (data.profile) {
                        c.ck.journey.profile = data.profile;
                    }
                    c.ck.journey.stats = data.stats || {
                        count: 0,
                        places: 0,
                        photos: 0
                    };
                    c.ck.journey.items = data.items || [];
                }, function() {
                    c.ck.journey.loading = false;
                    c.ck.journey.items = [];
                    c.ck.journey.stats = {
                        count: 0,
                        places: 0,
                        photos: 0
                    };
                });
            };
            c.ckLoadMore = function() { // tải thêm ~10 check-in (phân trang offset; append + re-partition)
                if (c.ck.loadingMore || !c.ck.feedMore || !c.ck.feed) {
                    return;
                }
                c.ck.loadingMore = true;
                $http.get(url("feed"), {
                    params: {
                        offset: c.ck.feedOffset
                    }
                }).then(function(r) {
                    c.ck.loadingMore = false;
                    if (r.data && !r.data.error) {
                        c.ck.feed.list = (c.ck.feed.list || []).concat(r.data.list || []);
                        c.ck.feedOffset = r.data.next_offset || c.ck.feedOffset;
                        c.ck.feedMore = !!r.data.has_more;
                        ckPartitionFeed();
                    }
                }, function() {
                    c.ck.loadingMore = false;
                });
            };
            /* Camera check-in TRONG APP có filter — logic ở file riêng chat2.camera.js (nạp trước file này). Glue: khởi tạo + nối ảnh chụp vào luồng review. */
            var cam = (typeof window.Chat2Camera === "function") ? window.Chat2Camera({
                c: c,
                apply: apply,
                onShot: function(data) { // ảnh từ camera trong app → mở form review + đo GPS (giống ckOnPhoto)
                    photoGen++;
                    pendingPhoto = data;
                    c.ck.photo = data;
                    c.ck.geoErr = "";
                    c.ck.geoCoarse = 0;
                    c.ck.subErr = "";
                    c.ck.geoReady = false;
                    c.ck.addrLoading = false;
                    c.ck.address = "";
                    c.ck.note = "";
                    c.ck.geoMsg = "Đang định vị…";
                    c.ck.geoGood = false;
                    setState("review");
                    startGeo();
                }
            }) : null;

            function camTry() {
                if (cam) {
                    cam.open();
                } else {
                    var f = document.getElementById("fhc-ck-file");
                    if (f) {
                        f.click();
                    }
                }
            } // không có module/không hỗ trợ → camera native
            c.ckOnCta = function() { // bấm "Chụp ảnh check-in": MỞ MÁY ẢNH trước; GPS đo SAU khi có ảnh
                stopGeo();
                bestFix = null;
                bestFixT = 0;
                pendingPhoto = null;
                geoReady = false;
                posting = false;
                geoErr = "";
                photoGen++;
                addrGen++;
                camTry(); // mở camera TRONG APP (có filter); getUserMedia lỗi / in-app browser (Zalo/FB) → tự fallback camera native
                // KHÔNG startGeo / KHÔNG đổi state ở đây: máy ảnh chiếm màn (page bị treo → GPS không hội tụ);
                // đo định vị trong ckOnPhoto (sau khi đóng máy ảnh, page foreground). Huỷ chụp → vẫn ở 'idle'.
            };
            c.ckRetryGeo = function() { // định vị lỗi trong form xác nhận → đo lại (GIỮ ảnh đã chụp)
                c.ck.geoErr = "";
                c.ck.geoCoarse = 0;
                c.ck.geoReady = false;
                geoReady = false;
                c.ck.geoMsg = "Đang định vị…";
                c.ck.geoGood = false;
                startGeo();
            };
            c.ckOnPhoto = function(input) { // CÓ ảnh → mở form XÁC NHẬN (ảnh + định vị + ghi chú), KHÔNG auto-gửi
                if (!input.files || !input.files[0]) {
                    return;
                }
                var gen = ++photoGen; // thế hệ ảnh: kết quả resize của lần cũ (đã bị thay) sẽ bị bỏ
                c.ck.geoErr = "";
                c.ck.geoCoarse = 0;
                c.ck.subErr = "";
                c.ck.geoReady = false;
                c.ck.addrLoading = false;
                c.ck.address = "";
                c.ck.note = "";
                c.ck.geoMsg = "Đang định vị…";
                c.ck.geoGood = false;
                setState("review");
                startGeo(); // GPS chạy KHI page foreground (máy ảnh đã đóng) → không bị máy ảnh treo
                imgResize(input.files[0], 1280, function(data) {
                    if (gen !== photoGen) {
                        return;
                    } // đã có lần chụp/mở mới → bỏ kết quả cũ (chống latch ảnh stale)
                    if (!data) {
                        apply(function() {
                            c.ck.subErr = "Ảnh lỗi, chụp lại";
                            setState("idle");
                        });
                        return;
                    }
                    pendingPhoto = data;
                    apply(function() {
                        c.ck.photo = data;
                    }); // ảnh xem trước trong form
                });
                input.value = "";
            };

            function geoIsAndroid() {
                return /Android/i.test(navigator.userAgent);
            }

            function geoInApp() {
                return /FBAN|FBAV|FB_IAB|Instagram|Zalo|Messenger|Line\/|MicroMessenger|GSA/i.test(navigator.userAgent);
            }

            function geoTipPerm() {
                if (geoInApp()) {
                    return "Bạn đang mở trong ứng dụng (Zalo/Facebook…) — định vị hay bị chặn. Mở bằng Chrome/Safari: menu ⋮ (hoặc ‘…’) → ‘Mở trong trình duyệt’, rồi thử lại.";
                }
                if (geoIsAndroid()) {
                    return "Android: vuốt từ trên xuống BẬT ‘Vị trí’; Cài đặt → Vị trí → ‘Độ chính xác vị trí của Google’ BẬT; chạm ổ khoá cạnh địa chỉ → Quyền → Vị trí → Cho phép. Rồi bấm Thử lại.";
                }
                return "iPhone: Cài đặt → Quyền riêng tư & Bảo mật → Dịch vụ vị trí → BẬT → Safari (Trang web) → ‘Trong khi dùng ứng dụng’ + ‘Vị trí chính xác’. Rồi thử lại.";
            }

            function geoTipAccuracy() {
                if (geoInApp()) {
                    return "Định vị trong ứng dụng (Zalo/Facebook…) rất kém chính xác. Mở bằng Chrome/Safari rồi thử lại.";
                }
                if (geoIsAndroid()) {
                    return "GPS đang tắt hoặc ở chế độ tiết kiệm pin nên chỉ định vị theo mạng (sai số lớn). Bật ‘Vị trí’ + ‘Độ chính xác vị trí của Google’, ra chỗ thoáng, rồi bấm Thử lại.";
                }
                return "Bật ‘Vị trí chính xác’ (iOS: Cài đặt → Quyền riêng tư → Dịch vụ vị trí → trình duyệt → Vị trí chính xác), ra ngoài trời, rồi bấm Thử lại.";
            }

            function startGeo() {
                if (!window.isSecureContext) {
                    geoBad("Trang không chạy HTTPS nên trình duyệt chặn định vị. Mở bằng địa chỉ https://… rồi thử lại.");
                    return;
                }
                if (!navigator.geolocation) {
                    geoBad("Trình duyệt không hỗ trợ định vị. Hãy mở bằng Chrome/Safari.");
                    return;
                }
                stopGeo();
                bestFix = null;
                bestFixT = 0;
                geoReady = false;
                geoErr = "";
                geoTimer = setTimeout(function() {
                    onGeoTimeout();
                }, GEO_MAX_WAIT_MS);
                geoWatch = navigator.geolocation.watchPosition(
                    function(p) {
                        onGeoFix(p);
                    },
                    function(e) {
                        onGeoErr(e);
                    }, {
                        enableHighAccuracy: true,
                        maximumAge: 0,
                        timeout: GEO_MAX_WAIT_MS
                    }
                );
            }

            function onGeoFix(p) {
                if (posting) {
                    return;
                }
                var acc = Math.round((p.coords && p.coords.accuracy) || 0);
                if (acc <= 0) {
                    return;
                }
                geoErr = "";
                if (!bestFix || acc < bestFix.acc) {
                    bestFix = {
                        lat: p.coords.latitude,
                        lng: p.coords.longitude,
                        acc: acc
                    };
                    bestFixT = Date.now();
                }
                var best = bestFix.acc,
                    good = best <= GEO_TARGET_M;
                apply(function() {
                    c.ck.geoGood = good;
                    c.ck.geoMsg = "Định vị ±" + best + "m" + (good ? " ✓ đủ chính xác." : (best >= 500 ? " · GPS có thể đang tắt — bật ‘Vị trí độ chính xác cao’…" : " · đang cải thiện, đứng yên/ra chỗ thoáng…"));
                });
                if (best <= GEO_GREAT_M) {
                    stopGeo();
                    onGeoResolved();
                    return;
                } // rất chính xác → chốt ngay
                if (good && geoSettleTimer === null) { // đã ≤ 50m → đo thêm GEO_SETTLE_MS lấy fix tốt nhất rồi chốt (bestFix chỉ cải thiện)
                    geoSettleTimer = setTimeout(function() {
                        stopGeo();
                        onGeoResolved();
                    }, GEO_SETTLE_MS);
                }
            }

            function onGeoErr(e) {
                if (posting) {
                    return;
                }
                if (e && e.code === 1) {
                    geoBad("Chưa lấy được quyền/định vị. " + geoTipPerm());
                    return;
                } // code 1: quyền chưa cấp HOẶC (Android) GPS/Dịch vụ vị trí đang tắt — hướng dẫn theo nền tảng
                // code 2 (vị trí không khả dụng) / 3 (quá lâu) = tạm thời → ghi lý do, để timeout 15s quyết (watch có thể vẫn ra fix)
                geoErr = (e && e.code === 2) ? "tín hiệu vị trí chưa khả dụng" : (e && e.code === 3) ? "định vị hơi lâu" : "lỗi định vị";
                apply(function() {
                    if (!bestFix) {
                        c.ck.geoMsg = "Đang định vị… (" + geoErr + ")";
                    }
                });
            }

            function onGeoTimeout() {
                if (posting) {
                    return;
                }
                stopGeo();
                if (bestFix && bestFix.acc <= GEO_TARGET_M) {
                    onGeoResolved();
                    return;
                } // đủ chính xác → chốt
                if (bestFix) { // CÓ fix nhưng sai số > 50m (thường wifi/IP) → KHÔNG gửi toạ độ lệch, buộc đo lại
                    if (bestFix.acc <= GEO_FALLBACK_M) {
                        apply(function() {
                            c.ck.geoCoarse = bestFix.acc;
                        });
                        geoBad("Sai số định vị ±" + bestFix.acc + "m (> " + GEO_TARGET_M + "m). Nên bấm Thử lại cho chính xác hơn — " + geoTipAccuracy() + " Nếu không được, có thể bấm ‘Vẫn check-in với vị trí này’.");
                        return;
                    }
                    geoBad("Sai số định vị ±" + bestFix.acc + "m — quá lớn, không dùng được. " + geoTipAccuracy());
                    return;
                }
                geoBad("Chưa lấy được vị trí" + (geoErr ? (" — " + geoErr) : "") + ". " + geoTipPerm());
            }
            /* Có fix GPS → chốt vị trí vào form, bật nút Hoàn tất, lấy địa chỉ (best-effort, không chặn nút). */
            function onGeoResolved() {
                if (posting) {
                    return;
                }
                if (!c.open || c.tab !== "checkin") {
                    return;
                } // đã rời/đóng → bỏ
                if (!bestFix) {
                    return;
                }
                if (Date.now() - bestFixT > GEO_TTL_MS) {
                    startGeo();
                    return;
                } // mẫu quá cũ → đo lại
                var fix = bestFix;
                geoReady = true;
                apply(function() {
                    c.ck.gpsLat = fix.lat;
                    c.ck.gpsLng = fix.lng;
                    c.ck.gpsAcc = fix.acc;
                    c.ck.geoReady = true;
                    c.ck.geoErr = "";
                    c.ck.geoCoarse = 0;
                    c.ck.address = "";
                    c.ck.geoMsg = "Đã định vị ±" + fix.acc + "m";
                });
                fetchAddress(); // BẮT BUỘC có địa chỉ mới cho gửi → lấy địa chỉ ngay
            }
            /* Reverse-geocode (server) → c.ck.address. Nút Hoàn tất chỉ bật khi CÓ địa chỉ (user yêu cầu). */
            function fetchAddress() {
                if (!bestFix) {
                    return;
                }
                var fix = bestFix,
                    gen = ++addrGen; // token chống latch: geocode cũ (chậm) không ghi đè lần Thử lại mới
                apply(function() {
                    c.ck.addrLoading = true;
                    c.ck.address = "";
                    c.ck.atOffice = true;
                }); // reset atOffice, chờ geocode trả at_office
                post("geocode", {
                    lat: fix.lat,
                    lng: fix.lng
                }).then(function(r) {
                    if (gen !== addrGen) {
                        return;
                    }
                    c.ck.addrLoading = false;
                    c.ck.address = (r.data && !r.data.error && r.data.address) ? r.data.address : "";
                    c.ck.atOffice = !(r.data && r.data.at_office === 0); // at_office=0 → ngoài VP → bắt buộc tag/note
                }, function() {
                    if (gen !== addrGen) {
                        return;
                    }
                    c.ck.addrLoading = false;
                    c.ck.address = "";
                }); // lỗi → trống → UI hiện "Không lấy được địa chỉ · Thử lại"
            }
            c.ckRetryAddr = function() {
                fetchAddress();
            };
            c.ckSetIo = function(m) {
                c.ck.io = (m === "out") ? "out" : "in";
            }; // nút toggle Check-in / Check-out (chế độ bắt đầu / kết thúc ngày)
            c.ckAcceptCoarse = function() { // "Vẫn check-in với vị trí này" khi sai số 50–200m → dùng fix thô (server nhận, đánh dấu Ngoài VP → buộc tag/note)
                if (!bestFix || bestFix.acc > GEO_FALLBACK_M) {
                    return;
                }
                apply(function() {
                    c.ck.geoCoarse = 0;
                    c.ck.geoErr = "";
                });
                onGeoResolved();
            }; // (chấp nhận vị trí thô ≤200m, không đo lại)
            c.ckNeedTagNote = function() { // ngoài VP → BẮT BUỘC chọn tag HOẶC nhập ghi chú
                return !c.ck.atOffice && !((c.ck.tags && c.ck.tags.length) || (c.ck.note && ('' + c.ck.note).trim()));
            };
            c.ckSubmit = function() { // bấm "Hoàn tất check-in" → gửi ảnh + GPS + ghi chú + địa chỉ
                if (c.ck.posting || !geoReady || !c.ck.address || !pendingPhoto || !bestFix) {
                    return;
                } // BẮT BUỘC có địa chỉ
                if (c.ckNeedTagNote()) {
                    c.ck.subErr = "Do bạn đang checkin bên ngoài VP làm việc, vui lòng ghi rõ nội dung và hoạt động.";
                    return;
                }
                if (!c.open || c.tab !== "checkin") {
                    return;
                }
                c.ck.posting = true;
                posting = true;
                c.ck.subErr = "";
                var fix = bestFix;
                post("checkin", {
                    photo: pendingPhoto,
                    lat: fix.lat,
                    lng: fix.lng,
                    accuracy: fix.acc,
                    note: c.ck.note || "",
                    address: c.ck.address || "",
                    tags: (c.ck.tags || []).join(","),
                    io: c.ck.io
                }).then(function(r) {
                    c.ck.posting = false;
                    posting = false;
                    if (!r.data || r.data.error) {
                        c.ck.subErr = (r.data && r.data.message) ? r.data.message : "Check-in thất bại, thử lại";
                        return;
                    } // GIỮ form để gửi lại
                    pendingPhoto = null;
                    c.loadCheckin(); // xong → reset + về idle + nạp lại feed
                }, function() {
                    c.ck.posting = false;
                    posting = false;
                    c.ck.subErr = "Lỗi kết nối, thử lại";
                });
            };
            c.ckCancel = function() { // huỷ form xác nhận → bỏ ảnh/định vị đang dở, về idle
                stopGeo();
                pendingPhoto = null;
                geoReady = false;
                posting = false;
                photoGen++;
                addrGen++;
                c.ck.posting = false;
                c.ck.geoReady = false;
                c.ck.photo = "";
                c.ck.note = "";
                c.ck.address = "";
                c.ck.tags = [];
                c.ck.geoErr = "";
                c.ck.subErr = "";
                c.ck.addrLoading = false;
                setState("idle");
            };

            function stopGeo() {
                if (geoWatch !== null && navigator.geolocation) {
                    navigator.geolocation.clearWatch(geoWatch);
                }
                geoWatch = null;
                if (geoTimer) {
                    clearTimeout(geoTimer);
                    geoTimer = null;
                }
                if (geoSettleTimer) {
                    clearTimeout(geoSettleTimer);
                    geoSettleTimer = null;
                }
            }

            function geoBad(msg) { // GPS lỗi → GIỮ ảnh, hiện lý do ở ô Vị trí trong form + cho "thử lại" định vị
                stopGeo();
                geoReady = false;
                posting = false;
                apply(function() {
                    c.ck.geoErr = msg;
                    c.ck.geoReady = false;
                });
            }
            /* Nén ảnh (cạnh dài ≤ max) → data URL jpeg. Dùng chung cho ảnh nhóm tạo/đổi + check-in.
               Lỗi đọc/giải mã ảnh (ảnh hỏng) → cb(null) để caller thoát (KHÔNG treo). */
            function imgResize(file, max, cb) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = new Image();
                    img.onload = function() {
                        var w = img.width,
                            h = img.height;
                        if (w > h && w > max) {
                            h = Math.round(h * max / w);
                            w = max;
                        } else if (h >= w && h > max) {
                            w = Math.round(w * max / h);
                            h = max;
                        }
                        var cv = document.createElement("canvas");
                        cv.width = w;
                        cv.height = h;
                        cv.getContext("2d").drawImage(img, 0, 0, w, h);
                        cb(cv.toDataURL("image/jpeg", 0.82));
                    };
                    img.onerror = function() {
                        cb(null);
                    }; // ảnh không giải mã được → thoát
                    img.src = e.target.result;
                };
                reader.onerror = function() {
                    cb(null);
                };
                reader.readAsDataURL(file);
            }
            c.loadHistory = function(cid) {
                c.loading = true;
                c.msgs = [];
                c.pinnedList = [];
                c.pinOpen = false;
                c.seenList = [];
                setTyping("");
                c.hasMoreHistory = false;
                c.loadingMore = false;
                c.srch.open = false;
                c.srch.q = "";
                c.srch.results = null;
                post("history", {
                    channel_id: cid,
                    since: 0
                }).then(function(r) {
                    c.loading = false;
                    if (r.data && !r.data.error) {
                        c.msgs = (r.data.list || []).map(decorate);
                        c.pinnedList = r.data.pinned || [];
                        c.seenList = r.data.seen || [];
                        c.hasMoreHistory = !!r.data.has_more;
                        markRead(cid);
                        $timeout(scrollBottom, 30);
                    }
                }, function() {
                    c.loading = false;
                });
            };
            c.loadMore = function() { // tải tin CŨ HƠN (load-more quá khứ) → prepend + giữ vị trí cuộn
                if (c.loadingMore || !c.hasMoreHistory || !c.msgs.length || !(c.curCh > 0)) {
                    return;
                }
                c.loadingMore = true;
                var cid = c.curCh,
                    before = parseInt(c.msgs[0].message_id, 10) || 0;
                var b = document.getElementById("fhc-ng-body"),
                    oldH = b ? b.scrollHeight : 0,
                    oldTop = b ? b.scrollTop : 0;
                post("history", {
                    channel_id: cid,
                    before: before
                }).then(function(r) {
                    c.loadingMore = false;
                    if (c.curCh !== cid || !r.data || r.data.error) {
                        return;
                    }
                    var older = (r.data.list || []).map(decorate);
                    if (older.length) {
                        c.msgs = older.concat(c.msgs);
                    }
                    c.hasMoreHistory = !!r.data.has_more;
                    $timeout(function() {
                        var bb = document.getElementById("fhc-ng-body");
                        if (bb) {
                            bb.scrollTop = oldTop + (bb.scrollHeight - oldH);
                        }
                    }, 0);
                }, function() {
                    c.loadingMore = false;
                });
            };

            function parseMentions(content) {
                content = content || "";
                var parts = [],
                    re = /@\[([^\]]+)\]\((\d+)\)/g,
                    last = 0,
                    mm, memMe = false;
                while ((mm = re.exec(content)) !== null) {
                    if (mm.index > last) {
                        parts.push({
                            men: 0,
                            v: content.slice(last, mm.index)
                        });
                    }
                    var pid = parseInt(mm[2], 10);
                    parts.push({
                        men: 1,
                        name: mm[1],
                        pid: pid
                    });
                    if (pid === ME) {
                        memMe = true;
                    }
                    last = re.lastIndex;
                }
                if (last < content.length) {
                    parts.push({
                        men: 0,
                        v: content.slice(last)
                    });
                }
                return {
                    parts: parts.length ? parts : [{
                        men: 0,
                        v: content
                    }],
                    memMe: memMe
                };
            }

            function decorate(m) {
                m.is_me = (parseInt(m.profile_id, 10) === ME) ? 1 : 0;
                var pm = parseMentions(m.content || "");
                m._parts = pm.parts;
                m._memMe = pm.memMe ? 1 : 0;
                return m;
            }

            function curGroup() {
                var ch = c.curChan();
                return !!(ch && parseInt(ch.type, 10) === 4);
            }

            function loadMentionMembers() {
                if (c._atMemCid === c.curCh && c.atMembers) {
                    return;
                }
                c._atMemCid = c.curCh;
                c.atMembers = [];
                post("group_info", {
                    channel_id: c.curCh
                }).then(function(r) {
                    if (r.data && !r.data.error) {
                        c.atMembers = (r.data.members || []).filter(function(mm) {
                            return parseInt(mm.profile_id, 10) !== ME;
                        });
                    }
                }, function() {});
            }
            c.onCompose = function($event) { // gõ @ trong NHÓM → mở picker thành viên
                tlog("onCompose keyup");
                emitTyping();
                var ta = $event.target,
                    pos = ta.selectionStart,
                    txt = (c.draft || "").slice(0, pos),
                    mt = txt.match(/@(\S*)$/);
                if (mt && curGroup()) {
                    if (mt[1] !== c.atQuery) {
                        c.atActive = 0;
                    } // query đổi (gõ thêm/xoá) → về dòng đầu; ↑/↓ không đổi text nên giữ
                    c.atQuery = mt[1];
                    c.atStart = pos - mt[0].length;
                    c.atOpen = true;
                    loadMentionMembers();
                } else {
                    c.atOpen = false;
                }
            };
            c.atFiltered = function() { // stable element refs (như cgFiltered) → KHÔNG infdig
                var q = (c.atQuery || "").toLowerCase(),
                    out = [],
                    list = c.atMembers || [],
                    i;
                for (i = 0; i < list.length && out.length < 8; i++) {
                    if (!q || (list[i].name || "").toLowerCase().indexOf(q) >= 0) {
                        out.push(list[i]);
                    }
                }
                return out;
            };

            function atEnsureVisible() { // cuộn dòng đang chọn vào tầm nhìn TRONG hộp .fhc-at (không cuộn cả trang)
                $timeout(function() {
                    var box = document.querySelector("#fhchat .fhc-at");
                    if (!box) {
                        return;
                    }
                    var items = box.querySelectorAll(".fhc-at-item"),
                        el = items[c.atActive];
                    if (!el) {
                        return;
                    }
                    var top = el.offsetTop,
                        bot = top + el.offsetHeight;
                    if (top < box.scrollTop) {
                        box.scrollTop = top;
                    } else if (bot > box.scrollTop + box.clientHeight) {
                        box.scrollTop = bot - box.clientHeight;
                    }
                }, 0);
            }
            c.pickMention = function(mem) {
                var d = c.draft || "",
                    after = d.slice(c.atStart + 1 + (c.atQuery || "").length);
                c.draft = d.slice(0, c.atStart) + "@" + mem.name + " " + after;
                var dup = false,
                    i;
                for (i = 0; i < c.mentions.length; i++) {
                    if (c.mentions[i].pid === mem.profile_id) {
                        dup = true;
                        break;
                    }
                }
                if (!dup) {
                    c.mentions.push({
                        name: mem.name,
                        pid: mem.profile_id
                    });
                }
                c.atOpen = false;
                c.atQuery = "";
                c.atActive = 0;
                $timeout(function() {
                    var ta = document.getElementById("fhc-input");
                    if (ta) {
                        ta.focus();
                        ta.selectionStart = ta.selectionEnd = ta.value.length;
                    }
                }, 0); // con trỏ về cuối
            };

            function markRead(cid) {
                var last = 0;
                for (var i = 0; i < c.msgs.length; i++) {
                    last = Math.max(last, parseInt(c.msgs[i].message_id, 10) || 0);
                }
                post("read", {
                    channel_id: cid,
                    last_id: last
                });
                var ch = c.curChan();
                if (ch) {
                    ch.unread = 0;
                    c.refreshBadge();
                }
            }

            function scrollBottom() {
                var b = document.getElementById("fhc-ng-body");
                if (b) {
                    b.scrollTop = b.scrollHeight;
                }
            }
            /* ---------- gửi tin ---------- */
            c.send = function() {
                var t = (c.draft || "").trim();
                if (t === "" || !c.curCh) {
                    return;
                }
                var content = t,
                    i;
                c.mentions.sort(function(a, b) {
                    return b.name.length - a.name.length;
                }); // tên dài trước → tránh "@An" nuốt "@An Nguyen"
                for (i = 0; i < c.mentions.length; i++) {
                    content = content.split("@" + c.mentions[i].name).join("@[" + c.mentions[i].name + "](" + c.mentions[i].pid + ")");
                }
                c.draft = "";
                c.mentions = [];
                c.atOpen = false;
                post("send", {
                    channel_id: c.curCh,
                    content: content,
                    reply_to_id: c.replyTo ? c.replyTo.message_id : 0
                }).then(function(r) {
                    if (r.data && r.data.message) {
                        c.msgs.push(decorate(r.data.message));
                        $timeout(scrollBottom, 20);
                    }
                });
                c.cancelReply();
            };
            c.onKey = function($event) {
                if (c.atOpen) {
                    var f = c.atFiltered();
                    if ($event.which === 40) {
                        $event.preventDefault();
                        if (f.length) {
                            c.atActive = (c.atActive + 1) % f.length;
                            atEnsureVisible();
                        }
                        return;
                    } // ↓
                    if ($event.which === 38) {
                        $event.preventDefault();
                        if (f.length) {
                            c.atActive = (c.atActive - 1 + f.length) % f.length;
                            atEnsureVisible();
                        }
                        return;
                    } // ↑ (cuộn vòng)
                    if ($event.which === 13 || $event.which === 9) {
                        if (f.length) {
                            $event.preventDefault();
                            c.pickMention(f[c.atActive] || f[0]);
                            return;
                        }
                    }
                    if ($event.which === 27) {
                        c.atOpen = false;
                        return;
                    }
                }
                if ($event.which === 13 && !$event.shiftKey) {
                    $event.preventDefault();
                    c.send();
                }
            };
            /* ---------- ảnh / album ---------- */
            c.album = function(m) {
                return (m.images && m.images.length) ? m.images : (m.image ? [m.image] : []);
            };
            c.albumLay = function(m) {
                var n = c.album(m).length;
                return n <= 1 ? "1" : (n === 2 ? "2" : (n === 3 ? "3" : (n === 4 ? "4" : "more")));
            };
            c.onPickFiles = function(input) {
                if (!c.curCh || !input.files || !input.files.length) {
                    input.value = "";
                    return;
                }
                var files = Array.prototype.slice.call(input.files, 0, 9);
                input.value = "";
                var out = new Array(files.length),
                    done = 0,
                    n = files.length;

                function finish() {
                    if (++done === n) {
                        apply(function() {
                            c.sendImages(out.filter(Boolean));
                        });
                    }
                } // vào digest
                files.forEach(function(file, idx) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = new Image();
                        img.onload = function() {
                            var max = 1280,
                                w = img.width,
                                h = img.height;
                            if (w > max) {
                                h = Math.round(h * max / w);
                                w = max;
                            }
                            var cv = document.createElement("canvas");
                            cv.width = w;
                            cv.height = h;
                            cv.getContext("2d").drawImage(img, 0, 0, w, h);
                            out[idx] = cv.toDataURL("image/jpeg", 0.82);
                            finish();
                        };
                        img.onerror = finish;
                        img.src = e.target.result;
                    };
                    reader.onerror = finish;
                    reader.readAsDataURL(file);
                });
            };
            c.sendImages = function(dataUrls) {
                if (c.imgPosting || !dataUrls || !dataUrls.length || !c.curCh) {
                    return;
                }
                c.imgPosting = true;
                var cap = (c.draft || "").trim();
                post("send_image", {
                    channel_id: c.curCh,
                    images: dataUrls,
                    content: cap,
                    reply_to_id: c.replyTo ? c.replyTo.message_id : 0
                }).then(function(r) {
                    c.imgPosting = false;
                    if (r.data && r.data.message) {
                        c.draft = "";
                        c.msgs.push(decorate(r.data.message));
                        $timeout(scrollBottom, 20);
                    }
                }, function() {
                    c.imgPosting = false;
                });
                c.cancelReply();
            };
            /* ---------- quote / trả lời ---------- */
            c.startReply = function(m, $event) {
                if ($event) {
                    $event.stopPropagation();
                }
                var prev = (parseInt(m.type, 10) === 2) ? "[Hình ảnh]" : (m.content || "");
                c.replyTo = {
                    message_id: m.message_id,
                    name: m.name || "Tin gốc",
                    preview: prev.length > 80 ? prev.slice(0, 80) : prev
                };
            };
            c.cancelReply = function() {
                c.replyTo = null;
            };
            c.scrollToMsg = function(mid) { // bấm quote → nhảy tới tin gốc + nháy
                var el = document.getElementById("fhc-m-" + (parseInt(mid, 10) || 0));
                if (!el) {
                    return;
                }
                el.scrollIntoView({
                    block: "center",
                    behavior: "smooth"
                });
                el.classList.add("fhc-flash");
                $timeout(function() {
                    el.classList.remove("fhc-flash");
                }, 1200);
            };
            /* ---------- tìm tin nhắn trong kênh ---------- */
            var _srchTimer = null;

            function doSearch() {
                var q = (c.srch.q || "").trim(),
                    cid = c.curCh;
                if (!q || !(cid > 0)) {
                    c.srch.results = null;
                    c.srch.loading = false;
                    return;
                }
                c.srch.loading = true;
                post("search", {
                    channel_id: cid,
                    q: q
                }).then(function(r) {
                    if (c.curCh !== cid) {
                        return;
                    }
                    c.srch.loading = false;
                    c.srch.results = (r.data && !r.data.error) ? (r.data.list || []) : [];
                }, function() {
                    c.srch.loading = false;
                    c.srch.results = [];
                });
            }
            c.searchToggle = function() {
                c.srch.open = !c.srch.open;
                if (!c.srch.open) {
                    c.srch.q = "";
                    c.srch.results = null;
                } else {
                    $timeout(function() {
                        var i = document.getElementById("fhc-srch-input");
                        if (i) {
                            i.focus();
                        }
                    }, 30);
                }
            };
            c.searchRun = function() {
                if (_srchTimer) {
                    $timeout.cancel(_srchTimer);
                }
                _srchTimer = $timeout(doSearch, 300);
            }; // debounce 300ms
            c.searchGo = function(mid) {
                c.srch.open = false;
                mid = parseInt(mid, 10) || 0;
                if (!mid) {
                    return;
                }
                if (document.getElementById("fhc-m-" + mid)) {
                    $timeout(function() {
                        c.scrollToMsg(mid);
                    }, 30);
                    return;
                } // đã nạp → nhảy
                var cid = c.curCh; // chưa nạp → nạp từ tin đó tới hiện tại rồi nhảy
                post("history", {
                    channel_id: cid,
                    since: mid - 1
                }).then(function(r) {
                    if (c.curCh !== cid || !r.data || r.data.error) {
                        return;
                    }
                    c.msgs = (r.data.list || []).map(decorate);
                    c.hasMoreHistory = true;
                    c.loadingMore = false;
                    if (r.data.pinned !== undefined) {
                        c.pinnedList = r.data.pinned || [];
                    }
                    $timeout(function() {
                        c.scrollToMsg(mid);
                    }, 60);
                });
            };
            /* ---------- lightbox xem ảnh ---------- */
            c.lbOpen = function(m, i) {
                c.lb = {
                    open: true,
                    srcs: c.album(m),
                    idx: parseInt(i, 10) || 0,
                    zoom: false
                };
            };
            c.lbOpenSrc = function(url) {
                if (!url) {
                    return;
                }
                c.lb = {
                    open: true,
                    srcs: [url],
                    idx: 0,
                    zoom: false
                };
            }; // zoom 1 ảnh (vd ảnh check-in)
            c.lbClose = function() {
                c.lb.open = false;
            };
            c.lbNav = function(d, $event) {
                if ($event) {
                    $event.stopPropagation();
                }
                var n = c.lb.srcs.length;
                if (n) {
                    c.lb.idx = (c.lb.idx + d + n) % n;
                    c.lb.zoom = false;
                }
            };
            c.lbZoom = function($event) {
                if ($event) {
                    $event.stopPropagation();
                }
                c.lb.zoom = !c.lb.zoom;
            };
            /* ---------- cảm xúc ---------- */
            c.reactCells = function(m) { // các emoji đang có (thứ tự REACTS) cho badge
                var out = [],
                    r = m.reactions;
                if (!r || !r.total) {
                    return out;
                }
                for (var i = 0; i < REACTS.length; i++) {
                    var k = REACTS[i];
                    if (r.counts && r.counts[k] > 0) {
                        out.push(EMOJI[k]);
                    }
                }
                return out;
            };
            c.openPicker = function(m, $event) {
                if ($event) {
                    $event.stopPropagation();
                }
                closePickers();
                m._picker = true;
            };
            c.openMenu = function(m, $event) {
                if ($event) {
                    $event.stopPropagation();
                }
                var was = m._menu;
                closePickers();
                m._menu = !was;
            }; // toggle dropdown ⋮
            c.closeMenu = function() {
                closePickers();
            };
            c.rx = {
                open: false,
                loading: false,
                total: 0,
                list: [],
                summary: []
            }; // popup "ai đã thả cảm xúc"
            c.openReactors = function(m, $event) { // chạm badge cảm xúc → popup ai đã thả (channel-scope, server-gated)
                if ($event) {
                    $event.stopPropagation();
                }
                if (!m.reactions || !m.reactions.total || !c.curCh) {
                    return;
                }
                closePickers();
                c.rx.open = true;
                c.rx.loading = true;
                c.rx.list = [];
                c.rx.total = m.reactions.total || 0;
                c.rx.summary = rxSummary(m.reactions.counts);
                post("reactors", {
                    channel_id: c.curCh,
                    message_id: m.message_id
                }).then(function(r) {
                    c.rx.loading = false;
                    c.rx.list = (r.data && !r.data.error && r.data.list) ? r.data.list : [];
                }, function() {
                    c.rx.loading = false;
                    c.rx.list = [];
                });
            };
            c.closeReactors = function() {
                c.rx.open = false;
            };

            function rxSummary(counts) { // [{emoji,n}] theo thứ tự REACTS — tính 1 lần lúc mở (tránh infdig do trả ref mới mỗi digest)
                var out = [],
                    cc = counts || {};
                for (var i = 0; i < REACTS.length; i++) {
                    var k = REACTS[i];
                    if (cc[k] > 0) {
                        out.push({
                            emoji: EMOJI[k],
                            n: cc[k]
                        });
                    }
                }
                return out;
            }
            c.react = function(m, code, $event) {
                if ($event) {
                    $event.stopPropagation();
                }
                m._picker = false;
                post("react", {
                    channel_id: c.curCh,
                    message_id: m.message_id,
                    emoji: code
                }).then(function(r) {
                    if (r.data && !r.data.error) {
                        m.reactions = r.data.reactions;
                    }
                });
            };

            function closePickers() {
                for (var i = 0; i < c.msgs.length; i++) {
                    c.msgs[i]._picker = false;
                    c.msgs[i]._menu = false;
                    c.msgs[i]._rlist = false;
                }
            }
            /* ---------- Thu hồi (gỡ tin với MỌI người, 24h, người gửi) ---------- */
            function markRecalled(m) {
                m.recalled = 1;
                m.type = 9;
                m.content = "";
                m.image = "";
                m.images = [];
                m.reactions = {
                    counts: {},
                    mine: null,
                    total: 0
                };
                m._picker = false;
                m._menu = false;
                m._rlist = false;
            }
            c.canRecall = function(m) { // chỉ tin CỦA MÌNH, chưa thu hồi, còn trong 24h kể từ lúc gửi (server vẫn chốt lại)
                if (!m || !m.is_me || m.recalled) {
                    return false;
                }
                var ts = parseInt(m.ts, 10) || 0;
                return ts > 0 && (Math.floor(Date.now() / 1000) - ts) <= 86400;
            };
            c.recall = function(m, $event) {
                if ($event) {
                    $event.stopPropagation();
                }
                if (!c.canRecall(m)) {
                    return;
                }
                if (!window.confirm("Thu hồi tin nhắn này? Mọi người sẽ không xem được nữa.")) {
                    return;
                }
                post("recall", {
                    channel_id: c.curCh,
                    message_id: m.message_id
                }).then(function(r) {
                    if (r.data && !r.data.error) {
                        markRecalled(m);
                    } else {
                        alert(r.data && r.data.message ? r.data.message : "Thu hồi thất bại");
                    }
                }, function() {
                    alert("Lỗi kết nối, thử lại");
                });
            };
            $scope.$on("fhc:closePickers", function() {
                apply(closePickers);
            });
            /* ---------- realtime (tái dùng $Core.socket) ---------- */
            function subscribe() {
                if (!window.$Core || !$Core.socket || !c.chans.length) {
                    return;
                }
                var subs = [];
                for (var i = 0; i < c.chans.length; i++) {
                    subs.push({
                        id: c.chans[i].channel_id,
                        token: c.chans[i].token
                    });
                }
                $Core.socket.emit("chat:subscribe", {
                    profile_id: ME,
                    channels: subs
                });
            }

            function findChan(cid) {
                for (var i = 0; i < c.chans.length; i++) {
                    if (c.chans[i].channel_id == cid) {
                        return c.chans[i];
                    }
                }
                return null;
            }

            function onIncoming(cid, m) {
                if (!m || !(cid > 0)) {
                    return;
                }
                decorate(m); // set is_me + parse mention (_memMe)
                if (c.curCh === cid && c.view === "thread") {
                    for (var i = 0; i < c.msgs.length; i++) {
                        if (c.msgs[i].message_id == m.message_id) {
                            return;
                        }
                    } // dedup → bỏ frame trùng (không báo lại)
                    c.msgs.push(m);
                    markRead(cid);
                    $timeout(scrollBottom, 20);
                    if (!m.is_me && (m._memMe || !chanMuted(cid))) {
                        c.notifyMsg(m, cid);
                    } // báo khi tin mới (mention vượt mute)
                } else if (!m.is_me) {
                    var ch = findChan(cid);
                    if (ch) {
                        ch.unread = (parseInt(ch.unread, 10) || 0) + 1;
                        ch.last_msg = (parseInt(m.type, 10) === 2) ? "[Hình ảnh]" : (m.content || "");
                        ch.last_at = m.time || ch.last_at || "";
                        ch.last_ts = Math.floor(new Date().getTime() / 1000);
                        ch.last_sender = (m.name || "").split(" ").pop() || "";
                        c.refreshBadge();
                        if (m._memMe || !chanMuted(cid)) {
                            c.notifyMsg(m, cid);
                        } // báo tin kênh khác (mention vượt mute)
                    }
                }
            }

            function onReaction(cid, d) {
                if (!d || c.curCh !== parseInt(cid, 10)) {
                    return;
                }
                for (var i = 0; i < c.msgs.length; i++) {
                    if (c.msgs[i].message_id == d.message_id) {
                        var mine = c.msgs[i].reactions ? c.msgs[i].reactions.mine : null;
                        c.msgs[i].reactions = {
                            counts: d.counts || {},
                            mine: mine,
                            total: parseInt(d.total, 10) || 0
                        };
                        break;
                    }
                }
            }

            function onRecall(cid, d) { // realtime: 1 tin bị thu hồi → đánh dấu tại chỗ (mọi client đang mở kênh)
                if (!d || c.curCh !== parseInt(cid, 10)) {
                    return;
                }
                for (var i = 0; i < c.msgs.length; i++) {
                    if (c.msgs[i].message_id == d.message_id) {
                        markRecalled(c.msgs[i]);
                        break;
                    }
                }
            }
            /* ---------- Báo tin mới: âm thanh (Web Audio beep) + thông báo trình duyệt (Notification API) ---------- */
            function ensureAudio() { // tạo/đánh thức AudioContext TRONG cử chỉ mở chat (autoplay policy chặn nếu tạo lúc nhận tin)
                try {
                    var Ctx = window.AudioContext || window.webkitAudioContext;
                    if (!Ctx) {
                        return;
                    }
                    if (!c._actx) {
                        c._actx = new Ctx();
                    }
                    if (c._actx.state === "suspended") {
                        c._actx.resume();
                    }
                } catch (e) {}
            }

            function ensureNotifyPerm() { // xin quyền thông báo (lần đầu mở chat = cử chỉ hợp lệ)
                try {
                    if (("Notification" in window) && Notification.permission === "default") {
                        Notification.requestPermission();
                    }
                } catch (e) {}
            }

            function playPing() {
                try {
                    if (!c._actx) {
                        return;
                    }
                    var ctx = c._actx,
                        t = ctx.currentTime,
                        o = ctx.createOscillator(),
                        g = ctx.createGain();
                    o.type = "sine";
                    o.frequency.setValueAtTime(880, t);
                    o.frequency.setValueAtTime(660, t + 0.08);
                    g.gain.setValueAtTime(0.0001, t);
                    g.gain.exponentialRampToValueAtTime(0.16, t + 0.012);
                    g.gain.exponentialRampToValueAtTime(0.0001, t + 0.25);
                    o.connect(g);
                    g.connect(ctx.destination);
                    o.start(t);
                    o.stop(t + 0.26);
                } catch (e) {}
            }

            function browserNotify(m, cid) {
                try {
                    if (!("Notification" in window) || !window.isSecureContext || Notification.permission !== "granted") {
                        return;
                    }
                    if (c.open && c.curCh === cid && c.view === "thread" && !document.hidden) {
                        return;
                    } // đang xem đúng kênh đó → khỏi báo
                    var body = (parseInt(m.type, 10) === 2) ? "[Hình ảnh]" : (m.content || "");
                    var n = new Notification(m.name || "Tin nhắn mới", {
                        body: body,
                        tag: "fhchat-" + cid,
                        icon: m.avatar || undefined
                    });
                    n.onclick = function() {
                        window.focus();
                        try {
                            n.close();
                        } catch (e) {}
                    };
                } catch (e) {}
            }
            c.notifyMsg = function(m, cid) {
                if (!m || m.is_me) {
                    return;
                }
                playPing();
                browserNotify(m, cid);
            };
            c.notifyNeedsEnable = function() {
                return ("Notification" in window) && window.isSecureContext && Notification.permission !== "granted";
            };
            c.askNotify = function() {
                try {
                    if (!("Notification" in window)) {
                        window.alert("Trình duyệt không hỗ trợ thông báo.");
                        return;
                    }
                    if (!window.isSecureContext) {
                        window.alert("Cần mở trang bằng HTTPS để bật thông báo.");
                        return;
                    }
                    if (Notification.permission === "denied") {
                        window.alert("Thông báo đang bị chặn. Bấm biểu tượng khoá trên thanh địa chỉ → Thông báo → Cho phép, rồi tải lại trang.");
                        return;
                    }
                    Notification.requestPermission().then(function() {
                        ensureAudio();
                    });
                } catch (e) {}
            };
            /* ---------- Ghim TIN nhắn (banner đầu thread; any member; realtime) ---------- */
            c.isPinned = function(mid) {
                for (var i = 0; i < c.pinnedList.length; i++) {
                    if (c.pinnedList[i].message_id == mid) {
                        return true;
                    }
                }
                return false;
            };
            c.pinMessage = function(m, $event) { // toggle ghim 1 tin (tối đa 5/kênh — server chốt)
                if ($event) {
                    $event.stopPropagation();
                }
                if (!m || m.recalled || !c.curCh) {
                    return;
                }
                var pin = c.isPinned(m.message_id) ? 0 : 1;
                post("pin_message", {
                    channel_id: c.curCh,
                    message_id: m.message_id,
                    pin: pin
                }).then(function(r) {
                    if (r.data && !r.data.error) {
                        c.pinnedList = r.data.pinned || [];
                    } else {
                        alert(r.data && r.data.message ? r.data.message : "Ghim thất bại");
                    }
                });
            };
            c.unpinOne = function(mid, $event) { // bỏ ghim 1 tin cụ thể (từ danh sách ghim)
                if ($event) {
                    $event.stopPropagation();
                }
                if (!c.curCh || !mid) {
                    return;
                }
                post("pin_message", {
                    channel_id: c.curCh,
                    message_id: mid,
                    pin: 0
                }).then(function(r) {
                    if (r.data && !r.data.error) {
                        c.pinnedList = r.data.pinned || [];
                    }
                });
            };

            function onPinMsg(d) { // realtime: ghim/bỏ ghim → cập nhật banner mọi client đang mở kênh
                if (!d || c.curCh !== parseInt(d.channel_id, 10)) {
                    return;
                }
                c.pinnedList = d.pinned || [];
            }
            var _typingSentT = 0,
                _typingTimer = null;

            function setTyping(name) { // nguồn-chân-lý hiển thị 'đang soạn' (poll + socket cùng gọi): set + hẹn tự ẩn
                tlog("setTyping →", name || "(ẩn)");
                c.typingName = name || "";
                if (_typingTimer) {
                    clearTimeout(_typingTimer);
                    _typingTimer = null;
                }
                if (c.typingName) {
                    _typingTimer = setTimeout(function() {
                        apply(function() {
                            c.typingName = "";
                        });
                    }, 12000);
                } // an toàn nếu poll ngắt
            }

            function typingLabel(names) { // 1→tên; 2→"A, B"; ≥3→"A và N người"
                if (!names || !names.length) {
                    return "";
                }
                if (names.length === 1) {
                    return names[0];
                }
                if (names.length === 2) {
                    return names[0] + ", " + names[1];
                }
                return names[0] + " và " + (names.length - 1) + " người";
            }

            function emitTyping() { // gõ → báo 'đang soạn' (throttle ~2s): socket tức-thì (khi Node có) + Redis fallback (poll bù)
                if (!(c.curCh > 0)) {
                    tlog("emit BỎ: chưa mở kênh");
                    return;
                }
                var now = new Date().getTime();
                if (now - _typingSentT < 2000) {
                    tlog("emit throttle (<2s, bỏ)");
                    return;
                }
                _typingSentT = now;
                var nm = (typeof logdedUser !== "undefined" && logdedUser) ? logdedUser.full_name : "";
                var hasSock = !!(window.$Core && $Core.socket);
                tlog("emit → cid=" + c.curCh + " name=" + nm + " socket=" + hasSock + " connected=" + (hasSock && $Core.socket.connected));
                if (hasSock) {
                    $Core.socket.emit("chat:typing", {
                        channel_id: c.curCh,
                        profile_id: ME,
                        name: nm
                    });
                }
                post("typing", {
                    channel_id: c.curCh
                }).then(function(r) {
                    tlog("POST typing OK", r.data);
                }, function(e) {
                    tlog("POST typing LỖI status=", e && e.status);
                });
            }

            function showTyping(d) { // socket: hiện NGAY (poll giữ/ẩn tiếp theo Redis)
                if (!d || parseInt(d.channel_id, 10) !== c.curCh || parseInt(d.profile_id, 10) === ME) {
                    tlog("showTyping BỎ (kênh khác / tin của mình)", d);
                    return;
                }
                setTyping(d.name ? d.name : "Ai đó");
            }

            function bindSocket() {
                if (!window.$Core || !$Core.socket || c._sockBound) {
                    return;
                }
                c._sockBound = true;
                $Core.socket.on("chat:message", function(p) {
                    apply(function() {
                        if (p && p.message && p.message.reaction) {
                            onReaction(p.channel_id, p.message.reaction);
                        } else if (p && p.message && p.message.recall) {
                            onRecall(parseInt(p.channel_id, 10), p.message.recall);
                        } else if (p && p.message && p.message.pin) {
                            onPinMsg(p.message.pin);
                        } else if (p) {
                            onIncoming(parseInt(p.channel_id, 10), p.message);
                        }
                    });
                });
                $Core.socket.on("chat:kick", function(d) {
                    apply(function() {
                        if (!d || parseInt(d.profile_id, 10) !== ME && parseInt(d.profile_id, 10) !== 0) {
                            return;
                        }
                        var kc = parseInt(d.channel_id, 10);
                        for (var i = c.chans.length - 1; i >= 0; i--) {
                            if (c.chans[i].channel_id == kc) {
                                c.chans.splice(i, 1);
                            }
                        }
                        if (c.curCh === kc) {
                            c.backToList();
                        }
                        c.refreshBadge();
                    });
                });
                $Core.socket.on("chat:typing", function(d) {
                    tlog("socket NHẬN chat:typing", d);
                    apply(function() {
                        showTyping(d);
                    });
                });
                $Core.socket.on("connect", function() {
                    apply(subscribe);
                });
            }
            /* ---------- poll fallback (socket rớt / Node chưa deploy → vẫn nhận tin, trễ ≤ ~5s) ---------- */
            function hasMsg(id) {
                for (var i = 0; i < c.msgs.length; i++) {
                    if (c.msgs[i].message_id == id) {
                        return true;
                    }
                }
                return false;
            }

            function pollThread(cid) {
                var since = 0;
                for (var i = 0; i < c.msgs.length; i++) {
                    since = Math.max(since, parseInt(c.msgs[i].message_id, 10) || 0);
                }
                post("history", {
                    channel_id: cid,
                    since: since
                }).then(function(r) {
                    if (c.curCh !== cid || !r.data || r.data.error) {
                        return;
                    } // đã đổi kênh / lỗi → bỏ
                    var list = r.data.list || [],
                        added = false;
                    for (var j = 0; j < list.length; j++) {
                        var m = decorate(list[j]);
                        if (hasMsg(m.message_id)) {
                            continue;
                        } // dedup với socket/append trước
                        c.msgs.push(m);
                        added = true;
                        if (!m.is_me && (m._memMe || !chanMuted(cid))) {
                            c.notifyMsg(m, cid);
                        }
                    }
                    if (r.data.pinned !== undefined) {
                        c.pinnedList = r.data.pinned || [];
                    } // đồng bộ ghim
                    if (r.data.seen !== undefined) {
                        c.seenList = r.data.seen || [];
                    } // đồng bộ đã-xem
                    if (added) {
                        markRead(cid);
                        $timeout(scrollBottom, 20);
                    }
                }, function() {
                    /* poll lỗi → bỏ qua, tick sau thử lại */ });
            }
            var _pollN = 0,
                _seenTs = {},
                _revs = {},
                _seenRevs = {};

            function notifyFromChannels(list) { // so mốc tin cuối mỗi kênh → BÁO tin mới từ người khác (chạy cả khi tab ẩn / kênh khác)
                for (var i = 0; i < list.length; i++) {
                    var ch = list[i],
                        cid = parseInt(ch.channel_id, 10),
                        ts = parseInt(ch.last_ts, 10) || 0,
                        seen = _seenTs[cid];
                    _seenTs[cid] = ts;
                    if (seen === undefined || ts <= seen) {
                        continue;
                    } // lần đầu thấy kênh / không có tin mới → bỏ (tránh spam lúc nạp)
                    if (!ch.last_sender || ch.last_sender === "Bạn") {
                        continue;
                    } // tin của mình → bỏ
                    if (parseInt(ch.muted, 10)) {
                        continue;
                    } // kênh tắt thông báo → bỏ chuông/notify
                    if (c.open && c.curCh === cid && c.view === "thread" && !document.hidden) {
                        continue;
                    } // đang xem kênh đó → pollThread lo (khỏi báo)
                    c.notifyMsg({
                        name: ch.last_sender,
                        content: ch.last_msg,
                        type: 1
                    }, cid);
                }
            }

            function pollChannels() {
                $http.get(url("channels")).then(function(r) {
                    if (!r.data || r.data.error) {
                        return;
                    }
                    var list = r.data.channels || [];
                    notifyFromChannels(list);
                    c.chans = list;
                    c.refreshBadge();
                    if (window.$Core && $Core.socket) {
                        subscribe();
                    }
                }, function() {});
            }

            function pollPing() { // poll-nhẹ qua REDIS: gửi cids đang theo dõi → chỉ fetch DB kênh có rev đổi
                if (!c.chans.length) {
                    return;
                }
                var cids = [];
                for (var i = 0; i < c.chans.length; i++) {
                    cids.push(c.chans[i].channel_id);
                }
                post("ping", {
                    cids: cids,
                    open: (c.open && c.tab === "chat" && c.view === "thread" && c.curCh > 0) ? c.curCh : 0
                }).then(function(r) {
                    if (!r.data || r.data.error) {
                        return;
                    }
                    var revs = r.data.revs || {},
                        listDirty = false,
                        threadDirty = false,
                        cid;
                    for (cid in revs) {
                        if (!revs.hasOwnProperty(cid)) {
                            continue;
                        }
                        if (revs[cid] !== _revs[cid]) {
                            _revs[cid] = revs[cid];
                            listDirty = true;
                            if (parseInt(cid, 10) === c.curCh) {
                                threadDirty = true;
                            }
                        }
                    }
                    var srevs = r.data.seenrevs || {}; // seenrev đổi (có người đọc) → chỉ làm mới THREAD đang mở cho 'đã xem', KHÔNG kéo list
                    if (c.curCh > 0 && srevs[c.curCh] !== undefined && srevs[c.curCh] !== _seenRevs[c.curCh]) {
                        _seenRevs[c.curCh] = srevs[c.curCh];
                        threadDirty = true;
                    }
                    if (c.open && c.tab === "chat" && c.view === "thread" && c.curCh > 0) {
                        tlog("poll typing field=", r.data.typing);
                        setTyping(typingLabel(r.data.typing || []));
                    } // 'đang soạn' từ Redis (kênh mở)
                    if (threadDirty && c.open && c.tab === "chat" && c.view === "thread" && c.curCh > 0) {
                        pollThread(c.curCh);
                    }
                    if (listDirty) {
                        pollChannels();
                    } // có kênh đổi → làm mới badge/list (+ thông báo)
                }, function() {});
            }

            function pollTick() {
                _pollN++;
                var hidden = document.hidden;
                if (_pollN % 12 === 0 || !c.chans.length) { // catch-all full-refresh ~60s (bắt kênh MỚI + lỡ bump + Redis down)
                    pollChannels();
                    if (!hidden && c.open && c.tab === "chat" && c.view === "thread" && c.curCh > 0) {
                        pollThread(c.curCh);
                    }
                    return;
                }
                if (hidden && _pollN % 3 !== 0) {
                    return;
                } // tab ẩn → thưa (~15s)
                pollPing(); // các tick còn lại: ping Redis-nhẹ, KHÔNG đụng DB nếu rev không đổi
            }

            function startPoll() {
                if (!c._pollTimer) {
                    c._pollTimer = $interval(pollTick, 5000);
                }
            }
            /* ---------- init ---------- */
            c.loadChannels();
            bindSocket();
            startPoll();
            $timeout(function() { // cuộn gần đỉnh thread → tự tải tin cũ hơn
                var sb = document.getElementById("fhc-ng-body");
                if (sb) {
                    sb.addEventListener("scroll", function() {
                        if (sb.scrollTop <= 60 && c.open && c.tab === "chat" && c.view === "thread" && c.hasMoreHistory && !c.loadingMore) {
                            apply(c.loadMore);
                        }
                    });
                }
            }, 0);
            document.addEventListener("click", function() { // click ngoài → đóng mọi popup (picker/menu tin + menu hội thoại)
                var i, hit = false;
                for (i = 0; i < c.msgs.length; i++) {
                    if (c.msgs[i]._picker || c.msgs[i]._menu || c.msgs[i]._rlist) {
                        hit = true;
                        break;
                    }
                }
                if (!hit) {
                    for (i = 0; i < c.chans.length; i++) {
                        if (c.chans[i]._cmenu) {
                            hit = true;
                            break;
                        }
                    }
                }
                if (hit) {
                    apply(function() {
                        closePickers();
                        closeConvMenus();
                    });
                }
            });
        }]);
        /* <input type=file> không bind ng-model được → directive bắt 'change', eval expr với $input. */
        app.directive("fhcFile", function() {
            return {
                link: function(scope, el, attrs) {
                    el.on("change", function() {
                        scope.$apply(function() {
                            scope.$eval(attrs.fhcFile, {
                                $input: el[0]
                            });
                        });
                    });
                }
            };
        });
        /* Ô gõ tự cao dần theo nội dung (tối đa 92px → rồi cuộn); reset 1 dòng khi draft rỗng (sau gửi).
           Chỉ chạm style.height (DOM) nên KHÔNG cần $apply ở handler 'input'. */
        app.directive("fhcAutogrow", function() {
            return {
                link: function(scope, el, attrs) {
                    var node = el[0],
                        CAP = 92;

                    function grow() {
                        node.style.height = "auto";
                        node.style.height = Math.min(node.scrollHeight, CAP) + "px";
                    }
                    el.on("input", grow);
                    if (attrs.ngModel) {
                        scope.$watch(attrs.ngModel, function(v) {
                            if (!v) {
                                node.style.height = "auto";
                            }
                        }); // gửi xong → về 1 dòng
                    }
                }
            };
        });
        /* Manual bootstrap trên #fhchat (KHÔNG dùng ng-app) → coexist với app Angular khác cùng trang (vd worldcup auto-bootstrap). */
        var el = document.getElementById("fhchat");
        if (el && !angular.element(el).injector()) {
            try {
                angular.bootstrap(el, ["fhChat"]);
            } catch (e) {
                if (window.console) {
                    console.warn("fhChat bootstrap fail", e);
                }
            }
        }
    }
    /* Chạy boot khi DOM sẵn sàng (lúc đó angular — dù bạn nạp ở chỗ khác — đã có nếu là <script> đồng bộ). */
    if (document.readyState !== "loading") {
        boot();
    } else {
        document.addEventListener("DOMContentLoaded", boot);
    }
})();