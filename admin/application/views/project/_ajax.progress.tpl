<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a>
            <h3 class="modal-title"><strong>{$titlePage}</strong></h3>
        </div>
        {assign var = toId value = $clsISO->getUniqid()}
        <form method="POST" class="frmIssue d-none" enctype="multipart/form-data">
            <input type="file" onchange="$Core.project.upload_image(this, event)" name="image"
                   maxlength="255" id="{$toId}" toId="{$toId}" />
        </form>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade py-3 active in" id="content" role="tabpanel" aria-labelledby="content-tab">
                        <div class="widget-block mb-5">
                            <div class="widget-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <strong class="mb-0">Tổng quan</strong>
                                    <a href="javascript:void(0);" class="widget-add" onClick="add_progress(this, event)" project_id="{$pvalTable}" _openFrom="_block" _holderG="_attrs"><i class="fa fa-plus"></i> Thêm mốc</a>
                                </div>
                            </div>
                            <div class="widget-content">
                                <div class="progress-blocks tbody_attrs no_group">
                                    {if !empty($list_project_progress)}
                                        {foreach from = $list_project_progress name=i key=uid item = _Item}
                                            {$core->build("_ajax.progress_item.tpl",  ["_Item" => $_Item, "uid" => $_Item.id, "index" => $smarty.foreach.i.iteration, "media_json" => $_Item.media_json] )}
                                        {/foreach}
                                    {else}
                                        {assign var = uid value = $clsISO->getUniqid()}
                                        {$core->build("_ajax.progress_item.tpl",  ["uid" => $uid] )}
                                    {/if}
                                </div>
                            </div>
                            <div class="pc-add-bottom">
                                <a href="javascript:void(0);" class="btn btn-default btn-block pc-add-btn" onClick="add_progress(this, event)" project_id="{$project_id}" _openFrom="_block" _holderG="_attrs"><i class="fa fa-plus"></i> Thêm mốc tiến độ</a>
                            </div>
                        </div>
                        <datalist id="progress-date-suggest">
                            <option value="Quý I/2026"></option>
                            <option value="Quý II/2026"></option>
                            <option value="Quý III/2026"></option>
                            <option value="Quý IV/2026"></option>
                            <option value="Quý I/2027"></option>
                            <option value="Quý II/2027"></option>
                            <option value="Quý III/2027"></option>
                            <option value="Quý IV/2027"></option>
                            <option value="Quý I/2028"></option>
                            <option value="Quý II/2028"></option>
                            <option value="T6/2026"></option>
                            <option value="T12/2026"></option>
                            <option value="Đã hoàn thành"></option>
                            <option value="Đang thi công"></option>
                            <option value="Dự kiến"></option>
                        </datalist>
                    </div>
                </div>
            </div>
            <input type="hidden" project_id="{$project_id}" value="{$project_id}">
            <input type="hidden" block_id="{$block_id}" value="{$block_id}">
            <div class="modal-footer">
                <button type="button" class="btn btn-success pull-right" onClick="pop_save_progress(this, event)" block_id="{$block_id}" project_id="{$project_id}" building_id="{$building_id}" _openFrom="{$_openFrom}"{if $_openFrom eq '_stock'} toId="{$toId}"{/if}>Cập nhật</button>
                <button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
            </div>
        </form>
    </div>
</div>