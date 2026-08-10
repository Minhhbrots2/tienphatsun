jQuery.fn.vibrate = function (conf) {
    var config = jQuery.extend({
        speed:        30, 
        duration:    500,  
        spread:        3
    }, conf);

    return this.each(function () {
        var t = jQuery(this);

        var vibrate = function () {
			var w=$(window).width();
            var topPos    = Math.floor(Math.random() * config.spread) - ((config.spread - 1) / 2);
            var leftPos    = Math.floor(Math.random() * config.spread) - ((config.spread - 1) / 2);
            var rotate    = Math.floor(Math.random() * config.spread) - ((config.spread - 1) / 2);

            t.css({
                position:'absolute', 
                left:((w-350)/2)+leftPos + 'px', 
                top:topPos+250 + 'px', 
                WebkitTransform:'rotate(' + rotate + 'deg)',
				'z-index':1000  // cheers to erik@birdy.nu for the rotation-idea
            });
        };

        var doVibration = function () {
            var vibrationInterval = setInterval(vibrate, config.speed);

            var stopVibration = function () {
                clearInterval(vibrationInterval);
                t.css({
                    position:            'absolute', 
                    WebkitTransform:    'rotate(0deg)',
					'z-index':1000
                });
            };

            setTimeout(stopVibration, config.duration);
        };
        doVibration();
    });
};