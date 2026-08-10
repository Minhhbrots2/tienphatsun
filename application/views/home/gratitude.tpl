<script type="text/javascript">
	var _NEWS_EVENT_CAT_ID = '{$smarty.const._NEWS_EVENT_CAT_ID}';
</script>
<div class="container-xxl flex-grow-1 container-p-y">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">{$clsProperty->getTitle($cat_id)}</li>
		</ol>
	</nav>
	<section class="section section-xxs">
		<div class="row flex-row flex-wrap">
			<div class="col-md-8">
				<div class="awe__post-page">
					<div class="awe__post-form bg-white radius-4 mb-4">
						<div class="d-flex align-items-center w-100">
							<div class="awe__post-avatar">
								{if $_loggedin eq '1'}
								<img class="rounded" src="{$clsMember->getAvatar($_frontIsLoggedin_user_id, $_LoggedUser)}" width="44" height="44" />{else}
								<img class="rounded" src="{$URL_IMAGES}/no-avatar.jpg?v={$upd_version}" width="44" height="44" />
								{/if}
							</div>
							<div class="textarea">
								<a class="awe__post-input-link" href="javascript:void(0);" onClick="open_gratitude(this, event)" news_id="0" action="_add">Viết bài tri ân</a>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					{if !empty($list_post)}
					<div class="awe__list-post"> 
						{foreach name=i from=$list_post item = _oNews}
						{assign var = news_id value = $_oNews.news_id}
						{assign var = events_config value = $_oNews.events_config}
						{assign var = _title value = $clsNews->getTitle($news_id, $_oNews)}
						{assign var = gid value = $clsISO->getUniqid()}
						<div class="awe__post-item" order_no="{$_oNews.order_no}" id="post_item_{$news_id}">
							<div class="w-100 d-flex align-items-center justify-content-between mb-3">
								<div class="awe__post-profile d-flex" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oNews.user_id}" data-toggle="webui-popover" data-trigger="hover" data-width="400">
									<div class="awe__post-avatar">
										<img class="rounded" src="{$_oNews.db_profile.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" width="44" height="44" /> 
									</div>
									<div class="awe__post-profile-body">
										<p class="awe__post-name">{$_oNews.db_profile.name}</p>
										<div class="d-flex align-items-center">
											{if !empty($_oNews.db_profile.level)}
											<span class="awe__post-level mr-2 text-muted">
												{$_oNews.db_profile.level}
											</span>
											{/if}
											<span class="awe__post-star mr-2 text-muted">
												{$_oNews.db_profile.html_star}
											</span>
											<span class="awe__post-time text-muted">
												{$clsISO->getTimeAgo($_oNews.reg_date)}
											</span>
										</div>
									</div>
								</div>
								{if $_oNews.user_id eq $profile_id and $clsNews->checkCanEdit($news_id, $_oNews) eq '1' || 1==1}
									<div class="dropdown">
										<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="true"><i class="bx bx-dots-vertical-rounded"></i></button>
										<div class="dropdown-menu" data-popper-placement="bottom-end">
											<a class="dropdown-item" href="javascript:void(0);" gid="{$gid}" onClick="open_gratitude(this, event)" news_id="{$news_id}" action="_edit">{$clsISO->makeIcon('bx-edit-alt me-1', 'Chỉnh sửa')}</a>
											<a class="dropdown-item" href="javascript:void(0);" onClick="delete_news(this, event)" news_id="{$news_id}">{$clsISO->makeIcon('bx-trash me-1', 'Xóa')}</a>
										</div>
									</div>
								{/if}
							</div>
							<div class="awe__post-item-body">
								<h3 class="awe__post-title mb-3" onClick="open_gratitude(this, event)" gid="{$gid}" news_id="{$news_id}" action="_detail">
									<a class="fs-16 awe__post-link" title="{$_title}">{$_title}</a>
								</h3>
								<div class="awe__post-description mb-3" onClick="open_gratitude(this, event)" gid="{$gid}" news_id="{$news_id}" action="_detail">
									{$clsNews->getIntro($news_id, $_oNews)}
								</div>
								{if !empty($_oNews.department)}
									<div class="box_list_gratitude d-flex flex-wrap align-items-center cursor-pointer" onClick="open_gratitude(this, event)" gid="{$gid}" news_id="{$news_id}" action="_detail">
										<label for="" class="lbl_gratitude mb-2 cursor-pointer">Tri ân đến phòng:</label>
										<div class="lst_gratitude ml-2">
											{foreach from=$_oNews.department item=department}
												<span class="badge px-2 bg-label-primary me-2 mb-2">{$department.title}</span>
											{/foreach}
										</div>
									</div>
								{/if}
								{if !empty($_oNews.staff)}
									<div class="box_list_gratitude d-flex flex-wrap align-items-center cursor-pointer" onClick="open_gratitude(this, event)" gid="{$gid}" news_id="{$news_id}" action="_detail">
										<label for="" class="lbl_gratitude mb-2 cursor-pointer">Tri ân đến:</label>
										<div class="lst_gratitude ml-2">
											{foreach from=$_oNews.staff item=staff}
												<span class="badge px-2 bg-label-primary me-2 mb-2" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$staff.profile_id}" data-toggle="webui-popover" data-trigger="hover" data-width="400">{$staff.full_name}</span>
											{/foreach}
										</div>
									</div>
								{/if}
								{if !empty($_oNews.images)}
								<div class="awe__post-gallery gallery mb-3">
									{$clsNews->getImageGrid($news_id, $_oNews.images)}
								</div>
								{/if}
								{if $_oNews.cat_id eq $smarty.const._NEWS_EVENT_CAT_ID 
									&& ($events_config.is_staff_regis eq '1' || $events_config.is_cusotmer_regis eq '1')}
								<div class="d-flex py-2 justify-content-start">
									<a href="javascript:void(0);" news_id="{$news_id}" onClick="open_rlist_register(this, event)">10+ Danh sách đăng ký</a>
								</div>
								{/if}
							</div>
							{assign var = total_liked value = $_oNews.total_liked}
							{assign var = total_comments value = $_oNews.total_comments}
							<div gid="{$gid}" class="x1n2onr6{if $_oNews.total_actions eq '0'} d-none{/if} py-1">
								<div class="d-flex justify-content-between align-items-center">
									<div gid="{$gid}" class="reactions-total">{$clsNews->genTotalLike($news_id)}</div>
									<div class="comment"><i class="bx bx-comment"></i> {$total_comments}</div>
								</div>
							</div>
							<div class="awe__post-cmd">
								<div class="d-flex justify-content-center">
									<div class="awe__post-action reactions-wrap">
										<a gid="{$gid}" href="javascript:void(0);" news_id="{$news_id}" class="awe__post-action awe__post-like-action control-action{if $_oNews.status_liked eq '1'} liked{/if}" onClick="$Core.news.like(this, event)" data-name="like" data-clsTable="News" data-table_id="{$news_id}">
											{if $_oNews.status_liked eq '1'}
												{$clsISO->makeIcon('bxs-heart','Thích')}
											{else}
												{$clsISO->makeIcon('bx-heart','Thích')}
											{/if}
										</a>
										<div class="reactions-container d-none">
											<div class="reactions-list reactions-menu" data-selected="">
												<div class="reaction-item">
													<button class="reaction" onClick="$Core.news.like(this, event)"  data-name="like" data-clsTable="News" gid="{$gid}" data-table_id="{$news_id}"></button>
													<span class="label">Thích</span>
												</div>
												<div class="reaction-item">
													<button class="reaction" onClick="$Core.news.like(this, event)" data-name="fun" data-clsTable="News" gid="{$gid}" data-table_id="{$news_id}"></button>
													<span class="label">Vui</span>
												</div>
												<div class="reaction-item">
													<button class="reaction" onClick="$Core.news.like(this, event)" data-name="surprised" data-clsTable="News" gid="{$gid}" data-table_id="{$news_id}"></button>
													<span class="label">Ngạc nhiên</span>
												</div>
												<div class="reaction-item">
													<button class="reaction" onClick="$Core.news.like(this, event)" data-name="sad" data-clsTable="News" gid="{$gid}" data-table_id="{$news_id}"></button>
													<span class="label">Buồn</span>
												</div>
											</div>
										</div>
									</div>
									<a href="{$_link}" class="awe__post-action awe__post-comment-action" 
									onClick="open_gratitude(this, event)" gid="{$gid}" action="_detail" news_id="{$news_id}">
										{$clsISO->makeIcon('bx-comment','Bình luận')}
									</a>
									{if $_oNews.cat_id eq $smarty.const._NEWS_EVENT_CAT_ID 
									&& ($events_config.is_staff_regis eq '1' || $events_config.is_cusotmer_regis eq '1')}
										{if $_oNews.is_registed eq '1'}
											<a href="javascript:void(0);" onClick="cancel_register(this, event)" news_id="{$news_id}" class="awe__post-action awe__post-cancel-action text-danger" title="Hủy đăng ký">
												<i class="bx bxs-trash"></i> Hủy đăng ký</a>
											{if $events_config.is_cusotmer_regis eq '1'}
											<a href="javascript:void(0);" onClick="ms_register(this, event)" tp="edit" news_id="{$news_id}" class="awe__post-action awe__post-edit-action text-danger" title="Sửa đăng ký">
												<i class="bx bx-pencil"></i> Sửa đăng ký</a>
											{/if}
										{else}
											<a href="javascript:void(0);" onClick="ms_register(this, event)" tp="register" news_id="{$news_id}" class="awe__post-action awe__post-share-action text-success" title="Đăng ký tham gia">
												<i class="bx bx-plus"></i> Đăng ký</a>
										{/if}
									{else}
										<a href="javascript:void(0);" class="awe__post-action awe__post-share-action" data-link="{$_link}" data-title="{$_title}" data-toggle="modal" data-target="#sharer">
											<i class="bx bx-share"></i> Chia sẻ</a>
									{/if}
								</div>
							</div>
						</div>
						{/foreach} 
					</div>
					{/if}
					<div class="clearfix"></div>
					{if $total_record gt $per_page}
					<div class="d-flex justify-content-center">
						<button onClick="load_post_more(this, event)" class="btn btn-block btn-lg btn-link bg-white font-weight-bold" cat_id="{$cat_id}" total_loaded="{$per_page}" total_record="{$total_record}">Xem thêm</button>
					</div>
					{/if}
			   </div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title text-primary">Chào mừng {$oneProfile.full_name}!</h5>
						<p class="mb-3">Trang thông tin dành riêng cho CBNV {$smarty.const.BRAND_NAME}. Nơi đây sẽ có tất cả các thông tin phục vụ cho công việc mà bạn cần.</p>
						<a href="{$PCMS_URL}/staff.html" class="btn btn-sm btn-outline-primary">Truy cập hồ sơ nhân viên của bạn</a>
					</div>
                </div>
				 <div class="card sticky mt-3">
                    {$core->getBlock('top_staff')}
                </div>
			</div>
		</div>
	</section>
</div>
{$scriptJs}
<script type="text/javascript">
	$(function(){
		if($('.awe__post-description:not(.collapsed)').length){
			$('.awe__post-description:not(.collapsed)').each((_i, _elem) => {
				var _height = $(_elem).outerHeight();
				if(_height > 200){
					$(_elem).addClass('collapsed').append('<a class="awe__link-more">Xem thêm...</a>');
				}
			});
		}
	});
</script>
