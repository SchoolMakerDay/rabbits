var cacheName = 'pwa-dashboard';
var filesToCache = [
'/rabbits/dashboard/index.html'
];
/*
var filesToCache = [
'/rabbits/dashboard/',
'/rabbits/dashboard/index.html',
'/rabbits/dashboard/css/stylesheet.css',
'/rabbits/dashboard/images/pwa-logo.svg',
'/rabbits/dashboard/js/core.js'
];
*/ 
/* Avvia il Service Worker e Memorizza il contenuto nella cache */
self.addEventListener('install', function(e) {
    e.waitUntil(
        caches.open(cacheName).then(function(cache) {
            return cache.addAll(filesToCache);
        })
    );
}); 
/* Serve i Contenuti Memorizzati quando sei Offline */
self.addEventListener('fetch', function(e) {
    e.respondWith(
        caches.match(e.request).then(function(response) {
            return response || fetch(e.request);
        })
    );
});