var v = '2.3';
var dataCacheName = 'ponymanagerData-v'+v;
var cacheName = 'ponymanager-v'+v;
var filesToCache = [
];

self.addEventListener('install', function(e) {
	e.waitUntil(
		caches.open(cacheName).then(function(cache) {
			return cache.addAll(filesToCache);
		})
	);
});

self.addEventListener('activate', function(e) {
	e.waitUntil(
		caches.keys().then(
			function(keyList) {
				return Promise.all(
					keyList.map(
						function(key) {
							if (key !== cacheName && key !== dataCacheName) {
								return caches.delete(key);
							}
						}
					)
				);
			}
		)
	);
	return self.clients.claim();
});

self.addEventListener('fetch', function(ev) {
	ev.respondWith(
		caches.match(ev.request)
		.then(function(response) {
			// Cache hit - return response
			if (response) {
				return response;
			}
			return fetch(ev.request);
		}
	)
);
});
