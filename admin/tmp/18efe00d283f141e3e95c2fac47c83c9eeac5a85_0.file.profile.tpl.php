<?php
/* Smarty version 3.1.33, created on 2026-07-30 11:30:57
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/profile.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6ad38146eee6_76615744',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '18efe00d283f141e3e95c2fac47c83c9eeac5a85' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/profile.tpl',
      1 => 1785385855,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6ad38146eee6_76615744 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="container-fluid">
    <div class="page-title">
        <h2><i class="fa fa-wrench"></i> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Settings');?>
 &raquo; <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('companyprofile');?>
</h2>
        <p><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('System setting');?>
</p>
    </div>
    <div class="clearfix"></div>
    <form method="post" action="" enctype="multipart/form-data" class="validate-form">
        <div class="cfg-grid">
            <div class="cfg-main">
				<?php if (1 == 2) {?>
                <section class="cfg-card">
                    <h3 class="cfg-card__title"><i class="fa fa-picture-o"></i> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('logo');?>
</h3>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Header Logo');?>
 (Page In)</label>
                        <div class="cfg-row__control cfg-imgfield">
                            <img class="isoman_img_pop" id="isoman_show_image_Fx1" src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('HeaderLogoPage');?>
" />
                            <input type="hidden" id="isoman_hidden_image_Fx1" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('HeaderLogoPage');?>
">
                            <input class="cfg-input" type="text" id="isoman_url_image_Fx1" name="iso-HeaderLogoPage" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('HeaderLogoPage'), ENT_QUOTES, 'UTF-8', true);?>
">
                            <a href="#" class="ajOpenDialog cfg-pick" isoman_for_id="image_Fx1" isoman_val="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('HeaderLogoPage');?>
" isoman_name="image"><img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/general/folder-32.png" border="0" title="Open" alt="Open"></a>
                        </div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Footer Logo');?>
</label>
                        <div class="cfg-row__control cfg-imgfield">
                            <img class="isoman_img_pop" id="isoman_show_image_Fx2" src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('FooterLogo');?>
" />
                            <input type="hidden" id="isoman_hidden_image_Fx2" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('FooterLogo');?>
">
                            <input class="cfg-input" type="text" id="isoman_url_image_Fx2" name="iso-FooterLogo" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('FooterLogo'), ENT_QUOTES, 'UTF-8', true);?>
">
                            <a href="#" class="ajOpenDialog cfg-pick" isoman_for_id="image_Fx2" isoman_val="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('FooterLogo');?>
" isoman_name="image"><img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/general/folder-32.png" border="0" title="Open" alt="Open"></a>
                        </div>
                    </div>
                </section>
 				<?php }?>
                <section class="cfg-card">
                    <h3 class="cfg-card__title"><i class="fa fa-star"></i> Nhận diện thương hiệu</h3>
                    <div class="cfg-row">
                        <label class="cfg-row__label">Slogan</label>
                        <div class="cfg-row__control">
                            <input class="cfg-input" type="text" name="iso-slogan" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('slogan'), ENT_QUOTES, 'UTF-8', true);?>
">
                        </div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">Tên thương hiệu Check-in</label>
                        <div class="cfg-row__control">
                            <input class="cfg-input" type="text" name="iso-checkin_brand_name" placeholder="Vd: Future Way (để trống = mặc định)" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('checkin_brand_name'), ENT_QUOTES, 'UTF-8', true);?>
">
                        </div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">Màu thương hiệu</label>
                        <div class="cfg-row__control">
                            <input class="cfg-color" type="color" name="iso-BrandColor" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('BrandColor'))===null||$tmp==='' ? '#696cff' : $tmp);?>
">
                        </div>
                    </div>
                </section>

                <section class="cfg-card">
                    <h3 class="cfg-card__title"><i class="fa fa-building-o"></i> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('address');?>
</h3>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Full Company Name');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-CompanyName" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('CompanyName'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Brief Company Name');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-CompanyNameBrief" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('CompanyNameBrief'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Address 1 [map]');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" id="search_location" name="iso-CompanyAddress1" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('CompanyAddress1'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Address 2');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-CompanyAddress" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('CompanyAddress'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Company Phone');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-CompanyPhone" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('CompanyPhone'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Company Fax');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-CompanyFax" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('CompanyFax'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Company Hotline');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-CompanyHotline" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('CompanyHotline'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Primary Email');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-CompanyEmail" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('CompanyEmail'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Official Website');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input url" type="text" name="iso-CompanyWebsite" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('CompanyWebsite'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Copyright');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-Copyright" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('Copyright'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                </section>

                <section class="cfg-card">
                    <h3 class="cfg-card__title"><i class="fa fa-share-alt"></i> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('social');?>
</h3>
                    <?php if (1 == 2) {?>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Facebook Admin ID');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-SiteFacebookAdminID" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('SiteFacebookAdminID'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <?php }?>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Facebook Link');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-SiteFacebookLink" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('SiteFacebookLink'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Twitter Link');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-SiteTwitterLink" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('SiteTwitterLink'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Google+ Link');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-SiteGoogleLink" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('SiteGoogleLink'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Likedin Link');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-SiteLikedinLink" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('SiteLikedinLink'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Printest Link');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-SitePrintestLink" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('SitePrintestLink'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <?php if (1 == 2) {?>
                    <div class="cfg-row">
                        <label class="cfg-row__label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Youtube Link');?>
</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-SiteYoutubeLink" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('SiteYoutubeLink'), ENT_QUOTES, 'UTF-8', true);?>
"></div>
                    </div>
                    <?php }?>
                </section>

            </div>
            <div class="cfg-side">
                <section class="cfg-card">
                    <h3 class="cfg-card__title"><i class="fa fa-map-marker"></i> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('location');?>
</h3>
                    <?php echo '<script'; ?>
 type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAGBM_QUAg8Oi-dI_Bopn6JVe4jrgVUcWw&libraries=places"><?php echo '</script'; ?>
>
                    <div style="width:100%; height:220px; border-radius:8px; overflow:hidden;" id="map_canvas"></div>
                    <input type="hidden" name="iso-CompanyMapLo" id="map_lo" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('CompanyMapLo');?>
" />
                    <input type="hidden" name="iso-CompanyMapLa" id="map_la" value="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('CompanyMapLa');?>
" />
                </section>
            </div>
        </div>
        <div class="cfg-footer">
            <?php echo $_smarty_tpl->tpl_vars['saveBtn']->value;?>

            <input value="CompanyProfile" name="submit" type="hidden">
        </div>
    </form>
</div>
<?php echo '<script'; ?>
 type="text/javascript">
    var $map_la = '<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue("CompanyMapLa");?>
';
    var $map_lo = '<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue("CompanyMapLo");?>
';
<?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 type="text/javascript">
    var geocoder = new google.maps.Geocoder();
    var map;
    var marker;
    function $getID(id) {
        return document.getElementById(id);
    }
    function geocode(position) {
        geocoder.geocode({
            latLng: position
        }, function(responses) {
            $getID('search_location').value = responses[0].formatted_address;
            $getID('map_la').value = marker.getPosition().lat();
            $getID('map_lo').value = marker.getPosition().lng();
            map.panTo(marker.getPosition());
        });
    }
    function initialize() {
        $map_la = ($map_la != '') ? $map_la : '20.988668210459167';
        $map_lo = ($map_lo != '') ? $map_lo : '105.86727258378903';
        var mapOptions = {
            center: new google.maps.LatLng($map_la, $map_lo),
            zoom: 17,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
        var input = document.getElementById('search_location');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);
        var location = new google.maps.LatLng($map_la, $map_lo);
        marker = new google.maps.Marker({position: location});
        marker.setMap(map);
        marker.setDraggable(true);
        google.maps.event.addListener(marker, "dragend", function(event) {
            var point = marker.getPosition();
            map.panTo(point);
            geocode(point);
        });
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            geocode(place.geometry.location);
            marker.setPosition(place.geometry.location);
        });
    }
    function showAddress(address) {
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                marker.setPosition(results[0].geometry.location);
                geocode(results[0].geometry.location);
            } else {
                alert("Sorry but Google Maps could not find this location.");
            }
        });
    }
    $(document).ready(function() {
        initialize();
        $('#searchMap').click(function() {
            var address = $('#search_location').val();
            showAddress(address);
            return false;
        });
    });
<?php echo '</script'; ?>
>

<?php }
}
