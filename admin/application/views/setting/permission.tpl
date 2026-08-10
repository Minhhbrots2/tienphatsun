<div class="breadcrumb">
    <a href="{$PCMS_URL}">{$core->get_Lang('Home')}</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod={$mod}&act=central" title="{$mod}">{$core->get_Lang('System Settings')}</a>
    <a href="javascript:window.history.back();" class="back fr">Quay lại</a>
</div>
<div class="container-fluid">
    <div class="page-title">
        <h2><i class="fa fa-shield"></i> Cấu hình &raquo; Nhân sự &amp; phân quyền</h2>
        <p>Gán nhân sự vào nhóm quyền — lưu ra configs/business.php</p>
    </div>
    <div class="clearfix"></div>
    <div style="background:#fff3cd;padding:10px 14px;border-radius:6px;margin-bottom:14px;font-family:monospace;font-size:13px;color:#664d03;">
        DEBUG — staffList: {if isset($staffList)}SET, count = {$staffList|@count}{else}<b style="color:#a4161a;">KHÔNG SET → handler default_permission() KHÔNG chạy (IonCube dispatch không gọi handler cho act mới)</b>{/if}
    </div>
    <form method="post" action="" class="validate-form">
        <div class="cfg-grid">
            <div class="cfg-main">

                <section class="cfg-card">
                    <h3 class="cfg-card__title"><i class="fa fa-shield"></i> Nhóm quyền hệ thống</h3>
                    <p style="color:#a4161a; font-size:13px; padding:4px 0 12px; line-height:1.5;">
                        <i class="fa fa-exclamation-triangle"></i> Người trong nhóm này được cấp <b>toàn quyền</b>. Khách mới phải gán lại đúng nhân sự — để trống là an toàn (không ai được cấp).
                    </p>
                    <div class="cfg-row" style="align-items:start;">
                        <label class="cfg-row__label">Super Admin (toàn quyền)<br><small style="font-family:monospace;color:#9aa1ad;">_PROFILE_SUPPER_ID</small></label>
                        <div class="cfg-row__control">
                            <select name="perm_supper[]" multiple size="8" class="cfg-input" style="height:auto; padding:4px;">
                                {foreach from=$staffList key=pid item=m}
                                <option value="{$pid}"{if isset($sel_supper[$pid])} selected{/if}>{$m.full_name|escape} (#{$pid})</option>
                                {/foreach}
                            </select>
                            <small style="color:#9aa1ad;">Giữ Ctrl (Windows) / Cmd (Mac) để chọn nhiều người.</small>
                        </div>
                    </div>
                </section>

                <section class="cfg-card">
                    <h3 class="cfg-card__title"><i class="fa fa-user"></i> Vai trò chủ chốt</h3>
                    <div class="cfg-row">
                        <label class="cfg-row__label">Admin tối cao<br><small style="font-family:monospace;color:#9aa1ad;">_USER_ADMIN_SUPER_ID</small></label>
                        <div class="cfg-row__control">
                            <select name="perm_user_admin" class="cfg-input">
                                <option value="0">— Chọn nhân sự —</option>
                                {foreach from=$staffList key=pid item=m}
                                <option value="{$pid}"{if $pid eq $cur_user_admin} selected{/if}>{$m.full_name|escape} (#{$pid})</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">Giám đốc điều hành<br><small style="font-family:monospace;color:#9aa1ad;">_PROFILE_CEO_ID</small></label>
                        <div class="cfg-row__control">
                            <select name="perm_ceo" class="cfg-input">
                                <option value="0">— Chọn nhân sự —</option>
                                {foreach from=$staffList key=pid item=m}
                                <option value="{$pid}"{if $pid eq $cur_ceo} selected{/if}>{$m.full_name|escape} (#{$pid})</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">Quản trị kỹ thuật<br><small style="font-family:monospace;color:#9aa1ad;">_PROFILE_TECH_ID</small></label>
                        <div class="cfg-row__control">
                            <select name="perm_tech" class="cfg-input">
                                <option value="0">— Chọn nhân sự —</option>
                                {foreach from=$staffList key=pid item=m}
                                <option value="{$pid}"{if $pid eq $cur_tech} selected{/if}>{$m.full_name|escape} (#{$pid})</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">Admin hệ thống<br><small style="font-family:monospace;color:#9aa1ad;">_PROFILE_ADMIN_ID</small></label>
                        <div class="cfg-row__control">
                            <select name="perm_admin" class="cfg-input">
                                <option value="0">— Chọn nhân sự —</option>
                                {foreach from=$staffList key=pid item=m}
                                <option value="{$pid}"{if $pid eq $cur_admin} selected{/if}>{$m.full_name|escape} (#{$pid})</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </section>

            </div>
        </div>
        <div class="cfg-footer">
            <button type="submit" class="save_setting">Lưu thay đổi</button>
            <input type="hidden" name="submit" value="SavePermission">
        </div>
    </form>
</div>
