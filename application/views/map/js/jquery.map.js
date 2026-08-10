
// Khởi tạo khi document ready
$(function() {
    $Core.projectMapView.init({
        locationInfos: $('#map').data('location'),
        mapZoom: $('#map').data('map-zoom')
    });
});