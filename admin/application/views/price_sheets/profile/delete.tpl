<h2 id="headTitle">Xác nhận xóa</h2>
<form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form">
	<fieldset>
		<legend>Xác nhận:</legend>
        Bạn có chắc chắn muốn Xóa <strong style="color:#FF0000">{$clsClassTable->getOneField('title',$pvalTable)}</strong>?
	</fieldset>
    <fieldset class="submit-buttons">
        <button type="submit" name="update" class="btn btn-primary start">
            <i class="icon-ok icon-white"></i>
            <span>Đồng ý</span>
        </button>
        <button type="button" class="btn btn-warning delete" onclick="javascript:history.back();">
            <i class="icon-retweet icon-white"></i>
            <span>Không/Quay lại</span>
        </button>
        <input value="agree" name="agree" type="hidden">
    </fieldset>
</form>