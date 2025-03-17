/**
 * Service Worker for BUH Events Student Dashboard
 * @version 1.0
 */

// Tên cache
const CACHE_NAME = 'hub-events-v1';

// Tài nguyên cần cache
const CACHE_ASSETS = [
    '/',
    '/assets/css/bootstrap.min.css',
    '/assets/css/icons.css',
    '/assets/modules/layouts/student/css/header.css',
    '/assets/modules/layouts/student/css/sidebar.css',
    '/assets/js/jquery.min.js',
    '/assets/js/bootstrap.bundle.min.js',
    '/assets/plugins/nprogress/nprogress.js',
    '/assets/plugins/nprogress/nprogress.css',
    '/assets/modules/layouts/student/js/main.js',
    '/assets/images/logo-icon.png',
    '/assets/images/favicon-32x32.png',
    '/assets/images/apple-touch-icon.png',
    'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css'
];

// Cài đặt Service Worker
self.addEventListener('install', event => {
    // Caching tài nguyên
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(CACHE_ASSETS);
            })
            .then(() => self.skipWaiting())
    );
});

// Kích hoạt Service Worker
self.addEventListener('activate', event => {
    // Xóa các cache cũ
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
        .then(() => self.clients.claim())
    );
});

// Xử lý lấy tài nguyên
self.addEventListener('fetch', event => {
    // Bỏ qua các request không phải GET
    if (event.request.method !== 'GET') return;
    
    // Bỏ qua request API và request có chứa ?
    const url = new URL(event.request.url);
    if (url.pathname.startsWith('/api/') || url.search.length > 0) {
        return;
    }
    
    // Xử lý request
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // Trả về cache nếu có
                if (response) {
                    return response;
                }
                
                // Fetch từ mạng nếu không có trong cache
                return fetch(event.request).then(
                    networkResponse => {
                        // Nếu response không hợp lệ, trả về luôn
                        if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
                            return networkResponse;
                        }
                        
                        // Cache response mới
                        const responseToCache = networkResponse.clone();
                        
                        // Chỉ cache các tài nguyên tĩnh
                        if (event.request.url.match(/\.(js|css|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot)$/)) {
                            caches.open(CACHE_NAME)
                                .then(cache => {
                                    cache.put(event.request, responseToCache);
                                });
                        }
                        
                        return networkResponse;
                    }
                );
            })
            .catch(() => {
                // Trường hợp offline và request là HTML, trả về trang offline
                if (event.request.headers.get('accept').includes('text/html')) {
                    return caches.match('/offline.html');
                }
                
                // Trường hợp là ảnh, trả về ảnh placeholder
                if (event.request.headers.get('accept').includes('image')) {
                    return caches.match('/assets/images/offline-image.png');
                }
            })
    );
});

// Xử lý các message từ main thread
self.addEventListener('message', event => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

// Background sync cho form submissions offline
self.addEventListener('sync', event => {
    if (event.tag === 'form-submission') {
        event.waitUntil(
            // Lấy tất cả các form submissions chưa gửi từ IndexedDB
            // và gửi lại khi có mạng
            // Đây là phần nâng cao, sẽ được triển khai sau
            Promise.resolve()
        );
    }
});
