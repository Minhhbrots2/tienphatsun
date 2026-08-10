<h1 class="titHead" style="width:100%">
	<img src="{$URL_IMAGES}/icon_admin/Terminal.png" width="50" class="imgHead" /> {$core->get_Lang('confirm_delete')}
</h1>
<form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form">
	<fieldset>
        <legend>{$core->get_Lang('confirm')}:</legend>
        {$core->get_Lang('Are you sure delete this')} {$mod|ucfirst} ?
    </fieldset>
    <fieldset class="submit-buttons">
        <button type="submit" name="update" class="btn btn-primary start">
            <i class="icon-ok icon-white"></i>
            <span>{$core->get_Lang('agree')}</span>
        </button>
        <button type="button" class="btn btn-warning delete" onclick="javascript:history.back();">
            <i class="icon-retweet icon-white"></i>
            <span>{$core->get_Lang('no/back')}</span>
        </button>
        <input value="agree" name="agree" type="hidden">
    </fieldset>
</form>
