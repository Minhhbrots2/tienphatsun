<tr id="{$uid}_media" class="tr_attrs">
    <td class="text-center">{$i|default:""}</td>
    <td class="text-center">
        <input class="form-control" name="attrs_media[{$uid}][title]" placeholder="Nhập tiêu đề" type="text" value="{$item.title}" /></td>
    <td class="text-center">
        <input class="form-control" name="attrs_media[{$uid}][images]" placeholder="Nhập giá trị" type="text" value="{$item.images}" />
    </td>
    <td class="text-center">
        <input class="form-control" name="attrs_media[{$uid}][videos]" placeholder="Nhập giá trị" type="text" value="{$item.videos}" />
    </td>
    <td class="text-center">
        <a class="btn btn-default" progress_id="{$uid}" title="Xóa" href="javascript:void(0);" uid="{$uid}" project_id="{$item.project_id}" block_id="{$item.block_id}" building_id="{$item.building_id}" onClick="delete_progress_media(this, event)">{$core->makeIcon('trash')}</a>
    </td>
</tr>