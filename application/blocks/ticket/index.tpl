{if $ticket_enabled}
{literal}
<div id="fhticket" class="tkw" ng-controller="TicketCtrl as vm" ng-cloak>
	<script>
		window.TICKET_BOOT = {
			profile_id: (typeof profile_id !== 'undefined' ? profile_id : 0),
			ajax: (typeof path_ajax_script !== 'undefined' ? path_ajax_script : '')
		};
	</script>

	<!-- Panel -->
	<div class="tkw-panel" ng-show="vm.open">
		<div class="tkw-hd">
			<button class="tkw-hd-b" ng-if="vm.v!=='list'" ng-click="vm.back()" aria-label="Quay lại"><i class="bx bx-chevron-left"></i></button>
			<span class="tkw-hd-ico" ng-if="vm.v==='list'"><i class="bx bx-support"></i></span>
			<span class="tkw-hd-t">{{vm.title()}}</span>
			<button class="tkw-hd-b tkw-hd-x" ng-click="vm.open=false" aria-label="Đóng"><i class="bx bx-x"></i></button>
		</div>

		<div class="tkw-alert" ng-if="vm.err">{{vm.err}}</div>

		<!-- LIST -->
		<div class="tkw-view" ng-if="vm.v==='list'">
			<div class="tkw-bar">
				<div class="tkw-seg">
					<button ng-class="{on:vm.tab==='all'}" ng-click="vm.setTab('all')">Tất cả</button>
					<button ng-class="{on:vm.tab==='open'}" ng-click="vm.setTab('open')">Đang mở</button>
					<button ng-class="{on:vm.tab==='answered'}" ng-click="vm.setTab('answered')">Đã trả lời</button>
				</div>
				<button class="tkw-new" ng-click="vm.goNew()" title="Tạo ticket mới"><i class="bx bx-plus"></i></button>
			</div>
			<div class="tkw-body">
				<div class="tkw-loading" ng-if="vm.loading"><i class="bx bx-loader-alt bx-spin"></i> Đang tải…</div>
				<div class="tkw-it" ng-repeat="t in vm.ts" ng-click="vm.open_(t)">
					<div class="tkw-it-1"><span class="tkw-code">#{{t.code}}</span><span class="tkw-when">{{t._date}}</span></div>
					<div class="tkw-it-2">{{t.subject}}</div>
					<div class="tkw-it-3">
						<span class="tkw-bdg" ng-class="t.status">{{vm.st(t.status)}}</span>
						<span class="tkw-dep" ng-if="t.department"><i class="bx bx-buildings"></i>{{t.department}}</span>
					</div>
				</div>
				<div class="tkw-empty" ng-if="!vm.loading && !vm.ts.length">
					<i class="bx bx-conversation"></i><b>Chưa có ticket</b><span>Bấm + để gửi yêu cầu hỗ trợ</span>
				</div>
			</div>
		</div>

		<!-- DETAIL -->
		<div class="tkw-view" ng-if="vm.v==='detail'">
			<div class="tkw-meta">
				<span class="tkw-bdg" ng-class="vm.c.status">{{vm.st(vm.c.status)}}</span>
				<span class="tkw-meta-x" ng-if="vm.c.department">{{vm.c.department}}</span>
				<button class="tkw-reopen" ng-if="vm.c.status==='closed'" ng-click="vm.reopen()"><i class="bx bx-refresh"></i>Mở lại</button>
			</div>
			<div class="tkw-body">
				<div class="tkw-subj">{{vm.c.subject}}</div>
				<div class="tkw-th">
					<div class="tkw-loading" ng-if="vm.loading"><i class="bx bx-loader-alt bx-spin"></i> Đang tải…</div>
					<div class="tkw-entry" ng-repeat="m in vm.c.replies" ng-class="{'tkw-sup':m.side==='support'}">
						<div class="tkw-e-hd">
							<div class="tkw-e-av">{{m._ini}}</div>
							<div>
								<div class="tkw-e-who">{{m.sender_name}}</div>
								<span class="tkw-e-role" ng-class="m.side==='support'?'tkw-sup':'tkw-cus'">{{m.side==='support'?'HỖ TRỢ':'BẠN'}}</span>
							</div>
							<span class="tkw-e-time">{{m._time}}</span>
							<button class="tkw-e-q" ng-click="vm.doQuote(m)" title="Trích dẫn"><i class="bx bxs-quote-alt-left"></i></button>
						</div>
						<div class="tkw-e-body" ng-bind-html="m._html"></div>
						<div class="tkw-e-att" ng-if="m.attachments.length">
							<a ng-repeat="f in m.attachments" class="tkw-att" ng-class="{'tkw-att-img': f.kind==='image'}"
								ng-href="{{ f.kind==='image' ? f.big : (f.kind==='preview' ? f.preview : f.download) }}"
								ng-attr-data-fancybox="{{ f.kind==='file' ? undefined : ('tk'+m.reply_id) }}"
								ng-attr-data-type="{{ f.kind==='image' ? 'image' : (f.kind==='preview' ? 'iframe' : undefined) }}"
								data-caption="{{f.name}}" target="_blank" rel="noopener noreferrer">
								<img ng-if="f.kind==='image'" class="tkw-att-thumb" ng-src="{{f.thumb}}" alt="{{f.name}}" />
								<span ng-if="f.kind!=='image'"><i class="bx" ng-class="f.ext==='pdf' ? 'bxs-file-pdf' : ((f.ext==='doc'||f.ext==='docx') ? 'bxs-file-doc' : (f.kind==='preview' ? 'bxs-file' : 'bx-paperclip'))"></i>{{f.name}}</span>
							</a>
						</div>
						<div class="tkw-e-ft" ng-if="m.side==='support'">
							<div class="tkw-rate">
								<i class="bx" ng-repeat="s in [1,2,3,4,5]" ng-class="s<=m.rating?'bxs-star on':'bx-star'" ng-click="vm.rate(m,s)"></i>
								<span>{{m.rating?'cảm ơn bạn đã đánh giá':'đánh giá phản hồi này'}}</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tkw-cm">
				<div ng-if="vm.c.status==='closed'" class="tkw-closed-note">Ticket đã đóng — bấm "Mở lại" để tiếp tục.</div>
				<div ng-if="vm.c.status!=='closed'">
					<div class="tkw-qchip" ng-if="vm.quote">
						<div class="tkw-qchip-b">
							<div class="tkw-qchip-w"><i class="bx bxs-quote-alt-left"></i>Đang trích: {{vm.quote.sender_name}}</div>
							<div class="tkw-qchip-t" ng-bind-html="vm.quote._html"></div>
						</div>
						<button class="tkw-qchip-x" ng-click="vm.quote=null"><i class="bx bx-x"></i></button>
					</div>
					<div class="tkw-ed">
						<div class="tkw-ed-tb">
							<button type="button" onmousedown="event.preventDefault()" ng-click="vm.exec('bold')" title="Đậm"><i class="bx bx-bold"></i></button>
							<button type="button" onmousedown="event.preventDefault()" ng-click="vm.exec('italic')" title="Nghiêng"><i class="bx bx-italic"></i></button>
							<button type="button" onmousedown="event.preventDefault()" ng-click="vm.exec('insertUnorderedList')" title="Danh sách"><i class="bx bx-list-ul"></i></button>
							<button type="button" onmousedown="event.preventDefault()" ng-click="vm.exec('insertOrderedList')" title="Danh sách số"><i class="bx bx-list-ol"></i></button>
							<button type="button" onmousedown="event.preventDefault()" ng-click="vm.link()" title="Chèn link"><i class="bx bx-link"></i></button>
						</div>
						<div class="tkw-ed-area" contenteditable="true" tk-ed ng-model="vm.d" data-ph="Nhập nội dung trả lời…"></div>
					</div>
					<div class="tkw-chips" ng-if="vm.datt.length">
						<span class="tkw-chip" ng-repeat="a in vm.datt" ng-class="{'tkw-chip-e':a.err}">
							<i class="bx" ng-class="a.up?'bx-loader-alt bx-spin':(a.err?'bx-x-circle':'bx-paperclip')"></i>
							<span class="tkw-chip-n" title="{{a.err||a.name}}">{{a.name}}</span>
							<i class="bx bx-x tkw-chip-x" ng-click="vm.rmAtt(vm.datt, $index)"></i>
						</span>
					</div>
					<label class="tkw-drop tkw-drop-sm" tk-drop="vm.addFiles($files, vm.datt)">
						<i class="bx bx-paperclip"></i><span>Đính kèm ảnh/file — kéo thả hoặc bấm chọn · tối đa 10MB</span>
						<input type="file" multiple tk-file="vm.addFiles($files, vm.datt)" style="display:none">
					</label>
					<div class="tkw-upwarn" ng-if="vm.upErr">{{vm.upErr}}</div>
					<div class="tkw-cm-f">
						<span class="tkw-flex"></span>
						<button class="tkw-btn tkw-btn-p" ng-disabled="vm.blank(vm.d) || vm.sending || vm.upBusy(vm.datt)" ng-click="vm.send()">Gửi trả lời</button>
					</div>
				</div>
			</div>
		</div>

		<!-- NEW -->
		<div class="tkw-view" ng-if="vm.v==='new'">
			<div class="tkw-body">
				<div class="tkw-fm">
					<div class="tkw-f"><label>Tiêu đề <i>*</i></label><input type="text" ng-model="vm.f.subject" placeholder="Mô tả ngắn vấn đề bạn gặp"></div>
					<div class="tkw-f"><label>Ưu tiên</label>
						<select ng-model="vm.f.priority">
							<option value="normal">Bình thường</option>
							<option value="low">Thấp</option>
							<option value="high">Khẩn</option>
						</select>
					</div>
					<div class="tkw-f"><label>Nội dung <i>*</i></label>
						<div class="tkw-ed">
							<div class="tkw-ed-tb">
								<button type="button" onmousedown="event.preventDefault()" ng-click="vm.exec('bold')" title="Đậm"><i class="bx bx-bold"></i></button>
								<button type="button" onmousedown="event.preventDefault()" ng-click="vm.exec('italic')" title="Nghiêng"><i class="bx bx-italic"></i></button>
								<button type="button" onmousedown="event.preventDefault()" ng-click="vm.exec('insertUnorderedList')" title="Danh sách"><i class="bx bx-list-ul"></i></button>
								<button type="button" onmousedown="event.preventDefault()" ng-click="vm.exec('insertOrderedList')" title="Danh sách số"><i class="bx bx-list-ol"></i></button>
								<button type="button" onmousedown="event.preventDefault()" ng-click="vm.link()" title="Chèn link"><i class="bx bx-link"></i></button>
							</div>
							<div class="tkw-ed-area" contenteditable="true" tk-ed ng-model="vm.f.content" data-ph="Thao tác nào, màn hình nào, lỗi hiện ra sao…"></div>
						</div>
					</div>
					<div class="tkw-f">
						<label>Đính kèm</label>
						<label class="tkw-drop" tk-drop="vm.addFiles($files, vm.f.att)">
							<i class="bx bx-cloud-upload tkw-drop-ic"></i>
							<span class="tkw-drop-t">Kéo thả hoặc <u>bấm chọn</u> ảnh/file</span>
							<span class="tkw-drop-s">tối đa 10MB · png, jpg, pdf, doc, xls, zip…</span>
							<input type="file" multiple tk-file="vm.addFiles($files, vm.f.att)" style="display:none">
						</label>
						<div class="tkw-chips" ng-if="vm.f.att.length">
							<span class="tkw-chip" ng-repeat="a in vm.f.att" ng-class="{'tkw-chip-e':a.err}">
								<i class="bx" ng-class="a.up?'bx-loader-alt bx-spin':(a.err?'bx-x-circle':'bx-paperclip')"></i>
								<span class="tkw-chip-n" title="{{a.err||a.name}}">{{a.name}}</span>
								<i class="bx bx-x tkw-chip-x" ng-click="vm.rmAtt(vm.f.att, $index)"></i>
							</span>
						</div>
						<div class="tkw-upwarn" ng-if="vm.upErr">{{vm.upErr}}</div>
					</div>
					<div class="tkw-warn" ng-if="vm.secret">
						<i class="bx bx-shield-x"></i><span>Nội dung có vẻ chứa <b>mật khẩu / thông tin đăng nhập</b>. Nên gửi qua kênh bảo mật riêng thay vì ticket.</span>
					</div>
				</div>
			</div>
			<div class="tkw-ft">
				<button class="tkw-btn tkw-btn-g" ng-click="vm.back()">Huỷ</button>
				<button class="tkw-btn tkw-btn-p" ng-disabled="!vm.f.subject || vm.blank(vm.f.content) || vm.sending || vm.upBusy(vm.f.att)" ng-click="vm.create()">Gửi ticket</button>
			</div>
		</div>
	</div>
	<!-- FAB -->
	<button class="tkw-fab" ng-if="!vm.open" ng-click="vm.toggle()" aria-label="Hỗ trợ khách hàng">
		<i class="bx bx-support"></i><span class="tkw-fab-b" ng-if="vm.unread">{{vm.unread}}</span>
	</button>
</div>
{/literal}
{/if}
