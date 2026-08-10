<div class="card bg-main">
	<div class="card-header d-flex align-items-center justify-content-between">
		<h5 class="card-title text-white m-0">
			<i class='bx bx-bell'></i>
			<span>Lịch ghi chú</span>
		</h5>
		{if $clsISO->checkPermission("view_all_course")}
		<a href="{$clsISO->getLink('course')}" class="text-white text-decoration-underline" title="Xem tất cả">Xem tất cả</a>
		{/if}
	</div>
	<div class="card-body mt-0">
		<div class="note_calendar position-relative">
			<div class="calendar">
				<div class="header">
					<button id="prev" class="btn btn-outline-default"><i class='bx bx-chevron-left'></i></button>
					<div id="text_month" style="font-weight:700"></div>
					<button id="next" class="btn btn-outline-default"><i class='bx bx-chevron-right'></i></button>
				</div>
				<div class="weekdays">
					<div class="weekday">CN</div>
					<div class="weekday">T2</div>
					<div class="weekday">T3</div>
					<div class="weekday">T4</div>
					<div class="weekday">T5</div>
					<div class="weekday">T6</div>
					<div class="weekday">T7</div>
				</div>
				<div class="grid" id="load_calendar"></div>
			</div>
			<div id="tooltip" class="tooltip-custom" aria-hidden="true"></div>
		</div>
	</div>
</div>
{literal}
<style>
  :root{--bg:#f8fafc;--card:#fff;--accent:#2563eb;--muted:#6b7280;--note:#16a34a}
  *{box-sizing:border-box}
  body{margin:0;font-family:Inter,system-ui,"Segoe UI",Roboto,Arial,sans-serif;background:var(--bg);color:#0b2430;padding:18px}
  .note_calendar .wrap{max-width:900px;margin:0 auto}
  h1{text-align:center;margin:6px 0 14px;font-weight:700}
  .note_calendar .calendar{background:var(--card);border-radius:12px;padding:12px;box-shadow:0 8px 30px rgba(2,6,23,0.06)}
  .note_calendar .header{display:flex;gap:10px;align-items:center;justify-content:center;margin-bottom:10px}
  .note_calendar .weekdays{display:grid;grid-template-columns:repeat(7,1fr);gap:6px;margin-bottom:8px}
  .note_calendar .weekday{text-align:center;font-weight:700;color:var(--muted);padding:8px 6px;border-radius:6px;background:#f1f5f9}
  .note_calendar .grid{display:grid;grid-template-columns:repeat(7,1fr);gap:8px}
  .note_calendar .cell{background:transparent;border-radius:10px;aspect-ratio:1/1;padding:8px;border:1px solid transparent;display:flex;flex-direction:column;justify-content:flex-start;align-items:flex-start;position:relative;overflow:hidden;cursor:pointer}
  .note_calendar .cell.box{background:var(--card);border:1px solid #eef2f7;position-relative}
  .note_calendar .cell.other{opacity:.45}
  .note_calendar .cell.today{outline:3px solid rgba(37,99,235,0.10);border-color:var(--accent)}
  .note_calendar .solar{font-weight:700;font-size:16px}
  .note_calendar .lunar{font-size:12px;color:var(--muted);margin-top:6px}
  .note_calendar .badge{position:absolute;right: 2px;bottom:8px;background:var(--note);color:#fff;border-radius:12px;padding: 3px;font-size: 10px;aspect-ratio: 1 / 1;min-height: 16px;line-height: 100%;}
  .note_calendar .info-icon{font-size:14px;color:inherit;cursor:pointer;position:absolute;right:5px}
 .note_calendar .tooltip-custom{position:absolute;z-index:1100;background:rgba(0,0,0,.82);color:#fff;padding:6px 8px;border-radius:6px;font-size:13px;display:none;white-space:nowrap}
  @media(max-width:800px){ .note_calendar .cell{min-height:72px;padding:6px} .note_calendar .solar{font-size:14px} .note_calendar .lunar{font-size:11px} }
  @media(max-width:480px){ .note_calendar .grid{gap:6px} .note_calendar .cell{min-height:64px;padding:6px} }
</style>
<script>
	function INT(d) {
		return Math.floor(d);
	}

	function jdFromDate(dd, mm, yy) {
		var a = INT((14 - mm) / 12);
		var y = yy + 4800 - a;
		var m = mm + 12 * a - 3;
		var jd = dd + INT((153 * m + 2) / 5) + 365 * y + INT(y / 4) - INT(y / 100) + INT(y / 400) - 32045;
		if (jd < 2299161) jd = dd + INT((153 * m + 2) / 5) + 365 * y + INT(y / 4) - 32083;
		return jd;
	}

	function getNewMoonDay(k, timeZone) {
		var T = k / 1236.85,
			T2 = T * T,
			T3 = T2 * T,
			dr = Math.PI / 180;
		var Jd1 = 2415020.75933 + 29.53058868 * k + 0.0001178 * T2 - 0.000000155 * T3;
		Jd1 += 0.00033 * Math.sin((166.56 + 132.87 * T - 0.009173 * T2) * dr);
		var M = 359.2242 + 29.10535608 * k - 0.0000333 * T2 - 0.00000347 * T3;
		var Mpr = 306.0253 + 385.81691806 * k + 0.0107306 * T2 + 0.00001236 * T3;
		var F = 21.2964 + 390.67050646 * k - 0.0016528 * T2 - 0.00000239 * T3;
		var C1 = (0.1734 - 0.000393 * T) * Math.sin(M * dr) + 0.0021 * Math.sin(2 * dr * M) - 0.4068 * Math.sin(Mpr * dr) +
			0.0161 * Math.sin(2 * dr * Mpr) - 0.0004 * Math.sin(3 * dr * Mpr) + 0.0104 * Math.sin(2 * dr * F) -
			0.0051 * Math.sin((M + Mpr) * dr) - 0.0074 * Math.sin((M - Mpr) * dr) + 0.0004 * Math.sin((2 * F + M) * dr) -
			0.0004 * Math.sin((2 * F - M) * dr) - 0.0006 * Math.sin((2 * F + Mpr) * dr) + 0.0010 * Math.sin((2 * F - Mpr) * dr) +
			0.0005 * Math.sin((2 * Mpr + M) * dr);
		var deltaT = (T < -11) ? (0.001 + 0.000839 * T + 0.0002261 * T2 - 0.00000845 * T3 - 0.000000081 * T * T3) :
			(-0.000278 + 0.000265 * T + 0.000262 * T2);
		var JdNew = Jd1 + C1 - deltaT;
		return INT(JdNew + 0.5 + timeZone / 24);
	}

	function getSunLongitude(jdn, timeZone) {
		var T = (jdn - 2451545.5 - timeZone / 24) / 36525,
			T2 = T * T,
			dr = Math.PI / 180;
		var M = 357.52910 + 35999.05030 * T - 0.0001559 * T2 - 0.00000048 * T * T2;
		var L0 = 280.46645 + 36000.76983 * T + 0.0003032 * T2;
		var DL = (1.914600 - 0.004817 * T - 0.000014 * T2) * Math.sin(dr * M) + (0.019993 - 0.000101 * T) * Math.sin(dr * 2 * M) + 0.000290 * Math.sin(dr * 3 * M);
		var L = L0 + DL;
		L = L * dr;
		L = L - Math.PI * 2 * (INT(L / (Math.PI * 2)));
		return INT(L / Math.PI * 6);
	}

	function getLunarMonth11(yy, timeZone) {
		var off = jdFromDate(31, 12, yy) - 2415021;
		var k = INT(off / 29.530588853);
		var nm = getNewMoonDay(k, timeZone);
		var sunLong = getSunLongitude(nm, timeZone);
		if (sunLong >= 9) nm = getNewMoonDay(k - 1, timeZone);
		return nm;
	}

	function getLeapMonthOffset(a11, timeZone) {
		var k = INT((a11 - 2415021.076998695) / 29.530588853 + 0.5);
		var last = 0,
			i = 1;
		var arc = getSunLongitude(getNewMoonDay(k + i, timeZone), timeZone);
		do {
			last = arc;
			i++;
			arc = getSunLongitude(getNewMoonDay(k + i, timeZone), timeZone);
		} while (arc != last && i < 14);
		return i - 1;
	}

	function convertSolar2Lunar(dd, mm, yy, timeZone) {
		var dayNumber = jdFromDate(dd, mm, yy);
		var k = INT((dayNumber - 2415021.076998695) / 29.530588853);
		var monthStart = getNewMoonDay(k + 1, timeZone);
		if (monthStart > dayNumber) monthStart = getNewMoonDay(k, timeZone);
		var a11 = getLunarMonth11(yy, timeZone);
		var b11 = a11;
		var lunarYear;
		if (a11 >= monthStart) {
			lunarYear = yy;
			a11 = getLunarMonth11(yy - 1, timeZone);
		} else {
			lunarYear = yy + 1;
			b11 = getLunarMonth11(yy + 1, timeZone);
		}
		var lunarDay = dayNumber - monthStart + 1;
		var diff = INT((monthStart - a11) / 29);
		var lunarLeap = 0;
		var lunarMonth = diff + 11;
		if (b11 - a11 > 365) {
			var leapDiff = getLeapMonthOffset(a11, timeZone);
			if (diff >= leapDiff) {
				lunarMonth = diff + 10;
				if (diff == leapDiff) lunarLeap = 1;
			}
		}
		if (lunarMonth > 12) lunarMonth -= 12;
		if (lunarMonth >= 11 && diff < 4) lunarYear -= 1;
		return {
			day: lunarDay,
			month: lunarMonth,
			year: lunarYear,
			leap: lunarLeap
		};
	}

	/* ========== Demo notes (fake) ========== */
	const fakeNotes = {
		"2025-11-04": 2,
		"2025-11-10": 1,
		"2025-11-15": 3,
		"2025-12-15": 3
	};

	/* ========== Ngày đặc biệt (MM-DD or DD-MM-AL keys) ========== */
	const special = {
		"01-01": {
			name: "Tết Dương Lịch",
			color: "#ef4444"
		},
		"30-04": {
			name: "Giải phóng miền Nam",
			color: "#fb923c"
		},
		"01-05": {
			name: "Quốc tế Lao động",
			color: "#f59e0b",
			textColor: "#000"
		},
		"02-09": {
			name: "Quốc khánh",
			color: "#2563eb"
		},
		"20-11": {
			name: "Ngày Nhà giáo Việt Nam",
			color: "#7c3aed"
		},
		// lunar keys example: "01-01-AL" means âm lịch 01/01
		"01-01-AL": {
			name: "Tết Nguyên Đán (Mùng 1)",
			color: "#f97316"
		}
	};

	/* ========== Main render logic ========== */
	let view = new Date();
	view.setDate(1);
	view.setHours(0, 0, 0, 0);
	const today = new Date();
	today.setHours(0, 0, 0, 0);

	

	/* navigation */
	$('#prev').on('click', function() {
		view.setMonth(view.getMonth() - 1);
		$Core.note_calendar.renderCalendar();
	});
	$('#next').on('click', function() {
		view.setMonth(view.getMonth() + 1);
		$Core.note_calendar.renderCalendar();
	});

	/* initial render */
	$(function() {
		$Core.note_calendar.renderCalendar();
	});
	
	$Core.note_calendar = {
		renderCalendar : function(){
			const grid = $('#load_calendar').empty();
			const year = view.getFullYear(),
				month = view.getMonth();
			$('#text_month').text(`Tháng ${month+1} / ${year}`);

			const first = new Date(year, month, 1);
			const firstWeekday = first.getDay();
			const daysInMonth = new Date(year, month + 1, 0).getDate();
			const daysInPrev = new Date(year, month, 0).getDate();

			const TOTAL = 42;
			const cells = [];

			for (let i = firstWeekday - 1; i >= 0; i--) {
				const d = daysInPrev - i;
				cells.push({
					date: new Date(year, month - 1, d),
					other: true
				});
			}
			for (let d = 1; d <= daysInMonth; d++) cells.push({
				date: new Date(year, month, d),
				other: false
			});
			let nd = 1;
			while (cells.length < TOTAL) cells.push({
				date: new Date(year, month + 1, nd++),
				other: true
			});

			cells.forEach(item => {
				const dt = item.date;
				dt.setHours(0, 0, 0, 0);
				const key = $Core.note_calendar.ymd(dt);
				const solar = dt.getDate();
				const lunObj = convertSolar2Lunar(solar, dt.getMonth() + 1, dt.getFullYear(), 7);
				const lunarText = lunObj.day + '/' + lunObj.month + (lunObj.leap ? ' (N)' : '');
				
				console.log(key,convertSolar2Lunar);
				const $cell = $('<div class="cell box" onclick="$Core.note_calendar.open_day(this,event)" data-date="'+key+'" data-lunarText="'+lunarText+'" ></div>');
				if (item.other) $cell.addClass('other');
				if (dt.getTime() === today.getTime()) $cell.addClass('today');
				else if (dt < today) $cell.addClass('past');

				$cell.append(`<div class="solar">${solar}</div>`);
				$cell.append(`<div class="lunar fs-9">${lunarText} ÂL</div>`);

				// notes badge
				if (fakeNotes[key]) $cell.append(`<div class="badge">${fakeNotes[key]}</div>`);

				// check special: priority solar MM-DD then lunar DD-MM-AL
				const sKeySolar = `${$Core.note_calendar.pad(solar)}-${$Core.note_calendar.pad(dt.getMonth()+1)}`;
				const sKeyLunar = $Core.note_calendar.ddmmAL(lunObj);
				let spec = special[sKeySolar] || special[sKeyLunar];

				if (spec) {
					// apply background color to whole cell; set text color if provided
					/*$cell.css('background', spec.color);*/
					if (spec.textColor) $cell.css('color', spec.textColor);

					// append info icon
					const $icon = $(`<span class="info-icon" aria-hidden="true" data-bs-toggle="tooltip" data-bs-trigger="hover focus" data-bs-placement="top" title="`+spec.name+`" data-bs-original-title="`+spec.name+`" ><i class='bx bx-info-circle' ></i></span>`);

					$cell.append($icon);
				}

				$('#load_calendar').append($cell);
				$('[data-bs-toggle="tooltip"]').tooltip('dispose').tooltip();
			});
		},
		pad: function (n) {
			return String(n).padStart(2, '0');
		},
		ymd: function (d) {
			return d.getFullYear() + '-' + $Core.note_calendar.pad(d.getMonth() + 1) + '-' + $Core.note_calendar.pad(d.getDate());
		},
		ddmmAL: function (lun) {
			return String(lun.day).padStart(2, '0') + '-' + String(lun.month).padStart(2, '0') + '-AL';
		},
		open_day : function(_this,e){
			e.preventDefault();
			var date=$(_this).data("date"),
				lunartext=$(_this).data("lunartext");
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod=ajax&sub=helper&act=open_day', {
				'date' : date,
				'lunartext'	: lunartext
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			},"json");
		},
		open_note : function(_this,e){
			e.preventDefault();
			var date=$(_this).data("date"),
				lunarText=$(_this).data("lunarText");
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/index.php?mod=ajax&sub=helper&act=open_note', {
				'date' : date,'lunarText':lunarText
			}, function(respJson){
				$Core.util.toggleIndicatior(0);
				$Core.popup.open('auto','auto', respJson.html, respJson.uid);
			},"json");
		}
	}
</script>
{/literal}

