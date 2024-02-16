// service-worker.js

self.addEventListener('install', (event) => {
    event.waitUntil(
      caches.open('tsboi').then((cache) => {
        return cache.addAll([
          '/',
          '/Contact-Us',
          '/manifest.json',
          '/public/logo.png'
          // Add other resources your app needs
        ]);
      })
    );
  });

  self.addEventListener('fetch', (event) => {
    event.respondWith(
      caches.match(event.request).then((response) => {
        return response || fetch(event.request);
      })
    );
  });
