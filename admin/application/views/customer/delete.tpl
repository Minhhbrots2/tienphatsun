<div class="container-fluid">
    <form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form">
    	<div class="infobox">
        	<b>Xác nhận</b><br />
            Bạn có chắc muốn thực hiện thao tác này.
        </div>
        <center>
            <div class="submit">
                <button type="submit" name="update" class="btn btn-primary">
                    <i class="icon-ok icon-white"></i>
                    <span>Tôi đồng ý</span>
                </button>
                <button type="button" class="btn btn-warning" onclick="javascript:history.back();">
                    <i class="icon-chevron-left icon-white"></i>
                    <span>Quay lại</span>
                </button>
                <input value="agree" name="agree" type="hidden">
            </div>
        </center>
    </form>
</div>