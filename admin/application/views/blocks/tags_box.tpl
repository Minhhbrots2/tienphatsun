<div class="fieldlabel"><span class="notice"><span class="requiredMask">*</span> {$core->get_Lang('Tags')}.</span></div>
<div class="fieldarea">
    <div class="coltrols">
        <div id="listTag" style="margin-bottom:5px; display:block;">
            {section name=i loop=$listTag}
                <div class="oneTag"><span style="float:left;">{$clsTag->getTitle($listTag[i].tag_id)}</span><a href="#" class="clickToClose" title="Xóa" id="t-{$listTag[i].tag_blog_id}">x</a></div>
                    {/section}
        </div>
        <div class="wrap">
            {$listAvailableTag}
            <input type="text" name="txtTag" id="txtTag" class="text full" style="float:left; width:200px;margin-right:4px;" />
            <a href="javascript:;" class="btn btn-success fl" id="addTag" style="padding:4px 5px 5px">
                <i class="icon-plus-sign icon-white"></i>
            </a>
        </div>
    </div>
</div>

<script type="text/javascript"> $blog_id = {$blog_id}</script>
{literal}
    <script type="text/javascript">
    $(document).ready(function() {
        $("#txtTag").autocomplete(availableTags, {
            minChars: 1,
            width: 200,
            matchContains: true,
            autoFill: false,
            formatItem: function(row, i, max) {
                return row.name;
            },
            formatResult: function(row) {
                return row.val;
            }
        });
        $('#txtTag').keypress(function(e) {
            var key;
            if (window.event)
                key = window.event.keyCode;
            else
                key = e.which;
            if (key == 13) {
                $('#addTag').trigger('click');
            }
        });
        $('#addTag').click(function() {
            var newval = $('#txtTag').val();
            if (newval != '') {
                vietiso_loading(1);
                var adata = {
                    "blog_id": $blog_id,
                    "val": $('#txtTag').val()
                };
                $.ajax({
                    type: "POST",
                    url: path_ajax_script + "/index.php?mod=blog&act=saveTag",
                    data: adata,
                    dataType: "html",
                    success: function(html) {
                        $('#listTag').append(html);
                        $('#txtTag').val('').focus();
                        vietiso_loading(0);
                    }
                });
                return false;
            }
        });
        $('.clickToClose').live('click', function() {
            $('#loadingWaitTags').show();
            var id = $(this).attr('id');
            var sp = id.split('-');
            var tag_blog_id = sp[1];
            var adata = {
                "tag_blog_id": tag_blog_id
            };
            $.ajax({
                type: "POST",
                url: path_ajax_script + "/index.php?mod=blog&act=deleteTagBlog",
                data: adata,
                dataType: "html",
                success: function(html) {
                }
            });
            $(this).parent().remove();
            $('#loadingWaitTags').hide();
            return false;
        });
        function stopRKey(evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.keyCode == 13) && (node.type == "text")) {
                return false;
            }
        }
        document.onkeypress = stopRKey;
    });
    </script>
{/literal}            