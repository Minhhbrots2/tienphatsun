(function($, window){
	"use strict";
	const FREEZE_FIXED_NS = ".freezeFixedTable";
	const FREEZE_FIXED_KEY = "freeze_fixed_table_opts";
	const FREEZE_FIXED_ID = "freeze_fixed_table_id";
	const FREEZE_FIXED_DEFAULTS = {
		fixedLeft: 2,
		fixedRight: 0,
		minWidth: 768,
		tableSelector: "table"
	};
	const _uid = () => ("crmft_" + Math.random().toString(36).slice(2, 10));
	const _clear = ($wrap) => {
		$wrap.removeClass("freeze-fixed-table-enabled freeze-fixed-table-scrollable freeze-fixed-table-scrolled freeze-fixed-table-end");
		$wrap.find(".freeze-fixed-col").each((_i, el) => {
			$(el)
				.removeClass("freeze-fixed-col freeze-fixed-left freeze-fixed-right")
				.css("--freeze-fixed-left", "")
				.css("--freeze-fixed-right", "")
				.css("width", "")
				.css("min-width", "")
				.css("max-width", "");
		});
	};
	const _syncState = ($wrap) => {
		if(!$wrap.length || !$wrap[0]){
			return;
		}
		const dom = $wrap[0];
		const maxScroll = Math.max(0, dom.scrollWidth - dom.clientWidth);
		$wrap.toggleClass("freeze-fixed-table-scrollable", maxScroll > 0);
		$wrap.toggleClass("freeze-fixed-table-scrolled", dom.scrollLeft > 0);
		$wrap.toggleClass("freeze-fixed-table-end", dom.scrollLeft >= (maxScroll - 1));
	};
	const _colWidth = ($table, idx) => {
		let width = 0;
		$table.find("tr").each((_i, row) => {
			const $cell = $(row).children().eq(idx - 1);
			if($cell.length){
				const w = Math.ceil($cell.outerWidth() || 0);
				if(w > width){
					width = w;
				}
			}
		});
		return width;
	};
	const _totalCols = ($table) => {
		let total = 0;
		$table.find("tr").each((_i, row) => {
			const len = $(row).children().length;
			if(len > total){
				total = len;
			}
		});
		return total;
	};
	const _applyCol = ($table, idx, width, side, offset) => {
		const styleVar = side === "right" ? "--freeze-fixed-right" : "--freeze-fixed-left";
		const sideClass = side === "right" ? "freeze-fixed-right" : "freeze-fixed-left";
		$table.find(`tr > *:nth-child(${idx})`).each((_i, cell) => {
			$(cell)
				.addClass("freeze-fixed-col")
				.addClass(sideClass)
				.css(styleVar, `${offset}px`)
				.css("width", `${width}px`)
				.css("min-width", `${width}px`)
				.css("max-width", `${width}px`);
		});
	};
	const _refresh = ($wrap, opts) => {
		if(!$wrap.length){
			return;
		}
		const $table = $wrap.find(opts.tableSelector).first();
		if(!$table.length){
			_clear($wrap);
			return;
		}
		if($(window).width() < opts.minWidth){
			_clear($wrap);
			return;
		}
		_clear($wrap);
		const totalCols = _totalCols($table);
		if(totalCols <= 0){
			return;
		}
		const leftCount = Math.max(0, parseInt(opts.fixedLeft, 10) || 0);
		const rightCount = Math.max(0, parseInt(opts.fixedRight, 10) || 0);
		const maxLeft = Math.min(leftCount, totalCols);
		const maxRight = Math.min(rightCount, Math.max(0, totalCols - maxLeft));
		let left = 0;
		for(let i=1; i<=maxLeft; i++){
			const width = _colWidth($table, i);
			if(width > 0){
				_applyCol($table, i, width, "left", left);
				left += width;
			}
		}
		let right = 0;
		for(let k=0; k<maxRight; k++){
			const i = totalCols - k;
			const width = _colWidth($table, i);
			if(width > 0){
				_applyCol($table, i, width, "right", right);
				right += width;
			}
		}
		$wrap.addClass("freeze-fixed-table-enabled");
		_syncState($wrap);
	};
	$.fn.freezeFixedTable = function(methodOrOptions){
		return this.each(function(){
			const $wrap = $(this);
			const id = $wrap.data(FREEZE_FIXED_ID) || _uid();
			$wrap.data(FREEZE_FIXED_ID, id);
			if(methodOrOptions === "destroy"){
				$(window).off(`resize${FREEZE_FIXED_NS}.${id}`);
				$wrap.off(`scroll${FREEZE_FIXED_NS}`);
				_clear($wrap);
				$wrap.removeData(FREEZE_FIXED_KEY);
				return;
			}
			const opts = $.extend({}, FREEZE_FIXED_DEFAULTS, $wrap.data(FREEZE_FIXED_KEY) || {}, (typeof methodOrOptions === "object" ? methodOrOptions : {}));
			$wrap.data(FREEZE_FIXED_KEY, opts);
			_refresh($wrap, opts);
			$wrap.off(`scroll${FREEZE_FIXED_NS}`).on(`scroll${FREEZE_FIXED_NS}`, () => _syncState($wrap));
			$(window).off(`resize${FREEZE_FIXED_NS}.${id}`).on(`resize${FREEZE_FIXED_NS}.${id}`, () => _refresh($wrap, opts));
		});
	};
})(jQuery, window);
