<div class="container-fluid">
    <div class="page-title">
        <h2><i class="fa fa-wrench"></i> {$core->get_Lang('Settings')} &raquo; {$core->get_Lang('companyprofile')}</h2>
        <p>{$core->get_Lang('System setting')}</p>
    </div>
    <div class="clearfix"></div>
    <form method="post" action="" enctype="multipart/form-data" class="validate-form">
        <div class="cfg-grid">
            <div class="cfg-main">
				{if 1 eq 2}
                <section class="cfg-card">
                    <h3 class="cfg-card__title"><i class="fa fa-picture-o"></i> {$core->get_Lang('logo')}</h3>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Header Logo')} (Page In)</label>
                        <div class="cfg-row__control cfg-imgfield">
                            <img class="isoman_img_pop" id="isoman_show_image_Fx1" src="{$clsConfiguration->getValue('HeaderLogoPage')}" />
                            <input type="hidden" id="isoman_hidden_image_Fx1" value="{$clsConfiguration->getValue('HeaderLogoPage')}">
                            <input class="cfg-input" type="text" id="isoman_url_image_Fx1" name="iso-HeaderLogoPage" value="{$clsConfiguration->getValue('HeaderLogoPage')|escape}">
                            <a href="#" class="ajOpenDialog cfg-pick" isoman_for_id="image_Fx1" isoman_val="{$clsConfiguration->getValue('HeaderLogoPage')}" isoman_name="image"><img src="{$URL_IMAGES}/general/folder-32.png" border="0" title="Open" alt="Open"></a>
                        </div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Footer Logo')}</label>
                        <div class="cfg-row__control cfg-imgfield">
                            <img class="isoman_img_pop" id="isoman_show_image_Fx2" src="{$clsConfiguration->getValue('FooterLogo')}" />
                            <input type="hidden" id="isoman_hidden_image_Fx2" value="{$clsConfiguration->getValue('FooterLogo')}">
                            <input class="cfg-input" type="text" id="isoman_url_image_Fx2" name="iso-FooterLogo" value="{$clsConfiguration->getValue('FooterLogo')|escape}">
                            <a href="#" class="ajOpenDialog cfg-pick" isoman_for_id="image_Fx2" isoman_val="{$clsConfiguration->getValue('FooterLogo')}" isoman_name="image"><img src="{$URL_IMAGES}/general/folder-32.png" border="0" title="Open" alt="Open"></a>
                        </div>
                    </div>
                </section>
 				{/if}
                <section class="cfg-card">
                    <h3 class="cfg-card__title"><i class="fa fa-star"></i> Nhận diện thương hiệu</h3>
                    <div class="cfg-row">
                        <label class="cfg-row__label">Slogan</label>
                        <div class="cfg-row__control">
                            <input class="cfg-input" type="text" name="iso-slogan" value="{$clsConfiguration->getValue('slogan')|escape}">
                        </div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">Tên thương hiệu Check-in</label>
                        <div class="cfg-row__control">
                            <input class="cfg-input" type="text" name="iso-checkin_brand_name" placeholder="Vd: Future Way (để trống = mặc định)" value="{$clsConfiguration->getValue('checkin_brand_name')|escape}">
                        </div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">Màu thương hiệu</label>
                        <div class="cfg-row__control">
                            <input class="cfg-color" type="color" name="iso-BrandColor" value="{$clsConfiguration->getValue('BrandColor')|default:'#696cff'}">
                        </div>
                    </div>
                </section>

                <section class="cfg-card">
                    <h3 class="cfg-card__title"><i class="fa fa-building-o"></i> {$core->get_Lang('address')}</h3>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Full Company Name')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-CompanyName" value="{$clsConfiguration->getValue('CompanyName')|escape}"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Brief Company Name')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-CompanyNameBrief" value="{$clsConfiguration->getValue('CompanyNameBrief')|escape}"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Address 1 [map]')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" id="search_location" name="iso-CompanyAddress1" value="{$clsConfiguration->getValue('CompanyAddress1')|escape}"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Address 2')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-CompanyAddress" value="{$clsConfiguration->getValue('CompanyAddress')|escape}"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Company Phone')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-CompanyPhone" value="{$clsConfiguration->getValue('CompanyPhone')|escape}"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Company Fax')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-CompanyFax" value="{$clsConfiguration->getValue('CompanyFax')|escape}"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Company Hotline')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-CompanyHotline" value="{$clsConfiguration->getValue('CompanyHotline')|escape}"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Primary Email')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-CompanyEmail" value="{$clsConfiguration->getValue('CompanyEmail')|escape}"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Official Website')}</label>
                        <div class="cfg-row__control"><input class="cfg-input url" type="text" name="iso-CompanyWebsite" value="{$clsConfiguration->getValue('CompanyWebsite')|escape}"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Copyright')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-Copyright" value="{$clsConfiguration->getValue('Copyright')|escape}"></div>
                    </div>
                </section>

                <section class="cfg-card">
                    <h3 class="cfg-card__title"><i class="fa fa-share-alt"></i> {$core->get_Lang('social')}</h3>
                    {if 1 eq 2}
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Facebook Admin ID')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-SiteFacebookAdminID" value="{$clsConfiguration->getValue('SiteFacebookAdminID')|escape}"></div>
                    </div>
                    {/if}
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Facebook Link')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-SiteFacebookLink" value="{$clsConfiguration->getValue('SiteFacebookLink')|escape}"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Twitter Link')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-SiteTwitterLink" value="{$clsConfiguration->getValue('SiteTwitterLink')|escape}"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Google+ Link')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-SiteGoogleLink" value="{$clsConfiguration->getValue('SiteGoogleLink')|escape}"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Likedin Link')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-SiteLikedinLink" value="{$clsConfiguration->getValue('SiteLikedinLink')|escape}"></div>
                    </div>
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Printest Link')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-SitePrintestLink" value="{$clsConfiguration->getValue('SitePrintestLink')|escape}"></div>
                    </div>
                    {if 1 eq 2}
                    <div class="cfg-row">
                        <label class="cfg-row__label">{$core->get_Lang('Youtube Link')}</label>
                        <div class="cfg-row__control"><input class="cfg-input" type="text" name="iso-SiteYoutubeLink" value="{$clsConfiguration->getValue('SiteYoutubeLink')|escape}"></div>
                    </div>
                    {/if}
                </section>

            </div>
            <div class="cfg-side">
                <section class="cfg-card">
                    <h3 class="cfg-card__title"><i class="fa fa-map-marker"></i> {$core->get_Lang('location')}</h3>
                    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAGBM_QUAg8Oi-dI_Bopn6JVe4jrgVUcWw&libraries=places"></script>
                    <div style="width:100%; height:220px; border-radius:8px; overflow:hidden;" id="map_canvas"></div>
                    <input type="hidden" name="iso-CompanyMapLo" id="map_lo" value="{$clsConfiguration->getValue('CompanyMapLo')}" />
                    <input type="hidden" name="iso-CompanyMapLa" id="map_la" value="{$clsConfiguration->getValue('CompanyMapLa')}" />
                </section>
            </div>
        </div>
        <div class="cfg-footer">
            {$saveBtn}
            <input value="CompanyProfile" name="submit" type="hidden">
        </div>
    </form>
</div>
<script type="text/javascript">
    var $map_la = '{$clsConfiguration->getValue("CompanyMapLa")}';
    var $map_lo = '{$clsConfiguration->getValue("CompanyMapLo")}';
</script>
{literal}
<script type="text/javascript">
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
</script>
{/literal}
