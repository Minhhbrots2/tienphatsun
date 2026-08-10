{literal}
<script type="text/javascript">
	$(function(){
		$('#slb_intro_box_domain').change(function(){
			var $_this = $(this);
			window.location.href = '/ucp/index.php?mod='+mod+'&act='+act+'&domain_id='+$_this.val()+'#isotab1';
		});
	});
</script>
{/literal}
<form id="forums" method="post" action="" enctype="multipart/form-data">
    <div class="row-span" >
		<div class="fieldlabel"><u class="color_r">{$_lang->get_Lang('Select domain')}</u></div>
		<div class="fieldarea">
			<select class="slb" style="width:160px;" name="domain_id" id="slb_intro_box_domain">
				{$clsISO->setSelectDomain($domain_id)}
			</select>
		</div>
	</div>
	<div class="row-field">
    	<div class="row-heading notToogle">Nội dung</div>
        <div class="coltrols">
            {$clsForm->showInput($intro)}
        </div>
    </div>
    <!--<div class="row-field">
    	<div class="row-heading notToogle">Ảnh trang</div>
        <div class="coltrols">
            <table>
                <tr>
                    <td width="300px">
                        <a href="javascript:void()" class="image">
                            <img src="{$oneItem.image}" width="300" height="110" style="display:block;"  />
                        </a>
                    </td>
                    <td valign="top" align="left">
                        Tải ảnh từ trên máy tính<br />
                        <input type="file" name="image" /><br />
                        Hệ thống chỉ chấp nhận các ảnh có định dạng .jpg,.png, hoặc .gif
                        <br /><br />
                        Hoặc sử dụng ảnh trên mạng<br />
                        <input type="text" name="image_url" class="full text">
                    </td>
                </tr>
            </table>
        </div>
    </div>
    -->
    <div class="submit-buttons">
        <center>{$saveBtn}</center>
        <input value="Update" name="commit" type="hidden">
    </div>
</form>