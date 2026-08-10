<div class="breadcrumb">
	<strong>Bạn đang ở:</strong>
	<a href="{$PCMS_URL}" title="Dashboard">Dashboard</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}?mod={$mod}">Mẫu E-Mail</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod={$mod}&country_id={$country_id}" title="{$mod}">Khởi tạo mới</a>
</div>
<div class="container-fluid">
    <div class="page-title">	
    	<a href="javascript:window.history.back();" class="back fr">Back</a>
        <h2>Mẫu E-Mail</h2>
        <p>Quản lý mẫu E-Mail trong Hệ Thống</p>
    </div>
    <form method="post" action="" enctype="multipart/form-data" class="validate-form">
    	<div class="wrap">
        	<div class="leftPage">
            	<div class="row-field">
                    <div class="row-heading">Tiêu đề*</div>
                    <div class="coltrols">
                        <input class="text full required" name="iso-{$title}" value="" maxlength="255" type="text">
                    </div>
                </div>
				<div class="form-group">
					<div class="col-xs-12 col-md-6">
						<label class="col-form-label">From name</label>
						<input type="text" class="form-control" name="iso-fromname" value="" />
					</div>
					<div class="col-xs-12 col-md-6">
						<label class="col-form-label">From email</label>
						<input type="text" class="form-control" name="iso-fromemail" value="" />
					</div>
				</div>
                <div class="row-field">
                    <div class="row-heading">Subject*</div>
                    <div class="coltrols">
                        <input class="text full required" name="iso-{$subject}" value="" maxlength="255" type="text">
                    </div>
                </div>
                <div class="row-field">
                    <div class="row-heading">Nội dung</div>
                    <div class="coltrols">
                        {$clsForm->showInput($content)}
                    </div>
                </div>
                <div class="row-field">
                    <div class="row-heading collapse">Nội dung mặc định</div>
                    <div class="coltrols" style="display:none">
                        {$clsForm->showInput($default_content)}
                    </div>
                </div>
            </div>
            <div class="rightPage">
            	<div class="row-field">
                	<div class="row-heading">Type Template Email*</div>
                    <div class="coltrols">
                        <select class="slb span12 required" name="iso-email_cat_id">
                        	 {$clsProperty->getSelectByProperty('TypeEmail',$email_cat_id)}
                        </select>
                    </div>
                </div>
                <div class="row-field">
                	<div class="row-heading">Company profile Tags</div>
                    <div class="content">
                        <ul class="wicket">
                            <li>
                                <a class="command" href="javascript:void();" data="[%PAGE_NAME%]">Tên công ty</a>
                                <span>[%PAGE_NAME%]</span>
                            </li>
                            <li>
                                <a class="command" href="javascript:void();" data="[%COMPANY_ADDRESS%]">Địa chỉ</a>
                                <span>[%COMPANY_ADDRESS%]</span>
                            </li>
                            <li>
                                <a class="command" href="javascript:void();" data="[%COMPANY_PHONE%]">Điện thoại</a>
                                <span>[%COMPANY_PHONE%]</span>
                            </li>
                            <li>
                                <a class="command" href="javascript:void();" data="[%COMPANY_EMAIL%]">Email</a>
                                <span>[%COMPANY_EMAIL%]</span>
                            </li>
                            <li>
                                <a class="command" href="javascript:void();" data="[%COMPANY_FAX%]">Fax</a>
                                <span>[%COMPANY_FAX%]</span>
                            </li>
                            <li>
                                <a class="command" href="javascript:void();" data="[%COMPANY_WEBSITE%]">Website</a>
                                <span>[%COMPANY_WEBSITE%]</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row-field">
                	<div class="row-heading">Customer Tags</div>
                    <div class="content">
                        <ul class="wicket">
                            <li>
                                <a class="command" href="javascript:void();" data="[%CUSTOMER_FULLNAME%]">Họ và tên</a>
                                <span>[%CUSTOMER_FULLNAME%]</span>
                            </li>
                            <li>
                                <a class="command" href="javascript:void();" data="[%CUSTOMER_EMAIL%]">Email</a>
                                <span>[%CUSTOMER_EMAIL%]</span>
                            </li>
                            <li>
                                <a class="command" href="javascript:void();" data="[%DATETIME%]">Thời Gian</a>
                                <span>[%DATETIME%]</span>
                            </li>
                            <li class="lastBorder">
                                <a class="command" href="javascript:void();" data="[%UNSUBSCRIBE%]">Unsubscribe email</a>
                                <span>[%UNSUBSCRIBE%]</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row-field">
                	<div class="row-heading">Custom Tags <a class="fr" id="clickQuickAddTeplateTag" href="#"><img align="absmiddle" src="{$URL_IMAGES}/v2/icon_edit.gif" /></a>
                    </div>
                    <div class="content">
                        <ul class="wicket divide2col" id="custom_template_tags">
                           
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <fieldset class="submit-buttons">
            {$saveBtn} {$resetBtn}
            <input value="Insert" name="submit" type="hidden">
        </fieldset>
    </form>
</div>

