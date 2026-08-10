window.rzdApp = window.rzdApp || {};
rzdApp.FeatureDetector = function() {};
rzdApp.FeatureDetector.isSafari = (window.navigator.userAgent.indexOf('Safari') !== -1 
	&& window.navigator.userAgent.indexOf('Chrome') === -1 
	&& window.navigator.userAgent.indexOf('Firefox') === -1);
rzdApp.FeatureDetector.supportedFeatures = ['iframe-resize'];
rzdApp.FeatureDetector.detectFeature = function _detectFeature(feature) {
	var script = null,
		existingScript = null,
		scriptId = 'rzd-'+ feature;
	script = document.createElement('script');
	script.id = scriptId;
	script.defer = true;
	existingScript = document.querySelector('#' + scriptId);
	if (existingScript) {
		return true;
	}
	var rzdIframes =  [].filter.call(document.querySelectorAll('iframe'), function(iframe) {
		return iframe.className.indexOf('itourism') !== -1 || iframe.src.indexOf('itourism.vn') !== -1;
	});
	/* Check for features to activate */
	switch(feature) {
		case 'iframe-resize':
			script.innerText = "        var rzdResizeIframe = function _rzdResizeIframe(iframe) {"+
"          	 var rzdFindPos = function _findPos(obj) {"+
"            var curleft = 0;"+
"            var curtop = 0;"+
"            if (obj.offsetParent) {"+
"                do {"+
"                    curleft += obj.offsetLeft;"+
"                    curtop += obj.offsetTop;"+
"                } while (obj = obj.offsetParent);"+
"             }"+
"          	  return {x: curleft, y: curtop};"+
"          };"+
""+
"           try {"+
"                iFrameResize({"+
"                    checkOrigin: false,"+
"                    log: false,"+
"                    enablePublicMethods: false,"+
"                    heightCalculationMethod: 'lowestElement',"+
"                    messageCallback: function(data) {"+
"                        if (data.message.type === 'scrollToTop') {"+
"                            var getOffset = function _offset(elem) {"+
"                                if ( !elem.getClientRects().length ) {"+
"                                    return { top: 0, left: 0 };"+
"                                }"+
"                                var rect = elem.getBoundingClientRect();"+
"                                var win = elem.ownerDocument.defaultView;"+
"                                return {"+
"                                    top: rect.top + win.pageYOffset,"+
"                                    left: rect.left + win.pageXOffset"+
"                                };"+
"                            };"+
"                            window.scrollTo(0, getOffset(data.iframe).top);"+
"                        }"+
"                    },"+
"                    resizedCallback: function(messageData){"+
"                        var iframePosition = rzdFindPos(document.getElementById(messageData.id));"+
"                        if (typeof iframePosition !== 'undefined') {"+
"                            var iframeBottomPosition = iframePosition.y + parseFloat(messageData.height);"+
"                            if(window.pageYOffset > iframeBottomPosition) {"+
"                                window.scrollTo(0, iframePosition.y);"+
"                            }"+
"                        }"+
"                    },"+
"                }, iframe);"+
"            } catch (error) {"+
"            	rzdApp.hasError = true;"+
"              	console.error(error);"+
"            }"+
"        };"+
"        var iframeResizeHandler = function _iframeResizeHandler(event) {"+
"            if (event.target && event.target.matches('iframe.itourism') || event.target.src.indexOf('itourism.vn') !== -1) {"+
"            	rzdResizeIframe(event.target);"+
"            }"+
"        };"+
"        document.removeEventListener('load', iframeResizeHandler);"+
"        document.addEventListener('load', iframeResizeHandler);"+
"        if (!document.querySelector('#rzd-iframe-resizer')) {"+
"            var rzdIframeResizer = document.createElement('script');"+
"            rzdIframeResizer.id = 'rzd-iframe-resizer';"+
"            rzdIframeResizer.defer = true;"+
"            rzdIframeResizer.addEventListener('load',function() {"+
"                rzdApp.iFrameResize = function(){};"+
"                rzdApp.iFrameResize.prototype = window.iFrameResize.prototype;"+
"                [].forEach.call(document.querySelectorAll('iframe'), function(iframe) {"+
"                  if (iframe.src.indexOf('itourism.vn') !== -1 || iframe.className.indexOf('itourism') !== -1) {"+
"                    rzdResizeIframe(iframe);"+
"                    iframe.addEventListener('load', function(event) {"+
"                        if (!rzdApp.hasError) {"+
"                        	event.target.contentWindow.postMessage('rzd_plugin_js_loaded', '*');"+
"                        }"+
"                        event.target.contentWindow.postMessage('CUSTOMER_URL=' + window.location.href, '*');"+
"                    });"+
"                  }"+
"                });"+
"            });"+
"            rzdIframeResizer.src = 'https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.6.2/iframeResizer.min.js';"+
"            document.body.append(rzdIframeResizer);"+
"        }"+
"";
		break;
	}
	if (script.innerText.length) {
		document.body.append(script);
		return true;
	}
	return false;
};
rzdApp.FeatureDetector.init = function _init() {
	rzdApp.FeatureDetector.supportedFeatures.filter(function(feature) {
		return rzdApp.FeatureDetector.detectFeature(feature);
	});
};
//Dedicated endpoint to deal with async loaded booking button (monthly calendar)
rzdApp.healthCheck = function _healthCheck(event) {
 if (event.data && event.data === 'rzd_health_check' 
	&& !document.querySelector('.rzd-modal') 
	&& window.location.protocol === 'https:' 
	&& !rzdApp.hasError) {
		rzdApp.notifyIframes('rzd_plugin_js_loaded');
	}
};
rzdApp.initPluginJS = function _initPluginJs() {
	if (document.querySelector('#rzd-polyfill')) {
		return;
	}
	var polyfillScript = document.createElement('script');
	polyfillScript.id = 'rzd-polyfill';
	polyfillScript.defer = true;
	polyfillScript.addEventListener('load', function(){
		rzdApp.FeatureDetector.init();
	});
	polyfillScript.src = '';
	//BAD DOM
	if (document.readyState === 'complete' && !document.body) {
	var body = document.createElement("body");
		document.documentElement.appendChild(body);
	}
	document.body.appendChild(polyfillScript);
	window.addEventListener('message', rzdApp.healthCheck);
	window.addEventListener('message', function(event) {
		if (event && event.data && event.data === 'rzd_app_shutdown') {
			rzdApp.hasError = true;
		}
	});
	window.iFrameResize = rzdApp.iFrameResize;
	window.addEventListener('error', function() {
		rzdApp.hasError = true;
	});
	//hasError is reactive, it will trigger the shutdown has soon has it's set to true.
	var internalValue = false;
	Object.defineProperty(rzdApp, 'hasError', {
		configurable: true,
		enumerable: true,
		writeable: true,
		get: function() {
			return internalValue;
		},
		set: function (value) {
			internalValue = value;
			if (value) {
				rzdApp.shutdown();
			}
		}
	});
	rzdApp.hasError = false;
	//Disable the modal for safari (until we find a reliable way to check 3rd party cookies being allowed).
	if ( rzdApp.FeatureDetector.isSafari) {
		rzdApp.hasError = true;
	}
};
rzdApp.notifyIframes = function _notifyIframes(message) {
	var rzdIframes = document.querySelectorAll('iframe.itourism, iframe[src*="itourism.vn"]');
	if (!rzdIframes.length) { return; }
	[].forEach.call(rzdIframes, function(iframe) {
		iframe.contentWindow.postMessage(message, '*');
	});
};
//If something goes wrong, try to provide the feature
//Without using modal
rzdApp.shutdown = function _gracefullyFail(event) {
	rzdApp.notifyIframes({type: 'rzd_js_errors'});
	if (typeof rzdApp.healthCheck === 'function') {
	  window.removeEventListener('message', rzdApp.healthCheck);
	}
};
if (!document.querySelector('#rzd-polyfill')) {
	if (document.readyState === 'loading') {
		document.removeEventListener('DOMContentLoaded', rzdApp.initPluginJS);
		document.addEventListener('DOMContentLoaded', rzdApp.initPluginJS);
	} else {
		rzdApp.initPluginJS();
	}
}
// check if gtag() exists in data layer
function allocateDataLayer() {
	window.dataLayer = window.dataLayer || [];
	if(window.gtag) {
		return window.gtag;
	}
	return function gtag() {
		window.dataLayer.push(arguments);
	}
}
(function() {
	const itourismIFrames = document.querySelectorAll('iframe.itourism,iframe[src*="user.futurehomes.vn"]');
})();