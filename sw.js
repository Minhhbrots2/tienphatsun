importScripts("https://cdn.pushalert.co/sw-90378.js");
// Import Workbox từ CDN với phiên bản 6.4.1
importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.4.1/workbox-sw.js');
// Kiểm tra nếu workbox được load thành công
if (workbox) {
	// Bỏ qua chờ đợi và tự động kích hoạt service worker mới
    workbox.core.skipWaiting();
    workbox.core.clientsClaim();
    // Đặt tên cache
    workbox.core.setCacheNameDetails({
        prefix: 'FutureHomesCA-Cache',
        suffix: 'v2',
        precache: 'precache',
        runtime: 'runtime',
    });
	// Cache hình ảnh sử dụng chiến lược CacheFirst
	/* workbox.routing.registerRoute(
		({ url }) => url.origin === 'https://ca.futurehomes.vn' 
			&& url.pathname.startsWith('/application/themes/images/'),
		new workbox.strategies.CacheFirst({
			cacheName: 'FutureHomes-Images',
			plugins: [
				new workbox.cacheableResponse.CacheableResponsePlugin({
					statuses: [200], // Chỉ cache các phản hồi thành công
				}), new workbox.expiration.ExpirationPlugin({
					maxEntries: 200,              // Giới hạn 100 mục trong cache
					maxAgeSeconds: 24 * 60 * 60,  // Cache trong 1 ngày
				}),
			],
		})
	); */
	// Cache JS sử dụng chiến lược StaleWhileRevalidate
    workbox.routing.registerRoute(
        ({ url }) => url.origin === 'https://ca.futurehomes.vn' 
			&& url.pathname.startsWith('/application/themes/js/'),
        new workbox.strategies.StaleWhileRevalidate({
            cacheName: 'FutureHomesCA-Js',
            plugins: [
                new workbox.cacheableResponse.CacheableResponsePlugin({
                    statuses: [200], // Chỉ cache các phản hồi thành công
                }), new workbox.expiration.ExpirationPlugin({
                    maxEntries: 100,              // Giới hạn 100 mục trong cache
                    maxAgeSeconds: 24 * 60 * 60,  // Cache trong 1 ngày
                }),
            ],
        })
    );
	// Cache CSS sử dụng chiến lược CacheFirst
    workbox.routing.registerRoute(
        ({ url }) => url.origin === 'https://ca.futurehomes.vn' 
			&& url.pathname.startsWith('/application/themes/css/'), //&& !url.pathname.endsWith('tool.css')
        new workbox.strategies.CacheFirst({
            cacheName: 'FutureHomesCA-Css',
            plugins: [
                new workbox.cacheableResponse.CacheableResponsePlugin({
                    statuses: [200], // Chỉ cache các phản hồi thành công
                }), new workbox.expiration.ExpirationPlugin({
                    maxEntries: 100,              // Giới hạn 100 mục trong cache
                    maxAgeSeconds: 24 * 60 * 60,  // Cache trong 1 ngày
                }),
            ],
        })
    );
	workbox.routing.registerRoute(
        ({ url }) => url.origin === 'https://ca.futurehomes.vn' 
			&& url.pathname.startsWith('/application/themes/vendor/'),
        new workbox.strategies.StaleWhileRevalidate({
            cacheName: 'FutureHomesCA-Vendor-Js',
            plugins: [
                new workbox.cacheableResponse.CacheableResponsePlugin({
                    statuses: [200], // Chỉ cache các phản hồi thành công
                }), new workbox.expiration.ExpirationPlugin({
                    maxEntries: 100,              // Giới hạn 100 mục trong cache
                    maxAgeSeconds: 24 * 60 * 60,  // Cache trong 1 ngày
                }),
            ],
        })
    );
	// Precache các tài nguyên cần thiết (nếu có)
    workbox.precaching.precacheAndRoute([]);
    // Logging để kiểm tra các hoạt động (tuỳ chọn)
    self.addEventListener('fetch', (event) => {
        // console.log(`Fetching: ${event.request.url}`);
    });
	console.log('Workbox loaded successfully');
} else {
	console.log('Workbox failed to load');
}